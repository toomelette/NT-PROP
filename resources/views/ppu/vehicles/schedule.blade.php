@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Vehicles</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    {!! \App\Swep\ViewHelpers\__form2::select('vehicle',[
                        'options' => \App\Swep\Helpers\Arrays::vehicles(),
                        'cols' => 2,
                        'label' => 'Vehicle:',
                        'id' => 'select_vehicle',
                    ]) !!}
                </div>
                <div id="calendar">
                </div>
            </div>
        </div>
    </section>


@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">
        /* initialize the calendar
             -----------------------------------------------------------------*/
        //Date for the calendar events (dummy data)
        var date = new Date()
        var d    = date.getDate(),
            m    = date.getMonth(),
            y    = date.getFullYear()
        var calendar = $('#calendar').fullCalendar({
            header    : {
                left  : 'prev,next today',
                center: 'title',
                right : 'month,agendaWeek,agendaDay'
            },
            buttonText: {
                today: 'today',
                month: 'month',
                week : 'week',
                day  : 'day'
            },
            //Random default events
            {{--//events    : {!!  $vehicleSchedule !!} ,--}}
            eventSources: [
                {
                    url: '{{route('dashboard.vehicles.schedule')}}?fetch=true',
                    type: 'GET',
                    data: function () {
                        return {
                            vehicle : $("#select_vehicle").val(),
                        }
                    },
                    error: function() {
                        alert('there was an error while fetching events!');
                    },
                }
            ],
            timeFormat: 'hh:mm A',
            eventRender: function(eventObj, $el) {
                $el.popover({
                    title: eventObj.title,
                    content: eventObj.description,
                    trigger: 'hover',
                    placement: 'top',
                    container: 'body',
                    html: true,
                });
            },
            editable  : true,
            droppable : true, // this allows things to be dropped onto the calendar !!!
            drop      : function (date, allDay) { // this function is called when something is dropped

                // retrieve the dropped element's stored Event Object
                var originalEventObject = $(this).data('eventObject')

                // we need to copy it, so that multiple events don't have a reference to the same object
                var copiedEventObject = $.extend({}, originalEventObject)

                // assign it the date that was reported
                copiedEventObject.start           = date
                copiedEventObject.allDay          = allDay
                copiedEventObject.backgroundColor = $(this).css('background-color')
                copiedEventObject.borderColor     = $(this).css('border-color')

                // render the event on the calendar
                // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                $('#calendar').fullCalendar('renderEvent', copiedEventObject, false)

                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove()
                }

            }
        })
        
        $("#select_vehicle").change(function () {
            calendar.fullCalendar('refetchEvents');

        })
    </script>
@endsection