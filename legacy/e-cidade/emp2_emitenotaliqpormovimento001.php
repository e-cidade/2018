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
$db_opcao = 1;
$clrotulo = new rotulocampo;
$clrotulo->label("e50_codord");
$clrotulo->label("e81_codmov");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
  <?php 
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
<center>
	<fieldset style = "margin-top:25px; width:500px;">
		<legend><strong>Emite Ordem de Pagamento por Movimento</strong></legend>
		<table>
			<tr>
				<td><?php db_ancora("<b>Nota de Liquidação</b>","js_pesquisaOrdem(true);",1) ?></td>
				<td style="font-weight: bold;">
					<? db_input('e50_codord', 23, $Ie50_codord, 
					            true, 'text', $db_opcao, " onchange='js_pesquisaOrdem(false);' ") ?>
				</td>
			</tr>
			<tr>
			  <td><?php db_ancora($Le81_codmov, 'js_pesquisaMovimento(true);', 1) ?></td>
			  <td><?php db_input('e81_codmov', 23, $Ie81_codmov, true, 
													 'text', $db_opcao, " onchange='js_pesquisaMovimento(false);' ") ?></td>
			</tr>
			<tr>
			  <td colspan="2">
			    <fieldset>
			      <legend><strong>Observação</strong></legend>
			      <?php db_textarea('observacoes', 5, 60, 0, true, 'text', 2); ?>
			    </fieldset>
			  </td>
			</tr>
		</table>
	</fieldset>
	<input type="button" value="Emitir" onclick="js_emiteOrdem();" style="margin-top:15px;">
</center>
</body>
</html>

<script type="text/javascript">

function js_pesquisaOrdem(mostra) {
  
  if(mostra) {
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem',
                        'func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord',
                        'Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem',
                        'func_pagordem.php?pesquisa_chave='+$('e50_codord').value+'&funcao_js=parent.js_mostrapagordem',
                        'Pesquisa',false);
  }
}
function js_mostrapagordem(chave,erro) {
  if(erro==true){ 
    $('e50_codord').focus(); 
    $('e50_codord').value = ''; 
  }
}
function js_mostrapagordem1(chave1){
  $('e50_codord').value = chave1;
  db_iframe_pagordem.hide();
}

/*  */
function js_pesquisaMovimento(mostra) {

  if (mostra) {
  
    if ($F('e50_codord') === '') {
      js_OpenJanelaIframe('top.corpo','db_iframe_movs',
                          'func_empagemovordem.php?funcao_js=parent.js_mostramov1|e81_codmov','Pesquisa',true);
    } else {
      js_OpenJanelaIframe('top.corpo','db_iframe_movs',
                          'func_empagemovordem.php?e50_codord='+$F('e50_codord')+'&funcao_js=parent.js_mostramov1|e81_codmov',
                          'Pesquisa',true);
    }
  }else{
    if ($F('e50_codord') === '') {
      js_OpenJanelaIframe('top.corpo','db_iframe_movs',
                          'func_empagemovordem.php?pesquisa_chave='+$('e81_codmov').value+'&funcao_js=parent.js_mostramov',
                          'Pesquisa',false);
    } else {
      js_OpenJanelaIframe('top.corpo','db_iframe_movs',
                          'func_empagemovordem.php?e50_codord='+$F('e50_codord')+'&pesquisa_chave='+$('e81_codmov').value+'&funcao_js=parent.js_mostramov',
                          'Pesquisa',false);
    }
  }
}
function js_mostramov(chave,erro){
  if(erro==true){ 
    $('e81_codmov').focus(); 
    $('e81_codmov').value = ''; 
  }
}
function js_mostramov1(chave1){
  $('e81_codmov').value = chave1;
  db_iframe_movs.hide();
}
/*  */

/**
 * Função que emite a Ordem
 */
function js_emiteOrdem() {

  if (($F('e50_codord') === "") && ($F('e81_codmov') === "")) {

    alert("Os dados da pesquisa devem ser preenchidos.");
    return false;
  }

  var sUrl  = 'emp2_emitenotaliqpormovimento002.php?e50_codord='+$F('e50_codord');
      sUrl += '&e81_codmov='+$F('e81_codmov')+'&sObservacao='+$F('observacoes');
  jan = window.open(sUrl, 
                    '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>