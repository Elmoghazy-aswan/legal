<?php
include 'config.php';
protect();

$id = $_GET['id'];
$res = mysqli_query($conn, "SELECT * FROM lawsuits WHERE id=$id");
$data = mysqli_fetch_assoc($res);

if (!$data)
    die("الدعوى غير موجودة");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تفاصيل الدعوى | <?php echo $data['case_number']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --bg-page: #f4f7f6;
            --text-color: #212529;
            --card-bg: #ffffff;
            --accent: #0d6efd;
        }

        body.dark-mode {
            --bg-page: #0f111a;
            --text-color: #ffffff;
            --card-bg: #1a1d29;
            --accent: #4facfe;
        }

        body {
            background: var(--bg-page);
            color: var(--text-color);
            transition: 0.4s;
            font-family: 'Segoe UI', sans-serif;
        }

        .detail-card {
            background: var(--card-bg);
            border-radius: 25px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .info-label {
            font-weight: bold;
            color: var(--accent);
            font-size: 0.9rem;
            text-transform: uppercase;
            display: block;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 1.2rem;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(128, 128, 128, 0.1);
            padding-bottom: 10px;
        }

        .btn-back {
            border-radius: 50px;
            padding: 10px 30px;
        }

        body.dark-mode .info-value {
            color: #fff;
        }

        /* زر العودة للرئيسية الجذاب */
        .home-fab {
            position: fixed;
            top: 90px;
            /* تحت زر الوضع الليلي */
            left: 30px;
            width: 55px;
            height: 55px;
            background: var(--primary-grad);
            color: white !important;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 10px 20px rgba(0, 78, 146, 0.3);
            transition: 0.4s;
            z-index: 999;
        }

        .home-fab:hover {
            transform: scale(1.1);
            color: white;
        }

        @media print {
            .home-fab {
                display: none;
            }
        }
    </style>
</head>

<body class="p-4">
    <a href="index.php" class="home-fab shadow-lg" title="الرئيسية">
        <i class="fas fa-home fa-lg"></i>
    </a>
    <div class="container animate__animated animate__fadeIn">
        <div class="detail-card mx-auto" style="max-width: 800px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold m-0"><i class="fas fa-file-alt me-2 text-primary"></i> تفاصيل ملف القضية</h3>
                <button onclick="window.print()" class="btn btn-outline-secondary btn-sm"><i
                        class="fas fa-print"></i></button>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <span class="info-label">رقم الدعوى</span>
                    <div class="info-value"><?php echo $data['case_number']; ?></div>
                </div>
                <div class="col-md-6">
                    <span class="info-label">اسم المدعي</span>
                    <div class="info-value"><?php echo $data['plaintiff_name']; ?></div>
                </div>
                <div class="col-md-6">
                    <span class="info-label">الإدارة المختصة</span>
                    <div class="info-value"><?php echo $data['department']; ?></div>
                </div>
                <div class="col-md-6">
                    <span class="info-label">حالة القضية</span>
                    <div class="info-value"><?php echo $data['case_status']; ?></div>
                </div>
                <div class="col-12">
                    <span class="info-label">مرفق الـ PDF</span>
                    <div class="info-value">
                        <?php if ($data['attachment_path']): ?>
                            <a href="uploads/<?php echo $data['attachment_path']; ?>" target="_blank"
                                class="btn btn-danger btn-sm rounded-pill">
                                <i class="fas fa-file-pdf me-1"></i> فتح المرفق الإلكتروني
                            </a>
                        <?php else: ?>
                            <span class="text-muted">لا يوجد ملف مرفق</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="general.php" class="btn btn-primary btn-back shadow"><i class="fas fa-arrow-right me-2"></i>
                    العودة للجدول العام</a>
            </div>
        </div>
    </div>

    <script>
        if (localStorage.getItem('global_theme') === 'dark') document.body.classList.add('dark-mode');
    </script>
</body>

</html>