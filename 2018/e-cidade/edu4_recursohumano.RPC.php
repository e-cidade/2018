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
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlibwebseller.php"));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";
$oRetorno->erro    = false;

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oDaoRecursoHumanoNecessidade = db_utils::getDao("rechumanonecessidade");

try {

  switch ($oParam->exec) {

    case 'getNecessidadesEspeciais':

      $sCampos  = "ed48_i_codigo as codigo, ed48_c_descr as necessidade,";
      $sCampos .= " case when ed310_sequencial is null then false else true end as possui";

      $sWhere = "ed48_i_codigo < 108";

      $sSqlNecessidades        = $oDaoRecursoHumanoNecessidade->sql_query_necessidade($oParam->iRecursoHumano,
                                                                                      $sCampos,
                                                                                      $sWhere);
      $rsNecessidades          = $oDaoRecursoHumanoNecessidade->sql_record($sSqlNecessidades);
      $aNecessidades           = db_utils::getCollectionByRecord($rsNecessidades, false, false, true);
      $oRetorno->aNecessidades = $aNecessidades;
      break;

    case 'salvarNecessidadesEspeciais':

      /**
       * Deletamos todos os dados as necessidades especiais do rechumano.
       */
      db_inicio_transacao();

      $sWhere  = "ed310_rechumano = {$oParam->iRecursoHumano}";
      $oDaoRecursoHumanoNecessidade->excluir(null, $sWhere);
      if ($oDaoRecursoHumanoNecessidade->erro_status == 0) {
        throw new Exception("Erro ao excluir os dados");
      }
      foreach ($oParam->aNecessidadesEspeciais as $iNecessidade) {

        $oDaoRecursoHumanoNecessidade->ed310_rechumano   = $oParam->iRecursoHumano;
        $oDaoRecursoHumanoNecessidade->ed310_necessidade = $iNecessidade;
        $oDaoRecursoHumanoNecessidade->incluir(null);
        if ($oDaoRecursoHumanoNecessidade->erro_status == 0) {
          throw new Exception("Erro ao incluir necessidades especiais para o Recurso Humano.\n{$oDaoRecursoHumanoNecessidade->erro_msg}");
        }
      }
      db_fim_transacao(false);

      break;

    /**
     * Retorna um array com as atividades do profissional na escola
     */
    case 'atividadesProfissionalEscola':

      if( !isset( $oParam->iProfissionalEscola ) || empty( $oParam->iProfissionalEscola ) ) {
        throw new ParameterException( "Profissional não informado." );
      }

      $oRetorno->aAtividades = array();
      $oProfissionalEscola   = ProfissionalEscolaRepository::getByCodigo( $oParam->iProfissionalEscola );

      foreach( $oProfissionalEscola->getAtividades() as $oAtividadeProfissionalEscola ) {

        $oDadosAtividade             = new stdClass();
        $oDadosAtividade->iCodigo    = $oAtividadeProfissionalEscola->getAtividadeEscolar()->getCodigo();
        $oDadosAtividade->sDescricao = urlencode( $oAtividadeProfissionalEscola->getAtividadeEscolar()->getDescricao() );

        $oRetorno->aAtividades[] = $oDadosAtividade;
      }

      break;
  }
} catch ( Exception $oErro ) {

  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($eErro->getMessage());
  $oRetorno->erro    = true;

  db_fim_transacao(true);
}

echo $oJson->encode($oRetorno);