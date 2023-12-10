<?php $__env->startSection('title','احصائيات'); ?>

<?php $__env->startSection('body'); ?>
    <div class="container mt-5" style="">
        <form id="statistics_form" method="GET">
            
            <div class="form-group row" dir="rtl">
                <div> <b>الفترة الاولى :</b> </div>
                <div class="col-sm-1"> من : </div>
                <div class="col-sm-3">
                    <input type="date" name="t1_f_from_date" id="t1_f_from_date" class="form-control w-100"/>
                </div>

                <div class="col-sm-1"></div>

                <div class="col-sm-1"> الى : </div>
                <div class="col-sm-3">
                    <input type="date" name="t1_t_from_date" id="t1_t_from_date" class="form-control w-100" />
                </div>
            </div>
            <br/>
            
            <div class="form-group row" dir="rtl">
                <div> <b>الفترة الثانية :</b> </div>
                <div class="col-sm-1"> من : </div>
                <div class="col-sm-3">
                    <input type="date" name="t2_f_from_date" id="t2_f_from_date" class="form-control w-100" />
                </div>

                <div class="col-sm-1"></div>

                <div class="col-sm-1"> الى : </div>
                <div class="col-sm-3">
                    <input type="date" name="t2_t_from_date" id="t2_t_from_date" class="form-control w-100" />
                </div>
            </div>
        </form>

        <br/>

        <div class="row justify-content-center">
            <button id="btn_get_statistics" class="btn btn-primary w-25"> عرض <i class="fab fa-get-pocket"></i> </button>
        </div>

        <hr><br/>

        <div class="row text-center mt-5" dir="rtl">
            <div class="col-sm-2">
               الفترة كاملة :
            </div>
            <div class="col-sm-10 text-center" style="border: 2px solid black; padding: 2px;">
                <div id="t" class="text-white" style="padding: 15px">

                </div>
            </div>
        </div>

        <br/>
        
        <div class="row text-center mt-5" dir="rtl">
            <div class="col-sm-2">
               الفترة الاولى :
            </div>
            <div class="col-sm-10 text-center" style="border: 2px solid black; padding: 2px;">
                <div id="t1" class="text-white" style="padding: 15px">

                </div>
            </div>
        </div>

        <br>

        <div class="row text-center mt-5" dir="rtl">
            <div class="col-sm-2">
               الفترة الثانية :
            </div>
            <div class="col-sm-10 text-center" style="border: 2px solid black; padding: 2px;">
                <div id="t2" class="text-white" style="padding: 15px;">

                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('js/statistics.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\Garage Management\resources\views/statistics.blade.php ENDPATH**/ ?>