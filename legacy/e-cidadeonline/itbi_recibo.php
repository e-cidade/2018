<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require("fpdf151/scpdf.php");
require("libs/db_conecta.php");
include("dbforms/db_funcoes.php");
include("classes/db_itbiavalia_classe.php");
include("classes/db_itbi_classe.php");
include("classes/db_itbimatric_classe.php");
include("classes/db_itbilogin_classe.php");
include("classes/db_itburbano_classe.php");
include("classes/db_itbirural_classe.php");
include("classes/db_itbiruralcaract_classe.php");
include("classes/db_itbinumpre_classe.php");

include("classes/db_itbinome_classe.php");
include("classes/db_itbinomecgm_classe.php");

include("classes/db_itbipropriold_classe.php");
include("classes/db_itbicgm_classe.php");
include("classes/db_itbiconstr_classe.php");
include("classes/db_numpref_classe.php");
include("classes/db_itbiconstrespecie_classe.php");
include("classes/db_itbiconstrtipo_classe.php");
include("classes/db_parreciboitbi_classe.php");
include("classes/db_recibo_classe.php");
include("classes/db_arrenumcgm_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);

$clitbiavalia        = new cl_itbiavalia;
$clitbi              = new cl_itbi;
$clitbirural         = new cl_itbirural;
$clitbiruralcaract   = new cl_itbiruralcaract;
$clitbimatric        = new cl_itbimatric;
$clitbilogin         = new cl_itbilogin;
$clitburbano         = new cl_itburbano;
$clitbinumpre        = new cl_itbinumpre;

$clitbinome          = new cl_itbinome;
$clitbinomecgm       = new cl_itbinomecgm;

$clitbicgm           = new cl_itbicgm;
$clitbiconstr        = new cl_itbiconstr;
$clitbipropriold     = new cl_itbipropriold;
$clitbiconstrespecie = new cl_itbiconstrespecie;
$clitbiconstrtipo    = new cl_itbiconstrtipo;
$clnumpref           = new cl_numpref;
$clparreciboitbi     = new cl_parreciboitbi;
$clrecibo            = new cl_recibo;
$clarrenumcgm        = new cl_arrenumcgm;

$compradoresm        = 0;
$compradoresf        = 0;
$transmitentesm      = 0;
$transmitentesf      = 0;
$outroscompradores   = "";
$outrostransmitentes = "";


$it14_valoravalterfinanc   = 0;
$it14_valoravalconstrfinanc= 0;
$it14_valoravalfinanc      = 0; 

$it04_aliquotafinanc = 0;


$p = 0;
$result = $clitbiavalia->sql_record($clitbiavalia->sql_query($itbi)); 
//db_criatabela($result);exit;
db_fieldsmemory($result,0);
$result = $clitbi->sql_record($clitbi->sql_query($itbi)); 
db_fieldsmemory($result,0);
$areaterreno = $it01_areaterreno;
$areatran = $it01_areatrans;
$result = $clitburbano->sql_record($clitburbano->sql_query($itbi));
if($clitburbano->numrows > 0){
  db_fieldsmemory($result,0);
  $tipo = "urbano";
}
$result = $clitbirural->sql_record($clitbirural->sql_query($itbi)); 
if($clitbirural->numrows > 0){
  db_fieldsmemory($result,0);
  $tipo = "rural";
}
$result = $clitbimatric->sql_record($clitbimatric->sql_query($itbi)); 
if($clitbimatric->numrows > 0){
  db_fieldsmemory($result,0);
}
$proprietarios = "";



/*************************************************************   A D Q U I R E N T E S   ****************************************************************************************/


/*===================================  COM A NOVA EXTRUTURA BUSCA OS DADOS DE TRANSMISSORES E ADQUIRENTES NA TABELA ITBINOME ===================================================*/


/* AKI PEGA SO O ADQUIRENTE PRINCIPAL E SEUS DADOS */

$rscompprinc = $clitbinome->sql_record($clitbinome->sql_query(""," it03_nome     as nomecompprinc,
																   it03_cpfcnpj  as cgccpfcomprador,
																   it03_endereco as enderecocomprador, 
																   it03_numero   as numerocomprador, 
																   it03_compl    as complcomprador, 
																   it03_munic    as municipiocomprador,
																   it03_uf       as ufcomprador,
																   it03_cep      as cepcomprador,
																   it03_sexo     as sexocomprador,
																   it03_princ    as principalcomprador,
																   it03_bairro   as bairrocomprador",
																   ""," it03_guia = $itbi 
																   and it03_tipo  = 'c' 
																   and it03_princ = 't' "));



if($clitbinome->numrows  > 0){
	db_fieldsmemory($rscompprinc,$p);
}else{
	  db_msgbox ('Adquirente principal não encontrado ! ');
	  echo "<script>window.close()</script>";
	  exit;
 
}

/*AKI PEGA OS ADQUIRENTES SECUNDARIOS*/

//                             die ($clitbinome->sql_query(""," it03_nome as nomecomp, it03_sexo as sexocomprador",""," it03_guia = $itbi and it03_tipo  = 'c' and it03_princ = 'f' "));
$result = $clitbinome->sql_record($clitbinome->sql_query(""," it03_nome as nomecomp,  it03_sexo as sexocomprador",""," it03_guia = $itbi and it03_tipo  = 'c' and it03_princ = 'f' "));

if($clitbinome->numrows  > 0){
  $traco = '';
  $proprietarios .= "\n".'ADQUIRENTES : ';
  $num = pg_numrows($result);
  for ($p = 0;$p < $num;$p++){
	  db_fieldsmemory($result,$p);
	  // acumula o numero compradores homens e mulheres para colocar na observação da guia
	  if(strtoupper($sexocomprador) == 'M'){
		  $compradoresm++;
	  }elseif(strtoupper($sexocomprador) == 'F'){
		  $compradoresf++;
	  }

	  $proprietarios .= $traco.trim($nomecomp);
	  $traco = ' - ';
  } 

  if($compradoresm == 1 && $compradoresf == 0){
	  $outroscompradores = " e outro... ";      	
  }else if($compradoresm > 0){
	  $outroscompradores = " e outros... ";      	
  }else if($compradoresf == 1 && $compradoresm == 0){
	  $outroscompradores = " e outra... ";      	
  }else if($compradoresf > 0 && $compradoresm == 0){
	  $outroscompradores = " e outras... ";      	
  }
//	die($outroscompradores);
}


$resultcons = $clitbiconstr->sql_record($clitbiconstr->sql_query("","*",""," it08_guia = $itbi")); 
//db_criatabela($resultcons);
if($clitbiconstr->numrows  > 0){
  $num = pg_numrows($resultcons);
  $areatotal = 0;
  $areatrans = 0;
  for ($p = 0;$p < $num;$p++){
    db_fieldsmemory($resultcons,$p);
    $areatrans += $it08_areatrans;
    //$areatotal += $it08_area; antes eu somava a area total para aparecer na guia, agora na linha de baixo
    //eu apenas coloco a primeira area total de uma das construcoes para aprecer na guia, conforme solicitado
    $areatotal += $it08_area;
  }
}


/*============================================================================================================================================================================*/
/********************************************************** T R A N S M I T E N T E S *****************************************************************************************/
/*============================================================================================================================================================================*/

//die($clitbinome->sql_queryguia("","it03_nome as z01_nome,it03_sexo,it03_cpfcnpj as z01_cgccpf,it03_endereco as z01_ender,it03_numero,it03_compl,it03_cxpostal,it03_bairro as z01_bairro,it03_munic as z01_munic,it03_uf as z01_uf,it03_cep as z01_cep,it03_mail,it22_itbi,it22_setor as j34_setor,it22_quadra as j34_quadra,it22_lote as j34_lote,it22_descrlograd as j14_nome,it22_numero as j39_numero,it22_compl,it06_matric,it04_codigo,it04_descr,it04_desconto,it04_obs,it04_aliquota,itbi.*,itburbano.*,itbirural.*,itbiavalia.*",""," it03_guia  = $itbi and it03_tipo  = 't' and it03_princ = 't' "));

$result1 = $clitbinome->sql_record($clitbinome->sql_queryguia("","it03_nome as z01_nome,it03_sexo,it03_cpfcnpj as z01_cgccpf,it03_endereco as z01_ender,it03_numero,it03_compl,it03_cxpostal,it03_bairro as z01_bairro,it03_munic as z01_munic,it03_uf as z01_uf,it03_cep as z01_cep,it03_mail,it22_itbi,it22_setor as j34_setor,it22_quadra as j34_quadra,it22_lote as j34_lote,it22_descrlograd as j14_nome,j13_descr,it22_numero as j39_numero,it22_compl,it06_matric,it04_codigo,it04_descr,it04_desconto,it04_obs,it04_aliquota,itbi.*,itburbano.*,itbirural.*,itbiavalia.*",""," it03_guia  = $itbi and it03_tipo  = 't' and it03_princ = 't' "));

if($clitbinome->numrows  > 0){
	//db_criatabela($result1);exit;
	db_fieldsmemory($result1,0);
}

$propri = "";

//$result = $clitbipropriold->sql_record($clitbipropriold->sql_query("",""," z01_nome as nomeoutro","it20_guia"," it20_guia = $itbi and it20_pri = 'f' and it20_numcgm not in ($z01_numcgm)")); 

//                            die($clitbinome->sql_queryguia("","it03_nome as nomeoutro, it03_guia as it20_guia,it03_sexo ",""," it03_guia = $itbi and it03_tipo = 't' and it03_princ= 'f' "));
$result = $clitbinome->sql_record($clitbinome->sql_queryguia("","it03_nome as nomeoutro, it03_guia as it20_guia,it03_sexo ",""," it03_guia = $itbi and it03_tipo = 't' and it03_princ= 'f' "));

//if($clitbipropriold->numrows  > 0){
$transmitentesm = 0;
$transmitentesf = 0;
if($clitbinome->numrows  > 0){
  $traco = '';
  $propri .= "\n".'OUTRO(S) TRANSMITENTE(S) : ';
  $num = pg_numrows($result);
	
// acumula o numero compradores homens e mulheres para colocar na observação da guia

  for ($p = 0;$p < $num;$p++){
	  db_fieldsmemory($result,$p);
	  if(strtoupper($it03_sexo)== 'M'){
		  $transmitentesm++;
	  }elseif(strtoupper($it03_sexo) == 'F'){
		  $transmitentesf++;
	  }
	  $propri .= $traco.trim($nomeoutro);
	  $traco = ' - ';
  }
//die($transmitentesm." - ".$transmitentesf);	
  if($transmitentesm == 1 && $transmitentesf == 0){
	  $outrostransmitentes = " e outro...";      	
  }else if($transmitentesm > 0){
	  $outrostransmitentes = " e outros...";      	
  }else if($transmitentesf == 1 && $transmitentesm == 0){
	  $outrostransmitentes = " e outra...";      	
  }else if($transmitentesf > 0 && $transmitentesm == 0){
	  $outrostransmitentes = " e outras...";      	
  }
//  die($outrostransmitentes);
}

/*================================================  B U S C A   O   C G M   D O   D E V E D O R   =======================================================================*/

	$result = $clparreciboitbi->sql_record($clparreciboitbi->sql_query_file()); 
	if($clparreciboitbi->numrows > 0){
	   db_fieldsmemory($result,0);
	   $cgmdevedor = $it17_numcgm; 
	}
	$rscgmdevedor = $clitbinomecgm->sql_record($clitbinomecgm->sql_query(null," it21_numcgm ",null," itbinome.it03_princ = 't' and itbinome.it03_tipo = 'c' and itbinome.it03_guia  = $itbi"));
	if($clitbinomecgm->numrows > 0){
     db_fieldsmemory($rscgmdevedor,0);
		 $cgmdevedor = $it21_numcgm;
	}else{
      $rscgmdevedor = $clitbinomecgm->sql_record($clitbinomecgm->sql_query(null," it21_numcgm ",null," itbinome.it03_princ = 'f' and itbinome.it03_tipo = 'c' and itbinome.it03_guia  = $itbi"));
      if($clitbinomecgm->numrows > 0){
	       db_fieldsmemory($rscgmdevedor,0);
  	     $cgmdevedor = $it21_numcgm;
      }	  
	}
	if(!isset($cgmdevedor) || $cgmdevedor == ""){
	   echo "<script>alert('Parâmetros do recibo não configurados! \\n Contate suporte!')</script>";
	   echo "<script>window.close()</script>";
	   exit;
	}	
	
//die("cgm devedor - ".$cgmdevedor);
/**************************************************    I N S E R E   O   R E C I B O    **********************************************************************************/
   
  $sqlerro = false;
  db_inicio_transacao();
  $numpre = $clnumpref->sql_numpre();
  $resnumpre = $clitbinumpre->sql_record($clitbinumpre->sql_query($itbi));
  if($clitbinumpre->numrows > 0){
			$clitbinumpre->it15_guia   = $itbi;
			$clitbinumpre->it15_numpre = $numpre;
			$clitbinumpre->alterar($itbi,$numpre);
  }else{
			$clitbinumpre->it15_guia   = $itbi;
			$clitbinumpre->it15_numpre = $numpre;
			$clitbinumpre->incluir($itbi,$numpre);
  }

  $numpre = $clitbinumpre->it15_numpre;
	
//die("afsdjf çasjldfa".$it17_codigo);

  $clrecibo->k00_numcgm    = $cgmdevedor;
  $clrecibo->k00_dtoper    = date("Y-m-d",db_getsession("DB_datausu"));
  $clrecibo->k00_receit    = $it17_codigo;
  $clrecibo->k00_hist      = 707;
  $clrecibo->k00_valor     = $it14_valorpaga;
  $clrecibo->k00_dtvenc    = $it14_dtvenc;
  $clrecibo->k00_numpre    = $numpre;
  $clrecibo->k00_numpar    = 1;
  $clrecibo->k00_numtot    = 1;
  $clrecibo->k00_numdig    = '0';
  $clrecibo->k00_tipo      = 29;
  $clrecibo->k00_tipojm    = '0';
  $clrecibo->k00_numnov    = 0;
  $clrecibo->k00_codsubrec = '0';
  $clrecibo->incluir();
  if($clrecibo->erro_status == 0){
      $sqlerro = true;
      $erromsg = "Erro recibo ".$clrecibo->erro_msg;
  }
	if ($sqlerro == false) {
		// inclui na arrenumcgm 
		$clarrenumcgm->incluir($cgmdevedor,$numpre);
		if($clarrenumcgm->erro_status == 0){
				$sqlerro = true;
			$erromsg = "Erro arrenumcgm ".$clarrenumcgm->erro_msg;
		}
	}
	if($sqlerro == true){
		 db_msgbox($erromsg);
		 echo "<script>window.close()</script>";
	 exit;
	}
  db_fim_transacao($sqlerro);
 
/******************************************************************************************************************************************************************/

$datavencimento = $it14_dtvenc;
$valorpagamento = $it14_valorpaga;
$vlrbar = db_formatar(str_replace('.','',str_pad(number_format($valorpagamento,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');
$config = db_query("select * from db_config where codigo = ".db_getsession("DB_instit"));
db_fieldsmemory($config,0);

$numpre = db_numpre_sp($numpre,1); 

if ($formvencfebraban == 1) {
  $db_dtvenc = str_replace("-","",$datavencimento);
  $vencbar = $db_dtvenc . '000000';
} elseif ($formvencfebraban == 2) {
  $db_dtvenc = str_replace("-","",$datavencimento);
  $db_dtvenc = substr($db_dtvenc,6,2) . substr($db_dtvenc,4,2) . substr($db_dtvenc,2,2);
  $vencbar = $db_dtvenc . '00000000';
}

$inibar="8" . $segmento . "6";
$resultcod = db_query("select fc_febraban('$inibar'||'$vlrbar'||'".$numbanco."'||'".$vencbar."'||'$numpre')");
$fc_febraban = pg_result($resultcod,0,0);

if ($fc_febraban == "") {
  db_msgbox("Erro ao gerar codigo de barras (3)!");
  exit;
}
    			
$codigo_barras   = substr($fc_febraban,0,strpos($fc_febraban,','));
$linha_digitavel = substr($fc_febraban,strpos($fc_febraban,',')+1);

/*************************************************************    R E C I B O    I T B I    *************************************************************************************/

$pdf = new scpdf();
$pdf->Open();
$pdf->settopmargin(5);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFillColor(235);
$altura = 3.5;

for ( $i=1;$i < 3;$i++){
   $pdf->SetFillColor(235);
   $y = $pdf->gety() - 2;
   $pdf->Image('imagens/files/'.$logo,10,$y,14);
   $pdf->SetFont('Arial','B',10);
   $pdf->setx(30);
   $pdf->Cell(100,3,$nomeinst,0,1,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->setx(30);
   $pdf->Cell(100,3,'Imposto Sobre Transmissão de Bens Imóveis (ITBI)',0,0,"L",0);
   $pdf->SetFont('Arial','B',12);
   $pdf->cell(100,3,'Vencimento : '.db_formatar($datavencimento,'d'),0,1,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->setx(30);
   $pdf->Cell(100,3,'Tipo de Transmissão : '.$it04_descr,0,0,"L",0);
   $pdf->cell(100,3,'Código de Arrecadação : '.$numpre,0,1,"L",0);
   $pdf->setx(30);
   $pdf->SetFont('Arial','B',10);
   $pdf->Cell(100,3,'Guia de Recolhimento N'.chr(176).' SMF/'.db_formatar($itbi,'s','0',5).'/'.db_getsession("DB_anousu"),0,1,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->Ln(7);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(20,$altura,'',1,0,"C",1);
   $pdf->cell(80,$altura,'Identificação do Transmitente',1,0,"C",1);
   $pdf->cell(97,$altura,'Identificação do Adquirente',1,1,"C",1);
   $pdf->cell(20,$altura,'Nome : ',1,0,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(80,$altura,$z01_nome.$outrostransmitentes,1,0,"L",0);    //nome do transmitente
   
   $pdf->cell(97,$altura,$nomecompprinc.$outroscompradores,1,1,"L",0);   //nome do comprador 
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(20,$altura,'CNPJ/CPF:',1,0,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(80,$altura,$z01_cgccpf,1,0,"L",0);                                   
   $pdf->cell(97,$altura,$cgccpfcomprador,1,1,"L",0);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(20,$altura,'Endereço : ',1,0,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(80,$altura,$z01_ender.' - '.$z01_bairro ,1,0,"L",0);
   $pdf->cell(97,$altura,$enderecocomprador.','.$numerocomprador.' / '.$complcomprador ,1,1,"L",0);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(20,$altura,'Município : ',1,0,"L",0);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(80,$altura,$z01_munic.'('.$z01_uf.') - CEP: '.$z01_cep ,1,0,"L",0);
   $pdf->cell(97,$altura,$municipiocomprador.'('.$ufcomprador.') - CEP: '.$cepcomprador . ' - BAIRRO: '.$bairrocomprador ,1,1,"L",0);
   $pdf->Ln(2);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(88,$altura,'Dados do Imóvel',1,0,"C",1);
   $pdf->cell(2,$altura,'',0,0,"C",0);
   $pdf->cell(107,$altura,'Dados da Construção(ções)',1,1,"C",1);
   $pdf->SetFont('Arial','',8);
   $y = $pdf->gety();

   $pdf->SetFont('Arial','B',8);
   $pdf->cell(35,$altura,'Matrícula da Prefeitura: ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(13,$altura,@$it06_matric,1,0,"L",0);

   $pdf->SetFont('Arial','B',8);
   $pdf->cell(30,$altura,'Número do imóvel: ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(10,$altura,@$j39_numero,1,1,"L",0);

   $pdf->SetFont('Arial','B',8);
   $pdf->cell(15,$altura,'Setor : ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(14,$altura,@$j34_setor,1,0,"L",0);
   $pdf->SetFont('Arial','B',8);

   $pdf->cell(15,$altura,'Quadra : ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(14,$altura,@$j34_quadra,1,0,"L",0);
   $pdf->SetFont('Arial','B',8);

   $pdf->cell(15,$altura,'Lote: ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
// $pdf->cell(15,$altura,@$matriz[3],1,1,"L",0);
   $pdf->cell(15,$altura,(@$matriz[3] == ""?$j34_lote:@$matriz[3]),1,1,"L",0);


   $pdf->SetFont('Arial','B',8);
   $pdf->cell(22,$altura,'Bairro: ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(66,$altura,@$j13_descr,1,1,"L",0);
	 
   $pdf->cell(22,$altura,'Logradouro: ',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(66,$altura,@$j14_tipo . " " . @$j14_nome,1,1,"L",0);
   $pdf->SetFont('Arial','B',8);

   if(isset($tipo) && $tipo == "urbano"){
     $pdf->cell(22,$altura,'Situação: ',1,0,"L",1);
     $pdf->SetFont('Arial','',8);
     $pdf->cell(66,$altura,@$it07_descr,1,1,"L",0);
     $pdf->SetFont('Arial','B',8);
     $pdf->cell(22,$altura,'Frente: ',1,0,"L",1);
     $pdf->cell(21,$altura,db_formatar($it05_frente,'f',' ' ,0,'e',3) . 'm',1,0,"R",0);
     $pdf->cell(22,$altura,'Fundos : ',1,0,"L",1);
     $pdf->cell(23,$altura,db_formatar($it05_fundos,'f',' ' ,0,'e',3).'m',1,1,"R",0);
     $pdf->cell(22,$altura,'Lado Esquerdo: ',1,0,"L",1);
     $pdf->cell(21,$altura,db_formatar($it05_esquerdo,'f',' ' ,0,'e',3).'m',1,0,"R",0);
     $pdf->cell(22,$altura,'Lado Direito: ',1,0,"L",1);
     $pdf->cell(23,$altura,db_formatar($it05_direito,'f',' ' ,0,'e',3).'m',1,1,"R",0);
   }else{
     $pdf->SetFont('Arial','B',8);
     $pdf->cell(22,$altura,'Frente: ',1,0,"L",1);
     $pdf->cell(21,$altura,db_formatar($it18_frente,'f',' ' ,0,'e',3) . 'm',1,0,"R",0);
     $pdf->cell(22,$altura,'Fundos : ',1,0,"L",1);
     $pdf->cell(23,$altura,db_formatar($it18_fundos,'f',' ' ,0,'e',3).'m',1,1,"R",0);
     $pdf->cell(22,$altura,'Profundidade: ',1,0,"L",1);
     $pdf->cell(66,$altura,db_formatar($it18_prof,'f',' ' ,0,'e',3).'m',1,0,"R",0);
    // $pdf->cell(22,$altura,'',1,0,"L",1);
    // $pdf->cell(23,$altura,"",1,1,"R",0);
   }
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(22,3.4,'',0,1,"L",0);
   $pdf->cell(22,$altura,'',1,0,"L",1);
   $pdf->cell(33,$altura,'REAL',1,0,"C",1);
   $pdf->cell(33,$altura,'TRANSMITIDA',1,1,"C",1);
   $pdf->SetFont('Arial','B',7);
   $pdf->cell(22,$altura,'Terreno',1,0,"L",1);
   $pdf->SetFont('Arial','',8);
   $pdf->cell(33,$altura,db_formatar($areaterreno+0,'f',' ',' ',' ',5).($tipo=="urbano"?'m2':'ha'),1,0,"R",0);

   $areaterrenomat = split('\.',$areatran);

   $pdf->cell(33,$altura,(count($areaterrenomat)==1?db_formatar($areatran,'f',' ',' ',' ',5).($tipo=="urbano"?'m2':'ha'):(strlen($areaterrenomat[1])>2?$areatran:db_formatar($areatran,'f',' ',' ',' ',5).($tipo=="urbano"?'m2':'ha'))),1,1,"R",0);

   $pdf->SetFont('Arial','B',7);
   $pdf->cell(22,$altura,'Construção(ções)',1,0,"L",1);
   $pdf->SetFont('Arial','',8);

   @$areaedificadamat = split('\.',@$areatotal);

   @$pdf->cell(33,@$altura,(@$areatotal == 0?'':(count(@$areaedificadamat)==1?db_formatar(@$areatotal,'f',' ',' ',' ',5).'m2':(strlen(@$areaedificadamat[1])>2?db_formatar(@$areatotal,'f',' ',' ',' ',5).'m2':db_formatar(@$areatotal,'f',' ',' ',' ',5).'m2'))),1,0,"R",0);
   
   @$pdf->cell(33,@$altura,(@$areatotal == 0?'':(count(@$areaedificadamat)==1?db_formatar(@$areatrans,'f',' ',' ',' ',5).'m2':(strlen(@$areaedificadamat[1])>2?db_formatar(@$areatrans,'f',' ',' ',' ',5).'m2':db_formatar(@$areatrans,'f',' ',' ',' ',5).'m2'))),1,1,"R",0);
   
//   $pdf->cell(33,$altura,(isset($areatotal)?db_formatar($areatotal,'f',' ',' ',' ',5):"").'m2',1,0,"R",0);
//   $pdf->cell(33,$altura,($areatrans == 0?'':db_formatar($areatrans,'f',' ',' ',' ',5).'m2'),1,1,"R",0);
//   $pdf->cell(33,$altura,(count($areaterrenomat)==1?db_formatar($areatrans,'f',' ',' ',' ',5).'m2':(strlen($areaterrenomat[1])>2?$areatrans:db_formatar($areatrans,'f',' ',' ',' ',5).($tipo=="urbano"?'m2':'ha'))),1,1,"R",0);

   if($tipo == "rural"){
     $pdf->SetFont('Arial','B',6);
     $result = $clitbiruralcaract->sql_record($clitbiruralcaract->sql_query($itbi,"","*","j31_codigo"));
     if($clitbiruralcaract->numrows > 0){
       $pula = 0;
       for($ru=0;$ru<$clitbiruralcaract->numrows;$ru++){
		 db_fieldsmemory($result,$ru);
		 if($ru == 2 || $ru == 5){
		    $pula = 1;
		 }else{
		    $pula = 0;
		 }
         $pdf->SetFont('Arial','B',6);
         $pdf->cell(23,$altura,$j31_descr,1,0,"L",1);
         $pdf->SetFont('Arial','',6);
         $pdf->cell(6.33,$altura,$it19_valor."%",1,$pula,"R",0);
       }
       $pdf->SetFont('Arial','B',7);
       $pdf->cell(23,$altura,'',1,0,"L",1);
       $pdf->SetFont('Arial','',7);
       $pdf->cell(6.33,$altura,'',1,$pula,"R",0);
     }
   }

   $pdf->SetXY(100,$y);

   $pdf->SetFont('Arial','B',7);
   $pdf->cell(24,$altura,'Descrição',1,0,"C",1);
   $pdf->cell(35,$altura,'Tipo',1,0,"C",1);
   $pdf->cell(20,$altura,'Área m2',1,0,"C",1);
   $pdf->cell(20,$altura,'Área trans m2',1,0,"C",1);
   $pdf->cell(8,$altura,'Ano',1,1,"C",1);
   $pdf->SetFont('Arial','',7);

   $y = $pdf->gety();
   for ($ii = 1;$ii <= 10 ; $ii++){
       $pdf->setx(100);
       $pdf->cell(24,$altura,'',1,0,"C");
       $pdf->cell(35,$altura,'',1,0,"C");
       $pdf->cell(20,$altura,'',1,0,"C");
       $pdf->cell(20,$altura,'',1,0,"C");
       $pdf->cell(8,$altura,'',1,1,"C");
   }
   $yy = $pdf->gety();
   $pdf->SetXY(100,$y);
   if(@pg_numrows($resultcons) > 0){
     for ($n = 0;$n < pg_numrows($resultcons) ; $n++){
	 db_fieldsmemory($resultcons,$n);
//	 echo "\n\n\n".$it08_areatrans;
	 $resultt = $clitbiconstrespecie->sql_record($clitbiconstrespecie->sql_query($it08_codigo)); 
	 db_fieldsmemory($resultt,0);
	 $it09_codigo = $j31_descr;
	 $resultt = $clitbiconstrtipo->sql_record($clitbiconstrtipo->sql_query($it08_codigo)); 
	 db_fieldsmemory($resultt,0);
	 $it10_codigo = $j31_descr;
	 $pdf->setx(100);
	 $pdf->cell(24,$altura,$it09_codigo,0,0,"L",0);
	 $pdf->cell(35,$altura,substr($it10_codigo,0,20),0,0,"L",0);
	 $pdf->cell(20,$altura,db_formatar($it08_area,'f',' ',' ',' ',5),0,0,"R",0);
	 $pdf->cell(20,$altura,db_formatar($it08_areatrans,'f',' ',' ',' ',5),0,0,"R",0);
	 $pdf->cell(8,$altura,$it08_ano,0,1,"C",0);
	 if($n == 9)
	   break;
     }
   }
   $pdf->sety($yy+2);
   $pdf->SetFont('Arial','B',8);
   $pdf->cell(170,$altura,'Observações',1,0,"L",1);
   $pdf->cell(27,$altura,'V I S T O',1,1,"C",1);
   $pdf->SetFont('Arial','',8);
   $y = $pdf->gety();
   $pdf->cell(170,$altura,'',"TLR",0,"L",0);
   $pdf->cell(27,$altura,'',"TLR",1,"L",0);
	 
   $pdf->cell(170,$altura,'',"LBR",0,"l",0);
   $pdf->cell(27,$altura,'',"LR",1,"L",0);
	 
   $pdf->cell(170,$altura,'',"LBR",0,"l",0);
   $pdf->cell(27,$altura,'',"LR",1,"L",0);
	 
   $pdf->cell(170,$altura,'',"LBR",0,"l",0);
   $pdf->cell(27,$altura,'',"BLR",1,"L",0);

//   $pdf->cell(180,$altura,'',"BLR",1,"l",0);
   $yy = $pdf->gety();
   $pdf->sety($y);   
   $pdf->multicell(170,$altura,$propri.$proprietarios.(strlen(trim($propri.$proprietarios)) > 0?"\n ":"").(strlen(trim($it01_obs)) > 0?$it01_obs:""),1,"L",0);
//   $pdf->multicell(180,$altura,$propri.$proprietarios,1,"L",0);
   $pdf->sety($yy);   
   $pdf->cell(40,$altura,'valor terreno     : ',                'LTB',0,"l",0);
   $pdf->cell(10,$altura,db_formatar($it14_valoravalter,'f'),   'RTB',0,"R",0);
   $pdf->cell(55,$altura,'valor construção(ções) : ',           'LTB',0,"l",0);
   $pdf->cell(10,$altura,db_formatar($it14_valoravalconstr,'f'),'RTB',0,"R",0);
   $pdf->cell(55,$altura,'valor avaliação : ',                  'LTB',0,"l",0);
   $pdf->cell(27,$altura,db_formatar($it14_valoraval,'f'),      'RTB',1,"R",0);

   $pdf->cell(40,$altura,'valor financ. terreno     : ',              'LTB',0,"l",0);
   $pdf->cell(10,$altura,db_formatar($it14_valoravalterfinanc,'f'),   'RTB',0,"R",0);
   $pdf->cell(55,$altura,'valor financ. construção(ções) : ',         'LTB',0,"l",0);
   $pdf->cell(10,$altura,db_formatar($it14_valoravalconstrfinanc,'f'),'RTB',0,"R",0);
   $pdf->cell(55,$altura,'valor financ. avaliação : ',                'LTB',0,"l",0);
   $pdf->cell(27,$altura,db_formatar($it14_valoravalfinanc,'f'),      'RTB',1,"R",0);

   $pdf->cell(50,$altura,'Valor Informado : '       .db_formatar($it01_valortransacao,'f'), 1,0,"L",0);
   if(isset($it04_aliquotafinanc) && $it04_aliquotafinanc != ''){
      $pdf->cell(35,$altura,'Alíq. av/fin : '.$it04_aliquota.'% / '.$it04_aliquotafinanc.'%',   1,0,"L",0);
	 }else{
      $pdf->cell(35,$altura,'Alíquota : '              .db_formatar($it04_aliquota,'f').'%',   1,0,"L",0);
		 
	 }

   $pdf->cell(35,$altura,'Desconto : '              .db_formatar($it14_desc,'f').'%',       1,0,"L",0);
   $pdf->SetFont('Arial','B',9);
   if ($it14_valorpaga == 0) {
		 $pdf->cell(77,$altura,'Valor a Pagar : I S E N T O',1,1,"L",0);
	 } else {
		 $pdf->cell(77,$altura,'Valor a Pagar : R$ '.db_formatar(($it14_valorpaga + $tx_banc),'f'),1,1,"L",0);
	 }
   $pdf->setfont('Arial','B',11); 
   $pdf->ln(3);
// $pdf->multicell(180,4,$munic.', '.date('d').' de '.db_mes(date('m')).' de '.date('Y').'.',0,"R",0);
   $pdf->multicell(180,4,$munic.', '.substr($it01_data,8,2).' de '.db_mes(substr($it01_data,5,2)).' de '.substr($it01_data,0,4).'.',0,"R",0);
   $pdf->Ln(4);
   $pdf->setfont('Arial','',11); 
   $pos = $pdf->gety();
   $pdf->setfillcolor(0,0,0); 
   if ($it14_valorpaga > 0) {
		 $pdf->text(14,$pos,$linha_digitavel);
		 $pdf->int25(10,$pos+1,$codigo_barras,15,0.341);
	 }
   $pdf->ln(30);
}   	
$pdf->Output()
?>