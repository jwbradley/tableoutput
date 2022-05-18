function doLoad() {
   alert( "The load event is executing" );
}
if ( window.addEventListener ) {
   window.addEventListener( "load", function() { document.getElementById('BARPT').disabled = false; }, false);
}
else
   if ( window.attachEvent ) {
      window.attachEvent( "onload", function() { document.getElementById('BARPT').disabled = false; }, false);
} else
      if ( window.onLoad ) {
         window.onload = doLoad;
}