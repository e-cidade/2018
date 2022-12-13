<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_pcparam_classe.php");
$clpcparam = new cl_pcparam;
$clrotulo = new rotulocampo;
$clrotulo->label("pc10_numero");
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_abre() {

   obj = document.form1;
   query='';
   query += "&ini="+obj.pc10_numerode.value;
   query += "&fim="+obj.pc10_numeroate.value;
   query += "&departamento=<?=db_getsession("DB_coddepto")?>";
   query += "&valor_orcado="+$F('valor_orcado');
   <?
   $result_emissao = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_tipoemiss"));
   if($clpcparam->numrows>0){
     db_fieldsmemory($result_emissao,0);
   }else{
     echo "alert('Usuário:\\n\\nParâmetros do módulo compras não configurados.\\n\\nAdministrador:');";
   }
   if(isset($pc30_tipoemiss) && trim($pc30_tipoemiss)!="") {

     if ($pc30_tipoemiss=="t") {
       echo "jan = window.open('com2_emitesolicita002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');";
     } else {
       echo "jan = window.open('com2_emitesolicita003.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');";
     }
     echo "jan.moveTo(0,0);";
   }
   ?>
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="document.form1.pc10_numerode.focus();" >
<center>
  <form name='form1' style="margin-top: 25px;">
    <fieldset style='width: 350px;'>
      <legend><b>Reemissão de Solicitação de Compra</b></legend>
      <table>
        <tr>
           <td  align="left" nowrap title="<?=$Tpc10_numero?>"> <b>
            <? db_ancora("Solicitações de : ","js_solicitade(true);",1);?>
          </td>
          <td align="left" nowrap>
            <?
               db_input("pc10_numerode",10,$Ipc10_numero,true,"text",4,"onchange='js_solicitade(false); js_copiacampo();'");
            ?>
          </td>
          <td  align="left" nowrap title="<?=$Tpc10_numero?>">
            <? db_ancora("<b>Até:</b> ","js_solicitaate(true);",1);?>
          </td>
          <td align="left" nowrap>
            <?
               db_input("pc10_numeroate",10,$Ipc10_numero,true,"text",4,"onchange='js_solicitaate(false);'");
            ?>
          </td>

        </tr>
        <tr>
          <td><b>Imprimir valor orçado:</b></td>
          <td colspan='3' >
            <?php
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('valor_orcado', $x, true, $db_opcao, "");
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name='pesquisar' type='button' value='Gerar relatório' onclick='js_abre();'>
  </form>
</center>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script>
function js_copiacampo() {

  if ($F("pc10_numerode") != "") {
    $("pc10_numeroate").value = $F("pc10_numerode");
  }
  document.form1.pc10_numeroate.focus();
}


function js_solicitade(mostra) {

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_solicita',
                        'func_solicitamanutencaoreserva.php?funcao_js=parent.js_mostrasolicitade1|'+
                        'pc10_numero&param_depart=<?=db_getsession("DB_coddepto")?>&anuladas=1','Pesquisa',true);
  }else{
     if(document.form1.pc10_numerode.value != ''){
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_solicita',
                            'func_solicitamanutencaoreserva.php?pesquisa_chave='+document.form1.pc10_numerode.value+
                            '&funcao_js=parent.js_mostrasolicitade&param_depart=<?=db_getsession("DB_coddepto")?>&anuladas=1',
                            'Pesquisa',false);
     }else{
       document.form1.pc10_numerode.value = '';
     }
  }
}

function js_mostrasolicitade(chave,erro) {
  if(erro==true){
    document.form1.pc10_numerode.focus();
    document.form1.pc10_numerode.value = '';
  }
}

function js_mostrasolicitade1(chave1,x) {

  document.form1.pc10_numerode.value  = chave1;
  document.form1.pc10_numeroate.value = chave1;
  db_iframe_solicita.hide();
}

// solicitacao ATE
function js_solicitaate(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_solicitaate',
                        'func_solicita.php?funcao_js=parent.js_mostrasolicitaate1|'+
                        'pc10_numero&param_depart=<?=db_getsession("DB_coddepto")?>',
                        'Pesquisa',true);
  } else {

     if (document.form1.pc10_numeroate.value != '') {

        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_solicitaate',
                            'func_solicita.php?pesquisa_chave='+
                            document.form1.pc10_numeroate.value+
                            '&funcao_js=parent.js_mostrasolicitaate&param_depart=<?=db_getsession("DB_coddepto")?>',
                            'Pesquisa',
                            false);
     }else{
       document.form1.pc10_numeroate.value = '';
     }
  }
}

function js_mostrasolicitaate(chave,erro){
  if(erro==true){
    document.form1.pc10_numeroate.focus();
    document.form1.pc10_numeroate.value = '';
  }
}

function js_mostrasolicitaate1(chave1,x){
  document.form1.pc10_numeroate.value = chave1;
  db_iframe_solicitaate.hide();
}
</script>