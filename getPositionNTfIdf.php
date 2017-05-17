<?php
//include "./indexer.php";

function getPositionNTfIdf($filename, $compress) {
    // proses indexing
    // $inv_index = indexer();

    // load file dan daftar stopwords
    $load_file = file_get_contents("./corpus/" . $filename);
    $inv_index  = indexer($load_file);
    $kalimat = preg_split("/[.?!]+/", $load_file,  -1, PREG_SPLIT_NO_EMPTY);
    // $kalimat = array_slice($kalimat, 0, sizeof($kalimat) - 1); // buang array terakhir (kosong)

    $stopwords = file_get_contents("./stopwords.txt");
    $stopwords = preg_split("/[\s]+/", $stopwords);

    //tokenisasi token title dan membuang stopwords
    $filename = explode(".", $filename);
    $key_title = preg_split("/[\d\W\s]+/", strtolower($filename[0])); //menghilangkan extension dari filename.
    $key_title = array_diff($key_title, $stopwords);
    $key_title = array_values($key_title); // perbaiki indeks
    // jumlah kalimat yang diringkas
    $compression_rate = $compress / 100;
    $jumlah_kalimat = ceil(sizeof($kalimat) * $compression_rate);

    // inisialisasi
    $bobot_kalimat = array();
    $bobot_position = array();
    $bobot_tfidf = array();
    $kalimat2 = array();
    // $paragraf = array();
    // $kalimat = array();
    // menghitung bobot tf.idf tiap kalimat
    $i = 0;
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
        $bobot_by_position = 1 / ($i); // menghitung bobot berdasarkan position
        // simpan nilai bobot kalimat
        $total_bobot = $bobot_by_position + $tf_idf;
        array_push($bobot_kalimat, $total_bobot);
        array_push($bobot_position, $bobot_by_position);
        array_push($bobot_tfidf, $tf_idf);
        array_push($kalimat2, $value);
        $i++;
    }
    // sorting bobot tertinggi -> potong array -> sorting urutan kalimat
    arsort($bobot_kalimat);
    arsort($bobot_position);
    arsort($bobot_tfidf);
    // return $bobot_kalimat;
    $sorted = array_slice($bobot_kalimat, 0, $jumlah_kalimat, true);
    // susun kembali menjadi kalimat berurutan
    ksort($sorted);

    // gabungkan ringkasan
    $ringkasan = "";
    foreach ($sorted as $key => $value)
        $ringkasan = $ringkasan . $kalimat2[$key] . ". ";
		
	foreach ($sorted as $key => $value)
		$show_kalimat[$key] = $kalimat2[$key];
		
		
    //return $ringkasan;
    // return teks asli dan hasil ringkasan
    $output = array();
    $output['original'] = $load_file;
    $output['ringkasan'] = $ringkasan;
    $output['bobot_kalimat'] = $bobot_kalimat;
    $output['bobot_position'] = $bobot_position;
    $output['bobot_tfidf'] = $bobot_tfidf;
    $output['sorted'] = $sorted;
	$output['kalimat'] = $show_kalimat;
	$output['all_kalimat'] = $kalimat2;
    
    return $output;
}
