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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("std/db_stdClass.php");

require_once("model/habitacao/CandidatoHabitacao.model.php");
require_once("model/habitacao/InscricaoHabitacao.model.php");
require_once("model/habitacao/InteresseHabitacao.model.php");
require_once("model/habitacao/InteresseProgramaHabitacao.model.php");
require_once("model/processoProtocolo.model.php");
require_once("model/Avaliacao.model.php");
require_once("model/AvaliacaoGrupo.model.php");
require_once("model/AvaliacaoPergunta.model.php");
require_once("model/CgmBase.model.php");
require_once("model/CgmFisico.model.php");
require_once("classes/db_habitcandidatointeresse_classe.php");
require_once("classes/db_habitprograma_classe.php");
require_once("classes/db_habitcandidato_classe.php");

$oJson              = new services_json();
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMsg     = '';

$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));

try {
  
  switch ($oParam->exec) {

      /*
       * Verificar a situaчуo do CPF na hora da inclusуo
       */
  	  
  	  case "getSituacaoCpf":
        
		    try {
		           
		      $oCgm = new CgmFisico($oParam->iCgm);
		      
		                switch ($oCgm->getSituacao()){ 
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
		      
		       $oRetorno->sSituacao = $sStituacao;
		       $oRetorno->iSituacao = $oCgm->getSituacao();      
		      //$oRetorno->iSituacao = $oCgm->getSituacao();
		       $oRetorno->sResult = 1;
		
		    } catch (Exception $eErro) {
		        
		      $oRetorno->status  = 2; 
		      $oRetorno->message = urlencode($eErro->getMessage()); 
		      $oRetorno->sResult = 0;
		    } 		    
				    
		    
		    
      
      break;  	
  	
    
    /**
     *  Consulta todos os programas a partir do grupo passado por parтmetro e retorna para a tela uma lista contendo
     *  todos registros, sendo que щ mantido um controle dos que jс estуo marcados ou cadastrados par um candidato
     */
    case "getHabitacaoProgramasGrupo":
      
      
      $oDaoProgramas = db_utils::getDao("habitprograma");
      $sSqlProrama   = $oDaoProgramas->sql_query_file(null,
                                                      "*", 
                                                      'ht01_descricao',
                                                      "ht01_habitgrupoprograma in ({$oParam->sGruposSelecionados})"
                                                     );
      $rsPogramas = $oDaoProgramas->sql_record($sSqlProrama);
      
      $oRetorno->aProgramas = db_utils::getColectionByRecord($rsPogramas, false, false, true);             

      $aProgramasSelecionados = explode(",",$oParam->sProgramasSelecionados);
      

      /**
       * Verifica se existe alguma opчуo selecionada na tela, caso exista entуo mantщm a opчуo marcada.
       */  
      
      foreach ($oRetorno->aProgramas as $iInd => $oPrograma) {
        if (in_array($oRetorno->aProgramas[$iInd]->ht01_sequencial,$aProgramasSelecionados)) {
          $oRetorno->aProgramas[$iInd]->lChecked = true;
        } else {
          $oRetorno->aProgramas[$iInd]->lChecked = false;
        }
      }
      
      /**
       * Caso exista algum candidato sendo pesquisado, щ verificado se alguma dos registros retornados no SQL jс estс
       * cadastrado, sendo que caso exista entуo a opчуo jс aparece como selecionado.  
       */
      if (isset($_SESSION['oCandidatoHabitacao'])) {
        
        $oCandidato = unserialize($_SESSION["oCandidatoHabitacao"]);
            
        foreach ($oCandidato->getInteresse() as $oInteresse) {
          if ( $oInteresse->isAtivo() && $oInteresse->getInteressePrograma() ) {
            foreach ($oRetorno->aProgramas as $iInd => $oPrograma) {
              if ( $oPrograma->ht01_sequencial ==  $oInteresse->getInteressePrograma()->getPrograma()) {
                $oRetorno->aProgramas[$iInd]->lChecked = true;           
              }
            }
          }
        }
      }
      
      break;


      
    /**
     *  Consulta os dados do candidato a partir do CGM  
     */  
    case "getDadosCandidato":
       
      $oCandidato = new CandidatoHabitacao($oParam->iCgm);
      
      $_SESSION["oCandidatoHabitacao"] = serialize($oCandidato);
      
      $oRetorno->candidato = new stdClass();
      $oRetorno->candidato->iNumCgm         = $oCandidato->getCgm()->getCodigo();
      $oRetorno->candidato->sNome           = urlencode($oCandidato->getCgm()->getNome());
      $oRetorno->candidato->iAvaliacao      = $oCandidato->getCadastroSocioEconomico();
      $oRetorno->candidato->aInteresseGrupo = array();
      $oRetorno->candidato->aFamiliares     = array();

      if ($oCandidato->getCgm()->getSituacao()) {
        $oRetorno->candidato->iSituacaoCpf  = $oCandidato->getCgm()->getSituacao();
      } else {
        $oRetorno->candidato->iSituacaoCpf  = null;
      }
      
      /**
       * Cria um array contendo todos grupos cadastrados para o candidato
       */
      foreach ($oCandidato->getInteresse() as $oInteresse) {
        if ( $oInteresse->isAtivo() ) {
          $oRetorno->candidato->aInteresseGrupo[] = $oInteresse->getGrupoPrograma();
        }
      }
      
      
      /**
       * Cria um array contendo todos os familiares cadastrados para o candidato
       */
      $aFamiliares = $oCandidato->getCgm()->getFamiliares();
      
      foreach ($aFamiliares as &$oFamiliar) {
        
        $oFamiliar->sNome  = urlencode($oFamiliar->sNome);
        $oFamiliar->sTipo  = urlencode($oFamiliar->sTipo);
        $oRetorno->candidato->aFamiliares[]  = $oFamiliar;
      }
      
      break;      
      
      
    /**
     * Incluir / Altera um Candidato a partir do CGM informado
     */
    case "salvarCandidato" :
      
      db_inicio_transacao();

      /**
       *  Caso exista algum objeto em sessуo entуo щ alterado o mesmo
       */
      if (isset($_SESSION["oCandidatoHabitacao"])) {
        $oCandidato = unserialize($_SESSION["oCandidatoHabitacao"]);
      } else {
        $oCandidato = new CandidatoHabitacao($oParam->iCgm);
      }
        
      $oCandidato->setCadastroSocioEconomico($oParam->iAvaliacao);
      $oCandidato->salvar();

      
      /**
       * Adiciona todos interesses nos programas selecionados
       */
      foreach ($oParam->aProgramaInteresse as $iProgramaInteresse) {
        $oCandidato->addInteressePrograma($iProgramaInteresse);
      }      
      
      /**
       *  Adiciona todos interesses nos grupos selecionados 
       */
      foreach ($oParam->aGrupoInteresse as $iGrupoInteresse) {
        $oCandidato->addInteresseGrupo($iGrupoInteresse);
      }
      
      
      /**
       *  Nos casos em que foi desselecionado algum grupo entуo щ cancelado o interesse existente 
       */
      foreach ($oCandidato->getInteresse() as $iInd => $oInteresse) {
        if ($oInteresse->isAtivo()) {
          if (!in_array($oInteresse->getGrupoPrograma(),$oParam->aGrupoInteresse)) {
            $oCandidato->cancelaInteresseGrupo($oInteresse->getGrupoPrograma());
          }
        }
      }

      /**
       *  Nos casos em que foi desselecionado algum programa entуo щ cancelado o interesse existente 
       */      
      foreach ($oCandidato->getInteresse() as $iInd => $oInteresse) {
        if ($oInteresse->isAtivo() && $oInteresse->getInteressePrograma()) {
          if (!in_array($oInteresse->getInteressePrograma()->getPrograma(),$oParam->aProgramaInteresse)) {
            $oCandidato->cancelaInteressePrograma($oInteresse->getInteressePrograma()->getPrograma());
          }
        }
      }      
      
      /**
       *  Inclui familiares para o candidato a partir dos registros informados na tela 
       */
      $oCandidato->getCgm()->removerFamiliares();
        
      foreach ($oParam->aFamiliares as $oFamiliar) {
        $oCandidato->getCgm()->adicionarFamiliar($oFamiliar);
      }
        
      $oCandidato->getCgm()->salvarFamiliares();

      $oCandidato->getCgm()->setSituacao($oParam->iSituacaoCpf);
      $oCandidato->getCgm()->save();
        
      $_SESSION["oCandidatoHabitacao"] = serialize($oCandidato); 
      
      db_fim_transacao(false);

      break;
      
    /**
     *  Gera um CGM para o familiar informado  
     */  
    case 'adicionarCgmFamiliar':
        
      db_inicio_transacao();
         
      $oCgmPrincipal = new CgmFisico($oParam->oCgm->principal);
      
      if ($oParam->oCgm->alterar) {
        $oCgmFamilia   = new CgmFisico($oParam->oCgm->filho);
      } else {
        $oCgmFamilia   = new CgmFisico();
      }
      
      $oCgmFamilia->setNome            (utf8_decode(db_stdClass::db_stripTagsJson($oParam->oCgm->nome))); 
      $oCgmFamilia->setProfissao       (utf8_decode(db_stdClass::db_stripTagsJson($oParam->oCgm->profissao))); 
      $oCgmFamilia->setDataNascimento  (implode("-", array_reverse(explode("/",$oParam->oCgm->nascimento)))); 
      $oCgmFamilia->setEstadoCivil     ($oParam->oCgm->estadocivil); 
      $oCgmFamilia->setRenda           ($oParam->oCgm->renda); 
      $oCgmFamilia->setEscolaridade    (utf8_decode(db_stdClass::db_stripTagsJson($oParam->oCgm->escolaridade)));
      $oCgmFamilia->setSexo            ($oParam->oCgm->sexo);
      $oCgmFamilia->setBairro          ($oCgmPrincipal->getBairro());
      $oCgmFamilia->setComplemento     ($oCgmPrincipal->getComplemento());
      $oCgmFamilia->setMunicipio       ($oCgmPrincipal->getMunicipio());
      $oCgmFamilia->setUf              ($oCgmPrincipal->getUf());
      $oCgmFamilia->setNumero          ($oCgmPrincipal->getNumero());
      $oCgmFamilia->setLogradouro      ($oCgmPrincipal->getLogradouro());
      $oCgmFamilia->setCpf             ("00000000000");
      $oCgmFamilia->setEnderecoPrimario($oCgmPrincipal->getEnderecoPrimario());
      $oCgmFamilia->save();
        
      $oRetorno->numcgm = $oCgmFamilia->getCodigo();
      $oRetorno->nome   = urlencode($oCgmFamilia->getNome());
      
      db_fim_transacao(false);
      
      break;
      
   
    /**
     * Busca dados de um CGM para alteraчуo (grid)
     */  
    case 'buscarCgmFamiliar':
      
      $oCgmDados              = new CgmFisico($oParam->oCgm);
      $oRetorno->nome         = urlencode ($oCgmDados->getNome());
      $oRetorno->profissao    = urlencode ($oCgmDados->getProfissao());
      $oRetorno->escolaridade = urlencode ($oCgmDados->getEscolaridade());
      $oRetorno->renda        = $oCgmDados->getRenda();
      $oRetorno->nascimento   = $oCgmDados->getDataNascimento();
      $oRetorno->sexo         = $oCgmDados->getSexo();
      $oRetorno->cgmfamiliar  = $oParam->oCgm;
      
      if (isset($oParam->tipofamiliar)) {
        $oRetorno->tipofamiliar = $oParam->tipofamiliar;
      }

      break;
      
  }  
  


} catch (Exception $eException) {
  
  if (db_utils::inTransaction()) {
    db_fim_transacao(true);
  }
  
  $oRetorno->iStatus = 2; 
  $oRetorno->sMsg    = urlencode(str_replace('\n', "\n", $eException->getMessage()));  
}


echo $oJson->encode($oRetorno);
?>