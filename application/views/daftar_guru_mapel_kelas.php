<div id="content">
<div class="cari">
<form method="post" action="<?=base_url()?>mapel/submit_guru_mapel">
	<label for="kunci"><b>Mapel :</b>
	<?php
		$pm = 'id="mapel" class="mapel" OnChange="this.form.submit()" ';
		echo form_dropdown('mapel',$mapels,'',$pm);
	?>
	</label>		
</form>
</div><hr />
<?=$this->load->view('admin/footer')?>
</div>