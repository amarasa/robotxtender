<?php
// Settings page content
function robotxtender_settings_page()
{
    // Get the current robots.txt content and "Include Sitemap" setting
    $robots_txt_content = get_option('robotxtender_robots_txt', '');
    $include_sitemap = get_option('robotxtender_include_sitemap', 0);

    // Generate the preview content
    $preview_content = $robots_txt_content;
    if ($include_sitemap == 1) {
        $sitemap_url = home_url('/sitemap_index.xml'); // Update this based on how your sitemap URL is generated
        $preview_content = preg_replace('/(# ---------------------------)/', "$1\nSitemap: $sitemap_url\n", $preview_content);
    }

?>
    <div class="wrap">
        <h1>RobotXtender Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('robotxtender_settings_group');
            do_settings_sections('robotxtender');
            $sitemap_url = home_url('/sitemap_index.xml');
            ?>
            <label>
                <input type="checkbox" name="robotxtender_include_sitemap" value="1" <?php checked(1, $include_sitemap, true); ?> />
                Include Sitemap
            </label>
            <br><br>
            <div style="display: flex;">
                <div style="flex: 1;">
                    <strong>Editable Robots.txt</strong><br>
                    <textarea name="robotxtender_robots_txt" rows="10" cols="50"><?php echo esc_textarea($robots_txt_content); ?></textarea>
                </div>
                <div style="flex: 1; margin-left: 20px;">
                    <strong>Robots.txt Preview</strong><a style="margin-left: 50px;" href="<?= $sitemap_url; ?>" target="_blank">Robots.txt<a /><br>
                        <textarea readonly rows="10" cols="50"><?php echo esc_textarea($preview_content); ?></textarea>
                </div>
            </div>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}
