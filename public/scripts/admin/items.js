$(function() {
   
   $('#items_form').submit(function() {
      return confirm("confirm");
   });
   
   $('.items_view').click(function () {
      event.preventDefault();
      var href = $(this).attr('href');
      var $modal = $('.bs-example-modal-lg .modal-content');
      $modal.empty();
      $.get(href, function( data ) {
         $modal.html( data );
      });
   });
   
   $( "#items > tbody" ).sortable({
      placeholder: "ui-state-highlight",
      handle: '.items_sort_handle',
      stop: function( event, ui ) {
         $('<input>').attr({
            type: 'hidden',
            name: 'action',
            value: 'order'
         }).appendTo('#items_form');
         $('input[type=checkbox]').attr('checked', 'checked');
         $('#items_form').submit();
      }
   });
   
   $("#check_all").click(function(){
      $('input:checkbox').not(this).prop('checked', this.checked);
   });
   
});

function delete_item (item_code) {
   $('<input>').attr({
      type: 'hidden',
      name: 'action',
      value: 'delete'
   }).appendTo('#items_form');
   $('#item_check_' + item_code).attr('checked', 'checked');
   $('#items_form').submit();
}

function restore_item (item_code) {
   $('<input>').attr({
      type: 'hidden',
      name: 'action',
      value: 'restore'
   }).appendTo('#items_form');
   $('#item_check_' + item_code).attr('checked', 'checked');
   $('#items_form').submit();
}

function confirm_href (txt, loc) {
   if (confirm(txt)) {
      window.location.href = loc;
   } else {
      return false;
   }
}