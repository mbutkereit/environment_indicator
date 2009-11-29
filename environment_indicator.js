/* $Id$ */

Drupal.environmentIndicator = Drupal.environmentIndicator || {};
Drupal.environmentIndicator.behaviors = Drupal.environmentIndicator.behaviors || {};

/**
 * Core behavior for Environment Indicator.
 *
 * Test whether there is an environment indicator strip in the output and execute all
 * registered behaviors.
 */
Drupal.behaviors.environmentIndicator = function (context) {
  // Initialize settings.
  Drupal.settings.environment_indicator = $.extend({
    text: '',
    color: '#d00c0c',
    suppress: false,
    margin: false,
    position: 'left',
    position_fixed: false,
  }, Drupal.settings.environment_indicator || {});
  
  // Check whether environment indicator strip menu should be suppressed.
  if (Drupal.settings.environment_indicator.suppress) {
    return;
  }
  
  Drupal.settings.environment_indicator.cssClass = 'environment-indicator-' + Drupal.settings.environment_indicator.position;
  
  var $environmentIndicator = $('#environment-indicator:not(.environment-indicator-processed)', context);
  if (!$environmentIndicator.length) {
    $('body', context).prepend('<div id="environment-indicator">' + Drupal.settings.environment_indicator.text + '</div>');
    $('body', context).addClass(Drupal.settings.environment_indicator.cssClass);
    
    var $environmentIndicator = $('#environment-indicator:not(.environment-indicator-processed)', context);
    $environmentIndicator.css('background-color', Drupal.settings.environment_indicator.color);
    
  }
  if ($environmentIndicator.length) {
    Drupal.environmentIndicator.attachBehaviors(context, $environmentIndicator);
  }
};

/**
 * Apply margin to page.
 *
 * Note that directly applying margin does not work in IE. To prevent
 * flickering/jumping page content with client-side caching, this is a regular
 * Drupal behavior.
 */
Drupal.behaviors.environmentIndicatorMargin = function (context) {
  if (!Drupal.settings.environment_indicator.suppress && Drupal.settings.environment_indicator.margin) {
    $('body:not(.environment-indicator-adjust)', context).addClass('environment-indicator-adjust');
    
    // Adjust the width of the admin-menu
    if ($("#admin-menu").length) {
      $("#admin-menu").css('margin-left', '30px');
      $("#admin-menu").css('width', $("#admin-menu").width() - 30 +'px');
      $(window).resize(Drupal.environmentIndicator.behaviors.stretchAdminMenu);
    }
  }
};

/**
 * @defgroup environment_indicator_behaviors Environemnt Indicator behaviors.
 * @{
 */

/**
 * Attach environment indicator behaviors.
 */
Drupal.environmentIndicator.attachBehaviors = function (context, $environmentIndicator) {
  if ($environmentIndicator.length) {
    $environmentIndicator.addClass('environment-indicator-processed');
  
    // Make the text appear vertically
    $environmentIndicator.html($environmentIndicator.text().replace(/(.)/g,"$1<br />"));
    
    if (Drupal.settings.environment_indicator.margin) {
      // Adjust the background position
      var pos = $("body").backgroundPosition();
      $("body").css({backgroundPosition: pos[0]+30 + 'px ' + pos[1] + 'px'});
    }
    
    $.each(Drupal.environmentIndicator.behaviors, function() {
      this(context, $environmentIndicator);
    });
  }
};

/**
 * Utility function to get the x and y attributes of background position.
 */
$.fn.backgroundPosition = function() {
  var p = $(this).css('background-position');
  if(typeof(p) === 'undefined') {
    p = $(this).css('background-position-x') + ' ' + $(this).css('background-position-y');
  }
  var posX = p.split(' ')[0].replace(/px/,'');
  var posY = p.split(' ')[1].replace(/px/,'');
  return [posX, posY];
};
  
/**
 * Set height to 100%.
 */
/*Drupal.environmentIndicator.behaviors.height = function (context, $environmentIndicator) {
    var height = $("body").height() + $("#admin-menu").height();
    $environmentIndicator.css('height', height + 'px');
};

/**
 * Apply 'position: fixed'.
 */
/*Drupal.environmentIndicator.behaviors.positionFixed = function (context, $environmentIndicator) {
  if (Drupal.settings.environment_indicator.position_fixed) {
    $environmentIndicator.css('position', 'fixed');
  } else{
    // ensure the height always fills the screen
    $(window).resize(function() { Drupal.environmentIndicator.behaviors.height(context, $environmentIndicator) } );
  }
};

/**
 * Stretch the admin menu.
 */
Drupal.environmentIndicator.behaviors.stretchAdminMenu = function (context, $environmentIndicator) {
  if (Drupal.settings.environment_indicator.margin) {
    $("#admin-menu").css('width', $("body").width() +'px');
  }
};

/**
 * @} End of "defgroup environment_indicator_behaviors".
 */

