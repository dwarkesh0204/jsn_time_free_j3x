<?php
/**
 * @version    $Id$
 * @package    JSN_Poweradmin
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Subinstall script for finalizing JSN Framework installation.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class PlgJsnpoweradminK2InstallerScript
{
	

	/**
	 * Enable k2 support plugin.
	 *
	 * @param   string  $route  Route type: install, update or uninstall.
	 * @param   object  $_this  The installer object.
	 *
	 * @return  boolean
	 */
	public function postflight($route, $_this)
	{
		// Get a database connector object
		$db = JFactory::getDbo();

		try
		{
			// Enable plugin by default
			$q = $db->getQuery(true);

			$q->update('#__extensions');
			$q->set(array('enabled = 1', 'protected = 0'));
			$q->where("element = 'k2'");
			$q->where("type = 'plugin'", 'AND');
			$q->where("folder = 'jsnpoweradmin'", 'AND');

			$db->setQuery($q);
			$db->query();

		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
}
