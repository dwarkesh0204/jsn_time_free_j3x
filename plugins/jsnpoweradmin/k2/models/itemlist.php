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

JSNFactory::import('components.com_k2.models.itemlist', 'site');
JSNFactory::import('plugins.jsnpoweradmin.k2.models.item', 'site');
JSNFactory::import('components.com_k2.helpers.utilities', 'site');
JTable::addIncludePath(JPATH_ROOT . '/administrator/components/com_k2/tables');
JSNFactory::import('components.com_k2.helpers.permissions', 'site');
JSNFactory::import('components.com_k2.helpers.route', 'site');

/**
 * @package		Joomla.Administrator
 * @subpackage	com_poweradmin extend com_content
 * @since		1.7
 */
class PoweradminK2ModelItemlist extends K2ModelItemlist
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
	 *
	 * Get data
	 * @param Array $pk
	 */
	public function &prepareDisplayedData( $pk )
	{
		$params = K2HelperUtilities::getParams('com_k2');
		$task = $pk['task'];
		$data = array();
		// Get data depending on task
		switch ($task)
		{

			case 'category' :
				// Get category
				$id = (int)$pk['id'];
				JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
				$category = JTable::getInstance('K2Category', 'Table');
				$category->load($id);


				// State check
				if (!$category->published || $category->trash)
				{
					JError::raiseError(404, JText::_('K2_CATEGORY_NOT_FOUND'));
				}


				// Merge params
				$cparams = class_exists('JParameter') ? new JParameter($category->params) : new JRegistry($category->params);

				// Get the meta information before merging params since we do not want them to be inherited
				$category->metaDescription = $cparams->get('catMetaDesc');
				$category->metaKeywords = $cparams->get('catMetaKey');
				$category->metaRobots = $cparams->get('catMetaRobots');
				$category->metaAuthor = $cparams->get('catMetaAuthor');

				if ($cparams->get('inheritFrom'))
				{
					$masterCategory = JTable::getInstance('K2Category', 'Table');
					$masterCategory->load($cparams->get('inheritFrom'));
					$cparams = class_exists('JParameter') ? new JParameter($masterCategory->params) : new JRegistry($masterCategory->params);
				}
				$params->merge($cparams);

				// Category link
				$category->link = urldecode(JRoute::_(K2HelperRoute::getCategoryRoute($category->id.':'.urlencode($category->alias))));

				// Category image
				$category->image = K2HelperUtilities::getCategoryImage($category->image, $params);

				// Category children
				$ordering = $params->get('subCatOrdering');
				$children = $this->getCategoryFirstChildren($id, $ordering);
				if (count($children))
				{
					foreach ($children as $child)
					{
						if ($params->get('subCatTitleItemCounter'))
						{
							$child->numOfItems = $this->countCategoryItems($child->id);
						}
						$child->image = K2HelperUtilities::getCategoryImage($child->image, $params);
						$child->name = htmlspecialchars($child->name, ENT_QUOTES);
						$child->link = urldecode(JRoute::_(K2HelperRoute::getCategoryRoute($child->id.':'.urlencode($child->alias))));
						$subCategories[] = $child;
					}
					$category->subCategories = $subCategories;
				}

				$data['category'] = $category;
				break;
		}

		$items = $this->getItems($pk);
		// Pagination
		jimport('joomla.html.pagination');
		$total = $this->getTotal();
		$pagination = new JPagination($total, $limitstart, $limit);

		$model = new PoweradminK2ModelItem();
		
		for ($i = 0; $i < sizeof($items); $i++)
		{

		//Item group

		if ($i < ($params->get('num_links') + $params->get('num_leading_items') + $params->get('num_primary_items') + $params->get('num_secondary_items')))
			$items[$i]->itemGroup = 'links';
		if ($i < ($params->get('num_secondary_items') + $params->get('num_leading_items') + $params->get('num_primary_items')))
				$items[$i]->itemGroup = 'secondary';
		if ($i < ($params->get('num_primary_items') + $params->get('num_leading_items')))
				$items[$i]->itemGroup = 'primary';
		if ($i < $params->get('num_leading_items'))
					$items[$i]->itemGroup = 'leading';

		// Prepare item
		///$items[$i] = $model->prepareItem($items[$i], $pk['view'], '');
		$items[$i] = $model->prepareItem($items[$i], 'item', '');
		
		}
		
		$data['item'] = $items;
		$data['params'] = $params;
		$data['pagination'] = $pagination;

		if ($task == "category" || $task == "")
		{
			$leading = @array_slice($items, 0, $params->get('num_leading_items'));
			$primary = @array_slice($items, $params->get('num_leading_items'), $params->get('num_primary_items'));
			$secondary = @array_slice($items, $params->get('num_leading_items') + $params->get('num_primary_items'), $params->get('num_secondary_items'));
			$links = @array_slice($items, $params->get('num_leading_items') + $params->get('num_primary_items') + $params->get('num_secondary_items'), $params->get('num_links'));
			$data['leading'] 	= $leading;
			$data['primary'] 	= $primary;
			$data['secondary'] 	= $secondary;
			$data['links'] 		= $links;
		}
// 		echo "<pre>";
// 		print_r($pagination);
// 		echo "</pre>";
// 		exit();
 		return $data;
	}

	function getCategoryFirstChildren($catid, $ordering = NULL)
	{

		$mainframe = JFactory::getApplication();
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__k2_categories WHERE parent={$catid} AND published=1 AND trash=0";

		$order = " id ASC";

		$query .= " ORDER BY {$order}";

		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if ($db->getErrorNum())
		{
			echo $db->stderr();
			return false;
		}

		return $rows;
	}

	function countCategoryItems($id)
	{

		$mainframe = JFactory::getApplication();
		$user = JFactory::getUser();
		$aid = (int)$user->get('aid');
		$id = (int)$id;
		$db = JFactory::getDBO();

		$jnow = JFactory::getDate();
		$now = K2_JVERSION == '15' ? $jnow->toMySQL() : $jnow->toSql();
		$nullDate = $db->getNullDate();

		$categories = $this->getCategoryTree($id);
		$query = "SELECT COUNT(*) FROM #__k2_items WHERE catid IN (".implode(',', $categories).") AND published=1 AND trash=0";

		if (K2_JVERSION != '15')
		{
			$query .= " AND access IN(".implode(',', $user->getAuthorisedViewLevels()).") ";

		}
		else
		{
			$query .= " AND access<=".$aid;
		}

		$query .= " AND ( publish_up = ".$db->Quote($nullDate)." OR publish_up <= ".$db->Quote($now)." )";
		$query .= " AND ( publish_down = ".$db->Quote($nullDate)." OR publish_down >= ".$db->Quote($now)." )";
		$db->setQuery($query);
		$total = $db->loadResult();
		return $total;
	}

	function getItems($pk)
	{
		$db = JFactory::getDBO();
		$params = K2HelperUtilities::getParams('com_k2');
		$limitstart = JRequest::getInt('limitstart');
		$limit = JRequest::getInt('limit');
		$task = $pk['task'];
		if ($task == 'search' && $params->get('googleSearch'))
			return array();

		$jnow = JFactory::getDate();
		$now = K2_JVERSION == '15' ? $jnow->toMySQL() : $jnow->toSql();
		$nullDate = $db->getNullDate();

		if (JRequest::getWord('format') == 'feed')
			$limit = $params->get('feedLimit');

		$query = "SELECT i.*, CASE WHEN i.modified = 0 THEN i.created ELSE i.modified END as lastChanged, c.name as categoryname,c.id as categoryid, c.alias as categoryalias, c.params as categoryparams";
		if ($ordering == 'best')
			$query .= ", (r.rating_sum/r.rating_count) AS rating";

		$query .= " FROM #__k2_items as i LEFT JOIN #__k2_categories AS c ON c.id = i.catid";

		if ($ordering == 'best')
			$query .= " LEFT JOIN #__k2_rating r ON r.itemID = i.id";


		//Build query depending on task
        switch ($task)
        {

            case 'category' :
                $id = (int)$pk['id'];

                $category = JTable::getInstance('K2Category', 'Table');
                $category->load($id);
                $cparams = class_exists('JParameter') ? new JParameter($category->params) : new JRegistry($category->params);

                if ($cparams->get('inheritFrom'))
                {

                    $parent = JTable::getInstance('K2Category', 'Table');
                    $parent->load($cparams->get('inheritFrom'));
                    $cparams = class_exists('JParameter') ? new JParameter($parent->params) : new JRegistry($parent->params);
                }

                if ($cparams->get('catCatalogMode'))
                {
                    $query .= " AND c.id={$id} ";
                }
                else
                {
                    $categories = $this->getCategoryTree($id);
                    $sql = @implode(',', $categories);
                    $query .= " AND c.id IN ({$sql})";
                }

                break;
			default:
				$searchIDs = $params->get('categories');

				if (is_array($searchIDs) && count($searchIDs))
				{

					if ($params->get('catCatalogMode'))
					{
						$sql = @implode(',', $searchIDs);
						$query .= " AND i.catid IN ({$sql})";
					}
					else
					{

						$result = $this->getCategoryTree($searchIDs);
						if (count($result))
						{
							$sql = @implode(',', $result);
							$query .= " AND i.catid IN ({$sql})";
						}
					}
				}

				break;
        }

		$orderby = 'i.id DESC';


		$query .= " ORDER BY ".$orderby;

		$db->setQuery($query, $limitstart, $limit);
		$rows = $db->loadObjectList();
		return $rows;
	}

	function getTotal()
	{
		$db = JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__k2_items as i LEFT JOIN #__k2_categories c ON c.id = i.catid";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
}