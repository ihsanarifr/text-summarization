<?php
//include "./indexer.php";

function summarize($filename, $compress) {
    // proses indexing
    $inv_index = indexer();
    // return $inv_index;
    // load file dan daftar stopwords
    $load_file = file_get_contents("./corpus/" . $filename);
    $kalimat = preg_split("/[.]+/", $load_file);
    $kalimat = array_slice($kalimat, 0, sizeof($kalimat) - 1); // buang array terakhir (kosong)

    $stopwords = file_get_contents("./stopwords.txt");
    $stopwords = preg_split("/[\s]+/", $stopwords);

    // jumlah kalimat yang diringkas
    $compression_rate = $compress / 100;
    $jumlah_kalimat = floor(sizeof($kalimat) * $compression_rate);

    // inisialisasi
    $bobot_kalimat = array();

    // menghitung bobot tf.idf tiap kalimat
    foreach ($kalimat as $key => $value) {
        // tokenisasi dengan membuang stopwords
        $kata = preg_split("/[\d\W\s]+/", strtolower($value));
        $kata = array_diff($kata, $stopwords);
        $kata = array_values($kata); // perbaiki indeks
        // inisialisasi bobot dan hitung frekuensi token
        $tf_idf = 0;
        $freq_kata = array_count_values($kata);

        // hitung bobot tf.idf
        foreach ($freq_kata as $token => $tf)
            $tf_idf += $tf * $inv_index[$token]['idf'];

        // simpan nilai bobot kalimat
        array_push($bobot_kalimat, $tf_idf);
    }
    // sorting bobot tertinggi -> potong array -> sorting urutan kalimat
    arsort($bobot_kalimat);
    $sorted = array_slice($bobot_kalimat, 0, $jumlah_kalimat, true);
    ksort($sorted);

    // gabungkan ringkasan
    $ringkasan = "";
    foreach ($sorted as $key => $value)
        $ringkasan = $ringkasan . $kalimat[$key] . ". ";

    // return teks asli dan hasil ringkasan
    $output = array();
    $output['original'] = $load_file;
    $output['ringkasan'] = $ringkasan;

    return $output;
}
