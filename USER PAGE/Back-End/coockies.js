window.onload = function() {
    if (document.cookie.indexOf('remember_username') !== -1 && document.cookie.indexOf('remember_password') !== -1) {
        const usernameCookie = getCookie('remember_username');
        const passwordCookie = getCookie('remember_password');

        if (usernameCookie) {
            document.querySelector('input[name="lusername"]').value = usernameCookie;
        }
        if (passwordCookie) {
            document.querySelector('input[name="lpassword"]').value = passwordCookie;
        }
        document.querySelector('input[name="rememberMe"]').checked = true;
    }
};

function getCookie(name) {
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i].trim(); 
        if (c.indexOf(name + "=") === 0) {
            return c.substring(name.length + 1);  
        }
    }
    return "";
}
