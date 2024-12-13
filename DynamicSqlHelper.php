<?php defined('SYSPATH') or die('No direct script access.');

    function sql_fetch_one($conn, $sql)
    {
        if(!$conn->real_query($sql))
            return '';
        $r = $conn->store_result();
        if(!$r)
            return '';

        $row = $r->fetch_row();
        $r->close();
        while($conn->next_result()) ;
        return $row;
    }

    function sql_fetch_one_cell($conn, $sql)
    {
        $result = sql_fetch_one($conn, $sql);
        if($result == '')
            return 0;
        return $result[0];
    }

?>
