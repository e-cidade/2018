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

session_start();
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<style type="text/css">
<!--
th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}
-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_descr() {
  var des = document.getElementById("descricao");
  var con = document.getElementById("consulta");
  if(des.style.visibility == 'hidden') {
    con.style.visibility = 'hidden';
    des.style.visibility = 'visible';
  } else {
    con.style.visibility = 'visible';
    des.style.visibility = 'hidden';
  }
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="" >
<div id="descricao" style="position:absolute; left:62px; top:141px; width:666px; height:186px; z-index:1; background-color: #00CCFF; layer-background-color: #00CCFF; border: 1px none #000000; visibility: hidden;">
  <BR>
  <CENTER>
  <table width="90%" border="1" cellpadding="3" cellspacing="0" bordercolor="#FFFFFF">
    <tr bgcolor="#0099FF">
      <th width="22%">Estado</th>
      <th width="78%">Descrição</th>
    </tr>
    <tr> 
      <td>ESTABELECIDO</td>
      <td>O socket tem uma conex&atilde;o estabelecida.</td>
    </tr>
    <tr> 
        <td>ESPERANDO_FECHAR&nbsp;&nbsp;</td>
      <td>O lado remoto terminou, esperando pelo fechamento do socket.</td>
    </tr>
    <tr> 
      <td> TIME_WAIT</td>
      <td> O socket est&aacute; esperando ap&oacute;s o fechamento por uma retransmiss&atilde;o 
        da ter&shy;<br>
        mina&ccedil;&atilde;o pelo lado remoto.</td>
    </tr>
    <tr> 
      <td>FECHADO</td>
      <td>O socket n&atilde;o est&aacute; sendo usado.</td>
    </tr>
    <tr>
      <td>DESCONHECIDO</td>
      <td>O estado do socket &eacute; desconhecido.</td>
    </tr>
  </table>
  </CENTER>  
</div>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> <center>
	   <form name="form1" method="post">
        <table border="0" cellpadding="3" cellspacing="0">
          <tr> 
            <td align="left" valign="bottom"> 
			  
                <table border="0" cellpadding="0" cellspacing="0">
                  <tr> 
                    <td width="139" nowrap><strong>Per&iacute;odo de atualiza&ccedil;&atilde;o:</strong></td>
                    <td width="107" nowrap><input name="atualizacao" type="text" value="5" size="3" maxlength="3">
                      <strong>s</strong></td>
                  </tr>
                  <tr> 
                    <td><input type="button" accesskey="a" value="Atualizar" name="sub" onClick="consulta.location.reload()"></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr> 
                    <td height="50" colspan="2" valign="bottom" nowrap>
					  <input type="button" accesskey="d" value="Descrição do Status" onClick="js_descr()">
					 <strong> Usuarios:</strong>&nbsp;<input type="text" size="3" name="tabusu">                      
					</td>
                  </tr>
                </table>
              
			</td>
          </tr>
          <tr> 
            <td> <iframe src="con3_usuonline002.php" id="consulta" name="consulta" scrolling="auto" width="700" height="300"></iframe>
            </td>
          </tr>
        </table>
		</form>
      </center>	  
      <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
    </td>
  </tr>
</table>
</body>
</html>