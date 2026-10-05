<?php

class Mahasiswa
{
    private ?int $id = null;
    private string $nim;
    private string $nama;
    private int $prodiId;
    private int $angkatan;
    private string $status;

    private ?string $prodiNama = null;

    public function __construct(string $nim, string $nama, int $prodiId, int $angkatan, string $status = 'aktif')
    {
        $this->setNim($nim);
        $this->setNama($nama);
        $this->prodiId  = $prodiId;
        $this->angkatan = $angkatan;
        $this->setStatus($status);
    }

    //  GETTER
    public function getId(): ?int        { return $this->id; }
    public function getNim(): string     { return $this->nim; }
    public function getNama(): string    { return $this->nama; }
    public function getProdiId(): int    { return $this->prodiId; }
    public function getAngkatan(): int   { return $this->angkatan; }
    public function getStatus(): string  { return $this->status; }
    public function getStatusLabel(): string { return ucfirst($this->status); }
    public function getProdiNama(): string   { return $this->prodiNama ?? '-'; }

    //  SETTER + VALIDASI
    public function setId(int $id): void
    {
        $this->id = $id;
    }


    public function setNim(string $nim): void
    {
        if (!ctype_digit($nim)) {
            throw new InvalidArgumentException('NIM harus berupa angka.');
        }
        $this->nim = $nim;
    }

    public function setNama(string $nama): void
    {
        if (trim($nama) === '') {
            throw new InvalidArgumentException('Nama mahasiswa tidak boleh kosong.');
        }
        $this->nama = $nama;
    }

    public function setProdiId(int $prodiId): void
    {
        if ($prodiId <= 0) {
            throw new InvalidArgumentException('Program studi wajib dipilih.');
        }
        $this->prodiId = $prodiId;
    }

    public function setAngkatan(int $angkatan): void
    {
        $this->angkatan = $angkatan;
    }

    public function setStatus(string $status): void
    {
        if (!in_array($status, ['aktif', 'cuti', 'lulus'], true)) {
            throw new InvalidArgumentException('Status tidak valid.');
        }
        $this->status = $status;
    }

    public function setProdiNama(string $prodiNama): void
    {
        $this->prodiNama = $prodiNama;
    }
}