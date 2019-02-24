<link rel="stylesheet" type="text/css" href="packages/DataTables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="packages/DataTables/DataTables-1.10.16/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="packages/DataTables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./">Home</a></li>
    <li class="breadcrumb-item"><a href="index.php?c=search">Search</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
</nav>

<h2 id="error-message-1" class="text-center" style="display: none;">Error Message: empty input</h2>
<h2 id="error-message-2" class="text-center" style="display: none;">Error Message: not found</h2>

<?php
require('nachtzug.php');
if (!isset($_POST['simplequery']) or $_POST['simplequery']=='') {
  echo '<script>$("#error-message-1").show();</script>';
} else {
  $stmt = $conn->prepare("SELECT * FROM `query_id` WHERE `querykey` = ?");
  $stmt->bind_param("s", $detail_query);
  $detail_query = $_POST['simplequery'];
  $stmt->execute();
  $res = $stmt->get_result();
  if (! $res) {
    die('Failed' . mysqli_error($conn));
    echo '<script>$("#error-message-2").show();</script>';
  } else {
    $row = $res->fetch_assoc();
    if ($row == NULL) {
      echo '<script>$("#error-message-2").show();</script>';
    } else {
      $fieldnames=array(
        //此处添加 detail 表的字段，另外数据库层面有映射错误
        'main_gene_name' => 'Gene name',
        'gene_names' => 'Other names',
        'prot_names' => 'Protein name',
        'inv_diseases' => 'Involvement in diseases',
        //'scl' => 'Subcellular localization',
        //'tissue_spec' => 'Tissue specificity',
        'uniprot' => 'UniProt ID',
        'isoforms' => 'Isoforms',
        'others_mimic' => 'Note',
      );
      $identifier = $row['identifier'];
      $general_info = mysqli_query($conn,"SELECT * FROM `entry_detail_map` WHERE `identifier` = '$identifier'");
      $general_row = mysqli_fetch_array($general_info);

      $ppinames = array(
        'interaction_type' => 'Interaction type',
        'num_dbs' => 'Num. of source databases',
      );
      $ppi_node = mysqli_query($conn, "SELECT node_id FROM `ppi_node` WHERE `identifier` = '$identifier'");
      while ($a = mysqli_fetch_assoc($ppi_node)) {
        $core_node = $a['node_id'];
      }
      
      //$ppi_edge = mysqli_query($conn,"SELECT * FROM `ppi_edge` WHERE `source` = '$core_node' OR; `target` = '$core_node' ");
      $ppi_info_src = mysqli_query($conn,"SELECT * FROM `ppi_edge` WHERE `source` = '$core_node'"); 
      $ppi_info_tgt = mysqli_query($conn,"SELECT * FROM `ppi_edge` WHERE `target` = '$core_node'");
      
      
      echo <<<EOF
<div class="card">
  <h5 class="card-header">Information about 
EOF;
echo $identifier;
echo <<<EOF
</h5>
  
  <div class="card-body">
  <div class="container-fluid">
    <div class="table-responsive">
  <table class="table table-bordered table-hover" cellspacing="0" width="100%">
    
    <tbody>
EOF;
foreach ($fieldnames as $key => $value) {
  switch ($value) {
    case 'UniProt ID':
      $detail_info = '<a href="https://www.uniprot.org/uniprot/'.$general_row[$key].'" target="_blank">'.$general_row[$key].'</a>';
      break;
    default:
      $detail_info = $general_row[$key];
      break;
  }
  
  echo '<tr>
        <th scope="row" class="table-info text-nowrap">'.$value.'</th>
        <td class="col-sm-10">'.$detail_info.'</td>
      </tr>'; //此处为 uniprot id 特别添加 a 标签(可能要在数据库层面操作)
}
echo <<<EOF
    </tbody>
  </table>
</div>
</div>
  </div>
</div>
<div class="divider"><p></p></div>
<div class="card">
  <h5 class="card-header">Interactions</h5> 
  <div class="card-body">
   <div class="container-fluid">
   <form name="form1" id="form1" action="index.php?c=viewnet" method="post">
    <input id="form1-input" type="hidden" name="detailquery" value="$core_node">
  <a href="javascript:void(0);" onclick="document.form1.submit();"><button type="button" class="btn btn-primary btn-sm">View network</button></a>
</form>
<div class="divider"><p></p></div>
</div>
    <table id="interaction-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr><th>Interactor</th>
EOF;

      foreach ($ppinames as $key => $value) {
        echo '<th>'.$value.'</th>';
      }
echo <<<EOF
</tr>
      </thead>
      <tbody>
EOF;

$node_label_sql = mysqli_query($conn, "SELECT node_id,identifier FROM `ppi_node`");
$node_label_array = array();
while ($node_label = mysqli_fetch_assoc($node_label_sql)) {
  $node_label_array[$node_label['node_id']] = $node_label['identifier'];
}

$disease_label_sql = mysqli_query($conn, "SELECT disease_id,disease_name FROM `disease_map`");
$disease_label_array = array();
while ($disease_label = mysqli_fetch_assoc($disease_label_sql)) {
  $disease_label_array[$disease_label['disease_id']] = $disease_label['disease_name'];
}

//var_dump($node_label_array);

//var_dump($disease_label_array);
$total_edge = array();
$i=0;

      while ($edge_src = mysqli_fetch_assoc($ppi_info_src)) {
        $interaction = array($node_label_array[$edge_src['source']],$node_label_array[$edge_src['target']],$edge_src['interaction_type'],$disease_label_array[$edge_src['edge_inv_disease']]);
        $total_edge = array_merge($total_edge,array($i => $interaction));
        $i += 1;
 
      }

      while ($edge_src = mysqli_fetch_assoc($ppi_info_tgt)) {
        $interaction = array($node_label_array[$edge_src['target']],$node_label_array[$edge_src['source']],$edge_src['interaction_type'],$disease_label_array[$edge_src['edge_inv_disease']]);
        $total_edge = array_merge($total_edge,array($i => $interaction));
        $i += 1;
 
      }

$entry_array = array();

$formentryid = 1;
foreach ($total_edge as $key => $value) {
  $interactor = $value[1];
  $int_type = $value[2];
  $int_disease = $value[3];

  if (in_array($interactor.$int_type.$int_disease, $entry_array)) {
    continue;
  } else {
    $formentryid += 1;
    echo '<tr><td><form name="form'.$formentryid.'" id="form'.$formentryid.'" action="index.php?c=detail" method="post">
    <input id="form'.$formentryid.'-input" type="hidden" name="simplequery" value="'.explode('-', $interactor)[0].'">
  <a href="javascript:void(0);" onclick="document.form'.$formentryid.'.submit();">'.$interactor.'</a>
</form></td><td>'.$int_type.'</td><td>'.$int_disease.'</td></tr>';
    $entry_array[] = $interactor.$int_type.$int_disease;
  }
  


//对于部分 id 不显示的错误，仍然怀疑数据库层面有映射问题，不影响网页开发，后续要重新导入数据

}

        echo <<<EOF
      </tbody>
    </table>
  </div>
</div>
<div class="divider"><p></p></div>
<script>
  $(document).ready(
    function () {
      $('#interaction-table').DataTable();
    }
  );
</script>

<div class="card">
  <h5 class="card-header">Genome information</h5>
  <div class="card-body">
    <ul>
      <li>Binding</li>
      <li>SNP</li>
      <li>Expression</li>
    </ul>
  </div>
</div>
EOF;




    }
    
  
  }
  
  
}

?>

