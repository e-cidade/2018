<?php
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

require_once ("libs/db_sql.php");
require_once ("fpdf151/pdf1.php");
require_once ("libs/db_libsys.php");
require_once ("dbagata/classes/core/AgataAPI.class");
require_once ("model/documentoTemplate.model.php");

$cldb_docparag = db_utils::getDao('db_docparag');
$clauto        = db_utils::getDao('auto');
$clautofiscal  = db_utils::getDao('autofiscal');
$clautousu     = db_utils::getDao('autousu');
$clautotipo    = db_utils::getDao('autotipo');
$clautonumpre  = db_utils::getDao('autonumpre');
$clfiscal      = db_utils::getDao('fiscal');
$oDaoParfiscal = db_utils::getDao('parfiscal');
$clrotulo      = new rotulocampo;

$clrotulo->label('y50_codauto');
$clrotulo->label('y50_nome');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

/**
 * Verifica se existe documento template setado para utilizar o agata
 */
$iCodigoAutodeInfracao = $codauto;
$sSqlParfiscal         = $oDaoParfiscal->sql_query_file(db_getsession("DB_instit"), "y32_templateautoinfracao");
$rsParfiscal           = $oDaoParfiscal->sql_record($sSqlParfiscal);
$iTemplateAutoinfracao = db_utils::fieldsMemory($rsParfiscal, 0)->y32_templateautoinfracao;
if ($iTemplateAutoinfracao != "") {

  $sDescrDoc        = date("YmdHis").db_getsession("DB_id_usuario");
  $sNomeRelatorio   = "tmp/CertidaoIsencao{$iCodigoAutodeInfracao}.pdf";
  $sCaminhoSalvoSxw = "tmp/CertidaoIsencao_{$sDescrDoc}_{$iCodigoAutodeInfracao}.sxw";

  $sAgt = "fiscal/auto_de_infracao.agt";

  $aParam = array();
  $aParam['$iCodigoAutodeInfracao'] = $iCodigoAutodeInfracao;

  db_stdClass::oo2pdf(51, $iTemplateAutoinfracao, $sAgt, $aParam, $sCaminhoSalvoSxw, $sNomeRelatorio);
  exit;
}

/**
 * Executa o padrão da rotina
 */
$pdf = new PDF1();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->Addpage();
$pdf->setfont('arial','b',8);

//--------------Retorna dados do Auto de infração---------------------------
$result_infoauto=$clauto->sql_record($clauto->sql_query_infoautos($codauto));
if ($clauto->numrows>0){

  db_fieldsmemory($result_infoauto,0);
  $data = split('-',$data);
  $dia  = $data[2];
  $mes  = $data[1];
  $ano  = $data[0];
  $mes  = db_mes($mes);
  $data = " $dia de $mes de $ano ";
  $prazo_recurso=db_formatar($prazo_recurso,'d');
}
$result_autofiscal=$clautofiscal->sql_record($clautofiscal->sql_query($codauto));
if ($clautofiscal->numrows > 0) {

	db_fieldsmemory($result_autofiscal, 0);
  $result_fiscal=$clfiscal->sql_record($clfiscal->sql_query_busca($y51_codnoti));
	if ($clfiscal->numrows > 0) {

		db_fieldsmemory($result_fiscal, 0);
		$bql = $j34_setor . "/" . $j34_quadra . "/" . $j34_lote;
		$nome = $z01_nome;
	}
}

//----------------------Traz valor do Auto----------------------------------
$sql          = $clautonumpre->sql_query_paga($codauto);
$result_valor = $clautonumpre->sql_record($sql);

if ($clautonumpre->numrows > 0){

  db_fieldsmemory($result_valor,0,true);
  $extenso = db_extenso($valor,false);
  $valor   = trim(db_formatar($valor,'f'));
}else{

  db_msgbox('Auto de Infração não Calculado');
  exit;
}
//----------------------Busca Fiscais---------------------------------------
$result_fiscal=$clautousu->sql_record($clautousu->sql_query($codauto,null,"y56_obs as obsfiscal, nome as fisc"));
$numrows_fiscal=$clautousu->numrows;
$vir="";
$fiscal="";
for($f=0;$f<$numrows_fiscal;$f++){

  db_fieldsmemory($result_fiscal,$f,true);
  $fiscal .= $vir." $fisc";
  $vir     = ",";
}

//----------------------Busca Procedências----------------------------------
$result_proc=$clautotipo->sql_record($clautotipo->sql_query(null,"y29_descr, y29_descr_obs",null,"y59_codauto=$codauto"));
$numrows_proc=$clautotipo->numrows;
$vir="";
$procedencia="";
$obsprocedenciacomquebra="";
for($p=0;$p<$numrows_proc;$p++){

  db_fieldsmemory($result_proc,$p,true);
  $procedencia.=$vir." $y29_descr";
	$obsprocedenciacomquebra.="$y29_descr_obs\n";
  $vir=",";
}

$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);
$result = $cldb_docparag->sql_record($cldb_docparag->sql_query(null,null,"db_docparag.*,db02_texto,db02_espaca,db02_alinha,db02_inicia","db04_ordem"," db_documento.db03_tipodoc = 1100"));
$numrows = $cldb_docparag->numrows;
$pdf->SetXY('10','60');
$pdf->SetFont('Arial','b',14);
$pdf->cell(0,10,"AUTO DE INFRAÇÃO E IMPOSIÇÃO DE MULTA N° $codauto",0,1,"C",0);
$pdf->cell(0,10,"",0,1,"R",0);

for($i=0; $i<$numrows; $i++){

 db_fieldsmemory($result,$i);
 $pdf->SetFont('Arial','',12);
 $pdf->SetX($db02_alinha);
 $texto=db_geratexto($db02_texto);
 $pdf->SetFont('Arial','',12);
 $pdf->MultiCell("0",4+$db02_espaca,$texto,"0","J",0,$db02_inicia+0);
 $pdf->cell(0,6,"",0,1,"R",0);
}

$pdf->cell(0,10,"",0,1,"R",0);
$pdf->SetFont('Arial','b',12);
$pdf->cell(90,4,"___________________________",0,0,"C",0);
$pdf->cell(90,4,"___________________________",0,1,"C",0);
$pdf->cell(90,4,"Fiscal",0,0,"C",0);
$pdf->cell(90,4,"Autuado",0,1,"C",0);
$pdf->cell(0,10,"",0,1,"R",0);
$pdf->cell(0,4,"Data:___/___/_____ ",0,1,"R",0);
$pdf->Output();