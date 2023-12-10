<?php $__env->startSection('title','عمليات البيع'); ?>

<?php $__env->startSection('style'); ?>
    <link href="<?php echo e(asset('css/select2.min.css')); ?>" rel="stylesheet" />
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <div class="container-fluid mt-3" style="">
        <div class="alert text-right">
            <div>
                <form id="formFilter">
                    <div class="form-group row" dir="rtl">
                        <div class="col-sm-1 text-center">
                            <i class="fas fa-search"></i> فلترة :
                        </div>
                        <div class="col-sm-11">
                            <input type="text" name="text_filter" id="text_filter" placeholder="ادخل بيانات للبحث عنها" class="form-control" />
                            <input type="checkbox" name="not_paid_check" id="not_paid_check" /> <label for="not_paid_check">غير دفوع</label>
                        </div>
                    </div>
                    <div class="form-group row" dir="rtl">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-1">
                            من :
                        </div>
                        <div class="col-sm-2">
                            <input type="date" class="form-control" id="start_date" name = "start_date">
                        </div>
                        <div class="col-sm-1">
                            الى :
                        </div>
                        <div class="col-sm-2">
                            <input type="date" class="form-control" id="end_date" name = "end_date">
                        </div>

                    </div>
                </form>

                <hr>

                <div class="row" dir="rtl">
                    <div class="col-sm-6">
                        <a href="#" id="makeReportResults" class="btn btn-info"> <i class="fas fa-print"></i> انشاء تقرير للنتائج الحالية </a>
                    </div>
                    <div class="col-sm-2">
                        <b> العدد : </b>
                        <b id="count">  </b>
                    </div>
                    <div class="col-sm-2">
                        <b> المجموع : </b>
                        <b id="total_amount"> 0 </b>
                    </div>

                    <div class="col-sm-2 <?php if(auth()->user()->type != "admin"): ?> d-none <?php endif; ?>">
                        <b> صافي الربح : </b>
                        <b id="final_amount"> 0 </b>
                    </div>

                </div>
            </div>
        </div>

        <div id="operations">
            <?php echo $__env->make('layouts.divs.sale_operation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        
        <?php if( \App\SaleOperation::count() >= \App\SaleOperation::$paginate ): ?>
            <div class="row justify-content-center mt-4">
                <button class="btn btn-primary btnShowMore">  عرض المزيد <i class="fas fa-chevron-circle-down"></i> </button>
            </div>
        <?php endif; ?>
    </div>

    <div class="modal fade" id="sale_operation_modal">
        <div class="modal-dialog modal-xl">
          <div class="modal-content" id="body_sale_operation_modal" dir="rtl">
          </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('js/salesOperations.js')); ?>"></script>

    <script>
        var amount = 0;
        var ids;
        var paginate = "<?php echo e(\App\SaleOperation::$paginate); ?>";
        var count;
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\Garage Management\resources\views/sales_operations.blade.php ENDPATH**/ ?>