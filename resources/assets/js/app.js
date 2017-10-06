// *************************************************************************
// *************************************************************************
// *************************************************************************



require('./bootstrap');
require('./icons/icons');

var log = console.log;

// #OFF CANVAS
// =========================================================================

var offCanvasTrigger = document.querySelector('.off-canvas-trigger');
var offCanvas = document.querySelector('.off-canvas');

if (offCanvasTrigger) {
    offCanvasTrigger.addEventListener('click', function () {
        offCanvas.classList.toggle('is-open');
        overlay.classList.toggle('is-active');
    });
}




// #MODAL
// =========================================================================

var modalTrigger = document.querySelector('.modal-trigger');
var modal = document.querySelector('.modal');

if (modalTrigger) {
    modalTrigger.addEventListener('click', function () {
        modal.classList.toggle('is-open');
        overlay.classList.toggle('is-active');
    });
}




// #KEY CONTROL
// =========================================================================

$(document).keyup(function(e) {
    if (e.keyCode === 27) {
        overlay.classList.remove('is-active');
    }
});

if (offCanvas) {

    $(document).keyup(function(e) {
        if (e.keyCode === 27) {
            offCanvas.classList.remove('is-open');
        }
    });

}

if (modal) {

    $(document).keyup(function(e) {
        if (e.keyCode === 27) {
            modal.classList.remove('is-open');
        }
    });

}




// #OVERLAY
// =========================================================================

var overlay = document.querySelector('.overlay');

if (overlay) {
    overlay.addEventListener('click', function () {
        this.classList.remove('is-active');
    });
}

if (overlay) {
    overlay.addEventListener('click', function () {
        offCanvas.classList.remove('is-open');
    });
}

if (overlay) {
    overlay.addEventListener('click', function () {
        modal.classList.remove('is-open');
    });
}




// #ACCORDION
// =========================================================================

$('.accordion-content').hide();
$('.accordion-content').first().show();
$('.accordion-panel').first().addClass('is-open');

$('.accordion-title').click(function() {
    $('.accordion-panel').removeClass('is-open');
    $(this).parent().addClass('is-open');
    $('.accordion-content').slideUp(200);
    $(this).next('.accordion-content').slideDown(200);
});




// #TABS
// =========================================================================

$('li[data-tab], .tabs__content').first().addClass('is-active');
$('.tabs-content').first().addClass('is-active');

$('li[data-tab]').click(function() {
    var thisTab = $(this).attr('data-tab');
    var tab = $('.tabs-content' + '[data-tab="' + thisTab + '"]');

    $('li[data-tab]').removeClass('is-active');
    $(this).addClass('is-active');
    $('.tabs-content').removeClass('is-active');
    tab.addClass('is-active');
});




// #FORM
// =========================================================================

var form = $('.form');

form.submit(function (event) {
    event.preventDefault();
    var formData = new FormData();

    $.ajax({
        url: $(this).attr('action'),
        type: 'post',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (returndata) {
            $('<div class="alert success">Your Message Was Sent!  We\'ll be in touch.</div>').insertAfter(form);

            $('input').val('');
            $('textarea').val('');
        },
        error: function () {
            $('<div class="alert error">Oh no!  Something went wrong, try again,</div>').insertAfter(form);

            $('input').val('');
            $('textarea').val('');
        }
    });

    return false;
});
 



// #PRINT DETAILS
// =========================================================================

var printConfirm = document.querySelector('.print-confirm input');
var printDetails = document.querySelector('.print-details');

if (printDetails) {
    
    printDetails.style.display = 'none';


    printConfirm.addEventListener('click', () => {
    if (printConfirm.checked) {
        printDetails.style.display = 'block';
    } else {
        printDetails.style.display = 'none';
        $('input').val("");
        $('select').val("");
        $('[data-price]').val("0");
        $('span.price').text($('span.price').data('price'));
    }
    });

}




// #FORM
// =========================================================================

var form = $('.js-form');

$(form).submit(function(e) {
    e.preventDefault();

    var formData = new FormData($(this)[0]);
    // if files => formData.append('file', $('input[type=file]')[0].files[0]); 

    $.ajax({
        type: 'post',
        url: $(this).attr('action'),
        data: formData,
        processData: false,
        contentType : false
    })
    .done(function (response) {
        $('input').val('');
        $('textarea').val('');
        $('<div class="alert is-success">Your Message Was Sent!  We\'ll be in touch.</div>').insertAfter(form);

        console.log('success' + response);
    })
    .fail(function (data) {
        $('input').val('');
        $('textarea').val('');
        $('<div class="alert is-error">Oh no!  Something went wrong, try again.</div>').insertAfter(form);

        console.log('fail' + data);
    });
});

// Option Pricing

var basePrice = $('span.price').data('price');
basePrice = parseInt(basePrice);
var optionPrice = $('.print-detail select option:selected').data('price');
//optionPrice = parseInt(optionPrice);
var newPrice = basePrice + optionPrice;
$('span.price').text(newPrice);

$('.print-detail select').change(function() {
    optionPrice = $('.print-detail select option:selected').data('price');
    optionPrice = parseInt(optionPrice);
    newPrice = basePrice + optionPrice;
    $('span.price').text(newPrice);
});



// Show Size Options

$("[data-select]").hide();
$('.quantity-label').hide();

$('[data-print]').click(function() {
    var thisPrint = $(this).attr('data-print');

    $('[data-select]').hide();
    $('[data-select="' + thisPrint + '"]').show();
    $('.quantity-label').show();
});