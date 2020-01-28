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

require_once("model/educacao/importacaoCenso.model.php");

class importacaoCodigoInep2014 extends importacaoCenso {

  /**
  * funcao que atualiza o codigo inep da turma,registro 20
  * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
  */
  function atualizaCodigoInepTurma($oLinha) {

    $sNomeTurmaCensoNovo = str_replace(array('ª', 'º'), array('', ''), $oLinha->nome_turma);
    $iTipoAtendimento    = trim($oLinha->tipo_atendimento);
    $iCodTurma           = isset($oLinha->codigo_turma_entidade_escola) ? $oLinha->codigo_turma_entidade_escola : '';
    $iModalidade         = trim($oLinha->modalidade_turma);
    $iEtapa              = trim($oLinha->etapa_ensino_turma);
    
    if ($oLinha->tipo_atendimento == 0 || $oLinha->tipo_atendimento == 1 
        || $oLinha->tipo_atendimento == 2 || $oLinha->tipo_atendimento == 3) {

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
          
          $oDaoTurma->ed57_i_codigoinep = $oLinha->codigo_turma_inep;
          $oDaoTurma->ed57_i_codigo     = $oDadosTurma->ed57_i_codigo;
          $oDaoTurma->alterar($oDadosTurma->ed57_i_codigo);
          
          if ($oDaoTurma->erro_status == '0') {
            throw new Exception("Erro na alteração dos dados da Turma. Erro da classe: ".$oDaoTurma->erro_msg);        
          } //fecha o if do erro_status
                            
        }//fecha o if (trim($this->iCodigoInepEscola) != "")
      } //fecha o else
              
    } elseif ($oLinha->tipo_atendimento == 4 || $oLinha->tipo_atendimento == 5) {
      
      $oDaoTurmaac    = db_utils::getdao('turmaac');
      $sWhereTurmaAc  = "";
      
      if ($iCodTurma != "") {
        $sWhereTurmaAc .= " ed268_i_codigo = ".$iCodTurma;  
      } else {
        
        $sWhereTurmaAc .= " translate(to_ascii(ed268_c_descr, 'LATIN1'), ' ', '') = '";
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
          
          $oDaoTurmaac->ed268_i_codigoinep = $oLinha->codigo_turma_inep;
          $oDaoTurmaac->ed268_i_codigo     = $oDadosTurmaac->ed268_i_codigo; 
          $oDaoTurmaac->alterar($oDadosTurmaac->ed268_i_codigo);
              
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
    $aDadosRechumano = $this->getMatriculasRechumano($oLinha);
   
    if ($aDadosRechumano != null) {
      
      $iTam = count($aDadosRechumano);

      for ($iCont = 0; $iCont < $iTam; $iCont++) {
        
        $oDaoRechumano              = db_utils::getdao('rechumano');
        $oDaoRechumano->ed20_i_pais = "";
              
        if ($oLinha->identificacao_unica_docente_inep != "") {                      
          $oDaoRechumano->ed20_i_codigoinep = $oLinha->identificacao_unica_docente_inep;                  
        } 
      
        $oDaoRechumano->ed20_i_codigo = $aDadosRechumano[$iCont]->ed20_i_codigo;
        $oDaoRechumano->alterar($aDadosRechumano[$iCont]->ed20_i_codigo);
     
        if ($oDaoRechumano->erro_status == '0') {
          throw new Exception("Erro na alteração dos dados do Rechumano. Erro da classe ".$oDaoRechumano->erro_msg);        
        }//fecha o if do erro_status
        
      }//fecha o for
       
    } else {//fecha o fi que verifica se os dados rechumano != null
      
      $sMsg  = "Docente: [".$oLinha->identificacao_unica_docente_inep."] ".$oLinha->nome_completo." - ".$oLinha->codigo_docente_entidade_escola; 
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
    $aDadosAluno                       = $this->getDadosAluno($oLinha);
    $oDaoAluno->ed47_i_censoorgemissrg = "";
    $oDaoAluno->ed47_i_censocartorio   = "";
    $oDaoAluno->ed47_i_pais            = "";     
    $oDaoAluno->oid                    = "";                                      

    if ($aDadosAluno != null) {
      
      $iTam = count($aDadosAluno);

      for ($iCont = 0; $iCont < $iTam; $iCont++) {
        
        if ($this->lImportarAlunoAtivo) { 
        
          if (trim($aDadosAluno[$iCont]->vinculo_escola) != trim($this->iCodigoInepEscola)) {
                
            $sMsg  = "Aluno [".$aDadosAluno[$iCont]->ed47_c_codigoinep."] ".$aDadosAluno[$iCont]->ed47_v_nome.": aluno";
            $sMsg .= " não está mais vinculado a esta escola.\n";      
            $this->log($sMsg);         
            return;
                 
          } //fecha if $oDadosAluno->vinculo_escola) != trim($this->iCodigoInepEscola
         
        } //fecha o if $this->lImportarAlunoAtivo
      
        if ($oLinha->identificacao_unica_aluno != "" && $oLinha->identificacao_unica_aluno != trim($aDadosAluno[$iCont]->ed47_c_codigoinep)) {                      
          $oDaoAluno->ed47_c_codigoinep = $oLinha->identificacao_unica_aluno;                 
        }
      
        $oDaoAluno->ed47_i_codigo = $aDadosAluno[$iCont]->ed47_i_codigo;            
        $oDaoAluno->alterar($aDadosAluno[$iCont]->ed47_i_codigo);      
                   
        if ($oDaoAluno->erro_status == '0') {
          throw new Exception("Erro na alteração do código inep do Aluno. Erro da classe ".$oDaoAluno->erro_msg);        
        }//fecha o erro_status
        
      }//fecha o for
      
    } else {  //fecha o else do if ($oDadosAluno == null) {
       
      $sMsg  = "Aluno [".$oLinha->identificacao_unica_aluno. "] ". $oLinha->nome_completo;
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
      
      if ($this->lImportarTurma) {

        if ($oLinha->tipo_registro == "20") {
          $this->atualizaCodigoInepTurma($oLinha);
        }
        
      }
      
      if ($this->lImportarDocente) {
        
        if ($oLinha->tipo_registro == "30") {
          $this->atualizaCodigoInepDocente($oLinha);
        }
        
      }
      
      if ($this->lImportarAluno) {

        if ($oLinha->tipo_registro == "60") {
          $this->atualizaCodigoInepAluno($oLinha);
        }
      }
    }
  }

  /**
   * Funcao que seleciona os dados dos alunos para utilizarmos nas funcoes
   * atualizaDadosAluno,atualizaEnderecoAluno,AtualizaDadosAdicionais
   * 
   * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro) 
   * @param boolean $lPesquisaInep //Se deseja incluir o código do inep na pesquisa do aluno
   * 
   * @return object  com os dados do aluno se encontra-lo atraves dos dados contidos em $oLinha, 
   * caso contrario retorna null
   * @param boolean $lbuscaNome = true busca pelo nome 
   *                              false busca pelo codigo inep do aluno
   */
  function getDadosAluno($oLinha, $lPesquisaInep = false) {

    $oDaoAluno    = db_utils::getdao('aluno');        
    $sCamposAluno = "aluno.*, ed228_i_paisonu, escola.ed18_c_codigoinep as vinculo_escola";
    $sWhereAluno  = "";
    
    if (isset($oLinha->codigo_aluno_entidade_escola) && !empty($oLinha->codigo_aluno_entidade_escola)) {
      
      $sWhereAluno .= " ed47_i_codigo = ".$oLinha->codigo_aluno_entidade_escola;
      
    } 
    
    if ($lPesquisaInep) {
      
      if (isset($oLinha->identificacao_unica_aluno) && !empty($oLinha->identificacao_unica_aluno)) {

        $sWhereAluno .= (empty($sWhereAluno) ? '' : ' AND ');
        $sWhereAluno .= " ed47_c_codigoinep = ".$oLinha->identificacao_unica_aluno;
      
      }

    } 
    
    if (isset($oLinha->nome_completo) && !empty($oLinha->nome_completo)) {

      $sNomeAlunoCensoNovo = str_replace(array('ª', 'º'), array('', ''), $oLinha->nome_completo); 
      $sWhereAluno        .= (empty($sWhereAluno) ? '' : ' AND ');
      $sWhereAluno        .= " to_ascii(translate(ed47_v_nome, '´`', '') ,'LATIN1') = '".$sNomeAlunoCensoNovo."'";
      
    }

    if (!empty($sWhereAluno)) {
      
      $sSqlAluno    = $oDaoAluno->sql_query_censo("", $sCamposAluno, "vinculo_escola DESC", $sWhereAluno);
      $rsAluno      = $oDaoAluno->sql_record($sSqlAluno);
   
    } else {
      return null;
    }
    
    if ($oDaoAluno->numrows > 0) {
      return $aDadosAluno = db_utils::getCollectionByRecord($rsAluno, false, false, false);            
    } else {
      return null;
    } //fecha o else
                    
  } //fecha a funcao getDadosAluno

  /**
   * 
   * Funcao que seleciona os recursos humanos para utilizarmos o mesmo sql nas funcoes 
   * atualizaDadosDocente, atualizaEnderecoDocente,atualizaEscolarizacaoDocente
   * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
   * @return object com os dados do rechumano caso tiver registro, caso contrario retorna null
   */
  function getMatriculasRechumano($oLinha) {

    $iCodDocenteEsc  = trim($oLinha->codigo_docente_entidade_escola);
    $oDaoRechumano   = db_utils::getdao('rechumano');
    $sCampos         = 'rechumano.*, ed228_i_paisonu, escola.ed18_c_codigoinep as vinculo_escola, ';
    $sCampos        .= 'case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome';
    $sWhereRechumano = "";

    if (isset($oLinha->identificacao_unica_docente_inep) && !empty($oLinha->identificacao_unica_docente_inep)) {
      
      $sWhereRechumano .= "ed20_i_codigoinep = ".$oLinha->identificacao_unica_docente_inep;
      
    } elseif (isset($oLinha->codigo_docente_entidade_escola) && !empty($oLinha->codigo_docente_entidade_escola)) {
      
      $sWhereRechumano .= (empty($sWhereRechumano) ? "" : " AND ");
      $sWhereRechumano .= "cgmrh.z01_numcgm = $iCodDocenteEsc";
      
    }

    if (!empty($sWhereRechumano)) {
      
      $sSqlRechumano   = $oDaoRechumano->sql_query_censomodel("", $sCampos, 
                                                              "vinculo_escola DESC", 
                                                              $sWhereRechumano
                                                             );
      $rsRechumano     = $oDaoRechumano->sql_record($sSqlRechumano);      
      
    }

    /* Nao encontrou o docente pelo codigo inep, entao tenta encontrar pelo nome, data de nascimento de nome da mae */
    if ($oDaoRechumano->numrows <= 0 && isset($oLinha->nome_completo) 
        && isset($oLinha->data_nascimento) && isset($oLinha->nome_completo_mae)) {
    
      $sNomeDocenteCensoNovo = str_replace(array('ª', 'º'), array('', ''), $oLinha->nome_completo);    
      $dNascDocente          = $this->formataData($oLinha->data_nascimento);
      $sMaeDocenteCenso      = str_replace(array('ª', 'º'), array('', ''), $oLinha->nome_completo_mae);
      $sWhereRechumano       = " ed18_c_codigoinep = '".$this->iCodigoInepEscola."'";
      $sWhereRechumano      .= " AND ( ";
      $sWhereRechumano      .= " ( (to_ascii(case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else ";
      $sWhereRechumano      .= "                  cgmcgm.z01_nome end, 'LATIN1') = '$sNomeDocenteCensoNovo'  ";
      $sWhereRechumano      .= "                  OR to_ascii(case when ed20_i_tiposervidor = 1 then ";
      $sWhereRechumano      .= "                     cgmrh.z01_nomecomple else cgmcgm.z01_nomecomple end ";
      $sWhereRechumano      .= "                     ) = '$sNomeDocenteCensoNovo') AND case when ";
      $sWhereRechumano      .= "                     ed20_i_tiposervidor = 1 then cgmrh.z01_nasc else ";
      $sWhereRechumano      .= "                     cgmcgm.z01_nasc end = '$dNascDocente') ";
      $sWhereRechumano      .= " OR ";
      $sWhereRechumano      .= " ( (to_ascii(case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else ";
      $sWhereRechumano      .= " cgmcgm.z01_nome end) = '$sNomeDocenteCensoNovo' OR to_ascii(case when ";
      $sWhereRechumano      .= " ed20_i_tiposervidor = 1 then cgmrh.z01_nomecomple else cgmcgm.z01_nomecomple end ";
      $sWhereRechumano      .= " ) = '$sNomeDocenteCensoNovo') AND to_ascii(case when ed20_i_tiposervidor = 1 ";
      $sWhereRechumano      .= "  then cgmrh.z01_mae else cgmcgm.z01_mae end) = '$sMaeDocenteCenso') ";
      $sWhereRechumano      .= " OR ";
      $sWhereRechumano      .= " ((to_ascii(case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else ";
      $sWhereRechumano      .= "            cgmcgm.z01_nome end) = '$sNomeDocenteCensoNovo' OR ";
      $sWhereRechumano      .= "            to_ascii(case when ed20_i_tiposervidor = 1 then cgmrh.z01_nomecomple ";
      $sWhereRechumano      .= "              else cgmcgm.z01_nomecomple end) = '$sNomeDocenteCensoNovo')) ";
      $sWhereRechumano      .= " ) ";
      $sSqlRechumano         = $oDaoRechumano->sql_query_censomodel("", $sCampos, "", $sWhereRechumano);
      $rsRechumano           = $oDaoRechumano->sql_record($sSqlRechumano);
      
    }     
  
    if ($oDaoRechumano->numrows > 0) {      
      return $aDadosRechumano = db_utils::getCollectionByRecord($rsRechumano, false, false, false);           
    } else {
      return null;
    } //fecha o else 
  } //fecha a funcao getDadosRechumano

}