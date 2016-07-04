/* global screenReaderText */
/**
 * Theme functions file.
 *
 * Contains handlers for navigation and widget area.
 */

(function ($) {
	var body, masthead, menuToggle, siteNavigation, socialNavigation, siteHeaderMenu, resizeTimer;

	function initMainNavigation(container) {

		// Add dropdown toggle that displays child menu items.
		var dropdownToggle = $('<button />', {
			'class': 'dropdown-toggle',
			'aria-expanded': false
		}).append($('<span />', {
			'class': 'screen-reader-text',
			text: screenReaderText.expand
		}));

		container.find('.menu-item-has-children > a').after(dropdownToggle);

		// Toggle buttons and submenu items with active children menu items.
		container.find('.current-menu-ancestor > button').addClass('toggled-on');
		container.find('.current-menu-ancestor > .sub-menu').addClass('toggled-on');

		// Add menu items with submenus to aria-haspopup="true".
		container.find('.menu-item-has-children').attr('aria-haspopup', 'true');

		container.find('.dropdown-toggle').click(function (e) {
			var _this            = $(this),
				screenReaderSpan = _this.find('.screen-reader-text');

			e.preventDefault();
			_this.toggleClass('toggled-on');
			_this.next('.children, .sub-menu').toggleClass('toggled-on');

			// jscs:disable
			_this.attr('aria-expanded', _this.attr('aria-expanded') === 'false' ? 'true' : 'false');
			// jscs:enable
			screenReaderSpan.text(screenReaderSpan.text() === screenReaderText.expand ? screenReaderText.collapse : screenReaderText.expand);
		});
	}

	initMainNavigation($('.main-navigation'));

	masthead         = $('#masthead');
	menuToggle       = masthead.find('#menu-toggle');
	siteHeaderMenu   = masthead.find('#site-header-menu');
	siteNavigation   = masthead.find('#site-navigation');
	socialNavigation = masthead.find('#social-navigation');

	// Enable menuToggle.
	(function () {

		// Return early if menuToggle is missing.
		if (!menuToggle.length) {
			return;
		}

		// Add an initial values for the attribute.
		menuToggle.add(siteNavigation).add(socialNavigation).attr('aria-expanded', 'false');

		menuToggle.on('click.wpsaas', function () {
			$(this).add(siteHeaderMenu).toggleClass('toggled-on');

			// jscs:disable
			$(this).add(siteNavigation).add(socialNavigation).attr('aria-expanded', $(this).add(siteNavigation).add(socialNavigation).attr('aria-expanded') === 'false' ? 'true' : 'false');
			// jscs:enable
		});
	})();

	// Fix sub-menus for touch devices and better focus for hidden submenu items for accessibility.
	(function () {
		if (!siteNavigation.length || !siteNavigation.children().length) {
			return;
		}

		// Toggle `focus` class to allow submenu access on tablets.
		function toggleFocusClassTouchScreen() {
			if (window.innerWidth >= 910) {
				$(document.body).on('touchstart.wpsaas', function (e) {
					if (!$(e.target).closest('.main-navigation li').length) {
						$('.main-navigation li').removeClass('focus');
					}
				});
				siteNavigation.find('.menu-item-has-children > a').on('touchstart.wpsaas', function (e) {
					var el = $(this).parent('li');

					if (!el.hasClass('focus')) {
						e.preventDefault();
						el.toggleClass('focus');
						el.siblings('.focus').removeClass('focus');
					}
				});
			} else {
				siteNavigation.find('.menu-item-has-children > a').unbind('touchstart.wpsaas');
			}
		}

		if ('ontouchstart' in window) {
			$(window).on('resize.wpsaas', toggleFocusClassTouchScreen);
			toggleFocusClassTouchScreen();
		}

		siteNavigation.find('a').on('focus.wpsaas blur.wpsaas', function () {
			$(this).parents('.menu-item').toggleClass('focus');
		});
	})();

	// Add the default ARIA attributes for the menu toggle and the navigations.
	function onResizeARIA() {
		if (window.innerWidth < 910) {
			if (menuToggle.hasClass('toggled-on')) {
				menuToggle.attr('aria-expanded', 'true');
			} else {
				menuToggle.attr('aria-expanded', 'false');
			}

			if (siteHeaderMenu.hasClass('toggled-on')) {
				siteNavigation.attr('aria-expanded', 'true');
				socialNavigation.attr('aria-expanded', 'true');
			} else {
				siteNavigation.attr('aria-expanded', 'false');
				socialNavigation.attr('aria-expanded', 'false');
			}

			menuToggle.attr('aria-controls', 'site-navigation social-navigation');
		} else {
			menuToggle.removeAttr('aria-expanded');
			siteNavigation.removeAttr('aria-expanded');
			socialNavigation.removeAttr('aria-expanded');
			menuToggle.removeAttr('aria-controls');
		}
	}

	// Add 'below-entry-meta' class to elements.
	function belowEntryMetaClass(param) {
		if (body.hasClass('page') || body.hasClass('search') || body.hasClass('single-attachment') || body.hasClass('error404')) {
			return;
		}

		$('.entry-content').find(param).each(function () {
			var element              = $(this),
				elementPos           = element.offset(),
				elementPosTop        = elementPos.top,
				entryFooter          = element.closest('article').find('.entry-footer'),
				entryFooterPos       = entryFooter.offset(),
				entryFooterPosBottom = entryFooterPos.top + ( entryFooter.height() + 28 ),
				caption              = element.closest('figure'),
				newImg;

			// Add 'below-entry-meta' to elements below the entry meta.
			if (elementPosTop > entryFooterPosBottom) {

				// Check if full-size images and captions are larger than or equal to 840px.
				if ('img.size-full' === param) {

					// Create an image to find native image width of resized images (i.e. max-width: 100%).
					newImg     = new Image();
					newImg.src = element.attr('src');

					$(newImg).on('load.wpsaas', function () {
						if (newImg.width >= 840) {
							element.addClass('below-entry-meta');

							if (caption.hasClass('wp-caption')) {
								caption.addClass('below-entry-meta');
								caption.removeAttr('style');
							}
						}
					});
				} else {
					element.addClass('below-entry-meta');
				}
			} else {
				element.removeClass('below-entry-meta');
				caption.removeClass('below-entry-meta');
			}
		});
	}

	$(document).ready(function () {
		body = $(document.body);

		$(window)
			.on('load.wpsaas', onResizeARIA)
			.on('resize.wpsaas', function () {
				clearTimeout(resizeTimer);
				resizeTimer = setTimeout(function () {
					belowEntryMetaClass('img.size-full');
					belowEntryMetaClass('blockquote.alignleft, blockquote.alignright');
				}, 300);
				onResizeARIA();
			});

		belowEntryMetaClass('img.size-full');
		belowEntryMetaClass('blockquote.alignleft, blockquote.alignright');


		$('.gallery a.colorbox').swipebox({
			useCSS: true, // false will force the use of jQuery for animations
			useSVG: true, // false to force the use of png for buttons
			initialIndexOnArray: 0, // which image index to init when a array is passed
			hideCloseButtonOnMobile: false, // true will hide the close button on mobile devices
			removeBarsOnMobile: false, // false will show top bar on mobile devices
			hideBarsDelay: 0, // delay before hiding bars on desktop
			videoMaxWidth: 1140, // videos max width
			beforeOpen: function () {
			}, // called before opening
			afterOpen: null, // called after opening
			afterClose: function () {
			}, // called after closing
			loopAtEnd: false // true will return to the first image after the last image is reached
		});

		$('a.colorbox').swipebox({
			useCSS: true, // false will force the use of jQuery for animations
			useSVG: true, // false to force the use of png for buttons
			initialIndexOnArray: 0, // which image index to init when a array is passed
			hideCloseButtonOnMobile: false, // true will hide the close button on mobile devices
			removeBarsOnMobile: false, // false will show top bar on mobile devices
			hideBarsDelay: 0, // delay before hiding bars on desktop
			videoMaxWidth: 1140, // videos max width
			beforeOpen: function () {
			}, // called before opening
			afterOpen: null, // called after opening
			afterClose: function () {
			}, // called after closing
			loopAtEnd: false // true will return to the first image after the last image is reached
		});

	});
})(jQuery);

// Smooth scroll for anchors
(function ($) {
	$('a[href*="#"]:not([href="#"])').click(function () {
		if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target     = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
			if (target.length) {
				$('html,body').animate({
					scrollTop: target.offset().top
				}, 1000);
				return false;
			}
		}
	});

})(jQuery);
