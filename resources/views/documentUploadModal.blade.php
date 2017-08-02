<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-body">
      <form id="uploadDocForm" method="post" action="{{mp_url('/document/upload')}}">
        {{csrf_field()}}
        <h4>上传文件</h4>
        请选择您要上传的学术文件。<br><input type="file" title="Browse" name="file" class="btn btn-sm btn-info file-input parsley-required" data-required="true">
        <button type="submit" class="btn btn-success">上传</button>
      </form>
    </div>
  </div>
</div>
<script>
$('#uploadDocForm').submit(function(e){
    e.preventDefault();
    $.post("{{mp_url('/document/upload')}}", $('#uploadDocForm').serialize()).done(function(data) {
        $.snackbar("新文件已上传");
        $("#ajaxModal").load("{{mp_url('/documentDetails.modal/')}}"+{content: data});
    });
});
</script>
