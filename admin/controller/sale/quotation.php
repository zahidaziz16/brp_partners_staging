<?php
class ControllerSaleQuotation extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/quotation');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/quotation');
		$this->load->model('tool/quotation');
		$this->model_tool_quotation->createTable();

		$this->getList();
	}

	public function underreviewquotation() {
		$this->load->language('sale/quotation');
		$this->load->model('sale/quotation');
		if ($this->validate()) {
			$this->model_sale_quotation->underreviewquotation($this->request->get['quotation_id'],$this->request->get['review']);
			$this->session->data['success'] = $this->language->get('quotation_under_review');
		}
		$url = '';

		if (isset($this->request->get['filter_quotation_id'])) {
			$url .= '&filter_quotation_id=' . $this->request->get['filter_quotation_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_quotation_status'])) {
			$url .= '&filter_quotation_status=' . $this->request->get['filter_quotation_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$this->response->redirect($this->url->link('sale/quotation', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function add() {
		$this->load->language('sale/quotation');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/quotation');

		unset($this->session->data['cookie']);

		if ($this->validate()) {
			// API
			$this->load->model('user/api');

			$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

			if ($api_info) {
				$curl = curl_init();

				// Set SSL if required
				if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
					curl_setopt($curl, CURLOPT_PORT, 443);
				}

				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLINFO_HEADER_OUT, true);
				curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/login');
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));

				$json = curl_exec($curl);

				if (!$json) {
					$this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
				} else {
					$response = json_decode($json, true);

					if (isset($response['cookie'])) {
						$this->session->data['cookie'] = $response['cookie'];
					}

					curl_close($curl);
				}
			}
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('sale/quotation');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/quotation');

		unset($this->session->data['cookie']);
		
		if ($this->validate()) {
			// API
			$this->load->model('user/api');

			$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));
			//print_r($api_info);
			if ($api_info) {
				$curl = curl_init();

				// Set SSL if required
				if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
					curl_setopt($curl, CURLOPT_PORT, 443);
				}

				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLINFO_HEADER_OUT, true);
				curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/login');
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));

				$json = curl_exec($curl);

				if (!$json) {
					$this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
				} else {
					$response = json_decode($json, true);
					//print_r($response);
					if (isset($response['cookie'])) {
						$this->session->data['cookie'] = $response['cookie'];
					}

					curl_close($curl);
				}
			}
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('sale/quotation');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/quotation');

		unset($this->session->data['cookie']);

		if (isset($this->request->get['quotation_id']) && $this->validate()) {
			// API
			$this->load->model('user/api');

			$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

			if ($api_info) {
				$curl = curl_init();

				// Set SSL if required
				if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
					curl_setopt($curl, CURLOPT_PORT, 443);
				}

				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLINFO_HEADER_OUT, true);
				curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/login');
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));

				$json = curl_exec($curl);

				if (!$json) {
					$this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
				} else {
					$response = json_decode($json, true);

					if (isset($response['cookie'])) {
						$this->session->data['cookie'] = $response['cookie'];
					}
					curl_close($curl);
				}
			}
		}

		//if (isset($this->session->data['cookie'])) {
			$curl = curl_init();

			// Set SSL if required
			if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
				curl_setopt($curl, CURLOPT_PORT, 443);
			}

			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLINFO_HEADER_OUT, true);
			curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/quotation/delete&quotation_id=' . $this->request->get['quotation_id']);
			//curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $this->session->data['cookie'] . ';');

			$json = curl_exec($curl);

			if (!$json) {
				$this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
			} else {
				$response = json_decode($json, true);

				curl_close($curl);

				if (isset($response['error'])) {
					$this->error['warning'] = $response['error'];
				}
			}
		//}

		if (isset($response['error'])) {
			$this->error['warning'] = $response['error'];
		}

		if (isset($response['success'])) {
			$this->session->data['success'] = $response['success'];

			$url = '';

			if (isset($this->request->get['filter_quotation_id'])) {
				$url .= '&filter_quotation_id=' . $this->request->get['filter_quotation_id'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_quotation_status'])) {
				$url .= '&filter_quotation_status=' . $this->request->get['filter_quotation_status'];
			}

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('sale/quotation', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_quotation_id'])) {
			$filter_quotation_id = $this->request->get['filter_quotation_id'];
		} else {
			$filter_quotation_id = null;
		}
		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}
		if (isset($this->request->get['filter_quotation_status'])) {
			$filter_quotation_status = $this->request->get['filter_quotation_status'];
		} else {
			$filter_quotation_status = null;
		}
		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}
		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}
		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = null;
		}
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.quotation_id';
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['filter_quotation_id'])) {
			$url .= '&filter_quotation_id=' . $this->request->get['filter_quotation_id'];
		}
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}
		if (isset($this->request->get['filter_quotation_status'])) {
			$url .= '&filter_quotation_status=' . $this->request->get['filter_quotation_status'];
		}
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/quotation', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('sale/quotation/add', 'token=' . $this->session->data['token'], 'SSL');
		$data['pdf'] = $this->url->link('sale/quotation/pdf', 'token=' . $this->session->data['token'], 'SSL');
		$data['qcompleted'] = $this->config->get('quotation_completed');
		$data['qexpired'] = $this->config->get('quotation_expired');
		$data['qipay88'] = $this->config->get('quotation_ipay88_payment');
		$data['qcredit'] = $this->config->get('quotation_credit_payment');
		$data['qcod'] = $this->config->get('quotation_cod_payment');
		$data['qbanktransfer'] = $this->config->get('quotation_banktransfer_payment');

		$data['orders'] = array();
		$filter_data = array(
			'filter_quotation_id'      => $filter_quotation_id,
			'filter_customer'	   => $filter_customer,
			'filter_quotation_status'  => $filter_quotation_status,
			'filter_total'         => $filter_total,
			'filter_date_added'    => $filter_date_added,
			'filter_date_modified' => $filter_date_modified,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$order_total = $this->model_sale_quotation->getTotalquotations($filter_data);
		$results = $this->model_sale_quotation->getquotations($filter_data);
		
		foreach ($results as $result) {
			$data['orders'][] = array(
				'quotation_id'  => $result['quotation_id'],
				'quotation_under_review'  => $this->model_sale_quotation->getquotationReview($result['quotation_id']),
				'customer'      => $result['customer'],
				'status_id'		=> $result['quotation_status_id'],
				'status'        => $result['status'],
				'expiry'		=> floor((strtotime($result['date_expired'])-time())/(60 * 60 * 24))+1,
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'shipping_code' => $result['shipping_code'],
				'view'          => $this->url->link('sale/quotation/info', 'token=' . $this->session->data['token'] . '&quotation_id=' . $result['quotation_id'] . $url, 'SSL'),
				//'edit'          => $this->url->link('sale/quotation/edit', 'token=' . $this->session->data['token'] . '&quotation_id=' . $result['quotation_id'] . $url, 'SSL'),
				'priceedit'     => $this->url->link('sale/quotation/priceedit', 'token=' . $this->session->data['token'] . '&quotation_id=' . $result['quotation_id'] . $url, 'SSL'),
				'submit'        => $this->url->link('sale/quotation/underreviewquotation', 'token=' . $this->session->data['token'] . '&quotation_id=' . $result['quotation_id'] ."&review=1".$url, 'SSL'),
				'disallow'      => $this->url->link('sale/quotation/underreviewquotation', 'token=' . $this->session->data['token'] . '&quotation_id=' . $result['quotation_id'] ."&review=0".$url, 'SSL'),
				'delete'        => $this->url->link('sale/quotation/delete', 'token=' . $this->session->data['token'] . '&quotation_id=' . $result['quotation_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_quotation_completed'] = $this->language->get('column_quotation_completed');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_modified'] = $this->language->get('column_date_modified');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_view'] = $this->language->get('column_view');
		$data['column_edit'] = $this->language->get('column_edit');
		$data['column_submit'] = "Invoice";

		$data['entry_return_id'] = $this->language->get('entry_return_id');
		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');


		$data['button_add'] = "Create New Quotation";
		$data['button_edit'] = $this->language->get('button_editdetails');
		$data['button_priceedit'] = $this->language->get('button_priceedit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_view'] = $this->language->get('button_view');

		$data['button_submitquotation'] = $this->language->get('button_submitquotation');
		$data['button_removequotation'] = $this->language->get('button_removequotation');
		$data['button_notifyemail'] = $this->language->get('button_notifyemail');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_quotation_id'])) {
			$url .= '&filter_quotation_id=' . $this->request->get['filter_quotation_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_quotation_status'])) {
			$url .= '&filter_quotation_status=' . $this->request->get['filter_quotation_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_order'] = $this->url->link('sale/quotation', 'token=' . $this->session->data['token'] . '&sort=o.quotation_id' . $url, 'SSL');
		$data['sort_customer'] = $this->url->link('sale/quotation', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('sale/quotation', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$data['sort_total'] = $this->url->link('sale/quotation', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('sale/quotation', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
		$data['sort_date_modified'] = $this->url->link('sale/quotation', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_quotation_id'])) {
			$url .= '&filter_quotation_id=' . $this->request->get['filter_quotation_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_quotation_status'])) {
			$url .= '&filter_quotation_status=' . $this->request->get['filter_quotation_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/quotation', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));

		$data['filter_quotation_id'] = $filter_quotation_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_quotation_status'] = $filter_quotation_status;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;

		$this->load->model('localisation/quotation_status');

		$data['quotation_statuses'] = $this->model_localisation_quotation_status->getquotationStatuses();
		
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/quotation_list.tpl', $data));
	}

	public function getForm() {
		$this->load->model('sale/customer');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['quotation_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_comment'] = $this->language->get('entry_comment');
		$data['entry_affiliate'] = $this->language->get('entry_affiliate');
		$data['entry_address'] = $this->language->get('entry_address');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_address_1'] = $this->language->get('entry_address_1');
		$data['entry_address_2'] = $this->language->get('entry_address_2');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_zone_code'] = $this->language->get('entry_zone_code');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_option'] = $this->language->get('entry_option');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_to_name'] = $this->language->get('entry_to_name');
		$data['entry_to_email'] = $this->language->get('entry_to_email');
		$data['entry_from_name'] = $this->language->get('entry_from_name');
		$data['entry_from_email'] = $this->language->get('entry_from_email');
		$data['entry_theme'] = $this->language->get('entry_theme');
		$data['entry_message'] = $this->language->get('entry_message');
		$data['entry_amount'] = $this->language->get('entry_amount');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_shipping_method'] = $this->language->get('entry_shipping_method');
		$data['entry_payment_method'] = $this->language->get('entry_payment_method');
		$data['entry_coupon'] = $this->language->get('entry_coupon');
		$data['entry_voucher'] = $this->language->get('entry_voucher');
		$data['entry_reward'] = $this->language->get('entry_reward');
		$data['entry_order_status'] = $this->language->get('entry_order_status');

		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_qprice'] = $this->language->get('column_qprice');
		$data['column_total'] = $this->language->get('column_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');
		$data['button_product_add'] = $this->language->get('button_product_add');
		$data['button_voucher_add'] = $this->language->get('button_voucher_add');
		$data['button_apply'] = $this->language->get('button_apply');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_remove'] = $this->language->get('button_remove');

		$data['tab_order'] = $this->language->get('tab_order');
		$data['tab_customer'] = $this->language->get('tab_customer');
		$data['tab_payment'] = $this->language->get('tab_payment');
		$data['tab_shipping'] = $this->language->get('tab_shipping');
		$data['tab_product'] = $this->language->get('tab_product');
		$data['tab_voucher'] = $this->language->get('tab_voucher');
		$data['tab_total'] = $this->language->get('tab_total');

		$data['token'] = $this->session->data['token'];
		//$this->log->write('FLAG TOKEN:'.$data['token']);
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_quotation_id'])) {
			$url .= '&filter_quotation_id=' . $this->request->get['filter_quotation_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_quotation_status'])) {
			$url .= '&filter_quotation_status=' . $this->request->get['filter_quotation_status'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/quotation', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['cancel'] = $this->url->link('sale/quotation', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['quotation_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$quotation_info = $this->model_sale_quotation->getquotation($this->request->get['quotation_id']);
		}

		if (!empty($quotation_info)) {
			$data['quotation_id'] = $this->request->get['quotation_id'];
			$data['store_id'] = $quotation_info['store_id'];

			$data['customer'] = $quotation_info['customer'];
			$data['customer_id'] = $quotation_info['customer_id'];
			$data['customer_group_id'] = $quotation_info['customer_group_id'];
			$data['firstname'] = $quotation_info['firstname'];
			$data['lastname'] = $quotation_info['lastname'];
			$data['email'] = $quotation_info['email'];
			$data['telephone'] = $quotation_info['telephone'];
			$data['fax'] = $quotation_info['fax'];
			$data['account_custom_field'] = $quotation_info['custom_field'];

			$this->load->model('sale/customer');

			$data['addresses'] = $this->model_sale_customer->getAddresses($quotation_info['customer_id']);

			$data['payment_firstname'] = $quotation_info['payment_firstname'];
			$data['payment_lastname'] = $quotation_info['payment_lastname'];
			$data['payment_company'] = $quotation_info['payment_company'];
			$data['payment_address_1'] = $quotation_info['payment_address_1'];
			$data['payment_address_2'] = $quotation_info['payment_address_2'];
			$data['payment_city'] = $quotation_info['payment_city'];
			$data['payment_postcode'] = $quotation_info['payment_postcode'];
			$data['payment_country_id'] = $quotation_info['payment_country_id'];
			$data['payment_zone_id'] = $quotation_info['payment_zone_id'];
			$data['payment_custom_field'] = $quotation_info['payment_custom_field'];
			$data['payment_method'] = $quotation_info['payment_method'];
			$data['payment_code'] = $quotation_info['payment_code'];

			$data['shipping_firstname'] = $quotation_info['shipping_firstname'];
			$data['shipping_lastname'] = $quotation_info['shipping_lastname'];
			$data['shipping_company'] = $quotation_info['shipping_company'];
			$data['shipping_address_1'] = $quotation_info['shipping_address_1'];
			$data['shipping_address_2'] = $quotation_info['shipping_address_2'];
			$data['shipping_city'] = $quotation_info['shipping_city'];
			$data['shipping_postcode'] = $quotation_info['shipping_postcode'];
			$data['shipping_country_id'] = $quotation_info['shipping_country_id'];
			$data['shipping_zone_id'] = $quotation_info['shipping_zone_id'];
			$data['shipping_custom_field'] = $quotation_info['shipping_custom_field'];
			$data['shipping_method'] = $quotation_info['shipping_method'];
			$data['shipping_code'] = $quotation_info['shipping_code'];

			// Add products to the API
			$data['order_products'] = array();

			$products = $this->model_sale_quotation->getquotationProducts($this->request->get['quotation_id']);

			foreach ($products as $product) {
				$data['order_products'][] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $this->model_sale_quotation->getquotationOptions($this->request->get['quotation_id'], $product['quotation_product_id']),
					'quantity'   => $product['quantity'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'reward'     => $product['reward']
				);
			}

			// Add vouchers to the API
			$data['order_vouchers'] = array();

			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';

			$data['order_totals'] = array();

			$order_totals = $this->model_sale_quotation->getquotationTotals($this->request->get['quotation_id']);

			foreach ($order_totals as $order_total) {
				// If coupon, voucher or reward points
				$start = strpos($order_total['title'], '(') + 1;
				$end = strrpos($order_total['title'], ')');

				if ($start && $end) {
					if ($order_total['code'] == 'coupon') {
						$data['coupon'] = substr($order_total['title'], $start, $end - $start);
					}

					if ($order_total['code'] == 'voucher') {
						$data['voucher'] = substr($order_total['title'], $start, $end - $start);
					}

					if ($order_total['code'] == 'reward') {
						$data['reward'] = substr($order_total['title'], $start, $end - $start);
					}
				}
			}

			$data['quotation_status_id'] = $quotation_info['quotation_status_id'];
			$data['comment'] = $quotation_info['comment'];
			$data['affiliate_id'] = $quotation_info['affiliate_id'];
			$data['affiliate'] = $quotation_info['affiliate_firstname'] . ' ' . $quotation_info['affiliate_lastname'];
			$data['currency_code'] = $quotation_info['currency_code'];
		} 
		else {
			$data['quotation_id'] = 0;
			$data['store_id'] = '';
			$data['customer'] = '';
			$data['customer_id'] = '';
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
			$data['firstname'] = '';
			$data['lastname'] = '';
			$data['email'] = '';
			$data['telephone'] = '';
			$data['fax'] = '';
			$data['customer_custom_field'] = array();

			$data['addresses'] = array();

			$data['payment_firstname'] = '';
			$data['payment_lastname'] = '';
			$data['payment_company'] = '';
			$data['payment_address_1'] = '';
			$data['payment_address_2'] = '';
			$data['payment_city'] = '';
			$data['payment_postcode'] = '';
			$data['payment_country_id'] = '';
			$data['payment_zone_id'] = '';
			$data['payment_custom_field'] = array();
			$data['payment_method'] = '';
			$data['payment_code'] = '';

			$data['shipping_firstname'] = '';
			$data['shipping_lastname'] = '';
			$data['shipping_company'] = '';
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';
			$data['shipping_city'] = '';
			$data['shipping_postcode'] = '';
			$data['shipping_country_id'] = '';
			$data['shipping_zone_id'] = '';
			$data['shipping_custom_field'] = array();
			$data['shipping_method'] = '';
			$data['shipping_code'] = '';

			$data['order_products'] = array();
			$data['order_vouchers'] = array();
			$data['order_totals'] = array();
			$data['quotation_status_id'] = $this->config->get('quotation_quotation_status_id');
			$data['comment'] = '';
			$data['affiliate_id'] = '';
			$data['affiliate'] = '';
			$data['currency_code'] = $this->config->get('config_currency');

			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';
		}

		// Stores
		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		// Customer Groups
		$this->load->model('sale/customer_group');

		$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		// Custom Fields
		$this->load->model('sale/custom_field');

		$data['custom_fields'] = array();

		$filter_data = array(
			'sort'  => 'cf.sort_order',
			'order' => 'ASC'
		);

		$custom_fields = $this->model_sale_custom_field->getCustomFields($filter_data);

		foreach ($custom_fields as $custom_field) {
			$data['custom_fields'][] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $this->model_sale_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
				'name'               => $custom_field['name'],
				'value'              => $custom_field['value'],
				'type'               => $custom_field['type'],
				'location'           => $custom_field['location'],
				'sort_order'         => $custom_field['sort_order']
			);
		}

		$this->load->model('localisation/quotation_status');

		$data['quotation_statuses'] = $this->model_localisation_quotation_status->getquotationStatuses();

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		$data['voucher_min'] = $this->config->get('config_voucher_min');

		$this->load->model('sale/voucher_theme');

		$data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/quotation_form.tpl', $data));
	}

	public function priceedit() {
		$this->load->model('sale/quotation');
		$this->load->model('localisation/quotation_status');
		$this->load->model('localisation/currency');
		$this->load->model('tool/upload');

		if (isset($this->request->get['quotation_id'])) {
			$quotation_id = $this->request->get['quotation_id'];
		} else {
			$quotation_id = 0;
		}

		$quotation_info = $this->model_sale_quotation->getquotation($quotation_id);
		
		if ($quotation_info) {
			$data['store_id'] = $quotation_info['store_id'];
			$this->load->language('sale/quotation');
			$currencyinfo = $this->model_localisation_currency->getCurrency($quotation_info['currency_id']);
			$data['currency_symbol'] = $currencyinfo['symbol_left'];
			$data['currency_code'] = $quotation_info['currency_code'];
			$this->document->setTitle($this->language->get('quotation_edit_title'));

			$data['heading_title'] = $this->language->get('quotation_edit_title');
			$data['text_quotationhistory'] = $this->language->get('text_quotationhistory');
			$data['text_loading'] = $this->language->get('text_loading');

			$data['column_product'] = $this->language->get('column_product');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_image'] = $this->language->get('column_image');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_percent'] = $this->language->get('column_percent');
			$data['column_qprice'] = $this->language->get('column_qprice');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');

			$data['entry_order_status'] = $this->language->get('entry_order_status');
			$data['entry_notify'] = $this->language->get('entry_notify');
			$data['entry_comment'] = $this->language->get('entry_comment');
			
			$data['button_edit'] = $this->language->get('button_edit');
			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['button_savequotation'] = $this->language->get('button_savequotation');
			$data['button_savetotal'] = $this->language->get('button_savetotal');

			$data['token'] = $this->session->data['token'];

			$url = '';

			if (isset($this->request->get['filter_quotation_id'])) {
				$url .= '&filter_quotation_id=' . $this->request->get['filter_quotation_id'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_quotation_status'])) {
				$url .= '&filter_quotation_status=' . $this->request->get['filter_quotation_status'];
			}

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('sale/quotation', 'token=' . $this->session->data['token'] . $url, 'SSL')
			);

			//$data['edit'] = $this->url->link('sale/quotation/edit', 'token=' . $this->session->data['token'] . '&quotation_id=' . (int)$this->request->get['quotation_id'], 'SSL');
			$data['cancel'] = $this->url->link('sale/quotation', 'token=' . $this->session->data['token'] . $url, 'SSL');

			$data['quotation_id'] = $this->request->get['quotation_id'];

			$data['comment'] = nl2br($quotation_info['comment']);
			$data['shipping_method'] = $quotation_info['shipping_method'];
			$data['payment_method'] = $quotation_info['payment_method'];
			$data['attachment_url'] = $quotation_info['invoice_attachment'];

			$data['products'] = array();

			$products = $this->model_sale_quotation->getquotationProducts($this->request->get['quotation_id']);
			foreach ($products as $product) {

				$this->load->model('tool/image');
				$result = $this->model_sale_quotation->getProductImage($product['product_id']);

				if((strpos($result, "brp.com.my")===false)) {
					if ($result) {
						$image = $this->model_tool_image->resize($result, $this->config->get($this->config->get('config_theme') . '_image_cart_width'), $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_cart_width'), $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
					}
				} else {
					$image = $this->model_tool_image->resizeBRP($result, $this->config->get($this->config->get('config_theme') . '_image_cart_width'), $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
					//$image = $result['image'];
				}

				$option_data = array();

				$options = $this->model_sale_quotation->getquotationOptions($this->request->get['quotation_id'], $product['quotation_product_id']);
				
				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
							'name'  => $option['name'],
							'product_option_id' => $option['product_option_id'],
							'product_option_value_id' => $option['product_option_value_id'],
							'value' => $option['value'],
							'type'  => $option['type']
						);
					} else {
						
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$option_data[] = array(
								'name'  => $option['name'],
								'value' => $upload_info['name'],
								'product_option_id' => $option['product_option_id'],
								'product_option_value_id' => $option['product_option_value_id'],
								'type'  => $option['type'],
								'href'  => $this->url->link('tool/upload/download', 'token=' . $this->session->data['token'] . '&code=' . $upload_info['code'], 'SSL')
							);
						}
					}
				}

				$data['products'][] = array(
					'quotation_product_id' => $product['quotation_product_id'],
					'product_id'       => $product['product_id'],
					'price_prefix'     => $product['price_prefix'],
					'percent'          => $product['percent'],
					'qprice'           => $product['qprice'],
					'name'    	 	   => $product['name'],
					'thumb'    	 	   => $image,
					'model'    		   => $product['model'],
					'option'   		   => $option_data,
					'ogprice'          => $product['price'],
					'quantity'		   => $product['quantity'],
					'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quotation_info['currency_code'], $quotation_info['currency_value']),
					'total'    		   => $product['total'],
					'href'     		   => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
				);
			}

			$data['vouchers'] = array();

			$data['totals'] = array();

			$totals = $this->model_sale_quotation->getquotationTotals($this->request->get['quotation_id']);

			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'quotation_total_id' => $total['quotation_total_id'],
					'code' => $total['code'],
					'total' => $total['value'],
					'text'  => $this->currency->format($total['value'], $quotation_info['currency_code'], $quotation_info['currency_value']),
				);
			}

			$data['quotation_statuses'] = $this->model_localisation_quotation_status->getquotationStatuses();

			$data['quotation_status_id'] = $quotation_info['quotation_status_id'];
			/*
			// Unset any past sessions this page date_added for the api to work.
			unset($this->session->data['cookie']);
			$this->log->write("BEFORE PERMISSION");
			// Set up the API session
			if ($this->user->hasPermission('modify', 'sale/quotation')) {
							$this->log->write("AFTER PERMISSION");
				$this->load->model('user/api');

				$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

				if ($api_info) {
					$curl = curl_init();

					// Set SSL if required
					if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
						curl_setopt($curl, CURLOPT_PORT, 443);
					}

					curl_setopt($curl, CURLOPT_HEADER, false);
					curl_setopt($curl, CURLINFO_HEADER_OUT, true);
					curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
					curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/login');
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));

					$json = curl_exec($curl);
					$this->log->write(json_decode($json));
					if (!$json) {
						$data['error_warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
					} else {
						$response = json_decode($json, true);
						$this->log->write('Response: '.json_encode($response));
					}

					if (isset($response['cookie'])) {
						$this->session->data['cookie'] = $response['cookie'];
					}
				}
			}

			if (isset($response['cookie'])) {
				$this->session->data['cookie'] = $response['token'];
			} else {
				$data['error_warning'] = $this->language->get('error_permission');
			}
			*/
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('sale/quotation_priceedit.tpl', $data));
		} else {
			$this->load->language('error/not_found');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_not_found'] = $this->language->get('text_not_found');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('error/not_found.tpl', $data));
		}
	}

	public function info() {
		$this->load->model('sale/quotation');

		if (isset($this->request->get['quotation_id'])) {
			$quotation_id = $this->request->get['quotation_id'];
		} else {
			$quotation_id = 0;
		}
	
		$quotation_info = $this->model_sale_quotation->getquotation($quotation_id);
			
		if ($quotation_info) {
			$this->load->language('sale/quotation');

			$this->load->model('localisation/currency');
			$currencyinfo = $this->model_localisation_currency->getCurrency($quotation_info['currency_id']);
			$data['currency_symbol'] = $currencyinfo['symbol_left'];

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('quotation_info_title');

			$data['text_quotation_id'] = $this->language->get('text_quotation_id');
			$data['text_invoice_no'] = $this->language->get('text_invoice_no');
			$data['text_invoice_date'] = $this->language->get('text_invoice_date');
			$data['text_store_name'] = $this->language->get('text_store_name');
			$data['text_store_url'] = $this->language->get('text_store_url');
			$data['text_customer'] = $this->language->get('text_customer');
			$data['text_customer_group'] = $this->language->get('text_customer_group');
			$data['text_email'] = $this->language->get('text_email');
			$data['text_telephone'] = $this->language->get('text_telephone');
			$data['text_fax'] = $this->language->get('text_fax');
			$data['text_total'] = $this->language->get('text_total');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_quotation_status'] = $this->language->get('text_quotation_status');
			$data['text_comment'] = $this->language->get('text_comment');
			$data['text_affiliate'] = $this->language->get('text_affiliate');
			$data['text_commission'] = $this->language->get('text_commission');
			$data['text_ip'] = $this->language->get('text_ip');
			$data['text_forwarded_ip'] = $this->language->get('text_forwarded_ip');
			$data['text_user_agent'] = $this->language->get('text_user_agent');
			$data['text_accept_language'] = $this->language->get('text_accept_language');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_date_modified'] = $this->language->get('text_date_modified');
			$data['text_firstname'] = $this->language->get('text_firstname');
			$data['text_lastname'] = $this->language->get('text_lastname');
			$data['text_company'] = $this->language->get('text_company');
			$data['text_address_1'] = $this->language->get('text_address_1');
			$data['text_address_2'] = $this->language->get('text_address_2');
			$data['text_city'] = $this->language->get('text_city');
			$data['text_postcode'] = $this->language->get('text_postcode');
			$data['text_zone'] = $this->language->get('text_zone');
			$data['text_zone_code'] = $this->language->get('text_zone_code');
			$data['text_country'] = $this->language->get('text_country');
			$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_history'] = $this->language->get('text_history');
			$data['text_loading'] = $this->language->get('text_loading');

			$data['column_product'] = $this->language->get('column_product');
			$data['column_image'] = $this->language->get('column_image');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_qprice'] = $this->language->get('column_qprice');
			$data['column_total'] = $this->language->get('column_total');

			$data['entry_order_status'] = $this->language->get('entry_order_status');
			$data['entry_notify'] = $this->language->get('entry_notify');
			$data['entry_comment'] = $this->language->get('entry_comment');

			$data['button_invoice_print'] = $this->language->get('button_invoice_print');
			$data['button_shipping_print'] = $this->language->get('button_shipping_print');
			$data['button_edit'] = $this->language->get('button_edit');
			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['button_generate'] = $this->language->get('button_generate');
			$data['button_reward_add'] = $this->language->get('button_reward_add');
			$data['button_reward_remove'] = $this->language->get('button_reward_remove');
			$data['button_commission_add'] = $this->language->get('button_commission_add');
			$data['button_commission_remove'] = $this->language->get('button_commission_remove');
			$data['button_history_add'] = $this->language->get('button_history_add');

			$data['tab_order'] = $this->language->get('tab_order');
			$data['tab_payment'] = $this->language->get('tab_payment');
			$data['tab_shipping'] = $this->language->get('tab_shipping');
			$data['tab_product'] = $this->language->get('tab_product');
			$data['tab_history'] = $this->language->get('tab_history');
			$data['tab_fraud'] = $this->language->get('tab_fraud');
			$data['tab_action'] = $this->language->get('tab_action');

			$data['token'] = $this->session->data['token'];

			$url = '';

			if (isset($this->request->get['filter_quotation_id'])) {
				$url .= '&filter_quotation_id=' . $this->request->get['filter_quotation_id'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_quotation_status'])) {
				$url .= '&filter_quotation_status=' . $this->request->get['filter_quotation_status'];
			}

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_date_modified'])) {
				$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('sale/quotation', 'token=' . $this->session->data['token'] . $url, 'SSL')
			);

			
			//$data['edit'] = $this->url->link('sale/quotation/edit', 'token=' . $this->session->data['token'] . '&quotation_id=' . (int)$this->request->get['quotation_id'], 'SSL');
			$data['cancel'] = $this->url->link('sale/quotation', 'token=' . $this->session->data['token'] . $url, 'SSL');

			$data['quotation_id'] = $this->request->get['quotation_id'];

			if ($quotation_info['invoice_no']) {
				$data['invoice_no'] = $quotation_info['invoice_prefix'] . $quotation_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}

			$data['store_name'] = $quotation_info['store_name'];
			$data['store_url'] = $quotation_info['store_url'];
			$data['firstname'] = $quotation_info['firstname'];
			$data['lastname'] = $quotation_info['lastname'];

			if ($quotation_info['customer_id']) {
				$data['customer'] = $this->url->link('sale/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $quotation_info['customer_id'], 'SSL');
			} else {
				$data['customer'] = '';
			}

			$this->load->model('sale/customer_group');

			$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($quotation_info['customer_group_id']);

			if ($customer_group_info) {
				$data['customer_group'] = $customer_group_info['name'];
			} else {
				$data['customer_group'] = '';
			}

			$data['email'] = $quotation_info['email'];
			$data['telephone'] = $quotation_info['telephone'];
			$data['fax'] = $quotation_info['fax'];

			$data['account_custom_field'] = $quotation_info['custom_field'];

			// Uploaded files
			$this->load->model('tool/upload');

			// Custom Fields
			$this->load->model('sale/custom_field');

			$data['account_custom_fields'] = array();

			$custom_fields = $this->model_sale_custom_field->getCustomFields();

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'account' && isset($quotation_info['custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_sale_custom_field->getCustomFieldValue($quotation_info['custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['account_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($quotation_info['custom_field'][$custom_field['custom_field_id']])) {
						foreach ($quotation_info['custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_sale_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['account_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['account_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $quotation_info['custom_field'][$custom_field['custom_field_id']]
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($quotation_info['custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['account_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name']
							);
						}
					}
				}
			}

			$data['comment'] = nl2br($quotation_info['comment']);
			$data['shipping_method'] = $quotation_info['shipping_method'];
			$data['payment_method'] = $quotation_info['payment_method'];
			$data['total'] = $this->currency->format($quotation_info['total'], $quotation_info['currency_code'], $quotation_info['currency_value']);

			$this->load->model('sale/customer');

			$data['reward'] = $quotation_info['reward'];

			$data['reward_total'] = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($this->request->get['quotation_id']);

			$data['affiliate_firstname'] = $quotation_info['affiliate_firstname'];
			$data['affiliate_lastname'] = $quotation_info['affiliate_lastname'];

			if ($quotation_info['affiliate_id']) {
				$data['affiliate'] = $this->url->link('marketing/affiliate/edit', 'token=' . $this->session->data['token'] . '&affiliate_id=' . $quotation_info['affiliate_id'], 'SSL');
			} else {
				$data['affiliate'] = '';
			}

			$data['commission'] = $this->currency->format($quotation_info['commission'], $quotation_info['currency_code'], $quotation_info['currency_value']);

			$this->load->model('marketing/affiliate');

			$data['commission_total'] = $this->model_marketing_affiliate->getTotalTransactionsByOrderId($this->request->get['quotation_id']);

			$this->load->model('localisation/quotation_status');

			$quotation_status_info = $this->model_localisation_quotation_status->getquotationStatus($quotation_info['quotation_status_id']);

			if ($quotation_status_info) {
				$data['quotation_status'] = $quotation_status_info['name'];
			} else {
				$data['quotation_status'] = '';
			}

			$data['ip'] = $quotation_info['ip'];
			$data['user_agent'] = $quotation_info['user_agent'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($quotation_info['date_added']));
			$data['date_modified'] = date($this->language->get('date_format_short'), strtotime($quotation_info['date_modified']));

			// Payment
			$data['payment_firstname'] = $quotation_info['payment_firstname'];
			$data['payment_lastname'] = $quotation_info['payment_lastname'];
			$data['payment_company'] = $quotation_info['payment_company'];
			$data['payment_address_1'] = $quotation_info['payment_address_1'];
			$data['payment_address_2'] = $quotation_info['payment_address_2'];
			$data['payment_city'] = $quotation_info['payment_city'];
			$data['payment_postcode'] = $quotation_info['payment_postcode'];
			$data['payment_zone'] = $quotation_info['payment_zone'];
			$data['payment_zone_code'] = $quotation_info['payment_zone_code'];
			$data['payment_country'] = $quotation_info['payment_country'];

			// Custom fields
			$data['payment_custom_fields'] = array();

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address' && isset($quotation_info['payment_custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_sale_custom_field->getCustomFieldValue($quotation_info['payment_custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['payment_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($quotation_info['payment_custom_field'][$custom_field['custom_field_id']])) {
						foreach ($quotation_info['payment_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_sale_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['payment_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name'],
									'sort_order' => $custom_field['sort_order']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['payment_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $quotation_info['payment_custom_field'][$custom_field['custom_field_id']],
							'sort_order' => $custom_field['sort_order']
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($quotation_info['payment_custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['payment_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}
				}
			}

			// Shipping
			$data['shipping_firstname'] = $quotation_info['shipping_firstname'];
			$data['shipping_lastname'] = $quotation_info['shipping_lastname'];
			$data['shipping_company'] = $quotation_info['shipping_company'];
			$data['shipping_address_1'] = $quotation_info['shipping_address_1'];
			$data['shipping_address_2'] = $quotation_info['shipping_address_2'];
			$data['shipping_city'] = $quotation_info['shipping_city'];
			$data['shipping_postcode'] = $quotation_info['shipping_postcode'];
			$data['shipping_zone'] = $quotation_info['shipping_zone'];
			$data['shipping_zone_code'] = $quotation_info['shipping_zone_code'];
			$data['shipping_country'] = $quotation_info['shipping_country'];

			$data['shipping_custom_fields'] = array();

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address' && isset($quotation_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_sale_custom_field->getCustomFieldValue($quotation_info['shipping_custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['shipping_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($quotation_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
						foreach ($quotation_info['shipping_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_sale_custom_field->getCustomFieldValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['shipping_custom_fields'][] = array(
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name'],
									'sort_order' => $custom_field['sort_order']
								);
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['shipping_custom_fields'][] = array(
							'name'  => $custom_field['name'],
							'value' => $quotation_info['shipping_custom_field'][$custom_field['custom_field_id']],
							'sort_order' => $custom_field['sort_order']
						);
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($quotation_info['shipping_custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['shipping_custom_fields'][] = array(
								'name'  => $custom_field['name'],
								'value' => $upload_info['name'],
								'sort_order' => $custom_field['sort_order']
							);
						}
					}
				}
			}

			$data['products'] = array();

			$products = $this->model_sale_quotation->getquotationProducts($this->request->get['quotation_id']);
			
			foreach ($products as $product) {

				$this->load->model('tool/image');
				$result = $this->model_sale_quotation->getProductImage($product['product_id']);

				if((strpos($result, "brp.com.my")===false)) {
					if ($result) {
						$image = $this->model_tool_image->resize($result, $this->config->get($this->config->get('config_theme') . '_image_cart_width'), $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_cart_width'), $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
					}
				} else {
					$image = $this->model_tool_image->resizeBRP($result, $this->config->get($this->config->get('config_theme') . '_image_cart_width'), $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
					//$image = $result['image'];
				}

				$option_data = array();

				$options = $this->model_sale_quotation->getquotationOptions($this->request->get['quotation_id'], $product['quotation_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
						);
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$option_data[] = array(
								'name'  => $option['name'],
								'value' => $upload_info['name'],
								'type'  => $option['type'],
								'href'  => $this->url->link('tool/upload/download', 'token=' . $this->session->data['token'] . '&code=' . $upload_info['code'], 'SSL')
							);
						}
					}
				}

				$data['products'][] = array(
					'quotation_product_id' => $product['quotation_product_id'],
					'product_id'       => $product['product_id'],
					'name'    	 	   => $product['name'],
					'thumb'    	 	   => $image,
					'model'    		   => $product['model'],
					'option'   		   => $option_data,
					'quantity'		   => $product['quantity'],
					'qprice'           => $this->currency->format($product['qprice'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quotation_info['currency_code'], $quotation_info['currency_value']),
					'ogprice'           => $product['price'],
					'price'    		   => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quotation_info['currency_code'], $quotation_info['currency_value']),
					'total'    		   =>  $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $quotation_info['currency_code'], $quotation_info['currency_value']),
					'href'     		   => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL')
				);
			}

			$data['vouchers'] = array();

			$data['totals'] = array();

			$totals = $this->model_sale_quotation->getquotationTotals($this->request->get['quotation_id']);

			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $quotation_info['currency_code'], $quotation_info['currency_value']),
				);
			}

			$data['quotation_statuses'] = $this->model_localisation_quotation_status->getquotationStatuses();

			$data['quotation_status_id'] = $quotation_info['quotation_status_id'];

			// Unset any past sessions this page date_added for the api to work.
			unset($this->session->data['cookie']);

			// Set up the API session
			if ($this->user->hasPermission('modify', 'sale/quotation')) {
				$this->load->model('user/api');

				$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

				if ($api_info) {
					$curl = curl_init();

					// Set SSL if required
					if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
						curl_setopt($curl, CURLOPT_PORT, 443);
					}

					curl_setopt($curl, CURLOPT_HEADER, false);
					curl_setopt($curl, CURLINFO_HEADER_OUT, true);
					curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
					curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/login');
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));

					$json = curl_exec($curl);

					if (!$json) {
						$data['error_warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
					} else {
						$response = json_decode($json, true);
					}

					if (isset($response['cookie'])) {
						$this->session->data['cookie'] = $response['cookie'];
					}
				}
			}

			if (isset($response['cookie'])) {
				$this->session->data['cookie'] = $response['cookie'];
			} else {
				$data['error_warning'] = $this->language->get('error_permission');
			}

			$data['frauds'] = array();

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('sale/quotation_info.tpl', $data));
		} else {
			$this->load->language('error/not_found');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_not_found'] = $this->language->get('text_not_found');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('error/not_found.tpl', $data));
		}
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'sale/quotation')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}

	public function createInvoiceNo() {
		$this->load->language('sale/quotation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/quotation')) {
			$json['error'] = $this->language->get('error_permission');
		} elseif (isset($this->request->get['quotation_id'])) {
			if (isset($this->request->get['quotation_id'])) {
				$quotation_id = $this->request->get['quotation_id'];
			} else {
				$quotation_id = 0;
			}

			$this->load->model('sale/quotation');

			$invoice_no = $this->model_sale_quotation->createInvoiceNo($quotation_id);

			if ($invoice_no) {
				$json['invoice_no'] = $invoice_no;
			} else {
				$json['error'] = $this->language->get('error_action');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addReward() {
		$this->load->language('sale/quotation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/quotation')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['quotation_id'])) {
				$quotation_id = $this->request->get['quotation_id'];
			} else {
				$quotation_id = 0;
			}

			$this->load->model('sale/quotation');

			$quotation_info = $this->model_sale_quotation->getquotation($quotation_id);

			if ($quotation_info && $quotation_info['customer_id'] && ($quotation_info['reward'] > 0)) {
				$this->load->model('sale/customer');

				$reward_total = $this->model_sale_customer->getTotalCustomerRewardsByOrderId($quotation_id);

				if (!$reward_total) {
					$this->model_sale_customer->addReward($quotation_info['customer_id'], $this->language->get('text_quotation_id') . ' #' . $quotation_id, $quotation_info['reward'], $quotation_id);
				}
			}

			$json['success'] = $this->language->get('text_reward_added');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeReward() {
		$this->load->language('sale/quotation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/quotation')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['quotation_id'])) {
				$quotation_id = $this->request->get['quotation_id'];
			} else {
				$quotation_id = 0;
			}

			$this->load->model('sale/quotation');

			$quotation_info = $this->model_sale_quotation->getquotation($quotation_id);

			if ($quotation_info) {
				$this->load->model('sale/customer');

				$this->model_sale_customer->deleteReward($quotation_id);
			}

			$json['success'] = $this->language->get('text_reward_removed');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addCommission() {
		$this->load->language('sale/quotation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/quotation')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['quotation_id'])) {
				$quotation_id = $this->request->get['quotation_id'];
			} else {
				$quotation_id = 0;
			}

			$this->load->model('sale/quotation');

			$quotation_info = $this->model_sale_quotation->getquotation($quotation_id);

			if ($quotation_info) {
				$this->load->model('marketing/affiliate');

				$affiliate_total = $this->model_marketing_affiliate->getTotalTransactionsByOrderId($quotation_id);

				if (!$affiliate_total) {
					$this->model_marketing_affiliate->addTransaction($quotation_info['affiliate_id'], $this->language->get('text_quotation_id') . ' #' . $quotation_id, $quotation_info['commission'], $quotation_id);
				}
			}

			$json['success'] = $this->language->get('text_commission_added');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeCommission() {
		$this->load->language('sale/quotation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/quotation')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['quotation_id'])) {
				$quotation_id = $this->request->get['quotation_id'];
			} else {
				$quotation_id = 0;
			}

			$this->load->model('sale/quotation');

			$quotation_info = $this->model_sale_quotation->getquotation($quotation_id);

			if ($quotation_info) {
				$this->load->model('marketing/affiliate');

				$this->model_marketing_affiliate->deleteTransaction($quotation_id);
			}

			$json['success'] = $this->language->get('text_commission_removed');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function history() {
		$this->load->language('sale/quotation');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_notify'] = $this->language->get('column_notify');
		$data['column_comment'] = $this->language->get('column_comment');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['histories'] = array();

		$this->load->model('sale/quotation');

		$results = $this->model_sale_quotation->getquotationHistories($this->request->get['quotation_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$history_total = $this->model_sale_quotation->getTotalquotationHistories($this->request->get['quotation_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('sale/quotation/history', 'token=' . $this->session->data['token'] . '&quotation_id=' . $this->request->get['quotation_id'] . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('sale/quotation_history.tpl', $data));
	}

	public function pdf() {

		require_once('../tcpdf/tcpdf.php');

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor($this->config->get('config_owner'));
		$pdf->SetTitle('Quotation PDF');
		$pdf->SetSubject('Quotation');
		$pdf->SetKeywords('Quotation, PDF');

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		$this->load->language('sale/quotation');

		$data['title'] = $this->language->get('text_invoice');

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			//$data['logo'] = HTTP_CATALOG . 'image/' . $this->config->get('config_logo');
			$data['logo'] = HTTP_CATALOG . 'image/' . str_replace(" ", "%20",$this->config->get('config_logo'));
		} else {
			$data['logo'] = '';
		}
		
		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		$data['text_invoice'] = $this->language->get('text_invoice');
		$data['text_order_detail'] = $this->language->get('text_order_detail');
		$data['text_quotation_id'] = $this->language->get('text_quotation_id');
		$data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_website'] = $this->language->get('text_website');
		$data['text_to'] = $this->language->get('text_to');
		$data['text_ship_to'] = $this->language->get('text_ship_to');
		$data['text_payment_method'] = $this->language->get('text_payment_method');
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['customer_name'] = $this->language->get('customer_name');

		$data['text_store_info'] = $this->language->get('storeinfo');
		$data['customer_info'] = $this->language->get('customerinfo');
		$data['discount_done'] = $this->language->get('discount_done');
		
		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_qprice'] = $this->language->get('column_qprice');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_image'] = $this->language->get('column_image');
		$data['column_comment'] = $this->language->get('column_comment');

		$data['quotation_actualprice'] = $this->config->get('quotation_actualprice');

		$this->load->model('sale/quotation');

		$this->load->model('setting/setting');

		$data['orders'] = array();

		$orders = array();

		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} elseif (isset($this->request->get['quotation_id'])) {
			$orders[] = $this->request->get['quotation_id'];
		}

		foreach ($orders as $quotation_id) {
			$quotation_info = $this->model_sale_quotation->getquotation($quotation_id);

			if ($quotation_info) {
				$store_info = $this->model_setting_setting->getSetting('config', $quotation_info['store_id']);

				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = $store_info['config_fax'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}
				$this->load->model('sale/customer');

			    $data['firstname'] = $quotation_info['firstname'];
			    $data['lastname'] = $quotation_info['lastname'];

				$this->load->model('tool/upload');
				$this->load->model('tool/image');
				$discounttotal = 0;
				$product_data = array();

				$products = $this->model_sale_quotation->getquotationProducts($quotation_id);

				foreach ($products as $product) {

					$option_data = array();

					$options = $this->model_sale_quotation->getquotationOptions($quotation_id, $product['quotation_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}

						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
						);
					}

					$result = $this->model_sale_quotation->getProductImage($product['product_id']);
					/*
					if (is_file(DIR_IMAGE . $image)) {
						$image = $this->model_tool_image->resize($image, 60, 60);
						$image = str_replace(" ","%20",$image);
					} else {
						$image = $this->model_tool_image->resize('no_image.png', 60, 60);
					}
					*/
					if((strpos($result, "brp.com.my")===false)) {
						if ($result) {
							$image = $this->model_tool_image->resize($result, $this->config->get($this->config->get('config_theme') . '_image_cart_width'), $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
							$image = str_replace(" ","%20",$image);
						} else {
							$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_cart_width'), $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
						}
					} else {
						$image = $this->model_tool_image->resizeBRP($result, $this->config->get($this->config->get('config_theme') . '_image_cart_width'), $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
						$image = str_replace(" ","%20",$image);
						//$image = $result['image'];
					}

					$product_data[] = array(
						'name'     => $product['name'],
						'thumb'     => $image,
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quotation_info['currency_code'], $quotation_info['currency_value']),
						'qprice'    => $this->currency->format($product['qprice'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quotation_info['currency_code'], $quotation_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $quotation_info['currency_code'], $quotation_info['currency_value'])
					);
					$discounttotal = $discounttotal + (($product['price'] - $product['qprice']) * $product['quantity']);
				}
				$discounttotal = $this->currency->format($discounttotal,$quotation_info['currency_code'], $quotation_info['currency_value']);
				$voucher_data = array();

				$total_data = array();

				$totals = $this->model_sale_quotation->getquotationTotals($quotation_id);

				foreach ($totals as $total) {
					$total_data[] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $quotation_info['currency_code'], $quotation_info['currency_value']),
					);
				}	
				$pdf->AddPage();
				$tbl = "";

				$quotationcomment = $this->model_sale_quotation->getLatestComment($quotation_id);

				if($data['logo']) { 
					$tbl .=  '<table cellpadding="1" cellspacing="1" border="0" ><tr style="text-align:left;"><td><img src="' . $data['logo'] . '" border="0" width="150px" /></td></tr></table>';
				}

				$tbl .= '<table border="0.2" cellpadding="4">';
				$tbl .= '<tbody><tr><td><b>'.$quotation_info['store_name'].'</b><br/><b>Address: </b> '.nl2br($store_address)."</td>";
				$tbl .= '<td><b>'.$data['text_date_added'].'</b> '.date($this->language->get('date_format_short'), strtotime($quotation_info['date_added'])).'<br />';
			    $tbl .= '<b>' . $data['text_quotation_id'].'</b> '.$quotation_id.'<br />';
			    $tbl .= '</td></tr></tbody></table>';
			    $tbl .= '<table border="0.2" cellpadding="4">';
			    $tbl .=  '<tr><td><b>'.$data['text_store_info'].'</b></td><td><b>'.$data['customer_info'].'</b></td></tr>';
				$tbl .= '<tr><td style="width: 50%;"><b>'.$data['text_telephone'].'</b> '.$store_telephone . '<br />';
			        
		        if ($store_fax) { 
		          $tbl .= '<b>'.$data['text_fax'].'</b>'.$store_fax.'<br /> ';
		        }
		        $tbl .= '<b>' . $data['text_email'] . '</b>  '.rtrim($store_email, '/').'<br/><b>'.$data['text_website'].'</b><a href=' . rtrim($quotation_info['store_url'], '/') . '>'.rtrim($quotation_info['store_url'], '/').'</a></td>';

		        $tbl .= '<td style="width: 50%;">';
		        $tbl .= '<b>'.$data['customer_name'].'</b> '.$quotation_info['firstname'] .' '.$quotation_info['lastname'].'<br />';
		        if ($quotation_info['telephone']) { 
		          $tbl .= '<b>'.$data['text_telephone'].'</b> '.$quotation_info['telephone'] . '<br />';
		        }
		        $tbl .= '<b>' . $data['text_email'] . '</b>  '.$quotation_info['email']."</td></tr>";
				$tbl .= '</table>';
				if(!$data['quotation_actualprice']) {
					$tbl .= '<table border="0.2" cellpadding="4" ><thead><tr><td style="width: 12%;" align="left"><b>' . $data['column_image'] . '</b></td><td  style="width: 38%;" align="left"><b>' . $data['column_product'] . '</b></td><td style="width: 14%;" align="left"><b>' . $data['column_model'] . '</b></td><td style="width: 8%;" align="right"><b>' . $data['column_quantity'] . '</b></td><td style="width: 16%;" align="right"><b>' . $data['column_qprice'] . '</b></td><td align="right" style="width: 12%;"><b>' . $data['column_total'] . '</b></td></tr></thead>';
				} else {
					$tbl .= '<table border="0.2" cellpadding="4" ><thead><tr><td style="width: 12%;" align="left"><b>' . $data['column_image'] . '</b></td><td  style="width: 38%;" align="left"><b>' . $data['column_product'] . '</b></td><td style="width: 8%;" align="right"><b>' . $data['column_quantity'] . '</b></td><td style="width: 15%;" align="center"><b>' . $data['column_price'] . '</b></td><td style="width: 15%;" align="center"><b>' . $data['column_qprice'] . '</b></td><td align="right" style="width: 12%;"><b>' . $data['column_total'] . '</b></td></tr></thead>';
				}
				$tbl .= '<tbody>';
				foreach ($product_data as $product) { 
					if(!$data['quotation_actualprice']) {
						$tbl .= '<tr><td style="width: 12%;" align="left">';
					    if($product['thumb']) {
					    	$tbl .='<img src="'.$product['thumb'].'" alt="'.$product['name'].'" title="'.$product['name'].'" class="img-thumbnail" />';
					    }
					    $tbl .= '</td>';
						$tbl .= '<td style="width: 38%;" align="left">'.$product['name'];
						foreach ($product['option'] as $option) {
							$tbl .= '<br /><small> - ' . $option['name'] . ': ' . $option['value'] . '</small>';
						}
						$tbl .= ' </td><td style="width: 14%;" align="left">' . $product['model'] . '</td><td style="width: 8%;" align="right">' . $product['quantity'] . '</td><td style="width: 16%;" align="right">' . $product['qprice'] . '</td><td style="width: 12%;" align="right">' . $product['total'] . '</td></tr>';
					} else {
						$tbl .= '<tr><td style="width: 12%;" align="left">';
					    if($product['thumb']) {
					    	$tbl .='<img src="'.$product['thumb'].'" alt="'.$product['name'].'" title="'.$product['name'].'" class="img-thumbnail" />';
					    }
					    $tbl .= '</td>';
						$tbl .= '<td style="width: 38%;" align="left">'.$product['name'];
						foreach ($product['option'] as $option) {
							$tbl .= '<br /><small> - ' . $option['name'] . ': ' . $option['value'] . '</small>';
						}
						$tbl .= '<br /><small> - ' . $product['model'] . '</small>';
						$tbl .= ' </td><td style="width: 8%;" align="right">' . $product['quantity'] . '</td><td style="width: 15%;" align="right">' . $product['price'] . '</td><td style="width: 15%;" align="right">' . $product['qprice'] . '</td><td style="width: 12%;" align="right">' . $product['total'] . '</td></tr>';
					}	
				}

				foreach ($voucher_data as $voucher) { 
					$tbl .= '<tr><td>' . $voucher['description'] . '</td><td></td><td align="right">1</td><td align="right">' . $voucher['amount'] . '</td><td align="right">' . $voucher['amount'] . '</td></tr>';
				}

				if($this->config->get('quotation_discountdone')) {
					$tbl .= '<tr><td align="right" colspan="5"><b>'.$data['discount_done'].'</b></td><td align="right">' . $discounttotal . '</td></tr>';
				}

				foreach ($total_data as $total) { 
					$tbl .= '<tr><td align="right" colspan="5"><b>' . $total['title'] . '</b></td><td align="right">' . $total['text'] . '</td></tr>';
				}

				$tbl .= '</tbody></table>';

				if (isset($quotationcomment['comment']) && $quotationcomment['comment'] != "" && $quotationcomment['comment'] != 0) { 
		          $tbl .= '<table border="1" cellpadding="4"><thead><tr><td><b>' . $data['column_comment'] . '</b></td></tr></thead><tbody><tr><td>' .  nl2br($quotationcomment['comment']) . '</td></tr></tbody></table>'; 
		        }
				
				$pdf->writeHTML($tbl, true, false, false, false, '');
			}
		}

		$pdf->Output('quotation.pdf', 'I');

	}

	public function api() {
		$this->load->language('sale/quotation');
		if ($this->validate()) {
			// Store
			if (isset($this->request->get['store_id'])) {
				$store_id = $this->request->get['store_id'];
			} else {
				$store_id = 0;
			}

			$this->load->model('setting/store');
			$store_info = $this->model_setting_store->getStore($store_id);

			if ($store_info) {
				$url = $store_info['ssl'];
			} else {
				$url = HTTPS_CATALOG;
			}

			if (isset($this->request->get['api'])) {
				// Include any URL perameters
				$url_data = array();
				foreach($this->request->get as $key => $value) {
					if ($key != 'route' && $key != 'token' && $key != 'store_id') {
						$url_data[$key] = $value;
					}
				}
				if(isset($this->request->get['quotation_id'])){
					$this->session->data['quotation_id'] = $this->request->get['quotation_id'];
				}

				$curl = curl_init();
				// Set SSL if required
				if (substr($url, 0, 5) == 'https') {
					curl_setopt($curl, CURLOPT_PORT, 443);
				}

				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLINFO_HEADER_OUT, true);
				curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=' . $this->request->get['api'] . ($url_data ? '&' . http_build_query($url_data) : ''));
				if ($this->request->post) {
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->request->post));
				}
				
				//curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $this->session->data['cookie'] . ';');
				$json = curl_exec($curl);
				curl_close($curl);
				//$this->log->write('API:'.$this->request->get['api']);
			}
		} 
		else {
			$response = array();
			$response['error'] = $this->error;
			$json = json_encode($response);
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($json);
		//$this->log->write('FLAG JSON:'.$json);
	}

	public function savedetails() {
		if(isset($this->request->get['product'])) {
			foreach ($this->request->get['product'] as $product) {
				//$this->log->write(print_r($product,true));
				$this->load->model('sale/quotation');
				$this->model_sale_quotation->updateprice($product['product_id'],$product['price']);
			}
		}
		$json = array();
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($json);
	}
}