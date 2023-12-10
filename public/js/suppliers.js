$(document).ready(function(){
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 1 : supplier , 2 : supply operation

    var type_of_operation; // add or update
    var type_of_operation2; // add or update history
    var update_btn;
    var update_btn2;
    var page;
    var page2;
    var sup_id;
    var row; // row of supplier
    
    // btn add new place
    $("#btn_show_formAdd").on('click', function(e){
        e.preventDefault();
        type_of_operation = "add";

        $("#title_placeModal").html(`<i class="fas fa-plus"></i> اضافة مورد جديد`);

        // reset inputs
        $("#supplier_name").val("");
        $("#phone_number").val("");
        $("#supplier_address").val("");
    });

    $("#btn_save_supplier").on('click', function(e){
        e.preventDefault();
        $("#form_supplier").submit();
    });

    // btn update supplier
    $("body").on('click', '.btnEditSupplier' , function(e){
        e.preventDefault();
        type_of_operation = "update";

        update_btn = $(this);
        $("#title_placeModal").html(`<i class="fas fa-edit"></i> تعديل المورد `);

        let supplier = JSON.parse($(this).closest('tr').find('.data').attr('data'));
        $("#form_supplier").attr('action', supplier.id);


        //to show data in speed way
        $("#supplier_name").val( supplier.name );
        $("#phone_number").val( supplier.phone_number );
        $("#supplier_address").val( supplier.address );
        
    });

    // form place [ add or update ]
    $("#form_supplier").on('submit', function(e){
        e.preventDefault();
        $("#result_form").hide();
        $("#btn_save_supplier").attr('disabled',true);
        let data = new FormData(this);
        let url;

        if ( type_of_operation == "add" )
        { 
            url = "/suppliers/add";
        } else { //update
            url = "/suppliers/update/"+$("#form_supplier").attr('action');
        }
  

        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function(data){
                $('.modal').modal('hide');
                
                $("#result_form").hide();
                if ( type_of_operation == "add" )
                {
                    $("#suppliers").append( data );
                    $("#count_of_suppliers").html( ++counts);
                }
                else
                {
                    update_btn.closest("tr").replaceWith( data );
                }
                $("#btn_save_supplier").attr('disabled',false);
            },
            error: function(data){
                $("#btn_save_supplier").attr('disabled',false);
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


    //delete supplier
    $("body").on("click",".btnDeleteSupplier", function(e){
        e.preventDefault();

        let supplier = JSON.parse($(this).closest('tr').find('.data').attr('data'));


        if ( !confirm("هل تريد بالتأكيد حذف المورد : " + supplier.name + " ؟ \n تحذير: اذا قمت بحذف هذا المورد سوف يتم حذف جميع القطع المتعلقة به") )
            return false;


        let btn = $(this);

        $.ajax({
            url: "/suppliers/delete/"+supplier.id,
            type: "POST",
            data: null,
            success: function(data){
                $("#count_of_suppliers").html( --counts);
                btn.closest('tr').remove();
            },
            error: function(data){
                alert("يوجد مشكلة في حذف هذا المورد, الرجاء المحاولة لاحقا");
            },
            cache: false,
            processData: false,
            contentType: false
        });

    });

    //filter form
    $("#formFilter").on("input", function(e){
        e.preventDefault();
        page = 1;
        $(".btnShowMore").hide();
        let text = $("#text_filter").val();
        $.ajax({
            url: "/suppliers/filter/",
            type: "GET",
            data: "text="+text,
            success: function(data){
                $("#suppliers").html( data );
                if (text == "")
                    $(".btnShowMore").show();
            },
        });

    });

    //show history
    $("body").on("click", ".btnShowHistory", function(e){
        e.preventDefault();
        page2 = 1;
        
        row = $(this).closest('tr');
        let supplier = JSON.parse($(this).closest('tr').find('.data').attr('data'));
        
        sup_id = supplier.id;

        $("#formNotes").attr('action', supplier.id);

        $(".historyTitle").html( " سجل عمليات المورد :  " + supplier.name );
        $("#all_amount").html(0); $("#all_amount").html( supplier.all_price );
        $("#total_amount").html(0); $("#total_amount").html( supplier.total_price );
        $("#operations").html(" "); // clear
        $(".operations > tr").empty();
        $.ajax({
            url: "/suppliers/getHistory",
            type: "POST",
            data: "id="+supplier.id,
            success: function( data ) {
                $("#operations").append(data);
            }
        });
        
    });

    $("#btnShowFormNewHistory").on('click', function(e){
        e.preventDefault();
        type_of_operation2 = "add";
        $(".history_title_modal").html(` <i class="fas fa-plus"></i> اضافة عملية توريد جديدة  `);

        // reset inputs
        $("#part_name").val("");
        $("#count").val("");
        $("#price").val("");
        
    });

    // btn update history
    $("body").on('click', '.btnEditHistory' , function(e){
        e.preventDefault();
        type_of_operation2 = "update";

        update_btn2 = $(this);
        $(".history_title_modal").html(`<i class="fas fa-edit"></i> تعديل عملية توريد `);

        let operation = JSON.parse($(this).closest('tr').find('.data').attr('data'));

        $("#form_history").attr('action', operation.id);

        $("#part_name").val( operation.part_name );
        $("#count").val( operation.count );
        $("#price").val( operation.price );
        $("#paid").val( ( operation.paid_at ) ? 1 : 0 );
    });

    //add new history
    $("#form_history").on('submit', function(e){
        e.preventDefault();
        
        $("#btn_save_history").attr('disabled',true);
        let url;
        
        if ( type_of_operation2 == "add" )
            url = "/suppliers/addSupplyOperation";
        else
            url = "/suppliers/updateSupplyOperation/" + $("#form_history").attr('action');

        let data = new FormData(this);
        data.append("supplier_id",sup_id);

        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function(data){
                $('.addHistoryModal').modal('hide');

                if (  type_of_operation2 == "add" )
                {
                    $("#operations").before( data );
                }
                else
                {
                    update_btn2.closest('tr').replaceWith( data );
                }

                $("#btn_save_history").attr('disabled',false);
            },
            error: function(data){
                $("#btn_save_history").attr('disabled',false);
                alert("يوجد مشكلة في اضافة عملية التوريد, الرجاء المحاولة لاحقا");
            },
            cache: false,
            processData: false,
            contentType: false
        });
    });

    $("#btn_save_history").on('click', function(e){
        e.preventDefault();

        $("#form_history").submit();
    });



    // show more history supplier
    $("body").on('click', '.btnShowMoreOps', function(e){
        e.preventDefault();
        let id = $(this).attr('href');
        let btn = $(this);
        btn.hide();
        
        $.ajax({
            url: "/suppliers/getMoreHistory/?page="+ (++page2),
            type: "GET",
            data: "id="+id,
            success: function(data){
                $("#operations").append( data );
            },
        });
    });

    //show more suppliers
    $("body").on("click" , ".btnShowMore" , function(e){
        e.preventDefault();

        let btn = $(this);

        btn.attr('disabled',true);
        
        $.ajax({
            url: "/suppliers/getMore/?page="+ (++page),
            type: "GET",
            data: null,
            success: function(data){
                if ( Object.keys(data).length == 0)
                    btn.hide();
                $("#suppliers").append( data );
                btn.attr('disabled',false);
            },
        });

    });

    //button set paid
    $("body").on("click", ".btnPaid", function(e){
        e.preventDefault();
        let btn = $(this);
        let operation = JSON.parse( $(this).closest('tr').find('.data').attr('data') );
        let id = $(this).attr("href");
        $.ajax({
            url: "/parts/setPaid",
            type: "POST",
            data: "id="+id,
            success: function(data){
                let currentdate = new Date();
                let datetime = currentdate.getDate() + "/"
                + (currentdate.getMonth()+1)  + "/" 
                + currentdate.getFullYear() + "  "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();
                btn.closest('div').replaceWith(` مدفوع ${ datetime } `);
                $("#total_amount").html( parseFloat($("#total_amount").html()) - parseFloat(operation.price) );
            },
            error: function(data){
                alert("يوجد مشكلة في هذه العملية الرجاء المحاولة لاحقا");
            }
        });
    });

    //delete history
    $("body").on('click', '.btnDeleteHistory', function(e){
        e.preventDefault();

        let btn = $(this);

        let operation = JSON.parse( $(this).closest('tr').find('.data').attr('data') );


        if ( !confirm( " هل تريد بالتأكيد حذف هذه العملية " ))
            return false;

        $.ajax({
            url: "/suppliers/deleteSupplyOperation/"+operation.id,
            type: "POST",
            data: null,
            success: function(data){
                $("#all_amount").html( parseFloat($("#all_amount").html()) - parseFloat(operation.price) );
                if ( ! operation.paid_at ) 
                    $("#total_amount").html( parseFloat($("#total_amount").html()) - parseFloat(operation.price) );

                btn.closest('tr').remove();
            },
            error: function(data){
                alert("يوجد مشكلة في حذف هذه العملية , الرجاء المحاولة لاحقا");
            },
            cache: false,
            processData: false,
            contentType: false
        });

    });

    //notes

    $(".btnShowNotes").on('click', function(e){
        e.preventDefault();

        let notes = row.find('.notes').attr('data');
        $("#notes").val( notes );

    });

    $("#formNotes").on('submit', function(e){
        e.preventDefault();
        $("#btn_save_notes").attr('disabled',true);
        $.ajax({
            url: "/suppliers/saveNotes/" + $("#formNotes").attr('action'),
            type: "POST",
            data: "notes="+$("#notes").val(),
            success: function(data){
                row.find('.notes').attr('data', $("#notes").val());
                $("#btn_save_notes").attr('disabled',false);
                $(".notesModal").modal('hide');
            },
            error: function(data){
                $("#btn_save_notes").attr('disabled',false);
                alert("يوجد مشكلة في حفظ الملاحظات");
            }
        });
    });

    //filter form for operations
    $("#formFilterOperations").on("input", function(e){
        e.preventDefault();
        page2 = 1;
        $(".btnShowMoreOps").hide();
        let text = $("#text_filter_operations").val();
        let supplier_id = JSON.parse( row.find('.data').attr('data') ).id;
        $.ajax({
            url: "/suppliers/filterOperations/" + supplier_id,
            type: "GET",
            data: "text="+text,
            success: function(data){
                $("#operations").html( data );
                if (text == "")
                    $(".btnShowMoreOps").show();
            },
        });

    });

    //report
    $("body").on('click', '.btnCreateReport',function(e){
        e.preventDefault();

        $("#formCreateReport").attr('action','/reports/supply_operations/' + sup_id);
    });

});