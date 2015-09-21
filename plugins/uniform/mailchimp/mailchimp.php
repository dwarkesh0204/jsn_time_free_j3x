<?php

/**
 * @version     $Id: mailchimp.php 19014 2012-11-28 04:48:56Z anhnt $
 * @package     JSNUniform
 * @subpackage  Plugin
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.plugin.plugin');
require_once 'class/Mailchimp.php';

//require_once 'director.php';
class plgUniformMailchimp extends JPlugin {

	public $mailchimp;
	public $listID = array();

	/**
	 * Constructor
	 *
	 * @param   object  &$subject  The object to observe
	 *
	 * @param   array   $config    An array that holds the plugin configuration
	 */
	public function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
		JPlugin::loadLanguage('plg_uniform_mailchimp', JPATH_PLUGINS);
	}

	// load plugin type layout
	public function mailchimp() {
		$model = new JSNUniformModelForm();
		$data = $model->getItem();
		$data = json_decode($data->form_settings);
		$mailchimp = $data->form_mailchimp;
		$path = str_replace('administrator', '', JPATH_BASE) . '/plugins/uniform/mailchimp/layout/mailchimp.php';
		if (file_exists($path)) {
			ob_start();
			include $path;
			$return = ob_get_contents();
			ob_end_clean();
			return $return;
		}
	}

	public function saveBackEnd($data) {
		$this->saveFieldToList($data);
	}

	public function saveFrontEnd($data) {

		$this->saveInfoToMailchimp($data);
	}

	/**
	 * Check API key Mailchimp
	 *
	 * @param   string  &$str  API Key string
	 *
	 * @return msg
	 */
	// grab an API Key from http://admin.mailchimp.com/account/api/
	public function checkApiKey($str) {
		$key = (isset($str['key']) && !empty($str['key']) ? $str['key'] : '');
		$mailchimp = new Mailchimp($key, array('ssl_verifypeer' => false));
		$mergeVars = array('EMAIL' => 'support@joomlashine.com');
		$list_id = '';
		$result = $mailchimp->lists->subscribe($list_id, array('email' => 'support@joomlashine.com'), $mergeVars, false, true, false, false);
		return $result;
	}

	/**
	 * Show All List Mailchimp
	 *
	 * @param   string  &$str  API Key string
	 * mergeVars($id)
	 * @return msg
	 */
	public function showListMailchimp($str) {
		$key = (isset($str['key']) && !empty($str['key']) ? $str['key'] : '');
		$mailchimp = new Mailchimp($key, array('ssl_verifypeer' => false));
		$result = $mailchimp->lists->getList(array(), 0, 100, 'created', 'DESC');
		return $result;
	}

	/**
	 * Save form field to List Mailchimp
	 *
	 * @param   array  &$arr field array to save
	 *
	 * @return void
	 */
	public function saveFieldToList($str) {
		if (!empty($str)) {
			$arr = json_decode($str);
		}
		if (is_object($arr)) {
			$arr = (array) $arr;
			$arrfield = (array) $arr['arrfield'];
			$lisId = (array) $arr['arrfield'];
			$mailchimp = new Mailchimp($arr['keyApi'], array('ssl_verifypeer' => false));
			foreach ($arrfield as $k => $arrVl) {
				$arrVl = (array) json_decode($arrVl);
				$arrField = (array) $arrVl['Field'];
				$newField = (array) $arrField['new'];
				$tagField = (array) $arrField['tag'];
				if (is_array($newField) && count($newField) > 0) {
					foreach ($newField as $key => $field) {
						if (array_search($field, $tagField)) {
							$tag = array_search($field, $tagField);
							$option = array('name' => $tagField[$tag], 'req' => false, 'public' => true);
							$mailchimp->lists->mergeVarUpdate($k, $tag, $option);
						} else {
							$fields = str_replace(" ", '', $field);
							$fields = preg_replace('/[^A-Za-z0-9\-]/', '', $fields);
							$fields = 'J' . substr($fields, 0, 9);
							$option = array('field_type' => 'text', 'req' => false, 'public' => true);
							$mailchimp->lists->mergeVarAdd($k, strtoupper($fields), $field, $option);
						}
					}
				}
			}
		}
	}

	/*
	 * Show list field in form and Mailchimp
	 * @param array @response
	 * @param string @id
	 * Return html
	 */

	public function listAllFieldInListOnMailchimp($arr) {
		$key = (isset($arr['key']) && !empty($arr['key']) ? $arr['key'] : '');
		$listId = (isset($arr['listId']) && !empty($arr['listId']) ? $arr['listId'] : '');
		$mailchimp = new Mailchimp($key, array('ssl_verifypeer' => false));
		$Arrmerge = $mailchimp->lists->mergeVars(array($listId));
		$arr = array();
		foreach ($Arrmerge['data'] as $arrVar) {
			$arr = $arrVar['merge_vars'];
		}
		return $arr;
	}

	/**
	 * Save info form to Mailchimp server
	 *
	 * @param   array  &$arrField field array to save
	 * @param   array  &$post info array from submit form
	 * @return void
	 */
	public function saveInfoToMailchimp($data) {
		$arrField = $data['mailchimp'];
		$post = $data['post'];
		$submissionsData = $data['sub'];
		if (isset($submissionsData) && !empty($submissionsData)) {
			foreach ($submissionsData as $ky => $data) {
				$arrData[$data['field_id']] = $data["field_type"];
			}
		}
		if (isset($arrField) && !empty($arrField)) {
			$arrField = (array) json_decode($arrField);
			$useMailchimp = ($arrField['useMailchimp'] == 1)? 1 :'';
			$apiKey = $arrField['keyApi'];
			$arrfield = (array) $arrField['arrfield'];
			$mailchimp = new Mailchimp($apiKey, array('ssl_verifypeer' => false));
			if (isset($arrData) && !empty($arrData)) {
				foreach ($arrData as $k => $v) {
					if (array_key_exists($k, $post)) {
						$arr[$v] = $post[$k];
					} else {
						$arr[$v] = $post[$v];
					}
				}
			}
		}
		$mergeVars = '';
		if ( isset( $useMailchimp ) && !empty( $useMailchimp ) )
		{
			foreach ($arrfield as $k => $arrVl) {
				if ($arrVl != '') {
					$arrVl = (array) json_decode($arrVl);
					$allow = $arrVl['allow'];
					if($allow == 1) {
						$aField = (array) $arrVl['Field'];
						$oldField = (array) $aField['old'];
						$tagField = (array) $aField['tag'];
						if (isset($arr) && !empty($arr)) {
							foreach ($arr as $key => $vl) {
								if ($key == 'email') {
									$mergeVars['EMAIL'] = $vl;
								} elseif ($key == 'name') {
									foreach ($vl as $name) {
										if ($name['first'] != '' && array_key_exists('first', $oldField)) {
											$tag = $this->func($oldField['first'], $tagField);
											$mergeVars[$tag] = $name['first'];
										}
										if ($name['suffix'] != '' && array_key_exists('middle', $oldField)) {
											$tag = $this->func($oldField['middle'], $tagField);
											$mergeVars[$tag] = $name['suffix'];
										}
										if ($name['last'] != '' && array_key_exists('last', $oldField)) {
											$tag = $this->func($oldField['last'], $tagField);
											$mergeVars[$tag] = $name['last'];
										}
									}
								} elseif ($key == 'address' && array_key_exists('address', $oldField)) {
									$tag = $this->func($oldField['address'], $tagField);
									foreach ($vl as $adress) {
										if ($adress['street'] != '') {
											$mergeVars[$tag] = $adress['street'];
										}
										if ($adress['city'] != '') {
											$mergeVars[$tag] .= $adress['city'] . ',';
										}
										if ($adress['country'] != '') {
											$mergeVars[$tag] .= $adress['country'] . ',';
										}
									}
								} elseif ($key == 'phone' && array_key_exists('phone', $oldField)) {
									$tag = $this->func($oldField['phone'], $tagField);
									foreach ($vl as $phone) {
										if (isset($phone['default'])) {
											$mergeVars[$tag] = $phone['default'];
										}
										if (isset($phone['one'])) {
											$mergeVars[$tag] = $phone['one'];
										}
										if (isset($phone['one'])) {
											$mergeVars[$tag].= $phone['two'];
										}
										if (isset($phone['one'])) {
											$mergeVars[$tag].= $phone['three'];
										}
									}
								} elseif ($key == 'likert' && array_key_exists('likert', $oldField)) {
									$tag = $this->func($oldField['likert'], $tagField);
									foreach ($vl as $linker) {
										if (!empty($linker['settings'])) {
											$settings = json_decode($linker['settings']);
											if (!empty($settings)) {
												foreach ($settings->rows as $set) {
													$likertHtml = '';
													$likertHtml .= $set->text . ":";
													$value = 'N/A';
													foreach ($linker['values'] as $key => $val) {
														if ($key == md5($set->text) || $key == $set->text) {
															$value = $val;
														}
													}
													$likertHtml .= $value;
													$contentField[] = $likertHtml;
												}
												$contentField = implode(";", $contentField);
											}
										} else {
											$contentField = '';
										}
										$mergeVars[$tag] = $contentField;
									}
								} elseif ($key == 'date' && array_key_exists('date', $oldField)) {
									$tag = $this->func($oldField['date'], $tagField);
									foreach ($vl as $date) {
										if ($date['date'] !== '') {
											$mergeVars[$tag] = $date['date'];
										}
										if ($date['daterange'] !== '') {
											$mergeVars[$tag] .= ' - ' . $date['daterange'];
										}
									}
								} elseif ($key == 'list' && array_key_exists('list', $oldField)) {
									$tag = $this->func($oldField['list'], $tagField);
									foreach ($vl as $ky => $list) {
										$mergeVars[$tag] .= $list . "; ";
									}
								} elseif ($key == 'currency' && array_key_exists('currency', $oldField)) {
									$tag = $this->func($oldField['currency'], $tagField);
									foreach ($vl as $ky => $v) {
										if ($v['value'] != '') {
											$mergeVars[$tag] = $v['value'];
										} else {
											$mergeVars[$tag] = 0;
										}
										if ($v['cents'] != '') {
											$mergeVars[$tag] .= "," . $v['cents'];
										}
									}
								} elseif ($key == 'number' && array_key_exists('number', $oldField)) {
									$tag = $this->func($oldField['number'], $tagField);
									foreach ($vl as $ky => $v) {
										if ($v['value'] != '') {
											$mergeVars[$tag] = $v['value'];
										}
									}
								} elseif ($key == 'password' && array_key_exists('password', $oldField)) {
									$tag = $this->func($oldField['password'], $tagField);
									foreach ($vl as $ky => $v) {
										if ($v[0] != '') {
											$mergeVars[$tag] = $v[0];
										}
									}
								} elseif ($key == 'checkboxes' && array_key_exists('checkboxes', $oldField)) {
									$tag = $this->func($oldField['checkboxes'], $tagField);
									foreach ($vl as $v) {
										if ($v != '') {
											$mergeVars[$tag] .= $v . ";";
										}
									}
								} elseif (array_key_exists($key, $oldField)) {
									$check = $this->checkTagMergeVar($apiKey, $k, $oldField[$key]);
									if( $check == 1 )
									{
										$tag = $oldField[$key];
									}
									else
									{
										$tag = $this->func($oldField[$key], $tagField);
									}
									$mergeVars[$tag] = $vl;
								}
							}
						}
						if (isset($mergeVars) && !empty($mergeVars)) {
							$mailchimp->lists->subscribe($k, array('email' => $mergeVars['EMAIL']), $mergeVars, false, false, false, false);
						}
					}
				}
			}
		}
	}
	
	/**
	 * Validate tag to save on Mailchimp server
	 *
	 * @param   string  &$oldField value of field to save
	 * @param   array  &$tagField 
	 * @return string
	 */
	public function checkTagMergeVar($apiKey,$listId,$tag){
		$mailchimp = new Mailchimp($apiKey, array('ssl_verifypeer' => false));
		$Arrmerge = $mailchimp->lists->mergeVars(array($listId));
		$arr = array();
		foreach ($Arrmerge['data'] as $arrVar) {
			$arr = $arrVar['merge_vars'];
		}
		$check =0;
		if(isset($arr) && !empty($arr)){
			foreach ($arr as $ar){
				if($ar['tag'] == $tag){
					$check = 1;
				}
			}
		}
		return $check;
	}
	/**
	 * Validate tag to save on Mailchimp server
	 *
	 * @param   string  &$oldField value of field to save
	 * @param   array  &$tagField 
	 * @return string
	 */
	public function func($oldField, $tagField) {
		if (array_search($oldField, $tagField)) {
			$tag = array_search($oldField, $tagField);
		} else {
			if($oldField != 'FNAME' && $oldField != 'LNAME' ){
				$tag = 'J' . substr(str_replace(" ", '', $oldField), 0, 9);
			}else{$tag = substr(str_replace(" ", '', $oldField), 0, 9);}
			$tag = strtoupper($tag);
		}
		return $tag;
	}

}
