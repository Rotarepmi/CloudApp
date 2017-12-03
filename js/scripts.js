'use strict';

// just design thing - display loader while sending file
$('#submitFileForm').click(function() {
  $('.file-form .btn .fa').css({
    "display" : "inherit"
  });
});

// give submit button from modal an attribute of download button of dynamicly listed by php files
$('.download-btn').click(function() {
  var $this = $(this);
  var dir = $this.data('file');
  var filename = dir.split('/').pop();

  $('#downloadTitle').html("Czy na pewno chcesz pobrać plik "+filename+"?");

  $('#download').attr('href', dir);
});

// while delete button is clicked locate to the directory from buttons attribute (which points on dynamicly listed file)
$('.delete-btn').click(function() {
  var $this = $(this);
  var dir = $this.data('file');
  var filename = dir.split('/').pop();

  $('#deleteTitle').html("Czy na pewno chcesz usunąć plik "+filename+"?");

  $('#delete').click(function() {
    window.location.href = './scripts/delete.php?dir='+dir;
  });
});
