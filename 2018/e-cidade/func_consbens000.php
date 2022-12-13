<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_bens_classe.php"));
require_once(modification("classes/db_db_depart_classe.php"));
$cldb_depart = new cl_db_depart;
$clbens = new cl_bens;
$clrotulo = new rotulocampo;
$clbens->rotulo->label();
$clrotulo->label("descrdepto");

db_postmemory($_POST);
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script>
    function js_abre(botao){
      var query = "";
      x = document.form1;
      erro = 0;
      for(i = 0;i < x.length;i++){
        if(x.elements[i].type == "text"){
          if(x.elements[i].name!="t52_descr" && x.elements[i].name!="descrdepto" && x.elements[i].value==""){
            erro++;
          }
        }
      }

      if(erro == 3){
        alert(_M("patrimonial.patrimonio.func_consbens000.informe_campo"));
      }else if(botao=="pesquisa" || botao=="relatorio"){

        if(botao=="pesquisa"){
          if(x.t52_ident.value!=""){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_func_consbens001','pat1_consbens002.php?t52_ident='+document.form1.t52_ident.value+query + '&lObrigaConta=false','Pesquisa',true);
          }else if(x.t52_bem.value!=""){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_func_consbens001','pat1_consbens002.php?t52_ident=&lObrigaConta=false&t52_bem='+document.form1.t52_bem.value+query,'Pesquisa',true);
          }else if(x.t52_depart.value!=""){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_func_consbensdepart001','func_bens.php?funcao_js=parent.js_mostrabens|t52_bem&lObrigaConta=false&chave_depto='+document.form1.t52_depart.value,'Pesquisa',true);
          }
        }else if(botao=="relatorio"){
          if(x.t52_ident.value!=""){
            jan = window.open('pat2_bens002.php?t52_ident='+document.form1.t52_ident.value+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
          }else if(x.t52_bem.value!=""){
            jan = window.open('pat2_bens002.php?t52_bem=('+document.form1.t52_bem.value+')'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
          }else if(x.t52_depart.value!=""){
            jan = window.open('pat2_bensdepart002.php?t52_depart='+document.form1.t52_depart.value+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
          }
        }
        jan.moveTo(0,0);
        document.form1.t52_bem.style.backgroundColor='';
      }
    }
    function js_mostrabens(chave1){
      if(chave1 != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_func_consbensdepart001','pat1_consbens002.php?t52_bem='+chave1,'Pesquisa',true);
      }
    }
  </script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC onLoad="document.form1.t52_ident.focus();">
<form class="container" name="form1" method="post">
  <fieldset>
    <legend>Consulta de Bens:</legend>
    <table class="form-container">
      <tr>
        <td title="<?=$Tt52_ident?>"> <? db_ancora(@$Lt52_ident,"",3);?>  </td>
        <td>
          <?
          db_input("t52_ident",20,$It52_ident,true,"text",4,"");
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=$Tt52_bem?>"> <? db_ancora(@$Lt52_bem,"js_pesquisa_bem(true);",1);?>  </td>
        <td>
          <?
          db_input("t52_bem",8,$It52_bem,true,"text",4,"onchange='js_pesquisa_bem(false);'");
          db_input("t52_descr",40,$It52_descr,true,"text",3);
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?= $Tt52_depart?>"><? db_ancora(@$Lt52_depart, "js_pesquisa_depart(true);", 1)?></td>
        <td>
          <?php
          db_input("t52_depart", 8, $It52_depart, true, "text", 4, "onchange='js_pesquisa_depart(false);'");
          db_input("descrdepto", 40, $Idescrdepto, true, "text", 3);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="pesquisa" type="button" onclick='js_abre(this.name);'  value="Pesquisa">
</form>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>

  //--------------------------------
  function js_pesquisa_bem(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bens','func_bens.php?lConsultaBens=true&lObrigaConta=false&opcao=todos&funcao_js=parent.js_mostrabem1|t52_bem|t52_descr','Pesquisa de Bens',true);
    }else{
      if(document.form1.t52_bem.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bens','func_bens.php?lConsultaBens=true&opcao=todos&lObrigaConta=false&pesquisa_chave='+document.form1.t52_bem.value+'&funcao_js=parent.js_mostrabem','Pesquisa de Bens',false);
      }else{
        document.form1.t52_descr.value = '';
      }
    }
  }
  function js_mostrabem(chave,erro){
    document.form1.t52_descr.value = chave;
    if(erro==true){
      document.form1.t52_bem.focus();
      document.form1.t52_bem.value = '';
    }
  }
  function js_mostrabem1(chave1,chave2){
    document.form1.t52_bem.value = chave1;
    document.form1.t52_descr.value = chave2;
    db_iframe_bens.hide();
  }
  //--------------------------------
  function js_pesquisa_depart(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_depart','func_db_depart.php?funcao_js=parent.js_mostradepart1|coddepto|descrdepto','Pesquisa',true);
    }else{
      if(document.form1.t52_depart.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_depart','func_db_depart.php?pesquisa_chave='+document.form1.t52_depart.value+'&funcao_js=parent.js_mostradepart','Pesquisa',false);
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
  //--------------------------------
</script>
</body>
</html>
<script>

  $("t52_ident").addClassName("field-size4");
  $("t52_bem").addClassName("field-size2");
  $("t52_descr").addClassName("field-size7");
  $("t52_depart").addClassName("field-size2");
  $("descrdepto").addClassName("field-size7");

</script>

<script type="text/javascript">
  (function() {
    var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
    input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
  })();
</script>
