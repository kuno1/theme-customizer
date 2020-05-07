/**
 * Multiple Checkbox.
 */

/* global jQuery:false */

jQuery( document ).ready( function( $ ) {
	$( '.customize-control-checkbox-multiple input[type="checkbox"]' ).on(
		'change',
		function() {
			var checkbox_values = $( this ).parents( '.customize-control' ).find( 'input[type="checkbox"]:checked' ).map( function() {
				return this.value;
			} ).get().join( ',' );

			$( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
		}
	);
} );
