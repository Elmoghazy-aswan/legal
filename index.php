<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة الشؤون القانونية | أسوان</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --bg-page: #f8f9fa;
            --text-color: #212529;
            --card-bg: #ffffff;
            --primary-grad: linear-gradient(45deg, #004e92, #000428);
        }

        body.dark-mode {
            --bg-page: #0f111a;
            --text-color: #ffffff;
            --card-bg: #1a1d29;
        }

        body {
            background-color: var(--bg-page);
            color: var(--text-color);
            transition: 0.5s;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 15px;
        }

        .main-container {
            background: var(--card-bg);
            padding: 40px 20px;
            border-radius: 40px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12);
            text-align: center;
            max-width: 1100px;
            width: 100%;
            border: 1px solid rgba(128, 128, 128, 0.1);
            margin: auto;
        }

        .law-title {
            font-size: clamp(2rem, 8vw, 3.5rem);
            font-weight: 900;
            margin-bottom: 5px;
            line-height: 1.2;
        }

        .sub-title {
            font-size: clamp(1rem, 4vw, 1.6rem);
            opacity: 0.8;
            margin-bottom: 40px;
            color: #0d6efd;
        }

        body.dark-mode .sub-title {
            color: #4facfe;
        }

        .scale-icon {
            font-size: clamp(3rem, 10vw, 4.5rem);
            color: #0d6efd;
            margin-bottom: 15px;
        }

        body.dark-mode .scale-icon {
            color: #ffce67;
        }

        .nav-button {
            background: var(--card-bg);
            border: 1px solid rgba(128, 128, 128, 0.1);
            border-radius: 20px;
            padding: 25px 15px;
            text-decoration: none;
            color: var(--text-color);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.03);
            height: 100%;
            min-height: 160px;
        }

        .nav-button:hover {
            transform: translateY(-10px);
            background: var(--primary-grad);
            color: white !important;
            box-shadow: 0 15px 30px rgba(0, 78, 146, 0.3);
        }

        .nav-button i {
            font-size: 2.2rem;
            margin-bottom: 15px;
        }

        .nav-button span {
            font-size: 1.1rem;
            font-weight: bold;
            white-space: nowrap;
        }

        /* تحسين استجابة الأزرار في الموبايل */
        @media (max-width: 576px) {
            .nav-button {
                padding: 15px 10px;
                min-height: 130px;
            }
            .nav-button i {
                font-size: 1.8rem;
                margin-bottom: 10px;
            }
            .nav-button span {
                font-size: 0.95rem;
            }
            .main-container {
                padding: 30px 15px;
                border-radius: 25px;
            }
        }

        .theme-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            border: none;
            background: #0d6efd;
            color: white;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        body.dark-mode .theme-toggle {
            background: #ffce67;
            color: #000;
        }
    </style>
</head>

<body>

    <button class="theme-toggle shadow-lg" onclick="toggleGlobalTheme()" aria-label="تبديل الوضع">
        <i id="themeIcon" class="fas fa-moon fa-lg"></i>
    </button>

    <div class="main-container animate__animated animate__fadeIn">
        <div class="scale-icon animate__animated animate__bounceIn">
            <i class="fas fa-balance-scale"></i>
        </div>

        <h1 class="law-title">الشؤون القانونية</h1>
        <p class="sub-title">مديرية التربية والتعليم بمحافظة أسوان</p>

        <hr class="my-5 opacity-25">

        <div class="row g-3 g-md-4 justify-content-center">
            <div class="col-6 col-sm-4 col-md-4 col-lg-2">
                <a href="general.php" class="nav-button animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                    <i class="fas fa-table text-primary"></i>
                    <span>الجدول العام</span>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-4 col-lg-2">
                <a href="add.php" class="nav-button animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                    <i class="fas fa-plus-circle text-success"></i>
                    <span>إضافة دعوى</span>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-4 col-lg-2">
                <a href="stats.php" class="nav-button animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                    <i class="fas fa-chart-bar text-warning"></i>
                    <span>الإحصائيات</span>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-4 col-lg-2">
                <a href="settings.php" class="nav-button animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                    <i class="fas fa-cog text-secondary"></i>
                    <span>الإعدادات</span>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-4 col-lg-2">
                <a href="reports.php" class="nav-button animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
                    <i class="fa-solid fa-envelope text-primary"></i>
                    <span>التقارير</span>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-4 col-lg-2">
                <a href="logout.php" class="nav-button animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
                    <i class="fas fa-sign-out-alt text-danger"></i>
                    <span>خروج</span>
                </a>
            </div>
        </div>

        <div class="mt-5 pt-4 border-top opacity-50 small">            
            <i class="fas fa-shield-alt me-1"></i> منظومة الأرشيف القانوني الرقمي - مديرية أسوان <br><br>
            <i class="fa-solid fa-code me-1 mt-2 d-inline-block"></i> برمجة : المغازي منصور
        </div>
    </div>

    <script>
        function applyTheme(theme) {
            if (theme === 'dark') {
                document.body.classList.add('dark-mode');
                document.getElementById('themeIcon').className = 'fas fa-sun fa-lg';
            } else {
                document.body.classList.remove('dark-mode');
                document.getElementById('themeIcon').className = 'fas fa-moon fa-lg';
            }
        }

        function toggleGlobalTheme() {
            const newTheme = document.body.classList.contains('dark-mode') ? 'light' : 'dark';
            localStorage.setItem('global_theme', newTheme);
            applyTheme(newTheme);
        }
        applyTheme(localStorage.getItem('global_theme'));
    </script>
</body>

</html>