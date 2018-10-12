<!DOCTYPE html>
<html>
	<head>
		<meta charset = "UTF-8">
    		<title>BBSF</title>
  	</head>
  	<body>
    		<?php
			/*****POST送信されたデータを変数に代入*****/
			$edit_mode = $_POST['edit_mode'];
			$name = $_POST['name'];
			$comment = $_POST['comment'];
			$Pas = $_POST['Pas'];

			$delNum = $_POST['delNum'];
			$delPas = $_POST['delPas'];

			$ediNum = $_POST['ediNum'];
			$ediPas = $_POST['ediPas'];

			$reset = $_POST['reset'];

			/*****日付を変数に代入*****/
			$date = date("Y/m/d H:i:s");

			/*****データベースに接続*****/
			$dsn = 'データベース名';
			$dbuser = 'ユーザ名';
			$dbpassword = 'パスワード';
			try{
				$pdo=new PDO($dsn,$dbuser,$dbpassword);
			}catch(Exception $e){
				echo"データベース接続失敗。";
				echo$e->getMessage;
				exit();
			}

			/*****テーブルを作成*****/
			$sql = "CREATE TABLE IF NOT EXISTS dbData"
			."("
			."id INT AUTO_INCREMENT,"
			."name char(32),"
			."comment TEXT,"
			."date char(32),"
			."password char(32),"
			."PRIMARY KEY(id)"
			.");";
			$stmt = $pdo->query($sql);

			/*****投稿機能*****/
			if(empty($edit_mode)){
				if(!empty($name) and !empty($comment) and !empty($Pas)){
					$sql = $pdo->prepare("INSERT INTO dbData (name,comment,date,password) VALUES (:name,:comment,:date,:password)");
					$sql->bindParam(':name',$name,PDO::PARAM_STR);
					$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
					$sql->bindParam(':date',$date,PDO::PARAM_STR);
					$sql->bindParam(':password',$Pas,PDO::PARAM_STR);
					$sql->execute();
				}
			}

			/*****削除機能*****/
			if(!empty($delNum) and !empty($delPas)){
				$sql1 = "SELECT * FROM dbData";
				$stmt = $pdo->query($sql1);
				foreach($stmt as $row){
					if($row['id'] == $delNum and $row['password'] == $delPas){
						$sql2 = "delete from dbData where id=$delNum";
						$results = $pdo->query($sql2);
					}
					if($row['id'] == $delNum){
						if($row['password'] != $delPas){
							echo"password error";
							break;
						}
					}
				}
			}

			/*****編集機能　入力フォームに表示*****/
			if(!empty($ediNum) and !empty($ediPas)){
				$sql = "SELECT * FROM dbData";
				$stmt = $pdo->query($sql);
				foreach($stmt as $row){
					if($row['id'] == $ediNum and $row['password'] == $ediPas){
						$edit_mode = $row['id'];
						$user = $row['name'];
						$text = $row['comment'];
					}
					if($row['id'] == $ediNum){
						if($row['password'] != $ediPas){
							echo"password error";
							break;
						}
					}
				}
			}

			/*****編集機能　データ書き換え*****/
			if(!empty($edit_mode) and !empty($name) and !empty($comment) and !empty($Pas)){
				$sql = "SELECT * FROM dbData";
				$stmt = $pdo->query($sql);
				foreach($stmt as $row){
					if($row['id'] == $edit_mode){
						$id = $edit_mode;
						$edited_name = $name;
						$edited_comment = $comment;
						$edited_password = $Pas;
						$sql="update dbData set name='$edited_name',comment='$edited_comment',password='$edited_password'where id=$id";
						$results=$pdo->query($sql);
					}
				}
				unset($edit_mode);
			}

			/*****リセット機能*****/
			if($reset){
				$sql = "delete from dbData";
				$results = $pdo->query($sql);
			}
		?>

    		<form action = ""method = "POST">
			<!--投稿用フォーム-->
				<p><input type = "hidden" name = "edit_mode" value = "<?php echo$edit_mode; ?>"></p>
      				<p><input type = "text" name = "name" value = "<?php echo$user; ?>" placeholder = "名前"></p>
      				<p><input type = "text" name = "comment" value = "<?php echo$text; ?>" placeholder = "コメント"></p>
				<p><input type = "text" name = "Pas" placeholder = "パスワード"></p>
      			<input type = "submit" value = "送信">
			<!--削除用フォーム-->
				<p><input type = "text" name = "delNum" placeholder = "削除対象番号"></p>
				<p><input type = "text" name = "delPas" placeholder = "パスワード"></p>
				<p><input type = "submit" value = "削除"></p>
			<!--編集用フォーム-->
				<p><input type = "text" name = "ediNum" placeholder = "編集対象番号"></p>
				<p><input type = "text" name = "ediPas" placeholder = "パスワード"></p>
				<p><input type = "submit" value = "編集"></p>
			<!--リセット用フォーム-->
				<p><input type = "submit" name = "reset" value = "全消去"></p>
		</form>

		<?php
			/*****ブラウザに出力*****/
			$sql = "SELECT * FROM dbData";
			$stmt = $pdo->query($sql);
			foreach($stmt as $row){
				echo $row['id']." ".$row['name']." ".$row['comment']." ".$row['date']."<br>";
			}
		?>
  	</body>
</html>	