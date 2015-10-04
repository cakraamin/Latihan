<link rel="stylesheet" href="<?=base_url()?>asset/js/jquery-ui/development-bundle/themes/base/jquery.ui.all.css">
<script src="<?=base_url()?>asset/js/jquery-ui/development-bundle/ui/jquery.ui.core.js"></script>
<script src="<?=base_url()?>asset/js/jquery-ui/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="<?=base_url()?>asset/js/jquery-ui/development-bundle/ui/jquery.ui.datepicker.js"></script>
<script>
	$(function() {
		$( "#datepicker" ).datepicker({
			changeMonth: true,
			changeYear: true
		});
	});
	</script>
<div id="utility">
	<ul id="nav-secondary">
		<li class="first"><a href="<?=base_url()?>laporan" <? if(isset($tambah)){ echo $tambah;}?>>Perkelas</a></li>
	</ul>
</div>
<div id="content">
<div class="cari">
<form method="post" action="<?=base_url()?>laporan/submit_laporan">
	<label for="nama"><b>Kelas :</b>
	<?
		if($kls == "")
		{
			$ketan = '';		
		}
		else
		{
			$ketan = 'OnChange="this.form.submit()"';
		}
		$pk = 'id="kelas" class="kelas" '.$ketan;
		echo form_dropdown('kelas',$kelas,$kls,$pk);
	?>
	</label><br/><br/>	
	<label for="kunci"><b>Tanggal Penyerahan :</b>
		<input type="text" id="datepicker" OnChange="this.form.submit()" name="tanggal" value="<? if(isset($tggl)){ echo $tggl;}?>">
	</label><br/><br/>
	<!--(0271) 854188-->
	<?
		if($bln != "" || $tgl != "" || $thn != "")
		{
	?>
	<label for="kunci"><b>Laporan :</b><a href="<?=base_url()?>laporan/lap/<?=$bln?>/<?=$tgl?>/<?=$thn?>/<?=$kls?>" target="_blank">Per tanggal <?=ganti_tanggal($thn."-".$bln."-".$tgl)?> Untuk Kode Kelas <?=$kls?></a></label>
	<?
		}	
	?>
</form>
</div>
<?=$this->load->view('admin/footer')?>
</div>