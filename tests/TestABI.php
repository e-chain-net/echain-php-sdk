<?php

namespace Test;

require_once __DIR__ . '/../vendor/autoload.php';

use EChain\Formatter;

$CONTRACT_ADDRESS = '0xF00dA0bDBeFE30659f2a40aBa168E9317A6dbB72';
$ACCOUNT_ADDRESS = '0xcDFC7406BeacF91ED425eade994CD0839d3FA9fD';

const INFURA_KEY = '8275f7b717754213a1c07e22939b324d';
$ETHERSCAN_KEY = 'KJU6S4DP2AFPA91T6XEKCEDJUA6V5R9MD5';
$PRIVATE_KEY = '0xd17053df99d95ba1589fdbb1ee1a84cf12f48ff0446caca1ff277763045dfdc8';



$methodMint = 'mint(address,uint256)';
$methodTransferFrom = 'transferFrom(address,address,uint256)';
$methodBurn = 'burn(uint256)';

$from = "0x2242eaaedb3ecb4d02c43aef87dd25e4ef559c29";
$to = "0x9847b8f7bf06fa6687f38475ab621c188689d11e";
$val = "123";

// $start = getMillisecond();
// for($i=0; $i<10000; $i++){
//     $formatMethod = Formatter::toMethodFormat($method);
//     $formatFrom = Formatter::toAddressFormat($from);
//     $formatTo = Formatter::toAddressFormat($to);
//     $formatInteger = Formatter::toIntegerFormat($val);

//     $inputData = "0x{$formatMethod}{$formatFrom}{$formatTo}{$formatInteger}";
// }
// $end = getMillisecond();
// echo "elapsed:" . ($end-$start) . "ms";


$formatMethodTransfer = Formatter::toMethodFormat($methodTransferFrom);
$formatMethodMint = Formatter::toMethodFormat($methodMint);
$formatMethodBurn = Formatter::toMethodFormat($methodBurn);
$formatFrom = Formatter::toAddressFormat($from);
$formatTo = Formatter::toAddressFormat($to);
$formatInteger = Formatter::toIntegerFormat($val);

$inputMint = "0x{$formatMethodMint}{$formatTo}{$formatInteger}";
$inputTransfer = "0x{$formatMethodTransfer}{$formatFrom}{$formatTo}{$formatInteger}";
$inputBurn = "0x{$formatMethodBurn}{$formatInteger}";
echo "mint:" . $inputMint . "\n";
echo "transfer:" . $inputTransfer . "\n";
echo "burn:" . $inputBurn . "\n";