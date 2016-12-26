<div class="modal-dialog">
      <div class="modal-content">
<header class="header bg-dark bg-gradient">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#export" data-toggle="tab" aria-expanded="true">导出</a></li>
            <li class=""><a href="#import" data-toggle="tab" aria-expanded="false">导入</a></li>
          </ul>
        </header>
      <div class="tab-content">
        <section class="tab-pane active" id="export">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                目前默认导出为xlsx格式。其他格式可能在以后加入。如需导入新数据，请填写导出的空表；如需批量修改数据，可在导出数据中修改并重新导入。
                <div class="btn-group btn-group-justified">
                  <a href="{{$exportURL}}" class="btn btn-info">导出（含数据）</a>
                  <a href="{{$exportURL}}/empty" class="btn btn-success">导出（空表）</a>
                </div>
              </div>
            </div>
          </div>          
        </section>
        <section class="tab-pane" id="import">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                仅支持导入（编辑过的）导出的表。请务必检查各项是否填写正确。如错误的大小写、多输入的空格等均会导致识别失败从而产生错误数据。慎重导入。
                <form id="uploadForm" enctype="multipart/form-data" data-validate="parsley"><!-- ToDo: validation -->
                  {{ csrf_field() }}
                  <br>请选择您要导入的表格文件。<br><input type="file" title="Browse" name="file" class="parsley-required" data-required="true"><button type="submit" class="btn btn-success pull-right" >导入</button>
                </form>
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
        //$.post("{{$importURL}}", $('#uploadForm').serialize());
        $.ajax({  
            url:  '{{$importURL}}',
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
                alert('An error occured while importing.');
            }  
       });  
    }
});
</script>
