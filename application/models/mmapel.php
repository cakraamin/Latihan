<?php

class Mmapel extends CI_Model{
	function __construct()
	{
		parent::__construct();
	}
	
	function getMapel($num,$offset,$sort_by,$sort_order)
	{
		if (empty($offset))
		{
			$offset=0;
		}
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('mapel');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'mapel';
		$sql = "SELECT * FROM mapel ORDER BY $sort_by $sort_order LIMIT $offset,$num";
		$query = $this->db->query($sql);
		return $query->result();
	}	
	
	function cekMapel()
	{
		$kueri = $this->db->query(" SELECT * FROM mapel WHERE mapel='".strip_tags(ascii_to_entities(addslashes($this->input->post('nama',TRUE))))."' ");
		return $kueri->num_rows();
	}
	
	function cekMapels($id)
	{
		$kueri = $this->db->query(" SELECT * FROM mapel WHERE mapel='".strip_tags(ascii_to_entities(addslashes($this->input->post('nama',TRUE))))."' AND id_mapel<>'$id' ");
		return $kueri->num_rows();
	}
	
	function addMapel()
	{	
		$this->db->query("INSERT INTO mapel(mapel,jum_jam_mapel,jenis,harian,tugas,ulangan) VALUES('".strip_tags(ascii_to_entities(addslashes($this->input->post('nama',TRUE))))."','".$this->input->post('jam',TRUE)."','".$this->input->post('jenis',TRUE)."','".$this->input->post('harian',TRUE)."','".$this->input->post('tugas',TRUE)."','".$this->input->post('ulangan',TRUE)."')");
	}	
	
	function getMapelId($id)
	{
		$sql = "SELECT * FROM mapel WHERE id_mapel='$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	function updateMapel($id)
	{
		$kueri = $this->db->query("UPDATE mapel SET mapel='".strip_tags(ascii_to_entities(addslashes($this->input->post('nama',TRUE))))."',jum_jam_mapel='".$this->input->post('jam',TRUE)."',jenis='".$this->input->post('jenis',TRUE)."',harian='".$this->input->post('harian',TRUE)."',tugas='".$this->input->post('tugas',TRUE)."',ulangan='".$this->input->post('ulangan',TRUE)."' WHERE id_mapel='$id' ");
		return $kueri;
	}
	
	function delMapel($id)
	{
		$kueri = $this->db->query("DELETE FROM mapel WHERE id_mapel='$id'");
		return $kueri;
	}
	
	function searchMapel($kunci,$num,$offset,$sort_by,$sort_order)
	{
		if (empty($offset))
		{
			$offset=0;
		}
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('mapel');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'mapel';
		$sql = "SELECT * FROM mapel WHERE mapel LIKE '%$kunci%' ORDER BY $sort_by $sort_order LIMIT $offset,$num";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function lookup($keyword)
	{
		$this->db->select('*')->from('guru');
        $this->db->like('nama',$keyword,'after');
        $query = $this->db->get();    
        
        return $query->result();
	}
	
	function getMapelGuru($num,$offset,$sort_by,$sort_order)
	{
		if (empty($offset))
		{
			$offset=0;
		}
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('b.mapel');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'b.mapel';
		$sql = "SELECT * FROM guru_mapel a,mapel b WHERE a.id_mapel=b.id_mapel GROUP BY id_team ORDER BY $sort_by $sort_order LIMIT $offset,$num";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function numMapelGuru()
	{
		$sql = "SELECT * FROM guru_mapel a,mapel b WHERE a.id_mapel=b.id_mapel GROUP BY id_team";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	
	function getMapell()
	{
		$query = $this->db->query("SELECT * FROM mapel");

		if ($query->num_rows()> 0){
			foreach ($query->result_array() as $row)
			{
				$data[$row['id_mapel']] = $row['mapel'];
			}
		}
		else
		{
			$data[''] = "";
		}
		//$query->free_result();
		return $data;
	}
	
	function getMapells()
	{
		$query = $this->db->query("SELECT * FROM guru_mapel a,mapel b WHERE a.id_mapel=b.id_mapel GROUP BY id_team");

		if ($query->num_rows()> 0)
		{
			$data[0] = "Pilih Nama Kelas";
			foreach ($query->result_array() as $row)
			{
				
				$data[$row['id_mapel']] = $row['mapel']." [".$this->getAllGuru($row['id_team'])."]";
			}
		}
		else
		{
			$data[''] = "";
		}
		return $data;
	}
	
	function cekMapelGuru()
	{
		$kueri = $this->db->query(" SELECT * FROM guru_mapel WHERE nip='".strip_tags(ascii_to_entities(addslashes($this->input->post('nip',TRUE))))."' AND id_mapel='".$this->input->post('nip',TRUE)."' ");
		return $kueri->num_rows();
	}
	
	function addMapelGuru($nip,$tim,$persen)
	{
		$sql = "INSERT INTO guru_mapel(nip,id_mapel,id_team,persen) VALUES('".strip_tags(ascii_to_entities(addslashes($nip)))."','".$this->input->post('mapel',TRUE)."','$tim','".strip_tags(ascii_to_entities(addslashes($persen)))."')";
		$kueri = $this->db->query($sql);
		return $kueri;	
	}
	
	function cekNip()
	{
		$kueri = $this->db->query(" SELECT * FROM guru WHERE nip='".strip_tags(ascii_to_entities(addslashes($this->input->post('nip',TRUE))))."' ");
		return $kueri->num_rows();
	}
	
	function cekNips($id)
	{
		$kueri = $this->db->query(" SELECT * FROM guru WHERE nip='".strip_tags(ascii_to_entities(addslashes($id)))."' ");
		return $kueri->num_rows();
	}
	
	function getIdGuruMapel($id)
	{
		$kueri = $this->db->query("SELECT * FROM guru_mapel a,guru b WHERE a.nip=b.nip AND a.id_team='$id'");
		return $kueri->row();
	}
	
	function cekMapelGurus($id)
	{
		$kueri = $this->db->query(" SELECT * FROM guru_mapel WHERE nip='".strip_tags(ascii_to_entities(addslashes($this->input->post('nip',TRUE))))."' AND id_mapel='".$this->input->post('nip',TRUE)."' AND id_guru_mapel<>'$id' ");
		return $kueri->num_rows();
	}
	
	function updateMapelGuru($id,$nip,$persen)
	{
		$sql = "UPDATE guru_mapel SET nip='".strip_tags(ascii_to_entities(addslashes($nip)))."',persen='".strip_tags(ascii_to_entities(addslashes($persen)))."' WHERE id_guru_mapel='$id'";
		$kueri = $this->db->query($sql);
		return $kueri;	
	}
	
	function delGuruMapel($id)
	{
		$kueri = $this->db->query("DELETE FROM guru_mapel WHERE id_team='$id'");
		return $kueri;
	}
	
	function searchMapelGuru($kunci,$num,$offset,$sort_by,$sort_order)
	{
		if (empty($offset))
		{
			$offset=0;
		}
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('b.mapel');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'b.mapel';
		$sql = "SELECT * FROM guru_mapel a,mapel b WHERE a.id_mapel=b.id_mapel AND b.mapel LIKE '%".$kunci."%' GROUP BY id_team ORDER BY $sort_by $sort_order LIMIT $offset,$num";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function numsearchMapelGuru($kunci)
	{
		$sql = "SELECT * FROM guru_mapel a,mapel b WHERE a.id_mapel=b.id_mapel AND b.mapel LIKE '%".$kunci."%' GROUP BY id_team ";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	
	function getMapelKelas($kunci,$num,$offset,$sort_by,$sort_order)
	{
		if (empty($offset))
		{
			$offset=0;
		}
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('c.nama','b.mapel');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'c.nama';
		$sql = "SELECT * FROM guru_mapel_kelas a,guru b WHERE a.nip=c.nip AND a.id_mapel=b.id_mapel AND c.nama LIKE '%".$kunci."%' ORDER BY $sort_by $sort_order LIMIT $offset,$num";
		$query = $this->db->query($sql);
		return $query;
	}
	
	function getKelas()
	{
		$query = $this->db->query("SELECT a.id_kelas,a.tingkat,a.nama,a.kode FROM kelas a");

		if ($query->num_rows()> 0)
		{
			foreach ($query->result_array() as $row)
			{
				$data[$row['id_kelas']] = $row['tingkat']." ".$this->arey->getPenjurusan($row['kode'])." ".$row['nama'];
			}
		}
		else
		{
			$data[''] = "";
		}
		$query->free_result();
		return $data;
	}
	
	function getNamaGuru($id)
	{
		$kueri = $this->db->query("SELECT * FROM guru a,guru_mapel b WHERE a.nip=b.nip AND b.id_guru_mapel='$id'");
		return $kueri->row();
	}
	
	function addMapelKelasGuru($id)
	{
		$kueri = $this->db->query("INSERT INTO guru_mapel_kelas(id_mapel,id_kelas,id_ta) VALUES('$id','".$this->input->post('kelas',TRUE)."','".$this->session->userdata('kd_ta')."')");
		return $kueri;
	}
	
	function getMapelGuruKelas($id,$num,$offset,$sort_by,$sort_order)
	{
		if (empty($offset))
		{
			$offset=0;
		}
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('b.nama');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'b.nama';
		$sql = "SELECT a.id_mapel_kelas,b.nama,b.id_kelas,b.tingkat,b.kode FROM guru_mapel_kelas a,kelas b,guru_mapel c WHERE a.id_kelas=b.id_kelas AND a.id_mapel=c.id_mapel AND a.id_mapel='$id' GROUP BY a.id_kelas ORDER BY $sort_by $sort_order LIMIT $offset,$num";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function numMapelGuruKelas($id)
	{
		$sql = "SELECT a.id_mapel_kelas,b.nama,b.id_kelas FROM guru_mapel_kelas a,kelas b,guru_mapel c WHERE a.id_kelas=b.id_kelas AND a.id_mapel=c.id_mapel AND a.id_mapel='$id' ";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	
	function getIdMapelKelas($id)
	{
		$kueri = $this->db->query("SELECT * FROM guru_mapel_kelas WHERE id_mapel_kelas='$id'");
		return $kueri->row();
	}
	
	function updateMapelKelasGuru($id)
	{
		$kueri = $this->db->query("UPDATE guru_mapel_kelas SET id_kelas='".$this->input->post('kelas',TRUE)."' WHERE id_mapel_kelas='$id'");
		return $kueri;
	}
	
	function delGuruMapels($id)
	{
		$kueri = $this->db->query("DELETE FROM guru_mapel_kelas WHERE id_mapel_kelas='$id'");
		return $kueri;
	}
	
	function getIdTeam()
	{
		$kueri = $this->db->query("SELECT id_team FROM guru_mapel ORDER BY id_guru_mapel DESC ");
		if($kueri->num_rows() > 0)
		{
			$data = $kueri->row();
			$id = $data->id_team + 1;
		}
		else
		{
			$id = 1;
		}		
		return $id;
	}
	
	function getAllGuru($id)
	{
		$kueri = $this->db->query("SELECT * FROM guru_mapel a,guru b WHERE a.nip=b.nip AND a.id_team='$id'");
		$data = $kueri->result();
		$daftar = '';
		foreach($data as $nilai)
		{
			$daftar .= $nilai->nama.", ";
		}
		$jumlah = strlen($daftar);
		return substr($daftar, 0, $jumlah - 2);
	}
	
	function getAllPersen($id)
	{
		$kueri = $this->db->query("SELECT * FROM guru_mapel WHERE id_team='$id'");
		$data = $kueri->result();
		$daftar = '';
		foreach($data as $nilai)
		{
			$daftar .= $nilai->persen.":";
		}
		$jumlah = strlen($daftar);
		return substr($daftar, 0, $jumlah - 1);
	}
	
	function getAllGuruEdit($id)
	{
		$kueri = $this->db->query("SELECT * FROM guru_mapel a,guru b WHERE a.nip=b.nip AND a.id_team='$id'");
		return $kueri->result();
	}
	
	
	
	
	
	
	
	
	
	function addKelas()
	{	
		$this->db->query("INSERT INTO kelas(nama) VALUES('".strip_tags(ascii_to_entities(addslashes($this->input->post('nama',TRUE))))."')");
	}
	
	function updateKelasWali($id)
	{
		$kueri = $this->db->query("UPDATE kelas_wali SET nip='".strip_tags(ascii_to_entities(addslashes($this->input->post('nip',TRUE))))."',id_kelas='".$this->input->post('kelas',TRUE)."' WHERE id_kelas_wali='$id' ");
		return $kueri;
	}
	
	function addKelasWali()
	{	
		$this->db->query("INSERT INTO kelas_wali(id_kelas,nip,id_ta) VALUES('".$this->input->post('kelas',TRUE)."','".strip_tags(ascii_to_entities(addslashes($this->input->post('nip',TRUE))))."','".$this->session->userdata('kd_ta')."')");
	}

	function cekKelas()
	{
		$kueri = $this->db->query(" SELECT * FROM kelas WHERE nama='".strip_tags(ascii_to_entities(addslashes($this->input->post('nama',TRUE))))."' ");
		return $kueri->num_rows();
	}
	
	function cekWali()
	{
		$kueri = $this->db->query(" SELECT * FROM kelas WHERE nip='".$this->input->post('wali',TRUE)."' AND id_ta='".$this->session->userdata('kd_ta')."' ");
		return $kueri->num_rows();
	}
	
	function cekWalis()
	{
		$kueri = $this->db->query(" SELECT * FROM kelas_wali WHERE nip='".strip_tags(ascii_to_entities(addslashes($this->input->post('nip',TRUE))))."' AND id_ta='".$this->session->userdata('kd_ta')."' ");
		return $kueri->num_rows();
	}
	
	function cekWalisE($id)
	{
		$kueri = $this->db->query(" SELECT * FROM kelas_wali WHERE nip='".strip_tags(ascii_to_entities(addslashes($this->input->post('nip',TRUE))))."' AND id_ta='".$this->session->userdata('kd_ta')."' AND id_kelas_wali<>'$id' ");
		return $kueri->num_rows();
	}
	
	function getGuru()
	{
		$query = $this->db->query("SELECT * FROM guru");

		if ($query->num_rows()> 0){
			foreach ($query->result_array() as $row)
			{
				$data[$row['nip']] = $row['nama'];
			}
		}
		else
		{
			$data[''] = "";
		}
		$query->free_result();
		return $data;
	}
	
	function getKelasW()
	{
		$query = $this->db->query("SELECT * FROM kelas");

		if ($query->num_rows()> 0){
			foreach ($query->result_array() as $row)
			{
				$data[$row['id_kelas']] = $row['nama'];
			}
		}
		else
		{
			$data[''] = "";
		}
		$query->free_result();
		return $data;
	}
	
	function getKelasSiswa($id)
	{
		$kueri = $this->db->query("SELECT * FROM kelas a,kelas_siswa b,siswa b WHERE a.id_kelas=b.id_kelas AND b.nip=c.nip AND a.id_kelas='$id'");
		return $kueri;
	}
	
	function cekNis()
	{
		$kueri = $this->db->query(" SELECT * FROM siswa WHERE nis='".strip_tags(ascii_to_entities(addslashes($this->input->post('nis',TRUE))))."' ");
		return $kueri->num_rows();
	}
	
	function cekKelasSiswa()
	{
		$kueri = $this->db->query(" SELECT * FROM kelas_siswa WHERE nis='".strip_tags(ascii_to_entities(addslashes($this->input->post('nis',TRUE))))."' AND id_ta='".$this->session->userdata('kd_ta')."' ");
		return $kueri->num_rows();
	}
	
	function addKelasSiswa()
	{	
		$this->db->query("INSERT INTO kelas_siswa(id_kelas,nis,id_ta) VALUES('".$this->input->post('kode',TRUE)."','".strip_tags(ascii_to_entities(addslashes($this->input->post('nis',TRUE))))."','".$this->session->userdata('kd_ta')."')");
	}
	
	function getKelass($kelas,$num,$offset,$sort_by,$sort_order)//menu admin
	{
		if (empty($offset))
		{
			$offset=0;
		}
		if (empty($kelas))
		{
			$kelas = "";
		}
		else
		{
			$kelas = "AND a.id_kelas='$kelas'";
		}
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('c.nis','c.nama');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'c.nis';
		$sql = "SELECT a.id_kelas_sis,b.nama as kelas,c.nama as jeneng,c.nis FROM kelas_siswa a,kelas b,siswa c WHERE a.nis=c.nis AND a.id_kelas=b.id_kelas $kelas AND a.id_ta='".$this->session->userdata('kd_ta')."' ORDER BY $sort_by $sort_order LIMIT $offset,$num";
		$query = $this->db->query($sql);
		return $query;
	}
	
	function getKelase($num,$offset,$sort_by,$sort_order)//menu admin
	{
		if (empty($offset))
		{
			$offset=0;
		}
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('nama');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'nama';
		$sql = "SELECT id_kelas,nama FROM kelas ORDER BY $sort_by $sort_order LIMIT $offset,$num";
		$query = $this->db->query($sql);
		return $query;
	}
	
	function getKelaseW($num,$offset,$sort_by,$sort_order)//menu admin
	{
		if (empty($offset))
		{
			$offset=0;
		}
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('a.nama');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'a.nama';
		$sql = "SELECT a.id_kelas,a.nama as nama,c.nama as wali,b.id_kelas_wali FROM kelas a,kelas_wali b,guru c WHERE b.nip=c.nip AND a.id_kelas=b.id_kelas ORDER BY $sort_by $sort_order LIMIT $offset,$num";
		$query = $this->db->query($sql);
		return $query;
	}
	
	function getWaliKelas($id)
	{
		$kueri = $this->db->query("SELECT * FROM kelas_wali WHERE id_kelas_wali='$id' ");
		return $kueri->row();
	}
	
	function searchKelase($kunci,$num,$offset,$sort_by,$sort_order)
	{
		if (empty($offset))
		{
			$offset=0;
		}
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('a.nama');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'a.nama';
		$sql = "SELECT a.id_kelas,a.nama as nama,b.nama as wali FROM kelas a,guru b WHERE a.nip=b.nip AND a.nama LIKE '%$kunci%' ORDER BY $sort_by $sort_order LIMIT $offset,$num";
		$query = $this->db->query($sql);
		return $query;
	}
	
	function getKelast($id)
	{
		$sql = "SELECT * FROM kelas WHERE id_kelas='$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	function getKelasSiswaId($id)
	{
		$sql = "SELECT * FROM kelas_siswa WHERE id_kelas_sis='$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	function updateKelas()
	{
		$sql = "UPDATE kelas SET nama='".strip_tags(ascii_to_entities(addslashes($this->input->post('nama',TRUE))))."' WHERE id_kelas='".$this->input->post('kode',TRUE)."' ";
		$this->db->query($sql);	
	}
	
	function updateKelasSiswa()
	{
		$sql = "UPDATE kelas_siswa SET nis='".strip_tags(ascii_to_entities(addslashes($this->input->post('nis',TRUE))))."',id_kelas='".$this->input->post('kelas',TRUE)."' WHERE id_kelas_sis='".$this->input->post('kode',TRUE)."' ";
		$this->db->query($sql);	
	}
	
	function delKelas()
	{
		$this->db->query("DELETE FROM kelas");
	}
	
	function getNip($id)
	{
		$query = "SELECT * FROM guru WHERE nama='$id'";
		$kueri = $this->db->query($query);
		$data = $kueri->row();
		return $data->nip;
	}
	
	function importKelas($value)
	{
		$kueri = $this->db->query("INSERT INTO kelas VALUES($value)");
		return $kueri;
	}
	
	function exportKelas()
	{
		$query = $this->db->query("SELECT a.id_kelas,a.nama as nama,b.nama as wali FROM kelas a,guru b WHERE a.nip=b.nip");
		return $query;
	}
	
	function del_kelas($id)
	{
		$kueri = $this->db->query("DELETE FROM kelas WHERE id_kelas='$id'");
		return $kueri;
	}
	
	function del_wali($id)
	{
		$kueri = $this->db->query("DELETE FROM kelas_wali WHERE id_kelas_wali='$id' AND id_ta='".$this->session->userdata('kd_ta')."'");
		return $kueri;
	}
	
	function del_kelas_siswa($id)
	{
		$kueri = $this->db->query("DELETE FROM kelas_siswa WHERE id_kelas_sis='$id'");
		return $kueri;
	}
	
	function addRecordSiswa()
	{
		$kueri = $this->db->query("INSERT INTO record_siswa(nis,id_ta) VALUES('".strip_tags(ascii_to_entities(addslashes($this->input->post('nis',TRUE))))."','".$this->session->userdata('kd_ta')."')");
		return $kueri;
	}
	
	function delRecordSiswa($id)
	{
		$kueri = $this->db->query("DELETE FROM record_siswa WHERE nis='".$id."' AND id_ta='".$this->session->userdata('kd_ta')."'");
		return $kueri;
	}
}
?>
