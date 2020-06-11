<?php
  $page_title = 'Registrar salida';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>

<?php
  if(isset($_POST['add_sale'])){
    $req_fields = array('s_id','quantity','price','total','date' );
    validate_fields($req_fields);
    //print_r( $errors );
    if(empty($errors)){
      $p_id      = $db->escape((int)$_POST['s_id']);
      $s_qty     = $db->escape((int)$_POST['quantity']);
      $s_total   = $db->escape($_POST['total']);
      if (isset( $_POST['destination'] ))
        $dest    = $db->escape($_POST['destination']);
      else
        $dest    = "";
      $date      = $db->escape($_POST['date']);
      $s_date    = make_date();

      $sql  = "INSERT INTO sales (";
      $sql .= " product_id,qty,sale_price,destination,date";
      $sql .= ") VALUES (";
      $sql .= "'{$p_id}','{$s_qty}','{$s_total}','{$dest}','{$s_date}'";
      $sql .= ")";

      if($db->query($sql)){
        update_product_qty($s_qty,$p_id);
        $session->msg('s',"Salida Registrada ");
        redirect('add_sale.php', false);
      } else {
        $session->msg('d','Lo siento, registro falló.');
        redirect('add_sale.php', false);
      }
    } else {
       $session->msg("d", $errors);
       redirect('add_sale.php',false);
    }
  }

?>
<?php include_once('layouts/header.php'); ?>

<!-- This is the jQuery script for auto-suggestion of product names -->

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
    <form method="post" action="ajax/product_item.php" autocomplete="off" id="sug_form">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">Búsqueda</button>
            </span>
            <!--<input type="text" id="sug_input" class="form-control" name="product_name" placeholder="Buscar por el nombre del producto">-->
            <input type="text" id="sug_input" class="form-control" name="hint" placeholder="Buscar por el nombre del producto">
          </div>

          <!-- NOTE: Yoel added style="cursor:pointer: -->
          <div id="result" style="cursor:pointer" class="list-group"></div>
        </div>
    </form>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Registrar Salida</span>
       </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_sale.php">
         <table class="table table-bordered">
           <thead>
            <th> Producto </th>
            <th> Precio </th>
            <th> Cantidad </th>
            <th> Total </th>
            <th> Destino </th>
            <th> Fecha </th>
            <th> Acciones </th>
           </thead>
             <tbody id="product_info"> </tbody>
         </table>
       </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
<!-- jQuery -->
<script type="text/javascript" src="libs/js/product_item.js"></script>