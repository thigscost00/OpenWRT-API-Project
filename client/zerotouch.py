from os import path
from datetime import datetime, timedelta
from pytz import timezone
import pytz
import os
import requests
import json
import subprocess
import tarfile
import shutil


class ZeroTouch(object):
    """docstring for ZeroTouch."""

    def __init__(self, cert, cacert, mac, tokenpath, configpath):
        self._cert = cert
        self._cacert = cacert
        self._mac = mac
        self._tokenpath = tokenpath
        self._configpath = configpath

    def gettoken(self):
        brtz = timezone('America/Sao_Paulo')
        current_time = datetime.now(tz=pytz.utc).astimezone(brtz)

        if path.isfile(self._tokenpath):
            with open(self._tokenpath) as f:
                token = json.load(f)

            expires = datetime.strptime(
                token['expires_at'],
                '%Y-%m-%d, %H:%M:%S'
            )
            expires = brtz.localize(expires)

            if current_time < expires:
                self._token = token['access_token']
                return

        response = requests.get(
            'https://192.168.1.2:8443/refresh',
            json={'mac': self._mac},
            cert=self._cert,
            verify=self._cacert
        )

        token = json.loads(response.text)
        seconds = token['expires_in']
        expires = current_time + timedelta(seconds=seconds)
        token['expires_at'] = expires.strftime('%Y-%m-%d, %H:%M:%S')

        file_token = open(self._tokenpath, "w")
        json.dump(token, file_token)
        file_token.close()

        self._token = token['access_token']

    def getconfig(self, tar_name):
        response = requests.get(
            'https://192.168.1.2:8443/config',
            json={'token': self._token},
            cert=self._cert,
            verify=self._cacert,
            stream=True
        )

        tar_file = open(tar_name, "wb")
        tar_file.write(response.content)
        tar_file.close()

        config = tarfile.open(tar_name)
        config.extractall(self._configpath)

        os.remove(tar_name)

    def execonfig(self):
        for filename in os.listdir(self._configpath):
            filepath = self._configpath + filename
            if path.isfile(filepath) and "config" in filename:
                config_json = open(filepath, "r")
                config_json = json.load(config_json)
            elif path.isdir(filepath):
                config_json = open(filepath + "/config.json", "r")
                config_json = json.load(config_json)

            if "uci" in config_json:
                uci_json = config_json['uci']
                self.uciconfig(uci_json)
            if "cmd" in config_json:
                for cmd in config_json['cmd']:
                    print(cmd)
                    # subprocess.call(cmd.split())

        # shutil.rmtree(self._configpath)

    def uciconfig(self, uci_json):
        for config in uci_json:
            for section in uci_json[config]:
                section_name = section['name']
                section_type = section['type']

                cmd = "uci set {}.{}={}".format(
                    config, section_name, section_type
                )
                print(cmd)
                # subprocess.call(cmd.split())

                for uci_cmd in section['uci_cmd']:
                    for option in section['uci_cmd'][uci_cmd]:
                        if uci_cmd == "add_list":
                            for val in section['uci_cmd'][uci_cmd][option]:
                                cmd = "uci {} {}.{}.{}={}".format(
                                    uci_cmd, config, section_name, option, val
                                )
                                print(cmd)
                                # subprocess.call(cmd.split())
                        else:
                            value = section['uci_cmd'][uci_cmd][option]

                            cmd = "uci {} {}.{}.{}={}".format(
                                uci_cmd, config, section_name, option, value
                            )
                            print(cmd)
                            # subprocess.call(cmd.split())

        cmd = "uci commit"
        # subprocess.call(cmd.split())
