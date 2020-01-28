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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require("libs/db_sql.php");
$clrotulo = new rotulocampo;
$clrotulo->label("v07_numpre");
if(isset($inicial)){
  $sql = " 
          select * 
	  	from inicialcert
		  inner join certid  on certid.v=divida.v01_coddiv 
			                  and  certid.v13_instit	= ".db_getsession('DB_instit') ."
		where parcel=$parcel  
        ";
}
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.document.getElementById('processando').style.visibility = 'hidden'">
<form name="form1" method="post" action="">
<center>
<table>
<tr>
  <td>
  <?
  db_lovrot($sql,15);
  ?>
  </td>
</tr>  
</table>
</center>
</form>
</body>
</html>
<script>
  function js_termodiv(parcel){
      js_OpenJanelaIframe('top.corpo','db_iframe3','cai3_gerfinanc043.php?certid='+parcel+'&tipo=<?=$tipo?>','Pesquisa',true);
  }
  function js_termoini(parcel){
      js_OpenJanelaIframe('top.corpo','db_iframe3','cai3_gerfinanc044.php?certid='+parcel+'&tipo=<?=$tipo?>','Pesquisa',true);
  }
</script>