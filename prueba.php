<?php include('db_connect.php');?>
<style>
	input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(1.3); /* IE */
  -moz-transform: scale(1.3); /* FF */
  -webkit-transform: scale(1.3); /* Safari and Chrome */
  -o-transform: scale(1.3); /* Opera */
  transform: scale(1.3);
  padding: 10px;
  cursor:pointer;
}
</style>
<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Lista de Órdenes ---------prueba </b>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
                                    <th class="">ID</th>
									<th class="">Fecha</th>
									<th class="">ID Factura</th>
									<th class="">N Orden</th>
									<th class="">Monto</th>
                                    <th class="">DETALLE</th>
									<th class="">Estado</th>
									<th class="text-center">Acción</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$order = $conn->query("SELECT * FROM orders order by unix_timestamp(date_created) desc ");
								while($row=$order->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
                                    <td>
										<p> <b><?php echo $row['id'] ?></b></p>
									</td>
									<td>
										<p> <b><?php echo date("M d,Y",strtotime($row['date_created'])) ?></b></p>
									</td>
									<td>
										<p> <b><?php echo $row['amount_tendered'] > 0 ? $row['ref_no'] : 'N/A' ?></b></p>
									</td>
									<td>
										<p> <b><?php echo $row['order_number'] ?></b></p>
									</td>
									<td>
										<p class="text-right"> <b><?php echo number_format($row['total_amount'],2) ?></b></p>
									</td>
                                    <td>
                                    <?php 
                                    $items = $conn->query("SELECT * FROM order_items o inner join products p on p.id = o.product_id JOIN orders where o.order_id = orders.id ");
                                    ?>
                                    <div >
                                        <table width="100%">


              
                                       
                                            <thead>
                                                <tr>
                                                    <td><b>NoRDER</b></td>
                                                    <td><b>QTY</b></td>
                                                    <td><b>Orden</b></td>
                                                    <td class="text-right"><b>Monto</b></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                while($row = $items->fetch_assoc()):
                                                ?>
                                                <tr>
                                                    <td><?php echo $row['ref_no'] ?></td>
                                                    <td><?php echo $row['qty'] ?></td>
                                                    <td><p><?php echo $row['name'] ?></p><?php if($row['qty'] > 0): ?><small>(<?php echo number_format($row['price'],2) ?>)</small> <?php endif; ?></td>
                                                    <td class="text-right"><?php echo number_format($row['amount'],2) ?></td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                        <hr>
                                    </div>
                                    

									</td>
									<td class="text-center">
										<?php if($row['amount_tendered'] > 0): ?>
											<span class="badge badge-success">Pagado</span>
										<?php else: ?>
											<span class="badge badge-primary">Sin pagar</span>
										<?php endif; ?>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-outline-primary " type="button" onclick="location.href='billing/index.php?id=<?php echo $row['id'] ?>'" data-id="<?php echo $row['id'] ?>" >Editar</button>
										<button class="btn btn-sm btn-outline-primary view_order" type="button" data-id="<?php echo $row['id'] ?>">Ver</button>
										<button class="btn btn-sm btn-outline-danger delete_order" type="button" data-id="<?php echo $row['id'] ?>">Eliminar</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height: :150px;
	}
</style>


<script>

    
	$(document).ready(function(){
		$('table').dataTable({

            fixedHeader: true,
            scrollX: true,
            searching: true,
            dom: 'Bfrtip',
            buttons: [],
            pageLength: 100,
            language: {
            search: 'Buscar',
            zeroRecords: 'No hay registros para mostrar.',
            emptyTable: 'La tabla está vacia.',
            info: "Mostrando _START_ de _END_ de _TOTAL_ Registros.",
            infoFiltered: "(Filtrados de _MAX_ Registros.)",
            paginate: {
                first: 'Primero',
                previous: 'Anterior',
                next: 'Siguiente',
                last: 'Último'
            }
            }

        })
	})


	$('#new_order').click(function(){
		uni_modal("New order ","manage_order.php","mid-large")
		
	})
	$('.view_order').click(function(){
		uni_modal("Detalles de Orden","view_order.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_order').click(function(){
		_conf("¿Est@s segura de eliminar este pedido?","delete_order",[$(this).attr('data-id')])
	})
	function delete_order($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_order',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Datos eliminados exitósamente",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>