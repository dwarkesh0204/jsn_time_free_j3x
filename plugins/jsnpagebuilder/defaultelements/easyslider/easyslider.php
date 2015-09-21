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

include_once 'helpers/helper.php';

/**
 * JSN Easy Slider shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeEasyslider extends IG_Pb_Element {

    /**
     * Constructor
     *
     */
    public function __construct() {
        if (JComponentHelper::isInstalled('com_easyslider') === 1 && JComponentHelper::isEnabled('com_easyslider') === '1') {
            include_once JPATH_ROOT . '/administrator/components/com_easyslider/classes/jsn.easyslider.render.php';
        }
        parent::__construct();
    }

    /**
     * Include admin scripts
     *
     * @return type
     */
    public function backend_element_assets() {
    }

    /**
     * DEFINE configuration information of shortcode
     *
     * @return type
     */
    public function element_config() {
        $this->config['shortcode'] = 'pb_easyslider';
        $this->config['name'] = JText::_('JSN Easy Slider');
        $this->config['cat'] = JText::_('Extra');
        $this->config['icon'] = 'icon-pb-easyslider';
        $this->config['description'] = JText::_("Multipurpose content slider with super user-friendly interface");
    }

    /**
     * DEFINE setting options of shortcode in backend
     */
    public function backend_element_items()
    {
        $allSlider = JSNPbEasySliderHelper::getAllSlider();
        $this->items = array(
            'content' => array(
                array(
                    'name'  => JText::_('Element Title'),
                    'id'    => 'el_title',
                    'type'  => 'text_field',
                    'class' => 'jsn-input-xxlarge-fluid',
                    'std'   => JText::_('JSN Easy Slider PB_INDEX_TRICK'),
                    'role'  => 'title',
                ),
                array(
                    'name'    => JText::_('Easy Slider'),
                    'id'      => 'easyslider_id',
                    'type'    => 'select',
                    "class"   => "jsn-input-large-fluid",
                    'options' => $allSlider,
                    'std'     => JSNPagebuilderHelpersType::getFirstOption($allSlider),
                ),
            ),
            'styling' => array(
                array(
                    'type' => 'preview',
                ),
            ),
        );
    }

    /**
     * DEFINE setting options of shortcode in frontend
     */
    public function frontend_element_items()
    {
        $this->items = array(
            'content' => array(
                array(
                    'name'  => JText::_('Element Title'),
                    'id'    => 'el_title',
                    'type'  => 'text_field',
                    'class' => 'jsn-input-xxlarge-fluid',
                    'std'   => JText::_('JSN Easy Slider PB_INDEX_TRICK'),
                    'role'  => 'title',
                ),
                array(
                    'name'    => JText::_('Easy Slider'),
                    'id'      => 'easyslider_id',
                    'type'    => 'select',
                    "class"   => "jsn-input-large-fluid",
                ),
            ),
            'styling' => array(
                array(
                    'type' => 'preview',
                ),
            ),
        );
    }

    /**
     * DEFINE setting options of shortcode
     *
     * @return type
     */
    public function element_shortcode($atts = null, $content = null) {
        $arr_params = JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $atts);
        extract($arr_params);

        if (JComponentHelper::isInstalled('com_easyslider') !== 1 || JComponentHelper::isEnabled('com_easyslider') !== '1') {
            $html_element = JText::_('Please install or enable JSN Easy Slider Component before use this element');
            return $this->element_wrapper($html_element, $arr_params);
        }

        $easySliderId = (int) $arr_params['easyslider_id'];
        /** @var JSNEasySliderRender $objJSNEasySliderRender */
        $objJSNEasySliderRender = new JSNEasySliderRender();
        $html = $objJSNEasySliderRender->render($easySliderId, true);

        return $this->element_wrapper($html, $arr_params);
    }

}
