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

    <div class="row justify-content-center">
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

    <div class="container-fluid mt-5" id="table">
        <div class="row justify-content-center">
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
                    <?php if($total_price != 0): ?>
                        <tr>
                            <?php for($i = 0; $i < count( $columns ) - 2; $i++): ?>
                            <td></td>
                            <?php endfor; ?>
                            <td> المجموع النهائي : </td>
                            <td> <?php echo e($total_price); ?> </td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>

    <script>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\Garage Management\resources\views/reports/parts.blade.php ENDPATH**/ ?>