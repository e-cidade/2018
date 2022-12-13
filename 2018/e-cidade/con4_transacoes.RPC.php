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

/**
 * Carregamos as bibliotecas necessárias para o funcionamento do programa
 */
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

/**
 * Instanciamos o objteto JSON e o que recebe as informações passadas pelopróprio JSON.
 * Também instsnciamos o objeto de retorno do processamento.
 */
$oJson  = new Services_JSON();
$oParam = $oJson->decode(str_replace("\\", "", $_POST['json']));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

/**
 * Recebe um número de reduzido e busca qual o estrutural da conplano que está vinculado
 * @param integer $iReduzido
 * @return string
 */
function getEstruturalFromReduzido($iReduzido) {

  $oDaoConplanoReduz     = db_utils::getDao('conplanoreduz');
  $iAnousu = db_getsession('DB_anousu');

  $sWhereBuscaEstrutural = " c61_reduz = {$iReduzido} and c61_anousu = {$iAnousu}";
  $sSqlBuscaEstrutural   = $oDaoConplanoReduz->sql_query(null, null, " conplano.c60_estrut, conplano.c60_descr ",
                                                         null, $sWhereBuscaEstrutural);
  $rsBuscaEstrutural     = $oDaoConplanoReduz->sql_record($sSqlBuscaEstrutural);
  if ($oDaoConplanoReduz->numrows > 0) {

    $oEstrutural = db_utils::fieldsMemory($rsBuscaEstrutural, 0);
    return array('c60_estrut' => (string)$oEstrutural->c60_estrut,
                 'c60_descr'  => $oEstrutural->c60_descr);
  }
  return array('c60_estrut' => "0",
               'c60_descr' => "Nenhuma conta informada");
}

/**
 * Recebe um código de documento e busca a descrição do mesmo na 'conhistdoc'
 * @param integer $iCodigoDocumento
 * @return string
 */
function getDescricaoDocumentoTransacao($iCodigoDocumento) {

  $oDaoConhistdoc = db_utils::getDao('conhistdoc');
  $sSqlBuscaDocumento = $oDaoConhistdoc->sql_query_file($iCodigoDocumento);
  $rsBuscaDocumento = $oDaoConhistdoc->sql_record($sSqlBuscaDocumento);
  if ($oDaoConhistdoc->numrows > 0) {

    $oDocumento = db_utils::fieldsMemory($rsBuscaDocumento, 0);
    return $oDocumento->c53_descr;
  }
  return "Descrição do documento não encontrada.";
}

switch ($oParam->exec) {

  /**
   * Criamos o arquivo xml com as informações das transações
   */
  case 'exportarTransacoes':

    /**
     * Instanciamos o objeto que manipula o XML e percorremos os dados jogando as informações no arquivo
     */
    $oXmlWriter = new XMLWriter();
    $oXmlWriter->openMemory();
    $oXmlWriter->setIndent(true);
    $oXmlWriter->startDocument('1.0', 'ISO-8859-1');
    $oXmlWriter->endDtd();
    $oXmlWriter->startElement('transacoes');

    /**
     * O ponto inicial é a pesquisa de todos os registros da contrans (transações) com o ano e intiuição da sessão
     */
    $oDaoContrans          = db_utils::getDao('contrans');
    $iAnoUsuLogado         = db_getsession("DB_anousu");
    $iInstituicaoLogada    = db_getsession("DB_instit");
    $sWhereBuscaTransacoes = " c45_anousu = {$iAnoUsuLogado} and c45_instit = {$iInstituicaoLogada} ";
    $sSqlBuscaTransacoes   = $oDaoContrans->sql_query_file(null, "*", null, $sWhereBuscaTransacoes);
    $rsBuscaTransacoes     = $oDaoContrans->sql_record($sSqlBuscaTransacoes);
    if ($oDaoContrans->numrows > 0) {

      for ($iRowTransacao = 0; $iRowTransacao < $oDaoContrans->numrows; $iRowTransacao++) {

        $oTransacao = db_utils::fieldsMemory($rsBuscaTransacoes, $iRowTransacao);
        $oXmlWriter->startElement('transacao');
        $oXmlWriter->writeAttribute('c45_seqtrans', $oTransacao->c45_seqtrans);
        $oXmlWriter->writeAttribute('c45_anousu', $oTransacao->c45_anousu);
        $oXmlWriter->writeAttribute('c45_coddoc', $oTransacao->c45_coddoc);
        $oXmlWriter->writeAttribute('c53_descr', utf8_encode(getDescricaoDocumentoTransacao($oTransacao->c45_coddoc)));
        $oXmlWriter->writeAttribute('c45_instit', $oTransacao->c45_instit);

        /**
         * Pesquisamos os lançamentos das transações encontradas na pesquisa anterior
         */
        $oDaoContranslan        = db_utils::getDao('contranslan');
        $sWhereBuscaLancamentos = " c46_seqtrans = {$oTransacao->c45_seqtrans} ";
        $sSqlBuscaLancamentos   = $oDaoContranslan->sql_query_file(null, "*", null, $sWhereBuscaLancamentos);
        $rsBuscaLancamentos     = $oDaoContranslan->sql_record($sSqlBuscaLancamentos);
        if ($oDaoContranslan->numrows > 0) {

          for ($iRowLancamento = 0; $iRowLancamento < $oDaoContranslan->numrows; $iRowLancamento++) {

            $oLancamento = db_utils::fieldsMemory($rsBuscaLancamentos, $iRowLancamento);
            $oXmlWriter->startElement('lancamento');
            $oXmlWriter->writeAttribute('c46_seqtranslan', $oLancamento->c46_seqtranslan);
            $oXmlWriter->writeAttribute('c46_seqtrans', $oLancamento->c46_seqtrans);
            $oXmlWriter->writeAttribute('c46_codhist', $oLancamento->c46_codhist);
            $oXmlWriter->writeAttribute('c46_obs', utf8_encode($oLancamento->c46_obs." "));
            $oXmlWriter->writeAttribute('c46_valor', utf8_encode($oLancamento->c46_valor." "));
            $oXmlWriter->writeAttribute('c46_obrigatorio', $oLancamento->c46_obrigatorio);
            $oXmlWriter->writeAttribute('c46_evento', $oLancamento->c46_evento);
            $oXmlWriter->writeAttribute('c46_descricao', utf8_encode($oLancamento->c46_descricao." "));
            $oXmlWriter->writeAttribute('c46_ordem', $oLancamento->c46_ordem);

            /**
             * Pesquisamos as contas dos lançamentos encontrados na pesquisa anterior
             */
            $oDaoContranslr    = db_utils::getDao('contranslr');
            $sWhereBuscaContas = " c47_seqtranslan = {$oLancamento->c46_seqtranslan} ";
            $sSqlBuscaContas   = $oDaoContranslr->sql_query_file(null, "*", null, $sWhereBuscaContas);
            $rsBuscaContas     = $oDaoContranslr->sql_record($sSqlBuscaContas);
            if ($oDaoContranslr->numrows > 0) {

              for ($iRowRegrasLancamento = 0; $iRowRegrasLancamento < $oDaoContranslr->numrows; $iRowRegrasLancamento++) {

                $oConta = db_utils::fieldsMemory($rsBuscaContas, $iRowRegrasLancamento);
                $oXmlWriter->startElement('conta');
                $oXmlWriter->writeAttribute('c47_seqtranslr', $oConta->c47_seqtranslr);
                $oXmlWriter->writeAttribute('c47_seqtranslan', $oConta->c47_seqtranslan);
                $aContaDebito = getEstruturalFromReduzido($oConta->c47_debito);
                $oXmlWriter->writeAttribute('c47_debito', utf8_encode($aContaDebito['c60_estrut']));
                $oXmlWriter->writeAttribute('c47_debito_descricao', utf8_encode($aContaDebito['c60_descr']));
                $aContaCredito = getEstruturalFromReduzido($oConta->c47_credito);
                $oXmlWriter->writeAttribute('c47_credito', utf8_encode($aContaCredito['c60_estrut']));
                $oXmlWriter->writeAttribute('c47_credito_descricao', utf8_encode($aContaCredito['c60_descr']));
                $oXmlWriter->writeAttribute('c47_obs', utf8_encode($oConta->c47_obs." "));
                $oXmlWriter->writeAttribute('c47_ref', utf8_encode($oConta->c47_ref." "));
                $oXmlWriter->writeAttribute('c47_anousu', $oConta->c47_anousu);
                $oXmlWriter->writeAttribute('c47_instit', $oConta->c47_instit);
                $oXmlWriter->writeAttribute('c47_compara', utf8_encode($oConta->c47_compara." "));
                $oXmlWriter->writeAttribute('c47_tiporesto', utf8_encode($oConta->c47_tiporesto." "));
                $oXmlWriter->endElement();
                unset($oConta);
              }
            }

            $oXmlWriter->endElement();
            unset($oLancamento);
          }
        }

        $oXmlWriter->endElement();
        unset($oTransacao);
      }
    } else {

      $oRetorno->status  = 2;
      $oRetorno->message = "Não foi encontrada nenhuma transação para o ano e intituição logados.\nFavor verificar.";
    }

    $oXmlWriter->endElement();
    $strBuffer = $oXmlWriter->outputMemory();
    /**
     * Salvamos o arquivo xml em tmp/transacoesexportadas.xml
     */
    $rsXML     = fopen('tmp/transacoesexportadas.xml', 'w');
    fputs($rsXML, $strBuffer);
    fclose($rsXML);
    $oRetorno->pathArquivo = 'tmp/transacoesexportadas.xml';
    break;
}

/**
 * Imprimimos o objeto de retorno para que o retorno do RPC possa avaliar o mesmo
 */
echo $oJson->encode($oRetorno);