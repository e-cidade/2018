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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson               = new services_json();
$oParametros         = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->erro      = false;
$oRetorno->sMensagem = '';

define("MENSAGENS", "tributario.meioambiente.amb1_empreendimentos.");

$oDaoResponsavelTecnico = db_utils::getDao("responsaveltecnico");
$oDaoCgm                = db_utils::getDao("cgm");

try {

  db_inicio_transacao();

  switch ($oParametros->sExecucao) {

    case 'getResponsavelTecnico':

      if (empty($oParametros->iEmpreendimento)) {
        throw new Exception(_M( MENSAGENS . 'codigo_empreendimento_obrigatorio'));
      }

      $sSql         = $oDaoResponsavelTecnico->sql_query_profissao($oParametros->iEmpreendimento);
      $oRecord      = $oDaoResponsavelTecnico->sql_record($sSql);

      $oResultado   = db_utils::getCollectionByRecord($oRecord);

      foreach ($oResultado as $oDados) {

        $sTitulacao = "NÃO INFORMADO";

        if (empty($oDados->rh70_descr) || trim($oDados->rh70_descr) == $sTitulacao) {

          if (!empty($oDados->z01_profis)) {
            $sTitulacao = trim($oDados->z01_profis);
          }
        } else {
          $sTitulacao = trim($oDados->rh70_descr);
        }

        $oDados->titulacao = utf8_encode($sTitulacao);

        unset($oDados->z01_profis);
        unset($oDados->rh70_descr);
      }

      $oRetorno->aResponsaveis = $oResultado;

    break;

    case 'setResponsavelTecnico':

      if (empty($oParametros->iCgmResponsavel)) {
        throw new Exception(_M( MENSAGENS . 'cgm_obrigatorio'));
      }

      if (empty($oParametros->iEmpreendimento)) {
        throw new Exception(_M( MENSAGENS . 'codigo_empreendimento_obrigatorio'));
      }

      $sWhere     = "    am07_empreendimento = {$oParametros->iEmpreendimento} ";
      $sWhere    .= "and am07_cgm            = {$oParametros->iCgmResponsavel}            ";
      $sSql       = $oDaoResponsavelTecnico->sql_query_file(null,'*',null, $sWhere);
      $oRecord    = $oDaoResponsavelTecnico->sql_record($sSql);
      $oResultado = db_utils::getCollectionByRecord($oRecord);

      if (!empty($oResultado)) {
        throw new Exception(urlencode( _M( MENSAGENS . 'erro_responsavel_inserido' ) ));
      }

      $oDaoResponsavelTecnico->am07_empreendimento = $oParametros->iEmpreendimento;
      $oDaoResponsavelTecnico->am07_cgm = $oParametros->iCgmResponsavel;
      $oDaoResponsavelTecnico->incluir(null);


      if ($oDaoResponsavelTecnico->erro_status == "0") {
        throw new Exception( _M( MENSAGENS . 'erro_incluir_responsavel_tecnico' ) );
      }

      if ($oDaoResponsavelTecnico->erro_status == "1") {
        $oRetorno->sMensagem = urlencode( _M( MENSAGENS . 'sucesso_incluir_responsavel_tecnico' ) );
      }

    break;

    case 'excluirResponsavelTecnico':

      if (empty($oParametros->iCodigoResponsavel)) {
        throw new Exception(_M( MENSAGENS . 'cgm_obrigatorio'));
      }

      $oDaoResponsavelTecnico->am07_sequencial = $oParametros->iCodigoResponsavel;
      $oDaoResponsavelTecnico->excluir($oParametros->iCodigoResponsavel);
      if ( $oDaoResponsavelTecnico->erro_status == 0 ) {
        throw new BusinessException( _M( MENSAGENS . 'erro_excluir_responsavel_tecnico' ) );
      }

      $oRetorno->sMensagem = urlencode(_M( MENSAGENS . 'sucesso_excluir_responsavel_tecnico' ));

    break;
  }

  db_fim_transacao(false);
} catch (Exception $eErro) {

  db_fim_transacao(true);
  $oRetorno->erro      = true;
  $oRetorno->sMensagem = $eErro->getMessage();
}

echo $oJson->encode($oRetorno);