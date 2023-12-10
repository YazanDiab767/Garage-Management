@foreach ($suppliers as $supplier)
    @php
        $total_price = 0;
        $all_price = 0;
        $total_price = \App\SupplyOperation::whereNull('paid_at')->where('supplier_id',$supplier->id)->sum('price');     
        $all_price = \App\SupplyOperation::where('supplier_id',$supplier->id)->sum('price'); 
        $supplier["total_price"] = $total_price;
        $supplier["all_price"] = $all_price;
    @endphp
    <tr>
        <td> {{ date('Y-m-d h:i A', strtotime($supplier->created_at)) }} </td>
        <td> {{ $supplier->name }} </td>
        <td> {{ $supplier->phone_number }} </td>
        <td> {{ $supplier->address }} </td>
        <td> <a href="" class="text-primary btnShowHistory" data-toggle="modal" data-target="#HistoryModal"> <i class="fas fa-history"></i> العمليات </a> </td>
        <td> <a href="" class="text-primary btnEditSupplier" data-toggle="modal" data-target="#SupplierModal"> <i class="fas fa-edit"></i> تعديل </a> </td>
        <td> <a href="" class="text-danger btnDeleteSupplier"> <i class="fas fa-trash"></i> حذف </a> </td>
        <td class="d-none">
            <a class="data" data="{{ $supplier }}"></a>
            <a class="notes" data="{{ $supplier->notes }}"></a>
        </td>
    </tr>  
@endforeach