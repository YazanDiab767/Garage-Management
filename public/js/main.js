$(document).ready(function(){

    var keys = {};  
    function handleKeyPress(evt) {
        let {
            keyCode,
            type
        } = evt || Event; // to deal with IE
        let isKeyDown = (type == 'keydown');
        keys[keyCode] = isKeyDown;
        
        if (isKeyDown && keys[17] && keys[18]) {
            $(".navbar").toggle(1000);
        }
    };
    window.addEventListener("keyup", handleKeyPress);
    window.addEventListener("keydown", handleKeyPress);


    $("#btnAddNewHistory").on('click', function(e){
        e.preventDefault();
        $("#formUpdatePassword").submit();
    });
    
    $("#formUpdatePassword").on('submit', function(e){
        e.preventDefault();
        $("#btnAddNewHistory").attr('disabled',true);
        let data = new FormData( this );
        $.ajax({
            url: '/employees/updatePassword',
            type: "POST",
            data: data,
            success: function(data){
                $("#btnAddNewHistory").attr('disabled',false);
                $('.modal').modal('hide');
                $("#current_password").val('');
                $("#new_password").val('');
                $(".result_form_password").hide();
            },
            error: function(data){
                $("#btnAddNewHistory").attr('disabled',false);
                $(".result_form_password").show();
                let errors = data.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    $(".result_form_password").html(`<b class = "text-danger"> ${errors[key]} </b>`);
                    return false;
                });  
            },
            cache: false,
            processData: false,
            contentType: false
        });
    });
});