<?php
// app/Models/Mahasiswa.php
class Mahasiswa extends Model
{

    protected static array $data = [
        ['nim' => '2001', 'nama' => 'Andi',   'prodi' => 'Teknik Informatika'],
        ['nim' => '2002', 'nama' => 'Rina',   'prodi' => 'Sistem Informasi'],
        ['nim' => '2003', 'nama' => 'Bagas',  'prodi' => 'Teknik Komputer'],
        ['nim' => '2004', 'nama' => 'Radit',  'prodi' => 'Teknik Informatika'],
        ['nim' => '2005', 'nama' => 'Samsul', 'prodi' => 'Teknik Komputer'],
        ['nim' => '2006', 'nama' => 'Nayla',  'prodi' => 'Teknik Informatika'],
    ];

    public static function findByNim(?string $nim): ?array
    {
        return $nim === null ? null : self::find('nim', $nim);
    }
}
