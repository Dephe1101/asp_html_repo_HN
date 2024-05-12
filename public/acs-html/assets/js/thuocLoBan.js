
//Mỗi đoạn thước dài 1000mm
var rulerLength = 1000; //Số đo 1 đoạn thước (mm)
var trimStart = 0; //Số đo đầu của thước (mm)
var trimEnd = 1000; //Số đo cuối của thước (mm)ư
let widthMain = 880
window.addEventListener("resize", resizeWindow)

function resizeWindow() {
    return widthMain = $("#main").width()
}

var myScroll;

function pullRightAction() {
    if (trimStart > 0) {
        $('#scroller').width(function (i, width) {
            return width + 10000;
        });
        trimStart -= rulerLength;
        var qStr = '?trimStart=' + trimStart + '&rulerLength=' + rulerLength;
        var newLi = $('<li>').append($('<img/>', {
            src: 'https://minhbao.vn/wp-content/plugins/devvn-thuoc-lo-ban/includes/thuoc522.php' +
                qStr
        }))
            .append($('<img />', {
                src: 'https://minhbao.vn/wp-content/plugins/devvn-thuoc-lo-ban/includes/thuoc429.php' +
                    qStr
            }))
            .append($('<img />', {
                src: "https://minhbao.vn/wp-content/plugins/devvn-thuoc-lo-ban/includes/thuoc388.php" +
                    qStr
            }));
        $('#thelist').prepend(newLi);
        myScroll.refresh();
    }
}

function pullLeftAction() {
    if (trimEnd < 100000) {
        $('#scroller').width(function (i, width) {
            return width + 10000;
        });
        var qStr = '?trimStart=' + trimEnd + '&rulerLength=' + rulerLength;
        var newLi = $('<li>').append($('<img/>', {
            src: 'https://minhbao.vn/wp-content/plugins/devvn-thuoc-lo-ban/includes/thuoc522.php' +
                qStr
        }))
            .append($('<img />', {
                src: 'https://minhbao.vn/wp-content/plugins/devvn-thuoc-lo-ban/includes/thuoc429.php' +
                    qStr
            }))
            .append($('<img />', {
                src: "https://minhbao.vn/wp-content/plugins/devvn-thuoc-lo-ban/includes/thuoc388.php" +
                    qStr
            }));
        trimEnd += rulerLength;
        $('#thelist').append(newLi);
        myScroll.refresh();
    }
}

function loaded() {
    Math.nativeRound = Math.round;
    Math.round = function (i, iDecimals) {
        if (!iDecimals)
            return Math.nativeRound(i);
        else
            return Math.nativeRound(i * Math.pow(10, Math.abs(iDecimals))) /
                Math.pow(10, Math.abs(
                    iDecimals));

    };

    myScroll = new iScroll('wrapper', {
        useTransition: true,
        leftOffset: $('#pullRight').outerWidth(true),
        onRefresh: function () {
            if ($('#pullRight').hasClass('loading')) {
                $('#pullRight').removeClass('loading');
                $('#pullRight .pullRightLabel').html(
                    'Kéo qua phải tải thêm...');
            } else if ($('#pullLeft').hasClass('loading')) {
                $('#pullLeft').removeClass('loading');
                $('#pullLeft .pullLeftLabel').html(
                    'Kéo qua trái tải thêm...');
            }
            $('#inputValue').val(Math.round((-this.x + widthMain / 2) / 100, 2))
        },
        onScrollMove: function () {
            // $('#sodoLoban').html(Math.round((-this.x + 44 * 10) / 100,
            //     2) + ' cm').css({
            //     'left': $('.container-thuoc-lo-ban').outerWidth(
            //             true) / 2 -
            //         $('#sodoLoban')
            //         .outerWidth(true) / 2
            // });

            $('#inputValue').val(Math.round((-this.x + widthMain / 2) / 100, 2))
        },
        onScrollEnd: function () {
            myScroll.refresh();
            console.log(this.x);
            console.log(($('#scroller').width() - 1000));
            if (this.x > 5 && !$('#pullRight').hasClass('flip')) {
                $('#pullRight').addClass('flip');
                $('#pullRight .pullRightLabel').html(
                    'Thả ra để làm mới...');
            } else if (this.x < -($('#scroller').width() - 2000) && !$(
                '#pullRight')
                .hasClass('flip')) {
                $('#pullLeft').addClass('flip');
                $('#pullLeft .pullLeftLabel').html(
                    'Thả ra để làm mới...');
            }
            //$('#abc').html('this.x='+this.x+' : out='+($('#scroller').width()-1000));
            if ($('#pullRight').hasClass('flip')) {
                $('#pullRight').removeClass('flip');
                $('#pullRight').addClass('loading');
                $('#pullRight .pullRightLabel').html('Đang tải...');
                pullRightAction
                    (); // Execute custom function (ajax call?)
            } else if ($('#pullLeft').hasClass('flip')) {
                $('#pullLeft').removeClass('flip');
                $('#pullLeft').addClass('loading');
                $('#pullLeft .pullLeftLabel').html('Đang tải...');
                pullLeftAction
                    (); // Execute custom function (ajax call?)
            }
            $('#inputValue').val(Math.round((-this.x + widthMain / 2) / 100, 2))
        },
    });


    setTimeout(function () {
        document.getElementById('wrapper').style.left = '0';
    }, 800);
}
if (document.addEventListener) {
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(loaded, 200);
    }, false);
} else {
    document.attachEvent('onreadystatechange', function () {
        setTimeout(loaded, 200);
    });
}

function handleTranslate(result) {
    document.getElementById("scroller").style.transform = `translate(${result}px, 0px) scale(1) translateZ(0px)`
}

function scrollToValue(event) {
    debugger

    let regex = /^\d*(\.\d+)?$/
    console.log(regex.test(value))
    var value = event.target.value
    if (regex.test(value)) {
        var result = -100 * value + widthMain / 2
        var loop = value / 100
        if (value > 0 && value <= 500) {
            document.getElementById("scroller").style.transitionDuration = "2000ms"
        } else if (value > 500 && value <= 1500) {
            document.getElementById("scroller").style.transitionDuration = "3000ms"
        } else if (value > 1500 && value <= 3000) {
            document.getElementById("scroller").style.transitionDuration = "4000ms"
        } else if (value > 3000) {
            document.getElementById("scroller").style.transitionDuration = "5000ms"
        } else if (value > 4000) {
            document.getElementById("scroller").style.transitionDuration = "6000ms"
        }
        for (var i = 0; i < (Math.round(loop, 0)); i++) {
            pullLeftAction()
        }
        $('#inputValue').val(value)
        handleTranslate(result)
    } else {
        $('#inputValue').val("4.4")
        handleTranslate(4.4)
    }
}

function btnPlus() {
    let value = $('#inputValue').val()
    let handleValue = Math.round(++value, 2)
    $('#inputValue').val(handleValue)
    var result = -100 * value + widthMain / 2
    document.getElementById("scroller").style.transitionDuration = "500ms"
    handleTranslate(result)
}

function btnMinus() {
    let value = $('#inputValue').val()
    if (value <= 0.4) {
        return
    } else {
        let handleValue = Math.round(--value, 2)
        $('#inputValue').val(handleValue)
        var result = -100 * handleValue + widthMain / 2
        document.getElementById("scroller").style.transitionDuration = "500ms"
        handleTranslate(result)

    }

}
