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
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_empempenho_classe.php");

//---  parser POST/GET
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

//---- instancia classes
$clempempenho = new cl_empempenho;
$cliframe_seleciona = new cl_iframe_seleciona;
$clrotulo = new rotulocampo;

//--- cria rotulos e labels
$clempempenho->rotulo->label();
//----
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC"  >
<table width="790" border="0" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td>  &nbsp; </td>
  </tr>  
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
       <form name="form1" method="post" action="">
       <table border="0">
       <tr> 
           <td align="center" colspan="2">
                <strong>Opções:</strong>
                <select name="veritem">
                    <option name="condicao5" value="com">Com os materiais/serviços ou subgrupo selecionados</option>
                    <option name="condicao5" value="sem">Sem os materiais/serviços ou subgrupo selecionadas</option>
                </select>
          </td>
       </tr>
       <tr>
          <td nowrap><table border="0">
               <?
                 $clmaterial = new cl_arquivo_auxiliar;
		 $clmaterial->nome_botao = "db_lanca_mat";
                 $clmaterial->cabecalho = "<strong> Material/Serviço </strong>";
                 $clmaterial->codigo = "pc01_codmater"; //chave de retorno da func
                 $clmaterial->descr  = "pc01_descrmater";   //chave de retorno
                 $clmaterial->nomeobjeto = 'item';
                 $clmaterial->funcao_js = 'js_mostra';
                 $clmaterial->funcao_js_hide = 'js_mostra1';
                 $clmaterial->sql_exec  = "";
                 $clmaterial->func_arquivo = "func_pcmater.php";  //func a executar
                 $clmaterial->nomeiframe = "db_iframe_pcmater";
                 $clmaterial->localjan = "";
                 $clmaterial->onclick = "";
                 $clmaterial->db_opcao = 2;
                 $clmaterial->tipo = 2;
                 $clmaterial->top = 1;
                 $clmaterial->linhas = 10;
                 $clmaterial->vwhidth = 400;
                 $clmaterial->funcao_gera_formulario();
              ?>    
          </table></td>
	  <td nowrap><table border="0">
       <?
                 $clsubgrupo = new cl_arquivo_auxiliar;
		 $clsubgrupo->nome_botao = "db_lanca_sub";
                 $clsubgrupo->cabecalho = "<strong>Subgrupo</strong>";
                 $clsubgrupo->codigo = "pc04_codsubgrupo";     //chave de retorno da func
                 $clsubgrupo->descr  = "pc04_descrsubgrupo";   //chave de retorno
                 $clsubgrupo->nomeobjeto = 'sub';
                 $clsubgrupo->funcao_js = 'js_mostra2';
                 $clsubgrupo->funcao_js_hide = 'js_mostra3';
                 $clsubgrupo->sql_exec  = "";
                 $clsubgrupo->func_arquivo = "func_pcsubgrupo.php";  //func a executar
                 $clsubgrupo->nomeiframe = "db_iframe_pcsubgrupo";
                 $clsubgrupo->localjan = "";
                 $clsubgrupo->onclick = "";
                 $clsubgrupo->db_opcao = 2;
                 $clsubgrupo->tipo = 2;
                 $clsubgrupo->top = 1;
                 $clsubgrupo->linhas = 10;
                 $clsubgrupo->vwhidth = 400;
                 $clsubgrupo->funcao_gera_formulario();
       ?>
          </td></table>
       </tr>  	  
       </table>
       </center>
       </form>

    </td>
  </tr>
</table>
<script>

</script>
  </body>
</html>