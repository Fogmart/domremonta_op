<?php 
class ControllerExtensionTotalRpcodonly2 extends Controller { 
	private $error = array(); 
	private $data = array(); 
	 
	public function index() { 
		$this->load->language('extension/total/rpcodonly2');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('rpcodonly2', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'].'&type=total', 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_estimator'] = $this->language->get('entry_estimator');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['text_rpcodonly2_notice'] = $this->language->get('text_rpcodonly2_notice');
		
					
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_total'),
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'].'&type=total', 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/total/rpcodonly2', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('extension/total/rpcodonly2', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'].'&type=total', 'SSL');

		if (isset($this->request->post['rpcodonly2_estimator'])) {
			$this->data['rpcodonly2_estimator'] = $this->request->post['rpcodonly2_estimator'];
		} else {
			$this->data['rpcodonly2_estimator'] = $this->config->get('rpcodonly2_estimator');
		}
		
		if (isset($this->request->post['rpcodonly2_status'])) {
			$this->data['rpcodonly2_status'] = $this->request->post['rpcodonly2_status'];
		} else {
			$this->data['rpcodonly2_status'] = $this->config->get('rpcodonly2_status');
		}

		if (isset($this->request->post['rpcodonly2_sort_order'])) {
			$this->data['rpcodonly2_sort_order'] = $this->request->post['rpcodonly2_sort_order'];
		} else {
			$this->data['rpcodonly2_sort_order'] = $this->config->get('rpcodonly2_sort_order');
		}
		
													
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/total/rpcodonly2', $this->data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/total/rpcodonly2')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	
	
	private function custom_unserialize($s)
	{
		if( is_array($s) ) return $s;
		
		if(
			stristr($s, '{' ) != false &&
			stristr($s, '}' ) != false &&
			stristr($s, ';' ) != false &&
			stristr($s, ':' ) != false
		){
			return unserialize($s);
		}else{
			return $s;
		}

	}

}
?>