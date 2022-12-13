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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

use ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Importacao\Exercicio;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Importacao\Modelo;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\PCASP\Importacao\Importacao;

$oParam             = JSON::create()->parse( str_replace("\\","",$_POST["json"]) );
$oRetorno           = new stdClass();
$oRetorno->mensagem = '';
$oRetorno->erro     = false;

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "getExercicios";

      $aExercicios          = Exercicio::getExercicios();
      $oRetorno->exercicios = array();
			foreach ($aExercicios as $oExercicio) {

				$oStdExercicio = new stdClass();
				$oStdExercicio->ano = $oExercicio->getAno();
				$oStdExercicio->importado = $oExercicio->isImportado();

				$oRetorno->exercicios[] = $oStdExercicio;
			}

      break;

    case "getModelos";

      $oRetorno->modelos = array();

      if (empty($oParam->exercicio)) {
        throw new ParameterException("O campo Exercício é de preenchimento obrigatório.");
      }

      if (!intval($oParam->exercicio)) {
        throw new ParameterException("Valor incorreto para o campo exercício.");
      }

      $bImportado = Importacao::consultaExercicioImportado($oParam->exercicio);
      if($bImportado){
        throw new DBException("Plano de contas já foi atualizado para o exercício {$oParam->exercicio}.");
      }
      $aModelos = Modelo::getModelosByExercicio($oParam->exercicio);

      if (empty($aModelos)) {
        throw new DBException("Nenhum modelo encontrado para o exercício selecionado.");
      }

			foreach ($aModelos as $oModelo) {

				$oStdModelo          = new stdClass();
				$oStdModelo->id      = $oModelo->getId();
				$oStdModelo->nome    = $oModelo->getNome();
				$oRetorno->modelos[] = $oStdModelo;
			}

      break;

    case "getRecurso":

      $oImportacao       = new Importacao();
      $oRetorno->recurso = $oImportacao->consultaRecurso();
      break;

    case "importar":

      $oImportacao = new Importacao();
      $oData       = new DBDate(date('Y-m-d'));

      $oImportacao->setCodigoModelo(intval($oParam->modelo));
      $oImportacao->setData($oData);
      $oImportacao->salvar();

      $oRetorno->mensagem = "Atualização realizada com sucesso.";
      break;

  }

  db_fim_transacao(false);

} catch (Exception $e) {

  $oRetorno->erro     = true;
  $oRetorno->mensagem = $e->getMessage();
  db_fim_transacao(true);
}

$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo JSON::create()->stringify($oRetorno);