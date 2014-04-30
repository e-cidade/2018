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
?>
<form name="form1" action="fis4_aidof004.php" class="container" method="get">
	<fieldset>
		<legend>Procedimentos - Liberar AIDOF</legend>
		<table border="0" align="center" class="form-container">
			<tr>
				<td>
					<?php
						db_ancora("Inscrição:","js_pesquisay08_inscr(true);",$db_opcao);
					?>
				</td>
				<td> 
					<?php
						db_input('y08_inscr', 6 , 1, true, 'text', $db_opcao, " class=' field-size2' onchange='js_pesquisay08_inscr(false);'");
						db_input('z01_nome' , 30, 0, true, 'text', 3        , " class=' field-size9' ");
					?>
				</td>
			</tr>
		</table>
	</fieldset>
	<input name="processar" type="button" value="Processar" onclick="js_processar()" disabled id="processar" />
</form>
<script>

function js_processar() {
  document.forms[0].submit();    
}

function js_pesquisay08_inscr( mostra ) {

  if ( mostra ) {
    js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_mostraissbase1|q02_inscr|z01_nome|q02_dtbaix','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_issbase','func_issbase.php?pesquisa_chave='+document.form1.y08_inscr.value+'&funcao_js=parent.js_mostraissbase','Pesquisa',false);
  }
}

function js_mostraissbase( chave, erro, baixa ){

  document.form1.z01_nome.value = chave; 

  if( erro==true){ 

    document.form1.y08_inscr.focus(); 
    document.form1.y08_inscr.value = ''; 
    $('processar').disabled = true;
  } else {

    if ( baixa != "" ) {

      alert("Inscrição já Baixada");
      document.form1.y08_inscr.focus(); 
      document.form1.y08_inscr.value = ''; 
      document.form1.z01_nome.value  = ''; 
    $('processar').disabled = true;
    }
    $('processar').disabled = false;
  }
}

function js_mostraissbase1( chave1, chave2, baixa ) {

  if ( baixa != "" ) {

    db_iframe_issbase.hide();

    alert("Inscrição já Baixada");

    document.form1.y08_inscr.focus(); 
    document.form1.y08_inscr.value = ''; 
    document.form1.z01_nome.value  = '';
    $('processar').disabled = true;
  } else {

    document.form1.y08_inscr.value = chave1;
    document.form1.z01_nome.value  = chave2;
    db_iframe_issbase.hide();
    $('processar').disabled = false;
  }
}

</script>