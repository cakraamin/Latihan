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
			redirect('home');
		}
	}

	function index()
	{
		redirect('laporan/tanggal');
	}
	
	function tanggal($bln=NULL,$tgl=NULL,$thn=NULL,$kls=NULL)
	{
		$this->load->helper('tanggal');	
	
		if($bln == "" || $tgl == "" || $thn == "")
		{
			$tggl = "";
		}
		else
		{
			$tggl = $bln."/".$tgl."/".$thn;
		}
	
		$data = array(
			'main'		=> 'laporan',
			'lap'			=> 'class="active"',
			'type'		=> 'b',
			'mapels'		=> $this->mnilai->getMapells(),
			'menu'		=> $this->mmenu->getTeamTeaching(),
			'tggl'		=> $tggl,
			'bln'			=> $bln,
			'tgl'			=> $tgl,
			'thn'			=> $thn,
			'tambah'		=> "class='active'",
			'kelas'		=> $this->mlaporan->getKelas(),
			'kls'			=> $kls
		);
		$this->load->view('template',$data);
	}
	
	function per_anak($bln=NULL,$tgl=NULL,$thn=NULL,$ank=NULL)
	{
		$this->load->helper('tanggal');	
	
		if($bln == "" || $tgl == "" || $thn == "")
		{
			$tggl = "";
		}
		else
		{
			$tggl = $bln."/".$tgl."/".$thn;
		}
	
		$data = array(
			'main'		=> 'per_laporan',
			'lap'			=> 'class="active"',
			'type'		=> 'b',
			'mapels'		=> $this->mnilai->getMapells(),
			'menu'		=> $this->mmenu->getTeamTeaching(),
			'tggl'		=> $tggl,
			'bln'			=> $bln,
			'tgl'			=> $tgl,
			'thn'			=> $thn,
			'daftar'		=> "class='active'",
			'anak'		=> $this->mlaporan->getListSiswaKelas(),
			'ank'			=> $ank
		);
		$this->load->view('template',$data);
	}
	
	function submit_laporan()
	{
		$tgl = $this->input->post('tanggal',TRUE);
		$kelas = $this->input->post('kelas',TRUE);
		if($kelas == "")
		{
			redirect('laporan/tanggal/'.$tgl);
		}
		else
		{
			redirect('laporan/tanggal/'.$tgl."/".$kelas);
		}	
	}
	
	function lap($bln,$tgl,$thn,$kls=NULL)
	{
		if($kls == "")
		{
			redirect('laporan/generate/'.$bln.'/'.$tgl.'/'.$thn);
		}
		else
		{
			redirect('laporan/generate/'.$bln.'/'.$tgl.'/'.$thn.'/'.$kls);		
		}
	}
	
	function generate($bln=NULL,$tgl=NULL,$thn=NULL,$kls=NULL)
	{
		$this->load->helper('tanggal');
		$this->load->library('words');	

		require_once("./application/libraries/Table/class.fpdf_table.php");
	
		require_once("./application/libraries/Table/header_footer.inc");
		require_once("./application/libraries/Table/table_def.inc");	
	
		$bg_color1 = array(234, 255, 218);
		$bg_color2 = array(165, 250, 220);
		$bg_color3 = array(255, 252, 249);	
	
		$pdf = new pdf_usage();		
		$pdf->SetAutoPageBreak(true, 20);
   		$pdf->SetMargins(-10, 20, -10, -10);	
			
		$kueri = $this->mlaporan->getSiswaKelasKls($kls);
		//echo $kueri;
		//exit();
		foreach($kueri as $dt_kueri)
		{
	$pdf->AddPage();
	$pdf->AliasNbPages();

	$pdf->SetStyle("head1","arial","",8,"0,0,0");
		
	$pdf->SetY(10);
	$pdf->SetX(14);
	$pdf->MultiCellTag(100, 3, "<head1>Nama Peserta Didik</head1>");
	$pdf->SetY(10);
	$pdf->SetX(50);
	$pdf->MultiCellTag(100, 3, "<head1>: ".$dt_kueri->jeneng."</head1>");
	$pdf->SetY(10);
	$pdf->SetX(110);
	$pdf->MultiCellTag(100, 3, "<head1>Nomor Induk</head1>");
	$pdf->SetY(10);
	$pdf->SetX(140);
	$pdf->MultiCellTag(100, 3, "<head1>: ".$dt_kueri->nis."</head1>");
	
	$pdf->SetY(14);
	$pdf->SetX(14);
	$pdf->MultiCellTag(100, 3, "<head1>Bidang Keahlian</head1>");
	$pdf->SetY(14);
	$pdf->SetX(50);
	$pdf->MultiCellTag(100, 3, "<head1>: ".$dt_kueri->bidang_keahlian."</head1>");
	$pdf->SetY(14);
	$pdf->SetX(110);
	$pdf->MultiCellTag(100, 3, "<head1>Program Keahlian</head1>");
	$pdf->SetY(14);
	$pdf->SetX(140);
	$pdf->MultiCellTag(100, 3, "<head1>: ".$dt_kueri->program_keahlian."</head1>");
	
	$pdf->SetY(18);
	$pdf->SetX(14);
	$pdf->MultiCellTag(100, 3, "<head1>Tahun Pelajaran</head1>");
	$pdf->SetY(18);
	$pdf->SetX(50);
	$pdf->MultiCellTag(100, 3, "<head1>: ".$this->mlaporan->getTA($this->session->userdata('kd_ta'))."</head1>");
	$pdf->SetY(18);
	$pdf->SetX(110);
	$pdf->MultiCellTag(100, 3, "<head1>Kelas/Semester</head1>");
	$pdf->SetY(18);
	$pdf->SetX(140);
	$pdf->MultiCellTag(100, 3, "<head1>: ".$dt_kueri->tingkat." / ".$this->session->userdata('kd_sem')."</head1>");	
	
	$pdf->SetY(25);
	
	$columns = 7; //five columns

	$pdf->SetStyle("p","times","",10,"130,0,30");
	$pdf->SetStyle("t1","arial","",10,"0,151,200");
	$pdf->SetStyle("size","times","BI",10,"0,0,120");
	$pdf->SetStyle("judul","times","B",8,"0,0,0");
    
	$ttxt1 = "<size>Tag-Based MultiCell Table</size>\nCreated by <t1 href='mailto:andy@interpid.eu'>Bintintan Andrei, Interpid Team</t1>";
	$ttxt2 = "<p>The cells in the table are fully functional <t1>Tag Based Multicells components</t1>. The description and usage of these components can be found <t1>here</t1>.</p>";
	
	//Initialize the table class
	$pdf->tbInitialize($columns, true, true);
	
	//set the Table Type
	$pdf->tbSetTableType($table_default_table_type);
	
	//Table Header
	for($i=0; $i<$columns; $i++) $header_type[$i] = $table_default_header_type;
	
	for($i=0; $i<$columns; $i++) {
		$header_type1[$i] = $table_default_header_type;
		$header_type2[$i] = $table_default_header_type;
	}

	$header_type1[0]['WIDTH'] = 10;
	$header_type1[1]['WIDTH'] = 60;
	$header_type1[2]['WIDTH'] = 11;
	$header_type1[3]['WIDTH'] = 11;
	$header_type1[4]['WIDTH'] = 43;
	$header_type1[5]['WIDTH'] = 17;
	$header_type1[6]['WIDTH'] = 28;
	
	$header_type1[0]['TEXT'] = "NO";
	$header_type1[1]['TEXT'] = "Mata Pelajaran";
	$header_type1[2]['TEXT'] = "KKM";
		
	$header_type1[3]['TEXT'] = "Nilai Hasil Belajar";
	$header_type1[3]['COLSPAN'] = 4;
	$header_type1[3]['T_ALIGN'] = 'C';
	
	$header_type1[0]['ROWSPAN'] = 2;
	
	$header_type1[1]['ROWSPAN'] = 2;
	
	$header_type1[2]['ROWSPAN'] = 2;
	
	$header_type2[3]['TEXT'] = "Angka";
	$header_type2[4]['TEXT'] = "Huruf";
	$header_type2[5]['TEXT'] = "Predikat";
	$header_type2[6]['TEXT'] = "Deskripsi Kemajuan Belajar";
	
	$aHeaderArray = array(
		$header_type1,
		$header_type2
	);	

	//set the Table Header
	$pdf->tbSetHeaderType($aHeaderArray, true);
	
	//Draw the Header
	$pdf->tbDrawHeader();

	//Table Data Settings
	$data_type = Array();//reset the array
	for ($i=0; $i<$columns; $i++) $data_type[$i] = $table_default_data_type;

	$pdf->tbSetDataType($data_type);
	
	$data = Array();
	$data[0]['TEXT'] = "<judul>I</judul>";
	$data[1]['TEXT'] = "<judul>NORMATIF</judul>";
	$data[1]['T_ALIGN'] = "L";
	$pdf->tbDrawData($data);
	
	$normatif = $this->mlaporan->getMapel($dt_kueri->id_kelas,"Normatif");
	if(count($normatif) < 1)
	{
		$data = Array();
		$data[0]['TEXT'] = "";
		$data[1]['TEXT'] = "";
		$data[1]['T_ALIGN'] = "";
		$pdf->tbDrawData($data);
	}
	else
	{	
		$no = 1;
		foreach($normatif as $dt_normatif)
		{
			$data = Array();
			$data[0]['TEXT'] = $no;
			$data[1]['TEXT'] = $dt_normatif->mapel;
			$data[1]['T_ALIGN'] = "L";
			$data[2]['TEXT'] = $this->mlaporan->getKkm($dt_normatif->id_mapel,$dt_kueri->tingkat);
			$data[2]['T_ALIGN'] = "C";
			$data[3]['TEXT'] = number_format($this->getNilaiTot($dt_normatif->id_mapel,$dt_kueri->id_kelas,$dt_kueri->tingkat,$dt_kueri->nis), 2, ',',' ');
			$data[3]['T_ALIGN'] = "C";
			$data[4]['TEXT'] = $this->words->terbilang(number_format($this->getNilaiTot($dt_normatif->id_mapel,$dt_kueri->id_kelas,$dt_kueri->tingkat,$dt_kueri->nis), 2, ',',' '),$style=4,$strcomma=",");
			$data[4]['T_ALIGN'] = "L";
			$data[5]['TEXT'] = $this->predikat($this->getNilaiTot($dt_normatif->id_mapel,$dt_kueri->id_kelas,$dt_kueri->tingkat,$dt_kueri->nis));
			$data[5]['T_ALIGN'] = "C";
			$data[6]['TEXT'] = (intval($this->mlaporan->getKkm($dt_normatif->id_mapel,$dt_kueri->tingkat)) < intval($this->getNilaiTot($dt_normatif->id_mapel,$dt_kueri->id_kelas,$dt_kueri->tingkat,$dt_kueri->nis)))?"Mampu":"Belum Mampu";
			$data[6]['T_ALIGN'] = "C";
			$pdf->tbDrawData($data);
			$no++;
		}	
	}
	
	$data = Array();
	$data[0]['TEXT'] = "<judul>II</judul>";
	$data[1]['TEXT'] = "<judul>ADAPTIF</judul>";
	$data[1]['T_ALIGN'] = "L";
	$pdf->tbDrawData($data);
	
	$adaptif = $this->mlaporan->getMapel($dt_kueri->id_kelas,"Adaptif");
	if(count($adaptif) < 1)
	{
		$data = Array();
		$data[0]['TEXT'] = "";
		$data[1]['TEXT'] = "";
		$data[1]['T_ALIGN'] = "";
		$pdf->tbDrawData($data);
	}
	else
	{	
		$no = 1;
		foreach($adaptif as $dt_adaptif)
		{
			$data = Array();
			$data[0]['TEXT'] = $no;
			$data[1]['TEXT'] = $dt_adaptif->mapel;
			$data[1]['T_ALIGN'] = "L";
			$data[2]['TEXT'] = $this->mlaporan->getKkm($dt_adaptif->id_mapel,$dt_kueri->tingkat);
			$data[2]['T_ALIGN'] = "C";
			$data[3]['TEXT'] = $this->getNilaiTot($dt_adaptif->id_mapel,$dt_kueri->id_kelas,$dt_kueri->tingkat,$dt_kueri->nis);
			$data[3]['T_ALIGN'] = "C";
			$data[4]['TEXT'] = $this->words->terbilang(number_format($this->getNilaiTot($dt_adaptif->id_mapel,$dt_kueri->id_kelas,$dt_kueri->tingkat,$dt_kueri->nis), 2, ',',' '),$style=4,$strcomma=",");
			$data[4]['T_ALIGN'] = "L";
			$data[5]['TEXT'] = $this->predikat($this->getNilaiTot($dt_adaptif->id_mapel,$dt_kueri->id_kelas,$dt_kueri->tingkat,$dt_kueri->nis));
			$data[5]['T_ALIGN'] = "C";
			$data[6]['TEXT'] = (intval($this->mlaporan->getKkm($dt_adaptif->id_mapel,$dt_kueri->tingkat)) < intval($this->getNilaiTot($dt_adaptif->id_mapel,$dt_kueri->id_kelas,$dt_kueri->tingkat,$dt_kueri->nis)))?"Mampu":"Belum Mampu";
			$data[6]['T_ALIGN'] = "C";	
			$pdf->tbDrawData($data);
			$no++;
		}	
	}
	
	$data = Array();
	$data[0]['TEXT'] = "<judul>III</judul>";
	$data[1]['TEXT'] = "<judul>PRODUKTIF</judul>";
	$data[1]['T_ALIGN'] = "L";
	$pdf->tbDrawData($data);
	
	$produktif = $this->mlaporan->getMapel($dt_kueri->id_kelas,"Produktif");
	if(count($produktif) < 1)
	{
		$data = Array();
		$data[0]['TEXT'] = "";
		$data[1]['TEXT'] = "";
		$data[1]['T_ALIGN'] = "";
		$pdf->tbDrawData($data);
	}
	else
	{	
		$no = 1;
		foreach($produktif as $dt_produktif)
		{
			$data = Array();
			$data[0]['TEXT'] = $no;
			$data[1]['TEXT'] = $dt_produktif->mapel;
			$data[1]['T_ALIGN'] = "L";
			$data[2]['TEXT'] = $this->mlaporan->getKkm($dt_produktif->id_mapel,$dt_kueri->tingkat);
			$data[2]['T_ALIGN'] = "C";
			$data[3]['TEXT'] = number_format($this->getNilaiTot($dt_produktif->id_mapel,$dt_kueri->id_kelas,$dt_kueri->tingkat,$dt_kueri->nis), 2, ',', ' ');
			$data[3]['T_ALIGN'] = "C";
			$data[4]['TEXT'] = $this->words->terbilang(number_format($this->getNilaiTot($dt_produktif->id_mapel,$dt_kueri->id_kelas,$dt_kueri->tingkat,$dt_kueri->nis), 2, ',', ' '),$style=4,$strcomma=",");
			$data[4]['T_ALIGN'] = "L";
			$data[5]['TEXT'] = $this->predikat($this->getNilaiTot($dt_produktif->id_mapel,$dt_kueri->id_kelas,$dt_kueri->tingkat,$dt_kueri->nis));
			$data[5]['T_ALIGN'] = "C";
			$data[6]['TEXT'] = (intval($this->mlaporan->getKkm($dt_produktif->id_mapel,$dt_kueri->tingkat)) < intval($this->getNilaiTot($dt_produktif->id_mapel,$dt_kueri->id_kelas,$dt_kueri->tingkat,$dt_kueri->nis)))?"Mampu":"Belum Mampu";
			$data[6]['T_ALIGN'] = "C";
			$pdf->tbDrawData($data);
			$no++;
		}	
	}
	
	$jumlah1 = count($normatif);
	$jumlah2 = count($adaptif);
	$jumlah3 = count($produktif);
	$nilai = 32 - ($jumlah1+$jumlah2+$jumlah3);
	for ($j=0; $j<$nilai; $j++)
	{
		$data = Array();
		$data[0]['TEXT'] = "";
		$data[1]['TEXT'] = "";
		$data[1]['T_ALIGN'] = "";
		$pdf->tbDrawData($data);
	}
	
	$data = Array();
	$data[0]['TEXT'] = "<judul>IV</judul>";
	$data[1]['TEXT'] = "<judul>MULOK</judul>";
	$data[1]['T_ALIGN'] = "L";
	$pdf->tbDrawData($data);
	
	$mulok = $this->mlaporan->getMapel($dt_kueri->id_kelas,"Mulok");
	if(count($mulok) > 1)
	{
		$data = Array();
		$data[0]['TEXT'] = "";
		$data[1]['TEXT'] = "";
		$data[1]['T_ALIGN'] = "";
		$pdf->tbDrawData($data);
	}
	else
	{	
		$no = 1;
		foreach($mulok as $dt_mulok)
		{
			$data = Array();
			$data[0]['TEXT'] = $no;
			$data[1]['TEXT'] = $dt_mulok->mapel;
			$data[1]['T_ALIGN'] = "L";
			$data[2]['TEXT'] = $this->mlaporan->getKkm($dt_mulok->id_mapel,$dt_kueri->tingkat);
			$data[2]['T_ALIGN'] = "C";
			$data[3]['TEXT'] = number_format($this->getNilaiTot($dt_mulok->id_mapel,$dt_kueri->id_kelas,$dt_kueri->tingkat,$dt_kueri->nis), 2, ',', ' ');
			$data[3]['T_ALIGN'] = "C";
			$data[4]['TEXT'] = $this->words->terbilang(number_format($this->getNilaiTot($dt_mulok->id_mapel,$dt_kueri->id_kelas,$dt_kueri->tingkat,$dt_kueri->nis), 2, ',', ' '),$style=4,$strcomma=",");
			$data[4]['T_ALIGN'] = "L";
			$data[5]['TEXT'] = $this->predikat($this->getNilaiTot($dt_mulok->id_mapel,$dt_kueri->id_kelas,$dt_kueri->tingkat,$dt_kueri->nis));
			$data[5]['T_ALIGN'] = "C";
			$data[6]['TEXT'] = (intval($this->mlaporan->getKkm($dt_mulok->id_mapel,$dt_kueri->tingkat)) < intval($this->getNilaiTot($dt_mulok->id_mapel,$dt_kueri->id_kelas,$dt_kueri->tingkat,$dt_kueri->nis)))?"Mampu":"Belum Mampu";
			$data[6]['T_ALIGN'] = "C";
			$pdf->tbDrawData($data);
			$no++;
		}	
	}
	
	$jumlah4 = count($mulok);
	$nilais = 3 - ($jumlah4);
	for ($j=0; $j<$nilais; $j++)
	{
		$data = Array();
		$data[0]['TEXT'] = "";
		$data[1]['TEXT'] = "";
		$data[1]['T_ALIGN'] = "";
		$pdf->tbDrawData($data);
	}
	
	//output the table data to the pdf
	$pdf->tbOuputData();
	
	//draw the Table Border
	$pdf->tbDrawBorder();
	
	$pdf->SetY(230);
	$pdf->SetX(75);
	$pdf->MultiCellTag(100, 3, "<head1>Mengetahui</head1>");
	$pdf->SetY(234);
	$pdf->SetX(72);
	$pdf->MultiCellTag(100, 3, "<head1>Orang Tua / Wali</head1>");
	$pdf->SetY(234);
	$pdf->SetX(140);
	$pdf->MultiCellTag(100, 3, "<head1>Wali Kelas,</head1>");
	$pdf->SetY(260);
	$pdf->SetX(66);
	$pdf->MultiCellTag(100, 3, "<head1>...........................................</head1>");
	$pdf->SetY(260);
	$pdf->SetX(133);
	$pdf->MultiCellTag(100, 3, "<head1>".$this->mlaporan->getNamaWalis($dt_kueri->nip)."</head1>");
	$pdf->SetY(264);
	$pdf->SetX(133);
	$pdf->MultiCellTag(100, 3, "<head1>NIP. ".$this->mlaporan->getNipWalis($dt_kueri->nip)."</head1>");

	$pdf->AddPage();
	
	$pdf->SetStyle("head1","arial","",8,"0,0,0");
	$pdf->SetStyle("head2","arial","B",8,"0,0,0");
	$pdf->SetStyle("head","arial","B",12,"0,0,0");
	$pdf->SetStyle("head3","arial","B",10,"0,0,0");
	
	$pdf->SetY(10);
	$pdf->SetX(75);
	$pdf->MultiCellTag(100, 3, "<head>CATATAN AKHIR SEMESTER</head>");
	
	$pdf->SetY(16);
	$pdf->SetX(14);
	$pdf->MultiCellTag(100, 3, "<head1>Kelas</head1>");
	$pdf->SetY(16);
	$pdf->SetX(50);
	$pdf->MultiCellTag(100, 3, "<head1>: ".$dt_kueri->tingkat."</head1>");
	
	$pdf->SetY(20);
	$pdf->SetX(14);
	$pdf->MultiCellTag(100, 3, "<head1>Semester</head1>");
	$pdf->SetY(20);
	$pdf->SetX(50);
	$pdf->MultiCellTag(100, 3, "<head1>: ".$this->session->userdata('kd_sem')."</head1>");
	
	$pdf->SetY(28);
	$pdf->SetX(14);
	$pdf->MultiCellTag(100, 3, "<head2>1. Kegiatan Belajar di Dunia Usaha/Industri dan Instansi Relevan</head2>");
	
	$pdf->SetY(32);
	
	$columns = 6; //five columns
    
	$ttxt1 = "<size>Tag-Based MultiCell Table</size>\nCreated by <t1 href='mailto:andy@interpid.eu'>Bintintan Andrei, Interpid Team</t1>";
	$ttxt2 = "<p>The cells in the table are fully functional <t1>Tag Based Multicells components</t1>. The description and usage of these components can be found <t1>here</t1>.</p>";
	
	//Initialize the table class
	$pdf->tbInitialize($columns, true, true);
	
	//set the Table Type
	$pdf->tbSetTableType($table_default_table_type);
	
	//Table Header
	for($i=0; $i<$columns; $i++) $header_type[$i] = $table_default_header_type;
	
	for($i=0; $i<$columns; $i++) {
		$header_type1[$i] = $table_default_header_type;
	}

	$header_type1[0]['WIDTH'] = 10;
	$header_type1[1]['WIDTH'] = 35;
	$header_type1[2]['WIDTH'] = 40;
	$header_type1[3]['WIDTH'] = 60;
	$header_type1[4]['WIDTH'] = 15;
	$header_type1[5]['WIDTH'] = 20;
	
	$header_type1[0]['TEXT'] = "NO";
	$header_type1[1]['TEXT'] = "Nama DU/DI atau Instansi Relevan";
	$header_type1[2]['TEXT'] = "Alamat";
	$header_type1[3]['TEXT'] = "Lama dan Waktu Pelaksanaan";
	$header_type1[4]['TEXT'] = "Nilai";
	$header_type1[5]['TEXT'] = "Predikat";

	$aHeaderArray = array(
		$header_type1
	);	

	//set the Table Header
	$pdf->tbSetHeaderType($aHeaderArray, true);
	
	//Draw the Header
	$pdf->tbDrawHeader();

	//Table Data Settings
	$data_type = Array();//reset the array
	for ($i=0; $i<$columns; $i++) $data_type[$i] = $table_default_data_type;

	$pdf->tbSetDataType($data_type);

	$dudi = $this->mlaporan->getDudi($dt_kueri->id_kelas,$dt_kueri->nis);
	$no = 1;
	foreach($dudi as $dt_dudi)
	{
		$data = Array();
		$data[0]['TEXT'] = $no;
		$data[1]['TEXT'] = $dt_dudi->nama_industri;
		$data[1]['T_ALIGN'] = "L";
		$data[2]['TEXT'] = $dt_dudi->alamat_industri;
		$data[2]['T_ALIGN'] = "L";
		$data[3]['TEXT'] = $dt_dudi->lama_industri;
		$data[3]['T_ALIGN'] = "L";
		$data[4]['TEXT'] = $dt_dudi->industri;
		$data[4]['T_ALIGN'] = "L";
		$data[5]['TEXT'] = "Diisikan dengan predikat";
		$data[5]['T_ALIGN'] = "L";

		$pdf->tbDrawData($data);
		$no++;
	}

	for ($j=0; $j<3-count($dudi); $j++)
	{
		$data = Array();
		$data[0]['TEXT'] = $j + 1;
		$data[1]['TEXT'] = "";
		$data[1]['T_ALIGN'] = "L";
		$data[2]['TEXT'] = "";
		$data[2]['T_ALIGN'] = "L";

		$pdf->tbDrawData($data);
	}
	
	//output the table data to the pdf
	$pdf->tbOuputData();
	
	//draw the Table Border
	$pdf->tbDrawBorder();
	
	$pdf->SetY(58);
	$pdf->SetX(14);
	$pdf->MultiCellTag(100, 3, "<head2>2. Pengembangan Diri dan Kepribadian</head2>");
	
	$pdf->SetY(62);
	
	$columns = 6; //five columns
    
	$ttxt1 = "<size>Tag-Based MultiCell Table</size>\nCreated by <t1 href='mailto:andy@interpid.eu'>Bintintan Andrei, Interpid Team</t1>";
	$ttxt2 = "<p>The cells in the table are fully functional <t1>Tag Based Multicells components</t1>. The description and usage of these components can be found <t1>here</t1>.</p>";
	
	//Initialize the table class
	$pdf->tbInitialize($columns, true, true);
	
	//set the Table Type
	$pdf->tbSetTableType($table_default_table_type);
	
	//Table Header
	for($i=0; $i<$columns; $i++) $header_type[$i] = $table_default_header_type;
	
	for($i=0; $i<$columns; $i++) {
		$header_type1[$i] = $table_default_header_type;
	}

	$header_type1[0]['WIDTH'] = 50;
	$header_type1[1]['WIDTH'] = 70;
	$header_type1[2]['WIDTH'] = 15;
	$header_type1[3]['WIDTH'] = 15;
	$header_type1[4]['WIDTH'] = 15;
	$header_type1[5]['WIDTH'] = 15;
	
	$header_type1[0]['TEXT'] = "Kegiatan";
	$header_type1[1]['TEXT'] = "Jenis";
	$header_type1[2]['TEXT'] = "Nilai";
		
	$header_type1[3]['TEXT'] = "Keterangan";
	$header_type1[3]['COLSPAN'] = 3;
	$header_type1[3]['T_ALIGN'] = 'C';
	
	$aHeaderArray = array(
		$header_type1
	);	

	//set the Table Header
	$pdf->tbSetHeaderType($aHeaderArray, true);
	
	//Draw the Header
	$pdf->tbDrawHeader();

	//Table Data Settings
	$data_type = Array();//reset the array
	for ($i=0; $i<$columns; $i++) $data_type[$i] = $table_default_data_type;

	$pdf->tbSetDataType($data_type);

	$kembang = $this->mlaporan->getDiri($dt_kueri->id_kelas,$dt_kueri->nis);
	foreach($kembang as $st_kembang)
	{
		$data = Array();		
		$data[0]['TEXT'] = "Pengembangan Diri";
		$data[0]['ROWSPAN'] = 5;
		$data[1]['TEXT'] = $no." ".$st_kembang->pengembangan_diri;
		$data[1]['T_ALIGN'] = "L";
		$data[2]['TEXT'] = $st_kembang->nilai_diri;
		$data[2]['T_ALIGN'] = "L";
		$data[3]['TEXT'] = "";
		$data[3]['T_ALIGN'] = "L";
		$data[4]['TEXT'] = "";
		$data[4]['T_ALIGN'] = "L";
		$data[5]['TEXT'] = "";
		$data[5]['T_ALIGN'] = "L";
		$pdf->tbDrawData($data);
		$no++;
	}
	for ($j=0; $j<5-count($kembang); $j++)
	{
		$data = Array();
		$data[0]['TEXT'] = "Pengembangan Diri";
		$data[0]['ROWSPAN'] = 5;		
		$data[1]['TEXT'] = "";
		$data[1]['T_ALIGN'] = "L";
		$data[2]['TEXT'] = "";
		$data[2]['T_ALIGN'] = "L";
		$pdf->tbDrawData($data);
	}
	$data = Array();
	$data[0]['TEXT'] = "Kepribadian";
	$data[1]['COLSPAN'] = 5;
	$data[1]['T_ALIGN'] = "C";
	$data[1]['TEXT'] = "Baik / Cukup / Kurang";
	$pdf->tbDrawData($data);
	
	//output the table data to the pdf
	$pdf->tbOuputData();
	
	//draw the Table Border
	$pdf->tbDrawBorder();
	
	$pdf->SetY(96);
	$pdf->SetX(14);
	$pdf->MultiCellTag(100, 3, "<head2>3. Ketidakhadiran</head2>");
	
	$pdf->SetY(100);
	
	$columns = 6; //five columns
    
	$ttxt1 = "<size>Tag-Based MultiCell Table</size>\nCreated by <t1 href='mailto:andy@interpid.eu'>Bintintan Andrei, Interpid Team</t1>";
	$ttxt2 = "<p>The cells in the table are fully functional <t1>Tag Based Multicells components</t1>. The description and usage of these components can be found <t1>here</t1>.</p>";
	
	//Initialize the table class
	$pdf->tbInitialize($columns, true, true);
	
	//set the Table Type
	$pdf->tbSetTableType($table_default_table_type);
	
	//Table Header
	for($i=0; $i<$columns; $i++) $header_type[$i] = $table_default_header_type;
	
	for($i=0; $i<$columns; $i++) {
		$header_type1[$i] = $table_default_header_type;
	}

	$header_type1[0]['WIDTH'] = 70;
	$header_type1[1]['WIDTH'] = 50;
	$header_type1[2]['WIDTH'] = 15;
	$header_type1[3]['WIDTH'] = 15;
	$header_type1[4]['WIDTH'] = 15;
	$header_type1[5]['WIDTH'] = 15;
	
	$header_type1[0]['TEXT'] = "Kegiatan";
	$header_type1[1]['TEXT'] = "Jenis";
	$header_type1[2]['TEXT'] = "Nilai";
		
	$header_type1[3]['TEXT'] = "Keterangan";
	$header_type1[3]['COLSPAN'] = 3;
	$header_type1[3]['T_ALIGN'] = 'C';
	
	$aHeaderArray = array(
		$header_type1
	);	

	//set the Table Header
	$pdf->tbSetHeaderType($aHeaderArray, true);

	//Table Data Settings
	$data_type = Array();//reset the array
	for ($i=0; $i<$columns; $i++) $data_type[$i] = $table_default_data_type;

	$pdf->tbSetDataType($data_type);

	for ($j=0; $j<3; $j++)
	{
		$data = Array();		
		$data[0]['TEXT'] = "Ketidakhadiran";
		$data[0]['ROWSPAN'] = 5;
		$data[0]['T_ALIGN'] = "L";
		if($j == 0)
		{
			$data[1]['TEXT'] = "1. Sakit                                   :       Hari";
		}
		elseif($j == 1)
		{
			$data[1]['TEXT'] = "2. Izin                                     :       Hari";
		}
		else
		{
			$data[1]['TEXT'] = "3. Tanpa Keterangan             :       Hari";
		}		
		$data[1]['T_ALIGN'] = "L";
		$data[1]['COLSPAN'] = 5;
	
		$pdf->tbDrawData($data);
	}	
	
	//output the table data to the pdf
	$pdf->tbOuputData();
	
	//draw the Table Border
	$pdf->tbDrawBorder();
	
	$pdf->SetY(118);
	$pdf->SetX(14);
	$pdf->MultiCellTag(100, 3, "<head2>4. Catatan untuk perhatian orang tua/wali</head2>");
	
	$pdf->SetY(122);
	
	$columns = 6; //five columns
    
	$ttxt1 = "<size>Tag-Based MultiCell Table</size>\nCreated by <t1 href='mailto:andy@interpid.eu'>Bintintan Andrei, Interpid Team</t1>";
	$ttxt2 = "<p>The cells in the table are fully functional <t1>Tag Based Multicells components</t1>. The description and usage of these components can be found <t1>here</t1>.</p>";
	
	//Initialize the table class
	$pdf->tbInitialize($columns, true, true);
	
	//set the Table Type
	$pdf->tbSetTableType($table_default_table_type);
	
	//Table Header
	for($i=0; $i<$columns; $i++) $header_type[$i] = $table_default_header_type;
	
	for($i=0; $i<$columns; $i++) {
		$header_type1[$i] = $table_default_header_type;
	}

	$header_type1[0]['WIDTH'] = 70;
	$header_type1[1]['WIDTH'] = 50;
	$header_type1[2]['WIDTH'] = 15;
	$header_type1[3]['WIDTH'] = 15;
	$header_type1[4]['WIDTH'] = 15;
	$header_type1[5]['WIDTH'] = 15;
	
	$header_type1[0]['TEXT'] = "Kegiatan";
	$header_type1[1]['TEXT'] = "Jenis";
	$header_type1[2]['TEXT'] = "Nilai";
		
	$header_type1[3]['TEXT'] = "Keterangan";
	$header_type1[3]['COLSPAN'] = 3;
	$header_type1[3]['T_ALIGN'] = 'C';
	
	$aHeaderArray = array(
		$header_type1
	);	

	//set the Table Header
	$pdf->tbSetHeaderType($aHeaderArray, true);

	//Table Data Settings
	$data_type = Array();//reset the array
	for ($i=0; $i<$columns; $i++) $data_type[$i] = $table_default_data_type;

	$pdf->tbSetDataType($data_type);

	for ($j=0; $j<3; $j++)
	{
		$data = Array();		
		$data[0]['TEXT'] = str_replace("%20"," ",$this->mlaporan->getCatat($dt_kueri->id_kelas,$dt_kueri->nis))."\n\n\n\n";
		$data[0]['ROWSPAN'] = 3;
		$data[0]['COLSPAN'] = 6;
		$data[0]['T_ALIGN'] = 'L';
	
		$pdf->tbDrawData($data);
	}	
	
	//output the table data to the pdf
	$pdf->tbOuputData();
	
	//draw the Table Border
	$pdf->tbDrawBorder();

	$pdf->SetY(143);
	$pdf->SetX(14);
	$pdf->MultiCellTag(100, 3, "<head2>5. Pernyataan</head2>");
	
	$pdf->SetY(147);
	
	$columns = 6; //five columns
    
	$ttxt1 = "<size>Tag-Based MultiCell Table</size>\nCreated by <t1 href='mailto:andy@interpid.eu'>Bintintan Andrei, Interpid Team</t1>";
	$ttxt2 = "<p>The cells in the table are fully functional <t1>Tag Based Multicells components</t1>. The description and usage of these components can be found <t1>here</t1>.</p>";
	
	//Initialize the table class
	$pdf->tbInitialize($columns, true, true);
	
	//set the Table Type
	$pdf->tbSetTableType($table_default_table_type);
	
	//Table Header
	for($i=0; $i<$columns; $i++) $header_type[$i] = $table_default_header_type;
	
	for($i=0; $i<$columns; $i++) {
		$header_type1[$i] = $table_default_header_type;
	}

	$header_type1[0]['WIDTH'] = 70;
	$header_type1[1]['WIDTH'] = 50;
	$header_type1[2]['WIDTH'] = 15;
	$header_type1[3]['WIDTH'] = 15;
	$header_type1[4]['WIDTH'] = 15;
	$header_type1[5]['WIDTH'] = 15;
	
	$header_type1[0]['TEXT'] = "Kegiatan";
	$header_type1[1]['TEXT'] = "Jenis";
	$header_type1[2]['TEXT'] = "Nilai";
		
	$header_type1[3]['TEXT'] = "Keterangan";
	$header_type1[3]['COLSPAN'] = 3;
	$header_type1[3]['T_ALIGN'] = 'C';
	
	$aHeaderArray = array(
		$header_type1
	);	

	//set the Table Header
	$pdf->tbSetHeaderType($aHeaderArray, true);

	//Table Data Settings
	$data_type = Array();//reset the array
	for ($i=0; $i<$columns; $i++) $data_type[$i] = $table_default_data_type;

	$pdf->tbSetDataType($data_type);

	for ($j=0; $j<3; $j++)
	{
		$data = Array();		
		$data[0]['TEXT'] = "\n\n\n\n";
		$data[0]['ROWSPAN'] = 3;
		$data[0]['COLSPAN'] = 6;
		$data[0]['HEIGHT'] = 500;
		$data[0]['T_ALIGN'] = 'L';
	
		$pdf->tbDrawData($data);
	}	
	
	//output the table data to the pdf
	$pdf->tbOuputData();
	
	//draw the Table Border
	$pdf->tbDrawBorder();	
	
	if($this->session->userdata('kd_sem') == 1)
	{
		$pdf->SetY(180);
		$pdf->SetX(35);
		$pdf->MultiCellTag(100, 3, "<head1>Mengetahui</head1>");
		$pdf->SetY(180);
		$pdf->SetX(133);
		$pdf->MultiCellTag(100, 3, "<head1>Rembang, ".ganti_tanggal($thn."-".$bln."-".$tgl)."</head1>");
		$pdf->SetY(184);
		$pdf->SetX(32);
		$pdf->MultiCellTag(100, 3, "<head1>Orang Tua / Wali</head1>");
		$pdf->SetY(184);
		$pdf->SetX(140);
		$pdf->MultiCellTag(100, 3, "<head1>Wali Kelas,</head1>");
		$pdf->SetY(210);
		$pdf->SetX(26);
		$pdf->MultiCellTag(100, 3, "<head1>...........................................</head1>");
		$pdf->SetY(210);
		$pdf->SetX(133);
		$pdf->MultiCellTag(100, 3, "<head1>".$this->mlaporan->getNamaWalis($dt_kueri->nip)."</head1>");
		$pdf->SetY(214);
		$pdf->SetX(133);
		$pdf->MultiCellTag(100, 3, "<head1>NIP. ".$this->mlaporan->getNipWalis($dt_kueri->nip)."</head1>");
		$pdf->SetY(218);
		$pdf->SetX(85);
		$pdf->MultiCellTag(100, 3, "<head1>Kepala Sekolah,</head1>");
		$pdf->SetY(244);
		$pdf->SetX(79);
		$pdf->MultiCellTag(100, 3, "<head1>Drs. Singgih Darjanto</head1>");
		$pdf->SetY(248);
		$pdf->SetX(79);
		$pdf->MultiCellTag(100, 3, "<head1>NIP. 19570807.198003.1.009</head1>");
	}
	else
	{
		$pdf->SetY(180);
		$pdf->SetX(15);
		$pdf->MultiCellTag(100, 3, "<head1>Diberikan di</head1>");
		$pdf->SetY(180);
		$pdf->SetX(50);
		$pdf->MultiCellTag(100, 3, "<head1>: Rembang</head1>");
		$pdf->SetY(180);
		$pdf->SetX(110);
		$pdf->MultiCellTag(100, 3, "<head1>Keputusan</head1>");
		$pdf->SetY(180);
		$pdf->SetX(140);
		$pdf->MultiCellTag(100, 3, "<head1>:</head1>");
		$pdf->SetY(185);
		$pdf->SetX(15);
		$pdf->MultiCellTag(100, 3, "<head1>Tanggal</head1>");
		$pdf->SetY(185);
		$pdf->SetX(50);
		$pdf->MultiCellTag(100, 3, "<head1>: ".ganti_tanggal($thn."-".$bln."-".$tgl)."</head1>");
		$pdf->SetY(185);
		$pdf->SetX(110);
		$pdf->MultiCellTag(100, 3, "<head1>Dengan memperhatikan hasil yang dicapai</head1>");
		$pdf->SetY(190);
		$pdf->SetX(45);
		$pdf->MultiCellTag(100, 3, "<head1>Wali Kelas</head1>");
		$pdf->SetY(190);
		$pdf->SetX(110);
		$pdf->MultiCellTag(100, 3, "<head1>Pada Semester .............. dan ..............</head1>");
		$pdf->SetY(195);
		$pdf->SetX(110);
		$pdf->MultiCellTag(100, 3, "<head1>Maka Ditetapkan</head1>");
		$pdf->SetY(195);
		$pdf->SetX(140);
		$pdf->MultiCellTag(100, 3, "<head1>:</head1>");
		$pdf->SetY(200);
		$pdf->SetX(40);
		$pdf->MultiCellTag(100, 3, "<head1>".$this->mlaporan->getNamaWalis($dt_kueri->nip)."</head1>");
		$pdf->SetY(203);
		$pdf->SetX(40);
		$pdf->MultiCellTag(100, 3, "<head1>NIP. ".$this->mlaporan->getNipWalis($dt_kueri->nip)."</head1>");
		$pdf->SetY(201);
		$pdf->SetX(110);
		$pdf->MultiCellTag(100, 3, ($this->mlaporan->getStateNaik($dt_kueri->id_kelas,$dt_kueri->nis))?"<head3>Dapat Melanjutkan</head3>":"<head3>Tidak Dapat Melanjutkan<head3>");
		$pdf->SetY(207);
		$pdf->SetX(110);
		$pdf->MultiCellTag(100, 3, "<head1>Ke Tingkat/Tahun ke</head1>");
		$pdf->SetY(207);
		$pdf->SetX(140);
		$pdf->MultiCellTag(100, 3, "<head1>:</head1>");
		$pdf->SetY(212);
		$pdf->SetX(110);
		$pdf->MultiCellTag(100, 3, "<head1>Program Keahlian</head1>");
		$pdf->SetY(212);
		$pdf->SetX(140);
		$pdf->MultiCellTag(100, 3, "<head1>: ".$dt_kueri->program_keahlian."</head1>");
		$pdf->SetY(210);
		$pdf->SetX(44);
		$pdf->MultiCellTag(100, 3, "<head1>Mengetahui,</head1>");
		$pdf->SetY(213);
		$pdf->SetX(41);
		$pdf->MultiCellTag(100, 3, "<head1>Orang Tua/Wali</head1>");		
		$pdf->SetY(223);
		$pdf->SetX(39);
		$pdf->MultiCellTag(100, 3, "<head1>..................................</head1>");
		$pdf->SetY(217);
		$pdf->SetX(135);
		$pdf->MultiCellTag(100, 3, "<head1>Kepala Sekolah</head1>");
		$pdf->SetY(230);
		$pdf->SetX(132);
		$pdf->MultiCellTag(100, 3, "<head1>Drs. Singgih Darjanto</head1>");
		$pdf->SetY(233);
		$pdf->SetX(132);
		$pdf->MultiCellTag(100, 3, "<head1>NIP. 19570807.198003.1.009</head1>");
	}
		
		}
		$pdf->Output();
	}
	
	function getNilaiTot($mapel,$klas,$tingkat,$nis)
	{
		$mapel = $this->mlaporan->getMapelAll($mapel,$klas);
		$akhir = '';
		if(count($mapel) > 0)
		{
			foreach($mapel as $dt_mapel)
			{
				if($tingkat == "X")
				{
					$tgkt = $dt_mapel->rumus1;
				}
				elseif($tingkat == "XI")
				{
					$tgkt = $dt_mapel->rumus2;
				}
				else
				{
					$tgkt = $dt_mapel->rumus3;
				}
				$kueri_jums = $this->mlaporan->jumNilai($dt_mapel->id_guru_mapel,$klas,$nis,4);
				$tots = count($kueri_jums);
				if($tots > 0)
				{
					$kueri_jum = $this->mlaporan->jumNilai($dt_mapel->id_guru_mapel,$klas,$nis,4);
					foreach($kueri_jum as $dt_jum)
					{
						$nilai = $dt_jum->nilai;
					}					
					$akhir = $nilai;
				}
				else
				{
					$pecah = explode("-",$tgkt);
					$no = 1;
					$total = '';
					foreach($pecah as $dt_pecah)
					{
						$kueri_jum = $this->mlaporan->jumNilai($dt_mapel->id_guru_mapel,$klas,$nis,$no);
						$nilai = '';
						$nilai_sem = '';
						$tot = '';
						$tot = count($kueri_jum);
						foreach($kueri_jum as $dt_jum)
						{
							$nilai += $dt_jum->nilai;
						}
						if($tot > 0)
						{
							$nilai_sem = $nilai/$tot;
							$total += ($dt_pecah*$nilai_sem)/100;
						}
						else
						{
							$total += ($dt_pecah*$nilai)/100;
						}
						$no++;	
					}
					$akhir += ($dt_mapel->persen*$total)/100;
				}
			}	
			return $akhir;
		}
		else
		{
			return 0;		
		}
	}
	
	function predikat($nilai)
	{
		if($nilai < 70)
		{
			$data = "Kurang";		
		}
		elseif($nilai >= 70 && $nilai <= 80)
		{
			$data = "Baik";
		}
		else
		{
			$data = "Sangat Baik";		
		}
		return $data;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */