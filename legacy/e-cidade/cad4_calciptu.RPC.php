<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson                = JSON::create();
$oParametros          = $oJson->parse(str_replace("\\","",$_POST["json"]));
$oRetorno             = new stdClass();
$oRetorno->erro       = false;
$oRetorno->lQuitado   = false;
$oRetorno->lIrregular = false;
$oRetorno->sMensagem  = '';

/**
 * Constante com o c�digo de retorno da verificacalculo, quando o IPTU est� quitado
 * @todo Garantir que este c�digo seja o mesmo retornado pela PL e do c�lculo geral
 */
define("IPTU_QUITADO", 27);

try{

  switch ($oParametros->sExecucao) {

    case 'verificaPagamentos':

      $sSql     = "select fc_iptu_verificacalculo($oParametros->iMatricula::integer,$oParametros->iAnousu::integer, 0, 0)";
      $rsResult = db_query($sSql);

      if ( empty($rsResult) ) {
        throw new DBException("Erro ao verificar pagamentos.");
      }

      $sRetorno       = pg_result($rsResult,0,0);
      $iCodigoRetorno = substr($sRetorno,3,2);

      if ( $iCodigoRetorno == IPTU_QUITADO ) {
        $oRetorno->lQuitado = true;
      }

      break;
    case 'verificaParametros':

      $oCfIptu = new cl_cfiptu();

      /**
       * Validamos a receita de cr�dio nos par�metros
       */
      $sSqlReceita = $oCfIptu->verificaReceitaCreditoRecalculo($oParametros->iAnousu);
      $rsReceita   = $oCfIptu->sql_record($sSqlReceita);

      if ( empty($rsReceita) ) {

        $oRetorno->lIrregular = true;
        $oRetorno->sMensagem  = "Receita de Cr�dito para o rec�lculo n�o est� configurada nos par�metros de IPTU.";
        break;
      }

      /**
       * Validamos o tipo de d�bito nos par�metros
       */
      $sSqlDebito = $oCfIptu->verificaTipoDebitoRecalculo($oParametros->iAnousu);
      $rsDebito   = $oCfIptu->sql_record($sSqlDebito);

      if ( empty($rsDebito) ) {

        $oRetorno->lIrregular = true;
        $oRetorno->sMensagem  = "Tipo de D�bito para o rec�lculo n�o est� configurada nos par�metros de IPTU.";
        break;
      }

      /**
       * Validamos a proced�ncia do tipo de d�bito
       */
      $sSqlProcedencia = $oCfIptu->verificaProcedenciaDebitoRecalculo($oParametros->iAnousu);
      $rsProcedencia   = $oCfIptu->sql_record($sSqlProcedencia);

      if ( empty($rsProcedencia) ) {

        $oRetorno->lIrregular = true;
        $oRetorno->sMensagem  = "Tipo de D�bito configurado para o rec�lculo n�o tem proced�ncia v�lida vinculada.";
        break;
      }

      break;
  }

} catch ( Exception $oErro ) {

  db_fim_transacao(true);
  $oRetorno->erro      = true;
  $oRetorno->sMensagem = $oErro->getMessage();
}

echo $oJson->stringify($oRetorno);
