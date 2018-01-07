$(function () {
   
   //$('#item').bootstrapValidator({});
   
   $('#item').validate({
      lang: 'it'
   });
   
   /*
   var $masonry = $('#masonry_container').masonry({
      columnWidth: '.masonry_items',
      itemSelector: '.masonry_items'
   }); 
   */
   /*
   if ($('#items_languages_description').length) {
      CKEDITOR.replace( 'items_languages_description', {
         width: '100%'
      });
   }
   */
   $('.chosen-select').chosen();
   
});


