<?php
require_once 'config.php';
header('Content-Type: application/json');

// دالة لتنظيف بيانات الإدخال
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'register':
            // معالجة التسجيل
            $name = clean_input($_POST['name']);
            $email = clean_input($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            
            // التحقق من عدم وجود البريد الإلكتروني مسبقاً
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => false, 'message' => 'البريد الإلكتروني مسجل مسبقاً']);
                exit;
            }
            
            // إدخال المستخدم الجديد
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $password]);
            
            echo json_encode(['success' => true, 'message' => 'تم التسجيل بنجاح']);
            break;
            
        case 'login':
            // معالجة تسجيل الدخول
            $email = clean_input($_POST['email']);
            $password = $_POST['password'];
            
            $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() === 0) {
                echo json_encode(['success' => false, 'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة']);
                exit;
            }
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $user['password'])) {
                // بدء الجلسة
                session_start();
                $_SESSION['user_id'] = $user['id'];
                
                echo json_encode(['success' => true, 'message' => 'تم تسجيل الدخول بنجاح']);
            } else {
                echo json_encode(['success' => false, 'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'إجراء غير معروف']);
    }
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'حدث خطأ في الخادم: ' . $e->getMessage()]);
}
?>