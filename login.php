<?php
require_once 'config.php';

// إذا كان المستخدم مسجل دخوله بالفعل، يتم تحويله للرئيسية
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if (isset($_POST['login_btn'])) {
    $input_user = mysqli_real_escape_string($conn, $_POST['user']);
    $input_pass = $_POST['pass']; // كلمة المرور المدخلة

    // جلب بيانات المستخدم بناءً على الاسم
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $input_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // التحقق من كلمة المرور (نص واضح كما طلبت للمرحلة الحالية)
        if ($input_pass === $user['password']) {
            // تخزين البيانات في الجلسة (Sessions)
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role']; // هام جداً لمنع خطأ الـ Undefined key
            
            header("Location: index.php");
            exit();
        } else {
            $error = "كلمة المرور غير صحيحة!";
        }
    } else {
        $error = "اسم المستخدم غير موجود بالنظام!";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام القضايا</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; height: 100vh; display: flex; align-items: center; }
        .login-card { border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .btn-login { border-radius: 10px; padding: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-8 col-md-6 col-lg-4">
                <div class="card login-card p-4">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold">تسجيل الدخول</h3>
                        <p class="text-muted small">يرجى إدخال البيانات للوصول للنظام</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger py-2 text-center small"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">اسم المستخدم</label>
                            <input type="text" name="user" class="form-control" placeholder="مثلاً: admin" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">كلمة المرور</label>
                            <input type="password" name="pass" class="form-control" placeholder="أدخل كلمة السر" required>
                        </div>
                        <button type="submit" name="login_btn" class="btn btn-primary w-100 btn-login">دخول للنظام</button>
                    </form>
                </div>
                <p class="text-center mt-4 text-muted small">&copy; <?php echo date('Y'); ?> نظام متابعة القضايا</p>
            </div>
        </div>
    </div>
</body>
</html>