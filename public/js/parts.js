$(document).ready(function(){
    var page = 1;
    var type_of_operation; // add or update
    var update_btn; // update button
    var btnAddQuantity;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // btn show form add new part
    $("#btn_show_form_add").on('click', function(e){
        e.preventDefault();
        type_of_operation = "add";

        $("#title_partModal").html(`<i class="fas fa-plus"></i> اضافة قطعة جديد `);
        // reset inputs
        $("#part_number").val("");
        $("#part_original_number").val("");
        $("#part_description").val("");
        $("#part_name").val("");
        $("#part_supplier").val("");
        $("#part_place").val("");
        $("#part_count").val("");
        $("#part_image").val("");
        $("#part_orignal_price").val("");
        $("#part_selling_price").val("");
    });


    // btn update part
    $("body").on('click', '.btnEditPart' , function(e){
        e.preventDefault();
        type_of_operation = "update";
        let part = JSON.parse(  $(this).closest('tr').find('.data').attr('data')  );
        update_btn = $(this);
        
        $("#title_partModal").html(` <i class="fas fa-edit"></i> تعديل القطعة - ${ part.name } `);

        $("#form_part").attr('action', part.id);

        $("#part_number").val( part.number );
        $("#part_original_number").val( part.original_number );
        $("#part_description").val( part.description );
        $("#part_name").val( part.name );
        $("#part_supplier").val( part.supplier.id );
        $("#part_place").val( part.place.id );
        $("#part_count").val( part.count );
        $("#part_orignal_price").val( part.orignal_price );
        $("#part_selling_price").val( part.selling_price );
    });

    //to submit form
    $("#btn_save_part").on('click', function(e){
        e.preventDefault();
        $("#form_part").submit();
    });

    // form part [ add or update ]
    $("#form_part").on('submit', function(e){
        e.preventDefault();
        
        $("#btn_save_part").attr('disabled',true);
        $("#result_form").hide();

        let data = new FormData(this);
        let url;

        if ( type_of_operation == "add" )
            url = "/parts/add";
        else
            url = "/parts/update/" + $(this).attr('action');

        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function(data){
                $('.modal').modal('hide');
                $("#result_form").hide();

                if ( type_of_operation == "add" ) // add
                {
                    $("#parts").prepend( data );
                    $("#count_of_parts").html( ++counts);
                }
                else // update
                {
                    update_btn.closest("tr").replaceWith( data );
                }

                $("#btn_save_part").attr('disabled',false);
            },
            error: function(data){
                $("#btn_save_part").attr('disabled',false);
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

    //delete part
    $("body").on("click",".btnDeletePart", function(e){
        e.preventDefault();

        part = JSON.parse(  $(this).closest('tr').find('.data').attr('data')  );

        if ( !confirm("هل تريد بالتأكيد حذف القطعة : " + part.name + " ؟ ") )
            return false;

        let btn = $(this);

        $.ajax({
            url: "/parts/delete/"+part.id,
            type: "POST",
            data: null,
            success: function(data){
                $("#count_of_parts").html( --counts);
                btn.closest('tr').remove();
            },
            error: function(data){
                alert("يوجد مشكلة في حذف هذه القطعة , الرجاء المحاولة لاحقا");
            },
            cache: false,
            processData: false,
            contentType: false
        });

    });

    //show more parts
    $("body").on("click" , ".btnShowMore" , function(e){
        e.preventDefault();

        let btn = $(this);
        btn.attr('disabled',true);
        
        $.ajax({
            url: "/parts/getMore/?page="+ (++page),
            type: "GET",
            data: null,
            success: function(data){
                $("#parts").append( data );
                btn.attr('disabled',false);
                if ( count_of_request < paginate )
                    btn.hide();
            },
        });
    });

    //filter form
    $("#formFilter").on('submit', function(e){
        e.preventDefault();
    });
    
    $("#formFilter").on("input" ,function(e){
        e.preventDefault();
        page = 1;
        $(".btnShowMore").hide();
        let text = $("#text_filter").val();
        $.ajax({
            url: "/parts/filter",
            type: "GET",
            data: "text="+text,
            success: function(data){
                $("#parts").html( data );
                if (text == "")
                    $(".btnShowMore").show();
            },
        });
    });

    //form add quantity
    $("body").on("click", ".btnAddQuantity", function(e){
        e.preventDefault();
        btnAddQuantity = $(this);
        $("#quantity").val(' ');
        let part = JSON.parse( $(this).closest('tr').find('.data').attr('data') );
        $("#form_quantity").attr('action', part.id);
    });

    $("#btn_save_quantity").on('click', function(e){
        e.preventDefault();
        $("#form_quantity").submit();
    });

    $("#form_quantity").on('submit', function(e){
        e.preventDefault();
        let data = new FormData(this);
        let id = $(this).attr('action')
        $.ajax({
            url: "/parts/addQuantity/"+id,
            type: "POST",
            data: data,
            success: function(data){
                $('.modal').modal('hide');
                $("#result_form2").hide();
                btnAddQuantity.closest("tr").replaceWith( data );
            },
            error: function(data){
                $("#result_form2").show();
                let errors = data.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    $("#result_form2").html(`<b class = "text-danger"> ${errors[key]} </b>`);
                });  
            },
            cache: false,
            processData: false,
            contentType: false
        });

    });

    //form zoom image
    $("body").on("click", ".btnShowImage", function(e){
        e.preventDefault();
        let src = $(this).attr('src')
        $("#formShowImage").show();
        $("#image").attr('src', src );
    });

    $("#formShowImage").on("click", function(e){
        e.preventDefault();
        $("#formShowImage").hide();
    });

});