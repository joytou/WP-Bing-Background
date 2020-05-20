/**
 * 
 */
//利用闭包函数，避免污染全局
( function() {
	
	/*[ ...document.querySelectorAll( "input[type=range]" ) ].map( ( item ) => {
		setInterval( () => {
			document.getElementById( item.id + "-value" ).innerHTML = item.value;
		}, 0.01 );
	});*/
	var timer = null;
	[ ...document.querySelectorAll( "input[type=range]" ) ].map( ( item ) => {
		item.addEventListener( "mouseover", startTimerForRange );
		item.addEventListener( "mouseout", stopTimerForRange );
		item.addEventListener( "touchstart", startTimerForRange );
		item.addEventListener( "touchend", stopTimerForRange );
	});
	function startTimerForRange( e ) {
		timer = setInterval( () => { timerForRange( e.target.id ) }, 0.01 );
	}
	function stopTimerForRange( e ) {
		clearInterval( timer );
	}
	function timerForRange( id ) {
		document.getElementById( id + "-value" ).innerHTML = document.getElementById( id ).value;
	}
} )();