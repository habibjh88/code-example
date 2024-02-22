var form_el = document.querySelectorAll('.form-control');


const validateEmail = (email) => {
    return String(email)
        .toLowerCase()
        .match(
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
};

function lengthCheck(length, helpText, min, max) {
    if (length < min) {
        helpText.innerHTML = `Please enter atleset ${min} charecter`;
    } else if (length > max) {
        helpText.innerHTML = `Please enter upto ${max} charecter`;
    }
}

function cbg(item) {
    var helpText = item.parentElement.querySelector('.form-help')
    var status = item.parentElement.dataset;
    if (!item.value) {
        status.dataset = 'error'
        helpText.innerHTML = 'Please enter the ' + item.id;
    } else {

        if (item.type === 'email' && !validateEmail(item.value)) {
            status.dataset = 'error'
            helpText.innerHTML = 'You Email is not valid';
            return;
        }

        status.dataset = 'success'
        helpText.innerHTML = 'Thank you';

        // lengthCheck(item.value.length, helpText, 4, 12);
        //Email validate

    }

}

form_el.forEach(function (item) {
    item.addEventListener('blur', function () {
        cbg(this);
        console.log(this)
    })
});

