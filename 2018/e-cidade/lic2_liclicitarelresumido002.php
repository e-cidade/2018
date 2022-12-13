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

include ("fpdf151/pdf.php");
include ("libs/db_sql.php");
include ("libs/db_utils.php");
include ("classes/db_liclicita_classe.php");
include ("classes/db_liclicitasituacao_classe.php");
include ("classes/db_liclicitem_classe.php");
include ("classes/db_empautitem_classe.php");
include ("classes/db_pcorcamjulg_classe.php");
$clliclicita = new cl_liclicita();
$clliclicitasituacao = new cl_liclicitasituacao();
$clliclicitem = new cl_liclicitem();
$clempautitem = new cl_empautitem();
$clpcorcamjulg = new cl_pcorcamjulg();
$clrotulo = new rotulocampo();
$clrotulo->label('');
parse_str($HTTP_SERVER_VARS ['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);
$where = "";
$and = "";
if (($data != "--") && ($data1 != "--")) {
  $where .= $and . " l20_datacria  between '$data' and '$data1' ";
  $data = db_formatar($data, "d");
  $data1 = db_formatar($data1, "d");
  $info = "De $data até $data1.";
  $and = " and ";
} else if ($data != "--") {
  $where .= $and . " l20_datacria >= '$data'  ";
  $data = db_formatar($data, "d");
  $info = "Apartir de $data.";
  $and = " and ";
} else if ($data1 != "--") {
  $where .= $and . " l20_datacria <= '$data1'   ";
  $data1 = db_formatar($data1, "d");
  $info = "Até $data1.";
  $and = " and ";
}
if ($l20_codigo != "") {
  $where .= $and . " l20_codigo=$l20_codigo ";
  $and = " and ";
}
if ($l20_numero != "") {
  $where .= $and . " l20_numero=$l20_numero ";
  $and = " and ";
  $info1 = "Numero:" . $l20_numero;
}
if ($l03_codigo != "") {
  $where .= $and . " l20_codtipocom=$l03_codigo ";
  $and = " and ";
  if ($l03_descr != "") {
    $info2 = "Modalidade:" . $l03_codigo . "-" . $l03_descr;
  }
}
if ($situac != '') {
  
  $in = $selec == "S" ? " in " : " not in";
  $where .= $and . " l20_licsituacao $in ($situac)";
  $and = " and ";

}

$where .= $and . " l20_instit = " . db_getsession("DB_instit");

$result = $clliclicita->sql_record($clliclicita->sql_query(null, "*", "l20_codtipocom,l20_numero", $where));
//die ($clliclicita->sql_query(null,"*","l20_codtipocom,l20_numero",$where));
$numrows = $clliclicita->numrows;
if ($numrows == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existe registro cadastrado.');
  exit();
}
$head2 = "Relatório Resumido de Licitação";
$head3 = @$info;
$head4 = @$info1;
$head5 = @$info2;
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(0, 1);
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 8);
$troca = 1;
$alt = 4;
$total = 0;
$p = 0;
$valortot = 0;
$muda = 0;
for($i = 0; $i < $numrows; $i ++) {
  
  $iPosFinal = 0;
  db_fieldsmemory($result, $i);
  $pdf->addpage();

  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(30, $alt, 'Edital :', 0, 0, "R", 0);
  $pdf->setfont('arial', '', 7);
  $pdf->cell(30, $alt,$l20_edital, 0, 0, "L", 0);

  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(30, $alt, 'Modalidade :', 0, 0, "R", 0);
  $pdf->setfont('arial', '', 7);
  $pdf->cell(30, $alt, $l20_codtipocom . ' - ' . $l03_descr, 0, 0, "L", 0);
  
  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(30, $alt, 'Número :', 0, 0, "R", 0);
  $pdf->setfont('arial', '', 7);
  $pdf->cell(30, $alt, $l20_numero, 0, 1, "L", 0);

  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(30, $alt, 'Data Abertura :', 0, 0, "R", 0);
  $pdf->setfont('arial', '', 7);
  $pdf->cell(30, $alt, db_formatar($l20_dataaber, 'd'), 0, 0, "L", 0);
  
  if (empty($l20_procadmin)) {
    
    $oDAOLiclicitaproc    = db_utils::getDao("liclicitaproc");
    $sSqlProcessoSistema  = $oDAOLiclicitaproc->sql_query(null,"*", null, "l34_liclicita = {$l20_codigo}");
    $rsProcessoSistema    = $oDAOLiclicitaproc->sql_record($sSqlProcessoSistema);
    
    if ($oDAOLiclicitaproc->numrows == 1) {
      
      $oLiclicitaproc = db_utils::fieldsMemory($rsProcessoSistema, 0);
      $l20_procadmin  = substr($oLiclicitaproc->l34_protprocesso ." - ". $oLiclicitaproc->p51_descr, 0, 30);
    }
  }
  
  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(30, $alt, 'Processo Administrativo:', 0, 0, "R", 0);
  $pdf->setfont('arial', '', 7);
  $pdf->cell(30, $alt, $l20_procadmin, 0, 0, "L", 0);
  
  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(30, $alt, 'tipo :', 0, 1, "R", 0);
  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(30, $alt, 'Objeto :', 0, 0, "R", 0);
  $pdf->setfont('arial', '', 7);
  $pdf->multicell(150, $alt, $l20_objeto, 0, "L", 0);
  $result_sec = $clliclicitem->sql_record($clliclicitem->sql_query_orc(null, "distinct o40_descr", null, "l21_codliclicita = $l20_codigo"));
  if ($clliclicitem->numrows > 0) {
    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(30, $alt, 'Secretaria(s) :', 0, 0, "R", 0);
    $pdf->setfont('arial', '', 7);
    for($z = 0; $z < $clliclicitem->numrows; $z ++) {
      db_fieldsmemory($result_sec, $z);
      
      if ($z != 0) {
        $pdf->cell(30, $alt, "", 0, 0, "R", 0);
      }
      $pdf->cell(150, $alt, $o40_descr, 0, 1, "L", 0);
    }
  }
  //Consultamos os participantes da licitação, e percorremos o recordset para montar o relatorio
  $pdf->cell(190, $alt, '', 'T', 1, "L", 0);
  $pdf->setfont('arial', 'b', 7);
  $pdf->Cell(70, $alt, "Participantes", "TB", 0, "C", 1);
  $pdf->Cell(70, $alt, "Vencedores", 1, 0, "C", 1);
  $pdf->Cell(28, $alt, "Valor Contrato", 1, 0, "C", 1);
  $pdf->Cell(28, $alt, "Total", "TB", 1, "C", 1);
  $iInicio = $pdf->gety();
  $nTotalLicitação = 0;
  $sSqlParticipantes = $clpcorcamjulg->sql_query_participantes_licitacao(null, null, "distinct z01_numcgm,
                                                                     z01_nome,
                                                                     pcorcam.pc20_dtate,
                                                                     pcorcam.pc20_hrate", "z01_nome", "l20_codigo={$l20_codigo} 
                                                            and pc10_instit=" . db_getsession("DB_instit"));
  $rsParticipantes = $clpcorcamjulg->sql_record($sSqlParticipantes);

  $pdf->setfont('arial', '', 6);
  if ($clpcorcamjulg->numrows > 0) {
    
    $aParticipantes = db_utils::getCollectionByRecord($rsParticipantes);
    foreach ( $aParticipantes as $oParticipante ) {
      $pdf->Cell(70, $alt, $oParticipante->z01_nome, 0, 1, "L");
    }
    $iPosFinal = $pdf->GetY();
    
    $sSqlVencedores  = "select cgm.z01_nome,                                                                                "; 
    $sSqlVencedores .= "       sum(pcorcamval.pc23_valor) as valortotal                                                     "; 
    $sSqlVencedores .= "  from pcorcamval                                                                                   ";
    $sSqlVencedores .= "       inner join pcorcamjulg     on pcorcamjulg.pc24_orcamitem    = pcorcamval.pc23_orcamitem      ";
    $sSqlVencedores .= "                                 and pcorcamjulg.pc24_orcamforne   = pcorcamval.pc23_orcamforne     ";
    $sSqlVencedores .= "                                 and pcorcamjulg.pc24_pontuacao    = 1                              ";
    $sSqlVencedores .= "       inner join pcorcamforne    on pcorcamforne.pc21_orcamforne  = pcorcamjulg.pc24_orcamforne    ";                  
    $sSqlVencedores .= "       inner join cgm             on cgm.z01_numcgm                = pcorcamforne.pc21_numcgm       ";
    $sSqlVencedores .= "       inner join pcorcamitem     on pcorcamitem.pc22_orcamitem    = pcorcamjulg.pc24_orcamitem     ";
    $sSqlVencedores .= "       inner join pcorcamitemlic  on pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem     ";
    $sSqlVencedores .= "       inner join liclicitem      on liclicitem.l21_codigo         = pcorcamitemlic.pc26_liclicitem ";
    $sSqlVencedores .= "       inner join pcprocitem      on pcprocitem.pc81_codprocitem   = liclicitem.l21_codpcprocitem   ";
    $sSqlVencedores .= "       inner join solicitem       on solicitem.pc11_codigo         = pcprocitem.pc81_solicitem      ";
    $sSqlVencedores .= "       inner join solicita        on solicita.pc10_numero          = solicitem.pc11_numero          ";
    $sSqlVencedores .= " where liclicitem.l21_codliclicita = {$l20_codigo}                                                  ";    
    $sSqlVencedores .= "   and solicita.pc10_instit        = " . db_getsession("DB_instit"); 
    $sSqlVencedores .= " group by cgm.z01_nome                                                                              ";
    $sSqlVencedores .= " order by cgm.z01_nome                                                                              ";
    $rsVencedores = $clpcorcamjulg->sql_record($sSqlVencedores);
    if ($clpcorcamjulg->numrows > 0) {
      
      $pdf->SetXY(80, $iInicio);
      $aVencedores = db_utils::getCollectionByRecord($rsVencedores);
      foreach ( $aVencedores as $oVencedores ) {
        
        $pdf->SetX(80);
        $pdf->Cell(70, $alt, $oVencedores->z01_nome, 0, 0, "L");
        $pdf->Cell(28, $alt, db_formatar($oVencedores->valortotal, "f"), 0, 1, "R");
        $nTotalLicitação += $oVencedores->valortotal;
      
      }
    }
    $pdf->SetXY(176, $iInicio);
    $pdf->Cell(28, $alt, db_formatar($nTotalLicitação, "f"), 0, 1, "R");
    
    $pdf->SetY($iPosFinal+5);
    //$troca = 1;
    $result_dot = $clliclicitem->sql_record($clliclicitem->sql_query_inf(null, "distinct fc_estruturaldotacao(pc13_anousu,pc13_coddot) as estrutural ", null, "l21_codliclicita=$l20_codigo"));
    if ($clliclicitem->numrows > 0) {
      $pdf->setfont('arial', 'b', 8);
      $pdf->cell(60, $alt, 'Origem do Recurso', "TB", 1, "C", 1);
      $p = 0;
      //$troca = 0;
      for($w = 0; $w < $clliclicitem->numrows; $w ++) {
        db_fieldsmemory($result_dot, $w);
        
        $pdf->setfont('arial', '', 7);
        $pdf->cell(60, $alt, $estrutural, 0, 1, "C", 0);
        if ($p == 0) {
          $p = 1;
        } else {
          $p = 0;
        }
        $total ++;
      }
    }
  }
}
$pdf->Output();

?>