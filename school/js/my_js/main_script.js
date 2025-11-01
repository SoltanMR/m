// ---------------- Dark Mode Toggle ---------------- //
const darkModeToggle = document.getElementById('darkModeToggle');
const body = document.body;


// همیشه بعد از لود صفحه برو بالا
window.scrollTo(0, 0);
// اگر مرورگر بخواد اسکرول رو حفظ کنه (مثل کروم)، این خط جلویش رو می‌گیره
if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}

let theme = localStorage.getItem("theme") || "light";

function applyTheme(theme) {
    if (theme === "dark") {
        body.classList.add("dark-mode");
        darkModeToggle?.classList.add("dark-btn-on");
    } else {
        body.classList.remove("dark-mode");
        darkModeToggle?.classList.remove("dark-btn-on");
    }
}
applyTheme(theme);

if (darkModeToggle) {
    darkModeToggle.addEventListener('click', () => {
        theme = (theme === "light") ? "dark" : "light";
        localStorage.setItem("theme", theme);
        applyTheme(theme);
    });
}

// ---------------- Prevent default anchor behavior ---------------- //
document.querySelectorAll('a[href="#"]').forEach(anchor => {
    anchor.addEventListener('click', e => e.preventDefault());
});

// ---------------- Dropdown ---------------- //
if ($(".icon-dropdown-md").length) {
    $(".icon-dropdown-md").click(function () {
        $(this).toggleClass("icon-dropdown-md-rotate");
        $(".nav-dropdown-items-md").toggleClass("show__dropdown");
    });
}

// ---------------- Follow Us Height ---------------- //
const followIcon = document.querySelector('.fallow-us__icon');
const followFooter = document.querySelector('.footer__fallow-us');

if (followIcon && followFooter) {
    followIcon.style.height = '0';
    followIcon.style.transition = 'height 0.35s ease';

    followFooter.addEventListener('click', () => {
        const isOpen = parseInt(followIcon.style.height) > 0;
        const autoHeight = followIcon.scrollHeight + "px";

        // toggle height
        followIcon.style.height = isOpen ? '0' : autoHeight;

        // toggle images after transition
        setTimeout(() => {
            followIcon.querySelectorAll('ul li img').forEach(img => {
                img.classList.toggle('show__follow-us', !isOpen);
            });
        }, 350);
    });
}

document.addEventListener('click', function (event) {
    const navbar = document.querySelector('.navbar-collapse');
    const toggler = document.querySelector('.navbar-toggler');

    // اگر منو باز است و کلیک خارج از آن انجام شود
    if (navbar.classList.contains('show') &&
        !navbar.contains(event.target) &&
        !toggler.contains(event.target)) {
        const bsCollapse = new bootstrap.Collapse(navbar, { toggle: false });
        bsCollapse.hide();
    }
});

window.addEventListener('scroll', function () {
    const navbar = document.querySelector('.navbar-collapse');
    if (navbar.classList.contains('show')) {
        const bsCollapse = new bootstrap.Collapse(navbar, { toggle: false });
        bsCollapse.hide();
    }
});