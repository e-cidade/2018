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
include("classes/db_cgm_classe.php");

//---  parser POST/GET
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

//---- instancia classes
$cl_cgm = new cl_cgm;
$aux = new cl_arquivo_auxiliar;

//--- cria rotulos e labels
$cl_cgm->rotulo->label();

?>
<html>
	<head>
	  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	  <meta http-equiv="Expires" CONTENT="0">
	  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
	  <link href="estilos.css" rel="stylesheet" type="text/css">
	</head>
	<body bgcolor=#CCCCCC bgcolor="#CCCCCC">
	<br /><br />
	  <center>
	    <form name="form1" method="post" action="">
	      <fieldset style="width: 90px;">
	        <legend style="font-weight: bold; font-size: 13px;">&nbsp;CGM&nbsp;</legend>
	        <table border="0" >
	          <tr>
	            <td align="center">
	              <b>Opções:</b>
	                <?
	                  $aCondicoes = array ('com' => 'Com o CGM selecionado', 'sem' => 'Sem o CGM selecionado');
	                  db_select('ver',$aCondicoes, true, 1);
	                ?>
	            </td>
	          </tr>
	          <tr>
	            <td nowrap width="50%">
	              <?
									$aux->cabecalho      = "<strong>&nbsp;Selecione&nbsp;</strong>";
									$aux->codigo         = "z01_numcgm"; //chave de retorno da func
									$aux->descr          = "z01_nome";   //chave de retorno
									$aux->nomeobjeto     = 'cgm';
									$aux->funcao_js      = 'js_mostra';
									$aux->funcao_js_hide = 'js_mostra1';
									$aux->sql_exec       = "";
									$aux->func_arquivo   = "func_nome.php";  //func a executar
									$aux->isfuncnome     = true;
									$aux->nomeiframe     = "db_iframe_proc";
									$aux->localjan       = "";
									$aux->onclick        = "";
									$aux->db_opcao       = 2;
									$aux->tipo           = 2;
									$aux->top            = 1;
									$aux->linhas         = 10;
									$aux->vwidth         = 400;
									$aux->funcao_gera_formulario();
	              ?>
	            </td>
	          </tr>
	        </table>
	      </form>
	  </center>
	</body>
</html>