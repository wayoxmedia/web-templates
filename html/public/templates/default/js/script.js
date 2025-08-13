/******************
Title: Main Scripts
******************/

$(function(){
	"use strict";

	/*******************
	 * Global Variables.
	 ******************/
	let triggerBtn = document.getElementById( 'trigger-navbar' );
	let navbar = document.querySelector('section.navbar');
	let closeBtn = navbar.querySelector('a.navbar-close')
	let transEndEventNames = {
		'WebkitTransition': 'webkitTransitionEnd',
		'MozTransition': 'transitionend',
		'OTransition': 'oTransitionEnd',
		'msTransition': 'MSTransitionEnd',
		'transition': 'transitionend'
	}
	let transEndEventName = transEndEventNames[Modernizr.prefixed('transition')];
	let support = {transitions : Modernizr.csstransitions};

	// wow animation by using with animate css.
	let isiPad = (navigator.userAgent.match(/iPad/i) != null);
	if (($.browser.mobile)||(isiPad)) {
		// disable animation on mobile.
		$("body").removeClass("wow");
	}
	else {
		let wow = new WOW({
			boxClass:     'wow',
			animateClass: 'animated',
			offset:       0,
			mobile:       true
		});
		wow.init();
	}

	// Contact Form Elements.
	let $contactFormResponse = $('#contactFormResponse');
	let $btnSubmitContact = $('#btnSubmitContact');
	let $iptName = $("#iptName");
	let $iptEmail = $("#iptEmail");
	let $iptMessage = $("#iptMessage");

	// Subscribe Form Elements.
	let $submitFormResponse = $('#submitFormResponse');
	let $btnSubmitSubscribe = $('#btnSubmitSubscribe');
	let $selAddressType = $("#selAddressType");
	let $iptAddress = $("#iptAddress");

	/****************
	 * Global Events.
	 ***************/
	// Full screen pre loader
	$(window).load(function(){
		$("#pre-loader").delay(500).fadeOut(2000);
		$(".preload-logo").addClass('zoomOutUp');
		$(".loader").addClass('zoomOutDown');
	});

	//Logo fadeIn fadeOut on
	$(window).scroll(function(){
	  if($(this).scrollTop() > $(window).height()/2.2) $('.logo-wrapper').fadeOut('slow');
	  if($(this).scrollTop() < $(window).height()/2.2) $('.logo-wrapper').fadeIn('slow');
	});

	//Using the smooth scroll for smooth navigation
	smoothScroll.init({
		speed: 500, // Integer. How fast to complete the scroll in milliseconds.
		easing: 'easeInOutCubic', // Easing pattern to use.
		updateURL: false, // Boolean. To update or not the URL with the anchor hash on scroll.
		offset: 0, // Integer. How far to offset the scrolling anchor location in pixels.
		callbackBefore: function ( toggle, anchor ) {}, // Function to run before scrolling.
		callbackAfter: function ( toggle, anchor ) {} // Function to run after scrolling.
	});

	// Navbar toggle.
	triggerBtn.addEventListener('click', toggleOverlay);
	closeBtn.addEventListener('click', toggleOverlay);
	$('section.navbar nav ul li a').click(() => {
		toggleOverlay();
	});

	// Contact Form Events.
	$('#contact_form').on('submit', function(event) {
		event.preventDefault();
		$contactFormResponse.addClass('hidden');
		$contactFormResponse.removeClass('alert-success alert-danger');
		// Disable the submit button to prevent multiple clicks.
		$btnSubmitContact.prop('disabled', true);

		// Validate the form.
		let isValid = true;
		let errorMessage = [];
		let selectedNameValue = $iptName.val();
		let selectedEmailValue = $iptEmail.val();
		let selectedMessageValue = $iptMessage.val();

		// No empty fields.
		if (selectedNameValue === "") {
			isValid = false;
			errorMessage.push("Por favor, ingrese su nombre.");
			$iptName.focus();
		}
		if (selectedEmailValue === "") {
			isValid = false;
			errorMessage.push("Por favor, ingrese su correo electrónico.");
			$iptEmail.focus();
		}
		if (selectedMessageValue === "") {
			isValid = false;
			errorMessage.push("Por favor, ingrese su mensaje.");
			$iptMessage.focus();
		}

		// Validate email
		if (selectedEmailValue !== "" && !validateEmail(selectedEmailValue)) {
			isValid = false;
			errorMessage.push("Por favor, ingrese un correo electrónico válido.");
			$iptEmail.focus();
		}

		if (!isValid) {
			$contactFormResponse.removeClass('hidden');
			$contactFormResponse.addClass('alert-danger');
			$contactFormResponse.html(errorMessage.join('<br>'));
			$btnSubmitContact.prop('disabled', false);
			return;
		}
		else {
			$contactFormResponse.addClass('hidden');
			$contactFormResponse.removeClass('alert-danger');
			$contactFormResponse.html("");
		}

		let formData = new FormData(this);

		$.ajax({
			url:  msaConfig.apiUrl + '/contact-form',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			headers: {
				'Accept': 'application/json',
			},
			success: function(data) {
				$contactFormResponse.removeClass('hidden');
				$contactFormResponse.addClass('alert-success');
				$contactFormResponse.html("Su mensaje ha sido enviado, pronto nos pondremos en contacto con usted.");
				if (msaConfig.LOCAL_ENV) console.log('Data: ', data);
			},
			error: function(xhr) {
				$contactFormResponse.removeClass('hidden');
				$contactFormResponse.addClass('alert-danger');
				$contactFormResponse.html(processAjaxErrors(xhr));
				if (msaConfig.LOCAL_ENV) console.log('Request failed', xhr);
			}
		});
	});

	// Subscribe Form Events.
	$selAddressType.on('change', function () {
		let selectedValue = $(this).val();

		if (selectedValue === 'e') {
			$iptAddress.attr('placeholder', 'Su Email');
		} else if (selectedValue === 'p') {
			$iptAddress.attr('placeholder', 'Su Teléfono');
		}
	});

	$('#subscribe_form').on('submit', function(event) {
		event.preventDefault();
		$submitFormResponse.addClass('hidden');
		$submitFormResponse.removeClass('alert-success alert-danger');
		// Disable the submit button to prevent multiple clicks.
		$btnSubmitSubscribe.prop('disabled', true);

		// Validate the form.

		let isValid = true;
		let errorMessage = "";
		let selectedAddressTypeValue = $selAddressType.val();
		let selectedAddressValue = $iptAddress.val();

		// Validate email
		if (selectedAddressTypeValue === "e") {
			if (!validateEmail(selectedAddressValue)) {
				isValid = false;
				errorMessage = "Por favor, ingrese un correo electrónico válido.";
			}
		} else if (selectedAddressTypeValue === "p") {
			// Validate phone number
			const phoneRegex = /^[0-9]{10}$/; // Example: 10-digit phone number
			if (!phoneRegex.test(selectedAddressValue)) {
				isValid = false;
				errorMessage = "Por favor, ingrese un número de teléfono válido.";
			}
		}

		if (!isValid) {
			$submitFormResponse.removeClass('hidden');
			$submitFormResponse.addClass('alert-danger');
			$submitFormResponse.html(errorMessage);
			$btnSubmitSubscribe.prop('disabled', false);
			return;
		}
		else {
			$submitFormResponse.removeClass('hidden');
			$submitFormResponse.removeClass('alert-danger');
			$submitFormResponse.html("");
		}

		let formData = new FormData(this);

		$.ajax({
			url:  msaConfig.apiUrl + '/subscribe-form',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			headers: {
				'Accept': 'application/json',
			},
			success: function(data) {
				$submitFormResponse.removeClass('hidden');
				$submitFormResponse.addClass('alert-success');
				$submitFormResponse.html("Su subscripción ha sido recibida.");
				if (msaConfig.LOCAL_ENV) console.log('Data: ', data);
			},
			error: function(xhr) {
				$submitFormResponse.removeClass('hidden');
				$submitFormResponse.addClass('alert-danger');
				$submitFormResponse.html(processAjaxErrors(xhr));
				if (msaConfig.LOCAL_ENV) console.log('Request failed', xhr);
			},
			complete: function() {
				// Re-enable the submit button after the request is complete.
				$btnSubmitSubscribe.prop('disabled', false);
			}
		});
	});

	/*******************
	 * Global Functions.
	 ******************/

	/**
	 * Toggle the menu overlay.
	 */
	function toggleOverlay() {
		if (classie.has(navbar, 'open')) {
			classie.remove(navbar, 'open');
			classie.add(navbar, 'close');
			let onEndTransitionFn = function(ev) {
				if (support.transitions) {
					if (ev.propertyName !== 'visibility') return;
					this.removeEventListener(transEndEventName, onEndTransitionFn);
				}
				classie.remove( navbar, 'close' );
			};
			if (support.transitions) {
				navbar.addEventListener(transEndEventName, onEndTransitionFn);
			}
			else {
				onEndTransitionFn();
			}
		}
		else if(!classie.has( navbar, 'close' )) {
			classie.add( navbar, 'open' );
		}
	}

	/**
	 * Validate email format.
	 *
	 * @param {string} email - The email address to validate.
	 * @returns {boolean}
	 */
	function validateEmail(email) {
		const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		return emailRegex.test(email);
	}

	function processAjaxErrors(xhr) {
		// Handle AJAX errors here
		let errorMsg = 'Ha ocurrido un error, por favor intente m&aacute;s tarde.'
		if (xhr.responseJSON) {
			if (xhr.responseJSON.errors) {
				let errorList = [];
				for (let key in xhr.responseJSON.errors) {
					if (xhr.responseJSON.errors.hasOwnProperty(key)) {
						errorList.push(xhr.responseJSON.errors[key]);
					}
				}
				if (errorList.flat().length > 1) {
					errorMsg = 'Por favor revise los siguientes errores: <br>';
				} else {
					errorMsg = 'Por favor revise el siguiente error: <br>';
				}
				errorMsg += errorList.flat().join('<br>');
				if (msaConfig.LOCAL_ENV) console.log('Errors: ', JSON.stringify(xhr.responseJSON.errors));
			} else if (xhr.responseJSON.message) {
				errorMsg = xhr.responseJSON.message;
			}
		}

		return errorMsg;
	}
});
