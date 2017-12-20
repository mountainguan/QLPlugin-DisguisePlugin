# QueryList V4 Plugin - DisguisePlugin
Make a disguise header for get/post request.

# QueryList V4 插件 - 混淆插件
给Post/Get方法加上带有混淆信息的headers。

# Installation
```
composer require mountainguan/ql-plugin-disguise
```
## API
-  **disguiseIp($otherArgs,$ip)**: Add custom or random headers -- disguise ip address for QueryList's otherArgs,return **QueryList**
-  **disguiseUa($otherArgs,$ua)**:  Add custom or random headers -- disguise UserAgent for QueryList's otherArgs,return **QueryList**

## Installation options

 **QueryList::use(DisguisePlugin::class,$opt1,$opt2)**
- **$opt1**:`disguiseIp` function alias.
- **$opt2**:`disguiseUa` function alias.

## Usage

- Installation Plugin

```php
use QL\QueryList;
use QL\Ext\DisguisePlugin;

$ql = QueryList::getInstance();
$ql->use(DisguisePlugin::class);
//or Custom function name
$ql->use(DisguisePlugin::class,'disguiseIp','disguiseUa');
```

- Only disguise IP in random way.

```
print_r($ql->disguiseIp()->disguise_headers);
//or custom
print_r($ql->disguiseIp([],'66.248.172.185')->disguise_headers);
```
Out:
```
Array ( 
	[headers] => Array ( 
    	[X-Forwarded-For] => 66.248.172.185 
        [Proxy-Client-IP] => 66.248.172.185 
        [WL-Proxy-Client-IP] => 66.248.172.185 
        [HTTP_CLIENT_IP] => 66.248.172.185 
        [X-Real-IP] => 66.248.172.185 
        )
)
```

- Only disguise UserAgent in random way.

```
print_r($ql->disguiseUa()->disguise_headers);
//or custom
print_r($ql->disguiseUa([],'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11')->disguise_headers);
```
Out:
```
Array 
( 
	[headers] => Array ( 
    	[User-Agent] => Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11 
        )
)
```

- Using both two functions in random way.

```
print_r($ql->disguiseIp()->disguiseUa()->disguise_headers);
```
Out:
```
Array ( 
	[headers] => Array ( 
    	[X-Forwarded-For] => 222.122.96.204 
        [Proxy-Client-IP] => 222.122.96.204 
        [WL-Proxy-Client-IP] => 222.122.96.204 
        [HTTP_CLIENT_IP] => 222.122.96.204 
        [X-Real-IP] => 222.122.96.204 
        [User-Agent] => Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0) 
        ) 
)
```

- Using post/get request's otherArgs param can make more perfect disguise.

```
print_r($ql->disguiseIp(
	array('headers'=>[	'Accept'=>'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
	'Accept-Encoding'=>'gzip, deflate, br',
	'Accept-Language'=>'en-US,en;q=0.9,zh-CN;q=0.8,zh;q=0.7',
	'Connection'=>'keep-alive'])
    )->disguiseUa()->disguise_headers);
```
Attention: otherArgs param must be like Array('headers'=>[...]).

Out:
```
Array ( 
	[headers] => Array ( 
    	[Accept] => text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8 
        [Accept-Encoding] => gzip, deflate, br 
        [Accept-Language] => en-US,en;q=0.9,zh-CN;q=0.8,zh;q=0.7
        [Connection] => keep-alive 
        [X-Forwarded-For] => 60.169.94.187
        [Proxy-Client-IP] => 60.169.94.187
        [WL-Proxy-Client-IP] => 60.169.94.187
        [HTTP_CLIENT_IP] => 60.169.94.187
        [X-Real-IP] => 60.169.94.187
        [User-Agent] => Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; .NET4.0C; .NET4.0E; .NET CLR 2.0.50727; .NET CLR 3.0.30729; .NET CLR 3.5.30729; InfoPath.3; rv:11.0) like Gecko 
        ) 
)
```