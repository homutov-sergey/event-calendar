<?php
/*
Plugin Name: My Bokningar
Description: To display the calendar, use this shortcode = [fullcalendar]
Version: 1.0
Author: SERHII
Text Domain: bokningar
Development: SERHII KHOMUTOV
*/

if(!defined('ABSPATH')){
    die;
}


define('BOKNINGAR_PATH',plugin_dir_path(__FILE__));

// registration CPT
require BOKNINGAR_PATH . 'inc/class-registercpt.php';

// shortcode
require BOKNINGAR_PATH . 'inc/class-shortcode.php';


//// register scrypt frontend
add_action('wp_enqueue_scripts', 'bokningar_enqueue_scripts');
function bokningar_enqueue_scripts() {
    wp_enqueue_script('fullcalendar-js', plugin_dir_url(__FILE__) . 'assets/js/index.global.min.js', array(), null, true);
    wp_enqueue_script('fullcalendar-global-js', plugin_dir_url(__FILE__) . 'assets/js/locales-all.global.min.js', array(), null, true);
}

// register style frontend
add_action('wp_enqueue_scripts', 'bokningar_register_styles');
function bokningar_register_styles() {
        wp_enqueue_style( 'events-style', plugin_dir_url(__FILE__) . 'assets/css/style.css' );
}

// admin panel register bokningar custom script
add_action('admin_enqueue_scripts', 'bokningar_admin_script', 20);
function bokningar_admin_script() {
    wp_enqueue_style('jquery-ui-style', plugin_dir_url(__FILE__) . 'assets/css/jquery-ui.css');
    wp_enqueue_style('jquery-timepicker-style', plugin_dir_url(__FILE__) . 'assets/css/jquery.timepicker.min.css');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('timepicker', plugin_dir_url(__FILE__) . 'assets/js/jquery.timepicker.min.js', array('jquery'), null, true);
    wp_enqueue_script('date-en-US', plugin_dir_url(__FILE__) . 'assets/js/date-en-US.js', null, true);
    wp_enqueue_script('timeselect', plugin_dir_url(__FILE__) . 'assets/js/jquery.ui.timeselect.js', null, true);
    wp_enqueue_script('bokningar-admin-script', plugin_dir_url(__FILE__) . 'assets/js/main.admin.js', array('jquery', 'jquery-ui-datepicker', 'timepicker'), null, true);
}

// register meta boxes
add_action( 'add_meta_boxes', 'bokningar_register_meta_boxes' );
function bokningar_register_meta_boxes() {
	add_meta_box( 
        'bokningar-info', // id
        __( 'Bokningar', 'bokningar' ), // label
        'bokningar_callback', // bokningar_callback( $post )
         'bokningar' // cpt 
    );
}

///// render profile
function bokningar_callback( $post ) {
    $starttime = get_post_meta($post->ID, 'event_starttime', true);
    $endtime = get_post_meta($post->ID, 'event_endtime', true);
    $startdate = get_post_meta($post->ID, 'event_startdate', true);
    $enddate = get_post_meta($post->ID, 'event_enddate', true);
    $event_type = get_post_meta($post->ID, 'event_type', true);
    $event_end_repeat = get_post_meta($post->ID, 'event_end_repeat', true);
    $event_repeat_days = get_post_meta($post->ID, 'repeat_days', true);

    if (!empty($event_repeat_days)) {
        if (is_string($event_repeat_days)) {
            $selected_days = json_decode($event_repeat_days, true);
            if ($selected_days === null) {
                $selected_days = [];
            }
        } else {
            $selected_days = (array) $event_repeat_days;
        }
    } else {
        $selected_days = [];
    }

    $days = [
        1 => 'Måndag',
        2 => 'Tisdag',
        3 => 'Onsdag',
        4 => 'Torsdag',
        5 => 'Fredag',
        6 => 'Lördag',
        0 => 'Söndag'
    ];

    $event_types = array(
        'Match' => '#F3FF59',
        'Träning' => '#add8e6',
        'Övrigt' => '#EEEEEE',
    );

    echo '<table class="form-table">
          <tbody>';

			echo '<tr>
				<th><label for="starttime">'. __('Start Time:', 'bokningar') .'</label></th>
				<td><input type="text" id="starttime" name="starttime" value="' . esc_attr( $starttime ) . '" class="regular-text"></td>
			</tr>';

            echo '<tr>
				<th><label for="endtime">'. __('End Time:', 'bokningar') .'</label></th>
				<td><input type="text" id="endtime" name="endtime" value="' . esc_attr( $endtime ) . '" class="regular-text"></td>
			</tr>';

            echo '<tr>
				<th><label for="startdate">'. __('Start Date:', 'bokningar') .'</label></th>
				<td><input type="text" id="startdate" name="startdate" value="' . esc_attr( $startdate ) . '" class="regular-text"></td>
			</tr>';
            
            echo '<tr>
				<th><label for="enddate">'. __('End Date:', 'bokningar') .'</label></th>
				<td><input type="text" id="enddate" name="enddate" value="' . esc_attr( $enddate ) . '" class="regular-text"></td>
			</tr>';

            echo '<tr>';
				echo '<th><label for="type">'. __('Select type Event:', 'bokningar') .'</label></th>';
				echo '<td>';
                echo '<select name="type" id="type">';
                echo '<option value="">Select type Event</option>';
                foreach ( $event_types as $event => $color ) {
                    if ( $event_type ==  $color ) {
                        echo '<option value="' . $color . '"selected>' . $event . '</option>';
                    } else {
                        echo '<option value="' . $color . '">' . $event . '</option>';
                    }
                }
                echo '</select>';
                echo '</td>';
			echo '</tr>';

            echo '<tr>';
            echo '<th><label for="repeat-days">'. __('Repeat on:', 'bokningar') .'</label></th>';
            echo '<td>';
                foreach ($days as $value => $day) {
                    $checked = in_array($value, $selected_days) ? 'checked' : '';
                    echo "<label><input type='checkbox' class='repeat-day' name='repeat_days[]' value='$value' $checked> $day</label><br>";
                }
            echo '</td>';
            echo '</tr>';

            echo '<tr>
				<th><label for="endeventrepeat">'. __('Specify the date until which the event should repeat (if required):', 'event') .'</label></th>
				<td><input type="text" id="endeventrepeat" name="endeventrepeat" value="' . esc_attr( $event_end_repeat ) . '" class="regular-text"></td>
			</tr>';

	echo '</tbody>
	</table>';
}

//// save function
add_action( 'save_post', 'bokningar_save_meta_box' );
function bokningar_save_meta_box( $post_id ) {

    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return $post_id;
	}

    if ( isset( $_POST['starttime'] ) ) {
	    update_post_meta($post_id, 'event_starttime', $_POST[ 'starttime' ]);
    }
    if ( isset( $_POST['endtime'] ) ) {
        update_post_meta($post_id, 'event_endtime',   $_POST[ 'endtime' ]);
    }
    if ( isset( $_POST['startdate'] ) ) {
        update_post_meta($post_id, 'event_startdate', $_POST[ 'startdate' ]);
    }
    if ( isset( $_POST['enddate'] ) ) {
        update_post_meta($post_id, 'event_enddate',  $_POST[ 'enddate' ]);
    }
    if ( isset( $_POST['type'] ) ) {
        update_post_meta($post_id, 'event_type',  $_POST[ 'type' ]);
    }
    if (isset($_POST['repeat_days'])) {
        $selected_days = $_POST['repeat_days'];
        update_post_meta($post_id, 'repeat_days', json_encode($selected_days));
    } else {
        update_post_meta($post_id, 'repeat_days', json_encode([]));
    }
    if ( isset( $_POST['endeventrepeat'] ) ) {
        update_post_meta($post_id, 'event_end_repeat',  $_POST[ 'endeventrepeat' ]);
    }
}