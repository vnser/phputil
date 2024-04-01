<?php
/**
 * Author: vring
 * Date: 2024/4/1
 * Email: <971626354@qq.com>
 */

namespace vring\wechat;

use EasyWeChat\OfficialAccount\Application;
use vring\util\Url;

class Oauth2
{
    /**
     * @param string $url 获取到openid后的跳转url，如果有带参数，可先对整个url做下urlencode编码
     * @param int $flag 0表示静默获取openid，1表示需要用户授权获取详细信息
     * @return void
     */
    static public function oauth2(Application $app,string $url,string $scopes = 'snsapi_userinfo')
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        ;
//        $aUrl = self::getOauth2Url($url,$flag);
        $auth_user  = $_SESSION["wachat_user"]??null;
        if ($auth_user){
            return $auth_user;
        }
        if (!isset($_GET['code'])){
            $rep = $app->oauth->scopes([$scopes])
//                ->setRequest($request)
                ->redirect();
            print_r($rep);
//            header('location: '.$aUrl);
            exit;
        }
        $code = $_GET['code'] ?? '';
        if ($code){
          /*  if (isset($_GET['userinfo'])){
                $_GET['userinfo'] = json_decode(base64_decode($_GET['userinfo']),true);
            }*/
            $user = $app->oauth->user();
            $_SESSION["wechat_user"] = $user;
            return $user;
        }
        throw new \Exception("授权异常，".Url::instance()->url());
    }
}