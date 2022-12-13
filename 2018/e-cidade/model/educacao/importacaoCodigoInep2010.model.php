<?
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

require_once("model/educacao/importacaoCenso.model.php");

class importacaoCodigoInep2010 extends importacaoCenso {
	
  /**
   * (non-PHPdoc)
   * @see importacaoCenso::getDadosAluno()
   */
  function getDadosAluno($oLinha) {
  	
  	$sNomeAluno  = $oLinha->nomealuno;
  	$oDaoAluno   = db_utils::getdao('aluno');
  	$sCampos     = " aluno.*, ed228_i_paisonu, escola.ed18_c_codigoinep as vinculo_escola ";
  	$sWhereAluno = " ed47_v_nome = '$sNomeAluno' ";
  	$sSqlAluno   = $oDaoAluno->sql_query_censo("", $sCampos, "", $sWhereAluno);
  	$rsAluno     = $oDaoAluno->sql_record($sSqlAluno);
  	
  	if ($oDaoAluno->numrows > 0) {
  	  return $aDadosAluno = db_utils::getColectionByRecord($rsAluno, false, false, false);
  	} else {
  	  return null;
  	}
  	
  }

  /**
   * funcao que atualiza o codigo do inep da escola   
   * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
   */
  function atualizaCodigoInepEscola($oLinha) {

  	$oDaoEscola   = db_utils::getDao('escola');
    $oDadosEscola = $this->getDadosEscola($oLinha);
    
    if ($oDadosEscola != null) {
                      
      if ($oLinha->inep_escola != "" && $oLinha->inep_escola != trim($oDadosEscola->ed18_c_codigoinep)) {       
        $oDaoEscola->ed18_c_codigoinep = $oLinha->inep_escola;
      }
      
      $oDaoEscola->ed18_i_codigo = $oDadosEscola->ed18_i_codigo;    
      $oDaoEscola->alterar($oDadosEscola->ed18_i_codigo);
    
      if ($oDaoEscola->erro_status == '0') {
        throw new Exception("Erro na alteração do código inep da escola. Erro da classe: ".$oDaoEscola->erro_msg);        
      }
         
    } else {
      $this->log("Código inep da escola difere do informado no sistema!");	
    }//fecha o else
    
  }//fecha funcao atualizaCodigoInepescola
	
  /**
  * funcao que atualiza o codigo inep da turma,registro 20
  * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
  */
  function atualizaCodigoInepTurma($oLinha) {

  	$sNomeTurmaCensoNovo = str_replace(array('ª', 'º'), array('', ''), $oLinha->nometurma);
    $iTipoAtendimento    = trim($oLinha->tpatend);
    $iCodTurma           = isset($oLinha->codigoturma) ? $oLinha->codigoturma : '';
    $iModalidade         = trim($oLinha->modalidade);
    $iEtapa              = trim($oLinha->etapaensino);
    
    if ($oLinha->tpatend == 0 || $oLinha->tpatend == 1 
        || $oLinha->tpatend == 2 || $oLinha->tpatend == 3) {

      $oDaoTurma    = db_utils::getdao('turma');
      $sWhereTurma  = "";  
      
      if ($iCodTurma != "") {
        $sWhereTurma .= " ed57_i_codigo = ".$iCodTurma;  
      } else {
      	
      	$sWhereTurma .= " translate(to_ascii(ed57_c_descr, 'LATIN1'), ' ', '') = '";
        $sWhereTurma .=            str_replace('', '', $sNomeTurmaCensoNovo)."' ";
      	
      }
      
      $sWhereTurma .= empty($sWhereTurma) ? '' : ' AND ';
      $sWhereTurma .= "      ed57_i_tipoatend = $iTipoAtendimento ";
      $sWhereTurma .= "      AND ed52_i_ano = ".$this->iAnoEscolhido;
      $sWhereTurma .= "      AND ed10_i_tipoensino = $iModalidade ";
      $sWhereTurma .= "      AND ed18_c_codigoinep = '".$this->iCodigoInepEscola."'";
      $sSqlTurma    = $oDaoTurma->sql_query_censo("", "ed57_i_codigo", "", $sWhereTurma);  
      $rsTurma      = $oDaoTurma->sql_record($sSqlTurma);            
      
      if ($oDaoTurma->numrows == 0) {
 
        $sMsg  = "TURMA: [".$this->iCodigoInepEscola."] ".$sNomeTurmaCensoNovo; 
        $sMsg .= " não foi encontrada no sistema.\n";
        $this->log($sMsg);           
           
      } else {
      	
      	$oDadosTurma = db_utils::fieldsmemory($rsTurma, 0);
      	
        if (trim($this->iCodigoInepEscola) != "") {
        
          $oDaoTurma->ed57_i_codigoinep = $oLinha->codigoinepturma;
          $oDaoTurma->ed57_i_codigo     = $oDadosTurma->ed57_i_codigo;
          $oDaoTurma->alterar($oDadosTurma->ed57_i_codigo);
          
          if ($oDaoTurma->erro_status == '0') {
            throw new Exception("Erro na alteração dos dados da Turma. Erro da classe: ".$oDaoTurma->erro_msg);        
          } //fecha o if do erro_status
                            
        }//fecha o if (trim($this->iCodigoInepEscola) != "")
        
      } //fecha o else
              
    } elseif ($oLinha->tpatend == 4 || $oLinha->tpatend == 5) {
              
      $oDaoTurmaac    = db_utils::getdao('turmaac');
      $sWhereTurmaAc  = "";
      
      if ($iCodTurma != "") {
        $sWhereTurmaAc .= " ed57_i_codigo = ".$iCodTurma;  
      } else {
      	
      	$sWhereTurmaAc .= " translate(to_ascii(ed57_c_descr, 'LATIN1'), ' ', '') = '";
        $sWhereTurmaAc .=            str_replace('', '', $sNomeTurmaCensoNovo)."' ";
      	
      }
      
      $sWhereTurmaAc .= empty($sWhereTurma) ? '' : ' AND ';
      $sWhereTurmaAc .= "      AND ed268_i_tipoatend = ".$iTipoAtendimento ;
      $sWhereTurmaAc .= "      AND ed52_i_ano = ".$this->iAnoEscolhido;
      $sWhereTurmaAc .= "      AND ed18_c_codigoinep = '".$this->iCodigoInepEscola."'";  
      $sSqlTurmaac    = $oDaoTurmaac->sql_query_censo("", "*", "", $sWhereTurmaAc); 
      $rsTurmaac      = $oDaoTurmaac->sql_record($sSqlTurmaac);                    
    
      if ($oDaoTurmaac->numrows == 0) {
      	      	
        $sMsg  = "TURMA: [".$this->iCodigoInepEscola."] ".$sNomeTurmaCensoNovo; 
        $sMsg .= " código inep diferente do informado no sistema.\n";
        $this->log($sMsg);
                                       
      } else {
      	
      	$oDadosTurmaac = db_utils::fieldsmemory($rsTurmaac, 0);        
      	
        if (trim($this->iCodigoInepEscola) != "") {
        	
          $oDaoTurmaac->ed268_i_codigoinep = $oDadosTurmaAc->ed268_i_codigoinep;
          $oDaoTurmaac->ed268_i_codigo     = $oDadosTurmaAc->ed268_i_codigo; 
          $oDaoTurmaac->alterar($oDadosTurmaAc->ed268_i_codigo);
              
          if ($oDaoTurmaac->erro_status == '0') {            
            throw new Exception("Erro na alteração do código inep da Turma. Erro da classe: ".$oDaoTurmaac->erro_msg);                    
          } //fecha if $oDaoTurmaac->erro_status == '0' 
                      
        } //fecha if que verifica $codigoinep_turmacenso
        
      } //fecha o else
      
    } //fecha o elseif tipoatend ==4 e ==5
    
  } //fecha a funcao atualizaDadosTurma	
  
  /**
  * funcao que atualiza o codigo inep do docente,registro 30
  * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
  */
  function atualizaCodigoInepDocente($oLinha) {

  	$oDaoRechumano   = db_utils::getdao('rechumano'); 
    $aDadosRechumano = $this->getMatriculasRechumano($oLinha, true);
   
    if ($aDadosRechumano == null) {
      $aDadosRechumano = $this->getMatriculasRechumano($oLinha, false);
    }

    if ($aDadosRechumano != null) {
    	
      $iTam = count($aDadosRechumano);

      for ($iCont = 0; $iCont < $iTam; $iCont++) {
      	
        $oDaoRechumano              = db_utils::getdao('rechumano');
        $oDaoRechumano->ed20_i_pais = "";
              
        if ($oLinha->inepdocente != "") {                      
          $oDaoRechumano->ed20_i_codigoinep = $oLinha->inepdocente;                  
        }	
      
        $oDaoRechumano->ed20_i_codigo = $aDadosRechumano[$iCont]->ed20_i_codigo;
        $oDaoRechumano->alterar($aDadosRechumano[$iCont]->ed20_i_codigo);
     
        if ($oDaoRechumano->erro_status == '0') {
          throw new Exception("Erro na alteração dos dados do Rechumano. Erro da classe ".$oDaoRechumano->erro_msg);        
        }//fecha o if do erro_status
      	
      }//fecha o for
       
    } else {//fecha o fi que verifica se os dados rechumano != null
    	
      $sMsg  = "Docente: [".$oLinha->inepdocente."] ".$oLinha->nomedocente." - ".$oLinha->codigodocenteescola; 
      $sMsg .= " não foi encontrado no sistema.\n";
      $this->log($sMsg);
      
    }//fecha o else
    	
  }//fecha funcao atualizaCodigoInepDocente
  
  /**
  * funcao que atualiza o codigo inep do aluno,registro 60
  * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
  */
  function atualizaCodigoInepAluno($oLinha) {
      
    $oDaoAluno                         = db_utils::getdao('aluno');
    $aDadosAluno                       = $this->getDadosAluno($oLinha, false);       
    $oDaoAluno->ed47_i_censoorgemissrg = "";
    $oDaoAluno->ed47_i_censocartorio   = "";
    $oDaoAluno->ed47_i_pais            = "";     
    $oDaoAluno->oid                    = "";                                      
   
    if ($aDadosAluno != null) {
    	
      $iTam = count($aDadosAluno);

      for ($iCont = 0; $iCont < $iTam; $iCont++) {
      	
        if ($this->lImportarAlunoAtivo) { 
        
          if (trim($aDadosAluno[$iCont]->vinculo_escola) != trim($this->iCodigoInepEscola)) {
                
            $sMsg  = "Aluno [".$oDadosAluno[$iCont]->ed47_c_codigoinep."] ".$oDadosAluno[$iCont]->ed47_v_nome.": aluno";
            $sMsg .= " não está mais vinculado a esta escola.\n";      
            $this->log($sMsg);         
            return;
                 
          } //fecha if $oDadosAluno->vinculo_escola) != trim($this->iCodigoInepEscola
         
        } //fecha o if $this->lImportarAlunoAtivo
        
        if ($oLinha->inepaluno != "" && $oLinha->inepaluno != trim($aDadosAluno[$iCont]->ed47_c_codigoinep)) {                      
          $oDaoAluno->ed47_c_codigoinep = $oLinha->inepaluno;                 
        }
      
        $oDaoAluno->ed47_i_codigo = $aDadosAluno[$iCont]->ed47_i_codigo;            
        $oDaoAluno->alterar($aDadosAluno[$iCont]->ed47_i_codigo);      
                   
        if ($oDaoAluno->erro_status == '0') {
          throw new Exception("Erro na alteração do código inep do Aluno. Erro da classe ".$oDaoAluno->erro_msg);        
        }//fecha o erro_status
        
      }//fecha o for
      
    } else {  //fecha o else do if ($oDadosAluno == null) {
       
      $sMsg  = "Aluno [".$oLinha->inepaluno. "] ". $oLinha->nomealuno;
      $sMsg .= " : Nome cadastrado no censo não existe no sistema.\n";             
      $this->log($sMsg); 
              
    } //fecha o else
                                                                  
  }//fecha a funcao atualizaCodigoInepAluno
	
  /**
   * (non-PHPdoc)
   * @see importacaoCenso::importarArquivo()
   */
  function importarArquivo() {
    
    $sMsgErro = "Importação de Arquivo Censo abortada!\n";    
    if (!db_utils::inTransaction()) {
      throw new Exception("Nenhuma transação do banco encontrada!");
    }
           
    $this->sNomeArquivoLog  = "tmp/censo".$this->iAnoEscolhido."_importacao_".db_getsession("DB_coddepto")."_";
    $this->sNomeArquivoLog .= db_getsession("DB_id_usuario")."_".date("dmY")."_".date("His")."_log.txt";
    
    $this->pArquivoLog      = fopen($this->sNomeArquivoLog, "w");    
    if (!$this->pArquivoLog) {
      throw new Exception(" Não foi possível abrir o arquivo de log! ");   
    }    
    
    if ($this->lIncluirAlunoNaoEncontrado) {  
      $this->log("Registros atualizados na importação do Censo Escolar:\n\n");
    } else {
      $this->log("Registros não atualizados na importação do Censo Escolar:\n\n");  
    }
    
    try {
      
      $this->validaArquivo(); 
      $this->getLinhasArquivo($this->iCodigoLayout);   
      $aLinhasArquivo = $this->getLinhasArquivo();
     
      
    } catch (Exception $eException) {
      throw new Exception($sMsgErro." ".$eException->getMessage());
    }
    
    foreach ($aLinhasArquivo as $iIndLinha => $oLinha) {
    	
      if ($this->lImportarEscola) {
      	
      	if ($oLinha->tiporegistro == "00") {
      	  $this->atualizaCodigoInepEscola($oLinha);
      	}
      	
      }	
      
      if ($this->lImportarTurma) {
      	
      	if ($oLinha->tiporegistro == "20") {
      	  $this->atualizaCodigoInepTurma($oLinha);
      	}
      	
      }
      
      if ($this->lImportarDocente) {
      	
      	if ($oLinha->tiporegistro == "30") {
      	  $this->atualizaCodigoInepDocente($oLinha);
      	}
      	
      }
      
      if ($this->lImportarAluno) {
      	
      	if ($oLinha->tiporegistro == "60") {
      	  $this->atualizaCodigoInepAluno($oLinha);
      	}
      	
      }
    
    }

  }

}

?>