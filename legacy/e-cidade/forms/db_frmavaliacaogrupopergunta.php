<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: Habitacao
include(modification("dbforms/db_classesgenericas.php"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo                 = new rotulocampo;

$clavaliacao->rotulo->label();
$clavaliacaogrupopergunta->rotulo->label();

if (isset($oPost->db_opcaoal)) {

  $db_opcao = 33;
  $db_botao = false;
} else if (isset($oPost->opcao) && $oPost->opcao == "alterar") {

  $db_botao = true;
  $db_opcao = 2;
  echo "<script>parent.document.formaba.avaliacaopergunta.disabled=false;</script>";
  echo "<script>
          var sUrl = 'hab1_avaliacaopergunta001.php?db103_avaliacaogrupopergunta=".$db102_sequencial."';
          (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_avaliacaopergunta.location.href=sUrl;
        </script>";
} else if (isset($oPost->opcao) && $oPost->opcao == "excluir") {

  $db_opcao = 3;
  $db_botao = true;
  echo "<script>parent.document.formaba.avaliacaopergunta.disabled=true;</script>";
} else {

  $db_opcao = 1;
  $db_botao = true;
  if (isset($oPost->novo) || isset($oPost->excluir) && $sqlerro == false) {

    $db102_descricao      = "";
		$db102_identificador  = "";
    echo "<script>parent.document.formaba.avaliacaopergunta.disabled=true;</script>";
  }

  if (isset($oPost->incluir) && $sqlerro == false || isset($oPost->alterar) && $sqlerro == false) {

  	$db_opcao = 2;
    echo "<script>parent.document.formaba.avaliacaopergunta.disabled=false;</script>";
    echo "<script>
            var sUrl = 'hab1_avaliacaopergunta001.php?db103_avaliacaogrupopergunta=".$db102_sequencial."';
            (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_avaliacaopergunta.location.href=sUrl;
          </script>";
  }

}
?>
<form name="form1" method="post" action="">
<fieldset>
<legend><b>Grupo</b></legend>
<table border="0" align="left" width="100%">
  <tr>
    <td nowrap title="<?=@$Tdb102_avaliacao?>">
      <b>Código da Avaliação:</b>
    </td>
    <td width="10">
			<?
			  db_input('db102_sequencial',10,$Idb102_sequencial,true,'hidden',3,"");
			  db_input('db102_avaliacao',10,$Idb102_avaliacao,true,'text',3," onchange='js_pesquisadb102_avaliacao(false);'");
      ?>
    </td>
    <td>
      <?
        db_input('db101_descricao',50,$Idb101_descricao,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb102_descricao?>">
      <?=@$Ldb102_descricao?>
    </td>
    <td colspan="2">
			<?
			  db_input('db102_descricao',50,$Idb102_descricao,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb102_identificador?>">
      <?=@$Ldb102_identificador?>
    </td>
    <td colspan="2">
			<?
			  db_input('db102_identificador',65,$Idb102_identificador,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
</table>
</fieldset>
<table>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
             type="submit" id="db_opcao" onclick="return js_validaCaracteres();";
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
             <?=($db_botao==false?"disabled":"")?>  >
    </td>
    <td>
      <input name="novo"
             type="button" id="cancelar" value="Novo" onclick="js_cancelar();"
             <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
<table>
  <tr>
    <td valign="top"  align="center">
	    <?
	      $sWhere   = "db102_avaliacao = {$db102_avaliacao}";
			  $chavepri = array("db102_sequencial"=>@$db102_sequencial);
			  $cliframe_alterar_excluir->chavepri=$chavepri;
			  $cliframe_alterar_excluir->sql     = $clavaliacaogrupopergunta->sql_query_file(null, "*", "db102_sequencial", $sWhere);
			  $cliframe_alterar_excluir->campos  ="db102_sequencial,db102_avaliacao,db102_descricao, db102_identificador";
			  $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
			  $cliframe_alterar_excluir->iframe_height ="160";
			  $cliframe_alterar_excluir->iframe_width ="600";
			  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
	    ?>
    </td>
  </tr>
</table>
</form>
<script>
$('db101_descricao').style.width = '100%';
$('db102_descricao').style.width  = '100%';

function js_validar(){

  var sOpcao = $('db_opcao').value;
  if (sOpcao == 'Excluir') {

    if (!confirm('Excluir todas as perguntas para esse grupo?')) {
      return false;
    }
  }
  return js_validaCaracteres();
}

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

/**
 * Validamos os caracteres do identificador registrado
 * Primeiramente verificamos o caracter inicial, permitindo apenas letras
 * Em seguida, verificamos o que vem a seguir, permitindo letras, numeros e _
 */
function js_validaCaracteres() {

  var sValorInicial     = $F('db102_identificador').substring(0,1);
  var sExpressaoInicial = /[A-Za-z]/;
  var sRegExpInicial    = new RegExp(sExpressaoInicial);
  var lResultadoInicial = sRegExpInicial.test(sValorInicial);

  if (sValorInicial == '') {

    alert('É necessário informar um identificador');
    $('db102_identificador').focus();
    return false;
  }

  if (lResultadoInicial) {

    var sValorCaracteres      = $F('db102_identificador').substring(1);
    var sExpressaoCaracteres  = /^[A-Za-z0-9_]+?$/i;
    var sRegExpCaracteres     = new RegExp(sExpressaoCaracteres);
    var lResultadoCaracteres  = sRegExpCaracteres.test(sValorCaracteres);
    if (!lResultadoCaracteres) {

      alert('Não são permitidos caracteres especiais e espaços');
      return false;
    }
  } else {

    alert('Não são permitidos caracteres especiais e espaços');
    return false;
  }
  return true;
}
</script>
