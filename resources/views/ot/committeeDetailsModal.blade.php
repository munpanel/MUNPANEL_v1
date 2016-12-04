<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <h4>委员会ID {{$committee->id}}</h4><button id="enableEditable-{{$committee->id}}" class="btn btn-default pull-right">编辑模式</button>
            <table id="committee-{{$committee->id}}" class="table table-bordered table-striped" style="clear: both">
                <tbody>
                    <tr>
                        <td width="35%">名称</td>
                        <td width="65%"><a href="#" id="name" data-type="text" data-pk="{{$committee->id}}" data-url="{{secure_url('/ot/update/committee/'.$committee->id)}}" data-title="name" class="editable">{{$committee->name}}</a></td>
                    </tr>
                  </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$.fn.editable.defaults.mode = 'inline';
$('#committee-{{$committee->id}} .editable').editable();
$('#committee-{{$committee->id}} .editable').editable('toggleDisabled');

$('#enableEditable-{{$committee->id}}').click(function() {
    $('#committee-{{$committee->id}} .editable').editable('toggleDisabled');
});
</script>
