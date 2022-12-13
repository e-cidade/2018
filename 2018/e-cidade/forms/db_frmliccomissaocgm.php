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

require_once(modification("dbforms/db_classesgenericas.php"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clliccomissaocgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("l30_codigo");
$clrotulo->label("z01_nome");

if(isset($db_opcaoal)){
  $db_opcao = 33;
  $db_botao = false;
}else if(isset($opcao) && $opcao == "alterar"){

  $db_botao = true;
  $db_opcao = 2;
}else if(isset($opcao) && $opcao == "excluir"){

  $db_opcao = 3;
  $db_botao = true;
}else{

  $db_opcao = 1;
  $db_botao = true;
  if(isset($novo) || isset($alterar) || isset($excluir) || (isset($incluir) && $sqlerro == false ) ){

    if (isset($novo)){
      $l31_codigo = "";
    }
      $l31_numcgm = "";
  }
}
?>
<form name="form1" method="post" action="">
  <fieldset>
    <legend>Informações do Participante</legend>

    <table id="tabela-form">

      <tr>
        <td title="<?= $Tl31_liccomissao ?>">
          <b>Comissão:</b>
        </td>
        <td>
          <?php
            db_input('l31_liccomissao',10, $Il31_liccomissao, true, 'text', 3, " onchange='js_pesquisal31_liccomissao(false);'");
            db_input('l31_codigo', 10, $Il31_codigo, true, 'hidden', 3, "");
          ?>
        </td>
      </tr>

      <tr>
        <td title="<?= $Tl31_numcgm ?>">
          <?php
          $Ll31_numcgm = "Membro:";
          db_ancora($Ll31_numcgm, "js_pesquisal31_numcgm(true);", $db_opcao);
          ?>
        </td>
        <td>
          <?php db_input('l31_numcgm', 10, $Il31_numcgm, true, 'text', $db_opcao, " onchange='js_pesquisal31_numcgm(false);'") ?>
          <?php db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3 ,'') ?>
        </td>
      </tr>

      <tr>
        <td title="<?= $Tl31_tipo ?>">
          <?= $Ll31_tipo ?>
        </td>
        <td>
          <?php
            $sSql = $clliccomissaocgm->sql_query(null,"*",null,"l31_liccomissao=$l31_liccomissao and l31_tipo='P'");
            $result_pres = $clliccomissaocgm->sql_record($sSql);

            if ($result_pres && $clliccomissaocgm->numrows > 0) {
              $aTipo = array('M'=>'Membro','P'=>'Presidente');
            }else{
              $aTipo = array('P'=>'Presidente','M'=>'Membro');
            }

            $aTipo['2'] = 'Pregoeiro';
            $aTipo['3'] = 'Suplente';
            $aTipo['A'] = 'Equipe de Apoio';
            $aTipo['D'] = 'Servidor Designado';
            $aTipo['L'] = 'Leiloeiro';
            $aTipo['S'] = 'Secretário';

            db_select('l31_tipo', $aTipo, true, $db_opcao, "");
          ?>
        </td>
      </tr>

    </table>

  </fieldset>

  <?php
    $sName     = ($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"));
    $sValue    = ($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"));
    $sDisabled = ($db_botao == false ? "disabled" : "");
  ?>
  <input name="<?= $sName ?>" type="submit" id="db_opcao" value="<?= $sValue ?>" <?= $sDisabled ?>  >
  <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >

  <table>
    <tr>
      <td valign="top"  align="center">
        <?php
        $chavepri = array(
          "l31_codigo"      => $l31_codigo,
          "l31_liccomissao" => $l31_liccomissao,
        );
        $cliframe_alterar_excluir->chavepri      = $chavepri;
        $cliframe_alterar_excluir->sql           = $clliccomissaocgm->sql_query(null, "*", null, "l31_liccomissao = $l31_liccomissao");
        $cliframe_alterar_excluir->campos        = "l31_codigo, l31_numcgm, z01_nome, l31_tipo";
        $cliframe_alterar_excluir->legenda       = "ITENS LANÇADOS";
        $cliframe_alterar_excluir->iframe_height = "160";
        $cliframe_alterar_excluir->iframe_width  = "700";
        $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
        ?>
      </td>
    </tr>
  </table>

  <input type="hidden" id="grupo_valor_atributos" name="grupo_valor_atributos" value="<?= $iCodigoGrupo ?>">
</form>

<script>
var lRedireciona = false;

document.observe('dom:loaded', function () {

  if ($('db_opcao').getValue() !== 'Excluir' && !lRedireciona) {
    carregarAtributosDinamicos();
  }
});

function renderizarAtributosDinamicos() {

  var oCamposCollection = this.campos();
  var oTable = $('tabela-form');

  for (var aNomeCampos in oCamposCollection) {

    var oLinha = document.createElement('tr');
    for (var oCampo of oCamposCollection[aNomeCampos]) {

      if (oCampo) {

        var oColuna = document.createElement('td');
        oColuna.appendChild(oCampo);
        oLinha.appendChild(oColuna);
      }
    }

    oTable.appendChild(oLinha);
  }

  var iGrupoValorAtributos = $('grupo_valor_atributos').getValue();
  if (!empty(iGrupoValorAtributos)) {
    this.carregarValores(iGrupoValorAtributos);
  }
}

function carregarAtributosDinamicos() {

  var oAtributosDinamico = new DBAtributoDinamico();
  oAtributosDinamico.carregarAtributos(1325, renderizarAtributosDinamicos);
}

function js_cancelar(){

  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

function js_pesquisal31_liccomissao(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_liccomissaocgm','db_iframe_liccomissao','func_liccomissao.php?funcao_js=parent.js_mostraliccomissao1|l30_codigo|l30_codigo','Pesquisa',true,'0','1','775','390');
  }else{

     if(document.form1.l31_liccomissao.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_liccomissaocgm','db_iframe_liccomissao','func_liccomissao.php?pesquisa_chave='+document.form1.l31_liccomissao.value+'&funcao_js=parent.js_mostraliccomissao','Pesquisa',false);
     }else{
       document.form1.l30_codigo.value = '';
     }
  }
}

function js_mostraliccomissao(chave,erro){

  document.form1.l30_codigo.value = chave;
  if(erro==true){
    document.form1.l31_liccomissao.focus();
    document.form1.l31_liccomissao.value = '';
  }
}

function js_mostraliccomissao1(chave1,chave2){

  document.form1.l31_liccomissao.value = chave1;
  document.form1.l30_codigo.value = chave2;
  db_iframe_liccomissao.hide();
}

function js_pesquisal31_numcgm(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_liccomissaocgm','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true,'0','1','775','390');
  }else{

     if(document.form1.l31_numcgm.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_liccomissaocgm','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.l31_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}

function js_mostracgm(erro,chave){

  document.form1.z01_nome.value = chave;
  if(erro==true){

    document.form1.l31_numcgm.focus();
    document.form1.l31_numcgm.value = '';
  }
}

function js_mostracgm1(chave1,chave2){

  document.form1.l31_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
</script>
