@if($data->action == "DISAPPROVED")
<table style="width: 100%">
    <tr>
        <td style="width: 25%">Action</td>
        <td><small class="label pull-left bg-red">DISAPPROVED</small></td>
    </tr>
    <tr>
        <td>Remarks </td>
        <td>{{($data->remarks)}}</td>
    </tr>

</table>

@else
<table style="width: 100%">

        <tr>
            <td style="width: 25%">Vehicle</td>
            <td>{{($data->vehicleAssigned->make ?? '').' '.($data->vehicleAssigned->model ?? '').' - '.($data->vehicleAssigned->plate_no ?? '')}}</td>
        </tr>

        <tr>
            <td>Driver</td>
            <td>{{($data->driverAssigned->employee->fullname ?? '')}}</td>
        </tr>

</table>

@endif
