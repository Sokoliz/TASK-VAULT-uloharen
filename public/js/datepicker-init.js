/**
 * Datepicker Initialization
 * Handles the initialization and configuration of datepicker fields for project forms
 */

$(document).ready(function () {
    // Debug code for form submission
    console.log("Document ready - initializing project form debugging");
    $("#new-project-modal form").on("submit", function(e) {
        console.log("Project form submitted");
        var formData = {};
        $(this).serializeArray().forEach(function(item) {
            formData[item.name] = item.value;
        });
        console.log("Form data:", formData);
    });
    
    // First set of date pickers
    $("#startAdd").datepicker({
        todayBtn: 1,
        autoclose: true,
        format: "yyyy-mm-dd",
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#endAdd').datepicker('setStartDate', minDate);
    });
    
    $("#endAdd").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#startAdd').datepicker('setEndDate', minDate);
    });
    
    // Second set of date pickers
    $("#startAdd1").datepicker({
        todayBtn: 1,
        autoclose: true,
        format: "yyyy-mm-dd",
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#endAdd1').datepicker('setStartDate', minDate);
    });
    
    $("#endAdd1").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#startAdd1').datepicker('setEndDate', minDate);
    });
    
    // Third set of date pickers
    $("#startAdd2").datepicker({
        todayBtn: 1,
        autoclose: true,
        format: "yyyy-mm-dd",
        minDate: 0,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#endAdd2').datepicker('setStartDate', minDate);
    });
    
    $("#endAdd2").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#startAdd2').datepicker('setEndDate', minDate);
    });
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
}); 