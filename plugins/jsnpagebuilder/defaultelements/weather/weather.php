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
 * Weather shortcode element
 *
 * @package  JSN_PageBuilder
 * @since    1.0.0
 */
class JSNPBShortcodeWeather extends IG_Pb_Element
{

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Include admin scripts
     *
     * @return mixed
     */
    public function backend_element_assets()
    {
        JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/weather/assets/js/weather.js', 'js');
        JSNPagebuilderHelpersFunctions::print_asset_tag(JSNPB_ELEMENT_URL . '/weather/assets/css/weather.css', 'css');
    }

    /**
     * DEFINE configuration information of shortcode
     *
     * @return mixed
     */
    function element_config()
    {
        $this->config['shortcode'] = 'pb_weather';
        $this->config['name'] = JText::_('Weather');
        $this->config['cat'] = JText::_('Extra');
        $this->config['icon'] = "icon-weather";
        $this->config['description'] = JText::_("Display weather forecast from any place in the world you wish ");

        $this->config['exception'] = array(
            'default_content' => JText::_('Weather')
        );
    }

    /**
     * DEFINE setting options of shortcode in backend
     */
    public function backend_element_items()
    {
        $this->frontend_element_items();
    }

    /**
     * DEFINE setting options of shortcode in frontend
     */
    public function frontend_element_items()
    {
        $this->items = array(
            'content' => array(
                array(
                    "name"    => JText::_("Element Title"),
                    "id"      => "el_title",
                    "type"    => "text_field",
                    "class"   => "jsn-input-xxlarge-fluid",
                    "std"     => JText::_('Weather PB_INDEX_TRICK'),
                    "role"    => "title",
                    "tooltip" => JText::_("Set title for current element")
                ),
                array(
                    'name'    => JText::_('Data source'),
                    'id'      => 'weather_data_source',
                    'type'    => 'select',
                    'std'     => JSNPagebuilderHelpersType::getFirstOption(JSNPbWeatherHelper::getWeatherDataSources()),
                    'options' => JSNPbWeatherHelper::getWeatherDataSources(),
                ),
                array(
                    'name'  => JText::_('City, Town or Region'),
                    'id'    => 'weather_location_code',
                    'type'  => 'text_field',
                    'std'   => 'Hanoi,VN',
                    'class' => 'pb-weather-location-code',
                ),
            ),
            'styling' => array(
                array(
                    'type' => 'preview'
                ),
                array(
                    'name'       => JText::_('Choose Layout'),
                    'id'         => 'weather_layout',
                    'type'       => 'radio',
                    'std'        => 'basic',
                    'options'    => array(
                        'basic' => JText::_('Basic'),
                        'advanced' => JText::_('Advanced')),
                    'has_depend' => '1'
                ),
                array(
                    'name'    => JText::_('Measurements'),
                    'id'      => 'weather_measurement',
                    'type'    => 'radio',
                    'std'     => 'c',
                    'options' => array('f' => JText::_('&#8457;'), 'c' => JText::_('&#8451;')),
                ),
                array(
                    'name'       => JText::_('Show current conditions'),
                    'id'         => 'weather_show_current',
                    'type'       => 'radio',
                    'std'        => JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES,
                    'options'    => JSNPagebuilderHelpersType::getYesNoQuestion(),
                    'dependency' => array('weather_layout', '=', 'advanced'),
                ),
                array(
                    'name'       => JText::_('Show next day forecast'),
                    'id'         => 'weather_show_next',
                    'type'       => 'radio',
                    'std'        => JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES,
                    'options'    => JSNPagebuilderHelpersType::getYesNoQuestion(),
                    'dependency' => array('weather_layout', '=', 'advanced'),
                ),
                array(
                    'name'       => JText::_('Number day'),
                    'id'         => 'weather_number_day',
                    'type'       => 'select',
                    'std'        => 5,
                    'options'    => JSNPbWeatherHelper::getNumberDay(),
                ),
            )
        );
    }

    /**
     * DEFINE shortcode content
     *
     * @param mixed $atts
     * @param mixed $content
     *
     * @return string
     */
    function element_shortcode($atts = null, $content = null)
    {
        $document = JFactory::getDocument();
        $document->addStyleSheet(JSNPB_ELEMENT_URL . '/weather/assets/css/weather.css', 'text/css');

        $arr_params = JSNPagebuilderHelpersShortcode::shortcodeAtts($this->config['params'], $atts);
        extract($arr_params);
        $html_element = '';
        $weatherHelper = new JSNPbWeatherHelper();
        $weatherHelper->setDataSource($atts['weather_data_source']);
        $weatherHelper->setAttributes($atts);
        $weatherHelper->getData();
        $currentWeather = $weatherHelper->getCurrentDay();

        $html_element .= "<div class='pb-weather-wrapper'>";
        if (isset($currentWeather['error'])) {
            $html_element .= "<div class='alert alert-warning'>" . JText::_($currentWeather['error']['description']) . "</div></div>";
            return $this->element_wrapper($html_element, $arr_params);
        }

        $html_element .= "<div class='container-fluid pb-weather-current'>";
        if ($weatherHelper->getAttribute('weather_layout') == 'advanced') {
            $html_element .= "
            <div class=''>
                <h3 class='pb-weather-location-name'>" . $currentWeather['location_full_name'] . "</h3>
            </div>";
            $html_element .= "<div class='row pb-weather-advanced'>
                <div class='col-md-6'>
                    <div class='row'>
                        <img src='" . $currentWeather['icon_url'] . "'/>
                        <span>" . $currentWeather['weather'] . "</span>
                    </div>
                    <div class='row'>
                        <h1 class='pb-weather-current-temp'>" . $currentWeather['temp_current'] . "</h1>
                    </div>
                    <div class='row'>
                        <span class='pb-weather-max-temp'>" . strtoupper(JText::_('max')) . " " . $currentWeather['temp_max'] . "</span>,
                        <span class='pb-weather-min-temp'>" . strtoupper(JText::_('min')) . " " . $currentWeather['temp_min'] . "</span>
                    </div>
                </div>";
            if ($atts['weather_show_current'] == JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES) {
                $html_element .= "<div class='col-md-6 pb-weather-extra'>
                    <div class='row'>
                        <div class='col-md-4'>" . ucfirst(JText::_('humidity')) . ":</div>
                        <div class='col-md-8'>" . $currentWeather['humidity'] . "</div>
                    </div>
                    <div class='row'>
                        <div class='col-md-4'>" . ucfirst(JText::_('visibility')) . ":</div>
                        <div class='col-md-8'>" . $currentWeather['visibility'] . "</div>
                    </div>
                    <div class='row'>
                        <div class='col-md-4'>" . ucfirst(JText::_('wind')) . ":</div>
                        <div class='col-md-8'>" . $currentWeather['wind'] . " " . $currentWeather['wind_dir'] . "</div>
                    </div>
                </div>";
            }
            $html_element .= "</div>";
            $html_element .= "</div>";
            if ($atts['weather_show_next'] == JSNPagebuilderHelpersType::PB_HELPER_ANSWER_YES) {
                $html_element .= "<div class='container-fluid pb-weather-forecast'>
                    <h4 class='pb-weather-forecast-title'>" . strtoupper(JText::_('forecast')) . "</h4>";
                $forecastWeather = $weatherHelper->getForecast();
                $limit = (int)$weatherHelper->getAttribute('weather_number_day');
                for ($_index = 0; $_index < $limit; $_index++) {
                    $_forecastDay = $forecastWeather[$_index];
                    $html_element .= "
                    <div class='pb-weather-forecast-day'>
                        <img src='" . $_forecastDay['icon_url'] . "'/>
                        <p class='pb-weather-forecast-weekday'>" . $_forecastDay['date']['weekday_short'] . "</p>
                        <p class='pb-weather-max-temp'>" . strtoupper(JText::_('max')) . " " . $_forecastDay['temp_max'] . "</p>
                        <p class='pb-weather-min-temp'>" . strtoupper(JText::_('min')) . " " . $_forecastDay['temp_min'] . "</p>
                    </div>";
                }
            }
        } else {
            $html_element .= "
            <div class='pb-weather-current-day'>
                <div class='pb-weather-location-name'>" . $currentWeather['location_full_name'] . "</div>
                <div class='pb-weather-icon'><img src='" . $currentWeather['icon_url'] . "'/></div>
                <div class='pb-weather-info'>
                    <span class='pb-weather-current-temp'>" . $currentWeather['temp_current'] .  "</span>
                    <span class='pb-weather-max-temp'>(" . $currentWeather['temp_max'] .  "</span>
                    <span class='pb-weather-min-temp'>" . $currentWeather['temp_min'] .  ")</span>
                </div>
            </div>";
        }
        $html_element .= "</div></div>";

        return $this->element_wrapper($html_element, $arr_params);
    }


}
