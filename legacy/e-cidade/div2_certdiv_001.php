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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));

$clrotulo = new rotulocampo;
$clrotulo->label("v14_certid");
$clrotulo->label("DBtxt14");
$clrotulo->label("DBtxt15");
$clrotulo->label("DBtxt10");
$clrotulo->label("DBtxt11");
$clrotulo->label("DBtxt16");

$oGet = db_utils::postMemory($_GET);

if (isset($oGet->iCdaDividaIni) && isset($oGet->iCdaDividaFim)) {

  $v14_certid  = $oGet->iCdaDividaIni;
  $v14_certid1 = $oGet->iCdaDividaFim;
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" content="0">
<script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="javascript" type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript">

function js_AbreJanelaRelatorio() {

  var ordenarpor = '';

  var eDBtxt16 = document.getElementById("DBtxt16");
  ordenarpor = eDBtxt16.options[eDBtxt16.selectedIndex].value;

  if (js_verifica() == true) {

    if (document.form1.v14_certid.value != '') {

  	  datacertidao = '';

      if (document.form1.DBtxt15_ano.value != '') {
    	  datacertidao = document.form1.DBtxt15_ano.value+'/'+document.form1.DBtxt15_mes.value+'/'+document.form1.DBtxt15_dia.value;
    	}

      jan = window.open('div2_certidaodivida002.php?tipo=2&certid='+document.form1.v14_certid.value+'&certid1='+document.form1.v14_certid1.value+'&reemissao='+document.form1.DBtxt14.value+'&valormaximo='+document.form1.DBtxt11.value+'&valorminimo='+document.form1.DBtxt10.value+'&datacertidao='+datacertidao+'&ordenarpor='+ordenarpor+'&totexe='+document.form1.totexe.value+'&endaimp='+document.form1.endaimp.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
      jan.moveTo(0, 0);
    } else {
  	  alert('Campo Certidão é de preenchimento obrigatório.');
    }
  }
}

</script>
<link href="estilos.css" rel="stylesheet" type="text/css"/>
<style type="text/css">

#DBtxt10, #DBtxt11, #DBtxt16 {
  width: 83px;
}
#DBtxt16 {
  font-size:14px;
}


</style>
</head>
<body class="body-default" onLoad="a=1" >
<form class="container" name="form1" method="post" action="div2_certparc_002.php" onsubmit="return js_verifica()">
	<fieldset>
		<legend>Certidão de Dívida Ativa</legend>
			<table class="form-container">

        <tr>
          <td title="<?php echo $Tv14_certid; ?>">
            <?php
              db_ancora($Lv14_certid, "js_pesquisaparcel(true)", 1);
            ?>
          </td>
          <td>
            <?php

              $Sv14_certid = "Certidão";

              db_input("v14_certid", 10, 1, true, "text", 1, "onchange='js_pesquisaparcel(false);document.form1.v14_certid1.value=this.value'");

              db_ancora("<strong>à</strong>", "js_pesquisaparcel1(true)", 1);

              db_input("v14_certid", 10, 1, true, "text", 1, "onchange='js_pesquisaparcel1(false);'", "v14_certid1");
            ?>
          </td>
        </tr>

        <tr>
          <td>
            <label for="DBtxt10">Valores:</label>
          </td>
          <td>
            <?php

              $SDBtxt10 = "Valor";
              $SDBtxt11 = "Valor";

              db_input("DBtxt10", 15, 4, true, "text", 1);
            ?>
            <strong>à</strong>
            <?php
              db_input("DBtxt11", 15, 4, true, "text", 1);
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="Informe a data de Emissão da certidão">
            <label for="DBtxt15">Data de Emissão:</label>
          </td>
          <td>
            <?php db_inputdata("DBtxt15", "", "", "", true, "text", 4) ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $TDBtxt14;?>">
              <label for="DBtxt14"><?php echo $LDBtxt14;?></label>
            </td>
          <td>
            <?php
              $aReemissao = array("t" => "Sim", "f" => "Não");
              db_select("DBtxt14", $aReemissao, true, 4, "");
            ?>
          </td>
        </tr>

        <tr>
          <td><label for="totexe">Totaliza por exercício:</label></td>
          <td>
            <?php
              $aTotaliza = array("f" => "Não", "t" => "Sim");
              db_select("totexe", $aTotaliza, true, 4, "");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="Escolha a Ordem"><label for="DBtxt16">Ordenar por:</label></td>
          <td>
            <?php
              $aOrder = array("v14_certid" => "Certidão", "z01_nome" => "Nome");
              db_select("DBtxt16", $aOrder, true, 4, "");
            ?>
          </td>
        </tr>

        <tr>
        <td nowrap title=""><label for="endaimp">Endereço a imprimir:</label></td>
        <td>
          <?php
            $r = db_query("select v04_ordemendcda from pardiv");
            db_fieldsmemory($r, 0);

            $endaimp = "o";
            if ($v04_ordemendcda == 2) {
              $endaimp = "c";
            }

            $aImprime = array("o" => "Origem", "c" => "CGM");
            db_select("endaimp", $aImprime, true, 4, "");
          ?>
        </td>
        </tr>
      </table>
  </fieldset>
  <input name="exibir_relatorio" type="button" id="exibir_relatorio" value="Processar" onClick="js_AbreJanelaRelatorio()"/>
</form>

<?php
  if (!isset($oGet->iCdaDividaIni) && !isset($oGet->iCdaDividaFim)) {
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  }
?>
</body>
</html>
<script type="text/javascript">
function js_pesquisaparcel(mostra){

     if(mostra == true){
       js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe','func_certdiv.php?funcao_js=parent.js_mostratermo1|0','Pesquisa',true);
     }else{
       js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe','func_certdiv.php?pesquisa_chave='+document.form1.v14_certid.value+'&funcao_js=parent.js_mostratermo','Pesquisa',false);
     }
}
function js_mostratermo(chave,erro){

  if(erro==true){

     document.form1.v14_certid.focus();
     document.form1.v14_certid.value = '';
     document.form1.v14_certid1.value = '';
  }
}
function js_mostratermo1(chave1){

     document.form1.v14_certid.value = chave1;
     db_iframe.hide();
}
function js_pesquisaparcel1(mostra){

     if(mostra==true){
       js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe','func_certdiv.php?funcao_js=parent.js_mostratermo11|0','Pesquisa',true);
     }else{
       js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe','func_certdiv.php?pesquisa_chave='+document.form1.v14_certid1.value+'&funcao_js=parent.js_mostratermo2','Pesquisa',false);
     }
}
function js_mostratermo2(chave,erro){

  if(erro==true){
     document.form1.v14_certid1.focus();
     document.form1.v14_certid1.value = '';
  }
}
function js_mostratermo11(chave1){

     document.form1.v14_certid1.value = chave1;
     db_iframe.hide();
}
function js_verifica(){

  var val1 = new Number(document.form1.DBtxt10.value);
  var val2 = new Number(document.form1.DBtxt11.value);
  if(val1.valueOf() >= val2.valueOf()){
     alert('Valor máximo menor que o valor mínimo.');
     return false;
  }
  return true;
}

$("v14_certid").addClassName("field-size2");
$("v14_certid1").addClassName("field-size2");
$("DBtxt10").addClassName("field-size3");
$("DBtxt11").addClassName("field-size3");
$("DBtxt15").addClassName("field-size2");
$("DBtxt14").setAttribute("rel","ignore-css");
$("DBtxt14").addClassName("field-size2");
$("totexe").setAttribute("rel","ignore-css");
$("totexe").addClassName("field-size2");
$("endaimp").setAttribute("rel","ignore-css");
$("endaimp").addClassName("field-size2");

</script>