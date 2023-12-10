<?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td> <?php echo e(date('Y-m-d h:i A', strtotime($customer->created_at))); ?> </td>
        <td> <?php echo e($customer->name); ?> </td>
        <td> <?php echo e($customer->phone_number); ?> </td>
        <td> <?php echo e($customer->car_number); ?> </td>
        <td> <?php echo e($customer->address); ?> </td>
        <?php if( auth()->user()->type == "admin" ): ?>
            <td> <a href="" class="text-info btnCreateReport" data-target="#ReportModal" data-toggle="modal"><i class="fas fa-print"></i> تقرير لعمليات الشراء </a> </td>
        <?php endif; ?>
        <td> <a href="" class="text-primary btnEditCustomer" data-toggle="modal" data-target="#CustomerModal"> <i class="fas fa-edit"></i> تعديل </a> </td>
        <td> <a href="" class="text-danger btnDeleteCustomer"> <i class="fas fa-trash"></i> حذف </a> </td>
        <td class="d-none">
            <a class="data" data="<?php echo e($customer); ?>"></a>
        </td>
    </tr>  
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<script>
    count_of_request = "<?php echo e(count( $customers )); ?>";
</script><?php /**PATH D:\laragon\www\Garage Management\resources\views/layouts/divs/customer.blade.php ENDPATH**/ ?>