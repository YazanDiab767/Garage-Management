<?php $__env->startSection('style'); ?>
    <style>
        table {
        table-layout: fixed ;
        width: 100% ;
        }
        td {
        width: 25% ;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="row justify-content-center mt-1">
        <div class="col-sm-12 text-center">
            <a href="#" class="btn btn-info text-white w-50" onclick="history.back()"> خلف <i class="fas fa-arrow-circle-left"></i> </a>
        </div>
        <div class="col-sm-12">
            <br/>
        </div>
        <div class="col-sm-12 text-center">
            <button id="btn" class="btn btn-primary w-50" onclick='printDiv();'>
                طباعة
                <i class="fas fa-print"></i>
            </button>
        </div>
    </div>

    <div class="row justify-content-center mt-4" dir="rtl">
        <div class="col-sm-3">
            حجم الخط :
        </div>
        <div class="col-sm-3">
            <select class="form-control" id="font_size">
                <?php for($i = 2; $i <= 30; $i++): ?>
                    <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>


    <div class="container-fluid text-center mt-4" id="table" style="margin: auto !important;">
        <?php $amount = 0; $final_amount = 0;  $ids = []; ?>
        <?php if(count( $operations ) == 1): ?>
            
            <div class="op mt-5" dir="rtl" style="background-color: #f7f7f7;border: 1px solid black; padding: 20px; border-radius: 5px;">
                <?php
                    $operation = $operations[0];
                    $data = json_decode( $operation->data , true  );
                    $amount += ( $operation->price + $operation->discount_add) ;
                    array_push($ids, $operation->id);
                ?>

                <div class="row text-center">
                    <div class="col-sm-4">
                        <b> الموظف : <?php echo e($operation->username); ?> </b>
                    </div>
                    <div class="col-sm-4">
                        <b> الزبون : <?php echo e($operation->customer->name); ?> </b>
                    </div>
                    <div class="col-sm-4">
                        <b> التاريخ و الوقت : <?php echo e(date('Y-m-d h:i A', strtotime($operation->created_at))); ?> </b>
                    </div>
                </div>
                <hr>
                <div>
                    <?php
                        //if there is add or discount => divide the addition over parts
                        $pr = 0;
                        $rem = 0;
                        if ( $operation->discount_add != 0 )
                            $pr = $operation->discount_add / count( $data );
                        $dis = 0;
                    ?>

                    <br/>

                    <div class="row text-center mt-3">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-10">
                            <table class="table table-bordered" border="">
                                <tr class="bg-light">
                                    <th> القطعة </th>
                                    <th> الكمية </th>
                                    <th> السعر </th>
                                </tr>
                                <?php for($i = 0; $i < sizeof( $data ); $i++): ?>
                                <tr>
                                        <td> <?php echo e($data[$i]["part_name"]); ?> </td>
                                        <td> <?php echo e($data[$i]["quantity"]); ?> </td>
                                        <td>
                                            <?php if( isset($data[$i]["add_sub"]) && $data[$i]["add_sub"] != 0): ?>
                                                <?php
                                                    $dis += $data[$i]["add_sub"];
                                                    $amount += $data[$i]["add_sub"];
                                                ?>

                                                <?php if( ( $data[$i]["total_price"] + $data[$i]["add_sub"] + $pr + $rem ) > 0 ): ?>
                                                    <?php echo e(sprintf('%.2f', $data[$i]["total_price"] + $data[$i]["add_sub"] + $pr + $rem )); ?> شيكل
                                                    <?php $rem = 0; ?>
                                                <?php else: ?>
                                                    <?php echo e(sprintf('%.2f', $data[$i]["total_price"] + $data[$i]["add_sub"] )); ?> شيكل
                                                    <?php $rem += $pr; ?>
                                                <?php endif; ?>

                                            <?php else: ?>

                                                <?php if( ( $data[$i]["total_price"] + $pr + $rem ) > 0 ): ?>
                                                    <?php echo e(sprintf('%.2f', $data[$i]["total_price"] + $pr + $rem )); ?> شيكل
                                                    <?php $rem = 0; ?>
                                                <?php else: ?>
                                                    <?php echo e(sprintf('%.2f', $data[$i]["total_price"] )); ?> شيكل
                                                    <?php $rem += $pr; ?>
                                                <?php endif; ?>

                                            <?php endif; ?>
                                        </td>
                                </tr>
                                    <?php
                                        $final_amount +=  (  ( $data[$i]["total_price"]) - ($data[$i]["orignal_price"] * $data[$i]["quantity"])  );
                                    ?>
                                <?php endfor; ?>
                            </table>
                        </div>
                        <div class="col-sm-1"></div>
                    </div>
                </div>

                <br/>

                <?php if(  $_GET['withNote'] == 1 ): ?>
                    <div class="float-right">
                        ملاحظة : <?php echo e($operation->note); ?>

                    </div>
                <?php endif; ?>

                <br/>
                <hr>

                <div class="text-center">
                    <div class="">
                        <b> <u> المجموع الكلي : </u> </b>
                        <b> <u> <?php echo e(sprintf ( '%.2f' , ( $operation->price ) + $operation->discount_add + $dis , 2 )); ?> شيكل </u> </b>
                        <br/><br/>
                    </div>
                </div>

            </div>
        <?php else: ?>
        <div class="float-right"> العدد : <?php echo e(count( $operations )); ?> </div>
        
            <table class="table table-bordered" dir="rtl">
                <tr>
                    <td> التاريخ </td>
                    <td> اسم الموظف </td>
                    <td> اسم الزبون </td>
                    <td> القطعة </td>
                    <td> الكمية </td>
                    <td> السعر </td>
                </tr>
                <?php $__currentLoopData = $operations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $operation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $data = json_decode( $operation->data , true  );
                        $amount += ( $operation->price + $operation->discount_add) ;
                    ?>
                    <?php
                        //if there is add or discount => divide the addition over parts
                        $pr = 0;
                        $rem = 0;
                        if ( $operation->discount_add != 0 )
                            $pr = $operation->discount_add / count( $data );
                        $dis = 0;
                    ?>
                    <?php for($i = 0; $i < sizeof( $data ); $i++): ?>
                        <tr>
                            <td> <?php echo e(date('Y-m-d h:i A', strtotime($operation->created_at))); ?> </td>
                            <td> <?php echo e($operation->username); ?> </td>
                            <td> <?php echo e($operation->customer->name); ?> </td>
                            <td> <?php echo e($data[$i]["part_name"]); ?> </td>
                            <td> <?php echo e($data[$i]["quantity"]); ?> </td>
                            <td>
                                <?php if( isset($data[$i]["add_sub"]) && $data[$i]["add_sub"] != 0): ?>
                                    <?php
                                        $dis += $data[$i]["add_sub"];
                                        $amount += $data[$i]["add_sub"];
                                    ?>

                                    <?php if( ( $data[$i]["total_price"] + $data[$i]["add_sub"] + $pr + $rem ) > 0 ): ?>
                                        <?php echo e(sprintf('%.2f', $data[$i]["total_price"] + $data[$i]["add_sub"] + $pr + $rem )); ?> شيكل
                                        <?php $rem = 0; ?>
                                    <?php else: ?>
                                        <?php echo e(sprintf('%.2f', $data[$i]["total_price"] + $data[$i]["add_sub"] )); ?> شيكل
                                        <?php $rem += $pr; ?>
                                    <?php endif; ?>

                                <?php else: ?>

                                    <?php if( ( $data[$i]["total_price"] + $pr + $rem ) > 0 ): ?>
                                        <?php echo e(sprintf('%.2f', $data[$i]["total_price"] + $pr + $rem )); ?> شيكل
                                        <?php $rem = 0; ?>
                                    <?php else: ?>
                                        <?php echo e(sprintf('%.2f', $data[$i]["total_price"] )); ?> شيكل
                                        <?php $rem += $pr; ?>
                                    <?php endif; ?>

                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                            $final_amount +=  (  ( $data[$i]["total_price"]) - ($data[$i]["orignal_price"] * $data[$i]["quantity"])  );
                        ?>
                    <?php endfor; ?>
                    <?php $final_amount += ($operation->discount_add + $dis); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td></td><td></td><td></td><td></td>
                    <td> مجموع البيع  </td>
                    <td> <?php echo e(sprintf ( '%.2f' , $amount , 2 )); ?> شيكل </td>
                </tr>

                <tr id="finalAmount" style="visibility: hidden">
                    <td></td><td></td><td></td><td></td>
                    <td> مجموع الربح </td>
                    <td> <?php echo e(sprintf ( '%.2f' , $final_amount , 2 )); ?> شيكل </td>
                </tr>
            </table>
        <?php endif; ?>
    </div>

    <div class="row mt-4 pb-5">

        <div class="col-sm-1">
             <label for="showFinalAmount">Total Profit :</label>
        </div>

        <div class="col-sm-1 text-left">
            <input type="checkbox" class="" id="showFinalAmount" />
        </div>

    </div>

    <script>

        document.getElementById('showFinalAmount').onchange = function (){
            let v = document.getElementById('finalAmount').style.visibility;
            if ( v == "visible" )
                document.getElementById('finalAmount').style.visibility = "hidden";
            else
                document.getElementById('finalAmount').style.visibility = "visible";
        }

        function printDiv()
        {
            let font_size = document.getElementById('font_size').value;

            var divToPrint=document.getElementById('table');

            var newWin=window.open('','Print-Window');

            newWin.document.open();

            newWin.document.write(
                `
                    <html>
                        <head>
                        <link rel="stylesheet" href="/css/bootstrap.min.css" />
                        </head>
                        <style>
                            *{
                                font-size: ${ font_size }px;
                            }
                        </style>

                        <body onload="window.print()" style="width:100%;">
                            ${divToPrint.innerHTML}
                        </body>


                    </html>
                `
            );

            newWin.document.close();

            setTimeout(function(){newWin.close();},2000);
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\Garage Management\resources\views/reports/sales_operations.blade.php ENDPATH**/ ?>