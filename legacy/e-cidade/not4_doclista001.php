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
include("classes/db_listadoc_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cllistadoc = new cl_listadoc;
$clrotulo = new rotulocampo;
$clrotulo->label("k60_codigo");
$clrotulo->label("k60_descr");
$clrotulo->label("db03_docum");
$clrotulo->label("db03_descr");
$sqlerro=false;
$abil=false;
$instit = db_getsession("DB_instit");
if (isset($incluir)){
	$sqlerro=false;
	db_inicio_transacao();
	$result_listadoc=$cllistadoc->sql_record($cllistadoc->sql_query_file($k60_codigo));
   	if ($cllistadoc->numrows>0){
   		$cllistadoc->excluir($k60_codigo);
   		if ($cllistadoc->erro_status=0){
   			$sqlerro=true;
   			$erro_msg=$cllistadoc->erro_msg;
   		}  		    		    		
   	}
   	$cllistadoc->k64_docum=$db03_docum;
   	$cllistadoc->k64_codigo=$k60_codigo;
   	$cllistadoc->incluir($k60_codigo);
   	if ($sqlerro==false){
  	    $erro_msg=$cllistadoc->erro_msg;
   	}
	if ($cllistadoc->erro_status=0){
		$sqlerro=true;
	}
	db_fim_transacao($sqlerro);
}
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
<body bgcolor=#CCCCCC>

<form class="container" name="form1" method="post" action="">
  <fieldset>
	<legend>Procedimentos - Documento da Lista</legend>
  <table class="form-container">
      <tr> 
        <td title="<?=$Tk60_codigo?>">
          <?db_ancora('Lista',"js_pesquisa_lista(true);",1); ?>:
        </td>
        <td>
          <?db_input("k60_codigo",6,$Ik60_codigo,true,"text",4,"onchange='js_pesquisa_lista(false);'"); ?>
          <?db_input("k60_descr",40,$Ik60_descr,true,"text",3); ?>
        </td>
      </tr>
      <?
        if (isset($k60_codigo)&&$k60_codigo!=""){
          $result_listadoc=$cllistadoc->sql_record($cllistadoc->sql_query("","k64_docum,db03_descr","","k64_codigo = $k60_codigo and k60_instit = $instit"));
          if ($cllistadoc->numrows>0){
          	db_fieldsmemory($result_listadoc,0);
          	$db03_docum=$k64_docum;    		    		
          }
          $abil=true;
      ?>
      <tr> 
        <td title="<?=$Tdb03_docum?>">
          <?db_ancora('Documento',"js_pesquisa_docum(true);",1); ?>:
        </td>
        <td>
          <?db_input("db03_docum",6,$Idb03_docum,true,"text",4,"onchange='js_pesquisa_docum(false);'"); ?>
          <?db_input("db03_descr",40,$Idb03_descr,true,"text",3); ?>
        </td>
      </tr>
      <?
        }
      ?>
    </table>
  </fieldset>
  <input  name="incluir" id="incluir" type="submit" value="Incluir" <?=$abil==false?'disabled':''?>>
</form>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisa_lista(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lista','func_lista.php?funcao_js=parent.js_mostralista1|k60_codigo|k60_descr','Pesquisa',true);
  }else{
     if(document.form1.k60_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lista','func_lista.php?pesquisa_chave='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostralista','Pesquisa',false);
     }else{
       document.form1.k60_codigo.value = ''; 
     }
  }
}
function js_mostralista(chave,erro){
  document.form1.k60_descr.value = chave; 
  if(erro==true){ 
    document.form1.k60_codigo.value = ''; 
    document.form1.k60_codigo.focus(); 
  }else{
  	document.form1.submit();
  }
}
function js_mostralista1(chave1,chave){
   document.form1.k60_codigo.value = chave1;
   document.form1.k60_descr.value = chave;
   document.form1.submit();  
   db_iframe_lista.hide();
}
function js_pesquisa_docum(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_documento','func_db_documento.php?funcao_js=parent.js_mostradb_documento1|db03_docum|db03_descr','Pesquisa',true);
  }else{
     if(document.form1.db03_docum.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_documento','func_db_documento.php?pesquisa_chave='+document.form1.db03_docum.value+'&funcao_js=parent.js_mostradb_documento','Pesquisa',false);
     }else{
       document.form1.db03_docum.value = ''; 
     }
  }
}
function js_mostradb_documento(chave,erro){
  document.form1.db03_descr.value = chave; 
  if(erro==true){ 
    document.form1.db03_docum.value = ''; 
    document.form1.db03_docum.focus(); 
  }
}
function js_mostradb_documento1(chave1,chave){
   document.form1.db03_docum.value = chave1;
   document.form1.db03_descr.value = chave;
   db_iframe_db_documento.hide();
}
</script>
<?
if(isset($incluir)){
  db_msgbox($erro_msg);
  if($sqlerro==false){
  	echo "<script>location.href='not4_doclista001.php';</script>";
  }
}
?>
<script>

$("k60_codigo").addClassName("field-size2");
$("k60_descr").addClassName("field-size7");
<?php if (isset($k60_codigo)&&$k60_codigo!=""){ ?>
  $("db03_docum").addClassName("field-size2");
  $("db03_descr").addClassName("field-size7");
<?php } ?>

</script>