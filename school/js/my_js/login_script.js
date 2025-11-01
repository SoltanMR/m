$(document).ready(function () {
    // فقط حذف فاصله
    window.removeSpaces = function (input) {
        input.value = input.value.replace(/\s/g, '');
    }

    // حذف فاصله و کاراکترهای خطرناک برای رمز هنرجو
    window.sanitizeStudentPassword = function (input) {
        input.value = input.value.replace(/[\s<>\/\\'"`]/g, '');
    }

    // حذف کاراکترهای خطرناک برای نام کاربری هنرجو (فاصله مجاز است)
    window.sanitizeStudentUsername = function (input) {
        input.value = input.value.replace(/[<>\/\\'"`]/g, '');
    }

    const maxLengthPersian = convertNumbers("200"); // طول حداکثر ورودی

    // مدیریت باز و بسته شدن فرم‌ها
    function toggleForm(closeSelector, childSelector) {
        $(closeSelector).click(function () {
            $(childSelector).addClass("animate2");
            $(".chiled2").toggleClass("animate1");
            $(".parent").toggleClass("animate3");
            $(".close").addClass("d-none");
            $(".h11").toggleClass("animate5");
            $(".paragraf2").addClass("d-none");
            $(".h12").toggleClass("animate5");
        });
    }
    toggleForm(".close__student", ".chiled1");
    toggleForm(".close__teacher", ".chiled3");

    $(".back").click(function (e) {
        e.preventDefault(); // جلوگیری از رفتار پیش‌فرض
        if (!$(".close").hasClass("d-none")) {
            window.location.href = '../';
        }
        $(".chiled1, .chiled3").removeClass("animate2");
        $(".chiled2").removeClass("animate1");
        $(".parent").removeClass("animate3");
        $(".close").removeClass("d-none");
        $(".paragraf2").removeClass("d-none");
    });

    // تابع ارسال فرم با اعتبارسنجی
    function handleForm(submitSelector, userSelector, passSelector) {
        $(submitSelector).click(function (e) {
            e.preventDefault();
            const userName = $(userSelector).val().trim();
            const password = $(passSelector).val().trim();

            if (!userName || !password) {
                errorFunction("info", "لطفا مقادیر را کامل وارد کنید.");
                return;
            }
            if (userName.length >= 200 || password.length >= 200) {
                errorFunction("error", `مقادیر نباید بیشتر از ${maxLengthPersian} حرف باشند.`);
                return;
            }
            if (!(/^[۰-۹]+$/.test(userName))) {
                errorFunction("error", `کد ملی باید اعداد باشد.`);
                return;
            }

            // اصلاح شده: استفاده از get(0).submit() به جای [0].submit
            const form = $(this).closest('form').get(0);
            if (form) {
                HTMLFormElement.prototype.submit.call(form);
            }
        });
    }

    handleForm("#submitStudent", "#userName", "#password");
    handleForm("#submitTeacher", "#userNameHonaramoz", "#passwordHonaramoz");

});
