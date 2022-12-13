<?
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("pc10_numero");
$clrotulo->label("l20_codigo");
$clrotulo->label("pc80_codproc");
$clrotulo->label("l20_licsituacao");
$clrotulo->label("l03_codigo");
$clrotulo->label("l03_descr");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function js_abreconsulta() {
  
	if (document.form1.l20_codigo.value != "") {
    
		js_OpenJanelaIframe('top.corpo',
                        'db_iframe_infolic',
                        'lic3_licitacao002.php?l20_codigo='+$F('l20_codigo'),
                        'Consulta Licitação',true);
	} else if(document.form1.pc80_codproc.value != "") {
    
		js_OpenJanelaIframe('top.corpo',
                        'db_iframe_liclicita',
                        'func_liclicitaalt.php?pc80_codproc='+$F('pc80_codproc')+'&funcao_js=parent.js_abreconsulta2|l20_codigo',
                        'Pesquisa', true);
	} else if(document.form1.pc10_numero.value != "") {
    
		js_OpenJanelaIframe('top.corpo',
                        'db_iframe_liclicita',
                        'func_liclicitaalt.php?pc10_numero='+$F('pc10_numero')+'&funcao_js=parent.js_abreconsulta2|l20_codigo',
                        'Pesquisa',true);
	}
  document.form1.l20_codigo.value="";
}
function js_abreconsulta2(codigo) {
  
	db_iframe_liclicita.hide();
	js_OpenJanelaIframe('top.corpo','db_iframe_infolic','lic3_infolic002.php?l20_codigo='+codigo,'Consulta Licitação',true);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC >

<center>
<div style="margin-top: 20px">
  <form name="form1" method="post">
    <fieldset style="width: 550;">
      <legend><strong>Consulta Licitações</strong></legend>
      <table >
        <tr>
          <td>
            <?php 
              db_ancora("<b>{$Ll03_codigo}</b>", "js_pesquisaTipoLicitacao(true);", 1);
            ?>
          </td>
          <td>
            <?php 
              db_input('l03_codigo', 10, $Il03_codigo, true, 'text', 1, "onchange='js_pesquisaTipoLicitacao(false);'");
              db_input('l03_descr', 30, $Il03_descr, true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr> 
          <td nowrap="nowrap" title="<?=$Tl20_codigo?>">
            <b><?db_ancora('Licitação:',"js_pesquisa_liclicita(true);",1);?></b> 
          </td>
          <td align="left" nowrap="nowrap">
            <? 
              db_input("l20_codigo",10,$Il20_codigo,true,"text",3,"onchange='js_pesquisa_liclicita(false);'");
            ?>
          </td>
        </tr>
        <tr> 
          <td nowrap="nowrap" title='$Tpc80_codproc'>
            <?db_ancora(@$Lpc80_codproc,"js_pesquisa_pcproc(true);",1);?> 
          </td>
          <td align='left' nowrap="nowrap">
            <?
              db_input("pc80_codproc",10,$Ipc80_codproc,true,"text",4,"onchange='js_pesquisa_pcproc(false);'"); 
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" title='<?=$Tpc10_numero?>' > 
            <?db_ancora(@$Lpc10_numero,"js_pesquisa_solicita(true);",1) ?> 
          </td>
          <td align='left' nowrap>
            <?
              db_input("pc10_numero",10,$Ipc10_numero,true,"text",4,"onchange='js_pesquisa_solicita(false);'"); 
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <br>
    <input name="processar" type="button" onclick='js_abreconsulta();'  value="Processar">
  </form>
</div>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>

var lAvisoModalidadeLicitacao = true;


function js_pesquisaTipoLicitacao(lMostra) {

  if (lAvisoModalidadeLicitacao) {
    alert("Aviso:\n\nAo informar a modalidade de compra, serão listados somente licitações com a modalidade informada.");
    lAvisoModalidadeLicitacao = false;
  }
  var sQueryString = "func_cflicita.php?pesquisa_chave="+$F('l03_codigo')+"&funcao_js=parent.js_completaTipoLicitacao";
  if (lMostra) {
    sQueryString = "func_cflicita.php?funcao_js=parent.js_preencheTipoLicitacao|l03_codigo|l03_descr";
  }
  js_OpenJanelaIframe('top.corpo', 'db_iframe_cflicita', sQueryString, "Pesquisa Tipos de Compra", lMostra);
}


function js_preencheTipoLicitacao(iCodigoTipo, sDescricaoTipo) {

  $("l03_codigo").value = iCodigoTipo;
  $("l03_descr").value = sDescricaoTipo;
  db_iframe_cflicita.hide();
}

function js_completaTipoLicitacao(sDescricao, lErro) {

  $("l03_descr").value = sDescricao;
  if (lErro) {
    $("l03_codigo").value = '';
  }
}

function js_pesquisa_solicita(mostra) {
  
  if (mostra) {
    js_OpenJanelaIframe('top.corpo','db_iframe_solicita','func_solicita.php?funcao_js=parent.js_mostrasolicita1|pc10_numero','Pesquisa',true);
  } else {
    
     if (document.form1.pc10_numero.value != '') { 
        js_OpenJanelaIframe('top.corpo','db_iframe_solicita','func_solicita.php?pesquisa_chave='+document.form1.pc10_numero.value+'&funcao_js=parent.js_mostrasolicita','Pesquisa',false);
     }
  }
}
function js_mostrasolicita(chave,erro) {
  
  if (erro) {
     
    document.form1.pc10_numero.focus(); 
    document.form1.pc10_numero.value = ''; 
  }
}
function js_mostrasolicita1(chave1,chave2) {
  
  document.form1.pc10_numero.value = chave1;
  db_iframe_solicita.hide();
}

function js_pesquisa_pcproc(mostra) {
  
  if (mostra) {
    js_OpenJanelaIframe('top.corpo','db_iframe_pcproc','func_pcproc.php?funcao_js=parent.js_mostrapcproc1|pc80_codproc','Pesquisa',true);
  } else {
    
     if (document.form1.pc80_codproc.value != '') {
        js_OpenJanelaIframe('top.corpo','db_iframe_pcproc','func_pcproc.php?pesquisa_chave='+document.form1.pc80_codproc.value+'&funcao_js=parent.js_mostrapcproc','Pesquisa',false);
     }
  }
}
function js_mostrapcproc(chave,erro) {
  
  if (erro) {
     
    document.form1.pc80_codproc.focus(); 
    document.form1.pc80_codproc.value = ''; 
  }
}
function js_mostrapcproc1(chave1,chave2) {
  
  document.form1.pc80_codproc.value = chave1;
  db_iframe_pcproc.hide();
}
function js_pesquisa_liclicita(mostra) {

  var iModalidadeLicitacao = '';
  if ($F('l03_codigo') != "") {
    iModalidadeLicitacao = $F('l03_codigo');
  }
  
  if (mostra) {
    js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicita.php?iModalidadeLicitacao='+iModalidadeLicitacao+'&funcao_js=parent.js_mostraliclicita1|l20_codigo','Pesquisa',true);
  } else {
    
     if (document.form1.l20_codigo.value != '') { 
        js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicita.php?iModalidadeLicitacao='+iModalidadeLicitacao+'&pesquisa_chave='+document.form1.l20_codigo.value+'&funcao_js=parent.js_mostraliclicita','Pesquisa',false);
     } else {
       document.form1.l20_codigo.value = ''; 
     }
  }
}
function js_mostraliclicita(chave,erro) {
  
  document.form1.l20_codigo.value = chave; 
  if (erro) {
     
    document.form1.l20_codigo.value = ''; 
    document.form1.l20_codigo.focus(); 
  }
}
function js_mostraliclicita1(chave1) {
  
  document.form1.l20_codigo.value = chave1;  
  db_iframe_liclicita.hide();
}
</script>
</body>
</html>