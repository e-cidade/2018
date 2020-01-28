<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

include("fpdf151/pdf.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_utils.php");
$oDaoVacina         = db_utils::getdao('vac_vacina');
$oDaoVacAplicalote  = db_utils::getdao('vac_aplicalote');
$oDaoVacboletim     = db_utils::getdao('vac_boletim');
$oDaoVacVacinadose  = db_utils::getdao('vac_vacinadose');
$oDaoVacSala        = db_utils::getdao('vac_sala');
$oDaoMatestoqueitem = db_utils::getdao('matestoqueitemlote');
$dHoje              = date("Y-m-d",db_getsession("DB_datausu"));
$aHoje              = explode("-",$dHoje);
$iDepartamento      = db_getsession("DB_coddepto");
$dIni               = substr($dDataini,6,4)."-".substr($dDataini,3,2)."-".substr($dDataini,0,2);
$dFim               = substr($dDatafim,6,4)."-".substr($dDatafim,3,2)."-".substr($dDatafim,0,2);
$sWhere             = " vc16_d_data between '$dIni' and '$dFim' ";
if ($iLote != 0) {
  $sWhere .= " and m77_sequencial=$iLote ";
}
$sSql    = $oDaoVacAplicalote->sql_query2(null,"*",null,$sWhere);
$oDaoVacAplicalote->sql_record($sSql);
if ($oDaoVacAplicalote->numrows == 0) {?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhum registro encontrado<br>
     <input type='button' value='Fechar' onclick='window.close()'></b>
    </font>
   </td>
  </tr>
 </table>
 <?
 exit;
}

//DEFINIÇÕES
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(200);
$head1  = "Vacinas por Faixa Etaria";
$head2  = "Período: $dDataini A $dDatafim ";
$lFirst = true;
if ($iLote == 0) {

  $sSql    = $oDaoMatestoqueitem->sql_query(null,"m77_sequencial,m77_lote",null," m70_coddepto = $iDepartamento ");
  $rsDados = $oDaoMatestoqueitem->sql_record($sSql);
  for ($iX=0; $iX < $oDaoMatestoqueitem->numrows; $iX++) {

    $oDados      = db_utils::fieldsmemory($rsDados,$iX);
    $aiLote[$iX] = $oDados->m77_sequencial;
    $asLote[$iX] = $oDados->m77_lote;
    
  }
} else {

  $aiLote[0] = $iLote;
  $asLote[0] = $sLote;
  
}
$iTam = count($aiLote);

if ($iUnidades == 1) {
	
  //Selecionar todas as unidades
  $sSql       = $oDaoVacSala->sql_query(null," vc01_i_unidade,descrdepto ");
  $rsSala     = $oDaoVacSala->sql_record($sSql);
  $iTotalUnid = $oDaoVacSala->numrows;

}else{
	$iTotalUnid = 1;
}

//For das unidades que tem salas cadastradas
for ($iInUnid=0; $iInUnid < $iTotalUnid; $iInUnid++) {
  
  if ($iUnidades == 1) {
  	
	 $oDadosDepto  = db_utils::fieldsmemory($rsSala,$iInUnid);
   $head3        = "unidade: ".$oDadosDepto->descrdepto;
   $lFirst       = true;
   
  }
  //for principal percorre todas os Lotes ou a que foi selecionada na formulario
  for ($iX = 0; $iX < $iTam; $iX++) {

    if ($pdf->GetY() > $pdf->h -25 || $lFirst == true) {
      
      $pdf->ln(5);
      $pdf->addpage('P');
      $lFirst = false;
      
    }
    $pdf->setfont('arial','b',10);
    $pdf->cell(110,4," Lote: ".$asLote[$iX],0,1,"L",0);
    
    //selecionas todas as vacinas do lote corrente
    $sCampos  = "vc06_i_codigo,vc06_c_codpni,vc06_c_descr";
    $sSql     = $oDaoVacina->sql_query_file(null,$sCampos);
    $sSql    .= " inner join vac_vacinamaterial on vc29_i_vacina      = vc06_i_codigo";
    $sSql    .= " inner join matmater           on m60_codmater       = vc29_i_material";
    $sSql    .= " inner join matestoque         on m70_codmatmater    = m60_codmater";
    $sSql    .= " inner join matestoqueitem     on m71_codmatestoque  = m70_codigo";
    $sSql    .= " inner join matestoqueitemlote on m77_matestoqueitem = m71_codlanc"; 
    $sSql    .= " where m77_sequencial=".$aiLote[$iX];
    $rsVacina = $oDaoVacina->sql_record($sSql);
    $iVacinaL = $oDaoVacina->numrows;
    //for secundario percorre todas as doses da vacina corrente no for principal
    if ($iVacinaL > 0) {
    	
      $oVacina = db_utils::fieldsMemory($rsVacina,0);
      $pdf->cell(170,4,"       Imuno: ".$oVacina->vc06_c_codpni." - ".$oVacina->vc06_c_descr,0,1,"L",0);
      $pdf->cell(30,4," CGS ",1,0,"C",1);
      $pdf->cell(80,4," Nome ",1,0,"C",1);
      $pdf->cell(40,4," Data de Aplcação ",1,1,"C",1);
      
      $pdf->setfont('arial','',10);
      
      //Percorre todas as plaicação daqueela vacina com aquele lote num determinado periodo de tempo
      $sWhere      = " vc16_d_data between '$dIni' and '$dFim' ";
      $sWhere     .= " and m77_sequencial=$aiLote[$iX] ";
      if ($iUnidades == 1) {
        $sWhere     .= " and vc01_i_unidade=".$oDadosDepto->vc01_i_unidade;
      }
      $sCampos     = "z01_i_cgsund,z01_v_nome,vc16_d_data";  
      $sSql        = $oDaoVacAplicalote->sql_query2(null,$sCampos,null,$sWhere);
      $rsAplicadas = $oDaoVacAplicalote->sql_record($sSql);
      for ($iInd = 0; $iInd < $oDaoVacAplicalote->numrows; $iInd++) {
      
        $oAplicadas = db_utils::fieldsMemory($rsAplicadas,$iInd,true);
        $pdf->cell(30,4,$oAplicadas->z01_i_cgsund,1,0,"C",0);
        $pdf->cell(80,4,$oAplicadas->z01_v_nome,1,0,"L",0);
        $pdf->cell(40,4,$oAplicadas->vc16_d_data,1,1,"C",0);
      
      }
      if ($oDaoVacAplicalote->numrows == 0) {
      	$pdf->cell(150,4,"Nenhuma vacina aplicada",1,1,"C",0);
      }
      $pdf->setfont('arial','b',10);
      $pdf->cell(110,4,"","TBL",0,"C",1);
      $pdf->cell(40,4," Total: ".$oDaoVacAplicalote->numrows,"TBR",1,"L",1);
    }
    
  }//For dos lotes
}//For do das Unidades 
$pdf->Output();
?>