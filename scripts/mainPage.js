window.onload = function () {

    let login_panel = document.getElementsByTagName('login-panel')[0];
    login_panel.style.backgroundImage = "url('./img/football1.jpg')";

    let form_menu_login = document.getElementById('login_radio');
    let form_menu_signin = document.getElementById('signin_radio');

    form_menu_login.addEventListener('click', (e) => { toggle_panel(1) }, false);
    form_menu_login.addEventListener('touchend', (e) => { toggle_panel(1) }, false);
    form_menu_signin.addEventListener('click', (e) => { toggle_panel(2) }, false);
    form_menu_signin.addEventListener('touchend', (e) => { toggle_panel(2) }, false);
};

function toggle_panel(x) {

    let login_panel = document.getElementsByTagName('login-panel')[0];
    let login = document.getElementById('login_form');
    let signin = document.getElementById('signin_form');

    switch (x) {
        case 1:
            if (login.classList.contains('collapse')) {
                login.classList.remove('collapse');
                signin.classList.add('collapse');
                login_panel.style.backgroundImage = "url('./img/football1.jpg')";
            }
            break;
        case 2:
            if (signin.classList.contains('collapse')) {
                signin.classList.remove('collapse');
                login.classList.add('collapse');
                login_panel.style.backgroundImage = "url('./img/football2.jpg')";
            }
            break
    }
}

function checksport(sport) {
    document.getElementsByName(sport)[0].checked = true;
}
