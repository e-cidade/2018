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

$clavaliacaoperguntaopcao->rotulo->label();
$clavaliacaopergunta->rotulo->label();

if (isset($oPost->db_opcaoal)) {

  $db_opcao = 33;
  $db_botao = false;
} else if (isset($oPost->opcao) && $oPost->opcao == "alterar") {

  $db_botao = true;
  $db_opcao = 2;
} else if(isset($oPost->opcao) && $oPost->opcao == "excluir") {

  $db_opcao = 3;
  $db_botao = true;
} else {

  $db_opcao = 1;
  $db_botao = true;

  if (isset($oPost->novo) || isset($oPost->incluir) || isset($oPost->alterar) || isset($oPost->excluir) ) {

    $db104_sequencial    = "";
    $db104_descricao     = "";
    $db104_aceitatexto   = "";
    $db104_identificador = "";
  }
}
?>
<form name="form1" method="post" action="">
<fieldset><legend><b>Respostas</b></legend>
<table border="0" align="left" width="602">
  <tr>
    <td nowrap title="<?=@$Tdb104_avaliacaopergunta?>">
      <b>Código da Pergunta:</b>
    </td>
    <td colspan="3">
      <?
        db_input('db104_sequencial',10,$Idb104_sequencial,true,'hidden',3,"");
        db_input('db103_sequencial',10,$Idb103_sequencial,true,'text',3,"");
        db_input('db103_descricao',40,@$Idb103_descricao,true,'text',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb104_descricao?>">
      <?=@$Ldb104_descricao?>
    </td>
    <td colspan="4">
      <?
        db_input('db104_descricao',255,$Idb104_descricao,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb104_identificador?>">
      <?=@$Ldb104_identificador?>
    </td>
    <td colspan="4">
      <?
        db_input('db104_identificador',65,$Idb104_identificador,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb104_peso?>">
      <?=@$Ldb104_peso?>
    </td>
    <td colspan="4">
      <?
        db_input('db104_peso',10,$Idb104_peso,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Valor da Resposta">
      <b>Valor da Resposta:</b>
    </td>
    <td colspan="4">
      <?
      db_input('db104_valorresposta',10,"0",true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb104_aceitatexto?>">
      <?=@$Ldb104_aceitatexto?>
    </td>
    <td>
      <?
        $aAceitaTexto = array("f"=>"NAO","t"=>"SIM");
        db_select('db104_aceitatexto',$aAceitaTexto,true,$db_opcao,"");
      ?>
    </td>
  </tr>
</table>
</fieldset>
<table border="0" width="100%">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr align="center">
    <td>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
             type="submit" id="db_opcao" onclick="return js_validaCaracteres();"
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
             <?=($db_botao==false?"disabled":"")?>>

      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();"
             <?=($db_opcao==1||isset($oPost->db_opcaoal)?"style='visibility:hidden;'":"")?>>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table>
  <tr>
    <td valign="top" align="center">
      <?
        $sWhere    = "db104_avaliacaopergunta = {$db103_sequencial}";
        $sCampos   = "db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador";
        $sCampos  .= ", db104_aceitatexto, db104_peso";
        $chavepri  = array("db104_sequencial"=>@$db104_sequencial);
        $cliframe_alterar_excluir->chavepri      = $chavepri;
        $cliframe_alterar_excluir->sql           = $clavaliacaoperguntaopcao ->sql_query_file(null,'avaliacaoperguntaopcao.*','db104_sequencial',$sWhere);
        $cliframe_alterar_excluir->campos        = $sCampos;
        $cliframe_alterar_excluir->legenda       = "ITENS LANÇADOS";
        $cliframe_alterar_excluir->iframe_height = "160";
        $cliframe_alterar_excluir->iframe_width  = "600";
        $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
      ?>
    </td>
  </tr>
</table>
</form>
<script>
$('db103_descricao').style.width   = '80%';
$('db104_descricao').style.width   = '100%';

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

  var sValorInicial     = $F('db104_identificador').substring(0,1);
  var sExpressaoInicial = /[A-Za-z]/;
  var sRegExpInicial    = new RegExp(sExpressaoInicial);
  var lResultadoInicial = sRegExpInicial.test(sValorInicial);

  if (sValorInicial == '') {

    alert('É necessário informar um identificador');
    $('db104_identificador').focus();
    return false;
  }

  if (lResultadoInicial) {

    var sValorCaracteres      = $F('db104_identificador').substring(1);
    var sExpressaoCaracteres  = /^[A-Za-z0-9_]+?$/i;
    var sRegExpCaracteres     = new RegExp(sExpressaoCaracteres);
    var lResultadoCaracteres  = sRegExpCaracteres.test(sValorCaracteres);
    if (!lResultadoCaracteres) {

      alert('São permitidas apenas letras, números e/ou caracter "_" (underline)');
      return false;
    }
  } else {

    alert('É permitido apenas letra no caracter inicial');
    return false;
  }
}
</script>
