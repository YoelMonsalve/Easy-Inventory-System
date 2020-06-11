<?php
  $page_title = 'Edit sale';
  require_once('includes/load.php');
  // Checking What level user has permission to view this page
  page_require_level(3);

  $all_categories = find_all('categories');
  $all_photos = find_all('media');

?>
<?php
  /* if sale-ID not specified, ... go back */
  if( !isset( $_GET['id'] ) )
    redirect('sales.php');

  /* Get sale record, from sale-id */
  $sale = find_by_id('sales',(int)$_GET['id']);
  $sale_time = strtotime( remove_junk($sale['date']) );

  if(!$sale) {
    $session->msg("d","Sale not found.");
    redirect('sales.php');
  }
  
  /* Get product record, from product-id */
  $product = find_by_id('products',$sale['product_id']);

  if(!$product) {
    $session->msg("d","Product not found.");
    redirect('sales.php');
  }

  /* Get category_name and media_name */
  $category_name = '';      /* default */
  if( isset($product['categorie_id']) ) {
    $category = find_by_id( 'categories', $product['categorie_id'] );
    if ( isset( $category['name'] ) )
      $category_name = $category['name'];
  }

  $media_name = '';        /* default */
  if( isset($product['media_id']) ) {
    $media = find_by_id( 'media', $product['media_id'] );
    if ( isset( $media['file_name'] ) )
      $media_name = $media['file_name'];
  }
?>

<?php
  /* UPDATING SALE
   * ====================================================== */
  if( isset($_POST['update_sale']) ) {
  
    $req_fields = array( 'quantity','sale_price','total_sale','date' );
    
    if( validate_fields($req_fields) ){

      $p_id = $product['id'];
      $s_qty     = $db->escape((int)$_POST['quantity']);
      $extra_qty = $s_qty - $sale['qty'];     /* <-- note this !!! */
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
      $s_id      = $sale['id'];

      //echo $date."<br/>";
      //echo date( 'Y-m-d', strtotime( $date ) )."<br/>";

      $sql  = "UPDATE `sales` SET";
      $sql .= " `product_id`='${p_id}'";
      $sql .= ", `qty`='${s_qty}'";
      $sql .= ", `buy_price`='${b_price}'";
      $sql .= ", `sale_price`='${s_price}'";
      $sql .= ", `total_sale`='${s_total}'";
      $sql .= ", `profit`='${s_profit}'";
      $sql .= ", `destination`='${s_dest}'";
      $sql .= ", `date`='${s_date}'";
      $sql .= " WHERE `id`=$s_id";

      
      if( $db->query($sql) ){
        if ( $extra_qty )
          update_product_qty($extra_qty, $p_id);
        $session->msg('s',"Listo!");
        
        //print( "SUCCESS" );
        //exit();
        redirect('sales.php', false);
      } else {
        $session->msg( 'd','Operación falló: '.$db->get_last_error() );
        
        /* error */
        redirect('sales.php', false);
        //print( "failed" . $db->get_last_error() );
        //exit();
      }
    } else {
      $session->msg("d", $errors);
      
      /* error */
      redirect('sales.php',false);
      //print( "failed: bad fields " );
      //exit();
    }

  /* === OLD SCHEMA === 
  $req_fields = array('quantity','s_total', 'date' );
  validate_fields($req_fields);
  if(empty($errors)) {
  	
    $p_id      = $db->escape((int)$product['id']);
    $s_qty     = $db->escape((int)$_POST['quantity']);
    $s_total   = $db->escape((int)$_POST['s_total']);
    $date      = $db->escape($_POST['date']);
    $dest      = isset($_POST['destination']) ? $db->escape($_POST['destination']) : "";
    $s_date    = date("Y-m-d", strtotime($date));

    $sql  = "UPDATE sales SET";
    $sql .= " product_id='{$p_id}',qty={$s_qty},price={$s_total},destination='${dest}',date='{$s_date}'";
    $sql .= " WHERE id ='{$sale['id']}'";
    $result = $db->query($sql);
    if( $result ){
      if( $db->affected_rows() === 0 ) {
        $session->msg('i',"No se cambió el registro");
        redirect('edit_sale.php?id='.$sale['id'], false);
      }
      else {
        update_product_qty($s_qty,$p_id);
        $session->msg('s',"Salida actualizada.");
        redirect('edit_sale.php?id='.$sale['id'], false);
      }
    } 
    else {
      $session->msg('d',' Actualización falló!' );
      //$session->msg('d',' Problema en esta consulta: '.$sql );
      redirect('sales.php', false);
    }
  } else {
     $session->msg("d", $errors);
     redirect('edit_sale.php?id='.(int)$sale['id'],false);
  }
  */
}

?>
<?php include_once('layouts/header.php'); ?>

<!-- This is the jQuery script for auto-suggestion of product names -->
<script type="text/javascript" src="libs/js/jquery-3.5.1.js"></script>
<script type="text/javascript" src="cache/js/bootstrap.min.js"></script>
<script type="text/javascript" src="cache/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="libs/js/functions.js"></script>
<script type="text/javascript" src="libs/js/add_sale2.js"></script>

<style type="text/css">
  input-group {
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

<div class="row">
  <div class="col-md-9">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-9">

    <!--<div class="panel panel-default">-->
    <div class="panel">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-pencil" style="margin-right: 4pt"></span>
          <span>Editar Venta</span>
        </strong>
        <div class="pull-right">
          <a href="sales.php" class="btn btn-warning">Regresar</a>
        </div>
      </div>
      <div class="panel-body">
       <div class="col-md-12">
        <form method="post" action="edit_sale.php?id=<?php echo (int)$sale['id']?>" class="clearfix">

          <div class="form-group">
            <div class="row">
              <!--- separator -->
              <div class="col-md-3">
              </div>
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
                  <!--<input type="text" class="form-control" id="partNo" name="partNo" placeholder="COD/Part No">-->
                  <span class="form-control">
                    <!-- COD/PartNo is NON-editable -->
                    <?php echo remove_junk($product['partNo']); ?>
                  </span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="input-group">
                  <span class="input-group-addon" style="cursor:pointer" id="check-product-name">
                    <i class="glyphicon glyphicon-align-left"></i>
                  </span>
                  <!--<input type="text" class="form-control" id="product_name" name="product_name" placeholder="Nombre/T&iacute;tulo" autofocus>-->
                  <span class="form-control">
                    <!-- Product-Name is NON-editable -->
                    <?php echo remove_junk($product['name']); ?>
                  </span>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-4">
                <label for="">categor&iacute;a</label>
                <div class="input-group" style="margin-bottom: 1ex">
                  <span class="input-group-addon" style="background-color: #f0f0f0" >
                    <i class="glyphicon glyphicon-indent-left"></i>
                  </span>
                  <span class="form-control" id="category">
                    <!-- Category is NON-editable -->
                    <?php echo remove_junk($category_name); ?>
                  </span>
                </div>
              </div>
              <div class="col-md-4">
                <label for="">ubicaci&oacute;n</label>
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-th-list"></i>
                  </span>
                  <span class="form-control" id="location">
                    <!-- Location is NON-editable -->
                    <?php echo remove_junk($product['location']); ?>
                  </span>
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
                  <input type="text" class="form-control" name="date" id="date" data-date data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" value="<?php echo date('Y-m-d', $sale_time );?>" >
                  
                  <!--<input type="date" class="form-control datePicker" name="date" id="date" data-date data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d', $sale_time );?>" >-->

                </div>
              </div>
              <div class="col-md-1">
                <!-- column separator -->
              </div>
              <div class="col-md-4">
                <label for="destinatio">destino/cliente</label>
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-home"></i>
                  </span>
                  <input type="text" class="form-control" id="destination" name="destination" 
                  placeholder="" value="<?php echo remove_junk($sale['destination']); ?>" >
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-3">
                <label for="">disponible <i>(antes de vender)</i></label>
                <div class="input-group">
                  <span class="input-group-addon">
                    #&nbsp;
                  </span>
                  <span class="form-control" id="stock">
                    <?php echo remove_junk($product['quantity']) + remove_junk($sale['qty']); ?>
                  </span>
                </div>
              </div>
              <div class="col-md-1">
                <!-- column separator -->
              </div>
              <div class="col-md-2">
                <label for="">cantidad</label>
                <div class="input-group">
                  <span class="input-group-addon" border="0">
                    <i class="glyphicon glyphicon-shopping-cart"></i>
                  </span>
                  <input type="number" class="form-control" id="quantity" name="quantity" 
                  value="<?php echo remove_junk($sale['qty']); ?>" placeholder="0">
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
                  <span class="form-control" id="buy_price">
                    <?php echo remove_junk($sale['buy_price']); ?>
                  </span>
                </div>
              </div>
              <div class="col-md-1">
                <!-- column separator -->
              </div>
              <div class="col-md-2">
                <label for="">precio venta</label> 
                <div class="input-group">
                  <span class="input-group-addon">$</span>
                  <input type="text" class="form-control" id="sale_price" name="sale_price" 
                  value="<?php echo remove_junk($sale['sale_price']); ?>" placeholder="0.00">
               </div>
              </div>
              <div class="col-md-2">
                <label for="">Total Venta</label>
                <div class="input-group">
                  <span class="input-group-addon">$</span>
                  <input type="text" class="form-control" id="total_sale" name="total_sale" 
                  value="<?php echo remove_junk($sale['total_sale']); ?>" placeholder="0.00">
                </div>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <div class="row" style="margin-top: 3em;">
              <div class="col-md-2">
                <div class="input-group">
                  <button type="submit" name="update_sale" class="btn btn-primary">Actualizar</button>
                </div>
              </div>
              <div class="col-md-2">
                <!-- column separator -->
              </div>
              <!-- DISABLED --
              <div class="col-md-1">
                <div class="input-group">
                  <button class="btn btn-warning" id="refresh">Limpiar</button>
                </div>
              </div>
            -->
            </div>
          </div>

        </form>
       </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
