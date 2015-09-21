<?php
/**
 * @version		$Id: item_comments_form.php 1618 2012-09-21 11:23:08Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 *
 * Modified by JoomlaShine
 *
 */

// no direct access
defined('_JEXEC') or die;

?>

<h3><?php echo JText::_('K2_LEAVE_A_COMMENT') ?></h3>

<p class="itemCommentsFormNotes">
	<?php if($item->params->get('commentsFormNotesText')): ?>
	<?php echo nl2br($item->params->get('commentsFormNotesText')); ?>
	<?php else: ?>
	<?php echo JText::_('K2_COMMENT_FORM_NOTES') ?>
	<?php endif; ?>
</p>
<?php echo JText::_('JSN_RAWMODE_COMPONENT_K2_COMMENT_FORM_COME');?>
