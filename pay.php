<?php
	define('SYSPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
    header("Content-Type:text/html;charset=utf-8");

	require_once('config.php');
	require_once('DynamicSqlHelper.php');
	require_once('Utils.php');
	require_once('SocketClient.php');
	
	define('PARA_IS_NOT_COMPLETE','-1');//提交参数不全
	define('AUTHENTICSTION_FAILED','-2');//签名验证失败
	define('USER_DOES_NOT_EXIST','-3');//用户不存在
	define('OVER_TIME','-4');//请求超时
	define('CHARGE_FAILE','-5');//充值失败
	define('SUCCESS','1');//成功
	define('DUPLICATE_ORDER','2');//订单重复
	
	$rate = 10;    // 10:1

	if(!isset($_GET['order_id']) ||
		!isset($_GET['game_id']) ||
		!isset($_GET['server_id']) ||
		!isset($_GET['uid']) ||
		!isset($_GET['pay_way']) ||
		!isset($_GET['amount']) ||
		!isset($_GET['callback_info']) ||
		!isset($_GET['order_status']) ||
		!isset($_GET['failed_desc']) ||
		!isset($_GET['sign']))
    {
        echo PARA_IS_NOT_COMPLETE;
        return;
    }

    $order_id = $_GET['order_id'];
    $game_id = $_GET['game_id'];
    $server_id = $_GET['server_id'];
    $uid = $_GET['uid'];
    $pay_way = $_GET['pay_way'];
    $amount = $_GET['amount'];
    $callback_info = $_GET['callback_info'];
    $order_status = $_GET['order_status'];
    $failed_desc = $_GET['failed_desc'];
    $sign = $_GET['sign'];

    $gold = $amount * $rate;

    if($gold <= 0 || $order_status != "S"){
        echo PARA_IS_NOT_COMPLETE;
        return;
    }

    $ret = Utils::validateSignature($order_id, $game_id, $server_id,
        $uid, $pay_way, $amount, $callback_info, $order_status,
        $failed_desc, $sign);
    if(!$ret){
        echo AUTHENTICSTION_FAILED;
        return;
    }

    $db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
    $db->set_charset('utf-8');

    $sqlCheck = "SELECT id FROM player WHERE account_id = '".$uid."'";
    $r_check = sql_fetch_one($db, $sqlCheck);
    if($r_check == "")
    {
        echo USER_DOES_NOT_EXIST;
        return;
    }

    $sql_count = "select count(1) from charge where order_id = '$order_id'";
    $count = sql_fetch_one_cell($db, $sql_count);
    if($count > 0)
    {
        echo DUPLICATE_ORDER;
        return;
    }

//     if(PAY_ACTIVITY == 1)
//     {
//         if($amount >= 300)
//             $gold = $gold + round($gold * 0.15);
//         else if($amount >= 100)
//             $gold = $gold + round($gold * 0.1);
//     }
    $sql_insert = "insert into charge (order_id, game_id, server_id, account_id, pay_way, amount, gold, handle_status) ";
    $sql_insert .= "values('$order_id', $game_id, $server_id, '$uid', $pay_way, $amount, $gold, 0)"; //handle_status:0未处理
    if(!$db->query($sql_insert))
    {
        echo CHARGE_FAILE;
        return;
    }
  
    Utils::cUrlSendRequest($uid, $order_id);
    echo SUCCESS;
?>