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
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_relatorio(){ // relatorio com todos os andamentos dos tipos de processo
  obj = document.form1;
  ordem = obj.ordem.value;
  jan = window.open('pro2_tipoproc002.php?ordem='+ordem,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
}
function js_reland(){ // relatorio com andamentos iniciais
  obj = document.form1;
  andamento = obj.andamento.value;
  ordem     = obj.ordem_p.value;
  jan = window.open('pro2_tipoproc003.php?andamento='+andamento+'&ordem='+ordem,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
}

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
  <center>
  <form name="form1" method="post" action="" target="rel">
     <table width="41%" border="0" cellspacing="0" cellpadding="0">
     <tr>
        <td> &nbsp;  </td><td> &nbsp; </td>
     </tr>
     <tr> 
        <td height="30" colspan="1"><b> Tipos de Processo  </b> </td>
        <td height="30" nowrap colspan="1">
           Ordem 
          <select name="ordem">
            <option  value="p51_descr">   Descrição </option>
            <option  value="p51_codigo">  codigo    </option>
          </select>
        </td>
     </tr>  
     <tr>
        <td height="30" align="center" colspan="2">
            <input type="button" value="emite" onClick="js_relatorio()"> 
        </td>	       
     </tr>
      
     <!--- andamentos padrões  --->
   
     <tr>
        <td> &nbsp;  </td><td> &nbsp; </td>
     </tr>
     <tr> 
         <td height="30" colspan="2"><b> Tipos de Processo </b> </td>
     </tr>
     <tr>
        <td height="30" nowrap colspan="1">  
          <select name="andamento">
            <option  value="com"> Com Andamento  </option>
            <option  value="sem" > Sem Andamento  </option>
	    <!---
            <option  value="todos"> Todos        </option>
	     --->
          </select>
        </td>
        <td height="30" nowrap colspan="1">  Ordem
          <select name="ordem_p">
            <option  value="tipoproc"> Tipo de processo  </option>
            <option  value="depart" > Departamento  </option>
          </select>
        </td>

     </tr>  
     <tr>
        <td height="30" align="center" colspan="2">
            <input type="button" value="emite" onClick="js_reland()"> 
        </td>	       
     </tr>


    </table>
  </form>
  </center>
  </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>