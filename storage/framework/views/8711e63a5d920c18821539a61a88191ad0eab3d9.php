<?php $__env->startSection('title','القطع'); ?>

<?php $__env->startSection('body'); ?>
    <div class="container mt-3" style="">
        <div class="alert text-right">
            <a href="#" id="btn_show_formAdd" class="btn btn-primary mr-5" data-toggle="modal" data-target="#PlaceModal"> اضافة رف جديد <i class="fas fa-plus"></i> </a>
            <b clas="mr-5"> عدد الرفوف : <span id="count_of_places"><?php echo e($places->count()); ?></span> # </b>
        </div>
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
        <table class="table table-striped text-center" dir="rtl">
            <thead>
              <tr>
                <th> اسم الرف </th>
                <th> المكان </th>
                <th> عدد القطع الموجودة </th>
                <th> تعديل </th>
                <th> حذف </th>
              </tr>
            </thead>
            <tbody id="places">
                <?php echo $__env->make('layouts.divs.place', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </tbody>
        </table>
    </div>

    

    <div class="modal fade" id="PlaceModal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content" dir="rtl">
          
            <!-- Modal Header -->
            <div class="modal-header">
              <h5 class="modal-title" id="title_placeModal"></h5>
            </div>
            
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row justify-content-center">
                    <form method="POST" id="form_place" class="w-75">
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <span> اسم الرف : </span>
                            </div>
                            <div class="col-sm-10">
                                <input type="text" name="name" id="place_name" placeholder="اسم الرف" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-2">
                                <span> وصف المكان : </span>
                            </div>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="place" id="place_description" placeholder="مكان الرف"></textarea>
                            </div>
                        </div>

                        <div id="result_form" style="display: none" class="alert text-center"></div>
                    </form>
                </div>
            </div>
            
            <!-- Modal footer -->
            <div class="modal-footer" dir="rtl">
                <button type="button" id="btn_save_place" class="btn btn-success ml-5"> <i class="fas fa-save"></i> حفظ </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> اغلاق </button>
            </div>
            
          </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('js/places.js')); ?>"></script>
    <script>
        var counts = <?php echo e($places->count()); ?>;
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\Garage Management\resources\views/places.blade.php ENDPATH**/ ?>