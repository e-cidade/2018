<?php
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

//MODULO: fiscal
require_once(modification("classes/db_arreinscr_classe.php"));
require_once(modification("classes/db_notasiss_classe.php"));
$clarreinscr = new cl_arreinscr;
$clnotasiss  = new cl_notasiss;

$oPost = db_utils::postMemory($_POST);

$claidof->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q09_descr");
$clrotulo->label("z01_nome");
$clrotulo->label("nome");
$clrotulo->label("z01_nome_grafica");
$clrotulo->label("y08_login");
$clrotulo->label("p58_requer");
$clrotulo->label("y02_codproc");

$sSqlNotaIss     = $clnotasiss->sql_query_file(null, 'q09_codigo, q09_descr, q09_gruponotaiss', null, " q09_gruponotaiss <> 2 ");
$result_notasiss = $clnotasiss->sql_record($sSqlNotaIss);

if (!isset($y08_nota)){
  db_fieldsmemory($result_notasiss,0);
  $y08_nota=$q09_codigo;
}

$debito    = false;
$existnota = false;
$sMensagem   = '';

if ( !empty($y08_inscr) ) {

  $oEmpresa  = new Empresa($y08_inscr);
  $nomeinscr = $oEmpresa->getCgmEmpresa()->getNome();

  if ($db_opcao == 1) {

	  $result_aidof = $claidof->sql_record($claidof->sql_query_file(null,"y08_notafi as nota_fin","y08_notafi desc","y08_inscr=$y08_inscr and y08_cancel='f' and y08_nota=$y08_nota"));

	  if ($claidof->numrows != 0){

	    db_fieldsmemory($result_aidof,0);
	    $existnota = true;

	  } else {
	    $y08_notain="";
	  }
  }
}

if ( empty($valida_dados) && isset($y08_inscr) && $y08_inscr != "" && $db_opcao == 1) {

  $valida_dados = 1;

  $result_arreinscr=$clarreinscr->sql_record($clarreinscr->sql_query_arrecad(null,null,"arrecad.k00_dtvenc as vencimento",null,"arreinscr.k00_inscr=$y08_inscr"));
  for ($y=0;$y<$clarreinscr->numrows;$y++){
    db_fieldsmemory($result_arreinscr,$y);
    $data_atual=date("Y-m-d",db_getsession("DB_datausu"));
    $aidofante = true;
    if ($vencimento < $data_atual){
      $debito = true;
    }
  }

  if ($debito) {
    $sMensagem .= 'Inscrição com debitos em aberto.\\n';
  }

  /**
   * Verifica se a inscrição escolhida é do tipo serviço
   */
  $lTipo = false;
  $oDaoIssBase = db_utils::getDao('issbase');
  $sSqlIssBase = $oDaoIssBase->sql_queryAtividadeServico($y08_inscr);
  $rsIssBase = db_query($sSqlIssBase);

  if (!$rsIssBase) {
    throw new Exception("Erro ao Buscar Incrição: ". pg_last_error());
  }

  if (pg_num_rows($rsIssBase) == 0) {

    $lTipo      = false;
    $sMensagem .= 'Inscrição com tipo de atividade não permitido.\\n';
  }

  if ($debito || $lTipo){

    if (!isset($passa)||@$passa=="") {
      db_msgbox($sMensagem);
    }

    if ($lTipo) {
      db_redireciona('fis4_aidof004.php');
    }
  }
}

?>

<style>
 #y08_nota {
   width: 83px;
   <?php if ($db_opcao == 1) { ?>
   display: none;
   <?php } ?>

 }
 #y08_notadescr{
   width: 100%;
 }
 #y08_obs {
   width: 100%;
   height: 70px;
 }
</style>

<form name="form1" class="container" id="formulario_principal" method="POST">

  <?php db_input('valida_dados', 10, 0, true, 'hidden', 3); ?>

  <fieldset>
    <legend>Procedimentos - Libera AIDOF</legend>

    <table border="0" align="center" class="form-container">

  <tr>
    <td nowrap title="<?=@$Ty08_quantsol?>">
       Inscrição:
    </td>
    <td>
    <?php db_input('y08_codigo', 10, $Iy08_codigo, true, 'hidden', 3); ?>
    <?php db_input('y08_inscr' , 6, $Iy08_inscr, true, 'text', 3,'class="field-size2"'); ?>
    <?php db_input('nomeinscr'  , 6, $Iz01_nome, true, 'text', 3,'class="field-size9"'); ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty08_nota?>">
       <?=@$Ly08_nota?>
    </td>
    <td>
    <?php
     if ($db_opcao != 1) {
      $opc = 3;
     } else {
      $opc = 1;
     }
     db_selectrecord('y08_nota',$result_notasiss,true,$opc,"","","","","document.form1.submit();");
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty02_codproc?>">
       <?php
        db_ancora(@$Ly02_codproc,"js_pesquisay02_codproc(true);",$db_opcao);
       ?>
    </td>
    <td>
      <?php
        db_input('y02_codproc',6,$Iy02_codproc,true,'text',$db_opcao," class='field-size2' onchange='js_pesquisay02_codproc(false);'")
      ?>
      <?php
        db_input('p58_requer',30,@$Ip58_requer,true,'text',3,"class='field-size9'")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty08_dtlanc?>">
       <?=@$Ly08_dtlanc?>
    </td>
    <td>
      <?php
        db_inputdata('y08_dtlanc',@$y08_dtlanc_dia,@$y08_dtlanc_mes,@$y08_dtlanc_ano,true,'text',$db_opcao," class='field-size2'")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty08_notain?>">
       <?=@$Ly08_notain?>
    </td>
    <td>
      <?php

        $iOpcNotaInc = 1;
        $y08_notain  = null;

        if ($existnota == true){

          if ($db_opcao == 1){
            $y08_notain = $nota_fin + 1;
            $iOpcNotaInc = 3;
          }
        }

        db_input('y08_notain',6,$Iy08_notain,true,'text', $iOpcNotaInc,"onchange='js_notafin();' class='field-size2'");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty08_quantsol?>">
       <?=@$Ly08_quantsol?>
    </td>
    <td>
      <?php
        db_input('y08_quantsol',6,$Iy08_quantsol,true,'text',$db_opcao,"onchange='js_notafin();' class='field-size2'")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty08_quantlib?>">
       <?=@$Ly08_quantlib?>
    </td>
    <td>
      <?php
        db_input('y08_quantlib',6,$Iy08_quantlib,true,'text',$db_opcao,"onchange='js_notafin();' class='field-size2'")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty08_notafi?>">
       <?=@$Ly08_notafi?>
    </td>
    <td>
      <?php
        db_input('y08_notafi',6,$Iy08_notafi,true,'text',3," class='field-size2'")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty08_numcgm?>">
       <?php
        db_ancora(@$Ly08_numcgm,"js_pesquisay08_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td>
      <?
        db_input('y08_numcgm',6,$Iy08_numcgm,true,'text',$db_opcao,"class='field-size2' onchange='js_pesquisay08_numcgm(false);'");
        db_input('z01_nome_grafica',30,@$Iz01_nome,true,'text',3,"class='field-size9'")
      ?>
    </td>
  </tr>
  <tr>
    <td colspan ="2" nowrap title="<?=@$Ty08_obs?>">
      <fieldset>
        <legend><?=@$Ly08_obs?></legend>
        <?php
          db_textarea('y08_obs',0,36,$Iy08_obs,true,'text',$db_opcao,"", "");
        ?>
      </fieldset>
    </td>
  </tr>
  </table>

  </fieldset>

  <input name="db_opc" onClick="return js_validarFormulario()" type="submit" id="db_opc"    value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==3&&$alt==true?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="voltar" onClick="js_voltar()"                   type="button" id="voltar"    value="Voltar" <?php echo $db_opcao <> 1 ? "style='display:none'":""; ?>  >

<?php if ($db_opcao != 1) { ?>
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?php } ?>
</form>
<div class="container" id="aidof_lancados">
<fieldset>
  <legend>AIDOFs Liberados:</legend>
  <iframe  name="itens" id="itens" src="forms/db_frmaidofant.php?inscr=<?=@$y08_inscr?>" width="600" height="130" marginwidth="0" marginheight="0" frameborder="1"></iframe>
</fieldset>
</div>
<script type="text/javascript">

/**
 * Valida formulario
 *
 * @access public
 * @return bool
 */
function js_validarFormulario() {

  iGrafica = document.getElementById('y08_numcgm').value;

  if ( iGrafica == '' || iGrafica == null  ) {

    alert("Usuário:\n\nCampo gráfica não informado.");
    return false;
  }

  return true;
}

function js_submit(){
  document.form1.submit();
}
function js_pesquisay08_nota(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_notasiss','func_notasiss.php?funcao_js=parent.js_mostranotasiss1|0|q09_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_notasis','func_notasis.php?pesquisa_chave='+document.form1.y08_nota.value+'&funcao_js=parent.js_mostranotasiss','Pesquisa',false);
  }
}
function js_mostranotasiss(chave,erro){
  document.form1.q09_descr.value = chave;
  if(erro==true){
    document.form1.y08_nota.focus();
    document.form1.y08_nota.value = '';
  }
}
function js_mostranotasiss1(chave1,chave2){
  document.form1.y08_nota.value = chave1;
  document.form1.q09_descr.value = chave2;
  db_iframe_notasiss.hide();
}

function js_pesquisay08_login(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.y08_login.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){
    document.form1.y08_login.focus();
    document.form1.y08_login.value = '';
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.y08_login.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisay08_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe','func_graficas.php?funcao_js=parent.js_mostragraficas1|0|1&aidof=1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe','func_graficas.php?pesquisa_chave='+document.form1.y08_numcgm.value+'&funcao_js=parent.js_mostragraficas&aidof=1','Pesquisa',false);
  }
}
function js_mostragraficas(chave,erro){
//  alert (chave+" - "+erro);
  document.form1.z01_nome_grafica.value = chave;
  if(erro==true){
    document.form1.y08_numcgm.focus();
    document.form1.y08_numcgm.value = '';
  }
}
function js_mostragraficas1(chave1,chave2){
  document.form1.y08_numcgm.value = chave1;
  document.form1.z01_nome_grafica.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_aidof','func_aidofalt.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_aidof.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
function js_notafin(){

  quant = new Number(document.form1.y08_quantlib.value);
  notain = new Number(document.form1.y08_notain.value);

  if(quant != 0 && notain != 0){
    document.form1.y08_notafi.value = quant+notain-1 ;
  }
}
function js_pesquisay02_codproc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_proc','func_protprocesso.php?funcao_js=parent.js_mostraprocesso1|p58_codproc|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_proc','func_protprocesso.php?pesquisa_chave='+document.form1.y02_codproc.value+'&funcao_js=parent.js_mostraprocesso','Pesquisa',false);
  }
}
function js_mostraprocesso(iCodigoProcesso, sRequerente, lErro){

  document.form1.p58_requer.value = sRequerente;
  if( lErro==true){
    document.form1.y02_codproc.focus();
    document.form1.y02_codproc.value = '';
  }
}
function js_mostraprocesso1(chave1,chave2){

  document.form1.y02_codproc.value = chave1;
  document.form1.p58_requer.value  = chave2;
  db_iframe_proc.hide();
}

function js_imprimir( iCodigo, iIncricao ) {

  var iOpcao = '<?php echo $db_opcao; ?>';

  if (confirm('Deseja imprimir AIDOF ?')) {

    jan = window.open('fis2_emiteaidof002.php?codaidof='+iCodigo,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }

  if ( iOpcao == 1 ) {
    location.href = "fis4_aidof004.php?y08_inscr="+iIncricao;
  }
}

function js_voltar() {
  window.location.href = "fis4_aidof004.php";
}
</script>