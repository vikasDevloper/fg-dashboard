<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Dashboard</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->     
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    
    </head>
    <body>
        <div class="container">
        <h2>Dashboard</h2>                                                                                      
            <div class="table-responsive">          
                <table class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>COD</th>
                        <th>Processing</th>
                        <th>On Hold</th>
                        <th>Shipped</th>
                        <th>Delivered</th>
                        <th>Pending Payments</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Total Number of Orders : </td>
                        <td><?php echo isset($data['unDeliveredOrder']['pending']) ? $data['unDeliveredOrder']['pending'] : 0;?></td>
                        <td><?php echo isset($data['unDeliveredOrder']['processing']) ? $data['unDeliveredOrder']['processing'] : 0;?></td>
                         <td><?php echo isset($data['unDeliveredOrder']['holded']) ? $data['unDeliveredOrder']['holded'] : 0;?></td>
                        <td><?php echo isset($data['unDeliveredOrder']['shipped']) ? $data['unDeliveredOrder']['shipped'] : 0;?></td>
                        <td><?php echo isset($data['unDeliveredOrder']['delivered']) ? $data['unDeliveredOrder']['delivered'] : 0;?></td>
                        <td><?php echo isset($data['unDeliveredOrder']['pending_payment']) ? $data['unDeliveredOrder']['pending_payment'] : 0;?></td>
                      </tr>
                       <tr>
                        <td>Total Revenue of Orders : </td>
                        <td><?php echo isset($data['revenueByUnDeliveredOrder']['pending']) ? number_format($data['revenueByUnDeliveredOrder']['pending'], 2) : 0;?></td>
                        
                        <td><?php echo isset($data['revenueByUnDeliveredOrder']['processing']) ? number_format($data['revenueByUnDeliveredOrder']['processing'], 2) : 0;?></td>

                         <td><?php echo isset($data['revenueByUnDeliveredOrder']['holded']) ? number_format($data['revenueByUnDeliveredOrder']['holded'], 2) : 0;?></td>
                        
                        <td><?php echo isset($data['revenueByUnDeliveredOrder']['shipped']) ? number_format($data['revenueByUnDeliveredOrder']['shipped'], 2) : 0;?></td>

                        <td><?php echo isset($data['revenueByUnDeliveredOrder']['delivered']) ? number_format($data['revenueByUnDeliveredOrder']['delivered'], 2) : 0;?></td>

                        <td><?php echo isset($data['revenueByUnDeliveredOrder']['pending_payment']) ? number_format($data['revenueByUnDeliveredOrder']['pending_payment'], 2) : 0;?></td>
                      </tr>
                      <tr>
                        <td>Total Revenue : </td>
                        <td> <?php echo $data['revenueByUnDeliveredOrder']['total']; ?></td>
                      </tr>  
                    </tbody>
                </table>
               <table class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>COD</th>
                        <th>Prepaid Order</th>
                        <th>Store Credit Order</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td> Today's Orders : </td>
                        <td><?php echo isset($data['ordersByPaymentMethods']['order']['cashondelivery']) ? $data['ordersByPaymentMethods']['order']['cashondelivery'] : 0;?></td>
                        <td><?php echo isset($data['ordersByPaymentMethods']['order']['payubiz']) ? $data['ordersByPaymentMethods']['order']['payubiz'] : 0;?></td>
                        <td><?php echo isset($data['ordersByPaymentMethods']['order']['free']) ? $data['ordersByPaymentMethods']['order']['free'] : 0;?></td>
                      </tr> 

                       <tr>
                        <td> Today's Orders Amount: </td>
                        <td><?php echo isset($data['ordersByPaymentMethods']['amount']['cashondelivery']) ? number_format($data['ordersByPaymentMethods']['amount']['cashondelivery'], 2) : 0;?></td>
                        <td><?php echo isset($data['ordersByPaymentMethods']['amount']['payubiz']) ? number_format($data['ordersByPaymentMethods']['amount']['payubiz'], 2) : 0;?></td>
                        <td><?php echo isset($data['ordersByPaymentMethods']['amount']['free']) ? number_format($data['ordersByPaymentMethods']['amount']['free'], 2 ) : 0;?></td>
                      </tr> 

                    </tbody>
                </table> 

                 <table class="table">
                    <thead>
                      <tr>
                        <th>City</th>
                        <th>Revenue</th>
                        <th>Orders</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data['revenueByCities'] as $value) { ?>
                   
                      <tr>

                        <td><?php echo $value['city'];?></td>
                        <td><?php echo $value['amount'];?></td>
                        <td><?php echo $value['orders'];?></td>

                      </tr> 
                    <?php } ?>
                    
                    </tbody>
                </table> 
            </div>
        </div>

    </body>
</html>
