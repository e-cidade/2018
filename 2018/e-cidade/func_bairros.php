<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

// Conexões necessárias
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_classecadastro.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

db_postmemory($_POST);

if (isset($HTTP_POST_VARS["filtro"])) {
  $nomeBairro = $HTTP_POST_VARS["filtro"];
}
$clbairro = new cl_bairro();	
$clbairro->rotulo->label("j13_descr");

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
        <form name="form1" method="post" action="">
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
      <tr> 
        <td align="center"> <? if (isset($nomeBairro)) { ?> &nbsp;Nome do bairro:&nbsp;
          <input name="nomeBairro" type="text" id="nomeBairro" size="41" maxlength="40"> 
          <? } else if (isset($codbairro)) { ?>
          &nbsp;C&oacute;digo do bairro:&nbsp; 
          <input name="codbairro" type="text" id="codbairro" size="41" maxlength="40">
          <? } ?> <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar">
        </form></td>
      </tr>
      <tr> 
        <td align="center">&nbsp;</td>
      </tr>
      <tr> 
        <td align="center"> 
          <?
            if (isset($nomeBairro)) {
              
              $nomeBairro = strtoupper($nomeBairro);
              $sql        = $clbairro->sqldadosNome($nomeBairro);
              db_lovrot($sql,15,"()",$nomeBairro,$funcao_js);
            } else if (isset($codbairro)) {
              
               if ($clbairro->dadosCodigo($codbairro) == false) {
          	      echo "<script>alert('Bairro com o código: {$codbairro} não encontrado.')</script>";
        	     }
          	   $sql = $clbairro->sqldadosCodigo($codbairro);
          	 
               db_lovrot($sql,15,"()",$codbairro,$funcao_js);
            }
          
          ?>
        </td>
      </tr>
    </table>

</center>

</body>
</html>