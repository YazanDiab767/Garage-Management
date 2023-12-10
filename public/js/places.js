$(document).ready(function(){
    var type_of_operation; // add or update
    var update_btn; // update button

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    
    // btn add new place
    $("#btn_show_formAdd").on('click', function(e){
        e.preventDefault();
        type_of_operation = "add";
        $("#result_form").hide();
        $("#title_placeModal").html(` <i class="fas fa-plus"></i> اضافة رف جديد `);

        // reset inputs
        $("#place_name").val("");
        $('#place_description').val('');
    });

    $("#btn_save_place").on('click', function(e){
        e.preventDefault();
        $("#form_place").submit();
    });

    // btn update place
    $("body").on('click', '.btnEditPlace' , function(e){
        e.preventDefault();
        type_of_operation = "update";

        update_btn = $(this);

        let place = JSON.parse( $(this).closest('tr').find('.data').attr('data') );

        $("#title_placeModal").html(`<i class="fas fa-edit"></i> تعديل رف ${ place.name }`);

        $("#form_place").attr('action', place.id);

        $("#result_form").hide();
        
        //to show data in update form
        $("#place_name").val( place.name );
        $("#place_description").val( place.place );
        
    });

    // form place [ add or update ]
    $("#form_place").on('submit', function(e){
        e.preventDefault();
        $("#btn_save_place").attr('disabled',true);
        $("#result_form").hide();
        let data = new FormData(this);
        let url;

        if ( type_of_operation == "add" )
            url = "/places/add";

        else
            url = "/places/update/"+$("#form_place").attr('action');
            
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function(data){
                $('.modal').modal('hide');
                $("#result_form").hide();
                
                if (type_of_operation == "add")
                {
                    $("#places").prepend( data );
                    $("#count_of_places").html( ++counts);
                } 
                else
                {
                    update_btn.closest("tr").replaceWith( data );
                }
                $("#btn_save_place").attr('disabled',false);
            },
            error: function(data){
                $("#btn_save_place").attr('disabled',false);
                $("#result_form").show();
                let errors = data.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    $("#result_form").html(`<b class = "text-danger"> ${errors[key]} </b>`);
                });  
            },
            cache: false,
            processData: false,
            contentType: false
        });
    });


    //delete
    $("body").on("click",".btnDeletePlace", function(e){
        e.preventDefault();

        let place = JSON.parse( $(this).closest('tr').find('.data').attr('data') );

        if ( !confirm("هل تريد بالتأكيد حذف الرف : " + place.name + " ؟ \n تحذير : اذا قمت بحذف هذا الرف سوف يتم حذف جميع القطع التي بداخله") )
            return false;

        let id = place.id;

        let btn = $(this);

        $.ajax({
            url: "/places/delete/"+id,
            type: "POST",
            data: null,
            success: function(data){
                $("#count_of_places").html( --counts);
                btn.closest('tr').remove();
            },
            error: function(data){
                alert("يوجد مشكلة في حذف هذا الرف , الرجاء المحاولة لاحقا");
            },
            cache: false,
            processData: false,
            contentType: false
        });

    });

    // filter places
    $("#text_filter").keyup(function(){
        let input = $(this).val();
        $("#places tr").each(function(){
            if ($(this).text().search(new RegExp(input, "i")) < 0)
                $(this).hide();
            else
                $(this).show();
        });
    });

});