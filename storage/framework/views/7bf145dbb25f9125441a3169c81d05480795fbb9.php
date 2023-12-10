<?php $__env->startSection('title','الموردون'); ?>

<?php $__env->startSection('body'); ?>
    <div class="container mt-3" style="">
        <div class="alert text-right">
            <a href="#" id="btn_show_formAdd" class="btn btn-primary mr-5" data-toggle="modal" data-target="#SupplierModal"> اضافة مورد جديد <i class="fas fa-plus"></i> </a>
            <b clas="mr-5"> عدد الموردون : <span id="count_of_suppliers"><?php echo e(\App\Supplier::count()); ?></span> # </b>
            <hr>
            <div>
                <form id="formFilter">
                    <div class="form-group row" dir="rtl">
                        <div class="col-sm-1 text-center">
                            <i class="fas fa-search"></i> فلترة : 
                        </div>
                        <div class="col-sm-11">
                            <input type="text" name="text_filter" id="text_filter" placeholder="ادخل اسم المورد او رقم الهاتف او العنوان" class="form-control" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-striped" dir="rtl">
            <thead>
              <tr>
                <th> تاريخ الاضافة </th>
                <th> اسم المورد </th>
                <th> رقم الهاتف </th>
                <th> العنوان </th>
                <th> عمليات البيع </th>
                <th> تعديل </th>
                <th> حذف </th>
              </tr>
            </thead>
            <tbody id="suppliers">
                <?php echo $__env->make('layouts.divs.supplier', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </tbody>
        </table>
        
        <?php if( \App\Supplier::count() >= \App\Supplier::$paginate ): ?>
            <div class="row justify-content-center">
                <button class="btn btn-primary btnShowMore">  عرض المزيد <i class="fas fa-chevron-circle-down"></i> </button>
            </div>
        <?php endif; ?>
    </div>

    

    <div class="modal fade" id="SupplierModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content" dir="rtl">
          
            <!-- Modal Header -->
            <div class="modal-header">
              <h5 class="modal-title" id="title_placeModal"></h5>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row justify-content-center">
                    <form method="POST" id="form_supplier" class="w-75">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span>اسم المورد :</span>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" name="name" id="supplier_name" placeholder="اسم المورد" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span>رقم الهاتف :</span>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" name="phone_number" id="phone_number" placeholder="رقم الهاتف" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span>العنوان :</span>
                            </div>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="address" id="supplier_address" placeholder="عنوان المورد"></textarea>
                            </div>
                        </div>

                        <div id="result_form" style="display: none" class="alert text-center"></div>
                    </form>
                </div>
            </div>
            
            <!-- Modal footer -->
            <div class="modal-footer" dir="rtl">
                <button type="button" id="btn_save_supplier" class="btn btn-success ml-5"> <i class="fas fa-save"></i> حفظ </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> اغلاق </button>
            </div>
            
          </div>
        </div>
    </div>

    

    <div class="modal fade" id="HistoryModal" style="overflow-y: scroll">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" dir="rtl">
            
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title"> <i class="fas fa-history"></i> <label class="historyTitle"></label>  </h5> 
                </div>
                
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="float-right">
                        <a href="#" class="btn btn-primary" id="btnShowFormNewHistory" data-toggle="modal" data-target="#addHistoryModal"> <i class="fas fa-plus"></i> اضافة عملية جديدة  </a>

                        <a href="#" class="btn btn-info btnCreateReport mr-5" data-toggle="modal" data-target="#reportHistoryModal"> <i class="fas fa-print"></i> انشاء تقرير لهذا المورد  </a>

                        <a href="#" class="btn btn-success btnShowNotes mr-5" data-toggle="modal" data-target="#notesModal"> <i class="fas fa-sticky-note"></i> ملاحظات  </a>

                        <b class="mr-4"> المجموع الكلي : <span id="all_amount">  </span> شيكل </b>
                        
                        <b class="mr-4"> مجموع الدين : <span id="total_amount">  </span> شيكل </b>
                    </div>
                    <br>
                    <div class="mt-5">
                        <form id="formFilterOperations" dir="rtl">
                            <div class="form-group row">
                                <div class="col-sm-1 text-center">
                                    <i class="fas fa-search"></i> فلترة : 
                                </div>
                                <div class="col-sm-11">
                                    <input type="text" name="text_filter_operations" id="text_filter_operations" placeholder="ادخل تاريخ او اسم القطعة" class="form-control" />
                                </div>
                            </div>
                        </form>
                    </div>
                    <br/><br/>
                    <div class="row justify-content-center">
                        <table class="table table-striped operations" dir="rtl">
                            <thead>
                              <tr>
                                  <th> تاريخ العملية </th>
                                  <th> اسم القطعة </th>
                                  <th> الكمية </th>
                                  <th> المبلغ </th>
                                  <th> الدفع </th>
                                  <th> تعديل </th>
                                  <th> حذف </th>
                              </tr>
                            </thead>
                            <tbody id="operations">
                                
                            </tbody>
                        </table>
                        <div id="formShowMoreOps">

                        </div>
                    </div>
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer" dir="rtl">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> اغلاق </button>
                </div>
            
            </div>
        </div>
    </div>

    
    
    <div class="modal fade addHistoryModal" id="addHistoryModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" dir="rtl">
            
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title history_title_modal">  </h5>
                </div>
                
                <!-- Modal body -->
                <div class="modal-body">
                    <form id="form_history">
                        <div class="form-group row">
                            <div class="col-sm-2">
                                اسم القطعة
                            </div>
                            <div class="col-sm-10">
                                <input type="text" name="part_name" id="part_name" class="form-control" placeholder="اسم القطعة" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                الكمية
                            </div>
                            <div class="col-sm-10">
                                <input type="number" name="count" id="count" class="form-control" placeholder="الكمية" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                المبلغ
                            </div>
                            <div class="col-sm-10">
                                <input type="number" name="price" id="price" class="form-control" placeholder="المبلغ" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                حالة الدفع
                            </div>
                            <div class="col-sm-10">
                                <select name="paid" id="paid" class="form-control">
                                    <option value="1">تم الدفع</option>
                                    <option value="0">غير مدفوع</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer" dir="rtl">
                    <button  class="btn btn-primary" id="btn_save_history"> حفظ </button>
                    <button type="button" class="btn btn-secondary mr-5" data-dismiss="modal"><i class="fas fa-times"></i> اغلاق </button>
                </div>
            
            </div>
        </div>
    </div>

    

    <div class="modal fade notesModal" id="notesModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" dir="rtl">
            
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title reporthistoryTitle"> <i class="fas fa-sticky-note"></i> ملف الملاحظات </h5>
                </div>
                
                <!-- Modal body -->
                <div class="modal-body">
                    <form action="" id="formNotes" method="POST">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <textarea id="notes" style="height: 350px;" class="form-control"></textarea>
                            </div>
                        </div>

                        <button  class="btn btn-success w-100" id="btn_save_notes"> <i class="fas fa-save"></i> حفظ  </button>

                    </form>
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer" dir="rtl">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> اغلاق </button>
                </div>
            
            </div>
        </div>
    </div>

    

    <div class="modal fade reportHistoryModal" id="reportHistoryModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" dir="rtl">
            
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title reporthistoryTitle"> انشاء تقرير </h5>
                </div>
                
                <!-- Modal body -->
                <div class="modal-body">
                    <form action="" id="formCreateReport" method="GET">
                        <div class="form-group row">
                            <div class="col-sm-2">
                                من تاريخ
                            </div>
                            <div class="col-sm-10">
                                <input type="date" name="start_date" id="start_date" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                الى تاريخ
                            </div>
                            <div class="col-sm-10">
                                <input type="date" name="end_date" id="end_date" class="form-control" />
                            </div>
                        </div>

                    
             
                        <button  class="btn btn-primary" id=""> انشاء تقرير </button>

                    </form>
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer" dir="rtl">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> اغلاق </button>
                </div>
            
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('js/suppliers.js')); ?>"></script>
    <script>
        var counts = <?php echo e($suppliers->count()); ?>;
        var sup_id; //supplier id
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\Garage Management\resources\views/suppliers.blade.php ENDPATH**/ ?>