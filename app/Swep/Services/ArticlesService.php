<?php


namespace App\Swep\Services;


use App\Models\Articles;
use App\Swep\BaseClasses\BaseService;

class ArticlesService extends BaseService
{
    public function makeStockNo(){
        $a = Articles::query()->select('stockNo')->orderBy('stockNo','desc')->first();
        return (empty($a) ? 1 : ($a->stockNo + 1));
    }
}