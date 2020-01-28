<?php
/*
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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_iptuisen_classe.php");
require_once("classes/db_iptubase_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$db_botao  = 1;
$db_opcao  = 1;
$db_opcaom = 1;
$db_opcaon = 3;

$cliptuisen = new cl_iptuisen;
$cliptuisen->rotulo->label();
$clrotulo   = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
function js_checa(){

  if(document.form1.j46_matric.value==""){

    alert("Informe a matrícula!");
    return false;
  }
  return true;
}
</script>
</head>
<body class="body-default" onload="document.form1.j46_matric.focus();">
  <div class="container">
    <form name="form1" method="post" action="cad4_iptuisen002.php">
      <fieldset style="width:520px;">
        <legend>Isenção</legend>

          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="80px;" title="<?=@$Tj46_matric?>">
                <?
                db_ancora(@$Lj46_matric,"js_pesquisaj46_matric(true);",$db_opcao);
                ?>
              </td>
              <td>
                <?
                db_input('j46_matric',10,$Ij46_matric,true,'text',$db_opcaom," onchange='js_pesquisaj46_matric(false);'");
                db_input('z01_nome',45,$Iz01_nome,true,'text',3,"","z01_nomematri");
                ?>
              </td>
          </table>
      </fieldset>
      <input name="entrar" type="submit" id="entrar" value="Pesquisar" onclick=" return js_checa()" />
    </form>
  </div>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">
function js_pesquisaj46_matric(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe','func_iptubase.php?funcao_js=parent.js_mostraiptubase1|j01_matric|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe','func_iptubase.php?pesquisa_chave='+document.form1.j46_matric.value+'&funcao_js=parent.js_mostraiptubase','Pesquisa',false);
  }
}
function js_mostraiptubase(chave,erro){

  document.form1.z01_nomematri.value = chave;
  if(erro==true){

    document.form1.j46_matric.focus();
    document.form1.j46_matric.value = '';
  }
}
function js_mostraiptubase1(chave1,chave2){

  document.form1.j46_matric.value = chave1;
  document.form1.z01_nomematri.value = chave2;
  db_iframe.hide();
}
</script>
<?php

$func_iframe                 = new janela('db_iframe','');
$func_iframe->posX           = 1;
$func_iframe->posY           = 20;
$func_iframe->largura        = 780;
$func_iframe->altura         = 430;
$func_iframe->titulo         = 'Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

if(isset($invalido)){
  echo "<script>alert('Número de matrícula inválido!')</script>";

}
if(isset($excluir)){

  if($cliptuisen->erro_status=="0"){

    $cliptuisen->erro(true,false);
    if($cliptuisen->erro_campo!=""){

      echo "<script> document.form1.".$cliptuisen->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cliptuisen->erro_campo.".focus();</script>";
    }
  }else{

    $cliptuisen->erro(true,false);
    db_redireciona("cad4_iptuisen001.php");
  }
}
if(isset($atualizar)){

  if($cliptuisen->erro_status=="0"){

    $cliptuisen->erro(true,false);
    if($cliptuisen->erro_campo!=""){

      echo "<script> document.form1.".$cliptuisen->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cliptuisen->erro_campo.".focus();</script>";
    }
  }else{

    $cliptuisen->erro(true,false);
    db_redireciona("cad4_iptuisen001.php");
  }
}
?>