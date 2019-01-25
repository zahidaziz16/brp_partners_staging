<?php
class ControllerStartupPermission extends Controller {
	public function index() {
		if (isset($this->request->get['route'])) {
			$route = '';
			
			$part = explode('/', $this->request->get['route']);

			if (isset($part[0])) {
				$route .= $part[0];
			}

			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}

			// If a 3rd part is found we need to check if its under one of the extension folders.
			$extension = array(
				'extension/dashboard',
				'extension/analytics',
				'extension/captcha',
				'extension/extension',
				'extension/feed',
				'extension/fraud',
				'extension/module',
				'extension/payment',
				'extension/shipping',
				'extension/theme',
				'extension/total'
			);

			if (isset($part[2]) && in_array($route, $extension)) {
				$route .= '/' . $part[2];
			}
			
			// We want to ingore some pages from having its permission checked. 
			$ignore = array(
				'common/dashboard',
				'common/login',
				'common/logout',
				'common/forgotten',
                                'tool/data_sync',
				'common/reset',
				'error/not_found',
				'error/permission'
			);
			
			if (!in_array($route, $ignore) && !$this->user->hasPermission('access', $route)) {
				return new Action('error/permission');
			} else if(in_array($route, array("warehouse/transactions","warehouse/transactions/headeradd","warehouse/transactions/headerdelete","warehouse/transactions/headertran","warehouse/transactions_header_list","warehouse/transactions/add","warehouse/transactions/delete","warehouse/transactions/detailtran","warehouse/transactions/detailaddedit","warehouse/transactions_content_list","warehouse/transactions_header_add"))) {
				if(!$this->config->get('config_using_warehouse_module')) {
					return new Action('error/permission');
				}
			}

		}
	}
}
