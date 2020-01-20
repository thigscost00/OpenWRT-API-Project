import requests
import json
import subprocess

cert = ('/root/client.crt','/root/client.key')
cacert = '/root/ca.crt'


response = requests.post('https://192.168.1.2:8443/register', data={'mac':'52:54:00:12:34:57'}, cert=cert, verify=cacert)
token = json.loads(response.text)
token = token['access_token']

response = requests.get('https://192.168.1.2:8443/config', data={'token':token}, cert=cert, verify=cacert)
config = json.loads(response.text)

iface = ("config interface \'" + config['iface'] + "\'\n"
        "\toption ifname \'" + config['ifname'] + "\'\n"
        "\toption ipaddr \'" + config['ip'] + "\'\n"
        "\toption netmask \'" + config['mask'] + "\'\n"
        "\toption proto \'" + config['proto'] + "\'\n"
        )
with open('/etc/config/network', "a") as network:
    network.write(iface)

cmd = "/etc/init.d/network reload"
subprocess.call(cmd.split())
