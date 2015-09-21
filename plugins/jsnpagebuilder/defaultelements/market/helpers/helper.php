<?php
/**
 * @version    $Id$
 * @package    JSN_PageBuilder
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file.
defined('_JEXEC') || die('Restricted access');

/**
 * Helper class for market element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbMarketHelper
{
    const PB_MARKET_DATA_TYPE_NAME = 'name';
    const PB_MARKET_DATA_TYPE_SYMBOL = 'symbol';
    const PB_MARKET_DATA_TYPE_PRICE = 'LastTradePriceOnly';
    const PB_MARKET_DATA_TYPE_CHANGE = 'Change';
    const PB_MARKET_DATA_TYPE_PERCENT_CHANGE = 'ChangeinPercent';
    const PB_MARKET_DATA_TYPE_VOLUME = 'Volume';
    const PB_MARKET_DATA_TYPE_CHART = 'chart';

    const PB_MARKET_SLIDE_DIMENSION_HORIZONTAL = "horizontal";
    const PB_MARKET_SLIDE_DIMENSION_VERTICAL = "vertical";

    public static function getMarketSlideDimensions() {
        return array(
            self::PB_MARKET_SLIDE_DIMENSION_VERTICAL => JText::_('Vertical'),
            self::PB_MARKET_SLIDE_DIMENSION_HORIZONTAL => JText::_('Horizontal'),
        );
    }

    public static function getMarketDataType() {
        return array(
            self::PB_MARKET_DATA_TYPE_NAME           => JText::_('Name'),
            self::PB_MARKET_DATA_TYPE_SYMBOL         => JText::_('Symbol'),
            self::PB_MARKET_DATA_TYPE_PRICE          => JText::_('Price'),
            self::PB_MARKET_DATA_TYPE_CHANGE         => JText::_('Change'),
            self::PB_MARKET_DATA_TYPE_PERCENT_CHANGE => JText::_('% Change'),
            self::PB_MARKET_DATA_TYPE_VOLUME         => JText::_('Volume'),
//            self::PB_MARKET_DATA_TYPE_CHART          => JText::_('Chart'),
        );
    }

    public static function getMarketDefaultDataType() {
        return implode("__#__", array(
            self::PB_MARKET_DATA_TYPE_SYMBOL,
            self::PB_MARKET_DATA_TYPE_PRICE,
            self::PB_MARKET_DATA_TYPE_CHANGE,
            self::PB_MARKET_DATA_TYPE_PERCENT_CHANGE,
        ));
    }
}