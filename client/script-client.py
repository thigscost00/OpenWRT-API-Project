import requests
import json
import subprocess
import zipfile
import StringIO
import zerotouch

cert = ('/root/client.crt', '/root/client.key')
cacert = '/root/ca.crt'

response = requests.get(
    'https://192.168.1.2:8443/refresh',
    json={'mac': '52:54:00:12:34:57'},
    cert=cert,
    verify=cacert
)

token = json.loads(response.text)
token = token['access_token']

file_token = open("token", "w")
file_token.write(token)
file_token.close()

response = requests.get(
    'https://192.168.1.2:8443/config',
    json={'token': token},
    cert=cert,
    verify=cacert,
    stream=True
)

z = zipfile.ZipFile(StringIO.StringIO(response.content))
z.extractall()

config_json = open("config2", "r")
config_json = json.load(config_json)

if "uci" in config_json:
    uci_json = config_json['uci']
    zt = zerotouch.ZeroTouch()
    zt.uciconfig(uci_json)
if "cmd" in config_json:
    for cmd in config_json['cmd']:
        print(cmd)
        # subprocess.call(cmd.split())
