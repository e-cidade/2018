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

//MODULO: patrim
include("classes/db_situabens_classe.php");
include("dbforms/db_classesgenericas.php");
$clsituabens = new cl_situabens;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clbenstransfcodigo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("t52_descr");
$clrotulo->label("t52_ident");
$clrotulo->label("t70_descr");
$clrotulo->label("t30_descr");
$clrotulo->label("t31_divisao");
?>
<script>
  function js_troca(){

    obj=document.createElement('input');
    obj.setAttribute('name','troca');
    obj.setAttribute('type','hidden');
    obj.setAttribute('value','ok');

    document.form1.appendChild(obj);
    document.form1.submit();
  }
  function js_muda_situacao(situacao){
    document.form1.t95_situac.value      = situacao;
    document.form1.t95_situacdescr.value = situacao;
  }
</script>
<?
if(isset($db_opcaoal)){
  $db_opcao=33;
  $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
  $db_botao=true;
  $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
  $db_opcao = 3;
  $db_botao=true;
}else{
  $db_opcao = 1;
  $db_botao=true;
  if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false )){
    $t95_codbem = "";
    $t95_situac = "";
    $t95_histor = "";
    $t52_descr = "";
    $t70_descr = "";
  }
}
$idus = db_getsession("DB_id_usuario");
$iddepart = db_getsession("DB_coddepto");
$func_conf = "";
if(isset($db_param) && $db_param=='int'){
  $func_conf = "func_bensconfirmacao001.php";
}else if(isset($db_param) && $db_param=='ext'){
  $func_conf = "func_bensconfirmacao.php";
}
?>
<form name="form1" method="post" action="">
  <center>

    <fieldset style="margin-top: 30px; width: 700px;">
      <legend><strong>Itens da Transferência</strong></legend>
      <table border="0" style="width: 100%">
        <tr>
          <td nowrap title="<?=@$Tt95_codtran?>" style="width: 100px">
            <label class="bold" id="lbl_t95_codtran" for="t95_codtran"><?=(@$Lt95_codtran)?></label>
          </td>
          <td>
            <?
            db_input('depto',40,"",true,'hidden',3,'');
            db_input('t95_codtran',8,$It95_codtran,true,'text',3,"");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tt95_codbem?>">
            <label class="bold" id="lbl_t95_codbem" for="t95_codbem"><?
              db_ancora("Bem:", "js_pesquisat95_codbem(true);",($db_opcao==2?3:$db_opcao));
              ?></label>
          </td>
          <td>
            <?
            db_input('t95_codbem',8,$It95_codbem,true,'text',($db_opcao==2?3:$db_opcao)," onchange='js_pesquisat95_codbem(false);'")
            ?>
            <?
            db_input('t52_descr',40,$It52_descr,true,'text',3,'')
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tt95_codbem?>">
            <label class="bold" id="lbl_t52_ident" for="t52_ident"><?
              db_ancora(@$Lt52_ident,"js_pesquisat52_placa(true);",($db_opcao==2?3:$db_opcao));
              ?></label>
          </td>
          <td>
            <?
            $Nt52_ident = null;
            db_input('t52_ident',8,$It52_ident,true,'text',($db_opcao == 2 ? 3 : $db_opcao)," onchange='js_pesquisat52_placa(false);'")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tt95_situac?>">
            <label class="bold" id="lbl_t95_situac" for="t95_situac">Situação:</label>
          </td>
          <td>
            <?
            $result_sit=$clsituabens->sql_record($clsituabens->sql_query_file());
            db_selectrecord('t95_situac',$result_sit,true,'text',$db_opcao);
            if (isset($t95_codbem)&&trim($t95_codbem)!=""&&($db_opcao==1||$db_opcao==11)){
              $res_situacao = $clsituabens->sql_record($clsituabens->sql_query_histbem(null,"t70_situac,t70_descr",null,"t56_codbem=$t95_codbem order by t56_data desc, t56_histbem desc limit 1"));
              if ($clsituabens->numrows > 0){
                db_fieldsmemory($res_situacao,0);
                $t95_situac      = $t70_situac;
              }
            }
            if(isset($t95_situac)&&$t95_situac!=""){
              echo "<script>js_muda_situacao($t95_situac);</script>";
            }
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="Divisão do Depart.">
            <label class="bold" id="lbl_t31_divisao" for="t31_divisao">Divisão de Destino:</label>
          </td>
          <td>
            <?
            if (isset($db_opcao)&&$db_opcao!=3) { ?>
              <select name='t31_divisao' id='t31_divisao'>
                <option value=''>Nenhuma</option>
                <?
                $result = $cldepartdiv->sql_record($cldepartdiv->sql_query_file(null,
                                                                                "t30_codigo, t30_descr",
                                                                                null,
                                                                                "t30_depto = {$depto}"));
                for ($y = 0; $y < $cldepartdiv->numrows; $y++) {
                  db_fieldsmemory($result,$y);
                  ?>
                  <option value=<?=@$t30_codigo?> <?=(isset($t31_divisao) && $t31_divisao == $t30_codigo ? "selected" : "") ?> > <?=@$t30_descr?></option>
                <? } ?>
              </select>
            <?
            } else {
              db_input('t31_divisao',8,$It31_divisao,true,'text',3,'');
              db_input('t30_descr',40,$It30_descr,true,'text',3,'');
            } ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tt95_histor?>" colspan="2">
            <fieldset style="width: 97%">
              <legend class="bold"><label id="lbl_t95_histor" for="t95_histor"><?=@$Lt95_histor?></label></legend>
              <?php
              db_textarea('t95_histor',3,48,$It95_histor,true,'text',$db_opcao,"")
              ?>
            </fieldset>
          </td>
        </tr>
      </table>

    </fieldset>

    <div style="margin-top: 10px;">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
      <input name="novo" type="button" id="" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </div>


    <table style="margin-top: 10px;">
      <tr>
        <td valign="top"  align="center">
          <?
          $chavepri= array("t95_codtran"=>@$t95_codtran,"t95_codbem"=>@$t95_codbem,"t31_codigo"=>@$t31_codigo);
          $cliframe_alterar_excluir->chavepri=$chavepri;
          $cliframe_alterar_excluir->sql     = $clbenstransfcodigo->sql_query_div($t95_codtran,null,"distinct t95_codtran, t95_codbem, t52_ident, t52_descr, t95_situac,t95_histor,t31_codigo,t30_descr");
          $cliframe_alterar_excluir->campos  = "t95_codtran,t95_codbem, t52_ident, t52_descr, t95_situac,t95_histor,t30_descr";
          $cliframe_alterar_excluir->legenda = "BENS LANÇADOS";
          $cliframe_alterar_excluir->iframe_height = "200";
          $cliframe_alterar_excluir->iframe_width  = "700";
          $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
          ?>
        </td>
      </tr>
    </table>



  </center>
</form>
<script>

  var sPlacaParametro = '';
  $('t52_descr').style.width = "85%";
  var oTextArea = $('t95_histor');
  oTextArea.style.width = '100%';
  oTextArea.style.height = '60px';

  var oComboCodigoSituacao = $('t95_situac');
  oComboCodigoSituacao.style.width = '13%';

  var oComboDescricaoSituacao = $('t95_situacdescr');
  oComboDescricaoSituacao.style.width = '86%';

  $('t31_divisao').style.width = '100%';

  function js_cancelar(){
    var opcao = document.createElement("input");
    opcao.setAttribute("type","hidden");
    opcao.setAttribute("name","novo");
    opcao.setAttribute("value","true");
    document.form1.appendChild(opcao);
    document.form1.submit();
  }
  function js_pesquisat95_codbem(mostra){
    if(mostra == true){
      js_OpenJanelaIframe('top.corpo.iframe_benstransfcodigo','db_iframe_bensconfirmacao','<?=($func_conf)?>?funcao_js=parent.js_mostrabens1|t52_bem|t52_descr|t52_ident&chave_id_usuario=<?=$idus?>&chave_t93_depart=<?=$iddepart?>&db_param=<?=($db_param)?>','Pesquisa',true);
    }else{
      if(document.form1.t95_codbem.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_benstransfcodigo','db_iframe_bensconfirmacao','<?=($func_conf)?>?pesquisa_chave='+document.form1.t95_codbem.value+'&funcao_js=parent.js_mostrabens&chave_id_usuario=<?=$idus?>&chave_t93_depart=<?=$iddepart?>&db_param=<?=($db_param)?>','Pesquisa',false);
      }else{
        document.form1.t52_descr.value = '';
        document.form1.t52_ident.value = '';
      }
    }
  }
  function js_pesquisat52_placa(mostra){
    if(mostra == true){
      js_OpenJanelaIframe('top.corpo.iframe_benstransfcodigo','db_iframe_bensconfirmacao','<?=($func_conf)?>?funcao_js=parent.js_mostrabens1|t52_bem|t52_descr|t52_ident&chave_id_usuario=<?=$idus?>&chave_t93_depart=<?=$iddepart?>&db_param=<?=($db_param)?>','Pesquisa',true);
    }else{
      if(document.form1.t52_ident.value != ''){

        sPlacaParametro = document.form1.t52_ident.value;
        js_OpenJanelaIframe('top.corpo.iframe_benstransfcodigo','db_iframe_bensconfirmacao','<?=($func_conf)?>?pesquisa_chave_placa='+document.form1.t52_ident.value+'&funcao_js=parent.js_mostrabens&chave_id_usuario=<?=$idus?>&chave_t93_depart=<?=$iddepart?>&db_param=<?=($db_param)?>','Pesquisa',false);
      }else{
        sPlacaParametro = '';
        document.form1.t95_codbem.value = '';
        document.form1.t52_descr.value  = '';
        document.form1.t52_ident.value  = '';
      }
    }
  }
  function js_mostrabens(iCodigoBem, sDescricaoBem, sPlacaBem, erro){

    document.form1.t95_codbem.value = iCodigoBem;
    document.form1.t52_descr.value = sDescricaoBem.replace('()', "("+sPlacaParametro+")");
    document.form1.t52_ident.value = sPlacaBem;

    if(erro==true){

      document.form1.t95_codbem.value = '';
      document.form1.t52_ident.value = '';
      if (sPlacaParametro != '') {
        document.form1.t52_ident.focus();
      } else {
        document.form1.t95_codbem.focus();
      }
    }

    if(erro==false){
      js_troca();
    }
  }
  function js_mostrabens1(iCodigoBem, sDescricaoBem, sPlacaBem){
    document.form1.t95_codbem.value = iCodigoBem;
    document.form1.t52_descr.value = sDescricaoBem;
    document.form1.t52_ident.value = sPlacaBem;
    db_iframe_bensconfirmacao.hide();
    js_troca();
  }
  function js_pesquisat95_situac(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo.iframe_benstransfcodigo','db_iframe_situabens','func_situabens.php?funcao_js=parent.js_mostrasituabens1|t70_situac|t70_descr','Pesquisa',true);
    }else{
      if(document.form1.t95_situac.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_benstransfcodigo','db_iframe_situabens','func_situabens.php?pesquisa_chave='+document.form1.t95_situac.value+'&funcao_js=parent.js_mostrasituabens','Pesquisa',false);
      }else{
        document.form1.t70_descr.value = '';
      }
    }
  }
  function js_mostrasituabens(chave,erro){
    document.form1.t70_descr.value = chave;
    if(erro==true){
      document.form1.t95_situac.focus();
      document.form1.t95_situac.value = '';
    }
  }
  function js_mostrasituabens1(chave1,chave2){
    document.form1.t95_situac.value = chave1;
    document.form1.t70_descr.value = chave2;
    db_iframe_situabens.hide();
  }
</script>