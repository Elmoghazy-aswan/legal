<?php
include 'config.php';
protect();

$id = $_GET['id'];
$res = mysqli_query($conn, "SELECT * FROM lawsuits WHERE id=$id");
$data = mysqli_fetch_assoc($res);

if (isset($_POST['update'])) {
    $case_num = mysqli_real_escape_string($conn, $_POST['case_number']);
    $name = mysqli_real_escape_string($conn, $_POST['plaintiff_name']);
    $dept = mysqli_real_escape_string($conn, $_POST['department']);
    $status = mysqli_real_escape_string($conn, $_POST['case_status']);

    $q = "UPDATE lawsuits SET 
          case_number='$case_num', 
          plaintiff_name='$name', 
          department='$dept', 
          case_status='$status' 
          WHERE id=$id";

    if (mysqli_query($conn, $q)) {
        echo "<script>alert('تم تحديث البيانات بنجاح'); window.location='general.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل الدعوى</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f7f6; }
        .card { border-radius: 15px; }
        .btn-update { background: #27ae60; color: white; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="card p-4 mx-auto" style="max-width: 800px;">
            <h3 class="text-center mb-4">تعديل بيانات الدعوى</h3>
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label>رقم الدعوى</label>
                        <input type="text" name="case_number" class="form-control" value="<?php echo $data['case_number']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>اسم المدعي</label>
                        <input type="text" name="plaintiff_name" class="form-control" value="<?php echo $data['plaintiff_name']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>الإدارة المختصة</label>
                        <input type="text" name="department" class="form-control" value="<?php echo $data['department']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>حالة الدعوى الحالية</label>
                        <select name="case_status" class="form-select">
                            <option value="متداولة" <?php if ($data['case_status'] == 'متداولة') echo 'selected'; ?>>متداولة</option>
                            <option value="صدر الحكم" <?php if ($data['case_status'] == 'صدر الحكم') echo 'selected'; ?>>صدر الحكم</option>
                            <option value="وردت المطالبات القضائية" <?php if ($data['case_status'] == 'وردت المطالبات القضائية') echo 'selected'; ?>>وردت المطالبات القضائية</option>
                        </select>
                    </div>
                </div>
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" name="update" class="btn btn-update">تحديث وإرسال للأرشيف</button>
                    <a href="general.php" class="btn btn-light rounded-pill text-center">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>