<?php
require($_SERVER['DOCUMENT_ROOT'].'/include/auth.inc.php');

require($_SERVER['DOCUMENT_ROOT'].'/include/config.inc.php');

$output = '';

$sql = mysqli_connect($app_sqlhost,$app_sqluser,$app_sqlpasswd,$app_sqldb);

if(!$sql)
{
	$output .= '<div class="text-white">Es konnte keine Datenbankverbindung hergestellt werden.</div>';
}
else
{
	$sql->query('SET NAMES UTF8');

	if(!empty($_GET))
	{
        if(empty($_GET['category']))
        {
            $output .= '<div class="text-white">Es wurde keine Kategorie gesendet.</div>';
        }
        else
        {
            if(preg_match('/[^a-z]/',$_GET['category'])== 0)
            {
                $allowed_category = array('lend','asset','user');

                if(in_array($_GET['category'],$allowed_category))
                {
                    if($_GET['category'] == $allowed_category[0])
                    {
                        $output .= '<form action="../search.php" method="get" target="_top">';
                        $output .= '<input type="hidden" name="category" value="lend"/>';
                        $output .= '<input class="input-default border border-grey focus-border-light-blue" type="number" name="filter[]" placeholder="Belegnummer"/>';
                        $output .= '<p><input class="input-default border border-grey focus-border-light-blue" type="number" name="filter[]" placeholder="Personalnummer"/></p>';
                        $output .= '<p><input class="input-default border border-grey focus-border-light-blue" type="text" name="filter[]" placeholder="Vorname"/></p>';
                        $output .= '<p><input class="input-default border border-grey focus-border-light-blue" type="text" name="filter[]" placeholder="Nachname"/></p>';
                        $output .= '<p><input class="input-default border border-grey focus-border-light-blue" type="text" name="filter[]" placeholder="Seriennummer"/></p>';
                        $output .= '<p><button class="btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit">suchen <i class="fas fa-search"></i></button></p>';
                        $output .= '</form>';
                    }
                    else if($_GET['category'] == $allowed_category[1])
                    {
                        $output .= '<form action="../search.php" method="get" target="_top">';
                        $output .= '<input type="hidden" name="category" value="asset"/>';
                        $output .= '<select class="input-default border border-grey focus-border-light-blue" name="filter[]">';
                        $output .= '<option value="">Typ</option>';
                        
                        $query = "
                        SELECT type_id,type_name
                        FROM type";

                        $result = $sql->query($query);

                        while($row = $result->fetch_array(MYSQLI_ASSOC))
                        {
                            $output .= '<option value="'.$row['type_id'].'">'.$row['type_name'].'</option>';
                        }

                        $output .= '</select>';
                        $output .= '<p><select class="input-default border border-grey focus-border-light-blue" name="filter[]">';
                        $output .= '<option value="">Hersteller</option>';
                        
                        $query = "
                        SELECT vendor_id,vendor_name
                        FROM vendor";

                        $result = $sql->query($query);

                        while($row = $result->fetch_array(MYSQLI_ASSOC))
                        {
                            $output .= '<option value="'.$row['vendor_id'].'">'.$row['vendor_name'].'</option>';
                        }

                        $output .= '</select></p>';
                        $output .= '<p><select class="input-default border border-grey focus-border-light-blue" name="filter[]">';
                        $output .= '<option value="">Modell</option>';
                        
                        $query = "
                        SELECT model_id,model_name
                        FROM model";

                        $result = $sql->query($query);

                        while($row = $result->fetch_array(MYSQLI_ASSOC))
                        {
                            $output .= '<option value="'.$row['model_id'].'">'.$row['model_name'].'</option>';
                        }

                        $output .= '</select></p>';
                        $output .= '<p><input class="input-default border border-grey focus-border-light-blue" type="text" name="filter[]" placeholder="Seriennummer"/></p>';
                        $output .= '<p><button class="btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit">suchen <i class="fas fa-search"></i></button></p>';
                        $output .= '</form>';
                    }
                    else if($_GET['category'] == $allowed_category[2])
                    {
                        $output .= '<form action="../search.php" method="get" target="_top">';
                        $output .= '<input type="hidden" name="category" value="user"/>';
                        $output .= '<input class="input-default border border-grey focus-border-light-blue" type="number" name="filter[]" placeholder="Personalnummer"/>';
                        $output .= '<p><input class="input-default border border-grey focus-border-light-blue" type="text" name="filter[]" placeholder="Vorname"/></p>';
                        $output .= '<p><input class="input-default border border-grey focus-border-light-blue" type="text" name="filter[]" placeholder="Nachname"/></p>';
                        $output .= '<p><input class="input-default border border-grey focus-border-light-blue" type="email" name="filter[]" placeholder="E-Mail-Adresse"/></p>';
                        $output .= '<p><button class="btn-default border border-light-blue light-blue hover-white hover-text-blue" type="submit">suchen <i class="fas fa-search"></i></button></p>';
                        $output .= '</form>';
                    }
                }
                else
                {
                    $output .= '<div class="text-white">Die gew&auml;hlte Kategorie kann nicht bearbeitet werden.</div>';
                }
            }
            else
            {
                $output .= '<div class="text-white">Die gew&auml;hlte Kategorie kann nicht bearbeitet werden.</div>';
            }
        }
    }
    else
    {
        $output .= '<div class="text-white">Es wurden keine Daten gesendet.</div>';
    }
}
?>
<!DOCTYPE HTML>
<html lang="de">
	<head>
		<title>Sheldon #Filter</title>
		<?php
		require($_SERVER['DOCUMENT_ROOT'].'/include/head.inc.php');
		?>
	</head>
	<body>
	<?php
    if(!empty($output))
    {
        echo $output;
    }
    ?>
	</body>
</html>