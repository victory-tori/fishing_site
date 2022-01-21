<?php

class Record {
    protected $user_id;
    protected $record;
    protected $fish;
    protected $date;
    protected $weight;
    
    function __construct($record){
        $this->user_id = $record["user_id"];
        $this->record_name = $record["record_name"];
        $this->fish = $record["fish"];
        $this->weight = $record["weight"];
        //$this->date = ($record["date"])->format('Y-m-d');
        $this->date = $record["date"];
    }
    
    function to_array(){
        $arr = ["user_id" => $this->user_id, 
                "record_name" => $this->record_name,
                "fish" => $this->fish,
                "weight" => $this->weight,
                "date" => $this->date
                ];
        return $arr; 
    }
    
    function save_record_to_DB($db) {
        $data = $this->to_array();
        $content = "";
        if ($db->insert("records", $data, true)) 
            $content .= "ok";
        else
            $content .= "error";
        return $content;
    }
    static function get_all_records_of($db, $id_name, $id, $fields) {
        return $db->select_where("records", $id_name, $id, $fields);
    }
    static function show_records($records) {
        if (empty($records))
            return null;
        else {
            $table = "";        
            $table .= "<table class='show_table'><tbody><thead>";
            $table .= "<tr><th>Lp</th>";
            foreach (array_keys($records[0]) as $field)
                $table .= "<th>$field</th>";
            $table .= "</tr></thead><tbody>";
            $counter = 1;
            foreach ($records as $record) {
                $table .= "<tr><td>$counter</td>";
                foreach ($record as $val) 
                    $table .= "<td>" . $val . "</td>";
                $table .= "</tr>";
                $counter++;
            }
            $table .= "</tbody></table>";
            return $table;
        }
    } 
    
}
