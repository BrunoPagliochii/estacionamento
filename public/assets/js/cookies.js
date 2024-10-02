// Função para definir um cookie
function setCookie(name, value, days) {
    const d = new Date();
    d.setTime(d.getTime() + (days*24*60*60*1000));
    const expires = "expires=" + d.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

// Função para obter um cookie
function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

// Função para verificar o consentimento de cookies
function checkCookieConsent() {
    // Verificar se a página atual é politicaCookies.php
    if (window.location.pathname.includes("politicaCookies.php")) {
        return;
    }

    const consent = getCookie("cookieConsent");
    if (!consent) {
        $('#cookieModal').modal({backdrop: 'static', keyboard: false});
        $('#cookieModal').modal('show');
    }
}

// Event listener para aceitar cookies
document.getElementById('acceptCookies').addEventListener('click', function() {
    const date = new Date().toISOString();
    setCookie("cookieConsent", date, 365);
    $('#cookieModal').modal('hide');
});

// Verificar o consentimento de cookies ao carregar a página
window.onload = function() {
    checkCookieConsent();
}