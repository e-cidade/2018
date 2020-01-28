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
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");

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
<body bgcolor=#CCCCCC onLoad="a=1">
<form class="container" name="form1" method="post" action="">
  <fieldset>
  	<legend>Posição dos Parcelamentos</legend>
    <table  class="form-container">    
      <tr> 
        <td>
          Opções:
        </td>
        <td>  
          <select name="ver">
            <option name="condicao2" value="com">Com as Regras selecionadas</option>
            <option name="condicao2" value="sem">Sem as Regras selecionadas</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>
          <?
            // $aux = new cl_arquivo_auxiliar;
            $aux->cabecalho = "Regras de Parcelamento";
            $aux->codigo = "k40_codigo"; //chave de retorno da func
            $aux->descr  = "k40_descr";   //chave de retorno
            $aux->nomeobjeto = 'regras';
            $aux->funcao_js = 'js_mostra';
            $aux->funcao_js_hide = 'js_mostra1';
            $aux->sql_exec  = "";
            $aux->func_arquivo = "func_cadtipoparc.php";  //func a executar
            $aux->nomeiframe = "db_iframe_cadtipoparc";
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
</form>
</body>
</html>
<script>

$("fieldset_regras").addClassName("separator");
$("regras").style.width = "100%";
$("k40_codigo").addClassName("field-size1");
$("k40_descr").addClassName("field-size4");
   
</script>