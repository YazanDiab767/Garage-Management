<?php $__env->startSection('title','الزبائن'); ?>

<?php $__env->startSection('body'); ?>
    <div class="container mt-3" style="">
        <div class="alert text-right">
            <a href="#" id="btn_show_formAdd" class="btn btn-primary mr-5" data-toggle="modal" data-target="#CustomerModal"> اضافة زبون جديد <i class="fas fa-plus"></i> </a>
            <b clas="mr-5"> عدد الزبائن : <span id="count_of_customers"><?php echo e(\App\Customer::count()); ?></span> # </b>
            <hr>
            <div>
                <form id="formFilter">
                    <div class="form-group row" dir="rtl">
                        <div class="col-sm-1 text-center">
                            <i class="fas fa-search"></i> فلترة : 
                        </div>
                        <div class="col-sm-11">
                            <input type="text" name="text_filter" id="text_filter" placeholder="ادخل اسم الزبون او رقم الهاتف او رقم السيارة او العنوان" class="form-control" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-striped" dir="rtl">
            <thead>
              <tr>
                <th> تاريخ الاضافة </th>
                <th> الاسم </th>
                <th> رقم الهاتف </th>
                <th> رقم السيارة </th>
                <th> العنوان </th>
                <?php if( auth()->user()->type == "admin" ): ?>
                    <th> تقرير </th>
                <?php endif; ?>
                <th> تعديل </th>
                <th> حذف </th>
              </tr>
            </thead>
            <tbody id="customers">
                <?php echo $__env->make('layouts.divs.customer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </tbody>
        </table>
        
        <?php if( \App\Customer::count() >= \App\Customer::$paginate ): ?>
            <div class="row justify-content-center">
                <button class="btn btn-primary btnShowMore">  عرض المزيد <i class="fas fa-chevron-circle-down"></i> </button>
            </div>
        <?php endif; ?>
    </div>

    

    <div class="modal fade" id="CustomerModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content" dir="rtl">
          
            <!-- Modal Header -->
            <div class="modal-header">
              <h5 class="modal-title" id="title_customerModal"></h5>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row justify-content-center">
                    <form method="POST" id="form_customer" class="w-75">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span>اسم الزبون :</span>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" name="name" id="customer_name" placeholder="اسم الزبون" class="form-control" />
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
                                <span>رقم السيارة :</span>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" name="car_number" id="car_number" placeholder="رقم السيارة" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span>العنوان :</span>
                            </div>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="address" id="customer_address" placeholder="عنوان الزبون"></textarea>
                            </div>
                        </div>

                        <div id="result_form" style="display: none" class="alert text-center"></div>
                    </form>
                </div>
            </div>
            
            <!-- Modal footer -->
            <div class="modal-footer" dir="rtl">
                <button type="button" id="btn_save_customer" class="btn btn-success ml-5"> <i class="fas fa-save"></i> حفظ </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> اغلاق </button>
            </div>
            
          </div>
        </div>
    </div>

    <div class="modal fade" id="ReportModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content" dir="rtl">
          
            <!-- Modal Header -->
            <div class="modal-header">
              <h5 class="modal-title" id="title_reportModal"> انشاء تقرير لعمليات الشراء </h5>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row justify-content-center">
                    <form method="GET" id="form_report" class="w-75">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span>من تاريخ : </span>
                            </div>
                            <div class="col-sm-9">
                                <input type="date" name="start_date" id="start_date"  class="form-control" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span> الى تاريخ : </span>
                            </div>
                            <div class="col-sm-9">
                                <input type="date" name="end_date" id="end_date" class="form-control" />
                            </div>
                        </div>

                        <div id="result_form" style="display: none" class="alert text-center"></div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="submit" onclick="" class="btn btn-success w-100"> انشاء </button>
                            </div>
                        </div>
                        
                    </form>
                </div>
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
    <script src="<?php echo e(asset('js/customers.js')); ?>"></script>
    <script>
        var counts = <?php echo e($customers->count()); ?>;
        var paginate = <?php echo e(\App\Customer::$paginate); ?>;
        var count_of_request; // count of customer in request
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\Garage Management\resources\views/customers.blade.php ENDPATH**/ ?>