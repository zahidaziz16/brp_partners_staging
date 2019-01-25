<?php

class TB_PriceFormatter
{
    /**
     * @var Cart\Currency|Currency
     */
    protected $currency;
    protected $currency_code;
    protected $decimal_point;
    protected $thousand_point;
    protected $symbol_left;
    protected $symbol_right;
    protected $format_method;

    public function __construct($currency, $currency_code, $decimal_point, $thousand_point)
    {
        $this->currency = $currency;
        $this->currency_code = $currency_code;
        $this->decimal_point = $decimal_point;
        $this->thousand_point = $thousand_point;
        $this->symbol_left = trim($this->currency->getSymbolLeft($this->currency_code));
        $this->symbol_right = trim($this->currency->getSymbolRight($this->currency_code));

        if(function_exists('mb_substr') && function_exists('mb_strrpos')) {
            $this->format_method = 'mb';
        } else
        if (function_exists('utf8_substr')) {
            $this->format_method = 'lib';
        } else {
            $this->format_method = 'simple';
        }
    }

    public function format($price)
    {
        switch ($this->format_method) {
            case 'mb':
                return $this->formatMb($price);
                break;
            case 'lib':
                return $this->formatLib($price);
                break;
            default:
                return $this->formatSimple($price);
        }
    }

    public function extract($price)
    {
        switch ($this->format_method) {
            case 'mb':
                if ($this->symbol_left && false !== mb_strpos($price, $this->symbol_left, null, 'UTF-8')) {
                    $price = mb_substr($price, mb_strpos($price, $this->symbol_left, null, 'UTF-8') + mb_strlen($this->symbol_left, 'UTF-8'), mb_strlen($price, 'UTF-8'), 'UTF-8');
                }

                if ($this->symbol_right && false !== mb_strpos($price, $this->symbol_right, null, 'UTF-8')) {
                    $price = mb_substr($price, 0, mb_strpos($price, $this->symbol_right, null, 'UTF-8'), 'UTF-8');
                }
                break;
            default:
                if ($this->symbol_left && false !== utf8_strpos($price, $this->symbol_left)) {
                    $price = utf8_substr($price, utf8_strpos($price, $this->symbol_left) + utf8_strlen($this->symbol_left), utf8_strlen($price));
                }

                if ($this->symbol_right && false !== utf8_strrpos($price, $this->symbol_right)) {
                    $price = utf8_substr($price, 0, utf8_strrpos($price, $this->symbol_right));
                }
        }

        return floatval(str_replace($this->decimal_point, '.', preg_replace('/[^\d'.preg_quote($this->decimal_point).']/', '', $price)));
    }

    protected function formatMb($price)
    {
        $price_before = '';
        $price_after = '';

        if ($this->symbol_left) {
            $price_before = '<span class="tb_currency tb_before">' . $this->symbol_left . '</span>';
            $price = mb_substr($price, mb_strpos($price, $this->symbol_left, null, 'UTF-8') + mb_strlen($this->symbol_left, 'UTF-8'), mb_strlen($price, 'UTF-8'), 'UTF-8');
        }

        if ($this->symbol_right) {
            $price_after = '<span class="tb_currency tb_after">' . $this->symbol_right . '</span>';
            $price = mb_substr($price, 0, mb_strpos($price, $this->symbol_right, null, 'UTF-8'), 'UTF-8');
        }

        $parts = explode($this->decimal_point, $price);
        if (isset($parts[1])) {
            $last = array_pop($parts);
            $price = '<span class="tb_integer">' . implode($this->decimal_point, $parts) . '</span><span class="tb_decimal_point">' . $this->decimal_point . '</span><span class="tb_decimal">' . $last . '</span>';
        }

        return $price_before . $price . $price_after;
    }

    protected function formatLib($price)
    {
        $price_before = '';
        $price_after = '';

        if ($this->symbol_left) {
            $price_before = '<span class="tb_currency tb_before">' . $this->symbol_left . '</span>';
            $price = utf8_substr($price, utf8_strpos($price, $this->symbol_left) + utf8_strlen($this->symbol_left), utf8_strlen($price));
        }

        if ($this->symbol_right) {
            $price_after = '<span class="tb_currency tb_after">' . $this->symbol_right . '</span>';
            $price = utf8_substr($price, 0, utf8_strrpos($price, $this->symbol_right));
        }

        $parts = explode($this->decimal_point, $price);
        if (isset($parts[1])) {
            $last = array_pop($parts);
            $price = '<span class="tb_integer">' . implode($this->decimal_point, $parts) . '</span><span class="tb_decimal_point">' . $this->decimal_point . '</span><span class="tb_decimal">' . $last . '</span>';
        }

        return $price_before . $price . $price_after;
    }

    protected function formatSimple($price)
    {
        if ($this->symbol_left) {
            $price = substr_replace($price, '<span class="tb_currency tb_before">' . $this->symbol_left . '</span>', strpos($price, $this->symbol_left), strlen($this->symbol_left));
        }

        if ($this->symbol_right) {
            $price = substr_replace($price, '<span class="tb_currency tb_after">' . $this->symbol_right . '</span>', strrpos($price, $this->symbol_right), strlen($this->symbol_right));
        }

        return $price;
    }
}