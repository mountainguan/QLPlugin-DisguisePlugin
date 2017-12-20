<?php
/**
 * User: mountainguan <mountainguan@gmail.com>
 * Date: 2017/12/20
 * Make a disguise header for get/post request.
 */
namespace QL\Ext;
use QL\Contracts\PluginContract;
use QL\QueryList;

class DisguisePlugin implements PluginContract
{
    protected static $args = [];

    //注册
    public static function install(QueryList $queryList, ...$opt)
    {
        $disguise_ip = $opt[0] ?? 'disguiseIp';
        $disguise_ua = $opt[1] ?? 'disguiseUa';
        
        $queryList->bind($disguise_ip,function ($otherArgs = [] ,$ip = ''){
            return DisguisePlugin::setIp($this,$otherArgs,$ip);
        });

        $queryList->bind($disguise_ua,function ($otherArgs = [] ,$ua = ''){
            return DisguisePlugin::setUserAgent($this,$otherArgs,$ua);
        });
    }

    //设置混淆IP
    public static function setIp($ql ,$otherArgs = [] ,$ip = '')
    {
        if (empty($ip)) {
            $ip = self::getRandIp();
        }
        $header = $otherArgs['headers'] ?? [];
        $header_add = [
            'X-Forwarded-For' => $ip,
            'Proxy-Client-IP' => $ip,
            'WL-Proxy-Client-IP' => $ip,
            'HTTP_CLIENT_IP' => $ip,
            'X-Real-IP' => $ip
        ];
        
        if (empty($otherArgs) && !empty(self::$args)) {
            $header = self::$args['headers'];
        }
        self::$args['headers'] = array_merge($header,$header_add);

        $ql->disguise_headers = self::$args;
        return $ql;
    }

    //设置混淆UA
    public static function setUserAgent($ql ,$otherArgs = [] ,$ua = '')
    {
        if (empty($ua)) {
            $ua = self::getRandUa();
        }
        $header = $otherArgs['headers'] ?? [];
        $header_add = [
            'User-Agent' => $ua
        ];
        
        if (empty($otherArgs) && !empty(self::$args)) {
            $header = self::$args['headers'];
        }
        self::$args['headers'] = array_merge($header,$header_add);

        $ql->disguise_headers = self::$args;
        return $ql;
    }

    /*
     * 随机ip函数(中国ip段)
     */
    private static function getRandIp(){
        $arr_1 = array("218","218","66","66","218","218","60","60","202","204","66","66","66","59","61","60","222","221","66","59","60","60","66","218","218","62","63","64","66","66","122","211");
        $randarr= mt_rand(0,count($arr_1)-1);
        $ip1id = $arr_1[$randarr];
        $ip2id=  round(rand(600000,  2550000)  /  10000);
        $ip3id=  round(rand(600000,  2550000)  /  10000);
        $ip4id=  round(rand(600000,  2550000)  /  10000);
        return  $ip1id . "." . $ip2id . "." . $ip3id . "." . $ip4id;
    }

    /*
     * 随机UA
     */
    private static function getRandUa() {
        $agent_arr = [
            //PC端的UserAgent  
            "safari 5.1 – MAC"=>"Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11",  
            "safari 5.1 – Windows"=>"Mozilla/5.0 (Windows; U; Windows NT 6.1; en-us) AppleWebKit/534.50 (KHTML, like Gecko) Version/5.1 Safari/534.50",  
            "Firefox 38esr"=>"Mozilla/5.0 (Windows NT 10.0; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0",  
            "IE 11"=>"Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; .NET4.0C; .NET4.0E; .NET CLR 2.0.50727; .NET CLR 3.0.30729; .NET CLR 3.5.30729; InfoPath.3; rv:11.0) like Gecko",  
            "IE 9.0"=>"Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0",  
            "IE 8.0"=>"Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)",  
            "IE 7.0"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)",  
            "IE 6.0"=>"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)",  
            "Firefox 4.0.1 – MAC"=>"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:2.0.1) Gecko/20100101 Firefox/4.0.1",  
            "Firefox 4.0.1 – Windows"=>"Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1",  
            "Opera 11.11 – MAC"=>"Opera/9.80 (Macintosh; Intel Mac OS X 10.6.8; U; en) Presto/2.8.131 Version/11.11",  
            "Opera 11.11 – Windows"=>"Opera/9.80 (Windows NT 6.1; U; en) Presto/2.8.131 Version/11.11",  
            "Chrome 17.0 – MAC"=>"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_0) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11",  
            "傲游（Maxthon）"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Maxthon 2.0)",  
            "腾讯TT"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; TencentTraveler 4.0)",  
            "世界之窗（The World） 2.x"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)",  
            "世界之窗（The World） 3.x"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; The World)",  
            "360浏览器"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; 360SE)",  
            "搜狗浏览器 1.x"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SE 2.X MetaSr 1.0; SE 2.X MetaSr 1.0; .NET CLR 2.0.50727; SE 2.X MetaSr 1.0)",  
            "Avant"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Avant Browser)",  
            "Green Browser"=>"Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)",  
            //移动端口  
            "safari iOS 4.33 – iPhone"=>"Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5",  
            "safari iOS 4.33 – iPod Touch"=>"Mozilla/5.0 (iPod; U; CPU iPhone OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5",  
            "safari iOS 4.33 – iPad"=>"Mozilla/5.0 (iPad; U; CPU OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5",  
            "Android N1"=>"Mozilla/5.0 (Linux; U; Android 2.3.7; en-us; Nexus One Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",  
            "Android QQ浏览器 For android"=>"MQQBrowser/26 Mozilla/5.0 (Linux; U; Android 2.3.7; zh-cn; MB200 Build/GRJ22; CyanogenMod-7) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",  
            "Android Opera Mobile"=>"Opera/9.80 (Android 2.3.4; Linux; Opera Mobi/build-1107180945; U; en-GB) Presto/2.8.149 Version/11.10",  
            "Android Pad Moto Xoom"=>"Mozilla/5.0 (Linux; U; Android 3.0; en-us; Xoom Build/HRI39) AppleWebKit/534.13 (KHTML, like Gecko) Version/4.0 Safari/534.13",  
            "BlackBerry"=>"Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en) AppleWebKit/534.1+ (KHTML, like Gecko) Version/6.0.0.337 Mobile Safari/534.1+",  
            "WebOS HP Touchpad"=>"Mozilla/5.0 (hp-tablet; Linux; hpwOS/3.0.0; U; en-US) AppleWebKit/534.6 (KHTML, like Gecko) wOSBrowser/233.70 Safari/534.6 TouchPad/1.0",  
            "UC标准"=>"NOKIA5700/ UCWEB7.0.2.37/28/999",  
            "UCOpenwave"=>"Openwave/ UCWEB7.0.2.37/28/999",  
            "UC Opera"=>"Mozilla/4.0 (compatible; MSIE 6.0; ) Opera/UCWEB7.0.2.37/28/999",  
            "微信内置浏览器"=>"Mozilla/5.0 (Linux; Android 6.0; 1503-M02 Build/MRA58K) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/37.0.0.0 Mobile MQQBrowser/6.2 TBS/036558 Safari/537.36 MicroMessenger/6.3.25.861 NetType/WIFI Language/zh_CN",  
        ];
        
        $useragent = $agent_arr[array_rand($agent_arr,1)];
        return $useragent;
    }
}
