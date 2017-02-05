<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <h4>重新配对搭档</h4>
            请务必谨慎使用此功能，否则很可能导致数据错误。正常情况下此功能不应被使用，因为秘书处会把所有配对做好。但如不幸秘书处工作疏忽，修改表单时并未改好搭档信息，学术团队可在此手动调整搭档配对。请在下方输入搭档二人的UID，同时请注意：<b>请先移出双方的任何席位信息，否则将导致严重的数据错误。所填二人必须系统中均无搭档，否则将不会配对。</b><br>
            <input id='pair_id1' type='text'></input><br>
            <input id='pair_id2' type='text'></input><br>
            <button id='pair-button'>配对（慎重。）</button>
        </div>
    </div>
</div>
<script>
$(document).on('click','#pair-button', function() {
        jQuery.get('dais/linkPartner/' + $('#pair_id1').val() + '/' + $('#pair_id2').val());
        location.reload();
});
</script>
