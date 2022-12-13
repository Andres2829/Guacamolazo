<?php
    include 'db_connect.php';
    $month = isset($_GET['month']) ? $_GET['month'] : date('m/d/Y');
    $busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : date('');
?>
<style>
    .heading { color: #FF0000; }
    .heading2 { color: #04ff00; }
</style>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card_body">
            <div class="row justify-content-center pt-4">
                <label for="" class="mt-2">Día:</label>
                <div class="col-sm-3">
                    <input type="date" name="month" id="month" value="<?php echo $month ?>" class="form-control">
                </div>
                <label for="" class="mt-2">Filtrar Factura:</label>
                <div class="col-sm-3">
                    <input type="text" name="busqueda" id="busqueda" value="<?php echo $busqueda ?>" class="form-control">
                </div>
            </div>

            
                
            
            <hr>
            <div class="col-md-12">
                <table class="table table-bordered" id='report-list'>
                    <thead>
                        <h2>Reporte de Ventas - Detalle</h2>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="">Fecha</th>
                            <th class="">Detalle</th>
                            <th class="">Factura</th>
                            <th class="">Número Orden</th>
                            <th class="text-right">Valor Total</th>
                         
                        </tr>
                    </thead>
                    <tbody>
			          <?php
                      $i = 1;
                      $total = 0;
                      $items = $conn->query("SELECT *FROM orders join order_items o ON orders.id=o.order_id join products p on p.id = o.product_id where amount_tendered > 0 and date_format(date_created,'%Y-%m-%d') = '$month'or ref_no='$busqueda' order by unix_timestamp(date_created) asc ");

                      $sales = $conn->query("SELECT * FROM orders where amount_tendered > 0 and date_format(date_created,'%Y-%m-%d') = '$month' order by unix_timestamp(date_created) asc ");
                      
                      while($row = $items->fetch_assoc()):
                        $total += $row['amount'];
			          ?>
			          <tr>
                        <td class="text-center"><?php echo $i++ ?></td>
                        <td>
                            <p> <b><?php echo date("M,d,Y",strtotime($row['date_created'])) ?></b></p>
                        </td>
                        <td>     
                                   
								   <p>Producto: <?php echo $row['name'] ?></p>
                                   <p>Cantidad: <?php echo $row['qty'] ?></p>
								   <p>Precio Unitario: <?php echo number_format($row['price'],2) ?></p>

                        </td>
                        <td>
                            <p> <b><?php echo $row['amount_tendered'] > 0 ? $row['ref_no'] : 'N/A' ?></b></p>
                        </td>
                        <td>
                            <p> <b><?php echo $row['order_number'] ?></b></p>
                        </td>
                        <td>
                            <p class="text-right"> <b><?php echo number_format($row['amount'],2) ?></b></p>
                        </td>
                    </tr>
                    <?php 
                        endwhile;
                       
                    ?>
                    <tr>
            
			        </tbody>
                    <tfoot>
                        <tr class="heading2">
                            <th colspan="5" class="text-right">Total</th>
                            <th class="text-right"><?php echo number_format($total,2) ?></th>
                        </tr>
                    </tfoot>
                </table>
                <hr>
                <div class="col-md-12 mb-4">
                    <center>
                        <button class="btn btn-success btn-sm col-sm-3" type="button" id="print"><i class="fa fa-print"></i> Imprimir</button>
                    </center>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<noscript>
	<style>
		table#report-list{
			width:100%;
			border-collapse:collapse
		}
		table#report-list td,table#report-list th{
			border:1px solid
		}
        p{
            margin:unset;
        }
		.text-center{
			text-align:center
		}
        .text-right{
            text-align:right
        }
	</style>
</noscript>
<script>

$('#month').change(function(){
    location.replace('index.php?page=sales_report&month='+$(this).val())
})
$('#busqueda').change(function(){
    location.replace('index.php?page=sales_report&busqueda='+$(this).val())
})
$('#print').click(function(){
		var _c = $('#report-list').clone();
		var ns = $('noscript').clone();
            ns.append(_c)
		var nw = window.open('','_blank','width=900,height=600')
		nw.document.write('<p class="text-center"><b>Reporte de ingresos - Detalle <?php echo date("d, F, Y",strtotime($month)) ?></b></p>')
		nw.document.write(ns.html())
		nw.document.close()
		nw.print()
		setTimeout(() => {
			nw.close()
		}, 500);
	})
</script>