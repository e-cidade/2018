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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

$clrotulo = new rotulocampo;
$clrotulo->label('r44_selec');
$clrotulo->label('r44_descr');
$clrotulo->label('rh49_anousu');
$clrotulo->label('rh49_mesusu');

?>
<html>
<head>
<?php 
	db_app::load("scripts.js, prototype.js, strings.js, estilos.css");
?>
</head>
<body bgcolor="#CCCCCC">

<form name="form1" method="post" action="">
	<fieldset style="margin:25px auto 10px auto; width:450px">
		<legend><b>Processar Valor Mensal </b></legend>
		<table align="center">
		  <tr>
		    <td title="Ano / Mês de competência">
		      <b>Ano / Mês:</b>
		    </td>
		    <td>
		      <?
		        $rh49_anousu = db_anofolha();
			      db_input('rh49_anousu',4,$Irh49_anousu,true,'text',3,"");
		      ?>
		      
		      &nbsp;/&nbsp;
		      
		      <?
		        $rh49_mesusu = db_mesfolha();
		      	db_input('rh49_mesusu',2,$Irh49_mesusu,true,'text',3,"");
		      ?>
		    </td>
		  </tr>
		  <tr>
		    <td title="Digite o Ano / Mes para levar em consideração os afastamentos">
		    	<strong>Ano / Mês Afastamentos:</strong>
		    </td>
		    <td>
		      <?
		      	$iAnoAfastamento = db_anofolha();
		       
		       	db_input('iAnoAfastamento',4, 1,true,'text',2,'');
		      ?>
		      &nbsp;/&nbsp;
		      <?
						$iMesAfastamento = db_mesfolha();
		       	
						db_input('iMesAfastamento',2, 1,true,'text',2,'');
		      ?>
		    </td>
		  </tr>
		  <tr>
		    <td title="Seleção:">
		      <?
		    	 db_ancora("<b>Não Gerar Para Seleção:</b>","js_pesquisaSelecao(true)",1);
		      ?>
		    </td>
		    <td>
	       <?
		       db_input('r44_selec',4,$Ir44_selec,true,'text',2,'onchange="js_pesquisaSelecao(false)"');
		       db_input('r44_descr',25,$Ir44_descr,true,'text',3,'');
	       ?>
		    </td>
		  </tr>
		</table>
	</fieldset>
	<center>
		<input name="processar" type="button" id="processar" value="Processar" onclick="return js_processar();">
	</center>
</form>

<script>

function js_pesquisaSelecao(mostra) {
  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?funcao_js=parent.js_mostraSelecao1|r44_selec|r44_descr','Pesquisa',true);
  } else {
     if ($F('r44_selec') != '') { 
        js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?pesquisa_chave='+$F('r44_selec')+'&funcao_js=parent.js_mostraSelecao','Pesquisa',false);
     } else {
       $('r44_descr').value = '';
     }
  }
}

function js_mostraSelecao(chave,erro) {
  $('r44_descr').value = chave;
  if (erro == true) {
    $('r44_selec').focus();
    $('r44_selec').value = '';
  }
}

function js_mostraSelecao1 (chave1,chave2) {
  $('r44_selec').value = chave1;
  $('r44_descr').value = chave2;
  db_iframe_selecao.hide();
}

sUrl = 'pes4_rhvalealimentacaovalormensal.RPC.php';

function js_processar() {
	
  if (!confirm("Todos os registros serão processados. Deseja continuar?")) {
    return false;
  } 

  var oParametros = new Object();

  oParametros.sExec 					= 'processar';
  oParametros.iAnoFolha 			= $F('rh49_anousu');
  oParametros.iMesFolha 			= $F('rh49_mesusu');
  oParametros.iAnoAfastamento = $F('iAnoAfastamento');
  oParametros.iMesAfastamento = $F('iMesAfastamento');
  oParametros.iCodigoSelecao 	= $F('r44_selec');

	js_divCarregando('Processando registros, aguarde.', 'msgbox');

	var oAjax = new Ajax.Request(sUrl,
			                        {
	                             method    : 'POST',
                             	 parameters: 'json=' + Object.toJSON(oParametros), 
                               onComplete: js_confirma
                              });

  
  
}

function js_confirma(oAjax){

  js_removeObj('msgbox');

  var sExpReg  = new RegExp('\\\\n','g');
  
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.iStatus == 1) {
    
    sMensagem = "Processamento realizado com sucesso.";

    alert(sMensagem);
    
  } else { 

    alert(oRetorno.sMessage.urlDecode().replace(sExpReg,'\n'));

    return false;
    
  }
  
}

</script>

<?
	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>