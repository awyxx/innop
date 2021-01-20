<?php

function nacionalidade($abreviatura) {
    
    $nacio = array("Portuguesa", "Alemã", "Americana", "Francesa", "Espanhola", "Brasileira");
    $abrev = array("PT", "DE", "US", "FR", "ESP", "BR");
    $urls = array("https://flagpedia.net/data/flags/w580/pt.png", "https://flagpedia.net/data/flags/w580/de.png", "https://flagpedia.net/data/flags/w580/us.png", "https://flagpedia.net/data/flags/w580/fr.png", "https://flagpedia.net/data/flags/w580/es.png", "https://flagpedia.net/data/flags/w580/br.png");
    $idx = -1;

    for ($i = 0; $i < sizeof($abrev); $i++)
        if ($abreviatura == $abrev[$i])
            $idx = $i;

    if ($idx == -1) {
        printf("nacionalidade() erro!");
        exit;
    }
    
    printf("%s &emsp; <img src='%s' alt='Portugal' width='20px' height='13px''>", $nacio[$idx],$urls[$idx]);
}


?>