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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

$clrotulo = new rotulocampo;
$clrotulo->label("x40_codcorte");
$clrotulo->label("x40_dtinc");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC style="margin: 25px auto;">

<form name="form1" method="post" action="">
<table align="center">

<tr>
  <td nowrap title="<?=@$Tx40_codcorte?>" align="center">
  <?
    db_ancora(@$Lx40_codcorte,"js_pesquisax40_codcorte(true);",$db_opcao);
    db_input('x40_codcorte',10,$Ix40_codcorte,true,'text',$db_opcao," onchange='js_pesquisax40_codcorte(false);'");
    db_input('x40_dtinc',10,$Ix40_dtinc,true,'text',3,'');
  ?>
  </td>
</tr> 

<tr>
  <td align="center"><strong>Ordenar por:</strong>
  <?
    $aOrdenar = array('matricula'=>'Matricula', 'logradouro'=>'Logradouro', 'leituracorte'=>'Leitura Corte', 'leituraleiturista'=>'Leitura Leiturista', 'data'=>'Data Instalação');
    db_select('ordenar', $aOrdenar, true, 1); 
  ?>
  </td>
</tr>

<tr>
  <td align="center">
    <br>
    <input name="processar" id="processar" type="button" value="Processar" onclick="js_processar();">
  </td>
</tr>

</table>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script type="text/javascript">

function js_processar() {
	var codcorte     = document.form1.x40_codcorte.value;
	var ordenar      = document.form1.ordenar.value;
	var sQueryString = '?ordenar=' + ordenar + '&corte=' + codcorte;

	if(codcorte == '') {
		alert('Lista de corte não informada.');
		return false; 
	}

	jan = window.open('agu2_compleituras002.php'+sQueryString,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	
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
	chave = chave[2] + '/' + chave[1] +'/' + chave[0];
	
  document.form1.x40_dtinc.value = chave; 
  if(erro==true){ 
    document.form1.x40_codcorte.value = '';
    document.form1.x40_dtinc.value    = ''; 
    document.form1.x40_codcorte.focus(); 
  }
}

function js_mostraaguacorte1(chave1,chave2){

	chave2 = chave2.split('-');
	chave2 = chave2[2] + '/' + chave2[1] +'/' + chave2[0];
	
	document.form1.x40_codcorte.value = chave1;
	document.form1.x40_dtinc.value = chave2;
	db_iframe_aguacorte.hide();
}

</script>
</body>
</html>