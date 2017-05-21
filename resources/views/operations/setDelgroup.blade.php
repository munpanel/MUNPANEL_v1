@php
$delgroups = App\Delegategroup::where('conference_id', Reg::currentConferenceID())->get(['id', 'display_name']);
$regDelgroups = $reg->delegate->delegategroups;
$arr_group = [];
foreach ($regDelgroups as $group)
    array_push($arr_group, $group->id);
@endphp
<div id="ot_verify" style="display: block;">
  <h3 class="m-t-sm">设定代表组</h3>
  <form method="post" action="{{mp_url('/ot/setDelgroup')}}" id="setDelgroupForm">
    <p>请点击以下项目的复选框以变更{{$reg->user->name}}归属的代表组。</p>
    {{csrf_field()}}
    <input type="hidden" name="id" value="{{$reg->id}}">
    <div class="scrollable m-b" style="max-height:400px">
      <table class="table table-striped m-b-none">
        <tbody>
          @foreach ($delgroups as $delgroup)
          <tr>
            <td width="20px"><input type="checkbox" name="delgroup[]" value="{{$delgroup->id}}"{{in_array($delgroup->id, $arr_group) ? ' checked=""' : ''}}></td>
            <td>{{$delgroup->display_name}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <button type="submit" class="btn btn-success">保存更改</button>
    <button name="" type="button" class="btn btn-danger m-l-xs" onclick="">重置</button>
  </form>
</div>
<script>
$('#setDelgroupForm').submit(function(e){
    e.preventDefault();
    $.post('{{mp_url('/ot/setDelgroup')}}', $('#setDelgroupForm').serialize()).done(function(data) {
        $.snackbar({content: data});
        $("#ajaxModal").load("{{mp_url('/ot/regInfo.modal/'.$reg->id.'?active=operations')}}");
    });
});
</script>
