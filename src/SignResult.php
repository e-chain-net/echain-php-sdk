<?php
/**
 * author: luleigreat
 * datetime: 2023/4/11 17:52
 */

namespace EChain;

class SignResult{
    public $txHash;
    public $signed;

    function __construct(string $txHash, $signed) {
        $this->txHash = $txHash;
        $this->signed = $signed;
    }
}