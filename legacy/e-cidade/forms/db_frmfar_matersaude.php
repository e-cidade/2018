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

//MODULO: Farmacia
$clfar_matersaude->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("fa05_i_codigo");
$clrotulo->label("m60_descr");
$clrotulo->label("fa14_i_codigo");
$clrotulo->label("fa01_codigobarras");
?>
<form name="form1" method="post" action="">
  <fieldset>
    <legend><?=$sLegenda?></legend>
    <table border="0">
      <tr>
        <td nowrap title="<?=@$Tfa01_i_codigo?>">
          <label for="fa01_i_codigo"> <?=@$Lfa01_i_codigo?> </label>
        </td>
        <td>
          <?php
          db_input('fa01_i_codigo',10,$Ifa01_i_codigo,true,'text',3,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tfa01_c_nomegenerico?>">
          <label for="fa01_c_nomegenerico"> <?=@$Lfa01_c_nomegenerico?> </label>
        </td>
        <td>
          <?php
          db_input('fa01_c_nomegenerico',52,$Ifa01_c_nomegenerico,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tfa01_i_codmater?>">
          <label for="fa01_i_codmater">
            <?php
              db_ancora(@$Lfa01_i_codmater,"js_pesquisafa01_i_codmater(true);",$db_opcao);
            ?>
          </label>
        </td>
        <td>
          <?php
          db_input('fa01_i_codmater',10,$Ifa01_i_codmater,true,'text',$db_opcao," onchange='js_pesquisafa01_i_codmater(false);'");
          db_input('m60_descr',40,$Im60_descr,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tfa01_i_medanvisa?>">
          <label for="fa01_i_medanvisa">
            <?php
              db_ancora(@$Lfa01_i_medanvisa,"js_pesquisafa01_i_medanvisa(true);",$db_opcao);
            ?>
          </label>
        </td>
        <td>
          <?php
          db_input('fa01_i_medanvisa',10,$Ifa01_i_medanvisa,true,'text',$db_opcao," onchange='js_pesquisafa01_i_medanvisa(false);'");
          db_input('fa14_c_medanvisa',40,@$Ifa14_c_medanvisa,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tfa01_i_prescricaomed?>">
          <label for="fa01_i_prescricaomed">
            <?php
            db_ancora(@$Lfa01_i_prescricaomed,"js_pesquisafa01_i_prescricaomed(true);",$db_opcao);
            ?>
          </label>
        </td>
        <td>
          <?php
          db_input('fa01_i_prescricaomed',10,@$Ifa01_i_prescricaomed,true,'text',$db_opcao," onchange='js_pesquisafa01_i_prescricaomed(false);'");
          db_input('fa20_c_prescricao',40,@$Ifa20_c_prescricao,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tfa01_i_classemed?>">
          <label for="fa01_i_classemed">
            <?php
              db_ancora(@$Lfa01_i_classemed,"js_pesquisafa01_i_classemed(true);",$db_opcao);
            ?>
          </label>
        </td>
        <td>
          <?php
          db_input('fa01_i_classemed',10,@$Ifa01_i_classemed,true,'text',$db_opcao," onchange='js_pesquisafa01_i_classemed(false);'");
          db_input('fa18_c_classetera',40,@$Ifa18_c_classetera,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tfa01_i_listacontroladomed?>">
          <label for="fa01_i_listacontroladomed">
            <?php
              db_ancora(@$Lfa01_i_listacontroladomed,"js_pesquisafa01_i_listacontroladomed(true);",$db_opcao);
            ?>
          </label>
        </td>
        <td>
          <?php
          db_input('fa01_i_listacontroladomed',10,@$Ifa01_i_listacontroladomed,true,'text',$db_opcao," onchange='js_pesquisafa01_i_listacontroladomed(false);'");
          db_input('fa15_c_listacontrolado',40,@$Ifa15_c_listacontrolado,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tfa01_i_medrefemed?>">
          <label for="fa01_i_medrefemed">
            <?php
              db_ancora(@$Lfa01_i_medrefemed,"js_pesquisafa01_i_medrefemed(true);",$db_opcao);
            ?>
          </label>
        </td>
        <td>
          <?php
          db_input('fa01_i_medrefemed',10,@$Ifa01_i_medrefemed,true,'text',$db_opcao," onchange='js_pesquisafa01_i_medrefemed(false);'");
          db_input('fa19_c_medreferencia',40,@$Ifa19_c_medreferencia,true,'text',3,'')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tfa01_i_laboratoriomed?>">
          <label for="fa01_i_laboratoriomed">
          <?php
            db_ancora(@$Lfa01_i_laboratoriomed,"js_pesquisafa01_i_laboratoriomed(true);",$db_opcao);
          ?>
          </label>
        </td>
        <td>
          <?php
            db_input('fa01_i_laboratoriomed',10,@$Ifa01_i_laboratoriomed,true,'text',$db_opcao," onchange='js_pesquisafa01_i_laboratoriomed(false);'");
            db_input('fa24_c_laboratorio',40,@$Ifa24_c_laboratorio,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tfa01_i_formafarmaceuticamed?>">
          <label for="fa01_i_formafarmaceuticamed">
            <?php
              db_ancora(@$Lfa01_i_formafarmaceuticamed,"js_pesquisafa01_i_formafarmaceuticamed(true);",$db_opcao);
            ?>
          </label>
        </td>
        <td>
          <?php
            db_input('fa01_i_formafarmaceuticamed',10,@$Ifa01_i_formafarmaceuticamed,true,'text',$db_opcao," onchange='js_pesquisafa01_i_formafarmaceuticamed(false);'");
            db_input('fa29_c_forma',40,@$Ifa29_c_forma,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tfa01_i_concentracaomed?>">
          <label for="fa01_i_concentracaomed">
            <?php
              db_ancora(@$Lfa01_i_concentracaomed,"js_pesquisafa01_i_concentracaomed(true);",$db_opcao);
            ?>
          </label>
        </td>
        <td>
          <?php
            db_input('fa01_i_concentracaomed',10,@$Ifa01_i_concentracaomed,true,'text',$db_opcao," onchange='js_pesquisafa01_i_concentracaomed(false);'");        db_input('fa30_c_concentracao',40,@$Ifa30_c_concentracao,true,'text',3,'')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tfa01_i_class?>">
          <label for="fa01_i_class">
            <?php
              db_ancora(@$Lfa01_i_class,"js_pesquisafa01_i_class(true);",$db_opcao);
            ?>
          </label>
        </td>
        <td>
          <?php
            db_input('fa01_i_class',10,$Ifa01_i_class,true,'text',$db_opcao," onchange='js_pesquisafa01_i_class(false);'");
            db_input('fa05_c_descr',40,@$Ifa05_c_descr,true,'text',3,'');
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap>
          <label for="fa01_medicamentos">
            <?php
              db_ancora("Farmácia Básica:", "pesquisaMedicamentoFarmaciaBasica(true);", $db_opcao);
            ?>
          </label>
        </td>
        <td>
          <?php
            db_input('fa01_medicamentos', 10, $Ifa01_medicamentos, true, 'text', $db_opcao," onchange='pesquisaMedicamentoFarmaciaBasica(false);'");
            db_input('fa58_descricao', 40, @$Ifa05_c_descr,true,'text', 3, '');
          ?>
        </td>
      </tr>

      <tr>
        <td>
          <label for="fa01_i_medhiperdia">
            <?=$Lfa01_i_medhiperdia;?>
          </label>
        </td>
        <td>
          <?php
            $oDaoFarMedicamentoHiperdia = db_utils::getdao('far_medicamentohiperdia');
            $sSql                       = $oDaoFarMedicamentoHiperdia->sql_query_file(null, 'fa43_i_codigo, fa43_c_descr', 'fa43_i_codigo');
            $rs                         = $oDaoFarMedicamentoHiperdia->sql_record($sSql);
            $aX                         = array();
            for ($iCont = 0; $iCont < $oDaoFarMedicamentoHiperdia->numrows; $iCont ++) {
              $oDados                     = db_utils::fieldsmemory($rs, $iCont);
              $aX[$oDados->fa43_i_codigo] = $oDados->fa43_c_descr;
            }
            db_select('fa01_i_medhiperdia', $aX, true, $db_opcao, '');
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Tfa01_codigobarras?>">
          <label for="fa01_codigobarras" > <?=@$Lfa01_codigobarras?> </label>
        </td>
        <td>
          <?php
            db_input('fa01_codigobarras', 20, $Ifa01_codigobarras, true, 'text', $db_opcao);
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Tfa01_t_obs?>">
          <label for="fa01_t_obs"> <?=@$Lfa01_t_obs?> </label>
        </td>
        <td>
          <?php
            db_textarea('fa01_t_obs',0,50,$Ifa01_t_obs,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </center>
</form>
<script type="text/javascript">

function js_pesquisafa01_i_class(mostra) {

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_far_class','func_far_class.php?funcao_js=parent.js_mostrafar_class1|fa05_i_codigo|fa05_c_descr','Pesquisa',true);
  }else{
     if(document.form1.fa01_i_class.value != ''){
      js_OpenJanelaIframe('','db_iframe_far_class','func_far_class.php?pesquisa_chave='+document.form1.fa01_i_class.value+'&funcao_js=parent.js_mostrafar_class','Pesquisa',false);
    }else{
     document.form1.fa05_c_descr.value = '';
    }
  }
}
function js_mostrafar_class(chave,erro){
  document.form1.fa05_c_descr.value = chave;
  if(erro==true){
    document.form1.fa01_i_class.focus();
    document.form1.fa01_i_class.value = '';
  }
}
function js_mostrafar_class1(chave1,chave2){
  document.form1.fa01_i_class.value = chave1;
  document.form1.fa05_c_descr.value = chave2;
  db_iframe_far_class.hide();
}
function js_pesquisafa01_i_codmater(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_matmater','func_matmater.php?funcao_js=parent.js_mostramatmater1|m60_codmater|m60_descr','Pesquisa',true);
  }else{
     if(document.form1.fa01_i_codmater.value != ''){
      js_OpenJanelaIframe('','db_iframe_matmater','func_matmater.php?pesquisa_chave='+document.form1.fa01_i_codmater.value+'&funcao_js=parent.js_mostramatmater','Pesquisa',false);
    }else{
     document.form1.m60_descr.value = '';
    }
  }
}
function js_mostramatmater(chave,erro){
  document.form1.m60_descr.value = chave;
  if(erro==true){
    document.form1.fa01_i_codmater.focus();
    document.form1.fa01_i_codmater.value = '';
  }
}
function js_mostramatmater1(chave1,chave2,chave3){
  document.form1.fa01_i_codmater.value = chave1;
  document.form1.m60_descr.value = chave2;
  db_iframe_matmater.hide();
}
function js_pesquisafa01_i_medanvisa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_far_medanvisa','func_far_medanvisa.php?funcao_js=parent.js_mostrafar_medanvisa1|fa14_i_codigo|fa14_c_medanvisa','Pesquisa',true);
  }else{
     if(document.form1.fa01_i_medanvisa.value != ''){
        js_OpenJanelaIframe('','db_iframe_far_medanvisa','func_far_medanvisa.php?pesquisa_chave='+document.form1.fa01_i_medanvisa.value+'&funcao_js=parent.js_mostrafar_medanvisa','Pesquisa',false);
     }else{
       document.form1.fa14_c_medanvisa.value = '';
     }
  }
}
function js_mostrafar_medanvisa(chave,erro){
  document.form1.fa14_c_medanvisa.value = chave;
  if(erro==true){
    document.form1.fa01_i_medanvisa.focus();
    document.form1.fa01_i_medanvisa.value = '';
  }
}
function js_mostrafar_medanvisa1(chave1,chave2){
  document.form1.fa01_i_medanvisa.value = chave1;
  document.form1.fa14_c_medanvisa.value = chave2;
  db_iframe_far_medanvisa.hide();
}

function js_pesquisafa01_i_concentracaomed(mostra){
  if(document.form1.fa01_i_medanvisa.value==''){
     alert('Selecione o medicamento da Anvisa!');
     return false;
  }
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_far_concentracaomed','func_far_concentracaomed.php?fa01_i_medanvisa='+document.form1.fa01_i_medanvisa.value+'&funcao_js=parent.js_mostrafar_concentracaomed1|fa37_i_codigo|fa30_c_concentracao','Pesquisa',true);
  }else{
     if(document.form1.fa01_i_concentracaomed.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_far_concentracaomed','func_far_concentracaomed.php?fa01_i_medanvisa='+document.form1.fa01_i_medanvisa.value+'&pesquisa_chave='+document.form1.fa01_i_concentracaomed.value+'&funcao_js=parent.js_mostrafar_concentracaomed','Pesquisa',false);
     }else{
       document.form1.fa37_i_codigo.value = '';
     }
  }
}
function js_mostrafar_concentracaomed(chave,erro){
  document.form1.fa30_c_concentracao.value = chave;
  if(erro==true){
    document.form1.fa01_i_concentracaomed.focus();
    document.form1.fa01_i_concentracaomed.value = '';
  }
}
function js_mostrafar_concentracaomed1(chave1,chave2){
  document.form1.fa01_i_concentracaomed.value = chave1;
  document.form1.fa30_c_concentracao.value = chave2;
  db_iframe_far_concentracaomed.hide();
}
function js_pesquisafa01_i_formafarmaceuticamed(mostra){
  if(document.form1.fa01_i_medanvisa.value==''){
     alert('Selecione o medicamento da Anvisa!');
     return false;
  }
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_far_formafarmaceuticamed','func_far_formafarmaceuticamed.php?fa01_i_medanvisa='+document.form1.fa01_i_medanvisa.value+'&funcao_js=parent.js_mostrafar_formafarmaceuticamed1|fa33_i_codigo|fa29_c_forma','Pesquisa',true);
  }else{
     if(document.form1.fa01_i_formafarmaceuticamed.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_far_formafarmaceuticamed','func_far_formafarmaceuticamed.php?fa01_i_medanvisa='+document.form1.fa01_i_medanvisa.value+'&pesquisa_chave='+document.form1.fa01_i_formafarmaceuticamed.value+'&funcao_js=parent.js_mostrafar_formafarmaceuticamed','Pesquisa',false);
     }else{
       document.form1.fa33_i_codigo.value = '';
     }
  }
}
function js_mostrafar_formafarmaceuticamed(chave,erro){
  document.form1.fa29_c_forma.value = chave;
  if(erro==true){
    document.form1.fa01_i_formafarmaceuticamed.focus();
    document.form1.fa01_i_formafarmaceuticamed.value = '';
  }
}
function js_mostrafar_formafarmaceuticamed1(chave1,chave2){
  document.form1.fa01_i_formafarmaceuticamed.value = chave1;
  document.form1.fa29_c_forma.value = chave2;
  db_iframe_far_formafarmaceuticamed.hide();
}
function js_pesquisafa01_i_laboratoriomed(mostra){
  if(document.form1.fa01_i_medanvisa.value==''){
     alert('Selecione o medicamento da Anvisa!');
     return false;
  }
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_far_laboratoriomed','func_far_laboratoriomed.php?fa01_i_medanvisa='+document.form1.fa01_i_medanvisa.value+'&funcao_js=parent.js_mostrafar_laboratoriomed1|fa32_i_codigo|fa24_c_laboratorio','Pesquisa',true);
  }else{
     if(document.form1.fa01_i_laboratoriomed.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_far_laboratoriomed','func_far_laboratoriomed.php?fa01_i_medanvisa='+document.form1.fa01_i_medanvisa.value+'&pesquisa_chave='+document.form1.fa01_i_laboratoriomed.value+'&funcao_js=parent.js_mostrafar_laboratoriomed','Pesquisa',false);
     }else{
       document.form1.fa32_i_codigo.value = '';
     }
  }
}
function js_mostrafar_laboratoriomed(chave,erro){
  document.form1.fa24_c_laboratorio.value = chave;
  if(erro==true){
    document.form1.fa01_i_laboratoriomed.focus();
    document.form1.fa01_i_laboratoriomed.value = '';
  }
}
function js_mostrafar_laboratoriomed1(chave1,chave2){
  document.form1.fa01_i_laboratoriomed.value = chave1;
  document.form1.fa24_c_laboratorio.value = chave2;
  db_iframe_far_laboratoriomed.hide();
}
function js_pesquisafa01_i_medrefemed(mostra){
  if(document.form1.fa01_i_medanvisa.value==''){
     alert('Selecione o medicamento da Anvisa!');
     return false;
  }
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_far_medreferenciamed','func_far_medreferenciamed.php?fa01_i_medanvisa='+document.form1.fa01_i_medanvisa.value+'&funcao_js=parent.js_mostrafar_medreferenciamed1|fa34_i_codigo|fa19_c_medreferencia','Pesquisa',true);
  }else{
     if(document.form1.fa01_i_medrefemed.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_far_medreferenciamed','func_far_medreferenciamed.php?fa01_i_medanvisa='+document.form1.fa01_i_medanvisa.value+'&pesquisa_chave='+document.form1.fa01_i_medrefemed.value+'&funcao_js=parent.js_mostrafar_medreferenciamed','Pesquisa',false);
     }else{
       document.form1.fa34_i_codigo.value = '';
     }
  }
}
function js_mostrafar_medreferenciamed(chave,erro){
  document.form1.fa19_c_medreferencia.value = chave;
  if(erro==true){
    document.form1.fa01_i_medrefemed.focus();
    document.form1.fa01_i_medrefemed.value = '';
  }
}
function js_mostrafar_medreferenciamed1(chave1,chave2){
  document.form1.fa01_i_medrefemed.value = chave1;
  document.form1.fa19_c_medreferencia.value = chave2;
  db_iframe_far_medreferenciamed.hide();
}
function js_pesquisafa01_i_listacontroladomed(mostra){
  if(document.form1.fa01_i_medanvisa.value==''){
     alert('Selecione o medicamento da Anvisa!');
     return false;
  }
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_far_listacontroladomed','func_far_listacontroladomed.php?fa01_i_medanvisa='+document.form1.fa01_i_medanvisa.value+'&funcao_js=parent.js_mostrafar_listacontroladomed1|fa35_i_codigo|fa15_c_listacontrolado','Pesquisa',true);
  }else{
     if(document.form1.fa01_i_listacontroladomed.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_far_listacontroladomed','func_far_listacontroladomed.php?fa01_i_medanvisa='+document.form1.fa01_i_medanvisa.value+'&pesquisa_chave='+document.form1.fa01_i_listacontroladomed.value+'&funcao_js=parent.js_mostrafar_listacontroladomed','Pesquisa',false);
     }else{
       document.form1.fa35_i_codigo.value = '';
     }
  }
}
function js_mostrafar_listacontroladomed(chave,erro){
  document.form1.fa15_c_listacontrolado.value = chave;
  if(erro==true){
    document.form1.fa01_i_listacontroladomed.focus();
    document.form1.fa01_i_listacontroladomed.value = '';
  }
}
function js_mostrafar_listacontroladomed1(chave1,chave2){
  document.form1.fa01_i_listacontroladomed.value = chave1;
  document.form1.fa15_c_listacontrolado.value = chave2;
  db_iframe_far_listacontroladomed.hide();
}
function js_pesquisafa01_i_classemed(mostra){
  if(document.form1.fa01_i_medanvisa.value==''){
     alert('Selecione o medicamento da Anvisa!');
     return false;
  }
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_far_classeterapeuticamed','func_far_classeterapeuticamed.php?fa01_i_medanvisa='+document.form1.fa01_i_medanvisa.value+'&funcao_js=parent.js_mostrafar_classeterapeuticamed1|fa36_i_codigo|fa18_c_classetera','Pesquisa',true);
  }else{
     if(document.form1.fa01_i_classemed.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_far_classeterapeuticamed','func_far_classeterapeuticamed.php?fa01_i_medanvisa='+document.form1.fa01_i_medanvisa.value+'&pesquisa_chave='+document.form1.fa01_i_classemed.value+'&funcao_js=parent.js_mostrafar_classeterapeuticamed','Pesquisa',false);
     }else{
       document.form1.fa36_i_codigo.value = '';
     }
  }
}
function js_mostrafar_classeterapeuticamed(chave,erro){
  document.form1.fa18_c_classetera.value = chave;
  if(erro==true){
    document.form1.fa01_i_classemed.focus();
    document.form1.fa01_i_classemed.value = '';
  }
}
function js_mostrafar_classeterapeuticamed1(chave1,chave2){
  document.form1.fa01_i_classemed.value = chave1;
  document.form1.fa18_c_classetera.value = chave2;
  db_iframe_far_classeterapeuticamed.hide();
}
function js_pesquisafa01_i_prescricaomed(mostra){
  if(document.form1.fa01_i_medanvisa.value==''){
     alert('Selecione o medicamento da Anvisa!');
     return false;
  }
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_far_prescricaomed','func_far_prescricaomed.php?fa01_i_medanvisa='+document.form1.fa01_i_medanvisa.value+'&funcao_js=parent.js_mostrafar_prescricaomed1|fa31_i_codigo|fa20_c_prescricao','Pesquisa',true);
  }else{
     if(document.form1.fa01_i_prescricaomed.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_far_prescricaomed','func_far_prescricaomed.php?fa01_i_medanvisa='+document.form1.fa01_i_medanvisa.value+'&pesquisa_chave='+document.form1.fa01_i_prescricaomed.value+'&funcao_js=parent.js_mostrafar_prescricaomed','Pesquisa',false);
     }else{
       document.form1.fa31_i_codigo.value = '';
     }
  }
}
function js_mostrafar_prescricaomed(chave,erro){
  document.form1.fa20_c_prescricao.value = chave;
  if(erro==true){
    document.form1.fa01_i_prescricaomed.focus();
    document.form1.fa01_i_prescricaomed.value = '';
  }
}
function js_mostrafar_prescricaomed1(chave1,chave2){
  document.form1.fa01_i_prescricaomed.value = chave1;
  document.form1.fa20_c_prescricao.value = chave2;
  db_iframe_far_prescricaomed.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_far_matersaude','func_far_matersaude.php?funcao_js=parent.js_preenchepesquisa|fa01_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_far_matersaude.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function pesquisaMedicamentoFarmaciaBasica(lMostra) {

  var sTitulo = 'Pesquisa de Medicamentos da Farmácia Básica';
  var sUrl    = "func_medicamentos.php";
  if (lMostra) {

    sUrl += '?funcao_js=parent.medicamentoFarmaciaBasica|fa58_codigo|fa58_descricao';
    js_OpenJanelaIframe('top.corpo','db_iframe_medicamentos', sUrl, sTitulo, true);
  } else if ( $F('fa01_medicamentos') != '' ) {

    sUrl += '?funcao_js=parent.medicamentoFarmaciaBasica';
    sUrl += '&pesquisa_chave=' + $F('fa01_medicamentos');
    js_OpenJanelaIframe('top.corpo','db_iframe_medicamentos', sUrl, sTitulo, false);
  } else {

    $('fa01_medicamentos').value = '';
    $('fa58_descricao').value    = '';
  }
}
function medicamentoFarmaciaBasica() {

  if ( typeof(arguments[1]) == 'boolean') {

    $('fa58_descricao').value = arguments[0];
    if (arguments[1]) {
      $('fa01_medicamentos').value = '';
    }
    return;
  }
  $('fa01_medicamentos').value = arguments[0];
  $('fa58_descricao').value    = arguments[1];
  db_iframe_medicamentos.hide();

}
</script>