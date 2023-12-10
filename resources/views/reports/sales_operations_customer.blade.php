@extends('layouts.app')
@section('style')
    <style>
        table {
        table-layout: fixed ;
        width: 100% ;
        }
        td {
        width: 25% ;
        }
    </style>
@endsection
@section('content')

    <div class="row justify-content-center mt-1">
        <div class="col-sm-12 text-center">
            <a href="#" class="btn btn-info text-white w-50" onclick="history.back()"> خلف <i class="fas fa-arrow-circle-left"></i> </a>
        </div>
        <div class="col-sm-12">
            <br/>
        </div>
        <div class="col-sm-12 text-center">
            <button id="btn" class="btn btn-primary w-50" onclick='printDiv();'>
                طباعة
                <i class="fas fa-print"></i>
            </button>
        </div>
    </div>
    <div class="row justify-content-center mt-4" dir="rtl">
        <div class="col-sm-3">
            حجم الخط :
        </div>
        <div class="col-sm-3">
            <select class="form-control" id="font_size">
                @for($i = 2; $i <= 30; $i++)
                    <option value="{{ $i  }}">{{ $i  }}</option>
                @endfor
            </select>
        </div>
    </div>

    <div class="op mt-5" dir="rtl" style="background-color: #f7f7f7;border: 1px solid black; padding: 20px; border-radius: 5px;">

        <div class="container-fluid text-center" id="table">
            @php $amount = 0; $final_amount = 0;  $ids = []; $total_amount = 0; @endphp

            <div class="row justify-content-center">
                <u> <b> الزبون : {{ $customer->name }} </b> </u>
            </div>

            <br/><hr>

            @foreach ($operations as $operation)
                <div class="row justify-content-center mt-5">
                    - تاريخ و وقت: {{ date('Y-m-d h:i A', strtotime($operation->created_at)) }}
                </div>
                <div class="row text-center mt-3">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <table class="table table-bordered" dir="rtl" border="">
                            <tr class="bg-light">
                                <th> القطعة </th>
                                <th> الكمية </th>
                                <th> السعر </th>
                            </tr>
                        @php
                            $data = json_decode( $operation->data , true  );
                            $amount += ( $operation->price + $operation->discount_add) ;
                            array_push($ids, $operation->id);
                        @endphp

                        <div>

                            @php
                                //if there is add or discount => divide the addition over parts
                                $pr = 0;
                                $rem = 0;
                                if ( $operation->discount_add != 0 )
                                    $pr = $operation->discount_add / count( $data );
                                $dis = 0;
                            @endphp


                            @for ($i = 0; $i < sizeof( $data ); $i++)
                            <tr>
                                    <td> {{ $data[$i]["part_name"] }} </td>
                                    <td> {{ $data[$i]["quantity"] }} </td>
                                    <td>
                                        @if ( isset($data[$i]["add_sub"]) && $data[$i]["add_sub"] != 0)
                                            @php
                                                $dis += $data[$i]["add_sub"];
                                                $amount += $data[$i]["add_sub"];
                                            @endphp

                                            @if ( ( $data[$i]["total_price"] + $data[$i]["add_sub"] + $pr + $rem ) > 0 )
                                                {{ sprintf('%.2f', $data[$i]["total_price"] + $data[$i]["add_sub"] + $pr + $rem ) }} شيكل
                                                @php $rem = 0; @endphp
                                            @else
                                                {{ sprintf('%.2f', $data[$i]["total_price"] + $data[$i]["add_sub"] ) }} شيكل
                                                @php $rem += $pr; @endphp
                                            @endif

                                        @else

                                            @if ( ( $data[$i]["total_price"] + $pr + $rem ) > 0 )
                                                {{ sprintf('%.2f', $data[$i]["total_price"] + $pr + $rem ) }} شيكل
                                                @php $rem = 0; @endphp
                                            @else
                                                {{ sprintf('%.2f', $data[$i]["total_price"] ) }} شيكل
                                                @php $rem += $pr; @endphp
                                            @endif

                                        @endif
                                    </td>
                            </tr>
                                @php
                                    $final_amount +=  (  ( $data[$i]["total_price"]) - ($data[$i]["orignal_price"] * $data[$i]["quantity"])  );
                                @endphp
                            @endfor
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-1"></div>
                </div>

                <div class="text-center">
                    <div class="">
                        <b>  المجموع  : </b>
                        @php $total_amount += $operation->price+ $operation->discount_add + $dis; @endphp
                        <b> {{ sprintf ( '%.2f' , ( $operation->price ) + $operation->discount_add + $dis , 2 ) }} شيكل </b>
                        <br/><br/>
                    </div>
                </div>
            @endforeach

            <br/><hr>

            <div  class="row justify-content-center">
                <b> <u> المجموع الكلي : {{ sprintf( '%.2f' , $total_amount )}} شيكل </u> </b>
            </div>

        </div>
    </div>

    <script>
        function printDiv()
        {
            let font_size = document.getElementById('font_size').value;

            var divToPrint=document.getElementById('table');

            var newWin=window.open('','Print-Window');

            newWin.document.open();

            newWin.document.write(
                `
                    <html>
                        <head>
                        <link rel="stylesheet" href="/css/bootstrap.min.css" />
                        </head>
                        <style>
                            *{
                                font-size: ${ font_size }px;
                            }
                        </style>

                        <body onload="window.print()" style="width:100%;">
                            ${divToPrint.innerHTML}
                        </body>


                    </html>
                `
            );

            newWin.document.close();

            setTimeout(function(){newWin.close();},2000);
        }
    </script>
@endsection
