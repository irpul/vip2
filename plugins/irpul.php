<?php
/*
  Virtual Freer
  http://freer.ir/virtual

  Copyright (c) 2011 Mohammad Hossein Beyram, freer.ir

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v3 (http://www.gnu.org/licenses/gpl-3.0.html)
  as published by the Free Software Foundation.
*/
	//-- اطلاعات کلی پلاگین
	$pluginData[irpul][type] = 'payment';
	$pluginData[irpul][name] = 'ایرپول';
	$pluginData[irpul][uniq] = 'irpul';
	$pluginData[irpul][description] = 'مخصوص پرداخت با دروازه پرداخت <a href="https://irpul.ir">ایرپول</a>';
	$pluginData[irpul][author][name] = 'IrPul';
	$pluginData[irpul][author][url] = 'https://irpul.ir';
	$pluginData[irpul][author][email] = 'info@irpul.ir';
	
	//-- فیلدهای تنظیمات پلاگین
	$pluginData[irpul][field][config][1][title] = 'توکن درگاه';
	$pluginData[irpul][field][config][1][name] = 'token';
	$pluginData[irpul][field][config][2][title] = 'عنوان خرید';
	$pluginData[irpul][field][config][2][name] = 'title';
	
	//-- تابع انتقال به دروازه پرداخت
	function gateway__irpul($data){
		global $config,$db,$smarty,$_POST;
		//include_once('lib/nusoap.php');
		$token 			= trim($data[token]);
		$amount 		= round($data[amount]);
		$invoice_id		= $data[invoice_id];
		$callBackUrl 	= $data[callback];
		
		$username		= $_POST['username'];
		$mobile			= $_POST['number'];
		$email			= $_POST['email'];
		$category 		= $_POST['category']; 
		
		$sql = $db->prepare("SELECT * FROM category WHERE id = ?");
		$sql->execute(array($category));
		$sql = $sql->fetch();
		
		$cat_day		= $sql['day'];
		$product 		= $sql['title'];
		$description 	=  "$product به مدت $cat_day روز";
		
		$parameters = array(
			'method'		=> 'payment',
			'order_id'		=> $invoice_id,
			'product'		=> $product,
			'payer_name'	=> $username ,
			'phone' 		=> '',
			'mobile' 		=> $mobile,
			'email' 		=> $email,
			'amount' 		=> $amount,
			'callback_url' 	=> $callBackUrl,
			'address' 		=> '',
			'description' 	=> $description,
			'test_mode' 	=> true,
		);
		//print_r($parameters);exit;
		$result 	= post_data('https://irpul.ir/ws.php', $parameters, $token );

		if( isset($result['http_code']) ){
			$data =  json_decode($result['data'],true);

			if( isset($data['code']) && $data['code'] === 1){
				$go = $data['url'];
				
				$update[payment_rand]	= $data['trans_id'];
				$sql = $db->prepare("UPDATE `payment` SET `payment_rand` = ? WHERE `payment_rand` = ? LIMIT 1");
				$sql->execute(array($update[payment_rand],$invoice_id));
				
				redirect_to($go);
			}
			else{
				$data[title] = 'خطای سیستمي';
				$data[message] = '<font color="red">خطا در اتصال به ایرپول</font>'.$data['code'] . ' ' . $data['status'].'<br /><a href="index.php" class="button">بازگشت</a>';
				throw new Exception($data[message]);
			}
		}else{
			$data[title] = 'خطای سیستمي';
			$data[message] = '<font color="red">خطا در اتصال به ایرپول</font>پاسخی از سرویس دهنده دریافت نشد. لطفا دوباره تلاش نمائید<br /><a href="index.php" class="button">بازگشت</a>';
			throw new Exception($data[message]);
		}
	}
	
	function post_data($url,$params,$token) {
		ini_set('default_socket_timeout', 15);

		$headers = array(
			"Authorization: token= {$token}",
			'Content-type: application/json'
		);

		$handle = curl_init($url);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($handle, CURLOPT_TIMEOUT, 40);

		curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($params) );
		curl_setopt($handle, CURLOPT_HTTPHEADER, $headers );

		$response = curl_exec($handle);
		//error_log('curl response1 : '. print_r($response,true));

		$msg='';
		$http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));

		$status= true;

		if ($response === false) {
			$curl_errno = curl_errno($handle);
			$curl_error = curl_error($handle);
			$msg .= "Curl error $curl_errno: $curl_error";
			$status = false;
		}

		curl_close($handle);//dont move uppder than curl_errno

		if( $http_code == 200 ){
			$msg .= "Request was successfull";
		}
		else{
			$status = false;
			if ($http_code == 400) {
				$status = true;
			}
			elseif ($http_code == 401) {
				$msg .= "Invalid access token provided";
			}
			elseif ($http_code == 502) {
				$msg .= "Bad Gateway";
			}
			elseif ($http_code >= 500) {// do not wat to DDOS server if something goes wrong
				sleep(2);
			}
		}

		$res['http_code'] 	= $http_code;
		$res['status'] 		= $status;
		$res['msg'] 		= $msg;
		$res['data'] 		= $response;

		if(!$status){
			//error_log(print_r($res,true));
		}
		return $res;
	}

	
	function url_decrypt($string){
		$counter = 0;
		$data = str_replace(array('-','_','.'),array('+','/','='),$string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
		$data .= substr('====', $mod4);
		}
		$decrypted = base64_decode($data);
		
		$check = array('tran_id','order_id','amount','refcode','status');
		foreach($check as $str){
			str_replace($str,'',$decrypted,$count);
			if($count > 0){
				$counter++;
			}
		}
		if($counter === 5){
			return array('data'=>$decrypted , 'status'=>true);
		}else{
			return array('data'=>'' , 'status'=>false);
		}
}
	
	//-- تابع بررسی وضعیت پرداخت
	function callback__irpul($data){
		global $db,$get;
		
		if( isset($_GET['irpul_token']) ){
			$irpul_token 	= $_GET['irpul_token'];
			$decrypted 		= url_decrypt( $irpul_token );
			if($decrypted['status']){
				parse_str($decrypted['data'], $ir_output);
				$tran_id 	= $ir_output['tran_id'];
				$order_id 	= $ir_output['order_id'];
				$amount 	= $ir_output['amount'];
				$refcode	= $ir_output['refcode'];
				$status 	= $ir_output['status'];

				/*$sql = $db->prepare("SELECT * FROM payment WHERE payment_rand = ?");
				$sql->execute(array($tran_id));
				$sql = $sql->fetch();*/

				//بررسی قبلا پرداخت نشده باشد
				$sql 		= 'SELECT * FROM `payment` WHERE `payment_rand` = ? LIMIT 1;';
				$sql = $db->prepare($sql);
				$sql->execute(array($tran_id));
				$payment 	= $sql->fetch();

				$amount		= round($payment[payment_amount]);
				
				if ($payment[payment_status] == 1){
					if($status == 'paid'){
						$parameters = array(
							'method'		=> 'verify',
							'trans_id' 		=> $tran_id,
							'amount'	 	=> $amount,
						);
						error_log(print_r($parameters,true));
						
						$token =  $data[token];
						$result =  post_data('https://irpul.ir/ws.php', $parameters, $token );

						if( isset($result['http_code']) ){
							$data =  json_decode($result['data'],true);

							if( isset($data['code']) && $data['code'] === 1){
								//-- آماده کردن خروجی
								$output[status]		= 1;
								$output[res_num]	= $refcode;
								$output[ref_num]	= $order_id;
								$output[payment_id]	= $payment[payment_id];
							}
							else{
								$output[status]	= 0;
								$output[message]= 'خطا در پرداخت. کد خطا: ' . $data['code'] . '<br/>' . $data['status'];
							}
						}else{
							$output[status]	= 0;
							$output[message]= 'پاسخی از سرویس دهنده دریافت نشد. لطفا دوباره تلاش نمائید';
						}
					}else{
						$output[status]	= 0;
						$output[message]= 'تراکنش پرداخت نشده است.';
					}
				}
				else{
					//-- قبلا پرداخت شده است‌
					$output[status]	= 0;
					$output[message]= 'سفارش قبلا پرداخت شده است.';
				}
			}
		}else{
			//-- شماره یکتا اشتباه است
			$output[status]	= 0;
			$output[message]= 'شماره یکتا اشتباه است.';
		}

		return $output;
	}