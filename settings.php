<?php 
require_once 'config.php'; 
protect(); 

// حماية الصفحة: لا يدخلها إلا الأدمن
if($_SESSION['role'] != 'admin') { header("Location: index.php"); exit(); }

$msg = "";

// 1. إضافة مستخدم جديد
if(isset($_POST['add_user'])){
    $u = mysqli_real_escape_string($conn, $_POST['username']);
    $p = mysqli_real_escape_string($conn, $_POST['password']); 
    $r = $_POST['role'];
    
    $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$u'");
    if(mysqli_num_rows($check) > 0){
        $msg = "<div class='alert alert-danger text-center shadow-sm animate__animated animate__shakeX'>اسم المستخدم موجود مسبقاً!</div>";
    } else {
        mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$u', '$p', '$r')");
        $msg = "<div class='alert alert-success text-center shadow-sm animate__animated animate__fadeIn'>تم إضافة المستخدم بنجاح.</div>";
    }
}

// 2. تعديل كلمة سر
if(isset($_POST['update_pass'])){
    $uid = $_POST['user_id'];
    $new_p = mysqli_real_escape_string($conn, $_POST['new_password']);
    if(!empty($new_p)){
        mysqli_query($conn, "UPDATE users SET password='$new_p' WHERE id='$uid'");
        $msg = "<div class='alert alert-info text-center shadow-sm animate__animated animate__fadeIn'>تم تحديث كلمة المرور بنجاح.</div>";
    }
}

// 3. حذف مستخدم
if(isset($_GET['delete'])){
    $did = $_GET['delete'];
    if($did != $_SESSION['user_id']){
        mysqli_query($conn, "DELETE FROM users WHERE id='$did'");
        header("Location: settings.php");
    }
}

// 4. كود النسخ الاحتياطي
if(isset($_POST['backup'])){
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="law_backup_'.date('Y-m-d').'.sql"');
    system("mysqldump -u root law_system"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <title>إعدادات النظام - الشؤون القانونية</title>
    <style>
        :root {
            --primary-blue: #004e92;
            --dark-blue: #000428;
            --light-bg: #f8f9fa;
        }
        body { background-color: var(--light-bg); font-family: 'Segoe UI', sans-serif; transition: all 0.3s; }
        
        /* تحسين الحاويات */
        .settings-container { max-width: 1000px; margin: auto; padding: 20px 15px; }
        
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); transition: 0.3s; }
        .card:hover { transform: translateY(-5px); }
        
        .btn-round { border-radius: 12px; padding: 8px 20px; font-weight: 600; }
        .form-control, .form-select { border-radius: 10px; padding: 10px; border: 1px solid #dee2e6; }
        
        /* تخصيص الهيدر */
        .card-header-custom {
            background: linear-gradient(45deg, var(--dark-blue), var(--primary-blue));
            color: white; border-radius: 20px 20px 0 0 !important; padding: 15px 20px;
        }

        /* الجداول التفاعلية */
        .table-responsive { border-radius: 15px; }
        .table thead { background-color: #f1f3f5; }

        /* تعديلات الموبايل */
        @media (max-width: 768px) {
            .header-section { flex-direction: column; text-align: center; gap: 15px; }
            .backup-section { text-align: center !important; }
            .backup-section .btn { width: 100%; margin-top: 15px; }
            .btn-round { width: 100%; }
        }
    </style>
</head>
<body>

    <div class="settings-container">
        <div class="header-section d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
            <div>
                <h3 class="fw-bold mb-0 text-dark"><i class="fas fa-user-shield me-2 text-primary"></i> إعدادات النظام والصلاحيات</h3>
                <small class="text-muted">لوحة تحكم المدير العام للمنظومة</small>
            </div>
            <a href="index.php" class="btn btn-outline-dark btn-round shadow-sm">
                <i class="fas fa-arrow-right me-1"></i> العودة للرئيسية
            </a>
        </div>

        <?php echo $msg; ?>

        <div class="card mb-4 animate__animated animate__fadeInUp">
            <div class="card-header-custom">
                <h5 class="mb-0"><i class="fas fa-database me-2"></i> صيانة قاعدة البيانات</h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center backup-section">
                    <div class="col-md-8">
                        <p class="mb-1 fw-bold">النسخ الاحتياطي الدوري (Backup)</p>
                        <p class="text-muted small mb-0">لحماية بيانات القضايا من الفقدان، يوصى بتحميل نسخة كل أسبوع وحفظها في مكان آمن خارج الجهاز.</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <form method="POST" class="d-inline">
                            <button name="backup" class="btn btn-warning fw-bold btn-round">
                                <i class="fas fa-download me-1"></i> تصدير SQL
                            </button>
                        </form>
                        <button class="btn btn-link btn-sm text-danger d-block w-100 mt-2 text-decoration-none" onclick="alert('للاسترداد: يرجى فتح phpMyAdmin واختيار Import للملف الذي قمت بتحميله.')">
                            <i class="fas fa-question-circle"></i> كيف أسترجع البيانات؟
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 animate__animated animate__fadeInLeft">
                <div class="card p-3 h-100 border-top border-primary border-5">
                    <h5 class="mb-4 text-primary fw-bold"><i class="fas fa-user-plus me-2"></i> مستخدم جديد</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">اسم المستخدم</label>
                            <input type="text" name="username" class="form-control shadow-sm" placeholder="أدخل الاسم..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">كلمة المرور</label>
                            <input type="password" name="password" class="form-control shadow-sm" placeholder="••••••••" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">نوع الصلاحية</label>
                            <select name="role" class="form-select shadow-sm">
                                <option value="user">👤 مستخدم (عرض وإضافة)</option>
                                <option value="admin">🔑 مدير (تحكم كامل)</option>
                            </select>
                        </div>
                        <button type="submit" name="add_user" class="btn btn-primary w-100 btn-round shadow">
                            تأكيد الإضافة
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8 animate__animated animate__fadeInRight">
                <div class="card p-3 h-100 border-top border-success border-5">
                    <h5 class="mb-4 text-success fw-bold"><i class="fas fa-users-cog me-2"></i> إدارة الحسابات</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>المستخدم</th>
                                    <th>الصلاحية</th>
                                    <th>تحديث السر</th>
                                    <th>إجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $res = mysqli_query($conn, "SELECT * FROM users");
                                while($row = mysqli_fetch_assoc($res)): ?>
                                <tr>
                                    <td class="fw-bold text-dark"><?php echo $row['username']; ?></td>
                                    <td>
                                        <?php echo ($row['role'] == 'admin') ? 
                                        '<span class="badge bg-danger rounded-pill px-3">مدير</span>' : 
                                        '<span class="badge bg-info text-dark rounded-pill px-3">مستخدم</span>'; ?>
                                    </td>
                                    <td>
                                        <form method="POST" class="d-flex justify-content-center">
                                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                            <div class="input-group input-group-sm" style="max-width: 150px;">
                                                <input type="text" name="new_password" class="form-control" placeholder="جديدة">
                                                <button type="submit" name="update_pass" class="btn btn-success"><i class="fas fa-check"></i></button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <?php if($row['id'] != $_SESSION['user_id']): ?>
                                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم نهائياً؟')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border">حسابك الحالي</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5 mb-3 text-muted">
        <small>© نظام الشؤون القانونية - مديرية التربية والتعليم بأسوان</small>
    </div>

</body>
</html>