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
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;
$clrotulo->label('e50_numemp');
$clrotulo->label('e50_codord');


?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
  jan = window.open('emp2_ordempag002.php?data='+document.form1.data_ano.value+'-'+document.form1.data_mes.value+'-'+document.form1.data_dia.value+'&data1='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value+'&codini='+document.form1.e50_codordINI.value+'&codfim='+document.form1.e50_codordFIM.value+'&numempini='+document.form1.e50_numempINI.value+'&numempfim='+document.form1.e50_numempFIM.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_testa(campo,valor,nomecampo1,nomecampo2){
  msg = "Informe um intervalo de código válido!";
  erro = false;
  if(campo=="i"){
    if(eval("document.form1."+nomecampo2+".value")!="" && parseInt(valor)>=parseInt(eval("document.form1."+nomecampo2+".value"))){
      erro = true;
    }
  }else if(campo=="f"){
    if(eval("document.form1."+nomecampo1+".value")!="" && parseInt(valor)<=parseInt(eval("document.form1."+nomecampo1+".value"))){
      erro = true;
    }
  }
  if(erro == true){
    alert(msg);
    eval('document.form1.'+nomecampo1+'.value = ""');
    eval('document.form1.'+nomecampo2+'.value = ""');
    eval('document.form1.'+nomecampo1+'.focus()');
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
         <tr>
	 <td><b>De:</b><?db_inputdata("data","","","","true","text",2)      ?>      </td>
	 <td><b>Ate:</b>  <?db_inputdata("data1","","","","true","text",2)      ?> </td>
	 </tr>
      <tr >
        <td align="left" nowrap >
        <strong>Apartir da Ordem:</strong>
	  <?
            db_input('e50_codord',8,$Ie50_codord,true,'text',1,"onChange=\"js_testa('i',this.value,'e50_codordINI','e50_codordFIM')\"","e50_codordINI","");             
	  ?>
        </td>
	<td><strong>ate:</strong>
	  <?
            db_input('e50_codord',8,$Ie50_codord,true,'text',1,"onChange=\"js_testa('f',this.value,'e50_codordINI','e50_codordFIM')\"","e50_codordFIM","");             
          ?>
	</td>
      </tr>
      <tr >
        <td align="left" nowrap >
        <strong>Apartir do Empenho:</strong>
	  <?
            db_input('e50_numemp',8,$Ie50_numemp,true,'text',1,"onChange=\"js_testa('i',this.value,'e50_numempINI','e50_numempFIM')\"","e50_numempINI","");             
	  ?>
        </td>
	<td><strong>ate:</strong>
	  <?
            db_input('e50_numemp',8,$Ie50_numemp,true,'text',1,"onChange=\"js_testa('f',this.value,'e50_numempINI','e50_numempFIM')\"","e50_numempFIM","");             
          ?>
	</td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Emitir Relatório" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>