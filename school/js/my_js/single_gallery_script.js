$(document).ready(function () {
    let galleryImages = [];
    let currentImageIndex = 0;

    $('.gallery-image').each(function () {
        galleryImages.push($(this).attr('src'));
    });

    // کلیک روی عکس
    $('.gallery-image').click(function () {
        const src = $(this).attr('src');
        currentImageIndex = galleryImages.indexOf(src);
        showImage(currentImageIndex);
    });

    // دکمه بعدی
    $('#nextBtn').click(function (e) {
        e.stopPropagation();
        currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
        $('#modalImage').attr('src', galleryImages[currentImageIndex]);
    });

    // دکمه قبلی
    $('#prevBtn').click(function (e) {
        e.stopPropagation();
        currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
        $('#modalImage').attr('src', galleryImages[currentImageIndex]);
    });

    // بستن مودال
    $('#close-span').click(function () {
        closeImageModal();
    });

    // کلیدهای کیبورد (راست و چپ و ESC)
    $(document).keydown(function (e) {
        if ($('#imageModal').hasClass('show')) {
            if (e.key === 'ArrowRight') $('#nextBtn').click();
            else if (e.key === 'ArrowLeft') $('#prevBtn').click();
            else if (e.key === 'Escape') closeImageModal();
        }
    });

    // نمایش مودال
    function showImage(index) {
        $('#modalImage').attr('src', galleryImages[index]);
        $('#imageModal').addClass('show');
        $('body').css('overflow', 'hidden'); // جلوگیری از اسکرول پس‌زمینه
    }

    // بستن مودال
    function closeImageModal() {
        $('#imageModal').removeClass('show');
        $('body').css('overflow', 'auto'); // بازگشت اسکرول
    }
});
