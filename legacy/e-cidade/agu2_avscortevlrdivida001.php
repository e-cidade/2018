<?php
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
include("dbforms/db_classesgenericas.php");
require("libs/db_app.utils.php");

$clrotulo = new rotulocampo;
$clrotulo->label("x40_codcorte");
$clrotulo->label("x40_dtinc");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load('scripts.js');
  db_app::load('estilos.css');
?>
</head>
<body bgcolor=#CCCCCC>
<form name="form1">
<table align="center" style="margin: 20px auto">
  <tr>
    <td nowrap title="<?=@$Tx40_codcorte?>">
    <?
      db_ancora(@$Lx40_codcorte,"js_pesquisax40_codcorte(true);",$db_opcao);
    ?>
    </td>
    <td>
    <?
	    db_input('x40_codcorte',10,$Ix40_codcorte,true,'text',$db_opcao," onchange='js_pesquisax40_codcorte(false);'");
    ?>
    </td>
  </tr>
  <tr>
	  <td nowrap title="<?=@$Tx40_dtinc?>">
	  <?=$Lx40_dtinc?>
	  </td>
	  <td> 
	  <?
	    db_input('x40_dtinc',10,$Ix40_dtinc,true,'text',3,'');
	  ?>
	  </td>
  </tr>
  <tr>
    <td nowrap title="Ordem do relat&oacute;rio">
      <strong>Ordenar por:</strong>
    </td>
    <td nowrap title="Campo a ser ordenado">
    <?
      $aOrdem = array('corte'     => 'C&oacute;digo do corte', 
                      'historico' => 'Valor Hist&oacute;rico',
                      'corrigido' => 'Valor Corrigido',
                      'tipo'      => 'Tipo de D&eacute;bito');
      db_select('ordem', $aOrdem, true, 1);
    ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="button" name="processar" value="Processar" onclick="js_processar()"/>
    </td>
  </tr>
</table>

<? 
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</form>
<script type="text/javascript">
function js_processar() {

	if(document.form1.x40_codcorte.value == '') {
		alert('Código da lista de corte não informado.')
		document.form1.x40_codcorte.focus();
		return false;
	}
	
  jan = window.open('agu2_avscortevlrdivida002.php?corte='+document.form1.x40_codcorte.value+'&ordem='+document.form1.ordem.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  
  jan.moveTo(0,0);
  
}

function js_pesquisax40_codcorte(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_aguacorte','func_aguacorte.php?funcao_js=parent.js_mostraaguacorte1|x40_codcorte|x40_dtinc','Pesquisa',true,20);
  }else{
    if(document.form1.x40_codcorte.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_aguacorte','func_aguacorte.php?pesquisa_chave='+document.form1.x40_codcorte.value+'&funcao_js=parent.js_mostraaguacorte','Pesquisa',false);
    }else{
      document.form1.x40_dtinc.value = ''; 
    }
  }
}
function js_mostraaguacorte(chave,erro){

	chave = chave.split('-');
	chave = chave[2] + '/' + chave[1] + '/' + chave[0]; 

  document.form1.x40_dtinc.value = chave; 
  if(erro==true){ 
	  document.form1.x40_codcorte.focus(); 
	  document.form1.x40_codcorte.value = ''; 
	}
}

function js_mostraaguacorte1(chave1,chave2){
	
	chave2 = chave2.split('-');
	chave2 = chave2[2] + '/' + chave2[1] + '/' + chave2[0];
	
  document.form1.x40_codcorte.value = chave1;
  document.form1.x40_dtinc.value = chave2;
  db_iframe_aguacorte.hide();
  
}

</script>
</body>
</html>