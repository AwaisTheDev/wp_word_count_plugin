<?php
/**
 * Plugin Name:       My Test Plugin
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            John Smith
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */

new wordCountPlugin();

class wordCountPlugin
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'admin_settings_page'));
        add_action('admin_init', array($this, 'settings_page_options'));
        add_filter('the_content', array($this, 'add_widget_to_content'));
    }

    public function add_widget_to_content($content)
    {

        if (is_main_query() and is_single()) {
            return $this->createHTML($content);
        }

        return $content;
    }

    public function createHTML($content)
    {

        $html = "<h3>" . get_option('WCP_headline', "Post Stats ") . "</h3>";
        $wordCount = str_word_count(wp_strip_all_tags($content));
        $charCount = strlen(wp_strip_all_tags($content));
        $readTime = round($wordCount / 200);

        $html .= "<b>Word Count: </b>$wordCount <br>";
        $html .= "<b>Character Count: </b>$charCount <br>";
        $html .= "<b>Read time </b>: $readTime minute(s)";

        if (get_option('WCP_location') == "0") {
            return $html . $content;
        } elseif (get_option('WCP_location') == "1") {
            return $content . $html;

        }

    }
    public function admin_settings_page()
    {
        add_options_page('Word Count Settings', 'Word Count', 'manage_options', 'word-count-settings', array($this, 'admin_settings_word_count_callback'));
    }

    public function admin_settings_word_count_callback()
    {?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="POST">

        <?php settings_fields('WordCountPlugin');
        do_settings_sections('word-count-settings');
        submit_button();
        ?>
        </form>
    </div>
<?php }

    public function settings_page_options()
    {
        // register_setting($option_group, $option_name, $sanitize_callback)
        // add_settings_section($id, $title, $callback, $page)
        // add_settings_field($id, $title, $callback, $page, $section, $args)

        add_settings_section('WCP_first_section', 'Subtitle', array($this, 'settings_section_callback'), 'word-count-settings');

        //Location
        register_setting('WordCountPlugin', 'WCP_location', array('sanitize_callback' => array($this, 'sanitize_location'), 'default' => "1"));
        add_settings_field('WCP_location_field', "Display Location", array($this, 'location_html_callback'), "word-count-settings", 'WCP_first_section');
        //Headline text
        register_setting('WordCountPlugin', 'WCP_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => "Heading"));
        add_settings_field('WCP_headline', "Eidget headline", array($this, 'headline_html_callback'), "word-count-settings", 'WCP_first_section');

        //show word count
        register_setting('WordCountPlugin', 'WCP_wordCount', array('sanitize_callback' => 'sanitize_text_field', 'default' => "1"));
        add_settings_field('WCP_wordCount', "Show headline", array($this, 'wordCount_html_callback'), "word-count-settings", 'WCP_first_section');

    }

    public function settings_section_callback()
    {
        echo "Out description here";
    }

    public function sanitize_location($input)
    {
        if ($input != "0" and $input != "1") {
            add_settings_error('WCP_location', 'WCP_location_error', "Location must be either begining or end");
            return get_option('WCP_location');
        }
        return $input;
    }

    public function location_html_callback()
    {?>
    <select name="WCP_location">
        <option value="0" <?php selected(get_option('WCP_location'), '0')?>>Top</option>
        <option value="1" <?php selected(get_option('WCP_location'), '1')?>>Bottom</option>
    </select>
<?php }

    public function headline_html_callback()
    {?>
        <input type="text" name="WCP_headline" value="<?php echo get_option('WCP_headline') ?>">
<?php }

    public function wordCount_html_callback()
    {?>

    <input type="checkbox" value="1" <?php checked(get_option('WCP_wordCount'), "1")?>>

    <?php }
}

?>