<?php
include '../../Connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST["date"];
    $time = $_POST["time"];
    $tableCategory = $_POST["tableCategory"];

    switch ($tableCategory) {
        case '2':
            $tableCount = 2;
            $columnCount = 6;
            break;
        case '4':
            $tableCount = 2;
            $columnCount = 5;
            break;
        case '6':
            $tableCount = 2;
            $columnCount = 4;
            break;
        case '8':
            $tableCount = 2;
            $columnCount = 3;
            break;
        case '10':
            $tableCount = 1;
            $columnCount = 5;
            break;
        default:
            $tableCount = 5;
            $columnCount = 2;
            break;
    }

    $reservedTables = array();

    $sql = "SELECT reservations.date, reservations.time, reservations.table_category, reserve_tables.tables FROM reserve_tables
            INNER JOIN reservations ON reserve_tables.reservation_id = reservations.id
            WHERE reservations.date = ? AND reservations.time = ? AND reservations.table_category = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $date, $time, $tableCategory);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reservedTablesArray = explode(",", $row["tables"]);
            foreach ($reservedTablesArray as $table) {
                if (!in_array($table, $reservedTables)) {
                    $reservedTables[] = $table;
                }
            }
        }
    }

    $output = '<p style="color: #000;">Select a Table Number:</p><div class="tables-container">';

    $tableNumber = 1;
    for ($x = 0; $x < $tableCount; $x++) {
        $output .= '<div class="table-row">';
        for ($i = 0; $i < $columnCount; $i++) {
            $isReserved = in_array($tableNumber, $reservedTables);
            $disabled = $isReserved ? 'disabled' : '';
            $tableClass = $isReserved ? 'tablebox reserved' : 'tablebox';
            $output .= '<div class="table-item">';
            $output .= '<input type="checkbox" id="table' . $tableNumber . '" class="' . $tableClass .
                '" value="' . $tableNumber . '" name="tables[]" ' . $disabled . '>';
            $output .= '<label for="table' . $tableNumber . '">' . $tableNumber . '</label>';
            $output .= '</div>';
            $tableNumber++;
        }
        $output .= '</div>';
    }

    $output .= '</div>';
    echo $output;
}
?>
