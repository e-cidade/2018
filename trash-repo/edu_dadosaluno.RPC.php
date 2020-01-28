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
require_once("libs/db_conecta.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("std/DBDate.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_aluno_classe.php");
require_once("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once("model/educacao/avaliacao/iElementoAvaliacao.interface.php");

$oDaoAlunoTransporte  = db_utils::getDao("alunocensotipotransporte");
$oJson                = new Services_JSON();
$oParam               = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
switch ($oParam->exec) {

  case 'getTransportesAluno':

    $sCampos              = "ed312_sequencial as codigo, ed312_descricao as descricao, ";
    $sCampos             .= "case when ed311_sequencial is null then false else true end as possui";
    $sSqlDadosTransporte  = $oDaoAlunoTransporte->sql_query_transporte_aluno($oParam->iCodigoAluno, $sCampos);
    $rsDadosTransporte    = $oDaoAlunoTransporte->sql_record($sSqlDadosTransporte);
    $oRetorno->aTransportes = db_utils::getCollectionByRecord($rsDadosTransporte, false, false, true);

    break;
  case 'inserirTransporteAluno':

    try {

      db_inicio_transacao(false);
      $sWhereExclusao         = "ed311_aluno = {$oParam->iCodigoAluno}";
      $sSqlExcluirTransportes = $oDaoAlunoTransporte->excluir(null, $sWhereExclusao);
      if ($oDaoAlunoTransporte->erro_status == 0) {
        throw new Exception('Erro ao Salvar dados do transporte publico do aluno');
      }
      foreach ($oParam->aTransporte as $iCodigoTransporte) {

        $oDaoAlunoTransporte->ed311_aluno               = $oParam->iCodigoAluno;
        $oDaoAlunoTransporte->ed311_censotipotransporte = $iCodigoTransporte;
        $oDaoAlunoTransporte->incluir(null);
        if ($oDaoAlunoTransporte->erro_status == 0) {

          $sErroMensagem = "Erro ao Salvar dados do transporte publico do aluno.\n";
          $sErroMensagem .= $oDaoAlunoTransporte->erro_msg;
          throw new Exception($sErroMensagem);
        }
      }
      db_fim_transacao(false);
    } catch (Exception $eErro) {

      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
      db_fim_transacao(true);

    }

    break;

  case 'gradeAproveitamentoAluno':

    try {

      $oGradeAproveitamento           = new GradeAproveitamentoAluno(new Matricula($oParam->iMatricula));
      $oGradeAproveitamento->setUrlEncode(true);

      $oRetorno->aGradeAproveitamento = $oGradeAproveitamento->getGradeAproveitamento();
    } catch (ParameterException $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    } catch (BusinessException $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }

    break;

}
echo $oJson->encode($oRetorno);
?>