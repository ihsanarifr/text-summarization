<?php
//include "./indexer.php";
function getByTitle($filename, $compress) {
    // proses indexing
    $inv_index = indexer();
    // return $inv_index;
    // load file dan daftar stopwords
    $load_file = file_get_contents("./corpus/" . $filename);
    //$kalimat = preg_split(" /(?<=[.?!;:])\s+/", $load_file);
	$kalimat = preg_split("/[.?!]+/", $load_file, -1, PREG_SPLIT_NO_EMPTY);
    //$kalimat = array_slice($kalimat, 0, sizeof($kalimat) - 1); // buang array terakhir (kosong)

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

    // menghitung bobot tf.idf tiap kalimat
    foreach ($kalimat as $key => $value) {
        // tokenisasi dengan membuang stopwords
        $kata = preg_split("/[\d\W\s]+/", strtolower($value));
        $kata = array_diff($kata, $stopwords);
        $kata = array_values($kata); // perbaiki indeks
        //menghitung jumlah kata pada kalimat yang sama dengan key title
        $bobot = 0;
        foreach ($kata as $word) {
            foreach ($key_title as $title) {
                if ($word == $title) {
                    $bobot++;
                }
            }
        }
        array_push($bobot_kalimat, $bobot);
    }
    // sorting bobot tertinggi -> potong array -> sorting urutan kalimat
    arsort($bobot_kalimat);

    $sorted = array_slice($bobot_kalimat, 0, $jumlah_kalimat, true);
    ksort($sorted);

    // gabungkan ringkasan
    $ringkasan = "";
    foreach ($sorted as $key => $value)
        $ringkasan = $ringkasan . $kalimat[$key] . ". ";
	
	foreach ($sorted as $key => $value)
		$show_kalimat[$key] = $kalimat[$key];
		
    //return $ringkasan;
    // return teks asli dan hasil ringkasan
    $output = array();
    $output['original'] = $load_file;
    $output['ringkasan'] = $ringkasan;
    $output['bobot_kalimat'] = $bobot_kalimat;
    $output['sorted'] = $sorted;
	$output['kalimat'] = $show_kalimat;
	$output['all_kalimat'] = $kalimat;
	

    return $output;
}
