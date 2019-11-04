<?php
class ModelExtensionPaymentYamodule extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/yamodule');

		if ($total == 0 || $total < $this->config->get('ya_p2p_minimum')) {
			$status = false;
		} else {
			$status = true;
		}

		$method_data = array();

		if ($status) {
            $name =($this->config->get('ya_kassa_active'))?'kassa_title':'p2p_title';
			$method_data = array(
				'code'       => 'yamodule',
				'title'      => $this->language->get($name),
				'terms'      => '',
				'sort_order' => $this->config->get('ya_p2p_sort_order')
			);
		}

		return $method_data;
	}
}