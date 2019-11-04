<?php
class ControllerExtensionPaymentBankTransfer extends Controller {
	public function index() {
		$this->load->language('extension/payment/bank_transfer');

		$data['text_instruction'] = $this->language->get('text_instruction');
		$data['text_description'] = $this->language->get('text_description');
		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['bank'] = nl2br($this->config->get('bank_transfer_bank' . $this->config->get('config_language_id')));

		$data['continue'] = $this->url->link('checkout/success');

		return $this->load->view('extension/payment/bank_transfer', $data);
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'bank_transfer') {
			$this->load->language('extension/payment/bank_transfer');

			$this->load->model('checkout/order');

			$comment  = $this->language->get('text_instruction') . "\n\n";
			$comment .= $this->config->get('bank_transfer_bank' . $this->config->get('config_language_id')) . "\n\n";
			$comment .= $this->language->get('text_payment');

            $this->sendMail();

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('bank_transfer_order_status_id'), $comment, true);
		}
	}

	public function sendMail(  ){

	    $msg = "<table class=\"table table-bordered table-hover\">
            <thead>
              <tr> 	 	 	
                        <td class=\"text-left\">Название товара</td>
                <td class=\"text-right\">Количество</td>
                <td class=\"text-right\">Цена</td>
                <td class=\"text-right\">Итого</td>
                </tr>
                </thead>
                <tbody>
            ";


        foreach ($this->cart->getProducts() as $product) {
            $total = 0;
            $msg .= "<td class=\"text-left\"> " . $product['name'];
            foreach ($product['option'] as $option) {
                if ($option['type'] != 'file') {
                    $msg .=  "<br />&nbsp;<small> - " . $option['name'] ." " . $option['value'] . "</small>";
                }
            }
            $msg .=  "<td class=\"text-right\">" . $product['quantity'] . "</td> ";
            $msg .=  "<td class=\"text-right\">" .$product['price'] . "</td> ";
            $msg .=  "<td class=\"text-right\">" .$product['total'] . "</td> ";
            $msg .=  "</tr> ";
            $total += $product['total'];
        }

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
                    $msg .=  "    <tr>
                        <td class=\"text-left\">" . $voucher['description'] ."</td>
                        <td class=\"text-right\">1</td>
                        <td class=\"text-right\">" .$voucher['amount'] ."</td>
                        <td class=\"text-right\">" .$voucher['amount'] ."</td>
                    </tr>";
				}
			}

			$msg .= "<tr>
                <td colspan=\"3\" class=\"text-right\"><strong> Итого:</strong></td>
                <td class=\"text-right\">".$total."</td> </tr> </table>";

            $msg .= $this->language->get('text_instruction') . "<br><br>";
            $msg .= $this->config->get('bank_transfer_bank' . $this->config->get('config_language_id')) . "<br><br>";
            $msg .= $this->language->get('text_payment');


            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

            $mail->setTo("aifogmart@mail.ru");
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
            $mail->setSubject(html_entity_decode("Счет на оплату", ENT_QUOTES, 'UTF-8'));
            $mail->setHtml(html_entity_decode($msg, ENT_QUOTES, 'UTF-8'));
            $mail->send();


    }
}

