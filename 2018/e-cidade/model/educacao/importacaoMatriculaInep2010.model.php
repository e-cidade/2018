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

class importacaoMatriculaInep extends importacaoCenso{    
        
  /**
   * Funcao que seleciona a matricula do aluno registro 90
   * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
   */
  function getMatriculaAluno($oLinha) {
  	
  	$oDaoAlunoMatcenso  = db_utils::getdao('alunomatcenso');  	  
  	$aDados             = $this->getDadosAluno($oLinha);  	
  	
    for ($iCont = 0; $iCont < count($aDados); $iCont++) {
    
      $iCodigoAluno       = $aDados[$iCont]->ed47_i_codigo;
      $sWhere             = "ed280_i_matcenso = ".$oLinha->codigomatricula." AND ed280_i_ano = ";
  	  $sWhere            .= $this->iAnoEscolhido." AND ed280_i_aluno = ".$aDados[$iCont]->ed47_i_codigo;
  	  $sCampos            = "ed280_i_codigo,ed280_i_aluno";
      $sSqlAlunoMatricula = $oDaoAlunoMatcenso->sql_query_file("", $sCampos, "", $sWhere);   
      $rsAlunoMatricula   = $oDaoAlunoMatcenso->sql_record($sSqlAlunoMatricula);   
      
  	  if ($oDaoAlunoMatcenso->numrows > 0) {
        return db_utils::fieldsmemory($rsAlunoMatricula, 0);      
      } else {
        return null;
      } //fecha o else

    } //fecha for
    
  }//fecha a funcao getMatriculaAluno
  
  /**
   * Funcao que seleciona os dados da turma
   * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro)
   */
  function getDadosTurma($oLinha) {
  	
  	$oDaoTurma           = db_utils::getdao('turma');      
  	$sNomeTurmaCensoNovo = str_replace(array('ª', 'º'), array('', ''), $oLinha->nometurma);       
    $sWhereTurma         = " to_ascii(translate(ed57_c_descr, ' ', ''), 'LATIN1') = '";
    $sWhereTurma        .=         str_replace(' ','',$sNomeTurmaCensoNovo)."'";    
    $sWhereTurma        .= "       AND ed52_i_ano = ".$this->iAnoEscolhido;
    $sWhereTurma        .= "       AND ed11_i_codcenso = ".trim($oLinha->etapa);
    $sWhereTurma        .= "       AND ed18_c_codigoinep = '".$this->iCodigoInepEscola."'";
    $sSqlTurma           = $oDaoTurma->sql_query_censo("", "ed57_i_codigo, ed57_i_codigoinep", "", $sWhereTurma);  
    $rsTurma             = $oDaoTurma->sql_record($sSqlTurma);  	
    
    if ($oDaoTurma->numrows > 0) {
      return db_utils::fieldsmemory($rsTurma, 0);      
    } else {
      return null;
    } //fecha o else
                    
  } //fecha a funcao getDadosTurma
  
   /**
   * função que valida o arquivo txt para importacao dos dados, verifica se o codigo inep é da escola 
   * onde o usuario esta logado, e verifica também o ano atual
   * @override
   */
  function validaArquivo() {

    $sMsgErro      = "Importação de Arquivo Censo abortada!\n";
    $oDaoEscola    = db_utils::getdao('escola');
    $pArquivoCenso = fopen($this->sCaminhoArquivo, "r");
    
    if (!$pArquivoCenso) {
      throw new Exception(" Não foi possível abrir o arquivo para importação! "); 	
    }
    
    $sSqlEscola = $oDaoEscola->sql_query("", "ed18_c_codigoinep", "", "ed18_i_codigo = ".db_getsession("DB_coddepto"));      
    $rsEscola   = $oDaoEscola->sql_record($sSqlEscola);     
        
    if ($oDaoEscola->numrows > 0) {
      $iInepBanco = db_utils::fieldsMemory($rsEscola, 0)->ed18_c_codigoinep;   
    }
    
    $sLinha = fgets($pArquivoCenso);   
    $aLinha = explode("|", $sLinha);    

    if ($aLinha[0] != "89") {
    	
      fclose($pArquivoCenso);
      throw new Exception(" Arquivo informado não é um arquivo de exportação geral gerado pelo Educacenso! ");        
        
    } elseif ($aLinha[1] != $iInepBanco) {
    	
      fclose($pArquivoCenso);
      throw new Exception(" Arquivo não pertence a esta escola, código inep diferente do que informado no arquivo! ");  
                 
    }
    
    rewind($pArquivoCenso); 
    while (!feof($pArquivoCenso)) {
      
      $sLinha = fgets($pArquivoCenso);
      $aLinha = explode("|", $sLinha);
      /**
       * se alinha esta em branco é ignorada
       */
      if (empty($aLinha[0])) {
        continue;
      }   
      
      if ($aLinha[0] != "89" && $aLinha[0] != "90") {
          	
        fclose($pArquivoCenso);    
        throw new Exception(" Arquivo informado não é valido, existe registro de código ".
                            $aLinha[0]." que é desconhecido"
                           );
        
      } //fecha o if que verifica os tipos de registros  
           
    }//fecha o while
    
    fclose($pArquivoCenso);
    
  } //fecha a funcao validaArquivo
  

  /**
   * funcao que atualiza a situacao do aluno
   * @param integer $oLinha
   */
  function atualizaSituacaoAluno ($oLinha) {

  	$oDaoAluno         = db_utils::getdao('aluno');
  	$oDaoAlunoMatcenso = db_utils::getdao('alunomatcenso');
    $aDadosAluno       = $this->getDadosAluno($oLinha);
    $oDaoTurma         = db_utils::getdao('turma');
    
    if ($aDadosAluno == null) {
      
      $sMsg  = "\nAluno [".$oLinha->inepaluno."] ".$oLinha->nomealuno;
      $sMsg .= ": Nome cadastrado no censo não existe no sistema.";    
      $this->log($sMsg);
              
    } else {
    	
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
        
        $oDaoAluno->ed47_i_codigo     = $aDadosAluno[$iCont]->ed47_i_codigo;
        $oDaoAluno->ed47_c_codigoinep = $aDadosAluno[$iCont]->ed47_c_codigoinep;
        $oDaoAluno->alterar($aDadosAluno[$iCont]->ed47_i_codigo);
      
        if ($oDaoAluno->erro_status == '0') {
          throw new Exception("Erro na alteração dos dados do Aluno. Erro da classe: ".$oDaoAluno->erro_msg);        
        }//fecha o if erro_status
          
      }//fecha o for

      if (isset($oLinha->codigomatricula)) { 
        
        for ($iCont = 0; $iCont < $iTam; $iCont++) { 
      
          $oDadosMatriculaAluno = $this->getMatriculaAluno($oLinha); 
          if ($oDadosMatriculaAluno == null) {
            
            $oDaoAlunoMatcenso->ed280_i_aluno      = $aDadosAluno[$iCont]->ed47_i_codigo;  
            $oDaoAlunoMatcenso->ed280_i_turmacenso = $oLinha->codigoturma;
            $oDaoAlunoMatcenso->ed280_i_ano        = $oLinha->anoreferencia;
            $oDaoAlunoMatcenso->ed280_i_matcenso   = $oLinha->codigomatricula;
            $oDaoAlunoMatcenso->incluir(null);   
            
            if ($oDaoAlunoMatcenso->erro_status == '0') {
          	
              throw new Exception("Erro na alteração da Matricula do Aluno. Erro da classe: ".
                                  $oDaoAlunoMatcenso->erro_msg
                                 );
                                       
            }//fecha o if erro_status
                       
          } /**else {

            $oDaoAlunoMatcenso->ed280_i_aluno      = $aDadosAluno[$iCont]->ed47_i_codigo;  
            $oDaoAlunoMatcenso->ed280_i_turmacenso = $oLinha->codigoturma;
            $oDaoAlunoMatcenso->ed280_i_ano        = $oLinha->anoreferencia;
            $oDaoAlunoMatcenso->ed280_i_matcenso   = $oLinha->codigomatricula;
            $oDaoAlunoMatcenso->alterar($aDadosAluno[$iCont]->ed47_i_codigo); 

          }//fecha o else */
        
        } //fecha for

      }//fecha o if trim($oLinha->codigomatricula) != ""
      
    }//fecha o else
    
    $oDadosturma = $this->getDadosTurma($oLinha);            
    if ($oDadosturma != null) {    	      
      
      $oDaoTurma->ed57_i_codigoinep = $oDadosturma->ed57_i_codigoinep;
      $oDaoTurma->ed57_i_codigo     = $oDadosturma->ed57_i_codigo;
      $oDaoTurma->alterar($oDaoTurma->ed57_i_codigo);
      
      if ($oDaoTurma->erro_status == '0') {
        throw new Exception("Erro na alteração da Matricula do Aluno. Erro da classe: ".$oDaoTurma->erro_msg);        
      }//fecha o if err_status       
      
    }//fecha o if $oDadosturma != null
           
  }//fecha a funcao atualizaSituacaoAluno
  
  
  /**
   * funcao que importa os dados selecionados no arquivo txt
   * @override
   */
  function importarArquivo () {

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

    try {
    	
      $this->validaArquivo();  
      $this->getLinhasArquivo($this->iCodigoLayout);      
      $aLinhasArquivo = $this->getLinhasArquivo();
      
    } catch (Exception $eException) {
      throw new Exception("{$sMsgErro}{$eException->getMessage()}");
    }
    
    foreach ($aLinhasArquivo as $iIndLinha => $oLinha) {    
      
      if ($oLinha->tiporegistro == "89") {
        continue;                    
      } 
      if ($oLinha->tiporegistro == "90") {
        $this->atualizaSituacaoAluno($oLinha);
      }//fecha o if do tipo de registro 90
        
    }//fecha o foreach
    
  }//fecha a funcao importarMatriculaInep
  
 }//fecha classe
?>