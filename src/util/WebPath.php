<?php
/**
 * Created by PhpStorm.
 * User: vring
 * Date: 2021/10/6
 * Time: 10:10
 */

namespace vring\util;


class WebPath
{
    /**
     * 网站目录替换分割符 始终 /
     * @param string $path
     * @return string
     */
    static public function webTransform($path)
    {
        return str_replace('\\', '/', $path);
    }

    /**
     * 磁盘路径转网站访问路径，需文件在网站根
     * @param string $real_path
     * @return mixed
     */
    static public function realToSitePath($real_path)
    {
        $web_root = self::webTransform($_SERVER['DOCUMENT_ROOT']);
//    var_dump($web_root);
        return str_replace($web_root, '', self::webTransform($real_path));
    }

    /**
     * 取得网站根路径
     * @return string
     * */
    static public function getSiteRoot()
    {
//        $root = Path::transform(dirname(str_replace($_SERVER['PATH_INFO'],'',$_SERVER['PHP_SELF'])));
        $root = dirname(WebPath::realToSitePath($_SERVER['SCRIPT_FILENAME']));
//        var_dump($root);
        return $root == '/' ? '' : $root;
    }
}