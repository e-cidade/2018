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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson      = new Services_JSON();
$oParametro = $oJson->decode(str_replace("\\","",$_POST["json"]));
define('MENSAGEM_CONTROLEMATRICULAINICIAL_RPC', 'educacao.secretariaeducacao.sec4_controlematriculainicial_RPC.');

$oRetorno             = new stdClass();
$oRetorno->iStatus    = 1;
$oRetorno->sMensagem  = '';
$oRetorno->erro       = false;

try {
  
  $oDataSistema   = new DBDate( date( DBDate::DATA_EN, db_getsession('DB_datausu') ) );
  $oRetorno->iAno = $oDataSistema->getAno();

  db_inicio_transacao();

  switch ( $oParametro->sExecucao ) {

    /**
     * Busca os controles de matrículas iniciais já cadastrados
     */
    case 'buscarControleMatricula':
    
      $oDaoControleMatricula = new cl_controlematriculainicial();
      $sSqlControleMatricula = $oDaoControleMatricula->sql_query_file( null, '*', 'ed135_anofinal', null);
      $rsControleMatricula   = db_query( $sSqlControleMatricula );

      if ( !$rsControleMatricula ) {
        throw new DBException( _M(MENSAGEM_CONTROLEMATRICULAINICIAL_RPC . "erro_busca_controle_matriculas_iniciais") );
      }

      $oRetorno->aControlesMatriculas = array();
      $iTotalLinhas = pg_num_rows( $rsControleMatricula );

      for ( $iContador = 0; $iContador < $iTotalLinhas; $iContador++ ) {
        $oDadosControle = db_utils::fieldsMemory( $rsControleMatricula, $iContador );
        
        $oControle                  = new stdClass();
        $oControle->iCodigo         = $oDadosControle->ed135_sequencial;
        $oControle->iAnoInicial     = $oDadosControle->ed135_anoinicial;
        $oControle->iAnoFinal       = $oDadosControle->ed135_anofinal;
        $oControle->iQuantidadeDias = $oDadosControle->ed135_quantidadedias;

        // Controla se é o último registro para permitir a exclusão do mesmo na tela
        $oControle->lUltimoRegistro = ($iTotalLinhas -1) == $iContador ? true : false;

        // Atualiza o Ano de acordo com o último ano Final lançado, caso não possua Ano Final informado, retorna null
        $oRetorno->iAno = !empty($oControle->iAnoFinal) ? $oControle->iAnoFinal + 1 : null;


        $oRetorno->aControlesMatriculas[] = $oControle;
      }

    break;

    /**
     * Inclui e Altera os Controles de Matrículas
     */
    case 'salvarControleMatricula':

    if ( empty($oParametro->iAnoInicial) ) {
      throw new ParameterException( _M(MENSAGEM_CONTROLEMATRICULAINICIAL_RPC . "informe_ano_inicial") );
    }

    if ( empty($oParametro->iQuantidadeDias) ) {
      throw new ParameterException( _M(MENSAGEM_CONTROLEMATRICULAINICIAL_RPC . "informe_quantidade_dias") );
    }
    
    $oDaoControleMatricula = new cl_controlematriculainicial();

    // Se for uma inclusão, valida se não há nenhum controle de matrícula inicial em vigência
    if ( empty( $oParametro->iCodigo ) ) {

      $sSqlControle = $oDaoControleMatricula->sql_query_file( null, '*', 'ed135_anofinal', 'ed135_anofinal is null');
      $rsControle   = db_query($sSqlControle);

      if( !$rsControle ) {
        throw new DBException( _M(MENSAGEM_CONTROLEMATRICULAINICIAL_RPC . "erro_busca_controle_matriculas_iniciais") );
      }

      if( pg_num_rows($rsControle) > 0 ) {
        throw new BusinessException( _M(MENSAGEM_CONTROLEMATRICULAINICIAL_RPC . "existe_controle_em_vigencia") );
      }
    }

    $oDaoControleMatricula->ed135_sequencial     = $oParametro->iCodigo;
    $oDaoControleMatricula->ed135_anoinicial     = $oParametro->iAnoInicial;
    $oDaoControleMatricula->ed135_anofinal       = $oParametro->iAnoFinal;
    $oDaoControleMatricula->ed135_quantidadedias = $oParametro->iQuantidadeDias;

    if ( !empty($oParametro->iCodigo) ) {
      $oDaoControleMatricula->alterar( $oParametro->iCodigo );
    } else {
      $oDaoControleMatricula->incluir( $oParametro->iCodigo );
    }

    if ( $oDaoControleMatricula->erro_status == '0' ) {
      throw new DBException( _M(MENSAGEM_CONTROLEMATRICULAINICIAL_RPC . "erro_salvar_controle_matricula_inicial") );
    }

    $oRetorno->sMensagem = urlencode( _M(MENSAGEM_CONTROLEMATRICULAINICIAL_RPC . "salvo_controle_matricula_inicial") );

    break;

    case 'excluirControleMatricula':

      if ( empty( $oParametro->iCodigo ) ) {
        throw new ParameterException( _M(MENSAGEM_CONTROLEMATRICULAINICIAL_RPC . "codigo_controle_matricula_nao_informado") );        
      }

      $oDaoControleMatricula = new cl_controlematriculainicial();
      $oDaoControleMatricula->excluir($oParametro->iCodigo);

      if ( $oDaoControleMatricula->erro_status == '0' ) {
        throw new DBException( _M(MENSAGEM_CONTROLEMATRICULAINICIAL_RPC . "erro_excluir_controle_matricula") );        
      }
      
      $oRetorno->sMensagem = urlencode( _M(MENSAGEM_CONTROLEMATRICULAINICIAL_RPC . "excluido_controle_matricula") );

    break;
  }

  db_fim_transacao();
} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
  $oRetorno->erro      = true;
}

$oRetorno->erro = $oRetorno->iStatus == 2;
echo $oJson->encode($oRetorno);