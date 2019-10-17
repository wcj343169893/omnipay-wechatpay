<?php

namespace Omnipay\WechatPay;

class Helper
{
    public static function array2xml($arr, $root = 'xml')
    {
        $xml = "<$root>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";

        return $xml;
    }


    public static function xml2array($xml)
    {
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }


    /**
     * MD5签名方式
     * @param unknown $data
     * @param unknown $key
     * @link https://pay.weixin.qq.com/wiki/doc/api/jsapi_sl.php?chapter=4_3
     * @return string
     */    
    public static function sign($data, $key)
    {
        unset($data['sign']);

        ksort($data);

        $query = urldecode(http_build_query($data));
        $query .= "&key={$key}";

        return strtoupper(md5($query));
    }
    /**
     * HMAC-SHA256签名方式
     * @param unknown $data
     * @param unknown $key
     * @link https://pay.weixin.qq.com/wiki/doc/api/jsapi_sl.php?chapter=4_3
     * @link https://pay.weixin.qq.com/wiki/doc/api/jsapi_sl.php?chapter=20_1
     * @return string
     */
    public static function hmacSha256($data, $key)
    {
        unset($data['sign']);

        ksort($data);

        $query = urldecode(http_build_query($data));
        $query .= "&key={$key}";

        return strtoupper(hash_hmac("sha256",$query,$key));
    }
}
