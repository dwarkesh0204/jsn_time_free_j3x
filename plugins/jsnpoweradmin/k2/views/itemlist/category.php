<?php
/**
 * @version		$Id: category.php 1618 2012-09-21 11:23:08Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;
$params = $data['params'];
$category = $data['category'];
$subcategories = $category->subCategories;
$pagination = $data['pagination'];

?>
<link rel="stylesheet" href="<?php echo JURI::root() . '/plugins/jsnpoweradmin/k2/assets/css/item_item.css?version=' . $version ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo JURI::root() . '/plugins/jsnpoweradmin/k2/assets/css/itemlist_category.css?version=' . $version ?>" type="text/css" />
<input id="category_id" type="hidden" value="<?php echo $category->id; ?>">
<input id="task" type="hidden" value="<?php echo $task; ?>">
<div class="jsn-article-layout">
	<?php $_showCatFeedIconClass = $params->get('catFeedIcon') ? 'display-default display-item' : 'hide-item'; ?>
	<div class="catFeedIcon element-switch contextmenu-approved <?php echo $_showCatFeedIconClass;?>" id="catFeedIcon">
		<a href="javascript:void(0)" title="<?php echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?>">
			<span><?php echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?></span>
		</a>
	</div>

	<?php if(isset($category) || (isset($subCategories) && count($subCategories) )){ ?>
		<?php if(isset($category) ){ ?>
			<div class="itemListCategory">
			<!-- category image -->
			<?php if($category->image){?>
				<?php $_showCatImageClass = $params->get('catImage') ? 'display-default display-item' : 'hide-item'; ?>
				<div class="catImage element-switch contextmenu-approved <?php echo $_showCatImageClass;?>" id="catImage">
					<img  src="<?php echo $category->image; ?>" style="width:<?php echo $params->get('catImageWidth'); ?>px; height:auto;" />
				</div>
			<?php }?>

			<!-- cat title -->
			<?php $_showCatTitleClass = $params->get('catTitle') ? 'display-default display-item' : 'hide-item'; ?>
			<?php $_showCatTitleItemCounterClass = $params->get('catTitleItemCounter') ? 'display-default display-item' : 'hide-item'; ?>
			<div class="catTitle element-switch contextmenu-approved <?php echo $_showCatTitleClass;?>" id="catTitle">
				<h2><?php echo $category->name; ?>
					<span class="catTitleItemCounter element-switch contextmenu-approved <?php echo $_showCatTitleItemCounterClass;?>" id="catTitleItemCounter"><?php echo ' ('.$pagination->total.')'; ?>
					</span>
				</h2>
			</div>
			<div class="clearbreak"></div>
			<!-- Cat description -->
			<?php if($category->description){?>
			<?php $_showCatDescriptionClass = $params->get('catDescription') ? 'display-default display-item' : 'hide-item'; ?>
			<div class="catDescription element-switch contextmenu-approved <?php echo $_showCatDescriptionClass;?>" id="catDescription">
				<p><?php echo $category->description; ?></p>
			</div>
			<?php }?>
			</div>
		<?php }?>

		<?php if(isset($subCategories) && count($subCategories)){ ?>
			<?php $_showCubCategoriesClass = $params->get('subCategories') ? 'display-default display-item' : 'hide-item'; ?>
				<div class="subCategories element-switch contextmenu-approved <?php echo $_showCubCategoriesClass;?>" id="subCategories">
					<h3><?php echo JText::_('K2_CHILDREN_CATEGORIES'); ?></h3>
					<?php foreach($subCategories as $key=>$subCategory){ ?>
						<?php
							// Define a CSS class for the last container on each row
							if( (($key+1)%($params->get('subCatColumns'))==0))
								$lastContainer= ' subCategoryContainerLast';
							else
								$lastContainer='';
						?>
						<div class="subCategoryContainer<?php echo $lastContainer?>" <?php echo  'style="width:'.number_format(100/$params->get('subCatColumns'), 1).'%;"'; ?>>
							<?php if($subCategory->image){?>
								<?php $_showSubCatImageClass = $params->get('subCatImage') ? 'display-default display-item' : 'hide-item'; ?>
								<div class="subCatImage element-switch contextmenu-approved <?php echo $_showCatDescriptionClass;?>" id="subCatImage">
									<img  src="<?php echo $subCategory->image; ?>" />
								</div>
							<?php }?>

							<?php $_showSubCatTitleClass = $params->get('subCatTitle') ? 'display-default display-item' : 'hide-item'; ?>
							<?php $_showSubCatTitleItemCounterClass = $params->get('subCatTitleItemCounter') ? 'display-default display-item' : 'hide-item'; ?>
							<div class="subCatTitle element-switch contextmenu-approved <?php echo $_showSubCatTitleClass;?>" id="subCatTitle">
								<h2>
									<a href="javascript:void(0)">
										<?php echo $subCategory->name; ?>
										<span  class="subCatTitleItemCounter element-switch contextmenu-approved <?php echo $_showSubCatTitleItemCounterClass;?>" id="subCatTitleItemCounter"><?php echo ' ('.$subCategory->numOfItems.')'; ?>
										</span>
									</a>
								</h2>
							</div>
							<?php if($subCategory->description){?>
								<?php $_showSubCatDescriptionClass = $params->get('subCatDescription') ? 'display-default display-item' : 'hide-item'; ?>
								<div class="subCatDescription element-switch contextmenu-approved <?php echo $_showSubCatDescriptionClass;?>" id="subCatDescription">
									<p><?php echo $subCategory->description; ?></p>
								</div>
							<?php }?>

							<a class="subCategoryMore" href="<?php echo $subCategory->link; ?>">
								<?php echo JText::_('K2_VIEW_ITEMS'); ?>
							</a>
							<div class="clearbreak"></div>
						</div>

						<?php if(($key+1)%($params->get('subCatColumns'))==0){ ?>
						<div class="clearbreak"></div>
						<?php } ?>
					<?php }?>
				</div>
		<?php }?>
	<?php }?>


		<?php if((isset($data['leading']) || isset($data['primary']) || isset($data['secondary']) || isset($data['links'])) && (count($data['leading']) || count($data['primary']) || count($data['secondary']) || count($data['links']))){ ?>
			<?php if(isset($data['leading']) && count($data['leading'])){ ?>
				<div id="itemListLeading">
				<?php foreach($data['leading'] as $key=>$item){ ?>
					<?php
					// Define a CSS class for the last container on each row
					if( (($key+1)%($params->get('num_leading_columns'))==0) || count($data['leading']) < $params->get('num_leading_columns') )
						$lastContainer= ' itemContainerLast';
					else
						$lastContainer='';
					?>

					<div class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($data['leading'])==1) ? '' : ' style="width:'.number_format(100/$params->get('num_leading_columns'), 1).'%;"'; ?>>
							<?php
								// Load category_item.php by default
								//$this->item=$item;
								include JPATH_ROOT . '/plugins/jsnpoweradmin/k2/views/itemlist/category_item.php';
							?>
						</div>
						<?php if(($key+1)%($params->get('num_leading_columns'))==0){ ?>
						<div class="clearbreak"></div>
						<?php } ?>
						<?php } ?>
						<div class="clearbreak"></div>
				</div>

			<?php }?>

			<?php if(isset($data['primary']) && count($data['primary'])){ ?>
				<div id="itemListPrimary">
				<?php foreach($data['primary'] as $key=>$item){ ?>
					<?php
					// Define a CSS class for the last container on each row
					if( (($key+1)%($params->get('num_primary_columns'))==0) || count($data['primary']) < $params->get('num_primary_columns') )
						$lastContainer= ' itemContainerLast';
					else
						$lastContainer='';
					?>

					<div class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($data['primary'])==1) ? '' : ' style="width:'.number_format(100/$params->get('num_primary_columns'), 1).'%;"'; ?>>
							<?php
								// Load category_item.php by default
								//$this->item=$item;
								include JPATH_ROOT . '/plugins/jsnpoweradmin/k2/views/itemlist/category_item.php';
							?>
						</div>
						<?php if(($key+1)%($params->get('num_primary_columns'))==0){ ?>
						<div class="clearbreak"></div>
						<?php } ?>
						<?php } ?>
						<div class="clearbreak"></div>
				</div>

			<?php }?>

			<?php if(isset($data['secondary']) && count($data['secondary'])){ ?>
				<div id="itemListSecondary">
				<?php foreach($data['secondary'] as $key=>$item){ ?>
					<?php
					// Define a CSS class for the last container on each row
					if( (($key+1)%($params->get('num_secondary_columns'))==0) || count($data['secondary']) < $params->get('num_secondary_columns') )
						$lastContainer= ' itemContainerLast';
					else
						$lastContainer='';
					?>

					<div class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($data['secondary'])==1) ? '' : ' style="width:'.number_format(100/$params->get('num_secondary_columns'), 1).'%;"'; ?>>
							<?php
								// Load category_item.php by default
								//$this->item=$item;
								include JPATH_ROOT . '/plugins/jsnpoweradmin/k2/views/itemlist/category_item.php';
							?>
						</div>
						<?php if(($key+1)%($params->get('num_secondary_columns'))==0){ ?>
						<div class="clearbreak"></div>
						<?php } ?>
						<?php } ?>
						<div class="clearbreak"></div>
				</div>

			<?php }?>

			<?php if(isset($data['links']) && count($data['links'])){ ?>
				<div id="itemListLinks">
				<?php foreach($data['links'] as $key=>$item){ ?>
					<?php
					// Define a CSS class for the last container on each row
					if( (($key+1)%($params->get('num_links_columns'))==0) || count($data['links']) < $params->get('num_links_columns') )
						$lastContainer= ' itemContainerLast';
					else
						$lastContainer='';
					?>

					<div class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($data['secondary'])==1) ? '' : ' style="width:'.number_format(100/$params->get('num_links_columns'), 1).'%;"'; ?>>
							<?php
								// Load category_item.php by default
								include JPATH_ROOT . '/plugins/jsnpoweradmin/k2/views/itemlist/category_item.php';
							?>
						</div>
						<?php if(($key+1)%($params->get('num_links_columns'))==0){ ?>
						<div class="clearbreak"></div>
						<?php } ?>
						<?php } ?>
						<div class="clearbreak"></div>
				</div>

			<?php }?>

		<?php }?>

		<?php if(count($pagination->getPagesLinks())){ ?>
			<?php $_showCatPaginationClass = $params->get('catPagination') ? 'display-default display-item' : 'hide-item'; ?>
			<div class="catPagination element-switch contextmenu-approved <?php echo $_showCatPaginationClass;?>" id="catPagination">
				<?php  echo $pagination->getPagesLinks(); ?>
			</div>
			<div class="clearbreak"></div>
			<?php $_showCatPaginationResultsClass = $params->get('catPaginationResults') ? 'display-default display-item' : 'hide-item'; ?>
			<div class="catPaginationResults element-switch contextmenu-approved <?php echo $_showCatPaginationResultsClass;?>" id="catPaginationResults">
				<?php  echo $pagination->getPagesCounter(); ?>
			</div>
		<?php }?>
</div>