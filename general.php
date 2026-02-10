<?php
include 'config.php';
protect();

// معالجة عملية الحذف إذا تم طلبها
if (isset($_GET['delete_id'])) {
    $id_to_delete = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_query = "DELETE FROM lawsuits WHERE id = '$id_to_delete'";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: general.php?msg=deleted");
        exit();
    }
}

// جلب الإحصائيات بالمسميات الجديدة
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM lawsuits"))['c'];
$active = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM lawsuits WHERE case_status='متداولة'"))['c'];
$done = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM lawsuits WHERE case_status='صدر الحكم' OR case_status LIKE 'منتهية%'"))['c'];
$claims = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM lawsuits WHERE case_status='وردت المطالبات القضائية' OR case_status='مؤجلة'"))['c'];

// جلب جميع البيانات للجدول
$res = mysqli_query($conn, "SELECT * FROM lawsuits ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>الأرشيف القانوني | مديرية التربية والتعليم بأسوان</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            /* ... التنسيقات الموجودة مسبقاً ... */
            font-variant-numeric: tabular-nums;
            -moz-font-feature-settings: "tnum";
            -webkit-font-feature-settings: "tnum";
            font-family: 'Cairo', sans-serif !important;
        }

        /* هذا الجزء هو المسؤول عن تحويل شكل الأرقام للغة العربية في المتصفحات التي تدعم الخصائص المحلية */
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* تأكد من استخدام خط يدعم الأرقام العربية */
        }

        :root {
            --primary-color: #1e3c72;
            --secondary-color: #2a5298;
        }

        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: all 0.3s;
        }

        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-bottom: 3px solid #f39c12;
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            transition: 0.3s;
            border-right: 5px solid;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .bg-dark-card {
            background: #1e1e1e !important;
            color: white !important;
        }

        .table-container {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        body.dark-mode .table-container {
            background: #1e1e1e;
            color: white;
        }

        .badge-status {
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .btn-action {
            width: 35px;
            height: 35px;
            padding: 0;
            line-height: 35px;
            border-radius: 10px;
        }
    </style>
    <style>
        :root {
            --primary-color: #1e3c72;
            --secondary-color: #2a5298;
        }

        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: all 0.3s;
        }

        /* --- تحسينات التباين للوضع الليلي --- */
        body.dark-mode {
            background-color: #121212;
            color: #ffffff;
        }

        /* تباين نصوص الجدول في الوضع الليلي */
        body.dark-mode .table {
            color: #ffffff !important;
        }

        body.dark-mode .table-hover tbody tr:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.05);
        }

        body.dark-mode .text-muted {
            color: #b0b0b0 !important;
        }

        /* تفتيح النصوص الباهتة */
        body.dark-mode td.text-muted.small {
            color: #d1d1d1 !important;
        }

        /* أرقام المسلسل */

        /* تباين الكروت والحاويات */
        body.dark-mode .table-container {
            background: #1e1e1e;
            color: #ffffff;
            border: 1px solid #333;
        }

        .bg-dark-card {
            background: #1e1e1e !important;
            color: white !important;
        }

        /* تحسين تباين الشارات (Badges) */
        body.dark-mode .badge.bg-light {
            background-color: #333 !important;
            color: #fff !important;
            border-color: #444 !important;
        }

        /* تنسيقات عامة */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-bottom: 3px solid #f39c12;
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            transition: 0.3s;
            border-right: 5px solid;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .table-container {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .badge-status {
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .btn-action {
            width: 35px;
            height: 35px;
            padding: 0;
            line-height: 35px;
            border-radius: 10px;
        }

        /* تحسين تباين أزرار الإجراءات في الوضع الليلي */
        body.dark-mode .btn-outline-info {
            color: #0dcaf0;
            border-color: #0dcaf0;
        }

        body.dark-mode .btn-outline-warning {
            color: #ffc107;
            border-color: #ffc107;
        }

        body.dark-mode .btn-outline-danger {
            color: #ea868f;
            border-color: #ea868f;
        }
    </style>
    <style>
        :root {
            --primary-color: #1e3c72;
            --secondary-color: #2a5298;
        }

        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: all 0.3s;
        }

        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }

        /* التعديل المطلوب: زيادة تباين رقم المسلسل في الوضع الليلي */
        body.dark-mode td.text-muted {
            color: #ffffff !important;
            font-weight: bold;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-bottom: 3px solid #f39c12;
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            transition: 0.3s;
            border-right: 5px solid;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .bg-dark-card {
            background: #1e1e1e !important;
            color: white !important;
        }

        .table-container {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        body.dark-mode .table-container {
            background: #1e1e1e;
            color: white;
        }

        .badge-status {
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .btn-action {
            width: 35px;
            height: 35px;
            padding: 0;
            line-height: 35px;
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <div>
        <a href="index.php" class="btn btn-outline-secondary me-2 rounded-pill"><i class="fas fa-home me-1"></i>
            الرئيسية</a>
        <a href="stats.php" class="btn btn-outline-primary me-2 rounded-pill"><i class="fas fa-chart-pie me-1"></i>
            الإحصائيات</a>
        <a href="add.php" class="btn btn-primary rounded-pill px-4 shadow"><i class="fas fa-plus-circle me-1"></i> إضافة
            دعوى جديدة</a>
    </div>

    <nav class="navbar navbar-dark shadow-sm no-print">
        <div class="container-fluid px-4">
            <span class="navbar-brand fw-bold mb-0 h1">
                <i class="fas fa-balance-scale-left me-2"></i> منظومة الأرشيف القانوني الرقمي
            </span>
            <div class="d-flex align-items-center">
                <button onclick="toggleGlobalTheme()" class="btn btn-link text-light me-3 no-print">
                    <i id="themeIcon" class="fas fa-moon fa-lg"></i>
                </button>
                <a href="logout.php" class="btn btn-danger btn-sm rounded-pill px-3"><i class="fas fa-sign-out-alt"></i>
                    خروج</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4 px-4">

        <div class="row g-3 mb-4 no-print">
            <div class="col-md-3">
                <div class="card stat-card shadow-sm p-3" style="border-color: #17a2b8;">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info bg-opacity-10 p-3 rounded-circle"><i
                                class="fas fa-folder-open fa-2x text-info"></i></div>
                        <div class="flex-grow-1 ms-3 text-end">
                            <h6 class="text-muted mb-0">إجمالي القضايا</h6>
                            <h3 class="mb-0 fw-bold"><?php echo $total; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card shadow-sm p-3" style="border-color: #0d6efd;">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-circle"><i
                                class="fas fa-gavel fa-2x text-primary"></i></div>
                        <div class="flex-grow-1 ms-3 text-end">
                            <h6 class="text-muted mb-0">متداولة</h6>
                            <h3 class="mb-0 fw-bold"><?php echo $active; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card shadow-sm p-3" style="border-color: #198754;">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded-circle"><i
                                class="fas fa-check-double fa-2x text-success"></i></div>
                        <div class="flex-grow-1 ms-3 text-end">
                            <h6 class="text-muted mb-0">صدر الحكم</h6>
                            <h3 class="mb-0 fw-bold"><?php echo $done; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card shadow-sm p-3" style="border-color: #ffc107;">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 p-3 rounded-circle"><i
                                class="fas fa-file-signature fa-2x text-warning"></i></div>
                        <div class="flex-grow-1 ms-3 text-end">
                            <h6 class="text-muted mb-0">مطالبات قضائية</h6>
                            <h3 class="mb-0 fw-bold"><?php echo $claims; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-container shadow-sm animate__animated animate__fadeIn">
            <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                <h4 class="mb-0 fw-bold"><i class="fas fa-list-ol me-2 text-primary"></i>سجل الدعاوى القضائية</h4>
                <div>
                    <a href="stats.php" class="btn btn-outline-primary me-2 rounded-pill"><i
                            class="fas fa-chart-pie me-1"></i> الإحصائيات</a>
                    <a href="add.php" class="btn btn-primary rounded-pill px-4 shadow"><i
                            class="fas fa-plus-circle me-1"></i> إضافة دعوى جديدة</a>
                </div>
            </div>

            <table id="smartTable" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">م</th>
                        <th>رقم الدعوى</th>
                        <th>اسم المدعي</th>
                        <th>الإدارة</th>
                        <th>الموقف القضائي</th>
                        <th class="no-print" style="width: 120px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $serial = 1;
                    while ($row = mysqli_fetch_assoc($res)):
                        $status = $row['case_status'];
                        $badge_class = "bg-secondary";
                        if ($status == 'متداولة')
                            $badge_class = "bg-primary";
                        elseif ($status == 'صدر الحكم' || strpos($status, 'منتهية') !== false)
                            $badge_class = "bg-success";
                        elseif ($status == 'وردت المطالبات القضائية' || $status == 'مؤجلة')
                            $badge_class = "bg-warning text-dark";
                        ?>
                        <tr>
                            <td class="text-muted small"><?php echo $serial++; ?></td>
                            <td class="fw-bold text-primary"><?php echo $row['case_number']; ?></td>
                            <td><?php echo $row['plaintiff_name']; ?></td>
                            <td><span class="badge bg-light text-dark border"><?php echo $row['department']; ?></span></td>
                            <td>
                                <span class="badge badge-status <?php echo $badge_class; ?>">
                                    <?php echo $status; ?>
                                </span>
                            </td>
                            <td class="no-print">
                                <div class="d-flex gap-1">
                                    <a href="view.php?id=<?php echo $row['id']; ?>" class="btn btn-action btn-outline-info"
                                        title="عرض"><i class="fas fa-eye"></i></a>
                                    <a href="edit.php?id=<?php echo $row['id']; ?>"
                                        class="btn btn-action btn-outline-warning" title="تعديل"><i
                                            class="fas fa-edit"></i></a>
                                    <a href="general.php?delete_id=<?php echo $row['id']; ?>"
                                        class="btn btn-action btn-outline-danger" title="حذف"
                                        onclick="return confirm('هل أنت متأكد من حذف هذه الدعوى نهائياً؟ لا يمكن التراجع عن هذا الإجراء.');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

    <script>

        function applyTheme(theme) {
            if (theme === 'dark') {
                document.body.classList.add('dark-mode');
                document.querySelectorAll('.stat-card').forEach(c => c.classList.add('bg-dark-card'));
                document.getElementById('themeIcon').className = 'fas fa-sun fa-lg';
            } else {
                document.body.classList.remove('dark-mode');
                document.querySelectorAll('.stat-card').forEach(c => c.classList.remove('bg-dark-card'));
                document.getElementById('themeIcon').className = 'fas fa-moon fa-lg';
            }
        }

        function toggleGlobalTheme() {
            const currentTheme = localStorage.getItem('global_theme') === 'dark' ? 'light' : 'dark';
            localStorage.setItem('global_theme', currentTheme);
            applyTheme(currentTheme);
        }

        applyTheme(localStorage.getItem('global_theme'));

        $(document).ready(function () {
            $('#smartTable').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json' },
                dom: '<"d-flex justify-content-between no-print"Bf>rt<"d-flex justify-content-between no-print"ip>',
                buttons: [
                    { extend: 'excelHtml5', text: '<i class="fas fa-file-excel"></i> اكسيل', className: 'btn btn-outline-success btn-sm shadow-sm' },
                    { extend: 'print', text: '<i class="fas fa-print"></i> طباعة', className: 'btn btn-outline-dark btn-sm shadow-sm' }
                ],
                pageLength: 10,
                order: [[0, 'asc']]
            });
        });
    </script>
</body>

</html>