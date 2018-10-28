/**
 * Any List widget frontend JS functions
 *
 * @author		Nir Goldberg
 * @package		js
 * @version		1.0
 */
jQuery( function($) {

	// general
	$('.anylist-filter-title').bind('click', anylist_filter_content_toggle);

	// collapse first filter
	$('.anylist-filter').first().children('.anylist-filter-title').addClass('collapsed');

	// append not found message
	var msg = '<div class="anylist-not-found" style="display: none;">' + _ANYLIST_products_filter_not_found +  '</div>';
	$('ul.products').after(msg);

	// categories menu
	if (_ANYLIST_products_filter_show_categories_menu) {
		// toggle subcategories menu on click event
		$('.anylist-category-filter li.has-children > .item-before').bind('click', anylist_subcategories_menu_toggle);
		$('.anylist-category-filter li.has-children > a').bind('click', anylist_subcategories_menu_toggle);

		// change category selection
		$('.anylist-category-filter input').bind('change', anylist_map_categories_menu);
	}

	// price filter
	if (_ANYLIST_products_filter_show_price_filter) {
		// set price filter init status to false
		_ANYLIST_products_filter_price_filter_init = false;

		if (_ANYLIST_products_filter_min_price != _ANYLIST_products_filter_max_price) {
			anylist_init_price_slider(_ANYLIST_products_filter_min_price, _ANYLIST_products_filter_max_price, _ANYLIST_products_filter_min_handle_price, _ANYLIST_products_filter_max_handle_price);

			// set price filter init status to true
			_ANYLIST_products_filter_price_filter_init = true;
		}
		else {
			// hide price filter completely
			$('.anylist-price-filter').hide();
		}
	}

	// taxonomy filters
	$('.anylist-tax-filter .tax-terms input').bind('change', anylist_map_taxonomy_terms);

});

/**
 * anylist_filter_content_toggle
 *
 * Toggle filter content
 *
 * @since		1.0
 * @param		event (object)
 * @return		N/A
 */
function anylist_filter_content_toggle(event) {

	var current = event.currentTarget,
		active = $(current).hasClass('collapsed') ? true : false;

	if (active) {
		$(current).removeClass('collapsed');
	}
	else {
		$(current).addClass('collapsed');
	}

}

/**
 * anylist_subcategories_menu_toggle
 *
 * Toggle subcategories menu
 *
 * @since		1.0
 * @param		event (object)
 * @return		N/A
 */
function anylist_subcategories_menu_toggle(event) {

	var current = event.currentTarget,
		li = $(current).parent(),
		active = li.hasClass('collapsed') ? true : false;

	if (active) {
		li.removeClass('collapsed').find('li.has-children').removeClass('collapsed');
	}
	else {
		li.addClass('collapsed');
	}

}

/**
 * anylist_map_categories_menu
 *
 * Modify subcategories input checked values according to last change event
 *
 * @since		1.0
 * @param		event (object)
 * @return		N/A
 */
function anylist_map_categories_menu(event) {

	var current = event.currentTarget,
		id		= $(current).attr('id').substring(12),		// input ID
		all		= id.indexOf('all') != -1 ? true : false,	// "all categories" input indication
		item_id	= all ? id.substring(0, id.length-4) : id,	// actual category ID
		checked	= $(current).is(':checked'),				// input new state
		filter	= false;									// indication if products filter should be taken

	// locate item parent id 
	var parent_id = anylist_categories_menu_get_item_parent(item_id);

	if (checked) {
		// item checked
		// uncheck parents
		anylist_categories_menu_uncheck_parents(item_id, parent_id);

		if (all) {
			// uncheck children
			anylist_categories_menu_uncheck_children(item_id);
		}

		// set categories filter current input attribute
		if (parent_id !== false) {
			_ANYLIST_products_filter_categories[parent_id][item_id][1] = true;
			filter = true;
		}
	}
	else {
		// item unchecked
		// check if no other item input is checked
		if ( anylist_categories_menu_is_single_checked_item(item_id) ) {
			// can't uncheck a single checked input - recheck
			$(current).prop('checked', true);
		}
		else {
			// unset categories filter current input attribute
			if (parent_id !== false) {
				_ANYLIST_products_filter_categories[parent_id][item_id][1] = false;
				filter = true;
			}
		}
	}

	if (filter) {
		// get category checked input IDs
		var checked_categories = anylist_get_checked_categories();

		anylist_filter_products_ajax(checked_categories);
	}

}

/**
 * anylist_categories_menu_get_item_parent
 *
 * Get item parent ID
 *
 * @since		1.0
 * @param		item_id (int) item ID
 * @return		(mixed) item parent ID or false if no item found
 */
function anylist_categories_menu_get_item_parent(item_id) {

	var p_id = false;

	$.each( _ANYLIST_products_filter_categories, function(parent_id, categories) {
		$.each( categories, function(cat_id, cat_data) {
			if (cat_id == item_id) {
				// item ID found - store item parent ID and break
				p_id = parent_id;
				return false;
			}
		});

		if (p_id) {
			// break
			return false;
		}
	});

	// return
	return p_id;

}

/**
 * anylist_categories_menu_uncheck_parents
 *
 * Recursive function to uncheck all item's parents
 *
 * @since		1.0
 * @param		item_id (int) item ID
 * @param		parent_id (int) parent item ID
 * @return		N/A
 */
function anylist_categories_menu_uncheck_parents(item_id, parent_id) {

	if ( ! parent_id ) {
		parent_id = anylist_categories_menu_get_item_parent(item_id);
	}

	if (parent_id !== false) {
		// uncheck parent item
		anylist_categories_menu_uncheck_item(parent_id);

		// uncheck parent's parents
		anylist_categories_menu_uncheck_parents(parent_id);
	}

}

/**
 * anylist_categories_menu_uncheck_children
 *
 * Recursive function to uncheck all item's children
 *
 * @since		1.0
 * @param		item_id (int) item ID
 * @return		N/A
 */
function anylist_categories_menu_uncheck_children(item_id) {

	$.each( _ANYLIST_products_filter_categories, function(parent_id, categories) {
		if (parent_id == item_id) {
			$.each( categories, function(cat_id, cat_data) {
				// uncheck item
				anylist_categories_menu_uncheck_item(cat_id, parent_id);

				// uncheck item's children
				anylist_categories_menu_uncheck_children(cat_id);
			});
		}
	});

}

/**
 * anylist_categories_menu_uncheck_item
 *
 * Uncheck an item
 *
 * @since		1.0
 * @param		item_id (int) item ID
 * @param		parent_id (int) parent item ID
 * @return		N/A
 */
function anylist_categories_menu_uncheck_item(item_id, parent_id) {

	if ( ! parent_id ) {
		parent_id = anylist_categories_menu_get_item_parent(item_id);
	}

	if (parent_id !== false) {
		// unset categories filter item attribute
		_ANYLIST_products_filter_categories[parent_id][item_id][1] = false;
	}

	// uncheck item's input
	anylist_categories_menu_uncheck_input(item_id);

}

/**
 * anylist_categories_menu_uncheck_input
 *
 * Uncheck an input related to a specified item ID
 *
 * @since		1.0
 * @param		item_id (int) item ID
 * @return		N/A
 */
function anylist_categories_menu_uncheck_input(item_id) {

	var dom_category = $('.anylist-category-filter .categories').find('li.cat-' + item_id);

	if (dom_category.length) {
		if (dom_category.hasClass('has-children')) {
			// parent category
			input = dom_category.find('li.cat-' + item_id + '-all').children('input');
		}
		else {
			// child category
			input = dom_category.children('input');
		}

		if (input.length) {
			input.prop('checked', false);
		}
	}

}

/**
 * anylist_categories_menu_is_single_checked_item
 *
 * Check if a given item is the only one checked
 *
 * @since		1.0
 * @param		item_id (int) item ID
 * @return		(bool)
 */
function anylist_categories_menu_is_single_checked_item(item_id) {

	result = true;	// set true as default

	$.each( _ANYLIST_products_filter_categories, function(parent_id, categories) {
		$.each( categories, function(cat_id, cat_data) {
			if (cat_data[1] && cat_id != item_id) {
				result = false;
				
				// break
				return false;
			}
		});

		if ( ! result ) {
			// break
			return false;
		}
	});

	// return
	return result;

}

/**
 * anylist_get_checked_categories
 *
 * Get category checked input IDs
 *
 * @since		1.0
 * @param		N/A
 * @return		(array) array of category IDs
 */
function anylist_get_checked_categories() {

	var category_ids = [];

	$.each( _ANYLIST_products_filter_categories, function(parent_id, categories) {
		$.each( categories, function(cat_id, cat_data) {
			if (cat_data[1]) {
				category_ids.push(cat_id);
			}
		});
	});

	// return
	return category_ids;

}

/**
 * anylist_filter_products_ajax
 *
 * Update products filter and products grid
 * Used in case of category menu selection change event
 *
 * @since		1.0
 * @param		terms (array) array of term_ids
 * @return		N/A
 */
function anylist_filter_products_ajax(terms) {

	if (typeof terms === "undefined" || terms === null)
		return;

	var loader = $('.anylist-widget .anylist-loader');

	// expose loader
	loader.show();

	$.ajax({

		url		: _ANYLIST_products_filter_ajaxurl,
		type	: 'POST',
		data	: {
			action		: 'filter_products',
			taxonomy	: 'product_cat',
			term_ids	: terms,
			wpml_lang	: _ANYLIST_products_filter_wpml_lang,
			tax_list	: _ANYLIST_products_filter_tax_list
		},
		success: function(result) {

			result = JSON.parse(result);

			// update JS attributes
			_ANYLIST_products_filter_min_price			=  result.min_price;
			_ANYLIST_products_filter_max_price			=  result.max_price;
			_ANYLIST_products_filter_min_handle_price	=  result.min_handle_price;
			_ANYLIST_products_filter_max_handle_price	=  result.max_handle_price;
			_ANYLIST_products_filter_taxonomies		=  result.taxonomies;
			_ANYLIST_products_filter_products			=  result.products;

			// display updated products filter
			anylist_display_products_filter(true);

			// display updated products grid
			anylist_update_products_grid_ajax(result.products_grid);

			// hide loader
			loader.hide();

			// return
			return true;

		},
		error: function(result) {

			// hide loader
			loader.hide();

			// return
			return false;

		}

	});

}

/**
 * anylist_init_price_slider
 *
 * Initiate price filter
 *
 * @since		1.0
 * @param		min_handle (int) price filter minimum price
 * @param		max_handle (int) price filter maximum price
 * @param		value0 (int) price filter minimum handle price
 * @param		value1 (int) price filter maximum handle price
 * @return		N/A
 */
function anylist_init_price_slider(min_handle, max_handle, value0, value1) {

	$('#anylist-price-filter-slider').slider({
		animate	: true,
		range	: true,
		min		: min_handle,
		max		: max_handle,
		values	: [ value0, value1 ],
		slide	: function(event, ui) {
			$('#anylist-price-filter-amount').val( _ANYLIST_products_filter_currency + ui.values[0] + " - " + _ANYLIST_products_filter_currency + ui.values[1] );
		},
		change	: function( event, ui ) {
			anylist_price_slider_change(ui.values[0], ui.values[1]);
		}
	});
	
	$('#anylist-price-filter-amount').val( _ANYLIST_products_filter_currency + $('#anylist-price-filter-slider').slider('values', 0) + " - " + _ANYLIST_products_filter_currency + $('#anylist-price-filter-slider').slider('values', 1) );

}

/**
 * anylist_price_slider_change
 *
 * Update price range after slider change event
 *
 * @since		1.0
 * @param		min (int) minimum handle price
 * @param		max (int) maximum handle price
 * @return		N/A
 */
function anylist_price_slider_change(min, max) {

	_ANYLIST_products_filter_min_handle_price = min;
	_ANYLIST_products_filter_max_handle_price = max;

	anylist_filter_products();

}

/**
 * anylist_map_taxonomy_terms
 *
 * Map taxonomy filters before performing product filtering
 *
 * @since		1.0
 * @param		N/A
 * @return		N/A
 */
function anylist_map_taxonomy_terms() {

	var taxonomy_filters = $('.anylist-tax-filter');
	
	taxonomy_filters.each(function() {
		var taxonomy_filter_class_list = $(this).attr('class').split(/\s+/),
			terms = $(this).find('.tax-terms');
		
		// get taxonomy name
		tax_name = '';
		$.each( taxonomy_filter_class_list, function(index, item) {
			if ( item.indexOf('anylist-tax-filter-') >= 0 ) {
				tax_name = item.substring(16);
			}
		});
		
		// set checked terms
		terms.find('input').each(function() {
			var term_id = $(this).attr('id');
			_ANYLIST_products_filter_taxonomies[tax_name][2][term_id][1] = ($(this).is(':checked')) ? 1 : 0; 
		});
	});
	
	anylist_filter_products();

}

/**
 * anylist_filter_products
 *
 * Update products filter and products grid
 *
 * @since		1.0
 * @param		N/A
 * @return		N/A
 */
function anylist_filter_products() {

	// update products filter
	anylist_update_products_filter();

	// update products grid
	anylist_update_products_grid();

	// return
	return true;

}

/**
 * anylist_update_products_filter
 *
 * Update products filter
 *
 * @since		1.0
 * @param		N/A
 * @return		N/A
 */
function anylist_update_products_filter() {

	_ANYLIST_products_filter_min_price = null;
	_ANYLIST_products_filter_max_price = null;

	// reset _ANYLIST_products_filter_taxonomies counters
	$.each( _ANYLIST_products_filter_taxonomies, function(tax_name, tax_data) {
		tax_data[1] = 0;
		$.each( tax_data[2], function(term_id, term_data) {
			term_data[0] = 0;
		});
	});

	// update _ANYLIST_products_filter_products product visibility and _ANYLIST_products_filter_taxonomies counters
	$.each( _ANYLIST_products_filter_products, function(product_id, product_data) {
		// set all product as visible by default
		product_data[1] = 1;

		// skip products not assosiated with checked taxonomy terms
		$.each(_ANYLIST_products_filter_taxonomies, function(tax_name, tax_data) {
			$.each( tax_data[2], function(term_id, term_data) {
				// Check if taxonomy term is checked and associated with this product 
				if ( term_data[1] && $.inArray(parseInt(term_id), product_data[2][tax_name]) == -1 ) {
					product_data[1] = 0;
					return false;
				}
			});
			if ( ! product_data[1] )
				return false;
		});

		if ( ! product_data[1] )
			return true;

		// update minimum and maximum price
		var price = product_data[0];

		if ( ! _ANYLIST_products_filter_min_price || ! _ANYLIST_products_filter_max_price ) {
			_ANYLIST_products_filter_min_price = _ANYLIST_products_filter_max_price = price;
		} else {
			_ANYLIST_products_filter_min_price = (price < _ANYLIST_products_filter_min_price) ? price : _ANYLIST_products_filter_min_price;
			_ANYLIST_products_filter_max_price = (price > _ANYLIST_products_filter_max_price) ? price : _ANYLIST_products_filter_max_price;
		}

		// skip products out of price range
		if ( price < _ANYLIST_products_filter_min_handle_price || price > _ANYLIST_products_filter_max_handle_price ) {
			product_data[1] = 0;
			return true;
		}

		// update _ANYLIST_products_filter_taxonomies counters
		$.each( product_data[2], function(tax_name, term_ids) {
			if (term_ids.length > 0) {
				// add 1 in taxonomy level
				_ANYLIST_products_filter_taxonomies[tax_name][1]++;

				$.each(term_ids, function(i, term_id) {
					// add 1 in taxonomy term level
					_ANYLIST_products_filter_taxonomies[tax_name][2][term_id][0]++;
				});
			}
		});
	});

	// display updated products filter
	anylist_display_products_filter();

}

/**
 * anylist_display_products_filter
 *
 * Display updated products filter
 *
 * @since		1.0
 * @param		init (bool) uncheck all categories inputs (true) or not (false) - used in case of categories menu selction change event
 * @return		N/A
 */
function anylist_display_products_filter(init) {

	// update price filter
	if ( _ANYLIST_products_filter_show_price_filter ) {
		// use new temporary variables in order to save untouched price filter handles
		min_handle_price = (_ANYLIST_products_filter_min_price && _ANYLIST_products_filter_min_handle_price < _ANYLIST_products_filter_min_price) ? _ANYLIST_products_filter_min_price : _ANYLIST_products_filter_min_handle_price;
		max_handle_price = (_ANYLIST_products_filter_max_price && _ANYLIST_products_filter_max_handle_price > _ANYLIST_products_filter_max_price) ? _ANYLIST_products_filter_max_price : _ANYLIST_products_filter_max_handle_price;

		if (_ANYLIST_products_filter_price_filter_init || _ANYLIST_products_filter_min_price != _ANYLIST_products_filter_max_price) {
			// reinit price slider
			if ( _ANYLIST_products_filter_price_filter_init ) {
				// price filter slider already initiated - destroy it before reinit
				$('#anylist-price-filter-slider').slider('destroy');
			}
			else {
				// price filter slider has not initiated yet
				// expose price filter and update status
				$('.anylist-price-filter').show();

				// set price filter status for further treatment
				_ANYLIST_products_filter_price_filter_init = true;
			}

			// init price slider
			anylist_init_price_slider(_ANYLIST_products_filter_min_price, _ANYLIST_products_filter_max_price, min_handle_price, max_handle_price);
		}
	}

	// update taxonomy filters
	$.each( _ANYLIST_products_filter_taxonomies, function(tax_name, tax_data) {
		if (tax_data[1] == 0) {
			// there are no filtered products associated with this taxonomy
			// hide taxonomy filter completely
			$('.anylist-tax-filter-' + tax_name).hide();
		} else {
			// there are filtered products associated with this taxonomy
			// expose taxonomy filter
			$('.anylist-tax-filter-' + tax_name).show();
			
			$.each( tax_data[2], function(term_id, term_data) {
				if (term_data[0] == 0) {
					// there are no filtered products associated with this term
					// hide taxonomy term
					$('.anylist-tax-filter input#' + term_id).parent('li').hide();
				} else {
					// there are filtered products associated with this term
					var input = $('.anylist-tax-filter input#' + term_id);

					// update term label
					input.parent('li').find('span.count').html(term_data[0]);

					if (init) {
						// uncheck category input
						input.prop('checked', false);
					}

					// expose taxonomy term
					input.parent('li').show();
				}
			});
		}
	});

}

/**
 * anylist_update_products_grid
 *
 * Update products grid
 *
 * @since		1.0
 * @param		N/A
 * @return		N/A
 */
function anylist_update_products_grid() {

	var displayed_products = 0;

	// hide not found message
	$('.anylist-not-found').hide();
	
	// hide all products
	$('ul.products li').hide();
	
	// expose filtered posts
	$('ul.products li').each(function(index) {
		//var postid = parseInt( $(this).attr('data-postid') );
		var classes			= $(this).attr('class'),
			postid_class	= classes.match(/post-(\d+)/g),
			postid			= parseInt( postid_class[0].substring(5) );

		if (_ANYLIST_products_filter_products[postid][1]) {
			$(this).show();
			displayed_products++;
		}
	});

	if ( ! displayed_products ) {
		// expose no posts found message
		$('.anylist-not-found').show();
	}

}

/**
 * anylist_update_products_grid_ajax
 *
 * Update products grid
 * Used in case of category menu selection change event
 *
 * @since		1.0
 * @param		products_grid (string) HTML representation of updated products grid
 * @return		N/A
 */
function anylist_update_products_grid_ajax(products_grid) {

	$('ul.products').html(products_grid);

	// add 'product' custom post type to post class
	$('ul.products > li').addClass('product');

}