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

require_once("fpdf151/pdf.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_utils.php");
$oDaoVacina        = db_utils::getdao('vac_vacina');
$oDaoVacAplicalote = db_utils::getdao('vac_aplicalote');
$oDaoVacboletim    = db_utils::getdao('vac_boletim');
$oDaoVacVacinadose = db_utils::getdao('vac_vacinadose');
$oDaoVacSala       = db_utils::getdao('vac_sala');
$dHoje             = date("Y-m-d", db_getsession("DB_datausu"));
$aHoje             = explode("-", $dHoje);
$iDepartamento     = db_getsession("DB_coddepto");
$dIni              = substr($dDataini, 6, 4)."-".substr($dDataini, 3, 2)."-".substr($dDataini, 0, 2);
$dFim              = substr($dDatafim, 6, 4)."-".substr($dDatafim, 3, 2)."-".substr($dDatafim, 0, 2);
$sWhere            = " vc16_d_data between '$dIni' and '$dFim' ";
if ($iVacina != 0) {
  $sWhere .= " and vc06_i_codigo=$iVacina ";
}
$sSql = $oDaoVacAplicalote->sql_query2(null, "*", null, $sWhere);
$oDaoVacAplicalote->sql_record($sSql);

if ($oDaoVacAplicalote->numrows == 0) {
  
  ?>
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
$oPdf = new pdf();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(200);
$head1  = "Vacinas por Faixa Etária";
$head2  = "Período: $dDataini A $dDatafim ";
$lFirst = true;
if ($iVacina == 0) {

  $sSql    = $oDaoVacina->sql_query_file(null, "vc06_i_codigo, vc06_c_descr, vc06_c_codpni");
  $rsDados = $oDaoVacina->sql_record($sSql);
  for ($iX = 0; $iX < $oDaoVacina->numrows; $iX++) {

    $oDados          = db_utils::fieldsmemory($rsDados, $iX);
    $aVacinas[$iX]   = $oDados->vc06_i_codigo;
    $aVacNomes[$iX]  = $oDados->vc06_c_descr;
    $aVacCodPNI[$iX] = $oDados->vc06_c_codpni;
    
  }
  
} else {

  $aVacinas[0] = $iVacina;
  $sSql        = $oDaoVacina->sql_query($iVacina, "vc06_c_descr, vc06_c_codpni", null, " vc06_i_codigo=$iVacina ");
  $rsDados     = $oDaoVacina->sql_record($sSql);
  if ($oDaoVacina->numrows > 0) {

    $oDados        = db_utils::fieldsmemory($rsDados, 0);
    $aVacNomes[0]  = $oDados->vc06_c_descr;
    $aVacCodPNI[0] = $oDados->vc06_c_codpni;
  
  }
  
}
$iTam       = count($aVacinas);
//Selecionar todas as unidades
$sSql       = $oDaoVacSala->sql_query(null, " vc01_i_unidade,descrdepto ");
$rsSala     = $oDaoVacSala->sql_record($sSql);
$iTotalUnid = $oDaoVacSala->numrows;
//For das unidades que tem salas cadastradas
for ($iInUnid = 0; $iInUnid < $iTotalUnid; $iInUnid++) {
  
  $oDadosDepto  = db_utils::fieldsmemory($rsSala, $iInUnid);
  $head3        = "Unidade: ".$oDadosDepto->descrdepto;
  $lFirst       = true;
  //for principal percorre todas as vacinas ou a que foi selecionada na formulario
  for ($iX = 0; $iX < $iTam; $iX++) {

    if ($oPdf->GetY() > ($oPdf->h - 25) || $lFirst == true) {
      
      $oPdf->ln(5); 
      $oPdf->addpage('P');
      $lFirst = false;
      
    }
    $oPdf->setfont('arial', 'b', 10);
    $oPdf->SetXY($oPdf->GetX(), $oPdf->GetY() + 2);
    $oPdf->cell(110, 4, "Vacina: ".$aVacNomes[$iX], 0, 0, "L", 0);
    $oPdf->cell(60, 4, "Imuno: ".$aVacCodPNI[$iX], 0, 1, "L", 0);
    $oPdf->cell(17.5, 4, "DOSE", 1, 0, "C", 1);
    $sOrder    = " vc13_i_anoini asc ";
    $sWhere    = " vc13_i_vacina = ".$aVacinas[$iX];
    $sSql      = $oDaoVacboletim->sql_query(null, "*", $sOrder, $sWhere);
    $rsBoletim = $oDaoVacboletim->sql_record($sSql);
    //Monta o label dos boletins referentes a vacina
    for ($iY = 1; $iY <= 10; $iY++) {
      
      $sIdade  = " 0 ";
      $iQuebra = 0;
      if ($iY == 10) {
        $iQuebra = 1;
      }
      if (($iY-1) < $oDaoVacboletim->numrows) {
        
        $oBoletim = db_utils::fieldsMemory($rsBoletim, $iY-1);
        $sIdade   = $oBoletim->vc13_c_descr;
      
      }
      $iTotalFaixa[$iY] = 0;
      $oPdf->cell(17.5, 4, $sIdade, 1, $iQuebra, "C", 1);
      
    }
    //selecionas todas as doses da vacina corrente
    $sSql         = $oDaoVacVacinadose->sql_query(null,
                                                  "vc07_i_codigo, vc03_c_codpni",
                                                  null,
                                                  "vc07_i_vacina=$aVacinas[$iX]"
                                                 );
    $rsVacinadose = $oDaoVacVacinadose->sql_record($sSql);
    $iVacinaDoseL = $oDaoVacVacinadose->numrows;
    $oPdf->setfont('arial', '', 10);
    //for secundario percorre todas as doses da vacina corrente no for principal
    for ($iX2 = 0; $iX2 < $iVacinaDoseL; $iX2++) {
      
      $oVacinadose = db_utils::fieldsMemory($rsVacinadose, $iX2);
      $oPdf->cell(17.5, 4, $oVacinadose->vc03_c_codpni, 1, 0, "C", 0);      
      for ($iY = 1; $iY <= 10; $iY++) {
        
        $iQuebra = 0;
        if ($iY == 10) {
          $iQuebra = 1;
        }
        if (($iY-1) < $oDaoVacboletim->numrows) {

          $oBoletim    = db_utils::fieldsMemory($rsBoletim, $iY-1);
          $dIniBoletim = somaDataDiaMesAno($aHoje[2],
                                           $aHoje[1],
                                           $aHoje[0],
                                           -$oBoletim->vc13_i_diafim,
                                           -$oBoletim->vc13_i_mesfim,
                                           -$oBoletim->vc13_i_anofim,
                                           2
                                          );
          $dFimBoletim = somaDataDiaMesAno($aHoje[2],
                                           $aHoje[1],
                                           $aHoje[0],
                                           -$oBoletim->vc13_i_diaini,
                                           -$oBoletim->vc13_i_mesini,
                                           -$oBoletim->vc13_i_anoini,
                                           2
                                          );
          $sWhere      = " b.vc16_i_dosevacina=$oVacinadose->vc07_i_codigo ";
          $sWhere     .= " and cgs_und.z01_d_nasc between '$dIniBoletim' and '$dFimBoletim' ";
          $sWhere     .= " and b.vc16_d_dataaplicada between '$dIni' and '$dFim' ";
          $sWhere     .= " and vc01_i_unidade = $oDadosDepto->vc01_i_unidade";
          $sInnerJoin  = " inner join vac_aplica as b on b.vc16_i_codigo = a.vc17_i_aplica ";
          $sInnerJoin .= " inner join cgs_und on cgs_und.z01_i_cgsund = b.vc16_i_cgs ";
          $sInnerJoin .= " inner join vac_sala on vc01_i_codigo = a.vc17_i_sala ";
          $sSql        = " select coalesce(sum(b.vc16_n_quant),0) as quant"; 
          $sSql       .= " from vac_aplicalote as a $sInnerJoin where $sWhere";
          $rsAplicadas = $oDaoVacAplicalote->sql_record($sSql);
           
          if ($oDaoVacAplicalote->numrows > 0) {
              
            $oDadosVacinaAplicada = db_utils::fieldsmemory($rsAplicadas, 0);
            if ($oDadosVacinaAplicada->quant > 0) {
              $iQuant = $oDadosVacinaAplicada->quant;
            } else {
              $iQuant = "0";
            }
            
          } else {
            $iQuant = "0";
          }
            
        } else {
          $iQuant = "0";
        }
        if ($iQuant != "0") {
          $iTotalFaixa[$iY] += $iQuant;
        }
        $oPdf->cell(17.5, 4, $iQuant, 1, $iQuebra, "C", 0);
      }
    }
    //imprime o total de cada faixaetaria
    $oPdf->cell(17.5, 4, "Total", 1, 0, "C", 1);
    for ($iY = 1; $iY <= 10; $iY++) {

      $iQuebra = 0;
      if ($iY == 10) {
        $iQuebra = 1;
      }
      if ($iTotalFaixa[$iY] == 0) {
        $iTotalFaixa[$iY] = "0";
      }
      $oPdf->cell(17.5, 4, $iTotalFaixa[$iY], 1, $iQuebra, "C", 1);
    
    }

  }//For das vacinas

}//For do das Unidades 
$oPdf->Output();
?>