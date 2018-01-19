<?php

namespace Web\Common;

class ArrayToolkit
{
    public static function column(array $array, $columnName)
    {
        if (empty($array)) {
            return array();
        }

        $column = array();
        foreach ($array as $item) {
            if (isset($item[$columnName])) {
                $column[] = $item[$columnName];
            }
        }
        return $column;
    }

    /**
     * 选取二维数组某一列, 并移除重复的值和空的值(若$notEmpty = true)
     * @param array $array
     * @param string $columnName 选取的列名
     * @param bool $notEmpty 是否移除空的值
     * @return array 选取列组成的数组
     */
    public static function uniqueColumn(array $array, $columnName, $notEmpty = false)
    {
        if (empty($array)) {
            return array();
        }

        $column = array();
        foreach ($array as $item) {
            if (isset($item[$columnName]) && !in_array($item[$columnName], $column)) {
                if (!$notEmpty || !empty($item[$columnName])) {
                    $column[] = $item[$columnName];
                }
            }
        }
        return $column;
    }

    public static function parts(array $array, array $keys)
    {
        foreach (array_keys($array) as $key) {
            if (!in_array($key, $keys)) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    /**
     * 剔除二维数组部分列
     * @param array $array
     * @param array $keys 需剔除的列名
     * @return array 剔除指定列后的数组
     */
    public static function excludes(array $array, array $keys)
    {
        if (!empty($keys)) {
            foreach ($array as &$value) {
                foreach ($keys as $key) {
                    unset($value[$key]);
                }
            }
        }

        return $array;
    }

    public static function requires(array $array, array $keys)
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                return false;
            }
        }
        return true;
    }

    public static function changes(array $before, array $after)
    {
        $changes = array('before' => array(), 'after' => array());
        foreach ($after as $key => $value) {
            if (!isset($before[$key])) {
                continue;
            }
            if ($value != $before[$key]) {
                $changes['before'][$key] = $before[$key];
                $changes['after'][$key] = $value;
            }
        }
        return $changes;
    }

    public static function group(array $array, $key)
    {
        $grouped = array();
        foreach ($array as $item) {
            if (empty($grouped[$item[$key]])) {
                $grouped[$item[$key]] = array();
            }
            $grouped[$item[$key]][] = $item;
        }

        return $grouped;
    }

    public static function index(array $array, $name)
    {
        $indexedArray = array();
        if (empty($array)) {
            return $indexedArray;
        }

        foreach ($array as $item) {
            if (isset($item[$name])) {
                $indexedArray[$item[$name]] = $item;
                continue;
            }
        }
        return $indexedArray;
    }

    public static function filter(array $array, array $specialValues)
    {
        $filtered = array();
        foreach ($specialValues as $key => $value) {
            if (!array_key_exists($key, $array)) {
                continue;
            }

            if (is_array($value)) {
                $filtered[$key] = (array)$array[$key];
            } elseif (is_int($value)) {
                $filtered[$key] = (int)$array[$key];
            } elseif (is_float($value)) {
                $filtered[$key] = (float)$array[$key];
            } elseif (is_bool($value)) {
                $filtered[$key] = (bool)$array[$key];
            } else {
                $filtered[$key] = (string)$array[$key];
            }

            if (empty($filtered[$key])) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    public static function pick(array $array, callable $function)
    {
        if (empty($array) || !is_callable($function)) {
            return [];
        }

        $picked = [];
        foreach ($array as $item) {
            if ($function($item)) {
                $picked[] = $item;
            }
        }
        return $picked;
    }

    /**
     * 返回出现在数组a中且不在b中的值
     * @param $a
     * @param $b
     * @return array
     *
     */
    public static function diff($a, $b)
    {
        $map = [];
        foreach ($a as $val) $map[$val] = 1;
        foreach ($b as $val) unset($map[$val]);
        return array_keys($map);
    }

}