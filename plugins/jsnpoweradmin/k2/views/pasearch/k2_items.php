<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: k2_items.php 13902 2012-07-11 10:34:39Z thangbh $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$this->lists['order_Dir']	= $this->escape($this->state->get('list.direction'));
$this->lists['order']		= $this->escape($this->state->get('list.ordering'));
$this->filter_trash = false;
$this->filter_featured = false;
$this->ordering = ($this->lists['order'] == 'a.lft');
$this->dateFormat = (K2_JVERSION == '16') ? JText::_('K2_J16_DATE_FORMAT',true) : JText::_('K2_DATE_FORMAT',true);
$this->nullDate = JFactory::getDBO()->getNullDate();
?>

<form action="<?php echo JRoute::_('index.php?option=com_poweradmin&view=search'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th class="title">
					<?php echo JText::_('K2_TITLE');?>
				</th>
				<th>
					<?php echo JText::_('K2_FEATURED');?>
				</th>
				<th>
					<?php echo JText::_('K2_PUBLISHED');?>
				</th>
				<th>
					<?php echo JText::_('K2_CATEGORY');?>
				</th>
				<th>
					<?php echo JText::_('K2_AUTHOR');?>
				</th>
				<th>
					<?php echo JText::_('K2_LAST_MODIFIED_BY');?>
				</th>
				<th>
					<?php echo JText::_('K2_ACCESS_LEVEL');?>
				</th>
				<th>
					<?php echo JText::_('K2_CREATED');?>
				</th>
				<th>
					<?php echo JText::_('K2_MODIFIED');?>
				</th>
				<th>
					<?php echo JText::_('K2_HITS');?>
				</th>
				<th>
					<?php echo JText::_('K2_ID');?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($this->items as $key => $row): ?>
			<tr class="row<?php echo ($key%2); ?>">
				<td>
					<?php
					JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_k2/tables');
					$table = JTable::getInstance('K2Item', 'Table');
					if ($table->isCheckedOut($this->user->get('id'), $row->checked_out )): ?>
					<?php echo $row->title; ?>
					<?php else: ?>
					<?php if(!$this->filter_trash): ?>
					<a href="<?php echo JRoute::_('index.php?option=com_k2&view=item&cid='.$row->id); ?>"><?php echo $row->title; ?></a>
					<?php else: ?>
					<?php echo $row->title; ?>
					<?php endif; ?>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php if(!$this->filter_trash): ?>
					<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $key; ?>','featured')" title="<?php echo ( $row->featured ) ? JText::_('K2_REMOVE_FEATURED_FLAG',true) : JText::_('K2_FLAG_AS_FEATURED',true); ?>">
					<?php endif; ?>
					<?php $row->state = $row->published; $row->published = $row->featured; echo strip_tags(JHTML::_('grid.published', $row, $key ), '<img>'); $row->published = $row->state; ?>
					<?php if(!$this->filter_trash): ?>
					</a>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo ($this->filter_trash) ? strip_tags(JHTML::_('grid.published', $row, $key ),'<img>') : JHTML::_('grid.published', $row, $key ); ?>
				</td>
				<td class="center">
					<a href="<?php echo JRoute::_('index.php?option=com_k2&view=category&cid='.$row->catid); ?>"><?php echo $row->category; ?></a>
				</td>
				<td class="center">
					<?php echo $row->author; ?>
				</td>
				<td class="center">
					<?php echo $row->moderator; ?>
				</td>
				<td class="center">
					<?php echo ($this->filter_trash || K2_JVERSION != '15')? $row->groupname:JHTML::_('grid.access', $row, $key); ?>
				</td>
				<td class="center">
					<?php echo JHTML::_('date', $row->created , $this->dateFormat); ?>
				</td>
				<td class="center">
					<?php echo ($row->modified == $this->nullDate) ? JText::_('K2_NEVER') : JHTML::_('date', $row->modified , $this->dateFormat); ?>
				</td>
				<td class="center">
					<?php echo $row->hits ?>
				</td>
				<td class="center">
					<?php echo $row->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_('form.token'); ?>
</form>
