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

//MODULO: empenho

require_once(modification("classes/db_empparametro_classe.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clempparametro = new cl_empparametro;

$result_elementos = $clorcparametro->sql_record($clorcparametro->sql_query_file(null, "o50_subelem"));
if($clorcparametro->numrows > 0){
  db_fieldsmemory($result_elementos,0);
}

$clempautitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e54_anousu");
$clrotulo->label("o56_elemento");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("m61_descr");

if (!isset($e30_numdec)) {
  $e30_numdec=4;
}

if(isset($db_opcaoal)) {
  $db_opcao = 3;
  $db_botao = false;
} else {
  $db_botao = true;
}
if(isset($opcao) && $opcao=="alterar") {
  $db_opcao = 2;
} elseif (isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3) {

  $db_opcao = 3;
  if(isset($db_opcaoal)) {
    $db_opcao = 33;
  }
} else {

  $db_opcao = 1;
  $db_botao = true;
  if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){

    $e55_item        = "";
    $e55_sequen      = "";
    $e55_quant       = "";
    unset($e55_matunid);
    $e55_vltot       = "";
    $e55_descr       = "";
    $e55_vluni       = "";
    $pc01_descrmater = "";
    $pc01_servico    = "f";
  }
}
?>

<script type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>

<script>

  function js_calcula(origem){

    obj   = document.form1;
    quant = new Number(obj.e55_quant.value);
    uni   = new Number(obj.e55_vluni.value);
    tot   = new Number(obj.e55_vltot.value).toFixed(2);

    if(origem=='quant' && quant != ''){

      if(isNaN(quant)){

        obj.e55_quant.focus();
        return false;
      }

      if(tot!=0){

        t = new Number(tot/quant);
        obj.e55_vltot.value = tot;
        obj.e55_vluni.value = t.toFixed('<?php echo $e30_numdec?>');
      }else{

        t = new Number(uni*quant);
        obj.e55_vltot.value=t.toFixed(2);
      }
    }

    if(origem=="uni"){

      if(isNaN(uni)){

        //alert("Valor unico inváido!");
        obj.e55_vluni.focus();
        return false;
      }
      t=new Number(uni*quant);
      obj.e55_vltot.value=t.toFixed(2);
    }
    if(origem=="tot"){

      if(isNaN(tot)){

        //alert("Valor total inváido!");
        obj.e55_vltot.focus();
        return false;
      }
      if(quant!=0){

        t = new Number(tot/quant);
        obj.e55_vltot.value = tot;
        obj.e55_vluni.value = t.toFixed('<?php echo $e30_numdec?>');
      }
    }
  }

  function js_verificaControlaQuantidade(lControla) {

    <?php
    if ($db_opcao == 3) {
      echo "return;";
    }
    ?>
    if (lControla == "true") {

      $("e55_quant").style.backgroundColor = "#FFFFFF";
      $("e55_quant").removeAttribute("readonly");

      $("e55_matunid").style.backgroundColor = "#FFFFFF";
      $("e55_matunid").removeAttribute("disabled");
    } else {

      $("e55_quant").style.backgroundColor = "#DEB887";
      $("e55_quant").setAttribute("readonly", true);
      $("e55_quant").value = 1;

      $("e55_matunid").style.backgroundColor = "#DEB887";
      $("e55_matunid").setAttribute("disabled", true);

      js_calcula('uni');
    }
  }

  function js_troca(codele) {

    descr = eval("document.form1.ele_"+codele+".value");
    arr =  descr.split("#");

    elemento  = arr[0];
    descricao = arr[1];
    document.form1.elemento01.value = elemento;
    document.form1.o56_descr.value = descricao;
  }
</script>

<style type="text/css">
  #e55_autori,
  #e55_sequen,
  #e55_item,
  #pc07_codele,
  #elemento01,
  #e55_quant,
  #e55_matunid,
  #e55_vluni,
  #e55_vltot {
    width: 20%;
  }
  #pc01_descrmater,
  #pc07_codeledescr,
  #o56_descr,
  #m61_descr {
    width: 75%;
  }
</style>

<form name="form1" method="post" action="">

  <center>

    <fieldset style="margin-top:5px; width:50%;">

      <legend>
        <strong>Itens</strong>
      </legend>
      <table border="0" cellpadding='0' cellspacing='0'>

        <tr style="height: 20px;">
          <td nowrap title="<?php echo (isset($Te55_autori) ? $Te55_autori : null)?>" style="width: 90px;">
            <?php echo $Le55_autori?>
          </td>
          <td>
            <?php db_input('e55_autori',8,$Ie55_autori,true,'text',3); ?>
          </td>
        </tr>

        <tr style="height: 20px;">
          <td nowrap title="<?php echo (isset($Te55_sequen) ? $Te55_sequen : null)?>">
            <strong>Sequência:</strong>
          </td>
          <td>
            <?php db_input('e55_sequen',6,$Ie55_sequen,true,'text',3)  ?>
          </td>
        </tr>

        <tr style="height: 20px;">
          <td nowrap title="<?php echo (isset($Te55_item) ? $Te55_item : null)?>">
            <?php db_ancora($Le55_item,"js_pesquisae55_item(true);",$db_opcao); ?>
          </td>
          <td>
            <?php db_input('e55_item',6,$Ie55_item,true,'text',$db_opcao," onchange='js_pesquisae55_item(false);'")  ?>
            <?php db_input('pc01_descrmater',50,$Ipc01_descrmater,true,'text',3,'')   ?>
          </td>
        </tr>

        <?php if( isset($e55_item) && $e55_item!='' && (empty($liberado) || (isset($liberado) && $liberado==true) ) ){?>
          <tr style="height: 20px;">
            <td nowrap title="">
              <strong>Ele. Item:</strong>
            </td>
            <td>
              <?php  db_selectrecord("pc07_codele",$result_elemento,true,$db_opcao,'','','','',"js_troca(this.value);");  ?>
            </td>
          </tr>

          <?php
        }else{
          db_input('pc07_codele',50,0,true,'hidden',1);
        }
        ?>

        <tr style="height: 20px;">
          <td><?php echo $Lo56_elemento?></td>
          <td>
            <?php
            $ero = $clempautitem->erro_msg;
            $result88 = $clempautitem->sql_record($clempautitem->sql_query_pcmaterele($e55_autori,null,"o56_codele as codele,o56_elemento as elemento01,o56_descr"));
            if($clempautitem->numrows>0){

              $numrows88= $clpcmater->numrows;
              db_fieldsmemory($result88,0);//$codele é o primeiro elemento incluido
              echo "
            <script>
            parent.document.formaba.empautidot.disabled=false;\n
            </script>
            ";
            }else{

              echo "
            <script>
            parent.document.formaba.empautidot.disabled=false;\n
            </script>

            ";
              if(isset($e55_item) && $e55_item!=""){

                $result99  = $clpcmater->sql_record($clpcmater->sql_query_elemento($e55_item,"o56_codele as  codele,o56_elemento as elemento01,o56_descr"));
                $numrows99 = $clpcmater->numrows;
                db_fieldsmemory($result99,0);//$codele é o primeiro elemento incluido
              }else{

                $elemento01 = '';
                $o56_descr = '';
              }
            }
            $clempautitem->erro_msg = $ero;
            db_input('elemento01',20,0,true,'text',3);
            db_input('o56_descr',40,0,true,'text',3);
            if(isset($numrows99) && $numrows99>0){

              for($i=0; $i<$numrows99; $i++){

                db_fieldsmemory($result99,$i);
                $r="ele_$codele";
                $$r = "$elemento01#$o56_descr";
                db_input("ele_$codele",20,0,true,'hidden',3);
              }
            }
            ?>
          </td>
        </tr>

        <tr style="height: 20px;">
          <td nowrap title="<?php echo (isset($Te55_quant) ? $Te55_quant : null)?>">
            <?php echo (isset($Le55_quant) ? $Le55_quant : null)?>
          </td>
          <td>
            <?php
            if(isset($pc01_servico) and $pc01_servico=='t') {

              if (!isset($e55_servicoquantidade) || $e55_servicoquantidade == "f") {
                $e55_quant = "";
              }
              $db_opcao_e55_quant = 3;
            } else {
              $db_opcao_e55_quant = $db_opcao;
            }
            db_input('e55_quant',8,$Ie55_quant,true,'text',$db_opcao_e55_quant,"onchange=\"js_calcula('quant');\"");
            ?>
            <label class="bold" style="width: 63px; display: inline-block">
              Unidade:
            </label>
            <?php

            $oDaoMatUnid      = new cl_matunid();
            $sSqlBuscaUnidade = $oDaoMatUnid->sql_query_file(null, "m61_codmatunid,substr(m61_descr,1,20) as m61_descr", "m61_descr");
            $rsBuscaUnidade   = $oDaoMatUnid->sql_record($sSqlBuscaUnidade);
            $aUnidadesMedidas = array();
            $iValueUnidade  = null;
            if ($oDaoMatUnid->numrows > 0) {

              for ($iRow = 0; $iRow < $oDaoMatUnid->numrows; $iRow++) {

                $oStdUnidadeMedida = db_utils::fieldsMemory($rsBuscaUnidade, $iRow);
                $aUnidadesMedidas[$oStdUnidadeMedida->m61_codmatunid] = $oStdUnidadeMedida->m61_descr;

                if (strtolower(trim($oStdUnidadeMedida->m61_descr)) == 'unidade' )  {
                  $iValueUnidade = $oStdUnidadeMedida->m61_codmatunid;
                }
              }
            }

            if (!isset($e55_matunid) || $e55_matunid == "") {
              $e55_matunid = $iValueUnidade;
            }
            db_select('e55_matunid', $aUnidadesMedidas, true, 1, ' lang="m61_codmatunid" ');
            $e55_matuniddefault = $iValueUnidade;
            db_input('e55_matuniddefault',0,0,true,'hidden');
            ?>
          </td>
        </tr>
        <?php

        if (isset($pc01_servico) and $pc01_servico=='t') {

          if (!isset($e55_servicoquantidade)) {
            $e55_servicoquantidade = "false";
          }
          ?>
          <tr>
            <td style="width: 220px;">
              <script>
                //Controla a validação de vírgulas e pontos.
                var oQuantidade = $("e55_quant");
                oQuantidade.setAttribute("onkeydown" ,"return js_controla_tecla_enter(this,event);");
                oQuantidade.setAttribute("onkeyup" ,"js_ValidaCampos(this,4,'Quantidade','f','f',event);");
                oQuantidade.setAttribute("onblur", "js_ValidaMaiusculo(this,'f',event);");
              </script>
              <b>Serviço Controlado por Quantidade:</b>
            </td>
            <td>
              <select name="lControlaQuantidade" id="lControlaQuantidade" onchange="js_verificaControlaQuantidade(this.value);" <?php echo $db_opcao == 3 ? " disabled='true'" : "" ?>>
                <option value="false">NÃO</option>
                <option value="true">SIM</option>
              </select>
              <script>
                lControlaQuantidade = "<?php echo $e55_servicoquantidade == 't' ? 'true' : 'false';?>";
                $("lControlaQuantidade").value = lControlaQuantidade;
                js_verificaControlaQuantidade($F("lControlaQuantidade"));
              </script>
            </td>
          </tr>
          <?php
        }
        ?>

        <tr style="height: 20px;">
          <td nowrap title="Valor unitário">
            <b>Valor Unitário:</b>
          </td>
          <td>
            <?php
            if(isset($opcao)){

              if(!isset($e55_vlrun)){
                $e55_vlrun = number_format($e55_vltot/$e55_quant,2,".","");
              }
              $e55_vluni=$e55_vlrun;
            }
            db_input('e55_vluni',13,$Ie55_vltot,true,'text',$db_opcao,"onchange=\"js_calcula('uni');\"")
            ?>
            <b>Valor Total:</b>
            <?php
            if(isset($pc01_servico) and $pc01_servico=='t') {
              $db_opcao_e55_vltot = 3;
            } else {
              $db_opcao_e55_vltot = $db_opcao;
            }

            db_input('e55_vltot',13,$Ie55_vltot,true,'text',$db_opcao_e55_vltot,"onblur=\"js_calcula('tot');\"");
            ?>
          </td>
        </tr>

        <tr style="height: 20px;">
          <td nowrap title="<?php echo (isset($Te55_descr) ? $Te55_descr : null)?>" colspan="2">
            <fieldset style="width:60%;">
              <legend>
                <?php echo (isset($Le55_descr) ? $Le55_descr : null)?>
              </legend>
              <?php
              $lDisabled = false;
              if (empty($opcao)) {

                if (isset($e55_item) && $e55_item != '') {

                  $sWhere      = "pc01_codmater = {$e55_item}";
                  $sSqlPcMater = $clpcmater->sql_query_file($e55_item, "pc01_complmater,pc01_liberaresumo", null, $sWhere);
                  $result      = $clpcmater->sql_record($sSqlPcMater);
                  if ($clpcmater->numrows > 0) {

                    db_fieldsmemory($result,0);
                    if ($pc01_liberaresumo == 'f') {

                      $lDisabled = true;
                      $e55_descr = $pc01_complmater;
                    } else {

                      // PARA SAPIRANGA A VARIÁVEL TEM QUE ESTAR EM BRANCO
                      $e55_descr = '';
                    }
                  } else {
                    $e55_descr='';
                  }
                } else {
                  $e55_descr='';
                }
              }

              if ($lDisabled) {
                $iOpcao = 3;
              } else {
                $iOpcao = $db_opcao;
              }

              db_textarea('e55_descr',3,87,$Ie55_descr,true,'text',$iOpcao,"");
              ?>
            </fieldset>
          </td>
        </tr>
      </table>

    </fieldset>

    <table>

      <tr>
        <td colspan='2' align='center'>
          <input name="<?php echo ($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?php echo ($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?php echo ($db_botao==false?"disabled":"")?> onclick=" return js_verificar();" >
          <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?php echo ($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
          <input name="importar" type="button" id="pesquisar" value="Importar autorização" onclick="js_importar();" <?php echo ($db_botao==false?"disabled":"")?> >
        </td>
      </tr>

    </table>

    <table width="90%" border="0" height="50%">

      <tr>
        <td valign="top"  align='center' width="90%"  height="100%">
          <?php
          $aCamposSqlItens = array(
            'e55_autori',
            'e55_item',
            '(select m61_descr from matunid where m61_codmatunid = e55_matunid) as m61_descr',
            'pc07_codele',
            'e55_sequen',
            'e55_descr',
            'e55_quant',
            'e55_vlrun',
            'round(e55_vltot,2) as e55_vltot',
            'pc01_descrmater',
          );
          $sSqlItens = $clempautitem->sql_query_pcmaterele($e55_autori, null, implode(',', $aCamposSqlItens), "e55_sequen");
          $chavepri  = array(
            "e55_autori" => $e55_autori,
            "e55_sequen" => (isset($e55_sequen) ? $e55_sequen : null),
          );
          $aCamposGrid = array(
            'e55_sequen',
            'e55_item',
            'pc07_codele',
            'pc01_descrmater',
            'e55_descr',
            'm61_descr',
            'e55_quant',
            'e55_vlrun',
            'e55_vltot',
          );
          $cliframe_alterar_excluir->chavepri      = $chavepri;
          $cliframe_alterar_excluir->sql           = $sSqlItens;
          $cliframe_alterar_excluir->campos        = implode(',', $aCamposGrid);
          $cliframe_alterar_excluir->legenda       = "Itens Lançados";
          $cliframe_alterar_excluir->strFormatar   = "";
          $cliframe_alterar_excluir->iframe_height = "160";
          $cliframe_alterar_excluir->iframe_width  = "100%";
          $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
          ?>
        </td>
      </tr>

      <tr>
        <td><b>Total de itens:</b>
          <?php
          $result02 = $clempautitem->sql_record($clempautitem->sql_query_file($e55_autori,null,"count(e55_sequen) as tot_item"));
          db_fieldsmemory($result02,0);

          if($tot_item>0){

            $result = $clempautitem->sql_record($clempautitem->sql_query_file($e55_autori,null,"sum(round(e55_vltot,2)) as tot_valor"));
            db_fieldsmemory($result,0);
            if(empty($tot_valor) ||  $tot_valor==""){

              $tot_valor = '0';
              $tot_item  = '0';
            }else{
              $tot_valor= number_format($tot_valor,2,".","");
            }
          }else{

            $tot_valor = '0';
            $tot_item  = '0';
          }
          db_input('tot_item',8,0,true,'text',3);
          ?>
          <b>Total dos valores:</b>
          <?php
          db_input('tot_valor',13,0,true,'text',3,"onchange=\"js_calcula('quant');\"")
          ?>

        </td>
      </tr>

    </table>

  </center>

</form>

<script>
  function js_verificar() {

    vltot = new Number(document.form1.e55_vltot.value);

    if (isNaN(vltot) || vltot==0 || vltot ==' '  ) {

      alert('Valor total inválido!');
      return false;
    }

    return true;
  }

  function js_importar(){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_empautitem','db_iframe_empautoriza','func_empautoriza.php?funcao_js=parent.js_importar02|e54_autori','Pesquisa',true,0);
  }

  function js_importar02(chave){

    db_iframe_empautoriza.hide();
    if (confirm("Deseja realmente importar os itens da autorização "+chave+"?")) {

      var opcao = document.createElement("input");
      opcao.setAttribute("type","hidden");
      opcao.setAttribute("name","autori_importa");
      opcao.setAttribute("value",chave);
      document.form1.appendChild(opcao);
      document.form1.submit();
    }
  }

  function js_consulta(){

    var opcao = document.createElement("input");
    opcao.setAttribute("type","hidden");
    opcao.setAttribute("name","consultando");
    opcao.setAttribute("value","true");
    document.form1.appendChild(opcao);
    <?php if(isset($opcao) && $opcao=="alterar"){ ?>
    var opcao = document.createElement("input");
    opcao.setAttribute("type","hidden");
    opcao.setAttribute("name","opcao");
    opcao.setAttribute("value","alterar");
    document.form1.appendChild(opcao);
    <?php } ?>
    document.form1.submit();
  }

  function js_cancelar(){

    var opcao = document.createElement("input");
    opcao.setAttribute("type","hidden");
    opcao.setAttribute("name","novo");
    opcao.setAttribute("value","true");
    document.form1.appendChild(opcao);
    document.form1.submit();
  }

  function js_pesquisae55_item(mostra){

    qry = "";
    <?php
    // t = desdobramento
    // f = elemento
    if(isset($elemento01) && trim($elemento01) != "" && $tot_item > 0){

      if($o50_subelem == 't'){
        echo " qry = '&chave_o56_elemento=".$elemento01."';";
      }else{

        $result_empparametro=$clempparametro->sql_record($clempparametro->sql_query_file(db_getsession("DB_anousu","e30_formvisuitemaut")));
        if ($clempparametro->numrows>0){

          db_fieldsmemory($result_empparametro,0);
          if($e30_formvisuitemaut==1){
            echo " qry = '&chave_o56_elemento=".substr($elemento01,0,7)."';";
          }else if($e30_formvisuitemaut==2){
            echo " qry = '&chave_o56_elemento=".$elemento01."';";
          }else if($e30_formvisuitemaut==3){

          }else{
            echo " qry = '&chave_o56_elemento=".substr($elemento01,0,7)."';";
          }
        }else{
          echo " qry = '&chave_o56_elemento=".substr($elemento01,0,7)."';";
        }
      }
    }
    ?>

    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_empautitem','db_iframe_pcmaterele',"func_pcmaterelelibaut.php?iCodigoAutorizacao="+$F('e55_autori')+"&funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater|pc07_codele"+qry,'Pesquisa',true,"0","1");
    }else{

      if(document.form1.e55_item.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_empautitem','db_iframe_pcmaterele',"func_pcmaterelelibaut.php?iCodigoAutorizacao="+$F('e55_autori')+"&pesquisa_chave='+document.form1.e55_item.value+'&funcao_js=parent.js_mostrapcmater"+qry,'Pesquisa',false);
      }else{

        document.form1.pc01_descrmater.value = '';
        document.form1.submit();
      }
    }
  }

  function js_mostrapcmater(chave,erro,codele){

    document.form1.pc01_descrmater.value = chave;

    if(erro==true){
      document.form1.e55_item.focus();
      document.form1.e55_item.value = '';
      document.form1.submit();
    }else{
      document.form1.pc07_codele.value = codele;
      js_consulta();
      document.form1.e55_quant.focus();
    }
  }

  function js_mostrapcmater1(chave1,chave2,codele){

    document.form1.e55_item.value        = chave1;
    document.form1.pc01_descrmater.value = chave2;
    document.form1.pc07_codele.value     = codele;
    db_iframe_pcmaterele.hide();
    js_consulta();
    document.form1.e55_quant.focus();
  }

  function js_pesquisa(){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empautitem','func_empautitem.php?funcao_js=parent.js_preenchepesquisa|e55_autori|e55_sequen','Pesquisa',true);
  }

  function js_preenchepesquisa(chave,chave1){
    db_iframe_empautitem.hide();
    <?php
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
    }
    ?>
  }

  <?php
  if(isset($incluir) || isset($alterar) || isset($excluir) ) {
    echo "\n\nparent.CurrentWindow.corpo.iframe_empautidot.location.href =  'emp1_empautidot001.php?anulacao=true&e56_autori=$e55_autori';\n";
  }
  ?>

  <?php if(isset($numrows99) && $numrows99>0){ ?>
  codele = document.form1.pc07_codele.value;
  if(codele!=''){
    js_troca(codele);
  }
  <?php } ?>
</script>
