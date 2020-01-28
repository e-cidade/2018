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


class cl_cgm {
  var $rotulo = null;

  function cl_cgm() {
    $this->rotulo = new rotulo("cgm");
  }
  function sqldadosNome($filtro = "") {
    return "select * from cgm ".($filtro != ""?"where z01_nome = '$filtro%'":"")." order by z01_nome";
  }
  function dadosNome($filtro = "") {
    $result = db_query($this->sqldadosNome($filtro));
    if(pg_numrows($result) > 0)
	  return $result;
	else
	  $db_erro = 'Nenhum Registro Selecionado';
	  return false;
  }
  function sqldadosCodigo($filtro = "") {
    return "select * from cgm ".($filtro != ""?"where z01_numcgm = $filtro":"")." order by z01_numcgm";
  }
  function dadosCodigo($filtro = "") {
    $result = db_query($this->sqldadosCodigo($filtro));
    if(pg_numrows($result) > 0)
	  return $result;
	else
	  $db_erro = 'Nenhum Registro Selecionado';
	  return false;
  }
}

?>