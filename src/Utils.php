<?php
/**
 * author: luleigreat
 * datetime: 2023/4/11 17:52
 */

namespace EChain;

use phpseclib\Math\BigInteger;
use InvalidArgumentException;
use kornrunner\Keccak;

class Utils
{

    /**
     * SHA3_NULL_HASH
     *
     * @const string
     */
    const SHA3_NULL_HASH = 'c5d2460186f7233c927e7db2dcc703c0e500b653ca82273b7bfad8045d85a470';

    public static function getSeconds(){
        list($msec,$sec)=explode(' ',microtime());
        return $sec;
    }    

    /**
     * isZeroPrefixed
     *
     * @param string
     * @return bool
     */
    public static function isZeroPrefixed($value)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('The value to isZeroPrefixed function must be string.');
        }
        return (strpos($value, '0x') === 0);
    }

    /**
     * stripZero
     *
     * @param string $value
     * @return string
     */
    public static function stripZero($value)
    {
        if (self::isZeroPrefixed($value)) {
            $count = 1;
            return str_replace('0x', '', $value, $count);
        }
        return $value;
    }

        /**
     * hexToBin
     *
     * @param string
     * @return string
     */
    public static function hexToBin($value)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('The value to hexToBin function must be string.');
        }
        if (self::isZeroPrefixed($value)) {
            $count = 1;
            $value = str_replace('0x', '', $value, $count);
        }
        return pack('H*', $value);
    }
    
    /**
     * isAddress
     *
     * @param string $value
     * @return bool
     */
    public static function isAddress($value)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('The value to isAddress function must be string.');
        }
        if (preg_match('/^(0x|0X)?[a-f0-9A-F]{40}$/', $value) !== 1) {
            return false;
        } elseif (preg_match('/^(0x|0X)?[a-f0-9]{40}$/', $value) === 1 || preg_match('/^(0x|0X)?[A-F0-9]{40}$/', $value) === 1) {
            return true;
        }
        return self::isAddressChecksum($value);
    }

    /**
     * isAddressChecksum
     *
     * @param string $value
     * @return bool
     */
    public static function isAddressChecksum($value)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('The value to isAddressChecksum function must be string.');
        }
        $value = self::stripZero($value);
        $hash = self::stripZero(self::sha3(mb_strtolower($value)));

        for ($i = 0; $i < 40; $i++) {
            if (
                (intval($hash[$i], 16) > 7 && mb_strtoupper($value[$i]) !== $value[$i]) ||
                (intval($hash[$i], 16) <= 7 && mb_strtolower($value[$i]) !== $value[$i])
            ) {
                return false;
            }
        }
        return true;
    }

        /**
     * sha3
     * keccak256
     *
     * @param string $value
     * @return string
     */
    public static function sha3($value)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('The value to sha3 function must be string.');
        }
        if (strpos($value, '0x') === 0) {
            $value = self::hexToBin($value);
        }
        $hash = Keccak::hash($value, 256);

        if ($hash === self::SHA3_NULL_HASH) {
            return null;
        }
        return '0x' . $hash;
    }

        /**
     * toBn
     * Change number or number string to BigInteger.
     *
     * @param BigInteger|string|int $number
     * @return array|BigInteger
     */
    public static function toBn($number)
    {
        if ($number instanceof BigInteger){
            $bn = $number;
        } elseif (is_int($number)) {
            $bn = new BigInteger($number);
        } elseif (is_numeric($number)) {
            $number = (string) $number;

            if (self::isNegative($number)) {
                $count = 1;
                $number = str_replace('-', '', $number, $count);
                $negative1 = new BigInteger(-1);
            }
            if (strpos($number, '.') > 0) {
                $comps = explode('.', $number);

                if (count($comps) > 2) {
                    throw new InvalidArgumentException('toBn number must be a valid number.');
                }
                $whole = $comps[0];
                $fraction = $comps[1];

                return [
                    new BigInteger($whole),
                    new BigInteger($fraction),
                    strlen($comps[1]),
                    isset($negative1) ? $negative1 : false
                ];
            } else {
                $bn = new BigInteger($number);
            }
            if (isset($negative1)) {
                $bn = $bn->multiply($negative1);
            }
        } elseif (is_string($number)) {
            $number = mb_strtolower($number);

            if (self::isNegative($number)) {
                $count = 1;
                $number = str_replace('-', '', $number, $count);
                $negative1 = new BigInteger(-1);
            }
            if (self::isZeroPrefixed($number) || preg_match('/[a-f]+/', $number) === 1) {
                $number = self::stripZero($number);
                $bn = new BigInteger($number, 16);
            } elseif (empty($number)) {
                $bn = new BigInteger(0);
            } else {
                throw new InvalidArgumentException('toBn number must be valid hex string.');
            }
            if (isset($negative1)) {
                $bn = $bn->multiply($negative1);
            }
        } else {
            throw new InvalidArgumentException('toBn number must be BigInteger, string or int.');
        }
        return $bn;
    }

        /**
     * isNegative
     *
     * @param string
     * @return bool
     */
    public static function isNegative($value)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('The value to isNegative function must be string.');
        }
        return (strpos($value, '-') === 0);
    }
}