class ZeroTouch(object):
    """docstring for ZeroTouch."""

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
