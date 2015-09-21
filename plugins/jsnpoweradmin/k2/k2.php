<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow - Image Source Picasa
 * @version $Id: sourcepicasa.php 11402 2012-02-27 10:14:44Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.plugin.plugin' );
include_once JPATH_ROOT . '/administrator/components/com_poweradmin/extensions/extensions.php';

class plgJsnpoweradminK2 extends plgJsnpoweradminExtensions
{
	/**
	 * Method to check current extension version supported or not
	 * @return string message if current extensio
	 * 			version not supported
	 */
	public static function checkSupportedVersion()
	{
		$info = JSNUtilsXml::loadManifestCache('com_k2', 'component');
		if(isset($info) && isset($info->version)){
			$_supportedVersion = '2.6.2';
			if(version_compare($_supportedVersion, $info->version, '>')){
				return JText::sprintf('JSN_RAWMODE_COMPONENT_K2_VERSION_NOT_SUPPORTED', $_supportedVersion);
			}
		}
		return '';
	}

	/**
	 * This event fired right after this plugin loaded
	 */
	public static function loadJavascriptLang()
	{
		$jsnLang = JSNJavascriptLanguages::getInstance();
		self::addLang('JSN_RAWMODE_COMPONENT_K2_MOVE_ABOVE');
		self::addLang('JSN_RAWMODE_COMPONENT_K2_MOVE_BELOW');
	}

	public static function getSupportedLanguages()
	{
		$languages	= array();
		$languages['admin']['file'] = array('plg_jsnpoweradmin_k2.ini');
		$languages['admin']['path'] = array(dirname(__FILE__).DS.'language');
		return $languages;
	}

	public static function getSpotLightDescriptionMap()
	{
		return array(	'com_k2_items' 				=> "Item intro text: \n{desc}",
						'com_k2_categories'			=> "Category description: \n{desc}");
	}

	public static function addSearchRange()
	{
		$searchRanges = array();
		$searchRanges['com_k2_items'] =
			array(
				'name' => '#__k2_items',
				'lookup' => array('title'),
				'fields' => array('title' => '{title}', 'description' => "{introtext}"),
				//'icon'	=> 'cls:icon-16-article',
				'icon'	=> 'cls:null',
				'link'	=> 'index.php?option=com_k2&view=item&cid={id}',
			);

		$searchRanges['com_k2_categories'] =
			array(
				'name' => '#__k2_categories',
				'lookup' => array('name'),
				'fields' => array('title' => '{name}', 'description' => "{description}"),
				//'icon'	=> 'cls:icon-16-category',
				'icon'	=> 'cls:null',
				'link'	=> 'index.php?option=com_k2&view=category&cid={id}',
			);
		return $searchRanges;
	}

	public static function addConfiguration()
	{
		$config = array();
		$config =
			array('tabs' =>
				array(
					'com_k2_items' => array(
						'title'			=> 'K2 Items',
						'language' 		=> 'com_k2',
						'path'			=> JPATH_ROOT . '/plugins/jsnpoweradmin/k2',
						'modelfile'		=> 'models/pasearch/k2itemsearch.php',
						'viewfile'		=> 'views/pasearch/k2_items',
						'modelname'			=> 'PoweradminModelK2ItemSearch',
						'order'			=> 'i.id'
					),

					'com_k2_categories' => array(
						'title'			=> 'K2 Categories',
						'language' 		=> 'com_k2',
						'path'			=> JPATH_ROOT . '/plugins/jsnpoweradmin/k2',
						'modelfile'		=> 'models/pasearch/k2categorysearch.php',
						'viewfile'		=> 'views/pasearch/k2_categories',
						'modelname'			=> 'PoweradminModelK2CategorySearch',
						'order'			=> 'c.ordering'
					))
			);
		return $config;
	}

	public static function getTableMapping()
	{
		$params = JSNConfigHelper::get('com_poweradmin');
		$_tableMapping	= self::addSearchRange();
		if (intval($params->get('search_trashed', 0)) == 0)
		{
			$_tableMapping['com_k2_items']['conditions']		= 'trash=0';
			$_tableMapping['com_k2_categories']['conditions']	= 'trash=0';
		}
		return $_tableMapping;
	}

	public static function saveParams($data)
	{
		$input		= $data['data'];
		$jsnConfig	= $data['jsnConfig'];
		$params		= $data['params'];
		$_tableName = $input->get('view') == 'item'? '#__k2_items' : '#__k2_categories';
		$jsnConfig->saveExtParams($input->get('id'), $_tableName, $params);
		jexit('success');
	}

}