@extends('layouts.header')

@section('title','القطع')

@section('style')
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
        .form_show_image button{
            position: fixed;
            left: 0;
            top: 0;
        }
        img{
            cursor: pointer;
        }
    </style>
@endsection

@section('body')
    <div class="container-fluid mt-3" style="">
        <div class="text-right">
            {{-- general info about parts --}}
            <div class="row">
                @php
                    $total_selling_price = \App\Part::value(DB::raw("SUM(selling_price * count)"));
                    $total_original_price = \App\Part::value(DB::raw("SUM(orignal_price * count)"));
                @endphp
                <div class="col-sm-3">
                    <b> مجموع الربح للقطع الحالية : <span>{{ $total_selling_price - $total_original_price }}</span>  شيكل </b>
                </div>
                <div class="col-sm-3">
                    <b> مجموع سعر البيع : <span> {{ $total_selling_price }}</span>  شيكل </b>
                </div>
                <div class="col-sm-3">
                    <b>مجموع السعر الاصلي : <span>{{ $total_original_price }}</span>شيكل </b>
                </div>
                <div class="col-sm-3">
                    <b> عدد القطع : <span id="count_of_parts">{{ \App\Part::count() }}</span>  </b>
                    <span class=""> / </span>
                    <b> عدد القطع * الكمية : <span id="count_of_parts">{{ \App\Part::sum('count') }}</span> </b>
                </div>
            </div>

            <hr>

            <a href="{{ route('reports.empty_parts') }}" class="btn btn-danger text-white mr-5"> انشاء تقرير للقطع التي كميتها نفذت <i class="fas fa-print"></i> </a>
            <a href="{{ route('reports.parts') }}" class="btn btn-info text-white mr-5"> انشاء تقرير لجميع القطع <i class="fas fa-print"></i> </a>
            <a href="#" id="btn_show_form_add" class="btn btn-primary mr-5" data-toggle="modal" data-target="#PartModal"> اضافة قطعة جديدة <i class="fas fa-plus"></i> </a>
            
            <hr>

            <div>
                <form id="formFilter">
                    <div class="form-group row" dir="rtl">
                        <div class="col-sm-1 text-center">
                            <i class="fas fa-search"></i> فلترة : 
                        </div>
                        <div class="col-sm-11">
                            <input type="text" name="text_filter" id="text_filter" placeholder="ادخل اي بيانات للبحث عنها" class="form-control" />
                        </div>
                    </div>
                </form>
            </div>

        </div>

        <table class="table table-striped" dir="rtl">
            <thead>
              <tr>
                <th> تاريخ اضافة القطعة </th>
                <th> رقم القطعة الاصلي </th>
                <th> رقم القطعة </th>
                <th> البيان </th>
                <th> اسم القطعة </th>
                <th> اسم المورد </th>
                <th> اسم الرف </th>
                <th> الكمية </th>
                <th> صورة </th>
                <th> السعر الاصلي </th>
                <th> سعر البيع </th>
                <th> اضافة الكمية </th>
                <th> تعديل </th>
                <th> حذف </th>
              </tr>
            </thead>
            <tbody id="parts">
                @include('layouts.divs.part')
            </tbody>
        </table>
        {{-- show more button --}}
        @if ( \App\Part::count() >= \App\Part::$paginate )
            <div class="row justify-content-center">
                <button class="btn btn-primary btnShowMore" id="btnShowMore">  عرض المزيد <i class="fas fa-chevron-circle-down"></i> </button>
            </div>
        @endif
    </div>

    {{-- form part --}}

    <div class="modal fade" id="PartModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content" dir="rtl">
          
            <!-- Modal Header -->
            <div class="modal-header">
              <h5 class="modal-title" id="title_partModal"></h5>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row justify-content-center">
                    <form  action="" method="POST" id="form_part" class="w-75">

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span> رقم القطعة الاصلي </span>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" name="original_number" id="part_original_number" placeholder="رقم القطعة الاصلي" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span> رقم القطعة </span>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" name="number" id="part_number" placeholder="رقم القطعة" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span> البيان </span>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" name="description" id="part_description" placeholder=" البيان" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span> اسم القطعة </span>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" name="name" id="part_name" placeholder="اسم القطعة" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span> المورد </span>
                            </div>
                            <div class="col-sm-9">
                                <select name="supplier" id="part_supplier" class="form-control">
                                    @forelse (\App\Supplier::all() as $supplier)
                                        <option value="{{ $supplier->id }}"> {{ $supplier->name }} </option>
                                    @empty
                                        <option value="0"> لا يوجد اي مورد ! </option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span> الرف </span>
                            </div>
                            <div class="col-sm-9">
                                <select name="place" id="part_place" class="form-control">
                                    @forelse (\App\Place::all() as $place)
                                        <option value="{{ $place->id }}"> {{ $place->name }} </option>
                                    @empty
                                        <option value="0"> لا يوجد اي رف ! </option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span> الكمية  </span>
                            </div>
                            <div class="col-sm-9">
                                <input type="number" name="count" id="part_count" placeholder="الكمية" class="form-control" />
                            </div>
                        </div>

                        
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span> اختر صورة </span>
                            </div>
                            <div class="col-sm-9">
                                <input type="file" name="image" id="part_image" placeholder="صورة" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span> السعر الاصلي  </span>
                            </div>
                            <div class="col-sm-9">
                                <input type="number" name="orignal_price" id="part_orignal_price" placeholder="السعر الاصلي" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span> سعر البيع   </span>
                            </div>
                            <div class="col-sm-9">
                                <input type="number" name="selling_price" id="part_selling_price" placeholder=" سعر البيع " class="form-control" />
                            </div>
                        </div>

      
                        <div id="result_form" style="display: none" class="alert text-center"></div>
                    </form>
                </div>
            </div>
            
            <!-- Modal footer -->
            <div class="modal-footer" dir="rtl">
                <button id="btn_save_part" class="btn btn-success text-white ml-5"> <i class="fas fa-save"></i> حفظ </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> اغلاق </button>
            </div>
            
          </div>
        </div>
    </div>


    {{-- form add quantity --}}

    <div class="modal fade" id="QuantityModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" dir="rtl">
            
              <!-- Modal Header -->
              <div class="modal-header">
                <h5 class="modal-title" id="title_QuantityModal"> اضافة كمية جديدة </h5>
              </div>
              
              <!-- Modal body -->
              <div class="modal-body">
                  <div class="row justify-content-center">
                      <form action="" method="POST" id="form_quantity" class="w-75">
                          <div class="form-group row">
                              <div class="col-sm-3">
                                  <span> الكمية  </span>
                              </div>
                              <div class="col-sm-9">
                                  <input type="number" name="count" id="quantity" min="1" placeholder="الكمية" class="form-control" />
                              </div>
                          </div>

                          <div id="result_form2" style="display: none" class="alert text-center"></div>
                      </form>
                  </div>
              </div>
              
              <!-- Modal footer -->
              <div class="modal-footer" dir="rtl">
                  <button type="button" id="btn_save_quantity" class="btn btn-success ml-5"> <i class="fas fa-save"></i> حفظ </button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> اغلاق </button>
              </div>
              
            </div>
          </div>
    </div>

    {{-- to zoom image --}}
    <div class="form_show_image" id="formShowImage">
        <div class="row justify-content-center">
            <img src="" id="image" class="img-fluid" style="  position: absolute; max-width: 90%; max-height: 100%;" alt="يوجد مشكلة في تحميل الصورة" />
        </div>
        <div style="width: 100%; height: 100%;" id="borderImage"></div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('js/parts.js') }}"></script>
    <script>
        var counts = {{ \App\Part::count() }};
        var paginate = {{ \App\Part::$paginate }};
        var count_of_request; // count of parts in request
    </script>
@endsection
