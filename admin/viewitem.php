<?php require('../config/autoload.php'); ?>

<?php
$dao=new DataAccess();
?>
<?php include('header.php'); ?>

    
    <div class="container_gray_bg" id="home_feat_1">
    <div class="container">
    	<div class="row">
            <div class="col-md-12">
                <table  border="1" class="table" style="margin-top:100px;">
                    <tr>
                        
                        <th>Item Id</th>
                        <th>Item Name</th>
                        <th>Item Image</th>
                        <th>Item Price</th>
                        <th>Item avalablity</th>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>EDIT/DELETE</th>
                     
                      
                    </tr>
<?php
    
    $actions=array(
    'edit'=>array('label'=>'Edit','link'=>'edititem.php','params'=>array('id'=>'i_id'),'attributes'=>array('class'=>'btn btn-success')),
    
    'delete'=>array('label'=>'Delete','link'=>'deleteitem.php','params'=>array('id'=>'i_id'),'attributes'=>array('class'=>'btn btn-success'))
    
    );

    $config=array(
        'srno'=>true,
        'hiddenfields'=>array('i_id'),
'actions_td'=>false,
         'images'=>array(
                        'field'=>'i_image',
                        'path'=>'../uploads/',
                        'attributes'=>array('style'=>'width:100px;'))
        
    );

   
   $join=array(
        'category as c'=>array('c.c_id=i.c_id','join'),

    );  
    $fields=array('i_id','i_name','i_image','i_price','i_stock','c_name','i_des');
    $condition="i.status=1";

$users=$dao->selectAsTable($fields,'item as i',$condition,$join,$actions,$config);
    
    echo $users;
                    
                    
                   
    
?>
             
                </table>
            </div>    

            
            
            
            
        </div><!-- End row -->
    </div><!-- End container -->
    </div><!-- End container_gray_bg -->
    
    
