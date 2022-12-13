<?php
/**
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");

include_once modification("libs/db_sessoes.php");
include_once modification("libs/db_usuariosonline.php");
include_once modification("dbforms/db_funcoes.php");
include_once modification("dbforms/db_classesgenericas.php");
include_once modification("classes/db_gerfcom_classe.php");

$aux       = new cl_arquivo_auxiliar;
$clgerfcom = new cl_gerfcom;
$clrotulo  = new rotulocampo;
$gform     = new cl_formulario_rel_pes;

$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
$clrotulo->label('r48_semest');
$clrotulo->label('rh56_localtrab');
$clrotulo->label('rh55_descr');

if (!isset($xano) || (isset($xano) && (trim($xano) == "" || $xano == 0))) {
  $xano = db_anofolha();
}
$Sxano = "Ano";

if (!isset($xmes) || (isset($xmes) && trim($xmes) == "" || $xmes == 0)) {
  $xmes = db_mesfolha();
}
$Sxmes = "Mês";

db_postmemory($HTTP_POST_VARS);

$sErroMsg     = '';
$oCompetencia = null;
try {
  $oCompetencia = new DBCompetencia($xano, $xmes);
} catch (Exception $e) {
  try {
    $oCompetencia = DBPessoal::getCompetenciaFolha();
    $sErroMsg     = "\nUtilizado competência atual (".$oCompetencia->getAno().'/'.$oCompetencia->getMes().")";
  } catch (Exception $e) {
    $sErroMsg     = $e->getMessage();
  }
}

$aSalarioFechada      = array();
$aSuplementarFechada  = array();
$aComplementarFechada = array();

if($oCompetencia instanceof DBCompetencia) {
  $aSalarioFechada      = FolhaPagamentoSalario::getFolhasFechadasCompetencia($oCompetencia);
  $aSuplementarFechada  = FolhaPagamentoSuplementar::getFolhasFechadasCompetencia($oCompetencia);
  $aComplementarFechada = FolhaPagamentoComplementar::getFolhasFechadasCompetencia($oCompetencia);
}

try {
  $result_semest = db_query($clgerfcom->sql_query_file(null,null,null,null,"distinct r48_semest",null, " r48_anousu = $xano and r48_mesusu = $xmes and r48_instit = ".db_getsession('DB_instit')));

  if(!$result_semest) {
    throw new DBException("Ocorreu um erro ao consultar a folha de complementar");
  }

  $clgerfcom->numrows = pg_num_rows($result_semest);

} catch (Exception $e) {
  $sErroMsg     = $e->getMessage();
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script>
function js_filtra(){
  document.form1.submit();
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc">

  <form name="form1" class="container" method="post" action="">
  <fieldset>
    <legend>Contra-Cheques (Laser)</legend>
    <table align="center" border="0" class="form-container">
      <tr>
        <td align="right" nowrap title="Digite o Ano / Mês de competência">
          <strong>Ano / Mês :</strong>
        </td>
        <td>
          <?php db_input('xano', 4, 1, true, 'text', 2, 'onchange="js_anomes();"'); ?> /
          <?php db_input('xmes', 2, 1, true, 'text', 2, 'onchange="js_anomes();"'); ?>
        </td>
      </tr>
      <tr >
        <td align="right" nowrap title="Digite o Ano / Mes de competência" >
          <?php
          $gform->selecao = true;
          $gform->desabam = false;
          $gform->manomes = false;
          $gform->gera_form(db_anofolha(),db_mesfolha());
          ?>
        </td>
      </tr>
      <tr>
        <td align="right" >
          <strong>Tipo de Folha :</strong>
        </td>
        <td>
          <select id="tipo_folha" name="folha" onchange="js_tipofolha();">
            <?php if ($oCompetencia && DBPessoal::verificarUtilizacaoEstruturaSuplementar()) { ?>

              <?php if (!empty($aSalarioFechada)) { ?>
                <option value="salario" <?= isset($folha) && $folha == 'salario' ? 'selected': '' ?>>
                  Salário
                </option>
              <?php } ?>

              <?php if (!empty($aSuplementarFechada)) { ?>
                <option value="suplementar" <?= isset($folha) && $folha == 'suplementar' ? 'selected' : '' ?>>
                  Suplementar
                </option>
              <?php } ?>

              <?php if (!empty($aComplementarFechada)) { ?>
                <option value="complementar" <?= isset($folha) && $folha == 'complementar' ? 'selected' : '' ?>>
                  Complementar
                </option>
              <?php } ?>
            <?php } else { ?>

              <option value='salario' <?= isset($folha) && $folha == 'salario' ? 'selected': '' ?>>
                Salário
              </option>
              <option value='complementar' <?= isset($folha) && $folha == 'complementar' ? 'selected': '' ?>>
                Complementar
              </option>
            <?php } ?>

            <option value='rescisao' <?= isset($folha) && $folha == 'rescisao' ? 'selected': '' ?>>
              Rescisão
            </option>
            <option value='13salario' <?= isset($folha) && $folha == '13salario' ? 'selected': '' ?>>
              13o. Salário
            </option>
            <option value='adiantamento' <?= isset($folha) && $folha == 'adiantamento' ? 'selected': '' ?>>
              Adiantamento
            </option>
          </select>
        </td>
      </tr>
      <?php if ($oCompetencia && DBPessoal::verificarUtilizacaoEstruturaSuplementar()) { ?>
        <?php if(isset($folha) && $folha == "suplementar") { ?>

          <?php if (!empty($aSuplementarFechada)) { ?>
            <tr>
              <td align="left">
                <strong>Nro. Suplementar:</strong>
              </td>
              <td>
                <select id="r48_semest" name="r48_semest">
                  <?php foreach ($aSuplementarFechada as $oSuplementar) { ?>
                    <option value="<?= $oSuplementar->getNumero() ?>"><?= $oSuplementar->getNumero(); ?></option>
                  <?php } ?>
                </select>
              </td>
            </tr>
          <?php } else { ?>
            <tr>
              <td colspan="2" align="center">
                <font color="red">Sem suplementar para este período.</font>
                <?php $suplementar = 0; ?>
                <?php db_input("suplementar", 2, 0, true, 'hidden', 3); ?>
              </td>
            </tr>
          <?php } ?>
        <?php } elseif(isset($folha) && $folha == "complementar") { ?>

          <?php if (!empty($aComplementarFechada)) { ?>
            <tr>
              <td align="left">
                <strong>Nro. Complementar:</strong>
              </td>
              <td>
                <select id="r48_semest" name="r48_semest">
                  <?php foreach ($aComplementarFechada as $oComplementar) { ?>
                    <option value="<?= $oComplementar->getNumero() ?>"><?= $oComplementar->getNumero(); ?></option>
                  <?php } ?>
                </select>
              </td>
            </tr>
          <?php } else { ?>
            <tr>
              <td colspan="2" align="center">
                <font color="red">Sem complementar para este período.</font>
                <?php $complementar = 0; ?>
                <?php db_input("complementar", 2, 0, true, 'hidden', 3); ?>
              </td>
            </tr>
          <?php } ?>
        <?php } ?>
      <?php } else { ?>
        <?php
        if (isset($folha) && $folha == "complementar") {
          if($clgerfcom->numrows > 0){
            echo "
                  <tr>
                    <td align='left' title='".$Tr48_semest."'><strong>Nro. Complementar:</strong></td>
                    <td>
                      <select id='r48_semest' name='r48_semest'>
                        <option value = '0'>Todos
                 ";
                 for($i=0; $i<$clgerfcom->numrows; $i++){
                   db_fieldsmemory($result_semest, $i);
                   echo "<option value = '$r48_semest'>$r48_semest";
                 }
            echo "
                    </td>
                  </tr>
	         ";
          }else{
        ?>
        <tr>
          <td colspan="2" align="center">
            <font color="red">Sem complementar para este período.</font>
            <?
            $r48_semest = 0;
            db_input("r48_semest", 2,0, true, 'hidden', 3);
            ?>
          </td>
        </tr>
        <?
          }
        }
        ?>
      <?php } ?>
	<tr>
	  <td align="right" ><strong>Ordem:</strong></td>
	  <td>
	  <?
	  $arr=array("L"=>"Estrutural das lotações","N"=>"Nome dos funcionários","M"=>"Matrícula dos funcionários");
	  db_select("ordem",$arr,true,2);
	  ?>
	  </td>
	</tr>
	<tr>
	  <td  align="right" ><strong>Número de Vias:</strong></td>
	  <td>
	  <?
	  $arr_vias=array("1"=>"1","2"=>"2","3"=>"3");
	  db_select("num_vias",$arr_vias,true,2);
	  ?>
	  </td>
	</tr>
	<tr>
	  <td align="right" ><strong>Filtro:</strong></td>
	  <td>
	  <?
    if(!isset($filtro)){
      $filtro = 'M';
    }
	  $arr=array("N"=>"Nenhum","M"=>"Matrícula","L"=>"Lotação");
	  db_select("filtro",$arr,true,2,"onchange='js_filtra();'");
	  ?>
	  </td>
	</tr>
	<?
	if (isset($filtro)&&$filtro!=""&&$filtro!="N"){
	?>
	<tr>
	  <td align="right" ><strong>Filtrar por:</strong></td>
	  <td>
	  <?
    if(!isset($filtrar)){
      $filtrar = 'S';
    }
	  $arr1=array("."=>"------------","I"=>"Intervalo","S"=>"Selecionados");
	  db_select("filtrar",$arr1,true,2,"onchange='js_filtra();'");
	  ?>
	  </td>
	</tr>
	<?
	}

  if(isset($filtrar)&&isset($filtro)&&$filtro!="N"){
    if($filtro=='M'){

      $func='func_rhpessoal.php';
      $info='Matrícula';
      $cod='rh01_regist';
      $descr='z01_nome';

      if($filtrar=='I'){ ?>

      <tr>
          <td>
            <strong><?=((isset($info) && !empty($info)) ? $info : '')?> de</strong>
          </td>
          <td>
            <? db_input('cod_ini',8,'',true,'text',1," onchange='js_copiacampo();'","")  ?>
            <strong> à </strong>
            <? db_input('cod_fim',8,'',true,'text',1,"","")  ?>
          </td>
        </tr>
    <?php
      }
    }else if ($filtro=='L'){

      $func='func_rhlota.php';
      $info='Lotação';
      $cod='r70_codigo';
      $descr='r70_descr';

      if($filtrar=='I'){
    ?>

        <tr>
          <td align="right">
            <?php db_ancora('Lotação de: ', "js_pesquisar14_lotac(true, document.form1.cod_ini);", 1); ?>
          </td>
          <td>
            <?php

              db_input('cod_ini',8,'',true,'text',1," onchange='js_pesquisar14_lotac(false, document.form1.cod_ini);'","");
              db_ancora('à', "js_pesquisar14_lotac(true, document.form1.cod_fim);", 1);
              db_input('cod_fim',8,'',true,'text',1," onchange='js_pesquisar14_lotac(false, document.form1.cod_fim);'","");
            ?>
          </td>
        </tr>
<?php
      }
    }
  }

    if (isset($filtrar) && $filtrar=='S'&&isset($filtro)&&$filtro!="N"){
  ?>
      <tr>
        <td colspan="2" >
        <?
        $aux->cabecalho = "<strong>$info</strong>";
        $aux->codigo = "$cod"; //chave de retorno da func
        $aux->descr  = "$descr";   //chave de retorno
        $aux->nomeobjeto = 'lista';
        $aux->funcao_js = 'js_mostra';
        $aux->funcao_js_hide = 'js_mostra1';
        $aux->sql_exec  = "";
        $aux->func_arquivo = "$func";  //func a executar
        $aux->nomeiframe = "db_iframe_lista";
        $aux->localjan = "";
        $aux->onclick = "";
        $aux->db_opcao = 2;
        $aux->tipo = 2;
        $aux->top = 0;
        $aux->linhas = 5;
        $aux->vwhidth = 520;
        $aux->funcao_gera_formulario();
        ?>
        </td>
      </tr>
  <?php
    }
  ?>

  <tr>
    <td colspan="2">
    <fieldset>
      <legend>Local de Trabalho</legend>
<table>
    <tr>
      <td nowrap title="<?=((isset($Trh56_localtrab) && !empty($Trh56_localtrab)) ? $Trh56_localtrab : '')?>" align="right">
        <?
        db_ancora("<b>Local de trabalho:</b>","js_pesquisarh56_localtrab(true);",1);
        ?>
      </td>
      <td>
        <?
        db_input('rh56_localtrab',6,$Irh56_localtrab,true,'text',1," onchange='js_pesquisarh56_localtrab(false);'")
        ?>
        <?
        db_input('rh55_descr',40,$Irh55_descr,true,'text',3,'')
        ?>
      </td>
    </tr>

	<tr>
	  <td align="left" ><strong>Tipo Local:</strong>
    </td>
    <td>
	   <?
	    $arr_local=array("s"=>"Somente o Local","e"=>"Exceto o Local");
	    db_select("tipo_local",$arr_local,true,2);
	   ?>
	  </td>
	</tr>
  </table>
  </fieldset>
  </td>
  </tr>

  <tr>
    <td colspan="2">
    <fieldset>
        <legend>Mensagem: </legend>
        <?php
         db_textarea("mensagem1",7,30,'',true,'text',1);
        ?>
      </fieldset>
    </td>
  </tr>

  </table>
  </fieldset>
        <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite(arguments[0]);">
  </form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
(function(){

  if ($('tipo_folha').value == 'complementar' || $('tipo_folha').value == 'suplementar') {

    if ($('r48_semest') === null) {
      js_tipofolha();
    }
  }
})()

function js_pesquisarh56_localtrab(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhlocaltrab','func_rhlocaltrab.php?funcao_js=parent.js_mostrarhlocaltrab1|rh55_codigo|rh55_descr','Pesquisa',true,'20');
  }else{
    if(document.form1.rh56_localtrab.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhlocaltrab','func_rhlocaltrab.php?pesquisa_chave='+document.form1.rh56_localtrab.value+'&funcao_js=parent.js_mostrarhlocaltrab','Pesquisa',false);
    }else{
      document.form1.rh55_descr.value = '';
    }
  }
}
function js_mostrarhlocaltrab(chave,erro){
  document.form1.rh55_descr.value = chave;
  if(erro==true){
    document.form1.rh56_localtrab.focus();
    document.form1.rh56_localtrab.value = '';
  }
}
function js_mostrarhlocaltrab1(chave1,chave2){
  document.form1.rh56_localtrab.value = chave1;
  document.form1.rh55_descr.value = chave2;
  db_iframe_rhlocaltrab.hide();
}
function js_tipofolha(){
  document.form1.submit();
}
function js_anomes(){
  document.form1.submit();
}
function js_copiacampo(){
  if(document.form1.cod_fim.value== ""){
    document.form1.cod_fim.value = document.form1.cod_ini.value;
  }
  document.form1.cod_fim.focus();
}

var oInputInicialFinal = null;

function js_pesquisar14_lotac(mostra, oInput){

  oInputInicialFinal = oInput;

  if(mostra == true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframelotacao','func_rhlota.php?funcao_js=parent.js_mostrarhlota1|r70_codigo|r70_estrut','Pesquisa',true);
  }else{
    if(oInput.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframelotacao','func_rhlota.php?pesquisa_chave='+oInput.value+'&funcao_js=parent.js_mostrarhlota','Pesquisa',false);
    }else{
      oInput.value = '';
    }
  }
}

function js_mostrarhlota(chave,erro){

  if ( erro==true ) {

    alert(chave);
    oInputInicialFinal.value = '';
  }
}
function js_mostrarhlota1(chave1){

  oInputInicialFinal.value = chave1;
  db_iframelotacao.hide();
}

function js_emite(evt){

  var aCampos = ['xano', 'xmes', 'cod_ini', 'cod_fim', 'rh56_localtrab'];
  var aCampoNotNull = ['xano', 'xmes'];
  for (var iIndex = 0; iIndex < aCampos.length; iIndex++) {
    try {
      var oCampo = $(aCampos[iIndex]);
    } catch (e) {
      return false;
    }

    if (!oCampo) {
      continue;
    }

    if (aCampos[iIndex] == aCampoNotNull[iIndex] && oCampo.value == "") {
      alert("Campo não pode ficar em branco.");
      oCampo.focus();
      evt.stopImmediatePropagation();
      return false;
    }

    var sValorAnterior = oCampo.value;

    oCampo.onkeyup = evt;

    if (sValorAnterior != "" && oCampo.value == "") {
      evt.stopImmediatePropagation();
      return false;
    }
  };

  /**
   * Atribui a variáveis todos os
   * elementos do formulário.
   */
  var oOpcao     = document.form1.folha;
  var oAno       = document.form1.xano;
  var oMes       = document.form1.xmes;
  var oFiltro    = document.form1.filtro;
  var oMensagem  = document.form1.mensagem1;
  var oSelecao   = document.form1.selecao;
  var oOrdem     = document.form1.ordem;
  var oTipoLocal = document.form1.tipo_local;
  var oNumVias   = document.form1.num_vias;
  var oLocal     = document.form1.rh56_localtrab;
  var oLista     = document.form1.lista;
  var oCodIni    = document.form1.cod_ini;
  var oCodFim    = document.form1.cod_fim;
  var oSemest    = document.form1.r48_semest;

  /**
   * Atribui valores dos elementos
   * obrigatórios ao array que
   * virará o URL de parametros.
   */
  var aParams = [
    'sOpcao='     + oOpcao.value,
    'iAno='       + oAno.value,
    'iMes='       + oMes.value,
    'sFiltro='    + oFiltro.value,
    'sMensagem='  + oMensagem.value.urlEncode(),
    'sSelecao='   + oSelecao.value,
    'sOrdem='     + oOrdem.value,
    'sTipoLocal=' + oTipoLocal.value,
    'iNumVias='   + oNumVias.value,
    'sLocal='     + oLocal.value,
  ];

  if (oSemest !== undefined) {
    aParams.push('iSemest=' + oSemest.value);
  }

  if (oLista !== undefined && oLista.length) {

    var sLista = oLista[0].value;
    for (var i = oLista.length - 1; i >= 1; i--) {
      sLista += ',' + oLista[i].value;
    };

    aParams.push('sLista=' + sLista);
  };

  if (oCodIni !== undefined) {

    aParams.push('iCodIni='   + oCodIni.value);

    if (oCodFim.value != '') {
      aParams.push('iCodFim=' + oCodFim.value);
    } else {
      aParams.push('iCodFim=' + oCodIni.value);
    }
  }

  var janela = window.open(
    'pes2_contra_cheque.php?' + aParams.join('&'),
    '',
    'width='   + (screen.availWidth  - 5)  +
    ',height=' + (screen.availHeight - 40) +
    ',scrollbars=1,location=0'
  );

  janela.moveTo(0, 0);
}
</script>
<?php
  if(!empty($sErroMsg)) {
    db_msgbox($sErroMsg);
  }
?>