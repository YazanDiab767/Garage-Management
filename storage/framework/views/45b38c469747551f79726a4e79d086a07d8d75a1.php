<?php $__currentLoopData = $parts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td class="align-middle"> <?php echo e(date('Y-m-d h:i A', strtotime($part->created_at ))); ?> </td>
        <td class="align-middle"> <?php echo e($part->original_number); ?> </td>
        <td class="align-middle"> <?php echo e($part->number); ?> </td>
        <td class="align-middle"> <?php echo e($part->description); ?> </td>
        <td class="align-middle"> <?php echo e($part->name); ?> </td>
        <td class="align-middle"> <?php echo e($part->supplier->name); ?> </td>
        <td class="align-middle"> <?php echo e($part->place->name); ?> </td>
        <td class="align-middle">
            <label 
                <?php if( $part->count <= 1 ): ?>
                    class="bg-danger text-white"
                    style = "padding: 15px; border-radius: 10px;"
                <?php endif; ?>
            > <?php echo e($part->count); ?> </label>
        </td>
        <td class="align-middle"> <img src="<?php echo e(asset('storage/' . $part->image)); ?>" class="img-fluid btnShowImage" width="100" height="100"/> </td>
        <td class="align-middle"> <?php echo e($part->orignal_price); ?> </td>
        <td class="align-middle"> <?php echo e($part->selling_price); ?> </td>
        <td class="align-middle"> <a href="" class="text-info btnAddQuantity" data-toggle="modal" data-target="#QuantityModal"><i class="fas fa-plus"></i> اضافة كمية جديدة </a> </td>
        <td class="align-middle"> <a href="" class="text-primary btnEditPart" data-toggle="modal" data-target="#PartModal"> <i class="fas fa-edit"></i> تعديل </a> </td>
        <td class="align-middle"> <a href="" class="text-danger btnDeletePart"> <i class="fas fa-trash"></i> حذف </a> </td>
        <td class="align-middle d-none"><a href="" data="<?php echo e($part); ?>" class="data"> </a></td>
    </tr>  
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<script>
    count_of_request = "<?php echo e(count( $parts )); ?>";
</script><?php /**PATH D:\laragon\www\Garage Management\resources\views/layouts/divs/part.blade.php ENDPATH**/ ?>