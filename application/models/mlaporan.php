<?php

class Mlaporan extends CI_Model{
	
	function __construct()
	{
		parent::__construct();
	}
	
	function getSiswaKelas()
	{
		$sql = "SELECT c.id_kelas,a.nis,a.nama as jeneng,d.bidang_keahlian,d.program_keahlian,c.nama as kelas,c.tingkat,e.nip FROM siswa a,kelas_siswa b,kelas c,keahlian d,kelas_wali e WHERE a.nis=b.nis AND b.id_kelas=c.id_kelas AND c.id_kelas=e.id_kelas AND c.id_keahlian=d.id_keahlian AND b.id_ta='".$this->session->userdata('kd_ta')."' AND e.nip='".$this->session->userdata('nip')."' ORDER BY a.nis ASC ";
		//$sql = "SELECT b.id_kelas,a.nis,a.nama as jeneng,d.bidang_keahlian,d.program_keahlian,e.nama as kelas,e.tingkat FROM siswa a,kelas_siswa b,kelas_wali c,keahlian d,kelas e WHERE c.id_kelas=b.id_kelas AND a.nis=b.nis AND c.nip='".$this->session->userdata('nip')."' AND a.id_keahlian=d.id_keahlian AND e.id_kelas=b.id_kelas AND b.id_ta='".$this->session->userdata('kd_ta')."' ORDER BY a.nis ASC";		
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getMapells()
	{
		$sql = "SELECT * FROM guru_mapel a,mapel b,guru_mapel_kelas c,kelas d WHERE a.id_mapel=b.id_mapel AND c.id_mapel=b.id_mapel AND c.id_kelas=d.id_kelas AND a.nip='".$this->session->userdata('nip')."'";		
		$query = $this->db->query($sql);

		if ($query->num_rows()> 0)
		{
			$data[0] = "Pilih Nama Pelajaran";
			foreach ($query->result_array() as $row)
			{
				
				$data[$row['id_mapel_kelas']] = $row['mapel']." Kelas ".$row['tingkat']." ".$this->arey->getPenjurusan($row['kode'])." ".$row['nama'];
			}
		}
		else
		{
			$data[''] = "Tidak Ada Mapel";
		}
		return $data;
	}
	
	function getSiswaKelasId($id)
	{
		$sql = "SELECT c.id_kelas,a.nis,a.nama as jeneng,d.bidang_keahlian,d.program_keahlian,c.nama as kelas,c.tingkat,e.nip FROM siswa a,kelas_siswa b,kelas c,keahlian d,kelas_wali e WHERE a.nis=b.nis AND b.id_kelas=c.id_kelas AND c.id_kelas=e.id_kelas AND c.id_keahlian=d.id_keahlian AND b.id_ta='".$this->session->userdata('kd_ta')."' AND a.nis='".$id."' AND e.nip='".$this->session->userdata('nip')."' ";
		//$sql = "SELECT b.id_kelas,a.nis,a.nama as jeneng,d.bidang_keahlian,d.program_keahlian,e.nama as kelas,e.tingkat FROM siswa a,kelas_siswa b,kelas_wali c,keahlian d,kelas e WHERE c.id_kelas=b.id_kelas AND a.nis=b.nis AND c.nip='".$this->session->userdata('nip')."' AND a.id_keahlian=d.id_keahlian AND e.id_kelas=b.id_kelas AND b.id_ta='".$this->session->userdata('kd_ta')."' AND a.nis='$id'";		
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function getSiswaKelasKls($id)
	{
		$sql = "SELECT c.id_kelas,a.nis,a.nama as jeneng,d.bidang_keahlian,d.program_keahlian,c.nama as kelas,c.tingkat,e.nip FROM siswa a,kelas_siswa b,kelas c,keahlian d,kelas_wali e WHERE a.nis=b.nis AND b.id_kelas=c.id_kelas AND c.id_kelas=e.id_kelas AND c.id_keahlian=d.id_keahlian AND b.id_ta='".$this->session->userdata('kd_ta')."' AND c.id_kelas='".$id."' ";
		//$sql = "SELECT b.id_kelas,a.nis,a.nama as jeneng,d.bidang_keahlian,d.program_keahlian,e.nama as kelas,e.tingkat,c.nip FROM siswa a,kelas_siswa b,kelas_wali c,keahlian d,kelas e WHERE c.id_kelas=b.id_kelas AND a.nis=b.nis AND a.id_keahlian=d.id_keahlian AND e.id_kelas=b.id_kelas AND b.id_ta='".$this->session->userdata('kd_ta')."' AND e.id_kelas='$id'";		
		$query = $this->db->query($sql);
		return $query->result();
		//return $sql;
	}
	
	function getListSiswaKelas()
	{
		$sql = "SELECT b.id_kelas,a.nis,a.nama as jeneng,d.bidang_keahlian,d.program_keahlian,e.nama as kelas,e.tingkat FROM siswa a,kelas_siswa b,kelas_wali c,keahlian d,kelas e WHERE c.id_kelas=b.id_kelas AND a.nis=b.nis AND c.nip='".$this->session->userdata('nip')."' AND a.id_keahlian=d.id_keahlian AND e.id_kelas=b.id_kelas AND b.id_ta='".$this->session->userdata('kd_ta')."'";		
		$query = $this->db->query($sql);
		if ($query->num_rows()> 0)
		{
			foreach ($query->result_array() as $row)
			{
				$data[$row['nis']] = $row['jeneng'];
			}
		}
		else
		{
			$data[''] = "";
		}
		$query->free_result();
		return $data;
	}
	
	function getKelas()
	{
		$sql = "SELECT a.id_kelas,a.tingkat,a.nama,b.kode_keahlian as kode FROM kelas a,keahlian b WHERE a.id_keahlian=b.id_keahlian";		
		$query = $this->db->query($sql);
		if ($query->num_rows()> 0)
		{
			foreach ($query->result_array() as $row)
			{
				$data[$row['id_kelas']] = $row['tingkat']." ".$row['nama']." ".$row['kode'];
			}
		}
		else
		{
			$data[''] = "";
		}
		$query->free_result();
		return $data;
	}
	
	function getDudi($kls,$nis)
	{
		$kueri = $this->db->query("SELECT * FROM industri WHERE id_kelas='$kls' AND nis='$nis' AND id_ta='".$this->session->userdata('kd_ta')."'");
		return $kueri->result();
	}
	
	function getCatat($kls,$nis)
	{
		$kueri = $this->db->query("SELECT * FROM catatan WHERE id_kelas='$kls' AND nis='$nis' AND id_ta='".$this->session->userdata('kd_ta')."' AND semester='".$this->session->userdata('kd_sem')."'");
		if($kueri->num_rows() > 0)
		{
			$data = $kueri->row();
			return $data->catatan;
		}
		else
		{
			return "";
		}
	}
	
	function getDiri($kls,$nis)
	{
		$kueri = $this->db->query("SELECT * FROM diri a,nilai_diri b WHERE a.id_diri=b.id_diri AND b.id_kelas='$kls' AND b.nis='$nis' AND b.id_ta='".$this->session->userdata('kd_ta')."' AND b.semester='".$this->session->userdata('kd_sem')."'");
		return $kueri->result();	
	}
	
	function getTA($id)
	{
		$kueri = $this->db->query("SELECT * FROM ta WHERE id_ta='$id'");
		$data = $kueri->row();
		return $data->ta;		
	}
	
	function getNamaWali()
	{
		$sql = "SELECT * FROM guru WHERE nip='".$this->session->userdata('nip')."'";
		$kueri = $this->db->query($sql);
		if($kueri->num_rows() > 0)
		{
			$data = $kueri->row();
			return $data->nama;
		}
	}
	
	function getNamaWalis($id)
	{
		$sql = "SELECT * FROM guru WHERE nip='$id'";
		$kueri = $this->db->query($sql);
		if($kueri->num_rows() > 0)
		{
			$data = $kueri->row();
			return $data->nama;
		}
	}
	
	function getNipWalis($id)
	{
		$sql = "SELECT * FROM guru WHERE nip='$id'";
		$kueri = $this->db->query($sql);
		if($kueri->num_rows() > 0)
		{
			$data = $kueri->row();
			return $data->nip;
		}
	}
	
	function getNipWali()
	{
		$sql = "SELECT * FROM guru WHERE nip='".$this->session->userdata('nip')."'";
		$kueri = $this->db->query($sql);
		$data = $kueri->row();
		return $data->nip;
	}
	
	function getMapel($kelas,$jenis)
	{
		$sql = "SELECT e.mapel,e.id_mapel FROM kelas a,kelas_siswa b,guru_mapel_kelas c,guru_mapel d,mapel e WHERE a.id_kelas=b.id_kelas AND a.id_kelas=c.id_kelas AND c.id_mapel AND d.id_mapel AND d.id_mapel=e.id_mapel AND b.id_kelas='$kelas' AND e.jenis='$jenis' AND c.id_mapel=e.id_mapel GROUP BY e.id_mapel";
		$kueri = $this->db->query($sql);
		return $kueri->result();
	}
	
	function getKkm($mapel,$tingkat)
	{
		if($tingkat == "X")
		{
			$sql = "SELECT kkm1 as nilai FROM kkm WHERE id_mapel='$mapel' AND id_ta='".$this->session->userdata('kd_ta')."'";
		}
		elseif($tingkat == "XI")
		{
			$sql = "SELECT kkm2 as nilai FROM kkm WHERE id_mapel='$mapel' AND id_ta='".$this->session->userdata('kd_ta')."'";
		}
		else
		{
			$sql = "SELECT kkm3 as nilai FROM kkm WHERE id_mapel='$mapel' AND id_ta='".$this->session->userdata('kd_ta')."'";
		}
		$kueri = $this->db->query($sql);
		if($kueri->num_rows() > 0)
		{
			$data = $kueri->row();
			return $data->nilai;
		}
		else
		{
			return "";		
		}
	}
	
	function getMapelAll($mapel,$kls)
	{
		$kueri = $this->db->query("SELECT b.persen,b.id_guru_mapel,d.rumus1,d.rumus2,d.rumus3 FROM mapel a,guru_mapel b,guru_mapel_kelas c,rumus_nilai d WHERE a.id_mapel=b.id_mapel AND a.id_mapel=c.id_mapel AND a.id_mapel='$mapel' AND c.id_kelas='$kls' AND c.id_ta='".$this->session->userdata('kd_ta')."' AND a.id_mapel=d.id_mapel GROUP BY b.id_guru_mapel");
		return $kueri->result();
	}
	
	function jumNilai($guru,$kls,$nis,$no)
	{
		$kueri = $this->db->query("SELECT * FROM nilai WHERE id_guru_mapel='$guru' AND id_kelas='$kls' AND nis='$nis' AND tingkat='$no'");
		return $kueri->result();
	}
	
	function jumNilaiCount($guru,$kls,$nis,$no)
	{
		$kueri = $this->db->query("SELECT nilai FROM nilai WHERE id_guru_mapel='$guru' AND id_kelas='$kls' AND nis='$nis' AND tingkat='$no'");
		$data = $kueri->result();
		return $kueri->num_rows();
	}
	
	function getStateNaik($kls,$nis)
	{
		$kueri = $this->db->query("SELECT * FROM kenaikan WHERE id_kelas_siswa='$kls' AND nis='$nis' AND id_ta='".$this->session->userdata('kd_ta')."'");
		if($kueri->num_rows() > 0)
		{
			$data = $kueri->row();
			return $data->status;
		}
		else
		{
			return "0";		
		}
	}

	function getMasterAll($id)
	{
		$hasil = array(
			'tahun'		=> $this->getTahun($this->session->userdata('kd_ta')),
			'sem'		=> $this->session->userdata('kd_sem'),
			'kelas'		=> $this->getKelasMapel($id)
		);

		return $hasil;
	}

	private function getTahun($id)
	{
		$kueri = $this->db->query("SELECT * FROM ta WHERE id_ta='$id'");
		$data = $kueri->row();
		$hasil = (isset($data->ta))?"Tahun Pelajaran ".$data->ta:"";
		return $hasil;
	}

	private function getKelasMapel($id)
	{
		$sql = "SELECT * FROM guru a,mapel b,guru_mapel c,guru_mapel_kelas d,kelas e WHERE c.nip=a.nip AND c.id_mapel=b.id_mapel AND d.id_mapel=b.id_mapel AND d.id_kelas=e.id_kelas AND d.id_mapel_kelas='$id'";	
		$kueri = $this->db->query($sql);
		$data = $kueri->row();
		$hasil = array(
			'kelas'			=> $data->tingkat." ".$this->arey->getPenjurusan($data->nama)." ".$data->kode,
			'mapel'			=> $data->mapel,			
			'wali'			=> $this->getWaliKelas($data->id_kelas),
			'guru_mapel'	=> $this->getGuruMapel($data->id_mapel),
			'kepala'		=> $this->getKepala(),
			'tanggal'		=> date("dd-mm-yyyy"),
			'id_kelas'		=> $data->id_kelas,
			'id_guru_mapel'	=> $data->id_guru_mapel,
			'id_mapel'		=> $data->id_mapel
		);
		return $hasil;
	}

	private function getGuruMapel($id)
	{
		$kueri = $this->db->query("SELECT * FROM guru_mapel a,guru b WHERE a.nip=b.nip AND a.id_mapel='$id'");
		$hasil = $kueri->row();
		$data = array(
			'nama_mapel'		=> (isset($hasil->nama))?$hasil->nama:"",
			'nip_mapel'			=> (isset($hasil->nip))?$hasil->nip:""
		);

		return $data;		
	}

	private function getWaliKelas($id)
	{
		$data = array();

		$kueri = $this->db->query("SELECT * FROM kelas_wali a,guru b WHERE a.nip=b.nip AND a.id_kelas='$id'");
		$hasil = $kueri->row();
		$data = array(
			'nama_wali'		=> (isset($hasil->nama))?$hasil->nama:"",
			'nip_wali'		=> (isset($hasil->nip))?$hasil->nip:""
		);

		return $data;
	}

	private function getKepala()
	{
		$data = array();

		$kueri = $this->db->query("SELECT * FROM kepala");
		$hasil = $kueri->row();

		$data = array(
			'nama_kepala'	=> (isset($hasil->nama_kep))?$hasil->nama_kep:"",
			'nip_kepala'	=> (isset($hasil->nip_kep))?$hasil->nip_kep:""
		);
		return $data;
	}

	public function getStatWali()
	{
		$kueri = $this->db->query("SELECT * FROM kelas_wali WHERE nip='".$this->session->userdata('nip')."'");
		if($kueri->num_rows() > 0)
		{
			$data = $kueri->row();
			$hasil = (isset($data->id_kelas))?$data->id_kelas:0;
		}	
		else
		{
			$hasil = 0;
		}	

		return $hasil;

	}

	public function getAllPengetahuan($kelas,$mapel,$id_mapel)
	{
		$hasil = array();
		$detail = array();
		$ket = array();

		$kueri = $this->db->query("SELECT * FROM kelas_siswa a,siswa b WHERE a.nis=b.nis AND a.id_kelas='$kelas' AND a.id_ta='".$this->session->userdata('kd_ta')."'");
		$data = $kueri->result();
		foreach($data as $dt)
		{
			for($i=1;$i<=5;$i++)
			{
				$detail[$i] = $this->getNilaiPengetahuan($dt->nis,$kelas,1,$mapel);
				$ket[$i] = $this->getKeterangan($detail[$i],$id_mapel,$i,1);
			}

			$hasil[] = array(
				'nis'			=> $dt->nis,
				'nama'			=> $dt->nama,
				'detail'		=> $detail,
				'keterangan'	=> $ket
			);
		}

		return $hasil;
	}

	private function getKeterangan($nilai,$mapel,$tingkat,$jenis)
	{
		if($nilai <= "76")
		{
			$kueri = $this->db->query("SELECT * FROM aspek_nilai WHERE id_mapel='$mapel' AND tingkat_aspek='$tingkat' AND jenis_aspek='$jenis'");
			$data = $kueri->row();
			$nilai = (isset($data->aspek))?$data->aspek:"";
			$hasil = $nilai." kurang";
		}
		else
		{
			$hasil = "";
		}

		return $hasil;
	}

	private function getNilaiPengetahuan($nis,$kelas,$tingkat,$mapel)
	{
		$sql = "SELECT * FROM nilai WHERE nis='$nis' AND id_ta='".$this->session->userdata('kd_ta')."' AND id_kelas='$kelas' AND semester='".$this->session->userdata('kd_sem')."' AND tingkat='$tingkat' AND id_guru_mapel='$mapel'";		
		$kueri = $this->db->query($sql);
		$data = $kueri->row();
		if(isset($data->nilai) && $data->nilai >= 76 )
		{
			$hasil = $data->nilai;			
		}	
		else
		{
			if(isset($data->remidi) && $data->remidi > 76)
			{
				$hasil = 76;
			}
			else
			{
				$hasil = (isset($data->nilai))?$data->nilai:0;;
			}			
		}
		$tugas = (isset($data->tugas))?$data->tugas:0;
		$hasil = $hasil + $tugas;
		$hasil = $hasil / 2;
		return $hasil;
	}

	public function getAllKeterampilan($kelas,$mapel)
	{
		$hasil = array();
		$detail = array();

		$kueri = $this->db->query("SELECT * FROM kelas_siswa a,siswa b WHERE a.nis=b.nis AND a.id_kelas='$kelas' AND a.id_ta='".$this->session->userdata('kd_ta')."'");
		$data = $kueri->result();
		foreach($data as $dt)
		{
			for($i=1;$i<=5;$i++)
			{
				$detail[$i] = $this->getNilaiKeterampilan($dt->nis,$kelas,1,$mapel);
			}

			$hasil[] = array(
				'nis'			=> $dt->nis,
				'nama'			=> $dt->nama,
				'detail'		=> $detail
			);
		}

		return $hasil;
	}

	private function getNilaiKeterampilan($nis,$kelas,$tingkat,$mapel)
	{
		$tingkat = $tingkat + 1;
		$kueri = $this->db->query("SELECT * FROM nilai_trampil WHERE nis='$nis' AND id_ta='".$this->session->userdata('kd_ta')."' AND id_kelas='$kelas' AND semester='".$this->session->userdata('kd_sem')."' AND ket='$tingkat' AND id_guru_mapel='$mapel'");		
		return $kueri->result_array();
	}

	public function getAllSikap($kelas,$mapel)
	{
		$hasil = array();
		$detail = array();

		$kueri = $this->db->query("SELECT * FROM kelas_siswa a,siswa b WHERE a.nis=b.nis AND a.id_kelas='$kelas' AND a.id_ta='".$this->session->userdata('kd_ta')."'");
		$data = $kueri->result();
		foreach($data as $dt)
		{
			for($i=1;$i<=5;$i++)
			{
				$detail[$i] = $this->getNilaiSikap($dt->nis,$kelas,1,$mapel);
			}

			$hasil[] = array(
				'nis'			=> $dt->nis,
				'nama'			=> $dt->nama,
				'detail'		=> $detail
			);
		}

		return $hasil;
	}

	private function getNilaiSikap($nis,$kelas,$tingkat,$mapel)
	{
		$kueri = $this->db->query("SELECT * FROM nilai_sikap WHERE nis='$nis' AND id_ta='".$this->session->userdata('kd_ta')."' AND id_kelas='$kelas' AND semester='".$this->session->userdata('kd_sem')."' AND tingkat='$tingkat' AND id_guru_mapel='$mapel'");		
		return $kueri->result_array();
	}
}
?>