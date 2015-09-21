/**
 * @subpackage	jsnpoweradmin (JSN POERADMIN JoomlaShine - http://www.joomlashine.com)
 * @copyright	Copyright (C) 2001 BraveBits,Ltd. All rights reserved.
 **/

(function($){
	$.com_k2_item_item = function(Itemid){
		/**
	 	 * Variable to store context menu
	 	 */
		this.contextMenu;
		this.contextElements;
		this.currentElement;
		/**
		 * Init all variables
		 */
		this.initVariables = function(){
			
			this.setData( 'option', 'com_k2' );
			this.setData( 'view', 'item' );
			this.setData( 'layout', 'item' );
			this.setData( 'id', $('#article_id').val()  );
			this.setData( 'Itemid', Itemid );
			this.setData( 'requestType', 'only' );			
			//Scan elements approved context menu
			this.contextElements = new Array( $( '.'+this.classApprovedContextMenu ).length );
			
			var $this = this;
			$( '.'+this.classApprovedContextMenu ).each(function(){
//				if ( $(this).parents('div.category').length || $(this).hasClass('category-desc') || $(this).hasClass('empty-category')){
//					$(this).data('edit-category', true);
//				}else{
//					$(this).data('edit-category', false);
//				}
//				if ( $(this).hasClass('article-tablist')){
//					$(this).data('set-content-layout', true);
//				}else{
//					$(this).data('set-content-layout', false);
//				}
//				if ( $(this).hasClass('cat-children')){
//					$(this).data('set-subcategories', true);
//				}else{
//					$(this).data('set-subcategories', false);
//				}
				if ( $(this).hasClass('display-default') ){
					$(this).data('show', true);
				}else{
					$(this).data('show', false);
				}				
				$this.contextElements[$(this).attr('id')] = $(this);
			});
		};
		
		/**
		 * Ajax request task function
		 */
		this.beforeAjaxRequest = function(task){
			this.currentElement.showImgStatus({status : 'request'});
//			if (this.currentElement.parents('div.article_layout').length || this.currentElement.parents('div.cat-children').length){
//				this.setData('prefix_params', true);
//			}else{
//				this.setData('prefix_params', false);
//			}
			this.setData( 'requestTask', task );
			this.ajaxRequest();
		};
		
		this.addContextMenu = function(){
			this.contextMenu = this.getContextMenu();
			var $this = this;			
			if ( this.contextMenu != null ){
				
				if ( $this.contextMenu.isNew() ){
					/**
					 * 
					 * Add menu for editing the article
					 */
					$this.contextMenu.addItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_EDIT_ARTICLE') ).addEventHandler("click", function(){
						$this.contextMenu.hide({});
						$this.editArticle();
					});
					
					/**
					 * 
					 * Add menu for editing the category
					 */
					$this.contextMenu.addItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_EDIT_CATEGORY') ).addEventHandler("click", function(){
						$this.contextMenu.hide({});
						$this.editCategory();
					});
					
					/**
					 * 
					 * Menu for showing element
					 */
					
					$this.contextMenu.addItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_SHOW_ARTICLE') ).addEventHandler("click", function(){
						$this.contextMenu.hide({});
						$this.setData( 'requestType', 'only' );
						$this.setParams( $this.currentElement.attr('id'), 1);
						$this.beforeAjaxRequest();
					});
					
					$this.contextMenu.addItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_HIDE_ARTICLE') ).addEventHandler("click", function(){
						$this.contextMenu.hide({});
						$this.setData( 'requestType', 'only' );
						$this.setParams( $this.currentElement.attr('id'), 0);
						$this.beforeAjaxRequest();
					});	
					
					$this.contextMenu.addItem(JSNLang.translate('JSN_RAWMODE_COMPONENT_K2_MOVE_ABOVE') ).addEventHandler("click", function(){
						$this.contextMenu.hide({});						
						$this.setParams( $this.currentElement.attr('id'), 'above');
						$this.beforeAjaxRequest();
					});	
					
					$this.contextMenu.addItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_K2_MOVE_BELOW')).addEventHandler("click", function(){
						$this.contextMenu.hide({});
						$this.setParams( $this.currentElement.attr('id'), 'below');
						$this.beforeAjaxRequest();
					});	
					
				}
			
				$this.container.unbind("mousedown").mousedown(function(e){					
					if ($(e.target).hasClass($this.classApprovedContextMenu.replace('.', ''))){
						$this.currentElement = $(e.target);
					}else{
						$this.currentElement = $(e.target).parents('.'+$this.classApprovedContextMenu);
					}
					var tagId = ( $this.currentElement.attr('id') != undefined ? $this.currentElement.attr('id') : '' );
					
					if ( e.which == 1 && $this.contextElements[tagId] != undefined ){
						$this.contextMenu.hideItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_EDIT_ARTICLE'));
						$this.contextMenu.hideItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_EDIT_CATEGORY'));
						$this.contextMenu.hideItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_SHOW_ARTICLE'));
						$this.contextMenu.hideItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_HIDE_ARTICLE'));
						$this.contextMenu.hideItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_K2_MOVE_ABOVE'));
						$this.contextMenu.hideItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_K2_MOVE_BELOW'));
						
						var current = $this.contextElements[tagId];
						
						
						if(current.attr('id') == 'commentsFormPosition'){
							if ( current.attr('data') =='below' ){
								$this.contextMenu.showItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_K2_MOVE_ABOVE'));
							}else{
								$this.contextMenu.showItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_K2_MOVE_BELOW'));
							}
						}else if(current.attr('id') == 'itemIntroText'){
							$this.contextMenu.showItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_EDIT_ARTICLE'));
						}else if(current.attr('id') == 'itemCategoryName'){
							$this.contextMenu.showItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_EDIT_CATEGORY'));
						}else{
							if ( current.data('show') ){
								$this.contextMenu.showItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_HIDE_ARTICLE'));
							}else{
								$this.contextMenu.showItem( JSNLang.translate('JSN_RAWMODE_COMPONENT_SHOW_ARTICLE'));
							}
						}
												
						// Build context menu position.
						$this.contextMenu.show({
							x : e.pageX,
							y : e.pageY
						});
						
						// IMPORTANT - trigger show event.
						$this.contextMenu.trigger('component.context.show');
					}else{
						$this.currentElement = $({});
					}
				});
			}
		}
		

		this.editArticle = function (){
			$this = this;
			var wWidth  = $(window).width()*0.85;
			var wHeight = $(window).height()*0.8;
			var pop = $.JSNUIWindow
			(
				baseUrl+'administrator/index.php?option=com_k2&view=item&tmpl=component&cid='+$this.getData('id'),
				{
					modal  : true,
					width  : wWidth, 
					height : wHeight,
					scrollContent: true,
					title  : JSNLang.translate( 'JSN_RAWMODE_COMPONENT_EDIT_ARTICLE_PAGE_TITLE' ),
					open   : function(){									
						var _this  = $(this);
						var iframe = $(this).find('iframe');
						iframe.load(function(){
							setTimeout(function(){
								if ( iframe[0].contentWindow != undefined ){									
									var form = iframe.contents().find('form');
									form.attr('action','index.php');
								}
							}, 400);
						});
						//bind trigger press enter submit form from child page
						$(window).unbind("pressEnterSubmitForm").bind("pressEnterSubmitForm", function(){
							iframe.load(function(){								
								$this.beforeAjaxRequest('brankNewData');
							});
						});
					},
					buttons: {									
						'Save': function(){
							var _this  = $(this);
							var iframe = $(this).find('iframe');
							_this.addClass('jsn-loading');

							if ( pop.submitForm('apply', 'Save') ){
								iframe.load(function(){
									$this.beforeAjaxRequest('brankNewData');
									_this.removeClass('jsn-loading');
									_this.dialog("close");
								});
							}
						},
						'Close': function(){
							$(this).dialog("close");
						}
					}
				}
			);
		}
		
		this.editCategory = function (){
			$this = this;
			var wWidth  = $(window).width()*0.85;
			var wHeight = $(window).height()*0.8;
			var pop = $.JSNUIWindow
			(
				baseUrl+'administrator/index.php?option=com_k2&view=category&tmpl=component&cid='+$this.getData('id'),
				{
					modal  : true,
					width  : wWidth, 
					height : wHeight,
					scrollContent: true,
					title  : JSNLang.translate( 'JSN_RAWMODE_COMPONENT_EDIT_CATEGORY_PAGE_TITLE' ),
					open   : function(){									
						var _this  = $(this);
						var iframe = $(this).find('iframe');
						iframe.load(function(){
							setTimeout(function(){
								if ( iframe[0].contentWindow != undefined ){									
									var form = iframe.contents().find('form');
									form.attr('action','index.php');
								}
							}, 400);
						});
						//bind trigger press enter submit form from child page
						$(window).unbind("pressEnterSubmitForm").bind("pressEnterSubmitForm", function(){
							iframe.load(function(){								
								$this.beforeAjaxRequest('brankNewData');
							});
						});
					},
					buttons: {									
						'Save': function(){
							var _this  = $(this);
							var iframe = $(this).find('iframe');
							_this.addClass('jsn-loading');

							if ( pop.submitForm('apply', 'Save') ){
								iframe.load(function(){
									$this.beforeAjaxRequest('brankNewData');
									_this.removeClass('jsn-loading');
									_this.dialog("close");
								});
							}
						},
						'Close': function(){
							$(this).dialog("close");
						}
					}
				}
			);
		}
	}
})(JoomlaShine.jQuery);
