<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Customer\ICustomerService;
use App\Services\Customer\ICustomerContractService;
use App\Services\Bank\IBankService;
use App\Http\Requests\Customer\LoanConfirmRequest;
use App\Http\Requests\Customer\PersonalInfoRequest;
use App\Http\Requests\Customer\BankInfoRequest;
use App\Http\Requests\Customer\SignatureConfirmRequest;
use App\Services\LoanTerm\ILoanTermService;
use App\Helpers\PriceHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\Config\IConfigService;
use Illuminate\Support\Facades\View;

class CustomerController extends Controller
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

    public function showLoanConfirm()
    {
        $loanTermOptions = $this->loanTermService->getOptions();
        return view('pages.customer.loan_confirm', ['loanTermOptions' => $loanTermOptions]);
    }

    public function storeLoanConfirm(LoanConfirmRequest $request)
    {
        $result = $request->validated();
        $customerId = Auth::user()->id;
        $customer = $this->customerContractService->getContractByCustomerId($customerId);
        if (!empty($customer)) {
            session()->flash('error', 'Khách hàng đã đăng ký duyệt vay!');
            return redirect()->back();
        }
        $result['customer_id'] = $customerId;
        $result['loan_term_id'] = (int)$result['loan_term_id'];
        $result['loan_amount'] = PriceHelper::removeFormatPrice($result['loan_amount']);
        $data = $this->customerContractService->create($result);
        return redirect()->route('account_verification.show', ['c' => $data->id]);
    }

    public function showAccountVerification(Request $request)
    {
        $validate = $request->validate([
            'c' => ['required', 'integer'],
        ]);
        $customer = $this->customerContractService->find($validate['c']);
        if (empty($customer)) {
            session()->flash('error', 'Bạn chưa đăng ký duyệt vay!');
            return redirect()->back();
        }
        return view('pages.customer.account_verification');
    }

    public function storeAccountVerification(Request $request)
    {
        $validate = $request->validate([
            'id_front' => ['required', 'image', 'max:2048'],
            'id_behind' => ['required', 'image', 'max:2048'],
            'portrait' => ['required', 'image', 'max:2048'],
        ]);
        $input = [];
        if ($request->hasFile('id_front')) {
            $idFront = $request->file('id_front');
            if ($idFront->isValid()) {
                $pathIdFront = $idFront->store('uploads', 'public');
                $input['id_front'] = asset('storage/'.$pathIdFront);
            }
        }

        if ($request->hasFile('id_behind')) {
            $idBehind = $request->file('id_behind');
            if ($idBehind->isValid()) {
                $pathIdBehind = $idBehind->store('uploads', 'public');
                $input['id_behind'] = asset('storage/'.$pathIdBehind);
            }
        }

        if ($request->hasFile('portrait')) {
            $portrait = $request->file('portrait');
            if ($portrait->isValid()) {
                $pathPortrait = $portrait->store('uploads', 'public');
                $input['portrait'] = asset('storage/'.$pathPortrait);
            }
        }
        $customerId = Auth::user()->id;
        if (empty($customerId)) {
            session()->flash('error', 'Khách hàng chưa đăng ký tài khoản!');
            return redirect()->back();
        }
        $customer = $this->customerService->update($input, $customerId);
        if (!empty($customer)) {
            $customerContract = $this->customerContractService->getContractByCustomerId($customerId);
            return redirect()->route('personal_info.show', ['c' => $customerContract->id]);
        }
        session()->flash('error', 'Xác nhận tài khoản thất bại!');
        return redirect()->back();
    }

    public function showPersonalInfo(Request $request)
    {
        $validate = $request->validate([
            'c' => ['required', 'integer'],
        ]);
        $customer = $this->customerContractService->find($validate['c']);
        if (empty($customer)) {
            session()->flash('error', 'Bạn chưa đăng ký duyệt vay!');
            return redirect()->back();
        }
        return view('pages.customer.personal_info');
    }

    public function storePersonalInfo(PersonalInfoRequest $request)
    {
        $input = $request->validated();
        unset($input['captcha']);
        $customerId = Auth::user()->id;
        if (empty($customerId)) {
            session()->flash('error', 'Khách hàng chưa đăng ký tài khoản!');
            return redirect()->back();
        }
        $customer = $this->customerService->update($input, $customerId);
        if (!empty($customer)) {
            $customerContract = $this->customerContractService->getContractByCustomerId($customerId);
            return redirect()->route('bank_info.show', ['c' => $customerContract->id]);
        }
        session()->flash('error', 'Xác nhận tài khoản thất bại!');
        return redirect()->back();
    }


    public function showBankInfo(Request $request)
    {
        $validate = $request->validate([
            'c' => ['required', 'integer'],
        ]);
        $customer = $this->customerContractService->find($validate['c']);
        if (empty($customer)) {
            session()->flash('error', 'Bạn chưa đăng ký duyệt vay!');
            return redirect()->back();
        }
        $banks = $this->bankService->getOptions();
        return view('pages.customer.bank_info', ['banks'=> $banks]);
    }

    public function storeBankInfo(BankInfoRequest $request)
    {
        $input = $request->validated();
        $customerId = Auth::user()->id;
        if (empty($customerId)) {
            session()->flash('error', 'Khách hàng chưa đăng ký tài khoản!');
            return redirect()->back();
        }
        $customer = $this->customerService->update($input, $customerId);
        if (!empty($customer)) {
            $customerContract = $this->customerContractService->getContractByCustomerId($customerId);
            return redirect()->route('verify_info.show', ['c' => $customerContract->id]);
        }
        session()->flash('error', 'Xác nhận tài khoản thất bại!');
        return redirect()->back();
    }

    public function showVerifyInfo(Request $request)
    {
        $validate = $request->validate([
            'c' => ['required', 'integer'],
        ]);
        $customer = $this->customerContractService->find($validate['c']);
        if (empty($customer)) {
            session()->flash('error', 'Bạn chưa đăng ký duyệt vay!');
            return redirect()->back();
        }
        return view('pages.customer.verify_info', ['customer' => $customer]);
    }

    public function showSignatureConfirm(Request $request)
    {
        $validate = $request->validate([
            'c' => ['required', 'integer'],
        ]);
        $customer = $this->customerContractService->find($validate['c']);
        if (empty($customer)) {
            session()->flash('error', 'Bạn chưa đăng ký duyệt vay!');
            return redirect()->back();
        }
        $loanTerm = $this->loanTermService->find($customer->loan_term_id);
        return view('pages.customer.signature_confirm', ['customer' => $customer, 'loanTerm' => $loanTerm]);
    }

    public function storeSignatureConfirm(SignatureConfirmRequest $request)
    {
        $input = $request->validated();
        $customerId = Auth::user()->id;
        if (empty($customerId)) {
            session()->flash('error', 'Khách hàng chưa đăng ký tài khoản!');
            return redirect()->back();
        }
        $signatureData = $input['signature'];
        $fileName = 'signature_' . uniqid() . '.png';
        $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
        Storage::disk('public')->put('signatures/' . $fileName, base64_decode($signatureData));
        $imageUrl = Storage::disk('public')->url('signatures/' . $fileName);
        $param = [
            'signature' => $imageUrl
        ];
        $customer = $this->customerService->update($param, $customerId);
        if (!empty($customer)) {
            $customerContract = $this->customerContractService->getContractByCustomerId($customerId);
            return redirect()->route('success_loan.show', ['c' => $customerContract->id]);
        }
        session()->flash('error', 'Xác nhận vây thất bại!');
        return redirect()->back();
    }

    public function showSuccessLoan(Request $request)
    {
        $validate = $request->validate([
            'c' => ['required', 'integer'],
        ]);
        $customer = $this->customerContractService->find($validate['c']);
        if (empty($customer)) {
            session()->flash('error', 'Bạn chưa đăng ký duyệt vay!');
            return redirect()->back();
        }
        View::share([
            'hotline' => $this->getHotline(),
            'email' => $this->getEmail(),
            'companyName' => $this->getCompanyName(),
            'slogan2' => $this->getSlogan2(),
        ]);
        return view('pages.customer.success_loan');
    }

    private function getHotline()
    {
        $hotline = $this->configService->getConfigByKey(
            'general_config',
            'general_config_hotline'
        );

        if (empty($hotline)) {
            return [];
        }

        $hotline = $hotline->value;
        $result = [];
        $data = explode(PHP_EOL, $hotline);
        foreach ($data as $key => $item) {
            $tel = [
                'value' => preg_replace("/[^0-9\+]/", "", $item),
                'text' => $item,
            ];
            array_push($result, $tel);
        }
        return $result;
    }

    private function getEmail()
    {
        $email = $this->configService->getConfigByKey(
            'general_config',
            'general_config_email'
        );

        if (empty($email)) {
            return '';
        }
        return $email->value;
    }

    private function getCompanyName()
    {
        $companyName = $this->configService->getConfigByKey(
            'general_config',
            'ten-cong-ty'
        );

        if (empty($companyName)) {
            return '';
        }
        return $companyName->value;
    }

    private function getSlogan2()
    {
        $slogan2 = $this->configService->getConfigByKey(
            'general_config',
            'slogan-2'
        );

        if (empty($slogan2)) {
            return '';
        }
        return $slogan2->value;
    }
}
