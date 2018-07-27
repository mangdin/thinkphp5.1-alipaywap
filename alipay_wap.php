<?php
/**
 * Created by 合肥芒丁数据系统有限责任公司 www.mangdin.com.
 * User: Administrator
 * Date: 2018/7/27
 * Time: 10:27
 */
return [
    'app_id' => "", //应用ID,您的APPID。
    'merchant_private_key' => "",   //商户私钥，您的原始格式RSA私钥
    'notify_url' => "http://".$_SERVER['HTTP_HOST']."/index.php/Home/Alipay/notifyurl",    //异步通知地址
    'return_url' => "http://".$_SERVER['HTTP_HOST']."/index.php/Home/Alipay/returnurl",     //同步跳转
    'charset' => "UTF-8",   //编码格式
    'sign_type'=>"RSA2",    //签名方式
    'gatewayUrl' => "https://openapi.alipay.com/gateway.do",    //支付宝网关
    'alipay_public_key' => "",  //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
];