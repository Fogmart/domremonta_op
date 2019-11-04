<?php
class ModelExtensionShippingRussianpost2f9 extends Model {
	
	private $method_number = 9;
	
	function getQuote($address)
	{
		$address['method_number'] = $this->method_number;
		
		$this->load->model('extension/shipping/russianpost2');
		return $this->model_extension_shipping_russianpost2->getQuote($address);
	}
}
?>