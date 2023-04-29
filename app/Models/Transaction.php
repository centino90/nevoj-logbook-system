<?php

namespace App\Models;

use App\Models\Scopes\TransactionByRoleScope;
use Carbon\Carbon;
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
        'served_at'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new TransactionByRoleScope);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public static function getTransactionsPreviousAndCurrentWeek() {
        return self::countTransactionsByWeek();
    }

    public static function getTransactionsPreviousAndCurrentMonth() {
        return self::countTransactionsByMonth();
    }

    public static function getTransactionsPreviousAndCurrentYear() {
        return self::countTransactionsByYear();
    }

    public static function countTransactionsByWeek($startWeekIndex = 0, $endWeekIndex = 6) {
        return collect([
            'previous' => self::whereBetween('created_at', [Carbon::now()->subWeek(1)->startOfWeek($startWeekIndex), Carbon::now()->subWeek(1)->endOfWeek($endWeekIndex)])->count(),
            'current' => self::whereBetween('created_at', [Carbon::now()->subWeek(0)->startOfWeek($startWeekIndex), Carbon::now()->subWeek(0)->endOfWeek($endWeekIndex)])->count()
        ]);
    }

    public static function countTransactionsByMonth() {
        return collect([
            'previous' => self::whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth(1)->endOfMonth()])->count(),
            'current' => self::whereBetween('created_at', [Carbon::now()->subMonth(0)->startOfMonth(), Carbon::now()->subMonth(0)->endOfMonth()])->count()
        ]);
    }

    public static function countTransactionsByYear() {
        return collect([
            'previous' => self::whereBetween('created_at', [Carbon::now()->subYear(1)->startOfYear(), Carbon::now()->subYear(1)->endOfYear()])->count(),
            'current' => self::whereBetween('created_at', [Carbon::now()->subYear(0)->startOfYear(), Carbon::now()->subYear(0)->endOfYear()])->count()
        ]);
    }
}
