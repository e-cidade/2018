<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSelller Servicos de Informatica
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

require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson                           = new services_json(0,true);
$oParam                          = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno                        = new stdClass();
$oRetorno->status                = true;
$oRetorno->erro                  = false;
$oRetorno->message               = '';

define('MENSAGENS', 'recursoshumanos.pessoal.pes4_assentamento.');

try {

  switch ($oParam->exec) {

    case 'salvarTipoAssentamento':

      if (!is_array($oParam->aAssentamentos)) {
        throw new ParameterException("os assentamentos nao foram informados");
      }
      $oDaoControleDiasAssentamento = new cl_tipoassecontrolediasmes();
      db_inicio_transacao();
      $oDaoControleDiasAssentamento->excluir();
      if ($oDaoControleDiasAssentamento->erro_status == 0) {
        throw new BusinessException("Erro ao remover configurações do assentamento");
      }
      foreach ($oParam->aAssentamentos as $iCodigoAssentamento) {

        $oAssentamento = TipoAssentamentoRepository::getInstance()->getInstanciaPorTipo($iCodigoAssentamento);

        $oDaoControleDiasAssentamento->rh170_tipoasse = $oAssentamento->getSequencial();
        $oDaoControleDiasAssentamento->incluir(null);
        if ($oDaoControleDiasAssentamento->erro_status == 0) {
          throw new BusinessException("Erro ao configurar assentamento {$iCodigoAssentamento} para utilizar os dias dos meses para cálculo.");
        }
      }
      $oRetorno->message = "Assentamentos configurados com sucesso.";
      db_fim_transacao(false);
      break;

    case 'getTipoDeAssentamentosConfigurados' :

      $oDaoControleDiasAssentamento = new cl_tipoassecontrolediasmes();
      $sSqlAssentamentos            = $oDaoControleDiasAssentamento->sql_query(null, "h12_assent as codigo, h12_descr as descricao", "rh170_sequencial");
      $rsAssentamentos              = db_query($sSqlAssentamentos);
      if (!$rsAssentamentos) {
        throw new BusinessException("Erro ao pesquisar configurações do assentamento para controle do dia do mês.".pg_last_error());
      }
      $oRetorno->assentamentos = db_utils::getCollectionByRecord($rsAssentamentos, false, false, true);
      break;
  }

} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->erro    = true;
  $oRetorno->status  = false;
  $oRetorno->message = urlencode($oErro->getMessage());
}
echo $oJson->encode($oRetorno);