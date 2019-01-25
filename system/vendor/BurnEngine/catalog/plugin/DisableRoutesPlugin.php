<?php

class Theme_Catalog_DisableRoutesPlugin extends TB_ExtensionPlugin
{
    public function configure(TB_ViewDataBag $themeData)
    {
        $this->eventDispatcher->connect('core:filterRoute', array($this, 'disableRoutes'));
    }

    public function disableRoutes(sfEvent $event, $route)
    {
        $error_route = 'error/not_found';
        $common = $this->getSetting('common');

        if (!$common['checkout_enabled']) {
            if (in_array($route, array(
                'checkout/cart',
                'checkout/cart/add',
                'checkout/checkout',
                'module/cart',
                'tb/cartCallback'
            ))) {
                $route = $error_route;
            }

        }

        if (!$common['compare_enabled']) {
            if (in_array($route, array(
                'product/compare',
                'tb/compareCallback'
            ))) {
                $route = $error_route;
            }

        }

        if (!$common['wishlist_enabled']) {
            if (in_array($route, array(
                'account/wishlist',
                'tb/wishlistCallback'
            ))) {
                $route = $error_route;
            }

        }

        if (!$common['returns_enabled']) {
            if (in_array($route, array(
                'account/return',
                'account/return/insert',
                'account/return/info'
            ))) {
                $route = $error_route;
            }

        }

        if (!$common['affiliate_enabled']) {
            if (in_array($route, array(
                'affiliate/login',
                'affiliate/forgotten',
                'affiliate/account',
                'affiliate/payment',
                'affiliate/tracking',
                'affiliate/transaction',
            ))) {
                $route = $error_route;
            }

        }

        if (!$common['voucher_enabled']) {
            if (in_array($route, array(
                'account/voucher',
                'account/voucher/success'
            ))) {
                $route = $error_route;
            }

        }

        if (!$common['manufacturers_enabled']) {
            if (in_array($route, array(
                'product/manufacturer',
                'product/manufacturer/product',
                'category/manufacturer'
            ))) {
                $route = $error_route;
            }

        }

        return $route;
    }
}