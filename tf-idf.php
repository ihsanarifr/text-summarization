<?php
//include "./indexer.php";

function getTfIDF($filename, $compress) {
    // proses indexing
    // $inv_index = indexer();
    // return $inv_index;
    // load file dan daftar stopwords
    $load_file = file_get_contents("./corpus/" . $filename);
    $inv_index  = indexer($load_file);
    $kalimat = preg_split("/[.?!]+/", $load_file , -1, PREG_SPLIT_NO_EMPTY);
    // $kalimat = array_slice($kalimat, 0, sizeof($kalimat) - 1); // buang array terakhir (kosong)
    //return $kalimat;
    $stopwords = file_get_contents("./stopwords.txt");
    $stopwords = preg_split("/[\s]+/", $stopwords);

    // jumlah kalimat yang diringkas
    $compression_rate = $compress / 100;
    $jumlah_kalimat = ceil(sizeof($kalimat) * $compression_rate);

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
    $sorted1 = array_slice($bobot_kalimat, 0, $jumlah_kalimat, true);
    ksort($sorted);

    // gabungkan ringkasan
    $ringkasan = "";
    foreach ($sorted as $key => $value)
        $ringkasan = $ringkasan . $kalimat[$key] . ". ";
		
	foreach ($sorted as $key => $value)
		$show_kalimat[$key] = $kalimat[$key];
		

    // return teks asli dan hasil ringkasan
    $output = array();
    $output['original'] = $load_file;
    $output['ringkasan'] = $ringkasan;
    $output['bobot_kalimat'] = $bobot_kalimat;
    $output['sorted'] = $sorted;
	$output['frek_data'] = $freq_kata;
	$output['tf_idf'] = $tf_idf;
	$output['inv_index'] = $inv_index;
	$output['kalimat'] = $show_kalimat;
	$output['all_kalimat'] = $kalimat;

    return $output;
}
