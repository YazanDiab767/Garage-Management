<?php $__currentLoopData = $places; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $place): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $count = \App\Part::where('place_id','=',$place->id)->sum('count');
    ?>
    <tr>
        <td> <?php echo e($place->name); ?> </td>
        <td> <?php echo e($place->place); ?> </td>
        <td> <?php echo e($count); ?> </td>
        <td>
            <a href="<?php echo e($place->id); ?>/<?php echo e($place->name); ?>/<?php echo e($place->place); ?>" class="text-primary btnEditPlace" data-toggle="modal" data-target="#PlaceModal">
                <i class="fas fa-edit"></i> تعديل 
            </a>
        </td>
        <td>
            <a href="<?php echo e($place->id); ?>/<?php echo e($place->name); ?>" class="text-danger btnDeletePlace">
                <i class="fas fa-trash"></i> حذف
            </a>
        </td>
        <td class="d-none">
            <a class="data" data="<?php echo e($place); ?>"></a>
        </td>
    </tr>  
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH D:\laragon\www\Garage Management\resources\views/layouts/divs/place.blade.php ENDPATH**/ ?>