<link rel="stylesheet" type="text/css" href="packages/DataTables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" type="text/css" />
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="packages/DataTables/DataTables-1.10.16/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="packages/DataTables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="packages/cytoscape/cytoscape.js"></script>
<!--panzoom -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cytoscape-panzoom/2.5.3/cytoscape.js-panzoom.css" rel="stylesheet" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cytoscape-panzoom/2.5.3/cytoscape-panzoom.js"></script>
<link href="http://cdnjs.cloudflare.com/ajax/libs/qtip2/2.2.0/jquery.qtip.min.css" rel="stylesheet" type="text/css" />
    
<script src="http://cdnjs.cloudflare.com/ajax/libs/qtip2/2.2.0/jquery.qtip.min.js"></script>
<!--<script src="https://cdn.rawgit.com/cytoscape/cytoscape.js-qtip/2.7.0/cytoscape-qtip.js"></script>-->
<script src="js/cytoscape-qtip.js"></script>
    
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./">Home</a></li>
    <li class="breadcrumb-item"><a href="./index.php?c=search">Search</a></li>
    <li class="breadcrumb-item active" aria-current="page">Network</li>
  </ol>
</nav>
<div class="card">
  <h5 class="card-header">Network</h5>
  <div class="card-body">
    <style>
      #cy {
        height: 600px;
        display: block;
        top: 0px;
        left: 0px;
      }
    </style>
    <h2 id="error-message-1" class="text-center" style="display: none;">Error Message: empty input</h2>
    <?php
    require('nachtzug.php');
    if ((!isset($_POST['detailquery']) or $_POST['detailquery'] == '') and (!isset($_POST['diseasequery']) or $_POST['diseasequery'] == '')) {
      echo '<script>$("#error-message-1").show();</script>';
    }
    else {
      $nodes = array();
      $edges = array();
      $cytoscape_nodes = array();
      if (!isset($_POST['detailquery']) or $_POST['detailquery']=='') {
        $disease = $_POST['diseasequery'];
        $fn = mysqli_query($conn, "SELECT * FROM `ppi_edge` WHERE `edge_inv_disease` = '$disease'");
      }
      if (!isset($_POST['diseasequery']) or $_POST['diseasequery']=='') {
        $core_node = $_POST['detailquery'];
        $fn = mysqli_query($conn, "SELECT * FROM `ppi_edge` WHERE `source` = '$core_node' OR `target` = '$core_node'");
      }


$node_label_sql = mysqli_query($conn, "SELECT * FROM `ppi_node`");
$node_label_array = array();
while ($node_label = mysqli_fetch_assoc($node_label_sql)) {
  $node_label_array[$node_label['node_id']] = $node_label;
}

$disease_label_sql = mysqli_query($conn, "SELECT * FROM `disease_map`");
$disease_label_array = array();
while ($disease_label = mysqli_fetch_assoc($disease_label_sql)) {
  $disease_label_array[$disease_label['disease_id']] = $disease_label;
}

while ($first_neighbor = mysqli_fetch_assoc($fn)) {
  $nodes = array_merge($nodes,array($first_neighbor['source'],));
  $nodes = array_merge($nodes,array($first_neighbor['target']));
  //array_push($nodes, array('data' => array('id' => $first_neighbor['source'], 'label' => $node_label_array[$first_neighbor['source']], 'mol' => $first_neighbor['mol_type'])));
  //array_push($nodes, array('data' => array('id' => $first_neighbor['target'], 'label' => $node_label_array[$first_neighbor['target']], 'mol' => $first_neighbor['mol_type'])));
}
$nodes = array_flip($nodes);
$nodes = array_flip($nodes);

foreach ($nodes as $key) {
  switch ($key) {
    case $core_node:
      array_push($cytoscape_nodes, array('data' => array('id' => $key, 'name' => $node_label_array[$key]['identifier'],'uniprot' => $node_label_array[$key]['uniprot'], 'mol' => 'core', 'rbp' => $node_label_array[$key]['is_RBP'], 'isoform' => $node_label_array[$key]['is_isoform'])));
      break;
    
    default:
      array_push($cytoscape_nodes, array('data' => array('id' => $key, 'name' => $node_label_array[$key]['identifier'],'uniprot' => $node_label_array[$key]['uniprot'], 'mol' => $node_label_array[$key]['mol_type'], 'rbp' => $node_label_array[$key]['is_RBP'], 'isoform' => $node_label_array[$key]['is_isoform'])));
      break;
  }
  //array_push($cytoscape_nodes, array('data' => array('id' => $key, 'name' => $node_label_array[$key]['identifier'],'uniprot' => $node_label_array[$key]['uniprot'], 'mol' => $node_label_array[$key]['mol_type'], 'rbp' => $node_label_array[$key]['is_RBP'], 'isoform' => $node_label_array[$key]['is_isoform'])));
  $fnint = mysqli_query($conn, "SELECT * FROM `ppi_edge` WHERE `source` = '$key' OR `target` = '$key'");
  while ($first_neighbor_interaciton = mysqli_fetch_assoc($fnint)) {
    $fnsrc = $first_neighbor_interaciton['source'];
    $fntgt = $first_neighbor_interaciton['target'];
    
    //var_dump(array_search);
    if (is_integer(array_search($fnsrc,$nodes)) AND is_integer(array_search($fntgt,$nodes))) {
      array_push($edges,array('data' => array('id' => $fnsrc.'-'.$fntgt, 'source' => $fnsrc, 'target' => $fntgt, 'type' => $first_neighbor_interaciton['interaction_type'], 'disease' => $disease_label_array[$first_neighbor_interaciton['edge_inv_disease']]['disease_attr'])));
      //echo $fnsrc.' '.$fntgt.'<br>';
    }
  }

}
$net = array_merge($cytoscape_nodes, $edges);
//$net = array('data' => array('nodes' => $cytoscape_nodes, 'edges' => $edges));
$myelements = json_encode($net);


//echo $myelements;

    echo "<script>var myelements = ".$myelements.";</script>";
    }

    ?>
    
    <button type="button" class="btn btn-outline-danger" id="thislayout" value="cose" onclick="reloadLayout()"><i class="fa fa-refresh" aria-hidden="true"></i> RELOAD</button>
    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
      <!--Export, Layout, Filter-->
      
      <div class="btn-group" role="group">
        <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-download" aria-hidden="true"></i> EXPORT
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
          <a class="dropdown-item" onclick="createAndDownloadFile('png')">PNG</a>
          <a class="dropdown-item" onclick="createAndDownloadFile('jpg')">JPG</a>
        </div>
      </div>
      <div class="btn-group" role="group">
        <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-cogs" aria-hidden="true"></i> LAYOUTS
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
          <a class="dropdown-item" onclick="switchLayout('cose')"><i class="fa fa-asterisk" aria-hidden="true"></i> Cose</a>
          <a class="dropdown-item" onclick="switchLayout('grid')"><i class="fa fa-th" aria-hidden="true"></i> Grid</a>
          <a class="dropdown-item" onclick="switchLayout('circle')"><i class="fa fa-circle-o" aria-hidden="true"></i> Circle</a>
          <a class="dropdown-item" onclick="switchLayout('random')"><i class="fa fa-random" aria-hidden="true"></i> Random</a>
        </div>
      </div>
      <div class="btn-group" role="group">
        <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-filter" aria-hidden="true"></i> FILTERS
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
          <button class="dropdown-item" id="showrbp" value="show"><i class="fa fa-eye-slash" aria-hidden="true"></i> HIDE RBPs</button>
          <button class="dropdown-item" id="showrna" value="show"><i class="fa fa-eye-slash" aria-hidden="true"></i> HIDE RNAs</button>
          <button class="dropdown-item" id="showiso" value="show"><i class="fa fa-eye-slash" aria-hidden="true"></i> HIDE Isoforms</button>
          <button class="dropdown-item" id="showdis" value="show"><i class="fa fa-eye-slash" aria-hidden="true"></i> HIDE Edge disease labels</button>
        </div>
      </div>
      <div class="btn-group" role="group">
        <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-code-fork" aria-hidden="true"></i> RESOURCES
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
          <a class="dropdown-item" href="https://js.cytoscape.org/" target="_blank">cytoscape.js</a>
          <a class="dropdown-item" href="https://github.com/cytoscape/cytoscape.js-panzoom" target="_blank">cytoscape-panzoom</a>
          <a class="dropdown-item" href="https://github.com/cytoscape/cytoscape.js-qtip" target="_blank">cytoscape-qtip</a>
          <a class="dropdown-item" href="https://fontawesome.com/free" target="_blank">Font Awesome</a>
          
           
          
        </div>
      </div>
    </div>
    <hr class="my-4">
    <div id="cy"></div>
    <script src="js/mynet.js"></script>
    <hr class="my-4">
  </div>
</div>
<div class="divider"><p></p></div>
<div class="card" id="network-detail" style="display: none;">
  <h5 class="card-header">Network details</h5>
  <div class="card-body">
    <table id="search_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
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
  </div>
</div>
<div class="divider"><p></p></div>
<script>
  $(document).ready(
    function () {
      $('#search_table').DataTable();
    }
  );
</script>