<?php $__env->startSection('title','الرئيسية'); ?>
<?php $__env->startSection('style'); ?>
    <link href="<?php echo e(asset('css/select2.min.css')); ?>" rel="stylesheet" />
    
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
    <div class="container">
        <div class="row justify-content-center mt-5" style="border: 1.5px solid black; border-radius: 15px; padding: 20px; box-shadow: 1px 0px 6px 1px black;">
            <h3> تسجيل عملية بيع جديدة </h3>
            <form method="POST" id="form_supplier" dir="rtl" class="mt-5 w-100">
                <div class="form-group row">
                    <div class="col-sm-3">
                        <span>اسم الزبون :</span>
                    </div>
                    <div class="col-sm-9">
                        <select name="customer" id="customer" class="form-control customers">
                            <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <option value="<?php echo e($customer->id); ?>"> <?php echo e($customer->name); ?> </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <option value="0"> لا يوجد اي زبون ! </option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <hr>

                <div class="form-group row">
                    <div class="col-sm-3">
                        <b class="text-left"> القطع : </b> <br />
                    </div>
                    <div class="col-sm-9"></div>
                </div>                    

                <div class="parts">

                </div>

                <a class="btn text-danger btnAddRowPart" href="#"> <i class="fas fa-plus"></i> اضف قطعة جديدة </a><br/>

                <label> <b> السعر الكلي : <span class="totalPrice"> <?php echo e(\App\Part::all()->first()->selling_price); ?> </span> </b> </label>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <span>ملاحظة :</span>
                    </div>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="note" id="note" placeholder=" ملاحظة ..."></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-6">
                        <input type="radio" value="0" name="paidType" checked/> دفع نقدا
                    </div>
                    <div class="col-sm-6">
                        <input type="radio"  value="1" name="paidType"/> تسجيل الى ملف الدين
                    </div>
                </div>

                
                <div id="result_form" style="display: none" class="alert text-center"></div>

                <div class="form-group justify-content-center">
                    <button class="btn btn-success w-75 btnSave"> تسجيل </button>
                </div>
            </form>
        </div>
    <div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('js/salesOperations.js')); ?>"></script>
    <script src="<?php echo e(asset('js/select2.min.js')); ?>"></script>
    <script>
        $(document).ready(function(){
            $('.customers').select2();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\Garage Management\resources\views//home.blade.php ENDPATH**/ ?>