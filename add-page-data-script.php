<?php
// function add_page_data_script() {
//     if (is_page()) {
//         $author_id = get_the_author_meta('ID');
//         $avatar_url = get_avatar_url($author_id, array('size' => 32));
//         $reading_time = ceil(str_word_count(get_the_content()) / 200);
//         ?>
//         <script>
//         window.pageData = {
//             id: <?php echo get_the_ID(); ?>,
//             author: '<?php echo get_the_author(); ?>',
//             avatar: '<?php echo $avatar_url; ?>',
//             date: '<?php echo get_the_date('j M Y'); ?>',
//             modified: '<?php echo get_the_modified_date('j M Y'); ?>',
//             readingTime: <?php echo $reading_time; ?>
//         };
//         </script>
//         <?php
//     }
// }
// add_action('wp_head', 'add_page_data_script');

function add_page_data_script() {
    // Ejecutar solo en páginas estáticas.
    if (is_page()) {
        $author_id = null;
        $author_name = '';

        // Detecta si estamos en el modo de vista previa/edición de Elementor
        // y si hay un usuario con sesión iniciada.
        if ( isset( $_GET['elementor-preview'] ) && is_user_logged_in() ) {
            // Si es así, obtiene los datos del usuario actual (el editor).
            $current_user = wp_get_current_user();
            $author_id = $current_user->ID;
            $author_name = $current_user->display_name;
        } else {
            // Si no, obtiene los datos del autor de la página (comportamiento normal).
            $author_id = get_the_author_meta('ID');
            $author_name = get_the_author();
        }

        $avatar_url = get_avatar_url($author_id, array('size' => 32));
        $reading_time = ceil(str_word_count(get_the_content()) / 200);
        ?>
        <script>
        window.pageData = {
            id: <?php echo json_encode(get_the_ID()); ?>,
            author: <?php echo json_encode($author_name); ?>,
            avatar: <?php echo json_encode($avatar_url); ?>,
            date: <?php echo json_encode(get_the_date('j M Y')); ?>,
            modified: <?php echo json_encode(get_the_modified_date('j M Y')); ?>,
            readingTime: <?php echo json_encode($reading_time); ?>,
             badgeText: <?php echo json_encode($badge_text); ?>
        };
        </script>
        <?php
    }
}
add_action('wp_head', 'add_page_data_script');
?>