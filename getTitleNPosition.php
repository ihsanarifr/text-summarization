<?php

//include "./indexer.php";

function getTitleNPosition($filename, $compress) {
    // $inv_index = indexer();

    $load_file = file_get_contents("./corpus/" . $filename);
    $inv_index  = indexer($load_file);
    $paragraf = preg_split('#(\r\n?|\n)+#', $load_file);
    $sentence = preg_split("/[.?!]+/", $load_file, -1, PREG_SPLIT_NO_EMPTY);

    // jumlah kalimat yang diringkas
    $compression_rate = $compress / 100;
    $jumlah_kalimat = ceil(sizeof($sentence) * $compression_rate);

    $stopwords = file_get_contents("./stopwords.txt");
    $stopwords = preg_split("/[\s]+/", $stopwords);

    $filename = explode(".", $filename);
    $key_title = preg_split("/[\d\W\s]+/", strtolower($filename[0])); //menghilangkan extension dari filename.
    $key_title = array_diff($key_title, $stopwords);
    $key_title = array_values($key_title); // perbaiki indeks

    $bobot_kalimat = array();
    $bobot_title = array();
    $bobot_position = array();
    $kalimat2 = array();

    foreach ($paragraf as $key => $value) {
        // tokenisasi dengan membuang stopwords
        $kalimat = preg_split("/[.?!]+/", $value, -1, PREG_SPLIT_NO_EMPTY);
        $i = 1;
        foreach ($kalimat as $key => $values) {
            $kata = preg_split("/[\d\W\s]+/", strtolower($values));
            $kata = array_diff($kata, $stopwords);
            $kata = array_values($kata); // perbaiki indeks
            
            //menghitung jumlah kata pada kalimat yang sama dengan key title
            $bobot_by_title = 0;
            foreach ($kata as $word) {
                foreach ($key_title as $title) {
                    if ($word == $title) {
                        $bobot_by_title++;
                    }
                }
            }

            $bobot_by_position = 1 / ($i);
            $total_bobot = $bobot_by_position + $bobot_by_title;
            // simpan nilai bobot kalimat
            array_push($bobot_kalimat, $total_bobot);
            array_push($bobot_title,$bobot_by_title);
            array_push($bobot_position,$bobot_by_position);
            array_push($kalimat2,$values);
            $i++;
        }
    }

    $kalimat = array_filter($kalimat);
    // sorting bobot tertinggi -> potong array -> sorting urutan kalimat
    arsort($bobot_kalimat);
    arsort($bobot_title);
    arsort($bobot_position);
    
    // return $bobot_kalimat;
    $sorted = array_slice($bobot_kalimat, 0, $jumlah_kalimat, true);
    
    ksort($sorted);
    
    $ringkasan = "";
    foreach ($sorted as $key => $value)
        $ringkasan = $ringkasan . $kalimat2[$key] . ". ";
	
	foreach ($sorted as $key => $value)
		$show_kalimat[$key] = $kalimat2[$key];
		
    $output = array();
    $output['original'] = $load_file;
    $output['ringkasan'] = $ringkasan;
    $output['bobot_kalimat'] = $bobot_kalimat;
    $output['bobot_position'] = $bobot_position;
    $output['bobot_title'] = $bobot_title;
    $output['sorted'] = $sorted;
	$output['kalimat'] = $show_kalimat;
	$output['all_kalimat'] = $kalimat2;
    
    return $output;
}

?>