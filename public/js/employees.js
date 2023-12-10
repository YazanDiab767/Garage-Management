$(document).ready(function(){
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let type_of_operation; // add or update
    let customer_id; // id of employee when update
    let update_btn;
    
    // btn add new employee
    $("#btn_show_formAdd").on('click', function(e){
        e.preventDefault();
        type_of_operation = "add";
        $("#title_employeeModal").html(`
            <i class="fas fa-plus"></i>
            اضافة موظف جديد
        `);
        // reset inputs
        $("#employee_name").val("");
        $("#phone_number").val("");
        $("#employee_address").val("");
        $("#employee_type").val("");
    });

    $("#btn_save_employee").on('click', function(e){
        e.preventDefault();
        $("#form_employee").submit();
    });

    // btn update place
    $("body").on('click', '.btnEditEmployee' , function(e){
        e.preventDefault();

        update_btn = $(this);
        let data = $(this).attr('href');
        data = data.split('/');

        type_of_operation = "update";

        $("#title_employeeModal").html(`
            <i class="fas fa-edit"></i>
            تعديل الموظف - ${ data[1] }
        `);


        employee_id = data[0];

        //to show data in speed way
        $("#employee_name").val( data[1] );

        $.ajax({
            url: "/employees/get",
            type: "POST",
            data: "id="+employee_id,
            success: function( data ) {

                $("#employee_name").val(data.name);
                $("#phone_number").val(data.phone_number);
                $("#employee_address").val(data.address);
                $("#employee_type").val( data.type );
            }
        });
        
    });

    // form employee [ add or update ]
    $("#form_employee").on('submit', function(e){
        e.preventDefault();
        $("#result_form").hide();
        let data = new FormData(this);
        if ( type_of_operation == "add" )
        { // add
            $.ajax({
                url: "/employees/add",
                type: "POST",
                data: data,
                success: function(data){
                    $('.modal').modal('hide');
                    $("#result_form").hide();
                    $("#employees").append( data );
                    $("#count_of_employees").html( ++counts);
                },
                error: function(data){
                    $("#result_form").show();
                    let errors = data.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        $("#result_form").html(`<b class = "text-danger"> ${errors[key]} </b>`);
                        return false;
                    });  
                },
                cache: false,
                processData: false,
                contentType: false
            });
        } else { //update
            $.ajax({
                url: "/employees/update/"+employee_id,
                type: "POST",
                data: data,
                success: function(data){
                    $('.modal').modal('hide');
                    $("#result_form").hide();
                    update_btn.closest("tr").replaceWith( data );
                },
                error: function(data){
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
        }
    });


    //delete
    $("body").on("click",".btnDeleteEmployee", function(e){
        e.preventDefault();

        let data = $(this).attr('href');
        inf = data.split('/');

        if ( !confirm("هل تريد بالتأكيد حذف الموظف : " + inf[1] + " ؟ ") )
            return false;

        let id = inf[0];

        let btn = $(this);

        $.ajax({
            url: "/employees/delete/"+id,
            type: "POST",
            data: null,
            success: function(data){
                $("#count_of_employees").html( --counts);
                btn.closest('tr').remove();
            },
            error: function(data){
                alert("يوجد مشكلة في حذف هذا الموظف, الرجاء المحاولة لاحقا");
            },
            cache: false,
            processData: false,
            contentType: false
        });

    });

    //show more employees

    let page = 1;

    $("body").on("click" , ".btnShowMore" , function(e){
        e.preventDefault();

        let btn = $(this);

        btn.attr('disabled',true);
        
        $.ajax({
            url: "/employees/getMore/?page="+ (++page),
            type: "GET",
            data: null,
            success: function(data){
                if ( Object.keys(data).length == 0)
                    btn.hide();
                $("#employees").append( data );
                btn.attr('disabled',false);
            },
        });

    });

    //filter form

    $("#formFilter").on("input",function(e){
        e.preventDefault();
        page = 1;
        $(".btnShowMore").hide();
        let text = $("#text_filter").val();
        $.ajax({
            url: "/employees/filter/",
            type: "GET",
            data: "text="+text,
            success: function(data){
                $("#employees").html( data );
                if (text == "")
                    $(".btnShowMore").show();
            },
        });

    });

});