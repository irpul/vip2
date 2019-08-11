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
	$pluginData[irpul][field][config][1][title] = 'پین';
	$pluginData[irpul][field][config][1][name] = 'merchant';
	$pluginData[irpul][field][config][2][title] = 'عنوان خرید';
	$pluginData[irpul][field][config][2][name] = 'title';
	
	//-- تابع انتقال به دروازه پرداخت
	function gateway__irpul($data)
	{
		global $config,$db,$smarty,$_POST;
		//include_once('lib/nusoap.php');
		$merchantID 	= trim($data[merchant]);
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
		
		$parameters = array
		(
			'plugin'		=> 'VIP_Final',
			'webgate_id' 	=> $merchantID,
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
		);
		//print_r($parameters);exit;
		try {
			$client = new SoapClient('https://irpul.ir/webservice.php?wsdl' , array('soap_version'=>'SOAP_1_2','cache_wsdl'=>WSDL_CACHE_NONE ,'encoding'=>'UTF-8'));
			$res = $client->Payment($parameters);
		}catch (Exception $e) { echo 'Error'. $e->getMessage();  }
		
		if( $res['res_code']===1 && is_numeric($res['res_code']) ){
			$go = $res['url'];
			$update[payment_rand]	= $res['tran_id'];
			
			$sql = $db->prepare("UPDATE `payment` SET `payment_rand` = ? WHERE `payment_rand` = ? LIMIT 1");
			$sql->execute(array($update[payment_rand],$invoice_id));
			redirect_to($go);
		}
		else{
			$data[title] = 'خطای سیستمي';
			$data[message] = '<font color="red">خطا در اتصال به ایرپول</font>'.$res['res_code'].'<br /><a href="index.php" class="button">بازگشت</a>';
			
			throw new Exception($data[message]);
		}
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
				
				//بررسی قبلا پرداخت نشده باشد
				$sql 		= 'SELECT * FROM `payment` WHERE `payment_rand` = ? LIMIT 1;';
				$sql = $db->prepare($sql);
				$sql->execute(array($tran_id));

				$payment 	= $sql->fetch();
				$amount		= round($payment[payment_amount]);
				
				if ($payment[payment_status] == 1){
					if($status == 'paid')	
					{
						$parameters = array
						(
							'webgate_id'	=> $data[merchant],
							'tran_id' 		=> $tran_id,
							'amount'	 	=> $amount,
						);
						try {
							$client = new SoapClient('https://irpul.ir/webservice.php?wsdl' , array('soap_version'=>'SOAP_1_2','cache_wsdl'=>WSDL_CACHE_NONE ,'encoding'=>'UTF-8'));
							$result = $client->PaymentVerification($parameters);
						}catch (Exception $e) { echo 'Error'. $e->getMessage();  }
						if($result == '1'){
							//-- آماده کردن خروجی
							$output[status]		= 1;
							$output[res_num]	= $refcode;
							$output[ref_num]	= $order_id;
							$output[payment_id]	= $payment[payment_id];
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
		
		/*
		$au 	= $_GET['au'];
		$ref_id = $_GET['order_id'];
		if (strlen($au)>8)
		{
			//include_once('lib/nusoap.php');
			$merchantID = $data[merchant];
			
			$sql 		= 'SELECT * FROM `payment` WHERE `payment_rand` = ? LIMIT 1;';
			$sql = $db->prepare($sql);
			$sql->execute(array($au));

			$payment 	= $sql->fetch();
			
			$amount		= round($payment[payment_amount]/10);
			
			
			//$client = new nusoap_client('https://irpul.ir/webservice.php?wsdl', 'wsdl');
			//$res = $client->call("verify", array($merchantID, $au, $amount));
			
			
			
			if ($payment[payment_status] == 1)
			{
				if ($res == 1)//-- موفقیت آمیز
				{
					//-- آماده کردن خروجی
					$output[status]		= 1;
					$output[res_num]	= $au;
					$output[ref_num]	= $ref_id;
					$output[payment_id]	= $payment[payment_id];
				}
				else
				{
					//-- در تایید پرداخت مشکلی به‌وجود آمده است‌
					$output[status]	= 0;
					$output[message]= 'پرداخت انجام نشده است .';
				}
			}
			else
			{
				//-- قبلا پرداخت شده است‌
				$output[status]	= 0;
				$output[message]= 'سفارش قبلا پرداخت شده است.';
			}
		}*/
		
		return $output;
	}