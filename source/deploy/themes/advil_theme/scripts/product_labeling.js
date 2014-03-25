(function($) {
  
  Drupal.behaviors.productLabeling = {
  
    attach: function(context) {
    
      $('div.product-labeling-wrapper', context).each(function() {
      
        var product_labeling_content = $('div.product-labeling-content', this);
		var product_labeling_content_class = $(this).parent().attr("id");
		
        $('a.product-labeling-link', this).click(function() {
          
          product_labeling_content.dialog({
            resizable: false,
            draggable: false,
            modal: true,
            dialogClass: 'product-labeling-dialog ' + product_labeling_content_class,
            open: function(event, ui) {
              create_scroll(product_labeling_content_class);
            },
            create: function(event, ui) {
              Drupal.attachBehaviors($(event.target).closest('.ui-dialog'));
              
              // fix for scrollbars in IE
              if ($.browser.msie) {
                $('body').css('overflow', 'hidden');
              }
            },
            close: function(event, ui) { 
              // fix for scrollbars in IE
              if ($.browser.msie) {
                $('body').css('overflow', 'auto');
              }
            }
          });
          
          return false;
        });
        
      });
      
    }
    
  }

  // insert scrollpane for overlay
  function create_scroll(product_labeling_content_class) {
	 	  
    if (($.browser.msie && parseInt($.browser.version) == 7) || ($.browser.msie && parseInt($.browser.version) == 8)) {

 	  if($(".product-labeling-dialog").hasClass(product_labeling_content_class)) {
		$(".product-labeling-dialog."+product_labeling_content_class+" .product-labeling-content").jScrollPane({
		  verticalDragMinHeight: 0,
		  verticalDragMaxHeight: 0
		});
	  }
    }
    else {
     if($(".product-labeling-dialog").hasClass(product_labeling_content_class)) {
		$(".product-labeling-dialog."+product_labeling_content_class+" .product-labeling-content").jScrollPane({
		  verticalDragMinHeight: 73,
		  verticalDragMaxHeight: 73
		});
	  }
      
      Drupal.attachBehaviors($('.product-labeling-dialog'));
    }
  }
  
})(jQuery);