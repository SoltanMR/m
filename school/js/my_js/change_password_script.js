$(document).ready(function () {

    $("#submitBtn").click(function () {
        const prives_password = $("#prives_password").val().trim();
        const new_password = $("#new_password").val().trim();
        const repet_new_password = $("#repet_new_password").val().trim();

        if (prives_password === "" || new_password === "" || repet_new_password === "") {
            errorFunction("info", "لطفا مقادیر را کامل وارد کنید.");
        } else if (new_password !== repet_new_password) {
            errorFunction("error", "رمز جدید با تکرار رمز متفاوت است.");
        } else if (prives_password.length > 200 || new_password.length > 200 || repet_new_password.length > 200) {
            const maxLengthPersian = convertNumbers("200"); // تبدیل 200 به فارسی
            errorFunction("error", `نباید مقادیر بیشتر از ${maxLengthPersian} حرف باشند.`);
        } else {
            $('.password-field').attr('type', 'password');
            $('.fa-eye').show();
            $('.fa-eye-slash').hide();

            $('.persian-number').each(function () {
                $(this).val(convertNumbers($(this).val(), false));
            });

            const form = document.getElementById('formPassword');
            if (form) form.submit()
        }
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
