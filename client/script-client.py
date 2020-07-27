# - *- coding: utf- 8 - *-

from zerotouch import ZeroTouch

cert = ('/root/client.crt', '/root/client.key')
cacert = '/root/ca.crt'
mac = '52:54:00:12:34:57'
config_path = '/root/config/'
token_path = '/root/token'
tar_name = 'config.tar.gz'

zt = ZeroTouch(cert, cacert, mac, token_path, config_path)
zt.gettoken()
zt.getconfig(tar_name)
zt.execonfig()
