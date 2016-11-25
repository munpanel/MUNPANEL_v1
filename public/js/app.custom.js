$(document).ready(function() {
    $('.approval-status').click(function(e) {
        var $this = $(this);
        var id = $this.attr('data-id');

        var cb = function() {
            $this.toggleClass('active');
        };

        if($this.hasClass('active'))
            jQuery.get('/school/unverify/' + id, cb);
        else 
            jQuery.get('/school/verify/' + id, cb);
    });
});
