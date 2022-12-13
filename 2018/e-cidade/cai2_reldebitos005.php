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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
$aux = new cl_arquivo_auxiliar;
$clrotulo = new rotulocampo;
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" action="">
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC" nowrap >
    <center>
     <table>
      <tr >
        <td align="right">
	   <strong>Opção de Seleção :<strong>
	</td>
        <td align="left">&nbsp;&nbsp;&nbsp;
          <?
	 $xxx = array("S"=>"Somente Selecionados","N"=>"Menos os Selecionados");
	  db_select('seledeb',$xxx,true,2);
          ?>

        </td>
      </tr>
      <?
      //$aux->cabecalho = "<strong>Selecione um tipo de dívida ou deixe em branco para todos</strong>";
      $aux->codigo = "k00_tipo";
      $aux->descr  = "k00_descr";
      $aux->nomeobjeto = 'arretipo';
      $aux->funcao_js = 'js_funcaotipo';
      $aux->funcao_js_hide = 'js_funcaotipo1';
      $aux->sql_exec  = "";
      $aux->func_arquivo = "func_arretipo.php";
      $aux->nomeiframe = "iframe_arretipo";
      $aux->localjan = "";
      $aux->db_opcao = 2;
      $aux->top = 0;
      $aux->linhas = 10;
      $aux->vwhidth = 500;
      $aux->funcao_gera_formulario();			    
      ?>
       </table>
    </center>
    </td>
  </tr>
</table>
</form>
</body>
</html>