<?php

namespace components;

use app\exceptions\JwtException;
use app\Request;
use components\Debugger as d;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use DomainException;
use InvalidArgumentException;
use models\Token;
use UnexpectedValueException;


class JwtHelper
{

    protected $private_key_path;
    protected $public_key_path;
    protected $type_hash = 'RS256';

    public function __construct()
    {
        // Тут можно было бы сделать проверку на существование файлов ключей.
        $this->public_key_path = @file_get_contents(__DIR__ . '/../keys/key.pub');
        $this->private_key_path = @file_get_contents(__DIR__ . '/../keys/key.pem');
    }

    /**
     * @param $payload
     * @return string|null
     */
    public function generateToken($payload = [])
    {
        $jwt_token = NULL;
        if (is_array($payload) and count($payload) > 0) {
            $jwt_token = JWT::encode($payload, $this->private_key_path, $this->type_hash);
        }
        return $jwt_token;
    }

    /**
     * @param $payload
     * @return string|null
     */
    public function createAccessToken($payload = [])
    {
        $token = NULL;
        if (is_array($payload) and count($payload) > 0) {
            $token = $this->generateToken($payload);
        }
        return $token;
    }

    /**
     * @param $payload
     * @return string|null
     */
    public function createRefreshToken($payload = [])
    {
        $token = '';
        if (is_array($payload) and count($payload) > 0) {
            $token = $this->generateToken($payload);
        }
        return $token;
    }

    /**
     * @param $auth_token
     * @return null
     */
    public function verify($auth_token)
    {
        try {
            $token_db = Token::findToken($auth_token);
            if ($token_db and $token_db['refresh_token'] > 0) {
                $decoded = JWT::decode($auth_token, new Key($this->public_key_path, $this->type_hash));
                return $decoded->data;
            }
            return NULL;
        } catch (JwtException $e) {
            return NULL;
        }
    }

} //Class