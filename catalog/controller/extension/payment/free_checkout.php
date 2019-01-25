<?php
class ControllerExtensionPaymentFreeCheckout extends Controller {
	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['text_loading'] = $this->language->get('text_loading');

		if($this->session->data['order_type'] == 'quotation'){
			$data['type'] = "confirmQ";
			$data['continue'] = $this->url->link('account/quotation');
		}else{
			$data['type'] = "confirm";
			$data['continue'] = $this->url->link('checkout/success');
		}
		
		return $this->load->view('extension/payment/free_checkout', $data);
	}

	public function confirm() {
			if ($this->session->data['payment_method']['code'] == 'free_checkout') {
			$this->load->model('checkout/order');
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('free_checkout_order_status_id'));
		}
		else if ($this->session->data['payment_method']['code'] == 'quotation_checkout'){
			$this->load->model('quotation/order');
			$this->model_quotation_order->addQuotationHistory($this->session->data['order_id'], $this->config->get('quotation_quotation_system'));

		}
	}
	
	public function confirmQ() { //send email alert
		$this->load->model('quotation/order');
		if(isset($this->request->get['comment'])){
			$comment = $this->request->get['comment'];
		}else{
			$comment = "";
		}
		$this->model_quotation_order->addQuotationHistory($this->session->data['quotation_id'], $this->config->get('quotation_credit_payment'), $comment);
	}
}