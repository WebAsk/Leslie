$(function() {
   
   $('#items').submit(function() {
      return confirm("confirm");
   });
   
   $( "#datatable-checkbox > tbody" ).sortable({
      placeholder: "ui-state-highlight",
      handle: '.items_sort_handle',
      stop: function( event, ui ) {
         $('<input>').attr({
            type: 'hidden',
            name: 'action',
            value: 'order'
         }).appendTo('#items');
         $('input[type=checkbox]').attr('checked', 'checked');
         $('#items').submit();
      }
   });
   
   $("#check_all").click(function(){
      $('input:checkbox').not(this).prop('checked', this.checked);
   });
   
});

function delete_item (item_id) {
   $('<input>').attr({
      type: 'hidden',
      name: 'action',
      value: 'delete'
   }).appendTo('#items');
   $('#item_check_' + item_id).attr('checked', 'checked');
   $('#items').submit();
}