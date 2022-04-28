@php($rand = \Illuminate\Support\Str::random(10))
<tr class="">
    <td style="padding: 5px; width: 20%">
        <div class="gen_desc_{{$rand}}" style="position: relative">
            <input type="text" class="no-style-input form-control gen_desc_typeahead" value="" name="gen_desc[{{$rand}}]" autocomplete="off">
        </div>
    </td>
    <td style="padding: 5px; width: 10%">
        <div class="unit_cost_{{$rand}}">
            <input rand="{{$rand}}" type="text" class="no-style-input mult unit_cost form-control autonumber autonumber_{{$rand}} text-right" value="" name="unit_cost[{{$rand}}]" autocomplete="off">
        </div>
    </td>
    <td style="padding: 5px; width: 5%">
        <div class="qty_{{$rand}}">
            <input rand="{{$rand}}" type="number" class="no-style-input mult qty form-control text-right" value="" name="qty[{{$rand}}]" autocomplete="off">
        </div>
    </td>
    <td style="padding: 5px; width: 8%">
        <div class="uom_{{$rand}}">
            <select class="no-style-input form-control" style="height: 30px" name="uom[{{$rand}}]">
                {!! \App\Swep\ViewHelpers\__html::populate_options(\App\Swep\Helpers\PPUHelpers::ppmpSizes()) !!}
            </select>
        </div>
    </td>
    <td style="padding: 5px; width: 10%">
        <input rand="{{$rand}}" type="text" class="no-style-input total form-control text-right" value="" autocomplete="off" readonly tabindex="-1">
    </td>
    <td style="padding: 5px; width: 8%">
        <div class="mode_of_proc_{{$rand}}">
            <select class="no-style-input form-control" style="height: 30px" name="mode_of_proc[{{$rand}}]">
                {!! \App\Swep\ViewHelpers\__html::populate_options(\App\Swep\Helpers\Helper::modesOfProcurement()) !!}
            </select>
        </div>
    </td>
    <td style="padding: 5px; width: 8%">
        <div class="source_of_fund_{{$rand}}">
            <select class="no-style-input form-control" style="height: 30px" name="source_of_fund[{{$rand}}]">
                {!! \App\Swep\ViewHelpers\__html::populate_options(\App\Swep\Helpers\Helper::fundSources()) !!}
            </select>
        </div>
    </td>


    @foreach(\App\Swep\Helpers\Helper::milestones() as $month)
        <td>
            <div class="ppmp-input-group qty_{{strtolower($month)}}_{{$rand}} minimal">
                <input type="text" class="no-style-input qty_{{strtolower($month)}} month-box" value="" name="qty_{{strtolower($month)}}[{{$rand}}]" autocomplete="off">
            </div>
        </td>
    @endforeach


    <td>
        <button class="btn btn-xs btn-danger remove_row_btn" type="button"><i class="fa fa-times"></i></button>
    </td>
</tr>

<script type="text/javascript">
    const autonumericElement_{{$rand}} = AutoNumeric.multiple('.autonumber_{{$rand}}');
</script>