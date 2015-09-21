<link rel="stylesheet" href="../plugins/uniform/mailchimp/assets/css/mailchimp.css" type="text/css" />
<script>
	window.base_url = <?php echo json_encode(JURI::root()); ?>;
</script>
<script src="../plugins/uniform/mailchimp/assets/js/jsn_mailchimp.js" type="text/javascript"></script>
<style>
	.mc-list-table {
		margin-bottom: 10px;
	}
	.mc-list-table thead th {
		border-bottom: 1px solid #ccc;
	}
	.mc-list-table td {
		border: none;
		vertical-align: middle;
		white-space: nowrap;
	}
	.mc-list-table td select,
	.mc-list-table td input:not([type="checkbox"]) {
		margin: 0;
	}
	.mc-mini-col {
		width: 1px;
	}
	#mc{width: 35% !important}
	#mc1{width: 64% !important}
</style>
<div class="mailchimp">
	<fieldset id="mailchimp">
		<legend>
			<i class="logoMailchimp"></i><?php echo JText::_('JSN_UNIFORM_FORM_MAILCHIMP_SETTING'); ?>
		</legend>
		<div class="control-group jsn-items-list-container">
			<label class="control-label jsn-label-des-tipsy">
				Use Mailchimp:
			</label>
			<div class="controls">
				<span class="btn use_mailchimp no choiseNo"><?php echo JText::_('JSN_UNIFORM_SELECT_CHOICE_NO'); ?></span>
				<span class="btn use_mailchimp yes"><?php echo JText::_('JSN_UNIFORM_SELECT_CHOICE_YES'); ?></span>
				<input type="hidden" id="hidUseMailchimp">
			</div>
		</div>
		<div class="usemailchimp">
			<div class="control-group jsn-items-list-container">
				<label class="control-label jsn-label-des-tipsy">
					Mailchimp API Key:
				</label>
				<div class="controls">
					<input type="text" name="mailchimKey" id="mailchimpKey">
					<span class="showkey"></span>
					<span class="btn checkkey btn-success">Ok</span>
					<span class="btn cancelcheck">Cancel</span>
					<span class="maichimploading">loadding....</span>
					<span class="mailchimp_err"></span>
					<input type="hidden" id="KeyMailchimp">
				</div>
			</div>
			<div class="validate_api">
				<div class="control-group jsn-items-list-container">
					<label class="control-label jsn-label-des-tipsy">
						Mailchimp List:
					</label>
				</div>
				<div class="">
					<div class="container-fluid">
						<div class="accordion listmailchimp" id="accordion2">

						</div>
					</div>
				</div>
			</div>
		</div>
	</fieldset>
	<input type="hidden" name="form_mailchimp"  id ="jform_form_mailchimp" value='<?php echo $mailchimp ?>'>
</div>