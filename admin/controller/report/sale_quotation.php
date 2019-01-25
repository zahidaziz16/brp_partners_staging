<?php
class ControllerReportSaleQuotation extends Controller {
	public function index() {
		$this->load->language('report/sale_quotation');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

//		if (isset($this->request->get['filter_group'])) {
//			$filter_group = $this->request->get['filter_group'];
//		} else {
//			$filter_group = 'week';
//		}
                
                if (isset($this->request->get['filter_approver']) && $this->request->get['filter_approver'] != "") {
			$filter_approver = $this->request->get['filter_approver'];
		}else {
                    $filter_approver = "";
                }
                
                if (isset($this->request->get['filter_approved']) && $this->request->get['filter_approved'] != "") {
                    
                    $filter_approved = $this->request->get['filter_approved'];
                    
		}else {
                    $filter_approved = "";
                }
                
                

		if (isset($this->request->get['filter_quotation_status_id'])) {
			$filter_quotation_status_id = $this->request->get['filter_quotation_status_id'];
		} else {
			$filter_quotation_status_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

//		if (isset($this->request->get['filter_group'])) {
//			$url .= '&filter_group=' . $this->request->get['filter_group'];
//		}

                if (isset($this->request->get['filter_approver'])) {
			$url .= '&filter_approver=' . $this->request->get['filter_approver'];
		}
                
                if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}
                
		if (isset($this->request->get['filter_quotation_status_id'])) {
			$url .= '&filter_quotation_status_id=' . $this->request->get['filter_quotation_status_id'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/sale_quotation', 'token=' . $this->session->data['token'] . $url, true)
		);

		$this->load->model('report/quotation');

		$data['quotations'] = array();

		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			//'filter_group'           => $filter_group,
                        'filter_approver'           => $filter_approver,
                        'filter_approved'           => $filter_approved,
			'filter_quotation_status_id' => $filter_quotation_status_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$quotation_total = $this->model_report_quotation->getTotalQuotations($filter_data);

		$results = $this->model_report_quotation->getQuotations($filter_data);

		foreach ($results as $result) {
			$data['quotations'][] = array(
				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
                                'quotation_date'     => $result['date_start'],
                                'bord'     => $result['bord'],
                                'user_name'     => $result['user_name'],
                                'approver_name'     => $result['approver_name'],
                                'delivery_date'     => $result['delivery_date'],
                                'quotation_id'     => $result['quotation_id'],
				'quotations'     => $result['quotations'],
				'products'   => $result['products'],
				'tax'        => $this->currency->format($result['tax'], $this->config->get('config_currency')),
                                'sub_total'        => $this->currency->format($result['sub_total'], $this->config->get('config_currency')),
				'total'      => $this->currency->format($result['total'], $this->config->get('config_currency'))
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_quotations'] = $this->language->get('column_quotations');
		$data['column_products'] = $this->language->get('column_products');
		$data['column_tax'] = $this->language->get('column_tax');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/quotation_status');

		$data['quotation_statuses'] = $this->model_localisation_quotation_status->getquotationStatuses();

		$data['groups'] = array();

		$data['groups'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		);

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

//		if (isset($this->request->get['filter_group'])) {
//			$url .= '&filter_group=' . $this->request->get['filter_group'];
//		}
                
                if (isset($this->request->get['filter_approver'])) {
			$url .= '&filter_approver=' . $this->request->get['filter_approver'];
		}
                
                if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}
                
//                

		if (isset($this->request->get['filter_quotation_status_id'])) {
			$url .= '&filter_quotation_status_id=' . $this->request->get['filter_quotation_status_id'];
		}

		$pagination = new Pagination();
		$pagination->total = $quotation_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/sale_quotation', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($quotation_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($quotation_total - $this->config->get('config_limit_admin'))) ? $quotation_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $quotation_total, ceil($quotation_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		//$data['filter_group'] = $filter_group;
                $data['filter_approver'] = $filter_approver;
                $data['filter_approved'] = $filter_approved;
		$data['filter_quotation_status_id'] = $filter_quotation_status_id;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/sale_quotation', $data));
	}
        
        function exportPSExcel(){
                require_once DIR_SYSTEM.'/PHPExcel18/Classes/PHPExcel.php';
                
                $this->load->model('report/quotation');
                
                $approver =  $this->request->get['approver'];
                $start_date = $this->request->get['start_date'];
                $end_date = trim($this->request->get['end_date']);
                $approved = $this->request->get['approved'];
                $quotationStatusId = $this->request->get['quotation_status'];

                $columns = 'a.*, f.delivery_date AS delivery_date, e.approved_date AS approved_date, CASE WHEN a.quotation_status_id = 1 THEN "Pending for Approval" WHEN a.quotation_status_id = 3 THEN "Quotation Rejected" WHEN a.quotation_status_id = 11 THEN "Quotation Approved, Pending Confirmation" WHEN a.quotation_status_id = 6 THEN c.name WHEN a.quotation_status_id = 7 THEN CONCAT(c.name, " Order ID: ", b.order_id, " Order Date: ", b.date_added, " Order Status: ", d.name) WHEN a.quotation_status_id = 8 THEN CONCAT(c.name, " Order ID: ", b.order_id, " Order Date: ", b.date_added, " Order Status: ", d.name) WHEN a.quotation_status_id = 9 THEN CONCAT(c.name, " Order ID: ", b.order_id, " Order Date: ", b.date_added, " Order Status: ", d.name) WHEN a.quotation_status_id = 10 THEN CONCAT(c.name, " Order ID: ", b.order_id, " Order Date: ", b.date_added, " Order Status: ", d.name) END AS bord ';
                $table = 'oc_quotation a LEFT JOIN oc_order b ON a.order_id = b.order_id LEFT JOIN oc_quotation_status c ON a.quotation_status_id = c.quotation_status_id LEFT JOIN oc_order_status d ON b.order_status_id = d.order_status_id LEFT JOIN (SELECT MIN(date_added) AS approved_date, quotation_id FROM `oc_quotation_history` WHERE `quotation_status_id` IN (9,10,8,7,11) GROUP BY quotation_id) AS e ON a.quotation_id = e.quotation_id LEFT JOIN (SELECT MIN(date_added) AS delivery_date, order_id FROM `oc_order_history` WHERE `order_status_id` IN (5) GROUP BY order_id) AS f ON b.order_id = f.order_id ';

                $condition = "WHERE 1=1 ";
                if(!empty($approver)){
                        $condition .= "AND o.approver_name LIKE '%$approver%' ";
                }if(!empty($start_date) && !empty($end_date)){
                        $condition .= "AND o.date_added >= '$start_date' AND o.date_added <= '$end_date' ";
                }else if(!empty($start_date)){
                        $condition .= "AND o.date_added >= '$start_date' ";
                }else if(!empty($end_date)){
                        
                }if(!empty($approved)){
                    if($approved == "all") {

                    }else if($approved == "no") {
                        $condition .= "AND o.approver_id = '0' ";
                    }else if($approved == "yes") {
                        $condition .= "AND o.approver_id != '0' ";
                    }

                }
                
                if(!empty($quotationStatusId)){
                    $condition .= "AND o.quotation_status_id = '$quotationStatusId' ";
                }else {
                    $condition .= "AND o.quotation_status_id > '0' ";
                }
                $results = $this->model_report_quotation->getExcelReport($condition);
                
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
                
                if(!empty($quotationStatusId)){
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('E2', 'Quotation Status:')
                            ->setCellValue('F2', $quotationStatusId);
                }
                
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
                            ->setCellValue('G5', 'Price (exl GST)')
                            ->setCellValue('H5', 'GST')
                            ->setCellValue('I5', 'Material Purchase Price');
                
                $objPHPExcel->getActiveSheet()->getStyle("A1:I5")->applyFromArray($boldStyle);
                $objPHPExcel->getActiveSheet()->getStyle("A5:I5")->applyFromArray($centerStyle);
                $objPHPExcel->getActiveSheet()->getStyle("A5:I5")->applyFromArray($greencell);
                $objPHPExcel->getActiveSheet()->getStyle("A5:I5")->applyFromArray($borderStyle);
                
                $row = 6;
                $total = 0;
                $no = 0;
                $approvedNo = 0;
                foreach($results AS $key => $value) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$row, $value['quotation_id'])
                            ->setCellValue('B'.$row, $value['date_start'])
                            ->setCellValue('C'.$row, $value['bord'])
                            ->setCellValue('D'.$row, $value['delivery_date'])
                            ->setCellValue('E'.$row, $value['user_name'])
                            ->setCellValue('F'.$row, $value['approver_name'])
                            ->setCellValue('G'.$row, $value['sub_total'])
                            ->setCellValue('H'.$row, $value['tax'])
                            ->setCellValue('I'.$row, $value['total']);
                    $row++;
                    $total += $value['total'];
                    $no++;
                    if($value['approver_name'] != '') {
                        $approvedNo++;
                    }
                }
                
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('H'.($row+3), 'Total Quotations:')
                            ->setCellValue('I'.($row+3), $no)
                            ->setCellValue('H'.($row+4), 'Total Approved Quotations:')
                            ->setCellValue('I'.($row+4), $approvedNo)
                            ->setCellValue('H'.($row+5), 'Total Purchase Price:')
                            ->setCellValue('I'.($row+5), $total);
                
                $objPHPExcel->getActiveSheet()->getStyle("G".($row+3).":H".($row+5))->applyFromArray($boldStyle);
                
                $colArr = array('B','C','D','E','F','G','H','I');
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
}