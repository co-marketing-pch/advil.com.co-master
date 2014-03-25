(function ($) {
  
  var setItemPosition = function($this, config, index, position) {
    var item = $('> :eq(' + index + ')', $this);

    var scale = 0.4 * Math.sin(Math.acos((position - config.width/2) / (config.width/2))) + 0.6;

    if (scale == 1) {
      item.addClass('active');
      $('img', item).css('width', 'auto'); //Set width current img to auto (IE workaround)
      if (config.change != undefined) {
        config.change(item);
      }
    } else {
      $('img', item).css('width', '100%'); //Set width of images to 100% because IE starts with width:auto
      item.removeClass('active');
    }
    item.css({
      position: 'absolute',
      left: position - (item.attr('original-width') * scale / 2),
      top: (item.attr('original-height') - (item.attr('original-height') * scale)) / 2,
      width: scale * item.attr('original-width') + 'px',
      height: scale * item.attr('original-height') + 'px',
      'z-index': Math.round(scale * 100)
    });
    item.attr('position', position);
  }
  
  var moveOnePixel = function($this, config, forward) {
    if (forward == undefined) {
      forward = true;
    }
    if (config != undefined) {
      setOffset($this, config, config.offset + (forward ? 1 : -1));
    }
  }
  
  var moveNext = function($this, config) {
    var count = 0;
    if (config.interval == undefined) {
      config.interval = setInterval(function() {
        if (count >= config.step) {
          clearInterval(config.interval);
          config.interval = undefined;
        }
        moveOnePixel($this, config, true);
        count++;
      }, 10);
    }
  }
  
  var setOffset = function($this, config, offset) {
    config.offset = offset;
    if (config.sliderUi != undefined) {
      config.sliderUi.slider('value', offset);
    }
    config.items.each(function(i, item) {
      var position = i * config.step;
      var overflowPosition = config.step * (i + config.items.length);
      var underflowPosition = config.step * (i - config.items.length);
      if (position >= offset && position <= offset + config.width) {
        $(item).show();
        setItemPosition($this, config, i, position - offset);
      } else  if (config.overflow && overflowPosition >= offset && overflowPosition <= offset + config.width) {
        $(item).show();
        setItemPosition($this, config, i, overflowPosition - offset);
      } else  if (config.overflow && underflowPosition >= offset && underflowPosition <= offset + config.width) {
        $(item).show();
        setItemPosition($this, config, i, underflowPosition - offset);
      } else {
        $(item).hide();
      }
    });
  }
    
  var movePrev = function($this, config) {
    var count = 0;
    if (config.interval == undefined) {
      config.interval = setInterval(function() {
        if (count >= config.step) {
          clearInterval(config.interval);
          config.interval = undefined;
        }
        moveOnePixel($this, config, false);
        count++;
      }, 10);
    }
  }
  
  var setActiveItem = function($this, config) {
    if (config.offset % config.step != 0) {
      var activeItemIndex = Math.round(config.offset / config.step);
      setOffset($this, config, activeItemIndex * config.step);
    }
  }
  
  $.fn.lgCarousel = function(config) {
    var carouselWrapper = $(this);
    
    config.items = $('> *', this);
    
    $(this).css({width: config.width + 'px', height: config.height + 'px', display: 'block'});

    config.items = $('> *', this);

    config.items.each(function(index, item) {
      var $item = $(item);
      $item.attr('original-width', $item.width());
      $item.attr('original-height', $item.height());
    });
        
    config.step = config.width / (config.visibleItems - 1);
    config.offset = 0

    $(config.next).click(function() {
      moveNext(carouselWrapper, config);
    });
    
    $(config.prev).click(function() {
      movePrev(carouselWrapper, config);
    });
    
    setOffset(carouselWrapper, config, 0);
    if (config.slider) {
      var sliderWidth = config.step * (config.items.length - 1);
      config.sliderUi = $('<div class="slider"></div>');
      $(this).after(config.sliderUi);
      config.sliderUi.slider({
        step: 10,
        max: sliderWidth - config.step,
        min: -config.step,
        value: (Math.floor(sliderWidth / 2 / config.step) - 1) * config.step,
        animate: true,
        slide: function(event, ui) {
          setOffset(carouselWrapper, config, ui.value);
        },
        change: function(event, ui) {
          setActiveItem(carouselWrapper, config);
        }
      });
      config.sliderUi.find('a.ui-slider-handle').css('margin-left',
        '-' + config.sliderUi.find('a.ui-slider-handle').width()/2 + 'px');
      setOffset(carouselWrapper, config, Math.floor(sliderWidth / 2 / config.step) * config.step);
    }
    
  }

})(jQuery);