<?php
add_action( 'wp_head', function () {
    $title = 'Clockdown Editor';
    echo "<title>$title</title>";
} );

get_header();

?>

<div id="clockdown"></div>

<?php get_footer();?>