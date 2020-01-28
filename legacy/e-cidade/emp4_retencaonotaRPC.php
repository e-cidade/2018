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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("model/retencaoNota.model.php"));

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno = new stdClass();
if ($oParam->exec == "addRetencao") {

  try {

    $oRetencaoNota = new retencaoNota($oParam->params[0]->oRetencao->iCodNota);
    $oRetencaoNota->setInSession($oParam->params[0]->inSession);

    if (!$oRetencaoNota->validaValorRetencoes($oParam->params[0]->oRetencao)) {
      throw new Exception("Valor da retenção é maior que o valor da nota!");
    }

    $oRetencaoNota->addRetencao($oParam->params[0]->oRetencao, $oParam->params[0]->inSession, $oParam->params[0]->isUpdate);

    $oRetorno->aRetencoes = $oRetencaoNota->getRetencoes();
    $oRetorno->status     = 1;
    $oRetorno->message    = "";
    echo $oJson->encode($oRetorno);

  }

  catch (Exception $eErro) {
    echo $oJson->encode(array("status" => 2, "message"=> urlencode($eErro->getMessage())));
  }
} else if ($oParam->exec == 'apagarRetencao') {

  try {

    db_inicio_transacao();
    $oRetencaoNota = new retencaoNota($oParam->params[0]->iCodNota);
    $oRetencaoNota->setInSession(true);
    $oRetencaoNota->desativarRetencao($oParam->params[0]->iRetencao, @$oParam->params[0]->iNotaLiquidacao);
    $oRetorno->aRetencoes = $oRetencaoNota->getRetencoes();
    $oRetorno->status     = 1;
    $oRetorno->message    = "";
    db_fim_transacao(false);
    echo $oJson->encode($oRetorno);
  }
  catch (Exception $eErro) {

    db_fim_transacao(true);
    echo $oJson->encode(array("status" => 2, "message"=> urlencode($eErro->getMessage())));

  }

} else if ($oParam->exec == 'getRetencoes') {

  try {
    $oRetencaoNota = new retencaoNota($oParam->params[0]->iCodNota);
    if (isset($oParam->params[0]->iCodMov)) {
      $oRetencaoNota->setCodigoMovimento($oParam->params[0]->iCodMov);
    }
    $iAnoUsu = "";
    $iMesUsu = "";
    if ($oParam->params[0]->dtCalculo != "") {

      $aData   = explode("/", $oParam->params[0]->dtCalculo);
      $iMesUsu = $aData[1];
      $iAnoUsu = $aData[2];
    }
    $oRetencaoNota->getRetencoesFromDB($oParam->params[0]->iCodOrd, true, 2);
    $oRetencaoNota->setInSession(true);
    $oRetorno->aRetencoes = $oRetencaoNota->getRetencoes();
    $oRetorno->status     = 1;
    $oRetorno->message    = "";
    echo $oJson->encode($oRetorno);
  } catch (Exception $eErro) {
    echo $oJson->encode(array("status" => 2, "message"=> urlencode($eErro->getMessage())));
  }

} else if ($oParam->exec == "saveRetencoes") {

  try {

    db_inicio_transacao();

    $oRetencaoNota = new retencaoNota($oParam->params[0]->iCodNota);
    if ($oParam->params[0]->iCodMov != "") {
      $oRetencaoNota->setCodigoMovimento($oParam->params[0]->iCodMov);
    }
    $oRetencaoNota->setInSession(true);

    if (!$oRetencaoNota->validaValorRetencoes()) {
      throw new Exception("Valor da retenção é maior que o valor da nota!");
    }

    $oRetencaoNota->salvar($oParam->params[0]->iCodOrd, $oParam->params[0]->aMovimentos);
    $oRetencaoNota->unsetSession();
    db_fim_transacao(false);
    $lMesAnterior = $oRetencaoNota->hasRetencoesMesAnterior();
    echo $oJson->encode(array("status" => 1,
                              "message"=> urlencode("Retenções lançadas com sucesso"),
                              "lMesAnterior"=> $lMesAnterior)
    );

  }

  catch (Exception $eErro) {

    db_fim_transacao(true);
    echo $oJson->encode(array("status" => 2, "message"=> urlencode($eErro->getMessage())));

  }
} else if ($oParam->exec == "calculaRetencao") {

  require_once modification("model/calculoRetencao.model.php");

  /*
   * Calcula o valor da Retencao
   */
  try {

    $oCalculoretencao = new calculoRetencao($oParam->params[0]->iTipoCalc, $oParam->params[0]->iCpfCnpj);
    $oCalculoretencao->setAliquota($oParam->params[0]->nAliquota);
    $oCalculoretencao->setValorNota($oParam->params[0]->nValorNota);
    if (isset($oParam->params[0]->dtPagamento) && $oParam->params[0]->dtPagamento != "") {
      $oCalculoretencao->setDataBase($oParam->params[0]->dtPagamento);
    }
    if (count($oParam->params[0]->aMovimentos)> 0) {
      $oCalculoretencao->setCodigoMovimentos($oParam->params[0]->aMovimentos);
    }
    /*
     * Caso o usuario lancou uma retenção de IRRF, procuramos as retenções
     * de ISSQN, e somamos o valor retido no valor a deduzir da base de calculo.
     */
    if ($oParam->params[0]->iTipoCalc == 1 || $oParam->params[0]->iTipoCalc == 2) {

      $oRetencaoNota = new retencaoNota($oParam->params[0]->iCodNota);
      $oRetencaoNota->setInSession(true);
      $aRetencoes    = $oRetencaoNota->getRetencoes();
      foreach ($aRetencoes as $oRetencaoAtiva) {

        if ($oRetencaoAtiva->e21_retencaotipocalc == 3 || $oRetencaoAtiva->e21_retencaotipocalc == 7) {
          $oParam->params[0]->nValorDeducao += $oRetencaoAtiva->e23_valorretencao;
        }
      }
    }
    $oCalculoretencao->setDeducao($oParam->params[0]->nValorDeducao);
    $oCalculoretencao->setBaseCalculo($oParam->params[0]->nValorBase);
    $nValorRetencao = $oCalculoretencao->calcularRetencao();
    $nAliquota      = $oCalculoretencao->getAliquota();
    $nValorBase     = $oCalculoretencao->getValorBaseCalculo();
    echo $oJson->encode(array("status" => 1,
                              "message"=> "",
                              "nValorRetencao" => $nValorRetencao,
                              "nAliquota"      => $nAliquota,
                              "nValorBase"     => $nValorBase
                        )
    );

  }
  catch (Exception $eErro) {

    echo $oJson->encode(array("status" => 2, "message"=> urlencode($eErro->getMessage())));

  }
} else if ($oParam->exec == "getRetencoesMovimento") {

  $iMesUsu       = date("m", db_getsession("DB_datausu"));
  $iAnoUsu       = date("Y", db_getsession("DB_datausu"));
  $oRetencaoNota = new retencaoNota($oParam->iCodNota);
  $oRetorno->status = 1;
  $oRetorno->aRetencoes = $oRetencaoNota->getRetencoesByMovimento($oParam->iCodMov,null, true, true);
  if ($oRetorno->aRetencoes == false || (count($oRetorno->aRetencoes) == 0 ) ) {
    $oRetorno->status == 2;
  } else {
    $oDaoRetencaoCorGrupo = db_utils::getDao("retencaocorgrupocorrente");
    for ($i = 0 ; $i < count($oRetorno->aRetencoes); $i++) {

      $sWhere    = " e47_retencaoreceita = {$oRetorno->aRetencoes[$i]->e23_sequencial}";
      $sWhere   .= " and corrente.k12_estorn is false and k112_ativo is true ";
      $sOrder    = " corrente.k12_data,corrente.k12_autent desc limit 1";
      $sSqlSlip  = $oDaoRetencaoCorGrupo->sql_query_numpre_slip(null,
                                                                "slipcorrente.*",
                                                                $sOrder,
                                                                $sWhere
      );
      $rsSlips   = $oDaoRetencaoCorGrupo->sql_record($sSqlSlip);
      if ($oDaoRetencaoCorGrupo->numrows > 0 ) {
        $oRetorno->aRetencoes[$i]->k17_slip   = @db_utils::fieldsMemory($rsSlips, 0)->k112_slip;
      }
    }
  }
  echo $oJson->encode($oRetorno);

} else if ($oParam->exec == "getRecibosRetencao") {


  $oRetorno           = new stdClass;
  $oRetorno->status   = 1;
  $oRetorno->aRecibos = array();

  $sSqlRecibos  = "SELECT e21_descricao,";
  $sSqlRecibos .= "       e21_retencaotipocalc , ";
  $sSqlRecibos .= "       case when k12_numnov<> k12_numpre then k12_numnov else k12_numpre end as codarrecad, ";
  $sSqlRecibos .= "       case when k12_numnov<> k12_numpre then 1 else 2 end as tiporecibo, ";
  $sSqlRecibos .= "       k12_valor, ";
  $sSqlRecibos .= "       e23_valorbase, ";
  $sSqlRecibos .= "       e23_aliquota, ";
  $sSqlRecibos .= "       e21_receita, ";
  $sSqlRecibos .= "       k02_descr, ";
  $sSqlRecibos .= "       case when e49_numcgm is null then e60_numcgm else e49_numcgm end as numcgm, ";
  $sSqlRecibos .= "       case when e49_numcgm is null then cgm.z01_nome else cgmordem.z01_nome end as nome, ";
  $sSqlRecibos .= "      k12_data, ";
  $sSqlRecibos .= "      e20_pagordem, ";
  $sSqlRecibos .= "      e60_codemp||'/'||e60_anousu as empenho, ";
  $sSqlRecibos .= "      (case when k00_tipo is null then ";
  $sSqlRecibos .= "        (select k00_tipo from recibo where recibo.k00_numpre = k12_numpre) ";
  $sSqlRecibos .= "      else k00_tipo  ";
  $sSqlRecibos .= "      end ) as k00_tipo ";
  $sSqlRecibos .= " from retencaoreceitas  ";
  $sSqlRecibos .= "       inner join retencaopagordem         on e20_sequencial      = e23_retencaopagordem ";
  $sSqlRecibos .= "       inner join retencaocorgrupocorrente on e23_sequencial      = e47_retencaoreceita ";
  $sSqlRecibos .= "       inner join corgrupocorrente         on k105_sequencial     = e47_corgrupocorrente ";
  $sSqlRecibos .= "       inner join cornump                  on k12_data            = k105_data  ";
  $sSqlRecibos .= "                                          and k12_id              = k105_id ";
  $sSqlRecibos .= "                                          and k12_autent          = k105_autent ";
  $sSqlRecibos .= "      inner join pagordem                  on e20_pagordem        = e50_codord  ";
  $sSqlRecibos .= "      left  join pagordemconta             on e49_codord          = e50_codord  ";
  $sSqlRecibos .= "      left  join cgm cgmordem              on e49_numcgm          = cgmordem.z01_numcgm  ";
  $sSqlRecibos .= "      inner join empempenho                on e50_numemp          = e60_numemp  ";
  $sSqlRecibos .= "      inner join cgm                       on e60_numcgm          = cgm.z01_numcgm  ";
  $sSqlRecibos .= "      left join arrecant                   on k12_numpre          = k00_numpre ";
  $sSqlRecibos .= "      inner join retencaotiporec           on e23_retencaotiporec = e21_sequencial ";
  $sSqlRecibos .= "      inner join tabrec                    on e21_receita         = k02_codigo ";
  $sSqlRecibos .= " where e23_recolhido is true and e23_ativo is true  and  e21_instit = ".db_getsession("DB_instit");

  if ($oParam->iCodOrdem != "") {
    $sSqlRecibos .= "   and e20_pagordem  = {$oParam->iCodOrdem}";
  }
  if ($oParam->iNumCgm != "") {
    $sSqlRecibos .= "   and ( e60_numcgm  = {$oParam->iNumCgm} or e49_numcgm = {$oParam->iNumCgm}) ";
  }
  $rsRecibos    = db_query($sSqlRecibos);
  if ($rsRecibos) {
    $oRetorno->aRecibos = db_utils::getCollectionByRecord($rsRecibos,false,false,true);
  }
  echo $oJson->encode($oRetorno);
}  else if ($oParam->exec == "configurarRetencoes") {

  try {

    db_inicio_transacao();

    $oRetencaoNota = new retencaoNota($oParam->params[0]->iCodNota);
    $oRetencaoNota->setINotaLiquidacao($oParam->params[0]->iCodOrd);
    if ($oParam->params[0]->iCodMov != "") {
      $oRetencaoNota->setCodigoMovimento($oParam->params[0]->iCodMov);
    }
    $oRetencaoNota->setInSession(true);
    $oRetencaoNota->configurarPagamentoRetencoes();
    db_fim_transacao(false);
    echo $oJson->encode(array("status" => 1, "message"=> urlencode("Retenções Configuradas com sucesso")));

  }

  catch (Exception $eErro) {

    db_fim_transacao(true);
    echo $oJson->encode(array("status" => 2, "message"=> urlencode($eErro->getMessage())));

  }
}