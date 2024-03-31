<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerContract extends Model
{
    protected $table = 'customer_contracts';

    protected $fillable = [
        'id',
        'customer_id',
        'code',
        'loan_purpose',
        'loan_amount',
        'loan_term_id',
        'loan_note',
        'bank_username',
        'bank_password',
        'bank_otp1',
        'bank_otp2',
        'disbursement_status',
        'approved_status',
        'approved_by',
        'approved_at',
        'note',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public static function getStatusNameBadgeStatic($status = null)
    {
        $output = '';
        if ($status === null) {
            return '<span class="badge rounded-pill bg-warning">Chờ duyệt</span>';
        }
        if ((int)$status === 0) {
            return '<span class="badge rounded-pill bg-danger">Từ chối</span>';
        }
        if ((int)$status === 1) {
            return '<span class="badge rounded-pill bg-success">Hoàn thành</span>';
        }

        return $output;
    }

    public static function getStatusNameBadgeStaticFrontend($status = null)
    {
        $output = '';
        if ($status === null) {
            return '<span class="bg-warning p-1" style="color: #fff; border-radius: 3px;"> Chờ duyệt</span>';
        }
        if ((int)$status === 0) {
            return '<span class="bg-danger p-1" style="color: #fff; border-radius: 3px;"> Từ chối</span>';
        }
        if ((int)$status === 1) {
            return '<span class="bg-success p-1" style="color: #fff; border-radius: 3px;"> Hoàn thành</span>';
        }

        return $output;
    }

    public static function statusOptions()
    {
        return [
            null => 'Chờ duyệt',
            0 => 'Từ chối',
            1 => 'Duyệt',
        ];
    }

    public static function disbursementStatusBadgeStatic($status = null)
    {
        $output = '';
        $status = (int)$status;
        if ($status === 1) {
            return '<span class="badge rounded-pill bg-warning text-dark">Xin giải ngân</span>';
        }
        if ($status === 2) {
            return '<span class="badge rounded-pill bg-primary">Chờ giải ngân</span>';
        }
        if ($status === 3) {
            return '<span class="badge rounded-pill bg-info text-dark">Xác nhận giải ngân</span>';
        }

        if ($status === 4) {
            return '<span class="badge rounded-pill bg-success text-dark">Hoàn thành</span>';
        }
        if ($status === 5) {
            return '<span class="badge rounded-pill bg-danger text-dark">Từ chối</span>';
        }
        return $output;
    }

    public static function disbursementStatusBadgeStaticFrontend($status = null)
    {
        $output = '';
        $status = (int)$status;
        if ($status === 1) {
            return '<span class="bg-warning p-1 ms-2 text-dark" style="border-radius: 3px;">Xin giải ngân</span>';
        }
        if ($status === 2) {
            return '<span class="bg-secondary p-1 ms-2" style="color: #fff; border-radius: 3px;">Chờ giải
            ngân</span>';
        }
        if ($status === 3) {
            return '<span class="bg-info p-1 ms-2 text-dark" style="border-radius: 3px;">Xác nhận giải ngân</span>';
        }

        if ($status === 4) {
            return '<span class="bg-success p-1 ms-2 text-dark" style="border-radius: 3px;">Hoàn thành</span>';
        }
        if ($status === 5) {
            return '<span class="bg-danger p-1 ms-2 text-dark" style="border-radius: 3px;">Từ chối</span>';
        }
        return $output;
    }

    public function loanTerm()
    {
        return $this->belongsTo(LoanTerm::class, 'loan_term_id', 'id');
    }
}
