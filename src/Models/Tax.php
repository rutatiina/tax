<?php

namespace Rutatiina\Tax\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Scopes\TenantIdScope;

class Tax extends Model
{
    use LogsActivity;

    protected static $logName = 'Tax';
    protected static $logFillable = true;
    protected static $logAttributes = ['*'];
    protected static $logAttributesToIgnore = ['updated_at'];
    protected static $logOnlyDirty = true;

    protected $connection = 'tenant';

    protected $table = 'rg_taxes';

    protected $primaryKey = 'id';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new TenantIdScope);
    }

    public static function findCode($code)
    {
        return static::where('code', $code)->first();
    }

    public function rgGetAttributes()
    {
        $attributes = [];
        $describeTable =  \DB::connection('tenant')->select('describe ' . $this->getTable());

        foreach ($describeTable  as $row) {

            if (in_array($row->Field, ['id', 'created_at', 'updated_at', 'deleted_at', 'tenant_id', 'user_id'])) continue;

            if (in_array($row->Field, ['currencies', 'taxes'])) {
                $attributes[$row->Field] = [];
                continue;
            }

            if ($row->Default == '[]') {
                $attributes[$row->Field] = [];
            } else {
                $attributes[$row->Field] = ''; //$row->Default; //null affects laravel validation
            }
        }

        //add the relationships
        //$attributes['on_sale_account'] = [];
        //$attributes['on_bill_account'] = [];


        return $attributes;
    }

    public function on_sale_account()
    {
        return $this->hasOne('Rutatiina\FinancialAccounting\Models\Account', 'code', 'on_sale_financial_account_code');
    }

    public function on_bill_account()
    {
        return $this->hasOne('Rutatiina\FinancialAccounting\Models\Account', 'code', 'on_bill_financial_account_code');
    }

}
