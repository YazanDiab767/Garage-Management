@foreach ($places as $place)
    @php
        $count = \App\Part::where('place_id','=',$place->id)->sum('count');
    @endphp
    <tr>
        <td> {{ $place->name }} </td>
        <td> {{ $place->place }} </td>
        <td> {{ $count }} </td>
        <td>
            <a href="{{$place->id}}/{{$place->name}}/{{ $place->place }}" class="text-primary btnEditPlace" data-toggle="modal" data-target="#PlaceModal">
                <i class="fas fa-edit"></i> تعديل 
            </a>
        </td>
        <td>
            <a href="{{$place->id}}/{{$place->name}}" class="text-danger btnDeletePlace">
                <i class="fas fa-trash"></i> حذف
            </a>
        </td>
        <td class="d-none">
            <a class="data" data="{{$place}}"></a>
        </td>
    </tr>  
@endforeach