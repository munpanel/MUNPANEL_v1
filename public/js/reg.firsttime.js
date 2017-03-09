$( document ).ready(function() {
    $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
    $('body').append($modal);
    $modal.modal();
    $modal.load('reg.first.modal');
});
