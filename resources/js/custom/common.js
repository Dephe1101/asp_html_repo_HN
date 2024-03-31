/**
 * Generate class name with prefix and suffix
 * @param {String} $class - Class name want to generate
 * @returns
 */
function className($class) {
    let $classArr = $class.split(' ');

    let $newClassArr = $classArr.map(value => {
        if (/^[.]/.test(value)) {
            value = value.substring(1);
            return '.' + cssPrefix + value + cssSuffix;
        } else {
            return cssPrefix + value + cssSuffix;
        }
    })

    return $newClassArr.toString().replace(/,/g, ' ');
}

/**
 * Format currency
 * @param {Number} amount - Amount want to format
 * @returns
 */
function formatToCurrency(amount) {
    let result = new Intl.NumberFormat().format(amount)
    result = result.replace(/,/g, '.');

    return result;
};

/**
 *
 * @param {*} e - event
 * @param {String} selector - query selector
 * @returns boolean
 */
function clickOutside(e, selector) {
    if (!$(e.target).is(selector) && $(e.target).closest(selector).length == 0) {
        return true;
    }

    return false;
}

/**
 * Detect if device is mobile
 * @returns boolean
 */
function isMobile() {
    let mobiles = [
        /Android/i, /BlackBerry/i, /iPhone|iPad|iPod/i, /Opera Mini/i, /IEMobile/i
    ];

    return mobiles.some(item => {
        return navigator.userAgent.match(item);
    })
}

export {
    className,
    formatToCurrency,
    clickOutside,
    isMobile
}
