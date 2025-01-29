<?php
// admin/student/save-tab-state.php
session_start();
if (isset($_GET['tab'])) {
    $_SESSION['active_tab'] = $_GET['tab'];
}
?>