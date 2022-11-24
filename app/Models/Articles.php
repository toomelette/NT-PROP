<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed acctCode stockNo
 * @property mixed article
 * @property int|mixed stockNo
 * @property mixed modeOfProc
 * @property mixed uom
 * @property mixed type
 * @property int|mixed unitPrice
 */
class Articles extends Model
{
    protected $table = 'inv_master';
}