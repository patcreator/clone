<?php
// file: includes/FormGenerator.php
require_once __DIR__ . '/TableHelper.php';

class FormGenerator {
    private $tableHelper;

    public function __construct($schema) {
        $this->tableHelper = new TableHelper($schema);
    }

    public function generateCreateForm($table, $actionPath) {
        $columns = $this->tableHelper->getTableColumns($table);
        $foreignKeys = $this->tableHelper->getForeignKeys($table);
        $foreignCols = array_column($foreignKeys, null, 'COLUMN_NAME');

        $form = '<div class="max-w-3xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-md">';
        $form .= '<form id="add'.$table.'Form" method="POST" action="' . htmlspecialchars($actionPath) . '" class="space-y-6">';
        foreach ($columns as $col) {
            $name = $col['Field'];
            $key = $col['Key'];
            $type = strtoupper($col['Type']);
            $isRequired = $col['Null'] == 'NO' ? 'required' : '';
            $default = $col['Default'] ?? '';
            $extra = $col['Extra'] ?? '';

            // Skip auto increment columns
            if (strpos($extra, 'auto_increment') !== false or strtolower($default) == 'current_timestamp()' || strtoupper($type) == 'SERIAL' || $name == 'updated_at' || $name == 'created_at') continue;
            if (strtoupper($key) == 'PRI') $default = random_int(99999, 999999);
            // Handle foreign key columns
            if (isset($foreignCols[$name])) {
                $fk = $foreignCols[$name];
                $form .= $this->foreignKeySelect($fk['REFERENCED_TABLE_NAME'], $name, $fk['REFERENCED_COLUMN_NAME']);
                // $form .= $this->foreignKeySelect("SELECT id, name FROM users WHERE active = 1", "user_id", "id");

                continue;
            }

            // ENUM
            if (preg_match('/^ENUM\((.*)\)$/i', $type, $matches)) {
                $values = str_getcsv($matches[1], ',', "'");
                $form .= $this->enumSelect($name, $values, $default, $isRequired);
                continue;
            }

            // SET
            if (preg_match('/^SET\((.*)\)$/i', $type, $matches)) {
                $values = str_getcsv($matches[1], ',', "'");
                $form .= $this->setMultiSelect($name, $values, $default, $isRequired);
                continue;
            }

            // YEAR
            if (preg_match('/^YEAR/i', $type)) {
                $form .= $this->yearSelect($name, $default);
                continue;
            }

            // JSON
            if (preg_match('/JSON/i', $type)) {
                $form .= $this->jsonField($name, $default);
                continue;
            }

            // Other normal columns
            $inputType = $this->mapSqlTypeToInput($type);
            $maxlength = $this->getMaxLength($type);
            $step = $this->getStep($type);
            $defaultAttr = $default ? "value='" . htmlspecialchars($default) . "'" : '';

            $form .= "
                <div>
                    <label class='block text-sm font-medium text-gray-700 mb-1' for='$name'>$name</label>
                    <input type='$inputType' id='$name' name='$name' $isRequired $defaultAttr 
                        " . ($maxlength ? "maxlength='$maxlength'" : '') . "
                        " . ($step ? "step='$step'" : '') . "
                        placeholder=\"Enter $name\" class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-400' />
                </div>
            ";
        }

        $form .= '
            <div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                    Add ' . ucfirst($table) . '
                </button>
            </div>
        ';
        $form .= '</form></div>';

        return $form;
    }









public function generateEditForm($table, $actionPath) {
    $columns = $this->tableHelper->getTableColumns($table);
    $foreignKeys = $this->tableHelper->getForeignKeys($table);
    $foreignCols = array_column($foreignKeys, null, 'COLUMN_NAME');

    // Primary key detection
    $primaryKey = $this->tableHelper->getPrimaryKey($table);
    $pk = $primaryKey ?: 'id';

    // Get record from DB dynamically
    $form = "<?php\n";
    $form .= "\$id = \$_GET['$pk'] ?? null;\n";
    $form .= "if (!\$id) { die('Missing $pk'); }\n";
    $form .= "include_once '../../../system/cogs/functions.php';";
    $form .= "\$row = fetchData(\"SELECT * FROM `$table` WHERE `$pk` = ?\", [\$id])[0] ?? null;\n";
    $form .= "if (!\$row) { die('Record not found'); }\n";
    $form .= "?>\n";

    $form .= '<div class="max-w-3xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-md">';
    $form .= '<form id="edit'.$table.'Form" method="POST" action="' . htmlspecialchars($actionPath) . '?'.$pk.'=<?= $id ?>" class="space-y-6">';

    foreach ($columns as $col) {
        $name = $col['Field'];
        $type = strtoupper($col['Type']);
        $isRequired = $col['Null'] == 'NO' ? 'required' : '';
        $extra = $col['Extra'] ?? '';

        // Skip auto increment columns
        if (strpos($extra, 'auto_increment') !== false) continue;

        // Foreign keys
        if (isset($foreignCols[$name])) {
            $fk = $foreignCols[$name];
            $form .= "<?php \$selectedVal = \$row['$name']; ?>\n";
            $form .= $this->foreignKeySelectWithSelected($fk['REFERENCED_TABLE_NAME'], $name, $fk['REFERENCED_COLUMN_NAME']);
            continue;
        }

        // ENUM
        if (preg_match('/^ENUM\((.*)\)$/i', $type, $matches)) {
            $values = str_getcsv($matches[1], ',', "'");
            $form .= "<?php \$selectedVal = \$row['$name']; ?>\n";
            $form .= $this->enumSelectWithSelected($name, $values, $isRequired);
            continue;
        }

        // SET
        if (preg_match('/^SET\((.*)\)$/i', $type, $matches)) {
            $values = str_getcsv($matches[1], ',', "'");
            $form .= "<?php \$selectedVal = \$row['$name']; ?>\n";
            $form .= $this->setMultiSelectWithSelected($name, $values, $isRequired);
            continue;
        }

        // YEAR
        if (preg_match('/^YEAR/i', $type)) {
            $form .= "<?php \$selectedVal = \$row['$name']; ?>\n";
            $form .= $this->yearSelectWithSelected($name);
            continue;
        }

        // JSON
        if (preg_match('/JSON/i', $type)) {
            $form .= "<?php \$selectedVal = \$row['$name']; ?>\n";
            $form .= $this->jsonFieldWithValue($name);
            continue;
        }

        // Normal columns
        $inputType = $this->mapSqlTypeToInput($type);
        $maxlength = $this->getMaxLength($type);
        $step = $this->getStep($type);

        $form .= "
            <div>
                <label class='block text-sm font-medium text-gray-700 mb-1' for='$name'>$name</label>
                <input type='$inputType' id='$name' name='$name' $isRequired 
                    value='<?= htmlspecialchars(\$row[\"$name\"] ?? \"\") ?>'
                    " . ($maxlength ? "maxlength='$maxlength'" : '') . " placeholder=\"Enter $name\" 
                    " . ($step ? "step='$step'" : '') . "
                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-400' />
            </div>
        ";
    }

    $form .= '
        <div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Update ' . ucfirst($table) . '
            </button>
        </div>
    ';
    $form .= '</form></div>';

    return $form;
}





private function enumSelectWithSelected($name, $values, $isRequired) {
    $options = "<?php foreach (" . var_export($values, true) . " as \$val): ?>\n";
    $options .= "<option value='<?= \$val ?>' <?= (\$selectedVal == \$val) ? 'selected' : '' ?>><?= \$val ?></option>\n";
    $options .= "<?php endforeach; ?>\n";

    return "
        <div>
            <label class='block text-sm font-medium text-gray-700 mb-1' for='$name'>$name</label>
            <select id='$name' name='$name' $isRequired
                class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-400'>
                <option value='' disabled>Select one</option>
                $options
            </select>
        </div>
    ";
}

private function setMultiSelectWithSelected($name, $values, $isRequired) {
    $options = "<?php \$selectedArr = explode(',', \$selectedVal ?? ''); foreach (" . var_export($values, true) . " as \$val): ?>\n";
    $options .= "<option value='<?= \$val ?>' <?= in_array(\$val, \$selectedArr) ? 'selected' : '' ?>><?= \$val ?></option>\n";
    $options .= "<?php endforeach; ?>\n";

    return "
        <div>
            <label class='block text-sm font-medium text-gray-700 mb-1' for='$name'>$name (multiple)</label>
            <select id='$name' name='{$name}[]' multiple $isRequired
                class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-400'>
                $options
            </select>
        </div>
    ";
}

private function yearSelectWithSelected($name) {
    return "
        <div>
            <label class='block text-sm font-medium text-gray-700 mb-1' for='$name'>$name</label>
            <select id='$name' name='$name' class='flex-1 border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:border-blue-400'>
                <?php
                \$current = date('Y');
                for (\$y = \$current; \$y >= 1900; \$y--) {
                    \$sel = (\$selectedVal == \$y) ? 'selected' : '';
                    echo \"<option value='\$y' \$sel>\$y</option>\";
                }
                ?>
            </select>
        </div>
    ";
}

private function jsonFieldWithValue($name) {
    return "
        <div>
            <label class='block text-sm font-medium text-gray-700 mb-1'>$name (JSON)</label>
            <textarea id='$name' name='$name' rows='6' placeholder=\"Enter $name\"
                class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-400'><?= htmlspecialchars(\$selectedVal ?? '') ?></textarea>
        </div>
    ";
}

private function foreignKeySelectWithSelected($source, $field, $refColumn = null) {
    $optionsHtml = $this->generateForeignKeyOptions($source, $refColumn);
    return "
        <div>
            <label class='block text-sm font-medium text-gray-700 mb-1' for='$field'>$field</label>
            <select id='$field' name='$field' required
                class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-400'>
                <option value='' disabled>Select one</option>
                <?php foreach (fetchData(\"SELECT * FROM `$source`\") as \$row2): ?>
                    <option value='<?= \$row2['$refColumn'] ?>' <?= (\$selectedVal == \$row2['$refColumn']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars(\$row2[array_key_first(\$row2)]) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    ";
}










    /** ENUM: Select one */
    private function enumSelect($name, $values, $default, $isRequired) {
        $options = "<option value='' disabled selected>Select one</option>";
        foreach ($values as $val) {
            $selected = ($val === $default) ? 'selected' : '';
            $options .= "<option value='$val' $selected>$val</option>";
        }
        return "
            <div>
                <label class='block text-sm font-medium text-gray-700 mb-1' for='$name'>$name</label>
                <select id='$name' name='$name' $isRequired
                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-400'>
                    $options
                </select>
            </div>
        ";
    }

    /** SET: Multiple select */
    private function setMultiSelect($name, $values, $default, $isRequired) {
        $defaultValues = explode(',', $default ?? '');
        $options = '';
        foreach ($values as $val) {
            $selected = in_array($val, $defaultValues) ? 'selected' : '';
            $options .= "<option value='$val' $selected>$val</option>";
        }
        return "
            <div>
                <label class='block text-sm font-medium text-gray-700 mb-1' for='$name'>$name (multiple)</label>
                <select id='$name' name='{$name}[]' multiple $isRequired
                    class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-400'>
                    $options
                </select>
                <p class='text-xs text-gray-500'>Hold Ctrl (Windows) or Command (Mac) to select multiple values.</p>
            </div>
        ";
    }

    /** YEAR: dropdown + add new year */
    private function yearSelect($name, $default) {
        $current = date('Y');
        $options = '';
        for ($y = $current; $y >= 1900; $y--) {
            $selected = ($y == $default) ? 'selected' : '';
            $options .= "<option value='$y' $selected>$y</option>";
        }
        return "
            <div>
                <label class='block text-sm font-medium text-gray-700 mb-1' for='$name'>$name</label>
                <div class='flex gap-2'>
                    <select id='$name' name='$name' class='flex-1 border border-gray-300 rounded-md p-2 focus:ring focus:ring-blue-200 focus:border-blue-400'>
                        $options
                    </select>
                    <button type='button' onclick='addNewYear(\"$name\")' class='bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600'>
                        + Add Year
                    </button>
                </div>
            </div>
            <script>
                function addNewYear(id) {
                    const sel = document.getElementById(id);
                    const newYear = prompt('Enter a new year:');
                    if (newYear && !isNaN(newYear)) {
                        const opt = new Option(newYear, newYear, true, true);
                        sel.add(opt);
                    }
                }
            </script>
        ";
    }

    /** JSON: dynamic nested form */
    private function jsonField($name, $default) {
        return "
            <div>
                <label class='block text-sm font-medium text-gray-700 mb-1'>$name (JSON)</label>
                <textarea id='$name' name='$name' placeholder=\"Enter $name\" rows='6' class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-400'>"
                . htmlspecialchars($default) .
                "</textarea>
                <p class='text-xs text-gray-500'>Provide JSON or use a nested form builder (to be implemented).</p>
            </div>
        ";
    }


private function generateForeignKeyOptions($source, $refColumn = null) {
    // Determine if it's SQL or a table
    $isSql = stripos(trim($source), 'SELECT') === 0;
    $sql = $isSql ? $source : "SELECT * FROM `$source`";

    $rows = fetchData($sql);
    if (!$rows || count($rows) === 0) {
        return "<option disabled>No records found</option>";
    }

    // Detect display column automatically if not specified
    if (!$refColumn) {
        $firstRow = $rows[0];
        $refColumn = array_key_first($firstRow);
    }

    // Try to find a human-readable column (like name/title)
    $displayColumn = null;
    foreach ($rows[0] as $col => $val) {
        if (is_string($val) && !preg_match('/id$/i', $col)) {
            $displayColumn = $col;
            break;
        }
    }
    if (!$displayColumn) {
        $displayColumn = $refColumn;
    }

    // Build <option> HTML
    $options = '';
    foreach ($rows as $row) {
        $value = htmlspecialchars($row[$refColumn]);
        $display = htmlspecialchars($row[$displayColumn]);
        $options .= "<option value='$value'>$display</option>";
    }

    return $options;
}


/** Foreign key dropdown that supports table or SQL source */
private function foreignKeySelect($source, $field, $refColumn = null) {
    $optionsHtml = $this->generateForeignKeyOptions($source, $refColumn);

    return "
        <div>
            <label class='block text-sm font-medium text-gray-700 mb-1' for='$field'>$field</label>
            <select id='$field' name='$field' required
                class='mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-400'>
                <option value='' disabled selected>Select one</option>
                $optionsHtml
            </select>
        </div>
    ";
}








    /** Map SQL to input types */
    private function mapSqlTypeToInput($sqlType) {
        $mapping = [
            'INT' => 'number',
            'BIGINT' => 'number',
            'DECIMAL' => 'number',
            'FLOAT' => 'number',
            'DOUBLE' => 'number',
            'BIT' => 'number',
            'BOOLEAN' => 'checkbox',
            'DATE' => 'date',
            'DATETIME' => 'datetime-local',
            'TIMESTAMP' => 'datetime-local',
            'TIME' => 'time',
            'CHAR' => 'text',
            'VARCHAR' => 'text',
            'TEXT' => 'text',
            'JSON' => 'text',
        ];
        foreach ($mapping as $key => $type) {
            if (strpos($sqlType, $key) !== false) return $type;
        }
        return 'text';
    }

    /** Extract maxlength if applicable */
    private function getMaxLength($type) {
        if (preg_match('/\((\d+)\)/', $type, $m)) {
            return $m[1];
        }
        return null;
    }

    /** Add step for decimals */
    private function getStep($type) {
    if (preg_match('/\((\d+),(\d+)\)/', $type, $m)) {
        $decimals = (int)$m[2];
        if ($decimals <= 0) {
            return '1';
        }
        return '0.' . str_repeat('0', $decimals - 1) . '1';
    }
    return null;
}


}
?>
