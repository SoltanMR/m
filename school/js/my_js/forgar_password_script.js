$(document).ready(function () {
    $("#submitBtn").click(function (e) {
        e.preventDefault(); // جلوگیری از ارسال فرم پیش‌ازموعد

        const userName = $("#user_name").val().trim();
        const national_code = $("#national_code").val().trim();
        const password = $("#password").val().trim();

        // اعتبارسنجی فیلدها
        if (!userName || !password || !national_code) {
            return errorFunction('info', 'لطفا مقادیر را کامل وارد کنید.');
        }

        else if (!/^[\d۰-۹]+$/.test(national_code)) {
            return errorFunction('error', 'کد ملی باید عدد باشد.');
        }

        if (national_code.length !== 10) {
            return errorFunction('error', `کد ملی باید ${convertNumbers("10")} رقمی باشد.`);
        }

        if (userName.length > 200 || password.length > 200) {
            return errorFunction('error', `حروف نباید بیشتر از ${convertNumbers("200")} تا باشند.`);
        }

        // بازنشانی فیلدهای پسورد و آیکون‌ها
        $('.password-field').attr('type', 'password');
        $('.fa-eye').show();
        $('.fa-eye-slash').hide();

        // تبدیل اعداد فارسی به انگلیسی
        $('.persian-number').each(function () {
            $(this).val(convertNumbers($(this).val(), false));
        });

        // تغییر نوع دکمه به submit
        $(this).closest('form')[0].submit();
    });

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
});
