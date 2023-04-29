<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Scopes\TransactionByRoleScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function($model){
            $pwd = 'password';
            // $pwd = Str::random(20);
            $model->password = Hash::make($pwd);
        });
        self::created(function($model){
            //todo: email the password
        });
    }

    /* HELPERS */

    /** Trends **/

    public static function getTopFiveProfessorsByTransactions() {
        return self::withCount(['servedTransactions', 'unservedTransactions', 'transactions'])
            ->onlyProfessors()
            ->orderByDesc('served_transactions_count')
            ->orderByDesc('unserved_transactions_count')
            ->orderByDesc('transactions_count')
            ->take(5)
            ->get();
    }

    public static function getTopFiveProfessorsByTransactionsInCurrentWeek() {
        return self::countTransactionsInCurrentWeek()
        ->onlyProfessors()
        ->orderByDesc('served_transactions_count')
        ->orderByDesc('unserved_transactions_count')
        ->orderByDesc('transactions_count')
        ->take(5)
        ->get();
    }

    public static function getTopFiveProfessorsByTransactionsInCurrentMonth() {
        return self::countTransactionsInCurrentMonth()
        ->onlyProfessors()
        ->orderByDesc('served_transactions_count')
        ->orderByDesc('unserved_transactions_count')
        ->orderByDesc('transactions_count')
        ->take(5)
        ->get();
    }

    public static function getTopFiveProfessorsByTransactionsInCurrentQuarter() {
        return self::countTransactionsInCurrentQuarter()
        ->onlyProfessors()
        ->orderByDesc('served_transactions_count')
        ->orderByDesc('unserved_transactions_count')
        ->orderByDesc('transactions_count')
        ->take(5)
        ->get();
    }

    public static function getTopFiveProfessorsByTransactionsInCurrentYear() {
        return self::countTransactionsInCurrentYear()
        ->onlyProfessors()
        ->orderByDesc('served_transactions_count')
        ->orderByDesc('unserved_transactions_count')
        ->orderByDesc('transactions_count')
        ->take(5)
        ->get();
    }

    /** With Count **/

    public static function countTransactionsInCurrentWeek($startWeekIndex = 0, $endWeekIndex = 6) {
        return self::withCount([
            'servedTransactions' => function ($query) use($startWeekIndex, $endWeekIndex) {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek($startWeekIndex), Carbon::now()->endOfWeek($endWeekIndex)]);
            },
            'unservedTransactions' => function ($query) use($startWeekIndex, $endWeekIndex) {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek($startWeekIndex), Carbon::now()->endOfWeek($endWeekIndex)]);
            },
            'transactions' => function ($query) use($startWeekIndex, $endWeekIndex) {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek($startWeekIndex), Carbon::now()->endOfWeek($endWeekIndex)]);
            },
        ]);
    }

    public static function countTransactionsInCurrentMonth() {
        return self::withCount([
            'servedTransactions' => function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            },
            'unservedTransactions' => function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            },
            'transactions' => function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            },
        ]);
    }

    public static function countTransactionsInCurrentQuarter() {
        return self::withCount([
            'servedTransactions' => function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->startOfQuarter(), Carbon::now()->endOfQuarter()]);
            },
            'unservedTransactions' => function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->startOfQuarter(), Carbon::now()->endOfQuarter()]);
            },
            'transactions' => function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->startOfQuarter(), Carbon::now()->endOfQuarter()]);
            },
        ]);
    }

    public static function countTransactionsInCurrentYear() {
        return self::withCount([
            'servedTransactions' => function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
            },
            'unservedTransactions' => function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
            },
            'transactions' => function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
            },
        ]);
    }

    /* LOCAL SCOPES */

    public function scopeOnlyProfessors($query) {
        return $query->whereHas('roles', function(Builder $query) {
            return $query->where('name', 'professor');
        });
    }

    /* RELATIONSHIPS */
    public function transactions() {
        return $this->hasMany(Transaction::class)->withoutGlobalScopes([TransactionByRoleScope::class]);
    }

    public function servedTransactions() {
        return $this->hasMany(Transaction::class)->withoutGlobalScopes([TransactionByRoleScope::class])->whereNotNull('served_at');
    }
    public function weeklyServedTransactions() {
        return $this->hasMany(Transaction::class)->withoutGlobalScopes([TransactionByRoleScope::class])->whereNotNull('served_at');
    }
    public function monthlyServedTransactions() {
        return $this->hasMany(Transaction::class)->withoutGlobalScopes([TransactionByRoleScope::class])->whereNotNull('served_at');
    }
    public function yearlyServedTransactions() {
        return $this->hasMany(Transaction::class)->withoutGlobalScopes([TransactionByRoleScope::class])->whereNotNull('served_at');
    }

    public function unservedTransactions() {
        return $this->hasMany(Transaction::class)->withoutGlobalScopes([TransactionByRoleScope::class])->whereNull('served_at');
    }
}
