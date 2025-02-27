<?php 

 require('../config/autoload.php'); 
include("header.php");

$file=new FileUpload();
$elements=array(
        "c_name"=>"","c_image"=>"");


$form=new FormAssist($elements,$_POST);



$dao=new DataAccess();

$labels=array('c_name'=>"Category Name","c_image"=>"Category Image" );

$rules=array(
    "c_name"=>array("required"=>true,"minlength"=>3,"maxlength"=>30,"alphaspaceonly"=>true),
"c_image"=> array('filerequired'=>true)
     
);
    
    
$validator = new FormValidator($rules,$labels);

if(isset($_POST["btn_insert"]))
{

if($validator->validate($_POST))
{
	
if($fileName=$file->doUploadRandom($_FILES['c_image'],array('.jpg','.png','.jpeg','.webp'),100000,5,'../uploads'))
		{
$data=array(

        'c_name'=>$_POST['c_name'],
          'c_image'=>$fileName,
    );
  
    if($dao->insert($data,"category"))
    {
        echo "<script> alert('New record created successfully');</script> ";
header('location:category.php');
    }
    else
        {$msg="Registration failed";} ?>

<span style="color:red;"><?php echo $msg; ?></span>

<?php
    
}
else
echo $file->errors();
}

}


?>
<html>
<head>
</head>
<body>

 <form action="" method="POST" enctype="multipart/form-data">
 
<div class="row">
                    <div class="col-md-6">
Category:

<?= $form->textBox('c_name',array('class'=>'form-control')); ?>
<?= $validator->error('c_name'); ?>

</div>
</div>


<div class="row">
                    <div class="col-md-6">
Category Image:

<?= $form->fileField('c_image',array('class'=>'form-control')); ?>
<span style="color:red;"><?= $validator->error('c_image'); ?></span>

</div>
</div>






<button type="submit" name="btn_insert"  >Submit</button>
</form>


</body>

</html>


