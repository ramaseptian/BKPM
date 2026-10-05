<?php
// app/models/Dosen.php
// Model: bertanggung jawab atas data dosen.

class Dosen
{
    private array $data = [
        ['nidn' => '0028069702', 'nama' => 'Ulfa Emi Rahmawati, S.Kom., M.Kom.'],
        ['nidn' => '0013109501', 'nama' => 'Intan Sulistyaningrum Sakkinah, S.Pd., M.Eng'],
        ['nidn' => '0030109502', 'nama' => 'Puji Hastuti, S.T., M.Eng.'],
        ['nidn' => '0009109304', 'nama' => 'Raditya Arief Pratama, S.Kom., M.Eng'],
        ['nidn' => '0009059403', 'nama' => 'Qonitatul Hasanah, S.S.T., M.Tr.T'],
        ['nidn' => '9990637319', 'nama' => 'Muhammad Ainul Fikri, S.T., M.Eng.'],
    ];

    /**
     * Mengambil seluruh data dosen.
     */
    public function getAll(): array
    {
        return $this->data;
    }
}
