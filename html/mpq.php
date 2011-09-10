<?php
ini_set('display_errors', 1);
error_reporting(E_ALL | E_NOTICE);
require_once('exo/init.php');

/**
 * Get list of all STL files and output them:
 */
class MPQ_Reader
{
    const DEFAULT_MPQ_DIR = '../data/mpq';

    /**
     * Get list of all STL files
     *
     * @param string $dir (optional) path of STL files
     * @return array of objects with attributes:
     *      $id - file identifier string
     *      $filename - filename with extension
     *      $path - path to file
     */
    public function get_stl_files($dir = NULL)
    {
        if ($dir === NULL) { $dir = self::DEFAULT_MPQ_DIR; }

        // holder of results
        $files = array();

        $dh = opendir($dir);
        $count = 0;

        $files = array();

        while ($file = readdir($dh))
        {
            if (substr($file, 0, 1) == '.' || substr($file, -3, 3) != 'stl') { continue; }
            $count++;
            $path = $dir . '/' . $file;

            $parts = explode('.', $file);
            $name = $parts[0];

            $obj = new stdClass;
            $obj->id = $name;
            $obj->filename = $file;
            $obj->path = $path;

            $files[] = $obj;
        }

        return $files;
    }

    /**
     * Get a specific stl file
     * 
     * @param string $id file identifier name
     * @return file object or NULL on failure
     */
    public function get_stl_file($id)
    {
        $files = $this->get_stl_files();
        foreach ($files as $file)
        {
            if ($file->id == $id)
            {
                return $file;
            }
        }
        return NULL;
    }
}

$reader = new MPQ_Reader();

// files to show
$visible = array(
    'Items',
    'ItemDescription',
    'ItemEnchancements',
    'ItemFlavor',
    'ItemInstructions',
    'ItemPowers',
    'ItemQuality',
    'ItemSets',
    'ItemSlots',
    'ItemTypeNames'    
);

// additional notes
$notes = array(
    'Items' => 'Parsed out item IDs and names from this file successfully, but have found other sites with gold/sockets/swingspeed/quality information-- did they get it from here?'
);

// if they're requesting a file, give it to them
if (isset($_GET['q']))
{
    $file = $reader->get_stl_file($_GET['q']);
    if ($file)
    {
        // We'll be outputting an STL file
        header('Content-type: text/stl');

        // It will be called downloaded.pdf
        header('Content-Disposition: attachment; filename="' . $file->filename . '"');

        print(file_get_contents($file->path));
        exit();
    }
    header("Location: /mpq.php");
    exit();
}

$view = new Exo_View();
$view->theme = 'horadric';

$data = array(
    '_title' => 'STL Files',
    '_description' => 'Help me find the patterns/code in these files!'
);

$view->render('inc/header', $data);
?>
<h1>Horadric STL Files</h1>
<p>The following files are from the Diablo 3 MPQ data file.  Help me try to find the patterns of these files so that I can write parsers for all of them! Some files may have notes beside them as I progress through this massive wall of text, others may be really easy to figure out but I haven't gotten to them yet.</p>
<p><strong style="color: #f00;">The giant current dilemma I'm dealing with is Items.STL's first half. I'm trying to figure out the pattern of all that binary information.</strong></p>

<?php $files = $reader->get_stl_files(); ?>
<table class="database">
    <thead>
        <tr>
            <th>ID</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($files as $file): ?>
        <?php if (!in_array($file->id, $visible)) { continue; } ?>
            <tr>
                <td><a href="?q=<?= $file->id ?>"><?= $file->id ?></a></td>
                <td class="notes"><?= isset($notes[$file->id]) ? $notes[$file->id] : '' ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
$view->render('inc/footer');
