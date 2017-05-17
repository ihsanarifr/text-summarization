<?php
//include "./indexer.php";
function getByPosistion($filename, $compress) {
// /[.]+/
    $load_file = file_get_contents("./corpus/" . $filename);
    $paragraf = preg_split('#(\r\n?|\n)+#', $load_file);
    $sentence = preg_split("/[.?!]+/", implode($paragraf), -1, PREG_SPLIT_NO_EMPTY);
    // $sentence = array_slice($sentence, 0, sizeof($sentence) - 2); // buang array terakhir (kosong)

    // jumlah kalimat yang diringkas
    $compression_rate = $compress / 100;
    $jumlah_kalimat = ceil(sizeof($sentence) * $compression_rate);

    //inisialisasi
    // $paragraf = array();
    $bobot_kalimat = array();
    $kalimat2 = array();

    foreach ($paragraf as $key => $value) {
        // tokenisasi dengan membuang stopwords
        $kalimat = preg_split("/[.?!]/", $value, -1, PREG_SPLIT_NO_EMPTY);
        $i = 1;
        foreach ($kalimat as $key => $values) {
            $bobot = 1 / ($i);
            // simpan nilai bobot kalimat
            array_push($bobot_kalimat, $bobot);
            array_push($kalimat2, $values);
            $i++;
        }
    }

    $kalimat = array_filter($sentence);
    // sorting bobot tertinggi -> potong array -> sorting urutan kalimat
    arsort($bobot_kalimat);
    $sorted = array_slice($bobot_kalimat, 0, $jumlah_kalimat, true);
    $sorted1 = array_slice($bobot_kalimat, 0, $jumlah_kalimat, true);
    ksort($sorted);
    // return $sorted;
    $ringkasan = "";
    foreach ($sorted as $key => $value)
        $ringkasan = $ringkasan . $kalimat2[$key] . ". ";
	
	foreach ($sorted as $key => $value)
		$show_kalimat[$key] = $kalimat2[$key];
    //return $ringkasan;
    $output = array();
    $output['original'] = $load_file;
    $output['ringkasan'] = $ringkasan;
    $output['bobot_kalimat'] = $bobot_kalimat;
    $output['sorted'] = $sorted;
    $output['sentence'] = $sentence;
    $output['kalimat'] = $show_kalimat;
	$output['all_kalimat'] = $kalimat2;
    
    return $output;
}

?>