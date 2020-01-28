<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

include ("fpdf151/pdf.php");
include ("libs/db_sql.php");
include ("libs/db_utils.php");

$oGet = db_utils::postMemory($_GET);

$sWhere           = "";
$sAnd             = "";
$sOrderby         = "";
$rsDadosAverbacao = "";

if (! empty($oGet->z01_numcgm)) {
  $sWhere .= " $sAnd j79_numcgm = {$oGet->z01_numcgm}";
  $sAnd = 'and';
}

if (! empty($oGet->j01_matric)) {
  $sWhere .= " $sAnd j75_matric = {$oGet->j01_matric}";
  $sAnd = 'and';
}

if (! empty($oGet->j75_codigo)) {
  $sWhere .= " $sAnd j75_codigo = {$oGet->j75_codigo}";
  $sAnd = 'and';
}

if (! empty($oGet->j14_codigo)) {
  $sWhere .= " $sAnd j14_codigo = {$oGet->j14_codigo}";
  $sAnd = 'and';
}

if (! empty($oGet->j93_codigo)) {
  $sWhere .= " $sAnd j93_codigo = {$oGet->j93_codigo}";
  $sAnd = 'and';
}

if (($dataini != "--") && ($datafim != "--")) {
  
  $sWhere .= " $sAnd j75_data  between '$dataini' and '$datafim'  ";
  $sAnd = 'and';
  
  $dataini  = db_formatar($dataini, "d");
  $datafim = db_formatar($datafim, "d");  
  
}

if ($oGet->processados == "S") {
  $sProcessados = " Sim ";
  $sWhere .= " $sAnd j75_situacao = 2";
  $sAnd = 'and';
  
} else if ($oGet->processados == "N") {
  $sProcessados = " Não ";
  $sWhere .= " $sAnd j75_situacao = 1";
  $sAnd = 'and';
}

if ($oGet->ordem == "M") {
  $ordem = " Matricula ";
  $sOrderby .= " order by j75_matric ";
  
} else if ($oGet->ordem == "A") {
  $ordem = " Averbação ";
  $sOrderby .= " order by j75_codigo ";
  
} else if ($oGet->ordem == "R") {
  $ordem = " Rua ";
  $sOrderby .= " order by j14_nome ";
    
} else if ($oGet->ordem == "T") {
  $ordem = " Tipo de Averbação ";
  $sOrderby .= " order by j93_descr ";
    
} else if ($oGet->ordem == "D") {
  $ordem = " Data ";
  $sOrderby .= " order by j75_data ";
    
}

if (! empty($sWhere)&&(! empty($sOrderby))) {
  $sWhere = " where {$sWhere} {$sOrderby}";
}

$head1 = "Relatório de Averbações";
$head3 = "Processados:    $sProcessados";
$head5 = "Data da Averbação:    $dataini  à  $datafim";
$head7 = "Ordem:    $ordem";

$sSql = " select averbacao.j75_matric,      ";
$sSql .= "        ruas.j14_nome,            ";
$sSql .= "        case                                                              "; 
$sSql .= "          when iptuconstr.j39_numero is not null then iptuconstr.j39_numero ";
$sSql .= "          else testadanumero.j15_numero  ";
$sSql .= "        end as j15_numero,               ";
$sSql .= "        case                                                              ";
$sSql .= "          when iptuconstr.j39_compl is not null then iptuconstr.j39_compl ";
$sSql .= "          else testadanumero.j15_compl  ";
$sSql .= "        end as j15_compl,               ";
$sSql .= "        averbacao.j75_codigo,     ";
$sSql .= "        averbacao.j75_data,       ";
$sSql .= "        averbacao.j75_dttipo,     ";
$sSql .= "        averbacao.j75_situacao,   ";
$sSql .= "        averbatipo.j93_descr,     ";
$sSql .= "        averbacao.j75_obs,        ";
$sSql .= "        averbacgm.j76_codigo,     ";
$sSql .= "        transmitente.z01_numcgm as cgmtransmitente,  ";
$sSql .= "        adquirente.z01_numcgm   as cgmadquirente,    ";
$sSql .= "        transmitente.z01_nome as nometransmitente,   ";
$sSql .= "        adquirente.z01_nome as nomeadquirente,       ";
$sSql .= "        averbacgmold.j79_codigo   ";
$sSql .= "   from averbacao                 ";
$sSql .= "        inner join iptubase         on averbacao.j75_matric       = iptubase.j01_matric ";
$sSql .= "        left  join iptuconstr       on iptuconstr.j39_matric      = iptubase.j01_matric ";
$sSql .= "                                   and iptuconstr.j39_idprinc is true                   ";
$sSql .= "        inner join testpri          on iptubase.j01_idbql         = testpri.j49_idbql ";
$sSql .= "        inner join testada          on testada.j36_idbql          = testpri.j49_idbql ";
$sSql .= "                                   and testada.j36_face           = testpri.j49_face ";
$sSql .= "        left  join testadanumero    on testada.j36_idbql          = testadanumero.j15_idbql ";
$sSql .= "        left  join ruas             on testada.j36_codigo         = ruas.j14_codigo ";
$sSql .= "        inner join averbacgm        on j75_codigo                 = j76_averbacao ";
$sSql .= "        left  join averbacgmold     on j75_codigo                 = j79_averbacao ";
$sSql .= "        left  join averbatipo       on j75_tipo                   = j93_codigo ";
$sSql .= "        inner join cgm adquirente   on adquirente.z01_numcgm      = j76_numcgm ";
$sSql .= "        left  join cgm transmitente on transmitente.z01_numcgm    = j79_numcgm ";
$sSql .= " $sWhere ";

$rsDadosAverbacao = db_query($sSql);
$iNumrows = pg_num_rows($rsDadosAverbacao);

if ($iNumrows == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.");
  exit();
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->addpage("");

$alt = 4;
$lPri = true;
$iTamamanhoFonte = 8;
$texto = 7;
$troca = 0;
$preenchimento = 0;
$iTotalMatriculas = 0;

$pdf->setX(10);

$aAverbacoesProcessadas   = array();
$aAdquirentes             = array();
$aTransmitentes           = array();

$pdf->SetFont('Arial', 'B', $iTamamanhoFonte);
$pdf->Cell(30, 4, "Matrícula",          "LRBT", 0, "C", 0);
$pdf->Cell(100,4, "Nome da Rua",        "LRBT", 0, "C", 0);
$pdf->Cell(30, 4, "Número",             "LRBT", 0, "C", 0);
$pdf->Cell(30, 4, "Complemento",        "LRBT", 1, "C", 0);
$pdf->Cell(30, 4, "Cód. Averbação",     "LRBT", 0, "C", 0);
$pdf->Cell(30, 4, "Data Averbação",     "LRBT", 0, "C", 0);
$pdf->Cell(30, 4, "Data do Tipo",       "LRBT", 0, "C", 0);
$pdf->Cell(70, 4, "Tipo Averbação",     "LRBT", 0, "C", 0);
$pdf->Cell(30, 4, "Situação",           "LRBT", 1, "C", 0);
$pdf->Cell(190,4, "    Observações: ",  "LRBT", 1, "L", 0);
$pdf->Cell(95, 4, "Transmitente(s)",    "LRBT", 0, "C", 0);
$pdf->Cell(95, 4, "Adquirente(s)",      "LRBT", 1, "C", 0);
$pdf->Cell(190,4, "",                   "B", 1, "C", 0);

for($i = 0; $i < $iNumrows; $i ++) {
  
  $oDadosAverbacao = db_utils::fieldsMemory($rsDadosAverbacao, $i);

  imprimeCabecalho($pdf, $iTamamanhoFonte);
	
  if ($oDadosAverbacao->j75_situacao == 2) {
    $vProcessados = " Processado ";
  } else if ($oDadosAverbacao->j75_situacao == 1) {
    $vProcessados = " Não Processado ";
  }
  
  $pdf->setfillcolor(235);
  $pdf->setX(10);
  $pdf->SetFont('Arial', '', $texto);
  
  if (!in_array($oDadosAverbacao->j75_codigo,$aAverbacoesProcessadas)) {
	
	  if ($preenchimento == 0) {
	    $preenchimento = 1;
	  } else {
	    $preenchimento = 0;
	  }  
	
	  $pdf->Cell(30, 4, "{$oDadosAverbacao->j75_matric}",                      0, 0, "C", $preenchimento);
	  $pdf->Cell(100,4, "{$oDadosAverbacao->j14_nome}",                        0, 0, "C", $preenchimento);
	  $pdf->Cell(30, 4, "{$oDadosAverbacao->j15_numero}",                      0, 0, "C", $preenchimento);
	  $pdf->Cell(30, 4, "{$oDadosAverbacao->j15_compl}",                       0, 1, "C", $preenchimento);
	  
      $pdf->Cell(30, 4, "{$oDadosAverbacao->j75_codigo}",                      0, 0, "C", $preenchimento);
      $pdf->Cell(30, 4, db_formatar($oDadosAverbacao->j75_data,"d"),           0, 0, "C", $preenchimento);
      $pdf->Cell(30, 4, db_formatar($oDadosAverbacao->j75_dttipo,"d"),         0, 0, "C", $preenchimento);
      $pdf->Cell(70, 4, "{$oDadosAverbacao->j93_descr}",                       0, 0, "C", $preenchimento);
      $pdf->Cell(30, 4, "$vProcessados",                                       0, 1, "C", $preenchimento);
    
	  $pdf->MultiCell(0,4, "{$oDadosAverbacao->j75_obs}",                         0, "L", $preenchimento);
	  
	  $iTotalMatriculas++;
	  
  }
  
 
  if (!in_array($oDadosAverbacao->cgmtransmitente,$aTransmitentes) || !in_array($oDadosAverbacao->j75_codigo,$aAverbacoesProcessadas)) {
    $pdf->Cell(95, 4, "{$oDadosAverbacao->nometransmitente}", 0, 0, "C", $preenchimento);
  }else{
    $pdf->Cell(95, 4, "", 0, 0, "C", $preenchimento);
  }
  
  if (!in_array($oDadosAverbacao->cgmadquirente,$aAdquirentes) || !in_array($oDadosAverbacao->j75_codigo,$aAverbacoesProcessadas)) {
    $pdf->Cell(95, 4, "{$oDadosAverbacao->nomeadquirente}", 0, 1, "C", $preenchimento);
  }else{
    $pdf->Cell(95, 4, "", 0, 1, "C", $preenchimento);
  }  
  
  $aAverbacoesProcessadas[] = $oDadosAverbacao->j75_codigo;
  $aAdquirentes[]           = $oDadosAverbacao->cgmadquirente;
  $aTransmitentes[]         = $oDadosAverbacao->cgmtransmitente;  

}

$pdf->setX(110);
$pdf->SetFont('Arial', 'B', $iTamamanhoFonte);
$pdf->Cell(60, 4, "", 0, 1, "C", 0);
$pdf->setX(110);
$pdf->Cell(60, 4, "TOTAL DE MATRICULAS LISTADAS: ", "LRBT", 0, "C", 0);
$pdf->Cell(30, 4, $iTotalMatriculas,                "LRBT", 0, "C", 0);

$pdf->Output();

function imprimeCabecalho($pdf, $iTamamanhoFonte) {

	if ( $pdf->gety() > $pdf->h - 35  ) {
		
		$pdf->addpage("");
		
	  $pdf->SetFont('Arial', 'B', $iTamamanhoFonte);
	  
	  $pdf->Cell(30, 4, "Matrícula",          "LRBT", 0, "C", 0);
	  $pdf->Cell(100,4, "Nome da Rua",        "LRBT", 0, "C", 0);
	  $pdf->Cell(30, 4, "Número",             "LRBT", 0, "C", 0);
	  $pdf->Cell(30, 4, "Complemento",        "LRBT", 1, "C", 0);
	  $pdf->Cell(30, 4, "Cód. Averbação",     "LRBT", 0, "C", 0);
	  $pdf->Cell(30, 4, "Data Averbação",     "LRBT", 0, "C", 0);
	  $pdf->Cell(30, 4, "Data do Tipo",       "LRBT", 0, "C", 0);
	  $pdf->Cell(70, 4, "Tipo Averbação",     "LRBT", 0, "C", 0);
	  $pdf->Cell(30, 4, "Situação",           "LRBT", 1, "C", 0);
	  $pdf->Cell(190,4, "    Observações: ",  "LRBT", 1, "L", 0);
	  $pdf->Cell(95, 4, "Transmitente(s)",    "LRBT", 0, "C", 0);
	  $pdf->Cell(95, 4, "Adquirente(s)",      "LRBT", 1, "C", 0);
	  $pdf->Cell(190,4, "",                   0, 1, "C", 0);
	  $pdf->SetFont('Arial', '', $iTamamanhoFonte);
	  
	}
}
?>