$(function () {
    $(function () {
        const loadContent = (type, page = 1) => {
            $.get("load_content.php", { type, page }, function (html) {
                $("." + type + "s .card-body").html(html);
            });
        };

        // بارگذاری صفحه اول
        loadContent("homework");
        loadContent("exam");

        // شماره صفحات
        $(document).on("click", ".page-btn", function () {
            const type = $(this).data("type");
            const page = $(this).data("page");
            loadContent(type, page);
        });
    });
});
