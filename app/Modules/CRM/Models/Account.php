<?php

namespace App\Modules\CRM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Traits\Filterable;

class Account extends Model
{
    use SoftDeletes, Filterable;

    protected $table = 'crm_accounts';
    protected $fillable = ['organization_id', 'name', 'industry', 'website', 'tax_id', 'address', 'is_active'];

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
