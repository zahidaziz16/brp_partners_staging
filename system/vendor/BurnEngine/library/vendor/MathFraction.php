<?php

class MathFraction
{
    protected $numerator;
    protected $denominator;

    public function __construct($numerator, $denominator)
    {
        if ($denominator == 0) {
            throw new Exception('Denominator cannot be zero');
        }

        $this->numerator   = (int) $numerator;
        $this->denominator = (int) $denominator;
    }

    public function add(MathFraction $fraction)
    {
        $num1 = $this->getNumerator();
        $num2 = $fraction->getNumerator();

        $den1 = $this->getDenominator();
        $den2 = $fraction->getDenominator();

        $den = $den1 * $den2;
        $num = $num1 * $den2 + $num2 * $den1;

        $fraction = new MathFraction($num, $den);
        $fraction->reduce();

        return $fraction;
    }

    public function subtract(MathFraction $fraction)
    {
        return $this->add($fraction->getNegative());
    }

    public function multiply(MathFraction $fraction)
    {
        $num1 = $this->getNumerator();
        $num2 = $fraction->getNumerator();

        $den1 = $this->getDenominator();
        $den2 = $fraction->getDenominator();

        $den = $den1 * $den2;
        $num = $num1 * $num2;

        $fraction = new MathFraction($num, $den);
        $fraction->reduce();

        return $fraction;
    }

    public function divide(MathFraction $fraction)
    {
        return $this->multiply($fraction->getReciprocal());
    }

    public function reduce()
    {
        if (!$this->isReducible()) {
            return;
        }

        $num = $this->getNumerator();
        $den = $this->getDenominator();

        if ($num === 0) {
            $this->setDenominator(1);

            return;
        }

        if (($num < 0 && $den < 0) || $den < 0) {
            $num = - $num;
            $den = - $den;
        }

        $gcf = $this->gcd(abs($num) , abs($den));

        if ($gcf != 1) {
            $num = floor($num / $gcf);
            $den = floor($den / $gcf);
        }

        $this->setNumerator($num);
        $this->setDenominator($den);
    }

    public function isReducible()
    {
        $num = $this->getNumerator();
        $den = $this->getDenominator();

        return
        ($num == 0 && $den !== 1)                    // for zero, 0/1 is best
        || (($num < 0 && $den < 0) || $den < 0)      //test if both are negative
        || !(($num % $den) && ($den % $num))         //test if divisible
        || ($this->gcd(abs($num) , abs($den)) != 1);
    }

    public function getNumerator()
    {
        return $this->numerator;
    }

    public function getDenominator()
    {
        return $this->denominator;
    }

    public function setNumerator($int)
    {
        $this->numerator = (int) $int;
    }

    public function setDenominator($int)
    {
        if ($int == 0) {
            throw new Exception('Denominator cannot be zero');
        }

        $this->denominator = (int) $int;
    }

    public function getReciprocal()
    {
        if ($this->getNumerator() == 0) {
            throw new Exception('Zero has no reciprocal');
        }

        return new MathFraction($this->getDenominator() , $this->getNumerator());
    }

    public function getNegative()
    {
        return new MathFraction(-($this->getNumerator()) , $this->getDenominator());
    }

    public function toPrimitive()
    {
        return $this->getNumerator() / $this->getDenominator();
    }

    public function __toString()
    {
        return $this->getNumerator() . '/' . $this->getDenominator();
    }

    protected function gcd($a, $b)
    {
        if ($b > $a) {
            list($a, $b) = array($b, $a);
        }

        $c = 1;

        while ($c > 0) {
            $c = $a % $b;
            $a = $b;
            $b = $c;
        }

        return $a;
    }
}