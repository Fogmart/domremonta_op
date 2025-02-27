<?php
class ControllerYandexbuyOrder extends Controller
{
	public function getRegion($data, $type)
	{
		if (isset($data->type))
		{
			if ($data->type == $type)
				return $data->name;
			else
				return $this->getRegion($data->parent, $type);
		}
		else
			return '';
	}

	public function getShipping($id)
	{
		$this->load->model('extension/extension');
		$results = $this->model_extension_extension->getExtensions('shipping');
		foreach ($results as $res)
			if ($res['extension_id'] == $id)
				return $res['code'].'.'.$res['code'];
		
		return '';
	}
	
	public function accept()
	{
		$sign = $this->config->get('ya_pokupki_stoken');
		$key = isset($_REQUEST['auth-token']) ? $_REQUEST['auth-token'] : '';
		if (strtoupper($sign) != strtoupper($key))
		{
			header('HTTP/1.0 403 Forbidden');
			echo '<h1>Wrong token</h1>';
			exit;
		}
		else
		{
			$json = file_get_contents("php://input");
			if (!$json)
			{
				header('HTTP/1.0 404 Not Found');
				echo '<h1>No data posted</h1>';
				exit;
			}
			else
			{
				$data = json_decode($json);
				$this->load->model('catalog/product');
				$this->load->model('account/customer');
				$this->load->model('account/address');
				$this->load->model('checkout/order');
				$shop_currency = $this->config->get('config_currency');//RUB
				$offers_currency = ($data->order->currency == 'RUR') ? 'RUB' : $data->order->currency;
				$decimal_place = $this->currency->getDecimalPlace($offers_currency);
				$order_data = array();
				$items = $data->order->items;
				$resultat = true;
				if (count($items))
				{
					$this->cart->clear();
					$count_items = 0;
					foreach ($items as $item)
					{
						$opt = array();
						$id_array = explode('c', $item->offerId);
						$id_product = $id_array[0];
						$product_info = $this->model_catalog_product->getProduct($id_product);
						if (count($id_array) > 1)
						{
							unset($id_array[0]);
							foreach ($this->model_catalog_product->getProductOptions($id_product) as $options)
							{
								foreach ($options['product_option_value'] as $option)
								{
									if (in_array($option['option_value_id'], $id_array))
										$opt[$options['product_option_id']] = $option['product_option_value_id'];
								}
							}
						}
						$count_items += (int)$item->count;
						$this->cart->add($id_product, $item->count, $opt);
					}

					if ($this->cart->countProducts() == $count_items)
					{
						$taxes = $this->cart->getTaxes();
						$this->session->data['customer_id'] = '';
						$message = isset($data->order->notes) ? $data->order->notes : null;
						$customer_info = $this->model_account_customer->getCustomer($this->config->get('yandexbuy_customer'));
						$this->session->data['customer_id'] = $customer_info['customer_id'];
						$delivery = isset($data->order->delivery->address) ? $data->order->delivery->address : new stdClass();
						$street = isset($delivery->street) ? ' Улица: '.$delivery->street : 'Самовывоз';
						$subway = isset($delivery->subway) ? ' Метро: '.$delivery->subway : '';
						$block = isset($delivery->block) ? ' Корпус/Строение: '.$delivery->block : '';
						$floor = isset($delivery->floor) ? ' Этаж: '.$delivery->floor : '';
						$house = isset($delivery->house) ? ' Дом: '.$delivery->house : '';
						$address1 = $street.$subway.$block.$floor.$house;
						$order_data['customer_id'] = $customer_info['customer_id'];
						$order_data['customer_group_id'] = $customer_info['customer_group_id'];
						$order_data['firstname'] = $customer_info['firstname'];
						$order_data['lastname'] = $customer_info['lastname'];
						$order_data['email'] = $customer_info['email'];
						$order_data['telephone'] = $customer_info['telephone'];
						$order_data['fax'] = $customer_info['fax'];
						$order_data['shipping_firstname'] = $customer_info['firstname'];
						$order_data['shipping_lastname'] = $customer_info['lastname'];
						$order_data['shipping_company'] = '';
						$order_data['shipping_address_1'] = $address1;
						$order_data['shipping_city'] = isset($delivery->city) ? $delivery->city : 'Город';
						$order_data['shipping_postcode'] = isset($delivery->postcode) ? $delivery->postcode : 000000;
						$order_data['shipping_zone'] = $this->getRegion($data->order->delivery->region, 'SUBJECT_FEDERATION');
						$order_data['shipping_zone_id'] = '';
						$order_data['shipping_country'] = $data->order->delivery->address->country;
						$order_data['shipping_country_id'] = '';
						$order_data['shipping_address_format'] = '';
						$order_data['shipping_method'] = $data->order->delivery->serviceName;
						$order_data['shipping_code'] = $this->getShipping($data->order->delivery->id);
						$order_data['shipping_address_2'] = '';
						$order_data['payment_firstname'] = $customer_info['firstname'];
						$order_data['payment_lastname'] = $customer_info['lastname'];
						$order_data['payment_address_1'] = $address1;
						$order_data['payment_city'] = isset($delivery->city) ? $delivery->city : 'Город';
						$order_data['payment_postcode'] = isset($delivery->postcode) ? $delivery->postcode : 000000;
						$order_data['payment_zone'] = $this->getRegion($data->order->delivery->region, 'SUBJECT_FEDERATION');
						$order_data['payment_zone_id'] = '';
						$order_data['payment_country'] = $data->order->delivery->address->country;
						$order_data['payment_country_id'] = '';
						$order_data['payment_address_format'] = '';
						$order_data['payment_method'] = ((isset($data->order->paymentMethod))?$data->order->paymentMethod:'');
						$order_data['payment_address_2'] = '';
						$order_data['payment_code'] = 'yamodule';
						$order_data['language_id'] = $this->config->get('config_language_id');
						if (version_compare(VERSION, "2.2.0", '>=')){
							$order_data['currency_id'] = $this->currency->getId("RUB");
							$order_data['currency_code'] = $this->session->data['currency'];
							$order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
						}else{
							$order_data['currency_id'] = $this->currency->getId();
							$order_data['currency_code'] = $this->currency->getCode();
							$order_data['currency_value'] = $this->currency->getValue($this->currency->getCode());
						}
						$order_data['ip'] = '127.0.0.1';//$this->request->server['REMOTE_ADDR'];
						$order_data['forwarded_ip'] = '127.0.0.1';//$this->request->server['HTTP_X_FORWARDED_FOR'];
						$order_data['user_agent'] = 'Yandex';//$this->request->server['HTTP_USER_AGENT'];
						$order_data['accept_language'] = '';
						$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
						$order_data['store_id'] = $this->config->get('config_store_id');
						$order_data['store_name'] = $this->config->get('config_name');
						$order_data['store_url'] = $this->config->get('config_url');
						$order_data['comment'] = $message;
						$order_data['products'] = array();
						$order_data['vouchers'] = array();
						$order_data['affiliate_id'] = 0;
						$order_data['commission'] = 0;
						$order_data['marketing_id'] = 0;
						$order_data['tracking'] = '';
						$order_data['totals'] = array();
						$order_data['payment_company'] = '';
						$this->load->model('extension/extension');
						$sort_order = array();

						$totals = array();
						$total = 0;
						// Because __call can not keep var references so we put them into an array.
						$total_data = array(
							'totals' => &$totals,
							'taxes'  => &$taxes,
							'total'  => &$total
						);
						$results = $this->model_extension_extension->getExtensions('total');
						foreach ($results as $key => $value) {
							$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
						}
						array_multisort($sort_order, SORT_ASC, $results);
						foreach ($results as $result) {
							if ($this->config->get($result['code'] . '_status')) {
								$this->load->model('total/' . $result['code']);
								if (version_compare(VERSION, "2.2.0", '>=')) {
									$this->{'model_total_' . $result['code']}->getTotal($total_data);
								}else{
									$this->{'model_total_' . $result['code']}->getTotal($order_data['totals'], $total, $taxes);
								}
							}
						}
						
						$order_data['total'] = $total;
						foreach ($this->cart->getProducts() as $product) {
							$option_data = array();

							foreach ($product['option'] as $option) {
								$option_data[] = array(
									'product_option_id'       => $option['product_option_id'],
									'product_option_value_id' => $option['product_option_value_id'],
									'option_id'               => $option['option_id'],
									'option_value_id'         => $option['option_value_id'],
									'name'                    => $option['name'],
									'value'                   => $option['value'],
									'type'                    => $option['type']
								);
							}

							$order_data['products'][] = array(
								'product_id' => $product['product_id'],
								'name'       => $product['name'],
								'model'      => $product['model'],
								'option'     => $option_data,
								'download'   => $product['download'],
								'quantity'   => $product['quantity'],
								'subtract'   => $product['subtract'],
								'price'      => $product['price'],
								'total'      => $product['total'],
								'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
								'reward'     => $product['reward']
							);
						}
						
						$id_order = $this->model_checkout_order->addOrder($order_data);
						$this->model_checkout_order->addOrderHistory($id_order, 1, 'Заказ '.$data->order->id.' сформирован по запросу Яндекс.Маркета', false);
						$this->session->data['order_id'] = $id_order;
						$values_to_insert = array(
							'id_order' => (int)$id_order,
							'id_market_order' => (int)$data->order->id,
							'ptype' => ((isset($data->order->paymentType))?$data->order->paymentType:''),
							'pmethod' => ((isset($data->order->paymentMethod))?$data->order->paymentMethod:''),
							'home' => isset($data->order->delivery->address->house) ? $data->order->delivery->address->house : 0,
							'outlet' => isset($data->order->delivery->outlet->id) ? $data->order->delivery->outlet->id : '',
							'currency' => $data->order->currency
						);
						
						$request = '';
						foreach ($values_to_insert as $key => $val)
							if (!empty($val))
								$request .= ' `'.$key.'` = "'.$this->db->escape($val).'",';

						$this->db->query('INSERT INTO '.DB_PREFIX.'pokupki_orders SET '.trim($request, ','));
						//$this->log_save(print_r($order_data, true));
					}
					else
						$resultat = false;
				}
				else
					$resultat = false;
				
				if($resultat)
				{
					$array = array(
						'order' => array(
							'accepted' => true,
							'id' => (string)$id_order,
						)
					);
				}
				else
				{
					$array = array(
						'order' => array(
							'accepted' => false,
							'reason' => 'OUT_OF_DATE'
						)
					);
				}

				$this->response->addHeader('Content-Type: application/json; charset=utf-8');
				$this->response->setOutput(json_encode($array));
			}
		}
	}
	
	public function getShopOrderId($id)
	{
		$query = $this->db->query('SELECT * FROM '.DB_PREFIX.'pokupki_orders WHERE `id_market_order` = '.(int)$id);
		return $query->row;
	}

	public function status()
	{
		$sign = $this->config->get('ya_pokupki_stoken');
		$key = isset($_REQUEST['auth-token']) ? $_REQUEST['auth-token'] : '';
		if (strtoupper($sign) != strtoupper($key))
		{
			header('HTTP/1.0 403 Forbidden');
			echo '<h1>Wrong token</h1>';
			$this->log_save('Yandex.Market CPA: Wrong auth-token in setting module.');
			exit;
		}
		else
		{
			$json = file_get_contents("php://input");
			if (!$json)
			{
				header('HTTP/1.0 404 Not Found');
				echo '<h1>No data posted</h1>';
				$this->log_save('Yandex.Market CPA: Empty request from service.');
				exit;
			}
			else
			{
				$this->language->load('yandexbuy/order');
				$data = json_decode($json);
				$shop_order = $this->getShopOrderId($data->order->id);
				if ($shop_order['id_order'])
				{
					$this->load->model('account/customer');
					$this->load->model('account/address');
					$this->load->model('checkout/order');
					$order = $this->model_checkout_order->getOrder($shop_order['id_order']);

					$buyer = isset($data->order->buyer) ? $data->order->buyer : '';
					if ($buyer!='' && (isset($buyer->firstName) && isset($buyer->lastName))){
						$order['payment_firstname'] = $buyer->firstName;
						$order['payment_lastname'] = $buyer->lastName;
						$order['firstname'] = $buyer->firstName;
						$order['lastname'] = $buyer->lastName;
						$order['shipping_firstname'] = $buyer->firstName;;
						$order['shipping_lastname'] = $buyer->lastName;
						$order['telephone'] = isset($buyer->phone) ? $buyer->phone : 999999;
						$this->editOrder($shop_order['id_order'], $order);
					}
					$text = "";
					switch($data->order->status){
						case "UNPAID":
							$status = $this->config->get('ya_pokupki_status_unpaid');
							break;
						case "CANCELLED":
							$status = $this->config->get('ya_pokupki_status_cancelled');
							$text = isset($data->order->substatus) ? $data->order->substatus : '';
							break;
						case "PROCESSING":
							$status = $this->config->get('ya_pokupki_status_processing');
							$text = $this->language->get('text_marketcpa_toprocessing');
							break;
						case "DELIVERY":
							$status = $this->config->get('ya_pokupki_status_delivery');
							$text = $this->language->get('text_marketcpa_todelivery');
							break;
						default:
							$status = $order['order_status_id'];
							break;
					}
					if ($status==$order['order_status_id']) $this->log_save(sprintf('Yandex.Market CPA: Status order %s has changed on %s', $shop_order['id_order'], $data->order->status));
					if ($status>0) $this->model_checkout_order->addOrderHistory($shop_order['id_order'], $status, sprintf($this->language->get('text_marketcpa_changeorder'), $data->order->status).$text, false);
				}else{
					$this->log_save(sprintf('Yandex.Market CPA: Order %s not found ', $shop_order['id_order']));
				}
				die();
			}
		}
	}
	
	public function editOrder($order_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) .
			"', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) .
			"', store_url = '" . $this->db->escape($data['store_url']) .
			"', customer_id = '" . (int)$data['customer_id'] .
			"', customer_group_id = '" . (int)(isset($data['customer_group_id'])?$data['customer_group_id']:1) .
			"', firstname = '" . $this->db->escape($data['firstname']) .
			"', lastname = '" . $this->db->escape($data['lastname']) .
			"', email = '" . $this->db->escape($data['email']) .
			"', telephone = '" . $this->db->escape($data['telephone']) .
			"', fax = '" . $this->db->escape($data['fax']) .
			"', custom_field = '" . $this->db->escape(serialize($data['custom_field'])) .
			"', payment_firstname = '" . $this->db->escape($data['payment_firstname']) .
			"', payment_lastname = '" . $this->db->escape($data['payment_lastname']) .
			"', payment_company = '" . $this->db->escape($data['payment_company']) .
			"', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) .
			"', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) .
			"', payment_city = '" . $this->db->escape($data['payment_city']) .
			"', payment_postcode = '" . $this->db->escape($data['payment_postcode']) .
			"', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_custom_field = '" . $this->db->escape(serialize($data['payment_custom_field'])) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_custom_field = '" . $this->db->escape(serialize($data['shipping_custom_field'])) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
	}
	
	public static function log_save($logtext)
	{
		$log = new Log('error.log');
		$log->write($logtext);
	}
}