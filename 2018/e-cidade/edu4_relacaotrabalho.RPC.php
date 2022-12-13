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

$oJson                  = new services_json();
$oParam                 = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';
$iEscola                = db_getsession('DB_coddepto');

define('MSG_EDU4_RELACAOTRABALHORPC', "educacao.escola.edu4_relacaotrabalhoRPC.");

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "buscaRechumanoEscola":

      $iEscolaBuscar = $iEscola;
      if ( !empty( $oParam->iEscola ) ) {
        $iEscolaBuscar = $oParam->iEscola;
      }

      $oProfissional = ProfissionalEscolaRepository::getUltimoVinculoByRecHumanoEscola($oParam->iRecHumano, new Escola($iEscolaBuscar));

      $oRetorno->iVinculoEscola = $oProfissional->getCodigo();

      break;

    case 'buscaRelacoesTrabalho':

      $oDaoRelacao = new cl_relacaotrabalho();

      $aCampos = array(
        'ed23_i_codigo   as codigo',
        'ed24_i_codigo   as regime_codigo',
        'ed24_c_descr    as regime',
        'ed25_i_codigo   as area_codigo',
        'ed25_c_descr    as area',
        'ed12_i_codigo   as disciplina_codigo',
        'ed232_c_descr   as disciplina',
        'ed10_i_codigo   as ensino_codigo',
        'ed10_c_descr    as ensino',
        'ed128_codigo    as tipo_hora_codigo',
        'ed128_descricao as tipo_hora',
        'ed22_i_codigo   as funcao_codigo',
        'ed01_c_descr    as funcao'
      );

      $sWhere = "ed23_i_rechumanoescola = {$oParam->iVinculoEscola}";
      $sSql   = $oDaoRelacao->sql_query_relacaotrabalho(null, implode(', ', $aCampos), null, $sWhere);
      $rs     = db_query($sSql);

      if ( !$rs ) {
        throw new Exception( _M( MSG_EDU4_RELACAOTRABALHORPC . "erro_buscar_relacao_trabalho" ) );
      }

      $oRetorno->aRelacoes = db_utils::getCollectionByRecord($rs);
      break;

    case 'salvar':

      $oDaoRelacao = new cl_relacaotrabalho();
      if ( !empty($oParam->iCodigoRelacao) ) {

        $oDaoRelacao->ed23_i_codigo          = $oParam->iCodigoRelacao;
        $oDaoRelacao->ed23_i_rechumanoescola = $oParam->iVinculoEscola;
        $oDaoRelacao->ed23_i_numero          = null;
        $oDaoRelacao->ed23_i_regimetrabalho  = $oParam->iRegime;
        $oDaoRelacao->ed23_i_areatrabalho    = $oParam->iArea       == '' ? 'null' : $oParam->iArea;
        $oDaoRelacao->ed23_i_disciplina      = $oParam->iDisciplina == '' ? 'null' : $oParam->iDisciplina;
        $oDaoRelacao->ed23_tipohoratrabalho  = $oParam->iTipoHora;
        $oDaoRelacao->ed23_ativo             = 'true';
        $oDaoRelacao->alterar($oParam->iCodigoRelacao);

        if ($oDaoRelacao->erro_status == 0) {
          throw new Exception($oDaoRelacao->erro_msg);
        }

        salvarVinculoFuncaoComRelacao( $oParam->iCodigoRelacao, $oParam->iFuncao );
      } else {

          $oDaoRelacao->ed23_i_codigo          = null;
          $oDaoRelacao->ed23_i_rechumanoescola = $oParam->iVinculoEscola;
          $oDaoRelacao->ed23_i_numero          = null;
          $oDaoRelacao->ed23_i_regimetrabalho  = $oParam->iRegime;
          $oDaoRelacao->ed23_i_areatrabalho    = $oParam->iArea;
          $oDaoRelacao->ed23_tipohoratrabalho  = $oParam->iTipoHora;
          $oDaoRelacao->ed23_ativo             = 'true';

        if ( count($oParam->aDisciplinas) > 0 ) {

          foreach ($oParam->aDisciplinas as $iDisciplina) {

            $oDaoRelacao->ed23_i_disciplina = $iDisciplina;
            $oDaoRelacao->incluir(null);

            if ($oDaoRelacao->erro_status == 0) {
              throw new Exception($oDaoRelacao->erro_msg);
            }

            salvarVinculoFuncaoComRelacao( $oDaoRelacao->ed23_i_codigo, $oParam->iFuncao );
          }
        } else {

          $oDaoRelacao->incluir(null);
          salvarVinculoFuncaoComRelacao( $oDaoRelacao->ed23_i_codigo, $oParam->iFuncao );
        }

      }

      $oRetorno->sMessage = _M(MSG_EDU4_RELACAOTRABALHORPC . 'relacao_salva');
      break;

    case 'excluir':

      $oDaoFuncaoRelacao = new cl_rechumanorelacao();
      $oDaoFuncaoRelacao->excluir(null, " ed03_i_relacaotrabalho = {$oParam->iCodigoRelacao}");

      if ($oDaoFuncaoRelacao->erro_status == 0) {
        throw new Exception($oDaoFuncaoRelacao->erro_msg);
      }

      $oDaoRelacao = new cl_relacaotrabalho();
      $oDaoRelacao->excluir($oParam->iCodigoRelacao);
      if ($oDaoRelacao->erro_status == 0) {
        throw new Exception($oDaoRelacao->erro_msg);
      }

      $oRetorno->sMessage = _M(MSG_EDU4_RELACAOTRABALHORPC . 'relacao_excluida');
      break;
  }

  db_fim_transacao(false);


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = $eErro->getMessage();
}
$oRetorno->erro = $oRetorno->iStatus == 2;
echo JSON::create()->stringify($oRetorno);


function salvarVinculoFuncaoComRelacao( $iCodigoRelacao, $iFuncao ) {

  $oDaoFuncaoRelacao = new cl_rechumanorelacao();
  $oDaoFuncaoRelacao->excluir(null, " ed03_i_relacaotrabalho = {$iCodigoRelacao}");
  if ($oDaoFuncaoRelacao->erro_status == 0) {
    throw new Exception($oDaoFuncaoRelacao->erro_msg);
  }

  $oDaoFuncaoRelacao->ed03_i_codigo          = null;
  $oDaoFuncaoRelacao->ed03_i_rechumanoativ   = $iFuncao;
  $oDaoFuncaoRelacao->ed03_i_relacaotrabalho = $iCodigoRelacao;
  $oDaoFuncaoRelacao->incluir(null);

  if ($oDaoFuncaoRelacao->erro_status == 0) {
    throw new Exception($oDaoFuncaoRelacao->erro_msg);
  }

  return true;
}