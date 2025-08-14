define( [
	"./whitespace.js"
], function( whitespace ) {

"use strict";

return new RegExp(
	"^" + whitespace + "+|((?:^|[^\\\\])(?:\\\\.)*)" + whitespace + "+$",
	"g"
);

} );
