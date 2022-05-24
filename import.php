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

                    if($_GET['category'] == $allowed_category[0])
                    {
                      $output .= '<h1>Assetimport</h1>';
                    }
                    else if($_GET['category'] == $allowed_category[1])
                    {
                        $output .= '<h1>Userimport</h1>';
                    }

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
