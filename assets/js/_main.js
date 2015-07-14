/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can 
 * always reference jQuery with $, even when in .noConflict() mode.
 *
 * Google CDN, Latest jQuery
 * To use the default WordPress version of jQuery, go to lib/config.php and
 * remove or comment out: add_theme_support('jquery-cdn');
 * ======================================================================== */

(function($) {

// Use this variable to set up the common and page specific functions. If you 
// rename this variable, you will also need to rename the namespace below.
var Monum = {
  // All pages
  common: {
    init: function() {
      // CookieCuttr
      $.cookieCuttr({
        cookieAnalytics: false,
        cookieAcceptButtonText: 'Accepteer Cookies',
        cookiePolicyLink: '/privacy-policy/',
        cookieMessage: 'Monum gebruikt cookies op deze website om bezoeken naar onze website te volgen, wij slaan geen persoonlijke gegevens op.',
        cookieDomain: 'monum.nl'
      });
      if($.cookieAccepted()) {
        $('body').removeClass('cookie-bar');
      }
      // If Cookie Bar is present
      if ($('.cc-cookies')[0]) {
        $('body').addClass('cookie-bar');
      }
      // ScrollTo section if url has hashtag
      if(location.hash.length > 1) {
        var s = location.hash;
        $('html, body').animate({
          scrollTop: $(s).offset().top
        }, 1000);
      }
    }
  },
  // Single Product
  single_product: {
    init: function() {
      // Show/hide custom variation fields
      $('.variations_form input[name=variation_id]').bind('change', function() {
        var $var_id  = $(this).val();
      	if ($var_id) {
          var tr_id = "var-" + $var_id;
          $('#tab-additional_information table.shop_attributes tr.custom-variation').css('display', 'none');
          $('#tab-additional_information table.shop_attributes tr.custom-variation#' + tr_id).css('display', 'table-row');   	  
      	}
      });
      // Read More Link
      var $morelink  = $('.woocommerce-tabs a.read-more');
      var more_text  = $morelink.text();
      $morelink.click(function(e){
        var less_text  = $(this).data('text-less');
        $(this).parents().find('.content_more').slideToggle();
        $(this).text( $(this).text() === more_text ? less_text : more_text);
      });
      // Variation description tooltip
      $('.swatch-desc-tooltip').mouseenter(function(e) {
        e.preventDefault();
        var $this = $(this);
        $this.attr('tt', $this.attr('title')).removeAttr('title');      
        // Store our timeout function in .data
        $this.data('hover_delay', setTimeout(function(title) {
          $('<div class="tool-tip"><p>'+$this.attr('tt')+'</p></div>').hide()
            .appendTo($this)
            .fadeIn(500);
        }, 500));
      }
      ).mouseleave(function() {
        var hover_delay = $(this).data('hover_delay');
        if (hover_delay) {
          clearTimeout(hover_delay);
          $(this).attr('title', $(this).attr('tt'));
          $(this).children('.tool-tip').fadeOut();
        }
      });
    }
  }
};

// The routing fires all common scripts, followed by the page specific scripts.
// Add additional events for more control over timing e.g. a finalize event
var UTIL = {
  fire: function(func, funcname, args) {
    var namespace = Monum;
    funcname = (funcname === undefined) ? 'init' : funcname;
    if (func !== '' && namespace[func] && typeof namespace[func][funcname] === 'function') {
      namespace[func][funcname](args);
    }
  },
  loadEvents: function() {
    UTIL.fire('common');

    $.each(document.body.className.replace(/-/g, '_').split(/\s+/),function(i,classnm) {
      UTIL.fire(classnm);
    });
  }
};

$(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.