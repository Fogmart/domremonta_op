<?php
class ModelExtensionShippingItem extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/item');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('item_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('item_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$items = 0;

			foreach ($this->cart->getProducts() as $product) {
				if ($product['shipping']) {
					$items += $product['quantity'];
				}
			}

			$quote_data = array();


            $quote_data['self'] = array(
                'code'         => 'item.self',
                'title'        => "Самовывоз",
                'cost'         => 0,
                'tax_class_id' => $this->config->get('item_tax_class_id'),
                'text'         => "Выбрать пункт выдачи"
            );

            $quote_data['ship'] = array(
                'code'         => 'item.ship',
                'title'        => "Доставка",
                'cost'         => 0,
                'tax_class_id' => $this->config->get('item_tax_class_id'),
                'text'         => "Условия и стоимость уточнит менеджер при подтверждении заказа"
            );
            $quote_data['post'] = array(
                'code'         => 'item.post',
                'title'        => "Почта России",
                'cost'         => 0,
                'tax_class_id' => $this->config->get('item_tax_class_id'),
                'text'         => "Посчитаем через модуль"
            );
            $quote_data['pek'] = array(
                'code'         => 'item.pek',
                'title'        => "ПЭК",
                'cost'         => 0,
                'tax_class_id' => $this->config->get('item_tax_class_id'),
                'text'         => "Условия и стоимость уточнит менеджер при подтверждении заказа"
            );
            $quote_data['del'] = array(
                'code'         => 'item.del',
                'title'        => "Деловые линии",
                'cost'         => 0,
                'tax_class_id' => $this->config->get('item_tax_class_id'),
                'text'         => "Условия и стоимость уточнит менеджер при подтверждении заказа"
            );
            $quote_data['dpd'] = array(
                'code'         => 'item.dpd',
                'title'        => "DPD",
                'cost'         => 0,
                'tax_class_id' => $this->config->get('item_tax_class_id'),
                'text'         => "Условия и стоимость уточнит менеджер при подтверждении заказа"
            );


			$method_data = array(
				'code'       => 'item',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('item_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}
}