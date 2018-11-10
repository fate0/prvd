#! /usr/bin/env python
# -*- coding: utf-8 -*-

import gevent.monkey
gevent.monkey.patch_all()


import json
import zlib
import copy
import base64
import logging
import requests
import gevent.queue
import urllib.parse
from flask import Flask, request
from gevent.pywsgi import WSGIServer


logger = logging.getLogger(__name__)
logging.basicConfig(level=logging.INFO)
app = Flask(__name__)
task_queue = gevent.queue.Queue()


def handle_task_queue():
    while True:
        task = task_queue.get()

        method = task['method']
        url = task['url']
        headers = task['headers']
        body = task['body']
        files = task['files']

        header_string = '\n'.join([key + ':' + value for key, value in headers.items()])
        logger.debug("sending request\n{} {}\n{}\n\n{}\n{}\n".format(method, url, header_string, body, files))
        try:
            requests.request(method=method, url=url, headers=headers, data=body, files=files,
                             allow_redirects=False, timeout=5)
        except:
            pass


class Fuzzer(object):
    payloads = ['\'\"><xtanzi>./../xtanzi']

    def __init__(self, _request):
        self.request = _request

    def start(self):
        logger.debug('origin request: \n{}'.format(json.dumps(self.request, indent=4)))

        if 'query' in self.request and self.request['query']:
            self.fuzz_query()

        if 'data' in self.request and self.request['data']:
            self.fuzz_body()

        if 'files' in self.request and self.request['files']:
            self.fuzz_files()

        if 'cookies' in self.request and self.request['cookies']:
            self.fuzz_cookies()

        if 'headers' in self.request and self.request['headers']:
            self.fuzz_headers()

    def fuzz_query(self):
        query = copy.deepcopy(self.request['query'])
        fuzz_queries = self.fuzz_value(query)

        for each_fuzz_query in fuzz_queries:
            fuzz_request = copy.deepcopy(self.request)
            fuzz_request['query'] = each_fuzz_query
            self.make_request(fuzz_request, 'query')

    def fuzz_body(self):
        body = copy.deepcopy(self.request['data'])
        fuzz_queries = self.fuzz_value(body)

        for each_fuzz_query in fuzz_queries:
            fuzz_request = copy.deepcopy(self.request)
            fuzz_request['data'] = each_fuzz_query
            self.make_request(fuzz_request, 'body')

    def fuzz_cookies(self):
        cookie = copy.deepcopy(self.request['cookies'])
        fuzz_queries = self.fuzz_value(cookie)

        for each_fuzz_query in fuzz_queries:
            fuzz_request = copy.deepcopy(self.request)
            fuzz_request['cookies'] = each_fuzz_query
            self.make_request(fuzz_request, 'cookies')

    def fuzz_files(self):
        # 暂时不支持 array
        files = copy.deepcopy(self.request['files'])
        for key in files:
            file_info = files[key]
            # fuzz 文件名
            for each_fuzz_data in self.add_value(file_info['name']):
                fuzz_request = copy.deepcopy(self.request)
                fuzz_request['files'][key]['name'] = each_fuzz_data
                self.make_request(fuzz_request)

            # fuzz 文件内容
            for each_fuzz_data in self.add_value(''):
                fuzz_request = copy.deepcopy(self.request)
                fuzz_request['files'][key]['data'] = each_fuzz_data
                self.make_request(fuzz_request, 'body')

    def fuzz_headers(self):
        headers = copy.deepcopy(self.request['headers'])
        fuzz_headers = self.fuzz_value(headers)

        for each_fuzz_header in fuzz_headers:
            fuzz_request = copy.deepcopy(self.request)
            fuzz_request['headers'] = each_fuzz_header
            self.make_request(fuzz_request, 'headers')

    @staticmethod
    def make_request(req, fuzz_origin=None):
        method = req['method']
        headers = req['headers']
        content_type = headers.get('Content-Type', '').lower()
        body = None
        files = {}

        if fuzz_origin == 'query' and 'query' in req and req['query']:
            urlspliter = urllib.parse.urlsplit(req['url'])
            query_string = Fuzzer.json_to_php_array_string(req['query'])
            url = urllib.parse.urlunsplit((urlspliter.scheme, urlspliter.netloc,
                                           urlspliter.path, query_string, urlspliter.fragment))
        else:
            url = req['url']

        if fuzz_origin == 'body' and 'data' in req and req['data']:
            if 'application/x-www-form-urlencoded' in content_type:
                body = Fuzzer.json_to_php_array_string(req['data'])
            elif 'application/json' in content_type:
                body = json.dumps(req['data'])
            elif 'multipart/form-data' in content_type:
                body = {}
                for key, value in urllib.parse.parse_qsl(Fuzzer.json_to_php_array_string(req['data'])):
                    body[key] = (None, value)

                req['headers'].pop('Content-Type')
        else:
            body = req.get('data')

        # 'multipart/form-data' 情况下，PHP 不支持获取原始 POST 数据，需要将 file 信息也设置好
        if 'multipart/form-data' in content_type:
            if 'files' in req and req['files']:
                for key in req['files']:
                    files[key] = (req['files'][key]['name'], req['files'][key].get('data', '!PNG Hello'))

        if fuzz_origin == 'cookies' and 'cookies' in req and req['cookies']:
            cookie_string = Fuzzer.json_to_php_array_string(req['cookies'])
            cookie_string = cookie_string.replace('&', ';')
            headers['Cookie'] = cookie_string

        headers['prvd-fuzzer'] = 'halo_from_fate0'
        if 'Content-Length' in headers:
            headers.pop('Content-Length')

        task_queue.put({
            'method': method,
            'url': url,
            'headers': headers,
            'body': body,
            'files': files
        })

    @staticmethod
    def add_value(value):
        result = []
        for i in Fuzzer.payloads:
            value = value or ''
            result.append(value + i)

        return result

    @staticmethod
    def fuzz_value(data):
        """
        >>> Fuzzer.fuzz_value({"a": "123"})
        [{'a': '123\\'"'}, {'a': '123"'}, {'a': "123'"}, {'a': '123%25%2b'}, {'a': '123\\'"><xtanzi>./../xtanzi'}]
        >>> Fuzzer.fuzz_value({'a': {"d": "x"}})
        [{'a': {'d': 'x\\'"'}}, {'a': {'d': 'x"'}}, {'a': {'d': "x'"}}, {'a': {'d': 'x%25%2b'}}, {'a': {'d': 'x\\'"><xtanzi>./../xtanzi'}}]
        >>> Fuzzer.fuzz_value({'a': ['x', 'y']})
        [{'a': ['x\\'"', 'y']}, {'a': ['x"', 'y']}, {'a': ["x'", 'y']}, {'a': ['x%25%2b', 'y']}, {'a': ['x\\'"><xtanzi>./../xtanzi', 'y']}, {'a': ['x', 'y\\'"']}, {'a': ['x', 'y"']}, {'a': ['x', "y'"]}, {'a': ['x', 'y%25%2b']}, {'a': ['x', 'y\\'"><xtanzi>./../xtanzi']}]
        """
        reqs = []

        def _fuzz_value(value):
            if isinstance(value, (dict, list)):
                items = value.items() if isinstance(value, dict) else enumerate(value)
                for each_key, each_value in items:
                    new_values = _fuzz_value(each_value)

                    if not new_values:
                        continue

                    for each_new_value in new_values:
                        value[each_key] = each_new_value
                        reqs.append(copy.deepcopy(data))
                        value[each_key] = each_value  # 还原

            elif isinstance(value, str):
                return Fuzzer.add_value(value)

        _fuzz_value(data)
        return reqs

    @staticmethod
    def json_to_php_array_string(data):
        """
        >>> Fuzzer.json_to_php_array_string({"key1": "value1", 'key2': 'value2'})
        'key1=value1&key2=value2'
        >>> Fuzzer.json_to_php_array_string({"key1": {"key11": "value11"}, "key2": "value2"})
        'key1[key11]=value11&key2=value2'
        >>> Fuzzer.json_to_php_array_string({"key1": ["value1", "value12", "value13"]})
        'key1[]=value1&key1[]=value12&key1[]=value13'
        >>> Fuzzer.json_to_php_array_string({"key1": {"key11": ['value11', 'value12']}})
        'key1[key11][]=value11&key1[key11][]=value12'
        """

        def to_string(value):
            if isinstance(value, dict):
                d = []
                for each_key, each_value in value.items():
                    result = to_string(each_value)

                    if isinstance(result, list):
                        for i in result:
                            d.append('[%s]%s' % (each_key, i))
                    else:
                        d.append('[%s]%s' % (each_key, result))

                return d

            elif isinstance(value, list):
                d = []
                for each_value in value:
                    result = to_string(each_value)

                    if isinstance(result, list):
                        for i in result:
                            d.append('[]%s' % i)
                    else:
                        d.append('[]%s' % result)

                return d

            elif isinstance(value, str):
                return '=' + urllib.parse.quote(value)

        d = []
        for i in data:
            result = to_string(data[i])
            if isinstance(result, list):
                for j in result:
                    d.append(i + j)
            else:
                d.append(i + result)

        return '&'.join(d)

    @staticmethod
    def fuzz(_request):
        f = Fuzzer(_request)
        f.start()


@app.route('/fuzz', methods=['POST'])
def index():
    if 'X-Sentry-Auth' not in request.headers:
        return "forbidden"

    h = request.headers['X-Sentry-Auth']
    sentry_info = {}
    for i in h.split(','):
        key, value = i.split('=')
        sentry_info[key.strip()] = value.strip()

    if sentry_info['sentry_key'] != 'admin' or sentry_info['sentry_secret'] != 'password':
        return "access deny"

    if not request.data:
        return "require data"

    data = base64.b64decode(request.data)
    try:
        data = zlib.decompress(data)
    except:
        pass
    data = json.loads(data)

    Fuzzer.fuzz(data['request'])
    return 'hello'


if __name__ == '__main__':
    gevent.spawn(handle_task_queue)
    http_server = WSGIServer(('0.0.0.0', 9090), app)
    http_server.serve_forever()
