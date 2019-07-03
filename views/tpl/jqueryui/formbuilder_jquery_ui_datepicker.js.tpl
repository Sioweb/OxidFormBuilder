$('[data-datepicker]').each(function() {
    var $el = $(this),
        datepicker = $el.data('datepicker');
    
    if(datepicker == '') {
        datepicker = {
            dateFormat: 'yy-mm-dd ',
            timeFormat: 'HH:mm:ss',
        };
    }
    $el.datetimepicker(datepicker);
    $('#ui-datepicker-div').removeClass('ui-helper-hidden-accessible');
});