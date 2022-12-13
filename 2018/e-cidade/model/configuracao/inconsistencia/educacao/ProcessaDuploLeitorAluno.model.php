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

require_once modification("model/configuracao/inconsistencia/iExcecaoProcessamentoDependencias.interface.php");

/**
 * Processa as exceções encontradas quando tentamos remover os duplos de leitoraluno
 * @package configuracao
 * @subpackage inconsistencia 
 * @subpackage educacao
 * @author Andrio <andrio.costa@dbseller.com.br>
 * @author Fabio <fabio.esteves@dbseller.com.br>
 */
class ProcessaDuploLeitorAluno implements IExcecaoProcessamentoDependencias {
  
	/**
	 * Armazena o SQL que gerou erro
	 * @var string
	 */
  private $sMensagemErro;
  
  /**
   * Busca os dados do aluno incorreto, percorrendo as tabelas leitor, leitoraluno e carteira. Caso seja encontrado 
   * algo, segue a seguinte ordem:
   * 1º - Verifica se possui carteira. Caso possua, atualiza o campo bi16_valida para 'N' e o codigo do leitor, para o 
   *      leitor correto
   * 2º - Exclui o registro da tabela leitoraluno do cadastro incorreto
   * 3º - Exclui o registro da tabela leitor do cadastro incorreto
   * @param integer $iChaveCorreta - codigo do aluno correto
   * @param integer $iChaveIncorreta - codigo do aluno incorreto
   * @return boolean
   * @see IExcecaoProcessamentoDependencias::processar()
   */
  public function processar($iChaveCorreta, $iChaveIncorreta) {

  	/**
     * Buscamos os dados do leitor incorreto
  	 */
  	$oDaoLeitorAluno    = new cl_leitoraluno();
  	$sCamposLeitorAluno = "bi11_leitor, bi11_aluno, bi16_codigo";
  	$sWhereLeitorAluno  = "bi11_aluno = {$iChaveIncorreta}";
  	$sSqlLeitorAluno    = $oDaoLeitorAluno->sql_query_leitoraluno_carteira(
  		                                                                    	null,
  		                                                                    	$sCamposLeitorAluno,
  		                                                                    	null,
  		                                                                    	$sWhereLeitorAluno
  		                                                                    );
  	$rsLeitorAluno      = $oDaoLeitorAluno->sql_record($sSqlLeitorAluno);
  	
  	/**
  	 * Caso tenha sido encontrado cadastro de leitor aluno, buscamos os dados do leitor correto, para atualizar as 
  	 * informacoes do aluno incorreto
  	 */
  	if ( $oDaoLeitorAluno->numrows > 0 ) {
  		
  		$oDadosIncorreto = db_utils::fieldsMemory($rsLeitorAluno, 0);
  		
  		/**
  		 * Buscamos os dados do leitor correto, para atualizar as informacoes do aluno incorreto
  		 */
  		$oDaoLeitorAlunoCorreto    = new cl_leitoraluno();
  		$sCamposLeitorAlunoCorreto = "bi11_leitor";
  		$sWhereLeitorAlunoCorreto  = "bi11_aluno = {$iChaveCorreta}";
  		$sSqlLeitorAlunoCorreto    = $oDaoLeitorAlunoCorreto->sql_query_leitoraluno_carteira(
  		                                                                                    	null,
  		                                                                                    	$sCamposLeitorAlunoCorreto,
  		                                                                                    	null,
  		                                                                                    	$sWhereLeitorAlunoCorreto
  		                                                                                    );
  		$rsLeitorAlunoCorreto      = $oDaoLeitorAlunoCorreto->sql_record($sSqlLeitorAlunoCorreto);
  		 
  		if ( $oDaoLeitorAlunoCorreto->numrows > 0 ) {
  			$iLeitorCorreto = db_utils::fieldsMemory($rsLeitorAlunoCorreto, 0)->bi11_leitor;
  		} else {
        
        // Insere aluno na tabela leitor caso ainda não possua registro
        $oDaoLeitor = new cl_leitor();
        $oDaoLeitor->incluir( null );
        
        if ( $oDaoLeitor->erro_status == "0" ) {
	  			
	  			$this->sMensagemErro = str_replace("\\n", "\n", $oDaoLeitor->erro_sql);
	  			return false;
	  		}
        
        $iLeitorCorreto = $oDaoLeitor->bi10_codigo;
        
        $oDaoLeitorAluno->bi11_leitor = $iLeitorCorreto;
        $oDaoLeitorAluno->bi11_aluno  = $iChaveCorreta;
        $oDaoLeitorAluno->incluir( null );
        
        if ( $oDaoLeitorAluno->erro_status == "0" ) {
	  			
	  			$this->sMensagemErro = str_replace("\\n", "\n", $oDaoLeitorAluno->erro_sql);
	  			return false;
	  		}
      }
  		
  		/**
  		 * Caso o leitor possua carteira cadastrada, alteramos as informacoes desta
  		 */
  		if ( !empty($oDadosIncorreto->bi16_codigo) ) {
  			
	  		$oDaoCarteira   = new cl_carteira();
	  		$oDaoCarteira->bi16_leitor = $iLeitorCorreto;
	  		$oDaoCarteira->bi16_codigo = $oDadosIncorreto->bi16_codigo;
	  		$oDaoCarteira->alterar($oDadosIncorreto->bi16_codigo);
	  		
	  		if ( $oDaoCarteira->erro_status == "0" ) {
	  			
	  			$this->sMensagemErro = str_replace("\\n", "\n", $oDaoCarteira->erro_sql);
	  			return false;
	  		}
  		}
  		
  		/**
  		 * Excluimos os dados de leitoraluno do leitor incorreto
  		 */
  		$sWhereLeitorAluno = "bi11_leitor = {$oDadosIncorreto->bi11_leitor} and bi11_aluno = {$oDadosIncorreto->bi11_aluno}";
  		$oDaoLeitorAluno->excluir(null, $sWhereLeitorAluno);
  		
  		if ( $oDaoLeitorAluno->erro_status == "0" ) {
  			
  			$this->sMensagemErro = str_replace("\\n", "\n", $oDaoLeitorAluno->erro_sql);
  			return false;
  		}
  		
  		/**
  		 * excluimos leitorcidadao
  		 */
  		$oDaoLeitorCidadao = new cl_leitorcidadao();
  		$oDaoLeitorCidadao->excluir(null, "bi28_leitor = {$oDadosIncorreto->bi11_leitor}");
  		if ($oDaoLeitorCidadao->erro_status == 0) {
  		  
  		  $this->sMensagemErro = str_replace("\\n", "\n", $oDaoLeitorCidadao->erro_sql);
  		  return false;
  		}
  		
  		/**
       * Excluimos o cadastro do leitor incorreto
  		 */
  		$oDaoLeitor   = new cl_leitor();
  		$sWhereLeitor = "bi10_codigo = {$oDadosIncorreto->bi11_leitor}";
  		$oDaoLeitor->excluir(null, $sWhereLeitor);
  		
  		if ( $oDaoLeitor->erro_status == "0" ) {
  			
  			$this->sMensagemErro = str_replace("\\n", "\n", $oDaoLeitor->erro_sql);
  			return false;
  		}
  	}
  	
    return true;
  }
  
  /**
   * Retorna uma mensagem com o SQL que gerou o erro
   * @return string
   * @see IExcecaoProcessamentoDependencias::getMensagemErro()
   */
  public function getMensagemErro() {
    return $this->sMensagemErro;
  }
}