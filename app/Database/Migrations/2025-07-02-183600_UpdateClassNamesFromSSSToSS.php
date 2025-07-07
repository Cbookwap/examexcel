<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateClassNamesFromSSSToSS extends Migration
{
    public function up()
    {
        // Update class names from SSS to SS
        $this->db->query("UPDATE classes SET name = REPLACE(name, 'SSS', 'SS') WHERE name LIKE 'SSS%'");
        
        echo "Updated class names from SSS to SS\n";
    }

    public function down()
    {
        // Revert class names from SS back to SSS
        $this->db->query("UPDATE classes SET name = REPLACE(name, 'SS', 'SSS') WHERE name LIKE 'SS %'");
        
        echo "Reverted class names from SS back to SSS\n";
    }
}
