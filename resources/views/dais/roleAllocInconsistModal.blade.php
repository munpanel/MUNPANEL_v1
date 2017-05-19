<div class="modal-dialog">
 <div class="modal-content">
   <header class="header bg-warning bg-gradient mp-modal-header">
      <center><h4>警告</h4></center>
   </header>
   <div class="modal-body">
     <div class="row">
       <div class="col-sm-12 b-r">
         <p>
            系统中该代表委员会为&nbsp;<b>{{$delegate->committee->display_name}}</b>&nbsp;与您欲分配的席位的委员会不符，是否继续？<br>如代表选择了此席位，系统中代表的委员会信息将在席位锁定时修改。
         </p>
         <p class="checkbox m-t-lg">
           <a id="cancel" onclick="$('#ajaxModal').modal('hide');$('#ajaxModal').remove();" class="btn btn-sm btn-danger text-uc m-t-n-xs pull-right"><i class="fa fa-times"></i> 否</a>
           <a id="confirmButton" href="#" class="btn btn-sm btn-success text-uc m-t-n-xs m-r-xs pull-right"><i class="fa fa-check"></i> 是</a>
         </p>
       </div>
     </div>
   </div>
 </div><!-- /.modal-content -->
</div>
<script>
$('#confirmButton').click(function(){
    loader(this);
    $.post("dais/addSeat/{{$delegate->reg_id}}/doAssign", $('#seatform').serialize(), function(receivedData){
        if (receivedData != "success")
            alert(receivedData);
        //location.reload();
        $('#ajaxModal').modal('hide');
        $('#ajaxModal').remove();
        $('#delegate-table').dataTable().fnReloadAjax(undefined, undefined, true);
        $('#nation-table').dataTable().fnReloadAjax(undefined, undefined, true);
    });
});
</script>
