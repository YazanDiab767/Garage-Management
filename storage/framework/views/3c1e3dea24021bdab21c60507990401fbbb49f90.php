<?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td> <?php echo e(date('Y-m-d h:i A', strtotime($employee->created_at))); ?> </td>
        <td> <?php echo e($employee->name); ?> </td>
        <td> <?php echo e($employee->phone_number); ?> </td>
        <td> <?php echo e($employee->address); ?> </td>
        <td> <?php echo e(( $employee->type == "admin" ) ? "مدير" : "موظف"); ?> </td>
        <td> <a href="<?php echo e($employee->id); ?>/<?php echo e($employee->name); ?>" class="text-primary btnEditEmployee" data-toggle="modal" data-target="#EmployeeModal"> <i class="fas fa-edit"></i> تعديل </a> </td>
        <td> <a href="<?php echo e($employee->id); ?>/<?php echo e($employee->name); ?>" class="text-danger btnDeleteEmployee"> <i class="fas fa-trash"></i> حذف </a> </td>
    </tr>  
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH D:\laragon\www\Garage Management\resources\views/layouts/divs/employee.blade.php ENDPATH**/ ?>