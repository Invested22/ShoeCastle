// JavaScript Document
var elFirstNameStatus = document.getElementById("firstNameStatus");
var elLastNameStatus = document.getElementById("lastNameStatus");
var elEmailStatus = document.getElementById('emailStatus');
var elPhoneStatus = document.getElementById("phoneStatus");
var elPasswordStatus = document.getElementById("passwordStatus");
var elPasswordConfirmationStatus = document.getElementById("passwordConfirmationStatus");
var nameRegex = /^[a-zA-z-']{2,32}$/;
var emailRegex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
var phoneRegex = /^[0-9]{10}$/;
var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,32}$/;
elFirstNameStatus.classList.add("alert");
elLastNameStatus.classList.add("alert");
elEmailStatus.classList.add("alert");
elPhoneStatus.classList.add("alert");
elPasswordStatus.classList.add("alert");
elPasswordConfirmationStatus.classList.add("alert");

function validateName(name,isFirstName){
    if (name.value.match(nameRegex)) {
        if (isFirstName) {
            elFirstNameStatus.classList.remove("alert-danger");
            elFirstNameStatus.classList.add("alert-success");
            elFirstNameStatus.innerHTML = "First is valid!";
        } else {
            elLastNameStatus.classList.remove("alert-danger");
            elLastNameStatus.classList.add("alert-success");
            elLastNameStatus.innerHTML = "Last Name is valid!";
        }
    } else {
        if (isFirstName) {
            elFirstNameStatus.classList.remove("alert-success");
            elFirstNameStatus.classList.add("alert-danger");
            elFirstNameStatus.innerHTML = "First Name is NOT valid!";
        } else {
            elLastNameStatus.classList.remove("alert-success");
            elLastNameStatus.classList.add("alert-danger");
            elLastNameStatus.innerHTML = "Last Name is NOT valid!";
        }
    }
}

function validateEmail(email){
    if (email.value.match(emailRegex)) {
        elEmailStatus.classList.remove("alert-danger");
        elEmailStatus.classList.add("alert-success");
        elEmailStatus.innerHTML = "Email is valid!";
    } else {
        elEmailStatus.classList.remove("alert-success");
        elEmailStatus.classList.add("alert-danger");
        elEmailStatus.innerHTML = "Email is NOT valid!";
    }
}

function validatePhone(phone){
    if (phone.value.match(phoneRegex)) {
        elPhoneStatus.classList.remove("alert-danger");
        elPhoneStatus.classList.add("alert-success");
        elPhoneStatus.innerHTML = "Phone is valid!";
    } else {
        elPhoneStatus.classList.remove("alert-success");
        elPhoneStatus.classList.add("alert-danger");
        elPhoneStatus.innerHTML = "Phone is NOT valid!";
    }
}

function validatePassword(password) {
    if (password.value.match(passwordRegex)) {
        elPasswordStatus.classList.remove("alert-danger");
        elPasswordStatus.classList.add("alert-success");
        elPasswordStatus.innerHTML = "Password is valid!";
    } else {
        elPasswordStatus.classList.remove("alert-success");
        elPasswordStatus.classList.add("alert-danger");
        elPasswordStatus.innerHTML = "Password is NOT valid!";
    }
}

function validatePasswordConfirmation(password,passwordConfirmation){
    if (passwordConfirmation.value == password.value) {
        elPasswordConfirmationStatus.classList.remove("alert-danger");
        elPasswordConfirmationStatus.classList.add("alert-success");
        elPasswordConfirmationStatus.innerHTML = "Password is valid!";
    } else {
        elPasswordConfirmationStatus.classList.remove("alert-success");
        elPasswordConfirmationStatus.classList.add("alert-danger");
        elPasswordConfirmationStatus.innerHTML = "Password is NOT valid!";
    }
}

var elFirst = document.getElementById("firstname");
elFirst.addEventListener("focusout", function(){ validateName(elFirst,true);}, false)

var elLast = document.getElementById("lastname");
elLast.addEventListener("focusout", function(){ validateName(elLast,false);}, false)

var elEmail = document.getElementById("email");
elEmail.addEventListener("focusout", function(){ validateEmail(elEmail);}, false);

var elPhone = document.getElementById("phone");
elPhone.addEventListener("focusout", function(){ validatePhone(elPhone);}, false);

var elPassword = document.getElementById("password");
elPassword.addEventListener("focusout", function(){ validatePassword(elPassword);}, false);

var elPasswordConfirmation = document.getElementById("passwordConfirmation");
elPasswordConfirmation.addEventListener("focusout", function(){ validatePasswordConfirmation(elPassword,elPasswordConfirmation);}, false);
