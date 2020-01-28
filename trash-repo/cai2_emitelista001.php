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
require_once('libs/db_utils.php');
require_once("libs/db_libpostgres.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);

$clpostgresqlutils = new PostgreSQLUtils;
$clrotulo          = new rotulocampo;
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k51_procede');
$clrotulo->label('k51_descr');

if (count($clpostgresqlutils->getTableIndexes('debitos')) == 0) {
  
  db_msgbox(_M('tributario.notificacoes.cai2_emitelista001.problema_indices_debitos'));
  $db_botao = false; 
  $db_opcao = 3;
} else {
  
  $db_botao = true;
  $db_opcao = 4;
}
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

    var val1 = new Number(document.form1.k60_codigo.value);
    if(val1.valueOf() < 1){
       alert(_M('tributario.notificacoes.cai2_emitelista001.selecione_lista'));
       return false;
    }

  
  jan = window.open('cai2_emitelista002.php?ordem='+document.form1.ordem.value+
                    '&lista='+document.form1.k60_codigo.value+
                    '&agrupar='+document.form1.agrupar.value+
                    '&tipo='+document.form1.tipo.value+
                    '&comvalor='+document.form1.comvalor.value+
                    '&filtro='+document.form1.filtro.value,
                    '',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc" onLoad="a=1" >

  
<form class="container" name="form1" method="post" action="" >
  <fieldset>
    <legend>Relatórios - Listas</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tk60_codigo?>" >
          <?
	        db_ancora(@$Lk60_codigo,"js_pesquisalista(true);",$db_opcao)
          ?>
        </td>
        <td>
          <?
	        db_input('k60_codigo',10,$Ik60_codigo,true,'text',$db_opcao,"onchange='js_pesquisalista(false);'");
            db_input('k60_descr',40,$Ik60_descr,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Ordem para a emissão da lista" >
          Ordem:
        </td>
        <td>
          <?
            $xx = array("v"=>"Valor", "a"=>"Alfabética","n"=>"Numérica");
            db_select('ordem',$xx,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Agrupar" >
          Agrupar por nome: 
        </td>
        <td>
          <?
            $xx = array("n"=>"Nao", "s"=>"Sim");
            db_select('agrupar',$xx,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Emitir com valor" >
          Emitir com valor : 
        </td>
        <td>
          <?
            $arraycomvalor = array("s"=>"Sim", "n"=>"Não");
            db_select('comvalor',$arraycomvalor,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Tipo de geracao" >
          Tipo de Geracao:
        </td>
        <td>
          <?
            $xx = array("p"=>"PDF", "t"=>"TXT");
            db_select('tipo',$xx,true,$db_opcao," onchange='js_filtro();' ");
          ?>
        </td>
      </tr>      
      <tr id="filtro1">
        <td nowrap title="Filtro">
          Informações Adicionais:
        </td>
        <td>
          <?
            $sn = array("s"=>"Sim", "n"=>"Não");
            db_select('filtro',$sn,true,$db_opcao,"");
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="imprime" type="button" id="imprime" value="Imprimir" onClick="js_emite();"
         <?=($db_botao ? '' : 'disabled')?>>  
</form>
  

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisa(){
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_pesquisalista(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_lista.php?funcao_js=parent.js_mostralista1|k60_codigo|k60_descr';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_lista.php?pesquisa_chave='+document.form1.k60_codigo.value+
                                     '&funcao_js=parent.js_mostralista';
     }
}
function js_mostralista(chave,erro){
  document.form1.k60_descr.value = chave;
  if(erro==true){
     document.form1.k60_descr.focus();
     document.form1.k60_descr.value = '';
  }
}
function js_mostralista1(chave1,chave2){
     document.form1.k60_codigo.value = chave1;
     document.form1.k60_descr.value = chave2;
     db_iframe.hide();
}
function js_filtro(){
	var tipo = document.form1.tipo.value;
	if(tipo =='t'){
	  document.getElementById('filtro1').style.visibility = 'hidden';
	}else{
	  document.getElementById('filtro1').style.visibility = 'visible';
	}
}
</script>
<?
  $func_iframe = new janela('db_iframe','');
  $func_iframe->posX=1;
  $func_iframe->posY=20;
  $func_iframe->largura=780;
  $func_iframe->altura=430;
  $func_iframe->titulo='Pesquisa';
  $func_iframe->iniciarVisivel = false;
  $func_iframe->mostrar();
?>
<script>

$("k60_codigo").addClassName("field-size2");
$("k60_descr").addClassName("field-size7");
$("ordem").setAttribute("rel","ignore-css");
$("ordem").addClassName("field-size9");
$("agrupar").setAttribute("rel","ignore-css");
$("agrupar").addClassName("field-size2");
$("comvalor").setAttribute("rel","ignore-css");
$("comvalor").addClassName("field-size2");
$("tipo").setAttribute("rel","ignore-css");
$("tipo").addClassName("field-size2");
$("filtro").setAttribute("rel","ignore-css");
$("filtro").addClassName("field-size2");

</script>