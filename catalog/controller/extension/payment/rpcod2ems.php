<?php
class ControllerExtensionPaymentrpcod2ems extends Controller {
	public function index() {
    	$data['button_confirm'] = $this->language->get('button_confirm');

		$data['continue'] = $this->url->link('checkout/success');
		
		
		return $this->load->view('extension/payment/rpcod2ems', $data);
	}
	
	public function confirm() 
	{
	
		if ($this->session->data['payment_method']['code'] == 'rpcod2ems') 
		{
			$this->load->model('checkout/order');
			
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('rpcod2ems_order_status'));
		}
		
	}
}
?>