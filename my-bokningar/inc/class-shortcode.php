<?php 

function fullcalendar_shortcode() {

    // info block color
    $event_types = array(
        'Match' => '#F3FF59',
        'Träning' => '#add8e6',
        'Övrigt' => '#EEEEEE',
    );

    echo '<div class="events-info">';
    foreach ( $event_types as $event => $color ) {
        echo '<div class="event-item">';
            echo '<div class="color-box" style="background-color:'.$color.'"></div>';
            echo '<div class="color-title">'.$event.'</div>';
        echo '</div>';
    }
    echo '</div>';

    $allevents = get_posts(array('post_type' => 'bokningar', 'post_status' => 'publish', 'numberposts' => -1));

    $events = [];

    foreach ($allevents as $itemevent) {
        $daysOfWeek = [];
    
        $id_event = $itemevent->ID;
        $get_start_date = get_post_meta($id_event, 'event_startdate', true);
        $get_end_date = get_post_meta($id_event, 'event_enddate', true);
        $get_start_time = get_post_meta($id_event, 'event_starttime', true);
        $get_end_time = get_post_meta($id_event, 'event_endtime', true);
        $get_type = get_post_meta($id_event, 'event_type', true);
        $repeat_days = get_post_meta($id_event, 'repeat_days', true);
        $event_repeat_days = get_post_meta($id_event, 'repeat_days', true);
        $event_end_repeat = get_post_meta($id_event, 'event_end_repeat', true);

        if (!empty($event_repeat_days) && strlen($event_repeat_days) >= 5) {
            $repeat_day = $event_repeat_days;
            $daysOfWeek = json_decode($repeat_day, true);
                    if (is_array($daysOfWeek)) {
                $daysOfWeek = array_map('intval', $daysOfWeek);
            } else {
                $daysOfWeek = [];
            }
        }
        
        $start = $get_start_date . 'T' . $get_start_time;
        $end = $get_end_date . 'T' . $get_end_time;
        
        $event = [
            'title' => $itemevent->post_title,
            'start' => $start,
            'end' => $end,
            'color' => $get_type,
            'textColor' => '#000000',
            //'daysOfWeek' => [ 2, 4 ],
            //'startTime' => $get_start_time,
            //'endTime' => $get_end_time,
        ];
        
        if (!empty($daysOfWeek)) {
            $event['daysOfWeek'] = $daysOfWeek;
            $event['startTime'] = $get_start_time;
            $event['endTime'] = $get_end_time;
            $event['startRecur'] = $get_start_date;
            $event['endRecur'] = $event_end_repeat;
        }
        
        $events[] = $event;
        
    
    }

    $json_events = json_encode($events);

    $output = '<div class="my-events-calendar" id="calendar"></div>';

    $script = '
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var calendarEl = document.getElementById("calendar");

        var events = ' . $json_events . ';

        var calendar = new FullCalendar.Calendar(calendarEl, {
			locale: "sv",
            //aspectRatio: 1,
            initialView: "timeGridWeek",
            firstDay: 1,
            slotMinTime: "07:00:00",
            slotMaxTime: "24:00:00",
            height: "auto",
            events: events,

            headerToolbar: {
                left: "prev,next today",
                center: "",
                right: ""
            },
            nowIndicator: false,
			views: {
                    timeGridWeek: {
                        dayMaxEvents: 0
                    }
                }
        });

        calendar.render();
    });
    </script>';

    return $output . $script;
}
add_shortcode('fullcalendar', 'fullcalendar_shortcode');