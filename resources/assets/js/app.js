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

var close = document.querySelector('.close');

close.addEventListener('click', function() {
    offCanvas.classList.toggle('is-open');
    overlay.classList.toggle('is-active');
});




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

if (overlay) {
    overlay.addEventListener('click', function () {
        lightbox.removeClass('is-open');
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
            $('input').val('');
            $('select').val('');
            $('[data-price]').val('0');
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




// #FILE UPLOAD
// =========================================================================

var uploadInput = $('input[type="file"]');

uploadInput.on('change', function () {
    $(this).parent().find('span').css('display', 'none');

    var thisFiles = $(this)[0].files;


    // if ($('.upload-confirm')) {
    //     $('.upload-confirm').css('display', 'none');
    // }

    // for (var i = thisFiles.length - 1; i >= 0; i--) {
    //     $(this).parent().append('<span style="display:block">' + thisFiles[i].name + '</span>');
    // }

    $(this).parent().after('<p class="upload-confirm" style="display: inline-block; color: #FFFFFF; background: #43CB9D; padding: .25rem; border-radius: 2px; box-shadow: inset 0 -2px 0 0 rgba(0, 0, 0, .1);">Files Uploaded!</p>');
});




// Option Pricing

var basePrice = $('span.price').data('price');
basePrice = parseInt(basePrice);
var optionPrice = $('.print-detail select option:selected').data('price');
var newPrice = basePrice + optionPrice;

$('.print-detail select').change(function() {

    optionPrice = $(this).find(':selected').data('price');
    optionPrice = parseInt(optionPrice);
    newPrice = basePrice + optionPrice;
    $('span.price').text(newPrice);
});

var printSelect = $('.print-selects select');

printSelect.change(function () {
    var selectedPrice = $(this).find(':selected').data('price');
    $('span.price').text(selectedPrice);
});




// Show Size Options

$('[data-select]').hide();
$('.quantity-label').hide();

$('[data-print]').click(function() {
    var thisPrint = $(this).attr('data-print');

    $('[data-select]').hide();
    $('[data-select="' + thisPrint + '"]').show();
    $('.quantity-label').show();
});




// #LIGHTBOX
// =========================================================================

var lightbox = $('.lightbox');
var lightboxImage = $('.lightbox img');
var lightboxTrigger = $('.lightbox-trigger');

lightbox.hide();

lightboxTrigger.click(function () {
    var image = $(this).data('src');

    lightbox.css('visibility', 'visible');
    lightbox.fadeIn('fast');
    lightboxImage.attr('src', image);
});

lightbox.click(function () {
    lightbox.fadeOut('fast');
});

// #COOKIES
// =========================================================================

window.Cookies = require('js-cookie');

$.get('https://ipinfo.io', function(response) {
    console.log(response.city, response.country);

    if (response.country == 'US') {
        if (Cookies.get('us-visited') != 'true') {
            $('.us-popup').fadeIn();
            Cookies.set('us-visited', 'true', { expires: 7 });
        }
    }

}, 'jsonp');

$('.us-popup-close').click(function() {
    $('.us-popup').fadeOut();
});

var cad = $.getJSON('https://api.fixer.io/latest', function(data) {
  fx.rates = data.rates
  var rate = fx(1).from('USD').to('CAD');
  var conversion = rate.toFixed(4);
  var cad = Math.round(conversion * 100) / 100;

  $('.cad-container').text(cad);
});

var rates = {
    'CA': {
        'ON': 13,
        'QC': 14.975,
        'NS': 15,
        'NB': 15,
        'MB': 13,
        'BC': 12,
        'PE': 15,
        'SK': 11,
        'AB': 5,
        'NL': 15,
        'NT': 5,
        'YT': 5,
        'NU': 5
    }
};

var countryInput = document.querySelector('select[name="country"]');

if (countryInput) {
var provinceInput = document.querySelector('select[name="province"]');
var subtotal = document.querySelector('input[name="subtotal"]').value;
var taxContainer = document.querySelector('.tax-container');
var totalContainer = document.querySelector('.total-container');
var stateLabel = document.querySelector('label[for="province"]');

provinceInput.addEventListener('change', function () {
    var rate = rates[countryInput.value][this.value];
    var tax = subtotal * rate / 100;

    taxContainer.innerHTML = tax;
    totalContainer.innerHTML = +subtotal + +tax;
});

countryInput.addEventListener('change', function() {

    if (this.value == 'US') {

        var stateTemplate = `
            <option value="" selected disabled>Choose your State</option>
            <option value="Alabama">Alabama</option>
            <option value="Alaska">Alaska</option>
            <option value="Arizona">Arizona</option>
            <option value="Arkansas">Arkansas</option>
            <option value="California">California</option>
            <option value="Colorado">Colorado</option>
            <option value="Connecticut">Connecticut</option>
            <option value="Delaware">Delaware</option>
            <option value="Florida">Florida</option>
            <option value="Georgia">Georgia</option>
            <option value="Hawaii">Hawaii</option>
            <option value="Idaho">Idaho</option>
            <option value="Illinois">Illinois</option>
            <option value="Indiana">Indiana</option>
            <option value="Iowa">Iowa</option>
            <option value="Kansas">Kansas</option>
            <option value="Kentucky">Kentucky</option>
            <option value="Louisiana">Louisiana</option>
            <option value="Maine">Maine</option>
            <option value="Maryland">Maryland</option>
            <option value="Massachusetts">Massachusetts</option>
            <option value="Michigan">Michigan</option>
            <option value="Minnesota">Minnesota</option>
            <option value="Mississippi">Mississippi</option>
            <option value="Missouri">Missouri</option>
            <option value="Montana">Montana</option>
            <option value="Nebraska">Nebraska</option>
            <option value="Nevada">Nevada</option>
            <option value="New Hampshire">New Hampshire</option>
            <option value="New Jersey">New Jersey</option>
            <option value="New Mexico">New Mexico</option>
            <option value="New York">New York</option>
            <option value="New Mexico">New Mexico</option>
            <option value="New York">New York</option>
            <option value="North Carolina">North Carolina</option>
            <option value="North Dakota">North Dakota</option>
            <option value="Ohio">Ohio</option>
            <option value="Oklahoma">Oklahoma</option>
            <option value="Oregon">Oregon</option>
            <option value="Pennsylvania">Pennsylvania</option>
            <option value="Rhode Island">Rhode Island</option>
            <option value="South Carolina">South Carolina</option>
            <option value="South Dakota">South Dakota</option>
            <option value="Tenessee">Tenessee</option>
            <option value="Texas">Texas</option>
            <option value="Utah">Utah</option>
            <option value="Vermont">Vermont</option>
            <option value="Virginia">Virginia</option>
            <option value="Washington">Washington</option>
            <option value="West Virginia">West Virginia</option>
            <option value="Wisconsin">Wisconsin</option>
            <option value="Wyoming">Wyoming</option>
            <option value="Washington D.C.">Washington D.C.</option>
        `;

        document.querySelector('.tax-container').innerHTML = '0.00';
        stateLabel.innerHTML = 'State';
        provinceInput.setAttribute('name', 'state');
        provinceInput.innerHTML = stateTemplate;
    } else if (this.value == 'CA') {
        var stateInput = document.querySelector('select[name="state"]');
        var provinceTemplate = `
            <option value="" selected disabled>Choose your Province</option>
            <option value="AB">Alberta</option>
            <option value="BC">British Columbia</option>
            <option value="MB">Manitoba</option>
            <option value="NB">New Brunswick</option>
            <option value="NL">Newfoundland &amp; Labrador</option>
            <option value="ON">Ontario</option>
            <option value="PE">Prince Edward Island</option>
            <option value="QC">Quebec</option>
            <option value="SK">Saskatchewan</option>
            <option value="NT">Northwest Territories</option>
            <option value="NU">Nunavut</option>
            <option value="YT">Yukon</option>
        `;

        stateLabel.innerHTML = 'Province';
        stateInput.setAttribute('name', 'province');
        stateInput.innerHTML = provinceTemplate;
    }
});
}
