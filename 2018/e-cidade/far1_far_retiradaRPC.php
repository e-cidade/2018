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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/requisicaoMaterial.model.php"));

$clcgs_und                  = new cl_cgs_und;
$clmatrequiitem             = new cl_matrequiitem;
$clatendrequi               = new cl_atendrequi;
$clmatrequi                 = new cl_matrequi;
$cldb_almox                 = new cl_db_almox;
$clfar_matersaude           = new cl_far_matersaude;
$clmatparam                 = new cl_matparam;
$clfar_retirada             = new cl_far_retirada;
$clmatestoque               = new cl_matestoque;
$clfar_controle             = new cl_far_controle;
$clfar_controlemed          = new cl_far_controlemed;
$clfar_retiradaitens        = new cl_far_retiradaitens;
$clfar_retiradaitemlote     = new cl_far_retiradaitemlote;
$clfar_retirada             = new cl_far_retirada;
$clfar_retiradarequi        = new cl_far_retiradarequi;
$clfar_retiradarequisitante = new cl_far_retiradarequisitante;
$clfar_requisitantecgs      = new cl_far_requisitantecgs;
$clfar_requisitanteoutro    = new cl_far_requisitanteoutro;
$clfar_parametros           = new cl_far_parametros;

$objJson             = new services_json();
$objParam            = $objJson->decode(str_replace("\\","",$_POST["json"]));
$objRetorno          = new stdClass();
$objRetorno->status  = 1;
$objRetorno->message = '';
$departamento        = db_getsession("DB_coddepto");
$hoje                = date("d/m/Y",db_getsession("DB_datausu"));
$hoje2               = date("Y-m-d",db_getsession("DB_datausu"));
$aHoje               = explode('-', $hoje2);
$tHoje               = mktime(0, 0 , 0, $aHoje[1], $aHoje[2], $aHoje[0]);
$descrdepto          = db_getsession("DB_nomedepto");
$login               = DB_getsession("DB_id_usuario");

$sSql                 = $clfar_parametros->sql_query2();
$rsFar_parametros     = $clfar_parametros->sql_record($sSql);
$oDadosFar_parametros = db_utils::fieldsmemory($rsFar_parametros, 0);

if ($objParam->exec == 'getAvisoRetirada') {

  $dData      = somardata($hoje, -($objParam->fa02_i_avisoretirada));
  $aData      = explode("/",$dData);
  $sSql       = $clfar_retirada->sql_query("",
                                      "fa04_i_codigo,fa04_d_data,fa04_i_unidades,descrdepto",
                                      "fa04_d_data desc",
                                      " fa04_i_cgsund = $objParam->fa04_i_cgsund ".
                                      " and fa04_d_data >= '$aData[2]-$aData[1]-$aData[0]' ");
  $rsRetirada = $clfar_retirada->sql_record($sSql);

  $aRetiradas = array();

  if ($clfar_retirada->numrows > 0) {

    for ( $iContador = 0; $iContador < $clfar_retirada->numrows; $iContador++ ) {
      
      $oRetirada = db_utils::fieldsmemory($rsRetirada, $iContador);
      $objRetorno->dData         = db_formatar($oRetirada->fa04_d_data,"d");
      $objRetorno->sDepartamento = utf8_encode($oRetirada->fa04_i_unidades." - ".$oRetirada->descrdepto);
      $aRetiradas[]              = $oRetirada->fa04_i_codigo;
    }

    $sRetiradas = implode(",", $aRetiradas);

    $sSql                      = $clfar_retiradaitens->sql_query("",
                                                                 "m60_descr",
                                                                 "",
                                                                 " fa06_i_retirada in ({$sRetiradas})");
    $sMedicamentos             = "";
    $sSep                      = "";
    $rsRetiradaitens           = $clfar_retiradaitens->sql_record($sSql);
    for ($iI=0; $iI < $clfar_retiradaitens->numrows; $iI++) {

      $oRetiradaitens  = db_utils::fieldsmemory($rsRetiradaitens,$iI);
      $sMedicamentos  .= $sSep.$oRetiradaitens->m60_descr;
      $sSep            = ", ";

    }
    $objRetorno->sMedicamentos  = utf8_encode($sMedicamentos);

  } else {
    $objRetorno->status = 2;
  }

}

if ($objParam->exec == 'ConsultaSaldo') {

  //Verificar aqui se o medicamento não é um excontinuado, se for carregar dados do respectivo tratamento e enviar
  //      valor que trocar o tipo no gird
  $sSubMovimentacao  = '';
  if ($oDadosFar_parametros->fa02_i_numdiasmedcontinativo > 0) {

    $strCampos          = " m60_codmater,";
    $strCampos         .= " m60_descr,";
    $strCampos         .= " fa10_d_datafim,";
    $strCampos         .= " fa10_i_prazo,";
    $strCampos         .= " fa10_i_quantidade,";
    $strCampos         .= " fa10_d_dataini,";
    $strCampos         .= " fa10_i_medicamento,";
    $strCampos         .= " fa10_i_margem, ";
    $strCampos         .= " case when fa01_i_medhiperdia = 1 then 'false' else 'true' end as hiperdia";
    $sSubMovimentacao   = '  select * from far_retiradaitens';
    $sSubMovimentacao  .= '    inner join far_retirada on far_retirada.fa04_i_codigo = far_retiradaitens.fa06_i_retirada';
    $sSubMovimentacao  .= '  where far_retiradaitens.fa06_i_matersaude = fa01_i_codigo';
    $sSubMovimentacao  .= '    and far_retirada.fa04_i_cgsund = z01_i_cgsund';
    $tDataInativo       = $tHoje - ($oDadosFar_parametros->fa02_i_numdiasmedcontinativo * 86400); // hoje - N dias
    $dDataInativo       = date('Y-m-d', $tDataInativo);
    $sSubMovimentacao1  = " exists ($sSubMovimentacao and far_retirada.fa04_d_data < '$dDataInativo' )  ";
    $sSubMovimentacao2  = " not exists ($sSubMovimentacao and far_retirada.fa04_d_data > '$dDataInativo' )  ";
    $sSubMovimentacao   = "    and ( $sSubMovimentacao1 and $sSubMovimentacao2 )";
    $strSQL             = $clfar_controlemed->sql_query("",
                                                        $strCampos,
                                                        "",
                                                        " fa11_i_cgsund=$objParam->fa04_i_cgsund and".
                                                        " fa10_i_medicamento=".$objParam->fa06_i_matersaude." and ".
                                                        " fa10_d_dataini<='".$hoje2."' and".
                                                        " (fa10_d_datafim>='".$hoje2."'".
                                                        " or fa10_d_datafim is null) $sSubMovimentacao");

    $rsContinuado = $clfar_controlemed->sql_record($strSQL);

  } else{
    $clfar_controlemed->numrows = 0;
  }

  if ($clfar_controlemed->numrows > 0) {

    $objRetorno->iExContinuado = 1;
    // [tag] reunir dodas as informações para exibir o continuado novamente
    //proxima data e saldo do tratamento

    $oContinuado = db_utils::fieldsmemory($rsContinuado, 0);

    $objRetorno->fa10_i_prazo      = $oContinuado->fa10_i_prazo;
    $objRetorno->fa10_i_margem     = $oContinuado->fa10_i_margem;
    $objRetorno->fa10_i_quantidade = $oContinuado->fa10_i_quantidade;

    if ((int)$oDadosFar_parametros->fa02_i_acumularsaldocontinuado == 1) {
      $lAcumularSaldo = 'true';
    } else {
      $lAcumularSaldo = 'false';
    }
    if ((int)$oDadosFar_parametros->fa02_i_tipoperiodocontinuado == 1) {
        $sql_retorno_func = "select fc_saldocontinuado_periodo_fixo";
    } else {
        $sql_retorno_func = "select fc_saldocontinuado_periodo_dinamico";
    }
    $sql_retorno_func        .= " ($objParam->fa04_i_cgsund,$objParam->fa06_i_matersaude,'$hoje2', $lAcumularSaldo)";
    $sql_retorno_func        .= " as retorno_func; ";
    $res_retorno_func         = db_query($sql_retorno_func);
    db_fieldsmemory($res_retorno_func,0);
    $retorno_func             = explode('#',$retorno_func);
    $retorno_func[1]          = $retorno_func[1] == "" ? "" : converte_data($retorno_func[1]);
    $objRetorno->prox_data    = $retorno_func[1];
    $objRetorno->saldo_atual  = $retorno_func[0] < 0 ? 0 : $retorno_func[0];

  } else {

    $objRetorno->iExContinuado = 0;
    $objRetorno->message       = "";

  }

  $sCampos     = "fa01_i_codmater,case when fa01_i_medhiperdia = 1 then 'false' else 'true' end as hiperdia";
  $sSql        = $clfar_matersaude->sql_query("",$sCampos,"","fa01_i_codigo=$objParam->fa06_i_matersaude");
  $resultmater = $clfar_matersaude->sql_record($sSql);
  db_fieldsmemory($resultmater,0);

  $sSubPontoPedido  = '(select m64_pontopedido from matmaterestoque where ';
  $sSubPontoPedido .= "m64_matmater = $fa01_i_codmater limit 1) as m64_pontopedido";
  $sSql             = $clmatestoque->sql_query(null,
                                               "m70_quant, $sSubPontoPedido",
                                               null,
                                               "m70_codmatmater=$fa01_i_codmater and m70_coddepto=$departamento");
  $result_matestoque=$clmatestoque->sql_record($sSql);
  if ($clmatestoque->numrows > 0) {

    db_fieldsmemory($result_matestoque,0);

    $objRetorno->quant_disp      = $m70_quant;
    $objRetorno->m64_pontopedido = (string)$m64_pontopedido;
    $objRetorno->hiperdia        = $hiperdia;

  } else {

    $objRetorno->quant_disp      = '0';
    $objRetorno->m64_pontopedido = '';
    $objRetorno->hiperdia        = 'false';

  }

  //Lote
  $sCampos     = " distinct m77_dtvalidade,";
  $sCampos    .= " m77_lote, ";
  $sCampos    .= " m71_codlanc ";
  $sWhere      = " fa01_i_codigo=$objParam->fa06_i_matersaude and m77_dtvalidade>'".$hoje2."' ";
  $sWhere     .= " and m70_coddepto = $departamento and m71_quantatend < m71_quant ";
  $sOrderby    = " m77_dtvalidade, m77_lote,m71_codlanc";
  $sSql        = $clfar_matersaude->sql_query_matmater("",$sCampos,$sOrderby,$sWhere);
  $result_lote = $clfar_matersaude->sql_record($sSql);
  if ($clfar_matersaude->numrows > 0) {

    db_fieldsmemory($result_lote,0);
    $label_lote = $m77_lote;
    $lote       = $m71_codlanc;
    $aVet       = explode("-",$m77_dtvalidade);
    $validade   = $aVet[2].'/'.$aVet[1].'/'.$aVet[0];

  } else {

    $lote     = "";
    $validade = "";
    $m77_lote = '';

  }
  $objRetorno->lote=$lote;
  $objRetorno->loteReal = $m77_lote;
  $objRetorno->validade=$validade;
}

if ($objParam->exec == 'getGridRemedioscont') {

  $strCampos  = " m60_codmater,";
  $strCampos .= " m60_descr,";
  $strCampos .= " fa10_d_datafim,";
  $strCampos .= " fa10_i_prazo,";
  $strCampos .= " fa10_i_quantidade,";
  $strCampos .= " fa10_d_dataini,";
  $strCampos .= " fa10_i_medicamento,";
  $strCampos .= " fa10_i_margem, ";
  $strCampos .= " fa11_t_obs, ";
  $strCampos .= " case when fa01_i_medhiperdia = 1 then 'false' else 'true' end as hiperdia";

  if (!isset($objParam->prescricao)) {
    $objParam->prescricao = -1;
  }


  /* Cálculo da data a partir da qual, se não foram ralizadas retiradas, o cadastro de continuados
    é dito sem movimentaçao (cancelado) */
  $sSubMovimentacao  = '';

  if ($oDadosFar_parametros->fa02_i_numdiasmedcontinativo > 0) {

    $sSubMovimentacao   = '  select * from far_retiradaitens';
    $sSubMovimentacao  .= '    inner join far_retirada on far_retirada.fa04_i_codigo = far_retiradaitens.fa06_i_retirada';
    $sSubMovimentacao  .= '      where far_retiradaitens.fa06_i_matersaude = fa01_i_codigo';
    $sSubMovimentacao  .= '        and far_retirada.fa04_i_cgsund = z01_i_cgsund';
    $tDataInativo       = $tHoje - ($oDadosFar_parametros->fa02_i_numdiasmedcontinativo * 86400); // hoje - N dias
    $dDataInativo       = date('Y-m-d', $tDataInativo);
    $sSubMovimentacao1  = " exists ($sSubMovimentacao and far_retirada.fa04_d_data > '$dDataInativo' )  ";
    $sSubMovimentacao2  = " not exists ($sSubMovimentacao and far_retirada.fa04_d_data < '$dDataInativo' )  ";
    $sSubMovimentacao   = " and ( $sSubMovimentacao1 or $sSubMovimentacao2 )";
//    die($sSubmovimentacao);

  }

  // Faz a busca por medicamentos continuados de acordo com a prescricao (receita),
  // O que valida ou nao a retirada dos medicamentos continuados

  if ($objParam->prescricao == 0) {

    $strSQL = $clfar_controlemed->sql_query("",
                                            $strCampos,
                                            "",
                                            " fa11_i_cgsund=$objParam->fa04_i_cgsund and".
                                            " fa10_d_dataini<='".$hoje2."' and".
                                            " (fa10_d_datafim>='".$hoje2."' or fa10_d_datafim is null) $sSubMovimentacao");

  } else {

    $strSQL = $clfar_controlemed->sql_query_tipo("",
                                                 $strCampos,
                                                 "",
                                                 " fa11_i_cgsund=$objParam->fa04_i_cgsund and".
                                                 " fa10_d_dataini<='".$hoje2."' and".
                                                 " (fa10_d_datafim>='".$hoje2."' or".
                                                 " fa10_d_datafim is null) and ".
                                                 " fa20_i_codigo=$objParam->prescricao $sSubMovimentacao ");

  }
  $res_controlemed    = $clfar_controlemed->sql_record($strSQL);
  $objRetorno->status = $clfar_controlemed->numrows == 0 ? 2 : 1;
  if ($objRetorno->status == 2 && $objParam->prescricao != 0) {  // Obtem todos os medicamentos continuados do paciente

    $strSQL = $clfar_controlemed->sql_query("",
                                            $strCampos,
                                            "",
                                            " fa11_i_cgsund=$objParam->fa04_i_cgsund and".
                                            " fa10_d_dataini<='".$hoje2."' and ".
                                            " (fa10_d_datafim>='".$hoje2."' or fa10_d_datafim is null) ");
    $res_controlemed= $clfar_controlemed->sql_record($strSQL);

  }

  if ($clfar_controlemed->numrows > 0) {

    $objRetorno->itens  = db_utils::getCollectionByRecord($res_controlemed, true, false, true);

    if ((int)$oDadosFar_parametros->fa02_i_acumularsaldocontinuado == 1) {
      $lAcumularSaldo = 'true';
    } else {
      $lAcumularSaldo = 'false';
    }

    for ($x = 0; $x < $clfar_controlemed->numrows; $x++) {

      db_fieldsmemory($res_controlemed,$x);
      $remedios[$x] = $fa10_i_medicamento;

      if ((int)$oDadosFar_parametros->fa02_i_tipoperiodocontinuado == 1) {
        $sql_retorno_func = "select fc_saldocontinuado_periodo_fixo";
      } else {
        $sql_retorno_func = "select fc_saldocontinuado_periodo_dinamico";
      }
      $sql_retorno_func .= " ($objParam->fa04_i_cgsund,$fa10_i_medicamento,'$hoje2', $lAcumularSaldo) as retorno_func;";
      $res_retorno_func  = db_query($sql_retorno_func);
      db_fieldsmemory($res_retorno_func,0);
      $retorno_func      = explode('#',$retorno_func);
      $retorno_func[1]   = $retorno_func[1] == "" ? "" : converte_data($retorno_func[1]);
      $objRetorno->itens[$x]->prox_data = $retorno_func[1];
      $saldo_atual       = $retorno_func[0] < 0 ? 0 : $retorno_func[0];
      $sSubPontoPedido   = '(select m64_pontopedido from matmaterestoque where ';
      $sSubPontoPedido  .= "m64_matmater = $m60_codmater limit 1) as m64_pontopedido";
      $sWhere            = "m70_codmatmater=$m60_codmater and m70_coddepto = $departamento";
      $sSql              = $clmatestoque->sql_query_file("","m70_quant, $sSubPontoPedido","",$sWhere);
      $result_saldo      = $clmatestoque->sql_record($sSql);
      if (pg_num_rows($result_saldo) > 0) {

        $objRetorno->itens[$x]->saldo_estoque   = pg_result($result_saldo,0,0);
        $objRetorno->itens[$x]->m64_pontopedido = (string)pg_result($result_saldo,0,1) ;

      } else {

        $objRetorno->itens[$x]->saldo_estoque   = 0;
        $objRetorno->itens[$x]->m64_pontopedido = '';

      }

      $objRetorno->itens[$x]->saldo  = (int)$saldo_atual;
      $objRetorno->itens[$x]->margem = $retorno_func[2];

      // Obtem informacoes de Lote
      $sSql = $clfar_matersaude->sql_query_matmater("",
                                                    "distinct m77_dtvalidade, m77_lote, m71_codlanc",
                                                    "m77_dtvalidade, m77_lote, m71_codlanc",
                                                    "fa01_i_codigo=$fa10_i_medicamento ".
                                                    "and m77_dtvalidade>'".$hoje2."'".
                                                    " and m70_coddepto=$departamento");
      $result_lote = $clfar_matersaude->sql_record($sSql);
      if ($clfar_matersaude->numrows > 0) {

        db_fieldsmemory($result_lote,0);
        $objRetorno->itens[$x]->lote       = $m71_codlanc;
        $objRetorno->itens[$x]->label_lote = $m77_lote;
        $aVet                              = explode("-",$m77_dtvalidade);
        $validade                          = $aVet[2].'/'.$aVet[1].'/'.$aVet[0];
        $objRetorno->itens[$x]->validade   = $validade;

      } else {

        $objRetorno->itens[$x]->lote       = "";
        $objRetorno->itens[$x]->validade   = "";
        $objRetorno->itens[$x]->label_lote = "";

      }
    }

  } else {
    $objRetorno->message = "Não ha remedios continuados para esse CGS.";
  }
}

/**
 * Confirma_Remedio
 */
if ($objParam->exec == 'Confirma_Remedio') {

  $cgs                  = $objParam->cgs;
  $sSql                 = $clfar_parametros->sql_query(null,' fa02_i_acaoprog ');
  $rsFar_parametros     = $clfar_parametros->sql_record($sSql);
  $oDadosFar_parametros = db_utils::fieldsmemory($rsFar_parametros, 0);

  db_inicio_transacao();

  //Carregadno dados do grid
  $remedio    = array();
  $nome       = array();
  $quant      = array();
  $posologia  = array();
  $lote       = array();
  $validade   = array();
  $continuado = array();
  $iTam       = count($objParam->aMedicamentos);

  for ($iX = 0; $iX < $iTam; $iX++) {

    $aItemMed    = explode("_|_",$objParam->aMedicamentos[$iX]);
    $remedio[]   = $aItemMed[1];
    $nome[]      = $aItemMed[2];
    $quant[]     = $aItemMed[11];
    $posologia[] = $aItemMed[12];

    if ($objParam->iTiporetirada == 1) {

      $lote[]      = $aItemMed[13];
      $validade[]  = $aItemMed[9];
    }


    if ($aItemMed[0] == 'A') {

      $continuado[] = true;
      $sSql         = $clfar_controle->sql_query("","*",""," fa11_i_cgsund = $cgs");
      $rsControlado = $clfar_controle->sql_record($sSql);

      if($clfar_controle->numrows == 0){

        $clfar_controle->fa11_i_cgsund = $cgs;
        if ($aItemMed[12 ] == '') {
          $aItemMed[12] = '.';
        }

        $clfar_controle->fa11_t_obs = $aItemMed[12];
        $clfar_controle->incluir(null);

        if ($clfar_controle->erro_status != "0") {
          $iCodContro = $clfar_controle->fa11_i_codigo;
        } else {

          $objRetorno->status  = 2;
          db_fim_transacao(true);
          $objRetorno->message = urlencode($clfar_controle->erro_msg);
          echo $objJson->encode($objRetorno);
          exit;
        }
      } else {

        $oContinuado = db_utils::fieldsmemory($rsControlado,0);
        $iCodContro  = $oContinuado->fa11_i_codigo;
      }

      $clfar_controlemed->fa10_d_datafim     = '';
      $clfar_controlemed->fa10_d_dataini     = $hoje2;
      $clfar_controlemed->fa10_i_controle    = $iCodContro;
      $clfar_controlemed->fa10_i_margem      = $aItemMed[5];
      $clfar_controlemed->fa10_i_medicamento = $aItemMed[1];
      $clfar_controlemed->fa10_i_prazo       = $aItemMed[4];
      $clfar_controlemed->fa10_i_programa    = $aItemMed[17];
      $clfar_controlemed->fa10_i_quantidade  = $aItemMed[6];
      $clfar_controlemed->incluir(null);

      if ($clfar_controlemed->erro_status == "0") {

        $objRetorno->status  = 2;
        $objRetorno->message = urlencode($clfar_controlemed->erro_msg);
        db_fim_transacao(true);
        echo $objJson->encode($objRetorno);
        exit;
      }
    } else {
      $continuado[] = false;
    }
  }

  /* Seto a variável de retorno que indica se o usuário clicou ou não no botão comprovante para
     efetuar a retirada, o que significa que ele deseja que o comprovante seja impresso após
     a confirmação da retirada */
  $objRetorno->lImprimirComprovante = $objParam->lImprimirComprovante;

  $sqlerro = "N";

  //Verifica se o departamento é um almoxarifado
  $sqlalmox = $cldb_almox->sql_query_file(null, "*", null, "m91_depto=$departamento");
  $resalmox = $cldb_almox->sql_record($sqlalmox);

  if ($cldb_almox->numrows > 0) {
    db_fieldsmemory($resalmox, 0);
  } else {

    $sqlerro = "S";
    $erro_msg= "Departamento $departamento não é um Almoxarifado!";
  }

  if ($sqlerro == "N") {

    if (!isset($objParam->data_receita)) {
      $objParam->data_receita = null;
    }

    $iNumeroReceita = null;
    if( isset( $objParam->lReceitaSistema ) && $objParam->lReceitaSistema ) {
      $iNumeroReceita = $objParam->numero_receita;
    }

    $clfar_retirada->fa04_d_dtvalidade      = $objParam->data_receita;
    $clfar_retirada->fa04_i_unidades        = $departamento;
    $clfar_retirada->fa04_i_cgsund          = $cgs;
    $clfar_retirada->fa04_i_tiporeceita     = $objParam->tipo_receita;
    $clfar_retirada->fa04_i_dbusuario       = $login;
    $clfar_retirada->fa04_d_data            = date('Y-m-d',db_getsession("DB_datausu"));
    $clfar_retirada->fa04_i_profissional    = $objParam->profissional;
    $clfar_retirada->fa04_d_dtvalidade      = $objParam->validade_receita;
    $clfar_retirada->fa04_c_numeroreceita   = $objParam->numero_receita;
    $clfar_retirada->fa04_i_receita         = $iNumeroReceita;
    $clfar_retirada->fa04_tiporetirada      = $objParam->iTiporetirada;
    $clfar_retirada->fa04_numeronotificacao = $objParam->iNumeronotificacao;
    $clfar_retirada->incluir(null);
    $objRetorno->fa04_i_codigo            = $clfar_retirada->fa04_i_codigo;

    if ($clfar_retirada->erro_status == '0') {

      $sqlerro = "S";
      $erro_msg=$clfar_retirada->erro_msg;
    }
  }

  /* Atualizo o campo s158_i_situacao da sau_receitamedica, pois já foi atendida */
  if (!empty($objParam->numero_receita) && $sqlerro == 'N') {

    $oDaoSauReceitaMedica                  = db_utils::getdao('sau_receitamedica');
    $oDaoSauReceitaMedica->s158_i_codigo   = $objParam->numero_receita;
    $oDaoSauReceitaMedica->s158_i_situacao = 2; // Atendida
    $oDaoSauReceitaMedica->alterar($objParam->numero_receita);

    if ($oDaoSauReceitaMedica->erro_status == '0') {

      $sqlerro  = 'S';
      $erro_msg = 'Ocorreu um erro ao atualizar a situação da receita médica para "Atendida"';
    }
  }

  if (isset($objParam->requi_cgs) && $sqlerro == 'N') {

    $clfar_retiradarequisitante->fa08_i_retirada  = $clfar_retirada->fa04_i_codigo;
    $clfar_retiradarequisitante->incluir(null);
    if ($clfar_retiradarequisitante->erro_status == "0") {

      $sqlerro  = "S";
      $erro_msg = $clfar_retiradarequisitante->erro_msg;
    } else {

      $clfar_requisitantecgs->fa38_i_requisitante = $clfar_retiradarequisitante->fa08_i_codigo;
      $clfar_requisitantecgs->fa38_i_cgs          = $objParam->requi_cgs;
      $clfar_requisitantecgs->incluir(null);

      if ($clfar_requisitantecgs->erro_status == '0') {

        $sqlerro  = "S";
        $erro_msg = $clfar_requisitantecgs->erro_msg;
      } else {

        $clcgs_und->z01_i_cgsund = $objParam->requi_cgs;
        $clcgs_und->z01_v_nome   = $objParam->requi_nome;
        $clcgs_und->z01_v_ender  = $objParam->requi_ender;
        $clcgs_und->z01_i_numero = $objParam->requi_numero;
        $clcgs_und->z01_v_ident  = $objParam->requi_ident;
        $clcgs_und->alterar($objParam->requi_cgs);

        if ($clcgs_und->erro_status == '0') {

          $sqlerro  = "S";
          $erro_msg = $clcgs_und->erro_msg;
        }
      }
    }
  } else {

    if (($sqlerro == "N") && (isset($objParam->requi_nome))) {

      $clfar_retiradarequisitante->fa08_i_retirada  = $clfar_retirada->fa04_i_codigo;
      $clfar_retiradarequisitante->incluir(null);

      if ($clfar_retiradarequisitante->erro_status == '0') {

        $sqlerro  = "S";
        $erro_msg = $clfar_retiradarequisitante->erro_msg;
      }

      if( ($sqlerro == "N")&&
          ($objParam->requi_nome !=null) &&
          ($objParam->requi_ender !=null) &&
          ($objParam->requi_numero != null) &&
          ($objParam->requi_ident != null)
        ) {

        $clfar_requisitanteoutro->fa39_i_requisitante  = $clfar_retiradarequisitante->fa08_i_codigo;
        $clfar_requisitanteoutro->fa39_c_nome          = $objParam->requi_nome;
        $clfar_requisitanteoutro->fa39_c_ender         = $objParam->requi_ender;
        $clfar_requisitanteoutro->fa39_i_numero        = $objParam->requi_numero;
        $clfar_requisitanteoutro->fa39_i_ident         = $objParam->requi_ident;
        $clfar_requisitanteoutro->incluir(null);

        if ($clfar_requisitanteoutro->erro_status == '0') {

          $sqlerro  = "S";
          $erro_msg = $clfar_requisitanteoutro->erro_msg;
        }
      }
    }
  }

  if ($sqlerro == "N" && $objParam->iTiporetirada == 1) {

    $clmatrequi->m40_data  = date("Y-m-d",db_getsession("DB_datausu"));
    $clmatrequi->m40_auto  = 't';
    $clmatrequi->m40_depto = $departamento;
    $clmatrequi->m40_login = $login;
    $clmatrequi->m40_almox = $m91_codigo;
    $clmatrequi->m40_hora  = db_hora();
    $clmatrequi->m40_obs   = "";
    $clmatrequi->incluir(null);

    if ($clmatrequi->erro_status == '0') {

      $sqlerro  = "S";
      $erro_msg = $clmatrequi->erro_msg;
    }

    $objRetorno->m40_codigo  = $clmatrequi->m40_codigo;
  }

  if ($sqlerro == "N" && $objParam->iTiporetirada == 1) {

    $clatendrequi->m42_login              = $login;
    $clatendrequi->m42_depto              = $departamento;
    $clatendrequi->m42_data               = date('Y-m-d',db_getsession("DB_datausu"));
    $clatendrequi->m42_hora               = db_hora();
    $clatendrequi->incluir(null);
    $clfar_retiradarequi->fa07_i_retirada = $clfar_retirada->fa04_i_codigo;
    $clfar_retiradarequi->fa07_i_matrequi = $clmatrequi->m40_codigo;
    $clfar_retiradarequi->incluir(null);
    $codigoatend                          = $clatendrequi->m42_codigo;

    if ($clatendrequi->erro_status == '0') {

      $sqlerro  = "S";
      $erro_msg = $clatendrequi->erro_msg;
    }
  }

  if ($sqlerro == "N") {

    //Percorre todos os itens da retirada
    for ($i = 0; $i < count($remedio); $i++) {

      if ($sqlerro == "N") {

        $clfar_retiradaitens->fa06_i_retirada   = $clfar_retirada->fa04_i_codigo;
        $clfar_retiradaitens->fa06_i_matersaude = $remedio[$i];
        $clfar_retiradaitens->fa06_f_quant      = $quant[$i];

        if ($continuado[$i] == true) {
          $clfar_retiradaitens->fa06_t_controlado = 'S';
        } else {
          $clfar_retiradaitens->fa06_t_controlado = 'N';
        }

        $clfar_retiradaitens->fa06_t_posologia  = $posologia[$i];
        $clfar_retiradaitens->incluir(null);

        if ($clfar_retiradaitens->erro_status == '0') {

          $sqlerro  = "S";
          $erro_msg = $clfar_retiradaitens->erro_msg;
        }
      }

      if ($sqlerro == "N" && $objParam->iTiporetirada == 1) {

        $clmatrequiitem->m41_codunid     = '1';
        $clmatrequiitem->m41_codmatrequi = $clmatrequi->m40_codigo;
        $clmatrequiitem->m41_codmatmater = convetremedio($remedio[$i]);
        $clmatrequiitem->m41_quant       = $quant[$i];
        $clmatrequiitem->m41_obs         = "";
        $clmatrequiitem->incluir(null);

        if ($clmatrequiitem->erro_status == '0') {

          $erro_msg = $clmatrequiitem->erro_msg;
          $sqlerro  = "S";
        } else {
          $codreqitem = $clmatrequiitem->m41_codigo;
        }
      }

      if ($sqlerro == "N" && $objParam->iTiporetirada == 1) {

        $quebra = explode(",",$lote[$i]);
        if ($lote[$i] != "") {

          for ($r = 0; $r < count($quebra); $r++) {

            $explode=explode("|",$quebra[$r]);
            if (count($explode) > 1) {

              $clfar_retiradaitemlote->fa09_i_retiradaitens  = $clfar_retiradaitens->fa06_i_codigo;
              $clfar_retiradaitemlote->fa09_i_matestoqueitem = $explode[0];
              $clfar_retiradaitemlote->fa09_f_quant          = $explode[1];
              $clfar_retiradaitemlote->incluir(null);
            } else {

              $clfar_retiradaitemlote->fa09_i_retiradaitens  = $clfar_retiradaitens->fa06_i_codigo;
              $clfar_retiradaitemlote->fa09_i_matestoqueitem = $lote[$i];
              $clfar_retiradaitemlote->fa09_f_quant          = $quant[$i];
              $clfar_retiradaitemlote->incluir(null);
            }
          }
          if ($clfar_retiradaitemlote->erro_status == '0') {

            $sqlerro  = "S";
            $erro_msg = $clfar_retiradaitemlote->erro_msg;

          }
        }
      }

      if ($objParam->iTiporetirada == 1) {

        $aSubItens[$i]              = new stdClass();
        $aSubItens[$i]->iCodMater   = convetremedio($remedio[$i]);
        $aSubItens[$i]->iCodItemReq = $codreqitem;
        $aSubItens[$i]->iCodalmox   = $departamento;
        $aSubItens[$i]->nQtde       = $quant[$i];
      }
    }

    // inclui a origem da receita:
    if ($sqlerro == "N" && isset($objParam->sOrigemReceita) && !empty($objParam->sOrigemReceita)) {

      $oDaofar_origemreceita         = db_utils::getdao('far_origemreceita');
      $oDaofar_origemreceitaretirada = db_utils::getdao('far_origemreceitaretirada');
      $sSqlOrig                      = $oDaofar_origemreceita->sql_query(null,
                                                  'fa40_i_codigo',
                                                  null,
                                                  ' fa40_c_descr = \''.strtoupper($objParam->sOrigemReceita).'\'');
      $rs                            = $oDaofar_origemreceita->sql_record($sSqlOrig);
      if ($oDaofar_origemreceita->numrows > 0) {

        $oOrig = db_utils::fieldsmemory($rs, 0);
        $iOrig = $oOrig->fa40_i_codigo;
      } else {

        $oDaofar_origemreceita->fa40_c_descr    = strtoupper($objParam->sOrigemReceita);
        $oDaofar_origemreceita->fa40_d_validade = null;
        $oDaofar_origemreceita->incluir(null);

        if ($oDaofar_origemreceita->erro_status == '0') {

          $sqlerro  = 'S';
          $erro_msg = $oDaofar_origemreceita->erro_msg;
        } else {
          $iOrig = $oDaofar_origemreceita->fa40_i_codigo;
        }
      }

      if ($sqlerro == 'N') {

        $oDaofar_origemreceitaretirada->fa41_i_retirada      = $clfar_retirada->fa04_i_codigo;
        $oDaofar_origemreceitaretirada->fa41_i_origemreceita = $iOrig;
        $oDaofar_origemreceitaretirada->incluir(null);

        if($oDaofar_origemreceitaretirada->erro_status == '0') {

          $sqlerro  = 'S';
          $erro_msg = $oDaofar_origemreceitaretirada->erro_msg;
        }
      }
    }

    if ($sqlerro == "N" && $objParam->iTiporetirada == 1) {
      try {

        $oRequisicao = new RequisicaoMaterial($clmatrequi->m40_codigo);
        $oRequisicao->atenderRequisicao(17, $aSubItens, $departamento,$clatendrequi->m42_codigo);
      } catch (Exception $eErro) {

        $sqlerro  = "S";
        $erro_msg = $eErro->getMessage();
      }
    }
  }

  db_fim_transacao(($sqlerro == "S"?true:false));
  if ($sqlerro == "S") {

    $objRetorno->status  = 2;
    $objRetorno->message = urlencode($erro_msg);
  } else {
    $objRetorno->iRetirada = $clfar_retirada->fa04_i_codigo;
  }
}//fim processo baixa

// Obtém o cartão sus a partir de um CGS
if ($objParam->exec == 'getCgsCns') {

  $oDaocgs_cartaosus = db_utils::getdao('cgs_cartaosus');

  $sSql = $oDaocgs_cartaosus->sql_query(null,
                                        'z01_i_cgsund, z01_v_nome',
                                        null,
                                        ' s115_c_cartaosus = \''.$objParam->iCns.'\'');
  $rsCgs_cartaosus = $oDaocgs_cartaosus->sql_record($sSql);

  if ($oDaocgs_cartaosus->numrows > 0) { // se encontrou o cgs

    $oDadosCgs_cartaosus      = db_utils::fieldsmemory($rsCgs_cartaosus, 0);
    $objRetorno->z01_i_cgsund = $oDadosCgs_cartaosus->z01_i_cgsund;
    $objRetorno->z01_v_nome   = urlencode($oDadosCgs_cartaosus->z01_v_nome);

  } else {

    $objRetorno->z01_i_cgsund = '';
    $objRetorno->z01_v_nome   = '';

  }

}

echo $objJson->encode($objRetorno);

 /*
 *
 *             FUNÇÕES
 *
 */

function esta_entre($data,$ini,$fim) {

  //calculo timestam das três datas
  $vet           = explode("/",$data);
  $timestampdata = mktime(0,0,0,$vet[1],$vet[0],$vet[2]);
  $vet           = explode("/",$ini);
  $timestampini  = mktime(0,0,0,$vet[1],$vet[0],$vet[2]);
  $vet           = explode("/",$fim);
  $timestampfim  = mktime(0,0,0,$vet[1],$vet[0],$vet[2]);
  $ok            = true;
  if ($timestampdata < $timestampini) {
    $ok = false;
  }
  if ($timestampdata > $timestampfim) {
    $ok = false;
  }
  return $ok;

}
function somardata ($data, $dias= 0, $meses = 0, $ano = 0) {

   $data      = explode("/", $data);
   $novadata  = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses,   $data[0] + $dias, $data[2] + $ano) );
   return     $novadata;

}
function convetremedio($remedio, $material = 0) {

  $clfar_matersaude = new cl_far_matersaude;
  if ($material != 0) {
    $sSql = $clfar_matersaude->sql_query_file("","fa01_i_codigo as resultado",""," fa01_i_codmater = $material ");
  } else {
    $sSql = $clfar_matersaude->sql_query_file("","fa01_i_codmater as resultado",""," fa01_i_codigo=$remedio ");

  }

  $result = $clfar_matersaude->sql_record($sSql);
  if ($clfar_matersaude->numrows > 0) {

   $resultado = pg_result($result,0,0);
   return       $resultado;

  }
}
?>