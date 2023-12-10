<?php $__currentLoopData = $supply_operations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td> <?php echo e(date('Y-m-d h:i A', strtotime($supply_operations[$key]->created_at ))); ?> </td>
        <td> <?php echo e($supply_operations[$key]->part_name); ?> </td>
        <td> <?php echo e($supply_operations[$key]->count); ?> </td>
        <td> <?php echo e($supply_operations[$key]->price); ?> </td>
        <td>
            <?php if(! $supply_operations[$key]->paid_at): ?>
                <div class="divPaid">
                    غير مدفوع 
                    <a href='<?php echo e($supply_operations[$key]->id); ?>' class='btn btn-primary btnPaid'> تسديد </a>
                </div>
            <?php else: ?>
                مدفوع
                <?php echo e(date('Y-m-d h:i A', strtotime($supply_operations[$key]->paid_at))); ?>

            <?php endif; ?>
    
        </td>
        <td> <a href="" class="btn btn-primary btnEditHistory" data-toggle="modal" data-target="#addHistoryModal" > تعديل <i class="fas fa-edit"></i> </a> </td>
        <td> <a href="" class="btn btn-danger btnDeleteHistory"> حذف <i class="fas fa-trash"></i> </a> </td>
        <td class="d-none">
            <a class="data" data="<?php echo e($supply_operations[$key]); ?>"></a>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php
$f = 0;
if ( count($supply_operations) >= \App\SupplyOperation::$paginate )
   $f = 1;
?>

<?php
    $total_amount = \App\SupplyOperation::whereNull('paid_at')
                ->where('supplier_id',$id)
                ->sum('price');     
                
    $all_amount = \App\SupplyOperation::where('supplier_id',$id)
                    ->sum('price');    
?>

<script>
     var t = "<?php echo e($f); ?>";
     document.getElementById('formShowMoreOps').innerHTML = " ";
    if ( t == 1)
    {
        document.getElementById('formShowMoreOps').innerHTML = 
        `
            <a href="<?php echo e($id); ?>" class = "btn btn-info text-white btnShowMoreOps"> عرض المزيد </a>
        `;
    }

    var all_amount = "<?php echo e($all_amount); ?>";
    var total_amount = "<?php echo e($total_amount); ?>";

    document.getElementById('all_amount').innerHTML = all_amount;
    document.getElementById('total_amount').innerHTML = total_amount;

</script><?php /**PATH D:\laragon\www\Garage Management\resources\views/layouts/divs/supply_operation.blade.php ENDPATH**/ ?>