<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);


$clrotulo = new rotulocampo;
$clrotulo->label("pc80_codproc");
$clrotulo->label("pc10_numero");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

<script>
function js_emite(){
    
    var querystring  = 'pc80_codproc=' + $F('pc80_codproc');
        querystring += '&pc10_numero=' + $F('pc10_numero');
        querystring += '&imp_troca=' + $F('imp_troca');
        querystring += '&modelo=' + $F('modelo');    
    
    jan = window.open('com2_mapaorc002.php?'+querystring,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);

}
</script>  
<style>
.fieldsetinterno {
  border:0px;
  border-top:2px groove white;
}
fieldset.fieldsetinterno table {

  width: 100%;
  table-layout:auto;
}
fieldset.fieldsetinterno table tr TD:FIRST-CHILD {

  width: 80px;
  white-space: nowrap;
}
select {
 width: 100%;
}  
fieldset.fieldsetinterno table tr TD {
  white-space: nowrap;
}
legend {

}
</style>


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

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td align=center>

<table border="0" cellpadding="0" cellspacing="0" style="margin-top:15px;">
<tr><td align=center>

<form name="form1" method="post" action="">    

<fieldset>
<legend><b>Mapa das Propostas do Orçamento</b></legend>


<fieldset class="fieldsetinterno" style="margin-top: 15px;">
<legend>Orçamento Processo de Compras</legend>
  <table align="left" width="100%" >  
      <tr> 
        <td  align="left" nowrap title="<?=$Tpc80_codproc?>">
        <b>
        <?db_ancora('Processo de Compra',"js_pesquisa_pcproc(true);js_verifica_campo(2);",1);?>&nbsp;:
        </b> 
        </td>
        
        <td align="right" nowrap>
          <? db_input("pc80_codproc",6,$Ipc80_codproc,true,"text",4,"onchange='js_pesquisa_pcproc(false);js_verifica_campo(2);'"); ?>
        </td>
      </tr>
</table>
</fieldset >



<fieldset class="fieldsetinterno" style="margin-top: 15px;">
<legend>Orçamento Licitações</legend>
<table align="left" width="100%" >            
     <tr>
      <td  align="left" nowrap title="<?=$Tpc10_numero?>">
        <b>
          <?db_ancora('Solicitação de Compra',"js_pesquisa_solic(true);js_verifica_campo(1);",1);?>&nbsp;:
        </b> 
      </td>
        
    <td align="right" nowrap>
      <? db_input("pc10_numero",6,$Ipc10_numero,true,"text",4,"onchange='js_pesquisa_solic(false);js_verifica_campo(1);'");
         ?></td>
     </tr>
   </table>
</fieldset>
  
  
  
  
  <fieldset class="fieldsetinterno" style="margin-top: 15px;">
  <legend>Visualizações</legend>
   <table align="left" width="100%">  
     
     <tr>
        <td align=left><b>Modelo:</b></td>
        <td>
        <?
          $x = array("1"=>"Modelo 1","2"=>"Modelo 2");
          db_select("modelo",$x,true,4);
        ?>
        </td>
      </tr>
     
     <tr>
        <td align=left><b>Imprimir justificativa de troca de fornecedores:</b></td>
        <td>
        <?
          $x = array("S"=>"Sim","N"=>"Não");
          db_select("imp_troca",$x,true,4,"style='width:83px;'");
        ?>
        </td>
      </tr>
    </table>
  </fieldset> 
  
  
  
  
   </fieldset>
  
  </td></tr>
  </table> 
   
   
   <table>   
      <tr height="25">
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>  
    </table>
   
   </form>
    
   </td></tr>
   </table>
    
    
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>

function js_pesquisa_pcproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcproc','func_pcproc.php?funcao_js=parent.js_mostrapcproc1|pc80_codproc','Pesquisa',true);
  }else{
     if(document.form1.pc80_codproc.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pcproc','func_pcproc.php?pesquisa_chave='+document.form1.pc80_codproc.value+'&funcao_js=parent.js_mostrapcproc','Pesquisa',false);
     }else{
       document.form1.pc80_codproc.value = ''; 
     }
  }
}
function js_mostrapcproc(chave,erro){
  if(erro==true){ 
    document.form1.pc80_codproc.value = ''; 
    document.form1.pc80_codproc.focus(); 
  }
}
function js_mostrapcproc1(chave1){
   document.form1.pc80_codproc.value = chave1;  
   db_iframe_pcproc.hide();
}

function js_pesquisa_solic(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_solic','func_solicita.php?funcao_js=parent.js_mostrasolic1|pc10_numero','Pesquisa',true);
  }else{
     if(document.form1.pc10_numero.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_solic','func_solicita.php?pesquisa_chave='+document.form1.pc10_numero.value+'&funcao_js=parent.js_mostrasolic','Pesquisa',false);
     }else{
       document.form1.pc10_numero.value = ''; 
     }
  }
}
function js_mostrasolic(chave,erro){   
  if(erro==true){ 
    document.form1.pc10_numero.value = ''; 
    document.form1.pc10_numero.focus(); 
  }
}
function js_mostrasolic1(chave1){
   document.form1.pc10_numero.value = chave1;  
   db_iframe_solic.hide();
}

function js_verifica_campo(v) {
  
  var lMsg = false;
  
  if(v == 1) {
  
    if( $F('pc80_codproc') != "" ) {
    
      lMsg = true;
      db_iframe_solic.hide();
      $('pc10_numero').value = "";
    }
  } else if (v == 2) {
  
    if( $F('pc10_numero') != "" ) {
    
      lMsg = true;
      db_iframe_pcproc.hide();
      $('pc80_codproc').value = "";
    }
  }
  
  if(lMsg == true) {    
    alert('Você deve selecionar apenas uma opção entre "Processo de Compra" e "Solicitação de Compra"!');
  }
  
}

</script>