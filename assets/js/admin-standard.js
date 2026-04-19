/*
 * Tabs — vanilla JS (no jQuery dependency)
 */
document.addEventListener( 'DOMContentLoaded', function () {
	'use strict';

	var tabLinks = document.querySelectorAll( '#tabs ul li a' );

	tabLinks.forEach( function ( link ) {

		link.addEventListener( 'click', handleTabActivation );
		link.addEventListener( 'keyup', function ( e ) {
			if ( e.key === 'Enter' ) {
				handleTabActivation.call( this, e );
			}
		} );

	} );

	function handleTabActivation( e ) {
		e.preventDefault();

		var targetId  = this.getAttribute( 'href' );
		var targetPanel = document.querySelector( targetId );

		if ( ! targetPanel ) {
			return;
		}

		// Deactivate all tab list items.
		document.querySelectorAll( '.azrcrv-ui-state-active' ).forEach( function ( el ) {
			el.classList.remove( 'azrcrv-ui-state-active' );
			el.setAttribute( 'aria-selected', 'false' );
			el.setAttribute( 'aria-expanded', 'false' );
		} );

		// Activate the clicked tab list item.
		var parentLi = this.closest( 'li' );
		parentLi.classList.add( 'azrcrv-ui-state-active' );
		parentLi.setAttribute( 'aria-selected', 'true' );
		parentLi.setAttribute( 'aria-expanded', 'true' );

		// Hide all sibling tab panels.
		var tabPanels = this.closest( 'ul' ).parentElement.querySelectorAll( '.azrcrv-ui-tabs-scroll' );
		tabPanels.forEach( function ( panel ) {
			panel.classList.add( 'azrcrv-ui-tabs-hidden' );
			panel.setAttribute( 'aria-hidden', 'true' );
		} );

		// Show the target panel.
		targetPanel.classList.remove( 'azrcrv-ui-tabs-hidden' );
		targetPanel.setAttribute( 'aria-hidden', 'false' );
	}

} );
