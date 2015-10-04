<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('mnilai','',TRUE);
		$this->load->model('mmenu','',TRUE);
		$this->load->model('mlaporan','',TRUE);
		
		if($this->session->userdata('level') == '' || $this->session->userdata('kd_ta') == '')
		{
			redirect('login/logout');
		}
	}

	function index()
	{
		$this->load->library('arey');

		$data = array(
			'main'		=> 'fguru/laporan',
			'lap'		=> 'class="active"',
			'type'		=> 'b',
			'mapels'	=> $this->mlaporan->getMapells(),
			'menu'		=> $this->mmenu->getTeamTeaching(),	
			'laporan'	=> $this->arey->getJenisLaporan(),
			'guru'		=> "class='active'",
			'sWali'		=> $this->mlaporan->getStatWali()
		);

		$this->load->view('fguru/template',$data);
	}	

	public function wali($id=NULL)
	{
		if(is_null($id))
		{
			$this->message->set('information','Maaf Perwalian Tidak Ditemukan');
			redirect("fguru/laporan");
		}

		$this->load->library('arey');

		$data = array(
			'main'		=> 'fguru/laporan_wali',
			'lap'		=> 'class="active"',
			'type'		=> 'b',
			'mapels'	=> $this->mlaporan->getMapells(),
			'menu'		=> $this->mmenu->getTeamTeaching(),	
			'laporan'	=> $this->arey->getJenisLaporan(),
			'wali'		=> "class='active'",
			'sWali'		=> $id
		);

		$this->load->view('fguru/template',$data);
	}

	function submit_laporan()
	{
		$jenis = $this->input->post('jenis',TRUE);
		$mapel = $this->input->post('mapel',TRUE);

		if($mapel == 0)
		{
			$this->message->set('information','Jenis Mapel Belum Dipilih');
			redirect("fguru/laporan");
		}

		$this->load->library(array('excel','arey'));		

		$master = $this->mlaporan->getMasterAll($mapel);		

		if($jenis == 1)
		{
			$objPHPExcel = PHPExcel_IOFactory::load("./template/PENGETAHUAN.xls");
			$judul = "PENGETAHUAN";						

			$objPHPExcel->getActiveSheet()->setCellValue('C11', ": ".$master['sem']);
			$objPHPExcel->getActiveSheet()->setCellValue('C12', ": ".$master['kelas']['kelas']);
			$objPHPExcel->getActiveSheet()->setCellValue('P11', "Mata Pelajaran : ".$master['kelas']['mapel']);
			$objPHPExcel->getActiveSheet()->setCellValue('P12', "Wali Kelas       : ".$master['kelas']['wali']['nama_wali']);

			$objPHPExcel->getActiveSheet()->setCellValue('A60', $master['kelas']['kepala']['nama_kepala']);
			$objPHPExcel->getActiveSheet()->setCellValue('A61', $master['kelas']['kepala']['nip_kepala']);

			$objPHPExcel->getActiveSheet()->setCellValue('O55', "Rembang, ".date("d")." ".$this->arey->getBulane(date("n"))." ".date("Y"));

			$objPHPExcel->getActiveSheet()->setCellValue('O60', $master['kelas']['guru_mapel']['nama_mapel']);
			$objPHPExcel->getActiveSheet()->setCellValue('O61', "'".$master['kelas']['guru_mapel']['nip_mapel']);

			$pengetahuan = $this->mlaporan->getAllPengetahuan($master['kelas']['id_kelas'],$master['kelas']['id_guru_mapel'],$master['kelas']['id_mapel']);						

			$awal = 17;
			$no = 1;
			foreach($pengetahuan as $detail)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$awal, $no);				
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$awal, $detail['nis']);				
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$awal, $detail['nama']);				
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$awal, $detail['detail']['1']);				
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$awal, $detail['detail']['2']);				
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$awal, $detail['detail']['3']);				
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$awal, $detail['detail']['4']);				
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$awal, $detail['detail']['5']);				
				$objPHPExcel->getActiveSheet()->setCellValue('P'.$awal, implode(",", $detail['keterangan']));								
				$awal++;
				$no++;
			}			
		}
		elseif($jenis == 2)
		{
			$objPHPExcel = PHPExcel_IOFactory::load("./template/KETERAMPILAN.xls");
			$judul = "KETERAMPILAN";

			$objPHPExcel->getActiveSheet()->setCellValue('C11', ": ".$master['sem']);
			$objPHPExcel->getActiveSheet()->setCellValue('C12', ": ".$master['kelas']['kelas']);
			$objPHPExcel->getActiveSheet()->setCellValue('M11', "Mata Pelajaran : ".$master['kelas']['mapel']);
			$objPHPExcel->getActiveSheet()->setCellValue('M12', "Wali Kelas       : ".$master['kelas']['wali']['nama_wali']);

			$objPHPExcel->getActiveSheet()->setCellValue('A60', $master['kelas']['kepala']['nama_kepala']);
			$objPHPExcel->getActiveSheet()->setCellValue('A61', $master['kelas']['kepala']['nip_kepala']);

			$objPHPExcel->getActiveSheet()->setCellValue('O55', "Rembang, ".date("d")." ".$this->arey->getBulane(date("n"))." ".date("Y"));

			$objPHPExcel->getActiveSheet()->setCellValue('O60', $master['kelas']['guru_mapel']['nama_mapel']);
			$objPHPExcel->getActiveSheet()->setCellValue('O61', "'".$master['kelas']['guru_mapel']['nip_mapel']);
			
			$keterampilan = $this->mlaporan->getAllKeterampilan($master['kelas']['id_kelas'],$master['kelas']['id_guru_mapel']);

			$objPHPExcel->getActiveSheet()->setCellValue('C11', "okelah");
		}
		else
		{
			$objPHPExcel = PHPExcel_IOFactory::load("./template/SIKAP.xls");
			$judul = "SIKAP";

			$objPHPExcel->getActiveSheet()->setCellValue('C11', ": ".$master['sem']);
			$objPHPExcel->getActiveSheet()->setCellValue('C12', ": ".$master['kelas']['kelas']);
			$objPHPExcel->getActiveSheet()->setCellValue('M11', "Mata Pelajaran : ".$master['kelas']['mapel']);
			$objPHPExcel->getActiveSheet()->setCellValue('M12', "Wali Kelas       : ".$master['kelas']['wali']['nama_wali']);

			$objPHPExcel->getActiveSheet()->setCellValue('A60', $master['kelas']['kepala']['nama_kepala']);
			$objPHPExcel->getActiveSheet()->setCellValue('A61', $master['kelas']['kepala']['nip_kepala']);

			$objPHPExcel->getActiveSheet()->setCellValue('O55', "Rembang, ".date("d")." ".$this->arey->getBulane(date("n"))." ".date("Y"));

			$objPHPExcel->getActiveSheet()->setCellValue('O60', $master['kelas']['guru_mapel']['nama_mapel']);
			$objPHPExcel->getActiveSheet()->setCellValue('O61', "'".$master['kelas']['guru_mapel']['nip_mapel']);

			$sikap = $this->mlaporan->getAllSikap($master['kelas']['id_kelas'],$master['kelas']['id_guru_mapel']);
		}		
		
		/*$objWorkSheetBase = $objPHPExcel->getSheet();
		$objWorkSheet1 = clone $objWorkSheetBase;		
		$objWorkSheet1->setTitle('Pengamatan');		
		$objPHPExcel->addSheet($objWorkSheet1);*/

		$filename="DAFTAR ".$judul." ".mt_rand(1,100000).".xls"; 
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment;filename='".$filename."'");
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
		$objWriter->save("php://output");

		exit;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */