# echain-php-sdk
EChain fisco-bcos php signing sdk

# Attention
1. Must use PHP with version >= 7.4 and enable **ffi** feature.
2. Dynamic library:
- Linux: put [libbcos-c-sdk.so](https://github.com/FISCO-BCOS/bcos-c-sdk/releases/download/v3.2.0/libbcos-c-sdk.so) to directory ``/usr/lib64`` before running.
- Windows: put [bcos-c-sdk.dll](https://github.com/FISCO-BCOS/bcos-c-sdk/releases/download/v3.2.0/bcos-c-sdk.dll) to directory ``C:\Windows\System32``
- MacOS:
    - M1: download [libbcos-c-sdk-aarch64.dylib](https://github.com/FISCO-BCOS/bcos-c-sdk/releases/download/v3.2.0/libbcos-c-sdk-aarch64.dylib) and rename to ``libbcos-c-sdk.dylib`` and put to ``/usr/local/lib``
    - x64: download [libbcos-c-sdk.dylib](https://github.com/FISCO-BCOS/bcos-c-sdk/releases/download/v3.2.0/libbcos-c-sdk.dylib) and put to directory ``/usr/local/lib``

# Usage
## 1. Add reference in ``composer.json``
```
"require": {
    "echain/fisco-php":"^1.3"
}
```
## 2. Call ``SignSDK`` in code
```
<?php

namespace EChainDemo;

require_once __DIR__ . '/../vendor/autoload.php';

use EChain\SignSDK;

//SignSDK can only be used as singleton
$sdk = SignSDK::getInstance();

$private = "9f49267bed433fa1f298aedd81ba4bb3f73622f94b40e6e50d1190f25cca0b27";
$contractAddress = "0x0c7ebf03fe7a61921ea4c93393c364859f3c2a3e";
$tokenId = 1000;

function testAccount(){
    global $sdk;
    $account = $sdk->generateAccount();
    echo "address:" . $account->address . "\n";
    echo "private:" . $account->private . "\n\n";
}

function testSignMint(){
    $toAddress = "0x0c7ebf03fe7a61921ea4c93393c364859f3c2a3e";
    $blockNumber = 100000;
    global $sdk,$private,$contractAddress,$tokenId;
    $signRes = $sdk->signMint($toAddress,$tokenId,$contractAddress,$private,$blockNumber);
    echo "Mint txHash:" . $signRes->txHash . "\n";
    echo "Mint signed:" . $signRes->signed . "\n\n";
}

function testSignTransfer(){
    $fromAddress = "0xcDFC7406BeacF91ED425eade994CD0839d3FA9fD";
    $toAddress = "0x0c7ebf03fe7a61921ea4c93393c364859f3c2a3e";
    $blockNumber = 100000;
    global $sdk,$private,$contractAddress,$tokenId;
    $signRes = $sdk->signTransferFrom($fromAddress,$toAddress,$tokenId,$contractAddress,$private,$blockNumber);
    echo "Transfer txHash:" . $signRes->txHash . "\n";
    echo "Transfer signed:" . $signRes->signed . "\n\n";
}

function testSignBurn(){
    $blockNumber = 100000;
    global $sdk,$private,$contractAddress,$tokenId;
    $signRes = $sdk->signBurn($tokenId,$contractAddress,$private,$blockNumber);
    echo "Burn txHash:" . $signRes->txHash . "\n";
    echo "Burn signed:" . $signRes->signed . "\n\n";
}

testAccount();
testSignMint();
testSignTransfer();
testSignBurn();
```
返回示例：
```
address:0xef27d6f42098272a9674d7e529a8419944c29015
private:12edc828cc88a8912ef8cffcecde81ab83fbaa2e4ab82194ea943045d5c3a5c7

Mint txHash:0x06fd242e0957af8ab609677ac648ddc4dfdf3a9afea4eee1f2af22654f5d25ac
Mint signed:0x1a1c2606636861696e30360667726f7570304200018a87564d3138333537333737343837343632333633383339393836343535373336313934393633333837363137313835363239373537373530353836383030343235353633363332343337383737303632662a3078306337656266303366653761363139323165613463393333393363333634383539663363326133657d00004440c10f190000000000000000000000000c7ebf03fe7a61921ea4c93393c364859f3c2a3e00000000000000000000000000000000000000000000000000000000000003e80b2d00002006fd242e0957af8ab609677ac648ddc4dfdf3a9afea4eee1f2af22654f5d25ac3d000041382434748fc1beadd7d57e5170aa526325db7618adf2df9deca6207ed5e294dc77e065b4673c2fd74cc8f39b962def9475eb4746818bd2ab58ed80fb5c54782e01

Transfer txHash:0x380cd5bc1337c0857d26b00d4214e9529cbac4ba3865704e2eed205f5fea1547
Transfer signed:0x1a1c2606636861696e30360667726f7570304200018a87564d3530393136303934373035393634363936343437353638383036393534373234363934343439303136393939373633323936373336323231303734313630303537383230313931393835333630662a3078306337656266303366653761363139323165613463393333393363333634383539663363326133657d00006423b872dd000000000000000000000000cdfc7406beacf91ed425eade994cd0839d3fa9fd0000000000000000000000000c7ebf03fe7a61921ea4c93393c364859f3c2a3e00000000000000000000000000000000000000000000000000000000000003e80b2d000020380cd5bc1337c0857d26b00d4214e9529cbac4ba3865704e2eed205f5fea15473d000041300f7d7afdb3b935fe99b687067386b1ec26e9cd1e62ded62074faf149497ede2c048cce88bbb1ca9e23df6fb8483755836b1f19404c99c547ad7a000146ee7701

Burn txHash:0x89ae25340ad3d4ba766555af1cbf5ae58504a3d9c6ed0e2f31e8018d097d79da
Burn signed:0x1a1c2606636861696e30360667726f7570304200018a87564d3236363638373232303237363936353232313432393038373632363135303633373433383039363833323530303438363036313634373438313533353130373536323836353133383033363333662a3078306337656266303366653761363139323165613463393333393363333634383539663363326133657d00002442966c6800000000000000000000000000000000000000000000000000000000000003e80b2d00002089ae25340ad3d4ba766555af1cbf5ae58504a3d9c6ed0e2f31e8018d097d79da3d000041593de7649f8fad6887dbd032bacc5b17a7b273e8b6879162ef8e74e5dea87a773057dfc85286c9fbd79a6fa8f9dfd997a93095bb97f708fbd60f62da5a4197d100
```