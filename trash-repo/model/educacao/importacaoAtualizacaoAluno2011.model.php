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

class importacaoAtualizacaoAluno2011 extends importacaoCenso {     

  /**
   * Funcao que seleciona e atualiza os dados dos alunos para CLIENTES NOVOS que utilizamos nas funcoes
   * atualizaAluno,atualizaEnderecoAluno,AtualizaDadosAdicionais
   * @param object $oLinha com os campos contidos em uma linha de importacao (conforme seu tipo de registro) 
   * @return object  com os dados do aluno se encontra-lo atraves dos dados contidos em $oLinha, 
   * caso contrario retorna null
   *  @param boolean $lbuscaNome = true busca pelo nome, data nascimento, nome mae
   *                              false busca pelo codigo inep do aluno
   * @override 
   */
  function getDadosAluno($oLinha) {

  	$oDaoAluno     = db_utils::getdao('aluno');
  	$sCamposAluno  = "distinct ed47_i_codigo,ed47_v_nome,aluno.*, ed228_i_paisonu, ";
  	$sCamposAluno .= "escola.ed18_c_codigoinep as vinculo_escola";
  	$sWhere        = "ed47_c_codigoinep = '".$oLinha->inepaluno."'";
    $sSqlAluno     = $oDaoAluno->sql_query_censo("", $sCamposAluno, "", $sWhere);       
    $rsAluno       = $oDaoAluno->sql_record($sSqlAluno);

    /* Nao encontrou o aluno pelo codigo inep, entao tenta encontrar pelo nome, data de nascimento,nome da mae */
  	if ($oDaoAluno->numrows <= 0 && isset($oLinha->nomealuno)) {
  		  
  	  $sNomeAlunoCensoNovo = str_replace(array('ª', 'º'), array('', ''), $oLinha->nomealuno);  
      $sNomeMae            = str_replace(array('ª', 'º'), array('', ''), $oLinha->mae);
      $dNascAluno          = substr($oLinha->nascaluno, 6, 4)."-".substr($oLinha->nascaluno, 3, 2).
                             "-".substr($oLinha->nascaluno, 0, 2);    
    
      $sWhere              = " ((to_ascii(ed47_v_nome, 'LATIN1') = '".$sNomeAlunoCensoNovo."'";
      $sWhere             .= "   AND ed47_d_nasc =".$dNascAluno.") OR ";
      $sWhere             .= "  (to_ascii(ed47_v_nome, 'LATIN1') = '".$sNomeAlunoCensoNovo."'";
      $sWhere             .= "   AND to_ascii(ed47_v_mae, 'LATIN1') = '".$sNomeMae."'))";  
            
   } 
   
   $sSqlAluno = $oDaoAluno->sql_query_censo("", $sCamposAluno, "", $sWhere);       
   $rsAluno   = $oDaoAluno->sql_record($sSqlAluno);
  
   if ($oDaoAluno->numrows > 0) {   
     return $aDadosAluno = db_utils::getColectionByRecord($rsAluno, false, false, false);       
   } else {
     return null;
   } //fecha o else
                    
 } //fecha a funcao getDadosAluno	
	  
}//fecha a classe
?>