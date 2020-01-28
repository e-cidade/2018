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

//MODULO: projetos

$clcaracter                = new cl_caracter;
$oRotulo                  = new rotulocampo;

$oDaoObrasConstr->rotulo->label();
$oDaoObrasEnder ->rotulo->label();

$oRotulo->label("ob01_nomeobra");
$oRotulo->label("j14_nome");
$oRotulo->label("j13_descr");

$sSqlObras         = $oDaoObras        ->sql_query_file(null, "*",             null, " ob01_codobra = {$oGet->ob08_codobra}");
$sSqlObrasAlvara   = $oDaoObrasAlvara  ->sql_query_file(null, "1",             null, " ob04_codobra = {$oGet->ob08_codobra}");
$sSqlObrasConstr   = $oDaoObrasConstr  ->sql_query_file(null, "*",             null, " ob08_codobra = {$oGet->ob08_codobra}");
$sSqlObrasEnder    = $oDaoObrasEnder   ->sql_query     (null, "*",             null, " ob07_codobra = {$oGet->ob08_codobra}");
$sSqlObrasIPTUBase = $oDaoObrasIPTUBase->sql_query_file(null, "ob24_iptubase", null, " ob24_obras   = {$oGet->ob08_codobra}");
$sSqlParProjetos   = $oDaoParProjetos  ->sql_query_pesquisaParametros(db_getsession("DB_anousu"));

$rsObras           = $oDaoObras        ->sql_record($sSqlObras);
$rsObrasAlvara     = $oDaoObrasAlvara  ->sql_record($sSqlObrasAlvara);
$rsObrasConstr     = $oDaoObrasConstr  ->sql_record($sSqlObrasConstr);
$rsObrasEnder      = $oDaoObrasEnder   ->sql_record($sSqlObrasEnder);
$rsObrasIPTUBase   = $oDaoObrasIPTUBase->sql_record($sSqlObrasIPTUBase);
$rsParProjetos     = $oDaoParProjetos  ->sql_record($sSqlParProjetos);
$iMatricula        = null;

$oObras            = db_utils::fieldsMemory($rsObras, 0);

if ( (int)$oDaoObrasIPTUBase->numrows == 1 ) {
  $iMatricula      = db_utils::fieldsMemory($rsObrasIPTUBase, 0)->ob24_iptubase;
}

if ( $oDaoParProjetos->numrows > 0 ) {
  $oParProjetos  = db_utils::fieldsMemory($rsParProjetos, 0);
} else {
  db_msgbox(_M('tributario.projetos.db_frmobrasconstr.sem_parametros'));
  $iDBOpcao      = 3;
}

$iDBOpcao_area   = 1;
/**
 * Valida se a obra possui alvara
 */

if ($oDaoObrasAlvara->numrows > 0) {
  $iDBOpcao_area = 3;
}

$ob01_regular    = null;
$ob01_nomeobra   = null;
$ob08_codconstr  = null;
$ob08_codobra    = null;
$ob08_area       = null;
$ob08_ocupacao   = null;
$ob08_tipoconstr = null;
$ob08_tipolanc   = null;
$ob07_lograd     = null;
$ob07_numero     = null;
$ob07_compl      = null;
$ob07_bairro     = null;
$ob07_areaatual  = null;
$ob07_unidades   = null;
$ob07_pavimentos = null;
$ob07_inicio     = null;
$ob07_inicio_dia = null;
$ob07_inicio_mes = null;
$ob07_inicio_ano = null;
$ob07_fim        = null;
$ob07_fim_dia    = null;
$ob07_fim_mes    = null;
$ob07_fim_ano    = null;
$j14_nome        = null;
$j13_descr       = null;

$ob08_codobra    = $oObras->ob01_codobra;
$ob01_nomeobra   = $oObras->ob01_nomeobra;

if ($oDaoObrasConstr->numrows > 0 && $oDaoObrasEnder->numrows > 0) {

  $oObrasConstr    = db_utils::fieldsMemory($rsObrasConstr, 0);
  $oObrasEnder     = db_utils::fieldsMemory($rsObrasEnder, 0);

  $ob08_codconstr  = $oObrasConstr->ob08_codconstr;
  $ob08_area       = number_format($oObrasConstr->ob08_area, 2, '.', '');
  $ob08_ocupacao   = $oObrasConstr->ob08_ocupacao;
  $ob08_tipoconstr = $oObrasConstr->ob08_tipoconstr;
  $ob08_tipolanc   = $oObrasConstr->ob08_tipolanc;
  $ob07_lograd     = $oObrasEnder ->ob07_lograd;
  $j14_nome        = $oObrasEnder ->j14_nome;
  $j13_descr       = $oObrasEnder ->j13_descr;
  $ob07_numero     = $oObrasEnder ->ob07_numero;
  $ob07_compl      = $oObrasEnder ->ob07_compl;
  $ob07_bairro     = $oObrasEnder ->ob07_bairro;
  $ob07_areaatual  = $oObrasEnder ->ob07_areaatual;
  $ob07_unidades   = $oObrasEnder ->ob07_unidades;
  $ob07_pavimentos = $oObrasEnder ->ob07_pavimentos;
  $ob07_inicio     = $oObrasEnder ->ob07_inicio;
  $ob07_fim        = $oObrasEnder ->ob07_fim;

  if ( !empty($ob07_inicio) ) {

    $aDataInicio     = explode("-", $oObrasEnder->ob07_inicio);
    $ob07_inicio_dia = $aDataInicio[2];
    $ob07_inicio_mes = $aDataInicio[1];
    $ob07_inicio_ano = $aDataInicio[0];
  }

  if ( !empty($ob07_fim) ) {

    $aDataFim        = explode("-", $oObrasEnder->ob07_fim);
    $ob07_fim_dia    = $aDataFim[2];
    $ob07_fim_mes    = $aDataFim[1];
    $ob07_fim_ano    = $aDataFim[0];
  }
}

/**
 * Dados para os Combos das caracteristicas
 */
$sSqlCaracterOcupacao   = $oDaoCaracter->sql_query("","j31_codigo, j31_descr","j31_codigo"," j32_grupo = {$oParProjetos->ob21_grupotipoocupacao}");
$sSqlCaracterConstrucao = $oDaoCaracter->sql_query("","j31_codigo, j31_descr","j31_codigo"," j32_grupo = {$oParProjetos->ob21_grupotipoconstrucao}");
$sSqlCaracterLancamento = $oDaoCaracter->sql_query("","j31_codigo, j31_descr","j31_codigo"," j32_grupo = {$oParProjetos->ob21_grupotipolancamento}");

$rsCaracterOcupacao     = $oDaoCaracter->sql_record($sSqlCaracterOcupacao);
$rsCaracterConstrucao   = $oDaoCaracter->sql_record($sSqlCaracterConstrucao);
$rsCaracterLancamento   = $oDaoCaracter->sql_record($sSqlCaracterLancamento);

if ( !empty ($ob08_codconstr) && empty($iMatricula) && $oObras->ob01_regular == "t" ) {

   $iDBOpcao      = 3;
   $iDBOpcao_area = 3;
   db_msgbox(_M('tributario.projetos.db_frmobrasconstr.obra_sem_matricula'));
}

$lPermiteExclusao = !empty($oObrasConstr) ? true : false;
?>


<style>
#tabela_construcoes td select:first-child {
 width: 93px;
}
#tabela_construcoes td select {
 width: 371px;
}
#ob07_compl {
 width: 467px;
}

</style>
<div id="ctnWindowAux"></div>
<form name="form1" method="post" action="">
  <center>
    <fieldset style="width: 600px">
    <legend><strong>Cadastro de Construções:</strong></legend>
    <table id="tabela_construcoes">
      <tr>
        <td nowrap title="<?=$Tob08_codobra?>">
           <?
           db_input('ob08_codconstr',10,$Iob08_codconstr,true,'hidden',3,"");
           db_ancora($Lob08_codobra,"js_pesquisaob08_codobra(true);",3);
           ?>
        </td>
        <td>
          <?
          db_input('ob08_codobra' ,10,$Iob08_codobra ,true,'text',3," onchange='js_pesquisaob08_codobra(false);'");
          db_input('ob01_nomeobra',50,$Iob01_nomeobra,true,'text',3,'')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Tob08_area?>">
           <?=$Lob08_area?>
        </td>
        <td>
        <?
          db_input('ob08_area',10,$Iob08_area,true,'text',$iDBOpcao_area," onkeypress='return validaMonetario(this, event)';")
        ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=$Tob08_area?>">
           <? db_ancora("<b>Caracteristicas:</b> ","js_caracteristicasConstrucao();",$iDBOpcao);?>
        </td>
        <td>
        &nbsp;
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=$Tob08_ocupacao?>">
          <?=$Lob08_ocupacao?>
        </td>
        <td>
          <?
          db_selectrecord("ob08_ocupacao", $rsCaracterOcupacao,    true,$iDBOpcao, "", "ob08_ocupacao");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Tob08_tipoconstr?>">
           <?=$Lob08_tipoconstr?>
        </td>
        <td>
          <?
          db_selectrecord("ob08_tipoconstr",$rsCaracterConstrucao, true,$iDBOpcao, "", "ob08_tipoconstr");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Tob08_tipolanc?>">
           <?=$Lob08_tipolanc?>
        </td>
        <td>
          <?
            db_selectrecord("ob08_tipolanc",$rsCaracterLancamento, true,$iDBOpcao, "", "ob08_tipolanc");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Tob07_lograd?>">
          <?
            db_ancora($Lob07_lograd,"js_pesquisaob07_lograd(true);", $iDBOpcao);
          ?>
        </td>
        <td>
        <?
          db_input('ob07_lograd',10,$Iob07_lograd,true,'text',($oObras->ob01_regular == "f" ? $iDBOpcao : 3)," onchange='js_pesquisaob07_lograd(false);'");
          db_input('j14_nome',50,$Ij14_nome,true,'text',3,'')
        ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=$Tob07_numero?>">
           <?=$Lob07_numero?>
        </td>
        <td>
        <?
        db_input('ob07_numero',10,$Iob07_numero,true,'text',$iDBOpcao,"");
        ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=$Tob07_compl?>">
           <?=$Lob07_compl?>
        </td>
        <td>
          <?
          db_input('ob07_compl',63,$Iob07_compl,true,'text',$iDBOpcao,"");
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=$Tob07_bairro?>">
           <?
           db_ancora($Lob07_bairro,"js_pesquisaob07_bairro(true);",$iDBOpcao);
           ?>
        </td>
        <td>
        <?
          db_input('ob07_bairro',10,$Iob07_bairro,true,'text',$iDBOpcao," onchange='js_pesquisaob07_bairro(false);'");
          db_input('j13_descr'  ,50,$Ij13_descr,  true,'text',3,'');
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=$Tob07_areaatual?>">
           <?=$Lob07_areaatual?>
        </td>
        <td>
        <?
          db_input('ob07_areaatual' ,10, $Iob07_areaatual,  true, 'text', $iDBOpcao, "");
        ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=$Tob07_unidades?>">
           <?=$Lob07_unidades?>
        </td>
        <td>
          <?
            db_input('ob07_unidades'  ,10, $Iob07_unidades,   true, 'text', $iDBOpcao, "");
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=$Tob07_pavimentos?>">
           <?=$Lob07_pavimentos?>
        </td>
        <td>
          <?
            db_input('ob07_pavimentos',10, $Iob07_pavimentos, true, 'text', $iDBOpcao, "");
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=$Tob07_inicio?>">
           <?=$Lob07_inicio?>
        </td>
        <td>
          <?
            db_inputdata('ob07_inicio',$ob07_inicio_dia,$ob07_inicio_mes,$ob07_inicio_ano,true,'text',$iDBOpcao,"");
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=$Tob07_fim?>">
           <?=$Lob07_fim?>
        </td>
        <td>
          <?
            db_inputdata('ob07_fim',$ob07_fim_dia,$ob07_fim_mes,$ob07_fim_ano,true,'text',$iDBOpcao,"");
          ?>
        </td>
      </tr>
    </table>
    </fieldset>
    <input name="salvar"  type="button" id="salvar"   onClick="return js_verifica_data2()"    value="Salvar">
    <input name="excluir" type="button" id="excluir"  onClick="return js_excluirConstrucao()" value="Excluir" <?php echo $lPermiteExclusao ? "" : "disabled";?> >
  </center>
</form>
<script>

var sRPC           = "pro1_obrasconstr.RPC.php";

/**
 *  impede o usuário de incluir uma data final menor do que a inicial.
 *  exemplo de caso que será impedido de ser incluído:
 *  Data inicio:  01/01/2009                  Data final:  01/12/2001
 */
function js_verifica_data2() {

   var sDataInicio       = new Number( js_formatar( $F('ob07_inicio'), 'd' ).replace( /-/g, '' ) );
   var sDataFim          = new Number( js_formatar( $F('ob07_fim')   , 'd' ).replace( /-/g, '' ) );

   if ( sDataFim != 0 && sDataInicio > sDataFim ) {

     alert(_M('tributario.projetos.db_frmobrasconstr.data_inicial_maior_data_final'));
     $('ob07_fim').setValue('');
     $('ob07_fim').focus();
     return false;
   }
   js_salvaDados();
}

function js_pesquisaob07_lograd(mostra){

  if (mostra) {
    <?
    if ($oObras->ob01_regular == "t") {
      echo "js_OpenJanelaIframe('','db_iframe_ruas','func_ruasobras.php?pesquisa_chave={$iMatricula}&funcao_js=parent.js_mostraruas2|j36_codigo|j14_nome|j13_codi|j13_descr','Pesquisa',true);";
    } else {
      echo "js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome','Pesquisa',true);";
    }
    ?>

  } else {

     if (document.form1.ob07_lograd.value != '') {
        js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.ob07_lograd.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false);
     } else {
       document.form1.j14_nome.value = '';
     }
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave;
  if(erro==true){
    document.form1.ob07_lograd.focus();
    document.form1.ob07_lograd.value = '';
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.ob07_lograd.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe_ruas.hide();
}
function js_mostraruas2(chave1,chave2,cod,bai){
  document.form1.ob07_lograd.value = chave1;
  document.form1.j14_nome.value = chave2;
  document.form1.ob07_bairro.value = cod;
  document.form1.j13_descr.value = bai;
  db_iframe_ruas.hide();
}
function js_pesquisaob07_bairro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostrabairro1|j13_codi|j13_descr','Pesquisa',true);
  }else{
     if(document.form1.ob07_bairro.value != ''){
        js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?pesquisa_chave='+document.form1.ob07_bairro.value+'&funcao_js=parent.js_mostrabairro','Pesquisa',false);
     }else{
       document.form1.j13_descr.value = '';
     }
  }
}
function js_mostrabairro(chave,erro){
  document.form1.j13_descr.value = chave;
  if(erro==true){
    document.form1.ob07_bairro.focus();
    document.form1.ob07_bairro.value = '';
  }
}
function js_mostrabairro1(chave1,chave2){
  document.form1.ob07_bairro.value = chave1;
  document.form1.j13_descr.value = chave2;
  db_iframe_bairro.hide();
}
function js_pesquisaob08_codobra(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_obras','func_obras.php?funcao_js=parent.js_mostraobras1|ob01_codobra|ob01_nomeobra','Pesquisa',true);
  }else{
     if(document.form1.ob08_codobra.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_obras','func_obras.php?pesquisa_chave='+document.form1.ob08_codobra.value+'&funcao_js=parent.js_mostraobras','Pesquisa',false);
     }else{

       document.form1.ob01_nomeobra.value = '';
     }
  }
}
function js_mostraobras(chave,erro){
  document.form1.ob01_nomeobra.value = chave;
  if(erro==true){
    document.form1.ob08_codobra.focus();
    document.form1.ob08_codobra.value = '';
  }
}
function js_mostraobras1(chave1,chave2){
  document.form1.ob08_codobra.value = chave1;
  document.form1.ob01_nomeobra.value = chave2;
  db_iframe_obras.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_obrasconstr','func_obrasconstr.php?funcao_js=parent.js_preenchepesquisa|ob08_codconstr','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_obrasconstr.hide();
  <?
  if($iDBOpcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
if(document.form1.ob08_codconstr.value == 0){
  document.form1.ob08_codconstr.value = '';
}


/**
 * funcao para windowAux com dados das caracteristicas da construção
 */
 function js_caracteristicasConstrucao() {

   /**
    * Inicia WindowAux
    */
   if (typeof(oWindowCaracteristicas) == "object") {
     js_showWindow(oWindowCaracteristicas.divWindow);
   } else {

     var sConteudo = " <center>                                                                             ";
         sConteudo+= "   <div id='headerCaracteristicas'></div>                                             ";
         sConteudo+= "   <fieldset>                                                                         ";
         sConteudo+= "     <legend>                                                                         ";
         sConteudo+= "       <strong>Características: </strong>                                             ";
         sConteudo+= "     </legend>                                                                        ";
         sConteudo+= "     <div id='contentCaracteristicas'></div>                                          ";
         sConteudo+= "   </fieldset>                                                                        ";
         sConteudo+= "   <div id='footerCaracteristicas'>                                                   ";
         sConteudo+= "     <br />                                                                           ";
         sConteudo+= "     <input type='button' value='Salvar' onClick='oWindowCaracteristicas.hide();'>    ";
         sConteudo+= "   </div>                                                                             ";
         sConteudo+= " </center>                                                                            ";

     var sMsg      = "Selecione as características relacionadas ao Grupo \"Construções\"";

     oWindowCaracteristicas = new windowAux("oWindowCaracteristicas", "Características da Constução", 650, 360);
     oWindowCaracteristicas.setContent(sConteudo);
     oWindowCaracteristicas.show(10, window.availWidth);

     $('contentCaracteristicas').style.width = oWindowCaracteristicas.getWidth() - 30;


     /**
      * Inicia MessageBoard
      */
     oMessage  = new DBMessageBoard('msgboard',
                                    'Características da Construção',
                                    sMsg,
                                    $('headerCaracteristicas'));
     oMessage.show();

     js_criaGridCaracateristicas();
   }
 }

/**
 * Renderiza grid vazia
 */
function js_criaGridCaracateristicas(){

  oGridCaracteristicas =   new DBGrid('oGridCaracteristicas');
  oGridCaracteristicas.nameInstance = 'oGridCaracteristicas';
  oGridCaracteristicas.sName        = 'oGridCaracteristicas';
  oGridCaracteristicas.setHeader    ( new Array('Codigo', 'Grupo da Característica', 'Característica') );
  oGridCaracteristicas.setCellAlign ( new Array('center',"left", "center") );
  oGridCaracteristicas.show         ( $('contentCaracteristicas') );


  /**
   * Carrega dados para que sejam renderizados
   */

  var aDadosGrid = js_carregaDadosGrid();
}

function js_carregaDadosGrid() {

  js_divCarregando(_M('tributario.projetos.db_frmobrasconstr.carregando_caracteristicas'), 'msgBox');

  var oUrl           = js_urlToObject(window.location.search);
  var oParam         = new Object();
  oParam.sExec        = "getCaracteristicasConstrucoes";
  oParam.iCodigoObra = oUrl.ob08_codobra;
  var oAjax          = new Ajax.Request(sRPC, {
      method         : 'post',
      parameters     : 'json=' + Object.toJSON(oParam),
      onComplete     : function(oAjax) {

        js_removeObj('msgBox');
        var oRetorno = eval("("+oAjax.responseText+")");

        if (oRetorno.iStatus== "2") {
          alert(oRetorno.sMessage.urlDecode());
        } else {

          oGridCaracteristicas.clearAll(true);


          for (var iIndice = 0 in oRetorno.aDadosCaracteristicas) {


            var aLinha = new Array();

            with (oRetorno.aDadosCaracteristicas[iIndice]) {

              var sComboBox = "<select id=\"comboCaracter"+ iIndice +"\" style='width: 100%'>";
                  sComboBox+= "<option value='0'>Nenhuma...</option>";

              for (var iIndiceCaracter = 0; iIndiceCaracter < aCaracteristicas.length; iIndiceCaracter++) {

                with(aCaracteristicas[iIndiceCaracter]) {
                  var sSelecionado = lSelecionada == "t" ? "selected" : "";
                  sComboBox += "<option value='" + j31_codigo + "' " + sSelecionado + " > " + j31_descr.urlDecode() + "</option>";
                }
              }
              sComboBox += "</select>";

              aLinha[0] = iCodigoGrupo;
              aLinha[1] = sDescricao.urlDecode();
              aLinha[2] = sComboBox;
            }
            oGridCaracteristicas.addRow(aLinha);
          }
          oGridCaracteristicas.renderRows();
        }
     }
  });
}

function js_showWindow(oElemento) {
  oElemento.style.display = '';
}

function js_salvaDados() {

   /**
    * Mostra div carregando
    */
   js_divCarregando(_M('tributario.projetos.db_frmobrasconstr.salvando_construcao'), 'msgBox');

   var oCaracteristicas   = new Object();
   var oDados             = new Object();
   oDados.ob08_codobra    = $F('ob08_codobra');
   oDados.ob08_codconstr  = $F('ob08_codconstr');
   oDados.ob08_ocupacao   = $F('ob08_ocupacao');
   oDados.ob08_tipoconstr = $F('ob08_tipoconstr');
   oDados.ob08_tipolanc   = $F('ob08_tipolanc');
   oDados.ob08_area       = $F('ob08_area');

   oDados.ob07_lograd     = $F('ob07_lograd');
   oDados.ob07_numero     = $F('ob07_numero');
   oDados.ob07_compl      = $F('ob07_compl');
   oDados.ob07_bairro     = $F('ob07_bairro');
   oDados.ob07_areaatual  = $F('ob07_areaatual');
   oDados.ob07_unidades   = $F('ob07_unidades');
   oDados.ob07_pavimentos = $F('ob07_pavimentos');
   oDados.ob07_inicio     = js_formatar($F('ob07_inicio'), "d");
   oDados.ob07_fim        = $F('ob07_fim') != '' ? js_formatar($F('ob07_fim'),    "d") : '';

   if ( typeof(oGridCaracteristicas) != "undefined" ) {

     for (var iIndiceLinha = 0; iIndiceLinha < oGridCaracteristicas.aRows.length; iIndiceLinha++) {

       with (oGridCaracteristicas.aRows[iIndiceLinha]) {

         oCaracteristicas[aCells[0].getValue()] = aCells[2].getValue();
       }
     }
   oDados.oCaracteristicas = oCaracteristicas;
   }

   var oParam              = new Object();
   oParam.oDados           = oDados;
   oParam.sExec            = "salvar";
   var oDataRequest        = new Object();
   oDataRequest.method     = "post";
   oDataRequest.parameters = "json=" + Object.toJSON(oParam);
   oDataRequest.onComplete = function(oAjax) {

     js_removeObj('msgBox');
     var oRetorno = eval("("+oAjax.responseText+")");

     if (oRetorno.iStatus== "2") {
       alert(oRetorno.sMessage.urlDecode().replace(/\\n/g,'\n') );
     } else {
       alert(oRetorno.sMessage.urlDecode().replace(/\\n/g,'\n') );
       window.location = window.location.href.replace(/#/g,'');
     }
   };
   var oAjax               = new Ajax.Request(sRPC, oDataRequest);
}

function js_excluirConstrucao() {

  js_divCarregando(_M('tributario.projetos.db_frmobrasconstr.excluindo_construcao'), 'msgBox');

  var oParam               = new Object();
  oParam.sExec             = "excluir";
  oParam.iCodigoObra       = $F('ob08_codobra');
  oParam.iCodigoConstrucao = $F('ob08_codconstr');

  var oDataRequest         = new Object();
  oDataRequest.method      = "post";
  oDataRequest.parameters  = "json=" + Object.toJSON(oParam);
  oDataRequest.onComplete  = function(oAjax) {

    js_removeObj('msgBox');

    var oRetorno = eval("(" + oAjax.responseText + ")");
    if (oRetorno.iStatus== "2") {
      alert( oRetorno.sMessage.urlDecode() );
    } else {
      alert( oRetorno.sMessage.urlDecode() );
      window.location = window.location.href.replace(/#/g,'');
    }
  };
  var oAjax               = new Ajax.Request(sRPC, oDataRequest);

}
</script>