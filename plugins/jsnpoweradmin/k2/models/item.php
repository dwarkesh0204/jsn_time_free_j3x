<?php
/**
 * @version    $Id$
 * @package    JSNPoweradmin
 * @subpackage Item
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
// No direct access to this file.
defined('_JEXEC') || die('Restricted access');

JSNFactory::import('components.com_k2.models.item', 'site');
JSNFactory::import('components.com_k2.helpers.utilities', 'site');
JTable::addIncludePath(JPATH_ROOT . '/administrator/components/com_k2/tables');
JSNFactory::import('components.com_k2.helpers.permissions', 'site');
JSNFactory::import('components.com_k2.helpers.route', 'site');

/**
 * @package		Joomla.Administrator
 * @subpackage	com_poweradmin extend com_content
 * @since		1.7
 */
class PoweradminK2ModelItem extends K2ModelItem
{
	/**
	 *
	 * Get params of current view
	 */
	protected function populateState()
	{
		// Load the parameters.
		$params = JComponentHelper::getParams('com_k2');
		$this->setState('params', $params);
	}
	/**

	/**
	 *
	 * Get data
	 * @param Array $pk
	 */
	public function &prepareDisplayedData( $pk )
	{
		$item = $this->getItem($pk['id']);
		$item = $this->prepareItem($item, $pk['view'], '');
		$item = $this->execPlugins($item, $pk['view'], $task);
		K2HelperUtilities::setDefaultImage($item, $pk['view']);

		$item->relatedItems = null;
		$model = JModelLegacy::getInstance('itemlist', 'K2Model');

		$relatedItems = $this->getRelatedItems($item->id, $item->tags, $item->params);
		if (count($relatedItems))
		{
			for ($i = 0; $i < sizeof($relatedItems); $i++)
			{
			$relatedItems[$i]->link = urldecode(JRoute::_(K2HelperRoute::getItemRoute($relatedItems[$i]->id.':'.urlencode($relatedItems[$i]->alias), $relatedItems[$i]->catid.':'.urlencode($relatedItems[$i]->categoryalias))));
			}
			$item->relatedItems = $relatedItems;
		}

		$authorLatestItems = $this->getAuthorLatest($item->id, $item->params->get('itemAuthorLatestLimit'), $item->created_by);
		if (count($authorLatestItems))
		{
			for ($i = 0; $i < sizeof($authorLatestItems); $i++)
			{
			$authorLatestItems[$i]->link = urldecode(JRoute::_(K2HelperRoute::getItemRoute($authorLatestItems[$i]->id.':'.urlencode($authorLatestItems[$i]->alias), $authorLatestItems[$i]->catid.':'.urlencode($authorLatestItems[$i]->categoryalias))));
			}
			$item->authorLatestItems = $authorLatestItems;
		}

		return $item;
	}

	public function getItem($cid)
	{
		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__k2_items WHERE id=" . (int)$cid . "";
		$db->setQuery($query, 0, 1);
		$row = $db->loadObject();
		return $row;
	}

	function getAuthorLatest($itemID, $limit, $userID)
	{
		$user = JFactory::getUser();
		$aid = (int)$user->get('aid');
		$itemID = (int)$itemID;
		$userID = (int)$userID;
		$limit = (int)$limit;
		$db = JFactory::getDBO();

		$jnow = JFactory::getDate();
		$now = K2_JVERSION == '15' ? $jnow->toMySQL() : $jnow->toSql();
		$nullDate = $db->getNullDate();

		$query = "SELECT i.*, c.alias as categoryalias FROM #__k2_items as i
		LEFT JOIN #__k2_categories c ON c.id = i.catid
		WHERE i.id != {$itemID}
		AND i.published = 1
		AND ( i.publish_up = ".$db->Quote($nullDate)." OR i.publish_up <= ".$db->Quote($now)." )
		AND ( i.publish_down = ".$db->Quote($nullDate)." OR i.publish_down >= ".$db->Quote($now)." ) ";

		if (K2_JVERSION != '15')
		{
			$query .= " AND i.access IN(".implode(',', $user->getAuthorisedViewLevels()).") ";

		}
		else
		{
			$query .= " AND i.access <= {$aid} ";
		}

		$query .= " AND i.trash = 0
		AND i.created_by = {$userID}
		AND i.created_by_alias=''
		AND c.published = 1 ";

		if (K2_JVERSION != '15')
		{
			$query .= " AND c.access IN(".implode(',', $user->getAuthorisedViewLevels()).") ";

		}
		else
		{
			$query .= " AND c.access <= {$aid} ";
		}

		$query .= " AND c.trash = 0
			ORDER BY i.created DESC";

			$db->setQuery($query, 0, $limit);
			$rows = $db->loadObjectList();

			foreach ($rows as $item)
		{
		//Image
		$item->imageXSmall = '';
			$item->imageSmall = '';
			$item->imageMedium = '';
			$item->imageLarge = '';
			$item->imageXLarge = '';

			if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_XS.jpg'))
			$item->imageXSmall = JURI::root(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_XS.jpg';

			if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_S.jpg'))
				$item->imageSmall = JURI::root(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_S.jpg';

						if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_M.jpg'))
						$item->imageMedium = JURI::root(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_M.jpg';

						if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_L.jpg'))
				$item->imageLarge = JURI::root(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_L.jpg';

				if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_XL.jpg'))
				$item->imageXLarge = JURI::root(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_XL.jpg';

			if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_Generic.jpg'))
			$item->imageGeneric = JURI::root(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_Generic.jpg';
		}

		return $rows;
	}


	function getRelatedItems($itemID, $tags, $params)
	{
		if (!count($tags)) return array();
		
		$limit = $params->get('itemRelatedLimit', 10);
		$itemID = (int)$itemID;
		foreach ($tags as $tag)
		{
			$tagIDs[] = $tag->id;
		}

		JArrayHelper::toInteger($tagIDs);
		$sql = implode(',', $tagIDs);

		$user = JFactory::getUser();
		$aid = (int)$user->get('aid');
		$db = JFactory::getDBO();

		$jnow = JFactory::getDate();
		$now = K2_JVERSION == '15' ? $jnow->toMySQL() : $jnow->toSql();
		$nullDate = $db->getNullDate();

		$query = "SELECT DISTINCT itemID FROM #__k2_tags_xref WHERE tagID IN ({$sql}) AND itemID!={$itemID}";
		$db->setQuery($query);
		$itemsIDs = K2_JVERSION == '30' ? $db->loadColumn() : $db->loadResultArray();

		if (!count($itemsIDs))
			return array();

		$sql = implode(',', $itemsIDs);

		$query = "SELECT i.*, c.alias as categoryalias FROM #__k2_items as i
		LEFT JOIN #__k2_categories c ON c.id = i.catid
		WHERE i.published = 1
		AND ( i.publish_up = ".$db->Quote($nullDate)." OR i.publish_up <= ".$db->Quote($now)." )
		AND ( i.publish_down = ".$db->Quote($nullDate)." OR i.publish_down >= ".$db->Quote($now)." ) ";

		if (K2_JVERSION != '15')
		{
			$query .= " AND i.access IN(".implode(',', $user->getAuthorisedViewLevels()).") ";

		}
		else
		{
			$query .= " AND i.access <= {$aid} ";
		}

		$query .= " AND i.trash = 0
		AND c.published = 1 ";

		if (K2_JVERSION != '15')
		{
			$query .= " AND c.access IN(".implode(',', $user->getAuthorisedViewLevels()).") ";

		}
		else
		{
			$query .= " AND c.access <= {$aid} ";
		}

		$query .= " AND c.trash = 0
		AND (i.id) IN ({$sql})
		ORDER BY i.created DESC";

		$db->setQuery($query, 0, $limit);
		$rows = $db->loadObjectList();
		K2Model::addIncludePath(JPATH_COMPONENT.DS.'models');
		$model = K2Model::getInstance('Item', 'K2Model');
		for ($key = 0; $key < sizeof($rows); $key++)
		{
			$rows[$key] = $model->prepareItem($rows[$key], 'relatedByTag', '');
			$rows[$key] = $model->execPlugins($rows[$key], 'relatedByTag', '');
			K2HelperUtilities::setDefaultImage($rows[$key], 'relatedByTag', $params);
		}
		return $rows;
	}
}