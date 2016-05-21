<?php
/**
 * Created by Catalin Teodorescu on 22-May-16 00:26.
 */
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/functions.php';

if (!empty($_GET['url']) && (filter_var($_GET['url'], FILTER_VALIDATE_URL) !== false)) {
    $metaElements = parser($_GET['url']);
} else {
    dump('Please provide a valid URL');
}
?>
    <form method="GET" action="">
        <input type="text" name="url" placeholder="url" value="<?php echo !empty($_GET['url']) ? $_GET['url'] : ''; ?>"/>
        <input type="submit"/>
    </form>
<?php if (!empty($metaElements)) { ?>
    <table border="1">
        <tr>
            <th>Tag</th>
            <th>Length</th>
            <th>Text</th>
        </tr>
        <?php
        foreach ($metaElements as $key => $value) {
            if ($key != 'content') { //nu generam un rand nou in tabel pentru content-ul paginii
                ?>
                <tr>
                    <td>
                        <b><?php echo ucfirst($key); ?></b><br>
                        <?php echo empty($value) ? ucfirst($key).' empty' : ''; ?>
                    </td>
                    <td>
                        <?php if (!empty($value)) {
                            $stats = stats($value, $metaElements['content']);
                            echo $stats;
                        } ?>
                    </td>
                    <td>
                        <?php if (!empty($value)) {
                            echo $value;
                        } ?>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
<?php } ?>