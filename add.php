<?php 
include 'config.php'; 
protect(); 

if(isset($_POST['save'])) {
    $case_num = mysqli_real_escape_string($conn, $_POST['case_number']);
    $name = mysqli_real_escape_string($conn, $_POST['plaintiff_name']);
    $dept = mysqli_real_escape_string($conn, $_POST['department']); 
    $status = mysqli_real_escape_string($conn, $_POST['case_status']);
    
    $file_name = "";
    if(!empty($_FILES['pdf_file']['name'])) {
        $file_name = time() . "_" . $_FILES['pdf_file']['name'];
        if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
        move_uploaded_file($_FILES['pdf_file']['tmp_name'], "uploads/" . $file_name);
    }

    $q = "INSERT INTO lawsuits (case_number, plaintiff_name, department, case_status, attachment_path) 
          VALUES ('$case_num', '$name', '$dept', '$status', '$file_name')";
    
    if(mysqli_query($conn, $q)) {
        header("Location: general.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة دعوى جديدة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body { background: #f4f7f6; font-family: 'Segoe UI', sans-serif; transition: 0.3s; }
        .card-add { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-top: 50px; background: #fff; }
        .form-label { font-weight: 600; color: #444; }
        .btn-save { background: linear-gradient(45deg, #1e3c72, #2a5298); color: white; border-radius: 50px; padding: 12px; border: none; font-weight: bold; }
        
        /* إصلاح تباين الوضع الليلي */
        body.dark-mode { background: #0f111a; color: #e1e1e1; }
        body.dark-mode .card-add { background: #1a1d29; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        body.dark-mode .form-label { color: #fff; }
        body.dark-mode .form-control, body.dark-mode .form-select { background: #24283b; border-color: #3d4455; color: #fff; }
        body.dark-mode .form-control::placeholder { color: #888; }
        body.dark-mode h3 { color: #fff !important; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card card-add p-4 mx-auto animate__animated animate__fadeInUp" style="max-width: 750px;">
            <h3 class="text-center mb-4 fw-bold"><i class="fas fa-plus-circle me-2 text-primary"></i> إضافة قضية جديدة</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">رقم الدعوى</label>
                        <input type="text" name="case_number" class="form-control" placeholder="مثال: 123 لسنة 2024" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">اسم المدعي</label>
                        <input type="text" name="plaintiff_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">الإدارة المختصة</label>
                        <select name="department" class="form-select" required>
                            <option value="إدارة أسوان التعليمية">إدارة أسوان التعليمية</option>
                            <option value="إدارة دراو التعليمية">إدارة دراو التعليمية</option>
                            <option value="إدارة كوم أمبو التعليمية">إدارة كوم أمبو التعليمية</option>
                            <option value="إدارة نصر النوبة التعليمية">إدارة نصر النوبة التعليمية</option>
                            <option value="إدارة إدفو التعليمية">إدارة إدفو التعليمية</option>
                            <option value="ديوان المديرية">ديوان المديرية</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">حالة القضية</label>
                        <select name="case_status" class="form-select">
                            <option value="متداولة">متداولة</option>
                            <option value="صدر الحكم">صدر الحكم</option>
                            <option value="وردت المطالبات القضائية">وردت المطالبات القضائية</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">الملف المرفق (PDF)</label>
                        <input type="file" name="pdf_file" class="form-control" accept=".pdf">
                    </div>
                </div>
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" name="save" class="btn btn-save">حفظ البيانات</button>
                    <a href="general.php" class="btn btn-link text-decoration-none">عودة</a>
                </div>
            </form>
        </div>
    </div>
    <script>if(localStorage.getItem('global_theme') === 'dark') document.body.classList.add('dark-mode');</script>
</body>
</html>