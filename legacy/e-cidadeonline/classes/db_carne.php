<?
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

class cl_carne {
  var $rotulo = null;
  var $db_erro = null;
  var $i = null;
  var $np = null;
  var $npi= null;
  var $npf= null;
  var $sql = null;
  var $resultparcelas = null;
  var $result = null;
  
  function cl_carne($numpre,$numparini,$numparfim) {
    $this->np  = $numpre;
    $this->npi = $numparini;
    $this->npf = $numparfim;	
  }  
  function verifica(){
    if($this->np == 0 or $this->np == ""){
	   $this->db_erro = "Numpre Inválido.";
	   return false;
	}
    if($this->npi == ""){
	   $this->npi = 0;
	}
    if($this->npf == ""){
	   $this->npf = 0;
	}
    $this->sql = "select distinct a.k00_numpre,a.k00_numpar
	              from arrecad a
				  where k00_numpre = $this->np ";
    if($this->npi!=0){
      if($this->npf!=0){
	    $this->sql .= " and k00_numpar between $this->npi and $this->npf";
      }else{
	    $this->sql .= " and k00_numpar = $this->npi";
	  }
	}
    $this->resultparcelas = db_query($this->sql);
	if(pg_numrows($this->resultparcelas)==0){
	  $this->db_erro = "Código de Arrecadacao nao Encontrado ou Quitado.";
	  return false;
	}
	return true;
  }
  function calcula_valores(){
     $this->sqlporreceita = "
	               select k00_numpre,k00_numpar,k00_receit,k00_dtvenc,k00_dtoper,sum(k00_valor) as k00_valor 
	               from arrecad 
				   where k00_numpre = $this->np and ( ";
	 $or = "";
	 echo pg_numrows($this->resultparcelas);			   
	 for($i=0;$i < $this->resultparcelas;$i++){
	   $this->sqlporreceita .= $or." k00_numpar = ".pg_result($this->resultparcelas,$i,"k00_numpar");
	   $or = " or ";
     }
     $this->sqlporreceita .= ") group by k00_numpre,k00_numpar,k00_receit,k00_dtvenc,k00_dtoper";
     return true;
  }
}
?>