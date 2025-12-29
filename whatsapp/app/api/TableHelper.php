<?php
// file: includes/TableHelper.php
require_once __DIR__ . '/Database.php';

class TableHelper {
    private $db;
    private $schema;

    public function __construct($schema) {
        $this->db = Database::getInstance();
        $this->schema = $schema;
    }

    public function getTableColumns($table) {
        return $this->db->fetchAll("DESC `$table`");
    }

    public function isColumnUnique($table, $column) {
        $db = $this->db;
        $indexes = $db->fetchAll("SHOW INDEX FROM `$table` WHERE Column_name = ?", [$column]);
        foreach ($indexes as $index) {
            if ($index['Non_unique'] == 0) {
                return true;
            }
        }
        return false;
    }

    public function getForeignKeys($table) {
        $sql = "
            SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = :schema 
              AND TABLE_NAME = :table 
              AND REFERENCED_TABLE_NAME IS NOT NULL";
        return $this->db->fetchAll($sql, ['schema' => $this->schema, 'table' => $table]);
    }

    public function getDb() {
        return $this->db;
    }

    /** ✅ NEW: Get table primary key name */
    public function getPrimaryKey($table) {
        $sql = "
            SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = :schema 
              AND TABLE_NAME = :table 
              AND CONSTRAINT_NAME = 'PRIMARY'
            LIMIT 1
        ";
        $result = $this->db->fetchAll($sql, ['schema' => $this->schema, 'table' => $table]);
        if ($result && isset($result[0]['COLUMN_NAME'])) {
            return $result[0]['COLUMN_NAME'];
        }
        return null; // fallback if no primary key found
    }
}
?>
