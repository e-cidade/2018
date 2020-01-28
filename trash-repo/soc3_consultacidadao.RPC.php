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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("libs/db_app.utils.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");

$oJson            = new Services_JSON();
$oParam           = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno         = new stdClass();
$oRetorno->dados  = array();
$oRetorno->status = 1;
$sDataAtual       = date("d/m/Y", db_getsession("DB_datausu"));

switch ($oParam->exec){

  case "buscaTelefone":
    
    $oCidadao   = new Cidadao($oParam->iCidadao);      
    $aTelefones = $oCidadao->getTelefones();
    
    foreach ($aTelefones as $oTelefone) {
      
      $oDado              = new stdClass();
      $oDado->ddd         = $oTelefone->getDDD();
      $oDado->numero      = $oTelefone->getNumeroTelefone();
      $oDado->ramal       = $oTelefone->getRamal();
      $oDado->tipo        = urlencode($oTelefone->getTipoTelefone());
      $oDado->principal   = urlencode($oTelefone->isTelefonePrincipal() ? "Sim" : "Não"); 
      $oDado->observacao  = urlencode($oTelefone->getObservacao());
      $oRetorno->dados[]  = $oDado;
    }
    
    if (count($aTelefones) == 0) {
      
      $oRetorno->status   = 2;
      $oRetorno->mesage  = "Nenhum telefone encontrado.";
    }

    break;
  case 'buscaRetorno':
    
    $oCidadao   = new Cidadao($oParam->iCidadao);
    $aRetorno   = $oCidadao->getFormasDeRetorno();
    
    foreach ($aRetorno as $oDadoRetorno) {
      
      $oDado             = new stdClass();
      $oDado->retorno    = $oDadoRetorno->getDescricao();
      $oRetorno->dados[] =   $oDado; 
    }
    
    if (count($aRetorno) == 0) {
      
      $oRetorno->status   = 2;
      $oRetorno->mesage  = "Nenhuma forma de getTipoBeneficioretorno encontrada.";
    }
    
    break;
    
  case 'buscaCadastroUnico':
    
    $oDaoCadUnico = db_utils::getDao('cidadaocadastrounico');
    $sWhere       = " as02_cidadao = {$oParam->iCidadao}";
    $sSqlCadUnico = $oDaoCadUnico->sql_query_file(null, "as02_sequencial", null, $sWhere);
    $rsCadUnico   = $oDaoCadUnico->sql_record($sSqlCadUnico);
    
    if ($oDaoCadUnico->numrows == 0) {
      
      $oRetorno->status   = 2;
      $oRetorno->mesage  = urlencode("Cidadão não possui dados no cadastro único.");
      break;
    }
    
    $oCadastroUnico = new CadastroUnico(db_utils::fieldsMemory($rsCadUnico, 0)->as02_sequencial);
    $sSexo          = $oCadastroUnico->getSexo() == "F" ? "Feminino" : "Masculino";
    
    $oRetorno->oCadastroUnico                             = new stdClass();
    $oRetorno->oCadastroUnico->iNis                       = $oCadastroUnico->getNis(); 
    $oRetorno->oCadastroUnico->sApelido                   = $oCadastroUnico->getApelido();
    $oRetorno->oCadastroUnico->dtNascimento               = $oCadastroUnico->getDataNascimento();
    $oRetorno->oCadastroUnico->dtAutalizacaoCadastroUnico = $oCadastroUnico->getDataAtualizacaoCadastroUnico();
    $oRetorno->oCadastroUnico->sSexo                      = $sSexo;
    
    break;
    
  case 'buscaAvaliacao':
    
    db_inicio_transacao();
    
    $oCidadao   = new Cidadao($oParam->iCidadao);
    if ($oCidadao->hasCadastroUnico()) {
      $oCidadao = new CadastroUnico($oCidadao->getSequencialCadastroUnico());
    }
    $oAvaliacao                 = $oCidadao->getAvaliacao();
    $oRetorno->iCodigoAvaliacao = Cidadao::CODIGO_AVALICAO;
    
    if (isset($oParam->lAvaliacaoFamilia) && $oParam->lAvaliacaoFamilia) {
      
      $oFamilia                   = $oCidadao->getFamilia();
      $oAvaliacao                 = $oFamilia->getAvaliacao();
      $oRetorno->iCodigoAvaliacao = Familia::CODIGO_AVALICAO;
      $oAvaliacao                 = $oFamilia->getAvaliacao();
      $oRetorno->iCodigoAvaliacao = Familia::CODIGO_AVALICAO;
    }
    
    /**
     * Buscamos o grupo de resposta
     */
    $oRetorno->iCodigoGrupoRespostas = $oAvaliacao->getAvaliacaoGrupo();
    db_fim_transacao();
    break;
    
  case 'buscaBeneficio':
  	
  	$sWhereFamilia = " ov02_sequencial = {$oParam->iCidadao}";
  	$sOrderFamilia = " ov02_seq desc limit 1"; 
  	$oDaoCadUnico  = db_utils::getDao('cidadaocadastrounico');
  	$sSqlCadUnico  = $oDaoCadUnico->sql_query(null, 'as02_sequencial', $sOrderFamilia, $sWhereFamilia);
  	$rsCadUnico    = $oDaoCadUnico->sql_record($sSqlCadUnico);
  	
  	if ($oDaoCadUnico->numrows == 0) {
  		
  		$oRetorno->status   = 2;
  		$oRetorno->mesage  = urlencode("Cidadão não faz parte do cadastro único.");
  		break;
  	}
  	
  	$oCadastroUnico = new CadastroUnico(db_utils::fieldsMemory($rsCadUnico, 0)->as02_sequencial);
  	$aBeneficios    = $oCadastroUnico->getBeneficios();
  	
  	if (count($aBeneficios) == 0) {
  		
  		$oRetorno->status   = 2;
  		$oRetorno->mesage  = urlencode("Cidadão não possui beneficios.");
  		break;
  	}
  	
  	foreach ($aBeneficios as $oBeneficio) {
  		
  		$oDados 						 = new stdClass();
  		$oDados->sBeneficio  = $oBeneficio->getTipoBeneficio();
  		
  		$oDataConcessao      = $oBeneficio->getDataConcessao();
  		$oDataSituacao       = $oBeneficio->getDataSituacao();
  		
  		if (!empty($oDataConcessao)) {
	  		$oDados->dtConcessao = $oDataConcessao->convertTo(DBDate::DATA_PTBR);
  		}
  		$oDados->sSituacao   = $oBeneficio->getSituacao();
  		if (!empty($oDataSituacao)) {
  			$oDados->dtSituacao = $oDataSituacao->convertTo(DBDate::DATA_PTBR);
  		}
  		
  		$oRetorno->dados[] = $oDados;
  	}
  	
  	break;
  	
  /**
   * Retorna uma colecao dos cursos/oficinas realizados pelo cidadao
   * @param integer $oParam->iCidadao - Codigo do cidadao
   * @return array $oRetorno->aCursosOficinas
   *               stdClass integer iCodigo     - Codigo do curso
   *                        string  sNome       - Nome do curso
   *                        string  sSituacao   - Em andamento, Nao Iniciado ou Concluido
   *                        string  sDataInicio - Data de inicio do curso
   *                        string  sDataFim    - Data de fim do curso
   */
  case 'buscaCursosOficinas':

    $oRetorno->aCursosOficinas = array();
    
    if (isset($oParam->iCidadao) && !empty($oParam->iCidadao)) {
      
      $oCidadao     = CidadaoRepository::getCidadaoByCodigo($oParam->iCidadao);
      $aCursoSocial = $oCidadao->getCursosCidadao(false);
      
      if (count($aCursoSocial) > 0) {
        
        foreach ($aCursoSocial as $oCursoSocial) {
          
          $oDadosCurso            = new stdClass();
          $oDadosCurso->iCodigo   = $oCursoSocial->getCodigo();
          $oDadosCurso->sNome     = urlencode($oCursoSocial->getNome());
          $oDadosCurso->sSituacao = urlencode("Em andamento");
          
          $oDataAtual  = new DBDate($sDataAtual);
          $oDataInicio = $oCursoSocial->getDataInicio();
          $oDataFim    = $oCursoSocial->getDataFim();
          
          if (!DBDate::dataEstaNoIntervalo($oDataAtual, $oDataInicio, $oDataFim)) {
            
            $oDadosCurso->sSituacao = urlencode("Concluído");
            if (DBDate::calculaIntervaloEntreDatas($oDataAtual, $oDataInicio, 's') < 0) {
              $oDadosCurso->sSituacao = urlencode("Não iniciado");
            }
          }
          
          $oDadosCurso->sDataInicio    = urlencode($oDataInicio->getDate(DBDate::DATA_PTBR));
          $oDadosCurso->sDataFim       = urlencode($oDataFim->getDate(DBDate::DATA_PTBR));
          $oRetorno->aCursosOficinas[] = $oDadosCurso;
        }
      }
    }
    break;
}

echo $oJson->encode($oRetorno);