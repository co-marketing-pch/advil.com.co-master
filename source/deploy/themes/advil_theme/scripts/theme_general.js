//PUT ALL THE JS FUNCTIONS, AND CODES INSIDE THE (function ($) {* INSIDE HERE *})(jQuery);
(function ($) {
  $(document).ready(function() {
    initFAQ();
    initCarousel();
    initReliefFinder();
    initJqTransform();
    megaNavigationBehavior();
    initSavingsForYouCarousel();
    overridePrint();
    removeMessagesTestimonials();
    initVideoPage();
    productDetailsTab();
	  shareYourStory(); 
  });

  function shareYourStory() {
	if($("div#block-menu-menu-trusted-pain-relief-menu").length > 0) {
	  if(window.location.href.split("share-your-story")[1] == '/thank-you') {
		 $("div#block-menu-menu-trusted-pain-relief-menu li.last a[title='Share Your Story']").addClass("active");
	  }
	}
  }
  
  function productDetailsTab() {
  
    //Behavior to see a product form or another.
    $('ul.product-detail-product-forms li a').click(function() {
      var tabId = $(this).attr('name');      
      $('div.product-detail-content').hide();
      $('div#' + tabId).show();
      
      $('div.product-detail-content ul.product-detail-product-forms li').removeClass('not-active active');
      $('div.product-detail-content ul.product-detail-product-forms').children().not('.' + tabId).addClass('not-active');
      $('div.product-detail-content ul.product-detail-product-forms li.' + tabId).addClass('active');     
      Cufon.refresh();      

      //Get advil product name based on the url
      var advilName = window.location.pathname.replace('/','') + '-';      
      //Remove the advil name from the selected tab name and add it to the hash
      tabId = tabId.replace(advilName,'');
      window.location.hash  = tabId;
      return false;
    });
    
    // If the window have a hash, show that div
    var hash = window.location.hash;
    if(hash){
      var advilName = window.location.pathname.replace('/','') + '-';      
      hash = hash.split('#')[1];
      tabId = advilName + hash;  
      $('a[name=' + tabId + ']').trigger('click');
    }
  }

  function initSavingsForYouCarousel() {
    if ($('#savings-for-you-carousel').length > 0) {
      var firstTime = true;
      //Show controllers if have more then one coupon
      if( $('#savings-for-you-carousel').children().length > 1) {
        $('div#couponsCarousel_controllers').show();
      }
      $('#savings-for-you-carousel').cycle({
        fx      : 'scrollHorz',
        prev    : '#couponsCarousel_Prev',
        next    : '#couponsCarousel_Next',
        timeout : 0,
        speed   : 500,
        onPrevNextEvent: function(isNext, zeroBasedSlideIndex, slideElement) {
          setTimeout(function(){
            animateSlideSavingsForYouCarousel(slideElement)
          }, 500);
        },
        before: function(currSlideElement, nextSlideElement, options, forwardFlag) {
          $('div.views-field-field-coupon-free-value-image',currSlideElement).fadeOut(100);
        },
        after: function(currSlideElement, nextSlideElement, options, forwardFlag) {
          if(firstTime){
            animateSlideSavingsForYouCarousel(currSlideElement);
            firstTime = false;
          }
        }
      });
    }
  }
  
  function animateSlideSavingsForYouCarousel(currSlideElement) {
    $('div.views-field-field-coupon-free-value-image',currSlideElement).fadeIn(200);
    
    //Get correct url for the coupon
    var coupon_url;
    if($('div.coupon-external-url', currSlideElement).length) {
      coupon_url = $('div.coupon-external-url', currSlideElement).html();
    }
    else {
      var coupon_id = $('div.coupon-id', currSlideElement).html();
      coupon_url = '/' + Drupal.settings.coupon.form_url + '/' + coupon_id;
    }
    $("div.view-display-id-block_coupons_savings_for_you a.btn-get-a-coupon").attr('href', coupon_url);
  }

  function initCarousel() {

    // Center thumbnails pager
    var thumbs = jQuery('#slideshow');

    var userInteracted = false;
    if ($('#rotation_promotion_carousel > ul').length) {
      var carousel = $('#rotation_promotion_carousel > ul').cycle({
        fx                : 'scrollHorz',
        timeout           : 7500,
        speed             : 400,
        pager             : '#slideshow',
        prev              : '#rotation_promotion_carousel_previous',
        next              : '#rotation_promotion_carousel_next',
        pagerAnchorBuilder: function(idx, slide) {
          // return selector string for existing anchor
          return '#slideshow li:eq(' + idx + ')';
        },
        onPrevNextEvent: function(isNext, zeroBasedSlideIndex, slideElement) {
          userInteracted = true;
        },
        onPagerEvent: function(zeroBasedSlideIndex, slideElement) {
          userInteracted = true;
        },
        after: function(currSlideElement, nextSlideElement, options, forwardFlag) {
          if (userInteracted) {
            pauseRotationPromotionCarousel();
          }
        }
      });

      initCarouselVideoItems();

      carousel.css('visibility', 'visible');
      thumbs.css('visibility', 'visible');
    }
  }

  /**
   * Add video player to rotation promotion carousel, if it exists
   */
  function initCarouselVideoItems() {
    var videos = $('div.rotation_promotion_video');

    if (videos.length > 0) {
      videos.each(function(index) {
        var videoTag = $('<video></video>').addClass('videoplayer').attr({
          id : 'player-' + index
        });

        if (Drupal.settings.video[index].video_url.ogg) {
          var oggSourceTag = $('<source></source>').attr({
            src  : Drupal.settings.video[index].video_url.ogg,
            type : 'video/ogg'
          });
          videoTag.append(oggSourceTag);
        }
        var mp4SourceTag = $('<source></source>').attr({
          src  : Drupal.settings.video[index].video_url.mp4,
          type : 'video/mp4'
        });
        videoTag.append(mp4SourceTag);
        videos.append(videoTag);
      });

      $('video.videoplayer').each(function(index) {
        jwplayer($(this).attr('id')).setup({
          height: 353,
          width : 625,
          skin  : Drupal.settings.basePath + Drupal.settings.themePath + '/video_player_skin/advil.zip',
          modes : [
            {
              type : 'html5',
              skin : Drupal.settings.basePath + Drupal.settings.themePath + '/video_player_skin/advil.xml'
            },
            {
              type: 'flash',
              src: Drupal.settings.basePath + Drupal.settings.themePath + '/swf/player.swf'
            }
          ],
          controlbar : {
            idlehide : true
          },
          image  : Drupal.settings.video[index].poster_src,
          events : {
            onBuffer: function () {
              pauseRotationPromotionCarousel();
              removeVideoPoster();
            },
            onPlay: function() {
              // trackOmniturePlay();
            }
          }
        });
      });
    }
  }

  /**
   * Pause automatic carousel.
   */
  function pauseRotationPromotionCarousel() {
    $('#rotation_promotion_carousel > ul').cycle('pause');
  }

  /**
   * Removes the preview image when the video is played
   */
  function removeVideoPoster() {
    $('img[id*="_jwplayer_display_image"]').remove();
  }


  /**
   * Get parameter from url query string
   * http://www.bloggingdeveloper.com/post/JavaScript-QueryString-ParseGet-QueryString-with-Client-Side-JavaScript.aspx
   */  
  function getQuerystring(key, default_)
  {
    if (default_==null) default_="";
    key = key.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regex = new RegExp("[\\?&]"+key+"=([^&#]*)");
    var qs = regex.exec(window.location.href);
    if(qs == null)
      return default_;
    else
      return qs[1];
  }
  
  
  function initVideoPage() {
    if ($('div#html5-video-player').length > 0) {
      $('div#html5-video-player').each(function() {
        var videoTag = $('<video></video>').addClass('videoplayer').attr({
          id : 'video-player',
          rel: $(this).attr('rel')
        });

        if (Drupal.settings.video.video_url.ogg) {
          var oggSourceTag = $('<source></source>').attr({
            src: Drupal.settings.video.video_url.ogg,
            type: 'video/ogg'
          });
          videoTag.append(oggSourceTag);
        }
        var mp4SourceTag = $('<source></source>').attr({
            src: Drupal.settings.video.video_url.mp4,
            type: 'video/mp4'
        });
        videoTag.append(mp4SourceTag);
        $('div#html5-video-player').append(videoTag);
      });

      var autoplay = false;
      var play = getQuerystring('play');
      if(play == '1'){
        autoplay = true;
      }

      var videoTitle = $('div#html5-video-player').attr('rel');
      jwplayer('video-player').setup({
        autostart: autoplay,
        height: 323,
        width: 575,
        image: Drupal.settings.video.poster_src,
        skin: Drupal.settings.basePath + Drupal.settings.themePath + '/video_player_skin/advil.zip',
        events: {
          onBuffer: function() {
            removeVideoPoster();
          },
          onPlay: function(states) {
            trackOmnitureVideoPlay(videoTitle, states);
          },
          onComplete: function(states) {
            trackOmnitureVideoComplete(videoTitle);
          }
        },
        controlbar: {
          idlehide: true
        },
        modes: [
          {type: 'html5',
            skin: Drupal.settings.basePath + Drupal.settings.themePath + '/video_player_skin/advil.xml'
          },
          {type: 'flash',
            src: Drupal.settings.basePath + Drupal.settings.themePath + '/swf/player.swf'
          }
        ]
      });
      $('div#html5-video-player_video_wrapper').css('position','static');
    }
  }
  
  $(document).click(function(e) {
    if ($('#symptons-itens-container').length) {
      if (!$('#symptons-itens-container').is(':hidden')) {
        var offset = $('#symptons').offset();
        var right = (offset.left + $('#symptons-itens-content').width());
        var bottom = (offset.top + $('#symptons-itens-content').height() + 10);
        if (e.pageX < offset.left || e.pageX > right || e.pageY < offset.top || e.pageY > bottom) {
          $('#symptons-itens-container').hide();
        }
      }
    }
  });

  // Omniture video start tracking
  function trackOmnitureVideoPlay(videoTitle, states) {
    if (states.oldstate == 'BUFFERING') {
      if (typeof(Drupal.settings.omniture) != 'undefined') {
        s.linkTrackEvents = 'event12,event19';
        s.linkTrackVars = 'events,eVar19,prop19';
        s.events = 'event12,event19';

        s.eVar19 = s.prop19 = s.pageName + ' : play : ' + videoTitle.replace(/[^\w-\s\/:]/g, '').toLowerCase();

        s.tl(this, 'o', s.prop19);
        s.linkTrackEvents = 'None';
        s.linkTrackVars   = 'None';
      }
    }
  }  
  
  // Omniture video complete tracking
  function trackOmnitureVideoComplete(videoTitle) {
    if (typeof(Drupal.settings.omniture) != 'undefined') {
      s.linkTrackEvents = 'event13';
      s.linkTrackVars = 'events';
      s.events = 'event13';

      var videoComplete = s.pageName + ' : video complete : ' + videoTitle.replace(/[^\w-\s\/:]/g, '').toLowerCase();

      s.tl(this, 'o', videoComplete);
      s.linkTrackEvents = 'None';
      s.linkTrackVars   = 'None';
    }
  }  
  
  
  function initJqTransform() {
  /* Where to buy */
    var formBuyNow = $("div#gmapslivesearch-buy-now-form");
    if (formBuyNow.length) {
      formBuyNow.jqTransform('');
    }

    var formFindStore = $('form.gmapslivesearch-find-online-form, #gmapslivesearch-buy-now-form');
    if (formFindStore.length) {
      formFindStore.jqTransform('');
    }

  /*Sign Up*/
    if ($('form#webform-client-form-47').length) {
      $('form#webform-client-form-47').jqTransform('');
    }

  /*Share Your Story*/
    if($("form#testimonial-submission-gateway").length > 0){
      $("form#testimonial-submission-gateway").jqTransform('');
    }

  /*Sign Up Coupons*/
    if ($("form#webform-form-coupon-sign-up").length > 0){
      $("form#webform-form-coupon-sign-up").jqTransform('');
    }

  /*Email to a Friend fields*/
    if($('body.page-send-to-a-friend form#communication-tools-send-form').length > 0){
      $('body.page-send-to-a-friend form#communication-tools-send-form').jqTransform('');
    }

  /*Relief Finder Selects*/
    if($('ul#symptons_itens_list').length > 0){
      $('ul#symptons_itens_list').jqTransform('');
    }
    
  /*FAQs*/
    if($('div#block-views-exp-faqs-faqs-page').length > 0){
      $('div#block-views-exp-faqs-faqs-page').jqTransform('');
    }

    if($('div.views-exposed-faq-filter').length > 0){
      $('div.views-exposed-faq-filter').jqTransform('');
    }
    
  /*Sign Up*/
    if($('form.webform-signup-email-updates').length > 0){
      $('form.webform-signup-email-updates').jqTransform('');
    }
  }
 
  /*
   * Cufon
  */
  Drupal.behaviors.cufon = {
    attach: function() {
      Cufon.replace('.avenir-heavy, .avenir-std-heavy, h1, h2, span.ui-dialog-title, div#gmapslivesearch-box-wrapper h3 ', {
        hover: true,
        fontFamily: 'Avenir Heavy'
      });
      Cufon.replace('.avenir-medium, .avenir-std-medium, h3, h4, h2.field-content, h2.medium, .faqs-menu h2, h6, div.rotation_promotion_text p, p.thead, div.product-labeling-dialog th', {
        hover: true,
        fontFamily: 'Avenir Medium'
      });
      Cufon.replace('.avenir-light, .avenir-std-light, a.light, div#block-related-content-related-content li > a', {
        hover: true,
        fontFamily: 'Avenir Light'
      });
      Cufon.replace('.avenir-medium-nav', {
        hover: true,
        fontFamily: 'Avenir Medium'
      });
      Cufon.replace('div#gmapslivesearch-box-wrapper h3', {
        hover: true,
        fontFamily: 'Avenir Heavy'
      });
      $('body').append('<script type="text/javascript">Cufon.now();</script>');
    }
  }

  function overridePrint() {
    $('a#communication_tools_print_link').unbind('click');
    $('a#communication_tools_print_link').click(function(){
      var printProperties = {};
      if (Drupal.settings.communication_tools.site_logo_print_path != '') {
        printProperties.logo = Drupal.settings.communication_tools.site_logo_print_path;
      }
      var content_to_print = $('div#content').clone();
      $('.slider-wrapper', content_to_print).remove();
      $('a.control-button', content_to_print).remove();

      printProperties.overrideElementCSS = [Drupal.settings.basePath + Drupal.settings.themePath + '/styles/print.css',
        { href:Drupal.settings.basePath + Drupal.settings.themePath + '/styles/print.css', media:'print' }
      ];

      content_to_print.printElement(printProperties);
      return false;
    });
  }

  /*
   * Relief Finder
   */
  function initReliefFinder() {
    if ($("div#relief_finder_container").length > 0) {
      var relief_finder_symptoms_list = $('ul#symptons_itens_list');

      // Create symptoms list
      $.each(Drupal.settings.relief_finder, function(key, value) {
        relief_finder_symptoms_list.append('<li><input type="radio" value="' + key + '" name="symptoms" id="' + key + '-symptom" /> <label for="' + key + '-symptom" >' + value.term_name +'</label> </li>');
      });
      //Enable submit button
      $('input', relief_finder_symptoms_list).click(function() {
        $('input#submit_relief_finder').removeClass('submit_disabled');
      });
      //Show results
      $('input#submit_relief_finder').click(function() {
        var selected_symptom =  $('input:radio[name=symptoms]:checked');
        var symptom_id = selected_symptom.val();
        selected_symptom.parents('li').addClass('selected');
        if(symptom_id){
          getReliefFinderResult(symptom_id);
        }
        else {
          return false;
        }
      });
      //Find other product
      $('a#find_another_product_link').click(function(){
        $('div#relief_finder_container').show();
        $('div#relief_finder_recomendation_container').hide();
		    $('ul#symptons_itens_list input[type="radio":checked]').each(function(){
		      $(this).checked = false;
		    });
		    $('ul#symptons_itens_list li').removeClass('selected');
        $('ul#symptons_itens_list a.jqTransformRadio').removeClass('jqTransformChecked');
        $.cookie('relief_finder_result', null);
        return false;
      });

      //If the cookie is set, relief finder starts with the previous result found.
      var previous_result = $.cookie('relief_finder_result');
      if (previous_result != null) {
        getReliefFinderResult(previous_result, false);
      }
    }
  }
  
  function getReliefFinderResult(symptom_id, set_cookie) {
    var symptom_selected = Drupal.settings.relief_finder[symptom_id];
    var primary_recommendation = symptom_selected.primary_recommendation;
    var secondary_recommendation = symptom_selected.secondary_recommendation;
    
    // Set Cookie
    if (set_cookie == undefined) {
      set_cookie = true;
    }
    if (set_cookie) {
      $.cookie('relief_finder_result', symptom_id, {expires: 365});
    }
    //Build result
    buildReliefFinderResult(primary_recommendation, secondary_recommendation);
  }
  
  function buildReliefFinderResult(primary_recommendation, secondary_recommendation) {
    //Primary recommendation
    $('div#relief_finder_recomendation_content div.recommend_content')
      .html(primary_recommendation.product_image + '<div class="relief_finder_product_information"><h3>' + primary_recommendation.title + '</h3>' + primary_recommendation.link_learn_more + '</div>');

    //Second recommendation
    $('div#relief_finder_recomendation_content div.also_try').hide();
    if (secondary_recommendation != null) {
      $('div#relief_finder_recomendation_content div.also_try div.also_try_content')
        .html(secondary_recommendation.product_image + '<div class="relief_finder_product_information"><h3>' + secondary_recommendation.title + '</h3>' + secondary_recommendation.link_learn_more + '</div>');
      $('div#relief_finder_recomendation_content div.also_try').show();
    }

    $('div#relief_finder_container').hide();
    $('div#relief_finder_recomendation_container').show();
  }


  // [END RELIEF FINDER]

  Drupal.behaviors.textResizer = {
    attach: function (context) {
      if ($('.article-type-size-block', context).length == 0) return false;

      var textContainer = getTextToResizeContainer();
      var defaultResizerFontSize = Drupal.settings.article_type_size.type_size_default_font_size;
      var minResizerFontSize = Drupal.settings.article_type_size.type_size_min_font_size;
      var maxResizerFontSize = Drupal.settings.article_type_size.type_size_max_font_size;

      $('.article-type-size-block', context).each(function(){
        //Decrease the font size
        $('a.fade-font-size', this).click(function(){
          newFontSize = parseInt(textContainer.css('font-size')) - 1;
          if (newFontSize >= minResizerFontSize) {
            resizeText(textContainer, newFontSize);
          }
          return false;
        });

        //Increase the font size
        $('a.expand-font-size', this).click(function(){
          newFontSize = parseInt(textContainer.css('font-size')) + 1;
          if (newFontSize <= maxResizerFontSize) {
            resizeText(textContainer, newFontSize);
          }
          return false;
        });
      });

      resizeText(textContainer, defaultResizerFontSize);

      function resizeText(textContainer, fontSize) {
        textContainer.css('font-size', fontSize + 'px');
      }

      function getTextToResizeContainer() {
        return $('.article-content', context);
      }
    }
  }

  // MEGA NAVIGATION

  // This function is executed in the $(document).ready function
  function megaNavigationBehavior() {
    $("div#navigation ul.menu li, div#mega_navigation").hover(function() {
      handleMegaNavigationShow($(this));
      Cufon.refresh('.avenir-medium-nav');
    }, function() {
      handleMegaNavigationHide($(this));
    });
  }

  var is_being_displayed = false;
  var megaNavigationTimeout = undefined;

  $.fn["handleMegaNavigation"] = function() {
    if (!is_being_displayed) {
      $("div#mega_navigation div.region-mega-navigation > div").hide();
      $('div#mega_navigation').hide();
      $("div#navigation ul.menu a").removeClass('nav-hover');
      Cufon.refresh('.avenir-medium-nav');
    }
  }

  function handleMegaNavigationHide(context) {
    if (is_being_displayed) {
      is_being_displayed = false;
      clearTimeout(megaNavigationTimeout);
      megaNavigationTimeout = setTimeout("jQuery().handleMegaNavigation()", 100);
    }
  }

  function handleMegaNavigationShow(context) {
    var selected_menu_item_class = /[a-z\-]*\-menu/.exec($('a', context).attr('class'));
    if (selected_menu_item_class) {
      selected_menu_item_class = selected_menu_item_class[0];
      $("div#navigation ul.menu a").removeClass('nav-hover');
      $("div#navigation ul.menu a." + selected_menu_item_class).addClass('nav-hover');
      $("div#mega_navigation div.region-mega-navigation > div").hide();
      $("div#mega_navigation div.region-mega-navigation > div." + selected_menu_item_class).show();
    }

    is_being_displayed = true;

    $('div#mega_navigation').show();
  }

  // [End of] MEGA NAVIGATION
  
  // FAQ MEGA NAVIGATION
  function initFAQ() {
    var exposed_faq_filter = $('div#mega_navigation form#views-exposed-form-faqs-faqs-page');

    // Clone the input search content to looking for question and answer
    $('div.views-submit-button input', exposed_faq_filter).click(function() {
      $('div.form-item-field-faq-question-value input', exposed_faq_filter).attr('value', $('div.form-item-search input', exposed_faq_filter).attr('value'));
    });
   
    // Change - Any - label to View All
    if($('div#edit-tid-all a', exposed_faq_filter).length > 0) {
  	  $('div#edit-tid-all a', exposed_faq_filter).text(Drupal.t('View All'));
    } else {
      $('div#edit-tid-all', exposed_faq_filter).text(Drupal.t('View All'));
    }

    // Change the urls of filter to exclude the inputs text content
    $('div.views-exposed-widget a', exposed_faq_filter).each(function() {
      var index = $(this).attr('href').indexOf('tid');
      if(index >= 0) {
        var tid = $(this).attr('href').slice(index);
        var url = '/' + Drupal.settings.faq.faq_path + '?' + tid;
        $(this).attr('href', url);
      }
    });
    
    // Add link to view all filter option
    if($('div.views-exposed-widget div#edit-tid-all a', exposed_faq_filter).length == 0) {
      var url = Drupal.settings.faq.faq_path + '?tid=All';
      var view_all = $('div.views-exposed-widget div#edit-tid-all', exposed_faq_filter);
      var text = view_all.html();
      var a = $('<a href=' + url +'>' + text + '</a>');
      view_all.html(a);
    }
    
    if($('input#edit-search', exposed_faq_filter).length > 0) {
      var default_search_text = Drupal.t('Search FAQs');
      
      var input = $('input#edit-search', exposed_faq_filter);
      
      input.focus(function() {
        if (input.attr('value') == default_search_text) {
          input.css("font-style", "normal");
          input.attr('value', '');
        }
      });
      
      input.focusout(function() {
        if (input.attr('value') == '') {
          input.attr('value', default_search_text);
          input.css("font-style", "italic");
        }
      });
      
      var input_value = input.attr('value');
      if(input_value == '') {
        input.attr('value', default_search_text);
        input.css("font-style", "italic");
      }
    }
  }
  // [End of] FAQ MEGA NAVIGATION

  /*
   * Hide the div messages on share your story page
   */
  function removeMessagesTestimonials() {    
    if ($('p.share-your-story-story-description').length > 0) {      
      $('div.messages').hide();      
    }
  }
})(jQuery);