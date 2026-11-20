package id.ac.univpancasila.perpustakaan;

import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.DisplayName;
import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.*;

/**
 * Sesi 11 — unit test pertama Anda.
 *
 * Letakkan berkas ini di src/test/java/id/ac/univpancasila/perpustakaan/
 * Jalankan dengan: mvn test
 *
 * Konvensi Maven: hanya berkas di src/test/java yang bernama *Test
 * yang akan dijalankan sebagai pengujian.
 */

class RepositoryTest {

    /** Objek sederhana untuk diuji. */
    record Buku(String isbn, String judul) {}

    private Repository<Buku> repo;

    @BeforeEach
    void siapkan() {
        repo = new Repository<>(Buku::isbn);
    }

    @Test
    @DisplayName("Item yang disimpan dapat ditemukan kembali")
    void simpanLaluTemukan() {
        repo.simpan(new Buku("978-1", "PBO"));

         // TODO 1: pastikan cariById("978-1") berisi nilai,
        //         dan judulnya "PBO".
        //         Petunjuk: assertTrue(...isPresent()) dan assertEquals(...)
        Optional<Buku> hasil = repo.cariById("978-1");

        assertTrue(hasil.isPresent());
        assertEquals("PBO", hasil.get().judul());
    }

    @Test
    @DisplayName("Mencari id yang tidak ada mengembalikan Optional kosong")
    void cariYangTidakAda() {

        // TODO 2
        assertTrue(repo.cariById("999").isEmpty());
    }

    @Test
    @DisplayName("Item yang dihapus tidak dapat ditemukan lagi")
    void hapusItem() {

        // TODO 3
        repo.simpan(new Buku("978-1", "PBO"));

        assertTrue(repo.hapus("978-1"));
        assertTrue(repo.cariById("978-1").isEmpty());
    }

    @Test
    @DisplayName("Repository baru berisi nol item")
    void repositoryKosong() {

        // TODO 4
        assertEquals(0, repo.jumlah());
        assertTrue(repo.cariSemua().isEmpty());
    }

    @Test
    @DisplayName("Koleksi yang dikembalikan cariSemua tidak dapat diubah dari luar")
    void koleksiTidakBocor() {
        repo.simpan(new Buku("978-1", "PBO"));

       // TODO 5: pastikan memanggil .add(...) pada hasil cariSemua()
        //         melemparkan UnsupportedOperationException.
        //         Petunjuk: assertThrows(UnsupportedOperationException.class, () -> ...)
        assertThrows(UnsupportedOperationException.class, () -> {
            repo.cariSemua().add(new Buku("978-2", "Java"));
        });
    }

     // TODO 6: tambahkan minimal TIGA pengujian lagi dari daftar skenario
    //         yang Anda susun pada sesi 10, termasuk satu yang memastikan
    //         exception yang benar dilemparkan.
    @Test
    @DisplayName("Jumlah item bertambah setelah menyimpan data")
    void jumlahBertambah() {

        repo.simpan(new Buku("978-1", "PBO"));
        repo.simpan(new Buku("978-2", "Java"));

        assertEquals(2, repo.jumlah());
    }

    @Test
    @DisplayName("Menyimpan ID yang sama akan mengganti data sebelumnya")
    void simpanIdSama() {

        repo.simpan(new Buku("978-1", "PBO"));
        repo.simpan(new Buku("978-1", "Java"));

        assertEquals(1, repo.jumlah());
        assertEquals("Java", repo.cariById("978-1").get().judul());
    }

    @Test
    @DisplayName("Menghapus ID yang tidak ditemukan mengembalikan false")
    void hapusIdTidakAda() {

        assertFalse(repo.hapus("999"));
    }
}
