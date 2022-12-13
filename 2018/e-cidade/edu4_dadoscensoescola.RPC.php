<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlibwebseller.php");
require_once("model/webservices/ControleAcessoAluno.model.php");
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

$oJson          = new services_json();
$oParam         = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$iCodigoEscola  = db_getsession("DB_coddepto");
switch ($oParam->exec) {
  
  case 'getAvaliacaoEscola':
    try {
      
      db_inicio_transacao();
      $oRetorno->iCodigoAvaliacao = '';
      $oDaoAvaliacaoEscola        = db_utils::getDao("escoladadoscenso");
      $sWhere                     = "ed308_escola = {$iCodigoEscola}";
      $sSqlDadosAvaliacao         = $oDaoAvaliacaoEscola->sql_query_file(null, 
                                                                         "ed308_avaliacaogruporesposta", 
                                                                         null, 
                                                                         $sWhere
                                                                        );
      $rsDadosEscola              = $oDaoAvaliacaoEscola->sql_record($sSqlDadosAvaliacao);
      if ($oDaoAvaliacaoEscola->numrows > 0) {
        $oRetorno->iCodigoAvaliacao = db_utils::fieldsMemory($rsDadosEscola, 0)->ed308_avaliacaogruporesposta;
      } else {
        
        $oDaoAvaliacaoGrupoPergunta = db_utils::getDao("avaliacaogruporesposta");
        $oDaoAvaliacaoGrupoPergunta->db107_datalancamento = date("Y-m-d", db_getsession("DB_datausu"));
        $oDaoAvaliacaoGrupoPergunta->db107_hora           = db_hora();
        $oDaoAvaliacaoGrupoPergunta->db107_usuario        = db_getsession("DB_id_usuario");
        $oDaoAvaliacaoGrupoPergunta->incluir(null);
        if ($oDaoAvaliacaoGrupoPergunta->erro_status == 0) {
          throw new Exception($oDaoAvaliacaoGrupoPergunta->erro_msg);
        }
        $oDaoAvaliacaoEscola->ed308_avaliacaogruporesposta = $oDaoAvaliacaoGrupoPergunta->db107_sequencial;
        $oDaoAvaliacaoEscola->ed308_escola                 = $iCodigoEscola;
        $oDaoAvaliacaoEscola->incluir(null);
        if ($oDaoAvaliacaoEscola->erro_status == 0) {
          throw new Exception($oDaoAvaliacaoEscola->erro_msg);
        }
         $oRetorno->iCodigoAvaliacao = $oDaoAvaliacaoEscola->ed308_avaliacaogruporesposta;
      }
      db_fim_transacao(false);
    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;
    
    case 'getAvaliacaoRecursoHumano':
    
      $oRetorno->iCodigoAvaliacao = '';
      $oDaoAvaliacaoRecursoHumano = db_utils::getDao("rechumanodadoscenso");
      $sWhere                     = "ed309_rechumano = {$oParam->iRecursoHumano}";
      $sSqlDadosAvaliacao         = $oDaoAvaliacaoRecursoHumano->sql_query_file(null, 
                                                                         "ed309_avaliacaogruporesposta", 
                                                                         null, 
                                                                         $sWhere
                                                                        );
      $rsDadosRecursoHumano              = $oDaoAvaliacaoRecursoHumano->sql_record($sSqlDadosAvaliacao);
      if ($oDaoAvaliacaoRecursoHumano->numrows > 0) {
        $oRetorno->iCodigoAvaliacao = db_utils::fieldsMemory($rsDadosRecursoHumano, 0)->ed309_avaliacaogruporesposta;
      } else {
        
        $oDaoAvaliacaoGrupoPergunta = db_utils::getDao("avaliacaogruporesposta");
        $oDaoAvaliacaoGrupoPergunta->db107_datalancamento = date("Y-m-d", db_getsession("DB_datausu"));
        $oDaoAvaliacaoGrupoPergunta->db107_hora           = db_hora();
        $oDaoAvaliacaoGrupoPergunta->db107_usuario        = db_getsession("DB_id_usuario");
        $oDaoAvaliacaoGrupoPergunta->incluir(null);
        if ($oDaoAvaliacaoGrupoPergunta->erro_status == 0) {
          throw new Exception($oDaoAvaliacaoGrupoPergunta->erro_msg);
        }
        $oDaoAvaliacaoRecursoHumano->ed309_avaliacaogruporesposta = $oDaoAvaliacaoGrupoPergunta->db107_sequencial;
        $oDaoAvaliacaoRecursoHumano->ed309_rechumano              = $oParam->iRecursoHumano;
        $oDaoAvaliacaoRecursoHumano->incluir(null);
        if ($oDaoAvaliacaoRecursoHumano->erro_status == 0) {
          throw new Exception($oDaoAvaliacaoRecursoHumano->erro_msg);
        }
        $oRetorno->iCodigoAvaliacao = $oDaoAvaliacaoGrupoPergunta->db107_sequencial;
      }
     break;
}
echo $oJson->encode($oRetorno);
?>