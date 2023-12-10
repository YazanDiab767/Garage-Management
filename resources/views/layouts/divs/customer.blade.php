@foreach ($customers as $customer)
    <tr>
        <td> {{ date('Y-m-d h:i A', strtotime($customer->created_at)) }} </td>
        <td> {{ $customer->name }} </td>
        <td> {{ $customer->phone_number }} </td>
        <td> {{ $customer->car_number }} </td>
        <td> {{ $customer->address }} </td>
        @if ( auth()->user()->type == "admin" )
            <td> <a href="" class="text-info btnCreateReport" data-target="#ReportModal" data-toggle="modal"><i class="fas fa-print"></i> تقرير لعمليات الشراء </a> </td>
        @endif
        <td> <a href="" class="text-primary btnEditCustomer" data-toggle="modal" data-target="#CustomerModal"> <i class="fas fa-edit"></i> تعديل </a> </td>
        <td> <a href="" class="text-danger btnDeleteCustomer"> <i class="fas fa-trash"></i> حذف </a> </td>
        <td class="d-none">
            <a class="data" data="{{ $customer }}"></a>
        </td>
    </tr>  
@endforeach
<script>
    count_of_request = "{{ count( $customers ) }}";
</script>