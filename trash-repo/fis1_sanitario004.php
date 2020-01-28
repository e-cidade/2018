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

$clrotulo = new rotulocampo;
$clrotulo->label('q02_numcgm');
$clrotulo->label('z01_nome');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_checa(){
  if(document.form1.q02_numcgm.value==""){
    alert("Informe um NUMCGM.");
    return false;
  }
  return true;
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="document.form1.q02_numcgm.focus();" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table height="430" width="790" border="0" cellspacing="0" cellpadding="0">
<form name="form1" method="post" action="">
  <tr>
    <td align="left" valign="center" bgcolor="#CCCCCC">
      <center>
<table border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td title="<?=$Tz01_nome?>">     
     <?
      db_ancora($Lz01_nome,' js_cgm(true); ',1);
     ?>
    </td>
    <td> 
     <?
      db_input('q02_numcgm',5,$Iq02_numcgm,true,'text',1,"onchange='js_cgm(false)'");
      db_input('z01_nome',40,0,true,'text',3,"");
     ?>
    </td>
  </tr>
</table><br>
<input name="entrar" type="submit" id="pesquisa" value="Entrar" onclick="return js_checa()">
<input name="db_opcao" type="hidden" value="1">
      </center>
    </td>
  </tr>
</form>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_cgm(mostra){
  var numcgm=document.form1.q02_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_nome.php?funcao_js=parent.js_mostra|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_nome.php?pesquisa_chave='+numcgm+'&funcao_js=parent.js_mostra1','Pesquisa',false);
  }
}
function js_mostra(chave1,chave2){
  document.form1.q02_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_mostra1(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.q02_numcgm.focus();
    document.form1.q02_numcgm.value = '';
  }
}
</script>
<?
if(isset($cgccpf)){
  db_msgbox('Atualize o CGCCPF do contribuinte no CGM');
}
if(isset($cep)){
  db_msgbox('Atualize o cep do contribuinte no CGM');
}
if(isset($invalido)){
  db_msgbox('NUMCGM inválido!');
}
?>