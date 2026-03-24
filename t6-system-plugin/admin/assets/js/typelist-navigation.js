(function($) {
	var Typelists = window.Typelists || {};

	var Navigation = function($el){
		Navigation.$container = $el;
		Navigation.$default = {
			name :'none',
			col : 'auto',
			type: ""
		};
	}

	Navigation.prototype.init = function () {

	}

	Navigation.prototype.get = function () {
		// find all fields and get value
		var $inputs = Navigation.$container.find('[name^="typelist-navigation["]');
		var result = {};
		$inputs.each(function(i, el){
			var $input = $(el),
				name = el.name.match(/typelist-navigation\[(.*)\]/)[1],
				value = $input.val();
				if(name == 'custom_colors'){
					value = JSON.stringify(Navigation.prototype.generatedUserColor());
				}
				/*if(name == 'navigation_mega_settings'){
					value = JSON.stringify(Navigation.prototype.generatedJSON());
				}*/
				if($input.attr('type') == 'checkbox'){
					value = $input.prop('checked');
				}
			result[name] = value;

		})
		return result;
	}

	Navigation.prototype.set = function (value) {
		var $inputs = Navigation.$container.find('[name^="typelist-navigation["]');
		$inputs.each( function(i, el) {
			var $input = $(el), name = el.name.match(/typelist-navigation\[(.*)\]/)[1];
			if (!value[name]) value[name] = '';
			if(name == 'mega_settings'){
				if(!value['menu_type']) value['menu_type'] = 'mainmenu';
				if(value[name]) Navigation.prototype.updateMegamenu(value[name],value['menu_type']);
			}
			if($input.is('select')){
				$input.chosen({with:'100%'});
				$input.val(value[name]).trigger('liszt:updated');
				$input.val(value[name]).trigger('chosen:updated');
				$input.trigger('change');
			}else if($input.attr('type') == 'checkbox'){
				$input.val(value[name]).prop('checked',value[name]);
			}else{
				$input.val(value[name]).trigger('change');
			}
			
		})
	}

	Navigation.prototype.generatedJSON =  function(){
		var t6item = {},
		megamenuType = $('.menu_type').val();
		$('.t6-menu-layout-builder').find('.t6-megamenu').each(function(index){
			// var $megamenu = {};
			var menutypes = $(this);
			var $itemType = menutypes.data('type');
			var $itemType = menutypes.data('type');
			// if($itemType == megamenuType){
				t6item[$itemType] = {};
				// Find menutypes Elements
				if(menutypes.find('.t6-menu-items').length){
					menutypes.find('.t6-menu-items').each(function(index) {
						var $itemsMenu = {};
						var type = $(this);
						var typeObj = type.data();
						// if(type.find('.t6-input-check-mega').getInputValue() == 1){
							var $itemsId = typeObj.itemid;
							t6item[$itemsId] = {};
							var itemsIndex = index;
							type.find('.t6-menu-item').each(function(index){
								var itemsIndex = index,
									items = $(this),
									dataItems = items.data();
									if(typeof dataItems.align == 'undefined'){
										dataItems.align = 'left';
									}
								t6item[$itemsId] = $.extend({

										'mega_extra'	: dataItems.mega_extra,
										'extra'			: dataItems.extra,
										'icons'			: dataItems.icons,
										'caption'		: dataItems.caption,
										'width'			: dataItems.width,
										'megabuild'		: dataItems.megabuild,
										'align'			: dataItems.align,
										'settings'		: []
									},dataItems);
								delete t6item[$itemsId].sortableitem;
								delete t6item[$itemsId].sortableItem;
								delete t6item[$itemsId].uiSortable;
								delete t6item[$itemsId].uisortable;
								items.find('.t6-mega-section').each(function(index){
									var itemIndex = index,
										item = $(this),
										itemObj = item.data();
									t6item[$itemsId].settings[itemIndex] = {
											'contents'	:[],
										};
									item.find('.t6-mega-col').each(function(index){
										var dataItem = $(this).data();
										var dataIndex = index;
										if(dataItem.name != 'none'){
											t6item[$itemsId].settings[itemIndex].contents[dataIndex] = $.extend({
												"name" : dataItem.name,
												"type" : dataItem.type,
												"style" : dataItem.style,
												"title" : dataItem.title,
												"col" : dataItem.col,
											},dataItem);
											if(dataItem.type == 'module'){
												t6item[$itemsId].settings[itemIndex].contents[dataIndex].modname = dataItem.modname;
												t6item[$itemsId].settings[itemIndex].contents[dataIndex].module_id = dataItem.module_id;
											}else if(dataItem.type == 'position'){
												t6item[$itemsId].settings[itemIndex].contents[dataIndex].position = dataItem.position;
											}else if(dataItem.type == 'items'){
												t6item[$itemsId].settings[itemIndex].contents[dataIndex].items = dataItem.items;
											}
											delete t6item[$itemsId].settings[itemIndex].contents[dataIndex].sortableItem;
											delete t6item[$itemsId].settings[itemIndex].contents[dataIndex].sortableitem;
											delete t6item[$itemsId].settings[itemIndex].contents[dataIndex].uiSortable;
											delete t6item[$itemsId].settings[itemIndex].contents[dataIndex].uisortable;
										}
									});
								});
							});
						// }
					});
				}
			// }

		});
		return t6item;
	}
	Navigation.prototype.updateMegamenu = function($megaVal,$menuType){
		var $megaBuilder = $('.t6-menu-layout-builder');
		$data = JSON.parse($megaVal);
		Navigation.prototype.renderMegaMenu($data,$menuType);
		if(typeof $lastMenuItemActive != 'undefined'){
			$lastMenuItemActive.trigger('click');
		}else{
			$(".menu-item.item-active").trigger('click');
		}
		T6AdminMegamenu.MegaMenuUiLayout();
	}
	Navigation.prototype.renderAttrs = function($data){
		var $dataAttr = '';
		$.each($data,function(index,value){
			if(index != 'settings'){
				$dataAttr += ' data-'+index+'="'+value+'"';
			}
		});
		return $dataAttr;
	}
	//$mega
	Navigation.prototype.renderMegaMenu = function($mega,$menuType){
		var alignData = ['left','right','center'];
		var $items = [];
		if(typeof $mega  == 'object' && typeof $mega[$menuType] == 'object'){
			$items = Object.keys($mega[$menuType]);
		}
		if($items.length){
			for (var i = 0; i < $items.length; i++) {
				var $dataItem = $mega[$menuType][$items[i]],
						$layoutMega = '',
						$megabuild = $dataItem.megabuild ? $dataItem.megabuild : "0",
						$style = "style='display:none;'", $checked = '';

				if(typeof $megabuild != 'undefined' && $megabuild != '0'){
					$style = "style='display:block;'";
					$checked = "checked='checked'";
				}
				// $layoutMega += '<div class="t6-menu-items itemid-'+$items[i]+'" data-itemid="'+$items[i]+'" '+$style+'>';
				$layoutMega += '<div class="enablemega t6-itemid-'+$items[i]+'">';
				$layoutMega += '<label for="megamenu">Build Mega Menu</label>';
				$layoutMega += '<input id="megamenu" class="t6-item t6-input t6-input-check-mega" type="checkbox" name="megabuild" data-attrname="megabuild" value="'+$megabuild+'" '+$checked+'></div>';
				$layoutMega += '<div class="item-mega-config" '+$style+'>';
				$layoutMega += '<div class="item-mega-width"><label class="item-width" for="width">'+T6Admin.langs.megamenuSubmenuWidth+'</label>';
				$layoutMega += '<input id="width" type="text" placeholder="300px" class="t6-item t6-item-width" name="item-width" value="'+$dataItem.width +'"></div>';
				$layoutMega += '<div id="mega-extra" class="mega-extra-class">';
				$layoutMega += '<label for="megaextra">'+T6Admin.langs.megamenuExtraClass+'</label>';
				$layoutMega += '<input id="mega_extra" type="text" name="mega_extra" value="'+$dataItem.extra +'" class="t6-item t6-mega-extra-class" aria-invalid="false"></div>';
				$layoutMega += '<div class="item-mega-align"><label class="item-align">'+T6Admin.langs.megamenuAlignment+'</label>';
				$layoutMega += '<div class="t6-item btn-group">';
				for (var n = 0; n < alignData.length; n++) {
					var classes = (alignData[n] == $dataItem.align) ? 'active' : '';
					$layoutMega += '<a class="btn t6-item-align-'+alignData[n]+' t6-item-action '+classes+'" href="#" data-action="alignment" data-align="'+alignData[n]+'" title="'+alignData[n]+'"><i class="fal fa-align-'+alignData[n]+'"></i></a>';
				}
				$layoutMega += '</div></div></div>';
				var $dataAttrs = Navigation.prototype.renderAttrs($dataItem);
				$layoutMega += '<div class="t6-menu-item" '+$style+' '+$dataAttrs+'>';
				var $dataSection = $mega[$menuType][$items[i]].settings;
				for (var j = 0; j < $dataSection.length; j++) {
					
					$layoutMega += '<div class="t6-mega-section" data-layout="12" data-cols="1">';
					$layoutMega += '<div class="t6-meganeu-settings clearfix">';
					$layoutMega += '<div class="pull-right"><ul class="t6-row-option-list">';
					$layoutMega += '<li><a class="t6-move-row" href="#"><i class="fal fa-arrows-alt"></i></a></li>';
					$layoutMega += '<li><a class="t6-meganeu-row-options" href="#"><i class="fal fa-cog fa-fw"></i></a></li>';
					$layoutMega += '<li><a class="t6-remove-row-mega" href="#"><i class="fal fa-trash-alt fa-fw"></i></a></li>';
					$layoutMega += '</ul></div></div>';
					$layoutMega += '<div class="t6-row-container"><div class="row">';
					var $dataContents = $dataSection[j].contents;
					if(!$dataContents.length) $dataContents.push(Navigation.$default);
					for (var k = 0; k < $dataContents.length; k++) {
						var $dataAttr = Navigation.prototype.renderAttrs($dataContents[k]);
						var nameTitle = $dataContents[k].name ? $dataContents[k].name : "none";
						$layoutMega += '<div class="t6-col t6-mega-col col-md" '+$dataAttr+'>';
						$layoutMega += '<div class="col-inner item-build clearfix"><span class="t6-column-title">'+nameTitle+'</span><span class="t6-col-remove hidden" title="Remove column" data-content="Remove column"><i class="fal fa-minus"></i> </span><a class="t6-item-options" href="#"><i class="fal fa-cog fa-fw"></i></a></div>';
						$layoutMega += '</div>';
					}
					$layoutMega += '</div></div></div>';
				}
				$layoutMega += '</div>';
				$layoutMega += '<div class="t6-menu-add-row" '+$style+'><a class="" href="#"><i class="fal fa-plus-circle"></i><span>Add Row</span></a></div>';//</div>
				$('.t6-menu-items.itemid-'+$items[i]).html($layoutMega);
			}
		}
		return true;
	}
	Typelists['navigation'] = Navigation;
	window.Typelists = Typelists;
})(jQuery);
