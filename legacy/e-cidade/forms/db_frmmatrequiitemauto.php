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

$clrotulo = new rotulocampo;
$clrotulo->label("m60_descr");
$clrotulo->label("m60_codmater");
$clrotulo->label("m40_codigo");
$clrotulo->label("m80_codigo");
$clrotulo->label("m41_codigo");
$clrotulo->label("m42_codigo");
$clrotulo->label("m41_quant");
$clrotulo->label("m41_obs");
$clrotulo->label("cc08_sequencial");

if(isset($opcao) && $opcao=="alterar"){
  $db_opcao = 2;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
  $db_opcao = 3;
}else{
  $db_opcao = 1;
}

$sDisplayPontoPedido = 'none';
$sTextoPontoPedido = '';

if (!empty($m41_codmatmater)) {

  try {

    $oMaterial = new MaterialAlmoxarifado($m41_codmatmater);
    $oAlmoxarifado = new Almoxarifado(db_getsession("DB_coddepto"));
    $nPontoPedido = $oMaterial->getPontoDePedidoNoAlmoxarifado($oAlmoxarifado);

    if ($nPontoPedido > 0 && ControleEstoque::itemEstaNoPontoPedido($oMaterial, $oAlmoxarifado)) {

      $sTextoPontoPedido    = "O item <b>{$oMaterial->getDescricao()}</b> atingiu o seu Ponto de Pedido: <b>";
      $sTextoPontoPedido   .= $nPontoPedido."</b>.";
      $sDisplayPontoPedido = '';
    }
  } catch (Exception $oErro) {
  }
}
?>

<div class="container">
  <form name="form1" method="POST" action="" onSubmit="return validarFormulario();">

    <fieldset>
      <legend>Item da Requisição</legend>
      <table class="form-container">

        <tr>
          <td nowrap title="<?=@$Tm40_codigo?>">
            <b>Requisição: </b>
          </td>
          <td>
            <?php
              db_input('m40_codigo',10,@$Im40_codigo,true,'text',3,"");
              db_input('m41_codigo',10,@$Im41_codigo,true,'hidden',3,"");
              db_input('m80_codigo',10,@$Im80_codigo,true,'hidden',3,"");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=@$Tm40_codigo?>">
            <b> Atendimento: </b>
          </td>
          <td>
            <?php db_input('m42_codigo',10,$Im42_codigo,true,'text',3,""); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=@$Tm60_codmater?>">
            <?db_ancora(@$Lm60_codmater,"js_pesquisa_codmater(true);",$db_opcao);?>
          </td>
          <td>
            <?php
              db_input('m41_codmatmater',10,$Im60_codmater,true,'text',$db_opcao,"onchange='js_pesquisa_codmater(false);'");
              db_input('m60_descr',40,$Im60_descr,true,'text',3,"");
            ?>
          </td>
        </tr>

        <tr>
          <td title=<?=@$Tm41_quant?>>
            <?php echo $Lm41_quant; ?>
          </td>
          <td>
            <?php db_input('m41_quant',10,$Im41_quant,true,'text',$db_opcao,""); ?>
          </td>
        </tr>

        <?php
          $testquan='f';
          if (isset($m41_codmatmater)&&$m41_codmatmater!="") {

            $codmater=$m41_codmatmater;
            $result_param=$clmatparam->sql_record($clmatparam->sql_query_file());
            if ($clmatparam->numrows){
              db_fieldsmemory($result_param,0);
              if ($m90_reqsemest=='t'){
                $testquan='t';
              }
            }

            $rsMaterial   = $clmatmater->sql_record($clmatmater->sql_query_file($m41_codmatmater));
            $oMaterial    = db_utils::fieldsMemory($rsMaterial, 0);
            $sqlmatrequi  = $clmatrequi->sql_query($m40_codigo, "m40_depto");
            $resmatrequi  = $clmatrequi->sql_record($sqlmatrequi);
            db_fieldsmemory($resmatrequi, 0);

            $result_matestoque=$clmatestoque->sql_record($clmatestoque->sql_query_almox(null,"sum(m70_valor)as vlrtot,sum(m70_quant)as quantot",null,"m70_codmatmater=$codmater", "", "", db_getsession("DB_coddepto")));
            if ($clmatestoque->numrows!=0) {
              db_fieldsmemory($result_matestoque,0);
            }

            if ($oMaterial->m60_controlavalidade == 1 || $oMaterial->m60_controlavalidade == 2) {

              echo "<tr>";
              echo "<td><b>Lotes do Material</b></td>";
              echo "<td><b><a href='' onclick='js_mostraLotes({$m41_codmatmater}, ".db_getsession("DB_coddepto").");return false;'>Ver Lotes</a></b>";
              echo "<tr>";
            }

            if ($iTipoControleCustos > 0) {

              echo "<tr><td>";
              db_ancora("<b>Centro de de Custo:",'js_adicionaCentroCusto()', 1);
              echo "</td><td>";
              db_input('cc08_sequencial',10,$Icc08_sequencial,true,"text", 3);
              db_input('cc08_descricao',40,$Im60_descr,true,"text",3);
              echo "</td></tr>" ;
            }
        ?>

        <tr>
          <td title='Quant. Disponível'>
            <b>Quantidade Disponível:</b></td>
          <td>
            <?php
              $quant_disp='0';
              if (isset($quantot)&&($quantot!="")){
                $quant_disp=$quantot;
              }
              db_input('quant_disp',10,$Im41_quant,true,'text',3,"");
            ?>
          </td>
        </tr>

      <?php } // if ?>

      <tr>
        <td nowrap title="<?=@$Tm41_obs?>" colspan='2'>
          <fieldset>
            <legend><?php echo $Lm41_obs; ?></legend>
            <?php db_textarea('m41_obs',0,50,$Im41_obs,true,'text',$db_opcao,"") ?>
          </fieldset>
        </td>
      </tr>

    </table>
    </fieldset>

    <?php
      if(!isset($opcao) && isset($db_opcao) && $db_opcao==3){
        $db_botao=false;
      }
    ?>

    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit"
    id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
    <?=($db_botao==false?"disabled":"")?> onClick="return validarFormulario();" />

    <input name="Imprimir" type="button" value="Imprimir Requisição" onclick="js_imprime();" />

    <div style="margin-top: 10px;text-align: left; display: <?php echo $sDisplayPontoPedido;?>; background-color: #fcf8e3;border: 1px solid #fcc888;padding: 10px">
        <?php echo $sTextoPontoPedido; ?>
    </div>

    <div style="margin-top:10px;">
      <?php
       $chavepri= array("m40_codigo"=>@$m40_codigo,"m41_codigo"=>@$m41_codigo,"m41_obs"=>@$m41_obs,"m41_quant"=>@$m41_quant,"m41_codmatmater"=>@$m41_codmatmater,"m60_descr"=>@$m60_descr);
       $cliframe_alterar_excluir->chavepri=$chavepri;
       if (isset($m40_codigo)&&@$m40_codigo!=""){
          $cliframe_alterar_excluir->sql = $clmatrequiitem->sql_query(null,'*',null,"m41_codmatrequi=".$m40_codigo);
          $cliframe_alterar_excluir->sql_disabled = $clmatrequiitem->sql_query_atend(null,'*',null,"m41_codmatrequi=".$m40_codigo." and m43_codigo is not null");
       }
       $cliframe_alterar_excluir->campos  ="m41_codmatmater,m60_descr,m41_quant,m41_obs";
       $cliframe_alterar_excluir->legenda       ="ITENS REQUISITADOS";
       $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
       $cliframe_alterar_excluir->textocabec    = "darkblue";
       $cliframe_alterar_excluir->textocorpo    = "black";
       $cliframe_alterar_excluir->fundocabec    = "#aacccc";
       $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
       $cliframe_alterar_excluir->iframe_width  = "710";
       $cliframe_alterar_excluir->iframe_height = "130";
       $lib=4;
       $cliframe_alterar_excluir->opcoes = @$lib;
       $cliframe_alterar_excluir->iframe_alterar_excluir(1);
       db_input('db_opcao',10,'',true,'hidden',3);
      ?>
    </div>

  </form>
</div>

<script type="text/javascript">

$('m41_codmatmater').focus();

function validarFormulario() {

  if (empty($('m41_codmatmater').value)) {

    alert('Campo Código do material não informado.');
    return false;
  }

  if (!js_testaquant()) {
    return false;
  }

  return true;
}

function js_imprime() {

  var query = "";

  if ( !empty($('m40_codigo').value) ) {

    query  = "ini="  + $('m40_codigo').value;
    query += "&fim=" + $('m40_codigo').value;
    query += "&tObserva=18";
    jan = window.open('mat2_matrequi002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
}

function js_pesquisa_codmater(mostra){

  if (mostra) {
    js_OpenJanelaIframe('','db_iframe_matmater','func_matmater.php?funcao_js=parent.js_mostra1|m60_codmater|m60_descr','Pesquisa',true,3);
  } else {

     if ($('m41_codmatmater').value != '') {
        js_OpenJanelaIframe('','db_iframe_matmater','func_matmater.php?pesquisa_chave='+ $('m41_codmatmater').value+'&funcao_js=parent.js_mostra','Pesquisa',false);
     } else {
        $('pc01_descrmater').value = "";
     }
  }
}

function js_mostra(chave,erro) {

  $('m60_descr').value = chave;

  if (!erro) {
    return document.form1.submit();
  }

  $('m41_codmatmater').value = '';
  $('m41_codmatmater').focus();
}

function js_mostra1(chave1,chave2){

  $('m41_codmatmater').value = chave1;
  $('m60_descr').value = chave2;
  db_iframe_matmater.hide();
  document.form1.submit();
}

function js_testaquant() {

  <?php if ($testquan == 't') { echo 'return true;'; } ?>

  m41_quant  = new Number($('m41_quant').value );
  quant_disp = new Number($('quant_disp').value );

  if (m41_quant <= quant_disp && m41_quant > 0){
    return true;
  }else{

    alert('Informe uma Quantidade Válida!!');
    $('m41_quant').value="";
    $('m41_quant').focus();
    return false;
  }
}

function js_mostraLotes(iItem, iCodEstoque) {

  iCodItem      = new Number(iItem);//código do material
  nValor        = new Number($F('m41_quant'));//Quantidade digitada pelo usuário
  nValorReqItem = new Number($F('m41_quant'));
  if (nValor  == 0) {
    return alert('Informe a quantidade');
  }

  sUrl  = 'mat4_mostraitemlotes.php?iCodMater='+iCodItem+'&iCodDepto='+iCodEstoque+'&nValor='+nValor;
  sUrl += '&nValorSolicitado='+nValorReqItem+'&updateField=m41_quant';
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_g2','db_iframe_lotes',sUrl,'Lotes ',true);
}

function js_adicionaCentroCusto() {

  <?php if (isset($oDeptoRequi) && $oDeptoRequi !== null) : ?>
  var iOrigem  = 2;
  var sUrl     = 'iOrigem='+iOrigem+'&iCodItem='+$F('m41_codmatmater')+'&iCodigoDaLinha='+$F('m41_codmatmater');
  sUrl        += '&iCodigoDepto=<?echo $oDeptoRequi->m40_depto?>';
  if ($F('m41_codmatmater')) {

    js_OpenJanelaIframe('',
                        'db_iframe_centroCusto',
                        'cus4_escolhercentroCusto.php?'+sUrl,
                        'Centro de Custos',
                        true,
                        '25',
                        '1',
                        (document.body.scrollWidth-10),
                        (document.body.scrollHeight-100)
                       );
  }
  <?php else : ?>
  alert('Não foi possível encontrar o departamento ' + '<?php echo isset($m40_codigo) ? $m40_codigo : null ?>');
  <?php endif ?>
}

function js_completaCustos(iCodigo, iCriterio, iDescr) {

  $('cc08_sequencial').value = iCriterio;
  $('cc08_descricao').value  = iDescr;
  db_iframe_centroCusto.hide();
}
</script>
