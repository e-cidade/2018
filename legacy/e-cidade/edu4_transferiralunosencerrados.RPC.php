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

$oParam              = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMessage  = '';

$iEscola = db_getsession('DB_coddepto');
if ( !empty($oParam->iEscola) ) {
  $iEscola = $oParam->iEscola;
}

define('MSG_EDU4_TRANSFERIRALUNOSENCERRADOSRPC', 'educacao.escola.edu4_transferiralunosencerradosRPC.');

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "buscarEscolasFora":

      $aEscolas           = EscolaProcedenciaRepository::getTodasEscolasProcedencia();
      $oRetorno->aEscolas = array();
      foreach ($aEscolas as $oEscola) {

        $oDados          = new stdClass();
        $oDados->sEscola = $oEscola->getNome();
        $oDados->iEscola = $oEscola->getCodigo();

        $oRetorno->aEscolas[] = $oDados;
      }

      break;
    case 'buscarEscolasRede':

      $sWhere = "ed18_i_codigo <> {$iEscola}";

      $oDao = new cl_escola();
      $sSql = $oDao->sql_query_file(null, 'ed18_i_codigo, ed18_c_nome', 'ed18_c_nome', $sWhere);
      $rs   = db_query($sSql);

      if (!$rs) {
        throw new DBException( _M( MSG_EDU4_TRANSFERIRALUNOSENCERRADOSRPC . "erro_buscar_escolas_rede") );
      }

      $iLinhas            = pg_num_rows($rs);
      $oRetorno->aEscolas = array();
      for( $i = 0; $i < $iLinhas; $i++) {

        $oDadosEscola         = db_utils::fieldsMemory($rs, $i);
        $oDados               = new stdClass();
        $oDados->sEscola      = $oDadosEscola->ed18_c_nome;
        $oDados->iEscola      = $oDadosEscola->ed18_i_codigo;
        $oRetorno->aEscolas[] = $oDados;
      }

      break;

    case 'buscarAlunosDisponiveisParaTransferencia':

      $oRetorno->aAlunos = array();
      if ( empty($oParam->iEtapa) ) {
        throw new ParameterException( _M( MSG_EDU4_TRANSFERIRALUNOSENCERRADOSRPC . "informe_etapa") );
      }

      $oEscola = EscolaRepository::getEscolaByCodigo($iEscola);
      $oEtapa  = EtapaRepository::getEtapaByCodigo($oParam->iEtapa);

      $sWhere = " and  not exists ( select 1 from transferencialotematricula where ed138_matricula = ed60_i_codigo) ";
      $aMatriculas = MatriculaRepository::getTodasMatriculasEncerradasPorEtapa($oEscola, $oEtapa, $sWhere);
      foreach ($aMatriculas as $oMatricula) {

        $oDiario = $oMatricula->getDiarioDeClasse();

        $oAluno             = new stdClass();
        $oAluno->iMatricula = $oMatricula->getCodigo();
        $oAluno->sAluno     = $oMatricula->getAluno()->getNome();
        $oAluno->sSituacao  = 'APROVADO';


        if ( $oDiario->getResultadoFinal() == 'R' ) {
          $oAluno->sSituacao = 'REPROVADO';
        }

        if ( $oDiario->aprovadoComProgressaoParcial() ) {
          $oAluno->sSituacao = 'APROVADO (Progressão Parcial / Dependência)';
        }

        $oRetorno->aAlunos[] = $oAluno;
      }

      break;

    case 'salvar':

      if ( empty($oParam->iEscolaDestino) ) {
        throw new ParameterException( _M(MSG_EDU4_TRANSFERIRALUNOSENCERRADOSRPC . "informe_escola_destino") );
      }

      if ( empty($oParam->aMatriculas) ) {
        throw new ParameterException( _M(MSG_EDU4_TRANSFERIRALUNOSENCERRADOSRPC . "informe_alunos") );
      }

      $oUsuario       = UsuarioSistemaRepository::getPorCodigo(db_getsession('DB_id_usuario'));
      $oEscolaOrigem  = EscolaRepository::getEscolaByCodigo($iEscola);
      $oEscolaDestino = null;
      if ( $oParam->iTipoDestino == 2 ) {
        $oEscolaDestino = EscolaRepository::getEscolaByCodigo($oParam->iEscolaDestino);
      } else {
        $oEscolaDestino = EscolaProcedenciaRepository::getEscolaByCodigo($oParam->iEscolaDestino);
      }

      $oTransfLote = new TransferenciaLote();
      $oTransfLote->setEscolaOrigem($oEscolaOrigem);
      $oTransfLote->setUsuario($oUsuario);
      $oTransfLote->setEscolaDestino($oEscolaDestino);
      foreach ($oParam->aMatriculas as $iCodigoMatricula) {
        $oTransfLote->addMatricula( MatriculaRepository::getMatriculaByCodigo($iCodigoMatricula) );
      }

      $oTransfLote->salvar();

      $oRetorno->iTransferencia = $oTransfLote->getCodigo();
      $oRetorno->sMessage       = _M(MSG_EDU4_TRANSFERIRALUNOSENCERRADOSRPC . "transferencia_realizada");
      break;

    case 'anular':

      if ( empty($oParam->iTransferencia) ){
        throw new Exception(  _M(MSG_EDU4_TRANSFERIRALUNOSENCERRADOSRPC . "informe_transferencia") );
      }
      if ( empty($oParam->iMatricula) ) {
        throw new Exception(  _M(MSG_EDU4_TRANSFERIRALUNOSENCERRADOSRPC . "informe_aluno") );
      }

      $oTransfLote = new TransferenciaLote($oParam->iTransferencia);
      $oTransfLote->anularTranferenciaMatricula(MatriculaRepository::getMatriculaByCodigo($oParam->iMatricula));

      $oRetorno->sMessage = _M(MSG_EDU4_TRANSFERIRALUNOSENCERRADOSRPC . "transferencia_anulada");

      break;

    case 'buscarEmissor':

      $oRetorno->aEmissores = array();

      $oDaoEscolaDiretor  = new cl_escoladiretor();
      $sCamposDiretor     = " 'DIRETOR' as funcao, ";
      $sCamposDiretor    .= "         case when ed20_i_tiposervidor = 1 then ";
      $sCamposDiretor    .= "                 cgmrh.z01_nome ";
      $sCamposDiretor    .= "              else cgmcgm.z01_nome ";
      $sCamposDiretor    .= "         end as nome, ";
      $sCamposDiretor    .= " ed83_c_descr||' n°: '||ed05_c_numero::varchar as atolegal";
      $sWhereDiretor      = " ed254_i_escola = ".$iEscola." AND ed254_c_tipo = 'A' AND ed01_i_funcaoadmin = 2 ";
      $sSqlDiretor        = $oDaoEscolaDiretor->sql_query_resultadofinal("", $sCamposDiretor, "", $sWhereDiretor);

      $oDaoRechumanoAtiv  = new cl_rechumanoativ();
      $sCamposSec         = " DISTINCT ed01_c_descr as funcao, ";
      $sCamposSec        .= "         case when ed20_i_tiposervidor = 1 then ";
      $sCamposSec        .= "                 cgmrh.z01_nome ";
      $sCamposSec        .= "              else cgmcgm.z01_nome ";
      $sCamposSec        .= "         end as nome,";
      $sCamposSec        .= " ed83_c_descr||' n°: '||ed05_c_numero::varchar as atolegal";
      $sWhereSec          = " ed75_i_escola = ".$iEscola." AND ed01_i_funcaoadmin = 3 ";
      $sSqlSec            = $oDaoRechumanoAtiv->sql_query_resultadofinal("", $sCamposSec, "", $sWhereSec);

      $sSqlUnion          = $sSqlDiretor;
      $sSqlUnion         .= " UNION ";
      $sSqlUnion         .= $sSqlSec;
      $rsAssinatura       = db_query( $sSqlUnion );

      if ( !$rsAssinatura ) {
        throw new DBException( _M(MSG_EDU4_TRANSFERIRALUNOSENCERRADOSRPC . "erro_buscar_emissor"));
      }

      $iTotalEmissores = pg_num_rows($rsAssinatura);

      for( $iContador = 0; $iContador < $iTotalEmissores; $iContador++ ) {

        $oDadosEmissor = db_utils::fieldsMemory( $rsAssinatura, $iContador );
        $oRetorno->aEmissores[] = $oDadosEmissor;
      }
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