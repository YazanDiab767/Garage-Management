<?php $__env->startSection('title','الموظفون'); ?>

<?php $__env->startSection('body'); ?>
    <div class="container mt-3" style="">
        <div class="alert text-right">
            <a href="#" id="btn_show_formAdd" class="btn btn-primary mr-5" data-toggle="modal" data-target="#EmployeeModal"> اضافة موظف جديد <i class="fas fa-plus"></i> </a>
            <b clas="mr-5"> عدد الموظفون : <span id="count_of_employees"><?php echo e(\App\User::count()); ?></span> # </b>
            <hr>
            <div>
                <form id="formFilter">
                    <div class="form-group row" dir="rtl">
                        <div class="col-sm-1 text-center">
                            <i class="fas fa-search"></i> فلترة : 
                        </div>
                        <div class="col-sm-11">
                            <input type="text" name="text_filter" id="text_filter" placeholder="ادخل اسم الموظف او رقم الهاتف او العنوان" class="form-control" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-striped" dir="rtl">
            <thead>
              <tr>
                <th> تاريخ الاضافة </th>
                <th> اسم الموظف </th>
                <th> رقم الهاتف </th>
                <th> العنوان </th>
                <th> النوع </th>
                <th> تعديل </th>
                <th> حذف </th>
              </tr>
            </thead>
            <tbody id="employees">
                <?php echo $__env->make('layouts.divs.employee', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </tbody>
        </table>
        
        <?php if( \App\User::count() >= \App\User::$paginate ): ?>
            <div class="row justify-content-center">
                <button class="btn btn-primary btnShowMore">  عرض المزيد <i class="fas fa-chevron-circle-down"></i> </button>
            </div>
        <?php endif; ?>
    </div>

    

    <div class="modal fade" id="EmployeeModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content" dir="rtl">
          
            <!-- Modal Header -->
            <div class="modal-header">
              <h5 class="modal-title" id="title_employeeModal"></h5>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row justify-content-center">
                    <form method="POST" id="form_employee" class="w-75">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span>اسم الموظف :</span>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" name="name" id="employee_name" placeholder="اسم الموظف" class="form-control" />
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
                                <textarea class="form-control" name="address" id="employee_address" placeholder="عنوان الموظف"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span> نوع الموظف </span>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-control" name="type" id="employee_type">
                                    <option value="employee"> موظف </option>
                                    <option value="admin"> مدير </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                <span>كلمة المرور :</span>
                            </div>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" name="password" id="employee_password" placeholder=" كلمة مرور"/>
                            </div>
                        </div>

                        <div id="result_form" style="display: none" class="alert text-center"></div>
                    </form>
                </div>
            </div>
            
            <!-- Modal footer -->
            <div class="modal-footer" dir="rtl">
                <button type="button" id="btn_save_employee" class="btn btn-success ml-5"> <i class="fas fa-save"></i> حفظ </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> اغلاق </button>
            </div>
            
          </div>
        </div>
    </div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('js/employees.js')); ?>"></script>
    <script>
        var counts = <?php echo e($employees->count()); ?>;
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\Garage Management\resources\views/employees.blade.php ENDPATH**/ ?>