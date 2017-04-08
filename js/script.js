
$(document).ready(function() {

    $("#recurring").click(function() {

        $(".day_wrp").show();
        $(".date_wrp").hide();

    });
    $("#one_time").click(function() {

        $(".date_wrp").show();
        $(".day_wrp").hide();


    });
});