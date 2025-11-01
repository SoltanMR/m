<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>خطا در اتصال</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="./img/favicon/favicon.ico">
  <link rel="stylesheet" href="../website_font/css/fonts.css">
  <style>
    :root {
      --purple-light: #4910cdff;
      --purple-dark: #0c0065ff;
      --text-color: #ffffffff;
      --btn-color: #ffffffff;
      --btn-bg: #4910cdff;
      --btn-hover: #732d91;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: 'IRANSans', sans-serif;
      background: linear-gradient(135deg, var(--purple-light), var(--purple-dark));
      color: var(--text-color);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .error-container {
      width: 90%;
      max-width: 600px;
      background-color: #0c0065ff;
      border-radius: 16px;
      padding: 40px 30px;
      text-align: center;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      backdrop-filter: blur(6px);
    }

    .error-container h1 {
      font-size: 28px;
      margin-bottom: 20px;
    }

    .error-container p {
      font-size: 17px;
      line-height: 1.8;
      margin-bottom: 30px;
    }

    .error-container button {
      background-color: var(--btn-bg);
      color: var(--btn-color);
      border: none;
      padding: 12px 24px;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .error-container button:hover {
      background-color: var(--btn-hover);
    }

    @media (max-width: 480px) {
      .error-container {
        padding: 30px 20px;
      }

      .error-container h1 {
        font-size: 24px;
      }

      .error-container p {
        font-size: 15px;
      }
    }
  </style>
</head>
<body>
  <div class="error-container">
    <h1>خطا در اتصال به پایگاه داده</h1>
    <p>متأسفانه در سایت مشکلی پیش امده<br>لطفاً بعداً دوباره تلاش کنید از صبوری شما متشکریم</p>
    <?php
session_start();
$back = isset($_SESSION['last_page']) ? $_SESSION['last_page'] : 'index.php';
?>

<button onclick="window.location.href='<?php echo $back; ?>'">بازگشت به صفحه قبلی</button>

  </div>

  <script>
    function goBack() {
      if (window.history.length > 1) {
        window.history.back();
      } else {
        window.location.href = "index.php";
      }
    }
  </script>
</body>
</html>
