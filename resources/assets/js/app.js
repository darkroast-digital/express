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
    }
    });

}
