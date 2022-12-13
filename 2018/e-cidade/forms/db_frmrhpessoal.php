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

//MODULO: pessoal
$clrhpessoal->rotulo->label();
$clrhpesfgts->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("rh06_descr");
$clrotulo->label("rh21_descr");
$clrotulo->label("rh08_descr");
$clrotulo->label("rh18_descr");
$clrotulo->label("rh37_descr");
$clrotulo->label("r70_descr");
$clrotulo->label("r59_descr");
$clrotulo->label("db90_descr");
$clrotulo->label("rh50_oid");
$clrotulo->label("rh116_descricao");
$clrotulo->label("rh164_datafim");
$clrotulo->label("rh01_registrapontoeletronico");

$rh01_instit = db_getsession("DB_instit");

$mostra = 1;
$registparam = "";
if($db_opcao == 1){

  $result_parametros = $clrhcfpessmatr->sql_record($clrhcfpessmatr->sql_query_file(null,"rh13_matricula",null,"rh13_instit=".db_getsession("DB_instit")));
  if($clrhcfpessmatr->numrows > 0){
    db_fieldsmemory($result_parametros,0);
    if(trim($rh13_matricula)!="" && $rh13_matricula!=0){
      $mostra = 3;
    }
  }
}

if($db_opcao==1) {

  $db_action="pes1_rhpessoal004.php";
} else if($db_opcao==2||$db_opcao==22) {

  $mostra = 3;
  $db_action="pes1_rhpessoal005.php";
} else if($db_opcao==3||$db_opcao==33) {

  $mostra = 3;
  $db_action="pes1_rhpessoal006.php";
}

$optionDatasContratosEmergenciais = ($db_opcao == 1 ? $db_opcao : 3);

if( ($db_opcao == 2 || $db_opcao == 22) && isset($rh01_regist) && $rh01_regist != "" ) {

  try{

    $clrhcontratoemergencialrenovacao    = new cl_rhcontratoemergencialrenovacao;
    $sWhereContratoEmergencialRenovacoes = " rh163_matricula = {$rh01_regist}";
    $sSqlContratosEmergenciais           = $clrhcontratoemergencialrenovacao->sql_query(null,"count(*) as totalcontratosemergenciaisparamatricula", null, $sWhereContratoEmergencialRenovacoes);
    $rsContratosEmergenciais             = db_query($sSqlContratosEmergenciais);

    if(!$rsContratosEmergenciais) {
      throw new DbException("Error ao buscar total de contratos emergenciais para a matrícula");
    }

    if(pg_num_rows($rsContratosEmergenciais) > 0) {
      $totalContratosEmergenciaisParaMatricula = db_utils::fieldsMemory($rsContratosEmergenciais, 0)->totalcontratosemergenciaisparamatricula;
    }

    if($totalContratosEmergenciaisParaMatricula <= 1) {
      $optionDatasContratosEmergenciais = 2;
    }

  } catch (Exception $oErro) {
    $sMessageErro = $oErro->getMessage();
    echo "<script type=\"text/javascript\">alert(\"$sMessageErro\");</script>";
  }
}
?>
<form name="form1" method="post" action="<?=$db_action?>">
  <input type="hidden" name="rh01_funcao" value="<?=@$rh02_funcao?>">

  <table border='0'>
    <tr>
      <td>
        <fieldset>
          <legend align="left"><b>DADOS PESSOAIS</b></legend>
          <table>
            <tr>
              <td nowrap title="<?=@$Trh50_oid?>" rowspan="7" id='fotofunc'>
                <?
                $regist = "";
                if(isset($rh01_numcgm)){
                  $regist = $rh01_numcgm;
                }
                db_foto($regist,$db_opcao,"js_alterafoto();");
                ?>
              </td>
              <td nowrap title="<?=@$Trh01_regist?>">
                <?=@$Lrh01_regist?>
              </td>
              <td nowrap>
                <?
                db_input('rhimp',6,0,true,'hidden',3,"");
                db_input('rh01_regist',6,$Irh01_regist,true,'text',$mostra,"");
                db_input('rh01_instit',6,$Irh01_instit,true,'hidden',3,"");
                db_input('registparam',6,0,true,'hidden',3,"");
                db_input('localrecebefoto',6,0,true,'hidden',3,"");

                ?>



              </td>
              <td nowrap title="<?=@$Trh01_sexo?>">
                <?=@$Lrh01_sexo?>
              </td>
              <td nowrap>
                <?
                $arr_sexo = array('M' => 'Masculino','F'=>'Feminino');
                db_select("rh01_sexo",$arr_sexo,true,$db_opcao,"");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh01_numcgm?>">
                <?
                db_ancora(@$Lrh01_numcgm,"js_pesquisarh01_numcgm(true);",$db_opcao);
                ?>
              </td>
              <td nowrap>
                <?
                db_input('rh01_numcgm',6,$Irh01_numcgm,true,'text',$db_opcao,"onchange='js_pesquisarh01_numcgm(false);' tabIndex='1'")
                ?>
                <?
                db_input('z01_nome',33,$Iz01_nome,true,'text',3,'')
                ?>
                <input type='button' value='alterar' onclick="js_alterarCgm($F('rh01_numcgm'))">
              </td>
              <td nowrap title="<?=@$Trh01_raca?>">
                <?
                db_ancora(@$Lrh01_raca,"js_pesquisarh01_raca(true);",3);
                ?>
              </td>
              <td nowrap>
                <?
                $result_raca = $clrhraca->sql_record($clrhraca->sql_query_file());
                db_selectrecord("rh01_raca",$result_raca,"",$db_opcao," tabIndex='2'");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh01_instru?>">
                <?
                db_ancora(@$Lrh01_instru,"js_pesquisarh01_instru(true);",3);
                ?>
              </td>
              <td nowrap>
                <?
                $result_instru = $clrhinstrucao->sql_record($clrhinstrucao->sql_query_file());
                db_selectrecord("rh01_instru",$result_instru,"",$db_opcao);
                ?>
              </td>
              <td nowrap title="<?=@$Trh01_estciv?>">
                <?
                db_ancora(@$Lrh01_estciv,"js_pesquisarh01_estciv(true);",3);
                ?>
              </td>
              <td nowrap>
                <?
                $result_estciv = $clrhestcivil->sql_record($clrhestcivil->sql_query_file());
                db_selectrecord("rh01_estciv",$result_estciv,"",$db_opcao);
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh01_nacion?>">
                <?
                db_ancora(@$Lrh01_nacion,"js_pesquisarh01_nacion(true);",3);
                ?>
              </td>
              <td nowrap>
                <?
                if(!isset($rh01_nacion)){
                  $rh01_nacion = 10;
                }
                $result_nacion = $clrhnacionalidade->sql_record($clrhnacionalidade->sql_query_file(null, "*", "rh06_nacionalidade"));
                db_selectrecord("rh01_nacion",$result_nacion,"",$db_opcao,"","","","","js_mudaanoche(this.value);");
                ?>
              </td>
              <td nowrap title="<?=@$Trh01_anoche?>">
                <?=@$Lrh01_anoche?>
              </td>
              <td nowrap>
                <?
                $iMaxLen_rh01_anoche = 4;
                db_input('rh01_anoche',4,$Irh01_anoche,true,'text',$db_opcao,"", "", "", "", $iMaxLen_rh01_anoche);
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?=@$Trh01_natura?>">
                <?=@$Lrh01_natura?>
              </td>
              <td nowrap>
                <?
                db_input('rh01_natura',42,$Irh01_natura,true,'text',$db_opcao,"")
                ?>
              </td>
              <td nowrap title="<?=@$Trh01_nasc?>">
                <?=@$Lrh01_nasc?>
              </td>
              <td nowrap>
                <?
                db_inputdata('rh01_nasc',@$rh01_nasc_dia,@$rh01_nasc_mes,@$rh01_nasc_ano,true,'text',$db_opcao,"")
                ?>
              </td>
            </tr>

            <tr>
              <td nowrap title="<?php echo $Trh01_rhsindicato; ?>">
                <?php db_ancora($Lrh01_rhsindicato, 'js_pesquisaSindicato()', $db_opcao); ?>
              </td>
            <td nowrap colspan="3">
                <?php db_input('rh01_rhsindicato', 10, null, true, 'hidden', 3); ?>
                <?php db_input('rh116_descricao', 42, $Irh116_descricao, true, 'text', 3); ?>
              </td>
            </tr>

          </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td>
        <fieldset>
          <legend align="left"><b>DADOS ADMISSIONAIS</b></legend>
          <center>
            <table border="0">
              <tr>
                <td nowrap title="<?=@$Trh01_admiss?>">
                  <?=@$Lrh01_admiss?>
                </td>
                <td colspan="2" nowrap>
                  <?
                  db_inputdata('rh01_admiss',@$rh01_admiss_dia,@$rh01_admiss_mes,@$rh01_admiss_ano,true,'text',$optionDatasContratosEmergenciais,"")
                  ?>
                </td>
                <td nowrap title="<?=@$Trh01_tipadm?>">
                  <?=@$Lrh01_tipadm?>
                </td>
                <td colspan="2" nowrap>
                  <?
                  $h01_tipadm = array(
                    1 => 'Admissao do 1o emprego',
                    2 => 'Admissao c/ emprego anterior',
                    3 => 'Transf de empreg s/ onus p/ a cedente',
                    4 => 'Transf de empreg c/ onus p/ a cedente'
                  );
                  db_select("rh01_tipadm",$h01_tipadm,true,$db_opcao,"");
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Trh01_vale?>" align="left">
                  <?=@$Lrh01_vale?>
                </td>
                <td colspan="2" nowrap>
                  <?$clrotulo->label("rh08_descr");
                  $arr_vale = array('S' => 'Sim','N'=>'Não');
                  db_select("rh01_vale",$arr_vale,true,$db_opcao,"");
                  ?>
                </td>
                <td nowrap title="<?=@$Trh01_ponto?>" align="right">
                  <?=@$Lrh01_ponto?>
                </td>
                <td nowrap>
                  <?
                  db_input('rh01_ponto',6,$Irh01_ponto,true,'text',$db_opcao,"")
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Trh01_clas1?>" align="left">
                  <?=@$Lrh01_clas1?>
                </td>
                <td nowrap colspan="2">
                  <?
                  db_input('rh01_clas1',6,$Irh01_clas1,true,'text',$db_opcao,"")
                  ?>
                </td>
                <td nowrap title="<?=@$Trh01_clas2?>" align="right">
                  <?=@$Lrh01_clas2?>
                </td>
                <td nowrap>
                  <?
                  db_inputdata('rh01_clas2',@$rh01_clas2_dia,@$rh01_clas2_mes,@$rh01_clas2_ano,true,'text',$db_opcao,"")
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Trh01_trienio?>">
                  <?=@$Lrh01_trienio;?>
                </td>
                <td nowrap>
                  <?
                  db_inputdata('rh01_trienio',@$rh01_trienio_dia,@$rh01_trienio_mes,@$rh01_trienio_ano,true,'text',$db_opcao,"")
                  ?>
                </td>
                <td></td>
                <td nowrap title="<?=@$Trh01_progres?>" align="right">
                  <?=@$Lrh01_progres;?>
                </td>
                <td nowrap>
                  <?
                  db_inputdata('rh01_progres',@$rh01_progres_dia,@$rh01_progres_mes,@$rh01_progres_ano,true,'text',$db_opcao,"")
                  ?>
                </td>
              </tr>
              <tr>
                <td colspan="6">
                  <fieldset><legend>
                      <?= $Lrh01_observacao?>
                    </legend>
                    <?
                    db_textarea('rh01_observacao', 2, 10, $Irh01_observacao,true,'text', $db_opcao);
                    ?>
                  </fieldset>
                </td>
              </tr>
              <tr>
                <td colspan="6">
                  <fieldset class="fieldset-hr">
                  </fieldset>
                </td>
              </tr>
              <tr>
                <td nowrap title="Contrato Emergencial" align="left">
                  <strong>Contrato Emergencial:</strong>
                </td>
                <td colspan="2">
                  <?
                db_input('contratoEmergencial', 1, '', true, 'checkbox', $optionDatasContratosEmergenciais, 'onclick="js_validaContratoEmergencial(this)"');
                  ?>
                </td>
                <td nowrap title="<?=@$Trh164_datafim?>" id="labelTerminoContratoEmergencial" style="visibility: <?=($visibilityContratoEmergencial == true ? 'visible' : 'hidden') ?>" align="right">
                  <?=@$Lrh164_datafim;?>
                </td>
                <td nowrap title="<?=@$Trh164_datafim?>" id="terminoContratoEmergencial" style="visibility: <?=($visibilityContratoEmergencial == true ? 'visible' : 'hidden') ?>" colspan="2">
                  <?
                  db_inputdata('rh164_datafim',@$rh164_datafim_dia,@$rh164_datafim_mes,@$rh164_datafim_ano,true,'text',$optionDatasContratosEmergenciais,"");
                  $hasContratoEmergencial = (isset($contratoEmergencial) && $contratoEmergencial) ? $contratoEmergencial : false;
                  db_input('hasContratoEmergencial', 1, '', true, 'hidden', 1, '');
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="rh01_registrapontoeletronico">
                    <?=$Lrh01_registrapontoeletronico;?>
                  </label>
                </td>
                <td>
                  <?php
                  $aOptions = array('t' => 'Sim', 'f'=>'Não');
                  db_select("rh01_registrapontoeletronico", $aOptions, true, $db_opcao, "");
                  ?>
                </td>
              </tr>
            </table>
          </center>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td align="center" nowrap>
        <fieldset>
          <legend align="left"><b>FGTS</b></legend>
          <table width="60%" border="0">
            <tr>
              <td nowrap title="<?=@$Trh15_data?>">
                <?
                db_ancora(@$Lrh15_data,"",3);
                ?>
              </td>
              <td colspan="3" nowrap>
                <?
                db_inputdata('rh15_data',@$rh15_data_dia,@$rh15_data_mes,@$rh15_data_ano,true,'text',$db_opcao,"")
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh15_banco?>">
                <?
                db_ancora(@$Lrh15_banco,"js_pesquisarh15_banco(true);",$db_opcao);
                ?>
              </td>
              <td colspan="3" nowrap>
                <?
                db_input('rh15_banco',5,$Irh15_banco,true,'text',$db_opcao,"onchange='js_pesquisarh15_banco(false);'")
                ?>
                <?
                db_input('db90_descr',40,$Idb90_descr,true,'text',3,"")
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh15_agencia?>">
                <?=@$Lrh15_agencia?>
              </td>
              <td nowrap>
                <?
                db_input('rh15_agencia',5,$Irh15_agencia,true,'text',$db_opcao,"")
                ?>
              </td>
              <td nowrap title="<?=@$Trh15_agencia_d?>">
                <?=@$Lrh15_agencia_d?>
              </td>
              <td nowrap>
                <?
                db_input('rh15_agencia_d',1,$Irh15_agencia_d,true,'text',$db_opcao,"")
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Trh15_contac?>">
                <?=@$Lrh15_contac?>
              </td>
              <td nowrap>
                <?
                db_input('rh15_contac',15,$Irh15_contac,true,'text',$db_opcao,"")
                ?>
              </td>
              <td nowrap title="<?=@$Trh15_contac_d?>">
                <?=@$Lrh15_contac_d?>
              </td>
              <td nowrap>
                <?
                db_input('rh15_contac_d',1,$Irh15_contac_d,true,'text',$db_opcao,"")
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
  </table>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_verificacampos();">
  <input name="pesquisar" type="button" id="pesquisar" value="<?=$db_opcao==1?"Importar":"Pesquisar"?>" onclick="js_pesquisa();">
</form>
<script>
  document.getElementById('rh01_observacao').style.width='100%';
  function js_verificacampos(){

    <?
    if($db_opcao == 1 || $db_opcao == 2){
    ?>

    if(document.form1.rh01_numcgm.value == ""){

      alert("Informe o CGM do funcionário.");
      document.form1.rh01_numcgm.focus();
      return false;
    }

    if( (document.form1.rh01_anoche.value.length < 4 && document.form1.rh01_nacion.value != 10) ||
      (document.form1.rh01_anoche.value == ""      && document.form1.rh01_nacion.value != 10) )
    {

      alert("Informe o ano de chegada com 04 caracteres.");
      document.form1.rh01_anoche.focus();
      return false;

    } else if (document.form1.rh01_nacion.value == 10) {
      document.form1.rh01_anoche.value = 0;
    }

    if ( document.form1.rh01_anoche.value > document.form1.rh01_admiss_ano.value ) {

      alert("O ano de chegada não pode ser maior que o ano de admissão.");
      document.form1.rh01_anoche.focus();
      return false;

    }

    if(document.form1.rh01_nasc_dia.value == "" || document.form1.rh01_nasc_mes.value == "" || document.form1.rh01_nasc_ano.value == ""){

      alert("Informe a data de nascimento.");
      document.form1.rh01_nasc_dia.focus();
      return false;
    }

    if(document.form1.rh01_admiss_dia.value == "" || document.form1.rh01_admiss_mes.value == "" || document.form1.rh01_admiss_ano.value == ""){

      alert("Informe a data de admissão.");
      document.form1.rh01_admiss_dia.focus();
      return false;
    }

    if(document.form1.rh01_rhsindicato.value == ""){

      alert("Informe o sindicato.");
      document.form1.rh01_admiss_dia.focus();
      return false;
    }

    <?
    if($db_opcao == 1){
    ?>

    if(!js_validaTerminoContratoEmergencial()) {
      return false;
    }

    <?
    }
    ?>

    return true;
    <?
    }
    ?>
  }
  function js_mudaanoche(valor){
    if(valor != 10){

      document.form1.rh01_anoche.style.backgroundColor='';
      document.form1.rh01_anoche.readOnly = false;

    }else{

      document.form1.rh01_anoche.style.backgroundColor='#DEB887';
      document.form1.rh01_anoche.readOnly = true;
      document.form1.rh01_anoche.value="0";
    }

  }
  function js_alterafoto(){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoal','db_iframe_localfoto','func_localfoto.php','Foto do funcionário',true,0);
  }
  function js_pesquisarh15_banco(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoal','db_iframe_db_bancos','func_db_bancos.php?funcao_js=parent.js_mostrabancos1|db90_codban|db90_descr','Pesquisa',true,0);
    }else{
      if(document.form1.rh15_banco.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoal','db_iframe_db_bancos','func_db_bancos.php?pesquisa_chave='+document.form1.rh15_banco.value+'&funcao_js=parent.js_mostrabancos','Pesquisa',false,0);
      }else{
        document.form1.db90_descr.value = '';
      }
    }
  }
  function js_mostrabancos(chave,erro){
    document.form1.db90_descr.value = chave;
    if(erro==true){
      document.form1.rh15_banco.focus();
      document.form1.rh15_banco.value = '';
    }
  }
  function js_mostrabancos1(chave1,chave2){
    document.form1.rh15_banco.value = chave1;
    document.form1.db90_descr.value = chave2;
    db_iframe_db_bancos.hide();
  }
  function js_pesquisarh01_numcgm(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoal','func_nome','func_nome.php?campos='+'cgm.z01_numcgm\, z01_nome\,trim\(z01_cgccpf\) as z01_cgccpf\, trim\(z01_ender\) as z01_ender\, z01_munic\, z01_uf\, z01_cep\, z01_email\,z01_sexo\,z01_nasc'+'&testanome=true&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome|z01_sexo|z01_nasc','Pesquisa',true,'0');
    }else{
      if(document.form1.rh01_numcgm.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoal','func_nome','func_nome.php?novosvalores=|z01_numcgm|z01_nome|z01_sexo|z01_nasc&testanome=true&pesquisa_chave='+document.form1.rh01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false,'0');
      }else{
        document.form1.z01_nome.value = '';
      }
    }
  }
  function js_mostracgm(erro,chave1,chave2,chave3,chave4){

    document.form1.rh01_numcgm.value = chave1;
    document.form1.z01_nome.value = chave2;
    if(chave3 != ""){
      document.form1.rh01_sexo.value = chave3;
    }
    if(chave4 != ""){
      $('rh01_nasc_dia').setValue(chave4.substr(8,2));
      $('rh01_nasc_mes').setValue(chave4.substr(5,2));
      $('rh01_nasc_ano').setValue(chave4.substr(0,4));
      $('rh01_nasc').setValue($F('rh01_nasc_dia')+"/"+$F('rh01_nasc_mes')+"/"+$F('rh01_nasc_ano'));
    }
    if(erro==true){
      document.form1.rh01_numcgm.focus();
      document.form1.rh01_numcgm.value = '';
    }
  }
  function js_mostracgm1(chave1,chave2,chave3,chave4){

    document.form1.rh01_numcgm.value = chave1;
    document.form1.z01_nome.value = chave2;
    if(chave3 != ""){
      document.form1.rh01_sexo.value = chave3;
    }
    if(chave4 != ""){
      $('rh01_nasc_dia').setValue(chave4.substr(8,2));
      $('rh01_nasc_mes').setValue(chave4.substr(5,2));
      $('rh01_nasc_ano').setValue(chave4.substr(0,4));
      $('rh01_nasc').setValue($F('rh01_nasc_dia')+"/"+$F('rh01_nasc_mes')+"/"+$F('rh01_nasc_ano'));
    }
    func_nome.hide();
  }
  function js_pesquisarh01_nacion(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoal','db_iframe_rhnacionalidade','func_rhnacionalidade.php?funcao_js=parent.js_mostrarhnacionalidade1|rh06_nacionalidade|rh06_descr','Pesquisa',true,'0');
    }else{
      if(document.form1.rh01_nacion.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoal','db_iframe_rhnacionalidade','func_rhnacionalidade.php?pesquisa_chave='+document.form1.rh01_nacion.value+'&funcao_js=parent.js_mostrarhnacionalidade','Pesquisa',false,'0');
      }else{
        document.form1.rh06_descr.value = '';
        js_mudaanoche();
      }
    }
  }
  function js_mostrarhnacionalidade(chave,erro){
    document.form1.rh06_descr.value = chave;
    js_mudaanoche(document.form1.rh01_nacion.value);
    if(erro==true){
      document.form1.rh01_nacion.focus();
      document.form1.rh01_nacion.value = '';
    }
  }
  function js_mostrarhnacionalidade1(chave1,chave2){
    document.form1.rh01_nacion.value = chave1;
    document.form1.rh06_descr.value = chave2;
    db_iframe_rhnacionalidade.hide();
    js_mudaanoche(document.form1.rh01_nacion.value);
  }
  function js_pesquisarh01_instru(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoal','db_iframe_rhinstrucao','func_rhinstrucao.php?funcao_js=parent.js_mostrarhinstrucao1|rh21_instru|rh21_descr','Pesquisa',true,'0');
    }else{
      if(document.form1.rh01_instru.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoal','db_iframe_rhinstrucao','func_rhinstrucao.php?pesquisa_chave='+document.form1.rh01_instru.value+'&funcao_js=parent.js_mostrarhinstrucao','Pesquisa',false,'0');
      }else{
        document.form1.rh21_descr.value = '';
      }
    }
  }
  function js_mostrarhinstrucao(chave,erro){
    document.form1.rh21_descr.value = chave;
    if(erro==true){
      document.form1.rh01_instru.focus();
      document.form1.rh01_instru.value = '';
    }
  }
  function js_mostrarhinstrucao1(chave1,chave2){
    document.form1.rh01_instru.value = chave1;
    document.form1.rh21_descr.value = chave2;
    db_iframe_rhinstrucao.hide();
  }
  function js_pesquisarh01_estciv(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoal','db_iframe_rhestcivil','func_rhestcivil.php?funcao_js=parent.js_mostrarhestcivil1|rh08_estciv|rh08_descr','Pesquisa',true,'0');
    }else{
      if(document.form1.rh01_estciv.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoal','db_iframe_rhestcivil','func_rhestcivil.php?pesquisa_chave='+document.form1.rh01_estciv.value+'&funcao_js=parent.js_mostrarhestcivil','Pesquisa',false,'0');
      }else{
        document.form1.rh08_descr.value = '';
      }
    }
  }
  function js_mostrarhestcivil(chave,erro){
    document.form1.rh08_descr.value = chave;
    if(erro==true){
      document.form1.rh01_estciv.focus();
      document.form1.rh01_estciv.value = '';
    }
  }
  function js_mostrarhestcivil1(chave1,chave2){
    document.form1.rh01_estciv.value = chave1;
    document.form1.rh08_descr.value = chave2;
    db_iframe_rhestcivil.hide();
  }
  function js_pesquisarh01_raca(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoal','db_iframe_rhraca','func_rhraca.php?funcao_js=parent.js_mostrarhraca1|rh18_raca|rh18_descr','Pesquisa',true,'0');
    }else{
      if(document.form1.rh01_raca.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoal','db_iframe_rhraca','func_rhraca.php?pesquisa_chave='+document.form1.rh01_raca.value+'&funcao_js=parent.js_mostrarhraca','Pesquisa',false,'0');
      }else{
        document.form1.rh18_descr.value = '';
      }
    }
  }
  function js_mostrarhraca(chave,erro){
    document.form1.rh18_descr.value = chave;
    if(erro==true){
      document.form1.rh01_raca.focus();
      document.form1.rh01_raca.value = '';
    }
  }
  function js_mostrarhraca1(chave1,chave2){
    document.form1.rh01_raca.value = chave1;
    document.form1.rh18_descr.value = chave2;
    db_iframe_rhraca.hide();
  }

  function js_pesquisa(){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhpessoal','db_iframe_rhpessoal','func_rhpessoal.php?<?=($db_opcao==2 || $db_opcao == 22 ? "testarescisao=ra&" : "")?>funcao_js=parent.js_preenchepesquisa|rh01_regist&instit=<?=db_getsession("DB_instit")?>','Pesquisa',true,0);
  }
  function js_preenchepesquisa(chave){
    db_iframe_rhpessoal.hide();
    <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    ?>
  }
  <?
  if(isset($rh01_nacion) && trim($rh01_nacion)!=""){
    echo "js_mudaanoche('$rh01_nacion');\n";
  }
  ?>
  function js_alterarCgm(iCgm) {

    if (iCgm != "") {
      js_OpenJanelaIframe('',
        'db_iframe_novocgm',
        'prot1_cadcgm002.php?chavepesquisa='+iCgm+
                      '&testanome=false&lCpf=true&execfunction=parent.CurrentWindow.corpo.iframe_rhpessoal.teste',
        'Novo CGM',
        true,'0');
    }
  }

  function teste(iCgm) {

    db_iframe_novocgm.hide();
    $('rh01_numcgm').value = iCgm;
    js_pesquisarh01_numcgm(false);
  }

  function js_pesquisaSindicato() {

    js_OpenJanelaIframe('',
      'db_iframe_rhsindicato',
      'func_rhsindicato.php?funcao_js=parent.js_retornoPesquisaSindicato|rh116_descricao|rh116_sequencial',
      'Pesquisa',
      true,
      '0');
  }

  function js_retornoPesquisaSindicato(sDescricao, iSindicato) {

    document.getElementById('rh01_rhsindicato').value = iSindicato;
    document.getElementById('rh116_descricao').value = sDescricao;
    db_iframe_rhsindicato.hide();
  }

  function js_validaContratoEmergencial(checkbox) {

    if (checkbox.readOnly) {
      return true;
    }

    if(checkbox.checked) {
      document.getElementById('labelTerminoContratoEmergencial').style.visibility = 'visible';
      document.getElementById('terminoContratoEmergencial').style.visibility      = 'visible';
      checkbox.value = 't';
    } else {
      document.getElementById('labelTerminoContratoEmergencial').style.visibility = 'hidden';
      document.getElementById('terminoContratoEmergencial').style.visibility      = 'hidden';
    }
  }

  function js_validaTerminoContratoEmergencial() {

    var sDataAdmissao = document.getElementById('rh01_admiss').value;
    var sDataTermino  = document.getElementById('rh164_datafim').value;

    if(document.getElementById('contratoEmergencial').checked) {

      if(sDataTermino.trim() == '') {
        alert("Preencha a data de término do contrato emergencial.");
        return false;
      }

      sDataAdmissao = getDateInDatabaseFormat(sDataAdmissao);
      sDataTermino  = getDateInDatabaseFormat(sDataTermino);

      if(sDataTermino <= sDataAdmissao) {
        alert("A data de término deve ser maior que a data de admissão.");
        return false;
      }
    }

    return true;
  }
</script>
