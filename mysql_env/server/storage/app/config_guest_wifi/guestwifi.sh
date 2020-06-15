#! /bin/sh

NET_ID="guest"
FW_ZONE="guest"
uci batch << EOF
set network.${NET_ID}=interface
set network.${NET_ID}.proto=static
set network.${NET_ID}.ipaddr=192.168.3.1
set network.${NET_ID}.netmask=255.255.255.0
set dhcp.${NET_ID}=dhcp
set dhcp.${NET_ID}.interface=${NET_ID}
set dhcp.${NET_ID}.start=100
set dhcp.${NET_ID}.leasetime=12h
set dhcp.${NET_ID}.limit=150
set firewall.${FW_ZONE}=zone
set firewall.${FW_ZONE}.name=${FW_ZONE}
set firewall.${FW_ZONE}.network=${NET_ID}
set firewall.${FW_ZONE}.forward=REJECT
set firewall.${FW_ZONE}.output=ACCEPT
set firewall.${FW_ZONE}.input=REJECT
set firewall.${FW_ZONE}_fwd=forwarding
set firewall.${FW_ZONE}_fwd.src=${FW_ZONE}
set firewall.${FW_ZONE}_fwd.dest=wan
set firewall.${FW_ZONE}_dhcp=rule
set firewall.${FW_ZONE}_dhcp.name=${FW_ZONE}_DHCP
set firewall.${FW_ZONE}_dhcp.src=${FW_ZONE}
set firewall.${FW_ZONE}_dhcp.target=ACCEPT
set firewall.${FW_ZONE}_dhcp.proto=udp
set firewall.${FW_ZONE}_dhcp.dest_port=67-68
set firewall.${FW_ZONE}_dns=rule
set firewall.${FW_ZONE}_dns.name=${FW_ZONE}_DNS
set firewall.${FW_ZONE}_dns.src=${FW_ZONE}
set firewall.${FW_ZONE}_dns.target=ACCEPT
add_list firewall.${FW_ZONE}_dns.proto=tcp
add_list firewall.${FW_ZONE}_dns.proto=udp
set firewall.${FW_ZONE}_dns.dest_port=53
EOF

uci commit network
uci commit dhcp
uci commit firewall
service network reload
service dnsmasq restart
service firewall restart
