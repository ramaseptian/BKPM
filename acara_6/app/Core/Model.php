<?php
// app/Core/Model.php
// Base Model sederhana. Pada Acara 6 data masih berupa array statis
// (belum terhubung MySQL - itu dimulai pada Acara 7-8), sehingga Base
// Model ini menyediakan operasi all()/find() generik di atas array.

abstract class Model
{
    protected static array $data = [];

    public static function all(): array
    {
        return static::$data;
    }

    public static function find(string $key, $value): ?array
    {
        foreach (static::$data as $item) {
            if (($item[$key] ?? null) == $value) {
                return $item;
            }
        }
        return null;
    }
}
