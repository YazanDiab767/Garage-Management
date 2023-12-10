<?php $__env->startSection('title','القطع'); ?>

<?php $__env->startSection('style'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <div class="container-fluid mt-3" style="">
        <div class="text-right">
            
            <div class="row">
                <?php
                    $total_selling_price = \App\Part::value(DB::raw("SUM(selling_price * count)"));
                    $total_original_price = \App\Part::value(DB::raw("SUM(orignal_price * count)"));
                ?>
                <div class="col-sm-3">
                    <b> مجموع الربح للقطع الحالية : <span><?php echo e($total_selling_price - $total_original_price); ?></span>  شيكل </b>
                </div>
                <div class="col-sm-3">
                    <b> مجموع سعر البيع : <span> <?php echo e($total_selling_price); ?></span>  شيكل </b>
                </div>
                <div class="col-sm-3">
                    <b>مجموع السعر الاصلي : <span><?php echo e($total_original_price); ?></span>شيكل </b>
                </div>
                <div class="col-sm-3">
                    <b> عدد القطع : <span id="count_of_parts"><?php echo e(\App\Part::count()); ?></span>  </b>
                    <span class=""> / </span>
                    <b> عدد القطع * الكمية : <span id="count_of_parts"><?php echo e(\App\Part::sum('count')); ?></span> </b>
                </div>
            </div>

            <hr>

            <a href="<?php echo e(route('reports.empty_parts')); ?>" class="btn btn-danger text-white mr-5"> انشاء تقرير للقطع التي كميتها نفذت <i class="fas fa-print"></i> </a>
            <a href="<?php echo e(route('reports.parts')); ?>" class="btn btn-info text-white mr-5"> انشاء تقرير لجميع القطع <i class="fas fa-print"></i> </a>
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
                <?php echo $__env->make('layouts.divs.part', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </tbody>
        </table>
        
        <?php if( \App\Part::count() >= \App\Part::$paginate ): ?>
            <div class="row justify-content-center">
                <button class="btn btn-primary btnShowMore" id="btnShowMore">  عرض المزيد <i class="fas fa-chevron-circle-down"></i> </button>
            </div>
        <?php endif; ?>
    </div>

    

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
                                    <?php $__empty_1 = true; $__currentLoopData = \App\Supplier::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <option value="<?php echo e($supplier->id); ?>"> <?php echo e($supplier->name); ?> </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <option value="0"> لا يوجد اي مورد ! </option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span> الرف </span>
                            </div>
                            <div class="col-sm-9">
                                <select name="place" id="part_place" class="form-control">
                                    <?php $__empty_1 = true; $__currentLoopData = \App\Place::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $place): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <option value="<?php echo e($place->id); ?>"> <?php echo e($place->name); ?> </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <option value="0"> لا يوجد اي رف ! </option>
                                    <?php endif; ?>
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

    
    <div class="form_show_image" id="formShowImage">
        <div class="row justify-content-center">
            <img src="" id="image" class="img-fluid" style="  position: absolute; max-width: 90%; max-height: 100%;" alt="يوجد مشكلة في تحميل الصورة" />
        </div>
        <div style="width: 100%; height: 100%;" id="borderImage"></div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('js/parts.js')); ?>"></script>
    <script>
        var counts = <?php echo e(\App\Part::count()); ?>;
        var paginate = <?php echo e(\App\Part::$paginate); ?>;
        var count_of_request; // count of parts in request
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\Garage Management\resources\views/parts.blade.php ENDPATH**/ ?>