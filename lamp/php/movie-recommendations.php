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
<script src="/javascript/movie-recommendations.js" type="text/javascript"></script>

<STYLE TYPE="text/css"><!--
body {
   font-family:Verdana, Trebuchet MS;
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
    
echo "<p>If you provide the name of a movie you like then this website will return a list of recommended movies that the k-means machine learning algorithm has categorized in the same category as the movie you entered.</p>\n";
    
echo "<form method='post' name='mov_rec' id='mov_rec'>\n";
echo "   <table>\n";
echo "      <tr>\n";
echo "         <td><label for='mtitle'>Movie you like:</label></td>\n";
echo "         <td><input type='text' id='mtitle' name='mtitle' value='$_POST[mtitle]'></td>\n";
echo "      </tr>\n";
echo "      <tr>\n";
echo "         <td><label for='numOfRecs'>How many recommendation do you want:</label></td>\n";
echo "         <td><input type='text' id='numOfRecs' name='numOfRecs' value=10></td>\n";
echo "      </tr>\n";
echo "   </table>\n";
#echo "   <input type='submit' value='Submit'>\n";
echo "   <input type='button'  value='Submit Preference' id='validate' />\n";
#echo "   <p><button id='validate'>Submit Preference</button></p>\n";
echo "</form>";
    
if (isset($_POST['numOfRecs']) && ( filter_var($_POST['numOfRecs'], FILTER_VALIDATE_INT) === false )) {
  echo "<p><font color=red><b>The value entered for the number of recommendations must be an integer.</b></font></p>\n";
}
elseif (isset($_POST['mtitle']) && isset($_POST['numOfRecs'])) {
    try {
        if (isset($_POST['title_id']) && isset($_POST['exact']) && $_POST['exact'] == 'true') {
           $query = "SELECT count(*) AS numRows FROM movie_categories WHERE title_id = ?";
           $sth = $dbh->prepare("$query");
           $sth->bindValue(1, "$_POST[title_id]");
        } else {
           $query = "SELECT count(*) AS numRows FROM movie_categories WHERE title LIKE ?";
           $sth = $dbh->prepare("$query");
           $sth->bindValue(1, "%$_POST[mtitle]%", PDO::PARAM_STR);
        }
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $numRows = $row['numRows'];
    } catch (PDOException $e) {
        //If mysql query was unsuccessful, output error
        echo "$query exception: " . $e->getMessage() . "!<br>\n";
        exit();
    }
    
    if ($numRows == 0) {
        zeroMovies($_POST['mtitle']);
    }
    elseif ($numRows == 1) {
        oneMovie($_POST['mtitle']);
    }
    elseif ($numRows > 1) {
        manyMovies($_POST['mtitle']);
    }
}
    
function zeroMovies($movieTitle)
{
    echo "<p><font color=navy><b>The movie you entered, $movieTitle, was not found in the movie recommendation database.  Please try again.</b></font></p>\n";
}

function oneMovie($movieTitle)
{
    global $dbh;
        
    echo "<p><font color=navy><b>Here are your recommended movies based upon you like $movieTitle</b></font></p>\n";
    
    try {
        if (isset($_POST['title_id']) && isset($_POST['exact']) && $_POST['exact'] == 'true') {
           $query1 = "SELECT labels FROM movie_categories WHERE title_id = ?";
           $sth1 = $dbh->prepare("$query1");
           $sth1->bindValue(1, "$_POST[title_id]");
        } else {
           $query1 = "SELECT labels FROM movie_categories WHERE title LIKE ?";
           $sth1 = $dbh->prepare("$query1");
           $sth1->bindValue(1, "%$movieTitle%", PDO::PARAM_STR);
        }
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
            # this code works, but is open to sql injection
            #$query2 = "SELECT title FROM movie_categories WHERE labels=$category ORDER BY RAND() LIMIT $_POST[numOfRecs]";
            #$sth2 = $dbh->query("$query2");
            
            # this code works, see how to apply bindvalue method in limit clause
            # https://stackoverflow.com/questions/2269840/how-to-apply-bindvalue-method-in-limit-clause
            $query2 = "SELECT title FROM movie_categories WHERE labels=:label ORDER BY RAND() LIMIT :limit";
            $sth2 = $dbh->prepare("$query2");
            $sth2->bindValue(':label', $category);
            $sth2->bindValue(':limit', (int)$_POST['numOfRecs'], PDO::PARAM_INT);
            $sth2->execute();

            while($row = $sth2->fetch(PDO::FETCH_ASSOC)) {
               echo "$row[title]<br>\n";
            }
        } catch (PDOException $e) {
            //If mysql query was unsuccessful, output error
            echo "$query2 exception: " . $e->getMessage() . "!<br>\n";
            exit();
        }
    }
}

function manyMovies($movieTitle)
{
    global $dbh;
    
    echo "<p><font color=navy><b>The movie you entered, $movieTitle, has several entries in the recommendation database.  Please select the specific movie you like.</b></font></p>\n";
    
    try {
        $query = "SELECT title_id, title FROM movie_categories WHERE title LIKE ?";
        $sth = $dbh->prepare("$query");
        $sth->bindValue(1, "%$movieTitle%", PDO::PARAM_STR);
        $sth->execute();
        $formCnt = 0;
        while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
           $formName = "titleForm" . $formCnt;
           echo "<form method='post' name='$formName'>\n";
           echo "<p>\n";
           echo "   <input type='hidden' id='title_id' name='title_id' value='$row[title_id]'>\n";
           echo "   <input type='hidden' id='mtitle' name='mtitle' value='$row[title]'>\n";
           echo "   <input type='hidden' id='numOfRecs' name='numOfRecs' value='$_POST[numOfRecs]'>\n";
           echo "   <input type='hidden' id='exact' name='exact' value='true'>\n";
           echo "   <script type='text/javascript'>\n";
           echo "      document.write('<a href=\"\" onclick=\"javascript:document.$formName.submit();return false;\">$row[title]<\/a>');\n";
           echo "   </script>\n";
           echo "</p>\n";
           echo "</form>\n";
           $formCnt++;
        }
    } catch (PDOException $e) {
        //If mysql query was unsuccessful, output error
        echo "$query1 exception: " . $e->getMessage() . "!<br>\n";
        exit();
    }
}
?>

</center>
</body>
</html>