<section class="panel text-sm bg-white">
  <div class="panel-body">
    @if (isset($score) && $score['all'] > 0)
    <label>自动计分</label>
    <div class="form-group"><h4 style="margin: 0px;">{{$score['correct']}}<small> / {{$score['all']}}</small></h4></div>
    @endif
    <label>提交内容</label>
    {!!$formContent!!}
  </div>
</section>
