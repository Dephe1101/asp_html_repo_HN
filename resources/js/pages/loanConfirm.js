function appendItemRate({ item = {} }) {
    return `<tr>
        <td>Kỳ thứ ${item.id}</td>
        <td>${item.payment} vnđ</td>
        <td>${item.date}</td>
    </tr>`;
}

$("#loan_amount").on("input", function() {
    console.log("Input event triggered");
    let loanAmount = document.getElementById("loan_amount");
    let numericValue = loanAmount.value.replace(/[^0-9]/g, '');
    let limitedValue = Math.min(Number(numericValue), 500000000);
    let formattedValue = limitedValue.toLocaleString();
    loanAmount.value = formattedValue;
    console.log('Number(numericValue): ', loanAmount.value);
    // Xử lý logic với giá trị nhập vào
    let select = document.getElementById("loan_term_id");
    let loanTerm = select.value;
    console.log('loanTerm: ', loanTerm);
    if (loanAmount.value && loanTerm) {
        let number = parseInt(numericValue);
        const params = { 'loan_term_id': loanTerm, 'loan_amount': number};
        $.ajax({
            type: "GET",
            url: `${import.meta.env.VITE_APP_URL}/api/on-change-term`,
            data: params,
            beforeSend: function () {
            },
            success: function (res) {
                if (!res.status) {
                    console.log(res);
                    return;
                }
                if (res.data) {
                    const loanTerm = res.data;
                    document.getElementById('loan_amount_info').textContent = loanAmount.value + ' VNĐ';
                    document.getElementById('loan_term_info').textContent = loanTerm.loan_term + ' Tháng';
                    document.getElementById('amount_info').textContent = loanTerm.amount_first_month + ' VNĐ';
                    document.getElementById('interest_info').textContent = loanTerm.rate + ' %';
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    }
});

$("#loan_term_id").change(function() {
    console.log("Change event triggered");
    let select = document.getElementById("loan_term_id");
    let selectedValue = select.value;
    console.log('Select changed:', selectedValue);
    // Xử lý logic với giá trị được chọn
    let input = document.getElementById("loan_amount");
    let loanAmount = input.value;
    console.log('loanAmount: ', loanAmount);
    if (selectedValue && loanAmount) {
        let numberWithNoDots = loanAmount.replace(/\./g, '');
        let number = parseInt(numberWithNoDots);
        const params = { 'loan_term_id': selectedValue, 'loan_amount': number};
        $.ajax({
            type: "GET",
            url: `${import.meta.env.VITE_APP_URL}/api/on-change-term`,
            data: params,
            beforeSend: function () {
            },
            success: function (res) {
                if (!res.status) {
                    console.log(res);
                    return;
                }
                if (res.data) {
                    const loanTerm = res.data;
                    document.getElementById('loan_amount_info').textContent = loanAmount + ' VNĐ';
                    document.getElementById('loan_term_info').textContent = loanTerm.loan_term + ' Tháng';
                    document.getElementById('amount_info').textContent = loanTerm.amount_first_month + ' VNĐ';
                    document.getElementById('interest_info').textContent = loanTerm.rate + ' %';
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    }
});

$('#rate_detail').on('click', function (e) {
    let input = document.getElementById("loan_amount");
    let loanAmount = input.value;
    if (!loanAmount) {
        alert('Số tiền vay không được để trống!');
        e.preventDefault();
        return;
    }
    let select = document.getElementById("loan_term_id");
    let selectedValue = select.value;
    if (!selectedValue) {
        alert('Thời hạn vay không được để trống!');
        e.preventDefault();
        return;
    }
    console.log('loanAmount: ', loanAmount);
    if (selectedValue && loanAmount) {
        let numberWithNoDots = loanAmount.replace(/\./g, '');
        let number = parseInt(numberWithNoDots);
        const params = { 'loan_term_id': selectedValue, 'loan_amount': number};
        const itemRate = $("#item_rate");
        itemRate.empty();
        $.ajax({
            type: "GET",
            url: `${import.meta.env.VITE_APP_URL}/api/get-rate`,
            data: params,
            beforeSend: function () {
            },
            success: function (res) {
                if (!res.status) {
                    console.log(res);
                    return;
                }
                if (res.data) {
                    console.log('res.data: ', res.data);
                    res.data.forEach(item => {
                        let html = appendItemRate({
                            item,
                        })
                        itemRate.append(html);
                    });
                    $("#model_rate").show();
                }
            },
            error: function (err) {
                console.log(err);
            }
        });
    }
    e.preventDefault();
});

const modal = $("#model_rate");
$("#model_rate .btn-close").click(function() {
    modal.hide();
});


$(window).click(function(event) {
    if (event.target == modal[0]) {
        modal.hide();
    }
});

$('.btn_loan_confirm').click(function (e) {
    // let input = document.getElementById("loan_amount");
    // let loanAmount = input.value;
    // if (!loanAmount) {
    //     alert('Số tiền vay không được để trống!')
    //     return;
    // }
    // let select = document.getElementById("loan_term_id");
    // let selectedValue = select.value;
    // if (!selectedValue) {
    //     alert('Thời hạn vay không được để trống!')
    //     return;
    // }
    // console.log('loanAmount: ', loanAmount);
    // if (selectedValue && loanAmount) {
        const form = document.getElementById("form_loan_confirm");
        form.submit();
    // }
    e.preventDefault();
});
