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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

define("MENSAGENS", "patrimonial.compras.com4_orcamentoprocessocompra.");

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->erro     = false;
$oRetorno->sMessage = '';

try {

  db_inicio_transacao();

  switch ($oParam->sExecutar) {

    case "getItensProcessoCompra":

      if (empty($oParam->iCodigoProcesso)) {
        throw new Exception( _M(MENSAGENS . "processo_nao_informado") );
      }

      if (empty($oParam->iCodigoOrcamento)) {
        throw new Exception( _M(MENSAGENS . "orcamento_nao_informado") );
      }

      $oDaoOrcamentoItem = new cl_pcorcamitemproc();
      $oDaoProcessoItem  = new cl_pcprocitem();

      $sSqlOrcamentoItem = $oDaoOrcamentoItem->sql_query( null,
                                                          null,
                                                          "distinct pc81_codprocitem",
                                                          "pc81_codprocitem",
                                                          "pc80_codproc= {$oParam->iCodigoProcesso} and pc22_codorc={$oParam->iCodigoOrcamento}" );
      $rsOrcamentoItens  = $oDaoOrcamentoItem->sql_record( $sSqlOrcamentoItem );

      $aItensSelecionados = array();
      if ($rsOrcamentoItens && $oDaoOrcamentoItem->numrows > 0) {

        for ($iRow = 0; $iRow < $oDaoOrcamentoItem->numrows; $iRow++) {
          $aItensSelecionados[] = db_utils::fieldsMemory($rsOrcamentoItens, $iRow)->pc81_codprocitem;
        }
      }

      $oRetorno->lSelectAll = empty($aItensSelecionados);

      $sSqlProcessoItem = $oDaoProcessoItem->sql_query_pcmater( null,
                                                                "distinct pc68_nome, pc01_codmater, m61_descr, pc11_quant, pc11_seq, pc81_codprocitem, pc01_descrmater, pc11_resum, "
                                                                . "exists( select *                                                                                               \n"
                                                                . "          from pcorcamitemproc                                                                                 \n"
                                                                . "               inner join pcorcamitem on pc31_orcamitem = pc22_orcamitem                                       \n"
                                                                . "         where pc31_pcprocitem = pc81_codprocitem and pc22_codorc <> {$oParam->iCodigoOrcamento}) as bloqueado \n",
                                                                "pc81_codprocitem",
                                                                " pc80_codproc={$oParam->iCodigoProcesso} and (e54_autori is null or (e54_autori is not null and e54_anulad is not null)) ");
      $rsProcessoItem   = $oDaoProcessoItem->sql_record( $sSqlProcessoItem );

      $aItens = array();
      if ($rsProcessoItem && $oDaoProcessoItem->numrows > 0) {

        for ($iRow = 0; $iRow < $oDaoProcessoItem->numrows; $iRow++ ) {
          $oItem = db_utils::fieldsMemory($rsProcessoItem, $iRow);

          $aItens[] = array(
              'codigo_item'        => $oItem->pc81_codprocitem,
              'sequencial'         => $oItem->pc11_seq,
              'codigo_material'    => $oItem->pc01_codmater,
              'descricao_material' => urlencode($oItem->pc01_descrmater),
              'unidade'            => urlencode($oItem->m61_descr),
              'quantidade'         => $oItem->pc11_quant,
              'resumo'             => urlencode($oItem->pc11_resum),
              'lote'               => urlencode($oItem->pc68_nome),
              'selecionado'        => in_array($oItem->pc81_codprocitem, $aItensSelecionados),
              'bloqueado'          => ($oItem->bloqueado == 't')
            );
        }
      }

      $oRetorno->aItens = $aItens;

      break;
  }

  db_fim_transacao(false);


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);
?>