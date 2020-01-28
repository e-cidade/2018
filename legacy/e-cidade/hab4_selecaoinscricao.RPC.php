<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");

require_once("classes/db_habitprograma_classe.php");
require_once("classes/db_habitcandidato_classe.php");
require_once("classes/db_habitcandidatointeresse_classe.php");

require_once("model/habitacao/CandidatoHabitacao.model.php");
require_once("model/habitacao/InscricaoHabitacao.model.php");
require_once("model/habitacao/InteresseHabitacao.model.php");
require_once("model/habitacao/InteresseProgramaHabitacao.model.php");
require_once("model/processoProtocolo.model.php");
require_once("model/CgmBase.model.php");
require_once("model/CgmFisico.model.php");


$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$lErro             = false;
$sMensagem         = "";

switch($oParam->exec) {

  case 'Programas' :
  	
  	//pesquisa os programas a partir do grupo selecionado
  	
  	$cl_habitprograma = new cl_habitprograma();
  	$aDadosProgramas  = array();
  	$sWhere           = '';
  	if ($oParam->iGrupo != 0 || $oParam->iGrupo != "0") {
  		$sWhere = " ht01_habitgrupoprograma = {$oParam->iGrupo} ";
  	}
  	
  	$sSqlProgramas    = $cl_habitprograma->sql_query_file(null,"ht01_sequencial, ht01_descricao","ht01_sequencial",
  	                                                                                                       "{$sWhere}");
		$rsProgramas      = $cl_habitprograma->sql_record($sSqlProgramas); 
		$aListaProgramas  = db_utils::getColectionByRecord($rsProgramas, false, false, true);
    foreach ($aListaProgramas as $oIndiceProgramas => $oValorProgramas) {
      
      $oDados             = new stdClass();	
      $oDados->sequencial = $oValorProgramas->ht01_sequencial;
      $oDados->descricao  = $oValorProgramas->ht01_descricao;  
      $aDadosProgramas[]  = $oDados;
    }
    $oRetorno->dados      = $aDadosProgramas;
     
  break; 

  case 'Interessados' :
  	// retornara os registros para a grid a partir dos filtros selecionados
    
    $aInteressados    = array();
    $cl_interessados  = new cl_habitcandidato();
    $cl_interesses    = new cl_habitcandidatointeresse();
    
    $iGrupoInteresse    = $oParam->iGrupoInteresse;
    $iProgramaInteresse = $oParam->iProgramaInteresse;
    $iProgramaInscricao = $oParam->iProgramaInscricao;
    
    $sCampos          = " z01_numcgm, z01_nome, z01_cgccpf, z17_situacao, ht01_descricao, ht03_descricao ";
    $sStituacao       = "";
    
    
    // retornara os registros de um determinado grupo de programas
    $sWhereInteresse  = "     ht20_habitgrupoprograma = {$iGrupoInteresse} " ;
    $sWhereInteresse .= " and ht20_ativo is true                           " ;
    
    if ( $iProgramaInteresse != null || $iProgramaInteresse != '') {
    	
	    // quando o grupo for especificado e houver interesse em um programa que não haja inscrição 
      $sWhereInteresse .= " and ht13_habitprograma = {$iProgramaInscricao} ";
	    $sWhereInteresse .= " and not exists ( select 1 
	                                               from habitcandidatointeresseprograma
	                                                    inner join habitinscricao on ht15_habitcandidatointeresseprograma = ht13_sequencial
	                                              where ht13_habitcandidatointeresse = ht20_sequencial )";    
      
    } else {
    	
     // quando o grupo for especificado e não houver interesse em programa   
    	$sWhereInteresse .= " and not exists ( select 1 
    	                                         from habitcandidatointeresseprograma 
    	                                        where ht13_habitcandidatointeresse = ht20_sequencial )";
    }
    
    $sSqlInteresse   = $cl_interesses->sql_query(null,$sCampos,null,$sWhereInteresse);
    
    //echo $sSqlInteresse;
    //die();
    
    $rsInteresse     = $cl_interesses->sql_record($sSqlInteresse);
    $aListaInteresse = db_utils::getColectionByRecord($rsInteresse, false, false, true);
    
    foreach ($aListaInteresse as $oIndiceInteresse => $oValorInteresse) {
    	
        $oDadosInteressados           = new stdClass(); 
        $oDadosInteressados->cgm      = $oValorInteresse->z01_numcgm;
        $oDadosInteressados->nome     = $oValorInteresse->z01_nome;
        $oDadosInteressados->cpf      = db_formatar($oValorInteresse->z01_cgccpf,"cpf");
        
        // validamos a situação: 1 = regular ; 2 = Irregular ; 3 = Suspenso
        switch ($oValorInteresse->z17_situacao){ 
        	case 1 : 
        		$sStituacao = "Regular";
        	break;

        	case 2 :
        		$sStituacao = "Irregular";
        	break;	
        	
        	case 3 :
        		$sStituacao = "Suspenso";
        	break;	
        	
        	case null :
        		$sStituacao = "Indefinido";
        	break;	
        }
        $oDadosInteressados->situacao = $sStituacao;
        $oDadosInteressados->consulta = "Ficha";
        $oDadosInteressados->grupo    = $oValorInteresse->ht03_descricao;
        $oDadosInteressados->programa = $oValorInteresse->ht01_descricao;
        $aInteressados[]              = $oDadosInteressados;   
    	
    }	
    $oRetorno->dados = $aInteressados;
     
  break;

  // se for clicado na opção 'ficha', sera disponibilizado a ficha socio economica do interessado
  case 'Ficha' :

    $iCgm        = $oParam->iCgm;
    $aListaFicha = array();
  	
  	$sSqlAvalGrupo    = " select distinct                                                                                                                                                        ";
    $sSqlAvalGrupo   .= "        db101_sequencial as avaliacao,                                                                                                                                  ";
    $sSqlAvalGrupo   .= "        db107_sequencial as grupo                                                                                                                                       "; 
    $sSqlAvalGrupo   .= "   from avaliacaogruporesposta                                                                                                                                          ";
    $sSqlAvalGrupo   .= "        inner join avaliacaogrupoperguntaresposta on avaliacaogruporesposta.db107_sequencial              = avaliacaogrupoperguntaresposta.db108_avaliacaogruporesposta "; 
    $sSqlAvalGrupo   .= "        inner join avaliacaoresposta              on avaliacaoresposta.db106_sequencial                   = avaliacaogrupoperguntaresposta.db108_avaliacaoresposta      "; 
    $sSqlAvalGrupo   .= "        inner join avaliacaoperguntaopcao         on avaliacaoperguntaopcao.db104_sequencial              = avaliacaoresposta.db106_avaliacaoperguntaopcao              ";
    $sSqlAvalGrupo   .= "        inner join avaliacaopergunta              on avaliacaopergunta.db103_sequencial                   = avaliacaoperguntaopcao.db104_avaliacaopergunta              ";
    $sSqlAvalGrupo   .= "        inner join avaliacaogrupopergunta         on avaliacaogrupopergunta.db102_sequencial              = avaliacaopergunta.db103_avaliacaogrupopergunta              "; 
    $sSqlAvalGrupo   .= "        inner join avaliacao                      on avaliacao.db101_sequencial                           = avaliacaogrupopergunta.db102_avaliacao                      ";
    $sSqlAvalGrupo   .= "        inner join habitfichasocioeconomica       on habitfichasocioeconomica.ht12_avaliacaogruporesposta = avaliacaogruporesposta.db107_sequencial                     ";
    $sSqlAvalGrupo   .= "        inner join habitcandidato                 on habitcandidato.ht10_sequencial                       = habitfichasocioeconomica.ht12_habitcandidato                "; 
    $sSqlAvalGrupo   .= "  where ht10_numcgm = {$iCgm}  ";  

    $rsFicha = db_query($sSqlAvalGrupo);
    $aFicha  = db_utils::getColectionByRecord($rsFicha, false, false, true);    
    foreach ($aFicha as $oIndiceFicha => $oValorFicha) {
      
        $oDadosInteressados           = new stdClass(); 
        $oDadosInteressados->iAval    = $oValorFicha->avaliacao;
        $oDadosInteressados->iGrupo   = $oValorFicha->grupo;
        
        $aListaFicha[]                = $oDadosInteressados; 
    }
    
    $oRetorno->dados = $aListaFicha;
    
  break;	

  case 'GerarInscricao' :
  	
    // processa as inscrições selecionadas na grid
    
    $iGrupoInteresse    = $oParam->iGrupoInteresse;
    $iProgramaInteresse = $oParam->iProgramaInteresse;
    $iProgramaInscricao = $oParam->iProgramaInscricao;
    
    $aListaInteressados = explode(",",$oParam->sListaInteressados);
    
    try {
      
      db_inicio_transacao();    
      
      foreach ($aListaInteressados as $iCgm) {
      	
      	$oCandidato = new CandidatoHabitacao($iCgm);
      	
      	if ($oCandidato->getCgm()->getSituacao() != 1 ) {
      		
      		$sMsgErro  = "Candidato {$oCandidato->getCgm()->getCodigo()}";
          $sMsgErro .= " - {$oCandidato->getCgm()->getNome()}";
          
          if ($oCandidato->getCgm()->getSituacao() != 1 ) {          	
            if ($oCandidato->getCgm()->getSituacao() == 2 ) {
	            $sMsgErro .= " com CPF irregular!";
            } else if ($oCandidato->getCgm()->getSituacao() == 3 ) {
              $sMsgErro .= " com CPF suspenso!";    	
            }else{
            	$sMsgErro .= " com situação de CPF não informada !";
            }
          } 
      		
      		throw new Exception($sMsgErro);
      	}
      	
      	foreach ($oCandidato->getInteresse() as $oInteresse) {
      		
      		if ($oInteresse->getGrupoPrograma() == $iGrupoInteresse) {

      			if (trim($iProgramaInteresse) == '') {

      				if (!$oInteresse->getInteressePrograma()) {
	      				$oInteresse->addInteressePrograma($iProgramaInscricao);
	              $oInteresse->getInteressePrograma()->addInscricao('Inscrição gerada por seleção');
      				}
      			} else {

      				if ($oInteresse->getInteressePrograma()) {
      					if ($oInteresse->getInteressePrograma()->getPrograma() == $iProgramaInscricao) {
      						$oInteresse->getInteressePrograma()->addInscricao('Inscrição gerada por seleção');
      					}
      				}
      			}
      		}
        }
      }
      
      $oRetorno->message = urlencode("Inscrições geradas com sucesso!");
      
      db_fim_transacao(false);
      
    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->status  = 2; 
      $oRetorno->message = urlencode($eErro->getMessage()); 
    }     
     
  break;     
  
  
}
  
echo $oJson->encode($oRetorno);   

?>