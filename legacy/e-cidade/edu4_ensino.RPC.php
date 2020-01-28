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
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");

define( "CAMINHO_MENSAGENS_ENSINO_RPC", "educacao.escola.edu4_ensino_RPC." );

$iEscola             = db_getsession( "DB_coddepto" );
$oJson               = new Services_JSON();
$oParam              = $oJson->decode( str_replace( "\\", "", $_POST["json"] ) );
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

try {

  switch( $oParam->sExecucao ) {
    
    /**
     * *********************************************************************************
     * Retorna todos os ensinos cadastrados
     * 
     * @return array aEnsinos - Array de stdClass com os ensinos cadastrados
     *         ..... stdClass
     *         .............. integer iEnsino   - Código do ensino
     *         .............. string  sEnsino   - Descrição do ensino
     *         .............. boolean lInfantil - Informa se o ensino é do tipo infantil
     * **********************************************************************************
     */
  	case 'buscaEnsinos':
  	  
  	  $oRetorno->aEnsinos = array();
  	  $aEnsino            = EnsinoRepository::getEnsinos();
  	  
  	  if ( count( $aEnsino ) == 0 ) {
  	    throw new BusinessException( _M( CAMINHO_MENSAGENS_ENSINO_RPC . "nenhum_ensino_encontrado" ) );
  	  }
  	  
  	  foreach( $aEnsino as $oEnsino ) {
  	    
  	    $oDadosEnsino            = new stdClass();
  	    $oDadosEnsino->iEnsino   = $oEnsino->getCodigo();
  	    $oDadosEnsino->sEnsino   = urlencode( $oEnsino->getNome() );
  	    $oDadosEnsino->lInfantil = $oEnsino->isInfantil();
  	    $oRetorno->aEnsinos[]    = $oDadosEnsino;
  	  }
  	  
  	  break;

    case 'salvarVinculoEnsinosInfantil':

      $aEnsinosVincular    = array();
      $aEnsinosDesvincular = array();

      $aEnsinosVinculados  = EnsinoRepository::getEnsinosInfantil();

      if ( count($aEnsinosVinculados) == 0) {
        $aEnsinosVincular = $oParam->aEnsinosInfantil;
      } else {

        $aCodigosEnsinosVinculados = array();

        // Cria um array com os codigos dos ensinos já vinculados
        foreach ($aEnsinosVinculados as $oEnsino) {
          $aCodigosEnsinosVinculados[] = $oEnsino->getCodigo();
        }

        // Percorro o array enviados do cliente verificando os que ainda não foram vinculados e adiciono no array para vincular
        foreach ($oParam->aEnsinosInfantil as $iCodigoEnsino) {
          
          if ( !in_array($iCodigoEnsino, $aCodigosEnsinosVinculados) ) {
            $aEnsinosVincular[] = $iCodigoEnsino;
          }
        }

        // Percorro o array de ensinos vinculados e comparo com o enviado pelo cliente verificando os que não constam e adiciono no array para desvincular
        foreach ($aCodigosEnsinosVinculados as $iCodigoEnsino) {
         
          if ( !in_array($iCodigoEnsino, $oParam->aEnsinosInfantil) ) {
            $aEnsinosDesvincular[] = $iCodigoEnsino;
          } 
        }
      }

      /**
       * Percorremos os arrays aEnsinosVincular e aEnsinosDesvincular persistindo na base
       */
      db_inicio_transacao();

      foreach ($aEnsinosVincular as $iCodigoEnsino) {

        $oEnsino = EnsinoRepository::getEnsinoByCodigo($iCodigoEnsino);
        $oEnsino->salvarVinculoInfantil();
      }

      foreach ($aEnsinosDesvincular as $iCodigoEnsino) {
        
        $oEnsino = EnsinoRepository::getEnsinoByCodigo($iCodigoEnsino);
        $oEnsino->removerVinculoInfantil();
      }

      $oRetorno->sMensagem = urlencode( _M( CAMINHO_MENSAGENS_ENSINO_RPC . "vinculo_salvo" ) );
      db_fim_transacao();      
      break;

    case 'isInfantil' :

      $oEnsino = EnsinoRepository::getEnsinoByCodigo($oParam->iEnsino);
      $oRetorno->lEnsinoInfantil = $oEnsino->isInfantil();

      break;
    
    case 'getDisciplinas' :
      
      $aWhere = array();
      
      if ( isset($oParam->iEnsino) && !empty($oParam->iEnsino) ) {
        $aWhere[] = " ed12_i_ensino = {$oParam->iEnsino} ";
      }
      
      if ( isset($oParam->iEscola) && !empty($oParam->iEscola) ) {
        $aWhere[] = " ed71_i_escola = {$oParam->iEscola} ";
      }
      
      $sCampos = " distinct ed12_i_codigo, ed12_i_ensino, ed232_c_descr, ed232_c_abrev, ed10_c_descr, ed10_c_abrev ";
      $sOrdem  = " ed12_i_ensino, ed232_c_descr ";
      $sWhere  = implode(" and", $aWhere);

      
      $oDaoDisciplinas = new cl_disciplina();
      $sSqlDisciplinas = $oDaoDisciplinas->sql_query_disciplinas_na_escola(null, $sCampos, $sOrdem, $sWhere);
      $rsDisciplinas   = db_query($sSqlDisciplinas);
      
      $aDisciplinas = array();
      
      if ( $rsDisciplinas && pg_num_rows($rsDisciplinas) > 0 ) {
        
        $iLinhas = pg_num_rows($rsDisciplinas);
        for ( $i  = 0; $i < $iLinhas; $i++) {
          
          $oDados                        = db_utils::fieldsMemory($rsDisciplinas, $i);
          $oDisciplina                   = new stdClass();
          $oDisciplina->iDisciplina      = $oDados->ed12_i_codigo;
          $oDisciplina->sDisciplina      = utf8_encode($oDados->ed232_c_descr);
          $oDisciplina->sDisciplinaAbrev = utf8_encode($oDados->ed232_c_abrev);
          $oDisciplina->iEnsino          = $oDados->ed12_i_ensino;
          $oDisciplina->sEnsino          = utf8_encode($oDados->ed10_c_descr);
          $oDisciplina->sEnsinoAbrev     = utf8_encode($oDados->ed10_c_abrev);
          $aDisciplinas[]                = $oDisciplina;
        }
      }
      $oRetorno->aDisciplinas = $aDisciplinas;
      break;
  }
} catch ( Exception $oErro ) {

  db_fim_transacao( true );
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode( $oErro->getMessage() );
}

echo $oJson->encode( $oRetorno );
?>