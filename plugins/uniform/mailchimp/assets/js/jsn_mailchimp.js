/**
 * @version    $Id: jsn_mailchimp.js 2014-8-28 anhnt $
 * @package    JSN.Uniform
 * @subpackage Plugins.Mailchimp
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2014 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

(function($) {
	var JSNMailchimp = {
		initialize: function()
		{
			var self = this;
			var apiKey;
			self.useMailchimp();
			$('.checkkey').click(function() {
				$('.listmailchimp').empty();
				$('.listmailchimp').removeAttr('data');
				self.changeApiMailchimp();
			})
			var jform = $('#jform_form_mailchimp').val();
			if (typeof jform !== 'undefined' && jform !== '') {
				var arr = JSON.parse(jform);
				var nm = parseFloat(arr['useMailchimp']);
				if (nm === 1)
				{
					self.loadDataMailchimp();
				}
			}
			self.survey('#responseFieldDesign', function() {
				$('.mailchimp').find('.listmailchimp').each(function() {
					self.changeFielDesign($(this));
				})
			});
			self.editApiKey()
		},
		survey: function(selector, callback) {
			var input = $(selector);
			var oldvalue = input.val();
			setInterval(function() {
				if (input.val() !== oldvalue) {
					oldvalue = input.val();
					callback();
				}
			}, 100);
		},
		changeFielDesign: function(e) {
			var self = this;
			var response = $('#responseFieldDesign').val();
			var arrOption = self.valOption(e);
			var fieldName = 0;
			var str = '';
			var first = 1;
			var middle = 1;
			var last = 1;
			if (typeof response !== 'undefined' && response !== '') {
				response = JSON.parse(response);
				var arr = new Array();
				var arrLable = new Array();
				$.each(response, function(i, item) {
					arrLable[item.options.label] = item.options.label;
				})
				var arrLb = Object.keys(arrLable).map(function(a) {
					return arrLable[a];
				});
				$.each(response, function(i, item) {
					arr[item.identify] = item.identify;
					if (arrOption.indexOf(item.identify) === -1 && item.type !== 'name') {
						str = '<option value ="' + item.identify + '.' + item.type + '" data="' + item.type + '">' + item.options.label + '</option>';
					}
					if (item.type === 'name') {
						fieldName = 1;
						if (typeof item.options.vfirst === 'undefined') {
							first = 0;
						}
						if (typeof item.options.vmiddle === 'undefined') {
							middle = 0;
						}
						if (typeof item.options.vlast === 'undefined') {
							last = 0;
						}
					}
					if (arrOption.indexOf(item.identify) === -1 && item.type === 'name' && arrOption.indexOf('first') === -1 && arrOption.indexOf('middle') === -1 && arrOption.indexOf('last') === -1) {
						var ar = JSON.parse(item.options.sortableField);
						$.each(ar, function(key, v) {
							if (v === 'vfirst' && item.options.vfirst === '1') {
								str += '<option value="first.first" >First Name</option>';
							}
							if (v === 'vmiddle' && item.options.vmiddle === '1') {
								str += '<option value="middle.middle"  >Middle Name</option>';
							}
							if (v === 'vlast' && item.options.vlast === '1') {
								str += '<option value="last.last" >Last Name</option>';
							}
						})
					}
				})

				if (arr !== '') {
					var arrs = Object.keys(arr).map(function(k) {
						return arr[k];
					});
				}
				e.find('.accordion-group').find('.accordion-body').each(function() {
					$(this).find('select').each(function() {
						if (typeof $(this).attr('id') === 'undefined') {
							$(this).append(str);
							arrOption = self.valOption(e);
							$(this).find('option').each(function() {
								var txt = $(this).text();
								var slf = $(this);
								var fieldType = $(this).attr('value');
								fieldType = fieldType.split('.');
								fieldType = fieldType[0];

								if (typeof arrs !== 'undefined')
								{
									if (fieldType !== '' && arrs.indexOf(fieldType) === -1 && fieldType !== 'first' && fieldType !== 'middle' && fieldType !== 'last') {
										if ($(this).is(':selected')) {
											var id = $(this).parent().parent().parent().parent().attr('id');
											$(this).parent().parent().parent().find('.mc-mini-col .mc_remove').each(function() {
												self.clickRemove($(this), id);
											})
										} else {
											$(this).remove();
										}
									}
									if (fieldType !== '' && arrs.indexOf(fieldType) !== -1 && arrLb.indexOf(txt) === -1) {
										$.each(response, function(i, item) {
											if (item.identify === fieldType) {
												slf.text(item.options.label);
											}
										})
									}
									if (fieldName == 0 && (fieldType === 'first' || fieldType === 'middle' || fieldType === 'last')) {
										if ($(this).is(':selected')) {
											var id = $(this).parent().parent().parent().parent().attr('id');
											$(this).parent().parent().parent().find('.mc-mini-col .mc_remove').each(function() {
												self.clickRemove($(this), id);
											})
										} else {
											$(this).remove();
										}
									}

									if ((fieldType === 'first' && first == 0) || (fieldType === 'middle' && middle == 0) || (fieldType === 'last' && last == 0)) {
										if ($(this).is(':selected')) {
											var id = $(this).parent().parent().parent().parent().attr('id');
											$(this).parent().parent().parent().find('.mc-mini-col .mc_remove').each(function() {
												self.clickRemove($(this), id);
											})
										} else {
											$(this).remove();
										}
									}
								}
							})
						}
					})
				})
			} else {
				var arrs = new Array();
				arrs[0] = 0;
				e.find('.accordion-group').find('.accordion-body').each(function() {
					$(this).find('select').each(function() {
						if (typeof $(this).attr('id') === 'undefined') {
							$(this).find('option').each(function() {
								var fieldType = $(this).attr('value');
								fieldType = fieldType.split('.');
								fieldType = fieldType[0];
								if ($(this).is(':selected')) {
									var id = $(this).parent().parent().parent().parent().attr('id');
									$(this).parent().parent().parent().find('.mc-mini-col .mc_remove').each(function() {
										self.clickRemove($(this), id);
									})
								} else {
									$(this).remove();
								}
							})
						}
					})
				})
			}
		},
		valOption: function(e) {
			var arr = new Array();
			e.find('.accordion-group').find('.accordion-body').each(function() {
				$(this).find('select').each(function() {
					if (typeof $(this).attr('id') === 'undefined') {
						$(this).find('option').each(function() {
							var fieldType = $(this).attr('value');
							fieldType = fieldType.split('.');
							fieldType = fieldType[0];
							arr[fieldType] = fieldType;
						})
					}
				})
			})
			var arrOption = Object.keys(arr).map(function(k) {
				return arr[k];
			});
			return arrOption;
		},
		/*
		 * Change value API Mailchimp
		 * 
		 * Return void
		 */
		changeApiMailchimp: function(e) {
			var self = this;
			var apiKey = $('#mailchimpKey').val();
			if (apiKey !== '') {
				$('.mailchimp_err').html('');
				$('.maichimploading').css('top', '0px');
				self.checkApiKey(apiKey);
			} else {
				$('.mailchimp_success').addClass('mailchimp_err');
				$('.mailchimp_success').removeClass('mailchimp_success');
				$('.mailchimp_err').html('Please enter API key!');
				$('.listmailchimp').empty();
				$('ul.listmailchimp').removeAttr('data');
			}
		},
		/*
		 * Load data of Mailchimp saved
		 * 
		 * Return string HTML 
		 */
		loadDataMailchimp: function(e) {
			var self = this;
			var data = $('#jform_form_mailchimp').val();
			if (typeof data !== 'undefined' && data !== '') {
				if (typeof e === 'undefined') {
					$('.yes').click();
				}
				data = $.parseJSON(data);
				$.each(data, function(k, v) {
					var listId = data['listId'];
					$('#checkListMailchimp').val(JSON.stringify(listId));
					var apiKey = data['keyApi'];
					var fieldLabel = data['fieldlabel'];
					$('#checkFieldMailchimp').val(JSON.stringify(fieldLabel));
					if (apiKey !== '') {
						$('#mailchimpKey').val(apiKey);
					}
				})
				self.changeApiMailchimp($('#mailchimpKey'));
			}
		},
		/*
		 * Check API Key of Mailchimp
		 * @param string @key string api key
		 * @param int @list condition to action method
		 * Return void 
		 */
		checkApiKey: function(key) {
			var self = this;
			var data = {};
			data['key'] = key;
			data['func'] = 'checkApiKey';
			data['plgName'] = 'mailchimp';
			var oldKey = $('ul.listmailchimp').attr('data');
			if (key !== oldKey) {
				$.ajax({
					type: "POST",
					async: true,
					url: 'index.php?option=com_uniform&view=form&task=form.do_ajax&tmpl=component',
					data: {
						val: JSON.stringify(data)
					},
					beforeSend: function() {
						$('.maichimploading').show();
					},
					success: function(msg) {
						var arr = $.parseJSON(msg);
						if (arr.name === 'Invalid_ApiKey') {
							$('.mailchimp_success').addClass('mailchimp_err');
							$('.mailchimp_success').removeClass('mailchimp_success');
							$('.mailchimp_err').html(arr.error);
							$('.validate_api').hide();
							$('.maichimploading').hide();
							$('.listmailchimp').empty();
							$('ul.listmailchimp').removeAttr('data');
						} else {
							$('#mailchimpKey').hide();
							$('.showkey').text(key);
							$('.showkey').show();
							$('.checkkey').hide();
							$('.cancelcheck').hide();
							$('.mailchimp_err').addClass('mailchimp_success');
							$('.mailchimp_err').removeClass('mailchimp_err');
							$('.mailchimp_success').html('success');
							self.listMailchimp(key);
							$('#KeyMailchimp').val(1);
							$('.maichimploading').hide();
							$('ul.listmailchimp').attr('data', key);
							self.editApiKey();
						}
					}
				});
			}
		},
		/*
		 * Edit API Key Mailchimp
		 *
		 * Return void 
		 */
		editApiKey: function() {
			var self = this;
			$('.showkey').click(function() {
				$('#mailchimpKey').show();
				$(this).hide();
				$('.checkkey').show();
				$('.cancelcheck').show();
				$('.mailchimp_success').empty();
				$('.mailchimp_success').addClass('mailchimp_err');
				$('.mailchimp_success').removeClass('mailchimp_success');

			})
			$('.cancelcheck').click(function() {
				if ($.trim($('.listmailchimp').html())) {
					var key = $(".showkey").text();
					$('#mailchimpKey').hide();
					$('#mailchimpKey').val(key);
					$('.showkey').show();
					$('.checkkey').hide();
					$('.cancelcheck').hide();
					$('.mailchimp_err').addClass('mailchimp_success');
					$('.mailchimp_err').removeClass('mailchimp_err');
					$('.mailchimp_success').html('success');
				} else {
					$('.showkey').text('Please Enter APIKey');
					$('.showkey').show();
					$('#mailchimpKey').hide();
					$('.checkkey').hide();
					$('.cancelcheck').hide();
				}
			})
		},
		/*
		 * Show list on Mailchimp
		 * @param string @key string api key
		 * @param int @list condition to action method
		 * Return string HTML 
		 */
		listMailchimp: function(apikey) {
			var self = this;
			var data = {};
			data['key'] = apikey;
			data['func'] = 'showListMailchimp';
			data['plgName'] = 'mailchimp';
			$.ajax({
				type: "POST",
				async: true,
				url: "index.php?option=com_uniform&view=form&task=form.do_ajax&tmpl=component",
				data: {
					val: JSON.stringify(data)
				},
				success: function(msg) {
					var arr = $.parseJSON(msg);
					var i = 0;
					var str = '';
					var sr = new Array();
					$.each(arr.data, function(value, key) {
						i++;
						var d = new Date();
						var time = d.getTime();
						$('.listmailchimp').append('<div class="accordion-group"><div class="accordion-heading"><div class="accordion-toggle"><label class="checkbox inline"><input class="checkbox" type="checkbox" id="chb' + arr.data[value].id + '"><a data-toggle="collapse" href="#list' + i + '" data-parent="#accordion2"><strong>' + arr.data[value].name + '</strong></a></label></div></div><div id="list' + i + '" class="editfield accordion-body collapse"><div class="accordion-inner"><table class="table table-hover mc-list-table list' + arr.data[value].id + '"><thead><tr><th>UniForm Field</th><th></th><th>MailChimp Field</th><th></th><th></th></tr><tbody class="listfieldmailchimp" id="' + arr.data[value].id + time + '"></tbody></table><span data="' + arr.data[value].id + '" class="btn addmoreField btn-info btn-block">Add new field</span></div></div><input type="hidden" id="vl' + arr.data[value].id + '" value=""><input type="hidden" id="hidmailchimp' + arr.data[value].id + '" value=""><input type="hidden" id="hidform' + arr.data[value].id + '" value=""><input type="hidden" id="fieldList' + arr.data[value].id + '"></div>');
						var formMailchimp = $('#jform_form_mailchimp').val();
						if (formMailchimp !== '') {
							formMailchimp = JSON.parse(formMailchimp);
							$('#vl' + arr.data[value].id).val(formMailchimp['arrfield'][arr.data[value].id]);
							if (typeof formMailchimp['arrfield'][arr.data[value].id] !== 'undefined' && formMailchimp['arrfield'][arr.data[value].id] !== '') {
								var hidform = JSON.parse(formMailchimp['arrfield'][arr.data[value].id]);
								$('#hidform' + arr.data[value].id).val(hidform['hidform']);
								$('#hidmailchimp' + arr.data[value].id).val(hidform['hidmailchimp']);
							}
						}
						var Mailchimp = $('#jform_form_mailchimp').val();
						var arrField = '';
						if (Mailchimp !== '') {
							Mailchimp = JSON.parse(Mailchimp);
							if (typeof Mailchimp['arrfield'][arr.data[value].id] !== 'undefined' && Mailchimp['arrfield'][arr.data[value].id] !== '') {
								arrField = JSON.parse(Mailchimp['arrfield'][arr.data[value].id]);
							}
						}
						if ($('#vl' + arr.data[value].id).val() !== '') {
							arrField = JSON.parse($('#vl' + arr.data[value].id).val());
						}

//							if (!$(this).hasClass('MailchimpActive')) {
						$(this).addClass('loadMC ');
						setTimeout(function() {
							var fieldList = self.listAllFieldInListOnMailchimp(apikey, arr.data[value].id);
							if (fieldList !== '') {
								$('#fieldList' + arr.data[value].id).val(fieldList);
								fieldList = JSON.parse(fieldList);
							}
							var response = $('#responseFieldDesign').val();

							if (arrField !== '') {
								self.loadTableFieldForm(response, arr.data[value].id, fieldList, '', arrField['Field'], time);
								self.clickShowList($('#' + arr.data[value].id + ' .showicon'), 0, arr.data[value].id, response);
								self.closeList(arr.data[value].id);
							} else {
								if (response !== '') {
									self.tableFieldForm(response, arr.data[value].id, fieldList, '', time);
									self.clickShowList($('#' + arr.data[value].id + ' .showicon'), 0, arr.data[value].id, response);
									self.closeList(arr.data[value].id);
								} else {
									alert("Your Form have't any field!");
								}
							}
						}, 1000);
						var saved = $('#vl' + arr.data[value].id).val();
						if (saved !== '') {
							var save = JSON.parse(saved);
							if (save.allow === 1) {
								$('#chb' + arr.data[value].id).attr('checked', 'checked');
							}
						} else {
							$('#chb' + arr.data[value].id).attr('disabled', 'disabled');
						}
					})
					self.checkBoxList();
					self.removeMapFied();
					self.addMoreFieldMapping();
					$('.validate_api').show();
				}
			});
		},
		/*
		 * Close list Mailchimp
		 * @param string @listId string list ID
		 * Return void
		 */
		closeList: function(listId) {
			var self = this;
			$('.listmailchimp').find('span.upicon').each(function() {
				var id = $(this).parent().attr('id');
				if (id !== listId) {
					var respons = $('#responseFieldDesign').val();
					var hidarr = {};
					var hidmailchimp = {};
					$('.list' + id).find('select').each(function() {
						var cl = $(this).attr('class');
						if (typeof cl !== 'undefined') {
							if ($('#mailchimp' + cl).val() !== '' || $('#txt' + cl).val() !== '') {
								if ($(this).val() !== '') {
									var txt = $(this).find('option:selected').text();
									hidarr[cl] = txt;
								}
							}
						} else {
							var hdid = $(this).attr('id');
							var cl = hdid.split('mailchimp');
							if ($('.' + cl[1]).val() !== '') {
								if ($(this).val() !== '') {
									var txt = $(this).find('option:selected').text();
									hidmailchimp[hdid] = txt;
								}
							}
						}
					})
					$('#hidmailchimp' + id).val(JSON.stringify(hidmailchimp));
					$('#hidform' + id).val(JSON.stringify(hidarr));
					self.clickShowList($(this), 1, id, respons);
					if ($('#chb' + id).is(':checked')) {
						self.getArrayField(id, 3);
					} else {
						self.getArrayField(id, 4);
					}
				}
			})
		},
		/*
		 * Check list to post on Mailchimp
		 * 
		 * Return void 
		 */
		checkBoxList: function() {
			$('.accordion-heading').find('input.checkbox').each(function() {
				$(this).click(function() {
					var id = $(this).attr('id');
					var di = id.split('chb');
					if ($(this).is(':checked')) {
						if ($('#hidUseMailchimp').val() === '1') {
							var arr = {};
							if ($('#KeyMailchimp').val() === "1") {
								var key = $('#mailchimpKey').val();
								arr['keyApi'] = key;
								arr['arrfield'] = {};
								var i = 0;
								var oldStr = $('#jform_form_mailchimp').val();
								if (oldStr !== '') {
									var objForm = JSON.parse(oldStr);
									var arrs = objForm.arrfield;
									var arrList = JSON.parse(arrs[di[1]]);
									if (typeof di[1] !== 'undefined' && di[1] !== '') {
										arrList.allow = 1;
										arrs[di[1]] = JSON.stringify(arrList);
										$('#jform_form_mailchimp').val(JSON.stringify(objForm));
									}
								}
							}
						}
					} else {
						var objForm = $('#jform_form_mailchimp').val();
						if (objForm !== '') {
							objForm = JSON.parse(objForm);
							var arr = objForm.arrfield;
							var arrL = JSON.parse(arr[di[1]]);
							if (typeof di[1] !== 'undefined' && di[1] !== '') {
								arrL.allow = 0;
								arr[di[1]] = JSON.stringify(arrL);
								$('#jform_form_mailchimp').val(JSON.stringify(objForm));
							}
						}
					}
				})
			})
		},
		/*
		 * Show content in  a list mailchimp
		 * 
		 * Return void 
		 */
		clickShowList: function(e, cl, id, response) {
			if (cl === 1) {
				e.removeClass('loadMC');
				e.removeClass('upicon');
				e.addClass('showicon');
				e.removeClass('MailchimpActive');
			} else {
				e.removeClass('loadMC');
				e.removeClass('showicon');
				e.addClass('upicon');
				if (response !== '') {
					e.addClass('MailchimpActive');
				}
				$('.list' + id).slideDown();
			}
		},
		/*
		 * Get value field in form and Mailchimp selected 	
		 * @param string @id
		 * Return html 
		 */
		getArrayField: function(id, cm) {
			var arrayField = {};
			if (cm === 2 || cm === 3) {
				arrayField['allow'] = 1;
			} else {
				arrayField['allow'] = 0;
			}
			arrayField['listid'] = id;
			arrayField['Fieldid'] = {};
			arrayField['Field'] = {};
			arrayField['hidform'] = $('#hidform' + id).val();
			arrayField['hidmailchimp'] = $('#hidmailchimp' + id).val();
			arrayField['Field']['identify'] = {};
			arrayField['Field']['old'] = {};
			arrayField['Field']['new'] = {};
			arrayField['Field']['tag'] = {};
			var i = 0;

			$('.list' + id).find('tbody').each(function() {
				var cl = $(this).attr('id');
				if (typeof cl !== 'undefined') {
					i++;
					var flForm = $('.' + cl).val();
					if (flForm !== '' && typeof flForm !== 'undefined') {
						flForm = flForm.split(".");
						arrayField['Fieldid'][flForm[0]] = flForm[1];
						if ($('#txt' + cl).val() !== '' || $('#mailchimp' + cl).val() !== '') {
							if ($('#txt' + cl).val() === '') {
								arrayField['Field']['old'][flForm[1]] = $('#mailchimp' + cl).val();
								arrayField['Field']['identify'][flForm[0]] = $('#mailchimp' + cl).val();
								arrayField['Field']['tag'][$('#mailchimp' + cl).val()] = $('#mailchimp' + cl).val();
							} else {
								arrayField['Field']['identify'][flForm[0]] = $('#txt' + cl).val();
								arrayField['Field']['old'][flForm[1]] = $('#txt' + cl).val();
								arrayField['Field']['new'][flForm[0]] = $('#txt' + cl).val();
								if ($('#other' + cl).val() !== '') {
									arrayField['Field']['tag'][$('#other' + cl).val()] = $('#txt' + cl).val();
								}
							}
						}
					}
					if (!$.isEmptyObject(arrayField['Field']['old'])) {
						$('#vl' + id).val(JSON.stringify(arrayField));
						$('#chb' + id).attr('disabled', false);
					} else {
						$('#vl' + id).val('');
					}
				}
			})
			if ($('#hidUseMailchimp').val() === '1') {
				var arr = {};
				if ($('#KeyMailchimp').val() === "1") {
					var key = $('#mailchimpKey').val();
					arr['useMailchimp'] = $('#hidUseMailchimp').val();
					arr['keyApi'] = key;
					arr['arrfield'] = {};
					var i = 0;
					$('.listmailchimp').find('input[type="hidden"]').each(function() {
						var id = $(this).attr('id');
						id = id.split('vl');
						if (typeof id[1] !== 'undefined' && id[1] !== '') {
							arr['arrfield'][id[1]] = $(this).val();
						}
					})
					$('#jform_form_mailchimp').val(JSON.stringify(arr));
				}
			}
			if (cm !== 1 && cm !== 2) {
				$('.list' + id).find('tbody').each(function() {
					if ($(this).hasClass('listfieldmailchimp')) {
						$(this).empty();
					} else {
						$(this).remove();
					}
				});
				$('.list' + id).hide();
				$('.list' + id).parent().hide();
			}
		},
		/*
		 * Remove character special 
		 * @param string @str
		 * Return string 
		 */
		removeCharacter: function(str) {
			str = str.replace(/[^a-zA-Z0-9]/g, "");
			str = str.substring(0, 10);
			str = str.toUpperCase();
			return str;
		},
		/*
		 * Show list field in form and Mailchimp
		 * @param array @response
		 * @param string @id
		 * Return html 
		 */
		listAllFieldInListOnMailchimp: function(key, id) {
			var data;
			var arr = {};
			arr['key'] = key;
			arr['listId'] = id;
			arr['func'] = 'listAllFieldInListOnMailchimp';
			arr['plgName'] = 'mailchimp';
			$.ajax({
				type: "POST",
				async: false,
				url: "index.php?option=com_uniform&view=form&task=form.do_ajax&tmpl=component",
				data: {
					val: JSON.stringify(arr)
				},
				success: function(msg) {
					$('.loadding' + id).hide();
					data = msg;
				}
			})
			return data;
		},
		/*
		 * Show list field in form and Mailchimp
		 * @param array @response
		 * @param string @id
		 * Return html 
		 */
		tableFieldForm: function(respons, id, fieldList, addmore, time) {
			var self = this;
			if (respons !== '') {
				var response = JSON.parse(respons);
				var htl;
				if (typeof time === 'undefined' || time === '') {
					time = addmore;
				}
				htl += '<tr><td><select name="" class="' + id + time + ' input-block-level" data="' + id + time + '"><option value="">--Select Field--</option>';
				$.each(response, function(i, item) {

					var arrs = self.disableFieldDuplicate(id, 'hidform');
					htl += '<option ' + (arrs.indexOf(item.identify) !== -1 ? "disabled" : "") + '  value="' + item.identify + '.' + item.type + '" data="' + item.type + '" >' + item.options.label + '</option>';
					if (item.type === 'name') {
						var arr = JSON.parse(item.options.sortableField);
						$.each(arr, function(key, v) {
							if (v === 'vfirst' && item.options.vfirst === '1') {
								htl += '<option value="first.first"  ' + ("first" === key ? "selected" : (arrs.indexOf("first") !== -1 ? "disabled" : "")) + '>First Name</option>';
							}
							if (v === 'vmiddle' && item.options.vmiddle === '1') {
								htl += '<option value="middle.middle"  ' + ("middle" === key ? "selected" : (arrs.indexOf("middle") !== -1 ? "disabled" : "")) + '>Middle Name</option>';
							}
							if (v === 'vlast' && item.options.vlast === '1') {
								htl += '<option value="last.last"  ' + ("last" === key ? "selected" : (arrs.indexOf("last") !== -1 ? "disabled" : "")) + '>Last Name</option>';
							}
						})
					}
				});
				htl += '</select></td>';
				htl += '<td class="mc-mini-col"><i class="icon-arrow-right"></i></td>';
				htl += '<td class="mailchimp' + id + time + '"><select name="" id="mailchimp' + id + time + '" class="input-block-level" data="' + id + time + '"><option value="">--Select Field--</option>';
				$.each(fieldList, function(i, item) {
					var arrh = self.disableFieldDuplicate(id, 'hidmailchimp');
					htl += '<option value="' + item.tag + '"  ' + (arrh.indexOf(item.tag) !== -1 ? "disabled" : "") + '>' + item.name + '</option>';
				})
				htl += '</select>';

				htl += '<input type="text" id="txt' + id + time + '" value="" style="display:none" class="input-block-level"></td><td class="mc-mini-col"><label class="checkbox inline"><input type="checkbox" id="other' + id + time + '" value=""> Custom</label></td>';
				htl += '<td class="mc-mini-col"><span class="btn btn-small mc_remove" data="' + id + '"><i class="icon-remove"></i></span></td>';
				$('.list' + id).parent().show();
				if (typeof addmore !== 'undefined' && addmore !== '') {
					$('.list' + id).append('<tbody id="' + id + addmore + '"></tbody>');
					$('.list' + id + ' #' + id + addmore).append(htl);
				} else {
					$('.list' + id + ' .listfieldmailchimp').html(htl);
				}
				$('.' + id + time).find('option').each(function() {
					if ($(this).attr('data') === 'name') {
						$(this).remove();
					}
				})
			}
			$('.list' + id).mouseout(function() {
				if ($('#chb' + id).is(':checked')) {
					self.getArrayField(id, 2);
				} else {
					self.getArrayField(id, 1);
				}
				self.selectBoxFormMailChimp(id, time, '.', 'form', 'class');
				self.selectBoxFormMailChimp(id, time, '#mailchimp', 'mailchimp', 'id');
			})
			//select other value for Mailchimp Field
			self.selectFormField(id, time);
			self.otherCheckBox(id, time, '');
			self.textFielduplicate(id, time, fieldList);
		},
		/*
		 * Disable option has been used
		 * @param string @id
		 * @param string @str
		 * Return array 
		 */
		disableFieldDuplicate: function(id, str) {
			var hid = $('#' + str + id).val();
			var arr = new Array();
			if (typeof hid !== 'undefined' && hid !== '') {
				hid = JSON.parse(hid);
				arr = Object.keys(hid).map(function(k) {
					return hid[k]
				});
			}
			return arr;
		},
		/*
		 * select other value for Mailchimp Field
		 * @param string @id
		 * @param string @time
		 * Return html 
		 */
		selectFormField: function(id, time) {
			var self = this;
			self.selectBoxFormMailChimp(id, time, '.', 'form', 'class');
			self.selectBoxFormMailChimp(id, time, '#mailchimp', 'mailchimp', 'id');
			$('select.' + id + time).change(function() {
				var vl = $(this).val();
				if (vl !== '') {
					self.checkFieldDuplicate($(this), 'class', id, time, 'form');
				}
			})
			$('select#mailchimp' + id + time).change(function() {
				var vl = $(this).val();
				var txt = $(this).find('option:selected').text();
				var cl = '#' + $(this).attr('id');
				self.checkFieldDuplicate($(this), 'id', id, time, 'mailchimp');
			})
		},
		selectBoxFormMailChimp: function(id, time, str, lb, cl) {
			$('select' + str + id + time).click(function() {
				var hidForm = new Array();
				var hidF = $('#hid' + lb + id).val();
				if (hidF !== '') {
					hidForm = JSON.parse(hidF);
					hidForm = Object.keys(hidForm).map(function(k) {
						return hidForm[k];
					});
				}
				if (lb === 'form') {
					$('.editfield').find('select').each(function() {
						$(this).find('option').each(function() {
							if (!$(this).is(':selected')) {
								var vl = $(this).val();
								vl = vl.split('.');
								vl = vl[0];
								if (hidForm.indexOf(vl) !== -1) {
									$(this).attr('disabled', 'disabled');
								} else {
									$(this).removeAttr('disabled');
								}
							}
						})
					})
				} else {
					$('.editfield').find('select').each(function() {
						$(this).find('option').each(function() {
							if (!$(this).is(':selected')) {
								var vl = $(this).val();
								if (hidForm.indexOf(vl) !== -1) {
									$(this).attr('disabled', 'disabled');
								} else {
									$(this).removeAttr('disabled');
								}
							}
						})
					})
				}
			})
		},
		checkFieldDuplicate: function(e, cl, id, time, str) {
			var hidinput = $('#hid' + str + id).val();
			var arr = {};
			var data;
			var pr = e.parent().parent().parent().parent();
			$(pr).find('select').each(function() {
				if (cl === 'id') {
					if (typeof $(this).attr('id') !== 'undefined') {
						data = $('#mailchimp' + id + time).attr('data');
						if (typeof data !== 'undefined' && data !== '') {
							var cla = $(this).attr('data');
							$(this).find('option:selected').each(function() {
								var vl = $(this).val();
								if (vl !== '') {
									vl = vl.split('.');
									vl = vl[0];
									arr[$(this).text()] = vl;
								}
							})
						}
					}
				}
				if (cl === 'class') {
					if (typeof $(this).attr('class') !== 'undefined' && typeof $(this).attr('id') === 'undefined') {
						data = $('.' + id + time).attr('data');
						if (typeof data !== 'undefined' && data !== '') {
							var cla = $(this).attr('data');
							$(this).find('option:selected').each(function() {
								var vl = $(this).val();
								if (vl !== '') {
									vl = vl.split('.');
									vl = vl[0];
									arr[$(this).text()] = vl;
								}
							})
						}
					}
				}
			})
			$('#hid' + str + id).val(JSON.stringify(arr));
		},
		/*
		 * Load field in form and Mailchimp saved
		 * @param array @response
		 * @param array @arr
		 * @param array @fieldList
		 * @param string @id
		 * Return html 
		 */
		loadTableFieldForm: function(respons, id, fieldList, addmore, arr, tm) {
			var self = this;
			var i = 0;
			$.each(arr['identify'], function(k, v) {
				i++;
				var d = new Date();
				var time = d.getTime();
				if (i === 1) {
					time = tm;
				}
				if (respons !== '') {
					var response = JSON.parse(respons);
					var htl;
					if (typeof addmore !== 'undefined' && addmore !== '') {
						time = addmore;
					}
					htl += '<tr><td><select name="" class="' + id + time + ' input-block-level" data="' + id + time + '"><option value="">--Select Field--</option>';
					$.each(response, function(i, item) {
						var arrs = self.disableFieldDuplicate(id, 'hidform');
						htl += '<option value="' + item.identify + '.' + item.type + '"  ' + (item.identify === k ? "selected" : (arrs.indexOf(item.options.label) !== -1 ? "disabled" : "")) + ' data="' + item.type + '">' + item.options.label + '</option>';
						if (item.type === 'name') {
							var arr = JSON.parse(item.options.sortableField);
							$.each(arr, function(key, v) {
								if (v === 'vfirst' && item.options.vfirst === '1') {
									htl += '<option fdf value="first.first"  ' + ("first" === k ? "selected" : (arrs.indexOf("First Name") !== -1 ? "disabled" : "")) + '  >First Name</option>';
								}
								if (v === 'vmiddle' && item.options.vmiddle === '1') {
									htl += '<option value="middle.middle"  ' + ("middle" === k ? "selected" : (arrs.indexOf("Middle Name") !== -1 ? "disabled" : "")) + ' >Middle Name</option>';
								}
								if (v === 'vlast' && item.options.vlast === '1') {
									htl += '<option value="last.last"  ' + ("last" === k ? "selected" : (arrs.indexOf("Last Name") !== -1 ? "disabled" : "")) + ' >Last Name</option>';
								}
							})
						}
					});
					htl += '</select></td>';
					htl += '<td class="mc-mini-col"><i class="icon-arrow-right"></i></td>';

					htl += '<td class="mailchimp' + id + time + '">';
					htl += '<select name="" class="input-block-level ' + (typeof arr['new'][k] !== 'undefined' && arr['new'][k] !== '' ? "hideSelect" : "") + '" id="mailchimp' + id + time + '" data="' + id + time + '"><option value="">--Select Field--</option>';
					$.each(fieldList, function(i, item) {
						var arrh = self.disableFieldDuplicate(id, 'hidmailchimp');
						htl += '<option value="' + item.tag + '" ' + (item.tag === v ? "selected" : (arrh.indexOf(item.name) !== -1 ? "disabled" : "")) + '>' + item.name + '</option>';
					})
					htl += '</select>';
					if (typeof arr['new'][k] !== 'undefined' && arr['new'][k] !== '') {
						var tagmage = '';
						var tags = self.removeCharacter(arr['new'][k]);
						$.each(fieldList, function(i, item) {
							if (tags === item.tag) {
								tagmage = item.tag;
							}
						})
						htl += '<input type="text" id="txt' + id + time + '" class="input-block-level" value="' + arr['new'][k] + '" style="display:block"></td><td class="mc-mini-col"><label class="checkbox inline"><input type="checkbox" checked id="other' + id + time + '" value="' + tagmage + '">Custom</label></td>';
					} else {
						htl += '<input type="text" id="txt' + id + time + '" value="" style="display:none" class="input-block-level"></td><td class="mc-mini-col"><label class="checkbox inline"><input type="checkbox" id="other' + id + time + '" value="">Custom</label></td>';
					}
					htl += '<td class="mc-mini-col"><span class="btn btn-small mc_remove" data="' + id + '"><i class="icon-remove"></i></span></td></tr>';
					$('.list' + id).parent().show();
					if (typeof addmore !== 'undefined' && addmore !== '') {
						$('.list' + id).append('<tbody id="' + id + addmore + '"></tbody>');
						$('.list' + id + ' #' + id + addmore).append(htl);
					} else {
						if (i === 1) {
							$('.list' + id + ' .listfieldmailchimp').append(htl);
						} else {
							$('.list' + id).append('<tbody id="' + id + time + '"></tbody>');
							$('.list' + id + ' #' + id + time).append(htl);
						}
					}
					$('.' + id + time).find('option').each(function() {
						if ($(this).attr('data') === 'name') {
							$(this).remove();
						}
					})
				}
				$('.list' + id).mouseout(function() {
					if ($('#chb' + id).is(':checked')) {
						self.getArrayField(id, 2);
					} else {
						self.getArrayField(id, 1);
					}
				})
				self.removeMapFied();
				//select other value for Mailchimp Field
				self.selectFormField(id, time);
				self.otherCheckBox(id, time, '');
				self.textFielduplicate(id, time, fieldList);
			})
		},
		textFielduplicate: function(id, time, fieldList) {

			$('#txt' + id + time).change(function() {
				var name = $('#txt' + id + time).val();
				var arr = new Array();
				if (name !== '') {
					$.each(fieldList, function(i, item) {
						arr[item.name] = item.name;
					})
					$('.list' + id).find('input[type=text]').each(function() {
						var txtid = $(this).attr('id');
						var vl = $(this).val();
						if (vl !== '' && txtid !== 'txt' + id + time) {
							arr[vl] = vl;
						}
					})
					var arrs = Object.keys(arr).map(function(k) {
						return arr[k];
					});
					if (arrs.indexOf(name) !== -1) {
						$('#i' + id + time).remove();
						$('<span id="i' + id + time + '"><i></br>Field name already exists</i></span>').insertAfter($('#txt' + id + time));
						$('#i' + id + time).css({'line-height': '2', 'color': 'red'});
						$('#txt' + id + time).focus();
						$('#txt' + id + time).val('');
					} else {
						$('#i' + id + time).remove();
					}
				}
			})
		},
		/*
		 * Remove field mapping
		 *
		 * Return void 
		 */
		removeMapFied: function() {
			var self = this;
			$('.listmailchimp').find('tbody').each(function() {
				var id = $(this).attr('id');
				if (typeof id !== 'undefined' && id !== '') {
					$(this).mouseover(function() {
						$(this).find('.mc_remove').each(function() {
							$(this).css('opacity', '1');
							$(this).click(function() {
								self.clickRemove($(this), id);
							})
						})
					})
					$(this).mouseout(function() {
						$(this).find('.icon-cancel').each(function() {
							$(this).css('opacity', '0');
						})
					})
				}
			})
		},
		clickRemove: function(e, id) {
			var self = this;
			var data = e.attr('data');
			var hidmailchimp = $('#hidmailchimp' + data).val();
			var hidform = $('#hidform' + data).val();
			var formField = $('.' + id).find('option:selected').attr('value');
			formField = formField.split('.');
			formField = formField[0];
			var mailchimpField = $('#mailchimp' + id).find('option:selected').attr('value');
			if (formField !== '') {
				if (typeof hidform !== 'undefined' && hidform !== '') {
					hidform = JSON.parse(hidform);
					var arr = Object.keys(hidform).map(function(k) {
						return hidform[k];
					});
					if (arr.indexOf(formField) !== -1) {
						arr.splice(arr.indexOf(formField), 1);
					}
					if (arr.length > 0) {
						$('#hidform' + data).val(JSON.stringify(arr));
					} else {
						$('#hidform' + data).val('');
					}
				}
			}
			if (mailchimpField !== '') {
				if (typeof hidmailchimp !== 'undefined' && hidmailchimp !== '') {
					hidmailchimp = JSON.parse(hidmailchimp);
					var arr = Object.keys(hidmailchimp).map(function(k) {
						return hidmailchimp[k];
					});
					if (arr.indexOf(mailchimpField) !== -1) {
						arr.splice(arr.indexOf(mailchimpField), 1);
					}
					if (arr.length > 0) {
						$('#hidmailchimp' + data).val(JSON.stringify(arr));
					} else {
						$('#hidmailchimp' + data).val('');
					}
				}
			}
			self.getArrayField(data, 1);
			if (typeof $(this).attr('class') !== 'undefined' && $(this).attr('class') !== '') {
				$('#' + id).empty();
			} else {
				$('#' + id).remove();
			}
		},
		/*
		 * Add more field mapping to Mailchimp
		 * @param string @id
		 * @param string @time
		 * Return void 
		 */
		otherCheckBox: function(id, time) {
			var self = this;
			$('.list' + id).find('input[type=checkbox]').each(function() {
				$('#other' + id + time).click(function() {
					if ($(this).is(':checked')) {
						self.disableSelectBox($(this), '', '', 0);
						$('#txt' + id + time).show();
					} else {
						self.disableSelectBox($(this), '', '', 1);
						$('#txt' + id + time).hide();
						$('#txt' + id + time).val('');
					}
				})
			})
		},
		/*
		 * Disable select box
		 * @param object @e
		 * @param string @id
		 * @param string @time
		 * @param int @c
		 * Return void 
		 */
		disableSelectBox: function(e, id, time, c) {
			var otherId;
			if (id === '' && time === '') {
				otherId = e.attr('id');
				otherId = otherId.split('other');
				otherId = otherId[1];
			} else {
				otherId = id + time;
				$('.mailchimp' + otherId).find('#mailchimp' + otherId).each(function() {
					$(this).addClass('hideSelect');
				})
			}
			$('.mailchimp' + otherId).find('#mailchimp' + otherId).each(function() {
				if (c === 1) {
					$(this).removeClass('hideSelect');
				} else {
					$(this).addClass('hideSelect');
				}
			})
		},
		/*
		 * Add more field mapping to Mailchimp
		 * @param array @response
		 * @param string @id
		 * @param array @fieldList
		 * Return html 
		 */
		addMoreFieldMapping: function() {
			var self = this;
			var id;
			$('.listmailchimp').find('.addmoreField').each(function() {
				$(this).click(function() {
					id = $(this).attr('data');
					var respons = $('#responseFieldDesign').val();
					var fieldList = $('#fieldList' + id).val();
					fieldList = JSON.parse(fieldList);
					var d = new Date();
					var addmore = d.getTime();
					self.tableFieldForm(respons, id, fieldList, addmore);
					self.removeMapFied();
				})
			})
		},
		/*
		 * Select Yes/No to ues Mailchimp
		 * 
		 * Return void 
		 */
		useMailchimp: function() {
			var self = this;
			var jform = $('#jform_form_mailchimp').val();
			$('.use_mailchimp').click(function() {
				if ($(this).text() === 'No') {
					$('.choiseYes').removeClass('choiseYes');
					$(this).addClass('choiseNo');
					$('.usemailchimp').hide();
					$('.listmailchimp').empty();
					$('#hidUseMailchimp').val(0);

					if (typeof jform !== 'undefined' && jform !== '') {
						var arr = JSON.parse(jform);
						arr['useMailchimp'] = 0;
						$('#jform_form_mailchimp').val(JSON.stringify(arr));
					}
				}
				if ($(this).text() === 'Yes') {
					$('.choiseNo').removeClass('choiseNo');
					$(this).addClass('choiseYes');
					$('.usemailchimp').show();
					$('#hidUseMailchimp').val(1);
					if (typeof jform !== 'undefined' && jform !== '') {
						var arr = JSON.parse(jform);
						if (arr['useMailchimp'] === 0) {
							self.loadDataMailchimp(1);
							arr['useMailchimp'] = 1;
							$('#jform_form_mailchimp').val(JSON.stringify(arr));
						}
					}
				}
			})
		}
	}
	window.addEvent('domready', function() {
		JSNMailchimp.initialize();
	});
})(jQuery);

