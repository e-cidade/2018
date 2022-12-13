<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_bens_classe.php");
include("classes/db_clabens_classe.php");
$clbens = new cl_bens;
$clclabens = new cl_clabens;
$cldb_estrut = new cl_db_estrut;
$clbens->rotulo->label("t52_bem");
$clbens->rotulo->label("t52_descr");
$clclabens->rotulo->label("t64_class");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
      <center>
      <table border="0" align="center">
        <tr>
	  <td>
            <table border="0" align="center">      
            <form name="form1" method="post" action="" >	   
              <tr>
                <td align="left" nowrap title="<?=$Tt52_bem?>">
                                 <?=$Lt52_bem?>
                </td>
                <td align="left" nowrap>
                                 <?
                    db_input("t52_bem",10,$It52_bem,true,"text",4,"","chave_t52_bem");
	                         ?>
                </td>
              </tr>
              <tr>
                                 <?
                    $cldb_estrut->autocompletar = true;
                    $cldb_estrut->funcao_onchange = 'js_troca(this.value)';
                    $cldb_estrut->nomeform = 'form1';
                    $cldb_estrut->mascara = false;
                    $cldb_estrut->reload  = false;
                    $cldb_estrut->input   = false;
                    $cldb_estrut->size    = 10;
                    $cldb_estrut->nome    = "t64_class";
                    $cldb_estrut->db_opcao= 1;
                    $cldb_estrut->db_mascara('12');
                                 ?>
              </tr>
              <tr>
                <td align="left" nowrap title="<?=$Tt52_descr?>">
                                 <?=$Lt52_descr?>
                </td>
                <td align="left" nowrap>
                <?
                    db_input("t52_descr",40,$It52_descr,true,"text",4,"","chave_t52_descr");
                ?>
                </td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                  <input name="limpar" type="reset" id="limpar" value="Limpar" >
                  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_func_consbensdepart001.hide();">
                </td>
              </tr>
            </form>
	    </table>
	  </td>
	</tr>
	<tr>
	  <td>
            <table border="0" >
	      <tr>
                                <?
		$campos = " distinct t52_bem,t64_class,t52_descr,t52_depart,descrdepto ";
                if(isset($t52_depart)){
		  if(isset($t64_class) && trim($t64_class) != ""){
		    //rotina q retira os pontos do estrutural da classe e busca o código do estrutural na tabela clabens
		    $t64_class = str_replace(".","",$t64_class);
		    $result_t64_codcla = $clclabens->sql_record($clclabens->sql_query_file(null,"t64_codcla as chave_t64_codcla",null," t64_class = $t64_class "));
		    if($clclabens->numrows>0){
		      db_fieldsmemory($result_t64_codcla,0);
		    }else{
		      $chave_t64_codcla = 'NDA';
		    }
		  }
		  if(isset($chave_t52_bem) && (trim($chave_t52_bem)!="") ){
			   $sql = $clbens->sql_query("",$campos,"t52_bem","t52_bem = $chave_t52_bem and t52_depart = $t52_depart");
		  }else if(isset($chave_t64_codcla) && (trim($chave_t64_codcla)!="") ){
		    if($chave_t64_codcla == 'NDA'){
			   $sql = $clbens->sql_query("",$campos,""," t52_codcla = -1 and t52_depart = $t52_depart");
		    }else{
			   $sql = $clbens->sql_query("",$campos,""," t52_codcla = $chave_t64_codcla and t52_depart = $t52_depart");
		    }
		  }else if(isset($chave_t52_descr) && (trim($chave_t52_descr)!="") ){
			   $sql = $clbens->sql_query("",$campos,"t52_descr","t52_depart = $t52_depart and t52_descr like '$chave_t52_descr%'");
		  }else{
			   $sql = $clbens->sql_query("",$campos,"","t52_depart = $t52_depart");
		  }
                  db_lovrot($sql,15,"()","",$funcao_js);
                }
		                ?>
              </tr>
            </table>  
          </td>
        </tr>
      </table>
      </center>
    </td>
  </tr>
</table>
</body>
</html>
<script>
function js_troca(obj){
      js_mascara02_t64_class();
}
</script>