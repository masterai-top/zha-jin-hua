<?php defined('SYSPATH') or die('No direct script access.');
require_once('config.php');

class Utils {
    public static function validateSignature($order_id, $game_id, $server_id, $uid, $pay_way,
        $amount, $callback_info, $order_status, $failed_desc, $sign)
    {
        $s = md5($order_id.$game_id.$server_id.$uid.$pay_way.$amount.
            $callback_info.$order_status.$failed_desc.S1_KEY);

        return strtolower($s) == strtolower($sign);
    }
    
    public static function cUrlSendRequest($accid, $orderid)
    {
    	$key = MD5_KEY;
    	$stamp = time();
    	$flag =strtoupper(md5('account_id'.$accid.'order_id'.$orderid.'time'.$stamp));
    	echo $flag;
    	$Url = 'http://192.168.44.53:7799/charge?account_id='.$accid.'&order_id='.$orderid.'&time='.$stamp.'&flag='.$flag;
    	echo $Url;
    	$ch = curl_init();
    	$timeout = 5;
    	curl_setopt ($ch, CURLOPT_URL, $Url);
    	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    	$file_contents = curl_exec($ch);
    	curl_close($ch);
    	
    	echo $file_contents;
    }
}
?>