<div class="modal-dialog"> 
 <div class="modal-content"> 
   <header class="header {{$danger ? bg-danger : bg-warning }} bg-gradient"> 
      <h4>{{$danger ? 危险 : 警告 }}</h4> 
   </header> 
   <div class="modal-body"> 
     <div class="row"> 
       <div class="col-sm-12 b-r"> 
         <p> 
            {{$msg}} 
         </p> 
         <p class="checkbox m-t-lg"> 
           <!-- TODO: 点击关闭对话框 -->  
           <a href="" class="btn btn-sm btn-danger text-uc m-t-n-xs"><i class="fa fa-arrow-left"></i> 否</button> 
           <a href="{{$target}}" class="btn btn-sm btn-success text-uc m-t-n-xs"><i class="fa fa-arrow-left"></i> 是</button> 
         </p> 
       </div> 
     </div> 
   </div> 
 </div><!-- /.modal-content --> 
</div> 