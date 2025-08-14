define( [
	"../core.js"
], function( jQuery ) {

"use strict";

jQuery.readyException = function( error ) {
	window.setTimeout( function() {
		throw error;
	} );
};

} );
