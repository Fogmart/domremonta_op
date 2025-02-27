<?php
class ControllerExtensionPaymentYamodule extends Controller
{
	const MONEY_URL = "https://money.yandex.ru";
   const SP_MONEY_URL = "https://money.yandex.ru";
	
   const PREFIX_DEBUG = "";
   const ORDERNUMBER = "orderNumber";
	
	public $error;
	public $errors;

	public function inside() {
		die('ok');
	}

	public function index() {
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$this->language->load('extension/payment/yamodule');
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['p2p_action'] = $this->url->link('extension/payment/yamodule/yaredirect', '', true);
		if ($this->config->get('ya_kassa_test'))
			$data['kassa_action'] = 'https://demomoney.yandex.ru/eshop.xml';
		else
			$data['kassa_action'] = 'https://money.yandex.ru/eshop.xml';

		$data['order_id'] = self::PREFIX_DEBUG.$this->session->data['order_id'];
		$data['p2p_mode'] = $this->config->get('ya_p2p_active');
		$data['kassa_mode'] = $this->config->get('ya_kassa_active');
		$data['shop_id'] = $this->config->get('ya_kassa_sid');
		$data['scid'] = $this->config->get('ya_kassa_scid');
		$data['kassa_paymode'] = $this->config->get('ya_kassa_paymode');
		$data['kassa_paylogo'] = $this->config->get('ya_kassa_paylogo');
		$data['customerNumber'] = $order_info['email'];
		$data['shopSuccessURL'] = $this->url->link('checkout/success', '', true);
		$data['shopFailURL'] = $this->url->link('checkout/failure', '', true);
		$data['email'] = $order_info['email'];
		$data['phone'] = preg_replace("/[-+()]/",'',$order_info['telephone']);
		$data['comment'] = $order_info['comment'];
		$data['sum'] = number_format($this->currency->convert($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false), $order_info['currency_code'], 'RUB'), 2, '.', '');
		foreach (array('ym','cards','cash','mobile','wm','sber','alfa','pb','ma','qp','qw') as $pName) {
            $data['method_'.$pName] = $this->config->get('ya_kassa_'.$pName);
            $data['method_'.$pName.'_text'] = $this->language->get('text_method_'.$pName);
        }
        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $data['imageurl'] = $this->config->get('config_ssl') . 'image/';
        } else {
            $data['imageurl'] = $this->config->get('config_url') . 'image/';
        }
		$data['method_label'] =  $this->language->get('text_method');
		$data['order_text'] =  $this->language->get('text_order');
		$end_tpl = (version_compare(VERSION, "2.2.0", '>='))?"":".tpl";
		$begin_tpl = (version_compare(VERSION, "2.2.0", '>='))?"":"default/template/";
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/yamodule'.$end_tpl)) {
			return $this->load->view($this->config->get('config_template') . '/template/extension/payment/yamodule'.$end_tpl, $data);
		} else {
			return $this->load->view($begin_tpl.'extension/payment/yamodule'.$end_tpl, $data);
		}
	}
	public function confirm(){
		if ($this->session->data['payment_method']['code'] == 'yamodule') {
			$this->load->model('checkout/order');
			if ($this->config->get('ya_kassa_create_order')=='1') $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('config_order_status_id'), '', true);
			if ($this->config->get('ya_kassa_cart_reset')=='1') $this->cart->clear();
		}
	}
	public static function log_save($logtext)
	{
		$error_log = new Log('error.log');
		$error_log->write($logtext.PHP_EOL);
		$error_log = null;
	}

	public function makeOrder($order_id, $red = true)
	{
		if ($order_id)
		{
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($order_id);
			if ($order_info['order_status_id'] != $this->config->get('ya_p2p_os'))
				$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('ya_p2p_os'), '', true);
			if ($red)
				$this->response->redirect($this->url->link('checkout/success', '', true));
		}
	}

	public static function buildObtainTokenUrl($client_id, $redirect_uri, $scope)
	{
        $params = sprintf(
            "client_id=%s&response_type=%s&redirect_uri=%s&scope=%s",
            $client_id, "code", $redirect_uri, implode(" ", $scope)
		);

        return sprintf("%s/oauth/authorize?%s", self::SP_MONEY_URL, $params);
    }

	public function d($d)
	{
		echo '<pre>';
		print_r($d);
		echo '</pre>';
		die();
	}

	public function yaredirect()
	{
		if (isset($_POST['payment-type']) && !empty($_POST['payment-type']))
		{
			if ($_POST['payment-type'] == 'wallet')
			{
				$this->session->data['p2p_type'] = $_POST['payment-type'];
				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
				// $this->d($total);
				$limit = number_format($this->currency->convert($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false), $order_info['currency_code'], 'RUB'), 2, '.', '');
				$scope = array(
					"payment.to-account(\"".$this->config->get('ya_p2p_number')."\",\"account\").limit(,".$limit.")",
					"money-source(\"wallet\",\"card\")"
				);
				
				$auth_url = $this->buildObtainTokenUrl($this->config->get('ya_p2p_idapp'), $this->url->link('extension/payment/yamodule/insidewallet', '', true), $scope);
				$this->response->redirect($auth_url);
			}
			elseif ($_POST['payment-type'] == 'card')
			{
				$this->insidecard();
			}
			else
				$this->response->redirect($this->url->link('checkout/checkout', '', true));
		}
		else
			$this->response->redirect($this->url->link('checkout/checkout', '', true));
	}

	public function getAccessToken($client_id, $code, $redirect_uri, $client_secret = null)
	{
        $full_url = self::SP_MONEY_URL . "/oauth/token";
        $result = $this->SendCurl($full_url, array(), array(
            "code" => $code,
            "client_id" => $client_id,
            "grant_type" => "authorization_code",
            "redirect_uri" => $redirect_uri,
            "client_secret" => $client_secret
        ));

        return json_decode($result->body);
    }

	public function successcard()
	{
		if ($this->session->data['cps_context_id'] == $_GET['cps_context_id'])
		{
			$this->load->model('checkout/order');
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('ya_p2p_os'), '', true);
			$this->session->data['cps_context_id'] = '';
			$this->response->redirect($this->url->link('checkout/success', '', true));
		}
	}

	public function insidecard()
	{
		$instance = $this->sendRequest('/api/instance-id', array('client_id' => $this->config->get('ya_p2p_idapp')));
		if($instance->status == 'success')
		{
			$instance_id = $instance->instance_id;
			$message = 'payment to order #'.$this->session->data['order_id'];
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			$total = number_format($this->currency->convert($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false), $order_info['currency_code'], 'RUB'), 2, '.', '');
			$payment_options = array(
				'pattern_id' => 'p2p',
				'to' => $this->config->get('ya_p2p_number'),
				'amount_due' => $total,
				'comment' => trim($message),
				'message' => trim($message),
				'instance_id' => $instance_id,
				'label' => $this->session->data['order_id']
			);

			$response = $this->sendRequest('/api/request-external-payment', $payment_options);
			if($response->status == 'success')
			{
				$this->error = false;
				if($this->config->get('ya_p2p_log'))
					$this->log_save('card_redirect:  request success');
				$request_id = $response->request_id;
				$this->session->data['ya_encrypt_CRequestId'] = urlencode(base64_encode($request_id));
				
				do{
					$process_options = array(
						"request_id" => $request_id,
						'instance_id' => $instance_id,
						'ext_auth_success_uri' => $this->url->link('extension/payment/yamodule/successcard', '', true),
						'ext_auth_fail_uri' => $this->url->link('checkout/failure', '', true)
					);	
					
					$result = $this->sendRequest('/api/process-external-payment', $process_options);					
					if($result->status == "in_progress") {
						sleep(1);
					}
				}while ($result->status == "in_progress");

				if($result->status == 'success')
				{
					$this->load->model('checkout/order');
					$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('ya_p2p_os'), '', true);
					$this->response->redirect($this->url->link('checkout/success', '', true));
				}
				elseif($result->status == 'ext_auth_required')
				{
					$this->session->data['cps_context_id'] = $result->acs_params->cps_context_id;
					$url = sprintf("%s?%s", $result->acs_uri, http_build_query($result->acs_params));
					if($this->config->get('ya_p2p_log'))
					{
						$this->log_save('card_redirect:  redirect to '.$url);
						$this->log_save('card_redirect:  params 3d '.serialize($result));
					}
					$this->response->redirect($url);
					exit;
				}
				elseif($result->status == 'refused')
				{
					$this->errors[] = $this->descriptionError($resp->error) ? $this->descriptionError($resp->error) : $result->error;
					if($this->config->get('ya_p2p_log'))
						$this->log_save('card_redirect:refused '.$this->descriptionError($resp->error) ? $this->module->descriptionError($resp->error) : $result->error);
					$this->error = true;
				}
			}
		}
	}

	public function insidewallet()
	{
		$code = $this->request->get['code'];
		if (empty($code))
			$this->response->redirect($this->url->link('extension/payment/yamodule/yaredirect', '', true));
		else
		{
			$response = $this->getAccessToken($this->config->get('ya_p2p_idapp'), $code, $this->url->link('extension/payment/yamodule/yaredirect', '', true), $this->config->get('ya_p2p_pw'));
			$error = '';
			$token = '';
			if (isset($response->access_token))
				$token = $response->access_token;
			else
				$error = $response->error;
			
			if (!empty($token))
			{
				$message = 'payment to order #'.$this->session->data['order_id'];
				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
				$total = number_format($this->currency->convert($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false), $order_info['currency_code'], 'RUB'), 2, '.', '');
				$rarray = array(
					'pattern_id' => 'p2p',
					'to' => $this->config->get('ya_p2p_number'),
					'amount_due' => $total,
					'comment' => trim($message),
					'message' => trim($message),
					'label' => $this->session->data['order_id']
				);

				$request_payment = $this->sendRequest('/api/request-payment', $rarray, $token);
				switch($request_payment->status)
				{
					case 'success':
						if($this->config->get('ya_p2p_log'))
							$this->log_save('wallet_redirect: request success');
						$this->session->data['ya_encrypt_token'] = urlencode(base64_encode($token));
						$this->session->data['ya_encrypt_RequestId'] = urlencode(base64_encode($request_payment->request_id));
						do{
							$array_p = array("request_id" => $request_payment->request_id);
							$process_payment = $this->sendRequest("/api/process-payment", $array_p, $token);
							
							if($process_payment->status == "in_progress") {
								sleep(1);
							}
						}while ($process_payment->status == "in_progress");

						$this->updateStatus($process_payment);
						$this->error = false;
						break;
					case 'refused':
						if($this->config->get('ya_p2p_log'))
							$this->log_save('wallet_redirect: request refused');
						$this->errors[] = $this->descriptionError($request_payment->error);
						if($this->config->get('ya_p2p_log'))
							$this->log_save('wallet_redirect: refused '.$this->descriptionError($request_payment->error));
							$this->error = true;
							// $this->d($this->descriptionError($request_payment->error));
							$this->response->redirect($this->url->link('checkout/failure', '', true));
						break;
					case 'hold_for_pickup':
						if($this->config->get('ya_p2p_log'))
							$this->log_save('wallet_redirect: hold_for_pickup');
						$this->errors[] = 'Получатель перевода не найден, будет отправлен перевод до востребования. Успешное выполнение.';
						if($this->config->get('ya_p2p_log'))
							$this->log_save('wallet_redirect: hold_for_pickup Получатель перевода не найден, будет отправлен перевод до востребования. Успешное выполнение.');
							$this->error = true;
						break;
						
				}

				$this->load->language('extension/payment/yamodule');
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
				$data['text_back'] = $this->language->get('text_back');
				if (!$this->error)
					$this->response->redirect($this->url->link('checkout/success', '', true));
				else
				{
					// $this->errors[] = $this->descriptionError('invalid_request');
					// $this->errors[] = $this->descriptionError('invalid_scope');
					// $data['error'] = $this->errors;
					// if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/wallet_error.tpl')) {
						// $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/wallet_error.tpl', $data));
					// } else {
						// $this->response->setOutput($this->load->view('default/template/payment/wallet_error.tpl', $data));
					// }
					$data['shopFailURL'] = $this->url->link('checkout/failure', '', true);
					$this->response->redirect($this->url->link('checkout/failure', '', true));
				}
			}
			else
				$this->response->redirect($this->url->link('extension/payment/yamodule/yaredirect', '', true));
		}
	}

	public function callback()
	{
		$data = $_POST;
		if($this->config->get('ya_kassa_log'))
			$this->log_save('callback:  request '.serialize($_REQUEST));

		$this->load->model('yamodel/yamoney');
		$password = $this->config->get('ya_kassa_pw');
		//$this->model_yamodel_yamoney->password2 = $this->config->get('ya_p2p_pw');
		$shopid = $this->config->get('ya_kassa_sid');

		$order_id = isset($data[self::ORDERNUMBER]) ? (int) substr($data[self::ORDERNUMBER], strlen(self::PREFIX_DEBUG), strlen($data[self::ORDERNUMBER])) : 0;
		if ($this->config->get('ya_kassa_active') && isset($data['action']) && !empty($data['action'])){
			$this->log_save('callback:  orderid='.$order_id);
			if($this->model_yamodel_yamoney->checkSign($data, $password, $shopid, true)){
				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($order_id);
                $sum = number_format($this->currency->convert($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false), $order_info['currency_code'], 'RUB'), 2);

                if (number_format($data['orderSumAmount'], 2)== $sum){
					if ($data['action'] == 'paymentAviso'){
						if ($order_id > 0)	$this->makeOrder($order_id, false);
					}
					$this->model_yamodel_yamoney->sendCode($data, $shopid, '0', "ok");
				}else{
					$this->model_yamodel_yamoney->sendCode($data, $shopid, '100', "Error total amount");
				}
			}
		}else{
			exit("You aren't Yandex.Money.");
		}
	}

	public function descriptionError($error)
	{
		$error_array = array(
			'invalid_request' => 'Your request is missing required parameters or settings are incorrect or invalid values',
			'invalid_scope' => 'The scope parameter is missing or has an invalid value or a logical contradiction',
			'unauthorized_client' => 'Invalid parameter client_id, or the application does not have the right to request authorization (such as its client_id blocked Yandex.Money)',
			'access_denied' => 'Has declined a request authorization application',
			'invalid_grant' => 'The issue access_token denied. Issued a temporary token is not Google search or expired, or on the temporary token is issued access_token (second request authorization token with the same time token)',
			'illegal_params' => 'Required payment options are not available or have invalid values.',
			'illegal_param_label' => 'Invalid parameter value label',
			'phone_unknown' => 'A phone number is not associated with a user account or payee',
			'payment_refused' => 'Магазин отказал в приеме платежа (например, пользователь пытался заплатить за товар, которого нет в магазине)',
			'limit_exceeded' => 'Exceeded one of the limits on operations: on the amount of the transaction for authorization token issued; transaction amount for the period of time for the token issued by the authorization; Yandeks.Deneg restrictions for different types of operations.',
			'authorization_reject' => 'In payment authorization is denied. Possible reasons are: transaction with the current parameters is not available to the user; person does not accept the Agreement on the use of the service "shops".',
			'contract_not_found' => 'None exhibited a contract with a given request_id',
			'not_enough_funds' => 'Insufficient funds in the account of the payer. Need to recharge and carry out a new delivery',
			'not-enough-funds' => 'Insufficient funds in the account of the payer. Need to recharge and carry out a new delivery',
			'money_source_not_available' => 'The requested method of payment (money_source) is not available for this payment',
			'illegal_param_csc' => 'tsutstvuet or an invalid parameter value cs',
			'payment_refused' => 'Shop for whatever reason, refused to accept payment.'
		);
		if(array_key_exists($error,$error_array))
			$return = $error_array[$error];
		else
			$return = $error;
		return $return;
	}
	
	public function SendCurl($url, $headers, $params){
		$curl = curl_init($url);
		if(isset($headers['Authorization'])){
			$token = $headers['Authorization'];
			$headers = array();
			$headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
			$headers[] = 'Authorization: '.$token;
		}
		$params = http_build_query($params);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_USERAGENT, 'yamolib-php');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 80);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $rbody = curl_exec($curl);
        $errno = curl_errno($curl);
        $error = curl_error($curl);
        $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		// self::de(curl_getinfo($curl, CURLINFO_HEADER_OUT), false);
        curl_close($curl);
		$result = new stdClass();
		$result->status_code = $rcode;
		$result->body = $rbody;
		return $result;
	}
	
	public function updateStatus(&$resp)
	{
		if ($resp->status == 'success')
		{
			$this->makeOrder($this->session->data['order_id'], false);
			$this->session->data['ya_encrypt_token'] = '';
			$this->session->data['ya_encrypt_RequestId'] = '';
			if($this->config->get('ya_p2p_log'))
				$this->log_save('wallet_redirect: #'.$this->session->data['order_id'].' Order success');
			$this->response->redirect($this->url->link('checkout/success', '', true));
		}
		else
		{ 
			$this->errors[] = $this->descriptionError($resp->error);
			if($this->config->get('ya_p2p_log'))
				$this->log_save('wallet_redirect: Error '.$this->descriptionError($resp->error));
			$this->error = true;
		}
    }

	public function sendRequest($url, $options = array(), $access_token = null)
	{
        $full_url= self::MONEY_URL . $url;
        if($access_token != null) {
            $headers = array(
                "Authorization" => sprintf("Bearer %s", $access_token),
            );
        } 
        else {
            $headers = array();
        }
        $result = $this->SendCurl($full_url, $headers, $options);
        return json_decode($result->body);
    }
}