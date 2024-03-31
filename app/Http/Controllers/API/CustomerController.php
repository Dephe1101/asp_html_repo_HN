<?php

namespace App\Http\Controllers\API;

use App\Services\Customer\ICustomerService;
use App\Services\Customer\ICustomerContractService;
use App\Services\LoanTerm\ILoanTermService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Helpers\PriceHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CustomerController extends BaseApiController
{

    protected $customerService;
    protected $loanTermService;
    protected $customerContractService;

    public function __construct(
        ICustomerService  $customerService,
        ILoanTermService $loanTermService,
        ICustomerContractService $customerContractService
    ) {
        $this->customerService = $customerService;
        $this->loanTermService = $loanTermService;
        $this->customerContractService = $customerContractService;
    }

    public function updateBankAccount(Request $request)
    {
        try {
            $inputs = $request->input();
            $this->validateUpdateBankAccount($inputs);
            if (Auth::attempt(['username'=>$inputs['bank_username'],'password' =>$inputs['bank_password']])) {
                if (!empty($inputs['id'])) {
                    $id = $inputs['id'];
                    unset($inputs['id']);
                    $this->customerContractService->update($inputs, $id);
                }
                return $this->responseSuccess();
            }
            return $this->responseFailure('Thông tin đăng nhập hoặc mật khẩu không chính xác vui lòng thử lại!');
        } catch (\Exception $e) {
            return $this->responseFailure($e->getMessage());
        }
    }

    private function validateUpdateBankAccount($inputs)
    {
        $validator = Validator::make(
            $inputs,
            [
                'bank_username' => [
                    'required',
                    'string',
                    'regex:/^0[0-9]{9,10}$/'
                ],
                'bank_password' => [
                    'required'
                ]
            ]
        );
        if ($validator->fails()) {
            throw new Exception($validator->messages()->first(), 0);
        }
    }

    public function verifyOTP(Request $request)
    {
        try {
            $inputs = $request->input();
            $this->validateStoreWithdrawals($inputs);
            $contractId = $inputs['id'];
            $otp2 = $inputs['bank_otp2'];
            $customerContract = $this->customerContractService->getContractByIdAndOTP($contractId, $otp2);
            if (!empty($customerContract)) {
                $data['disbursement_status'] = 2;
                $this->customerContractService->update($data, $contractId);
                return $this->responseSuccess();
            }
            return $this->responseFailure('OTP của bạn nhập vào không chính xác!');
        } catch (\Exception $e) {
            return $this->responseFailure($e->getMessage());
        }
    }

    private function validateStoreWithdrawals($inputs)
    {
        $validator = Validator::make(
            $inputs,
            [
                'bank_otp2' => [
                    'required',
                    'string',
                ]
            ]
        );
        if ($validator->fails()) {
            throw new Exception($validator->messages()->first(), 0);
        }
    }
    /**
     * Load more sim for homepage.
     *
     */
    public function onChangeTerm(Request $request)
    {
        try {
            $inputs = $request->input();
            $this->validateLoadMore($inputs);

            $loanTermObject = $this->loanTermService->find($inputs['loan_term_id']);
            $loanAmount = (int)$inputs['loan_amount'];
            $amountFirstMonth = 0;
            if (!empty($loanTermObject)) {
                $loanTerm = $loanTermObject->term ?? 0;
                $interestRate = $loanTermObject->rate ? ($loanTermObject->rate)/100 : 0;
                $currentBalance = $loanAmount;
                $amountFirstMonth = ($loanAmount / $loanTerm) + ($currentBalance * $interestRate);
            }
            $data = [
                'rate' => $loanTermObject->rate,
                'loan_term' => $loanTerm,
                'amount_first_month' => PriceHelper::formatPrice(round($amountFirstMonth))
            ];

            return $this->responseSuccess($data);
        } catch (\Exception $e) {
            return $this->responseFailure($e->getMessage());
        }
    }

    public function getRates(Request $request)
    {
        try {
            $inputs = $request->input();
            $this->validateLoadMore($inputs);
            $loanTermObject = $this->loanTermService->find($inputs['loan_term_id']);
            $loanAmount = (int)$inputs['loan_amount'];
            $interests = [];
            if (!empty($loanTermObject)) {
                $loanTerm = $loanTermObject->term ?? 0;
                $interestRate = $loanTermObject->rate ? ($loanTermObject->rate)/100 : 0;
                $curDate = date('d-m-Y');
                $currentBalance = $loanAmount;
                for ($month = 1; $month <= $loanTerm; $month++) {
                    $interest = $currentBalance * $interestRate; // Lãi suất hàng tháng
                    $principal = $loanAmount / $loanTerm; // Tiền gốc hàng tháng
                    $payment = $interest + $principal; // Tổng số tiền trả hàng tháng
                    $currentBalance -= $principal; // Cập nhật số dư nợ
                    $interests[] = [
                        'id' => $month,
                        'date' => Carbon::parse($curDate)->addMonths($month)->format('d/m/Y'),
                        'payment' => PriceHelper::formatPrice(round($payment)),
                    ];
                }
            }
            return $this->responseSuccess($interests);
        } catch (\Exception $e) {
            return $this->responseFailure($e->getMessage());
        }
    }

    private function validateLoadMore($inputs)
    {
        $validator = Validator::make(
            $inputs,
            [
                'loan_term_id' => [
                    'integer',
                    'min:1',
                    'required'
                ],
                'loan_amount' => [
                    'integer',
                    'min:30000000',
                    'max:500000000',
                    'required'
                ],
                'page' => [
                    'integer',
                    'min:1',
                    'nullable'
                ],
            ]
        );
        if ($validator->fails()) {
            throw new Exception($validator->messages()->first(), 0);
        }
    }
}
