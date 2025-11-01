// ------------------------------
// تبدیل اعداد فارسی و انگلیسی
// ------------------------------

// آرایه اعداد انگلیسی و فارسی
const englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
const persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

/**
 * تبدیل اعداد
  @param {string} text - متنی که می‌خواهیم اعدادش را تبدیل کنیم
  @param {boolean} toPersian - اگر true، تبدیل به فارسی؛ اگر false، تبدیل به انگلیسی
  @returns {string} متن با اعداد تبدیل شده
 */
function convertNumbers(text, toPersian = true) {
    const from = toPersian ? englishNumbers : persianNumbers;
    const to = toPersian ? persianNumbers : englishNumbers;

    return text.split('').map(char => {
        const index = from.indexOf(char);
        return index !== -1 ? to[index] : char;
    }).join('');
}
