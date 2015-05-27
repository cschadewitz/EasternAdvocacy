/**
 * Created by Wolf on 2/2/2015.
 */
(function()
{
    $('.submit').submit(function (e) {
        return false;
    });
})();

function processForm()
{
    var em = checkEmailError();
    var zip = checkZipError();
    var type = checkTypeError();
    var name = checkNameError();
    if(em && zip && type && name)
    {
        $(this).request('onSubscribe',{
            data: {
                "name" : $('#name').val(),
                "zip" : $('#zip').val(),
                "type" : $('#type').val(),
                "email" : $('#email').val()
            },
            success: function(data){
                location.reload();
            }
        }); return false;
    }
    else{ //Attach event listeners
        zipValid();
        typeValid();
        emailValid();
        nameValid();
    }
}

function checkEmailError()
{
    var matchEmail = /^([\w\.\-_]+)?\w+@[\w-_]+(\.\w+){1,}$/;
    var email = $('#email');
    var emailError = $('.email-error-hint');
    if(!email.val() || !matchEmail.test(email.val()))
    {
        email.addClass('input-error');
        emailError.show();
        return false;
    }
    else {
        return true;
    }
}

function checkZipError()
{
    var matchZip = /^\d{5}(\-?\d{4})?$/;
    var zip = $('#zip');
    var zipError = $('.zip-error-hint');
    if(!matchZip.test(zip.val())){
        zip.addClass('input-error');
        zipError.show();
        return false;
    }
    else
        return true;
}

function checkTypeError()
{
    var type = $('#type');
    var typeHint = $('.type-error-hint');
    if(type.val() === 'none')
    {
        typeHint.show();
        return false;
    }
    else
        return true;
}

function checkNameError()
{
    var name = $('#name');
    var nameError = $('.name-error-hint');
    if(!name.val())
    {
        nameError.show();
        return false;
    }
    else
    {
        return true;
    }
}

function emailError(data)
{
    var em = $('#email');
    var emh = $('.email-error-dup');
    if(data.error === 'nonUniqueEmail')
    {
        em.addClass('email-error').on('input propertychange paste', function () {
            em.removeClass('email-error');
            emh.hide();
        });
        emh.show();
        return true;
    }
}

function zipValid()
{
    var matchZip = /^\d{5}(\-?\d{4})?$/;
    var zip = $('#zip');
    var zipError = $('.zip-error-hint');
    zip.on('input propertychange paste', function () {
        if(matchZip.test(zip.val()))
        {
            zip.removeClass('input-error');
            zipError.hide();
        }
    });
}

function emailValid()
{
    var matchEmail = /^([\w\.\-_]+)?\w+@[\w-_]+(\.\w+){1,}$/;
    var email = $('#email');
    var emailError = $('.email-error-hint');
    email.on('input propertychange paste', function () {
        if(matchEmail.test(email.val()))
        {
            email.removeClass('input-error');
            emailError.hide();
        }
    });
}

function nameValid()
{
    var name = $('#name');
    var nameError = $('.name-error-hint');
    name.on('input propertychange paste', function() {
        if(name.val())
        {
            name.removeClass('.input-error');
            nameError.hide();
        }
    });
}

function typeValid()
{
    var type = $('#type');
    var typeHint = $('.type-error-hint');
    type.on('change', function () {
        if (type.val() !== 'none')
        {
            typeHint.hide();
        }
    });
}