<!DOCTYPE html>
<html>
<head>
	<title>Multiple File Upload With PHP</title>
</head>
<body>

<style type="text/css">
  table {
    border-spacing: 0;
    border-collapse: collapse;
    width: 90%;   
}
th, td {
    text-align: center;
    padding: 8px;
    border-bottom: 1px solid #ddd;
}
tr:nth-child(even){
	background-color: #B4EA9F
}
th{
	background-color: #1C993F;
	text-align: center;
}
input[type=file] {
    width: 49%;
    padding: 12px 20px;
    margin: 8px 0;
    box-sizing: border-box;
    border: none;
    background-color: #3CBC8D;
    color: white;
    text-align: center;
}
input[type=submit] {
    width: 49%;
    padding: 15px 20px;
    margin: 8px 0;
    box-sizing: border-box;
    border: none;
    background-color: #3CBC8D;
    color: white;
}
label{
    padding: 10px 20px;
    margin: 8px 0;
    box-sizing: border-box;
    border: none;
    background-color: #3CBC8D;
    color: white;
    margin-left: 38%;
}
.div1 {
    width: 100%;
    height: 110px;
    border: 1px;
    color: #fff; background-color: #007F58
}

</style>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="files[]" id="files" multiple="" directory="" webkitdirectory="" mozdirectory="">
    <input type="submit" name="submit" value="Upload" /><br/><br>
    <label><input type="checkbox" name="drop" value="1" /> <u>Kosongkan tabel sql terlebih dahulu.</u> </label>
</form><br>

<div id="anggotakelompok" class="div1" align="center">
			<h3 align="center">Anggota Kelompok 3</h3>
			Yudha Riwanto<br>Zidni Lutvi Hamdan<br>Hafidz Ibalqis<br>
</div>	

<?php 
//koneksi ke database, username,password  dan namadatabase menyesuaikan 
mysql_connect('localhost', 'root', '');
mysql_select_db('uploadmultiexcel');

//memanggil file excel_reader
require "excel_reader.php";
			  	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			  		$drop = isset( $_POST["drop"] ) ? $_POST["drop"] : 0 ;
				    if($drop == 1){
				//             kosongkan tabel siswa
				             $truncate ="TRUNCATE TABLE siswa";
				             mysql_query($truncate);
				    };
				    foreach ($_FILES['files']['name'] as $j => $name) {
				        if (strlen($_FILES['files']['name'][$j]) > 1) {
				            if (move_uploaded_file($_FILES['files']['tmp_name'][$j],$name)) {

				                chmod($_FILES['files']['name'][$j],0777);
						    
						    	$data = new Spreadsheet_Excel_Reader($_FILES['files']['name'][$j],$name,false);
						    	echo $name;
						    
						//    menghitung jumlah baris file xls
						    	$baris = $data->rowcount($sheet_index=0);

    
//    import data excel mulai baris ke-2 (karena tabel xls ada header pada baris 1)
?>
<table align="center">

	<thead>
		<th>NPSN</th>
		<th>Nama</th>
		<th>Tahun Ajaran</th>
		<th>Kelas</th>
		<th>Jurusan</th>
		<th>Rombel</th>
		<th>Putra</th>
		<th>Putri</th>
		<th>KMS</th>
		<th>NON KMS</th>
		<th>Jumlah Siswa</th>
	</thead>
	<tbody>
	<?php
    for ($i=2; $i<=$baris; $i++)
    {
//       membaca data (kolom ke-1 sd terakhir)
      $npsn           = $data->val($i, 1,0);
      $nama_sekolah	  = $data->val($i, 2,0);
      $th_ajaran  	  = $data->val($i, 3,0);
      $kelas  		  = $data->val($i, 4,0);
      $jurusan  	  = $data->val($i, 5,0);
      $rombel 		  = $data->val($i, 6,0);
      $pa   		  = $data->val($i, 7,0);
      $pi 	 		  = $data->val($i, 8,0);
      $kms 	 		  = $data->val($i, 9,0);
      $non_kms 		  = $data->val($i, 10,0);
      $jml_siswa 	  = $data->val($i, 11,0);
   
 
//      setelah data dibaca, masukkan ke tabel pegawai sql
      $query = "INSERT into siswa(npsn,nama_sekolah,th_ajaran,kelas,jurusan,rombel,pa,pi,kms,non_kms,jml_siswa)
      			values('$npsn','$nama_sekolah','$th_ajaran','$kelas','$jurusan','$rombel','$pa','$pi','$kms','$non_kms','$jml_siswa')";
      $hasil = mysql_query($query);
      	echo "<tr>
			<td> ".$npsn."</td>
			<td> ".$nama_sekolah." </td>
			<td> ".$th_ajaran." </td>
			<td> ".$kelas." </td>
			<td> ".$jurusan." </td>
			<td> ".$rombel." </td>
			<td> ".$pa." </td>
			<td> ".$pi." </td>
			<td> ".$kms." </td>
			<td> ".$non_kms." </td>
			<td> ".$jml_siswa." </td>
		</tr>";
    }
    ?>
     </tbody>
</table>
     
<?php
//    hapus file xls yang udah dibaca
    unlink($_FILES['files']['name'][$j]);
    			}
 		}
 	}
}
 
?>
 
</body>
</html>