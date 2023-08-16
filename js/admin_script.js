document.addEventListener('DOMContentLoaded', () => {
    let profile = document.querySelector('.header .flex .profile');
    let userBtn = document.querySelector('#user-btn');

    if (profile && userBtn) {
        userBtn.onclick = () => {
            profile.classList.toggle('active');
            navbar.classList.remove('active');
        };
    }
});

document.addEventListener('DOMContentLoaded', () => {
    let navbar = document.querySelector('.header .flex .navbar');
    let userBtn = document.querySelector('#menu-btn');

    if (navbar && userBtn) {
        userBtn.onclick = () => {
            navbar.classList.toggle('active');
            profile.classList.remove('active');
        };
    }
});

window.onscroll = () =>{
    profile.classList.remove('active');
    navbar.classList.remove('active');
}