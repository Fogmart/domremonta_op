<?php
class ControllerAccountEdit extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/edit', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/edit');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_account_customer->editCustomer($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			// Add to activity log
			if ($this->config->get('config_customer_activity')) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
				);

				$this->model_account_activity->addActivity('edit', $activity_data);
			}

			$this->response->redirect($this->url->link('account/account', '', true));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_edit'),
			'href'      => $this->url->link('account/edit', '', true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_your_details'] = $this->language->get('text_your_details');
		$data['text_additional'] = $this->language->get('text_additional');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_fax'] = $this->language->get('entry_fax');


		$data['entry_company']       = $this->language->get('entry_company');
		$data['entry_company_inn']   = $this->language->get('entry_company_inn');
		$data['entry_company_kpp']   = $this->language->get('entry_company_kpp');
		$data['entry_company_bik']   = $this->language->get('entry_company_bik');
		$data['entry_company_corr']  = $this->language->get('entry_company_corr');
		$data['entry_company_rs']    = $this->language->get('entry_company_rs');
		$data['entry_company_bank']  = $this->language->get('entry_company_bank');
		$data['entry_company_agent'] = $this->language->get('entry_company_agent');

		$data['isfl'] = $this->language->get('isfl');
		$data['isyl'] = $this->language->get('isyl');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');
		$data['button_upload'] = $this->language->get('button_upload');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['firstname'])) {
			$data['error_firstname'] = $this->error['firstname'];
		} else {
			$data['error_firstname'] = '';
		}

		if (isset($this->error['lastname'])) {
			$data['error_lastname'] = $this->error['lastname'];
		} else {
			$data['error_lastname'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}

		if (isset($this->error['custom_field'])) {
			$data['error_custom_field'] = $this->error['custom_field'];
		} else {
			$data['error_custom_field'] = array();
		}

		$data['action'] = $this->url->link('account/edit', '', true);

		if ($this->request->server['REQUEST_METHOD'] != 'POST') {
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
		}

		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($customer_info)) {
			$data['firstname'] = $customer_info['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} elseif (!empty($customer_info)) {
			$data['lastname'] = $customer_info['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($customer_info)) {
			$data['email'] = $customer_info['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($customer_info)) {
			$data['telephone'] = $customer_info['telephone'];
		} else {
			$data['telephone'] = '';
		}

		if (isset($this->request->post['fax'])) {
			$data['fax'] = $this->request->post['fax'];
		} elseif (!empty($customer_info)) {
			$data['fax'] = $customer_info['fax'];
		} else {
			$data['fax'] = '';
		}

        if (isset($this->request->post['isorg'])) {
            $data['isorg'] = $this->request->post['isorg'];
        } elseif (!empty($customer_info)) {
            $data['isorg'] = $customer_info['isorg'];
        } else {
            $data['isorg'] = '';
        }
        

        if (isset($this->request->post['company'])) {
            $data['company'] = $this->request->post['company'];
        } elseif (!empty($customer_info)) {
            $data['company'] = $customer_info['company'];
        } else {
            $data['company'] = '';
        }
        if (isset($this->error['company'])) {
            $data['error_company'] = $this->error['company'];
        } else {
            $data['error_company'] = '';
        }
        
        if (isset($this->request->post['company_inn'])) {
            $data['company_inn'] = $this->request->post['company_inn'];
        } elseif (!empty($customer_info)) {
            $data['company_inn'] = $customer_info['company_inn'];
        } else {
            $data['company_inn'] = '';
        }
        if (isset($this->error['company_inn'])) {
            $data['error_company_inn'] = $this->error['company_inn'];
        } else {
            $data['error_company_inn'] = '';
        }
        
        if (isset($this->request->post['company_kpp'])) {
            $data['company_kpp'] = $this->request->post['company_kpp'];
        } elseif (!empty($customer_info)) {
            $data['company_kpp'] = $customer_info['company_kpp'];
        } else {
            $data['company_kpp'] = '';
        }
        if (isset($this->error['company_kpp'])) {
            $data['error_company_kpp'] = $this->error['company_kpp'];
        } else {
            $data['error_company_kpp'] = '';
        }
        
        if (isset($this->request->post['company_bik'])) {
            $data['company_bik'] = $this->request->post['company_bik'];
        } elseif (!empty($customer_info)) {
            $data['company_bik'] = $customer_info['company_bik'];
        } else {
            $data['company_bik'] = '';
        }
        if (isset($this->error['company_bik'])) {
            $data['error_company_bik'] = $this->error['company_bik'];
        } else {
            $data['error_company_bik'] = '';
        }
        
        if (isset($this->request->post['company_corr'])) {
            $data['company_corr'] = $this->request->post['company_corr'];
        } elseif (!empty($customer_info)) {
            $data['company_corr'] = $customer_info['company_corr'];
        } else {
            $data['company_corr'] = '';
        }
        if (isset($this->error['company_corr'])) {
            $data['error_company_corr'] = $this->error['company_corr'];
        } else {
            $data['error_company_corr'] = '';
        }
        
        if (isset($this->request->post['company_rs'])) {
            $data['company_rs'] = $this->request->post['company_rs'];
        } elseif (!empty($customer_info)) {
            $data['company_rs'] = $customer_info['company_rs'];
        } else {
            $data['company_rs'] = '';
        }
        if (isset($this->error['company_rs'])) {
            $data['error_company_rs'] = $this->error['company_rs'];
        } else {
            $data['error_company_rs'] = '';
        }
        
        if (isset($this->request->post['company_bank'])) {
            $data['company_bank'] = $this->request->post['company_bank'];
        } elseif (!empty($customer_info)) {
            $data['company_bank'] = $customer_info['company_bank'];
        } else {
            $data['company_bank'] = '';
        }
        if (isset($this->error['company_bank'])) {
            $data['error_company_bank'] = $this->error['company_bank'];
        } else {
            $data['error_company_bank'] = '';
        }
        
        if (isset($this->request->post['company_agent'])) {
            $data['company_agent'] = $this->request->post['company_agent'];
        } elseif (!empty($customer_info)) {
            $data['company_agent'] = $customer_info['company_agent'];
        } else {
            $data['company_agent'] = '';
        }
        if (isset($this->error['company_agent'])) {
            $data['error_company_agent'] = $this->error['company_agent'];
        } else {
            $data['error_company_agent'] = '';
        }
        

		// Custom Fields
		$this->load->model('account/custom_field');

		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

		if (isset($this->request->post['custom_field'])) {
			$data['account_custom_field'] = $this->request->post['custom_field'];
		} elseif (isset($customer_info)) {
			$data['account_custom_field'] = json_decode($customer_info['custom_field'], true);
		} else {
			$data['account_custom_field'] = array();
		}

		$data['back'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/edit', $data));
	}

	protected function validate() {
	    
	    if ($this->request->post['isorg'] == "0") {
            if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
                $this->error['firstname'] = $this->language->get('error_firstname');
            }

            if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
                $this->error['lastname'] = $this->language->get('error_lastname');
            }
        } else {

            if ((utf8_strlen(trim($this->request->post['company'])) < 1) || (utf8_strlen(trim($this->request->post['company'])) > 32)) {
                $this->error['company'] = $this->language->get('error_company');
            }

            if ((utf8_strlen(trim($this->request->post['company_inn'])) < 1) || (utf8_strlen(trim($this->request->post['company_inn'])) > 32)) {
                $this->error['company_inn'] = $this->language->get('error_company_inn');
            }

            if ((utf8_strlen(trim($this->request->post['company_kpp'])) < 1) || (utf8_strlen(trim($this->request->post['company_kpp'])) > 32)) {
                $this->error['company_kpp'] = $this->language->get('error_company_kpp');
            }

            if ((utf8_strlen(trim($this->request->post['company_bik'])) < 1) || (utf8_strlen(trim($this->request->post['company_bik'])) > 32)) {
                $this->error['company_bik'] = $this->language->get('error_company_bik');
            }

            if ((utf8_strlen(trim($this->request->post['company_corr'])) < 1) || (utf8_strlen(trim($this->request->post['company_corr'])) > 32)) {
                $this->error['company_corr'] = $this->language->get('error_company_corr');
            }

            if ((utf8_strlen(trim($this->request->post['company_rs'])) < 1) || (utf8_strlen(trim($this->request->post['company_rs'])) > 32)) {
                $this->error['company_rs'] = $this->language->get('error_company_rs');
            }

            if ((utf8_strlen(trim($this->request->post['company_bank'])) < 1) || (utf8_strlen(trim($this->request->post['company_bank'])) > 32)) {
                $this->error['company_bank'] = $this->language->get('error_company_bank');
            }

            if ((utf8_strlen(trim($this->request->post['company_agent'])) < 1) || (utf8_strlen(trim($this->request->post['company_agent'])) > 32)) {
                $this->error['company_agent'] = $this->language->get('error_company_agent');
            }

        }

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if (($this->customer->getEmail() != $this->request->post['email']) && $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_exists');
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		// Custom field validation
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

		foreach ($custom_fields as $custom_field) {
			if (($custom_field['location'] == 'account') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
				$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
			} elseif (($custom_field['location'] == 'account') && ($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
                $this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
            }
		}

		return !$this->error;
	}
}