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

require_once ('libs/db_stdlib.php');
require_once ('libs/db_utils.php');
require_once ('libs/db_app.utils.php');
require_once ('libs/db_conecta.php');
require_once ('libs/db_sessoes.php');
require_once ('libs/JSON.php');
require_once ('dbforms/db_funcoes.php');

const MENSAGEM_HORARIOESCOLA_RPC = "educacao.escola.edu4_horariosescola_RPC.";

$oJson               = new Services_JSON();
$oParam              = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

try {

  db_inicio_transacao();

  switch( $oParam->sExecucao ) {

    /**
     * Case para buscar os horários de funcionamento da escola
     */
    case 'buscaHorariosEscola':

      $oRetorno->aHorariosEscola = array();

      $oDaoHorarioEscola   = new cl_horarioescola();
      $sWhereHorarioEscola = "ed123_escola = {$oParam->iEscola} ";
      $sOrderHorarioEscola = " ed123_turnoreferencia ";
      $sSqlHorarioEscola   = $oDaoHorarioEscola->sql_query_file( null, "*", $sOrderHorarioEscola, $sWhereHorarioEscola );
      $rsHorarioEscola     = db_query( $sSqlHorarioEscola );

      if ( !$rsHorarioEscola  ) {
        throw new DBException( _M(MENSAGEM_HORARIOESCOLA_RPC . "erro_buscar_horarios") );
      }

      $iLinhas = pg_num_rows($rsHorarioEscola);

      if ( $iLinhas > 0 ) {

        for( $iContador = 0; $iContador < $iLinhas; $iContador++ ) {

          $oDadosHorarioEscola         = db_utils::fieldsMemory( $rsHorarioEscola, $iContador );
          $oHorarioEscola              = new stdClass();
          $oHorarioEscola->iCodigo     = $oDadosHorarioEscola->ed123_sequencial;
          $oHorarioEscola->iTurno      = $oDadosHorarioEscola->ed123_turnoreferencia;
          $oHorarioEscola->iEscola     = $oDadosHorarioEscola->ed123_escola;
          $oHorarioEscola->sHoraInicio = $oDadosHorarioEscola->ed123_horainicio;
          $oHorarioEscola->sHorarioFim = $oDadosHorarioEscola->ed123_horafim;

          $oRetorno->aHorariosEscola[] = $oHorarioEscola;
        }
      }

      break;

    case 'salvaHorarioEscola':

      $oEscola = new Escola( $oParam->iEscola );

      $oHorarioEscola = new HorarioEscola($oParam->iHorarioEscola);
      $oHorarioEscola->setEscola( $oEscola );
      $oHorarioEscola->setTurno($oParam->iTurno);
      $oHorarioEscola->setHoraInicio($oParam->sHoraInicio);
      $oHorarioEscola->setHoraFinal($oParam->sHorarioFim);
      $oHorarioEscola->salvar();

      $oRetorno->sMensagem = urlencode( _M(MENSAGEM_HORARIOESCOLA_RPC . "salvo_sucesso") );
      break;

    case 'excluiHoraEscola':

      $oHorarioEscola = new HorarioEscola( $oParam->iHorarioEscola );
      $oHorarioEscola->remover();
      $oRetorno->sMensagem = urlencode( _M(MENSAGEM_HORARIOESCOLA_RPC . "excluido_sucesso") );
      break;
  }

  db_fim_transacao();

} catch ( Exception $oErro ) {

  db_fim_transacao( true );
  $oRetorno->iStatus = 2;
  $oRetorno->sMensagem = urlencode( $oErro->getMessage() );
}

echo $oJson->encode($oRetorno);