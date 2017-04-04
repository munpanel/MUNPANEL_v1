<div class="modal-dialog">
  <div class="modal-content">
    <header class="header bg-info bg-gradient mp-modal-header">
       <center><h4>选择面试官</h4></center>
    </header>
    <div class="modal-body">
       <p>请在此列表中选择您希望查看队列的面试官，面试官姓名右侧显示了面试官当前分配的未完成面试数量。</p>
       <form action="{{mp_url('/gotoInterviewer')}}" method="post">
       {{csrf_field()}}
          <div class="m-b">
            <select style="width:260px" class="interviewer-list" name="interviewer">
                @foreach (\App\Interviewer::list() as $name => $group)
                <optgroup label="{{$name}}">
                    @foreach ($group as $iid => $iname)
                    <option value="{{$iid}}">{{$iname}}</option>
                    @endforeach
                </optgroup>
                @endforeach
            </select>
          </div>
        <button name="submit" type="submit" class="btn btn-info">查看面试官的队列</button>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
      $(".interviewer-list").select2();
});
</script>
