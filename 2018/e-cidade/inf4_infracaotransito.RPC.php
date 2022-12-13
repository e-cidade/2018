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
require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));

use ECidade\Financeiro\Tesouraria\InfracaoTransito\ReceitaInfracao;
use ECidade\Financeiro\Tesouraria\InfracaoTransito\Repository\ReceitaInfracao as ReceitaInfracaoRepository;
use ECidade\Financeiro\Tesouraria\Repository\Receita as ReceitaRepository;

$oJson              = new services_json();
$oParam             = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->message  = '';
$oRetorno->erro     = false;

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case 'pesquisarInfracaoTransito':

      $oDaoInfracaoTransito = new cl_infracaotransito();
      $sSql = $oDaoInfracaoTransito->sql_query_file((int) $oParam->iSequencial);
      $rsResultado = db_query($sSql);

      if ($oDaoInfracaoTransito->erro_status == '0') {
        throw new DBException("Erro ao pesquisar as infrações de trânsito.");
      }

      $oRetorno->oInfracao = pg_fetch_object($rsResultado);

      break;

    case 'salvarInfracaoTransito':

      if (empty($oParam->codigo)) {

        throw new \ParameterException('O campo Código da infração é de preenchimento obrigatório.');
        return false;
      }

      if (empty($oParam->descricao)) {

        throw new \ParameterException('O campo Descrição é de preenchimento obrigatório.');
        return false;
      }

      if (empty($oParam->nivel)) {

        throw new \ParameterException('O campo Nível é de preenchimento obrigatório.');
        return false;
      }

      $oDaoInfracaoTransito   = new \cl_infracaotransito();
      $sWhereInfracaoTransito = "i05_codigo = '{$oParam->codigo}'";

      if ( !empty($oParam->sequencial) ) {
        $sWhereInfracaoTransito .= " AND i05_sequencial <> {$oParam->sequencial}";
      }

      $sSqlInfracaoTransito = $oDaoInfracaoTransito->sql_query_file(null, '1', null, $sWhereInfracaoTransito);
      $rsInfracaoTransito   = db_query($sSqlInfracaoTransito);

      if (!$rsInfracaoTransito) {
        throw new DBException("Erro ao verificar se código da infração informado já existe.");
      }

      if ( pg_num_rows($rsInfracaoTransito) > 0 ) {
        throw new BusinessException("Código da infração informado já existe.");
      }

      $oDaoInfracaoTransito->i05_sequencial = $oParam->sequencial;
      $oDaoInfracaoTransito->i05_codigo     = $oParam->codigo;
      $oDaoInfracaoTransito->i05_descricao  = $oParam->descricao;
      $oDaoInfracaoTransito->i05_nivel      = $oParam->nivel;

      if ($oDaoInfracaoTransito->i05_sequencial) {
        $oDaoInfracaoTransito->alterar($oDaoInfracaoTransito->i05_sequencial);
      } else {
        $oDaoInfracaoTransito->incluir(null);
      }

      if ($oDaoInfracaoTransito->erro_status == '0') {
        throw new Exception( "Erro ao incluir a Infração de trânsito.");
      }

      $oRetorno->message = "Infração salva com sucesso.";
      $oRetorno->iSequencial = $oDaoInfracaoTransito->i05_sequencial;
      break;

    case 'excluirInfracaoTransito':

      $oDaoInfracaoTransito = new \cl_infracaotransito();
      $oDaoInfracaoTransito->i05_sequencial = $oParam->sequencial;
      $oDaoInfracaoTransito->excluir($oParam->sequencial);

      if( $oDaoInfracaoTransito->erro_status == '0' ){
        throw new DBException("Erro ao excluir a infração de trânsito.");
      }

      $oRetorno->message = "Infração excluída com sucesso." ;
      break;

    case 'salvarReceitaInfracao':
      $oReceitaInfracaoRepository = ReceitaInfracaoRepository::getInstance();
      $oReceitaInfracao = new ReceitaInfracao();

      if(isset($oParam->sequencial) && !empty($oParam->sequencial)){
        $oReceitaInfracao->setId($oParam->sequencial);
      } else {
        $oReceitaInfracao->setExercicio(db_getsession("DB_anousu"));
      }

      $oReceitaInfracao->setReceitaPrincipal($oParam->receita_principal);
      $oReceitaInfracao->setReceitaDuplicidade($oParam->receita_duplicidade);
      $oReceitaInfracao->setNivel($oParam->nivel);
      $oReceitaInfracao->setConta($oParam->conta);

      $oReceitaInfracaoRepository->salvar($oReceitaInfracao);
      $oRetorno->message = "Configuração salva com sucesso.";
      break;

    case 'pesquisarReceitaInfracao' :

      $oReceitaInfracaoRepository = ReceitaInfracaoRepository::getInstance();
      $oReceitaRepository         = ReceitaRepository::getInstance();

      $aReceitaInfracao            = $oReceitaInfracaoRepository->getByAno($oParam->anousu);
      $oRetorno->receitas_infracao = array();

      foreach ($aReceitaInfracao as $oReceitaInfracao) {

        $oReceitaPrincipal   = $oReceitaRepository->getById($oReceitaInfracao->getReceitaPrincipal());
        $oReceitaDuplicidade = $oReceitaRepository->getById($oReceitaInfracao->getReceitaDuplicidade());
        $oContaTesouraria    = new \contaTesouraria($oReceitaInfracao->getConta());

        $oDadosReceita                     = new \stdClass;
        $oDadosReceita->codigo             = $oReceitaInfracao->getId();
        $oDadosReceita->nivel              = $oReceitaInfracao->getNivel();
        $oDadosReceita->receitaPrincipal   = array('k02_codigo' => $oReceitaPrincipal->getDadosReceita()->k02_codigo, 'k02_drecei' => $oReceitaPrincipal->getDadosReceita()->k02_drecei);
        $oDadosReceita->receitaDuplicidade = array('k02_codigo' => $oReceitaDuplicidade->getDadosReceita()->k02_codigo, 'k02_drecei' => $oReceitaDuplicidade->getDadosReceita()->k02_drecei);
        $oDadosReceita->conta              = $oContaTesouraria->getCodigoConta();
        $oDadosReceita->conta_descricao    = $oContaTesouraria->getDescricao();
        $oRetorno->receitas_infracao[]     = $oDadosReceita;
      }
      break;

    case 'verificaNivelReceitaInfracao':

      $oNiveiInfracao  = ReceitaInfracaoRepository::getInstance();
      $oNivel          = $oNiveiInfracao->verificaFaltantes($oParam->anousu);

      if(isset($oNivel->Conta)) {

        $oRetorno->Conta           = $oNivel->Conta;
        $oContaTesouraria          = new \contaTesouraria($oNivel->Conta);
        $oRetorno->ContaDescricao  = $oContaTesouraria->getDescricao();
      }

      if(isset($oNivel->Nivel)) {
        $oRetorno->Nivel           = $oNivel->Nivel;
      }
      break;
  }
  db_fim_transacao(false);
} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->erro  = true;
  $oRetorno->message = $oErro->getMessage();
}

echo JSON::create()->stringify($oRetorno);
