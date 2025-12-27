<?php

declare(strict_types=1);

namespace Lowel\Telepath\Helpers;

class Hasher
{
    const BASE62 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function shortHash(string $text, $len = 8): string
    {
        return substr(md5($text), 0, $len);
    }

    public static function encrypt(string $text): string
    {
        return self::binToBase62($text);
    }

    public static function decrypt(string $text): string
    {
        return self::base62ToBin($text); // или base62_decode
    }

    private static function binToBase62(string $bin): string
    {
        $num = gmp_init(bin2hex($bin), 16);
        $result = '';
        $base = strlen(self::BASE62);
        while (gmp_cmp($num, 0) > 0) {
            $rem = gmp_mod($num, $base);
            $result = self::BASE62[gmp_intval($rem)].$result;
            $num = gmp_div_q($num, $base);
        }

        return $result ?: '0';
    }

    private static function base62ToBin(string $str): string
    {
        $num = gmp_init(0);
        $base = strlen(self::BASE62);
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $num = gmp_add(gmp_mul($num, $base), strpos(self::BASE62, $str[$i]));
        }

        return hex2bin(gmp_strval($num, 16));
    }
}
