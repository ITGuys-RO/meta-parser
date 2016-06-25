<?php
/**
 * Created by Catalin Teodorescu on 22-May-16 00:26.
 */
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/Utils.php';

if (!empty($_GET['url']) && (filter_var($_GET['url'], FILTER_VALIDATE_URL) !== false)) {
    $metaElements = Utils::parser($_GET['url']);
} else {
    dump('Please provide a valid URL');
}
?>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="apple-touch-icon" sizes="57x57" href="/images/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/images/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/images/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/images/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/images/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/images/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/images/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/images/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/images/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/images/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
</head>
<div class="container">
    <form method="GET" action="" class="form-inline text-center">
        <div class="form-group">
            <label for="url">URL</label>
            <input type="text" name="url" placeholder="ex: http://autovit.ro" value="<?php echo !empty($_GET['url']) ? $_GET['url'] : ''; ?>" class="form-control"/>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <?php if (!empty($metaElements)) { ?>
        <table class="table table-responsive table-bordered table-condensed table-hover">
            <tr>
                <th>Tag</th>
                <th>Length</th>
                <th>Text</th>
            </tr>
            <?php
            foreach ($metaElements as $key => $value) {
                if ($key != 'content') {
                    ?>
                    <tr class="<?php echo empty($value) ? 'danger' : ''; ?>">
                        <td>
                            <b><?php echo ucfirst($key); ?></b><br>
                            <?php echo empty($value) ? ucfirst($key).' empty' : ''; ?>
                        </td>
                        <td>
                            <?php if (!empty($value)) {
                                $stats = Utils::stats($value, $metaElements['content']);
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
        <table class="table table-responsive table-bordered table-condensed table-hover">
            <tr>
                <th>Word</th>
                <th>Count</th>
                <th>Weight</th>
            </tr>
            <?php
            $words = Utils::weight_words($metaElements['content']);
            $nr = 0;
            foreach ($words as $word => $count) {
                $percent = 0;
                similar_text($word, $metaElements['content'], $percent);
                $density = Utils::density($count, count($words));
                $nr++;
                ?>
                <tr>
                    <td><?php echo $word; ?></td>
                    <td><?php echo $count; ?></td>
                    <td><?php echo number_format($density, 3).'%'; ?></td>
                </tr>
                <?php
//                }
            }
            ?>
        </table>
    <?php } ?>
</div>
<!-- Latest compiled and minified JavaScript -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
