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

    <div class="row justify-content-center">
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

    <div class="container-fluid mt-5" id="table">
        <div class="row justify-content-center">
            <table class="table text-center"  dir="rtl" border>
                <thead>
                    <tr class="bg-light">
                        @foreach ($columns as $column)
                            <th> {{ $column }} </th>
                        @endforeach
                    </tr>
                <thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr>
                            @foreach ($row as $data)
                                <td> {{ $data }} </td>
                            @endforeach
                        </tr>
                    @endforeach
                    @if ($total_price != 0)
                        <tr>
                            @for ($i = 0; $i < count( $columns ) - 2; $i++)
                            <td></td>
                            @endfor
                            <td> المجموع النهائي : </td>
                            <td> {{ $total_price }} </td>
                        </tr>
                    @endif

                </tbody>
            </table>
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
