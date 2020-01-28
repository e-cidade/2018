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
include("libs/db_utils.php");
include ("libs/db_app.utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_bens_classe.php");
$clbens = new cl_bens;
$clrotulo = new rotulocampo;
$clbens->rotulo->label();
$clrotulo->label("descrdepto");
$clrotulo->label("t30_codigo");
$clrotulo->label("t30_descr");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js");
  db_app::load("estilos.css");
?>
</head>
<body bgcolor=#CCCCCC >
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Imprimir Etiquetas</legend>
    <table class="form-container">
      <tr> 
        <td title="<?=$Tt52_depart?>"> <? db_ancora(@$Lt52_depart,"js_pesquisa_depart(true);",1);?>  </td>
        <td>
          <?
            db_input("t52_depart",8,$It52_depart,true,"text",4,"onchange='js_pesquisa_depart(false);'"); 
            db_input("descrdepto",40,$Idescrdepto,true,"text",3);  
          ?>
        </td>
      </tr>
      <tr> 
        <td title="<?=$Tt30_codigo?>"> <? db_ancora(@$Lt30_codigo,"js_pesquisa_divisao(true);",1);?>  </td>
        <td>
          <?
            db_input("t30_codigo",8,$It30_codigo,true,"text",4,"onchange='js_pesquisa_divisao(false);'"); 
            db_input("t30_descr",40,$It30_descr,true,"text",3);  
          ?>
        </td>
      </tr>
      <tr> 
	      <td title="<?=$Tt52_bem?>"> <? db_ancora('<b>Bem Inicial:</b>',"js_pesquisa_bem(true);",1);?>  </td>
	      <td>
	        <?
	          db_input("t52_bem",8,$It52_bem,true,"text",4,"onchange='js_pesquisa_bem(false);'"); 
	        ?>
	        <b>à</b>
	        <? db_ancora('<b>Bem Fim:</b>',"js_pesquisa_bemFinal(true);",1);?>
	        <?
            db_input("t52_bemfinal",8,$It52_bem,true,"text",4,"onchange='js_pesquisa_bemFinal(false);'"); 
          ?>
	      </td>
	    </tr>
    </table>
  </fieldset>
  <input value='Pesquisar' type='button' id='pesquisar' onclick='js_imprimeEtiquetas();'>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_fechar(){
    db_iframe_pesquisa.hide();
    $('t52_depart').value   = '';
    $('t30_codigo').value   = '';
    $('t52_bem').value      = '';
    $('t52_bemfinal').value = '';
    
}

function js_imprimeEtiquetas(){
	 
	 var t52_depart     = $('t52_depart').value;
	 var t30_codigo     = $('t30_codigo').value;
	 var t52_bemInicial = $('t52_bem').value;
	 var t52_bemFinal   = $('t52_bemfinal').value;
	 
	 if(t52_depart == '' && t30_codigo == '' && t52_bemInicial == '' && t52_bemFinal == ''){
	 
	   alert(_M("patrimonial.patrimonio.pat4_emiteetiquetas001.informe_filtro"));
	   return false;
	 }
	 
	 var sQuery  = "?t52_depart="+t52_depart;
       sQuery += "&t30_codigo="+t30_codigo;
	     sQuery += "&t52_beminicial="+t52_bemInicial;
	     sQuery += "&t52_bemfinal="+t52_bemFinal;
	 
	 js_OpenJanelaIframe('top.corpo','db_iframe_pesquisa','pat4_imprimeetiqueta002.php'+sQuery,'Pesquisa de Bens a Imprimir',true);

} 
  
function js_pesquisa_bem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bens','func_bens.php?funcao_js=parent.js_mostrabem1|t52_bem|t52_descr','Pesquisa',true);
  }else{
     if(document.form1.t52_bem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_bens','func_bens.php?pesquisa_chave='+document.form1.t52_bem.value+'&funcao_js=parent.js_mostrabem','Pesquisa',false);
     }else{
       document.form1.t52_descr.value = ''; 
     }
  }
}

function js_mostrabem(chave,erro){
  //document.form1.t52_descr.value = chave; 
  if(erro==true){ 
    document.form1.t52_bem.focus(); 
    document.form1.t52_bem.value = ''; 
  }
}

function js_mostrabem1(chave1,chave2){
  document.form1.t52_bem.value = chave1;
 // document.form1.t52_descr.value = chave2;
  db_iframe_bens.hide();
}

function js_pesquisa_bemFinal(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bens','func_bens.php?funcao_js=parent.js_mostrabemFinal1|t52_bem|t52_descr','Pesquisa',true);
  }else{
     if(document.form1.t52_bemfinal.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_bens','func_bens.php?pesquisa_chave='+document.form1.t52_bemfinal.value+'&funcao_js=parent.js_mostrabemFinal','Pesquisa',false);
     }else{
       //document.form1.t52_descr.value = ''; 
     }
  }
}

function js_mostrabemFinal(chave,erro){
  //document.form1.t52_descr.value = chave; 
  if(erro==true){ 
    document.form1.t52_bemfinal.focus(); 
    document.form1.t52_bemfinal.value = ''; 
  }
}

function js_mostrabemFinal1(chave1,chave2){
  document.form1.t52_bemfinal.value = chave1;
  //document.form1.t52_descr.value = chave2;
  db_iframe_bens.hide();
}

/*JavaScript Departamentos */
function js_pesquisa_depart(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_depart','func_db_depart.php?funcao_js=parent.js_mostradepart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.t52_depart.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_depart','func_db_depart.php?pesquisa_chave='+document.form1.t52_depart.value+'&funcao_js=parent.js_mostradepart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = ''; 
     }
  }
}
function js_mostradepart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.t52_depart.focus(); 
    document.form1.t52_depart.value = ''; 
  }
}
function js_mostradepart1(chave1,chave2){
  document.form1.t52_depart.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_depart.hide();
}
/* Fim dos Departamentos*/

/*JavaScript Divisoes */
function js_pesquisa_divisao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_departdiv',
                        'func_departdiv.php?funcao_js=parent.js_mostradepartdiv1|t30_codigo|t30_descr',
                        'Pesquisa',true);
  }else{
     if(document.form1.t30_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_departdiv',
                            'func_departdiv.php?pesquisa_chave='+document.form1.t30_codigo.value+'&funcao_js=parent.js_mostradepartdiv',
                            'Pesquisa',false);
     }else{
       document.form1.t30_descr.value = ''; 
     }
  }
}
function js_mostradepartdiv(chave,erro){
  document.form1.t30_descr.value = chave; 
  if(erro==true){ 
    document.form1.t30_codigo.focus(); 
    document.form1.t30_codigo.value = ''; 
  }
}
function js_mostradepartdiv1(chave1,chave2){
  document.form1.t30_codigo.value = chave1;
  document.form1.t30_descr.value = chave2;
  db_iframe_departdiv.hide();
}
/* Fim dos Divisoes*/  
</script>
<script>

$("t52_depart").addClassName("field-size2");
$("descrdepto").addClassName("field-size7");
$("t30_codigo").addClassName("field-size2");
$("t30_descr").addClassName("field-size7");
$("t52_bem").addClassName("field-size2");
$("t52_bemfinal").addClassName("field-size2");

</script>