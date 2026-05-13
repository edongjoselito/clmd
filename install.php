<?php
/**
 * CLMD - DepEd Region XI installer
 *
 * Usage:  http://localhost/clmd/install.php
 *
 * - Creates the `clmd_db` database
 * - Imports sql/clmd_db.sql
 * - Creates default regional admin (username: admin / password: admin123)
 *
 * Delete this file after a successful install.
 */

$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = 'moth34board';
$DB_NAME = 'clmd_db';
$SQL_FILE = __DIR__ . '/sql/clmd_db.sql';

$ADMIN_USER = 'admin';
$ADMIN_PASS = 'admin123';
$ADMIN_NAME = 'CLMD Regional Administrator';
$ADMIN_MAIL = 'clmd@region11.deped.gov.ph';

header('Content-Type: text/html; charset=utf-8');
echo "<!doctype html><meta charset=utf-8><title>CLMD Installer</title>";
echo "<style>body{font-family:system-ui;max-width:760px;margin:30px auto;padding:0 16px;}
       .ok{color:#198754}.err{color:#dc3545}.box{background:#f4f6fb;padding:14px;border-radius:8px;margin:10px 0}
       code{background:#fff;padding:2px 6px;border-radius:4px}</style>";
echo "<h2>CLMD - DepEd Region XI &mdash; Installer</h2>";

function out($msg, $cls = '') { echo "<div class='box $cls'>$msg</div>"; @ob_flush(); flush(); }

if (!file_exists($SQL_FILE)) {
    out("SQL file not found: <code>$SQL_FILE</code>", 'err');
    exit;
}

$mysqli = @new mysqli($DB_HOST, $DB_USER, $DB_PASS);
if ($mysqli->connect_errno) {
    out("MySQL connection failed: " . htmlspecialchars($mysqli->connect_error), 'err');
    exit;
}
$mysqli->set_charset('utf8mb4');
out("Connected to MySQL.", 'ok');

$sql = file_get_contents($SQL_FILE);
if ($mysqli->multi_query($sql)) {
    do {
        if ($res = $mysqli->store_result()) { $res->free(); }
    } while ($mysqli->more_results() && $mysqli->next_result());
}
if ($mysqli->errno) {
    out("Schema import error: " . htmlspecialchars($mysqli->error), 'err');
    exit;
}
out("Database <code>$DB_NAME</code> and tables created.", 'ok');

$mysqli->select_db($DB_NAME);

$stmt = $mysqli->prepare("SELECT user_id FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $ADMIN_USER);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    $hash = password_hash($ADMIN_PASS, PASSWORD_DEFAULT);
    $role = 'regional';
    $pos  = 'CLMD Chief';
    $ins = $mysqli->prepare(
        "INSERT INTO users (username, password, full_name, email, role, position)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $ins->bind_param('ssssss', $ADMIN_USER, $hash, $ADMIN_NAME, $ADMIN_MAIL, $role, $pos);
    if ($ins->execute()) {
        out("Default admin created &mdash; username: <code>$ADMIN_USER</code>, password: <code>$ADMIN_PASS</code>", 'ok');
    } else {
        out("Failed to create admin: " . htmlspecialchars($ins->error), 'err');
    }
    $ins->close();
} else {
    $stmt->close();
    out("Admin user already exists. Skipped.", 'ok');
}

out("Setup complete. <a href='" . htmlspecialchars(dirname($_SERVER['REQUEST_URI'])) . "/'>Go to login &rarr;</a>", 'ok');
out("<strong>Important:</strong> delete <code>install.php</code> after install.", 'err');

$mysqli->close();
