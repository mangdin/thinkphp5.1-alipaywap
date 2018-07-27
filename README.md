# thinkphp5.1-alipaywap

thinkphp5.1 支付宝 手机H5 支付

将根目录的alipay_wap.php拷贝到config目录

调用支付的控制器代码:
<pre>
  /**
     * @param $orderid  //订单ID
     * @return \think\response\Json
     * @throws \think\Exception
     */
  public function alipaywap($orderid){
        if (!empty($orderid)&& trim($orderid)!=""){
            $config = \think\facade\Config::get('alipay_wap.'); //支付宝 手机浏览器支付 配置参数

            $out_trade_no = $orderid;   //商户订单号，商户网站订单系统中唯一订单号，必填
            $subject = '这是一个测试订单';    //订单名称，必填
            $total_amount = 1;  //付款金额，必填
            $body = '这是一个支付测试订单';  //商品描述，可空
            $timeout_express="1m";  //超时时间 默认即可
            $payRequestBuilder = new \mangdin\alipaywap\AlipayTradeWapPayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setOutTradeNo($out_trade_no);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setTimeExpress($timeout_express);
            $payResponse = new \mangdin\alipaywap\AlipayTradeService($config);
            $payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);
        }else{
            return json(['code'=>500,'msg'=>'请求错误,没有订单编号']);
        }
    }
</pre>

支付回调控制器代码：
<pre>
/**
     * return_url接收页面
     */
    public function alipay_return(){
        $config=\think\facade\Config::get('alipay_wap.');
        $arr=input('get.');
        $alipaySevice=new \mangdin\alipaywap\AlipayTradeService($config);
        // 验证支付数据
        $result = $alipaySevice->check($arr);
        if($result){
            //验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

            //商户订单号
            $out_trade_no = htmlspecialchars($_GET['out_trade_no']);

            //支付宝交易号
            $trade_no = htmlspecialchars($_GET['trade_no']);

            echo "验证成功<br />外部订单号：".$out_trade_no;

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }else{
            $this->success('支付失败',url('index/user/order'));
        }
    }

    /**
     * notify_url接收页面
     */
    public function alipay_notify(){
        // 引入支付宝
        $config=\think\facade\Config::get('alipay_wap.');
        $arr=input('post.');
        $alipaySevice = new \mangdin\alipaywap\AlipayTradeService($config);
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代
            
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

            
            $out_trade_no = $_POST['out_trade_no']; //商户订单号
            $trade_no = $_POST['trade_no']; //支付宝交易号
            $trade_status = $_POST['trade_status']; //交易状态


            if($_POST['trade_status'] == 'TRADE_FINISHED') {

                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序			
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
                
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            echo "success";		//请不要修改或删除
        }else {
            //验证失败
            echo "fail";	//请不要修改或删除
        }
    }
</pre>