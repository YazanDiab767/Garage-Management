$(document).ready(function(){
    var type_of_operation; // add or update
    var update_btn;
    var page = 1;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    
    // btn add new customer
    $("#btn_show_formAdd").on('click', function(e){
        e.preventDefault();
        type_of_operation = "add";
        $("#title_customerModal").html(` <i class="fas fa-plus"></i> اضافة زبون جديد `);

        $("#result_form").hide();

        // reset inputs
        $("#customer_name").val("");
        $("#phone_number").val("");
        $("#car_number").val("");
        $("#customer_address").val("");
    });

    $("#btn_save_customer").on('click', function(e){
        e.preventDefault();
        $("#form_customer").submit();
    });

    // btn update place
    $("body").on('click', '.btnEditCustomer' , function(e){
        e.preventDefault();
        type_of_operation = "update";

        update_btn = $(this);
        let customer = JSON.parse($(this).closest('tr').find('.data').attr('data'));
        
        $("#result_form").hide();
        
        $("#title_customerModal").html(`<i class="fas fa-edit"></i> تعديل الزبون - ${ customer.name }`);

        $("#form_customer").attr('action', customer.id);
        
        $("#customer_name").val( customer.name );
        $("#phone_number").val( customer.phone_number );
        $("#car_number").val( customer.car_number );
        $("#customer_address").val( customer.address );

    });

    // form customer [ add or update ]
    $("#form_customer").on('submit', function(e){
        e.preventDefault();
        $("#btn_save_customer").attr('disabled',true);
        $("#result_form").hide();
        let data = new FormData(this);
        let url;
        if ( type_of_operation == "add" )
            url = "/customers/add";
        else
            url = "/customers/update/"+$("#form_customer").attr('action');

        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function(data){
                $("#btn_save_customer").attr('disabled',false);
                $('.modal').modal('hide');
                $("#result_form").hide();
                if ( type_of_operation == "add" )
                {
                    $("#customers").prepend( data );
                    $("#count_of_customers").html( ++counts);
                }
                else
                {
                    update_btn.closest("tr").replaceWith( data );
                }
     
            },
            error: function(data){
                $("#btn_save_customer").attr('disabled',false);
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
    });


    //delete
    $("body").on("click",".btnDeleteCustomer", function(e){
        e.preventDefault();

        let customer = JSON.parse($(this).closest('tr').find('.data').attr('data'));

        if ( !confirm("هل تريد بالتأكيد حذف الزبون : " + customer.name + " ؟ \n تحذير : اذا قمت بحذف هذا الزبون سوف يتم حذف العمليات الحسابية الخاصة به") )
            return false;

        let btn = $(this);

        $.ajax({
            url: "/customers/delete/"+customer.id,
            type: "POST",
            data: null,
            success: function(data){
                $("#count_of_customers").html( --counts);
                btn.closest('tr').remove();
            },
            error: function(data){
                alert("يوجد مشكلة في حذف هذا الزبون, الرجاء المحاولة لاحقا");
            },
            cache: false,
            processData: false,
            contentType: false
        });

    });

    //show more customers
    $("body").on("click" , ".btnShowMore" , function(e){
        e.preventDefault();

        let btn = $(this);

        btn.attr('disabled',true);
        
        $.ajax({
            url: "/customers/getMore/?page="+ (++page),
            type: "GET",
            data: null,
            success: function(data){
                $("#customers").append( data );
                btn.attr('disabled',false);
                if ( count_of_request < paginate )
                    $(".btnShowMore").hide();
            },
        });

    });

    //filter form

    $("#formFilter").on("input", function(e){
        e.preventDefault();
        page = 1;
        $(".btnShowMore").hide();
        let text = $("#text_filter").val();
        $.ajax({
            url: "/customers/filter/",
            type: "GET",
            data: "text="+text,
            success: function(data){
                $("#customers").html( data );
                if (text == "")
                    $(".btnShowMore").show();
            },
        });

    });

    $("body").on('click', '.btnCreateReport',function(e){
        e.preventDefault();
        let customer = JSON.parse($(this).closest('tr').find('.data').attr('data'));
        $("#form_report").attr('action', '/reports/sales_operations_customer/' + customer.id);
    });

});