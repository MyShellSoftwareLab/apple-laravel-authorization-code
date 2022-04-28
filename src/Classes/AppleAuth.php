<?php

namespace AnimusCoop\AppleTokenAuth\Classes;

use AnimusCoop\AppleTokenAuth\Console\Commands\AppleKeyGenerate;
use AnimusCoop\AppleTokenAuth\Utils\Call;


class AppleAuth
{

    public $jwt = '', $objJwt = null, $objCall = null;

    public function __construct($data)
    {
        $initialData = [
            'client_id' => config('services.apple.client_id'),
            'key' => config('services.apple.key'),
            'key_id' => config('services.apple.key_id'),
            'team_id' => config('services.apple.team_id'),
            'code' => isset($data['code']) ? $data['code'] : null
        ];

        self::validateData($initialData);

        //  $objJwtValidation = self::buildObjJwt($initialData);
        $objCallValidation = self::buildObjCall($initialData);

        //  $this->objJwt = $objJwtValidation;
        $this->objCall = $objCallValidation;

        //  $signjwt = new Jwt($objJwtValidation);

        //$this->jwt = $signjwt->jwtSigned;
        $this->jwt = AppleKeyGenerate::generateClientSecret(false);

    }

    public function getJwtSigned()
    {
        return $this->jwt;
    }

    public function getUserData()
    {
        $call = new Call($this->jwt, $this->objCall);
        $userData = $call->getResponse();

        return $userData;

    }

    private static function validateData($data)
    {
        if (!file_exists($data['key'])) {
            self::errorResponse('Key p8 not found');
        }

        if (!$data['client_id']) {
            self::errorResponse('client id not valid');
        }

        if (!$data['key_id']) {
            self::errorResponse('key id not valid');
        }

        if (!$data['team_id']) {
            self::errorResponse('team id not valid');
        }

        if (!$data['code']) {
            self::errorResponse('code not valid');
        }

        return true;

    }

    private static function errorResponse($error)
    {
        throw new \Exception($error);

    }

    private static function buildObjJwt($data)
    {
        $objJwtRequest = new \stdClass();
        $objJwtRequest->key_id = $data['key_id'];
        $objJwtRequest->team_id = $data['team_id'];
        $objJwtRequest->key = $data['key'];
        $objJwtRequest->client_id = $data['client_id'];
        return $objJwtRequest;
    }

    private static function buildObjCall($data)
    {
        $objJwtRequest = new \stdClass();
        $objJwtRequest->code = $data['code'];
        $objJwtRequest->client_id = $data['client_id'];
        return $objJwtRequest;
    }


}