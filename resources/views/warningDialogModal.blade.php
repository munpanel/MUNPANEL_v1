﻿<div class="modal-dialog">
 <div class="modal-content">
   <header class="header {{$danger ? 'bg-danger' : 'bg-warning' }} bg-gradient mp-modal-header">
      <center><h4>{{$danger ? '危险' : '警告' }}</h4></center>
   </header>
   <div class="modal-body">
     <div class="row">
       <div class="col-sm-12 b-r">
         <p>
            {!!$msg!!}
         </p>
         <p class="checkbox m-t-lg">
           <a id="cancel" onclick="$('#ajaxModal').modal('hide');$('#ajaxModal').remove();" class="btn btn-sm btn-danger text-uc m-t-n-xs pull-right"><i class="fa fa-times"></i> 否</a>
           <a id="confirm" {!!isset($target) ? 'href="'.$target.'"' : ''!!} {!!isset($onclick) ? 'onclick="'.$onclick.'"' : ''!!} class="btn btn-sm btn-success text-uc m-t-n-xs m-r-xs pull-right"><i class="fa fa-check"></i> 是</a>
         </p>
       </div>
     </div>
   </div>
 </div><!-- /.modal-content -->
</div>
