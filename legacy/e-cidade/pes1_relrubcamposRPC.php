<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("std/DBDate.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_relrubrelrubcampos_classe.php");

db_app::import('exceptions.*');

$oJson    = new services_json();
$oRetorno = new stdClass();

$oParametros         = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

try {

  switch ($oParametros->acao) {

		/**
     * Salva campos do relatorio
     * - Incluir dados na tabela relrubrelrucampos 
     * - ligacao da relrub com relrubcampos
		 */
		case 'salvarCampos' :
      
      db_inicio_transacao();

      /**
       * Exclui todos os campos do relatorio antes de incluir os novos 
       */
      $oDaoRelrubrelrubcampos = new cl_relrubrelrubcampos();
      $oDaoRelrubrelrubcampos->excluir(null, 'rh121_relrub = ' .$oParametros->iRelatorio);

      /**
       * Erro ao excluir registros 
       */
      if ( $oDaoRelrubrelrubcampos->erro_status == "0" ) {
        throw new DBException($oDaoRelrubrelrubcampos->erro_msg);
      }

      /**
       * Percorre os campos selecionados e inclui vinculco do campo(relrubcampos) com relatorio(relrub) 
       */
      foreach ( $oParametros->aCampos as $iOrdem => $oCampo ) {

        $oDaoRelrubrelrubcampos = new cl_relrubrelrubcampos();
        $oDaoRelrubrelrubcampos->rh121_sequencial   = null;
        $oDaoRelrubrelrubcampos->rh121_instit       = db_getsession('DB_instit');
        $oDaoRelrubrelrubcampos->rh121_relrub       = $oParametros->iRelatorio;
        $oDaoRelrubrelrubcampos->rh121_relrubcampos = $oCampo->codigo_campo;
        $oDaoRelrubrelrubcampos->rh121_ordem        = $iOrdem + 1;
        $oDaoRelrubrelrubcampos->incluir(null);

        if ( $oDaoRelrubrelrubcampos->erro_status == "0" ) {
          throw new DBException($oDaoRelrubrelrubcampos->erro_msg);
        }
      }

      $oRetorno->sMensagem = 'Campo(s) alterado(s) com sucesso.';
      db_fim_transacao(false); 

		break;

    default:
      throw new ParameterException("Nenhuma Opção Definida");
    break;

  }

} catch (Exception $eErro){

  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = $eErro->getMessage();
  db_fim_transacao(true);
}

$oRetorno->sMensagem = urlEncode($oRetorno->sMensagem);

echo $oJson->encode($oRetorno);