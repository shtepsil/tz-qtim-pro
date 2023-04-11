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
use http\Env\Response;
use InvalidArgumentException;
use models\Token;
use UnexpectedValueException;


class JwtHelper
{

    protected $private_key_path;
    protected $public_key_path;
    protected $type_hash = 'RS256';
    private $time;

    /**
     * JwtHelper constructor.
     */
    public function __construct()
    {
        $this->time = time();
        // Тут можно было бы сделать проверку на существование файлов ключей.
        $this->public_key_path = @file_get_contents(__DIR__ . '/../keys/key.pub');
        $this->private_key_path = @file_get_contents(__DIR__ . '/../keys/key.pem');
        //        $this->public_key_path = '1234567890P';
//        $this->private_key_path = '1234567890O';
    }

    /**
     * @param $user_id
     * @param \DateTime $dateTime
     * @return string
     */
    public function generateToken($payload = [])
    {
        $jwt_token = NULL;
        if (is_array($payload) and count($payload) > 0) {
            //        $jwt = JWT::encode($payload, $privateKey, 'RS256');
            $jwt_token = JWT::encode($payload, $this->private_key_path, $this->type_hash);
            //            d::ajax($jwt_token);

            //        $decoded = JWT::decode($jwt, new Key($publicKey, 'RS256'));
//            $decoded = JWT::decode($jwt, new Key($this->public_key_path, 'RS256'));
//            d::ajax($decoded);
//            return $decoded;
        }
        return $jwt_token;
    }

    public function createAccessToken($payload = [])
    {
        $token = NULL;
        if (is_array($payload) and count($payload) > 0) {
            $token = $this->generateToken($payload);
        }
        return $token;
    }

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
     * @return bool
     * @throws JwtException
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

    /**
     * @param $payload
     * @param $keys
     * @return void
     */
    public function validate($payload, $keys)
    {
        try {
            $decoded = JWT::decode($payload, $keys);
        } catch (InvalidArgumentException $e) {
            // предоставленный ключ /key-array пуст или неправильно сформирован.
        } catch (DomainException $e) {
            // предоставленный алгоритм не поддерживается, ИЛИ
            // предоставленный ключ недействителен, ИЛИ
            // в OpenSSL или libsodium возникает неизвестная ошибка, ИЛИ
            // libsodium требуется, но недоступен.
        } catch (SignatureInvalidException $e) {
            // не удалось выполнить проверку подписи JWT.
        } catch (BeforeValidException $e) {
            // при условии, что JWT пытается быть использован перед утверждением "nbf" ИЛИ
            // при условии, что JWT пытается быть использован перед утверждением "iat".
        } catch (ExpiredException $e) {
            // при условии, что JWT пытается быть использован после утверждения "exp".
        } catch (UnexpectedValueException $e) {
            // при условии, что JWT имеет неправильную форму Или
            // при условии, что в JWT отсутствует алгоритм / используется неподдерживаемый алгоритм ИЛИ
            // при условии, что алгоритм JWT не соответствует предоставленному ключу Или
            // предоставленный идентификатор ключа в key / key-array пуст или недействителен.
        }
        /*
        Все исключения в Firebase\JWT пространстве имен расширяются UnexpectedValueException и могут быть упрощены следующим образом:
        try {
        $decoded = JWT::decode($payload, $keys);
        } catch (LogicException $e) {
        // ошибки, связанные с настройкой среды или неправильными ключами JWT
        } catch (UnexpectedValueException $e) {
        // ошибки, связанные с подписью JWT и утверждениями
        }
        */
    }




    // Старые скрипты
    // =============================================================
    // =============================================================
    // =============================================================
    // =============================================================
    // =============================================================




    /**
     * @param $user_id
     * @param \DateTime $dateTime
     * @return \Lcobucci\JWT\Token
     */
    public function generateToken2($user_id, \DateTime $dateTime, $domains = '')
    {
        $key = new Key(@file_get_contents($this->private_key_path));
        $token = (new Builder())->issuedBy($domains)
            ->permittedFor($domains)
            ->issuedAt((new \DateTime('now'))->getTimestamp())
            ->expiresAt($dateTime->getTimestamp())
            ->withClaim('user_id', $user_id)
            ->getToken(new Sha256(), $key);

        d::ajax($token);
        return $token;
    }

    /**
     * @param $user_id
     * @param \DateTime $dateTime
     * @return \Lcobucci\JWT\Token
     */
    public function generateToken1($user_id, \DateTime $dateTime, $domains = '')
    {
        $key = new Key(@file_get_contents($this->private_key_path));
        $token = (new Builder())->issuedBy($domains)
            ->permittedFor($domains)
            ->issuedAt((new \DateTime('now'))->getTimestamp())
            ->expiresAt($dateTime->getTimestamp())
            ->withClaim('user_id', $user_id)
            ->getToken(new Sha256(), $key);

        d::ajax($token);
        return $token;
    }

    /**
     * @param $token
     * @param $claim
     * @return mixed
     */
    public function getClaim($token, $claim)
    {
        $parser = new Parser();
        $token = $parser->parse($token);
        return $token->getClaim($claim);
    }


    // Тестовые скрипты
    // =====================================================================
    // =====================================================================
    // =====================================================================
    // =====================================================================
    // =====================================================================
    // =====================================================================
    public function test()
    {
        $payload = [
            'iss' => 'example.org',
            'aud' => 'example.com',
            'iat' => 1356999524,
            'nbf' => 1357000000
        ];

        $domains = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        //        $expire = new \DateTime('+7 days');
        $expire = strtotime('+7 days');
        $payload = [
            // адрес или имя удостоверяющего центра
            'iss' => $domains,
            // имя клиента для которого токен выпущен
            'aud' => $domains,
            // время, когда был выпущен JWT
            'iat' => $this->time,
            // время, начиная с которого может быть использован (не раньше, чем)
            'nbf' => time(),
            // идентифицирует время истечения срока действия токена
            'exp' => $expire,
            'data' => [
                'id' => 4,
                'firstname' => 'user_firstname',
                'lastname' => 'user_lastname',
                'email' => 'user_email'
            ]
        ];

        $jwt = JWT::encode($payload, $this->private_key_path, 'RS256');
        //        d::ajax($jwt);

        $decoded = JWT::decode($jwt, new Key($this->public_key_path, 'RS256'));
        d::ajax($decoded);
    }

    public function testDock()
    {
        $privateKey = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAuzWHNM5f+amCjQztc5QTfJfzCC5J4nuW+L/aOxZ4f8J3Frew
M2c/dufrnmedsApb0By7WhaHlcqCh/ScAPyJhzkPYLae7bTVro3hok0zDITR8F6S
JGL42JAEUk+ILkPI+DONM0+3vzk6Kvfe548tu4czCuqU8BGVOlnp6IqBHhAswNMM
78pos/2z0CjPM4tbeXqSTTbNkXRboxjU29vSopcT51koWOgiTf3C7nJUoMWZHZI5
HqnIhPAG9yv8HAgNk6CMk2CadVHDo4IxjxTzTTqo1SCSH2pooJl9O8at6kkRYsrZ
WwsKlOFE2LUce7ObnXsYihStBUDoeBQlGG/BwQIDAQABAoIBAFtGaOqNKGwggn9k
6yzr6GhZ6Wt2rh1Xpq8XUz514UBhPxD7dFRLpbzCrLVpzY80LbmVGJ9+1pJozyWc
VKeCeUdNwbqkr240Oe7GTFmGjDoxU+5/HX/SJYPpC8JZ9oqgEA87iz+WQX9hVoP2
oF6EB4ckDvXmk8FMwVZW2l2/kd5mrEVbDaXKxhvUDf52iVD+sGIlTif7mBgR99/b
c3qiCnxCMmfYUnT2eh7Vv2LhCR/G9S6C3R4lA71rEyiU3KgsGfg0d82/XWXbegJW
h3QbWNtQLxTuIvLq5aAryV3PfaHlPgdgK0ft6ocU2de2FagFka3nfVEyC7IUsNTK
bq6nhAECgYEA7d/0DPOIaItl/8BWKyCuAHMss47j0wlGbBSHdJIiS55akMvnAG0M
39y22Qqfzh1at9kBFeYeFIIU82ZLF3xOcE3z6pJZ4Dyvx4BYdXH77odo9uVK9s1l
3T3BlMcqd1hvZLMS7dviyH79jZo4CXSHiKzc7pQ2YfK5eKxKqONeXuECgYEAyXlG
vonaus/YTb1IBei9HwaccnQ/1HRn6MvfDjb7JJDIBhNClGPt6xRlzBbSZ73c2QEC
6Fu9h36K/HZ2qcLd2bXiNyhIV7b6tVKk+0Psoj0dL9EbhsD1OsmE1nTPyAc9XZbb
OPYxy+dpBCUA8/1U9+uiFoCa7mIbWcSQ+39gHuECgYAz82pQfct30aH4JiBrkNqP
nJfRq05UY70uk5k1u0ikLTRoVS/hJu/d4E1Kv4hBMqYCavFSwAwnvHUo51lVCr/y
xQOVYlsgnwBg2MX4+GjmIkqpSVCC8D7j/73MaWb746OIYZervQ8dbKahi2HbpsiG
8AHcVSA/agxZr38qvWV54QKBgCD5TlDE8x18AuTGQ9FjxAAd7uD0kbXNz2vUYg9L
hFL5tyL3aAAtUrUUw4xhd9IuysRhW/53dU+FsG2dXdJu6CxHjlyEpUJl2iZu/j15
YnMzGWHIEX8+eWRDsw/+Ujtko/B7TinGcWPz3cYl4EAOiCeDUyXnqnO1btCEUU44
DJ1BAoGBAJuPD27ErTSVtId90+M4zFPNibFP50KprVdc8CR37BE7r8vuGgNYXmnI
RLnGP9p3pVgFCktORuYS2J/6t84I3+A17nEoB4xvhTLeAinAW/uTQOUmNicOP4Ek
2MsLL2kHgL8bLTmvXV4FX+PXphrDKg1XxzOYn0otuoqdAQrkK4og
-----END RSA PRIVATE KEY-----
EOD;

        $publicKey = <<<EOD
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuzWHNM5f+amCjQztc5QT
fJfzCC5J4nuW+L/aOxZ4f8J3FrewM2c/dufrnmedsApb0By7WhaHlcqCh/ScAPyJ
hzkPYLae7bTVro3hok0zDITR8F6SJGL42JAEUk+ILkPI+DONM0+3vzk6Kvfe548t
u4czCuqU8BGVOlnp6IqBHhAswNMM78pos/2z0CjPM4tbeXqSTTbNkXRboxjU29vS
opcT51koWOgiTf3C7nJUoMWZHZI5HqnIhPAG9yv8HAgNk6CMk2CadVHDo4IxjxTz
TTqo1SCSH2pooJl9O8at6kkRYsrZWwsKlOFE2LUce7ObnXsYihStBUDoeBQlGG/B
wQIDAQAB
-----END PUBLIC KEY-----
EOD;

        $payload = [
            'iss' => 'example.org',
            'aud' => 'example.com',
            'iat' => 1356999524,
            'nbf' => 1357000000
        ];

        $jwt = JWT::encode($payload, $privateKey, 'RS256');
        //        d::ajax($jwt);

        $decoded = JWT::decode($jwt, new Key($publicKey, 'RS256'));
        d::ajax($decoded);
    }


} //Class