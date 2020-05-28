<?php
  $page_title = 'Edit sale';
  require_once('includes/load.php');
  // Checking What level user has permission to view this page
  page_require_level(3);
?>
<?php
$sale = find_by_id('sales',(int)$_GET['id']);
if(!$sale){
  $session->msg("d","Missing product id.");
  redirect('sales.php');
}
//print_r( $sale );
//exit;
?>
<?php $product = find_by_id('products',$sale['product_id']); ?>

<?php
if(isset($_POST['update_sale'])){
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
}

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">

  <div class="col-md-12">
  <div class="panel">
    <div class="panel-heading clearfix">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Todas Las Salidas</span>
     </strong>
     <div class="pull-right">
       <a href="sales.php" class="btn btn-primary">Ver Todas Las Salidas</a>
     </div>
    </div>
    <div class="panel-body">
       <table class="table table-bordered">
         <thead>
          <th> Producto </th>
          <th> Cantidad </th>
          <th> Total Venta </th>
          <th> Destino </th>
          <th> Fecha </th>
          <th> Acci&oacute;n </th>
         </thead>
           <tbody  id="product_info">
              <tr>
              <form method="post" action="edit_sale.php?id=<?php echo (int)$sale['id']; ?>">
                <td id="s_name">
                  <!--
                  <span type="text" class="form-control" id="sug_input" name="title" value="<?php echo remove_junk($product['name']); ?>">
                  <div id="result" style="cursor:pointer" class="list-group"></div>-->
                  <?php echo remove_junk($product['name']); ?>
                </td>
                <td id="s_qty">
                  <input type="text" class="form-control" name="quantity" value="<?php echo (int)$sale['qty']; ?>">
                </td>
                <td id="s_price">
                  <!--<input type="text" class="form-control" name="price" value="<?php echo remove_junk($product['sale_price']); ?>" >-->
                  <input type="text" class="form-control" name="s_total" value="<?php echo remove_junk($sale['price']); ?>" >
                </td>
                <td>
                  <input type="text" class="form-control" name="destination" value="<?php echo /*remove_junk($product['buy_price']);*/remove_junk($sale['destination']); ?>">
                </td>
                <td id="s_date">
                  <!--<input type="date" class="form-control datepicker" name="date" data-date-format="" value="<?php echo /*remove_junk($sale['date'])*/"05-02-2019"; ?>">-->
                  <input type="date" class="form-control datepicker" name="date" data-date-format="d-m-Y" value="<?php echo /*remove_junk($sale['date'])*/"05-02-2019"; ?>">
                </td>
                <td>
                  <button type="submit" name="update_sale" class="btn btn-primary">Actualizar</button>
                </td>
              </form>
              </tr>
           </tbody>
       </table>

    </div>
  </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
