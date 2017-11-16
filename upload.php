<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Document</title>
	<style>
		form .privewImg{
			overflow:hidden;
			font-family:Microsoft Yahei;
		}
		form .left span{
			font-size:14px;
			color:#666;
		}
		.upload{
			width:140px;
			height:140px;
			border-radius:50%;
			position:relative;
			margin:20px 0;
		}
		.upload img{
			position:absolute;
			top:0;
			left:0;
			width:100%;
			height:100%;
			border-radius:50%;
		}
		.upload input{
			position:absolute;
			top:0;
			left:0;
			width:100%;
			height:100%;
			opacity:0;
			border-radius:50%;
		}
		.portraiterr{
			color:red;
			line-height:50px;
			font-size:14px;
		}
	</style>
</head>
<body>
	<!--通过js预览图片   通过php提交图片-->
    <?php
		date_default_timezone_set('PRC'); 
       
		$portrait = 'images/portrait.jpg';
		$portraiterr = '';//上传头像错误提示
		//获取提交方式
		$method = $_SERVER['REQUEST_METHOD'];
		if($method == 'POST'){
			 //判断文件上传是否出错
			if($_FILES["portrait"]["error"]){
				echo $_FILES["portrait"]["error"];
			}else{
				//控制上传文件的类型，大小 1M之内  1M=1024k=1048576字节
				if(($_FILES["portrait"]["type"]=="image/jpeg" || $_FILES["portrait"]["type"]=="image/png") && $_FILES["portrait"]["size"]<1048576){
					//找到文件存放的位置
					$filename = "images/".date("Ymd").$_FILES["portrait"]["name"];
					
					//转换编码格式
					$filename = iconv("UTF-8","gb2312",$filename);
					
					//判断文件是否存在
					if(file_exists($filename)){
						echo "该文件已存在！";
					}else{
						//保存文件
						move_uploaded_file($_FILES["portrait"]["tmp_name"],$filename);
						$portrait = $filename;
					}
				}
				else{
					if($_FILES["portrait"]["type"]=="image/jpeg" || $_FILES["portrait"]["type"]=="image/png"){
						$portraiterr = '图片格式不对';
					}else if($_FILES["portrait"]["size"]>1048576){
						$portraiterr = '图片不能大于1M';
					}
				}
			}
		}
    ?>
	
    <form action="upload.php" method="post" enctype="multipart/form-data">
		<div class="privewImg">
			<div class="left">上传头像：<span>可以是jpg和png格式，不能大于1M</span></div>
			<div class="upload">
				<img src="<?php echo $portrait;?>" id="imgPortrait"/>
				<input type="file" name="portrait" id="changeBtn"/>
			</div>
			<span class="portraiterr"><?php echo $portraiterr;?></span>
		</div>
		<input type="submit"/>
    </form>
	<script>
		document.getElementById('changeBtn').addEventListener('change', function() { 
			 var _this = this;
			 var oImg = document.getElementById('imgPortrait');
			 var file  = _this.files[0];
			 //保证上传的图片 而且 图片小于1m
			 if((file.type == "image/jpeg" || file.type == "image/png") && file.size < 1048576){
				 var reader = new FileReader();
				 reader.onloadend = function () {
					 console.log(reader.result);
					 oImg.src = reader.result;
				 }
				 if (file) {
				    reader.readAsDataURL(file);
				 } else {
				    oImg.src = "";
				 }
			 }else{
				 if(file.type != "image/jpeg" && file.type != "image/png"){
					 alert('图片格式不对');
				 }else if(file.size > 1048576){
					 alert('图片不能大于1M');
				 }
			 }
			
		}, false); 

	</script>
</body>
</html>



