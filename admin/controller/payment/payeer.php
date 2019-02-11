<?php
class ControllerPaymentpayeer extends Controller {
	private $error = array();

	public function index() 
	{
		$this->load->language('payment/payeer');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) 
		{
			$this->model_setting_setting->editSetting('payeer', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_success'] = $this->language->get('text_success');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_pay'] = $this->language->get('text_pay');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_payeer'] = $this->language->get('text_payeer');
		$data['text_email_subject'] = $this->language->get('text_email_subject');
		$data['text_email_message1'] = $this->language->get('text_email_message1');
		$data['text_email_message2'] = $this->language->get('text_email_message2');
		$data['text_email_message3'] = $this->language->get('text_email_message3');
		$data['text_email_message4'] = $this->language->get('text_email_message4');
		$data['text_email_message5'] = $this->language->get('text_email_message5');
		$data['text_email_message6'] = $this->language->get('text_email_message6');
		
		$data['entry_url'] = $this->language->get('entry_url');
		$data['entry_merchant'] = $this->language->get('entry_merchant');
		$data['entry_security'] = $this->language->get('entry_security');
		$data['entry_order_wait'] = $this->language->get('entry_order_wait');
		$data['entry_order_success'] = $this->language->get('entry_order_success');
		$data['entry_order_fail'] = $this->language->get('entry_order_fail');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_log'] = $this->language->get('entry_log');
		$data['entry_list_ip'] = $this->language->get('entry_list_ip');
		$data['entry_admin_email'] = $this->language->get('entry_admin_email');
		$data['entry_merchant'] = $this->language->get('entry_merchant');
		$data['entry_status_url'] = $this->language->get('entry_status_url');
		$data['entry_success_url'] = $this->language->get('entry_success_url');
		$data['entry_fail_url'] = $this->language->get('entry_fail_url');
		
		$data['error_url'] = $this->language->get('error_url');
		$data['error_permission'] = $this->language->get('error_permission');
		$data['error_merchant'] = $this->language->get('error_merchant');
		$data['error_security'] = $this->language->get('error_security');

		$data['help_url'] = $this->language->get('help_url');
		$data['help_merchant'] = $this->language->get('help_merchant');
		$data['help_security'] = $this->language->get('help_security');
		$data['help_log'] = $this->language->get('help_log');
		$data['help_list_ip'] = $this->language->get('help_list_ip');
		$data['help_admin_email'] = $this->language->get('help_admin_email');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) 
		{
			$data['error_warning'] = $this->error['warning'];
		} 
		else 
		{
			$data['error_warning'] = '';
		}

		if (isset($this->error['url'])) 
		{
			$data['error_url'] = $this->error['url'];
		} 
		else 
		{
			$data['error_url'] = '';
		}
		
		if (isset($this->error['merchant'])) 
		{
			$data['error_merchant'] = $this->error['merchant'];
		} 
		else 
		{
			$data['error_merchant'] = '';
		}

		if (isset($this->error['security'])) 
		{
			$data['error_security'] = $this->error['security'];
		} 
		else 
		{
			$data['error_security'] = '';
		}

		if (isset($this->error['type'])) 
		{
			$data['error_type'] = $this->error['type'];
		} 
		else 
		{
			$data['error_type'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/payeer', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/payeer', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['payeer_url'])) 
		{
			$data['payeer_url'] = $this->request->post['payeer_url'];
		} 
		else
		{
			if (!$this->config->get('payeer_url'))
			{
				$data['payeer_url'] = 'https://payeer.com/merchant/';
			}
			else
			{
				$data['payeer_url'] = $this->config->get('payeer_url');
			}
		}

		if (isset($this->request->post['payeer_merchant'])) 
		{
			$data['payeer_merchant'] = $this->request->post['payeer_merchant'];
		} 
		else 
		{
			$data['payeer_merchant'] = $this->config->get('payeer_merchant');
		}

		if (isset($this->request->post['payeer_security'])) 
		{
			$data['payeer_security'] = $this->request->post['payeer_security'];
		} 
		else 
		{
			$data['payeer_security'] = $this->config->get('payeer_security');
		}

		if (isset($this->request->post['payeer_order_wait_id'])) 
		{
			$data['payeer_order_wait_id'] = $this->request->post['payeer_order_wait_id'];
		}
		else 
		{
			if (!$this->config->get('payeer_order_wait_id'))
			{
				$data['payeer_order_wait_id'] = 1;
			}
			else
			{
				$data['payeer_order_wait_id'] = $this->config->get('payeer_order_wait_id');
			}
		}
		
		if (isset($this->request->post['payeer_order_success_id'])) 
		{
			$data['payeer_order_success_id'] = $this->request->post['payeer_order_success_id'];
		}
		else 
		{
			if (!$this->config->get('payeer_order_success_id'))
			{
				$data['payeer_order_success_id'] = 5;
			}
			else
			{
				$data['payeer_order_success_id'] = $this->config->get('payeer_order_success_id');
			}
		}
		
		if (isset($this->request->post['payeer_order_fail_id'])) 
		{
			$data['payeer_order_fail_id'] = $this->request->post['payeer_order_fail_id'];
		}
		else 
		{
			if (!$this->config->get('payeer_order_fail_id'))
			{
				$data['payeer_order_fail_id'] = 10;
			}
			else
			{
				$data['payeer_order_fail_id'] = $this->config->get('payeer_order_fail_id');
			}
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payeer_geo_zone_id'])) 
		{
			$data['payeer_geo_zone_id'] = $this->request->post['payeer_geo_zone_id'];
		} 
		else 
		{
			$data['payeer_geo_zone_id'] = $this->config->get('payeer_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payeer_status'])) 
		{
			$data['payeer_status'] = $this->request->post['payeer_status'];
		} 
		else 
		{
			$data['payeer_status'] = $this->config->get('payeer_status');
		}

		if (isset($this->request->post['payeer_sort_order'])) 
		{
			$data['payeer_sort_order'] = $this->request->post['payeer_sort_order'];
		} 
		else 
		{
			$data['payeer_sort_order'] = $this->config->get('payeer_sort_order');
		}
		
		if (isset($this->request->post['payeer_log_value'])) 
		{
			$data['payeer_log_value'] = $this->request->post['payeer_log_value'];
		} 
		else 
		{
			$data['payeer_log_value'] = $this->config->get('payeer_log_value');
		}
		
		if (isset($this->request->post['payeer_list_ip'])) 
		{
			$data['payeer_list_ip'] = $this->request->post['payeer_list_ip'];
		}
		else 
		{
			$data['payeer_list_ip'] = $this->config->get('payeer_list_ip');
		}
		
		if (isset($this->request->post['payeer_admin_email'])) 
		{
			$data['payeer_admin_email'] = $this->request->post['payeer_admin_email'];
		} 
		else 
		{
			$data['payeer_admin_email'] = $this->config->get('payeer_admin_email');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/payeer.tpl', $data));
	}

	protected function validate(){
		
		if (!$this->user->hasPermission('modify', 'payment/payeer')) 
		{
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payeer_url'])
		{
			$this->error['url'] = $this->language->get('error_url');
		}

		if (!$this->request->post['payeer_merchant']) 
		{
			$this->error['merchant'] = $this->language->get('error_merchant');
		}
		
		if (!$this->request->post['payeer_security']) 
		{
			$this->error['security'] = $this->language->get('error_security');
		}

		return !$this->error;
	}
}