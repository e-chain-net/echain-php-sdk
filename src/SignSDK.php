<?php
/**
 * author: luleigreat
 * datetime: 2023/4/11 17:52
 */

namespace EChain;

use EChain\Formatter;
use EChain\Account;
use FFI;
use Exception;

class SignSDK{
    private $libFisco;

    private $groupid;
    private $chainid;
    private $abi;

    private static $instance = null;
 
    // 禁止被实例化
    private function __construct()
    {
    }
 
    // 禁止clone
    private function __clone()
    {
    }

    public static function getInstance(): object
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
            self::$instance->libFisco = FFI::load(__DIR__ . "/fisco-c-api.h");
            // $this->libFisco = FFI::scope("libFisco");

            self::$instance->groupid = "group0";
            self::$instance->chainid = "chain0";
            self::$instance->abi = "";
        }
        return self::$instance;
    }

    /**
     * 生成随机账户
     * @return Account
     */
    public function generateAccount(){
         //生成随机账户
        $keypair = $this->libFisco->bcos_sdk_create_keypair(0);
        $address = $this->libFisco->bcos_sdk_get_keypair_address($keypair);
        $privateKey = $this->libFisco->bcos_sdk_get_keypair_private_key($keypair);
        $this->libFisco->bcos_sdk_destroy_keypair($keypair);
        return new Account($address,$privateKey);
    }

    /**
     * 签名NFT铸造
     * @param $toAddress 铸造目标地址
     * @param $tokenID   NFT TokenID
     * @param $contractAddress  合约地址
     * @param $privateKey 签名账户私钥，格式：
     * @return SignResult
     */
    public function signMint($toAddress,$tokenID,$contractAddress,$privateKey,$blockNumber){
        $input = $this->encodeMint($toAddress,$tokenID);
        return $this->sign($privateKey,$contractAddress,$input,$blockNumber);
    }

    /**
     * 签名NFT转移
     * @param $fromAddress 转移源地址（签名私钥对应的地址）
     * @param $toAddress 铸造目标地址
     * @param $tokenID   NFT TokenID
     * @param $contractAddress  合约地址
     * @param $privateKey 签名账户私钥，格式：
     * @return SignResult
     */
    public function signTransferFrom($fromAddress,$toAddress,$tokenID,$contractAddress,$privateKey,$blockNumber){
        $input = $this->encodeTransfer($fromAddress,$toAddress,$tokenID);
        return $this->sign($privateKey,$contractAddress,$input,$blockNumber);
    }

    /**
     * 签名NFT转移
     * @param $tokenID   NFT TokenID
     * @param $contractAddress  合约地址
     * @param $privateKey 签名账户私钥，格式：
     * @return SignResult
     */
    public function signBurn($tokenID,$contractAddress,$privateKey,$blockNumber){
        $input = $this->encodeBurn($tokenID);
        return $this->sign($privateKey,$contractAddress,$input,$blockNumber);
    }

    private function sign($privateKey,$contractAddress,$data,$blockNumber){
        if($blockNumber == null){
            throw new Exception("Request for blockNumber failed.");
        }
        $blockLimit =  $blockNumber + 999;
        $tx_hash = FFI::new("char*");
        $signed = FFI::new("char*");
        $keypair = $this->libFisco->bcos_sdk_create_keypair_by_hex_private_key(0,$privateKey);
        $this->libFisco->bcos_sdk_create_signed_transaction($keypair,$this->groupid,$this->chainid,$contractAddress,$data,$this->abi,$blockLimit,0,FFI::addr($tx_hash),FFI::addr($signed));
        $this->libFisco->bcos_sdk_destroy_keypair($keypair);

        return new SignResult(FFI::string($tx_hash),FFI::string($signed));
    }

    private function encodeMint($toAddress,$tokenID){
        $method = 'mint(address,uint256)';
        $formatMethod = Formatter::toMethodFormat($method);
        $formatTo = Formatter::toAddressFormat($toAddress);
        $formatTokenID = Formatter::toIntegerFormat($tokenID);

        return "0x{$formatMethod}{$formatTo}{$formatTokenID}";
    }

    private function encodeTransfer($fromAddress,$toAddress,$tokenID){
        $method = 'transferFrom(address,address,uint256)';
        $formatMethod = Formatter::toMethodFormat($method);
        $formatFrom = Formatter::toAddressFormat($fromAddress);
        $formatTo = Formatter::toAddressFormat($toAddress);
        $formatTokenID = Formatter::toIntegerFormat($tokenID);

        return "0x{$formatMethod}{$formatFrom}{$formatTo}{$formatTokenID}";
    }

    private function encodeBurn($tokenID){
        $method = 'burn(uint256)';
        $formatMethod = Formatter::toMethodFormat($method);
        $formatTokenID = Formatter::toIntegerFormat($tokenID);

        return "0x{$formatMethod}{$formatTokenID}";
    }
}