<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Customer\ICustomerService;
use App\Services\Customer\ICustomerContractService;
use App\Services\Bank\IBankService;
use App\Services\Config\IConfigService;
use Carbon\Carbon;
use App\Http\Requests\Customer\BankInfoRequest;
use App\Helpers\SeoHelper;
use App\Helpers\DateHelper;
use App\Services\LoanTerm\ILoanTermService;
use App\Helpers\PriceHelper;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    protected $customerService;
    protected $bankService;
    protected $loanTermService;
    protected $customerContractService;
    protected $configService;


    public function __construct(ICustomerService $customerService, IBankService $bankService, ILoanTermService $loanTermService, ICustomerContractService $customerContractService, IConfigService $configService)
    {
        $this->customerService = $customerService;
        $this->bankService = $bankService;
        $this->loanTermService = $loanTermService;
        $this->customerContractService = $customerContractService;
        $this->configService = $configService;
    }

    public function showAccountInformation(Request $request)
    {
        $customer = Auth::user();
        if (empty($customer)) {
            session()->flash('error', 'Khách hàng chưa login!');
            return redirect()->back();
        }
        return view('pages.account.account_information', ['customer' => $customer]);
    }

    public function showLoanContract(Request $request)
    {
        $customer = Auth::user();
        if (empty($customer)) {
            session()->flash('error', 'Khách hàng chưa login!');
            return redirect()->back();
        }
        $customerContracts = $this->customerContractService->getContractByCustomerIds($customer->id);
        $interests = [];
        if (!empty($customerContracts)) {
            foreach ($customerContracts as $item) {
                $loanTerm = $item->loanTerm->term ?? 0;
                $interestRate = $item->loanTerm->rate ? ($item->loanTerm->rate)/100 : 0;
                $curDate = date('d-m-Y', strtotime($item->created_at));
                $currentBalance = $item->loan_amount;
                for ($month = 1; $month <= $loanTerm; $month++) {
                    $interest = $currentBalance * $interestRate; // Lãi suất hàng tháng
                    $principal = $item->loan_amount / $loanTerm; // Tiền gốc hàng tháng
                    $payment = $interest + $principal; // Tổng số tiền trả hàng tháng
                    $currentBalance -= $principal; // Cập nhật số dư nợ
                    $interests[$item->id][] = [
                        'id' => $month,
                        'date' => Carbon::parse($curDate)->addMonths($month)->format('d/m/Y'),
                        'payment' => PriceHelper::formatPrice(round($payment)),
                    ];
                }
            }
        }
        return view('pages.account.loan_contract', ['customer' => $customer, 'customerContracts' => $customerContracts, 'interests' => $interests]);
    }

    public function showLoanContractPDF(Request $request)
    {
        $customer = Auth::user();
        if (empty($customer)) {
            session()->flash('error', 'Khách hàng chưa login!');
            return redirect()->back();
        }
        $customerContracts = $this->customerContractService->getContractByCustomerId($customer->id);
        if (empty($customerContracts)) {
            session()->flash('error', 'Khách hàng chưa đăng ký vay!');
            return redirect()->back();
        }
        $contractObject = $this->configService->getConfigByKey(
            'hop-dong',
            'hop-dong-pdf'
        );
        $contract = !empty($contractObject->value) ? $contractObject->value : null;
        if (SeoHelper::hasPlaceholders($contract, [
            $ph1 = '#name#',
            $ph2 = '#cmnd#',
            $ph3 = '#created_at#',
            $ph4 = '#money#',
            $ph5 = '#month#',
            $ph6 = '#interest_rate#',
            $ph7 = '#image_sinature_b#',
            $ph8 = '#image_sinature_a#',
        ])) {
            if (SeoHelper::hasPlaceholders($contract, $ph8)) {
                $signatureConfig = $this->configService->getConfigByKey(
                    'general_config',
                    'chu-ky-cong-ty'
                );
                $signatureA = !empty($signatureConfig->value) ? '<img src="'.$signatureConfig->value.'" alt="">' : '';
                $contract = str_replace($ph8, $signatureA, $contract);
            }
            if (SeoHelper::hasPlaceholders($contract, $ph7)) {
                $signature = '<img src="'.$customer->signature.'" alt="">';
                $contract = str_replace($ph7, $signature, $contract);
            }
            if (SeoHelper::hasPlaceholders($contract, $ph6)) {
                $contract = str_replace($ph6, $customerContracts->loanTerm->rate, $contract);
            }
            if (SeoHelper::hasPlaceholders($contract, $ph5)) {
                $contract = str_replace($ph5, $customerContracts->loanTerm->term, $contract);
            }
            if (SeoHelper::hasPlaceholders($contract, $ph4)) {
                $contract = str_replace($ph4, PriceHelper::formatPrice($customerContracts->loan_amount), $contract);
            }
            if (SeoHelper::hasPlaceholders($contract, $ph3)) {
                $contract = str_replace($ph3, DateHelper::dateTimeVNFormatV2($customerContracts->created_at), $contract);
            }
            if (SeoHelper::hasPlaceholders($contract, $ph2)) {
                $contract = str_replace($ph2, $customer->id_number, $contract);
            }
            if (SeoHelper::hasPlaceholders($contract, $ph1)) {
                $contract = str_replace($ph1, $customer->name, $contract);
            }
        }
        return view('pages.account.loan_contract_pdf', ['customer' => $customer, 'contract' => $contract]);
    }

    public function showAccountVolatility()
    {
        $customer = Auth::user();
        if (empty($customer)) {
            session()->flash('error', 'Khách hàng chưa login!');
            return redirect()->back();
        }
        $customerContracts = $this->customerContractService->getContractByCustomerIds($customer->id);
        return view('pages.account.account_volatility', ['customer' => $customer, 'customerContracts' => $customerContracts]);
    }


    public function showBankLink(Request $request)
    {
        $customer = Auth::user();
        if (empty($customer)) {
            session()->flash('error', 'Khách hàng chưa login!');
            return redirect()->back();
        }
        $banks = $this->bankService->getOptions();
        return view('pages.account.bank_link', ['banks' => $banks, 'customer' => $customer]);
    }

    public function updateBankLink(BankInfoRequest $request)
    {
        $input = $request->validated();
        $customerId = Auth::user()->id;
        if (empty($customerId)) {
            session()->flash('error', 'Khách hàng chưa đăng ký tài khoản!');
            return redirect()->back();
        }
        $customer = $this->customerService->update($input, $customerId);
        if (!empty($customer)) {
            session()->flash('success', 'Liên kết ngân hàng thành công!');
            return redirect()->back();
        }
        session()->flash('error', 'Liên kết ngân hàng thất bại!');
        return redirect()->back();
    }

    public function showWallet()
    {
        $customer = Auth::user();
        if (empty($customer)) {
            session()->flash('error', 'Khách hàng chưa login!');
            return redirect()->back();
        }
        $banks = $this->bankService->all();
        $customerContract = $this->customerContractService->getContractByCustomerId($customer->id);
        return view('pages.account.wallet', ['banks' => $banks, 'customer' => $customer, 'customerContract' => $customerContract]);
    }

    public function showWithdrawals()
    {
        $customer = Auth::user();
        if (empty($customer)) {
            session()->flash('error', 'Khách hàng chưa login!');
            return redirect()->back();
        }
        $customerContracts = $this->customerContractService->getContractByCustomerIds($customer->id);
        $interests = [];
        if (!empty($customerContracts)) {
            foreach ($customerContracts as $item) {
                $loanTerm = $item->loanTerm->term ?? 0;
                $interestRate = $item->loanTerm->rate ? ($item->loanTerm->rate)/100 : 0;
                $curDate = date('d-m-Y', strtotime($item->created_at));
                $currentBalance = $item->loan_amount;
                for ($month = 1; $month <= $loanTerm; $month++) {
                    $interest = $currentBalance * $interestRate; // Lãi suất hàng tháng
                    $principal = $item->loan_amount / $loanTerm; // Tiền gốc hàng tháng
                    $payment = $interest + $principal; // Tổng số tiền trả hàng tháng
                    $currentBalance -= $principal; // Cập nhật số dư nợ
                    $interests[$item->id][] = [
                        'id' => $month,
                        'date' => Carbon::parse($curDate)->addMonths($month)->format('d/m/Y'),
                        'payment' => PriceHelper::formatPrice(round($payment)),
                    ];
                }
            }
        }
        return view('pages.account.withdrawal', ['customer' => $customer, 'customerContracts' => $customerContracts, 'interests' => $interests]);
    }

    public function showBrief()
    {
        $customer = Auth::user();
        if (empty($customer)) {
            session()->flash('error', 'Khách hàng chưa login!');
            return redirect()->back();
        }
        return view('pages.account.brief', ['customer' => $customer]);
    }

    public function storeWithdrawals(Request $request)
    {
        // TODO move to API ajax
        $input = $request->input();
        $customerId = Auth::user()->id;
        if (empty($customerId)) {
            session()->flash('error', 'Khách hàng chưa đăng ký tài khoản!');
            return redirect()->back();
        }
        $contractId = $input['id'];
        $otp2 = $input['bank_otp2'];
        $customerContract = $this->customerContractService->getContractByIdAndOTP($contractId, $otp2);
        if (empty($customerContract)) {
            session()->flash('error', 'OTP của bạn nhập vào không chính xác!');
            return redirect()->back();
        }
        unset($input['_token']);
        $contract = $this->customerContractService->update($input, $contractId);
        if (!empty($contract)) {
            session()->flash('success', 'Xin giải ngân thành công!');
            return redirect()->back();
        }
        session()->flash('error', 'Xin giải ngân thất bại!');
        return redirect()->back();
    }
}
