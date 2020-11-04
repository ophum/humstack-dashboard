<?php

namespace App\Utils\HumClient\System\VirtualMachine;

use App\Utils\HumClient\Meta\Meta;

/*
meta:
  apiType: systemv0/virtualmachine
  id: vm1
  name: virtualmachine1
  group: group1
  namespace: ns1
  annotations:
    virtualmachinev0/node_name: worker1
spec:

  requestVcpus: 1000m
  limitVcpus: 1000m
  requestMemory: 1G
  limitMemory: 1G
  # BlockStorageのIDの配列
  blockStorageIDs:
    - bs1
  # 接続するネットワークの配列
  nics:
    - networkID: net1
      # cloudinitで設定するIPアドレス
      ipv4Address: 10.0.0.1
      # cloudinitで設定するネームサーバー
      nameservers:
        - 8.8.8.8
      # cloudinitで設定するデフォルトゲートウェイ
      defaultGateway: 10.0.0.254
  # VMを起動
  actionState: PowerOn
  # cloudinitで設定するユーザーの配列
  loginUsers:
    - username: test
      sshAuthorizedKeys:
        - ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABgQCsf7CDppU1lSzUbsmszAXX/rAXdGxB71i93IsZtV4omO/uRz/z6dLIsBidf9vIqcEfCFTFR00ULC+GKULTNz2LOaGnGsDS28Bi5u+cx90+BCAzEg6cBwPIYmdZgASsjMmRvI/r+xR/gNxq2RCR8Gl8y5voAWoU8aezRUxf1Ra3KljMd1dbIFGJxgzNiwqN3yL0tr9zActw/Q7yBWKWi1c5sW2QZLAnSj/WWTSGGm0Ad88Aq22DakwN6itUkS6XNhr4YKehLVm90fIojrCrtZmClULAlnUk5lbdzou4jiETsZz3zk/q76ZQ3ugk+G00kcx9v6ElLkAFv2ZZqzWbMvUz6J0k2SzkAIbcBDz+aq2sXeY04FaIOFPiH41+DTQXCtOskWkaJBMKLTE/Z83nSyQGr9If2F/PbnuxGkwiZzeZaLWxqI2SebhLR5jPETgfhB1y83RP6u8Jq5+9BUURFqpb8mfG/riTnAj0ZR4Li23+/hWhc8We+fVB1BxdbWyRn/M=
*/

class VirtualMachineSpec
{
    public $requestVcpus = "";
    public $requestMemory = "";
    public $limitVcpus = "";
    public $limitMemory = "";
    public $blockStorageIDs = [];
    public $nics = [];
    public $actionState = "";
    public $loginUsers = [];
}
