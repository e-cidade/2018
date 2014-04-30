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
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$sqldepart = "select distinct db_depart.coddepto, descrdepto,instit
              from db_depusu 
              inner join db_depart on db_depusu.coddepto = db_depart.coddepto 
              where db_depusu.id_usuario = $id_usuario
              ";
$resultdepart = pg_query($sqldepart);
$linhasdepart = pg_num_rows($resultdepart);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<center>
<br>

<?
if($linhasdepart > 0){
  ?>
  <table width='70%' border ="1" class="tab_cinza">
  <tr>
    <th width='10%' align= 'center'>Código      </th>
    <th width='50%' align= 'center'>Departamento</th>
    <th width='10%' align= 'center' >Instituição </th>
  </tr>
  <?
  for($i=0;$i<$linhasdepart;$i++){
    db_fieldsmemory($resultdepart,$i);  
    echo "
    <tr>
      <td align='center'> $coddepto      </td>
      <td>$descrdepto</td>
      <td align='center'> $instit </td>
    </tr>
    ";
  }
  
}else{
  echo "NÃO POSSUI DEPARTAMENTO CADASTRADO PARA ESTE USUÁRIO.";
}
?>
</table>
</center>
</body>
</html>