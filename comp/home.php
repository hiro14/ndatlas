<link rel="stylesheet" href="packages/jquery-typeahead/dist/jquery.typeahead.min.css">
<script src="packages/jquery-typeahead/dist/jquery.typeahead.min.js"></script>
  
  <div class="jumbotron jumbotron-fluid">
    <div class="container">
      <h1 class="display-4">NDAtlas</h1>
      <p class="lead">NDAtlas is a searchable and integrative database for disease module in neurodegenerative diseases.</p>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <div class="card">
        <h5 class="card-header">What is NDAtlas?</h5>
        <div class="card-body">
          <p class="lead">NDAtlas is a searchable and integrative database for disease module in neurodegenerative diseases.</p>
          <h3>Quick search</h3>
          <div class="col-sm-9">
          <form id="form-quicksearch" name="form-quicksearch" action="index.php?c=detail" method="post">
            <div class="typeahead__container">
              <div class="typeahead__field">
                  <div class="typeahead__query">
                    <input class="js-typeahead-quicksearch" name="simplequery" autocomplete="off" type="search" placeholder="Input a gene symbol, UniProt ID or protein name" aria-label="Search">
                  </div>
              <div class="typeahead__button">
                <button type="submit">
                    <i class="typeahead__search-icon"></i>
                </button>
            </div>
        </div>
    </div>
</form>
</div>
          
        </div>
      </div>
      <?php
      include('./comp/outputidentifier.php'); //鼠标点击输入框触发调用，不要直接调用，最好在 sql 层面上进行表格映射
      $identifier_list_json = json_encode($identifier_list);
      ?>
      <script>
      var identifier = <?php echo $identifier_list_json; ?>;
      $.typeahead({
    input: '.js-typeahead-quicksearch',
    order: "desc",
    source: {
        data: identifier,
    }
});
      </script>

      <div class="divider"><p></p></div>
      <div class="card">
        <h5 class="card-header">Explore disease-specified PPI modules</h5>
        <div class="card-body">
          <ul class="list-unstyled">
            <li class="media">
              <img class="mr-3" src="image/icons/diseases/ad.png" height="64" width="64" alt="Generic placeholder image">
              <form name="form1" id="form1" class="form-inline" action="index.php?c=viewnet" method="post">
                <input id="form1-input" type="hidden" name="diseasequery" value="1">
                <a href="javascript:void(0);" onclick="document.form1.submit();">
                  <div class="media-body">
                    <h5 class="mt-0 mb-1">Alzheimer's disease</h5>
                      Alzheimer's is a type of dementia that causes problems with memory, thinking and behavior. Symptoms usually develop slowly and get worse over time, becoming severe enough to interfere with daily tasks. 
                  </div>
                </a>
              </form>
            </li>
            <li class="media">
              <img class="mr-3" src="image/icons/diseases/ad.png" height="64" width="64" alt="Generic placeholder image">
              <form name="form2" id="form2" class="form-inline" action="index.php?c=viewnet" method="post">
                <input id="form2-input" type="hidden" name="diseasequery" value="5">
                <a href="javascript:void(0);" onclick="document.form2.submit();">
                  <div class="media-body">
                    <h5 class="mt-0 mb-1">Parkinson's disease</h5>
                      Parkinson disease is a complex multifactorial neurodegenerative disorder, usually occurring in late life, although an early onset and a juvenile form are known. 
                  </div>
                </a>
              </form>
            </li>
            <li class="media">
              <img class="mr-3" src="image/icons/diseases/ad.png" height="64" width="64" alt="Generic placeholder image">
              <form name="form3" id="form3" class="form-inline" action="index.php?c=viewnet" method="post">
                <input id="form3-input" type="hidden" name="diseasequery" value="2">
                <a href="javascript:void(0);" onclick="document.form3.submit();">
                  <div class="media-body">
                    <h5 class="mt-0 mb-1">ALS</h5>
                      A degenerative disorder of motor neurons in the cortex, brain stem and spinal cord. ALS is characterized by muscular weakness and atrophy.
                  </div>
                </a>
              </form>
            </li>
          </ul>
          <!--<div class="row">
            <div class="col-sm-4">
              <div class="jumbotron">
              <form name="form4" id="form4" action="index.php?c=viewnet" method="post">
                <input id="form4-input" type="hidden" name="diseasequery" value="AD">
                <a href="javascript:void(0);" onclick="document.form4.submit();">
                  <div class="media">
                  <img src="image/icons/diseases/ad.png" height="64" width="64" alt="">
                  <div class="media-body">
                    <h5 class="mt-0">AD</h5>
                      </div>
                  </div>
                </a>
              </form>
              </div>
                 
            </div>
            
          </div>-->
          
        </div>
      </div>
    </div>
    
    <div class="col col-lg-3">
      <div class="card">
        <h5 class="card-header">Updates</h5>
        <div class="card-body">
          <h5>2018-09-30: The PPI network and visualization were finished. <span class="badge badge-primary">New!</span></h5>
          <h6>2018-06-30: Neurodegenerative diseases involved proteins and the interactors were collected.</h6>
          <h6>2018-01-30: The NDAtlas Project was launched.</h6>
        </div>
      </div>
      <div class="divider"><p></p></div>
      <div class="card">
        <h5 class="card-header">Visiting statistics</h5>
        <div class="card-body">
          <script type='text/javascript' id='clustrmaps' src='//cdn.clustrmaps.com/map_v2.js?cl=080808&w=225&t=m&d=G0Y_rQlQL4Nb4rYmFLCuVHDQpX-NUrrS3zruydgI3r8&co=ffffff&cmo=3acc3a&cmn=ff5353&ct=808080'></script>
        </div>
      </div>
    </div>
  </div>

