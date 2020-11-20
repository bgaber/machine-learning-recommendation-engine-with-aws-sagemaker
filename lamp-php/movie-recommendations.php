<!DOCTYPE html>
<html>
<head>
	<meta charset=utf-8>
	<title>SageMaker Machine Learning Movie Recommendations Webpage</title>

<?php
$theme_arr = array('black-tie','blitzer','cupertino','dot-luv','ui-lightness','sunny','start','redmond','pepper-grinder','le-frog','humanity','eggplant');
$theme = $theme_arr[date("n")-1];
echo "<link rel='stylesheet' type='text/css' href='//code.jquery.com/ui/1.11.4/themes/$theme/jquery-ui.css' />";
?>

<!-- Favicon (optional) begins / D#233;but du favicon (optionnel) -->
<link rel="SHORTCUT ICON" href="/images/favicon.ico"/>
<!-- Favicon (optional) ends / Fin du favicon (optionnel) -->

<script src="https://code.jquery.com/jquery-1.12.4.min.js"
			  integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
			  crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
			  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
			  crossorigin="anonymous"></script>

<STYLE TYPE="text/css"><!--
body {
   font-family:Verdana, Trebuchet MS;
   font-size:12px;
}

.btn {
   font-family:Verdana, Trebuchet MS;
   font-size:12px;
   color:black;
   border:1px solid #000000;
   margin-top:5px;
   background-color: #FFD60D
}

li {
   font-family:Trebuchet MS, Verdana;
   font-size:12px;
}

p {
   font-family:Trebuchet MS, Verdana;
   font-size:14px;
}

.ui-dialog .ui-dialog-titlebar, .ui-dialog .ui-dialog-buttonpane, .ui-dialog .ui-dialog-content {
	font-size: 12px;
}

.ui-widget button {
	font-size: 14px;
}
--></STYLE>
</head>
<body>
    
<center>
	<p>
		<font size=+1 color=navy>
			Brian's Amazing, Mystifying, Captivating AWS SageMaker Machine Learning K-Means Movie Recommendation System<br>
		</font>
	</p>

<?php
require_once ('../php/pdo_connect.php'); // Connect to the db.
    
echo "<p>If you provide the name of a movie you like then this website will return a list of recommended movies that the k-means machine learning algorithm has categorized in the same category as the movie you entered.</p>";
    
echo "<form method='post' name='mov_rec' id='mov_rec'>";
echo "<table>";
echo "<tr>";
echo "<td><label for='mtitle'>Movie you like:</label></td>";
echo "<td><input type='text' id='mtitle' name='mtitle' value='$_POST[mtitle]'></td>";
echo "</tr>";
echo "<tr>";
echo "<td><label for='numOfRecs'>How many recommendation do you want:</label></td>";
echo "<td><input type='text' id='numOfRecs' name='numOfRecs' value=10></td>";
echo "</tr>";
echo "</table>";
echo "<input type='submit' value='Submit'>";
echo "</form>";
    
if (isset($_POST['numOfRecs']) && ( filter_var($_POST['numOfRecs'], FILTER_VALIDATE_INT) === false )) {
  echo "The value entered for the number of recommendations must be an integer.";
}
elseif (isset($_POST['mtitle']) && isset($_POST['numOfRecs'])) {
    echo "<p><font color=navy><b>Here are your recommended movies based upon you like $_POST[mtitle]</b></font></p>";

    try {
        $query1 = "SELECT labels FROM movie_categories WHERE title LIKE ?";
        $sth1 = $dbh->prepare("$query1");
        $sth1->bindValue(1, "%$_POST[mtitle]%", PDO::PARAM_STR);
        $sth1->execute();
        $row = $sth1->fetch(PDO::FETCH_ASSOC);
        $category = $row['labels'];
    } catch (PDOException $e) {
        //If mysql query was unsuccessful, output error
        echo "$query1 exception: " . $e->getMessage() . "!<br>\n";
        exit();
    }
    
    # Need to add code to see if previous query return a valid value for category, i.e. does the movie title exist in the database
    if ($row) {  
        try {
            $query2 = "SELECT title FROM movie_categories WHERE labels=$category ORDER BY RAND() LIMIT $_POST[numOfRecs]";
            #echo "$query2<br>";
            $sth2 = $dbh->query("$query2");

            while($row = $sth2->fetch(PDO::FETCH_ASSOC)) {
               echo "$row[title]<br>";
            }
        } catch (PDOException $e) {
            //If mysql query was unsuccessful, output error
            echo "$query2 exception: " . $e->getMessage() . "!<br>\n";
            exit();
        }
    }
    else {
        echo "<p><font color=navy><b>The movie you entered, $_POST[mtitle], was not found in the movie recommendation database.  Please try again.</b></font></p>";
    }
}
?>

</center>
</body>
</html>