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

require("libs/db_stdlib.php");
require("std/DBLargeObject.php");
require("libs/db_app.utils.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");

db_app::import("educacao.CarteiraIdentificacao");
db_app::import("educacao.LoteCartaoIdentificacao");
db_app::import("educacao.Aluno");
db_app::import("educacao.CarteiraIdentificacaoSituacao");

include("libs/JSON.php");

$oJson       = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oRetorno->iEscola = db_getsession("DB_coddepto");
$iModuloEscola     = 1100747;
switch ($oParam->exec) {

  case 'getEscolas':

    $sWhere      = "ed52_i_ano = ".db_getsession("DB_anousu");
      
    $oDaoEscola  = db_utils::getDao("calendarioescola");
    /**
     * Caso o modulo seja o modulo escola(1100747) traz apenas a escola logada
     */ 
    if (db_getsession("DB_modulo") == $iModuloEscola) {
      $sWhere .= " and ed18_i_codigo = {$oRetorno->iEscola} "; 
    }
    $sSqlEscolas = $oDaoEscola->sql_query(null, 
                                          "distinct ed18_i_codigo as escola,
                                          ed18_c_nome as nome",
                                          "ed18_i_codigo",
                                          $sWhere
                                         );
                                         
     $rsEscolas  = $oDaoEscola->sql_record($sSqlEscolas);                                            
     $oRetorno->aEscolas = db_utils::getCollectionByRecord($rsEscolas, false, false, true);
   break;
    
  case 'exportarAlunosArquivo':
        
    try {
      
      $sListaEscolas          = implode(",", $oParam->aEscolas);
      $sSituacoes             = "'MATRICULADO'";
      $sWhere                 = "    calendario.ed52_i_ano = ".db_getsession("DB_anousu");
      $sWhere                .= " and matricula.ed60_c_situacao  in ({$sSituacoes}) ";    
      $sWhere                .= " and turma.ed57_i_escola    in ({$sListaEscolas})";
      $sWhere                .= " and (ed47_o_oid is not null and ed47_o_oid <> 0)";
      $sWhere                .= " and not exists(select 1 ";
      $sWhere                .= "                  from loteimpressaocartaoidentificacaoaluno ";
      $sWhere                .= "                       inner join cartaoidentificacaosituacao on ed306_cartaoidentificacaosituacao = ed307_sequencial";
      $sWhere                .= "                 where ed306_aluno = matricula.ed60_i_aluno";
      $sWhere                .= "                   and (ed307_ativo is true or ed307_sequencial = 1))";
      $oDaoMatricula          = db_utils::getDao("matricula");    
      $sCampos                = "ed47_i_codigo";
      $sSqlAlunosMatriculados = $oDaoMatricula->sql_query(null, $sCampos, "turma.ed57_c_descr, ed60_i_numaluno, ed47_v_nome", $sWhere);
      
      $rsAlunos               = $oDaoMatricula->sql_record($sSqlAlunosMatriculados);
      $iTotalAlunos           = $oDaoMatricula->numrows ;  
      if ($iTotalAlunos == 0) {
        throw new Exception('Sem alunos disponíveis para a geração dos cartões de identificação.');
      }
      $oLoteCartoes  = new LoteCartaoIdentificacao();
      for ($iAluno = 0; $iAluno < $oDaoMatricula->numrows; $iAluno++) {
        
        $iCodigoAluno         = db_utils::fieldsMemory($rsAlunos, $iAluno)->ed47_i_codigo;
        $oCartaoIdentificacao = new CarteiraIdentificacao(new Aluno($iCodigoAluno));
        $oCartaoIdentificacao->setSituacao(new CarteiraIdentificacaoSituacao(1));
        $oLoteCartoes->adicionarCarteira($oCartaoIdentificacao); 
      }
      db_inicio_transacao();
      $oLoteCartoes->salvar();
      $sCaminhoArquivo = $oLoteCartoes->gerarArquivo();
      db_fim_transacao(false);
      $oRetorno->arquivo = $sCaminhoArquivo;      
    } catch (Exception $eErro) {

      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
      db_fim_transacao(true);
    }
    break;
    
  case 'getCartoesAlunos':  
    
    $oDaoLoteCartao = db_utils::getDao("loteimpressaocartaoidentificacao");
    $aWhere = Array();
    if (isset($oParam->iLote) && $oParam->iLote != "") {
      $aWhere[] = "ed305_sequencial = {$oParam->iLote}";
    }
    
    if (isset($oParam->iTurma) && $oParam->iTurma != "") {
      $aWhere[] = "ed57_i_codigo = {$oParam->iTurma}";
    }
    
    if (isset($oParam->iAluno) && $oParam->iAluno != "") {
      $aWhere[] = "ed47_i_aluno = {$oParam->iAluno}";
    }  
    if (db_getsession("DB_modulo") == $iModuloEscola) {
      $aWhere[] = " ed18_i_codigo = {$oRetorno->iEscola} "; 
    }
    $sWhere = implode(" and ", $aWhere);
    $sSqlCartaoIdentificacao = $oDaoLoteCartao->sql_query_lotes(null, 
                                                               "distinct ed60_i_aluno, ed47_v_nome", 
                                                               "ed47_v_nome",
                                                               $sWhere
                                                              );
                                                              
    $rsCartaoIdentificacao = $oDaoLoteCartao->sql_record($sSqlCartaoIdentificacao);
    $iTotalLinhas          = $oDaoLoteCartao->numrows;
    $aCartoes              = array(); 
    db_inicio_transacao();
    for ($iCartao = 0; $iCartao < $iTotalLinhas; $iCartao++) {

      $oAluno = new Aluno(db_utils::fieldsMemory($rsCartaoIdentificacao, $iCartao)->ed60_i_aluno);
      
      $oDadosAluno = new stdClass();
      $oDadosAluno->nome                      = urlencode($oAluno->getNome());
      $oDadosAluno->codigo                    = urlencode($oAluno->getCodigoAluno());
      $oDadosAluno->datanascimento            = $oAluno->getDataNascimento();
      $oDadosAluno->nomepai                   = urlencode($oAluno->getNomePai());
      $oDadosAluno->nomemae                   = urlencode($oAluno->getNomeMae());
      $oDadosAluno->nomeresponsavellegal      = urlencode($oAluno->getNomeResponsavelLegal());
      $oDadosAluno->foto                      = urlencode($oAluno->getFoto());
      $oDadosAluno->situacaocarteira          = $oAluno->getCarteiraIdentificacao()->getSituacao()->getCodigoSituacao();
      $oDadosAluno->descricaosituacaocarteira = urlencode($oAluno->getCarteiraIdentificacao()->getSituacao()->getDescricao());
      $oDadosAluno->codigocarteira            = $oAluno->getCarteiraIdentificacao()->getSequencialCarteira();
      $aCartoes[] = $oDadosAluno;
      unset($oAluno);
    }
    $oRetorno->cartoes = $aCartoes;
    db_fim_transacao(true);
    break;
    
  case 'salvarCartaoIdentificacao':

    try {
      
      db_inicio_transacao();
      if (isset($oParam->aCartoes) && is_array($oParam->aCartoes)) {
        
        foreach ($oParam->aCartoes as $oCartao) {
          
          $oAluno       = new Aluno($oCartao->iAluno);
          $oCartaoAluno =  $oAluno->getCarteiraIdentificacao();
          $oCartaoAluno->setSituacao(new CarteiraIdentificacaoSituacao($oCartao->iSituacao));
          $oCartaoAluno->salvar();
        }
      }
      db_fim_transacao(false);
    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->mesage = urlencode($eErro->getMessage());
    }
    break;
}
echo $oJson->encode($oRetorno);