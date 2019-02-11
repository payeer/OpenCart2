<?php
class ControllerPaymentPayeer extends Controller 
{	
	public function index() 
	{
		$data['button_confirm'] = $this->language->get('button_confirm');
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$m_key = $this->config->get('payeer_security');
		$data['lang'] = $this->session->data['language'];
		$data['action'] = $this->config->get('payeer_url');
		$data['m_shop'] = $this->config->get('payeer_merchant');
		$data['m_orderid'] = $this->session->data['order_id'];
		$data['m_amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$data['m_amount'] = number_format($data['m_amount'], 2, '.', '');
		$m_curr = strtoupper($order_info['currency_code']);
		$data['m_curr'] = ($m_curr == 'RUR') ? 'RUB' : $m_curr;
		$data['m_desc'] = base64_encode($order_info['comment']);
		$arHash = array(
			$data['m_shop'],
			$data['m_orderid'],
			$data['m_amount'],
			$data['m_curr'],
			$data['m_desc'],
			$m_key
		);
		
		$data['sign'] = strtoupper(hash('sha256', implode(':', $arHash)));
		$this->model_checkout_order->addOrderHistory($data['m_orderid'], $this->config->get('payeer_order_wait_id'));
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/payeer.tpl'))
		{
			return $this->load->view($this->config->get('config_template') . '/template/payment/payeer.tpl', $data);
		}
		else 
		{
			return $this->load->view('default/template/payment/payeer.tpl', $data);
		}
	}

	public function status()
	{
		$request = $this->request->post;
		
		if (isset($request["m_operation_id"]) && isset($request["m_sign"]))
		{
			$err = false;
			$message = '';
			$this->load->language('payment/payeer');
		
			// запись логов
			
			$log_text = 
			"--------------------------------------------------------\n" .
			"operation id		" . $request['m_operation_id'] . "\n" .
			"operation ps		" . $request['m_operation_ps'] . "\n" .
			"operation date		" . $request['m_operation_date'] . "\n" .
			"operation pay date	" . $request['m_operation_pay_date'] . "\n" .
			"shop				" . $request['m_shop'] . "\n" .
			"order id			" . $request['m_orderid'] . "\n" .
			"amount				" . $request['m_amount'] . "\n" .
			"currency			" . $request['m_curr'] . "\n" .
			"description		" . base64_decode($request['m_desc']) . "\n" .
			"status				" . $request['m_status'] . "\n" .
			"sign				" . $request['m_sign'] . "\n\n";
			
			$log_file = $this->config->get('payeer_log_value');
			
			if (!empty($log_file))
			{
				file_put_contents($_SERVER['DOCUMENT_ROOT'] . $log_file, $log_text, FILE_APPEND);
			}

			// проверка цифровой подписи и ip

			$sign_hash = strtoupper(hash('sha256', implode(":", array(
				$request['m_operation_id'],
				$request['m_operation_ps'],
				$request['m_operation_date'],
				$request['m_operation_pay_date'],
				$request['m_shop'],
				$request['m_orderid'],
				$request['m_amount'],
				$request['m_curr'],
				$request['m_desc'],
				$request['m_status'],
				$this->config->get('payeer_security')
			))));
			
			$valid_ip = true;
			$sIP = str_replace(' ', '', $this->config->get('payeer_list_ip'));
			
			if (!empty($sIP))
			{
				$arrIP = explode('.', $_SERVER['REMOTE_ADDR']);
				if (!preg_match('/(^|,)(' . $arrIP[0] . '|\*{1})(\.)' .
				'(' . $arrIP[1] . '|\*{1})(\.)' .
				'(' . $arrIP[2] . '|\*{1})(\.)' .
				'(' . $arrIP[3] . '|\*{1})($|,)/', $sIP))
				{
					$valid_ip = false;
				}
			}
			
			if (!$valid_ip)
			{
				$message .= $this->language->get('text_email_message4') . "\n" . 
				$this->language->get('text_email_message5') . $sIP . "\n" . 
				$this->language->get('text_email_message6') . $_SERVER['REMOTE_ADDR'] . "\n";
				$err = true;
			}

			if ($request['m_sign'] != $sign_hash)
			{
				$message .= $this->language->get('text_email_message2') . "\n";
				$err = true;
			}

			if (!$err)
			{
				// загрузка заказа
				
				$this->load->model('checkout/order');
				$order = $this->model_checkout_order->getOrder($request['m_orderid']);
				
				if (!$order)
				{
					$message .= $this->language->get('text_email_message9') . "\n";
					$err = true;
				}
				else
				{
					$order_curr = ($order['currency_code'] == 'RUR') ? 'RUB' : $order['currency_code'];
					$order_amount = number_format($order['total'], 2, '.', '');
					
					// проверка суммы и валюты
				
					if ($request['m_amount'] != $order_amount)
					{
						$message .= $this->language->get('text_email_message7') . "\n";
						$err = true;
					}

					if ($request['m_curr'] != $order_curr)
					{
						$message .= $this->language->get('text_email_message8') . "\n";
						$err = true;
					}
					
					// проверка статуса
					
					if (!$err)
					{
						switch ($request['m_status'])
						{
							case 'success':
								if ($order['order_status_id'] !== $this->config->get('payeer_order_success_id'))
								{
									$this->model_checkout_order->addOrderHistory($request['m_orderid'], $this->config->get('payeer_order_success_id'));
								}
								break;
								
							default:
								if ($order['order_status_id'] !== $this->config->get('payeer_order_fail_id'))
								{
									$message .= $this->language->get('text_email_message3') . "\n";
									$this->model_checkout_order->addOrderHistory($request['m_orderid'], $this->config->get('payeer_order_fail_id'));
									$err = true;
								}
								break;
						}
					}
				}
			}
			
			$data['order_id'] = $request['m_orderid'];
			
			if ($err)
			{
				$data['order_status'] = 'error';
				$to = $this->config->get('payeer_admin_email');

				if (!empty($to))
				{
					$message = $this->language->get('text_email_message1') . "\n\n" . $message . "\n" . $log_text;
					$headers = "From: no-reply@" . $_SERVER['HTTP_HOST'] . "\r\n" . 
					"Content-type: text/plain; charset=utf-8 \r\n";
					mail($to, $this->language->get('text_email_subject'), $message, $headers);
				}
			}
			else
			{
				$data['order_status'] = 'success';
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/payeer_status.tpl')) 
			{
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/payeer_status.tpl', $data));
			}
			else 
			{
				$this->response->setOutput($this->load->view('default/template/payment/payeer_status.tpl', $data));
			}
		}
   	}

	public function fail() 
	{
		$this->response->redirect($this->url->link('checkout/checkout'));	
		return true;
	}

	public function success() 
	{
		$this->response->redirect($this->url->link('checkout/success'));
		return true;
	}
}