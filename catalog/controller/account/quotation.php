<?php
class ControllerAccountquotation extends Controller {
	private $error = array();

	public function rejectquotation() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/quotation', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		if (isset($this->request->post['quotation_id'])) {
			$quotation_id = $this->request->post['quotation_id'];
		} else {
			$quotation_id = 0;
		}

		$this->load->model('account/quotation');
		$quotation_info = $this->model_account_quotation->getquotation($quotation_id);
    
		if($quotation_info) {
			if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
				$this->load->model("quotation/order");
				$this->model_quotation_order->rejectquotation($this->request->post['quotation_id'],$this->request->post['comment']);
				$this->session->data['success'] = "Quotation has been rejected successfuly and is under review.";
			}
		}	
		$this->response->redirect($this->url->link('account/quotation', '', 'SSL'));
	}

	public function pdf() {

    $this->load->language('account/quotation');

    if (isset($this->request->get['quotation_id'])) {
      $quotation_id = $this->request->get['quotation_id'];
    } else {
      $quotation_id = 0;
    }

    if (!$this->customer->isLogged()) {
      $this->session->data['redirect'] = $this->url->link('account/quotation/info', 'quotation_id=' . $quotation_id, 'SSL');

      $this->response->redirect($this->url->link('account/login', '', 'SSL'));
    }

    $data['title'] = $this->language->get('text_invoice');

    if ($this->request->server['HTTPS']) {
      $data['base'] = HTTPS_SERVER;
    } else {
      $data['base'] = HTTP_SERVER;
    }

    if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
      //$data['logo'] = HTTP_SERVER . 'image/' . $this->config->get('config_logo');
	  $data['logo'] = HTTP_SERVER . 'image/' . str_replace(" ", "%20",$this->config->get('config_logo'));
    } else {
      $data['logo'] = '';
    }
    
	if (isset($this->request->get['print'])) {
		$flagPrint = $this->request->get['print'];
		$title = "Quotation";
	}
	else $title = "Quotation";
    $data['direction'] = $this->language->get('direction');
    $data['lang'] = $this->language->get('code');

    $data['text_invoice'] = $this->language->get('text_invoice');
    $data['text_quotation_detail'] = $this->language->get('text_quotation_detail');
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

    $data['column_product'] = "PRODUCT & DESCRIPTION";
    $data['column_quantity'] = "QUANTITY";
    $data['column_price'] = "UNIT PRICE";
    $data['column_total'] = "TOTAL PRICE";
	
	require_once('tcpdf/mycpdf.php');
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor($this->config->get('config_owner'));
	$pdf->SetTitle('Quotation PDF');
	$pdf->SetSubject('PDF Invoice');
	$pdf->SetKeywords('TCPDF, PDF Invoice');
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	$fontname = TCPDF_FONTS::addTTFfont('tcpdf/fonts/Calibri.ttf', 'TrueType', '', 32);
	$fontbold = TCPDF_FONTS::addTTFfont('tcpdf/fonts/CalibriB.ttf', 'TrueType', '', 32);
	$pdf->SetFont($fontname, '', 10, '', false);

    $this->load->model('account/quotation');
    $this->load->model('setting/setting');

    $review = $this->model_account_quotation->getquotationReview($quotation_id);
   
    if(!$review) {
      $this->session->data['error_warning'] =  $this->language->get('error_under_review');
      $this->response->redirect($this->url->link('account/quotation', '', 'SSL'));
    }

    $quotation_info = $this->model_account_quotation->getquotation($quotation_id);
    $qcsid = $this->config->get('quotation_quotation_reject_status_id'); //3=rejected
	$qcompleted = $this->config->get('quotation_completed');

    if($quotation_info['quotation_status_id'] == $qcsid) {
      $this->session->data['error_warning'] = $this->language->get('error_complete');
      $this->response->redirect($this->url->link('account/quotation', '', 'SSL'));
    }

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
        $data['quotation_actualprice'] = $this->config->get('quotation_actualprice');
        $this->load->model('tool/upload');
        $this->load->model('tool/image');
        $discounttotal = 0;
        $product_data = array();

        $products = $this->model_account_quotation->getquotationProducts($quotation_id);

        foreach ($products as $product) {
			$option_data = array();
			$options = $this->model_account_quotation->getquotationOptions($this->request->get['quotation_id'], $product['quotation_product_id']);
			foreach ($options as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				}
				else {
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

			$product_data[] = array(
				'name'     => $product['name'],
				'option'   => $option_data,
				'quantity' => $product['quantity'],
				'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quotation_info['currency_code'], $quotation_info['currency_value']),
				'qprice'   => $this->currency->format($product['qprice'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quotation_info['currency_code'], $quotation_info['currency_value']),
				'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $quotation_info['currency_code'], $quotation_info['currency_value'])
			);
			$discounttotal = $discounttotal + (($product['price'] - $product['qprice']) * $product['quantity']);
        }
        $discounttotal = $this->currency->format($discounttotal,$quotation_info['currency_code'], $quotation_info['currency_value']);
        $pdf->AddPage(); $tbl = "";
        $voucher_data = array();

        $total_data = array();
        $totals = $this->model_account_quotation->getquotationTotals($quotation_id);
        foreach ($totals as $total) {
          $total_data[] = array(
            'title' => $total['title'],
            'text'  => $this->currency->format($total['value'], $quotation_info['currency_code'], $quotation_info['currency_value']),
          );
        } 

        $quotationcomment = $this->model_account_quotation->getLatestComment($quotation_id);
		
		$pdf_address = "";
		$pdf_owner = "";
		$pdf_telephone = "-";
		$pdf_email = "-";
		
		if ($this->config->get('config_address') != null){
			$pdf_address = $this->config->get('config_address');
		}
		if ($this->config->get('config_owner') != null){
			$pdf_owner = $this->config->get('config_owner');
		}
		if ($this->config->get('config_telephone') != null){
			$pdf_telephone = $this->config->get('config_telephone');
		}
		if ($this->config->get('config_email') != null){
			$pdf_email = $this->config->get('config_email');
		}

		
		
        if($data['logo']) {
			$dateExp = date_format(date_create($quotation_info['date_expired']), "d-M-Y");
            $tbl .= '<table cellpadding="1" cellspacing="1" border="0" width="100%">
						<tr style="text-align:left;">
							<td><img src="' . $data['logo'] . '" border="0" width="150px" /></td>
							<td style="text-align:right; color:#e06666;"><br><br><span style="font-size:28px;"><b>'.$title.'</b></span></td>
						</tr>
					</table><hr style="height:2px;">
					<table border="0" width="100%">
						<tr>
							<td style="width:48%;">
								<table>
									<tr style="background-color:#e06666; color:white;"><td><b>'.$pdf_owner.'</b></td></tr>
									<tr><td>'.$pdf_address.'</td></tr>
									<tr><td>Phone: '.$pdf_telephone.'</td></tr>
								</table>
								
							</td>
							<td style="width:17%;"></td>
							<td style="text-align:center; width:35%;">
								<table>
									<tr>
										<td style="text-align:left;"><b>Date:</b></td>
										<td style="border-bottom:0.5px solid black; border-right:0.5px solid black; border-left:0.5px solid black;">'.date('d-M-Y', strtotime($quotation_info['date_added'])).'</td>
									</tr>
									<tr>
										<td style="text-align:left;"><b>Quotation Ref:</b></td>
										<td style="border-bottom:0.5px solid black; border-right:0.5px solid black; border-left:0.5px solid black;">Q'.sprintf('%05d', $quotation_info['quotation_id']).'</td>
									</tr>
									<tr>
										<td style="text-align:left;"><b>Valid Until:</b></td>
										<td style="border-bottom:0.5px solid black; border-right:0.5px solid black; border-left:0.5px solid black;">'.$dateExp.'</td>
									</tr>
								</table>
							</td>
						</tr>
					  </table>';
        }
		$customerAddress = $quotation_info['payment_address_1'].'<br>'.$quotation_info['payment_city'].' '.$quotation_info['payment_postcode'].', '.$quotation_info['payment_zone'].', '.$quotation_info['payment_country'];
		if($quotation_info['payment_company'] == '') $company = "N/A";
		else $company = $quotation_info['payment_company'];
        $tbl .= '<br><br>
				<table cellpadding="1" border="0" width="100%">
					<tr style="background-color:#e06666; color:white;">
						<td style="width:48%;"><b> '.$company.'</b></td>
					</tr>
					<tr><td>'.ucwords($quotation_info['payment_firstname']).' '.ucwords($quotation_info['payment_lastname']).'</td></tr>
					<tr><td>'.$customerAddress.'</td></tr>
				</table><br><br>';
		
        $tbl .= '<table border="0.1" cellpadding="4">
					<tr style="color:white; background-color:#e06666; text-align:center">
						<td style="width: 55%;"><b>'.$data['column_product'].'</b></td>
						<td style="width: 15%;"><b>'.$data['column_quantity'].'</b></td>
						<td style="width: 15%;"><b>'.$data['column_price'].'</b></td>
						<td style="width: 15%;"><b>'.$data['column_total'].'</b></td>
					</tr>';

		foreach($product_data as $product){
            $tbl .= '<tr style="font-size:12px;">
						<td style="border-top:0px hidden white; border-right:0.5px solid black;">'.$product['name'];
            foreach($product['option'] as $option) {
				$tbl .= '<br /><small> - '.$option['name'].': '.$option['value'].'</small>';
            }
            $tbl .= ' 	</td>
						<td style="border-top:0px hidden white; border-right:0.5px solid black;" align="center">'.$product['quantity'].'</td>
						<td style="border-top:0px hidden white; border-right:0.5px solid black;" align="right">'.$product['qprice'].'</td>
						<td style="border-top:0px hidden white; border-right:0.5px solid black;" align="right">'.$product['total'].'</td>
					</tr>';
        }
		$tbl .= '</table><br><br>
				<table cellpadding="0" cellspacing="0" border="0" width="100%" style="font-size:11px;">
					<tr>
						<td style="width:55%">
							<table cellpadding="3">
								<tr style="color:white; background-color:#e06666;">
									<td><b> TERMS AND CONDITIONS</b></td>
								</tr>
								<tr>
									<td>1. All prices in Ringgit Malaysia (MYR) ONLY.<br style="line-height:1.5;">
										2. This quotation is valid for 30 days only from the date it is issued.<br style="line-height:1.5;">

									</td>
								</tr>
							</table>
						</td>
						<td style="width:45%">
							<table cellpadding="3">';
							foreach ($voucher_data as $voucher) { 
								$tbl .= '<tr>
											<td style="width:30%"></td>
											<td style="width:40%">'.$voucher['description'].'</td>
											<td style="width:30%" align="right">'.$voucher['amount'].'</td>
										</tr>';
							}
							foreach ($total_data as $total) { 
								if($total['title'] == "Total"){
									$style = "font-weight:bold; background-color:#f4cccc; border:0.5px solid #f4cccc; border-top:0.5px solid black;";
									$total['title'] = strtoupper($total['title']);
								}else $style = "";
								$tbl .= '<tr>
											<td style="width:30%"></td>
											<td style="width:40%;'.$style.'">'.$total['title'].'</td>
											<td style="width:30%;'.$style.'" align="right">'.$total['text'].'</td>
										</tr>';
							}
		$tbl .= '			</table>
						</td>
					</tr>
				</table>';

        $pdf->writeHTML($tbl, true, false, false, false, '');
		$pdf->my_Footer($pdf_email);
      }
    
    $pdf->Output('quotation'.$quotation_id.'.pdf', 'I');
	
	if($flagPrint == 1){
		header('Content-type: application/pdf');
		header('Content-Disposition: attachment; filename="quotation.pdf"');
	}
  }

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/quotation', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->request->get['success'])) {
			$data['success'] = urldecode($this->request->get['success']);
		}

		$this->load->model('account/quotation');
		$this->load->language('account/quotation');
		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/quotation', $url, 'SSL')
		);
                
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['column_quotation_id'] = $this->language->get('column_quotation_id');
                $data['column_quotation_by'] = $this->language->get('column_quotation_by');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_total'] = $this->language->get('column_total');
		$data['button_view'] = $this->language->get('button_view');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['current_page'] = $this->url->link('account/quotation', '', 'SSL');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['review'])) {
			if($this->model_account_quotation->underreviewquotation($this->request->get['quotation_id'], $this->request->get['review']))
				$data['success'] = "You have successfully generate invoice for Quotation ID: #".$this->request->get['quotation_id'];
		}

		$data['quotations'] = array();
		
		$data['qcompleted'] = $this->config->get('quotation_completed');
		$data['qexpired'] = $this->config->get('quotation_expired');
		$data['qipay88'] = $this->config->get('quotation_ipay88_payment');
		$data['qcredit'] = $this->config->get('quotation_credit_payment');
		$data['qcod'] = $this->config->get('quotation_cod_payment');
		$data['qpaypal'] = $this->config->get('quotation_pp_standard_payment');
		$data['qghl'] = $this->config->get('quotation_ghl_payment');
		$data['qbanktransfer'] = $this->config->get('quotation_banktransfer_payment');
                $data['qapprove'] = $this->config->get('quotation_quotation_approve_status_id');
                $data['qpending'] = $this->config->get('quotation_pending_approval');
                $data['qreject'] = $this->config->get('quotation_quotation_reject_status_id');
                
                $this->session->data['order_type'] = 'quotation';
                
                //if ($this->config->get('ipay88_status') == 1){
                //$data['payment'] = $this->load->controller('extension/payment/ipay88');	
                //}else{
                $data['payment'] = '';
                //}
                $data['payment2'] = $this->load->controller('extension/payment/free_checkout');

                $data['payment3'] = $this->load->controller('extension/payment/cod');

                $data['payment4'] = $this->load->controller('extension/payment/bank_transfer');
                
                $this->load->model('account/customer');
                
                $data['isPetronasUser'] = $this->model_account_customer->isPetronasUser();
                
                $data['quotationCustomerId'] = $this->customer->getId();
                        
                $data['quotationSuperAdmin'] = $this->model_account_quotation->getQuotationSuperAdmin();
                
                
                
                if($this->config->get('config_enable_approval')) {
                    $quotation_total = $this->model_account_quotation->getTotalquotationsApproval();
                    $results = $this->model_account_quotation->getquotationsApproval(($page - 1) * 10, 10);
                }else {
                    $quotation_total = $this->model_account_quotation->getTotalquotations();
                    $results = $this->model_account_quotation->getquotations(($page - 1) * 10, 10);
                }
                
                $data['enable_approval'] = false;
                if($this->config->get('config_enable_approval')){
                    if($this->model_account_quotation->getQuotationApproval()) {
                        $data['enable_approval'] = true;
                    }
                }
                
		if($this->config->get('config_enable_approval')){
                    foreach ($results as $result) {
                            $product_total = $this->model_account_quotation->getTotalquotationProductsByquotationId($result['quotation_id']);
                            $data['quotations'][] = array(
                                    'quotation_id'   => $result['quotation_id'],
                                    'name'       => $result['firstname'] . ' ' . $result['lastname'],
                                    'status'     => $result['status'],
                                    'status_id'  => $result['quotation_status_id'],
                                    'expiry'  	 => floor((strtotime($result['date_expired'])-time())/(60 * 60 * 24))+1,
                                    'quotation_under_review'  => $result['quotation_under_review'],
                                    'quotation_status_id'     => $result['quotation_status_id'],
                                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                                    'products'   => ($product_total),
                                    'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                                    'href'       => $this->url->link('account/quotation/info', 'quotation_id=' . $result['quotation_id'], 'SSL'),
                                    'pdf'        => $this->url->link('account/quotation/pdf', 'quotation_id=' . $result['quotation_id'], 'SSL'),
                                    'invoice_attachment'	=> $result['invoice_attachment'],
                                    'generate_inv'	=> $this->url->link('account/quotation', 'quotation_id=' . $result['quotation_id'].'&review=1', 'SSL'),
                                    'customer_id'  => $result['customer_id'],
                                    'reject_reason'  => $result['reject_reason'],
                            );
                    }
                }else {
                    foreach ($results as $result) {
                            $product_total = $this->model_account_quotation->getTotalquotationProductsByquotationId($result['quotation_id']);
                            $data['quotations'][] = array(
                                    'quotation_id'   => $result['quotation_id'],
                                    'name'       => $result['firstname'] . ' ' . $result['lastname'],
                                    'status'     => $result['status'],
                                    'status_id'  => $result['quotation_status_id'],
                                    'expiry'  	 => floor((strtotime($result['date_expired'])-time())/(60 * 60 * 24))+1,
                                    'quotation_under_review'  => $result['quotation_under_review'],
                                    'quotation_status_id'     => $result['quotation_status_id'],
                                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                                    'products'   => ($product_total),
                                    'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
                                    'href'       => $this->url->link('account/quotation/info', 'quotation_id=' . $result['quotation_id'], 'SSL'),
                                    'pdf'        => $this->url->link('account/quotation/pdf', 'quotation_id=' . $result['quotation_id'], 'SSL'),
                                    'invoice_attachment'	=> $result['invoice_attachment'],
                                    'generate_inv'	=> $this->url->link('account/quotation', 'quotation_id=' . $result['quotation_id'].'&review=1', 'SSL'),
                                    'customer_id'  => $result['customer_id']
                            );
                    }
                }

		$pagination = new Pagination();
		$pagination->total = $quotation_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/quotation', 'page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($quotation_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($quotation_total - 10)) ? $quotation_total : ((($page - 1) * 10) + 10), $quotation_total, ceil($quotation_total / 10));
		$data['continue'] = $this->url->link('account/account', '', 'SSL');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/quotation_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/quotation_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('account/quotation_list.tpl', $data));
		}
	}
        
        public function reportTables() {
            $this->load->model('account/ssp');
            
            
            
            $columnHeaders = 'a.*, f.delivery_date AS delivery_date, e.approved_date AS approved_date, CASE WHEN a.quotation_status_id = 1 THEN "Pending for Approval" WHEN a.quotation_status_id = 3 THEN "Quotation Rejected" WHEN a.quotation_status_id = 11 THEN "Quotation Approved, Pending Confirmation" WHEN a.quotation_status_id = 6 THEN c.name WHEN a.quotation_status_id = 7 THEN CONCAT(c.name, " Order ID: ", b.order_id, " Order Date: ", b.date_added, " Order Status: ", d.name) WHEN a.quotation_status_id = 8 THEN CONCAT(c.name, " Order ID: ", b.order_id, " Order Date: ", b.date_added, " Order Status: ", d.name) WHEN a.quotation_status_id = 9 THEN CONCAT(c.name, " Order ID: ", b.order_id, " Order Date: ", b.date_added, " Order Status: ", d.name) WHEN a.quotation_status_id = 10 THEN CONCAT(c.name, " Order ID: ", b.order_id, " Order Date: ", b.date_added, " Order Status: ", d.name) END AS bord ';
            $table = 'oc_quotation a LEFT JOIN oc_order b ON a.order_id = b.order_id LEFT JOIN oc_quotation_status c ON a.quotation_status_id = c.quotation_status_id LEFT JOIN oc_order_status d ON b.order_status_id = d.order_status_id LEFT JOIN (SELECT MIN(date_added) AS approved_date, quotation_id FROM `oc_quotation_history` WHERE `quotation_status_id` IN (9,10,8,7,11) GROUP BY quotation_id) AS e ON a.quotation_id = e.quotation_id LEFT JOIN (SELECT MIN(date_added) AS delivery_date, order_id FROM `oc_order_history` WHERE `order_status_id` IN (5) GROUP BY order_id) AS f ON b.order_id = f.order_id ';
            $primaryKey = 'a.quotation_id';
            $columns = array(
                    array( 	'db' => 'quotation_id', 		'dt' => 0,
//                                    'formatter' => function($val, $row){
//                                            $str = '<input type="checkbox" class="select-checkbox" name="check[]" value="'.$val.'">';
//                                            return $str;
//                                    }
                    ),
                    array( 	'db' => 'date_added',	'dt' => 1 ),
                    array( 	'db' => 'bord',	'dt' => 2 ),
                    array( 	'db' => 'delivery_date', 	'dt' => 3,
//                                    'formatter' => function($val, $row){
//                                           // $allowApprove = checkParam('approve'); 
//                                            $str = '<input type="hidden" id="name_'.$row[0].'" value="'.$val.'">';
////                                            if($allowApprove){
////                                                    $str .= "<a href='#' onclick='openWindow(".$row[0].")'>".$val."</a>";
////                                            }else{
////                                                    $str .= $val;
////                                            }
//                                            return $str;
//                                    }
                    ),
                    array( 	'db' => 'firstname',   'dt' => 4,
                                    'formatter' => function($val, $row){
                                            $str = $val." ".$row['lastname'];
                                            return $str;
                                    }
                    ),
                    array( 	'db' => 'approver_name',   'dt' => 5,
//                                    'formatter' => function($val, $row){
//                                            $str = '<div id="input_key_in_'.$row[0].'" class="text-center"></div>';
//                                            return $str;
//                                    }
                    ),
                    array( 	'db' => 'approved_date',	'dt' => 6 ),
                    array( 	'db' => 'total', 'dt' => 7,
                                    'formatter' => function($val, $row){
                                            $str = $this->currency->format($val, $row['currency_code'], $row['currency_value']);
                                            return $str;
                                    }
                    )
                                    //,
//                    array( 	'db' => 'credit_date_from', 'dt' => 6,
//                                    'formatter' => function($val, $row){
//                                            $str = '<div class="text-center" style="line-height:0.4;">
//                                                            <div id="input_date_from_'.$row[0].'"></div><br>
//                                                            <div id="input_date_to_'.$row[0].'"></div>
//                                                            </div>
//                                                            <script>
//                                                                    renderDateInput('.$row[0].', "date_from", "'.$row[10].'");
//                                                                    renderDateInput('.$row[0].', "date_to", "'.$row[11].'");
//                                                            </script>';
//                                            return $str;
//                                    }
//                    ),
//                    array( 	'db' => 'credit_limit', 'dt' => 7, 
//                                    'formatter' => function($val, $row){
//                                            $str = '<div class="text-center" style="line-height:1;">
//                                                            <div id="input_credit_limit_'.$row[0].'" class="text-center"></div><br>
//                                                            <div><a title="Credit Limit History" onclick="openHistory('.$row[0].')">View History</a></div>
//                                                            </div>
//                                                            <script>renderNumericInput('.$row[0].', "credit_limit", "'.$val.'");</script>';
//                                            return $str;
//                                    }
//                    ),
//                    array( 	'db' => 'credit_type', 'dt' => 8,
//                                    'formatter' => function($val, $row){
//                                            $str = '<select class="flat-selectbox" name="credit_type_'.$row[0].'" style="max-width:75px; min-width:75px;">
//                                                                    <option value="Cash" '.( $val == 'Cash' ? 'selected' : '' ).'>Cash</option>
//                                                                    <option value="Cheque" '.( $val == 'Cheque' ? 'selected' : '' ).'>Cheque</option>
//                                                                    <option value="Other" '.( $val == 'Other' ? 'selected' : '' ).'>Other</option>
//                                                            </select>';
//                                            return $str;
//                                    }
//                    ),
//                    array( 	'db' => 'remarks', 'dt' => 9,
//                                    'formatter' => function($val, $row){
//                                            $str = '<input type="text" class="flat-input" name="remarks_'.$row[0].'" value="'.$val.'" style="min-width:112px; max-width:112px;">';
//                                            return $str;
//                                    }
//                    ),
//                    array( 	'db' => 'attachment', 'dt' => 10,
//                                    'formatter' => function($val, $row){
//                                            $onClick = "$('#fs_".$row[0]."').text(''); $('#file_".$row[0]."').val(''); $('#fa_".$row[0]."').hide();";
//                                            $str = '<div id="no-attachment-'.$row[0].'" style="display:'.( !empty($val) ? 'none' : 'block' ).'">
//                                                            <input type="file" name="file_'.$row[0].'" id="file_'.$row[0].'" 
//                                                                       style="width:75px;" onchange="javascript: replacePath('.$row[0].');">
//                                                            <span id="fs_'.$row[0].'" class="txt-ellipsis" style="width:90px;"></span>
//                                                            <a id="fa_'.$row[0].'" style="display:none;" class="pull-right" onClick="'.$onClick.'">(-)</a>
//                                                    </div>
//                                                    <div id="has-attachment-'.$row[0].'" style="display:'.( empty($val) ? 'none' : 'block' ).'">
//                                                            <a href="'.HTTP_MEDIA.'/player/'.$val.'" target="_blank">Download</a> &emsp;
//                                                            <a class="pull-right" onClick="javascript: deleteAttachment('.$row[0].');">(-)</a>
//                                                    </div>';
//                                            return $str;
//                                    }
//                    ),
//                    array( 	'db' => 'status', 'dt' => 11 )
            );

            $sql_details = array('user' => DB_USERNAME, 'pass' => DB_PASSWORD, 'db' => DB_DATABASE, 'host' => DB_HOSTNAME);

            $approver =  $this->request->get['approver'];
            $start_date = $this->request->get['start_date'];
            $end_date = trim($this->request->get['end_date']);
            $approved = $this->request->get['approved'];
            //$approve = $this->request->get['approve'];
            
            

            $condition = "WHERE 1=1 ";
            if(!empty($approver)){
                    $condition .= "AND a.approver_name LIKE '%$approver%' ";
            }if(!empty($start_date) && !empty($end_date)){
                    $condition .= "AND a.date_added >= '$start_date' AND a.date_added <= '$end_date' ";
            }else if(!empty($start_date)){
                    $condition .= "AND a.date_added >= '$start_date' ";
            }else if(!empty($end_date)){
                    $condition .= "AND a.date_added <= '$end_date' ";
            }if(!empty($approved)){
                if($approved == "all") {
                    
                }else if($approved == "no") {
                    $condition .= "AND a.approver_id = '0' ";
                }else if($approved == "yes") {
                    $condition .= "AND a.approver_id != '0' ";
                }
                    
            }
            //$condition .= "AND a.firstname LIKE '%alif%' ";
            //print_r($this->model_account_ssp->simple( $_GET, $sql_details, $table, $primaryKey, $columns, $columnHeaders, $condition ));
            
            $arrData = $this->model_account_ssp->simple( $_GET, $sql_details, $table, $primaryKey, $columns, $columnHeaders, $condition );
            
            $arrData['totalPurchase'] = $this->currency->format($arrData['totalPurchase'], 'MYR', 1.00000000);
                
            echo json_encode($arrData);
        }
        
        public function getquotationipay88() {
            
            $json = array();
            $json['error_warning'] = "";
            $json['success'] = "";
            $json['ipay88'] = "";
            
            $this->session->data['quotation_id'] =  $this->request->post['quotation_id'];
            
            $json['ipay88'] = $this->load->controller('extension/payment/ipay88');
            
            echo json_encode($json);
        }
        
        function exportPSExcel(){
                require_once DIR_SYSTEM.'/PHPExcel18/Classes/PHPExcel.php';
                
                $this->load->model('account/quotation');
                
                $approver =  $this->request->get['approver'];
                $start_date = $this->request->get['start_date'];
                $end_date = trim($this->request->get['end_date']);
                $approved = $this->request->get['approved'];

                $columns = 'a.*, f.delivery_date AS delivery_date, e.approved_date AS approved_date, CASE WHEN a.quotation_status_id = 1 THEN "Pending for Approval" WHEN a.quotation_status_id = 3 THEN "Quotation Rejected" WHEN a.quotation_status_id = 11 THEN "Quotation Approved, Pending Confirmation" WHEN a.quotation_status_id = 6 THEN c.name WHEN a.quotation_status_id = 7 THEN CONCAT(c.name, " Order ID: ", b.order_id, " Order Date: ", b.date_added, " Order Status: ", d.name) WHEN a.quotation_status_id = 8 THEN CONCAT(c.name, " Order ID: ", b.order_id, " Order Date: ", b.date_added, " Order Status: ", d.name) WHEN a.quotation_status_id = 9 THEN CONCAT(c.name, " Order ID: ", b.order_id, " Order Date: ", b.date_added, " Order Status: ", d.name) WHEN a.quotation_status_id = 10 THEN CONCAT(c.name, " Order ID: ", b.order_id, " Order Date: ", b.date_added, " Order Status: ", d.name) END AS bord ';
                $table = 'oc_quotation a LEFT JOIN oc_order b ON a.order_id = b.order_id LEFT JOIN oc_quotation_status c ON a.quotation_status_id = c.quotation_status_id LEFT JOIN oc_order_status d ON b.order_status_id = d.order_status_id LEFT JOIN (SELECT MIN(date_added) AS approved_date, quotation_id FROM `oc_quotation_history` WHERE `quotation_status_id` IN (9,10,8,7,11) GROUP BY quotation_id) AS e ON a.quotation_id = e.quotation_id LEFT JOIN (SELECT MIN(date_added) AS delivery_date, order_id FROM `oc_order_history` WHERE `order_status_id` IN (5) GROUP BY order_id) AS f ON b.order_id = f.order_id ';

                $condition = "WHERE 1=1 ";
                if(!empty($approver)){
                        $condition .= "AND a.approver_name LIKE '%$approver%' ";
                }if(!empty($start_date) && !empty($end_date)){
                        $condition .= "AND a.date_added >= '$start_date' AND a.date_added <= '$end_date' ";
                }else if(!empty($start_date)){
                        $condition .= "AND a.date_added >= '$start_date' ";
                }else if(!empty($end_date)){
                        $condition .= "AND a.date_added <= '$end_date' ";
                }if(!empty($approved)){
                    if($approved == "all") {

                    }else if($approved == "no") {
                        $condition .= "AND a.approver_id = '0' ";
                    }else if($approved == "yes") {
                        $condition .= "AND a.approver_id != '0' ";
                    }

                }
                $results = $this->model_account_quotation->getExcelReport($columns, $table, $condition);
                
                // Create new PHPExcel object
                $objPHPExcel = new PHPExcel();
                // Set document properties
                $objPHPExcel->getProperties()->setCreator("NAK")
                            ->setLastModifiedBy("NAK")
                            ->setTitle("NAK Quotations Report")
                            ->setSubject("NAK Quotations Report")
                            ->setDescription("NAK Quotations Report")
                            ->setKeywords("nak quotation report")
                            ->setCategory("Report");
                
                $boldStyle = array('font' => array('bold' => true));
                $centerStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER));
                $borderStyle = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                $greencell = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '00a65c')));
                $whiteText = array('font' => array('color' => array('rgb' => 'ffffff')));
                $titleFont = array('font' => array('size' => 14));
                
                if(!empty($approver) && (!empty($approved))){
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A2', 'Approver:')
                            ->setCellValue('B2', $approver)
                            ->setCellValue('C2', 'Approved:')
                            ->setCellValue('D2', $approved);
                }else if(!empty($approver)){
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A2', 'Approver:')
                            ->setCellValue('B2', $approver);
                }else if(!empty($approved)){
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A2', 'Approved:')
                            ->setCellValue('B2', $approved);

                }
            
                if(!empty($start_date) && !empty($end_date)){
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A3', 'Start Date:')
                            ->setCellValue('B3', $start_date)
                            ->setCellValue('C3', 'End Date:')
                            ->setCellValue('D3', $end_date);
                }else if(!empty($start_date)){
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A3', 'Start Date:')
                            ->setCellValue('B3', $start_date);
                }else if(!empty($end_date)){
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A3', 'End Date:')
                            ->setCellValue('B3', $end_date);
                }
                
                // Add some data
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'Date Generated:')
                            ->setCellValue('B1', date("d/m/Y h:i:s A"))
                            ->setCellValue('A5', 'Quotation ID')
                            ->setCellValue('B5', 'Quotation Date')
                            ->setCellValue('C5', 'Transaction Status')
                            ->setCellValue('D5', 'Delivery Date')
                            ->setCellValue('E5', 'User Name')
                            ->setCellValue('F5', 'Approver Name')
                            ->setCellValue('G5', 'Approved Date')
                            ->setCellValue('H5', 'Material Purchase Price');
                
                $objPHPExcel->getActiveSheet()->getStyle("A1:H5")->applyFromArray($boldStyle);
                $objPHPExcel->getActiveSheet()->getStyle("A5:H5")->applyFromArray($centerStyle);
                $objPHPExcel->getActiveSheet()->getStyle("A5:H5")->applyFromArray($greencell);
                $objPHPExcel->getActiveSheet()->getStyle("A5:H5")->applyFromArray($borderStyle);
                
                $row = 6;
                $total = 0;
                $no = 0;
                $approvedNo = 0;
                foreach($results AS $key => $value) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$row, $value['quotation_id'])
                            ->setCellValue('B'.$row, $value['date_added'])
                            ->setCellValue('C'.$row, $value['bord'])
                            ->setCellValue('D'.$row, $value['delivery_date'])
                            ->setCellValue('E'.$row, $value['firstname']." ".$value['lastname'])
                            ->setCellValue('F'.$row, $value['approver_name'])
                            ->setCellValue('G'.$row, $value['approved_date'])
                            ->setCellValue('H'.$row, $value['total']);
                    $row++;
                    $total += $value['total'];
                    $no++;
                    if($value['approver_name'] != '') {
                        $approvedNo++;
                    }
                }
                
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('G'.($row+3), 'Total Quotations:')
                            ->setCellValue('H'.($row+3), $no)
                            ->setCellValue('G'.($row+4), 'Total Approved Quotations:')
                            ->setCellValue('H'.($row+4), $approvedNo)
                            ->setCellValue('G'.($row+5), 'Total Purchase Price:')
                            ->setCellValue('H'.($row+5), $total);
                
                $objPHPExcel->getActiveSheet()->getStyle("G".($row+3).":H".($row+5))->applyFromArray($boldStyle);
                
                $colArr = array('B','C','D','E','F','G','H');
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(13);
			foreach($colArr AS $col){
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setWidth(20);
				//$sheet->getColumnDimension($col)->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getStyle('A1:O999')->getAlignment()->setWrapText(true); 
			}
                
                
                // Rename worksheet
                $objPHPExcel->getActiveSheet()->setTitle('Simple');
                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $objPHPExcel->setActiveSheetIndex(0);
                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Quotations'.date('YmdHis').'.xlsx"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');
                // If you're serving to IE over SSL, then the following may be needed
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header ('Pragma: public'); // HTTP/1.0
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
                exit;
        }
	
        
        public function report() {
            
            //echo "lOL";
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/quotation', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->request->get['success'])) {
			$data['success'] = urldecode($this->request->get['success']);
		}
                
		$this->load->model('account/quotation');
		$this->load->language('account/quotation');
		$this->document->setTitle($this->language->get('text_quotation_report'));

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_quotation_report'),
			'href' => $this->url->link('account/quotation/report', $url, 'SSL')
		);
                
		$data['heading_title'] = $this->language->get('text_quotation_report');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['column_quotation_id'] = $this->language->get('column_quotation_id');
                $data['column_quotation_by'] = $this->language->get('column_quotation_by');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_total'] = $this->language->get('column_total');
		$data['button_view'] = $this->language->get('button_view');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['current_page'] = $this->url->link('account/quotation/report', '', 'SSL');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
                
		
		if (isset($this->request->get['review'])) {
			if($this->model_account_quotation->underreviewquotation($this->request->get['quotation_id'], $this->request->get['review']))
				$data['success'] = "You have successfully generate invoice for Quotation ID: #".$this->request->get['quotation_id'];
		}

		$data['quotations'] = array();
		
		$data['qcompleted'] = $this->config->get('quotation_completed');
		$data['qexpired'] = $this->config->get('quotation_expired');
		$data['qipay88'] = $this->config->get('quotation_ipay88_payment');
		$data['qcredit'] = $this->config->get('quotation_credit_payment');
		$data['qcod'] = $this->config->get('quotation_cod_payment');
		$data['qpaypal'] = $this->config->get('quotation_pp_standard_payment');
		$data['qghl'] = $this->config->get('quotation_ghl_payment');
		$data['qbanktransfer'] = $this->config->get('quotation_banktransfer_payment');
                $data['qapprove'] = $this->config->get('quotation_quotation_approve_status_id');
                $data['qpending'] = $this->config->get('quotation_pending_approval');
                $data['qreject'] = $this->config->get('quotation_quotation_reject_status_id');
                
                $this->session->data['order_type'] = 'quotation';
                
                if ($this->config->get('ipay88_status') == 1){
                $data['payment'] = $this->load->controller('extension/payment/ipay88');	
                }else{
                $data['payment'] = '';
                }
                $data['payment2'] = $this->load->controller('extension/payment/free_checkout');

                $data['payment3'] = $this->load->controller('extension/payment/cod');

                $data['payment4'] = $this->load->controller('extension/payment/bank_transfer');
                
                $this->load->model('account/customer');
                
                $data['isPetronasUser'] = $this->model_account_customer->isPetronasUser();
                
                $data['quotationCustomerId'] = $this->customer->getId();
                        
                $data['quotationSuperAdmin'] = $this->model_account_quotation->getQuotationSuperAdmin();
                
                if($data['isPetronasUser'] && !$data['quotationSuperAdmin']) {
                    $this->response->redirect($this->url->link('account/login', '', 'SSL'));
                }
                
                
                
                if($this->config->get('config_enable_approval')) {
                    $quotation_total = $this->model_account_quotation->getTotalquotationsApproval();
                    $results = $this->model_account_quotation->getquotationsApproval(($page - 1) * 10, 10);
                }else {
                    $quotation_total = $this->model_account_quotation->getTotalquotations();
                    $results = $this->model_account_quotation->getquotations(($page - 1) * 10, 10);
                }
                
                $data['enable_approval'] = false;
                if($this->config->get('config_enable_approval')){
                    if($this->model_account_quotation->getQuotationApproval()) {
                        $data['enable_approval'] = true;
                    }
                }
                
		
		foreach ($results as $result) {
			$product_total = $this->model_account_quotation->getTotalquotationProductsByquotationId($result['quotation_id']);
			$data['quotations'][] = array(
				'quotation_id'   => $result['quotation_id'],
				'name'       => $result['firstname'] . ' ' . $result['lastname'],
				'status'     => $result['status'],
				'status_id'  => $result['quotation_status_id'],
				'expiry'  	 => floor((strtotime($result['date_expired'])-time())/(60 * 60 * 24))+1,
				'quotation_under_review'  => $result['quotation_under_review'],
				'quotation_status_id'     => $result['quotation_status_id'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'products'   => ($product_total),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'href'       => $this->url->link('account/quotation/info', 'quotation_id=' . $result['quotation_id'], 'SSL'),
				'pdf'        => $this->url->link('account/quotation/pdf', 'quotation_id=' . $result['quotation_id'], 'SSL'),
				'invoice_attachment'	=> $result['invoice_attachment'],
				'generate_inv'	=> $this->url->link('account/quotation', 'quotation_id=' . $result['quotation_id'].'&review=1', 'SSL'),
                                'customer_id'  => $result['customer_id'],
                                'reject_reason'  => $result['reject_reason'],
			);
		}

		$pagination = new Pagination();
		$pagination->total = $quotation_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/quotation/report', 'page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($quotation_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($quotation_total - 10)) ? $quotation_total : ((($page - 1) * 10) + 10), $quotation_total, ceil($quotation_total / 10));
		$data['continue'] = $this->url->link('account/account', '', 'SSL');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/quotation_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/quotation_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('account/quotation_report.tpl', $data));
		}
	}

	public function placeorder() {
    
		$redirect = '';

		$this->load->language('account/quotation');

		if (isset($this->request->get['quotation_id'])) {
			$quotation_id = $this->request->get['quotation_id'];
		} else {
			$quotation_id = 0;
		}
   
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/quotation/info', 'quotation_id=' . $quotation_id, 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('account/quotation');

		$quotation_info = $this->model_account_quotation->getquotation($quotation_id);
		
		if ($quotation_info) {
      $this->customer->setAdminPriceEdit();

      $order_data = array();

      $order_data['totals'] = array();
      $total = 0;
      $taxes = $this->cart->getTaxes();
     
      $this->load->model('extension/extension');

      $sort_order = array();

      $results = $this->model_extension_extension->getExtensions('total');
     
      foreach ($results as $key => $value) {
        $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
      }

      array_multisort($sort_order, SORT_ASC, $results);

      foreach ($results as $result) {
        if ($this->config->get($result['code'] . '_status')) {
          $this->load->model('total/' . $result['code']);

          $this->{'model_total_' . $result['code']}->getTotal($order_data['totals'], $total, $taxes);
        }
      }
     
      $sort_order = array();

      foreach ($order_data['totals'] as $key => $value) {
        $sort_order[$key] = $value['sort_order'];
      }

      array_multisort($sort_order, SORT_ASC, $order_data['totals']);

      $this->load->language('checkout/checkout');

      $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
      $order_data['store_id'] = $this->config->get('config_store_id');
      $order_data['store_name'] = $this->config->get('config_name');
      
      if ($order_data['store_id']) {
        $order_data['store_url'] = $this->config->get('config_url');
      } else {
        $order_data['store_url'] = HTTP_SERVER;
      }

      if ($this->customer->isLogged()) {
        $this->load->model('account/customer');

        $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

        $order_data['customer_id'] = $this->customer->getId();
        $order_data['customer_group_id'] = $customer_info['customer_group_id'];
        $order_data['firstname'] = $customer_info['firstname'];
        $order_data['lastname'] = $customer_info['lastname'];
        $order_data['email'] = $customer_info['email'];
        $order_data['telephone'] = $customer_info['telephone'];
        $order_data['fax'] = $customer_info['fax'];
        $order_data['custom_field'] = unserialize($customer_info['custom_field']);
      } elseif (isset($this->session->data['guest'])) {
        $order_data['customer_id'] = 0;
        $order_data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
        $order_data['firstname'] = $this->session->data['guest']['firstname'];
        $order_data['lastname'] = $this->session->data['guest']['lastname'];
        $order_data['email'] = $this->session->data['guest']['email'];
        $order_data['telephone'] = $this->session->data['guest']['telephone'];
        $order_data['fax'] = $this->session->data['guest']['fax'];
        $order_data['custom_field'] = $this->session->data['guest']['custom_field'];
      }

      $order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
      $order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
      $order_data['payment_company'] = $this->session->data['payment_address']['company'];
      $order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
      $order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
      $order_data['payment_city'] = $this->session->data['payment_address']['city'];
      $order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
      $order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
      $order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
      $order_data['payment_country'] = $this->session->data['payment_address']['country'];
      $order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
      $order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
      $order_data['payment_custom_field'] = (isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : array());

      if (isset($this->session->data['payment_method']['title'])) {
        $order_data['payment_method'] = $this->session->data['payment_method']['title'];
      } else {
        $order_data['payment_method'] = '';
      }

      if (isset($this->session->data['payment_method']['code'])) {
        $order_data['payment_code'] = $this->session->data['payment_method']['code'];
      } else {
        $order_data['payment_code'] = '';
      }

      if ($this->cart->hasShipping()) {
        $order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
        $order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
        $order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
        $order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
        $order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
        $order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
        $order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
        $order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
        $order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
        $order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
        $order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
        $order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
        $order_data['shipping_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : array());

        if (isset($this->session->data['shipping_method']['title'])) {
          $order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
        } else {
          $order_data['shipping_method'] = '';
        }

        if (isset($this->session->data['shipping_method']['code'])) {
          $order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
        } else {
          $order_data['shipping_code'] = '';
        }
      } else {
        $order_data['shipping_firstname'] = '';
        $order_data['shipping_lastname'] = '';
        $order_data['shipping_company'] = '';
        $order_data['shipping_address_1'] = '';
        $order_data['shipping_address_2'] = '';
        $order_data['shipping_city'] = '';
        $order_data['shipping_postcode'] = '';
        $order_data['shipping_zone'] = '';
        $order_data['shipping_zone_id'] = '';
        $order_data['shipping_country'] = '';
        $order_data['shipping_country_id'] = '';
        $order_data['shipping_address_format'] = '';
        $order_data['shipping_custom_field'] = array();
        $order_data['shipping_method'] = '';
        $order_data['shipping_code'] = '';
      }

      $order_data['products'] = array();
    
      foreach ($this->cart->getProducts() as $product) {
        $option_data = array();

        foreach ($product['option'] as $option) {
          $option_data[] = array(
            'product_option_id'       => $option['product_option_id'],
            'product_option_value_id' => $option['product_option_value_id'],
            'option_id'               => $option['option_id'],
            'option_value_id'         => $option['option_value_id'],
            'name'                    => $option['name'],
            'value'                   => $option['value'],
            'type'                    => $option['type']
          );
        }

        $order_data['products'][] = array(
          'product_id' => $product['product_id'],
          'name'       => $product['name'],
          'model'      => $product['model'],
          'option'     => $option_data,
          'download'   => $product['download'],
          'quantity'   => $product['quantity'],
          'subtract'   => $product['subtract'],
          'price'      => $product['price'],
          'total'      => $product['total'],
          'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
          'reward'     => $product['reward']
        );
      }
      
      // Gift Voucher
      $order_data['vouchers'] = array();

      if (!empty($this->session->data['vouchers'])) {
        foreach ($this->session->data['vouchers'] as $voucher) {
          $order_data['vouchers'][] = array(
            'description'      => $voucher['description'],
            'code'             => substr(md5(mt_rand()), 0, 10),
            'to_name'          => $voucher['to_name'],
            'to_email'         => $voucher['to_email'],
            'from_name'        => $voucher['from_name'],
            'from_email'       => $voucher['from_email'],
            'voucher_theme_id' => $voucher['voucher_theme_id'],
            'message'          => $voucher['message'],
            'amount'           => $voucher['amount']
          );
        }
      }

      $order_data['comment'] = $this->session->data['comment'];
      $order_data['total'] = $total;

      if (isset($this->request->cookie['tracking'])) {
        $order_data['tracking'] = $this->request->cookie['tracking'];

        $subtotal = $this->cart->getSubTotal();

        // Affiliate
        $this->load->model('affiliate/affiliate');

        $affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);

        if ($affiliate_info) {
          $order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
          $order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
        } else {
          $order_data['affiliate_id'] = 0;
          $order_data['commission'] = 0;
        }

        // Marketing
        $this->load->model('checkout/marketing');

        $marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

        if ($marketing_info) {
          $order_data['marketing_id'] = $marketing_info['marketing_id'];
        } else {
          $order_data['marketing_id'] = 0;
        }
      } else {
        $order_data['affiliate_id'] = 0;
        $order_data['commission'] = 0;
        $order_data['marketing_id'] = 0;
        $order_data['tracking'] = '';
      }

      $order_data['language_id'] = $this->config->get('config_language_id');
      $order_data['currency_id'] = $this->currency->getId();
      $order_data['currency_code'] = $this->currency->getCode();
      $order_data['currency_value'] = $this->currency->getValue($this->currency->getCode());
      $order_data['ip'] = $this->request->server['REMOTE_ADDR'];

      if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
        $order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
      } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
        $order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
      } else {
        $order_data['forwarded_ip'] = '';
      }

      if (isset($this->request->server['HTTP_USER_AGENT'])) {
        $order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
      } else {
        $order_data['user_agent'] = '';
      }

      if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
        $order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
      } else {
        $order_data['accept_language'] = '';
      }

      $this->load->model('checkout/order');

      $this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);

      $data['text_recurring_item'] = $this->language->get('text_recurring_item');
      $data['text_payment_recurring'] = $this->language->get('text_payment_recurring');

      $data['column_name'] = $this->language->get('column_name');
      $data['column_model'] = $this->language->get('column_model');
      $data['column_quantity'] = $this->language->get('column_quantity');
      $data['column_price'] = $this->language->get('column_price');
      $data['column_total'] = $this->language->get('column_total');
	  $data['column_gst'] = $this->language->get('column_gst');

      $this->load->model('tool/upload');

      $data['products'] = array();
    
      foreach ($this->cart->getProducts() as $product) {
        $option_data = array();

        foreach ($product['option'] as $option) {
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
            'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
          );
        }

        $recurring = '';

        if ($product['recurring']) {
          $frequencies = array(
            'day'        => $this->language->get('text_day'),
            'week'       => $this->language->get('text_week'),
            'semi_month' => $this->language->get('text_semi_month'),
            'month'      => $this->language->get('text_month'),
            'year'       => $this->language->get('text_year'),
          );

          if ($product['recurring']['trial']) {
            $recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
          }

          if ($product['recurring']['duration']) {
            $recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
          } else {
            $recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax'))), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
          }
        }

		$priceWTax = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
        $data['products'][] = array(
          'key'        => $product['key'],
          'product_id' => $product['product_id'],
          'name'       => $product['name'],
          'model'      => $product['model'],
          'option'     => $option_data,
          'recurring'  => $recurring,
          'quantity'   => $product['quantity'],
          'subtract'   => $product['subtract'],
          'price'      => $this->currency->format($priceWTax),
          'total'      => $this->currency->format($priceWTax * $product['quantity']),
          'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id']),
		  'gst'		   => $this->currency->format(($priceWTax - $product['price']) * $product['quantity'])
        );
      }
     
      // Gift Voucher
      $data['vouchers'] = array();

      if (!empty($this->session->data['vouchers'])) {
        foreach ($this->session->data['vouchers'] as $voucher) {
          $data['vouchers'][] = array(
            'description' => $voucher['description'],
            'amount'      => $this->currency->format($voucher['amount'])
          );
        }
      }

      $data['totals'] = array();

      foreach ($order_data['totals'] as $total) {
        $data['totals'][] = array(
          'title' => $total['title'],
          'text'  => $this->currency->format($total['value']),
        );
      }

      $data['payment'] = $this->load->controller('payment/' . $this->session->data['payment_method']['code']);

      $data['quotationcheckout'] = 0;
      
      unset($this->session->data['shipping_method']);
      
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/confirm.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/confirm.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('checkout/confirm.tpl', $data));
			}

		} else {
			$this->document->setTitle($this->language->get('text_quotation'));

			$data['heading_title'] = $this->language->get('text_quotation');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/quotation', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_quotation'),
				'href' => $this->url->link('account/quotation/info', 'quotation_id=' . $quotation_id, 'SSL')
			);

			$data['continue'] = $this->url->link('account/quotation', '', 'SSL');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('error/not_found.tpl', $data));
			}
		}
	}

	public function info() {

		$this->load->language('account/quotation');

		if (isset($this->request->get['quotation_id'])) {
			$quotation_id = $this->request->get['quotation_id'];
			$this->session->data['quotation_id'] = $quotation_id;
			if(isset($this->session->data['order_id'])) unset($this->session->data['order_id']);
		} else {
			$this->session->data['error_warning'] = $this->language->get('text_error');
			$this->response->redirect($this->url->link('account/quotation', '', 'SSL'));
		}
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/quotation/info', 'quotation_id=' . $quotation_id, 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/quotation');
		$quotation_info = $this->model_account_quotation->getquotation($quotation_id);

		if ($quotation_info) {
			$this->document->setTitle($this->language->get('text_quotation'));
			$currencyinfo = $this->model_account_quotation->getCurrency($quotation_info['currency_id']);
			$data['currency_symbol'] = $currencyinfo['symbol_left'];
			
			$data['quotation_status_id'] = $quotation_info['quotation_status_id'];
			$data['qcompleted'] = $this->config->get('quotation_completed');
			$data['qexpired'] = $this->config->get('quotation_expired');
			$data['qipay88'] = $this->config->get('quotation_ipay88_payment');
			$data['qcredit'] = $this->config->get('quotation_credit_payment');
			$data['qcod'] = $this->config->get('quotation_cod_payment');
			$data['qbanktransfer'] = $this->config->get('quotation_banktransfer_payment');
			$data['qppstandard'] = $this->config->get('quotation_pp_standard_payment');
			$data['qghl'] = $this->config->get('quotation_ghl_payment');
                        $data['qapprove'] = $this->config->get('quotation_quotation_approve_status_id');
                        $data['qpending'] = $this->config->get('quotation_pending_approval');

			$data['breadcrumbs'] = array();
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL')
			);
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$data['breadcrumbs'][] = array(
				'text' => 'Quotation',
				'href' => $this->url->link('account/quotation', $url, 'SSL')
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_quotation'),
				'href' => $this->url->link('account/quotation/info', 'quotation_id=' . $this->request->get['quotation_id'] . $url, 'SSL')
			);

			$data['heading_title'] = $this->language->get('text_quotation');
			$data['text_quotation_detail'] = $this->language->get('text_quotation_detail');
			$data['text_invoice_no'] = $this->language->get('text_invoice_no');
			$data['text_quotation_id'] = $this->language->get('text_quotation_id');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_shipping_address'] = $this->language->get('text_shipping_address');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_payment_address'] = $this->language->get('text_payment_address');
			$data['text_history'] = $this->language->get('text_history');
			$data['text_comment'] = $this->language->get('text_comment');
			$data['text_customer_comment'] = $this->language->get('text_customer_comment');

			$data['column_name'] = $this->language->get('column_name');
			$data['column_image'] = $this->language->get('column_image');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');
			$data['column_gst'] = $this->language->get('column_gst');
			$data['column_action'] = $this->language->get('column_action');
			$data['column_date_added'] = $this->language->get('column_date_added');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_comment'] = $this->language->get('column_comment');

			$data['button_requotation'] = $this->language->get('button_requotation');
			$data['button_return'] = $this->language->get('button_return');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_cancel'] = 'Back';
			$data['button_placeorder'] = $this->language->get('button_placeorder');
			$data['button_rejectquotation'] = $this->language->get('button_rejectquotation');

			if (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];
				unset($this->session->data['error']);
			} else {
				$data['error_warning'] = '';
			}

			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];
				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}

			$data['quotation_id'] = $this->request->get['quotation_id'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($quotation_info['date_added']));
			$data['customer_name'] = $quotation_info['firstname'].' '.$quotation_info['lastname'];
			$data['email'] = $quotation_info['email'];
			$data['telephone'] = $quotation_info['telephone'];
			$data['address'] = $quotation_info['payment_address_1'].', '.$quotation_info['payment_city'].', '.$quotation_info['payment_postcode'].', '.$quotation_info['payment_zone'];
			$data['company'] = $quotation_info['payment_company'];
                        $data['customer_id'] = $quotation_info['customer_id'];
			
			$this->load->model('catalog/product');
			$this->load->model('tool/upload');

			// Products
			$data['products'] = array();
			$products = $this->model_account_quotation->getquotationProducts($this->request->get['quotation_id']);

			foreach ($products as $product) {
				$this->load->model('account/quotation');
				$this->load->model('tool/image');
				$result = $this->model_account_quotation->getProductImage($product['product_id']);
				/*
				if (is_file(DIR_IMAGE . $image)) {
				  $image = $this->model_tool_image->resize($image, 60, 60);
				} else {
				  $image = $this->model_tool_image->resize('no_image.png', 60, 60);
				}
				*/
				// BRP Customized Part
				
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
				$options = $this->model_account_quotation->getquotationOptions($this->request->get['quotation_id'], $product['quotation_product_id']);

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
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}
				$product_info = $this->model_catalog_product->getProduct($product['product_id']);
				if ($product_info) {
					$requotation = $this->url->link('account/quotation/requotation', 'quotation_id=' . $quotation_id . '&quotation_product_id=' . $product['quotation_product_id'], 'SSL');
				} else {
					$requotation = '';
				}

				$data['products'][] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'thumb'    => $image,
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'qprice'   => $this->currency->format($product['qprice'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quotation_info['currency_code'], $quotation_info['currency_value']),
					'ogprice'  => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quotation_info['currency_code'], $quotation_info['currency_value']),
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $quotation_info['currency_code'], $quotation_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $quotation_info['currency_code'], $quotation_info['currency_value']),
				    'href'     => $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'gst'	   => $this->currency->format(($this->config->get('config_tax') ? $product['tax'] : 0), $quotation_info['currency_code'], $quotation_info['currency_value'])
				);
			}

			// Voucher
			$data['vouchers'] = array();

			// Totals
			$data['totals'] = array();
			$totals = $this->model_account_quotation->getquotationTotals($this->request->get['quotation_id']);
			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $quotation_info['currency_code'], $quotation_info['currency_value']),
				);
			}
                        
                        $data['enable_approval'] = false;
                        if($this->config->get('config_enable_approval')){
                                $data['enable_approval'] = true;
                        }
                        
                        $this->load->model('account/customer');
                
                        $data['isPetronasUser'] = $this->model_account_customer->isPetronasUser();
                        
                        $data['quotationCustomerId'] = $this->customer->getId();
                        
                        $data['quotationSuperAdmin'] = $this->model_account_quotation->getQuotationSuperAdmin();
                        
                        $data['quotation_approval'] = $this->model_account_quotation->getQuotationApproval();

			$data['comment'] = nl2br($quotation_info['comment']);
			if (isset($this->session->data['comment'])) {
				$data['customer_comment'] = $this->session->data['comment'];
			} else {
				$data['customer_comment'] = '';
			}

			$data['cancel'] = $this->url->link('account/quotation', '', 'SSL');
			$data['placeorder'] = $this->url->link('account/quotation/placeorder', 'quotation_id=' . $quotation_info['quotation_id'], 'SSL');
			$data['rejectquotation'] = $this->url->link('account/quotation/rejectquotation', 'quotation_id=' . $quotation_info['quotation_id'], 'SSL');
			$data['quotation_under_review'] = $quotation_info['quotation_under_review'];
			$data['text_quotation_under_review'] = $this->language->get('text_quotation_under_review');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$data['text_checkout_option'] = $this->language->get('text_checkout_option');
			$data['text_checkout_account'] = $this->language->get('text_checkout_account');
			$data['text_checkout_payment_address'] = $this->language->get('text_checkout_payment_address');
			$data['text_checkout_shipping_address'] = $this->language->get('text_checkout_shipping_address');
			$data['text_checkout_shipping_method'] = $this->language->get('text_checkout_shipping_method');
			$data['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');
			$data['text_checkout_confirm'] = $this->language->get('text_checkout_confirm');
     
			$data['shipping_required'] = $quotation_info['shipping_required'];
			
			$this->session->data['payment_address']['zone_id'] = $quotation_info['payment_zone_id'];
			$this->session->data['payment_address']['country_id'] = $quotation_info['payment_country_id'];
			$this->session->data['payment_method']['code'] = 'ipay88';
			$this->session->data['payment_methods'][0] = 'ipay88'; //set default payment method
			$this->session->data['order_type'] = 'quotation';
			if ($this->config->get('ipay88_status') == 1){
			$data['payment'] = $this->load->controller('extension/payment/ipay88');	
			}else{
			$data['payment'] = '';
			}

			$data['payment2'] = $this->load->controller('extension/payment/free_checkout');
			
			$data['payment3'] = $this->load->controller('extension/payment/cod');
			
			$data['payment4'] = $this->load->controller('extension/payment/bank_transfer');
			
			$data['payment5'] = $this->load->controller('extension/payment/pp_standard');
			
			$data['payment6'] = $this->load->controller('extension/payment/ghl');
			
			//print_r($data); exit;
			
			$data['invoice_page'] = $this->url->link('account/quotation/pdf', 'quotation_id=' . $quotation_id.'&print=1', 'SSL');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/quotation_info.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/quotation_info.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('account/quotation_info.tpl', $data));
			}
		} 
		else {
			$this->document->setTitle($this->language->get('text_quotation'));
			$data['heading_title'] = $this->language->get('text_quotation');
			$data['text_error'] = $this->language->get('text_error');
			$data['button_continue'] = $this->language->get('button_continue');

			$data['breadcrumbs'] = array();
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL')
			);
			$data['breadcrumbs'][] = array(
				'text' => 'Quotation',
				'href' => $this->url->link('account/quotation', '', 'SSL')
			);
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_quotation'),
				'href' => $this->url->link('account/quotation/info', 'quotation_id=' . $quotation_id, 'SSL')
			);

			$data['continue'] = $this->url->link('account/quotation', '', 'SSL');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('error/not_found.tpl', $data));
			}
		}
	}

	public function saveAttachment(){
		$json = array();
		$this->load->language('tool/upload');
		$json['error_warning'] = "";
		$json['success'] = "";
		
		if(isset($this->request->post['quotation_id'])){
			$id = $this->request->post['quotation_id'];
		
			if(!empty($this->request->files['filename']['name']) && is_file($this->request->files['filename']['tmp_name'])) {
				// Sanitize the filename
				$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['filename']['name'], ENT_QUOTES, 'UTF-8')));
				$file = $id.'_'.$filename;
				
				// Allowed file extension types
				$allowed = array('jpeg', 'jpg', 'pdf', 'bmp', 'gif', 'png');
				$path_info = pathinfo($filename);
				if (!in_array(strtolower($path_info['extension']), $allowed)) {
					$json['error_warning'] = $this->language->get('error_filetype');
				}
				else {
					if(move_uploaded_file($this->request->files['filename']['tmp_name'], DIR_UPLOAD.'quotation/' . $file)){
						$this->load->model('tool/upload');
						$json['code'] = $this->model_tool_upload->addUpload($filename, $file);
						
						$this->load->model('account/quotation');
						$this->model_account_quotation->saveQuotationAttachment($id, $file);
						$json['success'] = "Attachment uploaded successfully!";
					}
					else{
						if(is_writable(DIR_UPLOAD.'quotation/'))
							$json['error_warning'] = "Error uploading file. Permission denied.";
						else if(is_dir(DIR_UPLOAD.'quotation/'))
							$json['error_warning'] = "Error uploading file. Folder not found: ".DIR_UPLOAD.'quotation/';
						else
							$json['error_warning'] = $this->request->files['filename']["error"];
					}
				}
			}
			else{
				$json['error_warning'] = "No image uploaded.";
			}
		}
		else{
			$json['error_warning'] = "Quotation no longer available. Please reload your page.";
		}
		if($json['error_warning'] == '')
			$json['redirect'] = $this->url->link('account/quotation&success='.urlencode($json['success']), '', 'SSL');
		else
			$json['redirect'] = '';
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
        
        
	
        public function approveQuotation(){
		$json = array();
		$json['error_warning'] = "";
		$json['success'] = "";
		
		if(isset($this->request->post['quotation_id'])){
			$id = $this->request->post['quotation_id'];
                        
                        $this->load->model('account/quotation');
                        $this->model_account_quotation->approveQuotation($id);
                        $json['success'] = "Quotation has been approved";
		}
		else{
			$json['error_warning'] = "Quotation no longer available. Please reload your page.";
		}
		if($json['error_warning'] == '')
			$json['redirect'] = $this->url->link('account/quotation&success='.urlencode($json['success']), '', 'SSL');
		else
			$json['redirect'] = '';
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
        
        public function rejectQuotations(){
		$json = array();
		$json['error_warning'] = "";
		$json['success'] = "";
		
		if(isset($this->request->post['quotation_id'])){
			$id = $this->request->post['quotation_id'];
                        $reason = $this->request->post['reject_reason'];
                        
                        $this->load->model('account/quotation');
                        $this->model_account_quotation->rejectQuotation($id,$reason);
                        $json['success'] = "Quotation has been rejected";
		}
		else{
			$json['error_warning'] = "Quotation no longer available. Please reload your page.";
		}
		if($json['error_warning'] == '')
			$json['redirect'] = $this->url->link('account/quotation&success='.urlencode($json['success']), '', 'SSL');
		else
			$json['redirect'] = '';
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
        
        
	public function deleteAttachment(){
		$json = array();
		$json['error_warning'] = "";
		$json['success'] = "";
		
		if(isset($this->request->get['quotation_id'])){
			$id = $this->request->get['quotation_id'];
			$this->load->model('account/quotation');
			$this->model_account_quotation->deleteQuotationAttachment($id);
			$json['success'] = "Attachment for Quotation ID: #".$id." has been removed!";
		}
		else{
			$json['error_warning'] = "Quotation no longer available. Please reload your page.";
		}
		if($json['error_warning'] == '')
			$json['redirect'] = $this->url->link('account/quotation&success='.urlencode($json['success']), '', 'SSL');
		else
			$json['redirect'] = '';
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}