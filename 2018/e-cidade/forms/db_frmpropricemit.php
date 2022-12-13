<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

$clpropricemit->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p51_descr");
$clrotulo->label("p58_requer");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("cm25_i_codigo");

$clrotulo->label("cm25_i_lotecemit");
$clrotulo->label("cm23_i_lotecemit");
$clrotulo->label("cm23_c_situacao");
$clrotulo->label("cm23_i_quadracemit");
$clrotulo->label("cm22_c_quadra");
$clrotulo->label("cm22_i_cemiterio");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<fieldset>
  <legend>Proprietário</legend>
    <table border="0">
      <tr>
        <td nowrap title="<?=@$Tcm28_i_codigo?>">
           <?=@$Lcm28_i_codigo?>
        </td>
        <td>
            <?
            db_input('cm28_i_codigo',10,$Icm28_i_codigo,true,'text',3,"readonly")
            ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tcm28_i_processo?>">
           <?
           db_ancora(@$Lcm28_i_processo,"js_pesquisacm28_i_processo(true);",$db_opcao);
           ?>
        </td>
        <td>
            <?db_input('cm28_i_processo',10,$Icm28_i_processo,true,'text',3,"onchange='js_pesquisacm28_i_processo(false);'")?>
            <?db_input('p51_descr',40,$Ip51_descr,true,'text',3,'');?>
        </td>
      </tr>
      <tr>
         <td nowrap title="<?=@$Tp58_requer?>"><?=@$Lp58_requer?></td>
         <td>
             <?db_input('p58_requer',40,$Ip58_requer,true,'text',3,'');?>
         </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tcm28_i_proprietario?>">
           <?
           db_ancora(@$Lcm28_i_proprietario,"js_pesquisacm28_i_proprietario(true);",$db_opcao);
           ?>
        </td>
        <td>
    <?
    db_input('cm28_i_proprietario',10,$Icm28_i_proprietario,true,'text',$db_opcao," onchange='js_pesquisacm28_i_proprietario(false);'")
    ?>
           <?
    db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
           ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tcm28_i_ossoariojazigo?>">
           <?
           db_ancora(@$Lcm28_i_ossoariojazigo,"js_pesquisacm28_i_ossoariojazigo(true);",$db_opcao);
           ?>
        </td>
        <td>
    <?
    db_input('cm28_i_ossoariojazigo',10,$Icm28_i_ossoariojazigo,true,'hidden',3);
    db_input('cm25_c_numero',10,@$cm25_c_numero,true,'text',3);
    ?>

           <?
    db_input('cm25_c_tipo',10,@$cm25_c_tipo,true,'text',3,'')
           ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tcm25_i_lotecemit?>">
           <?=@$Lcm25_i_lotecemit?>
        </td>
        <td>
           <?
            db_input('cm25_i_lotecemit',10,$Icm25_i_lotecemit,true,'hidden',3," onchange='js_pesquisacm25_i_lotecemit(false);'")
           ?>
           <?
            db_input('cm23_i_lotecemit',10,$Icm23_i_lotecemit,true,'text',3,'');
            db_input('cm23_c_situacao',10,$Icm23_c_situacao,true,'hidden',3,'');
           ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tcm23_i_quadracemit?>">
           <?=@$Lcm23_i_quadracemit?>
        </td>
        <td>
           <?
             db_input('cm23_i_quadracemit',10,$Icm23_i_quadracemit,true,'hidden',3,"");
             db_input('cm22_c_quadra',10,$Icm22_c_quadra,true,'text',3,"");
           ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tcm22_i_cemiterio?>">
           <?=@$Lcm22_i_cemiterio?>
        </td>
        <td>
           <?
             db_input('cm22_i_cemiterio',10,$Icm22_i_cemiterio,true,'text',3,"");
             db_input('cm22_c_cemiterio',40,@$cm22_c_cemiterio,true,'text',3,"");
           ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Tcm28_d_aquisicao?>">
           <?=@$Lcm28_d_aquisicao?>
        </td>
        <td>
    <?
    if(!isset($cm28_d_aquisicao) && $db_opcao==1){
      $cm28_d_aquisicao_dia = date('d',db_getsession("DB_datausu"));
      $cm28_d_aquisicao_mes = date('m',db_getsession("DB_datausu"));
      $cm28_d_aquisicao_ano = date('Y',db_getsession("DB_datausu"));
    }

    db_inputdata('cm28_d_aquisicao',@$cm28_d_aquisicao_dia,@$cm28_d_aquisicao_mes,@$cm28_d_aquisicao_ano,true,'text',$db_opcao,"");
    ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisacm28_i_processo(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p51_descr|p58_requer|DB_p58_numcgm','Pesquisa',true);
  }else{

     if(document.form1.cm28_i_processo.value != ''){
        js_OpenJanelaIframe('','db_iframe_protprocesso','func_protprocesso.php?pesquisa_chave='+document.form1.cm28_i_processo.value+'&funcao_js=parent.js_mostraprotprocesso|p58_codproc|p51_descr','Pesquisa',false);
     }else{
       document.form1.p51_descr.value = '';
     }
  }
}
function js_mostraprotprocesso(erro,chave){

  document.form1.p51_descr.value = chave;
  if(erro==true){
    document.form1.cm28_i_processo.focus();
    document.form1.cm28_i_processo.value = '';
  }
}
function js_mostraprotprocesso1(chave1,chave2,chave3,chave4){

  document.form1.cm28_i_processo.value     = chave1;
  document.form1.p51_descr.value           = chave2;
  document.form1.p58_requer.value          = chave3;
  document.form1.cm28_i_proprietario.value = chave4;
  js_pesquisacm28_i_proprietario(false);

  db_iframe_protprocesso.hide();
}
function js_pesquisacm28_i_proprietario(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm28_i_proprietario.value != ''){
        js_OpenJanelaIframe('','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.cm28_i_proprietario.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostracgm(erro,chave){

  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.cm28_i_proprietario.focus();
    document.form1.cm28_i_proprietario.value = '';
  }
}
function js_mostracgm1(chave1,chave2){

  document.form1.cm28_i_proprietario.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisacm28_i_ossoariojazigo(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_ossoariojazigo','func_ossoariojazigo.php?funcao_js=parent.js_mostraossoariojazigo1|cm25_i_codigo|cm25_c_numero|cm25_c_tipo|cm23_i_codigo|cm23_i_lotecemit|cm23_i_quadracemit|cm22_c_quadra|cm22_i_cemiterio|z01_nome|cm23_c_situacao&tp=<?=@$db_opcao?>','Pesquisa',true);
  }else{

     if(document.form1.cm28_i_ossoariojazigo.value != ''){
        js_OpenJanelaIframe('','db_iframe_ossoariojazigo','func_ossoariojazigo.php?pesquisa_chave='+document.form1.cm28_i_ossoariojazigo.value+'&funcao_js=parent.js_mostraossoariojazigo&tp=<?=@$db_opcao?>','Pesquisa',false);
     }else{
       document.form1.cm25_c_tipo.value = '';
     }
  }
}
function js_mostraossoariojazigo(chave,erro){

  document.form1.cm25_c_tipo.value = chave;
  if(erro==true){
    document.form1.cm28_i_ossoariojazigo.focus();
    document.form1.cm28_i_ossoariojazigo.value = '';
  }
}
function js_mostraossoariojazigo1(chave1,chave2,chave3,chave4,chave5,chave6,chave7,chave8,chave9,chave10){

  document.form1.cm28_i_ossoariojazigo.value = chave1;
  document.form1.cm25_c_numero.value         = chave2;
  document.form1.cm25_c_tipo.value           = chave3;
  document.form1.cm25_i_lotecemit.value      = chave4;
  document.form1.cm23_i_lotecemit.value      = chave5;
  document.form1.cm23_i_quadracemit.value    = chave6;
  document.form1.cm22_c_quadra.value         = chave7;
  document.form1.cm22_i_cemiterio.value      = chave8;
  document.form1.cm22_c_cemiterio.value      = chave9;
  document.form1.cm23_c_situacao.value       = chave10;
  db_iframe_ossoariojazigo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_propricemit','func_propricemit.php?funcao_js=parent.js_preenchepesquisa|cm28_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_propricemit.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>