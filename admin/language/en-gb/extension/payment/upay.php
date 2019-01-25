<?php
/**
 * MOLPay OpenCart Plugin
 * 
 * @package Payment Gateway
 * @author MOLPay Technical Team <technical@upay.com>
 * @version 2.0
 */
 
 // Versioning
$_['upay_ptype'] = "OpenCart";
$_['upay_pversion'] = "2.0";

// Heading
$_['heading_title']					= 'Upay Payment Gateway';
// Text
$_['text_payment']					= 'Payment';
$_['text_success']					= 'You have modified Upay Payment Gateway account details sucessfully!';
$_['text_edit']                                         = 'Edit Upay';
$_['text_upay']	     			                = '<a onclick="window.open(\'https://www.upay2us.com/iServeGateway/\');" style="text-decoration:none;"><img src="view/image/payment/upay-logo.png" alt="Upay Payment Gateway" title="Upay Payment Gateway" style="border: 0px solid #EEEEEE;" height=63 width=100/></a>';
// Entry
$_['entry_mid']						= 'Merchant ID';
$_['entry_vkey']					= 'Verify Key';
$_['entry_order_status']			        = 'Order Status';
$_['upay_envornment']			                = 'Upay Envornment';
$_['entry_completed_status']	                 	= 'Completed Status';
$_['entry_pending_status']			        = 'Pending Status';
$_['entry_failed_status']			        = 'Failed Status';
$_['entry_geo_zone']				        = 'Geo Zone';
$_['entry_status']					= 'Status';
$_['entry_sort_order']				        = 'Sort Order';

// Help
$_['help_vkey']						= 'Please refer to your Upay Merchant Profile for this key.';
$_['help_mid']						= 'Please refer to your Upay Merchant Profile for this key.';

// Error
$_['error_permission']				        = 'Warning: You do not have permission to modify Upay Payment Gateway!';
$_['error_mid']						= '<b>Upay Merchant ID</b> Required!';
$_['error_vkey']					= '<b>Upay Verify Key</b> Required!';
$_['error_settings']       			        = 'Upay merchant id and verify key mismatch, contact support@upay.com to assist.';
$_['error_status']          		                = 'Unable to connect Upay API.';