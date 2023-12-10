$(document).ready(function(){
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#btn_get_statistics").on('click', function(e){
        e.preventDefault();
        $("#statistics_form").submit();
    });

    $("#statistics_form").on('submit', function(e){
        e.preventDefault();
        $("#btn_get_statistics").attr('disabled',true);
        $("#t1").css('background-color', 'white');
        $("#t2").css('background-color', 'white');
        let data = new FormData(this);
        $.ajax({
            url: "/statistics/get",
            type: "POST",
            data: data,
            success: function(data){
                $("#btn_get_statistics").attr('disabled',false);
                let total = data.t1_amount + data.t2_amount;
                let t1 =  (data.t1_amount / total) * 100;
                let t2 =  (data.t2_amount / total) * 100;

                $("#t").css('width', '100%');
                $("#t").addClass('bg-success');
                $("#t").html(` ${ total } شيكل ( 100 % ) `);

                $("#t1").css('width', t1 + '%');
                $("#t1").addClass('bg-danger');
                $("#t1").html(` ${ data.t1_amount } شيكل ( ${ t1.toPrecision(3) } % ) `);

                $("#t2").css('width', t2 + '%');
                $("#t2").addClass('bg-warning');
                $("#t2").html(` ${ data.t2_amount } شيكل ( ${ t2.toPrecision(3) } % ) `);
            },
            error: function(data){
                $("#btn_get_statistics").attr('disabled',false);
                alert("يوجد مشكلة , الرجاء التأكد من الفترات المدخلة !!")
            },
            cache: false,
            processData: false,
            contentType: false
        })

    })

});