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

class cl_sqlmatriculas {
   function sqlmatriculas_ruas($pesquisaRua=0){
    $sql = "select iptubase.j01_matric, cgm.z01_nome,cgm.z01_ender,
                   cgm.z01_munic,cgm.z01_cep,cgm.z01_uf, j34_setor,                               j34_quadra, j34_lote,j34_idbql as db_idbql,
                   (CASE WHEN j49_idbql is null THEN 'secundario' ELSE 'principal' END) as dl_Tipo
            from testada
                 inner join iptubase on j01_idbql = j36_idbql
                 inner join cgm on z01_numcgm = j01_numcgm
                 inner join lote on j34_idbql = j36_idbql
                 left outer join testpri on j49_idbql = j36_idbql and j49_face = j36_face";
	 if($pesquisaRua!=0){
       $sql .= " where j36_codigo = $pesquisaRua";
     }
     return $sql;
   }
   function sqlmatriculas_setor($pesquisasetor=0){
    $sql = "select lote.j34_idbql as db_lote, j34_setor, j34_quadra, j34_lote
	              , j34_area, j34_areal,j01_matric,
	              ruas.j14_nome, bairro.j13_descr
                  from lote
				  inner join bairro on j13_codi = j34_bairro
				  inner join iptubase on j01_idbql = j34_idbql
                  left outer join testpri on j34_idbql = j49_idbql
				  left outer join ruas on j14_codigo = j49_codigo";
	 if($pesquisasetor!=0){
       $sql .= " where j34_setor = $pesquisasetor";
     }
	 return $sql;
   }
   function sqlmatriculas_setorQuadra($pesquisasetor="",$pesquisaquadra=""){
    $sql = "select lote.j34_idbql as db_lote, j34_setor, j34_quadra, j34_lote
	              , j34_area, j34_areal,j01_matric,
	              ruas.j14_nome, bairro.j13_descr
                  from lote
				  inner join bairro on j13_codi = j34_bairro
				  inner join iptubase on j01_idbql = j34_idbql
				  left outer join testpri on j34_idbql = j49_idbql
				  left outer join ruas on j14_codigo = j49_codigo";
	 if($pesquisasetor != ""){
       $sql .= " where j34_setor = '".strtoupper($pesquisasetor)."' and j34_quadra = '".strtoupper($pesquisaquadra)."'";
     }
	 return $sql;
   }
   function sqlmatriculas_IDBQL($pesquisaPorIDBQL=0){
	$sql = "
	select distinct * from (  select j01_matric, 'PROPRIETARIO'::varchar(12) as proprietario, j01_idbql, cgm.z01_nome
                                      from iptubase
                                      inner join cgm on j01_numcgm = z01_numcgm
                                      where j01_idbql = $pesquisaPorIDBQL
	) as dados
									  inner join lote on j34_idbql = j01_idbql
									  left outer join testpri on j49_idbql = j01_idbql
									  left outer join ruas on j49_codigo = j14_codigo
									  left outer join bairro on j34_bairro = j13_codi
	";
 	 return $sql;
   }
   function sqlmatriculas_nome($pesquisaPorNome=0){
	$sql = "
	select distinct * from (  select j01_matric, 'PROPRIETARIO'::varchar(12) as proprietario, j01_idbql, cgm.z01_nome
                                      from iptubase
                                      inner join cgm on j01_numcgm = z01_numcgm
                                      where j01_numcgm = $pesquisaPorNome
    union
                                      select j01_matric, 'OUTRO PROPR'::varchar(12) as proprietario, j01_idbql, cgm.z01_nome
                                      from propri
                                      inner join iptubase on j42_matric = j01_matric
                                      inner join cgm on j42_numcgm = z01_numcgm
                                      where j42_numcgm = $pesquisaPorNome
                                      union
                                      select j01_matric, 'PROMITENTE'::varchar(12) as proprietario, j01_idbql, cgm.z01_nome
                                      from promitente
                                      inner join iptubase on j41_matric = j01_matric
                                      inner join cgm on j41_numcgm = z01_numcgm
                                      where j41_numcgm = $pesquisaPorNome
	) as dados
									  inner join lote on j34_idbql = j01_idbql
									  left outer join testpri on j49_idbql = j01_idbql
									  left outer join ruas on j49_codigo = j14_codigo
									  left outer join bairro on j34_bairro = j13_codi
	";
 	 return $sql;
   }
   function sqlmatriculas_imobiliaria($pesquisaPorImobiliaria=0){
	$sql = "
    select distinct * from (  select j01_matric, c.z01_nome as proprietario, j01_idbql, cgm.z01_nome
                                      from imobil
									  inner join iptubase on j44_matric = j01_matric
                                      inner join cgm on j01_numcgm = cgm.z01_numcgm
									  inner join cgm c on j44_numcgm = c.z01_numcgm 		
                                      where j44_numcgm = $pesquisaPorImobiliaria
	) as dados
									  inner join lote on j34_idbql = j01_idbql
									  left outer join testpri on j49_idbql = j01_idbql
									  left outer join ruas on j49_codigo = j14_codigo
									  left outer join bairro on j34_bairro = j13_codi
	";
 	 return $sql;
   }
   function sqlmatriculas_bairros($pesquisaBairro=0){
	$sql = "
  select iptubase.j01_matric, cgm.z01_nome,cgm.z01_ender,cgm.z01_munic,cgm.z01_cep,cgm.z01_uf ,lote.*
          from lote
          inner join iptubase on j34_idbql = j01_idbql
		  inner join cgm on z01_numcgm = j01_numcgm

	";
	 if($pesquisaBairro!=0){
       $sql .= "where j34_bairro = $pesquisaBairro";
     }
	 return $sql;
   }
}

class cl_ruas {
  var $rotulo = null;
  var $db_erro = null;

  function cl_ruas() {
    $this->rotulo = new rotulo("ruas");
  }
  function cldb_erro($uri){
     echo "<script>location.href=\"db_erros.php?db_erro=";
	 echo $this->$db_erro;
	 echo "&acao=$uri\"</script>";
  } 
  function dadosCodigo($filtro = "") {
    $result = db_query("select * from ruas ".($filtro != ""?"where j14_codigo = $filtro":"")." order by j14_codigo");
    if(pg_numrows($result) > 0)
	  return $result;
	else
	  $db_erro = 'Nenhum Registro Selecionado';
	  return false;
  }
  function sqldadosCodigo($filtro = "") {
    return "select j14_codigo, j14_nome, j14_tipo from ruas ".($filtro==""?"":" where j14_codigo = $filtro")." order by j14_codigo";
  }
  function sqldadosNome($filtro = "") {
    return "select j14_codigo, j14_nome, j14_tipo from ruas where j14_nome like '$filtro%' order by j14_nome";
  }
  function dadosNome($filtro = "") {
    $result = db_query("select * from ruas ".($filtro != ""?"where j14_nome like '$filtro%'":"")." order by j14_nome");
    if(pg_numrows($result) > 0)
	  return $result;
	else
	  $db_erro = 'Nenhum Registro Selecionado';
	  return false;
  }
  function incluir($codigo,$nome,$tipo) {
    if($codigo==""){
	   $this->$db_erro = 'Código da Rua/Avenida Inválido';
	   return false;
	}
    if($nome==""){
	   $this->$db_erro = 'Descrição da Rua/Avenida deverá ser preenchida.';
	   return false;
	}
    $result = @db_query("insert into ruas(j14_codigo,j14_nome,j14_tipo) values($codigo,'$nome','$tipo')");
    if($result == false){
	   $this->$db_erro = 'Contate Administrador.';
    }
	if(@pg_cmdtuples($result) > 0)
	  return true;
	else{
      $this->$db_erro = 'Rua/Avenida nao Incluida. Verifique.';
	  return false;
	}
  }
  function alterar($codigo,$nome,$tipo) {
    if($codigo==""){
	   $this->$db_erro = 'Código da Rua/Avenida Inválido';
	   return false;
	}
    $result = @db_query("update ruas set j14_nome = '$nome',
	                                   j14_tipo = '$tipo'
							 where j14_codigo = $codigo");
	if(@pg_cmdtuples($result) > 0)
	  return true;
	else{
      $this->$db_erro = 'Rua/Avenida nao Alterada. Verifique.';
	  return false;
	}
  }
  function excluir($codigo) {
    if($codigo==""){
	   $this->$db_erro = 'Código do Logradouro Inválido';
	   return false;
	}
    $result = @db_query("delete from ruas where j14_codigo = $codigo");
	if(@pg_cmdtuples($result) > 0)
	  return true;
	else{
      $this->$db_erro = 'Rua/Avenida nao Excluida. Verifique.';
	  return false;
	}
  }
}
class cl_bairro {
  var $rotulo = null;
  function cl_bairro() {
    $this->rotulo = new rotulo("bairro");
  }
  function dadosCodigo($filtro = "") {
    $result = db_query("select * from bairro ".($filtro != ""?"where j13_codi = $filtro":"")." order by j13_codi");
    if(pg_numrows($result) > 0)
	  return $result;
	else
	  return false;
  }
  function dadosNome($filtro = "") {
    $result = db_query("select * from bairro ".($filtro != ""?"where j13_descr like '$filtro%'":"")." order by j13_descr ");
    if(pg_numrows($result) > 0)
	  return $result;
	else
	  return false;
  }
  function sqldadosNome($filtro = "") {
    return "select j13_codi,  j13_descr, j13_codant from bairro where j13_descr like '$filtro%' order by j13_descr ";
  }
  function sqldadosCodigo($filtro = "") {
    return "select j13_codi,  j13_descr, j13_codant from bairro where j13_codi = $filtro order by j13_codi";
  }
  function incluir($codigo,$nome,$codant) {
    if($codigo=="")
	   return false;
    $result = db_query("insert into bairro(j13_codi,j13_descr,j13_codant) values($codigo,'$descr','$codant')");
	if(pg_cmdtuples($result) > 0)
	  return true;
	else
	  return false;
  }
  function alterar($codigo,$descr,$codant) {
    if($codigo=="")
	   return false;
    $result = db_query("update bairro set j13_descr = '$descr',
	                                     j13_coant = '$coant'
							 where j13_codi = $codigo");
	if(pg_cmdtuples($result) > 0)
	  return true;
	else
	  return false;
  }
  function excluir($codigo) {
    if($codigo=="")
	   return false;
    $result = db_query("delete from bairro where j13_codi = $codigo");
	if(pg_cmdtuples($result) > 0)
	  return true;
	else
	  return false;
  }
}
class cl_setor {
  var $rotulo = null;
  function cl_setor() {
    $this->rotulo = new rotulo("setor");
  }
  function dadosCodigo($filtro = "") {
    $result = db_query("select * from setor ".($filtro != ""?"where j30_codi = $filtro":"")." order by j30_codi");
    if(pg_numrows($result) > 0)
	  return $result;
	else
	  return false;
  }
  function dadosNome($filtro = "") {
    $result = db_query("select * from setor ".($filtro != ""?"where j30_descr like '$filtro%'":"")." order by j30_descr ");
    if(pg_numrows($result) > 0)
	  return $result;
	else
	  return false;
  }
  function incluir($codigo,$descr,$alipre = 0,$aliter = 0) {
    if($codigo=="")
	   return false;
    $result = db_query("insert into setor(j30_codi,j30_descr,j30_alipre,j30_aliter) values($codigo,'$descr',$alipre,$aliter)");
	if(pg_cmdtuples($result) > 0)
	  return true;
	else
	  return false;
  }
  function alterar($codigo,$descr,$alipre = 0,$aliter = 0) {
    if($codigo=="")
	   return false;
    $result = db_query("update setor set j30_descr = '$descr',
	                                    j30_alipre = $alipre,
										j30_aliter = $aliter
							 where j30_codi = $codigo");
	if(pg_cmdtuples($result) > 0)
	  return true;
	else
	  return false;
  }
  function excluir($codigo) {
    if($codigo=="")
	   return false;
    $result = db_query("delete from setor where j30_codi = $codigo");
	if(pg_cmdtuples($result) > 0)
	  return true;
	else
	  return false;
  }
}
?>