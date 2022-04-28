<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\RecommendedBudget
 *
 * @property int $id
 * @property int $fiscal_year
 * @property string $budget_type
 * @property string $resp_center
 * @property string $pap_code
 * @property string $pap_desc
 * @property string $ps
 * @property string $co
 * @property string $mooe
 * @property string $pcent_share
 * @property string|null $user_created
 * @property string|null $user_updated
 * @property string|null $ip_created
 * @property string|null $ip_updated
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget whereBudgetType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget whereCo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget whereFiscalYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget whereIpCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget whereIpUpdated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget whereMooe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget wherePapCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget wherePapDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget wherePcentShare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget wherePs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget whereRespCenter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget whereUserCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget whereUserUpdated($value)
 * @mixin \Eloquent
 * @property string $pap_title
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget wherePapTitle($value)
 * @property string|null $division
 * @property string|null $section
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget whereDivision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget whereSection($value)
 * @property string $slug
 * @method static \Illuminate\Database\Eloquent\Builder|RecommendedBudget whereSlug($value)
 */
class RecommendedBudget extends Model
{

    public static function boot()
    {
        parent::boot();
        static::creating(function ($a){
            $a->user_created = Auth::user()->user_id;
            $a->ip_created = request()->ip();
        });

        static::updating(function ($a){
            $a->user_updated = Auth::user()->user_id;
            $a->ip_updated = request()->ip();
        });
    }

    public $table = 'rec_budget';
    protected $attributes = [
        'ps' => 0.00,
        'co' => 0.00,
        'mooe' => 0.00,
    ];
}