<?php
  $page_title = 'Procesar Venta';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);

  $all_categories = find_all('categories');
  $all_photos = find_all('media');
?>

<?php
  if(isset($_POST['add_sale'])){
    /* for me to see what fields are being submitted */
    //print_r( $_POST );
    //die;

    /* at least one, partNo, or productName is required */
    if ( !validate_fields( array('partNo') ) && !validate_fields( array('product_name') ) ) {
      $session->msg("d", $errors);
      print( "empty fields" );
      /* error */
      //exit();
      redirect('add_sale2.php',false);
    }
    $req_fields = array( 'quantity','sale_price','total_sale','date' );
    
    //if(empty($errors)){
    if( validate_fields($req_fields) ){

      if ( isset( $_POST['partNo'] ) && $_POST['partNo'] ) {
        $product = find_product_by_partNo( $_POST['partNo'] );
        if ( !$product || !sizeof($product) ) {
          $session->msg("d", sprintf("No se encontró partNo='%s'", $_POST['partNo'] ) );
          print( sprintf("No se encontró partNo='%s'", $_POST['partNo'] ) );
          /* error */
          //exit();
          redirect('add_sale2.php',false);
        }
      }
      $p_id = $product['id'];
      $s_qty     = $db->escape((int)$_POST['quantity']);
      $b_price   = $product['buy_price'];
      $s_price   = $db->escape($_POST['sale_price']);
      $s_total   = $db->escape($_POST['total_sale']);
      $s_profit  = $s_total - $s_qty * $b_price;
      if (isset( $_POST['destination'] ))
        $s_dest    = $db->escape($_POST['destination']);
      else
        $s_dest    = "";
      $date      = $db->escape( $_POST['date'] );
      $s_date    = date( 'Y-m-d', strtotime( $date ) );

      $sql  = "INSERT INTO sales (";
      $sql .= " product_id,qty,buy_price,sale_price,total_sale,profit,destination,date";
      $sql .= ") VALUES (";
      $sql .= "'${p_id}','${s_qty}','${b_price}','${s_price}','${s_total}','${s_profit}','${s_dest}','${s_date}'";
      $sql .= ")";

      print $sql;
      //exit;

      if( $db->query($sql) ){
        update_product_qty($s_qty, $p_id);
        $session->msg('s',"Listo!");
        
        //print( "SUCCESS" );
        //exit();
        redirect('add_sale2.php', false);
      } else {
        $session->msg( 'd','Operación falló: '.$db->get_last_error() );
        
        /* error */
        redirect('add_sale2.php', false);
        //print( "failed" . $db->get_last_error() );
        //exit();
      }
    } else {
      $session->msg("d", $errors);
      
      /* error */
      redirect('add_sale2.php',false);
      //print( "failed: bad fields " );
      //exit();
    }
  }
?>

<?php include_once('layouts/header.php'); ?>

<!--
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="UTF-8">

  <link rel="stylesheet" href="libs/css/main.css" />
  
  <link rel="stylesheet" href="libs/css/bootstrap.min.css" />
  <link rel="stylesheet" href="libs/css/datepicker3.min.css" />

  <script type="text/javascript" src="libs/js/jquery-3.5.1.js"></script>
  <script type="text/javascript" src="cache/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="cache/js/bootstrap-datepicker.min.js"></script>

  <script type="text/javascript" src="libs/js/functions.js"></script>

  </head>
  <body>
-->

<!-- Estilo MUY sencillo, que evita que los elementos
     se "peguen" al fluir en al vista movil -->
<style type="text/css">
  .input-group {
    margin-bottom: 1ex;
  }
  .input-group-addon {
    background-color: #f0f0f8;
  }
  .list-group li {
    background-color: #f3f9f0;
  }
  .panel {
    border-top-left-radius: 10pt;
    border-top-right-radius: 6pt;
    border-bottom-left-radius: 10pt;
    border-bottom-right-radius: 10pt;
  }
  .panel-heading {
    margin-top: 8pt;
    margin-right: 6pt;
    padding-bottom: 12pt;
    border-bottom: 1px solid gray;
  }
</style>


<!-- This is the jQuery script for auto-suggestion of product names -->
<script type="text/javascript" src="libs/js/jquery-3.5.1.js"></script>
<script type="text/javascript" src="cache/js/bootstrap.min.js"></script>
<script type="text/javascript" src="cache/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="libs/js/functions.js"></script>
<script type="text/javascript" src="libs/js/add_sale2.js"></script>

<div class="row">
  <div class="col-md-9">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-9">
    <!--<div class="panel panel-default">-->
    <div class="panel">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Procesar Venta</span>
        </strong>
      </div>
      <div class="panel-body">
       <div class="col-md-12">
        <form method="post" action="add_sale2.php" class="clearfix">

          <div class="form-group">
            <div class="row">
              <div class="col-md-3">
              </div>
              <!--
              <div class="col-md-2" style="text-align: right; border:1px dashed gray; padding-right: 1em">
                <div class="input-group" style="text-align: right; border:1px dashed gray">
                  <span class="form-control">Imagen</span>
                  <img class="img-avatar img-circle" src="uploads/products/tornillo1.jpg" style="height:6em" alt="">
                </div>
              </div>
              -->
              <!--<div class="col-md-2" style="text-align: center; border: 2px solid #c0c0c0; border-radius: 1ex;">-->
              <div class="col-md-2" style="text-align: center;">
                <img class="img-avatar img-circle" id="product_image" src="" style="height:6em; display:none", alt="">
              </div>  
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-4">
                <div class="input-group">
                  <span class="input-group-addon" style="cursor:pointer" id="check-partNo">
                    <i class="glyphicon glyphicon-align-left"></i>
                  </span>
                  <input type="text" class="form-control" id="partNo" name="partNo" placeholder="COD/Part No">
                </div>
                <div id="lst_partNo" style="cursor: pointer;" class="list-group"></div>
              </div>
              
              <div class="col-md-4">
                <div class="input-group">
                  <span class="input-group-addon" style="cursor:pointer" id="check-product-name">
                    <i class="glyphicon glyphicon-align-left"></i>
                  </span>
                  <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Nombre/T&iacute;tulo" autofocus>
                </div>
                <div id="lst_product_name" style="cursor: pointer;" class="list-group"></div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-4">
                <label for="">categor&iacute;a</label>
                <div class="input-group" style="margin-bottom: 1ex">
                  <span class="input-group-addon" style="background-color: #f0f0f0">
                    <i class="glyphicon glyphicon-indent-left"></i>
                  </span>
                  <span class="form-control" id="category"></span>
                </div>
              </div>
              <div class="col-md-4">
                <label for="">ubicaci&oacute;n</label>
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-th-list"></i>
                  </span>
                  <span class="form-control" id="location"></span>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-3">
                <label for="">fecha</label>
                <div class="input-group">
                  <span class="input-group-addon">&nbsp;&nbsp;</span>
                  <!--<input type="text" class="form-control" id="date" name="date" placeholder="yyyy-mm-dd">-->
                  <!--<input type="date" class="form-control datePicker" name="date" id="date" data-date data-date-format="yyyy-mm-dd" value="2020-05-01">-->
                  <!--<input type="date" name="date" id="date" data-date data-date-format="yyyy-mm-dd">-->
                  <input type="text" class="form-control" name="date" id="date" data-date data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd">
                </div>
              </div>
              <div class="col-md-1">
                <!-- column separator -->
              </div>
              <div class="col-md-4">
                <label for="destination">destino/cliente</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                  <input type="text" class="form-control" id="destination" name="destination" placeholder="">
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-3">
                <label for="">disponible</label>
                <div class="input-group">
                  <span class="input-group-addon">
                    #&nbsp;
                  </span>
                  <span class="form-control" id="stock">0</span>
                </div>
              </div>
              <div class="col-md-1">
                <!-- column separator -->
              </div>
              <div class="col-md-2">
                <label for="">cantidad</label>
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-shopping-cart"></i>
                  </span>
                  <input type="number" class="form-control" id="quantity" name="quantity" placeholder="0">
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-3">
                <label for="">precio compra</label> 
                <div class="input-group">
                  <span class="input-group-addon" id="buy_price_addon">$</span>
                  <span class="form-control" id="buy_price">0.00</span>
                </div>
              </div>
              <div class="col-md-1">
                <!-- column separator -->
              </div>
              <div class="col-md-2">
                <label for="">precio venta</label> 
                <div class="input-group">
                  <span class="input-group-addon">$</span>
                  <input type="text" class="form-control" id="sale_price" name="sale_price" placeholder="0.00">
               </div>
              </div>
              <div class="col-md-2">
                <label for="">Total Venta</label>
                <div class="input-group">
                  <span class="input-group-addon">$</span>
                  <input type="text" class="form-control" id="total_sale" name="total_sale" placeholder="0.00">
                </div>
              </div>
            </div>
          </div>

          <!--
          <div class="form-group">
            <div class="row">
              <div class="col-md-4">
              </div>
              <div class="col-md-3">
                <label for="">Total Venta</label>
                <div class="input-group">
                  <span class="input-group-addon">$</span>
                  <input type="text" class="form-control" id="total_sale" name="total_sale" placeholder="0.00">
                </div>
              </div>
            </div>
          </div>
          -->
          
          <div class="form-group">
            <div class="row" style="margin-top: 2.5em;">
              <div class="col-md-2">
                <div class="input-group">
                  <button type="submit" name="add_sale" class="btn btn-primary">Procesar Venta</button>
                </div>
              </div>
              <div class="col-md-2">
                <!-- column separator -->
              </div>
              <div class="col-md-1">
                <div class="input-group">
                  <button class="btn btn-warning" id="refresh">Limpiar</button>
                </div>
              </div>
            </div>
          </div>

        </form>
       </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

<!--
</body>
</html>
-->
