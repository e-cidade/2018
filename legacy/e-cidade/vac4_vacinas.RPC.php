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

require_once('libs/db_stdlib.php');
require_once('libs/db_utils.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/JSON.php');
require_once('dbforms/db_funcoes.php');
require_once('ext/php/adodb-time.inc.php');
require_once('libs/db_stdlibwebseller.php');

$oJson               = new services_json();
$oParam              = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMessage  = '';

if ($oParam->exec == 'getCgsCns') {

  $oDaoCgsCartaoSus = db_utils::getdao('cgs_cartaosus');
  
  $sSql             = $oDaoCgsCartaoSus->sql_query(null, 'z01_i_cgsund, z01_v_nome', 
                                                   null, ' s115_c_cartaosus = \''.$oParam->iCns.'\''
                                                  );
  $rsCgsCartaoSus   = $oDaoCgsCartaoSus->sql_record($sSql);
  
  if ($oDaoCgsCartaoSus->numrows > 0) { // se encontrou o cgs

    $oDadosCgsCartaoSus     = db_utils::fieldsmemory($rsCgsCartaoSus, 0);
    $oRetorno->z01_i_cgsund = $oDadosCgsCartaoSus->z01_i_cgsund;
    $oRetorno->z01_v_nome   = urlencode($oDadosCgsCartaoSus->z01_v_nome);

  } else {
 
    $oRetorno->z01_i_cgsund = '';
    $oRetorno->z01_v_nome   = '';

  }

}

if ($oParam->exec == 'getVacinaMaterial') {
	
  $sWhere = "vc29_i_vacina = ".$oParam->iVacina;
  $oDaoVacinaMaterial = db_utils::getdao('vac_vacinamaterial');
  $aItens             = array();
  $sCampos = "vc29_i_codigo,m60_descr,vc29_i_dose,m60_codmater";
  $sSql               = $oDaoVacinaMaterial->sql_query(null,$sCampos,"m60_descr",$sWhere);
  $rsVacinaMaterial   = $oDaoVacinaMaterial->sql_record($sSql);
  for ($iX = 0; $iX < $oDaoVacinaMaterial->numrows; $iX++) {

    $oDados         = db_utils::fieldsmemory($rsVacinaMaterial,$iX);
    $aItens[$iX][0] = $oDados->vc29_i_codigo;
    $aItens[$iX][1] = $oDados->m60_descr;
    $aItens[$iX][2] = $oDados->vc29_i_dose;
    $aItens[$iX][3] = $oDados->m60_codmater;

  }
  $oRetorno->aItens = $aItens;

}

if ($oParam->exec == 'VacinaMaterial') {

  $oDaoVacVacinaMaterial = db_utils::getdao('vac_vacinamaterial');

  db_inicio_transacao();
  $oDaoVacVacinaMaterial->vc29_i_codigo   = $oParam->iCodigo;
  $oDaoVacVacinaMaterial->vc29_i_vacina   = $oParam->iVacina;
  $oDaoVacVacinaMaterial->vc29_i_material = $oParam->iMaterial;
  $oDaoVacVacinaMaterial->vc29_i_dose     = $oParam->iDose;
  if($oParam->iOp == 1){
    $oDaoVacVacinaMaterial->incluir(null);
  } elseif ($oParam->iOp == 2) {
    $oDaoVacVacinaMaterial->alterar($oParam->iCodigo);
  } elseif ($oParam->iOp == 3) {
    $oDaoVacVacinaMaterial->excluir($oParam->iCodigo);
  }
  if ($oDaoVacVacinaMaterial->erro_status == '0') {
  	$oRetorno->iStatus   = 0;
    $oRetorno->sMessage  = $oDaoVacVacinaMaterial->erro_msg;
  }
  db_fim_transacao($oDaoVacVacinaMaterial->erro_status == "0");
  
}

if ($oParam->exec == 'getGridLotes') {

  $sWhere = "";
  if($oParam->iCodVacina != 0) {
    $sWhere = "vc15_i_vacina = ".$oParam->iCodVacina;
  }
  $oDaoVacinaLote = db_utils::getdao('vac_vacinalote');
  $aItens         = array();
  $sSql           = $oDaoVacinaLote->sql_query_matestoque(null,"vc15_i_codigo,vc06_c_descr,m77_lote",null,$sWhere);
  $rsVacinalote   = $oDaoVacinaLote->sql_record($sSql);
  for ($iX = 0; $iX < $oDaoVacinaLote->numrows; $iX++) {

    $oDados         = db_utils::fieldsmemory($rsVacinalote,$iX);
    $aItens[$iX][0] = $oDados->vc15_i_codigo;
    $aItens[$iX][1] = $oDados->vc06_c_descr;
    $aItens[$iX][2] = $oDados->m77_lote;

  }
  $oRetorno->aItens = $aItens;

}

if ($oParam->exec == 'getGridBoletim') {

  $sWhere = "";
  if($oParam->iCodVacina != 0) {
    $sWhere = " vc13_i_vacina = ".$oParam->iCodVacina;
  }
  $oDaoBoletim = db_utils::getdao('vac_boletim');
  $aItens      = array();
  $sCampos     = "vc13_i_codigo,vc13_c_descr,";
  $sCampos    .= "vc13_i_diaini,vc13_i_mesini,vc13_i_anoini,vc13_i_diafim,vc13_i_mesfim,vc13_i_anofim"; 
  $sSql        = $oDaoBoletim->sql_query(null,$sCampos,null,$sWhere);
  $rsBoletim   = $oDaoBoletim->sql_record($sSql);
  for ($iX = 0; $iX < $oDaoBoletim->numrows; $iX++) {

    $oDados         = db_utils::fieldsmemory($rsBoletim,$iX);
    $aItens[$iX][0] = $oDados->vc13_i_codigo;
    $aItens[$iX][1] = $oDados->vc13_c_descr;
    $aItens[$iX][2] = $oDados->vc13_i_diaini;
    $aItens[$iX][3] = $oDados->vc13_i_mesini;
    $aItens[$iX][4] = $oDados->vc13_i_anoini;
    $aItens[$iX][5] = $oDados->vc13_i_diafim;
    $aItens[$iX][6] = $oDados->vc13_i_mesfim;
    $aItens[$iX][7] = $oDados->vc13_i_anofim;
    
  }
  $oRetorno->aItens = $aItens;

}
if ($oParam->exec == 'getGridDevolucao') {

  $oDaoVacFechamento = db_utils::getdao('vac_fechamento');
  $oDaoVacDevolucao  = db_utils::getdao('vac_devolucao');
  $sInnerjoin        = " inner join vac_aplicalote on vc17_i_codigo = vc21_i_aplicalote ";
  $sInnerjoin       .= " inner join vac_sala on vc01_i_codigo = vc17_i_sala "; 
  $sSubSql           = "select vc17_i_matetoqueitemlote from vac_fechaaplica $sInnerjoin ";
  $sSubSql          .= " where vc21_i_fechamento=vc20_i_codigo and vc01_i_unidade=$oParam->iUnidade ";
  $sWhere            = "(vc19_i_matetoqueitemlote = $oParam->iLote or vc17_i_matetoqueitemlote = $oParam->iLote) and ";
  $sWhere           .= " ( $oParam->iLote in ( $sSubSql ) ";
  $sInnerjoin        = " inner join vac_sala on vc01_i_codigo = vc19_i_sala ";
  $sSubSql           = "select vc19_i_matetoqueitemlote from vac_descarte $sInnerjoin";
  $sSubSql          .= " where vc22_i_fechamento=vc20_i_codigo and vc01_i_unidade=$oParam->iUnidade";
  $sWhere           .= " or $oParam->iLote in ( $sSubSql )) ";
  $sWhere           .= " group by vc20_i_codigo,a.m77_lote,a.m77_dtvalidade,";
  $sWhere           .= "a3.vc29_i_dose,b.m77_lote,b.m77_dtvalidade,b3.vc29_i_dose";
  $sCampos           = "distinct on (vc20_i_codigo) vc20_i_codigo,";
  $sCampos          .= "a.m77_lote as lotea, ";
  $sCampos          .= "a.m77_dtvalidade as dta,";
  $sCampos          .= "a3.vc29_i_dose as saia,";
  $sCampos          .= "b.m77_lote as loteb,";
  $sCampos          .= "b.m77_dtvalidade as dtb,";
  $sCampos          .= "b3.vc29_i_dose as saib,";
  $sInnerjoin        = " inner join vac_aplicalote on vc17_i_codigo = vc21_i_aplicalote ";
  $sInnerjoin       .= " inner join vac_sala on vc01_i_codigo = vc17_i_sala "; 
  $sInnerjoin       .= " inner join vac_aplica     on vc16_i_codigo = vc17_i_aplica ";
  $sSubSql           = " (select coalesce(sum(vc16_n_quant),0) from vac_fechaaplica $sInnerjoin"; 
  $sSubSql          .= " where vc21_i_fechamento=vc20_i_codigo  and vc01_i_unidade=$oParam->iUnidade ";
  $sSubSql          .= " and vc17_i_matetoqueitemlote = $oParam->iLote) ";
  $sCampos          .= "($sSubSql+";
  $sInnerjoin        = " inner join vac_fechadescarte on vc22_i_descarte = vac_descarte.vc19_i_codigo ";
  $sInnerjoin       .= " inner join vac_sala on vc01_i_codigo = vc19_i_sala ";
  $sSubSql           = " (select coalesce(sum(vc19_n_quant),0) from vac_descarte $sInnerjoin"; 
  $sSubSql          .= "  where vc22_i_fechamento=vc20_i_codigo and vc01_i_unidade=$oParam->iUnidade";
  $sSubSql          .= " and vc19_i_matetoqueitemlote = $oParam->iLote) ";
  $sCampos          .= "$sSubSql) as quantidade_atendida";
  $sSql              = $oDaoVacFechamento->sql_query2(null,$sCampos,null,$sWhere,$oParam->iLote);
  $rsVacinas         = $oDaoVacFechamento->sql_record($sSql);
  $aItens            = array();
  $iCont             = 0;
  for ($iX = 0; $iX < $oDaoVacFechamento->numrows; $iX++) {

    $oDados  = db_utils::fieldsmemory($rsVacinas,$iX,true);
    $sWhere  = " vc20_i_codigo = $oDados->vc20_i_codigo and ( vc19_i_matetoqueitemlote=$oParam->iLote or ";
    $sWhere .= " vc17_i_matetoqueitemlote=$oParam->iLote)";
    $sSql    = $oDaoVacDevolucao->sql_query2(null,"*",null,$sWhere);
    $oDaoVacDevolucao->sql_record($sSql);
    if ($oDaoVacDevolucao->numrows == 0) {

      $aItens[$iCont][0] = $oDados->vc20_i_codigo;
      $aItens[$iCont][1] = ($oDados->lotea=='')?$oDados->loteb:$oDados->lotea;
      $aItens[$iCont][2] = ($oDados->dta=='')?$oDados->dtb:$oDados->dta;
      if ($oDados->saia == '') {
        $aItens[$iCont][3] = $oDados->quantidade_atendida/$oDados->saib;
      } else {
      	$aItens[$iCont][3] = $oDados->quantidade_atendida/$oDados->saia;
      }
      $iCont++;

    }

  }
  if ($oDaoVacFechamento->numrows > 0) {

    $oRetorno->aItens = $aItens;

  } else {

    $oRetorno->iStatus   = 0;
    $oRetorno->sMessage  = urlencode('Nenhuma aplicação ou descarte encontrados!');

  }

}
if ($oParam->exec == 'getGridBaixa') {

  $oDaoAplicalote = db_utils::getdao('vac_aplicalote');
  $sSubsql        = "select coalesce(sum(vc19_n_quant),0) from vac_descarte ";
  $sSubsql       .= " inner join vac_sala as a on vc01_i_codigo = vc19_i_sala ";
  $sSubsql       .= " where a.vc01_i_unidade = ".$oParam->iUnidade;
  $sSubsql       .= " and vc19_i_matetoqueitemlote = m77_sequencial and ";
  $sSubsql       .= " vc19_d_data between '$oParam->iDataini' and '$oParam->iDatafim' and ";
  $sSubanula      = " and not exists (select * from vac_devfechadescarte Where ";
  $sSubanula     .= "vc25_i_fechadescarte = vac_fechadescarte.vc22_i_codigo)";
  $sSubsql       .= " not exists (select * from vac_fechadescarte Where ";
  $sSubsql       .=" vc22_i_descarte = vac_descarte.vc19_i_codigo $sSubanula ) ";
  $sWhere         = " vc16_d_data between '$oParam->iDataini' and '$oParam->iDatafim' ";
  $sWhere        .= " and vc01_i_unidade = ".$oParam->iUnidade;
  $sWhere        .= " and not exists (select * from vac_aplicaanula Where vc18_i_aplica = vac_aplica.vc16_i_codigo) ";
  $sSubanula      = " and not exists (select * from vac_devfechaaplica Where ";
  $sSubanula     .= "vc24_i_fechaaplica = vac_fechaaplica.vc21_i_codigo)";
  $sWhere        .= " and not exists (select * from vac_fechaaplica Where ";
  $sWhere        .= " vc21_i_aplicalote = vac_aplica.vc16_i_codigo $sSubanula )";
  $sWhere        .= " group by vc06_c_descr,m77_lote,m77_dtvalidade,m71_quant,vc29_i_dose,m77_sequencial,m71_quantatend";
  $sCampos        = "vc06_c_descr,";
  $sCampos       .= "m77_lote,";
  $sCampos       .= "m77_dtvalidade,";
  $sCampos       .= "(m71_quant-m71_quantatend) as estoque,";
  $sCampos       .= "vc29_i_dose,";
  $sCampos       .= "m77_sequencial,";
  $sCampos       .= "sum(vc16_n_quant) as doses_aplicadas,";
  $sCampos       .= "($sSubsql) as doses_descartadas,";
  $sCampos       .= "(sum(vc16_n_quant)+($sSubsql))/vc29_i_dose as doses_baixar";
  $sSql           = $oDaoAplicalote->sql_query2(null,$sCampos,null,$sWhere);
  $rsVacinas      = $oDaoAplicalote->sql_record($sSql);
  $aItens         = array();
  for ($iX = 0; $iX < $oDaoAplicalote->numrows; $iX++) {

    $oDados         = db_utils::fieldsmemory($rsVacinas,$iX,true);
    $aItens[$iX][0] = urlencode($oDados->vc06_c_descr);
    $aItens[$iX][1] = $oDados->m77_lote;
    $aItens[$iX][2] = $oDados->m77_dtvalidade;
    $aItens[$iX][3] = $oDados->estoque;
    $aItens[$iX][4] = $oDados->vc29_i_dose;
    $aItens[$iX][5] = $oDados->doses_aplicadas;
    $aItens[$iX][6] = $oDados->doses_descartadas;
    $aItens[$iX][7] = $oDados->doses_baixar;
    $aItens[$iX][8] = $oDados->m77_sequencial;

  }
  $oDaoDescarte   = db_utils::getdao('vac_descarte');
  $sSubsql        = "select coalesce(sum(vc16_n_quant),0) from vac_aplicalote ";
  $sSubsql       .= " inner join vac_aplica on vc16_i_codigo = vc17_i_aplica";
  $sSubsql       .= " inner join vac_sala as a on vc01_i_codigo = vc17_i_sala";
  $sSubsql       .= " where a.vc01_i_unidade = ".$oParam->iUnidade;
  $sSubsql       .= " and vc17_i_matetoqueitemlote = m77_sequencial ";
  $sSubsql       .= " and vc16_d_data between '$oParam->iDataini' and '$oParam->iDatafim' and ";
  $sSubsql       .= " not exists (select * from vac_fechaaplica Where vc21_i_aplicalote = vac_aplica.vc16_i_codigo) ";
  $sWhere         = " vc19_d_data between '$oParam->iDataini' and '$oParam->iDatafim' ";
  $sWhere        .= " and vc01_i_unidade = ".$oParam->iUnidade;
  $sWhere        .= " and ($sSubsql) <= 0";
  $sWhere        .= " and not exists (select * from vac_fechadescarte Where vc22_i_descarte = vc19_i_codigo)";
  $sWhere        .= " group by vc06_c_descr,m77_lote,m77_dtvalidade,m71_quant,vc29_i_dose,m77_sequencial,m71_quantatend";
  $sCampos        = "vc06_c_descr,";
  $sCampos       .= "m77_lote,";
  $sCampos       .= "m77_dtvalidade,";
  $sCampos       .= "(m71_quant-m71_quantatend) as estoque,";
  $sCampos       .= "vc29_i_dose,";
  $sCampos       .= "m77_sequencial,";
  $sCampos       .= "0 as doses_aplicadas,";
  $sCampos       .= "sum(vc19_n_quant) as doses_descartadas,";
  $sCampos       .= "(sum(vc19_n_quant))/vc29_i_dose as doses_baixar";
  $sSql           = $oDaoDescarte->sql_query2(null,$sCampos,null,$sWhere);
  $rsVacinas      = $oDaoDescarte->sql_record($sSql);
  $iTam           = count($aItens);
  $iFim           = $oDaoDescarte->numrows+$iTam;
  $iY             = 0;
  for ($iX = 0+$iTam; $iX < $iFim; $iX++) {

    $oDados         = db_utils::fieldsmemory($rsVacinas,$iY,true);
    $aItens[$iX][0] = urlencode($oDados->vc06_c_descr);
    $aItens[$iX][1] = $oDados->m77_lote;
    $aItens[$iX][2] = $oDados->m77_dtvalidade;
    $aItens[$iX][3] = $oDados->estoque;
    $aItens[$iX][4] = $oDados->vc29_i_dose;
    $aItens[$iX][5] = $oDados->doses_aplicadas;
    $aItens[$iX][6] = $oDados->doses_descartadas;
    $aItens[$iX][7] = $oDados->doses_baixar;
    $aItens[$iX][8] = $oDados->m77_sequencial;
    $iY++;

  }
  $oDaoAplicalote->numrows += $oDaoDescarte->numrows;
  if ($oDaoAplicalote->numrows > 0) {
    $oRetorno->aItens = $aItens;
  } else {

    $oRetorno->iStatus   = 0;
    $oRetorno->sMessage  = urlencode('Nenhuma aplicação ou descarte encontrados!');

  }

}

if ($oParam->exec == 'getIdadeDiaMesAno') {

  if(!empty($oParam->z01_d_nasc)) {

    $dDataAtual       = date('d/m/Y', db_getsession('DB_datausu'));
    $aDataAtual       = explode('/', $dDataAtual);
    $aDataNasc        = explode('-', $oParam->z01_d_nasc);
    $sIdade           = calcage((int)$aDataNasc[2], (int)$aDataNasc[1], (int)$aDataNasc[0], 
                                (int)$aDataAtual[0], (int)$aDataAtual[1], (int)$aDataAtual[2], 2);
    $aIdade           = explode(' || ', $sIdade);
    $oRetorno->iAnos  = $aIdade[0];
    $oRetorno->iMeses = $aIdade[1];
    $oRetorno->iDias  = $aIdade[2];

  } else {
      
    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Nenhuma informação para a data de nascimento do CGS $oParam->iCgs encontrada!".
                                    " É necessário lançar a data de nascimento para este CGS.");

  }

}

if ($oParam->exec == 'getDosesUsadasLote') {

  $oDaoVacAplica   = db_utils::getdao('vac_aplica');
  $oDaoVacDescarte = db_utils::getdao('vac_descarte');

  /* Obtenho a quantidade aplicada */
  $sCampos = ' sum(vc16_n_quant) as soma ';
  $sWhere  = ' vc17_i_matetoqueitemlote = '.$oParam->iVacinaLote.' and vc16_i_codigo not in (';
  $sWhere .= ' select vc18_i_aplica from vac_aplicaanula) ';
  $sSql    = $oDaoVacAplica->sql_query2(null, $sCampos, null, $sWhere);
  $rs      = $oDaoVacAplica->sql_record($sSql);
  if ($oDaoVacAplica->numrows > 0) {

    $oDados               = db_utils::fieldsmemory($rs, 0);
    if (empty($oDados->soma)) {
      $oRetorno->iAplicadas = 0;
    } else {
      $oRetorno->iAplicadas = $oDados->soma;
    }

  } else {
    $oRetorno->iAplicadas = 0;
  }

  /* Obtenho a quantidade descartada */
  $sCampos = ' sum(vc19_n_quant) as soma ';
  $sWhere  = ' vc19_i_matetoqueitemlote = '.$oParam->iVacinaLote;
  $sSql    = $oDaoVacDescarte->sql_query2(null, $sCampos, null, $sWhere);
  $rs      = $oDaoVacDescarte->sql_record($sSql);
  if ($oDaoVacDescarte->numrows > 0) {

    $oDados                 = db_utils::fieldsmemory($rs, 0);
    if (empty($oDados->soma)) {
      $oRetorno->iDescartadas = 0;
    } else {
      $oRetorno->iDescartadas = $oDados->soma;
    }

  } else {
    $oRetorno->iDescartadas = 0;
  }

}

if ($oParam->exec == 'getDadosVacinas') {

  $oDaoVacVacinadose = db_utils::getdao('vac_vacinadose');
  $oDaoCgsUnd        = db_utils::getdao('cgs_und');

  /* Bloco que busca a data de nascimento do CGS */
  $sSql = $oDaoCgsUnd->sql_query_file($oParam->iCgs, 'z01_d_nasc');
  $rs   = $oDaoCgsUnd->sql_record($sSql);
  if ($oDaoCgsUnd->numrows < 1) {
  
    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("CGS $oParam->iCgs não encontrado! ".
                                    "Verifique as informações lançadas na aba \"Paciente\""
                                   );
    echo $oJson->encode($oRetorno);
    exit;
  
  } else {

    $oDados = db_utils::fieldsmemory($rs, 0);
    if (empty($oDados->z01_d_nasc)) {
  
      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("'Nenhuma informação para a data de nascimento do CGS $oParam->iCgs encontrada!".
                                      ' É necessário lançar a data de nascimento para este CGS'
                                     );
      echo $oJson->encode($oRetorno);
      exit;
  
    } else {
      $dNasc = $oDados->z01_d_nasc;
    }

  }

  $dAtual = date('d/m/Y', db_getsession('DB_datausu'));
  $aAtual = explode('/', $dAtual);
  
  /* Bloco que busca a informação das vacinas e doses */
  $sCampos  = ' vc07_i_codigo,';
  $sCampos .= ' vc05_c_descr,';
  $sCampos .= ' vc03_c_descr,';
  $sCampos .= ' vc07_c_nome,';
  $sCampos .= ' vc07_i_faixainidias,';
  $sCampos .= ' vc07_i_faixainimes,';
  $sCampos .= ' vc07_i_faixainiano,';
  $sCampos .= ' vc07_i_faixafimdias,';
  $sCampos .= ' vc07_i_faixafimmes,';
  $sCampos .= ' vc07_i_faixafimano,';
  $sCampos .= ' vc07_i_diasatraso,';
  $sCampos .= ' vc07_i_diasantecipacao,';
  $sCampos .= " (select vc16_d_dataaplicada || ' || ' ||";
  $sCampos .= "         vc16_t_obs || ' || ' ||";
  $sCampos .= "         vc16_i_usuario || ' || ' ||";
  $sCampos .= "         login || ' || ' ||";
  $sCampos .= "         vc16_i_codigo || ' || ' ||";
  $sCampos .= '         case when vc17_i_codigo is null';
  $sCampos .= '           then';
  $sCampos .= "             'true'";
  $sCampos .= '           else';
  $sCampos .= "             'false'";
  $sCampos .= "         end as lforarede";
  $sCampos .= '  from vac_aplica';
  $sCampos .= '    left join vac_aplicaanula on vac_aplicaanula.vc18_i_aplica = vac_aplica.vc16_i_codigo';
  $sCampos .= '    left join vac_aplicalote on vac_aplicalote.vc17_i_aplica = vac_aplica.vc16_i_codigo';
  $sCampos .= '    inner join db_usuarios on db_usuarios.id_usuario = vac_aplica.vc16_i_usuario';
  $sCampos .= "      where vc16_i_cgs = $oParam->iCgs";
  $sCampos .= '        and vc16_i_dosevacina = vc07_i_codigo';
  $sCampos .= '        and vc18_i_codigo is null';
  $sCampos .= '            order by vc16_i_codigo desc';
  $sCampos .= '              limit 1)';
  $sCampos .= ' as aplicacao';
  $sOrderBy = ' vc05_i_codigo,vc07_i_faixainiano, vc07_i_faixainimes, vc07_i_faixainidias ';
  $sSql     = $oDaoVacVacinadose->sql_query(null, $sCampos, $sOrderBy);
  $rs       = $oDaoVacVacinadose->sql_record($sSql);
  
 
  for ($iCont = 0; $iCont < $oDaoVacVacinadose->numrows; $iCont++) {
   
    $oDados = db_utils::fieldsmemory($rs, $iCont);
 
    $dDataAplicacao = '';
    $sObsAplicacao  = '';
    $iLogin         = '';
    $sLogin         = '';
    $iCodigoAplic   = '';
    $sForaRede      = '';
   
    /* A variável $oDados->aplicacao contém as informações da aplicação da vacina concatenadas com ' || ',
       que são buscadas no select acima. Caso a vacina (dose) ainda não tenha sido aplicada, a variável estará vazia */
    if (!empty($oDados->aplicacao)) {
     
      $aAplicacao     = explode(' || ', $oDados->aplicacao);
      $dDataAplicacao = $aAplicacao[0]; // data da aplicacao
      $sObsAplicacao  = $aAplicacao[1]; // obs da aplicacao
      $iLogin         = $aAplicacao[2]; // codigo do usuario que lancou a aplicacao
      $sLogin         = $aAplicacao[3]; // login do usuario que lancou a aplicacao
      $iCodigoAplic   = $aAplicacao[4]; // codigo da aplicacao
      $sForaRede      = $aAplicacao[5]; // foi ou nao realizada fora da rede
 
    }
   
    $aNasc = explode('-', $dNasc);
 
    /* Cálculo da data de vencimento (último dia em que é permitido tomar a vacina)*/
    $dVencimento = somaDataDiaMesAno($aNasc[2], $aNasc[1], $aNasc[0], 
                                     $oDados->vc07_i_faixafimdias + $oDados->vc07_i_diasatraso, 
                                     $oDados->vc07_i_faixafimmes, $oDados->vc07_i_faixafimano
                                    ); 
    /* Cálculo do primeiro dia em que é possível tomar a vacina */
    $dInicio     = somaDataDiaMesAno($aNasc[2], $aNasc[1], $aNasc[0], 
                                     $oDados->vc07_i_faixainidias - $oDados->vc07_i_diasantecipacao, 
                                     $oDados->vc07_i_faixainimes, $oDados->vc07_i_faixainiano
                                    );
 
    /* Verifica se a pessoa já podia ter tomado a vacina, ou seja, se a data atual
       é maior ou igual a data de início do periodo para a pessoa tomar a vacina */
    $aInicio     = explode('/', $dInicio);
    if(adodb_mktime(0, 0, 0, $aAtual[1], $aAtual[0], $aAtual[2]) 
       <= adodb_mktime(0, 0, 0, $aInicio[1], $aInicio[0], $aInicio[2])) {
      $sPassouInicio = 'false';
    } else {
      $sPassouInicio = 'true';
    }
 
    $aDadosVacinas[$iCont] = new StdClass();
    $aDadosVacinas[$iCont]->vc07_i_codigo  = $oDados->vc07_i_codigo;
    $aDadosVacinas[$iCont]->vc05_c_descr   = urlencode($oDados->vc05_c_descr);
    $aDadosVacinas[$iCont]->vc03_c_descr   = urlencode($oDados->vc03_c_descr);
    $aDadosVacinas[$iCont]->vc07_c_nome    = urlencode($oDados->vc07_c_nome);
    $aDadosVacinas[$iCont]->dataAplicacao  = $dDataAplicacao;
    $aDadosVacinas[$iCont]->obsAplicacao   = urlencode($sObsAplicacao);
    $aDadosVacinas[$iCont]->foraRede       = $sForaRede;
    $aDadosVacinas[$iCont]->vc16_i_usuario = "$iLogin";
    $aDadosVacinas[$iCont]->login          = urlencode($sLogin);
    $aDadosVacinas[$iCont]->vc16_i_codigo  = $iCodigoAplic;
    if (($oDados->vc07_i_faixafimdias == 0)&&
        ($oDados->vc07_i_faixafimmes  == 0)&&
        ($oDados->vc07_i_faixafimano  == 0)){
      $aDadosVacinas[$iCont]->periodo = urlencode("$dInicio - indefinida");
    } else {
      $aDadosVacinas[$iCont]->periodo = urlencode("$dInicio - $dVencimento");
    }
    $aDadosVacinas[$iCont]->passouinicio = $sPassouInicio;
 
  }

  $oRetorno->aDadosVacinas = $aDadosVacinas;

}

if ($oParam->exec == 'confirmarVacinasForaRede') {

  $oDaoVacAplica = db_utils::getdao('vac_aplica');

  db_inicio_transacao();

  if (count($oParam->aVacinasAlteracao) > 0) {

    for ($iCont = 0; $iCont < count($oParam->aVacinasAlteracao); $iCont++) {
      
      $oDaoVacAplica->vc16_i_codigo       = $oParam->aVacinasAlteracao[$iCont]->iId;
      $oDaoVacAplica->vc16_d_dataaplicada = $oParam->aVacinasAlteracao[$iCont]->dData;
      $oDaoVacAplica->vc16_t_obs          = $oParam->aVacinasAlteracao[$iCont]->sObs;
      $oDaoVacAplica->alterar($oParam->aVacinasAlteracao[$iCont]->iId);
      if ($oDaoVacAplica->erro_status == '0') {
        break;
      }

    }

  }

  if (count($oParam->aVacinasInclusao) > 0 && $oDaoVacAplica->erro_status != '0') {

    $oDaoVacAplica->vc16_i_cgs          = $oParam->iCgs;
    $oDaoVacAplica->vc16_n_quant        = 1;
    $oDaoVacAplica->vc16_d_data         = date('Y-m-d', db_getsession('DB_datausu'));
    $oDaoVacAplica->vc16_c_hora         = date('H:i');
    $oDaoVacAplica->vc16_i_usuario      = db_getsession('DB_id_usuario');
    $oDaoVacAplica->vc16_i_departamento = db_getsession('DB_coddepto');

    for ($iCont = 0; $iCont < count($oParam->aVacinasInclusao); $iCont++) {
      
      // = explode(' ## ', $oParam->aVacinasInclusao[$iCont]);
      $oDaoVacAplica->vc16_i_dosevacina   = $oParam->aVacinasInclusao[$iCont]->iDose;
      $oDaoVacAplica->vc16_d_dataaplicada = $oParam->aVacinasInclusao[$iCont]->dData;
      $oDaoVacAplica->vc16_t_obs          = $oParam->aVacinasInclusao[$iCont]->sObs;
      $oDaoVacAplica->incluir(null);
      if ($oDaoVacAplica->erro_status == '0') {
        break;
      }

    }

  }
  
  db_fim_transacao($oDaoVacAplica->erro_status == '0' ? true : false);

  if ($oDaoVacAplica->erro_status == '0') {

    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode($oDaoVacAplica->erro_msg);

  } else {
    $oRetorno->sMessage = urlencode($oDaoVacAplica->erro_msg);
  }
  
}

echo $oJson->encode($oRetorno);
?>