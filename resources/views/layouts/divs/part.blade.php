@foreach ($parts as $part)
    <tr>
        <td class="align-middle"> {{ date('Y-m-d h:i A', strtotime($part->created_at ))}} </td>
        <td class="align-middle"> {{ $part->original_number }} </td>
        <td class="align-middle"> {{ $part->number }} </td>
        <td class="align-middle"> {{ $part->description }} </td>
        <td class="align-middle"> {{ $part->name }} </td>
        <td class="align-middle"> {{ $part->supplier->name }} </td>
        <td class="align-middle"> {{ $part->place->name }} </td>
        <td class="align-middle">
            <label 
                @if ( $part->count <= 1 )
                    class="bg-danger text-white"
                    style = "padding: 15px; border-radius: 10px;"
                @endif
            > {{ $part->count }} </label>
        </td>
        <td class="align-middle"> <img src="{{ asset('storage/' . $part->image) }}" class="img-fluid btnShowImage" width="100" height="100"/> </td>
        <td class="align-middle"> {{ $part->orignal_price }} </td>
        <td class="align-middle"> {{ $part->selling_price }} </td>
        <td class="align-middle"> <a href="" class="text-info btnAddQuantity" data-toggle="modal" data-target="#QuantityModal"><i class="fas fa-plus"></i> اضافة كمية جديدة </a> </td>
        <td class="align-middle"> <a href="" class="text-primary btnEditPart" data-toggle="modal" data-target="#PartModal"> <i class="fas fa-edit"></i> تعديل </a> </td>
        <td class="align-middle"> <a href="" class="text-danger btnDeletePart"> <i class="fas fa-trash"></i> حذف </a> </td>
        <td class="align-middle d-none"><a href="" data="{{$part}}" class="data"> </a></td>
    </tr>  
@endforeach
<script>
    count_of_request = "{{ count( $parts ) }}";
</script>