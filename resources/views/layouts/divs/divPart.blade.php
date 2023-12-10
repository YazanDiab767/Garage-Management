<div class="form-group row partRow text-center">
    <div class="col-sm-7">
        <div class="box" style="text-align: right; direction: rtl; position: relative;">
            <select name="" id="" class="form-control partsDiv">
                <option value="0" selected disabled>اختر القطعة</option>
                @forelse (\App\Part::all() as $part)
                    @php
                        $part["place"] = $part->place;    
                    @endphp
                    @if ( $part->count > 0 )
                        <option value="{{ $part }}">
                            {{ $part->name }} / {{ $part->description }} /  {{ $part->number }} / {{ $part->original_number }}
                        </option>
                    @else {{-- there is no quantity --}}
                        <option value="{{ $part }}" disabled>
                            {{ $part->name }} / {{ $part->description }} / {{ $part->number }} / {{ $part->original_number }}
                        </option>
                    @endif
                @empty
                    <option value="0"> لا يوجد اي قطعة ! </option>
                @endforelse
            </select>
        </div>
    </div>
    <div class="col-sm-1">
        <input type="number" class="form-control quantityPart" placeholder="الكمية" value="1" min="1" max="{{ $parts[0]->count }}" />
    </div>
    <div class="col-sm-2">
        <input type="number" class="form-control pricePart text-center" placeholder="السعر" value="0" disabled />
    </div>
    <div class="col-sm-2">
        <input type="number" class="form-control add_discount_price w-50" placeholder="خصم" value="0"  />
    </div>

    {{-- info about part ( pirce , count , place , image ) --}}
    <div class="col-sm-12 text-right">
        <label class="info_part" style="">
            الرف : 
        </label>
        /
        <label style="font-size: 15px;"> سعر القطعة :  <span class="priceOfParts"> </span> شيكل </label>
        /
        <label style="font-size: 15px;"> عدد القطع المتوفرة : <span class="countOfParts"> {{-- $parts[0]->count --}} <span> </label>
        /
        <a href="parts/part.png" class="btnShowImage"> <i class="fas fa-image"></i> عرض الصورة </a>

        <a href="#" class="text-danger float-left btnDeleteRow"> <i class="fas fa-minus-circle"></i> حذف  </a>
    </div>

</div>

<script>
    $('.partsDiv').select2({
        dir: "rtl",
        dropdownAutoWidth: true,
    }); 
</script>