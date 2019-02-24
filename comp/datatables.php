<!--<link rel="stylesheet" type="text/css" href="packages/DataTables/DataTables-1.10.16/css/jquery.dataTables.css">-->
<link rel="stylesheet" type="text/css" href="packages/DataTables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
<!-- jQuery -->
<!--<script type="text/javascript" charset="utf8" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>-->
 
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="packages/DataTables/DataTables-1.10.16/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="packages/DataTables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>

<?php
require('nachtzug.php');
//var_dump($_POST);
if (isset($_POST['querykey']) and isset($_POST['disease'])) {
  if ( (!empty($_POST['querykey']) and !ctype_space($_POST['querykey'])) or ( !empty($_POST['disease']) and !ctype_space($_POST['disease']) ) ) {
    echo '<script>$("#search-brief-card").show();</script>';
    echo '<script>$("#results-table-card").show();</script>';
    if ( empty($_POST['disease']) or ctype_space($_POST['disease']) ) {
      //echo $_POST['querykey'];
      $stmt = $conn->prepare("SELECT * FROM `entry_detail_map` WHERE (`identifier` LIKE ?) or (`uniprot` LIKE ?)");
      $stmt->bind_param("ss", $querykey, $querykey);
      $querykey = $_POST['querykey'].'%';
      $stmt->execute();
      $res = $stmt->get_result();
      //var_dump($res);
      if (! $res) {
        die('Failed' . mysqli_error($conn));
      } else {

        
        
          echo <<<EOF
          <table id="search_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>Name</th>
      <th>Involvement in disease</th>
      <th>Isoforms</th>
      <th>PubMed</th>
      <th>Network</th>
      <th>Structure</th>
    </tr>
  </thead>
  <tbody>
EOF;
            $form_count = 1;
            while ($row = $res->fetch_assoc()){
          
          
            echo '<tr>
      <td><form name="form'.$form_count.'" id="form'.$form_count.'" action="index.php?c=detail" method="post">
    <input id="form'.$form_count.'-input" type="hidden" name="simplequery" value="'.$row['identifier'].'">
  <a href="javascript:void(0);" onclick="document.form'.$form_count.'.submit();">'.$row['identifier'].'</a>
</form></td>
      <td>'.$row['inv_diseases'].'</td><td>'.$row['isoforms'].'</td><td>'.$row['others_mimic'].'</td><td>'.$row['others_mimic'].'</td><td>'.$row['others_mimic'].'</td>
    </tr>';
          
          $form_count++;
          }
        echo '</tbody>
</table>';
        
        
        
      }
      
      
    }
    elseif ( empty($_POST['querykey']) or ctype_space($_POST['querykey']) ) {
      //echo $_POST['disease'];
      $stmt = $conn->prepare("SELECT * FROM `entry_detail_map` WHERE (`inv_diseases` LIKE ?)");
      $stmt->bind_param("s", $querydisease);
      $querydisease = $_POST['disease'].'%';
      $stmt->execute();
      $res = $stmt->get_result();
      //var_dump($res);
      if (! $res) {
        die('Failed' . mysqli_error($conn));
      } else {
        
        
          echo <<<EOF
          <table id="search_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>Name</th>
      <th>Involvement in disease</th>
      <th>Isoforms</th>
      <th>PubMed</th>
      <th>Network</th>
      <th>Structure</th>
    </tr>
  </thead>
  <tbody>
EOF;
          $form_count = 1;
          while ($row = $res->fetch_assoc()) {
            echo '<tr>
      <td><form name="form'.$form_count.'" id="form'.$form_count.'" action="index.php?c=detail" method="post">
    <input id="form'.$form_count.'-input" type="hidden" name="simplequery" value="'.$row['identifier'].'">
  <a href="javascript:void(0);" onclick="document.form'.$form_count.'.submit();">'.$row['identifier'].'</a>
</form></td>
      <td>'.$row['inv_diseases'].'</td><td>'.$row['isoforms'].'</td><td>'.$row['others_mimic'].'</td><td>'.$row['others_mimic'].'</td><td>'.$row['others_mimic'].'</td>
    </tr>';
          
          $form_count++;
          }
          echo '</tbody>
</table>';
        
      }
    } else {
      //echo $_POST['querykey'].'  '.$_POST['disease'];
      $stmt = $conn->prepare("SELECT * FROM `entry_detail_map` WHERE (`identifier` LIKE ? or `uniprot` LIKE ?) AND `inv_diseases` LIKE ?");
      $stmt->bind_param("sss", $querykey, $querykey, $querydisease);
      $querykey = $_POST['querykey'].'%';
      $querydisease = $_POST['disease'].'%';
      $stmt->execute();
      $res = $stmt->get_result();
      //var_dump($res);
      if (! $res) {
        die('Failed' . mysqli_error($conn));
      } else {
        
          echo <<<EOF
          <table id="search_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>Name</th>
      <th>Involvement in disease</th>
      <th>Isoforms</th>
      <th>PubMed</th>
      <th>Network</th>
      <th>Structure</th>
    </tr>
  </thead>
  <tbody>
EOF;
          $form_count = 1;
          while ($row = $res->fetch_assoc()) {
            echo '<tr>
      <td><form name="form'.$form_count.'" id="form'.$form_count.'" action="index.php?c=detail" method="post">
    <input id="form'.$form_count.'-input" type="hidden" name="simplequery" value="'.$row['identifier'].'">
  <a href="javascript:void(0);" onclick="document.form'.$form_count.'.submit();">'.$row['identifier'].'</a>
</form></td>
      <td>'.$row['inv_diseases'].'</td><td>'.$row['isoforms'].'</td><td>'.$row['others_mimic'].'</td><td>'.$row['others_mimic'].'</td><td>'.$row['others_mimic'].'</td>
    </tr>';
          
          $form_count += 1;
          }
        echo '</tbody>
</table>';
        
      }
    }
  } else {
    # code...
  }
  
} else {
  # code...
}

?>



<!--<table id="search_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>C1</th>
      <th>C2</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>R1D1</td>
      <td>R1D2</td>
    </tr>
    <tr>
      <td>R2D1</td>
      <td>R2D2</td>
    </tr>
  </tbody>
</table>
-->
<script>
  $(document).ready(
    function () {
      $('#search_table').DataTable();
    }
  );
</script>
