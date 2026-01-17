<?php

namespace vring\qqmap;



class QQMap
{
    static public $key = '';

    /**
     * 腾讯地图接口,逆地址查询,根据中文地址解析解析出
     * @return void
     * @throws \Exception
     */
    static public function qeocoderByAddress($address)
    {
        return self::qeocoder(['address'=>$address]);
    }


    /**
     * 根据坐标查询地址
     * @param string $lat
     * @param string $lng
     * @return mixed
     * @throws \Exception
     */
    static public function qeocoderByLocation($lat,$lng)
    {
        return self::qeocoder(['location'=>"{$lat},{$lng}"]);
    }


    /**
     * 腾讯地图接口,逆地址查询,根据中文地址解析解析出
     * @param $param
     * @return mixed
     * @throws \Exception
     */
    static private function qeocoder($param)
    {
        $key = self::$key;
//        $location = $this->request->request('location');
        $paramStr = http_build_query(array_merge($param,['key'=>$key]));
        $geocoder = file_get_contents("https://apis.map.qq.com/ws/geocoder/v1/?{$paramStr}");
        $res = json_decode($geocoder,true);
        if ($res['status'] != 0){
            throw new \Exception($res['message']);
        }
        return $res;
    }


    /**
     * @param array $list
     * @return array
     */
    static public  function removeMunicipalityCity(array $list): array
    {
        $municipalities = ['北京市', '上海市', '天津市', '重庆市'];

        foreach ($list as &$item) {
            if (isset($item['ad_info']['province'])
                && in_array($item['ad_info']['province'], $municipalities, true)
            ) {
                $item['ad_info']['province'] = str_replace('市', '', $item['ad_info']['province']);
            }
        }

        return $list;
    }

}