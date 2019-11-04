<?php
class ModelExtensionShippingRussianpost2f2 extends Model {
	
	private $method_number = 2;
	
	function getQuote($address)
	{
		$address['method_number'] = $this->method_number;
		
		$this->load->model('extension/shipping/russianpost2');
		return $this->model_extension_shipping_russianpost2->getQuote($address);
	}
}
?>