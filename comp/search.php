<link rel="stylesheet" href="packages/jquery-typeahead/dist/jquery.typeahead.min.css">
<script src="packages/jquery-typeahead/dist/jquery.typeahead.min.js"></script>
  
  <div id="advanced-search-form" class="card">
    <h5 class="card-header">Advanced search</h5>
    <div class="card-body">
<form action="index.php?c=search" method="post">
  <div class="form-row text-center">
    <div class="col-sm-12">
      <div class="control-group">
        <div class="form-row">
          <div class="col">
           <input name="querykey" class="form-control" type="search" autocomplete="off" placeholder="Gene symbol(s), UniProt ID(s) or other keywords" aria-label="Search">
          </div>
          <div class="col">
            <input name="disease" class="form-control" type="search" autocomplete="off" placeholder="Neurodegenerative disease(s)" aria-label="Search">
          </div>
        </div>
      </div>
      <div class="divider"><p></p></div>
      <div class="control-group">
        <button type="submit" class="btn btn-primary mb-2">Search</button>
      </div>
    </div>
  </div>
</form>
    </div>
  </div>
  <div class="divider"><p></p></div>
  
  <div id="results-table-card" class="card" style="display: none;">
    <h5 class="card-header">Results Table</h5>
    <div class="card-body">
      <?php
        include ('datatables.php');
      ?>
    </div>
  </div>
