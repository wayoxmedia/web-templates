define( [
	"./cssExpand.js"
], function( cssExpand ) {
	"use strict";

	return new RegExp( cssExpand.join( "|" ), "i" );
} );
