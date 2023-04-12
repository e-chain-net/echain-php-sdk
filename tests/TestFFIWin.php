<?php

$libFisco = FFI::cdef(<<<CTYPE
void* bcos_sdk_create_keypair(int crypto_type);
void bcos_sdk_destroy_keypair(void* key_pair);
void bcos_sdk_create_signed_transaction(void* key_pair, const char* group_id, const char* chain_id, const char* to, const char* data, const char* abi, int64_t block_limit, int32_t attribute, char** tx_hash, char** signed_tx);
const char* bcos_sdk_get_keypair_address(void* key_pair);
void* bcos_sdk_create_keypair_by_hex_private_key(int crypto_type, const char* private_key);
const char* bcos_sdk_get_keypair_private_key(void* key_pair);
void* bcos_sdk_create_keypair_by_hex_private_key(int crypto_type, const char* private_key);
void bcos_sdk_destroy_keypair(void* key_pair);
void bcos_sdk_c_free(void* p);
CTYPE
 , "bcos-c-sdk.dll"
 );

 //生成随机地址
 $keypair = $libFisco->bcos_sdk_create_keypair(0);
 $address = $libFisco->bcos_sdk_get_keypair_address($keypair);
 $privatekey = $libFisco->bcos_sdk_get_keypair_private_key($keypair);
 $libFisco->bcos_sdk_destroy_keypair($keypair);
 echo "random address:" . $address . "\n";
 echo "private key:" . $privatekey . "\n\n";
//  $libFisco->bcos_sdk_c_free($address);
//  $libFisco->bcos_sdk_c_free($privatekey);

 //用固定私钥
 $privateKeyHex = "89eee53f348d1f926c4bc3f0e95981cd694f1b2b75a0df4295d88f38f74c54fc";
 $keypair = $libFisco->bcos_sdk_create_keypair_by_hex_private_key(0,$privateKeyHex);

 //签名交易
 $groupid = "group0";
 $chainid = "chain0";
 $toAddress = "0x0c7ebf03fe7a61921ea4c93393c364859f3c2a3e";
 $data = "0x23b872dd000000000000000000000000ae1bd933fe320d6f926513d159f39b38614973660000000000000000000000009847b8f7bf06fa6687f38475ab621c188689d11e00000000000000000000000000000000000000000000000000000000000003e8";
 $abi = "";
 $blockLimit = 27200;

 $tx_hash = FFI::new("char*");
 $signed = FFI::new("char*");
 $libFisco->bcos_sdk_create_signed_transaction($keypair,$groupid,$chainid,$toAddress,$data,$abi,$blockLimit,0,FFI::addr($tx_hash),FFI::addr($signed));
 $libFisco->bcos_sdk_destroy_keypair($keypair);
 echo "tx-hash:" . FFI::string($tx_hash) . "\n";
 echo "signed:" . FFI::string($signed);
//  FFI::free($tx_hash);
//  FFI::free($signed);
//  $libFisco->bcos_sdk_c_free($address);
//  $libFisco->bcos_sdk_c_free($privatekey);
