@extends('layouts.admin')
@section('page-title')
    {{__('Dashboard')}}
@endsection
@push('script-page')
    <script>
        $(document).ready(function()
        {
            get_data();
        });

        function get_data()
        {
            var calender_type=$('#calender_type :selected').val();
            $('#calendar').removeClass('local_calender');
            $('#calendar').removeClass('goggle_calender');
            if(calender_type==undefined){
                $('#calendar').addClass('local_calender');
            }
            $('#calendar').addClass(calender_type);
            $.ajax({
                url: $("#event_dashboard").val()+"/event/get_event_data" ,
                method:"POST",
                data: {"_token": "{{ csrf_token() }}",'calender_type':calender_type},
                success: function(data) {
                    (function () {
                        var etitle;
                        var etype;
                        var etypeclass;
                        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'timeGridDay,timeGridWeek,dayGridMonth'
                            },
                            buttonText: {
                                timeGridDay: "{{__('Day')}}",
                                timeGridWeek: "{{__('Week')}}",
                                dayGridMonth: "{{__('Month')}}"
                            },
                            slotLabelFormat: {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false,
                            },
                            themeSystem: 'bootstrap',
                            navLinks: true,
                            droppable: true,
                            selectable: true,
                            selectMirror: true,
                            editable: true,
                            dayMaxEvents: true,
                            handleWindowResize: true,
                            height: 'auto',
                            timeFormat: 'H(:mm)',
                            {{--events: {!! json_encode($arrEvents) !!},--}}
                            events: data,
                            locale: '{{basename(App::getLocale())}}',
                            dayClick: function (e) {
                                var t = moment(e).toISOString();
                                $("#new-event").modal("show"), $(".new-event--title").val(""), $(".new-event--start").val(t), $(".new-event--end").val(t)
                            },
                            eventResize: function (event) {
                                var eventObj = {
                                    start: event.start.format(),
                                    end: event.end.format(),
                                };
                            },
                            viewRender: function (t) {
                                e.fullCalendar("getDate").month(), $(".fullcalendar-title").html(t.title)
                            },
                            eventClick: function (e, t) {
                                var title = e.title;
                                var url = e.url;

                                if (typeof url != 'undefined') {
                                    $("#commonModal .modal-title").html(title);
                                    $("#commonModal .modal-dialog").addClass('modal-md');
                                    $("#commonModal").modal('show');
                                    $.get(url, {}, function (data) {
                                        $('#commonModal .modal-body').html(data);
                                    });
                                    return false;
                                }
                            }
                        });
                        calendar.render();
                    })();
                }
            });
        }
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('HRM')}}</li>
@endsection
@php
    $setting = \App\Models\Utility::settings();
@endphp
@section('content')
    @if(\Auth::user()->type != 'client' && \Auth::user()->type != 'company')
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                 
                </div>
            </div>
        </div>
    @else
        <div class="row">
          

        </div>
    @endif
@endsection


