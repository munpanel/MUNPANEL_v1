<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-body">
      <div class="row">
        <div class="col-sm-12 b-r">
          <form method="post" action="{{mp_url('/setAccomodate')}}" >
            {{csrf_field()}}
            <input type="hidden" name="reg_id" value="{{Reg::currentID()}}">
            <p>由于{{Reg::currentConferenceID() == 3 ? 'MUNPANEL 开发人员的失误' : '组织团队设置不当'}}，我们在报名表单中遗失了"是否住宿"的选项，现需要您向报名表单补充相关内容。对您带来的不便我们深表歉意。</p>
            {!!$confForm!!}
            <button type="submit" class="btn btn-info">提交住宿信息</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
