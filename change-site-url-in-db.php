<h1>Change url of site in DB</h1>
<?php
$action = $_POST['action'];
$old = trim($_POST['old']);
$new = trim($_POST['new']);
if ($action && $old != '' && $new != '') {
    ?>
    <p>Changing old url: <strong><?php echo $old; ?></strong> to new url: <strong><?php echo $new; ?></strong></p>
    <p><a href="">Start new</a></p>
    <?php
    $DBHOST = "vto_db";
    $DBNAME = "vtodb";
    $DBUSER = "vtouser";
    $DBPASS = "123123";
    $conn = new mysqli($DBHOST, $DBUSER, $DBPASS, $DBNAME);
    if (mysqli_connect_error()) {
        die("Database connection failed: " . mysqli_connect_error());
    } else {
        echo "<p>Connected successfully</p>";
        $tables = array_column(mysqli_fetch_all($conn->query("SHOW TABLES")), 0);
        foreach ($tables as $table) {
            echo "<h4>" . $table . "</h4>";
            $columns_request = $conn->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . $DBNAME . "' AND TABLE_NAME = '" . $table . "'");
            $result = [];
            while ($row = $columns_request->fetch_assoc()) {
                $result[] = $row;
            }
            $columns = array_column($result, 'COLUMN_NAME');
            echo "<ul>";
            foreach ($columns as $column) {
                echo "<li>" . $column;
                $update = $conn->query("UPDATE `" . $table . "` SET `" . $column . "` = replace(`" . $column . "`, '" . $old . "', '" . $new . "');");
                if($update === TRUE) {
                    echo "<div style='font-size: 12px;'>UPDATE `" . $table . "` SET `" . $column . "` = replace(`" . $column . "`, '" . $old . "', '" . $new . "');</div>";
                }
                echo "</li>";
            }
            echo "</ul>";
        }
    }
    $conn->close();
    echo "<p>Connected closed</p>";
} else {
    ?>
    <form action="" method="post">
        <input type="hidden" value="true" name="action">
        <div>
            <input name="old" type="text" value="<?php echo $old; ?>" placeholder="old url of site"> <?php if ($action && $old == '') echo '<== empty'; ?>
        </div>
        <div>
            <input name="new" type="text" value="<?php echo $new; ?>" placeholder="new url of site"> <?php if ($action && $new == '') echo '<== empty'; ?>
        </div>
        <div>
            <button type="submit">Change</button>
        </div>
    </form>
    <?php
}
?>

