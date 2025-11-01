let pageUrl = window.location.href;

// بررسی وجود کلمه "school" در URL
let contains = pageUrl.toLowerCase().includes("school");

// تعیین مسیر پایه
let baseUrl = contains ? "./" : "./school/";

$(function () {
    const $modal = $("#customModal");
    const isDev = false; // در حالت dev اگر true باشه، لاگ فعال میشه

    // --- Helpers ---
    const toggleScroll = lock => $("body").css("overflow", lock ? "hidden" : "auto");
    const escapeHTML = str => $('<div>').text(str ?? "").html();

    const renderModal = ({ title = "", body = "" }) => {
        $modal.html(`
            <div class="modal-container animate__animated animate__fadeInDown">
                <div class="modal-header">
                    <h3 class="modal-title">${escapeHTML(title)}</h3>
                    <button class="close-btn modal__close-btn">&times;</button>
                </div>
                <div class="modal-body">${body}</div>
            </div>
        `).fadeIn(200);
        toggleScroll(true);
    };

    const closeModal = () => {
        $modal.fadeOut(150, () => $modal.empty());
        toggleScroll(false);
    };

    const showLoading = () => renderModal({
        title: "در حال بارگذاری...",
        body: `
            <div class="text-center py-4">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
                <p class="mt-2">در حال دریافت اطلاعات</p>
            </div>
        `
    });

    const showError = (message, details = "") => renderModal({
        title: "خطا",
        body: `
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <p>${escapeHTML(message)}</p>
                ${isDev && details
                ? `<pre class="bg-light p-2 mt-2 text-start overflow-auto" style="max-height:200px">${details}</pre>`
                : ""
            }
            </div>
        `
    });

    const renderContentModal = (d, type) => {
        const desc = d.has_description
            ? `<div class="content-section">
                   <h5>توضیحات:</h5>
                   <div class="scrollable-content vertical-scroll text-justify">${d.description}</div>
               </div>`
            : `<p class="modal-content-text text-muted text-center">هیچ توضیحی برای این محتوا وجود ندارد.</p>`;

        const file = d.has_file
            ? `<div class="div_btn-gradient text-center w-100 mt-3">
                   <a href="${baseUrl}download/${type === "homework" ? "download.php" : "download_nm.php"}?id=${(d.id)}" 
                      class="btn-gradient mx-auto download-btn" download>
                      دانلود فایل <i class="fa fa-download ms-2"></i>
                   </a>
               </div>`
            : "";

        renderModal({
            title: d.title,
            body: `
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
                    <p class="text-center modal-content-text mx-auto">درس: ${escapeHTML(d.lesson)}</p>
                    <p class="text-center modal-content-text mx-auto">مدرس: ${escapeHTML(d.writer)}</p>
                </div>
                ${desc}${file}
            `
        });
    };

    // --- باز کردن مودال ---
    $(document).on("click", ".openModalBtn", function () {
        const id = $(this).closest('.homework-items, .exam-items').data('id') || $(this).data("id");
        const type = $(this).data("type");

        if (isDev) console.log("Requesting content:", { id, type });

        showLoading();

        $.ajax({
            url: baseUrl + "get_content_details.php",
            type: "GET",
            data: { id, type },
            dataType: "json",
            success: function (res) {
                if (isDev) console.log("Response:", res);

                if (res.status === "success") {
                    renderContentModal(res.data, type);
                } else {
                    showError("دریافت اطلاعات با خطا مواجه شد.", res.message);
                }
            },
            error: function (xhr, status, error) {
                if (isDev) console.error("AJAX Error:", status, error, xhr.responseText);

                showError(
                    "مشکلی در دریافت اطلاعات رخ داده است.",
                    isDev ? `${xhr.status} - ${xhr.statusText}\n${xhr.responseText?.substring(0, 200)}...` : ""
                );
            }
        });
    });

    // --- بستن مودال ---
    $(document).on("click", ".close-btn", closeModal);
    $(window).on("click", e => { if ($(e.target).is("#customModal")) closeModal(); });
    $(document).on("keyup", e => { if (e.key === "Escape") closeModal(); });
});