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
    <div class="container-fluid">
        <div class="row" dir="rtl">
            <div class="col-sm-3">
                <b> اسم الزبون : <?php echo e($sale_operation->customer->name); ?> </b>
            </div>
            <div class="col-sm-3">
                <b> تاريخ و وقت الشراء : <?php echo e($sale_operation->created_at); ?> </b>
            </div>
        </div>
        <br>
        <div class="row justify-content-center" id="table">
            <table class="table text-center"  dir="rtl" border>
                <thead>
                    <tr class="bg-light">
                        <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th> <?php echo e($column); ?> </th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                <thead>
                <tbody>
                    <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td> <?php echo e($data); ?> </td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <?php for($i = 0; $i < count( $columns ) - 2; $i++): ?>
                            <td></td>
                        <?php endfor; ?>
                        <td> المجموع :  </td>
                        <td> <?php echo e($sale_operation->price); ?> شيكل </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <div class="row justify-content-center">
        <div class="col-sm-5">
            <input type='button' id='btn' value='طباعة' class="btn btn-primary w-100" onclick='printDiv();'>
        </div>
        <div class="col-sm-2">
            
        </div>
        <div class="col-sm-5">
            <a href="#" class="btn btn-info text-white w-100" onclick="history.back()"> خلف </a>  
        </div>
    </div>

    <script>
        function printDiv() 
        {

            var divToPrint=document.getElementById('table');

            var newWin=window.open('','Print-Window');

            newWin.document.open();

            newWin.document.write(`
                <html>
                    <style>
                        *{
                            font-size: 25px;
                            text-align: center;
                        }
                        table{
                            width: 95%;
                        }
                    </style>
                    <body onload="window.print()" style="width:100%;">
                        ${divToPrint.innerHTML}
                    </body>
                </html>
            `);

            newWin.document.close();

            setTimeout(function(){newWin.close();},10);

        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\Garage Management\resources\views/report.blade.php ENDPATH**/ ?>