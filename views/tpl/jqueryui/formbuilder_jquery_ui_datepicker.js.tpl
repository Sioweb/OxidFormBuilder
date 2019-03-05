$('[data-datepicker]').each(function() {
    var $el = $(this),
        datepicker = $el.data('datepicker');
    
    if(datepicker == '') {
        datepicker = {};
    }
    $el.datepicker(datepicker);
});