<?php $__env->startSection('content'); ?>
    <?php
        function selectedItem($name) // to set class selected on current page
        {
            $page_name = basename( $_SERVER['REQUEST_URI'] );
            if ( $name == $page_name )
                echo "selected";
        }
    ?>
    <nav class="navbar" dir="rtl">
        <a class="navbar-brand text-white" href="#"> <i class="fas fa-user-circle"></i> الموظف : <?php echo e(auth()->user()->name); ?>  </a>

        <ul class="nav navbar-nav mx-auto" dir="rtl">

            <li class="nav-item">
                <a class="nav-link <?php selectedItem('home') ?>" href="<?php echo e(route('home')); ?>"> <i class="fas fa-home"></i> الرئيسية  </a>
            </li>
            <?php if( auth()->user()->type == "admin" ): ?>
                <li class="nav-item">
                    <a class="nav-link <?php selectedItem('places') ?>" href="<?php echo e(route('places.index')); ?>"> <i class="fas fa-border-all"></i> الرفوف </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php selectedItem('parts') ?>" href="<?php echo e(route('parts.index')); ?>"> <i class="fas fa-chart-pie"></i> القطع </a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link <?php selectedItem('customers') ?>" href="<?php echo e(route('customers.index')); ?>"> <i class="fas fa-users"></i> الزبائن </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php selectedItem('salesOperations') ?>" href="<?php echo e(route('salesOperations.index')); ?>"> <i class="fas fa-file-invoice-dollar"></i> عمليات البيع </a>
            </li>
            <?php if( auth()->user()->type == "admin" ): ?>

                <li class="nav-item">
                    <a class="nav-link <?php selectedItem('suppliers') ?>" href="<?php echo e(route('suppliers.index')); ?>"> <i class="fas fa-ambulance"></i> ملف الموردين </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php selectedItem('employees') ?>" href="<?php echo e(route('employees.index')); ?>"> <i class="fas fa-users"></i> ملف الموظفين </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php selectedItem('statistics') ?>" href="<?php echo e(route('statistics.index')); ?>"> <i class="fas fa-chart-bar"></i> احصائيات </a>
                </li>

            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" href="" data-toggle="modal" data-target="#passwordModal"> <i class="fas fa-user-lock"></i> كلمة المرور  </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('logout')); ?>"> <i class="fas fa-sign-out-alt"></i> تسجيل الخروج  </a>
            </li>
            
        </ul>
    </nav>

    <?php echo $__env->yieldContent('body'); ?>

    

    <div class="modal fade passwordModal" id="passwordModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" dir="rtl">
            
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title"> <i class="fas fa-user-lock"></i> تعديل كلمة المرور </h5>
                </div>
                
                <!-- Modal body -->
                <div class="modal-body">
                    <form action="" id="formUpdatePassword" method="POST">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                كلمة المرور الحالية
                            </div>
                            <div class="col-sm-9">
                                <input type="password" name="current_password" id="current_password" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">
                                كلمة المرور الجديدة
                            </div>
                            <div class="col-sm-9">
                                <input type="password" name="new_password" id="new_password" class="form-control" />
                            </div>
                        </div>

                        <div style="display: none" class="alert text-center result_form_password"></div>

                    </form>
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer" dir="rtl">
                    <button  class="btn btn-primary ml-5" id="btnAddNewHistory"> <i class="fas fa-save"></i> حفظ </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> اغلاق </button>
                </div>
            
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\Garage Management\resources\views/layouts/header.blade.php ENDPATH**/ ?>