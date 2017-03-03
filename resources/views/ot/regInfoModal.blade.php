<div class="modal-dialog">
      <div class="modal-content">
<header class="header bg-dark bg-gradient">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#info" data-toggle="tab" aria-expanded="true">信息</a></li>
            <li class=""><a href="#events" data-toggle="tab" aria-expanded="false">事件</a></li>
            <li class=""><a href="#interview" data-toggle="tab" aria-expanded="false">面试</a></li>
          </ul>
        </header>
      <div class="tab-content">
        <section class="tab-pane active" id="info">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
              {{json_encode($reg)}}
              </div>
            </div>
          </div>
        </section>
        <section class="tab-pane" id="events">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                <ul class="timeline timeline-small">
                  @foreach($reg->events as $event)
                  <li>
                    <div class="timeline-badge {{$event->eventtype->level}}"><i class="fa fa-{{$event->eventtype->icon}} fa-fw"></i></div>
                    <div class="timeline-panel">
                      <div class="timeline-heading">
                        <h4 class="timeline-title">{{$event->eventtype->title}}<small class="text-muted"><i class="fa fa-clock-o fa-fw"></i>{{nicetime($event->created_at)}}</small></h4>
                      </div>
                      <div class="timeline-body">
                        {{$event->text()}}
                      </div>
                    </div>
                  </li>
                  @endforeach
              </div>
            </div>
          </div>          
        </section>
        <section class="tab-pane" id="interview">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
              面试
              </div>
            </div>
          </div>          
        </section>
        </div>
      </div><!-- /.modal-content -->
</div>
