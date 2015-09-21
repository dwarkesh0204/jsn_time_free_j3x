<?php
/**
 * @version		$Id: item.php 1766 2012-11-22 14:10:24Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */
// no direct access
defined('_JEXEC') or die;
$manifest = JSNUtilsXml::loadManifestCache('k2', 'plugin', 'jsnpoweradmin');
$version = $manifest->version;
K2HelperUtilities::setDefaultImage($item, 'itemlist', $params);
?>

<input id="article_id" type="hidden" value="<?php echo $item->id; ?>">
<div class="jsn-article-layout">
	<?php $_showCreatedDateClass = $item->params->get('catItemDateCreated') ? 'display-default display-item' : 'hide-item'; ?>
	<div class="item-date-created element-switch contextmenu-approved <?php echo $_showCreatedDateClass;?>" id="catItemDateCreated">
		<?php echo JHTML::_('date', $item->created , 'D F n, Y g:i a'); ?>
	</div>

	<div>
		<?php $_showItemTitleClass = $item->params->get('catItemTitle') ? 'display-default display-item' : 'hide-item'; ?>
		<div class="item-title element-switch contextmenu-approved <?php echo $_showItemTitleClass;?>" id="catItemTitle">
			<h1><?php echo $item->title; ?></h1>
		</div>

		<?php if ($item->featured): ?>
		<?php $_showFeaturedClass = $item->params->get('catItemFeaturedNotice') ? 'display-default display-item' : 'hide-item'; ?>
		<div class="item-featured-notice element-switch  contextmenu-approved <?php echo $_showFeaturedClass?>" id="catItemFeaturedNotice">
		  	<sup>
		  		<?php echo JText::_('K2_FEATURED'); ?>
		  	</sup>
	  	</div>
	  	<?php endif?>
	  	<div class="clearbreak"></div>
	</div>

	<?php $_showAuthorClass = $item->params->get('catItemAuthor') ? 'display-default display-item' : 'hide-item'; ?>
	<div class="item-featured-notice element-switch  contextmenu-approved <?php echo $_showAuthorClass?>" id="catItemAuthor">
		<span class="item-author">
			<?php echo K2HelperUtilities::writtenBy($item->author->profile->gender); ?>&nbsp;
			<?php if(empty($item->created_by_alias)): ?>
			<a rel="author" href="javascript:void(0)"><?php echo $item->author->name; ?></a>
			<?php else: ?>
			<?php echo $item->author->name; ?>
			<?php endif; ?>
		</span>
	</div>

	<?php $_showItemRatingClass = $item->params->get('catItemRating') ? 'display-default display-item' : 'hide-item'; ?>
	<div class="itemRating element-switch  contextmenu-approved <?php echo $_showItemRatingClass?>" id="catItemRating">
		<span><?php echo JText::_('K2_RATE_THIS_ITEM'); ?></span>
		<div class="itemRatingForm">
			<ul class="itemRatingList">
				<li class="itemCurrentRating" id="itemCurrentRating<?php echo $item->id; ?>" style="width:<?php echo $item->votingPercentage; ?>%;"></li>
				<li><a href="#" rel="<?php echo $item->id; ?>" title="<?php echo JText::_('K2_1_STAR_OUT_OF_5'); ?>" class="one-star">1</a></li>
				<li><a href="#" rel="<?php echo $item->id; ?>" title="<?php echo JText::_('K2_2_STARS_OUT_OF_5'); ?>" class="two-stars">2</a></li>
				<li><a href="#" rel="<?php echo $item->id; ?>" title="<?php echo JText::_('K2_3_STARS_OUT_OF_5'); ?>" class="three-stars">3</a></li>
				<li><a href="#" rel="<?php echo $item->id; ?>" title="<?php echo JText::_('K2_4_STARS_OUT_OF_5'); ?>" class="four-stars">4</a></li>
				<li><a href="#" rel="<?php echo $item->id; ?>" title="<?php echo JText::_('K2_5_STARS_OUT_OF_5'); ?>" class="five-stars">5</a></li>
			</ul>
			<div id="itemRatingLog<?php echo $item->id; ?>" class="itemRatingLog"><?php echo $item->numOfvotes; ?></div>
			<div class="clearbreak"></div>
		</div>
		<div class="clearbreak"></div>
	</div>
	<div class="clearbreak"></div>

	<?php if(!empty($item->image)){
		// If image url is relative, remove "administrator".
		if(strpos($item->image, 'http://') === false && strpos($item->image, 'https://') === false){
			$item->image = str_replace('/administrator/', '/', $item->image);
		}

	?>
	<?php $_showImageClass = $item->params->get('catItemImage') ? 'display-default display-item' : 'hide-item'; ?>
	<!-- Item Image -->
	<div class="item-image element-switch  contextmenu-approved <?php echo $_showImageClass?>" id="catItemImage">
		<img src="<?php echo $item->image; ?>" alt="<?php if(!empty($item->image_caption)) echo K2HelperUtilities::cleanHtml($item->image_caption); else echo K2HelperUtilities::cleanHtml($item->title); ?>" style="width:200px; height:auto;" />
	</div>
	<?php }?>

	<?php if(!empty($item->introtext)){ ?>
		<?php $_showIntroClass = $item->params->get('catItemIntroText') ? 'display-default display-item' : 'hide-item'; ?>
	<div class="item-intro element-switch  contextmenu-approved <?php echo $_showIntroClass?>" id="catItemIntroText" itemid="<?php echo $item->id;?>">
  		<?php echo $item->introtext; ?>
	</div>
	<?php }?>

	<div class="clearbreak"></div>

	<?php if(is_array($item->extra_fields) && count($item->extra_fields)){;?>
	<!-- Item extra fields -->
	<?php $_showExtraFieldsClass = $item->params->get('catItemExtraFields') ? 'display-default display-item' : 'hide-item'; ?>
	<div class="item-extra-fields element-switch  contextmenu-approved <?php echo $_showExtraFieldsClass?>" id="catItemExtraFields">
		<h3><?php echo JText::_('K2_ADDITIONAL_INFO'); ?></h3>
		<ul>
			<?php foreach ($item->extra_fields as $key=>$extraField){ ?>
			<?php if($extraField->value != ''){ ?>
			<li class="<?php echo ($key%2) ? "odd" : "even"; ?> type<?php echo ucfirst($extraField->type); ?> group<?php echo $extraField->group; ?>">
				<?php if($extraField->type == 'header'){ ?>
				<h4 class="itemExtraFieldsHeader"><?php echo $extraField->name; ?></h4>
				<?php }else{ ?>
				<span class="itemExtraFieldsLabel"><?php echo $extraField->name; ?>:</span>
				<span class="itemExtraFieldsValue"><?php echo $extraField->value; ?></span>
				<?php } ?>
			</li>
			 <?php } ?>
			<?php } ?>
		</ul>
	    <div class="clearbreak"></div>
	</div>
	<?php }?>

	<!-- Item Content footer -->
	<?php if(intval($item->modified)!=0){ ?>
	<div class="itemContentFooter">
		<?php $_showHitsClass = $item->params->get('catItemHits') ? 'display-default display-item' : 'hide-item'; ?>
		<div class="itemHits element-switch  contextmenu-approved <?php echo $_showHitsClass?>" id="catItemHits">
			<?php echo JText::_('K2_READ'); ?> <b><?php echo $item->hits; ?></b> <?php echo JText::_('K2_TIMES'); ?>
		</div>

	</div>
	<?php }?>
	<div class="clearbreak"></div>

	<?php $_showCategoryClass = $item->params->get('catItemCategory') ? 'display-default display-item' : 'hide-item'; ?>
	<!-- Item category -->
	<div class="itemCategory element-switch  contextmenu-approved <?php echo $_showCategoryClass?>" id="catItemCategory">
		<span><?php echo JText::_('K2_PUBLISHED_IN'); ?></span>
		<span class="contextmenu-approved" id="itemCategoryName">
		<a href="javascript:void(0)"><?php echo $item->category->name; ?></a>
		</span>
	</div>

	<?php if(count($item->tags)){ ?>
		<?php $_showTagsClass = $item->params->get('catItemTags') ? 'display-default display-item' : 'hide-item'; ?>
	<!-- Item tags -->
	<div class="itemTags element-switch  contextmenu-approved <?php echo $_showTagsClass?>" id="catItemTags">
		<span><?php echo JText::_('K2_TAGGED_UNDER'); ?></span>
		  <ul class="itemTags">
		    <?php foreach ($item->tags as $tag){ ?>
		    <li><a href="javascript:void(0)"><?php echo $tag->name; ?></a></li>
		    <?php } ?>
		  </ul>
		  <div class="clearbreak"></div>
	</div>
	<?php }?>

	<?php if( count($item->attachments)){?>
		<?php $_showAttachmentsClass = $item->params->get('catItemAttachments') ? 'display-default display-item' : 'hide-item'; ?>
	<!-- Item attachments -->
	<div class="itemAttachments element-switch  contextmenu-approved <?php echo $_showAttachmentsClass?>" id="catItemAttachments">
		<span><?php echo JText::_('K2_DOWNLOAD_ATTACHMENTS'); ?></span>
		  <ul class="itemAttachments">
		    <?php foreach ($item->attachments as $attachment){ ?>
		    <li>
			    <a title="<?php echo K2HelperUtilities::cleanHtml($attachment->titleAttribute); ?>" href="javascript:void(0)"><?php echo $attachment->title; ?></a>
			    <?php $_showAttachmentsCounterClass = $item->params->get('catItemAttachmentsCounter') ? 'display-default display-item' : 'hide-item'; ?>
			    <span class="itemAttachmentsCounter element-switch  contextmenu-approved <?php echo $_showAttachmentsCounterClass?>" id="catItemAttachmentsCounter">(<?php echo $attachment->hits; ?> <?php echo ($attachment->hits==1) ? JText::_('K2_DOWNLOAD') : JText::_('K2_DOWNLOADS'); ?>)</span>
			</li>
		    <?php } ?>
		  </ul>
	</div>
	<?php }?>

	<div class="clearbreak"></div>
	<?php if(!empty($item->video)){ ?>

	<?php $_showVideoClass = $item->params->get('catItemVideo') ? 'display-default display-item' : 'hide-item'; ?>
	<div class="itemVideo element-switch contextmenu-approved <?php echo $_showVideoClass?>"  id="catItemVideo">
		<h3><?php echo JText::_('K2_MEDIA'); ?></h3>
				<?php echo JText::_('JSN_RAWMODE_COMPONENT_K2_COMMENT_FORM_COME');?>
		<div class="clearbreak"></div>
	</div>
	<?php }?>
	<div class="clearbreak"></div>
	<?php if(!empty($item->gallery)){ ?>
		<?php $_showImageGalleryClass = $item->params->get('catItemImageGallery') ? 'display-default display-item' : 'hide-item'; ?>
	<!-- Item image gallery -->

	<div class="itemImageGallery element-switch  contextmenu-approved  <?php echo $_showImageGalleryClass; ?>" id="catItemImageGallery">
		<h3><?php echo JText::_('K2_IMAGE_GALLERY'); ?></h3>
		  <?php echo $item->gallery; ?>
	</div>
	<?php } ?>



	<?php $_showItemCommentsAnchorClass = $item->params->get('catItemCommentsAnchor') ? 'display-default display-item' : 'hide-item'; ?>
	<!-- Anchor link to comments below -->
	<div class="catItemCommentsAnchor element-switch  contextmenu-approved  <?php echo $_showItemCommentsAnchorClass; ?>" id="catItemCommentsAnchor" extvalue="<?php echo (int)$item->params->get('comments')?>">

			<?php if($item->numOfComments > 0){ ?>
			<a href="javascript:void(0)">
				<?php echo $item->numOfComments; ?> <?php echo ($item->numOfComments>1) ? JText::_('K2_COMMENTS') : JText::_('K2_COMMENT'); ?>
			</a>
			<?php }else{ ?>
			<a href="javascript:void(0)">
				<?php echo JText::_('K2_BE_THE_FIRST_TO_COMMENT'); ?>
			</a>
			<?php } ?>

	</div>


</div>

