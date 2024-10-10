<?php

namespace EscolaLms\MultidomainTool;

use phpseclib\Crypt\RSA as LegacyRSA;
use phpseclib3\Crypt\RSA;

class Keys
{
    public static function generateRSAKeysPair(): array
    {
        if (class_exists(LegacyRSA::class)) {
            $keys = (new LegacyRSA)->createKey(4096);
            return ['publickey' => $keys['publickey'], 'privatekey' => $keys['privatekey']];
        } else {
            $key = RSA::createKey(4096);
            return ['publickey' => (string) $key->getPublicKey(), 'privatekey' => (string) $key];
        }
    }
}
