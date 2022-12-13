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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("y60_codlev");
$clrotulo->label("z01_nome");
$db_opcao = 1;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default" onload="if(document.form1) document.form1.elements[0].focus()" >
  <div class="container">
   <form name="form1" method="post" action="">
    <fieldset>
      <legend>Levantamento</legend>
        <table>
         <tr>
          <td nowrap title="<?=@$Ty60_codlev?>">
          <?php
            db_ancora(@$Ly60_codlev,"js_lev(true);",$db_opcao);
          ?>
          </td>
          <td>
          <?php
           db_input('y60_codlev',6,$Iy60_codlev,true,'text',$db_opcao," onchange='js_lev(false);'");
           db_input('z01_nome',40,$Iz01_nome,true,'text',3);
          ?>
          </td>
         </tr>
        </table>
    </fieldset>
    <input name="importar" type="button"  onClick="return js_relatorio();" value="Processar">
   </form>
  </div>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">
  function js_relatorio() {

    jan = window.open('fis2_levantamento002.php?codlev='+document.form1.y60_codlev.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }

  function js_lev(mostra){

    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe','func_levanta02.php?funcao_js=parent.js_mostralev1|y60_codlev|DBtxtnome_origem','Pesquisa',true);
    }else{

      lev = document.form1.y60_codlev.value;
      if(lev != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe','func_levanta02.php?pesquisa_chave='+lev+'&funcao_js=parent.js_mostralev','Pesquisa',false);
      }else{
        document.form1.z01_nome.value='';
      }
    }

    document.form1.k00_tipo.value='';
    document.form1.descrTipo.value='';
  }

  function js_mostralev(chave,erro){

    if(erro==true){

      alert('Levantamento inválido.');
      document.form1.y60_codlev.value = "";
      document.form1.z01_nome.value   = ""
      document.form1.y60_codlev.focus();
    } else{
      document.form1.z01_nome.value = chave;
    }
  }

  function js_mostralev1(chave1,chave2){

    document.form1.y60_codlev.value = chave1;
    document.form1.z01_nome.value   = chave2;
    db_iframe.hide();
  }
</script>