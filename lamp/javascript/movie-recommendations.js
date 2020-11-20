$(document).ready(function() {
   $("#validate").button({
      icons: {
         primary: "ui-icon-gear",
      	secondary: "ui-icon-triangle-1-s"
      }
   });
   $("#validate").on('click', function() {
       document.mov_rec.submit();
   });
});