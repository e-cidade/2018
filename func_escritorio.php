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
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0">
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" valign="middle"> <form name="form1" method="post" action="">
        &nbsp;Pesquisa por escrit&oacute;rio de contabilidade:&nbsp; 
        <input name="nomeDigitadoParaPesquisa" type="text" id="nomeDigitadoParaPesquisa" size="41" maxlength="40">
        <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar">
      </form></td>
  </tr>
  <tr> 
    <td align="center" valign="middle"> 
      <?
  $nomeDigitadoParaPesquisa = strtoupper($nomeDigitadoParaPesquisa);
  $sql = "
         select distinct q10_numcgm as db_numerocgm, 
		 z01_numcgm, z01_nome, z01_ender,
	     z01_munic, z01_uf, z01_cep
         from escrito
		 inner join cgm on q10_numcgm = z01_numcgm
		 where z01_nome like '$nomeDigitadoParaPesquisa%'
	  ";
  db_lovrot($sql,15,"()",$nomeDigitadoParaPesquisa,$funcao_js);
?>
    </td>
  </tr>
</table>
</body>
</html>