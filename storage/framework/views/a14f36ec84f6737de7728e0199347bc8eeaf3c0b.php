<script src="<?php echo e(asset('js/select2.min.js')); ?>"></script>
<script>
    var final_price = "<?php echo e(\App\Part::all()->first()->selling_price); ?>";
    
    $(document).ready(function(){
        $('.customers').select2({
            dir: "rtl",
            dropdownAutoWidth: true,
            dropdownParent: $('#box_customers'),
            matcher: matchCustom
        });

        function matchCustom(params, data) {
        
            if ($.trim(params.term) === '') {
            return data;
            }

            if (typeof data.text === 'undefined') {

                return null;
            }

            if (data.text.indexOf(params.term) > -1) {
                var modifiedData = $.extend({}, data, true);
                modifiedData.text += '';
                return modifiedData;
            }
            $("#clearName").show();
            $("#customer_name").val( params.term );
            $("#label_customer_name").html( params.term );
            return null;
        }

    });
</script>
<script>
    $('.partsDiv').select2({
        dir: "rtl",
        dropdownAutoWidth: true,
    }); 
</script>

<div class="container">
    <div class="row justify-content-center divRegisterOperation" style="border: 1.5px solid black; border-radius: 15px; padding: 20px; box-shadow: 1px 0px 6px 1px black;">
        <h3> <i class="fas fa-edit"></i> تعديل عملية البيع  </h3>
        
        <form method="POST" id="form_supplier" dir="rtl" class="mt-5 w-100">
            
            <div class="form-group row">
                <div class="col-sm-2">
                    <span class="float-right"> <b> <i class="fas fa-user"></i> اسم الزبون : </b> </span>
                </div>
                <div class="col-sm-10">

                    <div id="box_customers" class="box box_customers" style="text-align: right; direction: rtl; position: relative;">
                        <select name="customer" id="customer" class="form-control customers text-white w-100">
                            <option></option>
                            <?php $__empty_1 = true; $__currentLoopData = \App\Customer::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <option value="<?php echo e($customer->id); ?>"
                                    <?php if($customer->id == $operation->customer->id): ?>
                                        selected
                                    <?php endif; ?>    
                                >
                                <?php echo e($customer->name); ?> </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <option value="0"> لا يوجد اي زبون ! </option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <br/>
                    <input type="text" placeholder="اسم الزبون" value="<?php echo e($operation->customer->name); ?>" name="customer_name" id="customer_name" class="form-control" hidden/>
                    
                    <label class="float-right">
                        <b>
                            <u>
                                <span> الاسم : </span>
                                <span id="label_customer_name"> <?php echo e($operation->customer->name); ?> </span> 
                            </u>
                            <a href="#" id="clearName" class="text-danger mr-3"> <i class="fas fa-times"></i> مسح  </a> 
                        </b>
                    </label>
                </div>
            </div>

            <hr>

            
            <div class="form-group row">
                <div class="col-sm-3">
                    <b class="text-left float-right"> <i class="fas fa-coins"></i> القطع : </b> <br />
                </div>
                <div class="col-sm-9"></div>
            </div>                    

            <div class="form-group row text-center">
                <div class="col-sm-7">
                    اسم القطعة
                </div>
                <div class="col-sm-1">
                    الكمية
                </div>
                <div class="col-sm-2">
                    السعر ( شيكل )
                </div>
                <div class="col-sm-2">
                
                </div>
            </div>

            <div class="parts w-100">

                <?php
                    //extract ids
                    $data = json_decode( $operation->data , true  );
                    $dis = 0;
                ?>

                <?php for($i = 0; $i < sizeof( $data ); $i++): ?>
                    <?php
                        $p = \App\Part::find($data[$i]['part_id']);  
                        if ( isset($data[$i]["add_sub"]) && $data[$i]["add_sub"] != 0 ) 
                            $dis += $data[$i]["add_sub"]; 
                        else
                            $data[$i]["add_sub"] = 0;
                    ?>
                    <div class="form-group row partRow text-center">
                        <div class="col-sm-7">
                            <div class="box" style="text-align: right; direction: rtl; position: relative;">
                                <select name="" id="" class="form-control partsDiv">
                                    <option value="0" selected disabled>اختر القطعة</option>
                                    <?php $__empty_1 = true; $__currentLoopData = \App\Part::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php
                                            $part["place"] = $part->place;    
                                        ?>
                                        <?php if( $part->count > 0 || $part->id == $data[$i]["part_id"]): ?>
                                            <?php if( $part->id == $data[$i]["part_id"] ): ?>
                                                <?php $part->count += $data[$i]['quantity']; ?>
                                            <?php endif; ?>
                                            <option value="<?php echo e($part); ?>"
                                                <?php if($part->id == $data[$i]["part_id"]): ?>
                                                    selected
                                                <?php endif; ?>
                                            >
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
                            <input type="number" class="form-control quantityPart" placeholder="الكمية" value="<?php echo e($data[$i]['quantity']); ?>" min="1" max="<?php echo e($p->count + $data[$i]['quantity']); ?>" />
                        </div>
                        <div class="col-sm-2">
                            <input type="number" class="form-control pricePart text-center" value="<?php echo e($data[$i]["total_price"] + $data[$i]["add_sub"]); ?>" placeholder="السعر" value="0" disabled />
                        </div>
                        <div class="col-sm-2">
                            <input type="number" class="form-control add_discount_price w-50" value="<?php echo e($data[$i]["add_sub"]); ?>" placeholder="خصم" value="0"  />
                        </div>
                    
                        
                        <div class="col-sm-12 text-right">
                            <label class="info_part" style="">
                                الرف : <?php echo e($p->place->name); ?>

                            </label>
                            /
                            <label style="font-size: 15px;"> سعر القطعة :  <span class="priceOfParts"> <?php echo e($p->selling_price); ?> </span> شيكل </label>
                            /
                            <label style="font-size: 15px;"> عدد القطع المتوفرة : <span class="countOfParts"> <?php echo e($p->count + $data[$i]['quantity']); ?> <span> </label>
                            /
                            <a href="/<?php echo e($p->image); ?>" class="btnShowImage"> <i class="fas fa-image"></i> عرض الصورة </a>
                    
                            <a href="#" class="text-danger float-left btnDeleteRow"> <i class="fas fa-minus-circle"></i> حذف  </a>
                        </div>
                    
                    </div>
    
                <?php endfor; ?>
                
            </div>

            <?php if( \App\Part::count() > 0 ): ?>
                <a class="btn text-danger btnAddRowPart float-right" href="#"> <i class="fas fa-plus"></i> اضف قطعة جديدة </a><br/>
                <label> السعر الكلي للقطع : <span class="totalPrice"> <?php echo e($operation->price + $dis); ?> </span> <span> شيكل </span> </label><br/>
                <input type="number" class="form-control add_discount_input float-left" style="width: 8%;" value="<?php echo e($operation->discount_add); ?>" placeholder="اضافة / خصم" />
                <br/><br/>
                <label> <b> <u> الصافي للدفع : <span class="finalPrice"> <?php echo e($operation->price + $operation->discount_add + $dis); ?> </span> <span> شيكل <span> </u> </b> </label><br/>
            <?php endif; ?>

            <hr>

            
            <div class="form-group row">
                <div class="col-sm-2">
                    <b class="text-left float-right"> <i class="fas fa-sticky-note"></i> ملاحظة :</b>
                </div>
                <div class="col-sm-10">
                    <textarea class="form-control" name="note" id="note" placeholder=" ملاحظة ..."><?php echo e($operation->note); ?></textarea>
                </div>
            </div>

            
            <div class="form-group row">
                <div class="col-sm-6">
                    <input type="radio" value="0" name="paidType" <?php if($operation->paid_at): ?> checked <?php endif; ?> /> <i class="fas fa-money-bill-wave"></i> دفع نقدا
                </div>
                <div class="col-sm-6">
                    <input type="radio"  value="1" name="paidType" <?php if(!$operation->paid_at): ?> checked <?php endif; ?>  /> <i class="fas fa-file-invoice-dollar"></i> تسجيل الى ملف الدين
                </div>
            </div>

            
            <div id="result_form" style="display: none" class="alert text-center"></div>

            <hr>

            <div class="form-group justify-content-center">
                <?php if( \App\Part::count() > 0 ): ?>
                    <button class="btn btn-success w-100 btnUpdate" data="<?php echo e($operation->id); ?>"> <i class="fas fa-save"></i> حفظ </button>
                <?php else: ?>
                    <button class="btn btn-success w-100 btnUpdate" disabled> <i class="fas fa-save"></i> حفظ </button>
                    <br/><br/>
                    <div class="alert alert-danger w-100 text-center"> <b> يجب اضافة قطع اولا  !</b> </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
<div>


<div class="form_show_image" id="formShowImage">
    <div class="row justify-content-center">
        <img src="" id="image" class="img-fluid" style="  position: absolute; max-width: 90%; max-height: 100%;" alt="يوجد مشكلة في تحميل الصورة" />
    </div>
    <div style="width: 100%; height: 100%;" id="borderImage"></div>
</div>
<?php /**PATH D:\laragon\www\Garage Management\resources\views/layouts/divs/edit_sale_operation.blade.php ENDPATH**/ ?>