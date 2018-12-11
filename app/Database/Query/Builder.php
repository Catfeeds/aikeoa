<?php namespace App\Database\Query;

use Illuminate\Database\Query\Builder as BaseBuilder;
use Schema;
use Auth;

class Builder extends BaseBuilder
{
    /**
     * Insert a new record into the database.
     *
     * @param  array  $values
     * @return bool
     */
    public function insert(array $values)
    {
        $values = $this->checkColumnsValues($values);

        // 空数据
        if (empty($values)) {
            return 0;
        }

        return parent::insert($values);
    }

    /**
     * Insert a new record and get the value of the primary key.
     *
     * @param  array   $values
     * @param  string  $sequence
     * @return int
     */
    public function insertGetId(array $values, $sequence = null)
    {
        $values = $this->checkColumnsValues($values);
        // 空数据
        if (empty($values)) {
            return 0;
        }

        return parent::insertGetId($values, $sequence);
    }

    /**
     * Update a record in the database.
     *
     * @param  array  $values
     * @return int
     */
    public function update(array $values)
    {
        $values = $this->checkColumnsValues($values, false);

        // 空数据
        if (empty($values)) {
            return 0;
        }

        return parent::update($values);
    }

    public function setValue($values, $columns, $insert)
    {
        if (is_array(current($values))) {
            foreach ($values as $k => $v) {
                // 递归处理多行数据
                $values[$k] = $this->setValue($v, $columns, $insert);
            }
        } else {
            // 删除不存在的字段的值
            foreach ($values as $k => $v) {
                if (!isset($columns[$k])) {
                    unset($values[$k]);
                }
            }
            if ($insert) {
                $at = 'created_at';
                $id = 'created_id';
                $by = 'created_by';
            } else {
                $at = 'updated_at';
                $id = 'updated_id';
                $by = 'updated_by';
            }
    
            if (isset($columns[$id])) {
                $values[$id] = isset($values[$id]) ? $values[$id] : (int)Auth::id();
                $values[$by] = isset($values[$by]) ? $values[$by] : Auth::user()->nickname;
            } else {
                if (isset($columns[$by])) {
                    $values[$by] = isset($values[$by]) ? $values[$by] : (int)Auth::id();
                }
            }

            if (isset($columns[$at])) {
                $values[$at] = isset($values[$at]) ? $values[$at] : time();
            }
        }
        return $values;
    }

    public function checkColumnsValues(array $values, $insert = true)
    {
        $columns = Schema::getColumnListing($this->from);
        $columns = array_flip($columns);

        $values = $this->setValue($values, $columns, $insert);
        return $values;
    }
}
