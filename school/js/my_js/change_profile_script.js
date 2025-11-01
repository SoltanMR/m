$(document).ready(function () {
    let imageValid = false;

    $('#image').on('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        if (!file.type.match('image.*')) {
            errorFunction("info", "نوع فایل باید تصویر باشد.");
            imageValid = false;
            return;
        }

        if (file.size > 4 * 1024 * 1024) {
            errorFunction("info", "حجم فایل نباید بیشتر از چهار مگابایت باشد.");
            imageValid = false;
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            $("#preview__image-box").addClass("d-none");
            $("#preview__image-img").attr('src', e.target.result);
            $("#preview__image-container").removeClass("d-none");
            imageValid = true;
        }
        reader.readAsDataURL(file);
    });

    $("#submitBtn").click(function () {
        if (!imageValid) {
            errorFunction("info", "لطفا اول عکس را وارد کنید.");
            return;
        }

        const form = $(this).closest('form')[0]; // فرم native
        if (form) form.submit();
    });
});
