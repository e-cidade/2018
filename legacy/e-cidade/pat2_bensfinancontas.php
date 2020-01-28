<?php
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
include("libs/db_app.utils.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

$clArquivoAuxiliar = new cl_arquivo_auxiliar();

?>
<html>
<head>
<?
  db_app::load("scripts.js, estilos.css, prototype.js, arrays.js");
?>
</head>
<body bgcolor="#cccccc">
<form name="form1" action="">
<fieldset style="width: 300px; margin: 10 auto;">
  <legend><strong>Contas Cont&aacute;beis</strong></legend>

	<table align="center">
	  <tr>
	    <td>
	    <?php
	     
	      $clArquivoAuxiliar->cabecalho      = '<strong>Estruturais</strong>';
	      $clArquivoAuxiliar->codigo         = 'c60_codcon'; 
	      $clArquivoAuxiliar->descr          = "c60_descr";  
	      $clArquivoAuxiliar->nomeobjeto     = 'contas';
	      $clArquivoAuxiliar->funcao_js      = 'js_mostra_conta';
	      $clArquivoAuxiliar->funcao_js_hide = 'js_mostra_conta1';
	      $clArquivoAuxiliar->func_arquivo   = 'func_clabensconta.php';
	      $clArquivoAuxiliar->nomeiframe     = 'db_iframe_conplano';
	      $clArquivoAuxiliar->localjan       = '';
	      $clArquivoAuxiliar->tipo           = 2;
	      $clArquivoAuxiliar->linhas         = 5;
	      $clArquivoAuxiliar->vwhidth        = 400;
	      $clArquivoAuxiliar->obrigarselecao = false;
	      $clArquivoAuxiliar->nome_botao     = 'db_lanca_conta';
	      $clArquivoAuxiliar->funcao_gera_formulario();
	
	    ?>
	    </td>
	  </tr>
	      
	  <tr>
	     <td align="center">
	        <strong>Op&ccedil;&otilde;es</strong>
	        <?
	          $opcoes = array('s' => 'Considerar selecionadas',
	                          'n' => 'N&atilde;o considerar selecionadas');
	          db_select('opcoescontas', $opcoes, true, 1);
	        ?>     
	     </td>
	  </tr>
  </table>
  
</fieldset>
</form>
</body>
</html>