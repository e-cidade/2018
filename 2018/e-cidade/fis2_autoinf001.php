<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("classes/db_auto_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);

$cldbauto = new cl_auto;
$cldbauto->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y50_codauto");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form name="form1" method="post" action="">
      <fieldset>
        <legend>Auto de Infração</legend>
        <table>
         <tr>
           <td nowrap title="<?=@$y50_codauto?>"><?db_ancora(@$Ly50_codauto,"js_codauto(true);",1);?></td>
           <td>
            <?php
              db_input('y50_codauto',6,$Iy50_codauto,true,'text',1," onchange='js_codauto(false);'");
              db_input('y50_nome',35,$Iy50_nome,true,'text',3,'');
            ?>
           </td>
         </tr>
        </table>
      </fieldset>
      <input name="consultar" type="submit" value="Processar" onclick="js_enviaDados();" />
    </form>
  </div>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

function js_enviaDados(){

  if(document.form1.y50_codauto.value == ""){

    alert("Preencha o código do Auto");
    document.form1.y50_codauto.focus();
  }else{

   jan = window.open('fis2_autoinf002.php?codauto='+document.form1.y50_codauto.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);
  }
}
function js_mostracodauto1(chave1,chave2){

  document.form1.y50_codauto.value = chave1;
  document.form1.y50_nome.value    = chave2;
  db_iframe_auto.hide();
}
function js_mostracodauto(chave,erro){

  document.form1.y50_nome.value = chave;
  if(erro==true){
    document.form1.y50_codauto.focus();
    document.form1.y50_codauto.value = '';
  }
}
function js_codauto(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_auto','func_autoalt.php?funcao_js=parent.js_mostracodauto1|dl_auto|z01_nome','Pesquisa',true);
  }else{

    y50_codauto = document.form1.y50_codauto.value;
    if(y50_codauto!=""){
      js_OpenJanelaIframe('top.corpo','db_iframe_auto','func_autoalt.php?pesquisa_chave='+y50_codauto+'&funcao_js=parent.js_mostracodauto','Pesquisa',false);
    }else{
      document.form1.y50_nome.value='';
    }
  }
}
</script>