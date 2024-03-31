<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    protected $table = 'customers';

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'id',
        'name',
        'username',
        'email',
        'address',
        'phone',
        'id_number',
        'id_front',
        'id_behind',
        'signature',
        'portrait',
        'birthday',
        'gender',
        'career',
        'income',
        'bank_id',
        'bank_account',
        'bank_number',
        'bank_type',
        'loan_purpose',
        'relative_phone1',
        'relative_relationship1',
        'relative_phone2',
        'relative_relationship2',
        'wallet_amount',
        'wallet_status',
        'error_status',
        'status_note',
        'password',
        'public',
    ];

    const BLOCK = 0;
    const NORMAL = 1;

    const ERROR_ONE = 1;
    const ERROR_TWO = 2;
    const ERROR_THREE = 3;
    const ERROR_FOUR = 4;
    const ERROR_FIVE = 5;
    const ERROR_SIX = 6;
    const ERROR_SEVEN = 7;

    public static function loanPurposeOptions()
    {
        return [
            'Mục đích khác',
            'Vay tiêu dùng',
            'Vay Kinh doanh',
            'Vay mua xe',
            'Vay mua nhà'
        ];
    }

    public static function walletStatus()
    {
        return [
            static::BLOCK => 'Khóa',
            static::NORMAL => 'Bình thường',
        ];
    }

    public static function errorStatus()
    {
        return [
            static::ERROR_ONE => 'Sai thông tin liên kết',
            static::ERROR_TWO => 'Đóng băng',
            static::ERROR_THREE => 'Cờ bạc',
            static::ERROR_FOUR => 'Tiền treo',
            static::ERROR_FIVE => 'Bảo hiểm khoản vay',
            static::ERROR_SIX => 'Sai phạm hợp đồng vay vốn',
            static::ERROR_SEVEN => 'Khác',
        ];
    }

    public static function filterOptions()
    {
        return [
            -1 => 'Tất cả',
            0 => 'Hôm qua',
            1 => 'Hôm nay',
            2 => 'Tháng này',
            3 => 'Tháng trước'
        ];
    }

    public function bank() {
        return $this->hasOne(Bank::class, 'id');
    }

    public function customerContracts() {
        return $this->hasMany(CustomerContract::class, 'customer_id');
    }
}
