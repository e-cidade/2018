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

$sqlperfil = "select distinct id_perfil ,u.nome
							from db_usuarios 
							inner join db_permherda   on db_permherda.id_usuario = db_usuarios.id_usuario 
							inner join db_usuarios  u on u.id_usuario = id_perfil
							inner join db_permissao s on u.id_usuario = s.id_usuario
							where db_usuarios.id_usuario = $id_usuario
							     and s.anousu= $ano";

$resultperfil = pg_query($sqlperfil);
$linhasperfil = pg_num_rows($resultperfil);
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
if($linhasperfil > 0){
  ?>
  <table width='60%' border ="1" class="tab_cinza" >
  <tr>
    <th>Código </th>
    <th>Perfil </th>
  </tr>
  <?
  for($i=0;$i<$linhasperfil;$i++){
    db_fieldsmemory($resultperfil,$i);  
    echo "
    <tr>
      <td width='15%' align='center'> $id_perfil      </td>
      <td><a href='func_consultapermissao.php?id_usuario=$id_perfil&ano=$ano'><b>$nome </b></a></td>
    </tr>
    ";
  } 
}else{
  echo "NÃO POSSUI PERFIL CADASTRADO PARA ESTE USUÁRIO E ANO.";
}
?>
</table>
</center>
</body>
</html>