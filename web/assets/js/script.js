/* Template	:	ICO Crypto v1.0.0 */
(function($){
	'use strict';
	var $win = $(window), $doc = $(document), $body_m = $('body'), $navbar = $('.navbar');

	// Touch Class
	if (!("ontouchstart" in document.documentElement)) {
		$body_m.addClass("no-touch");
	}
	// Get Window Width
	function winwidth () {
		return $win.width();
	}
	var wwCurrent = winwidth();
	$win.on('resize', function () {
		wwCurrent = winwidth();
	});
	// Sticky
	var $is_sticky = $('.is-sticky');
	if ($is_sticky.length > 0 ) {
		var $navm = $('#mainnav').offset();
		$win.scroll(function(){
			var $scroll = $win.scrollTop();
			if ($win.width() > 991) {
				if($scroll > $navm.top ){
					if(!$is_sticky.hasClass('has-fixed')) {$is_sticky.addClass('has-fixed');}
				} else {
					if($is_sticky.hasClass('has-fixed')) {$is_sticky.removeClass('has-fixed');}
				}
			} else {
				if($is_sticky.hasClass('has-fixed')) {$is_sticky.removeClass('has-fixed');}
			}
		});
	}

	// OnePage Scrolling
	$('a.menu-link[href*="#"]:not([href="#"])').on("click", function() {
		if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
			var toHash = $(this.hash), toHashN = (this.hash.slice(1)) ? $('[name=' + this.hash.slice(1) + ']') : false, nbar = (wwCurrent >= 992) ? $navbar.height() - 1 : 0;

			toHash = toHash.length ? toHash : toHashN;
			if (toHash.length) {
				$('html, body').animate({
					scrollTop: (toHash.offset().top - nbar)
				}, 1000, "easeInOutExpo");
				return false;
			}
		}
	});

	// Active page menu when click
	var CurURL = window.location.href, urlSplit = CurURL.split("#");
	var $nav_link = $(".nav li a");
	if ($nav_link.length > 0) {
		$nav_link.each(function() {
			if (CurURL === (this.href) && (urlSplit[1]!=="")) {
				$(this).closest("li").addClass("active").parent().closest("li").addClass("active");
			}
		});
	}

	// Bootstrap Dropdown 
	var $dropdown_menu = $('.dropdown');
	if ($dropdown_menu.length > 0 ) {
		$dropdown_menu.on("mouseover",function(){
			if ($win.width() > 991) {
				$('.dropdown-menu', this).not('.in .dropdown-menu').stop().fadeIn("400");
				$(this).addClass('open');
			}
		});
		$dropdown_menu.on("mouseleave",function(){
			if ($win.width() > 991) {
				$('.dropdown-menu', this).not('.in .dropdown-menu').stop().fadeOut("400");
				$(this).removeClass('open');
			}
		});
		$dropdown_menu.on("click",function(){
			if ($win.width() < 991) {
				$(this).children('.dropdown-menu').fadeToggle(400);
				$(this).toggleClass('open');
				return false;
			}
		});

	}
	$win.on('resize', function() {
		$('.navbar-collapse').removeClass('in');
		$dropdown_menu.children('.dropdown-menu').fadeOut("400");
	});


	// Nav collapse
	$('.menu-link').on("click",function() {
		$('.navbar-collapse').collapse('hide');
	});

	// Count Down
	var $count_token = $('.token-countdown');
	if ($count_token.length > 0 ) {
		$.ajax({
			url: 'index.php?c=subscribe&m=schedule',
			type: 'get',
			dataType: 'json',
			success: function(data) {
				if(data.result == 0) {
					if(data.sell_status == -1) {
						$count_token.each(function () {
							var $self = $(this), datetime = data.start_time;
							$self.countdown(datetime).on('update.countdown', function (event) {
								$(this).html(event.strftime('' + '<div class="col"><span class="countdown-time">%D</span><span class="countdown-text">Days</span></div>' + '<div class="col"><span class="countdown-time">%H</span><span class="countdown-text">Hours</span></div>' + '<div class="col"><span class="countdown-time">%M</span><span class="countdown-text">Minutes</span></div>' + '<div class="col"><span class="countdown-time countdown-time-last">%S</span><span class="countdown-text">Seconds</span></div>'));
							});
						});
					} else {
						$('#sell_time').hide()
						$('#sell_info').show()
						$('#sell_status').html(data.start_status);
						$('#sell_num').html(data.eth_num);
						if(data.sell_status == 0) {
							$('#go_buy').attr('href', data.go_buy);
						}
						// 融资进度条
						$('.in-pro').css({'width': data.show_width});
						// 融资进度条
					}
				}
			}
		});
	}
	// News
	var $newsul = $('#news_tip');
	if ($newsul.length > 0 ) {
		var lang ='',newsurl = window.location.href.toString(),
			news_cn = newsurl.indexOf('cn')>-1,
			news_kr = newsurl.indexOf('kr')>-1;
		if(news_cn){
			lang = 'cn'
		}else if(news_kr){
			lang = 'kr'
		}else{
			lang = 'en'
		}
		$.ajax({
			url: "/index.php",
			data:{lang:lang,c:'api',m:'getnews'},
			type: "get",
			dataType: "json",
			success: function(data) {
				var newsul = $("#news_tip");
				if(news_cn ){
					var newshtml=data.map(addr => `
        				<li class="news-list">
         					<div class="news-title">
          					<i></i>
          					<a href=${addr.cn_url} target="_blank">${addr.cn_title}</a >
         					</div>
         					<span>${addr.publish_time}</span>
        				</li>`).join('');
					newsul.html(newshtml);
				}else if(news_kr) {
					var newshtml=data.map(addr => `
       					<li class="news-list">
         					<div class="news-title">
          					<i></i>
          					<a href=${addr.kr_url} target="_blank">${addr.kr_title}</a >
         					</div>
         					<span>${addr.publish_time}</span>
        				</li>`).join('');
					newsul.html(newshtml);
				}else{
					var newshtml=data.map(addr => `
         				<li class="news-list">
          				<div class="news-title">
           					<i></i>
           					<a href=${addr.en_url} target="_blank">${addr.en_title}</a >
          				</div>
          				<span>${addr.publish_time}</span>
         				</li>`).join('');
					newsul.html(newshtml)
				}
			}
		});
	}else {
		$('#news').hide();
	}
	// 移动端页面跳转
	$(".new-mb").click(function(){
		$(".new-mb-list").show();
	});
	//POPUP - Content
	var $content_popup = $('.content-popup');
	if ($content_popup.length > 0 ) {
		$content_popup.magnificPopup({
			type: 'inline',
			preloader: true,
			removalDelay: 400,
			mainClass: 'mfp-fade bg-team-exp'
		});
	}

	//POPUP - Video
	var $video_play = $('.video-play');
	if ($video_play.length > 0 ) {
		$video_play.magnificPopup({
			type: 'iframe',
			removalDelay: 160,
			preloader: true,
			fixedContentPos: false,
			callbacks: {
				beforeOpen: function() {
					this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
					this.st.mainClass = this.st.el.attr('data-effect');
				}
			},
		});
	}

	//ImageBG
	var $imageBG = $('.imagebg');
	if ($imageBG.length > 0) {
		$imageBG.each(function(){
			var $this = $(this),
				$that = $this.parent(),
				overlay = $this.data('overlay'),
				image = $this.children('img').attr('src');
			var olaytyp = (typeof overlay!=='undefined' && overlay!=='') ? overlay.split('-') : false;

			// If image found
			if (typeof image!=='undefined' && image !==''){
				if (!$that.hasClass('has-bg-image')) {
					$that.addClass('has-bg-image');
				}
				if ( olaytyp!=='' && (olaytyp[0]==='dark') ) {
					if (!$that.hasClass('light')) {
						$that.addClass('light');
					}
				}
				$this.css("background-image", 'url("'+ image +'")').addClass('bg-image-loaded');
			}
		});
	}

	function getCookie(name){
		var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
		if(arr != null) return unescape(arr[2]); return null;
	}

	// Ajax Form Submission
	var subscribeForm = $('#subscribe-form');
	if (subscribeForm.length > 0) {
		if( !$().validate || !$().ajaxSubmit ) {
			console.log('contactForm: jQuery Form or Form Validate not Defined.');
			return true;
		}
		// SubscribeForm
		if (subscribeForm.length > 0) {
			var sf_results = subscribeForm.find('.subscribe-results');
			subscribeForm.validate({
				invalidHandler: function () { sf_results.slideUp(400); },
				submitHandler: function(form) {
					$('#csrf_test_name').val(getCookie('csrf_cookie_name'));
					sf_results.slideUp(400);
					$(form).ajaxSubmit({
						target: sf_results, dataType: 'json',
						success: function(data) {
							var type = (data.result==='error') ? 'alert-danger' : 'alert-success';
							sf_results.removeClass( 'alert-danger alert-success' ).addClass( 'alert ' + type ).html(data.message).slideDown(400);
							if (data.result !== 'error') { $(form).clearForm(); }
						}
					});
				}
			});
		}
	}

	// Input Animation
	var $inputline = $('.input-line');
	if ($inputline.length > 0) {
		$inputline.each(function(){
			var $this = $(this);
			var $thisval = $(this).val();
			if($thisval.length > 0) {
				$this.parent().addClass('input-focused');
			}
			$this.on('focus', function(){
				$this.parent().addClass('input-focused');
			});
			$this.on('blur', function(){
				$this.parent().removeClass('input-focused');
				var $afterblur = $(this).val();
				if($afterblur.length > 0) {
					$this.parent().addClass('input-focused');
				}
			});

		});
	}

	// On Scroll Animatio6
	var $aniKey = $('.animated');
	if($().waypoint && $aniKey.length > 0){
		$win.on('load', function() {
			$aniKey.each(function(){
				var aniWay = $(this), typ = aniWay.data("animate"), dur = aniWay.data("duration"), dly = aniWay.data("delay");
				aniWay.waypoint(function(){
					aniWay.addClass("animated "+typ).css("visibility", "visible");
					if(dur){
						aniWay.css('animation-duration', dur+'s');
					}
					if(dly){
						aniWay.css('animation-delay', dly+'s');
					}
				}, { offset: '93%' });
			});
		});
	}

	// remove ani
	var $navtoggler = $('.navbar-toggler');
	if ($navtoggler.length > 0) {
		$navtoggler.on("click",function(){
			$('.remove-animation').removeClass('animated');
		});
	}


	// Preloader
	var $preload = $('#preloader'), $loader = $('#loader');
	if ($preload.length > 0) {
		$win.on('load', function() {
			$loader.fadeOut(300);
			$body_m.addClass("loaded");
			$preload.delay(700).fadeOut(300);
		});
	}

	$(".btn-toolbar .subbutton").click(function(){
		var reg = /[0x]{2}[0-9a-zA-Z]{40,}/
		if($('#wallet').val() =='' || reg.test($('#wallet').val()) === false){
			$('#msg_tips').html('please input wallet address...');
			return false;
		}

		$('#msg_tips').html('checking...');
		$.ajax({
			url: 'index.php?c=WhiteList&m=check&wallet=' + ($('#wallet').val()),
			type: 'get',
			dataType: 'json',
			success: function(data) {
				if(data.result == 0) {
					$('#msg_tips').html(data.message);
				}
			}
		});
	});

	// particlesJS
	var $particles_js = $('#particles-js');
	if ($particles_js.length > 0 ) {
		particlesJS('particles-js',
			// Update your personal code.
			{
				"particles": {
					"number": {
						"value": 50,
						"density": {
							"enable": true,
							"value_area": 800
						}
					},
					"color": {
						"value": "#2b56f5"
					},
					"shape": {
						"type": "circle",
						"opacity": 0.08,
						"stroke": {
							"width": 0,
							"color": "#2b56f5"
						},
						"polygon": {
							"nb_sides": 5
						},
						"image": {
							"src": "img/github.svg",
							"width": 100,
							"height": 100
						}
					},
					"opacity": {
						"value": 0.15,
						"random": false,
						"anim": {
							"enable": false,
							"speed": 1,
							"opacity_min": 0.12,
							"sync": false
						}
					},
					"size": {
						"value": 6,
						"random": true,
						"anim": {
							"enable": false,
							"speed": 40,
							"size_min": 0.08,
							"sync": false
						}
					},
					"line_linked": {
						"enable": true,
						"distance": 150,
						"color": "#2b56f5",
						"opacity": 0.15,
						"width": 1.3
					},
					"move": {
						"enable": true,
						"speed": 6,
						"direction": "none",
						"random": false,
						"straight": false,
						"out_mode": "out",
						"bounce": false,
						"attract": {
							"enable": false,
							"rotateX": 600,
							"rotateY": 1200
						}
					}
				},
				"interactivity": {
					"detect_on": "canvas",
					"events": {
						"onhover": {
							"enable": true,
							"mode": "repulse"
						},
						"onclick": {
							"enable": true,
							"mode": "push"
						},
						"resize": true
					},
					"modes": {
						"grab": {
							"distance": 400,
							"line_linked": {
								"opacity": 1
							}
						},
						"bubble": {
							"distance": 400,
							"size": 40,
							"duration": 2,
							"opacity": 8,
							"speed": 3
						},
						"repulse": {
							"distance": 200,
							"duration": 0.4
						},
						"push": {
							"particles_nb": 4
						},
						"remove": {
							"particles_nb": 2
						}
					}
				},
				"retina_detect": true
			}
			// Stop here.
		);
	}
})(jQuery);
