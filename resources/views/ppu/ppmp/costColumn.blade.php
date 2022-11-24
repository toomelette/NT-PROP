<div class="text-right">
    <span class="pull-left text-strong">
        {{$data->budgetType}}
    </span>
    <span class="text-strong text-right">
        {{number_format($data->estTotalCost,2)}}
    </span>
</div>
<div class="table-subdetail text-right" style="color: #31708f">
    <span class="pull-left text-strong">
        {{\App\Swep\Helpers\Helper::toSentence($data->article->modeOfProc ?? '')}}
    </span>
    {{number_format($data->unitCost,2)}} x {{$data->qty ?? 0}} <b>{{$data->article->uom ?? ''}}</b>
</div>