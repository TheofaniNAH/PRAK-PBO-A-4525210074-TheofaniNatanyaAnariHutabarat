<?php
declare(strict_types=1);

namespace Perpustakaan\Tests;

use PHPUnit\Framework\TestCase;
use Perpustakaan\Model\Buku;
use Perpustakaan\Model\KoleksiBuku;

/**
 * Sesi 11 — unit test PHPUnit.
 *
 * Letakkan di tests/ dan pastikan autoload-dev pada composer.json sudah
 * memetakan Perpustakaan\Tests\ ke tests/.
 *
 * Jalankan: ./vendor/bin/phpunit tests
 */
final class KoleksiBukuTest extends TestCase
{
    private KoleksiBuku $koleksi;

    protected function setUp(): void
    {
        // Dijalankan ulang sebelum SETIAP pengujian.
        $this->koleksi = new KoleksiBuku();
    }

    public function testItemYangDisimpanDapatDitemukanKembali(): void
    {
        // TODO 1
        $buku = new Buku("978-1", "PBO", "Andi", 5);

        $this->koleksi->simpan($buku);

        $hasil = $this->koleksi->cariByIsbn("978-1");

        $this->assertNotNull($hasil);
        $this->assertEquals("PBO", $hasil->getJudul());
    }

    public function testMencariIsbnYangTidakAdaMengembalikanNull(): void
    {
        // TODO 2
        $hasil = $this->koleksi->cariByIsbn("999");

        $this->assertNull($hasil);
    }

    public function testKoleksiBaruBerisiNolItem(): void
    {
        // TODO 3: perhatikan bahwa count($this->koleksi) bekerja
        //         karena kelasnya implements Countable.
        $this->assertEquals(0, count($this->koleksi));
    }

    public function testFilterHanyaMengembalikanYangLolosPredikat(): void
    {
        // TODO 4
        $this->koleksi->simpan(
            new Buku("978-1", "PBO", "Andi", 5)
        );

        $this->koleksi->simpan(
            new Buku("978-2", "Java", "Budi", 0)
        );

        $hasil = $this->koleksi->filter(
            fn($buku) => $buku->getJumlahEksemplar() > 0
        );

        $this->assertEquals(1, count($hasil));
        $this->assertEquals("PBO", $hasil->semua()[0]->getJudul());
    }

    public function testJumlahkanMenghitungTotalDenganBenar(): void
    {
        // TODO 5
        $this->koleksi->simpan(
            new Buku("978-1", "PBO", "Andi", 5)
        );

        $this->koleksi->simpan(
            new Buku("978-2", "Java", "Budi", 3)
        );

        $hasil = $this->koleksi->jumlahkan(
            fn($buku) => $buku->getJumlahEksemplar()
        );

        $this->assertEquals(8.0, $hasil);
    }

    // TODO 6: terjemahkan minimal LIMA skenario dari skenario-uji.md sesi 10
    //         menjadi kasus uji di sini, termasuk satu yang memastikan
    //         exception yang benar dilemparkan.
    //         Petunjuk: $this->expectException(BukuTidakDitemukanException::class);

    public function testSimpanBukuDenganIsbnYangSama(): void
    {
        $this->koleksi->simpan(
            new Buku("978-1", "PBO", "Andi", 5)
        );

        $this->koleksi->simpan(
            new Buku("978-1", "Java", "Budi", 3)
        );

        $this->assertEquals(1, count($this->koleksi));
        $this->assertEquals(
            "Java",
            $this->koleksi->cariByIsbn("978-1")->getJudul()
        );
    }

    public function testMapMengubahDataBuku(): void
    {
        $this->koleksi->simpan(
            new Buku("978-1", "PBO", "Andi", 5)
        );

        $this->koleksi->simpan(
            new Buku("978-2", "Java", "Budi", 3)
        );

        $hasil = $this->koleksi->map(
            fn($buku) => $buku->getJudul()
        );

        $this->assertEquals(["PBO", "Java"], $hasil);
    }

    public function testKelompokkanBukuBerdasarkanPenulis(): void
    {
        $this->koleksi->simpan(
            new Buku("978-1", "PBO", "Andi", 5)
        );

        $this->koleksi->simpan(
            new Buku("978-2", "Java", "Budi", 3)
        );

        $hasil = $this->koleksi->kelompokkan(
            fn($buku) => $buku->getPenulis()
        );

        $this->assertCount(1, $hasil["Andi"]);
        $this->assertCount(1, $hasil["Budi"]);
    }

    public function testKoleksiBisaDigunakanDalamForeach(): void
    {
        $this->koleksi->simpan(
            new Buku("978-1", "PBO", "Andi", 5)
        );

        $jumlah = 0;

        foreach ($this->koleksi as $buku) {
            $jumlah++;
        }

        $this->assertEquals(1, $jumlah);
    }

    public function testJumlahEksemplarNegatifMenghasilkanException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Buku("978-3", "PHP", "Andi", -1);
    }
}
