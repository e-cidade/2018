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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_obrashabite_classe.php");

db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clobrashabite = new cl_obrashabite;
$clobrashabite->rotulo->label("ob09_codhab");
$clobrashabite->rotulo->label("ob09_habite");

$clRotulo 		 = new rotulocampo;
$clRotulo->label("j01_matric");
$clRotulo->label("z01_nome");
$clRotulo->label("ob06_setor");
$clRotulo->label("ob06_quadra");
$clRotulo->label("ob06_lote");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<style>
	fieldset {
		width: 500px;
		margin: 0 auto;
	}
</style>
</head>

<body bgcolor=#CCCCCC>
<form name="form2" method="post" action="" >
<fieldset>
	<legend><strong>Pesquisar</strong></legend>
	<table align="center">
		<tr> 
	    <td nowrap title="<?=$Tob09_codhab?>">
	      <?=$Lob09_codhab?>
	    </td>
	    <td nowrap> 
	      <?
		 			db_input("ob09_codhab",10,$Iob09_codhab,true,"text",1,"","chave_ob09_codhab");
		 		?>
	    </td>
	  </tr>
	  <tr> 
	    <td nowrap title="<?=$Tob09_habite?>">
	      <?=$Lob09_habite?>
	    </td>
	    <td  nowrap> 
	      <?
		 			db_input("ob09_habite",10,$Iob09_habite,true,"text",1,"","chave_ob09_habite");
		 		?>
	    </td>
	  </tr>
	  <tr>
	  	<td title="<?=@$Tj01_matric?>">
	  	  <?=$Lj01_matric?>
	    </td>
	    <td>
	    	<?
	    		db_input('j01_matric', 10, $Ij01_matric, true, 'text', 1)
	    	?>
	  	</td>
	  </tr>
	  
	  <tr>
	  	<td title="<?=@$Tob06_setor?>">
	  	  <strong>Setor/Quadra/Lote: </strong>
	    </td>
	    <td>
	    <?
	      db_input('ob06_setor',10,$Iob06_setor,true,'text',1,"")
	    ?>
	    /
	    <?
	      db_input('ob06_quadra',10,$Iob06_quadra,true,'text',1,"")
	    ?>
	    /
	    <?
	      db_input('ob06_lote',10,$Iob06_lote,true,'text',1,"")
	    ?>
	  	</td>
	  </tr>  
	</table>
</fieldset>
<br>	  
<center>	      
  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
  <input name="limpar" type="reset" id="limpar" value="Limpar" >
  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_obrashabite.hide();">
</center>

<table align="center">
	<tr>
		<td>
		<?
	    if(!isset($pesquisa_chave)  && !isset($constr)){                                                                                                   
	
	    	$aWherePesquisa = array();
	    	
	    	$campos = "ob09_codhab,
					         ob09_habite,
					         obras.ob01_codobra,
					         obras.ob01_nomeobra,
					         cgm.z01_nome,
					         ob09_data, 
					         ob24_iptubase,
					         ob06_setor,
					         ob06_quadra,
					         ob06_lote";                                
	    	                                                                                                                                                 
	    	if(isset($chave_ob09_codhab) && (trim($chave_ob09_codhab)!="") ){                                                                                
	    		$sSqlPesquisa = $clobrashabite->sql_query_obras_habite($chave_ob09_codhab,$campos,"");                                                                  
	    	}else if(isset($chave_ob09_habite) && (trim($chave_ob09_habite)!="") ){                                                                          
	    		$sSqlPesquisa = $clobrashabite->sql_query_obras_habite("",$campos,""," ob09_habite like '$chave_ob09_habite%' ");                                       
	    	}else if(isset($chave_unica) and ($chave_unica != '')) {                                                                                         
	    		$sSqlPesquisa = $clobrashabite->sql_query_obras_habite($chave_unica,$campos);   
	    		                                                                        
	    	}elseif( !empty($j01_matric) ) {
	      
	      	$aWherePesquisa [] = "ob24_iptubase = {$j01_matric}";
	      	 
	      	$sSqlPesquisa = $clobrashabite->sql_query_obras_habite("", $campos, "ob24_iptubase", implode(" and ", $aWherePesquisa));
	      	
	      	
	      } elseif( !empty($ob06_setor) || !empty($ob06_quadra) || !empty($ob06_quadra)){
	      	
	      	if(!empty($ob06_setor)) {
	      		$aWherePesquisa [] = "ob06_setor  = '{$ob06_setor}'";
	      	}
	      	if(!empty($ob06_quadra)) {
	      		$aWherePesquisa [] = "ob06_quadra = '{$ob06_quadra}'";
	      	}
	      	if(!empty($ob06_lote)) {
	      		$aWherePesquisa [] = "ob06_lote   = '{$ob06_lote}'";
	      	}
	      	
	      	$sSqlPesquisa = $clobrashabite->sql_query_obras_habite("",$campos,"ob01_codobra",implode(" and ", $aWherePesquisa) );
	      	
	      } else {                                                                                                                                           
	    		$sSqlPesquisa = $clobrashabite->sql_query_obras_habite("",$campos,"ob09_codhab desc","");                                                               
	    	}                                                                                                                                                
	    	db_lovrot($sSqlPesquisa,15,"()","",$funcao_js);                                                                                                           
	    	                                                                                                                                                 
	    }else{                                                                                                                                             
	    	                                                                                                                                                 
	    	if(isset($pesquisa_chave) && $pesquisa_chave!=null && $pesquisa_chave!=""){                                                                      
	    		                                                                                                                                               
	    		$result = $clobrashabite->sql_record($clobrashabite->sql_query_obras_habite($pesquisa_chave));                                                 
	    		                                                                                                                                               
	    		if($clobrashabite->numrows!=0){                                                                                                                
	    			db_fieldsmemory($result,0);                                                                                                                  
	    			echo "<script>".$funcao_js."('$ob09_habite',false);</script>";                                                                               
	    		}else{                                                                                                                                         
	    			echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";                                                   
	    		}                                                                                                                                              
	    		                                                                                                                                               
	    	}elseif(isset($constr) && $constr != null && $constr != ""){                                                                                     
	    		                                                                                                                                               
	    		$result = $clobrashabite->sql_record($clobrashabite->sql_query_obras_habite("","sum(ob09_area) as ob09_area",""," ob09_codconstr = $constr")); 
	    		                                                                                                                                               
	    		if($clobrashabite->numrows!=0){                                                                                                                
	    			db_fieldsmemory($result,0);                                                                                                                  
	    			echo "<script>".$funcao_js."('$ob09_area');</script>";                                                                                       
	    		}else{                                                                                                                                         
	    			echo "<script>".$funcao_js."('0');</script>";                                                                                                
	    		}                                                                                                                                              
	    		                                                                                                                                               
	    	}else{                                                                                                                                           
	    		echo "<script>".$funcao_js."('',false);</script>";                                                                                             
	    	}                                                                                                                                                
	    }                                                                                                                                                  
	  ?>
		</td>
	</tr>
</table>

</form>
</body>
</html>