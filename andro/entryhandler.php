<?php
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "androclients";
$alternatedb="searchdb";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
$alt_conn = mysqli_connect($servername, $username, $password, $alternatedb);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if(!$alt_conn){
  die("Connection failed: " . mysqli_connect_error());
}
if(isset($_POST['client_ent'])){
$user=$_SESSION['username'];
$tablename=$_POST['client']."table";
strtolower($tablename);
$tableexists=false;
$check_query="SHOW TABLES LIKE '$tablename'";
$c=mysqli_query($conn,$check_query);
if(mysqli_num_rows($c)>1)
  $tableexists=true;
if(!$tableexists){
    $init="
      CREATE TABLE `$tablename` (
      `partValue` varchar(50)NOT NULL,
      `Package` varchar(100) NOT NULL,
      `type` varchar(50) NOT NULL,
      `qty` int(11) NOT NULL,
      `qtypb` int(11) NOT NULL,
      `comments` varchar(200),
      primary key (`partValue`,`Package`)
    )ENGINE=InnoDB DEFAULT CHARSET=latin1;";
    mysqli_query($conn,$init);
    $addToSearchDB="INSERT INTO `details` (`tablename`) VALUES (' $tablename ')";
    mysqli_query($alt_conn,$addToSearchDB);
}
// Include Spout library
require_once 'C:/spout-2.4.3/src/Spout/Autoloader/autoload.php';
// check file name is not empty
if (!empty($_FILES['file']['name'])) {
    // Get File extension eg. 'xlsx' to check file is excel sheet
    $pathinfo = pathinfo($_FILES["file"]["name"]);
    // check file has extension xlsx, xls and also check
    // file is not empty
   if (($pathinfo['extension'] == 'xlsx' || $pathinfo['extension'] == 'xls') && $_FILES['file']['size'] > 0 ) {
        // Temporary file name
        $inputFileName = $_FILES['file']['tmp_name'];
        // Read excel file by using ReadFactory object.
        $reader = ReaderFactory::create(Type::XLSX);
        // Open file
        $reader->open($inputFileName);
        $count = 1;
        // Number of sheet in excel file
        foreach ($reader->getSheetIterator() as $sheet) {
            // Number of Rows in Excel sheet
            foreach ($sheet->getRowIterator() as $row) {
                // It reads data after header.
                if ($count > 1) {
                    // Data of excel sheet
                    $partValue = $row[0];
                    $Package = $row[1];
                    $type = $row[2];
                    $qty = $row[3];
                    $qtypb = $row[4];
                    if($row[5])
                      $commments=$row[5];
                    else
                      $comments="-";
                    //Here, You can insert data into database.
                    $query="
                    INSERT INTO `$tablename` (`partValue`, `Package`, `type`, `qty`,`qtypb`,`comments`) VALUES ('$partValue','$Package','$type','$qty','$qtypb','$comments')
                    ON DUPLICATE KEY UPDATE `qty` = `qty`+$qty, `qtypb`=`qtypb`+$qtypb
                    ";
                    mysqli_query($conn,$query);
                    $q2="
                    INSERT INTO `masterstock` (`partValue`, `Package`, `type`, `qty`,`qtypb`,`comments`) VALUES ('$partValue','$Package','$type','$qty','$qtypb','$comments')
                    ON DUPLICATE KEY UPDATE `qty` = `qty`+$qty, `qtypb`=`qtypb`+$qtypb
                    ";

                    mysqli_query($conn,$q2);
                }
                $count++;
            }
        }
        // Close excel file
        $reader->close();
    } else {
        echo "Please Select Valid Excel File";
    }
} else {
    echo "Please Select Excel File";
}
unset($_POST['client_ent']);
echo '<script language="javascript" type="text/javascript"> ';
echo 'if(!alert("data successfully Inserted")) {' ;//msg
echo ' location.href="index_admin.php"';
echo '}';
echo '</script>';
exit;
}
?>
