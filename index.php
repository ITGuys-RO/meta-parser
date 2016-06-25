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
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
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
                if ($key != 'content') { //nu generam un rand nou in tabel pentru content-ul paginii
                    ?>
                    <tr class="<?php echo empty($value) ? 'danger' : ''; ?>">
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
        <table class="table table-responsive table-bordered table-condensed table-hover">
            <tr>
                <th>Word</th>
                <th>Count</th>
                <th>Weight</th>
            </tr>
            <?php
            $words = weight_words($metaElements['content']);
            $nr = 0;
            foreach ($words as $word => $count) { //iteram array-ul cu toate cuvintele
//                if ($nr < 10) { //ne oprim la al 10-lea
                $percent = 0;
                similar_text($word, $metaElements['content'], $percent); //se calculeaza relevanta cuvantului fata de tot textul
                $density = density($count, count($words));
                $nr++; //se incrementeaza pentru a numara cate cuvinte am afisat pana acum
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
