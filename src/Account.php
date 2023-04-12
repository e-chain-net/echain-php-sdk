<?php
/**
 * author: luleigreat
 * datetime: 2023/4/11 17:52
 */

namespace EChain;

class Account{
    public $address;
    public $private;

    function __construct($address, $private) {
        $this->address = $address;
        $this->private = $private;
    }
}