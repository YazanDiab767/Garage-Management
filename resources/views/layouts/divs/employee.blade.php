@foreach ($employees as $employee)
    <tr>
        <td> {{ date('Y-m-d h:i A', strtotime($employee->created_at)) }} </td>
        <td> {{ $employee->name }} </td>
        <td> {{ $employee->phone_number }} </td>
        <td> {{ $employee->address }} </td>
        <td> {{ ( $employee->type == "admin" ) ? "مدير" : "موظف" }} </td>
        <td> <a href="{{$employee->id}}/{{$employee->name}}" class="text-primary btnEditEmployee" data-toggle="modal" data-target="#EmployeeModal"> <i class="fas fa-edit"></i> تعديل </a> </td>
        <td> <a href="{{$employee->id}}/{{$employee->name}}" class="text-danger btnDeleteEmployee"> <i class="fas fa-trash"></i> حذف </a> </td>
    </tr>  
@endforeach