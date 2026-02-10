<?php 
include 'config.php'; 
protect(); 

// 1. جلب البيانات من قاعدة البيانات
$total_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM lawsuits");
$total = mysqli_fetch_assoc($total_res)['total'];

$done_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM lawsuits WHERE case_status='صدر الحكم'");
$done = mysqli_fetch_assoc($done_res)['total'];

$active_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM lawsuits WHERE case_status='متداولة'");
$active = mysqli_fetch_assoc($active_res)['total'];

$delayed_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM lawsuits WHERE case_status='وردت المطالبات القضائية'");
$delayed = mysqli_fetch_assoc($delayed_res)['total'];

// حساب النسبة المئوية للإنجاز
$percentage = ($total > 0) ? round(($done / $total) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إحصائيات النظام - الأرشيف الرقمي</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root { 
            --primary-grad: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            --glass: rgba(255, 255, 255, 0.9);
            --text-main: #2d3436;
            --muted-text: #636e72;
        }

        /* تعديل الوضع الليلي ليكون النص أبيض صريح */
        body.dark-mode {
            --glass: rgba(30, 33, 45, 0.95);
            --text-main: #ffffff; /* أبيض ناصع */
            --muted-text: #b2bec3; /* رمادي فاتح جداً للوصف */
            background-color: #0f111a;
        }

        body { 
            background-color: #f0f2f5; 
            color: var(--text-main); 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: 0.3s;
        }

        /* إجبار العناوين والنصوص على اتباع المتغير */
        h1, h2, h3, h4, h5, .fw-bold { color: var(--text-main) !important; }
        .text-muted { color: var(--muted-text) !important; }

        /* زر العودة الأنيق */
        .back-btn {
            position: fixed; top: 20px; left: 20px;
            width: 50px; height: 50px; background: var(--primary-grad);
            color: white !important; border-radius: 15px;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; z-index: 1000;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .back-btn:hover { transform: scale(1.1) rotate(-10deg); }

        /* كروت الإحصائيات */
        .stat-card {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            transition: 0.4s;
            height: 100%;
        }
        .stat-card:hover { transform: translateY(-10px); }

        .icon-box {
            width: 60px; height: 60px; border-radius: 15px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px; font-size: 1.5rem;
        }

        /* الرسوم البيانية */
        .chart-box {
            background: var(--glass);
            border-radius: 25px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }

        @media (max-width: 768px) {
            .container { padding-top: 60px; }
            .display-4 { font-size: 2rem; }
        }
    </style>
</head>
<body class="p-3">

    <a href="index.php" class="back-btn shadow" title="العودة للرئيسية">
        <i class="fas fa-arrow-left fa-lg"></i>
    </a>

    <div class="container py-4">
        <header class="text-center mb-5">
            <h1 class="fw-bold">لوحة التحليل الرقمي</h1>
            <p class="text-muted">متابعة حية لمؤشرات أداء القضايا والدعاوى</p>
        </header>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h3 class="fw-bold"><?php echo $total; ?></h3>
                    <p class="text-muted mb-0">إجمالي الملفات</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="icon-box bg-success bg-opacity-10 text-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="fw-bold text-success"><?php echo $done; ?></h3>
                    <p class="text-muted mb-0">قضايا منتهية</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="fw-bold text-warning"><?php echo $active; ?></h3>
                    <p class="text-muted mb-0">تحت التداول</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="icon-box bg-danger bg-opacity-10 text-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="fw-bold text-danger"><?php echo $delayed; ?></h3>
                    <p class="text-muted mb-0">قضايا مؤجلة</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="chart-box">
                    <h5 class="fw-bold mb-4"><i class="fas fa-chart-bar me-2"></i>تحليل توزيع القضايا</h5>
                    <canvas id="barChart" height="200"></canvas>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="chart-box text-center">
                    <h5 class="fw-bold mb-4"><i class="fas fa-percentage me-2"></i>نسبة الإنجاز العام</h5>
                    <div style="position: relative; height:250px;">
                        <canvas id="doughnutChart"></canvas>
                        <div style="position: absolute; top:55%; left:50%; transform:translate(-50%, -50%);">
                            <h2 class="fw-bold mb-0"><?php echo $percentage; ?>%</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // مزامنة المظهر
        if(localStorage.getItem('global_theme') === 'dark') document.body.classList.add('dark-mode');

        const isDark = document.body.classList.contains('dark-mode');
        // تعديل لون الخط في الرسوم البيانية ليكون أبيض في الوضع الليلي
        const textColor = isDark ? '#ffffff' : '#2d3436';

        // الرسم البياني للأعمدة
        const ctxBar = document.getElementById('barChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['صدر الحكم', 'متداولة', 'وردت المطالبات القضائية'],
                datasets: [{
                    label: 'عدد القضايا',
                    data: [<?php echo "$done, $active, $delayed"; ?>],
                    backgroundColor: ['#2ecc71', '#3498db', '#e74c3c'],
                    borderRadius: 10
                }]
            },
            options: {
                responsive: true,
                plugins: { 
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)' },
                        ticks: { color: textColor } 
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: textColor } 
                    }
                }
            }
        });

        // الرسم البياني الدائري
        const ctxDoughnut = document.getElementById('doughnutChart').getContext('2d');
        new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: ['مكتمل', 'متبقي'],
                datasets: [{
                    data: [<?php echo $percentage; ?>, <?php echo (100 - $percentage); ?>],
                    backgroundColor: ['#1e3c72', isDark ? '#2d3436' : '#ecf0f1'],
                    borderWidth: 0,
                    cutout: '80%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false }
                }
            }
        });
    </script>
</body>
</html>