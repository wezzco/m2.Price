<?php
/**
 * Copyright © 2011-2018 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * 
 * See COPYING.txt for license details.
 */
namespace Faonni\Price\Model;

use Faonni\Price\Helper\Data as PriceHelper;

/**
 * Math Model
 */
class Math
{
    /**
     * Round Fractions Up
     */	
	const TYPE_CEIL = 'ceil';
	
    /**
     * Round Fractions Down
     */	
	const TYPE_FLOOR = 'floor';
	
    /**
     * Swedish Round Fractions Up
     */	
	const TYPE_SWEDISH_CEIL = 'swedish_ceil';
	
    /**
     * Swedish Round Fractions
     */	
	const TYPE_SWEDISH_ROUND = 'swedish_round';
	
    /**
     * Swedish Round Fractions Down
     */	
	const TYPE_SWEDISH_FLOOR = 'swedish_floor';
	
    /**
     * Round Price Helper
     *
     * @var Faonni\Price\Helper\Data
     */
    protected $_helper;    
	
    /**
     * Initialize Model
     * 
     * @param PriceHelper $helper
     */
    public function __construct(
        PriceHelper $helper
    ) {
        $this->_helper = $helper;
    }
	         
    /**
     * Retrieve the Rounded Price
     * 
     * @param float $price
     * @return float
     */
    public function round($price)
    {
		$helper = $this->_helper;
		$fraction = $helper->getSwedishFraction();
		$precision = $helper->getPrecision();
		switch ($helper->getRoundType()) {
			case self::TYPE_CEIL:
                    
                    if ($precision < 0):

                        // Use ceil when precision < 1 (when rounding full currency, not cents)
                        $price = $this->round_up($price, $precision);  
                    else:
                        $price = round($price, $precision, PHP_ROUND_HALF_UP);
                    endif;

				break;
			case self::TYPE_FLOOR:
				
                 if ($precision < 0):
                        // Use ceil when precision < 1 (when rounding full currency, not cents)
                        $price = $this->round_down($price, $precision);  
                    else:
                        $price = round($price, $precision, PHP_ROUND_HALF_DOWN);
                    endif;

				break;
			case self::TYPE_SWEDISH_CEIL:
				$price = ceil($price/$fraction) * $fraction;
				break;
			case self::TYPE_SWEDISH_ROUND:
				$price = round($price/$fraction) * $fraction;
				break;
			case self::TYPE_SWEDISH_FLOOR:
				$price = floor($price/$fraction) * $fraction;
				break;				
		}
		return $price;
    }

    /**
     * Excel-like ROUNDUP function
     * 
     * @param float $value
     * @return float
     */

    public static function round_up($value, $places) 
    {
        $mult = pow(10, abs($places)); 
         return $places < 0 ?
        ceil($value / $mult) * $mult :
            ceil($value * $mult) / $mult;
    }

    public static function round_down($value, $places) 
    {
        $mult = pow(10, abs($places)); 
         return $places < 0 ?
        floor($value / $mult) * $mult :
            floor($value * $mult) / $mult;
    }


}
