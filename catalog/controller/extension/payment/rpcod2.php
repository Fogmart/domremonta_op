<?php
class ControllerExtensionPaymentrpcod2 extends Controller {
	public function index() {
    	$data['button_confirm'] = $this->language->get('button_confirm');

		$data['continue'] = $this->url->link('checkout/success');
		
		
		return $this->load->view('extension/payment/rpcod2', $data);
	}
	
	public function confirm() 
	{
	
		if ($this->session->data['payment_method']['code'] == 'rpcod2') 
		{
			$this->load->model('checkout/order');
			
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('rpcod2_order_status'));
		}
		
	}
	
	
	public function setTerminal()
	{
		$code = $this->request->post['code'];
		$title = $this->request->post['title'];
		$cost = $this->request->post['cost'];
		$text = $this->request->post['text'];
		
		if( empty($code) ) exit("ERROR-1");
		
		$code_ar = explode("_", $code);
		$title =  strip_tags( html_entity_decode($title, ENT_QUOTES, 'UTF-8') );

		$this->session->data['rp_delivery_point_index'] = $this->request->post['delivery_point_index'];
		
		
		$code = $code_ar[0].'.'.$code_ar[1];
		 
		$this->session->data['shipping_method']['code'] = $code;
		$this->session->data['shipping_method']['title'] = $title;
		$this->session->data['shipping_method']['cost'] = $cost;
		$this->session->data['shipping_method']['text'] = $text;
		$this->session->data['shipping_method']['tax_class_id'] = false;
		
		$this->session->data['shipping_methods'][$code_ar[0]]['quote'][ $code_ar[1] ]['title'] = $title;
		$this->session->data['shipping_methods'][$code_ar[0]]['quote'][ $code_ar[1] ]['cost'] = $cost;
		$this->session->data['shipping_methods'][$code_ar[0]]['quote'][ $code_ar[1] ]['text'] = $text;
		$this->session->data['shipping_methods'][$code_ar[0]]['quote'][ $code_ar[1] ]['tax_class_id'] = false; 
		
		$json = array(
			"status" => 'OK',
			"address_1" => '',
			"address_2" => '',
			"comment" => '',
		);
		
		$is_simple = !empty($this->session->data['simple']) ? 1 : 0;
		$this->request->post['address'] = html_entity_decode($this->request->post['address'], ENT_QUOTES, 'UTF-8');
		$this->request->post['brand_name'] = html_entity_decode($this->request->post['brand_name'], ENT_QUOTES, 'UTF-8');
		
		$this->request->post['address'] .= ', '.html_entity_decode($this->request->post['brand_name'], ENT_QUOTES, 'UTF-8');
		
		exit( json_encode($json) );
	}
}
?>