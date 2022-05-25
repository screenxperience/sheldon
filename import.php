<?php
require($_SERVER['DOCUMENT_ROOT'].'/include/auth.inc.php');

require($_SERVER['DOCUMENT_ROOT'].'/include/config.inc.php');

$output = '';

$sql = mysqli_connect($app_sqlhost,$app_sqluser,$app_sqlpasswd,$app_sqldb);

if(!$sql)
{
	$output .= '<div class="container">';
	$output .= '<div class="content-center container white-alpha">';
	$output .= '<h1>Error</h1>';
	$output .= '<div class="panel black-alpha">';
	$output .= '<p>Es konnte keine Datenbankverbindung hergestellt werden.</p>';
	$output .= '</div>';
	$output .= '</div>';
	$output .= '</div>';
}
else
{
	$sql->query('SET NAMES UTF8');

    if(empty($_GET) && empty($_POST))
    {
        $output .= '<div class="container">';
        $output .= '<div class="content-center container white-alpha">';
        $output .= '<h1>Error</h1>';
        $output .= '<div class="panel black-alpha">';
        $output .= '<p>Es wurden keine Daten gesendet.</p>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
    }
    else
    {
        if(!empty($_POST))
        {
            if(empty($_POST['category']) || empty($_FILES['importfile']['tmp_name']))
            {
                $output .= '<div class="container">';
                $output .= '<div class="content-center container white-alpha">';
                $output .= '<h1>Error</h1>';
                $output .= '<div class="panel black-alpha">';
                $output .= '<p>Es konnte kein Import gestartet werden.</p>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';
            }
            else
            {
                if(preg_match('/[^a-z]/',$_POST['category']) == 0)
                {
                    $allowed_category = array('asset','user');

                    if(in_array($_POST['category'],$allowed_category))
                    {
                        $mime_type = $_FILES['importfile']['type'];

                        if($mime_type == 'application/vnd.ms-excel')
                        {
                            $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/upload/import/';

                            $uploadtime = strtotime('now');

                            $filename_new = $uploaddir.$uploadtime.'_import.csv';

                            if(move_uploaded_file($_FILES['importfile']['tmp_name'],$filename_new))
                            {
                                $csv_data = file_get_contents($filename_new);

                                if(!empty($csv_data))
                                {
                                    if($_POST['category'] == $allowed_category[0])
                                    {
                                        if(preg_match('/[^A-Z0-9\-\.\;\r\n]/',$csv_data) == 0)
                                        {
                                            $asset_cis = json_encode(array());

                                            $j = 0;

                                            $lines = explode("\r\n",$csv_data);

                                            $query = "
                                            INSERT INTO asset
                                            (asset_type_id,asset_vendor_id,asset_model_id,asset_serial,asset_cis)
                                            VALUES ";

                                            for($i = 1; $i < count($lines); $i++)
                                            {
                                                $cols = explode(';',$lines[$i]);
                                                
                                                $col_str = implode("",$cols);

                                                if($col_str != "")
                                                {
                                                    $query .= "('".$cols[0]."','".$cols[1]."','".$cols[2]."','".$cols[3]."','".$asset_cis."'),";

                                                    $j++;
                                                }
                                            }

                                            $query = substr($query,0,strlen($query)-1);

                                            $sql->query($query);

                                            if($sql->affected_rows > 0)
                                            {
                                                $output .= '<div class="container">';
                                                $output .= '<div class="content-center container white-alpha">';
                                                $output .= '<div class="panel black-alpha">';
                                                $output .= '<p>Assets wurden importiert.</p>';
                                                $output .= '</div>';
                                                $output .= '</div>';
                                                $output .= '</div>';
                                            }
                                            else
                                            {
                                                $output .= '<div class="container">';
                                                $output .= '<div class="content-center container white-alpha">';
                                                $output .= '<div class="panel black-alpha">';
                                                $output .= '<p>Import fehlgeschlagen.</p>';
                                                $output .= '</div>';
                                                $output .= '</div>';
                                                $output .= '</div>';
                                            }
                                        }
                                        else
                                        {
                                            $output .= '<div class="container">';
                                            $output .= '<div class="content-center container white-alpha">';
                                            $output .= '<h1>Error</h1>';
                                            $output .= '<div class="panel black-alpha">';
                                            $output .= '<p>Ung&uuml;ltige Zeichen innerhalb der Datei erkannt.</p>';
                                            $output .= '</div>';
                                            $output .= '</div>';
                                            $output .= '</div>';
                                        }
                                    }
                                }
                                else
                                {
                                    $output .= '<div class="container">';
                                    $output .= '<div class="content-center container white-alpha">';
                                    $output .= '<h1>Error</h1>';
                                    $output .= '<div class="panel black-alpha">';
                                    $output .= '<p>Es konnten keine Daten erkannt werden.</p>';
                                    $output .= '</div>';
                                    $output .= '</div>';
                                    $output .= '</div>';
                                }
                            }
                            else
                            {
                                $output .= '<div class="container">';
                                $output .= '<div class="content-center container white-alpha">';
                                $output .= '<h1>Error</h1>';
                                $output .= '<div class="panel black-alpha">';
                                $output .= '<p>Datei wurde nicht hochgeladen.</p>';
                                $output .= '</div>';
                                $output .= '</div>';
                                $output .= '</div>';
                            }
                        }
                        else
                        {
                            $output .= '<div class="container">';
                            $output .= '<div class="content-center container white-alpha">';
                            $output .= '<h1>Error</h1>';
                            $output .= '<div class="panel black-alpha">';
                            $output .= '<p>Verwenden Sie eine Datei vom Typ application/vnd.ms-excel (.csv)</p>';
                            $output .= '</div>';
                            $output .= '</div>';
                            $output .= '</div>';
                        }
                    }
                    else
                    {
                        $output .= '<div class="container">';
                        $output .= '<div class="content-center container white-alpha">';
                        $output .= '<h1>Error</h1>';
                        $output .= '<div class="panel black-alpha">';
                        $output .= '<p>Die gew&auml;hlte Kategorie kann nicht bearbeitet werden.</p>';
                        $output .= '</div>';
                        $output .= '</div>';
                        $output .= '</div>';
                    }
                }
                else
                {
                    $output .= '<div class="container">';
                    $output .= '<div class="content-center container white-alpha">';
                    $output .= '<h1>Error</h1>';
                    $output .= '<div class="panel black-alpha">';
                    $output .= '<p>Die gew&auml;hlte Kategorie kann nicht bearbeitet werden.</p>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                }
            }
        }

        if(!empty($_GET))
        {
            if(empty($_GET['category']))
            {
                $output .= '<div class="container">';
                $output .= '<div class="content-center container white-alpha">';
                $output .= '<h1>Error</h1>';
                $output .= '<div class="panel black-alpha">';
                $output .= '<p>Es wurde keine Kategorie gesendet.</p>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';
            }
            else
            {
                if(preg_match('/[^a-z]/',$_GET['category']) == 0)
                {
                    $allowed_category = array('asset','user');

                    if(in_array($_GET['category'],$allowed_category))
                    {
                        $output .= '<div class="container">';
                        $output .= '<div class="panel white-alpha">';
                        $output .= '<h1>Import</h1>';
                        $output .= '<form action="import.php" method="post" enctype="multipart/form-data">';
                        $output .= '<input type="hidden" name="category" value="'.$_GET['category'].'"/>';
                        $output .= '<ul class="flex section">';
                        $output .= '<li class="col-s10 col-m10 col-l10">';
                        $output .= '<input class="input-default" style="height:53px;" type="file" name="importfile" accept=".csv">';
                        $output .= '</li>';
                        $output .= '<li class="col-s2 col-m2 col-l2">';
                        $output .= '<button class="block btn-default border border-light-blue light-blue hover-white hover-text-blue" style="height:53px;" type="submit"><i class="fas fa-arrow-right"></i></button>';
                        $output .= '</li>';
                        $output .= '</ul>';
                        $output .= '</form>';
                        $output .= '</div>';
                        $output .= '</div>';
                    }
                    else
                    {
                        $output .= '<div class="container">';
                        $output .= '<div class="content-center container white-alpha">';
                        $output .= '<h1>Error</h1>';
                        $output .= '<div class="panel black-alpha">';
                        $output .= '<p>Es k&ouml;nnen nur Assets oder User importiert werden.</p>';
                        $output .= '</div>';
                        $output .= '</div>';
                        $output .= '</div>';
                    }
                }
                else
                {
                    $output .= '<div class="container">';
                    $output .= '<div class="content-center container white-alpha">';
                    $output .= '<h1>Error</h1>';
                    $output .= '<div class="panel black-alpha">';
                    $output .= '<p>Es k&ouml;nnen nur Assets oder User importiert werden.</p>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                }
            }
        }
    }
}
?>
<!DOCTYPE HTML>
<html lang="de">
	<head>
		<title>Sheldon #Import</title>
		<?php
		require($_SERVER['DOCUMENT_ROOT'].'/include/head.inc.php');
		?>
	</head>
	<body>
	<?php
	require($_SERVER['DOCUMENT_ROOT'].'/include/body.inc.php');
	?>
	</body>
</html>
