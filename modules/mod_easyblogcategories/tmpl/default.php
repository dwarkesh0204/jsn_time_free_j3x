<?php
/**
 * @package		EasyBlog
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *
 * EasyBlog is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Restricted access');
?>

<div id="ezblog-categories" class="ezb-mod mod_easyblogcategories<?php echo $params->get( 'moduleclass_sfx' ) ?>">
	<?php if(!empty($categories)){ ?>
		<?php echo accessNestedCategories( $categories , $selected , $params ); ?>
	<?php } else { ?>
			<?php echo JText::_('MOD_EASYBLOGCATEGORIES_NO_CATEGORY'); ?>
	<?php } ?>
</div>

<?php
function accessNestedCategories( &$categories , $selected , $params , $level = null )
{
	$menuItemId = modEasyBlogCategoriesHelper::_getMenuItemId($params);

	foreach($categories as $category)
	{
		if( is_null( $level ) )
		{
			$level 	= 0;
		}
		
		$css = '';

		if($category->id == $selected)
		{
			$css = 'font-weight: bold;';
		}
		
		if( $params->get( 'layouttype' ) == 'tree' )
		{
			// $category->level	-= 1;
			$padding	= $level * 30;
		}
		
?>
	<div class="mod-item">
		<div<?php echo ( $params->get( 'layouttype' ) == 'tree' ) ? ' style="padding-left: ' . $padding . 'px;"' : '';?>>
	 	<?php if ($params->get('showcavatar', true)) : ?>
			<a href="<?php echo EasyBlogRouter::_('index.php?option=com_easyblog&view=categories&layout=listings&id='.$category->id . $menuItemId );?>" class="mod-avatar">
				<img class="avatar" src="<?php echo modEasyBlogCategoriesHelper::getAvatar($category); ?>" width="40" alt="<?php echo $category->title; ?>" />
			</a>
		<?php endif; ?>
	 		<div class="mod-category-detail">
				<div class="mod-category-name">
					<a href="<?php echo EasyBlogRouter::_('index.php?option=com_easyblog&view=categories&layout=listings&id='.$category->id . $menuItemId );?>"><?php echo $category->title; ?></a>
					<?php echo JText::sprintf('(' . $category->cnt) . ')';?>
				</div>
			 </div>
		</div>
	</div>
<?php
		if( $params->get( 'layouttype' ) == 'tree' || $params->get( 'layouttype' ) == 'flat' )
		{
			if( isset( $category->childs ) && is_array( $category->childs ) )
			{
			    accessNestedCategories( $category->childs , $selected, $params ,  $level + 1 );
			}
		}
	}
}
