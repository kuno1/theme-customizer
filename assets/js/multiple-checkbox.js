/**
 * Multiple Checkbox.
 */

/* global jQuery:false */

jQuery( document ).ready( function( $ ) {

	$( '.kunoichi-customizer-multiple-checkbox input[type="checkbox"]' ).click( function () {
		var checkbox_values = $( this ).parents( '.customize-control' ).find( 'input[type="checkbox"]:checked' ).map( function () {
			return this.value;
		} ).get().join( ',' );

		$( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
	} );
} );
