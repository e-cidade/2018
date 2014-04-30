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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require_once("classes/db_solicita_classe.php");

$clsolicita = new cl_solicita;
$clrotulo = new rotulocampo();
$clrotulo->label("pc80_codproc");

$oRotuloSolicita = new rotulo('solicita');
$oRotuloSolicita->label();

$clsolicita->rotulo->label();
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="" >
	<br>
	<br>
<form name="form1" method="post" action="">	
	<center>
  	<fieldset style="width: 480px;">
  		<legend><strong>Emissão de Nota de Bloqueio</strong></legend>
    <table  align="left"  cellpadding="2" cellspacing="2" border="0">
		  <tr> 
		    <td  align="left" nowrap title="<?=$Tpc10_numero?>">
		      <? db_ancora("<b>Solicitações de:</b> ","js_solicitade(true);",1);?>  
		    </td>
		    <td align="left" nowrap>
		      <?
             db_input("pc10_numerode",10, $Ipc10_numero ,true,"text",4,"onchange='js_solicitade(false);'"); 
		      ?>
		      </b>
		    </td>
		    
        <td  align="left" nowrap title="<?=$Tpc10_numero?>"> 
          <? db_ancora("<b>Até:</b> ","js_solicitaate(true);",1);?>  
        </td>
        <td align="left" nowrap>
          <?
		         db_input("pc10_numeroate",10, $Ipc10_numero ,true,"text",4,"onchange='js_solicitaate(false);'"); 
          ?>
        </td>		    
		  </tr>    
    </table>
  	</fieldset>
  	<br>
  	<input type="button" name="btnReemite" id="btnReemite" value="Emitir" onclick="js_emiteNota();">
	</center>
</form>	
	
  <?php
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"),
            db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
</body>
</html>

<script>



function js_emiteNota() {

  var iSolicitaDe  = $('pc10_numerode').value;
  var iSolicitaAte = $('pc10_numeroate').value;

  if (iSolicitaDe != "" && iSolicitaAte != "") {

    if (iSolicitaAte < iSolicitaDe) {
  
      alert("Intervalo de solicitações inválido.");
      return false;
    }
  }
  
	var sGetUrl  = "?iSolicitaInicio="  + iSolicitaDe  ;
			sGetUrl += "&iSolicitaFim=" + iSolicitaAte ;
			

	var jan = window.open('com2_emitenotabloqueio002.php'+sGetUrl, '', 'location=0, width='+(screen.availWidth - 5)+
	                 'width='+(screen.availWidth - 5)+', scrollbars=1'); 
	jan.moveTo(0, 0);
}




  

//Solicitacao DE
function js_solicitade(mostra) {

  if(mostra == true){
    
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_solicita',
                        'func_solicitamanutencaoreserva.php?funcao_js=parent.js_mostrasolicitade1|'+
                        'pc10_numero&param_depart=<?=db_getsession("DB_coddepto")?>','Pesquisa',true);
  }else{
    
    if(document.form1.pc10_numerode.value != ''){
       js_OpenJanelaIframe('top.corpo', 
                           'db_iframe_solicita',
                           'func_solicitamanutencaoreserva.php?pesquisa_chave='+document.form1.pc10_numerode.value+
                           '&funcao_js=parent.js_mostrasolicitade&param_depart=<?=db_getsession("DB_coddepto")?>',
                           'Pesquisa',false);
    }else{
      document.form1.pc10_numerode.value = '';
    }
  }
}

function js_mostrasolicitade(chave,erro){
  
  if(erro==true){
    document.form1.pc10_numerode.focus();
    document.form1.pc10_numerode.value = '';
  }
}

function js_mostrasolicitade1(chave1,x){
  
  document.form1.pc10_numerode.value = chave1;
  db_iframe_solicita.hide();
}

//solicitacao ATE
function js_solicitaate(mostra){

if(mostra == true){
  
  js_OpenJanelaIframe('top.corpo','db_iframe_solicitaate','func_solicita.php?funcao_js=parent.js_mostrasolicitaate1|pc10_numero&param_depart=<?=db_getsession("DB_coddepto")?>','Pesquisa',true);
  }else{
    
     if(document.form1.pc10_numeroate.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_solicitaate','func_solicita.php?pesquisa_chave='+document.form1.pc10_numeroate.value+'&funcao_js=parent.js_mostrasolicitaate&param_depart=<?=db_getsession("DB_coddepto")?>','Pesquisa',false);
     }else{
       document.form1.pc10_numeroate.value = '';
     }
  }
}

function js_mostrasolicitaate(chave,erro){
  
  if(erro==true){
    document.form1.pc10_numeroate.focus();
    document.form1.pc10_numeroate.value = '';
  }
}

function js_mostrasolicitaate1(chave1,x){
  
  document.form1.pc10_numeroate.value = chave1;
  db_iframe_solicitaate.hide();
}



//pesquisa dotacao

function js_pesquisarh72_coddot(mostra){
  
  if(mostra==true){
  js_OpenJanelaIframe('', 
                      'db_iframe_orcdotacao', 
                      'func_orcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_coddot', 
                      'Pesquisar Dotações',true);
  }else{
    
    js_OpenJanelaIframe('',
                        'db_iframe_orcdotacao',
                        'func_orcdotacao.php?pesquisa_chave='+document.form1.dotacao.value+'&funcao_js=parent.js_mostraorcdotacao',
                        'Pesquisa de Dotacoes',
                        false
                        );
  }
}
function js_mostraorcdotacao(chave,erro) {

  if (erro == true) {
  
  document.form1.dotacao.focus(); 
  document.form1.dotacao.value = ''; 
  } else {
  //js_getDotacoes();
  }
}
function js_mostraorcdotacao1(chave1) {

  document.form1.dotacao.value = chave1;
  db_iframe_orcdotacao.hide();
  //js_getOrigemDotacao(chave1);
}





</script>