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
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt15');
$clrotulo->label('DBtxt16');
$clrotulo->label('DBtxt17');
$clrotulo->label('DBtxt18');
$clrotulo->label('procdiver');
db_postmemory($HTTP_POST_VARS);

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_verifica() { 
   var diasparc = new Number(document.form1.DBtxt16.value);
   var valor = new Number(document.form1.DBtxt17.value);
   var numero = new Number(document.form1.DBtxt18.value);
   if(document.form1.DBtxt15_ano.value == ''){
     alert('Informe a data da primeira parcela.');
     return false;
   }else if(diasparc.valueOf() == 0){
     alert('Informe o dia das demais parcelas.');
     return false;
   }else if(valor.valueOf() == 0){
     alert('Informe o valor das parcelas.');
     return false;
   }else if(numero.valueOf() == 0){
     alert('Informe o número de parcelas.');
     return false;
   }else if(document.form1.procdiver.value == ''){
     alert('Informe a procedência do débito.');
     return false;
   }
   return true;
}

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
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
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
 <form name="form1" method="post" action="dvr4_parclote002.php" onsubmit="return js_verifica();">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr align="center"> 
            <td height="84" colspan="3"><strong>GERA&Ccedil;&Atilde;O DOS PARCELAMENTOS 
              DE LOTEAMENTOS</strong></td>
          </tr>
          <tr> 
            <td width="45%" height="35" align="right"><strong>Loteamento:</strong></td>
            <td width="5%">&nbsp;</td>
            <td width="50%"><select name="loteam" id="select5">
                <option value="38" selected>38 - SOL NASCENTE </option>
                <option value="45">45 - POPULAR POR DO SOL</option>
              </select> </td>
          </tr>
          <tr> 
            <td height="32" align="right"> 
              <?=@$LDBtxt15?>
            </td>
            <td>&nbsp;</td>
            <td> 
              <?
			
			db_inputdata('DBtxt15','','','',true,'text',2)
			?>
            </td>
          </tr>
          <tr> 
            <td height="32" align="right"> 
              <?=@$LDBtxt16?>
            </td>
            <td>&nbsp;</td>
            <td> 
              <?
  			  db_input('DBtxt16',8,$IDBtxt16,true,'text',2);
			?>
            </td>
          </tr>
          <tr> 
            <td height="35" align="right"> 
              <?=@$LDBtxt17?>
            </td>
            <td>&nbsp;</td>
            <td> 
              <?
  			  db_input('DBtxt17',8,$IDBtxt17,true,'text',2);
			?>
            </td>
          </tr>
          <tr> 
            <td height="31" align="right"> 
              <?=@$LDBtxt18?>
            </td>
            <td>&nbsp;</td>
            <td> 
              <?
  			  db_input('DBtxt18',8,$IDBtxt18,true,'text',2);
			?>
            </td>
          </tr>
          <tr> 
            <td height="31" align="right">
              <?
            db_ancora(@$Lprocdiver,"js_pesquisaprocdiver(true);",4)
            ?>
            </td>
            <td>&nbsp;</td>
            <td>
              <?
              db_input('procdiver',4,$Iprocdiver,true,'text',4,"onchange='js_pesquisaprocdiver(false);'")
	        ?>
            </td>
          </tr>
          <tr> 
            <td height="64" align="right">&nbsp; </td>
            <td>&nbsp;</td>
            <td>&nbsp; </td>
          </tr>
        </table>
        <div align="center"> 
          <input name="processar" type="submit" id="processar" value="Processar" >
        </div>
      </form>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
  </tr>
</table>
      <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisaprocdiver(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_procdiver.php?funcao_js=parent.js_mostratermo1|0';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_procdiver.php?pesquisa_chave='+document.form1.v14_certid.value+'&funcao_js=parent.js_mostratermo';
     }
}
function js_mostratermo(chave,erro){
  if(erro==true){
     document.form1.procdiver.focus();
     document.form1.procdiver.value = '';
  }
}
function js_mostratermo1(chave1){
     document.form1.procdiver.value = chave1;
     db_iframe.hide();
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>