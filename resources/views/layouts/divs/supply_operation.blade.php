@foreach ($supply_operations as $key => $value)
    <tr>
        <td> {{ date('Y-m-d h:i A', strtotime($supply_operations[$key]->created_at ))}} </td>
        <td> {{ $supply_operations[$key]->part_name }} </td>
        <td> {{ $supply_operations[$key]->count }} </td>
        <td> {{ $supply_operations[$key]->price }} </td>
        <td>
            @if (! $supply_operations[$key]->paid_at)
                <div class="divPaid">
                    غير مدفوع 
                    <a href='{{$supply_operations[$key]->id}}' class='btn btn-primary btnPaid'> تسديد </a>
                </div>
            @else
                مدفوع
                {{ date('Y-m-d h:i A', strtotime($supply_operations[$key]->paid_at)) }}
            @endif
    
        </td>
        <td> <a href="" class="btn btn-primary btnEditHistory" data-toggle="modal" data-target="#addHistoryModal" > تعديل <i class="fas fa-edit"></i> </a> </td>
        <td> <a href="" class="btn btn-danger btnDeleteHistory"> حذف <i class="fas fa-trash"></i> </a> </td>
        <td class="d-none">
            <a class="data" data="{{ $supply_operations[$key] }}"></a>
        </td>
    </tr>
@endforeach
@php
$f = 0;
if ( count($supply_operations) >= \App\SupplyOperation::$paginate )
   $f = 1;
@endphp

@php
    $total_amount = \App\SupplyOperation::whereNull('paid_at')
                ->where('supplier_id',$id)
                ->sum('price');     
                
    $all_amount = \App\SupplyOperation::where('supplier_id',$id)
                    ->sum('price');    
@endphp

<script>
     var t = "{{$f}}";
     document.getElementById('formShowMoreOps').innerHTML = " ";
    if ( t == 1)
    {
        document.getElementById('formShowMoreOps').innerHTML = 
        `
            <a href="{{$id}}" class = "btn btn-info text-white btnShowMoreOps"> عرض المزيد </a>
        `;
    }

    var all_amount = "{{ $all_amount }}";
    var total_amount = "{{ $total_amount }}";

    document.getElementById('all_amount').innerHTML = all_amount;
    document.getElementById('total_amount').innerHTML = total_amount;

</script>