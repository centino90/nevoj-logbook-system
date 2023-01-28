<?php

namespace App\Models;

use App\Models\Scopes\TransactionByRoleScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course',
        'visitor_name',
        'purpose',
        'user_id',
        'note',
        'served'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new TransactionByRoleScope);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
