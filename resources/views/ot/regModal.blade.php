<div class="modal-dialog">
      <div class="modal-content">
<header class="header bg-dark bg-gradient">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#info" data-toggle="tab" aria-expanded="true">信息</a></li>
            <li class=""><a href="#events" data-toggle="tab" aria-expanded="false">事件</a></li>
            <li class=""><a href="#interview" data-toggle="tab" aria-expanded="false">面试</a></li>
          </ul>
        </header>
      <div class="tab-content">
        <section class="tab-pane active" id="info">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
              {{json_encode($delegate)}}
              </div>
            </div>
          </div>
        </section>
        <section class="tab-pane" id="events">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
              事件
              </div>
            </div>
          </div>          
        </section>
        <section class="tab-pane" id="interview">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
              面试
              </div>
            </div>
          </div>          
        </section>
        </div>
      </div><!-- /.modal-content -->
</div>
<script>
$('#uploadForm').submit(function(e){
    e.preventDefault();
    if ($('#uploadForm').parsley('validate')) 
    {
        var formData = new FormData($( "#uploadForm" )[0]);
        //$.post("{{$eventsURL}}", $('#uploadForm').serialize());
        $.ajax({  
            url:  '{{$eventsURL}}',
            type: 'POST',  
            data: formData,  
            async: false,  
            cache: false,  
            contentType: false,  
            processData: false,  
            success: function (returndata) {  
                location.reload();
            },  
            error: function (returndata) {  
                alert('An error occured while eventsing.');
            }  
       });  
    }
});
</script>
