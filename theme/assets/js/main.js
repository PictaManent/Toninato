/**
 * Toninato — script principali.
 */
( function () {
	'use strict';

	document.documentElement.classList.add( 'js' );

	/* Menu mobile ------------------------------------------------------- */
	var toggle = document.querySelector( '.nav-toggle' );
	var nav = document.getElementById( 'primary-nav' );

	if ( toggle && nav ) {
		var setOpen = function ( open ) {
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			nav.classList.toggle( 'is-open', open );
		};

		toggle.addEventListener( 'click', function () {
			setOpen( toggle.getAttribute( 'aria-expanded' ) !== 'true' );
		} );

		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && nav.classList.contains( 'is-open' ) ) {
				setOpen( false );
				toggle.focus();
			}
		} );

		window.matchMedia( '(min-width: 1200px)' ).addEventListener( 'change', function ( mq ) {
			if ( mq.matches ) {
				setOpen( false );
			}
		} );
	}

	/* Comparsa degli elementi allo scorrimento -------------------------- */
	var targets = document.querySelectorAll(
		'.section-head, .service-card, .value, .area, .gallery__item, .cta__card'
	);

	if ( ! ( 'IntersectionObserver' in window ) ) {
		return;
	}

	var observer = new IntersectionObserver( function ( entries ) {
		entries.forEach( function ( entry ) {
			if ( entry.isIntersecting ) {
				entry.target.classList.add( 'is-visible' );
				observer.unobserve( entry.target );
			}
		} );
	}, { rootMargin: '0px 0px -60px 0px' } );

	targets.forEach( function ( el, i ) {
		el.classList.add( 'reveal' );
		el.style.transitionDelay = ( ( i % 4 ) * 80 ) + 'ms';
		observer.observe( el );
	} );
}() );
