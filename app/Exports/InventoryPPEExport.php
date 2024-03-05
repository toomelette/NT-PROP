<?php

namespace App\Exports;

use App\Models\InventoryPPE;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventoryPPEExport implements FromQuery
{
    use Exportable;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return InventoryPPE::query()
            ->select('invtacctcode', 'fund_cluster', 'article', 'description', 'propertyno', 'uom', 'acquiredcost', 'qtypercard', 'onhandqty', 'shortqty', 'shortvalue', 'dateacquired', 'acctemployee_fname', 'remarks')
            ->where(function ($query) {
                $query->where('condition', '!=', 'DERECOGNIZED')
                    ->orWhereNull('condition')
                    ->orWhere('condition', '');
            })
            ->when($this->request->has('period_covered'), function ($query) {
                return $query->whereBetween('dateacquired', [$this->request->date_start, $this->request->date_end]);
            }, function ($query) {
                return $query->whereDate('dateacquired', '<=', $this->request->as_of);
            })
            ->when($this->request->filled('fund_cluster'), function ($query) {
                return $query->where('fund_cluster', '=', $this->request->fund_cluster);
            });
    }
}
