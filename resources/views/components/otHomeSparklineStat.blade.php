<div class="col-sm-8 pull-right">
  <div class="clearfix m-t-xs m-b-xs pull-right pull-none-xs">
    <div class="pull-left">
      <div class="pull-left m-r-xs">
        <span class="block text-sm">浏览 </span>
        <span class="h4">{{Reg::where('conference_id', Reg::currentConferenceID())->whereNotIn('type', ['ot', 'dais'])->count()}}</span>
        <i class="fa fa-level-up text-success"></i>
      </div>
      <div class="clear">
        <div class="sparkline inline m-t-sm" data-type="bar" data-height="20" data-stacked-bar-color="['#afcf6f', '#ddd']" data-bar-spacing="2" data-bar-width="4">5:5,8:4,12:5,10:6,11:7,12:2,8:6</div>
      </div>
    </div>
    <div class="pull-left m-l-lg">
      <div class="pull-left m-r-xs">
        <span class="block text-sm">报名 </span>
        @if (Reg::currentConference()->status == 'daisreg')
        <span class="h4">{{$dais}}</span>
        @else
        <span class="h4">{{$del + $obs + $vol}}</span>
        @endif
        <i class="fa fa-level-down text-danger"></i>
      </div>
      <div class="clear">
        <div class="sparkline inline m-t-sm" data-type="bar" data-height="20" data-bar-color="#fb6b5b" data-bar-spacing="2" data-bar-width="4">6,5,8,9,6,3,5</div>
      </div>
    </div>
  </div>
</div>