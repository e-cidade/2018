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

//MODULO: saude
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post" action="">
<br><br>
<table border="0">
 <tr>
  <td nowrap>
   <b>Tipo de Atendimento:</b>
  </td>
  <td>
   <?db_input('codigota',10,@$Icodigota,true,'text',$db_opcao,"")?>
   <?db_input('tipoa',40,@$Itipoa,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <b>Especialidade:</b>
  </td>
  <td>
   <?db_input('codigoes',10,@$Icodigoes,true,'text',$db_opcao,"")?>
   <?db_input('especialidade',40,@$Iespecialidade,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <b>Procedimento:</b>
  </td>
  <td>
   <?db_input('codigopr',10,@$Icodigopr,true,'text',$db_opcao,"")?>
   <?db_input('procedimento',40,@$Iprocedimento,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <b>Profissional:</b>
  </td>
  <td>
   <?db_input('codigoprofiss',10,@$Icodigoprofiss,true,'text',$db_opcao,"")?>
   <?db_input('profissional',40,@$Iprofissional,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <b>Data:</b>
  </td>
  <td>
   <?db_inputdata('data',@$data,@$data,@$data,true,'text',$db_opcao,"")?>
   <b>Hora:</b>
   <?db_input('hora',10,@$Ihora,true,'text',$db_opcao,"")?>
   <b>FE:</b>
   <?db_input('fe',10,@$Ife,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <b>Tratamento:</b>
  </td>
  <td>
   <?db_textarea('tratamento',2,60,@$Itratamento,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td colspan="2">
   <br>
   <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>">
  </td>
 </tr>
</table>
</form>
</body>
</html>