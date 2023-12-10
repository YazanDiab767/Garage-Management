<?php $amount = 0; $final_amount = 0;  $ids = []; ?>
<?php $__currentLoopData = $operations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  
    <div class="op mt-5" dir="rtl" style="background-color: #f7f7f7;border: 1px solid black; padding: 25px; border-radius: 5px;">
        <?php
            $data = json_decode( $operation->data , true  );
            $amount += ( $operation->price + $operation->discount_add) ;  
            array_push($ids, $operation->id);
        ?>

        <div class="row text-center">
            <div class="col-sm-4">
                <b> <i class="fas fa-user-tie"></i> الموظف : <?php echo e($operation->username); ?> </b>
            </div>
            <div class="col-sm-4">
                <b> <i class="fas fa-user"></i> الزبون : <?php echo e($operation->customer->name); ?> </b>
            </div>
            <div class="col-sm-4">
                <b> <i class="fas fa-calendar-alt"></i>  التاريخ و الوقت : <?php echo e(date('Y-m-d h:i A', strtotime($operation->created_at))); ?> </b>
            </div>
        </div>
        <hr>
        <div>
            <?php
                //if there is add or discount => divide the addition over parts
                $pr = $operation->discount_add;    
                $dis = 0;
            ?>

            <br/>
            
            <div class="row text-center mt-3">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <table class="table table-bordered" border="">
                        <tr class="bg-light">
                            <th> اسم القطعة </th>
                            <th> البيان </th>
                            <th> الكمية </th>
                            <th> السعر </th>
                        </tr>
                        <?php for($i = 0; $i < sizeof( $data ); $i++): ?>
                        <tr>       
                                <td> <?php echo e($data[$i]["part_name"]); ?> </td>
                                <td> <?php echo e($data[$i]["description"]); ?> </td>
                                <td> <?php echo e($data[$i]["quantity"]); ?> </td>
                                <td>
                                    <?php echo e($data[$i]["total_price"]); ?> شيكل
                                    <?php if( isset($data[$i]["add_sub"]) && $data[$i]["add_sub"] != 0): ?>
                                        <?php
                                            $dis += $data[$i]["add_sub"];
                                            $amount += $data[$i]["add_sub"];   
                                        ?>
                                        ( اضافة / خصم : <?php echo e($data[$i]["add_sub"]); ?> )
                                        = <?php echo e($data[$i]["total_price"] + $data[$i]["add_sub"]); ?> شيكل
                                    <?php endif; ?>
                                </td>
                        </tr>
                            <?php
                                $final_amount +=  (  ( $data[$i]["total_price"]) - ($data[$i]["orignal_price"] * $data[$i]["quantity"]) );
                            ?>
                        <?php endfor; ?>
                    </table>
                </div>
                <div class="col-sm-1"></div>
            </div>

            <div class="mt-4" style="overflow: hidden">
                <?php $final_amount += ($operation->discount_add + $dis); ?>
                
                <?php if( $operation->discount_add != 0 ): ?>
                    <div>
                        اضافة / خصم :
                        <?php echo e($operation->discount_add); ?> شيكل
                    </div><br/>
                <?php endif; ?>

                <div class="float-right text-center">
                    <a href="" data="<?php echo e(( strlen( $operation->note ) >= 300 ) ? '0' : '1'); ?>" class="showNote" style="color: black"> <b> <i class="fas fa-sticky-note"></i> ملاحظات : </b> </a>
                    <?php if( strlen( $operation->note ) >= 300 ): ?> <b> ..... </b> <?php endif; ?>
                    <br/>
                    <p class="note"
                         <?php if( strlen( $operation->note ) >= 300 ): ?> style="display: none;" <?php endif; ?>
                    >
                        <?php echo ( $operation->note ) ? nl2br($operation->note) : "لايوجد"; ?>

                    </p>
                </div>

                <div class="">
                    <b> <u> المجموع الكلي : </u> </b>
                    <b> <u> <?php echo e(sprintf ( '%.2f' , ( $operation->price ) + $operation->discount_add + $dis , 2 )); ?> شيكل </u> </b>
                    <br/><br/>
                </div>
                
                <?php
                    if ($operation->paid_at == null )
                        $btn = "<label> غير مدفوع  </label> <a href='' class = 'btn btn-warning text-white btnPaidDebt'> <i class='fas fa-check'></i>  دفع </a>";   
                    else
                        $btn = '<i class="fas fa-check-circle"></i> تم الدفع  ' . date('Y-m-d h:i A', strtotime($operation->paid_at))
                ?>
                <?php echo $btn; ?>

            </div>
        </div>
  
        <hr>
        <div class="">
            <a href="" class="btn btn-info ml-5 btnEditOperation" data-toggle="modal" data-target="#sale_operation_modal"> <i class="fas fa-edit"></i> تعديل </a>
            <a href="<?php echo e(route('reports.sales_operations', [1 ,$operation->id])); ?>?withNote=1" class="btn btn-primary ml-5 btnMakeReport">  <i class="fas fa-print"></i> انشاء تقرير </a>
            <a href="<?php echo e($operation->id); ?>" class="btn btn-danger btnDeleteOperation"> <i class="fas fa-undo-alt"></i> استرجاع </a>
        </div>
        
        <div class="d-none">
            <a class="data" data="<?php echo e($operation); ?>"></a>
        </div>

    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<script>
    amount = "<?php echo e($amount); ?>"; // total all sales operations
    final_amount = "<?php echo e($final_amount); ?>"; // profit from sales operations ( selling price - original price )
    ids = "<?php echo e(implode(',',$ids)); ?>"; // save ids current request
    count = "<?php echo e(count($operations)); ?>";
    document.getElementById('count').innerHTML = count;
    document.getElementById('total_amount').innerHTML = amount + " شيكل";
    document.getElementById('final_amount').innerHTML = final_amount + " شيكل";
    document.getElementById('makeReportResults').setAttribute("href", "/reports/sales_operations/0/" + ids);
</script><?php /**PATH D:\laragon\www\Garage Management\resources\views/layouts/divs/sale_operation.blade.php ENDPATH**/ ?>