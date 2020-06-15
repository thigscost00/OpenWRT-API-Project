# - *- coding: utf- 8 - *-

import shutil
import os
import requests
import json
import subprocess
import tarfile
import zerotouch

cert = ('/root/client.crt', '/root/client.key')
cacert = '/root/ca.crt'
mac = '52:54:00:12:34:57'
config_path = '/root/config/'

response = requests.get(
    'https://192.168.1.2:8443/refresh',
    json={'mac': mac},
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

tar_name = "config.tar.gz"
tar_file = open(tar_name, "wb")
tar_file.write(response.content)
tar_file.close()

config = tarfile.open(tar_name)
config.extractall(config_path)

# TODO: Criar função para iterar entre os arquivos
# TODO: Definir padrão de nome para arquivos de configuração
for filename in os.listdir(config_path):
    filepath = config_path + filename
    if os.path.isfile(filepath) and "config" in filename:
        config_json = open(filepath, "r")
        config_json = json.load(config_json)
    elif os.path.isdir(filepath):
        config_json = open(filepath + "/config.json", "r")
        config_json = json.load(config_json)

    if "uci" in config_json:
        uci_json = config_json['uci']
        zt = zerotouch.ZeroTouch()
        zt.uciconfig(uci_json)
    if "cmd" in config_json:
        for cmd in config_json['cmd']:
            print(cmd)
            # subprocess.call(cmd.split())

shutil.rmtree(config_path)
os.remove(tar_name)
