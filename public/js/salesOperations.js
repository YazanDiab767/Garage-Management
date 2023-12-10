$(document).ready(function(){

    var page = 1;
    var btn_edit;
    var c = 0;
    var row; // row part

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    getNewRow();

    //loop on all parts row to calculate total price
    function calculate_price()
    {
        let totalPrice = 0;
        $(".partRow").each(function(index, element){
            if ( $(this).find('.partsDiv').val() != null )
            {
                let part = JSON.parse( $(this).find('.partsDiv').val() );

                let price = parseFloat( part.selling_price );
                let add_sub = parseFloat( $(this).find('.add_discount_price').val() );
                let quantity = parseFloat($(this).find('.quantityPart').val());

                totalPrice += ( ( price * quantity ) + add_sub); // total price for all parts
            }
        });

        $(".totalPrice").html( totalPrice ); // total price without add/discount input

        $(".finalPrice").html( totalPrice +  parseFloat( $(".add_discount_input").val() ) ); // the final price for operation
    }

    //add new row
    $("body").on("click", ".btnAddRowPart" , function(e){
        e.preventDefault();

        $(".parts").append( row );

        getNewRow(); // for next click

        if ( c != 0 )
            calculate_price();
        else
            c++;
    });

    function getNewRow()
    {
        $.ajax({
            url: "/parts/getRow" ,
            type: "GET",
            data: null,
            success: function(data){
                row = data;
            }
        });
    }

    //calculate total price when any change in parts div ( rows )
    $("body").on("change", ".parts",function(){
        calculate_price();
    });

    //calculate price for [row] when change part
    $("body").on('change','.partsDiv', function(e){
        e.preventDefault();

        let part = JSON.parse( $(this).val() );

        $(this).closest(".row").find('.quantityPart').val(1);
        $(this).closest(".row").find('.quantityPart').attr("max", part.count); // max quanitity from part

        $(this).closest('.row').find('.add_discount_price').val(0);

        $(this).closest(".row").find('.add_discount_price').attr('min', '-' + part.selling_price ); // discount can't be grater than price

        //show price to part
        let quanity = parseFloat($(this).closest(".row").find('.quantityPart').val());
        let add_discount =  parseFloat($(this).closest('.row').find('.add_discount_price').val());
        let price = ( part.selling_price * quanity ) + add_discount;

        $(this).closest(".row").find(".pricePart").val( price );
        $(this).closest(".row").find(".priceOfParts").html( part.selling_price );
        $(this).closest(".row").find(".countOfParts").html( part.count );
        $(this).closest(".row").find(".info_part").html( " في الرف :  " + part.place.name );
        $(this).closest(".row").find('.btnShowImage').attr("href", part.image);

        calculate_price();
    });

    //calculate price for row when update [quantity]
    $("body").on('change','.quantityPart', function(e){
        e.preventDefault();

        if ( $(this).closest(".row").find(".partsDiv").val() != null )
        {
            let part = JSON.parse( $(this).closest(".row").find(".partsDiv").val() );

            let price = part.selling_price;

            let quanity = $(this).val();

            let add_discount_price =  $(this).closest(".row").find(".add_discount_price").val();

            $(this).closest(".row").find(".pricePart").val( price * quanity + parseFloat(add_discount_price) );

            calculate_price();
        }
    });

    //calcualte [price of part] when chnage add/discount
    $("body").on('change','.add_discount_price', function(e){
        e.preventDefault();

        if ( $(this).closest(".row").find(".partsDiv").val() != null )
        {
            let part = JSON.parse($(this).closest(".row").find(".partsDiv").val());

            let price = part.selling_price;
            let quanity = $(this).closest(".row").find(".quantityPart").val();

            $(this).closest(".row").find(".pricePart").val( ( price * quanity ) + parseFloat($(this).val()) );
        }

    });

    //calculate final price
    $("body").on('change', '.add_discount_input' , function(e){
        e.preventDefault();
        calculate_price();
    });

    function save()
    {
        $("#result_form").html('');
        $("#result_form").hide();

        let parts_id = [0];
        let quantities = [0];
        let adds_discounts = [0];
        let i = 0;
        let res;

        //get all parts and crosspond quantities
        $(".partRow").each(function(index, element){
            if ( $(this).find('.partsDiv').val() != null )
            {
                let part = JSON.parse( $(this).find('.partsDiv').val() );

                let part_id = part.id;
                let quanitity = $(this).find('.quantityPart').val();
                let add_discount = $(this).find('.add_discount_price').val();

                if ( quanitity <= part.count) // check from quantity
                {
                    parts_id[i] = part_id;
                    quantities[i] = quanitity;
                    adds_discounts[i++] = add_discount;
                }
                else
                {
                    res = ` لا تتوفر الكمية المطلوبة من : ${ part.name } `;
                    return false;
                }

            }
            else
            {
                // there is row has not filed
                res = ` يجب تعبئة جميع صفوف القطع `;
                return false;
            }
        });

        if ( res ) // there is error
        {
            $(".btnSave").attr('disabled',false);
            $("#result_form").show();
            $("#result_form").html(`<b class = "text-danger"> ${ res } </b>`);
            return false;
        }

        let customer_name = $("#customer_name").val();
        let note = $("#note").val();
        let type_paid = $('input[name="paidType"]:checked').val();
        let add_discount = $(".add_discount_input").val();

        data = {
            parts_id: parts_id,
            quantities: quantities,
            adds_discounts: adds_discounts,
            customer_name: customer_name,
            note: note,
            type_paid: type_paid,
            add_discount: add_discount
        }

        return data;
    }

    //button save
    $("body").on('click','.btnSave', function(e){
        e.preventDefault();
        let btn = $(this);
        btn.attr('disabled',true);
        let data = save();

        if ( data == false )
            return false;

        $.ajax({
            url: "/salesOperations/add",
            type: "POST",
            data: data,
            success: function(data){
                location.reload();
            },
            error: function(data){
                btn.attr('disabled',false);
                $("#result_form").show();
                let errors = data.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    $("#result_form").html(`<b class = "text-danger"> ${errors[key]} </b>`);
                    return false;
                });
            }
        });
    });

    //button delete
    $("body").on('click', '.btnDeleteRow' , function(e){
        e.preventDefault();

        $(this).closest(".row").remove();
        let totalPrice = 0;
        calculate_price();
    });

    function filter(text)
    {
        page = 1;
        $(".btnShowMore").hide();
        $("#operations").html( `
            <div class = "row justify-content-center">
                <i class="fas fa-spinner fa-3x fa-spin"></i>
            </div>
        `);

        $.ajax({
            url: "/salesOperations/filter/",
            type: "GET",
            data: "text="+text+"&not_paid_check="+ $("#not_paid_check").prop('checked') +"&start="+$("#start_date").val()+"&end="+$("#end_date").val(),
            success: function(data){
                $("#operations").html( data ).show();
                $("#total_amount").html( amount + " شيكل" );
                if ( text == "" && $("#start_date").val() == "" && $("#end_date").val() == "" )
                    $(".btnShowMore").show(); // if first pagiane dont need to show more btn in main page will not show this
            },
        });
    }

    // filter form
    $("#formFilter").on('change',function(e){
        e.preventDefault();
        filter($("#text_filter").val());
    });

    $("#text_filter").on('input',function(e){
        e.preventDefault();
        filter($("#text_filter").val());
    });

    //show more
    $("body").on("click" , ".btnShowMore" , function(e){
        e.preventDefault();

        let btn = $(this);

        btn.attr('disabled',true);

        $.ajax({
            url: "/salesOperations/getMore/?page="+ (++page),
            type: "GET",
            data: null,
            success: function(data){
                $("#operations").append( data );
                btn.attr('disabled',false);

                if ( count < paginate )
                    $(".btnShowMore").hide();

            },
        });

    });

    //delete operation
    $("body").on('click', '.btnDeleteOperation', function(e){
        e.preventDefault();

        let btn = $(this);
        let id = btn.attr('href');

        if ( !confirm("هل تريد بالتأكيد استرجاع هذه العملية ؟ ") )
            return false;

        $.ajax({
            url: "/salesOperations/delete/"+id,
            type: "POST",
            data: null,
            success: function(data){
                btn.closest('.op').remove();
            },
            error: function(data){
                alert("يوجد مشكلة في استرجاع هذه العملية الرجاء المحاولة مرة اخرى");
            },
            cache: false,
            processData: false,
            contentType: false
        });
    });

    //set debt as paid
    $("body").on('click', '.btnPaidDebt', function(e){
        e.preventDefault();

        let btn = $(this);
        let operation = JSON.parse( $(this).closest('.op').find('.data').attr('data') );

        if ( !confirm(" هل انت متأكد من تسديد هذا الدين ") )
            return false;

        $.ajax({
            url: "/salesOperations/setPaid",
            type: "POST",
            data: "id="+operation.id,
            success: function(data){
                btn.closest('.mt-4').find('label').html("مدفوع");
                btn.remove();
            },
            error: function(data){
                alert("يوجد مشكلة في تسديد الدين , الرجاء المحاولة لاحقا");
            }
        });
    });

    //for input customers
    $("body").on('change', '.customers',function(){
        $("#customer_name").val( $(".customers").select2('data')[0].text );
        $("#label_customer_name").html( $(".customers").select2('data')[0].text );
        $("#clearName").show();
    });

    $("body").on('change', '#customer_name',function(){
       $('.customers').val('');
       $('.customers').trigger('change');
       $("#label_customer_name").html( $(this).val() );
       $("#clearName").show();
    });

    $("body").on('click', '#clearName', function(e){
        e.preventDefault();
        $(this).hide();
        $("#clearName").val('');
        $("#customer_name").val('');
        $("#label_customer_name").html(' ');
    });

    //form zoom image
    $("body").on("click", ".btnShowImage", function(e){
        e.preventDefault();

        let src = $(this).attr('href')
        $("#formShowImage").show();
        $("#image").attr('src', "/storage/" + src );
    });

    $("body").on("click", "#formShowImage", function(e){
        e.preventDefault();
        $("#formShowImage").hide();
    });

    //btn edit opration
    $("body").on('click', '.btnEditOperation', function(e){
        e.preventDefault();
        btn_edit = $(this);
        let operation = JSON.parse($(this).closest(".op").find(".data").attr('data'));

        $.ajax({
            type: "GET",
            url: "/salesOperations/edit/" + operation.id,
            data: null,
            success: function(data){
                $("#body_sale_operation_modal").html( data );
            }
        });

    });

    //button update
    $("body").on('click','.btnUpdate', function(e){
        e.preventDefault();
        let data = save();

        if ( data == false )
            return false;

        let btn = $(this);
        btn.attr('disabled',true);
        let id = btn.attr('data');
        $.ajax({
            url: "/salesOperations/update/"+id,
            type: "POST",
            data: data,
            success: function(data){
                btn_edit.closest('.op').replaceWith( data );
                $(".modal").modal('hide');
            },
            error: function(data){
                alert("يوجد مشكلة في تعديل هذه العملية");
                btn.attr('disabled',false);
                $("#result_form").show();
                let errors = data.responseJSON.errors;
                Object.keys(errors).forEach(key => {
                    $("#result_form").html(`<b class = "text-danger"> ${errors[key]} </b>`);
                    return false;
                });
            }
        });
    });

    $("body").on('click', '.showNote', function(e){
        e.preventDefault();
        let v = parseInt($(this).attr('data'));
        if ( v == 1 )
            v = 0
        else
            v = 1;
        $(this).attr('data' , v );

        let note = $(this).closest('div').find('.note');
        note.slideToggle(500);

    });

    $("body").on('click','.btnMakeReport', function(e){
        let href = $(this).attr('href');
        let v = $(this).closest('.op').find('.showNote').attr('data');
        let u = href.split('?');
        let h = (u[0] + '/?withNote=' + v);
        $(this).attr('href', h );
    });

});
