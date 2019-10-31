#! /bin/bash
IMAGE="openwrt-15.05-malta-le-vmlinux-initramfs.elf"
LAN="ledetap0"

sysctl -w net.ipv4.ip_forward=1
iptables -t nat -A POSTROUTING -s 192.168.1.0/24 ! -d 192.168.1.0/24 -j MASQUERADE
brctl addbr br0
ifconfig br0 192.168.1.2
#tunctl -t $LAN
ip tuntap add dev $LAN mode tap user $(whoami)
brctl addif br0 $LAN
ifconfig $LAN up

#    -device pcnet,netdev=lan \
#    -netdev tap,id=lan,ifname=$LAN,script=no,downscript=no
qemu-system-mipsel \
    -device pcnet,netdev=wan \
    -netdev user,id=wan \
    -device pcnet,netdev=lan \
    -netdev tap,id=lan,ifname=$LAN,script=no,downscript=no \
    -nographic -m 256 -kernel $IMAGE

ifconfig $LAN down
brctl delif br0 $LAN
#tunctl -d $LAN
ip tuntap del $LAN mode tap
ifconfig br0 down
brctl delbr br0
iptables -t nat -D POSTROUTING -s 192.168.1.0/24 ! -d 192.168.1.0/24 -j MASQUERADE
sysctl -w net.ipv4.ip_forward=0
