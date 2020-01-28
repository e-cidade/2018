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

include("dbforms/db_classesgenericas.php");
include("classes/db_matparam_classe.php");
include("classes/db_db_departorg_classe.php");
include("classes/db_db_almoxdepto_classe.php");
include("classes/db_matestoque_classe.php");
include("classes/db_matunid_classe.php");
include("classes/db_matmater_classe.php");
include("classes/db_matmaterunisai_classe.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clmatparam               = new cl_matparam;
$cldb_departorg           = new  cl_db_departorg;
$clmatestoque             = new cl_matestoque;
$cldb_almoxdepto          = new cl_db_almoxdepto;
$clmatunid                = new cl_matunid;
$clmatmater               = new cl_matmater;
$clmatmaterunisai         = new cl_matmaterunisai;
$clrotulo                 = new rotulocampo;

$sSql                     = $clmatparam->sql_query_file();
$rsParam                  = $clmatparam->sql_record($sSql);
$oParam                   = db_utils::fieldsMemory($rsParam, 0);
$tobserva                 = $oParam->m90_modrelsaidamat;
$db_botao                 = true;

$clrotulo->label("m60_descr");
$clrotulo->label("m60_codmater");
$clrotulo->label("m97_sequencial");
$clrotulo->label("m80_codigo");
$clrotulo->label("m98_sequencial");
$clrotulo->label("m98_quant");
$clrotulo->label("m98_obs");
if (!empty($opcao) && $opcao=="alterar"){
  $db_opcao = 2;
}elseif (!empty($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
  $db_opcao = 3;
} else {
  $db_opcao = 1;
}

if (!empty($db_opcao)&&trim($db_opcao)==4){
  $db_opcao = 3;
}

if (!empty($m97_sequencial)&&$m97_sequencial!=""){
  $result_itematend=$clmatpedidoitem->sql_record($clmatpedidoitem->sql_query_anulacao(null,'*',null,"m98_matpedido=$m97_sequencial and (m99_codigo is not null OR m101_sequencial is not null)"));
  if ($clmatpedidoitem->numrows!=0){
    $itematend=true;
    $db_botao=false;
  }
  $result_dept=$clmatpedido->sql_record($clmatpedido->sql_query(null,'m91_depto',null,"m97_sequencial=$m97_sequencial"));
  if ($clmatpedido->numrows!=0){
    db_fieldsmemory($result_dept,0);
  }

}
if (!empty($m98_matmater)&&$db_opcao==1&&!empty($incluir)){
  $result_mat = $clmatpedidoitem->sql_record($clmatpedidoitem->sql_query(null,'*',null,"m98_matpedido = $m97_sequencial and m98_matmater = $m98_matmater "));
  if ($clmatpedidoitem->numrows>0){
    db_msgbox("Material já incluido nesta solicitação!!");
    $m98_matmater = "";
    $m60_descr = "";
  }
}
?>
<form name="form1" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>" >
  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td nowrap title="<?=@$Tm97_sequencial?>">

        <b>Solicitação: </b>
      </td>
      <td>
        <?
        db_input('m97_sequencial',10,$Im97_sequencial,true,'text',3,"");
        $m98_sequencial=@$m98_sequencial;
        db_input('m98_sequencial',10,$Im98_sequencial,true,'hidden',3,"");
        db_input('m80_codigo',10,$Im80_codigo,true,'hidden',3,"");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tm60_codmater?>">
        <?
        db_ancora(@$Lm60_codmater,"js_pesquisa_codmater(true);",$db_opcao);
        ?>
      </td>
      <td>
        <?
        db_input('m98_matmater',10,$Im60_codmater,true,'text',$db_opcao,"onchange='js_pesquisa_codmater(false);'");
        db_input('m60_descr',40,$Im60_descr,true,'text',1,"");
        ?>
      </td>
    </tr>

    <tr>
      <td title=<?=@$Tm98_quant?>>
        <?=@$Lm98_quant?>
      </td>
      <td>
        <?
        db_input('m98_quant',10,$Im98_quant,true,'text',$db_opcao,"");
        ?>
        <?if (isset($m98_matmater)&&$m98_matmater!=""){?>
          <b>Unid. Saída:</b>
          <?
          $result_unisai=$clmatmaterunisai->sql_record($clmatmaterunisai->sql_query($m98_matmater));
          if ($clmatmaterunisai->numrows>0){
            db_fieldsmemory($result_unisai,0);
            $unisai=$m61_abrev;
            $codunid=$m62_codmatunid;
          }
          db_input('codunid',10,'',true,'hidden',3,"");
          db_input('unisai',10,'',true,'text',3,"");
          ?>
        <?
        }
        db_input('m97_db_almox',10,'',true,'hidden',3,"");
        ?>
      </td>
    </tr>
    <?
    $testquan='f';
    if (!empty($m98_matmater)&&$m98_matmater!=""){

      $codmater=$m98_matmater;
      $result_param=$clmatparam->sql_record($clmatparam->sql_query_file());
      if ($clmatparam->numrows){
        db_fieldsmemory($result_param,0);
        if ($m90_reqsemest=='t'){
          $testquan='t';
        }
      }

      $depto_almox = "";
      $result_deptoalmox = $cldb_almox->sql_record($cldb_almox->sql_query_file($m97_db_almox, "m91_depto"));
      if ($cldb_almox->numrows>0){
        db_fieldsmemory($result_deptoalmox,0);
        $depto_almox = $m91_depto;
      }

      $result_matestoque=$clmatestoque->sql_record($clmatestoque->sql_query_almox(null,"sum(m70_valor)as vlrtot,sum(m70_quant)as quantot",null,"m70_codmatmater=$codmater and m70_coddepto = $depto_almox", "", "", $depto_almox));
      if ($clmatestoque->numrows!=0){
        db_fieldsmemory($result_matestoque,0);
      }

      if (@$iTipoControleCustos > 0) {

        echo "<tr><td>";
        db_ancora("<b>Centro de de Custo:",'js_adicionaCentroCusto()', 1);
        echo "</td><td>";
        db_input('cc08_sequencial',10,$Icc08_sequencial,true,"text", 3);
        db_input('cc08_descricao',40,$Im60_descr,true,"text",3);
        echo "</td></tr>" ;

      }

      if ($oParam->m90_mostrarsaldosolictransf == 2) { // não mostra o saldo disponível
        $sEstiloLinha = 'display: none;';
      } else {
        $sEstiloLinha = '';
      }
      ?>
      <tr style="<?=$sEstiloLinha?>">
        <td title='Quant. Disponível' >
          <b>Quantidade Disponível:</b>
        </td>
        <td>
          <?
          if (isset($quantot)&&($quantot!="")){
            $quant_disp=$quantot;
          } else {
            $quant_disp='0';
          }

          db_input('quant_disp', 10, $Im98_quant, true, 'text', 3, "");
          ?>
        </td>
      </tr>
    <?}?>
    <tr>
      <td nowrap title="<?=@$Tm98_obs?>">
        <?=@$Lm98_obs?>
      </td>
      <td>
        <?
        db_textarea('m98_obs',0,50,$Im98_obs,true,'text',$db_opcao,"")
        ?>
      </td>
    </tr>
    <tr>
      <td colspan=2 align=center>
        <?
        if (!isset($opcao) && isset($db_opcao) && $db_opcao==3){
          $db_botao=false;
        }
        ?>
        <?db_input('m91_depto',10,@$m91_depto,true,'hidden',3,"");?>
        <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=(($db_opcao==1||$db_opcao==2||$db_opcao==22)&&$testquan=='f'?"onclick='return js_testaquant();'":"")?>  >
        <?if ($db_opcao==1||$db_opcao==2){ ?>
          <input name='pesquisar' type='button' id='emite' value='Emite Solicitação' onclick='js_abre();' <?=($db_botao==false?"disabled":"")?>>
        <?}?>
        <?
        $clmatpedidoitem->numrows = 0;
        if (!empty($m97_sequencial)) {
          $resul1=$clmatpedidoitem->sql_record($clmatpedidoitem->sql_query(null,'*',null,"m98_matpedido= {$m97_sequencial}"));
        }
        if ($clmatpedidoitem->numrows==0){?>
          <script>document.form1.emite.disabled=true;</script>
        <?}?>
      </td>
    </tr>
  </table>
  <table>
    <tr>
      <td valign="top">
        <?
        $chavepri= array("m97_sequencial"=>@$m97_sequencial,"m98_sequencial"=>@$m98_sequencial,"m98_obs"=>@$m98_obs,"m98_quant"=>@$m98_quant,"m98_matunid"=>@$m98_matunid,"m61_descr"=>@$m61_descr,"m98_matmater"=>@$m98_matmater,"m60_descr"=>@$m60_descr);
        $cliframe_alterar_excluir->chavepri=$chavepri;

        if ( !empty($m97_sequencial) ){

          $cliframe_alterar_excluir->sql = $clmatpedidoitem->sql_query(null,'*',null,"m98_matpedido=$m97_sequencial");
          $cliframe_alterar_excluir->sql_disabled = $clmatpedidoitem->sql_query_anulacao(null,'*',null,"m98_matpedido=$m97_sequencial and (m99_codigo is not null OR m101_sequencial is not null) ");
        }
        $cliframe_alterar_excluir->campos  ="m98_matmater,m60_descr,m98_quant,m61_descr,m98_obs";
        $cliframe_alterar_excluir->legenda="ITENS SOLICITADOS";
        $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
        $cliframe_alterar_excluir->textocabec ="darkblue";
        $cliframe_alterar_excluir->textocorpo ="black";
        $cliframe_alterar_excluir->fundocabec ="#aacccc";
        $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
        $cliframe_alterar_excluir->iframe_width ="710";
        $cliframe_alterar_excluir->iframe_height ="130";
        $lib=1;
        if ($db_opcao==3||$db_opcao==33){
          $lib=4;
        }
        $cliframe_alterar_excluir->opcoes = @$lib;
        $cliframe_alterar_excluir->iframe_alterar_excluir(@$db_opcao);
        db_input('db_opcao',10,'',true,'hidden',3);
        ?>
      </td>
    </tr>
  </table>
</form>
<script>

  // Autocomplete do medicamento
  oAutoComplete = new dbAutoComplete(document.form1.m60_descr, 'mat4_autonome.RPC.php?iCodigo=1');
  oAutoComplete.setTxtFieldId(document.getElementById('m98_matmater'));
  oAutoComplete.show();
  oAutoComplete.setCallBackFunction(function(id, label) {

    document.form1.m98_matmater.value = id;
    document.form1.m60_descr.value    = label;
    document.form1.submit();

  });

  function js_abre(){
    obj = document.form1;
    var departamento = <?=db_getsession("DB_coddepto")?>;
    var nomedepto    = '<?=db_getsession("DB_nomedepto")?>';
    query='';
    query += "&ini="+obj.m97_sequencial.value;
    query += "&fim="+obj.m97_sequencial.value;
    query += "&tObserva="+obj.m98_obs.value;
    query += "&codalmox="+obj.m91_depto.value;
    query += "&nomedepto="+nomedepto;
    query += "&departamento="+departamento;

    jan = window.open('mat2_matpedido001.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
  function js_pesquisa_codmater(mostra){
    if (mostra==true){
      js_OpenJanelaIframe('','db_iframe_mater','func_matmater.php?funcao_js=parent.js_mostra1|m60_codmater|m60_descr','Pesquisa',true);
    } else {
      if (document.form1.m98_matmater.value != ''){
        js_OpenJanelaIframe('','db_iframe_mater','func_matmater.php?pesquisa_chave='+document.form1.m98_matmater.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
      } else {
        document.form1.m60_descr.value = "";
      }
    }
  }
  function js_mostra(chave,erro){
    document.form1.m60_descr.value = chave;
    if (erro==true){
      document.form1.m98_matmater.focus();
      document.form1.m98_matmater.value = '';
    } else {
      document.form1.m60_descr.value=chave;
      document.form1.submit();
    }
  }
  function js_mostra1(chave1,chave2){
    document.form1.m98_matmater.value = chave1;
    document.form1.m60_descr.value = chave2;
    db_iframe_mater.hide();
    document.form1.submit();
  }
  function js_testaquant(){
    <?
    if ($oParam->m90_validarsaldosolictransf == 2) { // não valida o saldo disponível
      echo 'return true;';
    }
    ?>
    m98_quant=new Number(document.form1.m98_quant.value );
    quant_disp= new Number(document.form1.quant_disp.value );

    if ( quant_disp == 0 ) {
      alert('Quantidade disponível igual a zero!');
      return false;
    } else {
      if (m98_quant<=quant_disp){
        return true;
      } else {
        alert('Informe uma Quantidade Válida!!');
        document.form1.m98_quant.value="";
        document.form1.m98_quant.focus();
        return false;
      }
    }

  }
</script>