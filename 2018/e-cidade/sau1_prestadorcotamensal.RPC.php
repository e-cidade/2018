<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBSeller Servicos de Informatica
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

use ECidade\Saude\Agendamento\Exame\Cota\Mensal\Prestador\Repository;
use ECidade\Saude\Agendamento\Exame\Cota\Mensal\Prestador\Individual;
use ECidade\Saude\Agendamento\Exame\Cota\Mensal\Factory;

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));

$oJson               = JSON::create();
$oParametros         = $oJson->parse(str_replace("\\","",$_POST["json"]));
$oRetorno            = new \stdClass();
$oRetorno->erro      = false;
$oRetorno->sMensagem = '';
$oRepository         = new Repository();

try {

  db_inicio_transacao();

  switch ($oParametros->sExecucao) {

    case "salvar":

      foreach ($oParametros->aCompetencia as $iIndice => $oCompetencia) {

        $oCotaMensalFactory = new Factory();

        $oFactotyParametros = new \stdClass();
        $oFactotyParametros->iQuantidade = $oParametros->iQuantidade;
        $oFactotyParametros->iMes = $oCompetencia->mes;
        $oFactotyParametros->iAno = $oCompetencia->ano;
        $oFactotyParametros->sNome = $oParametros->sNome;

        $oCotaMensal = $oCotaMensalFactory->getCotaMensal($oParametros->iTipo, $oFactotyParametros);
        $oCotaMensal->setPrestadorExame($oParametros->prestadorVinculo);

        $oRepository->add($oCotaMensal);
      }
      break;

    case 'buscarCotas':

        $aCotasMensais    = $oRepository->getGrupoByPrestador($oParametros->iPrestador);
        $oRetorno->aCotas = array();

        foreach ($aCotasMensais as $oMensal) {

          $oCotaRetorno   = new \stdClass;
          $oInfoAdicional = $oMensal->getInformacaoAdicional();

          $oCotaRetorno->quantidade = $oMensal->getQuantidade();
          $oCotaRetorno->mes        = $oMensal->getMes();
          $oCotaRetorno->ano        = $oMensal->getAno();

          $oCotaRetorno->grupo      = $oInfoAdicional->iGrupo;
          $oCotaRetorno->nomegrupo  = $oMensal->getNome();

          $oRetorno->aCotas[] = $oCotaRetorno;
      }

      break;

    case 'excluir':

      $oCotaMensal = $oRepository->getCotaByIdGrupo($oParametros->iGrupo);
      $oRepository->remove($oCotaMensal);
      break;

    case 'validar':

      foreach ($oParametros->aCompetencia as $oCompetencia) {
        $lValidado = $oRepository->checkByCompetenciaPrestadorExame($oCompetencia->mes,
                                                                    $oCompetencia->ano,
                                                                    $oParametros->prestadorVinculo);

        if ( !$lValidado ) {
          throw new BusinessException("Já tem uma cota lançada para este(s) exame(s) na competência ".$oCompetencia->mes." / ".$oCompetencia->ano.".");
        }
      }

      break;

      /** todo: falta default */
  }

  db_fim_transacao(false);

} catch (\Exception $oErro){

  db_fim_transacao(true);
  $oRetorno->erro      = true;
  $oRetorno->sMensagem = $oErro->getMessage();

   /**
    Component AjaxRequest reconhece (message e erro)
    $oRetorno->message = $exception->getMessage();
    $oRetorno->erro = true;
   */
}

echo $oJson->stringify($oRetorno);
