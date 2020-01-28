<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("classes/db_empagegera_classe.php");
require_once("classes/db_empageconfgera_classe.php");
require_once("classes/db_empagetipo_classe.php");
require_once("classes/db_empagedadosret_classe.php");
$clempagegera     = new cl_empagegera;
$clempageconfgera = new cl_empageconfgera;
$clempagetipo     = new cl_empagetipo;
$clempagedadosret = new cl_empagedadosret;
$clrotulo         = new rotulocampo;
$clempagegera    ->rotulo->label();
$clempagetipo    ->rotulo->label();
$clempagedadosret->rotulo->label();

db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>

  #e75_codretdescr {
    display: none; 
  }
  #e75_codret {
    width: 80px;
  }
  #e83_codtipo {
    width: 80px;
  }
  #e83_codtipodescr {
    width: 300px;
  }
  #ordem {
    width: 382px;
  }
  #modelo {
    width: 80px;
  }

</style>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.e87_codgera.focus();" bgcolor="#cccccc">

<center>
<form name="form1" method="post">

<fieldset style="margin-top: 50px; width: 600px;">
<legend><strong>Relatório Arquivos Cancelados</strong></legend>

<table border='0' align='left'>

  <tr> 
    <td  align="left" nowrap title="<?=$Te87_codgera?>"> <? db_ancora(@$Le87_codgera,"js_pesquisa_gera(true);",1);?>  </td>
    <td align="left" nowrap>
      <?
       db_input("e87_codgera",10,$Ie87_codgera,true,"text",4,"onchange='js_pesquisa_gera(false);'"); 
       db_input("e87_descgera",60,$Ie87_descgera,true,"text",3);
      ?>
    </td>
  </tr>
</table>

</fieldset>

<div style="margin-top: 10px;">

 <input name="relatorio" type="button" onclick='js_emite();'  value="Emitir Relatório">
</div>

</form>
</center>

<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>

</body>
</html>

<script>
  
function js_emite() {

  var sQuery   = "";
  var sFonte   = "cai2_arquivoscancelados002.php";
  var iCodGera = $F('e87_codgera');
  
  if (iCodGera == '') {

    alert('Selecione o Arquivo a ser Emitido');
    return false;
  }
  sQuery  = "?iCodGera=" + iCodGera;
  jan = window.open(sFonte+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);  
  
}

  
//--------------------------------
function js_pesquisa_gera(lMostra) {
  
  if (lMostra == true){
    
    js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?lCancelado=1&funcao_js=parent.js_mostragera1|e87_codgera|e87_descgera','Pesquisa',true);
  } else {
    
     if (document.form1.e87_codgera.value != '') { 
        js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?lCancelado=1&pesquisa_chave='+document.form1.e87_codgera.value+'&funcao_js=parent.js_mostragera','Pesquisa',false);
     } else {
       document.form1.e87_descgera.value = ''; 
     }
  }
}
function js_mostragera(chave, erro) {
  
  if (document.form1.e75_codret) {
    document.form1.e75_codret.value = "";
  }
  
  if (erro == true) {
     
    document.form1.e87_codgera.focus(); 
    document.form1.e87_codgera.value = ''; 
  }
  document.form1.e87_descgera.value = chave; 
  document.form1.submit();
}

function js_mostragera1(chave1, chave2) {
  
  if(document.form1.e75_codret){
    document.form1.e75_codret.value = "";
  }
  document.form1.e87_codgera.value = chave1;
  document.form1.e87_descgera.value = chave2;
  db_iframe_empagegera.hide();
  document.form1.submit();
}

//--------------------------------
</script>