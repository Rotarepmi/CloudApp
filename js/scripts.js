'use strict';

$('#submitFileForm').click(function() {
  $('.file-form .btn .fa').css({
    "display" : "inherit"
  });
});

$('.download-btn').click(function() {
  var $this = $(this);
  var dir = $this.data('file');
  var filename = dir.split('/').pop();

  $('#downloadTitle').html("Czy na pewno chcesz pobrać plik "+filename+"?");

  $('#download').attr('href', dir);
});

$('.delete-btn').click(function() {
  var $this = $(this);
  var dir = $this.data('file');
  var filename = dir.split('/').pop();

  $('#deleteTitle').html("Czy na pewno chcesz usunąć plik "+filename+"?");

  $('#delete').click(function() {
    window.location.href = './scripts/delete.php?dir='+dir;
  });
});
