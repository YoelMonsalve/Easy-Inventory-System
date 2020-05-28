<?php
  $page_title = 'Editar producto';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$product = find_by_id('products',(int)$_GET['id']);
$all_categories = find_all('categories');
$all_photo = find_all('media');
if(!$product){
  $session->msg("d","Error: No se encontró id de producto.");
  redirect('product.php');
}
?>
<?php
if(isset($_POST['update-product'])){
		$req_fields = array('product-name','product-partNo','product-category','product-photo','product-quantity','product-location' );
    validate_fields($req_fields);

   if(empty($errors)){
       $p_name  = remove_junk($db->escape($_POST['product-name']));
       $p_partNo = remove_junk($db->escape($_POST['product-partNo'])); 
       $p_cat   = (int)$_POST['product-category'];
       $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
       $p_loc   = remove_junk($db->escape($_POST['product-location']));
       if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
         $media_id = '0';
       } else {
         $media_id = remove_junk($db->escape($_POST['product-photo']));
       }
       $query   = "UPDATE products SET";
       $query  .=" name ='{$p_name}', ";
       $query  .=" partNo ='{$p_partNo}', ";
       $query  .=" categorie_id ='{$p_cat}', ";
       $query  .=" quantity ='{$p_qty}',";
       $query  .=" location ='{$p_loc}',";
       $query  .=" media_id='{$media_id}'";
       $query  .=" WHERE id ='{$product['id']}'";
       
       $result = $db->query($query);
							if( $result ) {
								if( $db->affected_rows() === 1 ) {
									$session->msg('s',"Producto ha sido actualizado.");
								} else {
									/* no row was changed */
									$session->msg('w',"No se cambió ningún registro." 
									//. "query: " . $query 
								 	. "Info: " . $db->get_info( )
								 	);
								}
								redirect('product.php', false);
             	}
							else {
								/* SQL query error */
            		$session->msg('d',"Lo siento, actualización falló." 
               	. "Message: " . $db->get_last_error( ) 
               	);
               	redirect('edit_product.php?id='.$product['id'], false);
              }

   } else{
       $session->msg("d", $errors);
     	 redirect('edit_product.php?id='.$product['id'], false);
   }

 }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Editar Material</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-7">
           <form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>">
              <div class="form-group">
              	<!-- Product Name -->
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-name" value="<?php echo remove_junk($product['name']);?>">
               </div>
              </div>
              
							<div class="form-group">
								<div class="row">
									<!-- Part No. -->
									<div class="col-md-6">
										<label for="product-partNo" class="control-label">Part No.</label>
										<div class="input-group">
											<!-- no need for addon here
											<span class="input-group-addon">XYZ</span> -->
									 		<input type="text" class="form-control" name="product-partNo"  value="<?php echo remove_junk($product['partNo']);?>">
									 		<!-- no need for addon here
									 		<span class="input-group-addon"></span> -->
										</div>
									</div>
									
									<!-- Category -->
									<div class="col-md-6">
										<label for="product-category" class="control-label">Categor&iacute;a</label>
                    <select class="form-control" name="product-category">
                    	<option value="">Selecciona una categoría</option>
                   <?php  foreach ($all_categories as $cat): ?>
                    	<option value="<?php echo (int)$cat['id']; ?>" <?php if($product['categorie_id'] === $cat['id']): echo "selected"; endif; ?> >
                      <?php echo remove_junk($cat['name']); ?></option>
                   <?php endforeach; ?>
                 		</select>
                  </div>
		          	</div>
							</div>
              
              <div class="form-group">
                <div class="row">
	                <!-- Product photo -->
                  <div class="col-md-6">
                  	<label for="product-photo" class="control-label">Imagen</label>
                    <select class="form-control" name="product-photo">
                      <option value=""> Sin imagen</option>
                      <?php  foreach ($all_photo as $photo): ?>
                        <option value="<?php echo (int)$photo['id'];?>" <?php if($product['media_id'] === $photo['id']): echo "selected"; endif; ?> >
                          <?php echo $photo['file_name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
               <div class="row">
               		<!-- Quantity -->
									<div class="col-md-6">
									<label for="product-quantity">Cantidad</label>
									<div class="input-group">
										<span class="input-group-addon">
										 <i class="glyphicon glyphicon-shopping-cart"></i>
										</span>
										<input type="number" class="form-control" name="product-quantity" value="<?php echo remove_junk($product['quantity']); ?>">
									</div>
								</div>
             	</div>
             </div>
               
						<div class="form-group">
            	<div class="row">
								<!-- Location -->               	
								<div class="col-md-6">
									<label for="product-location">Ubicaci&oacute;n</label>
									<div class="input-group">
										</span>
										<input type="text" class="form-control" name="product-location" value="<?php echo remove_junk($product['location']);?>">
								 	</div>
								</div>
							</div>
						</div>
							
						<div class="form-group">
            	<div class="row">
								<div class="col-md-6">
	              	<button type="submit" name="update-product" class="btn btn-primary" style="margin-top: 1em;">Actualizar</button>
              	</div>
							</div>
           </div>
          </form>
         </div>
        </div>
      </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
