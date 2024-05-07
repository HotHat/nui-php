<?php

namespace App\Http\Controller;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Nui\Application;
use Nui\Cache;
use Nui\Captcha;
use Nui\Database\DB;
use Nui\Request;
use Nui\Response;
use Nui\Support\Str;

class LoginController
{
    public function test(Request $request) {
        //创建图片
        // $captcha = Captcha::instance()->create();
        // $html = sprintf('<h1>%s</h1> <img src="%s" />', $captcha['code'], $captcha['data']);
        // echo $html;

        Cache::instance()->set('abc', 123, 5);
        sleep(6);
        dump(Cache::instance()->get('abc'));
    }

    public function captcha(Request $request) {
        $captcha = Captcha::instance()->create();
        $key = Str::random(32);
        Cache::instance()->set($key, $captcha['code'], 300);

        return respSuccess([
            'code' => $key,
            'data' => $captcha['data']
        ]);
    }

    public function test2(Request $request) {
        return respSuccess(['query' => $request->post(),
            // '_server' => $_SERVER,
            'content_type' => $request->header('Content-Type'),
            'raw' => file_get_contents('php://input'),
            'files' => $_FILES
        ]);
    }

    public function login(Request $request): Response|string
    {
        $username = $request->post('username', '');
        $password = $request->post('password', '');
        $captcha = $request->post('captcha', '');
        $captchaKey = $request->post('captchaKey', '');

        $cache = Cache::instance()->get($captchaKey);
        if (strtolower($cache) !== strtolower($captcha)) {
            // Cache::instance()->flush($captchaKey);
            return respFail('captcha auth fail');
        }

        $user = UserRepository::instance()->findByName($username);
        if (empty($user)) {
            return respFail('username or password fail1');
        }

        if (!hashVerify($password, $user['password'])) {
            return respFail('username or password fail2');
        }

        $cfg = config('jwt');
        $payload = [
            'iat' => time(),
            'exp' => time() + $cfg['jwt_expired'],
            'id' => $user['id'],
        ];

        $jwt = JWT::encode($payload, $cfg['jwt_key'], 'HS256');
        return respSuccess([
            'accessToken' => $jwt
        ]);
    }

    public function logout() {

    }
}