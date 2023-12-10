@extends('layouts.header')

@section('title','الرئيسية')
@section('style')
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    <style>
        .form_show_image{
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background-color:rgba(0, 0, 0, 0.7);
            z-index: 9999999;
            display: none;
        }
        .box .select2-container--open + .select2-container--open {
            left: auto;
            right: 0;
            width: 100%;
        }

        .box_customers .select2-selection__rendered {
            color: white !important;
        }

        .select2-container--default .select2-results__option[aria-disabled=true] {
            color: red !important;
        }
    </style>
@endsection

@section('body')
    <div class="container pb-5">
        @php
            $p = \App\Part::where('count','<=','1')->get(); //get parts have quantity equal or less than 1
            $s = \App\SaleOperation::where('paid_at','null')->get();
        @endphp
        <div class="notifications mt-3" dir="rtl">
            <div class="float-right">
                <button class="btn btn-primary" id="btnNotifications" type="button" data-toggle="collapse" data-target="#notifications" aria-expanded="false" aria-controls="notifications">
                    @if (count($p) > 0)
                        <i class="fas fa-bell fa-spin text-warning"></i> التنبيهات 
                    @else
                        <i class="fas fa-bell"></i> التنبيهات 
                    @endif
                </button>
            </div>
            <br/><br/>
            <div class="collapse multi-collapse" id="notifications">

                <section id="tabs">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12 ">
                                <nav>
                                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true"><i class="fas fa-coins"></i> القطع ( {{ count($p) }} )</a>
                                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false"><i class="fas fa-users"></i> الزبائن ( <span id="count_debts"></span> )</a>
                                    </div>
                                </nav>
                                <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                        @forelse ($p as $part)
                                            <div class="alert alert-warning text-right">
                                                <b>
                                                    الكمية المتبقية من {{ $part->name }} ( الرقم : {{ $part->number }} ) : {{ $part->count }}
                                                </b>
                                            </div>
                                        @empty
                                            <div class="alert alert-primary text-right">
                                                <b>
                                                    لا يوجد اي تنبيهات
                                                </b>
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                        <div class="text-right">
                                            <label class="text-info"> عرض الديون التي لها اسبوع او اكثر </label>
                                        </div>
                                        @php $i = 0; @endphp
                                        @forelse ($s as $sale_op)
                                            @php
                                                $date = date("Y-m-d");
                                                $date1 = strtotime( $date );
                                                $date2 = strtotime( date('Y-m-d', strtotime($sale_op->created_at) ) );
                                                $days = ($date1 - $date2) / 86400;
                                                
                                            @endphp
                                            @if ($days >= 7)
                                                @php $i++; @endphp
                                                <div class="alert alert-danger text-right">
                                                    <b>
                                                        <u> {{ $sale_op->customer->name }} </u> :
                                                        عليه دين من تاريخ
                                                        {{ $sale_op->created_at->toDateString() }}
                                                    </b>
                                                </div>
                                            @endif
                                        @empty
                                            <div class="alert alert-primary text-right">
                                                <b>
                                                    لا يوجد اي تنبيهات
                                                </b>
                                            </div>
                                        @endforelse
                                        <script>
                                            document.getElementById('count_debts').innerHTML = "{{ $i }}";
                                        </script>
                                    </div>
                                </div>
                            
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <br/><br/><hr>

        <div class="row justify-content-center divRegisterOperation mt-5" style="border: 1.5px solid black; border-radius: 15px; padding: 20px; box-shadow: 1px 0px 6px 1px black;">
            <h3> تسجيل عملية بيع جديدة <i class="fas fa-cart-plus"></i> </h3>
            
            <form method="POST" id="form_supplier" dir="rtl" class="mt-5 w-100">
                {{-- customer name --}}
                <div class="form-group row">
                    <div class="col-sm-2">
                        <span class="float-right"> <b> <i class="fas fa-user"></i> اسم الزبون : </b> </span>
                    </div>
                    <div class="col-sm-10">

                        <div id="box_customers" class="box box_customers" style="text-align: right; direction: rtl; position: relative;">
                            <select name="customer" id="customer" class="form-control customers text-white w-100">
                                <option></option>
                                @forelse ($customers as $customer)
                                    <option value="{{ $customer->id }}"> {{ $customer->name }} </option>
                                @empty
                                    <option value="0"> لا يوجد اي زبون ! </option>
                                @endforelse
                            </select>
                        </div>
                        <br/>
                        <input type="text" placeholder="اسم الزبون" name="customer_name" id="customer_name" class="form-control" hidden/>
                        
                        <label class="float-right">
                            <b>
                                <u>
                                    <span> الاسم : </span>
                                    <span id="label_customer_name">  </span> 
                                </u>
                                <a href="#" id="clearName" class="text-danger mr-3" style="display: none"> <i class="fas fa-times"></i> مسح  </a> 
                            </b>
                        </label>
                    </div>
                </div>

                <hr>

                {{-- parts --}}
                <div class="form-group row">
                    <div class="col-sm-3">
                        <b class="text-left float-right"> <i class="fas fa-coins"></i> القطع : </b> <br />
                    </div>
                    <div class="col-sm-9"></div>
                </div>                    

                <div class="form-group row text-center">
                    <div class="col-sm-7">
                        اسم القطعة
                    </div>
                    <div class="col-sm-1">
                        الكمية
                    </div>
                    <div class="col-sm-2">
                        السعر ( شيكل )
                    </div>
                    <div class="col-sm-2">
                    
                    </div>
                </div>

                <div class="parts w-100">

                </div>

                @if ( count( $parts ) > 0 )
                    <a class="btn text-danger btnAddRowPart float-right" href="#"> <i class="fas fa-plus"></i> اضف قطعة جديدة </a><br/>
                    <label> السعر الكلي للقطع : <span class="totalPrice">0 </span> <span> شيكل </span> </label><br/>
                    <input type="number" class="form-control add_discount_input float-left" style="width: 8%;" value="0" placeholder="اضافة / خصم" />
                    <br/><br/>
                    <label> <b> <u> الصافي للدفع : <span class="finalPrice"> 0 </span> <span> شيكل <span> </u> </b> </label><br/>
                @endif

                <hr>

                {{-- note --}}
                <div class="form-group row">
                    <div class="col-sm-2">
                        <b class="text-left float-right"> <i class="fas fa-sticky-note"></i> ملاحظة :</b>
                    </div>
                    <div class="col-sm-10">
                        <textarea class="form-control" style="resize: none" name="note" id="note" placeholder=" ملاحظة ..."></textarea>
                    </div>
                </div>

                {{-- type of paid --}}
                <div class="form-group row">
                    <div class="col-sm-6">
                        <input type="radio" value="0" name="paidType" checked/> <i class="fas fa-money-bill-wave"></i> دفع نقدا
                    </div>
                    <div class="col-sm-6">
                        <input type="radio"  value="1" name="paidType"/> <i class="fas fa-file-invoice-dollar"></i> تسجيل الى ملف الدين
                    </div>
                </div>

                
                <div id="result_form" style="display: none" class="alert text-center"></div>

                <hr>

                <div class="form-group justify-content-center">
                    @if ( count( $parts ) > 0)
                        <button class="btn btn-success w-100 btnSave"> <i class="fas fa-cart-arrow-down"></i> تسجيل </button>
                    @else
                        <button class="btn btn-success w-100 btnSave" disabled> <i class="fas fa-cart-arrow-down"></i> تسجيل </button>
                        <br/><br/>
                        <div class="alert alert-danger w-100 text-center"> <b> يجب اضافة قطع اولا  !</b> </div>
                    @endif
                </div>
            </form>
        </div>
    <div>

    {{-- to zoom image --}}
    <div class="form_show_image" id="formShowImage">
        <div class="row justify-content-center">
            <img src="" id="image" class="img-fluid" style="  position: absolute; max-width: 90%; max-height: 100%;" alt="يوجد مشكلة في تحميل الصورة" />
        </div>
        <div style="width: 100%; height: 100%;" id="borderImage"></div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('js/salesOperations.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script>
        var final_price = "{{ \App\Part::all()->first()->selling_price }}";
        
        $(document).ready(function(){
            $('.customers').select2({
                dir: "rtl",
                dropdownAutoWidth: true,
                dropdownParent: $('#box_customers'),
                matcher: matchCustom
            });

            function matchCustom(params, data) {
               
                if ($.trim(params.term) === '') {
                return data;
                }

                if (typeof data.text === 'undefined') {

                    return null;
                }

                if (data.text.indexOf(params.term) > -1) {
                    var modifiedData = $.extend({}, data, true);
                    modifiedData.text += '';
                    return modifiedData;
                }
                $("#clearName").show();
                $("#customer_name").val( params.term );
                $("#label_customer_name").html( params.term );
                return null;
            }

        });
    </script>
    <script>
       $(document).ready(function(){
            $(".divRegisterOperation").hide(0);
            $(".divRegisterOperation").slideDown(1000);  
        });
    </script>
@endsection
