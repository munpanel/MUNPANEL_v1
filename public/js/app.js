/*! http://mths.be/placeholder v2.0.7 by @mathias */
;(function(h,j,e){var a="placeholder" in j.createElement("input");var f="placeholder" in j.createElement("textarea");var k=e.fn;var d=e.valHooks;var b=e.propHooks;var m;var l;if(a&&f){l=k.placeholder=function(){return this};l.input=l.textarea=true}else{l=k.placeholder=function(){var n=this;n.filter((a?"textarea":":input")+"[placeholder]").not(".placeholder").bind({"focus.placeholder":c,"blur.placeholder":g}).data("placeholder-enabled",true).trigger("blur.placeholder");return n};l.input=a;l.textarea=f;m={get:function(o){var n=e(o);var p=n.data("placeholder-password");if(p){return p[0].value}return n.data("placeholder-enabled")&&n.hasClass("placeholder")?"":o.value},set:function(o,q){var n=e(o);var p=n.data("placeholder-password");if(p){return p[0].value=q}if(!n.data("placeholder-enabled")){return o.value=q}if(q==""){o.value=q;if(o!=j.activeElement){g.call(o)}}else{if(n.hasClass("placeholder")){c.call(o,true,q)||(o.value=q)}else{o.value=q}}return n}};if(!a){d.input=m;b.value=m}if(!f){d.textarea=m;b.value=m}e(function(){e(j).delegate("form","submit.placeholder",function(){var n=e(".placeholder",this).each(c);setTimeout(function(){n.each(g)},10)})});e(h).bind("beforeunload.placeholder",function(){e(".placeholder").each(function(){this.value=""})})}function i(o){var n={};var p=/^jQuery\d+$/;e.each(o.attributes,function(r,q){if(q.specified&&!p.test(q.name)){n[q.name]=q.value}});return n}function c(o,p){var n=this;var q=e(n);if(n.value==q.attr("placeholder")&&q.hasClass("placeholder")){if(q.data("placeholder-password")){q=q.hide().next().show().attr("id",q.removeAttr("id").data("placeholder-id"));if(o===true){return q[0].value=p}q.focus()}else{n.value="";q.removeClass("placeholder");n==j.activeElement&&n.select()}}}function g(){var r;var n=this;var q=e(n);var p=this.id;if(n.value==""){if(n.type=="password"){if(!q.data("placeholder-textinput")){try{r=q.clone().attr({type:"text"})}catch(o){r=e("<input>").attr(e.extend(i(this),{type:"text"}))}r.removeAttr("name").data({"placeholder-password":q,"placeholder-id":p}).bind("focus.placeholder",c);q.data({"placeholder-textinput":r,"placeholder-id":p}).before(r)}q=q.removeAttr("id").hide().prev().attr("id",p).show()}q.addClass("placeholder");q[0].value=q.attr("placeholder")}else{q.removeClass("placeholder")}}}(this,document,jQuery));

/* Modernizr 2.6.2 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-touch-cssclasses-teststyles-prefixes
 */
;window.Modernizr=function(a,b,c){function w(a){j.cssText=a}function x(a,b){return w(m.join(a+";")+(b||""))}function y(a,b){return typeof a===b}function z(a,b){return!!~(""+a).indexOf(b)}function A(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:y(f,"function")?f.bind(d||b):f}return!1}var d="2.6.2",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k,l={}.toString,m=" -webkit- -moz- -o- -ms- ".split(" "),n={},o={},p={},q=[],r=q.slice,s,t=function(a,c,d,e){var f,i,j,k,l=b.createElement("div"),m=b.body,n=m||b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:h+(d+1),l.appendChild(j);return f=["&#173;",'<style id="s',h,'">',a,"</style>"].join(""),l.id=h,(m?l:n).innerHTML+=f,n.appendChild(l),m||(n.style.background="",n.style.overflow="hidden",k=g.style.overflow,g.style.overflow="hidden",g.appendChild(n)),i=c(l,a),m?l.parentNode.removeChild(l):(n.parentNode.removeChild(n),g.style.overflow=k),!!i},u={}.hasOwnProperty,v;!y(u,"undefined")&&!y(u.call,"undefined")?v=function(a,b){return u.call(a,b)}:v=function(a,b){return b in a&&y(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=r.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(r.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(r.call(arguments)))};return e}),n.touch=function(){var c;return"ontouchstart"in a||a.DocumentTouch&&b instanceof DocumentTouch?c=!0:t(["@media (",m.join("touch-enabled),("),h,")","{#modernizr{top:9px;position:absolute}}"].join(""),function(a){c=a.offsetTop===9}),c};for(var B in n)v(n,B)&&(s=B.toLowerCase(),e[s]=n[B](),q.push((e[s]?"":"no-")+s));return e.addTest=function(a,b){if(typeof a=="object")for(var d in a)v(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof f!="undefined"&&f&&(g.className+=" "+(b?"":"no-")+a),e[a]=b}return e},w(""),i=k=null,e._version=d,e._prefixes=m,e.testStyles=t,g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+q.join(" "):""),e}(this,this.document);
Modernizr.addTest('android',function(){return!!navigator.userAgent.match(/Android/i)});
Modernizr.addTest('chrome',function(){return!!navigator.userAgent.match(/Chrome/i)});
Modernizr.addTest('firefox',function(){return!!navigator.userAgent.match(/Firefox/i)});
Modernizr.addTest('iemobile',function(){return!!navigator.userAgent.match(/IEMobile/i)});
Modernizr.addTest('ie',function(){return!!navigator.userAgent.match(/MSIE/i)});
Modernizr.addTest('ie10',function(){return!!navigator.userAgent.match(/MSIE 10/i)});
Modernizr.addTest('ie11',function(){return!!navigator.userAgent.match(/Trident.*rv:11\./)});
Modernizr.addTest('ios',function(){return!!navigator.userAgent.match(/iPhone|iPad|iPod/i)});
Modernizr.addTest('ios7 ipad',function(){return!!navigator.userAgent.match(/iPad;.*CPU.*OS 7_\d/i)});
/*!
* screenfull
* v1.0.4 - 2013-05-26
* https://github.com/sindresorhus/screenfull.js
* (c) Sindre Sorhus; MIT License
*/
(function(a,b){"use strict";var c="undefined"!=typeof Element&&"ALLOW_KEYBOARD_INPUT"in Element,d=function(){for(var a,c,d=[["requestFullscreen","exitFullscreen","fullscreenElement","fullscreenEnabled","fullscreenchange","fullscreenerror"],["webkitRequestFullscreen","webkitExitFullscreen","webkitFullscreenElement","webkitFullscreenEnabled","webkitfullscreenchange","webkitfullscreenerror"],["webkitRequestFullScreen","webkitCancelFullScreen","webkitCurrentFullScreenElement","webkitCancelFullScreen","webkitfullscreenchange","webkitfullscreenerror"],["mozRequestFullScreen","mozCancelFullScreen","mozFullScreenElement","mozFullScreenEnabled","mozfullscreenchange","mozfullscreenerror"]],e=0,f=d.length,g={};f>e;e++)if(a=d[e],a&&a[1]in b){for(e=0,c=a.length;c>e;e++)g[d[0][e]]=a[e];return g}return!1}(),e={request:function(a){var e=d.requestFullscreen;a=a||b.documentElement,/5\.1[\.\d]* Safari/.test(navigator.userAgent)?a[e]():a[e](c&&Element.ALLOW_KEYBOARD_INPUT)},exit:function(){b[d.exitFullscreen]()},toggle:function(a){this.isFullscreen?this.exit():this.request(a)},onchange:function(){},onerror:function(){},raw:d};return d?(Object.defineProperties(e,{isFullscreen:{get:function(){return!!b[d.fullscreenElement]}},element:{enumerable:!0,get:function(){return b[d.fullscreenElement]}},enabled:{enumerable:!0,get:function(){return!!b[d.fullscreenEnabled]}}}),b.addEventListener(d.fullscreenchange,function(a){e.onchange.call(e,a)}),b.addEventListener(d.fullscreenerror,function(a){e.onerror.call(e,a)}),a.screenfull=e,void 0):a.screenfull=!1})(window,document);


// data-shift api
!function ($) {

  "use strict"; // jshint ;_;

 /* SHIFT CLASS DEFINITION
  * ====================== */

  var Shift = function (element) {
    this.$element = $(element)
    this.$prev = this.$element.prev()
    !this.$prev.length && (this.$parent = this.$element.parent())
  }

  Shift.prototype = {
  	constructor: Shift

    , init:function(){
    	var $el = this.$element
    	, method = $el.data()['toggle'].split(':')[1]
    	, $target = $el.data('target')
    	$el.hasClass('in') || $el[method]($target).addClass('in')
    }
    , reset :function(){
    	this.$parent && this.$parent['prepend'](this.$element)
    	!this.$parent && this.$element['insertAfter'](this.$prev)
    	this.$element.removeClass('in')
    }
  }

 /* SHIFT PLUGIN DEFINITION
  * ======================= */

  $.fn.shift = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('shift')
      if (!data) $this.data('shift', (data = new Shift(this)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.shift.Constructor = Shift
}(window.jQuery);

Date.now = Date.now || function() { return +new Date; };

!function ($) {

  $(function(){

    // toogle fullscreen
    $(document).on('click', "[data-toggle=fullscreen]", function(e){
      if (screenfull.enabled) {
        screenfull.request();
      }
    });

  	// placeholder
  	$('input[placeholder], textarea[placeholder]').placeholder();

    // popover
    var popOverSettings = {
        html: true,
        selector: '[data-toggle="popover"]',
        container: 'body'
    }

    $(document).popover(popOverSettings);

    //$("[data-toggle=popover]").popover();
    /*$(document).on('shown.bs.popover', '[data-toggle="popover"]', function(e){
        var $pop = $(this);
        setTimeout(function () {
            $pop.popover('hide');
        }, 2000);
        setTimeout(function () {
            $pop.popover();
        }, 2200);
    });*/

    $(document).on('click', function (e) {
        $('[data-toggle="popover"],[data-original-title]').each(function () {
            //the 'is' for buttons that trigger popups
            //the 'has' for icons within a button that triggers a popup
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {                
                (($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false  // fix for BS 3.3.6
            }

        });
    });

    $('.pop').on('shown.bs.popover', function () {
        var $pop = $(this);
        setTimeout(function () {
            $pop.popover('hide');
        }, 2000);
        setTimeout(function () {
            $pop.popover();
        }, 2200);
    });

    // ajax modal
    $(document).on('click','[data-toggle="ajaxModal"]', function(e) {
        $('#ajaxModal').modal('hide');
        $('#ajaxModal').remove();
        e.preventDefault();
        var $this = $(this)
          , $remote = $this.data('remote') || $this.attr('href')
          , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
        $('body').append($modal);
        $modal.modal();
        $modal.load($remote);
    });

    // dropdown menu
    $.fn.dropdown.Constructor.prototype.change = function(e){
      e.preventDefault();
      var $item = $(e.target), $select, $checked = false, $menu, $label;
      !$item.is('a') && ($item = $item.closest('a'));
      $menu = $item.closest('.dropdown-menu');
      $label = $menu.parent().find('.dropdown-label');
      $labelHolder = $label.text();
      $select = $item.find('input');
      $checked = $select.is(':checked');
      if($select.is(':disabled')) return;
      if($select.attr('type') == 'radio' && $checked) return;
      if($select.attr('type') == 'radio') $menu.find('li').removeClass('active');
      $item.parent().removeClass('active');
      !$checked && $item.parent().addClass('active');
      $select.prop("checked", !$select.prop("checked"));

      $items = $menu.find('li > a > input:checked');
      if ($items.length) {
          $text = [];
          $items.each(function () {
              var $str = $(this).parent().text();
              $str && $text.push($.trim($str));
          });

          $text = $text.length < 4 ? $text.join(', ') : $text.length + ' selected';
          $label.html($text);
      }else{
        $label.html($label.data('placeholder'));
      }
    }
    $(document).on('click.dropdown-menu', '.dropdown-select > li > a', $.fn.dropdown.Constructor.prototype.change);

  	// tooltip
    $("[data-toggle=tooltip]").tooltip();

    // class
  	$(document).on('click', '[data-toggle^="class"]', function(e){
  		e && e.preventDefault();
  		var $this = $(e.target), $class , $target, $tmp, $classes, $targets;
  		!$this.data('toggle') && ($this = $this.closest('[data-toggle^="class"]'));
    	$class = $this.data()['toggle'];
    	$target = $this.data('target') || $this.attr('href');
      $class && ($tmp = $class.split(':')[1]) && ($classes = $tmp.split(','));
      $target && ($targets = $target.split(','));
      $targets && $targets.length && $.each($targets, function( index, value ) {
        ($targets[index] !='#') && $($targets[index]).toggleClass($classes[index]);
      });
    	$this.toggleClass('active');
  	});

    // panel toggle
    $(document).on('click', '.panel-toggle', function(e){
      e && e.preventDefault();
      var $this = $(e.target), $class = 'collapse' , $target;
      if (!$this.is('a')) $this = $this.closest('a');
      $target = $this.closest('.panel');
        $target.find('.panel-body').toggleClass($class);
        $this.toggleClass('active');
    });

  	// carousel
  	$('.carousel.auto').carousel();

  	// button loading
  	$(document).on('click.button.data-api', '[data-loading-text]', function (e) {
  	    var $this = $(e.target);
  	    $this.is('i') && ($this = $this.parent());
  	    $this.button('loading');
  	});

    var scrollToTop = function(){
  		!location.hash && setTimeout(function () {
  		    if (!pageYOffset) window.scrollTo(0, 0);
  		}, 1000);
  	};

    var $window = $(window);
    // mobile
  	var mobile = function(option){
  		if(option == 'reset'){
  			$('[data-toggle^="shift"]').shift('reset');
  			return true;
  		}
  		scrollToTop();
  		$('[data-toggle^="shift"]').shift('init');
      return true;
  	};
  	// unmobile
  	$window.width() < 768 && mobile();
    // resize
    var $resize, $width = $window.width();
  	$window.resize(function() {
      if($width !== $window.width()){
        clearTimeout($resize);
        $resize = setTimeout(function(){
          $window.width() < 767 && mobile();
          $window.width() >= 768 && mobile('reset') && fixVbox();
          $width = $window.width();
        }, 500);
      }
  	});

    // fix vbox
    var fixVbox = function(){
      $('.vbox > footer').prev('section').addClass('w-f');
      $('.ie11 .vbox').each(function(){
        $(this).height($(this).parent().height());
      });
    }
    fixVbox();

    // collapse nav
    $(document).on('click', '[data-ride^="collapse"] a', function (e) {
      var $this = $(e.target), $active;
      $this.is('a') || ($this = $this.closest('a'));
      if( $('.nav-vertical').length ){
        return;
      }

      $active = $this.parent().siblings( ".active" );
      $active && $active.find('> a').toggleClass('active') && $active.toggleClass('active').find('> ul:visible').slideUp(200);

      ($this.hasClass('active') && $this.next().slideUp(200)) || $this.next().slideDown(200);
      $this.toggleClass('active').parent().toggleClass('active');

      $this.next().is('ul') && e.preventDefault();
    });

  });
}(window.jQuery);

//snackbar
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?module.exports=a(require("jquery")):a(jQuery)}(function(a){function b(a){return void 0!==a&&null!==a}a(document).ready(function(){a("body").append("<div id=snackbar-container/>")});var c={events:{},on:function(a,b){this.events[a]=this.events[a]||[],this.events[a].push(b)},off:function(a){this.events[a]&&delete this.events[a]},emit:function(a,b){this.events[a]&&this.events[a].forEach(function(a){a(b)})}};a(document).on("click","[data-toggle=snackbar]",function(){a(this).snackbar("toggle")}).on("click","#snackbar-container .snackbar",function(){a(this).snackbar("hide")}),a.snackbar=function(d){if(b(d)&&d===Object(d)){var e,f=!1;d=Object.assign({},a.snackbar.defaults,d),b(d.id)?a("#"+d.id).length?e=a("#"+d.id):(e=a("<div/>").attr("id",""+d.id).attr("class","snackbar"),f=!0):(d.id="snackbar"+Date.now(),e=a("<div/>").attr("id",d.id).attr("class","snackbar"),f=!0);var g=e.hasClass("snackbar-opened");b(d.style)?(g?e.attr("class","snackbar snackbar-opened "+d.style):e.attr("class","snackbar "+d.style),e.attr("data-style",d.style)):g?e.attr("class","snackbar snackbar-opened"):e.attr("class","snackbar"),d.htmlAllowed=!!b(d.htmlAllowed)&&d.htmlAllowed,d.timeout=b(d.timeout)?d.timeout:3e3,e.attr("data-timeout",d.timeout),d.content=d.htmlAllowed?d.content:a("<p>"+d.content+"</p>").text(),b(d.onClose)&&c.on(d.id,d.onClose),b(d.htmlAllowed)&&e.attr("data-html-allowed",d.htmlAllowed),b(d.content)&&(e.find(".snackbar-content").length?e.find(".snackbar-content").html(d.content):e.prepend("<span class=snackbar-content>"+d.content+"</span>"),e.attr("data-content",d.content)),f?e.appendTo("#snackbar-container"):e.insertAfter("#snackbar-container .snackbar:last-child"),b(d.action)&&"toggle"==d.action&&(d.action=g?"hide":"show");var h=Date.now();e.data("animationId1",h),setTimeout(function(){e.data("animationId1")===h&&(b(d.action)&&"show"!=d.action?b(d.action)&&"hide"==d.action&&(e.removeClass("snackbar-opened"),c.emit(d.id),c.off(d.id)):e.addClass("snackbar-opened"))},50);var i=Date.now();return e.data("animationId2",i),0!==d.timeout&&setTimeout(function(){e.data("animationId2")===i&&(e.removeClass("snackbar-opened"),c.emit(d.id),c.off(d.id))},d.timeout),e}return!1},a.snackbar.defaults={},a.fn.snackbar=function(c){if(void 0!==c){var d={};if(this.hasClass("snackbar"))return d={id:this.attr("id"),content:a(this).attr("data-content"),style:a(this).attr("data-style"),timeout:parseInt(a(this).attr("data-timeout")),htmlAllowed:a(this).attr("data-html-allowed")},"show"!==c&&"hide"!==c&&"toggle"!=c||(d.action=c),a.snackbar(d);b(c)&&"show"!==c&&"hide"!==c&&"toggle"!=c||(d={content:a(this).attr("data-content"),style:a(this).attr("data-style"),timeout:a(this).attr("data-timeout"),htmlAllowed:a(this).attr("data-html-allowed")}),b(c)&&(d.id=this.attr("data-snackbar-id"),"show"!==c&&"hide"!==c&&"toggle"!=c||(d.action=c));var e=a.snackbar(d);return this.attr("data-snackbar-id",e.attr("id")),e}}});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


function loader(item) {
    var size = $(item).css('line-height');
    var string = '<div class="loader" style="width: ' + size + ' !important; height: ' + size + ' !important;"><div class="circle"></div><div class="circle"></div><div class="circle"></div><div class="circle"></div><div class="circle"></div></div>';
    var width = $(item).css('width');
    var height = $(item).css('height');
    var attributes = $(item).prop("attributes");
    $(item).css('width', width, 'important');
    $(item).css('height', height, 'important');
    $(item).html(string);
    var objs = $(item).find('div');
    for (var i = 0, len = attributes.length; i < len; i++) {
        if (-1 == $.inArray(attributes[i].name, ['class', 'onclick', 'style']))
        {
            $(objs).attr(attributes[i].name, attributes[i].value);
        }
    }
}

console.log('%cMUNPANEL', 'font-size:5em;color:green');
console.log('%c加入我们，改变世界？请联系xuan@yiad.am', 'color:blue');
console.log('%c让模联更美好！', 'color:grey');
