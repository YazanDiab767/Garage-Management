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
    $('.partsDiv').select2({
        dir: "rtl",
        dropdownAutoWidth: true,
    }); 
</script>

<div class="container">
    <div class="row justify-content-center divRegisterOperation" style="border: 1.5px solid black; border-radius: 15px; padding: 20px; box-shadow: 1px 0px 6px 1px black;">
        <h3> <i class="fas fa-edit"></i> تعديل عملية البيع  </h3>
        
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
                            @forelse (\App\Customer::all() as $customer)
                                <option value="{{ $customer->id }}"
                                    @if ($customer->id == $operation->customer->id)
                                        selected
                                    @endif    
                                >
                                {{ $customer->name }} </option>
                            @empty
                                <option value="0"> لا يوجد اي زبون ! </option>
                            @endforelse
                        </select>
                    </div>
                    <br/>
                    <input type="text" placeholder="اسم الزبون" value="{{ $operation->customer->name }}" name="customer_name" id="customer_name" class="form-control" hidden/>
                    
                    <label class="float-right">
                        <b>
                            <u>
                                <span> الاسم : </span>
                                <span id="label_customer_name"> {{ $operation->customer->name }} </span> 
                            </u>
                            <a href="#" id="clearName" class="text-danger mr-3"> <i class="fas fa-times"></i> مسح  </a> 
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

                @php
                    //extract ids
                    $data = json_decode( $operation->data , true  );
                    $dis = 0;
                @endphp

                @for ($i = 0; $i < sizeof( $data ); $i++)
                    @php
                        $p = \App\Part::find($data[$i]['part_id']);  
                        if ( isset($data[$i]["add_sub"]) && $data[$i]["add_sub"] != 0 ) 
                            $dis += $data[$i]["add_sub"]; 
                        else
                            $data[$i]["add_sub"] = 0;
                    @endphp
                    <div class="form-group row partRow text-center">
                        <div class="col-sm-7">
                            <div class="box" style="text-align: right; direction: rtl; position: relative;">
                                <select name="" id="" class="form-control partsDiv">
                                    <option value="0" selected disabled>اختر القطعة</option>
                                    @forelse (\App\Part::all() as $part)
                                        @php
                                            $part["place"] = $part->place;    
                                        @endphp
                                        @if ( $part->count > 0 || $part->id == $data[$i]["part_id"])
                                            @if ( $part->id == $data[$i]["part_id"] )
                                                @php $part->count += $data[$i]['quantity']; @endphp
                                            @endif
                                            <option value="{{ $part }}"
                                                @if ($part->id == $data[$i]["part_id"])
                                                    selected
                                                @endif
                                            >
                                                {{ $part->name }} / {{ $part->description }} /  {{ $part->number }} / {{ $part->original_number }}
                                            </option>
                                        @else {{-- there is no quantity --}}
                                            <option value="{{ $part }}" disabled>
                                                {{ $part->name }} / {{ $part->description }} / {{ $part->number }} / {{ $part->original_number }}
                                            </option>
                                        @endif
                                    @empty
                                        <option value="0"> لا يوجد اي قطعة ! </option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <input type="number" class="form-control quantityPart" placeholder="الكمية" value="{{ $data[$i]['quantity'] }}" min="1" max="{{ $p->count + $data[$i]['quantity'] }}" />
                        </div>
                        <div class="col-sm-2">
                            <input type="number" class="form-control pricePart text-center" value="{{ $data[$i]["total_price"] + $data[$i]["add_sub"] }}" placeholder="السعر" value="0" disabled />
                        </div>
                        <div class="col-sm-2">
                            <input type="number" class="form-control add_discount_price w-50" value="{{ $data[$i]["add_sub"] }}" placeholder="خصم" value="0"  />
                        </div>
                    
                        {{-- info about part ( pirce , count , place , image ) --}}
                        <div class="col-sm-12 text-right">
                            <label class="info_part" style="">
                                الرف : {{ $p->place->name  }}
                            </label>
                            /
                            <label style="font-size: 15px;"> سعر القطعة :  <span class="priceOfParts"> {{ $p->selling_price }} </span> شيكل </label>
                            /
                            <label style="font-size: 15px;"> عدد القطع المتوفرة : <span class="countOfParts"> {{ $p->count + $data[$i]['quantity'] }} <span> </label>
                            /
                            <a href="/{{ $p->image }}" class="btnShowImage"> <i class="fas fa-image"></i> عرض الصورة </a>
                    
                            <a href="#" class="text-danger float-left btnDeleteRow"> <i class="fas fa-minus-circle"></i> حذف  </a>
                        </div>
                    
                    </div>
    
                @endfor
                
            </div>

            @if ( \App\Part::count() > 0 )
                <a class="btn text-danger btnAddRowPart float-right" href="#"> <i class="fas fa-plus"></i> اضف قطعة جديدة </a><br/>
                <label> السعر الكلي للقطع : <span class="totalPrice"> {{ $operation->price + $dis }} </span> <span> شيكل </span> </label><br/>
                <input type="number" class="form-control add_discount_input float-left" style="width: 8%;" value="{{ $operation->discount_add }}" placeholder="اضافة / خصم" />
                <br/><br/>
                <label> <b> <u> الصافي للدفع : <span class="finalPrice"> {{ $operation->price + $operation->discount_add + $dis }} </span> <span> شيكل <span> </u> </b> </label><br/>
            @endif

            <hr>

            {{-- note --}}
            <div class="form-group row">
                <div class="col-sm-2">
                    <b class="text-left float-right"> <i class="fas fa-sticky-note"></i> ملاحظة :</b>
                </div>
                <div class="col-sm-10">
                    <textarea class="form-control" name="note" id="note" placeholder=" ملاحظة ...">{{ $operation->note }}</textarea>
                </div>
            </div>

            {{-- type of paid --}}
            <div class="form-group row">
                <div class="col-sm-6">
                    <input type="radio" value="0" name="paidType" @if ($operation->paid_at) checked @endif /> <i class="fas fa-money-bill-wave"></i> دفع نقدا
                </div>
                <div class="col-sm-6">
                    <input type="radio"  value="1" name="paidType" @if (!$operation->paid_at) checked @endif  /> <i class="fas fa-file-invoice-dollar"></i> تسجيل الى ملف الدين
                </div>
            </div>

            
            <div id="result_form" style="display: none" class="alert text-center"></div>

            <hr>

            <div class="form-group justify-content-center">
                @if ( \App\Part::count() > 0 )
                    <button class="btn btn-success w-100 btnUpdate" data="{{ $operation->id }}"> <i class="fas fa-save"></i> حفظ </button>
                @else
                    <button class="btn btn-success w-100 btnUpdate" disabled> <i class="fas fa-save"></i> حفظ </button>
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
