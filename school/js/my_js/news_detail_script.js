// --- ابزار کمکی: تبدیل اعداد به فارسی ---
function convertToPersianNumbers(text) {
    const numberMap = { '0': '۰', '1': '۱', '2': '۲', '3': '۳', '4': '۴', '5': '۵', '6': '۶', '7': '۷', '8': '۸', '9': '۹' };
    return text.replace(/[0-9]/g, match => numberMap[match]);
}

// --- ابزار کمکی: sanitize برای جلوگیری از XSS ---
function sanitizeText(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// --- جلوگیری از خط جدید با Enter ---
function preventNewLine(e) {
    if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();
        return false;
    }
    return true;
}

// --- مدیریت input و paste کامنت ---
const commentInput = document.getElementById('comment');
if (commentInput) {
    commentInput.addEventListener('input', function () {
        this.value = convertToPersianNumbers(sanitizeText(this.value));
    });

    commentInput.addEventListener('paste', function (e) {
        e.preventDefault();
        const text = (e.clipboardData || window.clipboardData).getData('text');
        this.value += convertToPersianNumbers(sanitizeText(text));
    });

    commentInput.addEventListener('keydown', preventNewLine);
}

// --- مودال تصویر ---
const imageModal = document.getElementById('imageModal');
const modalImg = document.getElementById('modalImage');

function openImageModal(imageSrc) {
    if (!imageModal || !modalImg) return;
    modalImg.src = sanitizeText(imageSrc);
    imageModal.style.display = "flex";
    imageModal.focus();
    document.body.style.overflow = "hidden";
}

function closeImageModal() {
    if (!imageModal) return;
    imageModal.style.display = "none";
    document.body.style.overflow = "auto";
    modalImg.src = "";
}

// بستن مودال با کلیک روی overlay
if (imageModal) {
    imageModal.addEventListener('click', function (e) {
        if (e.target === this) closeImageModal();
    });
}

// بستن مودال با ESC
document.addEventListener('keydown', function (e) {
    if (e.key === "Escape") closeImageModal();
});

// --- مدیریت فرم پاسخ ---
function showReplyForm(commentId) {
    if (!commentId) return;
    document.querySelectorAll('.reply-form').forEach(form => {
        if (form.id !== 'reply-form-' + commentId) form.style.display = 'none';
    });

    const replyForm = document.getElementById('reply-form-' + commentId);
    if (replyForm) {
        replyForm.style.display = 'block';
        replyForm.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        replyForm.querySelector('textarea, input')?.focus();
    }
}

function hideReplyForm(commentId) {
    const replyForm = document.getElementById('reply-form-' + commentId);
    if (replyForm) replyForm.style.display = 'none';
}
