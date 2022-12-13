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
?>
<html>
<head>
<title>imagem em anexo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr style="font-size:12px"> 
    <td align="center"><strong>Imagens em anexo desta ordem de servi&ccedil;o:</strong></td>
  </tr>
<?
  pg_exec("begin");
  $result = pg_exec("select * from db_ordemimagens where codordem = $ordem");
  $num = pg_numrows($result);
  for ($i=0;$i<$num;$i++) {
    $nomeTemporario = tempnam("../tmp/","");
    $oid = pg_result($result,$i,"arquivo");
    chmod($nomeTemporario,0664);
    pg_loexport($oid,$nomeTemporario) or die("Erro (21). Carregando a imagem.");
    echo "
    <tr> 
      <td align=\"center\" valign=\"middle\">
	    <img align=\"middle\" src=\"".$nomeTemporario."\">
    	&nbsp;</td>
    </tr>
    \n";
  }
  pg_exec("end");
?>
</table>
</body>
</html>