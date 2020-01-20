#! /bin/bash
IMAGE="openwrt-18.06.4-x86-generic-combined-ext4.img"
#IMAGE="overlay01.qcow2"
#IMAGE="snapshot.qcow2"
LAN="ledetap0"

brctl addbr br0
ifconfig br0 192.168.1.2
sudo ip route add 192.168.1.0/24 dev br0
ip tuntap add dev $LAN mode tap user $(whoami)
brctl addif br0 $LAN
ifconfig $LAN up

sysctl -w net.ipv4.ip_forward=1
sudo iptables -t nat -A POSTROUTING -s 192.168.1.0/24 ! -d 192.168.1.0/24 -j MASQUERADE
sudo iptables -A FORWARD -i br0 -o enp0s31f6 -j ACCEPT
sudo iptables -A FORWARD -m conntrack --ctstate RELATED,ESTABLISHED -j ACCEPT


qemu-system-x86_64 \
    -device e1000,netdev=lan \
    -netdev tap,id=lan,ifname=$LAN,script=no,downscript=no \
    -device e1000,netdev=wan \
    -netdev tap,id=wan \
    -nographic -m 256 $IMAGE


sudo iptables -t nat -D POSTROUTING -s 192.168.1.0/24 ! -d 192.168.1.0/24 -j MASQUERADE
sudo iptables -D FORWARD -i br0 -o enp0s31f6 -j ACCEPT
sudo iptables -D FORWARD -m conntrack --ctstate RELATED,ESTABLISHED -j ACCEPT
sysctl -w net.ipv4.ip_forward=0

sudo ip route del 192.168.1.0/24 dev br0
brctl delif br0 $LAN
ifconfig $LAN down
ip tuntap del $LAN mode tap
ifconfig br0 down
brctl delbr br0
