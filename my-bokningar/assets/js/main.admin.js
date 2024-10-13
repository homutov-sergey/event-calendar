jQuery(document).ready(function($) {

    // event start date picker
    jQuery( "#startdate, #enddate, #endeventrepeat" ).datepicker({
        dateFormat: "yy-mm-dd"
    });

    $('#starttime, #endtime').timeselect({
        step: 15,
        format: 'HH:mm:ss',
        startTime: '07:00',
        endTime: '23:00', 
        // autocompleteSettings: {
        //     autoFocus: true
        // }
    });

    $('.ui-autocomplete').css({
        'max-height': '200px',
        'overflow-y': 'auto'
    });

});