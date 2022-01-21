<?php

function show_gallery($name, $pattern, $size) {
    $content = "<h2 class='galery_name'>$name</h2>";
    $content .= "<div class='gallery'>";
    for ($i = 1; $i <= $size; $i++)
        $content .= "<a href='img/galery/$pattern$i.jpg'><img class='gal_img' alt='$pattern$i.jpg' src='img/galery/$pattern$i.jpg'></a>";
    $content .= "</div>";
    return $content;
}
$title = "Galeria"; 
$content = show_gallery("Pejzaże", "obraz",10);
$content .= show_gallery("Jeżyki", "obrazek_", 9);

