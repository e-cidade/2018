<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
$cliframe_seleciona = new cl_iframe_seleciona;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function js_AbreJanelaRelatorio() { 
    vir = "";
    cods= "";
    for(y=0;y<document.getElementById("matriculas").length;y++){
      var_if = document.getElementById("matriculas").options[y].value;
      cods += vir + var_if;
      vir = ",";
    }    
   jan = window.open('dvr2_capaloteamento002.php?matric='+cods+'&loteam='+document.form1.loteam.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>

       <form class="container" name="form1" method="post"  >
       <fieldset>
       <legend>Relatórios - Capa dos Parc. dos Loteamentos</legend>
        <table class="form-container">
          <tr> 
            <td>Loteamento:</td>
            <td>
			   <select name="loteam" id="select5">
                <option value="1284" selected>1284 - SOL NASCENTE </option>
                <option value="221">221 - POPULAR POR DO SOL</option>
              </select> 
            </td>
          </tr>
  </table>
	<table>
	  <tr>
	    <td align="center">
	      <?
	      $aux = new cl_arquivo_auxiliar;
	      $aux->cabecalho = "RECURSOS";
	      $aux->codigo = "j01_matric";
	      $aux->descr  = "z01_nome";
	      $aux->nomeobjeto = 'matriculas';
	      $aux->funcao_js = 'js_mostra';
	      $aux->funcao_js_hide = 'js_mostra1';
	      $aux->sql_exec  = "";
	      $aux->func_arquivo = "func_iptubase.php";
	      $aux->nomeiframe = "db_iframe_iptubase";
	      $aux->localjan = "";
	      $aux->db_opcao = 2;
	      $aux->tipo = 2;
	      $aux->linhas = 6;
	      $aux->vwhidth = 600;
	      $aux->funcao_gera_formulario();
	      ?>
	    </td>
	  </tr>
	</table>
</fieldset>
	      <input name="processar" type="submit" id="processar" value="Imprimir" onClick="js_AbreJanelaRelatorio()"> 

        </form>

<?

  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

?>

</body>
</html>
<script>

$("fieldset_matriculas").addClassName("separator");
$("j01_matric").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");
$("matriculas").setAttribute("rel","ignore-css");
$("matriculas").style.width = "100%";

</script>