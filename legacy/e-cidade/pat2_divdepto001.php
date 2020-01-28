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
$aux = new cl_arquivo_auxiliar;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Relat�rios - Cadastrais/Divis�es por Departamento</legend>
    <table class="form-container">
      <tr> 
        <td>
          Op��es:
        </td>
        <td>
          <select name="ver">
            <option name="condicao1" value="com">Com os departamentos selecionados</option>
            <option name="condicao1" value="sem">Sem os departamentos selecionados</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>
          Listar departamentos:
        </td>
        <td>
          <?
            $x = array("T"=>"TODOS","true"=>"ATIVOS","false"=>"INATIVOS");
            db_select("listar_depart",$x,true,4);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          Listar divisoes:
        </td>
        <td>
          <?
            $x = array("T"=>"TODAS","true"=>"ATIVAS","false"=>"INATIVAS");
            db_select("listar_divisao",$x,true,4);
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <?
            // $aux = new cl_arquivo_auxiliar;
            $aux->cabecalho = "<strong>Departamentos</strong>";
            $aux->codigo = "coddepto"; //chave de retorno da func
            $aux->descr  = "descrdepto";   //chave de retorno
            $aux->nomeobjeto = 'departamentos';
            $aux->funcao_js = 'js_mostra';
            $aux->funcao_js_hide = 'js_mostra1';
            $aux->sql_exec  = "";
            $aux->func_arquivo = "func_db_depart.php";  //func a executar
            $aux->nomeiframe = "db_iframe_db_depart";
            $aux->localjan = "";
            $aux->onclick = "";
            $aux->db_opcao = 2;
            $aux->tipo = 2;
            $aux->top = 0;
            $aux->linhas = 10;
            $aux->vwhidth = 400;
            $aux->funcao_gera_formulario();
      	  ?>
        </td>
      </tr>  
    </table>
  </fieldset>
  <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_mandadados();" >
</form>
    <?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_mandadados(){
 query="";
 vir="";
 listadepart="";
 
 for(x=0;x<document.form1.departamentos.length;x++){
  listadepart+=vir+document.form1.departamentos.options[x].value;
  vir=",";
 } 
 
 query += '&listadepart='+listadepart;
 query += '&verdepart='+document.form1.ver.value;
 query += '&listar_depart='+document.form1.listar_depart.value;
 query += '&listar_divisao='+document.form1.listar_divisao.value;

 
 jan = window.open('pat2_divdepto002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
 
}
</script>
<script>

$("fieldset_departamentos").addClassName("separator");
$("coddepto").addClassName("field-size2");
$("descrdepto").addClassName("field-size7");
$("departamentos").style.width = "100%";

</script>