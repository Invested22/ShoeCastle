var loginCheck = true;

function checkLogin() {
	if (loginCheck === true) {
		return true;
	}
	else {
		return false;
	}
}

function setLogin(status) {
	if (status === true) {
		loginCheck = true;
	}
	
	if (status === false) {
		loginCheck = false;
	}
}