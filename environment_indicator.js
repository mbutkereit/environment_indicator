/**
 * @file
 * Environment info JavaScript.
 *
 * @author Tom Kirkpatrick (mrfelton), www.systemseed.com
 */

(function ($) {

Drupal.environmentIndicator = Drupal.environmentIndicator || {};

/**
 * Core behavior for Environment Indicator.
 *
 * Test whether there is an environment indicator in the output and execute all
 * registered behaviors.
 */
Drupal.behaviors.environmentIndicator = {
  attach: function(context, settings) {
 
    // Initialize settings.
   settings.environment_indicator = $.extend({
      text: ' ',
      color: '#d00c0c',
      suppress: false,
      margin: false,
      position: 'left'
    }, settings.environment_indicator || {});
    
    // Check whether environment indicator strip menu should be suppressed.
    if (settings.environment_indicator.suppress) {
      return;
    };
    
    if ($('body:not(.environment-indicator-processed|.overlay)', context).length) {
      settings.environment_indicator.cssClass = 'environment-indicator-' + settings.environment_indicator.position;
      
      // If we don't have an environment indicator, inject it into the document.
      var $environmentIndicator = $('#environment-indicator', context);
      if (!$environmentIndicator.length) {
        $('body', context).prepend('<div id="environment-indicator">' + settings.environment_indicator.text + '</div>');
        $('body', context).addClass(settings.environment_indicator.cssClass);
        
        // Set the colour.
        var $environmentIndicator = $('#environment-indicator', context);
        $environmentIndicator.css('background-color', settings.environment_indicator.color);
        
        // Make the text appear vertically
        $environmentIndicator.html($environmentIndicator.text().replace(/(.)/g,"$1<br />"));
        
        // Adjust the margin.
        if (settings.environment_indicator.margin) {
          $('body:not(.environment-indicator-adjust)', context).addClass('environment-indicator-adjust');
 
          // Adjust the width of the toolbar
          if ($("#toolbar").length) {
            $("#toolbar").css('margin-'+settings.environment_indicator.position, '10px');
          }
        }
      }

      // Applies a coloured bar to the favicon
      Drupal.environmentIndicator.affectFavicon(settings.environment_indicator.color);
      
      $('body:not(.environment-indicator-processed)', context).addClass('environment-indicator-processed');
    }
  }
};

// Applies a coloured bar to the favicon
Drupal.environmentIndicator.affectFavicon = function (color) {
  var i,
      linkTags = document.getElementsByTagName("link"),
      icon = null,
      rel,
      iconImg,
      flatColor;

  for (i = linkTags.length; i >= 0; i -= 1) {
    if (typeof linkTags[i] !== "object") {
      continue;
    }
    rel = linkTags[i].getAttribute("rel");
    if (typeof rel === "undefined") {
      continue;
    }

    if (rel === "shortcut icon" || rel === "icon") {
      icon = linkTags[i];
      break;
    }
  }

  iconImg = new Image();
  flatColor = Drupal.environmentIndicator.splitColor(color).join(",");

  // See: https://developer.mozilla.org/en/Canvas_tutorial/Using_images
  iconImg.onload = function () {
    var canvas, ctx, newIcon;

    canvas = document.createElement("canvas");
    canvas.setAttribute("width", "16px");
    canvas.setAttribute("height", "16px");
    ctx = canvas.getContext("2d");  

    ctx.lineCap = "butt";
    ctx.drawImage(iconImg, 0, 0);  

    ctx.beginPath();
      ctx.strokeStyle = "rgba(" + flatColor + ",1)";
      ctx.lineWidth = 4;
      ctx.moveTo(0, 0);
      ctx.lineTo(canvas.width, 0);
    ctx.stroke();

    ctx.beginPath();
      ctx.strokeStyle = "rgba(0,0,0,0.7)";
      ctx.lineWidth = 1;
      ctx.moveTo(0, 2.5);
      ctx.lineTo(canvas.width, 2.5);
    ctx.stroke();

    // See: http://www.p01.org/releases/DEFENDER_of_the_favicon/
    try {
      (newIcon = icon.cloneNode(true)).setAttribute("href", ctx.canvas.toDataURL());
      icon.parentNode.replaceChild(newIcon, icon);
    } catch (e) {
      // Nobody cares if a favicon goes untweaked
    }
  };

  iconImg.src = icon.href;

  return this;
};

// See: http://stackoverflow.com/questions/5623838/rgb-to-hex-and-hex-to-rgb
Drupal.environmentIndicator.splitColor = function (color) {
  var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(color);

  return result ? [
    parseInt(result[1], 16),
    parseInt(result[2], 16),
    parseInt(result[3], 16)
  ] : null;
};

})(jQuery);
