<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Import dependencies.
Foundry::import( 'admin:/includes/apps/apps' );

/**
 * Dashboard view for Feeds app.
 *
 * @since	1.0
 * @access	public
 */
class FeedsViewFeeds extends SocialAppsView
{
	/**
	 * Displays the application output in the canvas.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user id that is currently being viewed.
	 */
	public function form()
	{
		$ajax 	= Foundry::ajax();

		$contents 	= parent::display( 'dashboard/form' );

		return $ajax->resolve( $contents );
	}

	/**
	 * Displays the application output in the canvas.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user id that is currently being viewed.
	 */
	public function confirmDelete()
	{
		$ajax 		= Foundry::ajax();

		$contents 	= parent::display( 'dashboard/dialog.delete' );

		return $ajax->resolve( $contents );
	}
}