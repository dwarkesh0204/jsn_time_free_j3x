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
 * Helper class for weather element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPbImageHelper
{
    /**
     * Link type options
     *
     * @return array
     */
    public static function getClickActionType()
    {
        return array(
            'no_link' => JText::_('No Action'),
            'image'   => JText::_('Show Original Image'),
            'url'     => JText::_('Open Image Link'),
        );
    }

    /**
     * "Open in" option for anchor
     *
     * @return array
     */
    static function getOpenInOptions() {
        return array(
            'current_browser' => JText::_( 'Current Browser' ),
            'new_browser' 	  => JText::_( 'New Browser' ),
        );
    }
}