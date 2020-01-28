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
include("classes/db_emphist_classe.php");
include("classes/db_empprestatip_classe.php");

//---  parser POST/GET
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

//---- instancia classes
$clempempenho   = new cl_empempenho;
$clemphist      = new cl_emphist;
$clempprestatip = new cl_empprestatip;

//--- cria rotulos e labels
$clempempenho->rotulo->label();
$clemphist->rotulo->label();
$clrotulo = new rotulocampo;

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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>  &nbsp; </td>
  </tr>  
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
       <form name="form1" method="post" action="">
       <table border="0" >
       <tr> 
           <td align="center" colspan="2">
                <strong>Opções:</strong>
                <select name="ver">
                    <option name="condicao2" value="com">Com os históricos/eventos selecionados</option>
                    <option name="condicao2" value="sem">Sem os históricos/eventos selecionadas</option>
                </select>
          </td>
       </tr>
       <tr>
          <td nowrap><table border="0">
               <?
                 $cl_emphist                 = new cl_arquivo_auxiliar;
		             $cl_emphist->nome_botao     = "db_lanca_hist";
                 $cl_emphist->cabecalho      = "<strong>Histórico</strong>";
                 $cl_emphist->codigo         = "e40_codhist"; //chave de retorno da func
                 $cl_emphist->descr          = "e40_descr";   //chave de retorno
                 $cl_emphist->nomeobjeto     = 'historico';
                 $cl_emphist->funcao_js      = 'js_mostra';
                 $cl_emphist->funcao_js_hide = 'js_mostra1';
                 $cl_emphist->sql_exec       = "";
                 $cl_emphist->func_arquivo   = "func_emphist.php";  //func a executar
                 $cl_emphist->nomeiframe     = "db_iframe_emphist";
                 $cl_emphist->localjan       = "";
                 $cl_emphist->onclick        = "";
                 $cl_emphist->db_opcao       = 2;
                 $cl_emphist->tipo           = 2;
                 $cl_emphist->top            = 1;
                 $cl_emphist->linhas         = 10;
                 $cl_emphist->vwhidth        = 400;
                 $cl_emphist->funcao_gera_formulario();
              ?>    
          </table></td>
	        <td nowrap><table border="0">
               <?
                 $cl_empprestatip                 = new cl_arquivo_auxiliar;
		             $cl_empprestatip->nome_botao     = "db_lanca_tipo";
                 $cl_empprestatip->cabecalho      = "<strong>Evento</strong>";
                 $cl_empprestatip->codigo         = "e44_tipo";  //chave de retorno da func
                 $cl_empprestatip->descr          = "e44_descr"; //chave de retorno
                 $cl_empprestatip->nomeobjeto     = 'evento';
                 $cl_empprestatip->funcao_js      = 'js_mostra2';
                 $cl_empprestatip->funcao_js_hide = 'js_mostra3';
                 $cl_empprestatip->sql_exec       = "";
                 $cl_empprestatip->func_arquivo   = "func_empprestatip.php";  //func a executar
                 $cl_empprestatip->nomeiframe     = "db_iframe_empprestatip";
                 $cl_empprestatip->localjan       = "";
                 $cl_empprestatip->onclick        = "";
                 $cl_empprestatip->db_opcao       = 2;
                 $cl_empprestatip->tipo           = 2;
                 $cl_empprestatip->top            = 1;
                 $cl_empprestatip->linhas         = 10;
                 $cl_empprestatip->vwhidth        = 400;
                 $cl_empprestatip->funcao_gera_formulario();
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