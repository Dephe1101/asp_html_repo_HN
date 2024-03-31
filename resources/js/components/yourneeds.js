const sliderWrappers = document.querySelectorAll('.slider-wrapper');
const ipMoney = document.querySelector('.ip-Money');
const ipMonth = document.querySelector('.ip-Month');
const opMoney = document.querySelector('.numberMoney');
const opMonth = document.querySelector('.numberMonth');
if (sliderWrappers.length > 0) {
    sliderWrappers.forEach(sliderWrapper => {
        const sliderInput = sliderWrapper.querySelector('.slider-input');
        const minValue = +sliderInput.min || 0;
        const maxValue = +sliderInput.max || 100;
        const updateSlider = () => {
        sliderWrapper.style.setProperty('--slider-value', 100 * +sliderInput.value / (maxValue - minValue));
        }
        sliderInput.addEventListener('input', () => {
        updateSlider();
        });
        updateSlider();
        
    });
    opMoney.innerHTML = ipMoney.value;
    ipMoney.addEventListener('input', function () {
        opMoney.innerHTML = ipMoney.value;
    }, false);
    opMonth.innerHTML = ipMonth.value;
    ipMonth.addEventListener('input', function () {
        opMonth.innerHTML = ipMonth.value;
    }, false);
}
