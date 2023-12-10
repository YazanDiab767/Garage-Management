<div class="form-group row partRow text-center">
    <div class="col-sm-7">
        <div class="box" style="text-align: right; direction: rtl; position: relative;">
            <select name="" id="" class="form-control partsDiv">
                <option value="0" selected disabled>اختر القطعة</option>
                <?php $__empty_1 = true; $__currentLoopData = \App\Part::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $part["place"] = $part->place;    
                    ?>
                    <?php if( $part->count > 0 ): ?>
                        <option value="<?php echo e($part); ?>">
                            <?php echo e($part->name); ?> / <?php echo e($part->description); ?> /  <?php echo e($part->number); ?> / <?php echo e($part->original_number); ?>

                        </option>
                    <?php else: ?> 
                        <option value="<?php echo e($part); ?>" disabled>
                            <?php echo e($part->name); ?> / <?php echo e($part->description); ?> / <?php echo e($part->number); ?> / <?php echo e($part->original_number); ?>

                        </option>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <option value="0"> لا يوجد اي قطعة ! </option>
                <?php endif; ?>
            </select>
        </div>
    </div>
    <div class="col-sm-1">
        <input type="number" class="form-control quantityPart" placeholder="الكمية" value="1" min="1" max="<?php echo e($parts[0]->count); ?>" />
    </div>
    <div class="col-sm-2">
        <input type="number" class="form-control pricePart text-center" placeholder="السعر" value="0" disabled />
    </div>
    <div class="col-sm-2">
        <input type="number" class="form-control add_discount_price w-50" placeholder="خصم" value="0"  />
    </div>

    
    <div class="col-sm-12 text-right">
        <label class="info_part" style="">
            الرف : 
        </label>
        /
        <label style="font-size: 15px;"> سعر القطعة :  <span class="priceOfParts"> </span> شيكل </label>
        /
        <label style="font-size: 15px;"> عدد القطع المتوفرة : <span class="countOfParts">  <span> </label>
        /
        <a href="parts/part.png" class="btnShowImage"> <i class="fas fa-image"></i> عرض الصورة </a>

        <a href="#" class="text-danger float-left btnDeleteRow"> <i class="fas fa-minus-circle"></i> حذف  </a>
    </div>

</div>

<script>
    $('.partsDiv').select2({
        dir: "rtl",
        dropdownAutoWidth: true,
    }); 
</script><?php /**PATH D:\laragon\www\Garage Management\resources\views/layouts/divs/divPart.blade.php ENDPATH**/ ?>