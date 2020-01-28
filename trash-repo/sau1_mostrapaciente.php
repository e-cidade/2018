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
   <b>CGS:</b>
  </td>
  <td>
   <?db_input('cgs',10,@$Icgs,true,'text',$db_opcao,"")?>
   <b>Paciente:</b>
   <?db_input('paciente',50,@$Ipaciente,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <b>RG:</b>
  </td>
  <td>
   <?db_input('rg',33,@$Irg,true,'text',$db_opcao,"")?>
   <b>CPF:</b>
   <?db_input('cpf',33,@$Icpf,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <b>CEP:</b>
  </td>
  <td>
   <?db_input('cep',10,@$Icep,true,'text',$db_opcao,"")?>
   <b>Endereço:</b>
   <?db_input('endereco',50,@$Iendereco,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <b>N°:</b>
  </td>
  <td>
   <?db_input('numero',10,@$Inumero,true,'text',$db_opcao,"")?>
   <b>Compl.:</b>
   <?db_input('compl',10,@$Icompl,true,'text',$db_opcao,"")?>
   <b>Bairro:</b>
   <?db_input('bairro',28,@$Ibairro,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <b>Cidade:</b>
  </td>
  <td>
   <?db_input('cidade',30,@$Icidade,true,'text',$db_opcao,"")?>
   <b>UF:</b>
   <?db_input('uf',2,@$Iuf,true,'text',$db_opcao,"")?>
   <b>Nascimento:</b>
   <?db_inputdata('nascimento',@$nascimento,@$nascimento,@$nascimento,true,'text',$db_opcao,"")?>
   <b>Sexo:</b>
   <?db_input('sexo',1,@$Isexo,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <b>Tipo Sangue:</b>
  </td>
  <td>
   <?
   $x = array(''=>'','1'=>'1 - A','2'=>'2 -B','3'=>'3 - AB','4'=>'4 - O');
   db_select('tiposangue',$x,true,$db_opcao,"");
   ?>
   <b>Fator RH:</b>
   <?
   $x = array(''=>'','1'=>'Positivo','2'=>'Negativo');
   db_select('fatorrh',$x,true,$db_opcao,"");
   ?>
   <b>Cartao SUS:</b>
   <?db_input('cartao',20,@$Icartao,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap>
   <b>Telefone:</b>
  </td>
  <td>
   <?db_input('telefone',20,@$Itelefone,true,'text',$db_opcao,"")?>
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