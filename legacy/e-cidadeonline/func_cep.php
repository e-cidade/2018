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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("dbforms/db_funcoes.php");
include("classes/db_db_consultacep_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcep = new cl_db_consultacep;
$clrotulo = new rotulocampo;
$clrotulo->label("db10_cep");
$clrotulo->label("db10_munic");
$clrotulo->label("db11_logradouro");
$clrotulo->label("cp06_numinicial");
if(!isset($db10_munic)){
$sqlcep= "select munic from db_config";
$resultcep=db_query($sqlcep);
db_fieldsmemory($resultcep,0);
$db10_munic = strtoupper(trim($munic));
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css"><?db_estilosite();?></style>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="<?=$w01_corbody?>" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="90%" height="100%" border="0"  align="center" cellspacing="0" bgcolor="<?=$w01_corbody?>" >
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0" class="texto">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tdb10_cep?>">
              <?=$Ldb10_cep?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
	        db_input("db10_cep",7,$Idb10_cep,true,"text",4,"");
	      ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tdb10_munic?>">
              <?=$Ldb10_munic?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
	        db_input("db10_munic",40,$Idb10_munic,true,"text",4,"");
	      ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tdb11_logradouro?>">
              <?=$Ldb11_logradouro?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
	        db_input("db11_logradouro",40,$Idb11_logradouro,true,"text",4,"");
	      ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tcp06_numincial?>">
              <? echo "<b>Número: </b>";?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
	        db_input("cp06_numinicial",10,$Icp06_numinicial,true,"text",4,"");
	      ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center" nowrap> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" class="botao"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar"  class="botao">
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cep.hide();" class="botao">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?


       $propaga["db10_cep"] = @$db10_cep;
       $propaga["db11_logradouro"] = @$db11_logradouro;
       $propaga["db10_munic"] = @$db10_munic;
       $propaga["cp06_numincial"] = @$cp06_numincial;
       $clcep->propagar = $propaga;
      
      if(isset($pesquisar)){
        if(isset($db10_cep) && $db10_cep != ""){
	  if(isset($municipio) && $municipio != ""){
	    $clcep->buscacep($db10_cep,"","","",$funcao_js,$municipio);
	  }else{
	    $clcep->buscacep($db10_cep,"","","",$funcao_js);
	  }
	}
        if(isset($db10_munic) && $db10_munic != ""){
          
	  if(isset($db11_logradouro) && $db11_logradouro != ""){ 
	    if(isset($cp06_numinicial) && $cp06_numinicial != ""){
	      $clcep->buscacep("",$db10_munic,$db11_logradouro,$cp06_numinicial,$funcao_js);
	    }else{  
	   
	      $clcep->buscacep("",$db10_munic,$db11_logradouro,"",$funcao_js);
	    }  
	  }else{
	    $clcep->buscacep("",$db10_munic,"","",$funcao_js);
	  }
	}
        if(isset($db11_logradouro) && $db11_logradouro != ""){
	  if(isset($cp06_numinicial) && $cp06_numinicial != ""){
	    $clcep->buscacep("","",$db11_logradouro,$cp06_numinicial,$funcao_js);
	  }else{  
	    $clcep->buscacep("","",$db11_logradouro,"",$funcao_js);
	  }
	}
      }
      if(isset($pesquisa_chave)){
	if($pesquisa_chave != ""){
	  if(isset($municipio) && $municipio != ""){
	    $clcep->buscacep($pesquisa_chave,"","","",$funcao_js,$municipio);
	  }else{
	    $clcep->buscacep($pesquisa_chave,"","","",$funcao_js);
	  }
	}
      }
      if(isset($filtroquery)){
	//$propaga["db10_munic"] = @$db10_munic;
	  
	db_lovrot("",15,"()","",$funcao_js,"","NoMe",$propaga);
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
document.form2.db10_cep.focus();
document.form2.db10_cep.select();
  </script>
  <?
}
?>