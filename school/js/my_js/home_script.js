$(document).ready(function () {

    // ---------------- Header Animations ---------------- //
    function updateHeaderAnimations() {
        if (window.innerWidth <= 768) {
            $(".header__animation-title").addClass("animate__fadeInDown");
            $(".header__animation-text").addClass("animate__fadeInRight");
            $(".header__animation-img").removeClass("animate__fadeInLeft");
        } else {
            $(".header__animation-img").addClass("animate__fadeInLeft wow animate__animated");
            $(".header__animation-title").addClass("animate__fadeInDown");
            $(".header__animation-text").addClass("animate__fadeInRight");
        }
    }
    updateHeaderAnimations();
    window.addEventListener('resize', updateHeaderAnimations);

    // ---------------- Smooth Scroll ----------------
    const smoothLinks = $("a.smooth-scroll");
    if (smoothLinks.length) {
        smoothLinks.click(function (event) {
            event.preventDefault();

            const sectionId = $(this).attr("href");
            const target = $(sectionId);
            if (target.length) {
                const headerOffset = 0; // اگر header fixed دارید این مقدار را تنظیم کنید
                $('html, body').animate({
                    scrollTop: target.offset().top - headerOffset
                }, 800);
            }
        });
    }

    // ---------------- Owl Carousel ----------------
    var $carousel = $(".owl-carousel");
    var itemCount = $carousel.find(".item").length;

    $carousel.owlCarousel({
        rtl: true,
        loop: itemCount > 3, // فقط اگر بیشتر از 3 خبر وجود داشت
        margin: 10,
        dots: itemCount > 1, // دات‌ها فقط وقتی بیش از یک خبر هست
        autoplay: itemCount > 3,
        smartSpeed: 500,
        autoplayHoverPause: true,
        responsive: {
            0: { items: 1 },
            576: { items: 1 },
            768: { items: 2 },
            992: { items: 3 }
        }
    });
});

function showAccessAlert() {
    errorFunction("error", "شما فقط می‌توانید تمرینات و نمونه سوالات مربوط به رشته و پایه خود را مشاهده کنید.");
}
function showLoginAlert() {
    errorFunction("info", "برای مشاهده نمونه سوالات و تمرینات ابتدا به عنوان هنرجو باید وارد حساب کاربری خود شوید.");
}