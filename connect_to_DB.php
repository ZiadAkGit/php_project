<?php
$servername = "localhost"; // כתובת השרת
$username = "root";        // שם משתמש (ברירת מחדל ל-MySQL: root)
$password = "";            // סיסמה (ברירת מחדל ל-MySQL: ריק)
$dbname = "watch_project";       // שם מסד הנתונים

// יצירת חיבור
$conn = new mysqli($servername, $username, $password, $dbname);

// בדיקת חיבור
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
