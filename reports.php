<?php 
include 'config.php'; 
if(function_exists('protect')) { protect(); } 

// 1. جلب الفلاتر مع قيم افتراضية
$dept_filter = $_POST['dept_filter'] ?? 'ديوان المديرية';
$date = $_POST['rep_date'] ?? date('Y-m-d');

// 2. تحضير متغيرات الاستعلام بصيغة مرنة
$search_date = date('Y-m-d', strtotime($date));
$dept_param = "%" . $dept_filter . "%";

// 3. جلب البيانات
$sql = "SELECT * FROM lawsuits WHERE department LIKE ? AND DATE(created_at) = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $dept_param, $search_date); 
$stmt->execute();
$res = $stmt->get_result();
$count = $res->num_rows;

// دالة لتحويل الأرقام الإنجليزية إلى أرقام عربية (اختياري إذا لم يقم الخط بذلك تلقائياً)
function convertToArabicNumbers($number) {
    $en = array("0","1","2","3","4","5","6","7","8","9");
    $ar = array("٠","١","٢","٣","٤","٥","٦","٧","٨","٩");
    return str_replace($en, $ar, $number);
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير القضايا - مديرية التربية والتعليم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    
    <style>
        * {
            font-family: 'Cairo', sans-serif !important;
        }
        body { 
            background-color: #f4f7f6; 
            /* هذا السطر يضمن ظهور الأرقام بالشكل العربي في بعض المتصفحات */
            font-variant-numeric: lining-nums; 
        }
        .report-card { 
            background: white; 
            border: 2px solid #000; 
            padding: 50px; 
            margin-top: 20px; 
            min-height: 297mm; /* قياس A4 تقريباً */
        }
        .header-box { border-bottom: 3px double #000; padding-bottom: 15px; margin-bottom: 30px; }
        .table-bordered border-dark th, .table-bordered border-dark td { 
            border: 1px solid #000 !important; 
            vertical-align: middle;
            font-size: 1.1rem;
        }
        .stamp-box {
            border: 1px solid #000;
            padding: 10px;
            display: inline-block;
            margin-top: 10px;
        }
        @media print {
            .no-print { display: none !important; }
            body { background: white; padding: 0; }
            .container { max-width: 100% !important; width: 100% !important; }
            .report-card { border: none; padding: 20px; margin: 0; }
        }
    </style>
</head>
<body>

    <div class="container mb-5">
        <div class="card no-print mt-4 shadow-sm border-0">
            <div class="card-body bg-light">
                <form method="POST" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">الإدارة المختصة:</label>
                        <select name="dept_filter" class="form-select">
                            <?php 
                            $depts = ["ديوان المديرية", "إدارة أسوان التعليمية", "إدارة دراو التعليمية", "إدارة كوم أمبو التعليمية", "إدارة نصر النوبة التعليمية", "إدارة إدفو التعليمية"];
                            foreach($depts as $d) {
                                $selected = ($dept_filter == $d) ? 'selected' : '';
                                echo "<option value='$d' $selected>$d</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">تاريخ الورود:</label>
                        <input type="date" name="rep_date" class="form-control" value="<?php echo $date; ?>">
                    </div>
                    <div class="col-md-5">
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-sync"></i> تحديث</button>
                        <button type="button" onclick="window.print()" class="btn btn-success px-4"><i class="fas fa-print"></i> طباعة بالعربي</button>
                        <a href="general.php" class="btn btn-outline-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="report-card">
            <div class="header-box">
                <div class="row">
                    <div class="col-4 text-center">
                        <h6 class="fw-bold">محافظة أسوان</h6>
                        <h6 class="fw-bold">مديرية التربية والتعليم</h6>
                        <h6 class="fw-bold">الشئون القانونية</h6>
                    </div>
                    <div class="col-4 text-center">
                        <br>
                        <h3 class="fw-bold text-decoration-underline">مذكرة عرض</h3>
                    </div>
                    <div class="col-4 text-center">
                        <p>تاريخ التحریر: <?php echo convertToArabicNumbers(date('Y/m/d')); ?></p>
                    </div>
                </div>
            </div>

            <div class="report-body mt-4">
                <h5 class="fw-bold mb-4">السيد الأستاذ/ مدير عام <?php echo $dept_filter; ?></h5>
                <p class="fs-5 text-center mb-4">تحية طيبة وبعد،،،</p>
                <p class="fs-5 mb-4" style="line-height: 1.8; text-indent: 40px;">
                    نحيط سيادتكم علماً بأنه تم تسجيل القضايا / الطلبات الموضحة أدناه ب سجلاتنا بتاريخ 
                    <strong><?php echo convertToArabicNumbers(date('Y/m/d', strtotime($search_date))); ?></strong> 
                    وهي كالتالي:
                </p>

                <table class="table table-bordered border-dark text-center">
                    <thead class="table-secondary">
                        <tr>
                            <th style="width: 8%;">م</th>
                            <th style="width: 22%;">رقم القضية</th>
                            <th style="width: 45%;">اسم الخصم (المدعي)</th>
                            <th style="width: 25%;">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($count > 0): $i=1; while($row = $res->fetch_assoc()): ?>
                            <tr>
                                <td class="fw-bold"><?php echo convertToArabicNumbers($i++); ?></td>
                                <td><?php echo convertToArabicNumbers($row['case_number']); ?></td>
                                <td><?php echo $row['plaintiff_name']; ?></td>
                                <td><?php echo $row['case_status']; ?></td>
                            </tr>
                        <?php endwhile; else: ?>
                            <tr>
                                <td colspan="4" class="py-5 fs-5 text-muted">لا توجد قضايا مسجلة لهذا التاريخ</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <p class="fs-6 mt-4">برجاء التفضل بالاطلاع واتخاذ اللازم قانوناً.</p>
            </div>

            <div class="report-footer mt-5 pt-5">
                <div class="row text-center fw-bold">
                    <div class="col-4">
                        <p>عضو قانوني</p>
                        <p class="mt-4">................</p>
                    </div>
                    <div class="col-4">
                        <p>رئيس القسم</p>
                        <p class="mt-4">................</p>
                    </div>
                    <div class="col-4">
                        <p>مدير الشئون القانونية</p>
                        <p class="mt-4">................</p>
                    </div>
                </div>
                
                <div class="text-center mt-5">
                    <div class="stamp-box">
                        <p class="fw-bold mb-5">يعتمد،، مدير المديرية</p>
                        <p>................................</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>