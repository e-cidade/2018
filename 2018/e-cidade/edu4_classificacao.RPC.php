<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once ("libs/db_stdlibwebseller.php");
require_once ("dbforms/db_funcoes.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");

define("ARQUIVO_MSG_EDU4_CLASSIFICACAORPC", "educacao.escola.classificacaorpc.");

$iEscola           = db_getsession("DB_coddepto");
$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->dados   = array();
$oRetorno->status  = 1;
$oRetorno->message = '';

try {

  switch($oParam->exec) {
    
  	case 'getDadosOrigemAluno':
  	  
  	  $oMatricula = MatriculaRepository::getMatriculaByCodigo($oParam->iMatricula);
  	  
  	  $oDadosAluno = new stdClass();
  	  
  	  $oDadosAluno->turma_codigo    = $oMatricula->getTurma()->getCodigo(); 
  	  $oDadosAluno->turma_descricao = urlencode($oMatricula->getTurma()->getDescricao());
  	  $oDadosAluno->etapa_codigo    = $oMatricula->getEtapaDeOrigem()->getCodigo();
  	  $oDadosAluno->etapa_descricao = urlencode($oMatricula->getEtapaDeOrigem()->getNome());
  	  $oDadosAluno->etapa_abreviado = urlencode($oMatricula->getEtapaDeOrigem()->getNomeAbreviado());
  	  $oDadosAluno->data_matricula  = $oMatricula->getDataMatricula()->convertTo(DBDate::DATA_PTBR);
  	  
  	  $oRetorno->oDadosAluno = $oDadosAluno;
  	  break;
  	  
  	case 'getDisciplinaTurmaOrigem':
  	  
  	  $oTurma     = TurmaRepository::getTurmaByCodigo($oParam->iTurma);
  	  $aRegencias = $oTurma->getDisciplinasPorEtapa(EtapaRepository::getEtapaByCodigo($oParam->iEtapa));
  	  
  	  $aDisciplinas = array(); 
  	  foreach ($aRegencias as $oRegencia) {
  	  	
  	    $oDisciplina          = new stdClass();
  	    $oDisciplina->nome    = urlencode($oRegencia->getDisciplina()->getNomeDisciplina());
  	    $oDisciplina->abrev   = urlencode($oRegencia->getDisciplina()->getAbreviatura());
  	    $oDisciplina->iCodigo = $oRegencia->getDisciplina()->getCodigoDisciplina();

  	    $aDisciplinas[] = $oDisciplina; 
  	  }
  	  
  	  $oRetorno->aDisciplinas = $aDisciplinas;
  	  break;
  	  
  	case 'processar':
  	  
  	  $oMatriculaAtual = MatriculaRepository::getMatriculaByCodigo($oParam->iMatriculaAtual);
  	  
  	  db_inicio_transacao();
  	  $oClassificacao = new ClassificacaoAluno();
  	  $oClassificacao->setAluno(AlunoRepository::getAlunoByCodigo($oParam->iAluno));
  	  $oClassificacao->setData(new DBDate($oParam->data));
  	  $oClassificacao->setObservacao(db_stdClass::normalizeStringJsonEscapeString($oParam->sObservavcao));
  	  $oClassificacao->setTipo($oParam->sTipo);
  	  $oClassificacao->setTurmaDestino(TurmaRepository::getTurmaByCodigo($oParam->iTurmaDestino));
  	  $oClassificacao->setTurmaOrigem($oMatriculaAtual->getTurma());
  	  
  	  if (count($oParam->aAvaliacao) > 0) {

  	    foreach ($oParam->aAvaliacao as $oResuldado) {
  	    	
  	      $oResuldadoClassificacao = new ResultadoClassificacao();
  	      $oResuldadoClassificacao->setDisciplina(DisciplinaRepository::getDisciplinaByCodigo($oResuldado->iCodigoDisciplina));
  	      $oResuldadoClassificacao->setResultado(db_stdClass::normalizeStringJsonEscapeString($oResuldado->sAvaliacao));
  	      $oClassificacao->adicionarResultadoAvaliacao($oResuldadoClassificacao);
  	    }
   	  }
      
  	  $oClassificacao->salvar(EtapaRepository::getEtapaByCodigo($oParam->iEtapaDestino), $oParam->aTurnoReferencia);
  	  
  	  db_fim_transacao(false);
  	  break;

  	case "getAlunosReclassificados":

  	  $oEscola              = EscolaRepository::getEscolaByCodigo($oParam->iEscola);
  	  $oCalendario          = CalendarioRepository::getCalendarioByCodigo($oParam->iCalendario);
  	  $aAlunosClassificados = ClassificacaoAluno::getAlunosClassificadoDaEscolaNoCalendario($oEscola, $oCalendario);
  	  
  	  foreach ($aAlunosClassificados as $oAluno) {
  	  	
  	    $oAlunoRetorno          = new stdClass();
  	    $oAlunoRetorno->iCodigo = $oAluno->getCodigoAluno();
  	    $oAlunoRetorno->sNome   = urlencode($oAluno->getNome());
  	    $oRetorno->dados[]      = $oAlunoRetorno; 
  	  }
  	  
  	  break;
  	  
  	case "buscaModelosAta":
  	  
  	  $sCampos               = " db82_sequencial, db82_descricao";
  	  $oDaoDocumento         = new cl_db_documentotemplate();
  	  $sSqlDocumentoTemplate = $oDaoDocumento->sql_query_file(null, $sCampos, null, "db82_templatetipo = 43");
  	  $rsDocumentoTemplate   = db_query($sSqlDocumentoTemplate);
  	  
  	  if (!$rsDocumentoTemplate) {
  	  	throw new DBException( _M(ARQUIVO_MSG_EDU4_CLASSIFICACAORPC."erro_buscar_modelo_ata") );
  	  }
  	  $iLinhas = pg_num_rows($rsDocumentoTemplate);
  	  if ($iLinhas == 0) {
  	    //throw new BusinessException( _M(ARQUIVO_MSG_EDU4_CLASSIFICACAORPC."nenhum_modelo_cadastrado") );
        $oModelo             = new stdClass();
        $oModelo->iCodigo    = null;
        $oModelo->sDescricao = urlencode("Padrão");
        $oRetorno->dados[]   = $oModelo;
  	  }
  	  
  	  for ( $i = 0; $i < $iLinhas; $i++ ) {
  	  	
  	    $oDadosModelos       = db_utils::fieldsMemory($rsDocumentoTemplate, $i);
  	    $oModelo             = new stdClass();
  	    $oModelo->iCodigo    = $oDadosModelos->db82_sequencial;
  	    $oModelo->sDescricao = urlencode($oDadosModelos->db82_descricao);
  	    $oRetorno->dados[]   = $oModelo;
  	  }
  	  	
  	  break;
  }
} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);