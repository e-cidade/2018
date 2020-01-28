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

//MODULO: pessoal
include("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrhpesrescisao->rotulo->label();
$clselecao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("rh01_admiss");
$clrotulo->label("z01_nome");
$clrotulo->label("r59_descr");
$clrotulo->label("r59_descr1");
$clrotulo->label("r59_menos1");
$clrotulo->label("r59_aviso");
$clrotulo->label("rh02_seqpes");
$clrotulo->label("r59_regime");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td align="center">
      <fieldset id="field-filtros">
        <legend align="left"><b>FILTRO</b></legend>
        <table width="100%">
          <tr>
<?php
  $geraform = new cl_formulario_rel_pes;

  $geraform->db_opcao = $db_opcao;

  $geraform->manomes = false;                     // PARA NÃO MOSTRAR ANO E MES DE COMPETÊNCIA DA FOLHA

  $geraform->usaregi = true;                      // PERMITIR SELEÇÃO DE MATRÍCULAS
  $geraform->usalota = true;                      // PERMITIR SELEÇÃO DE LOTAÇÕES

  $geraform->re1nome = "regisi";                  // NOME DO CAMPO DA MATRÍCULA INICIAL
  $geraform->re2nome = "regisf";                  // NOME DO CAMPO DA MATRÍCULA FINAL
  $geraform->re3nome = "selreg";                  // NOME DO CAMPO DE SELEÇÃO DE MATRÍCULAS

  $geraform->lo1nome = "lotai";                  // NOME DO CAMPO DA LOTAÇÃO INICIAL
  $geraform->lo2nome = "lotaf";                  // NOME DO CAMPO DA LOTAÇÃO FINAL
  $geraform->lo3nome = "sellot";                  // NOME DO CAMPO DE SELEÇÃO DE LOTAÇÕES

  $geraform->trenome = "tipo";               // NOME DO CAMPO TIPO DE RESUMO
  $geraform->tfinome = "filtro";                  // NOME DO CAMPO TIPO DE FILTRO

  //$geraform->filtropadrao = "s";                  // TIPO DE FILTRO PADRÃO
  //$geraform->resumopadrao = "g";                  // TIPO DE RESUMO PADRÃO

  $geraform->campo_auxilio_regi = "faixa_regis";  // NOME DO DAS MATRÍCULAS SELECIONADAS
  $geraform->campo_auxilio_lota = "faixa_lotac";  // NOME DO DAS LOTAÇÕES SELECIONADAS

  $geraform->strngtipores = "gml";                // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
                                                  //                                       m - Matrícula,
                                                  //                                       r - Resumo
  $geraform->selecao = true;
  $geraform->onchpad = true;                 // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
  $geraform->gera_form(null,null);
?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <fieldset>
        <legend align="left"><b>RESCISÃO</b></legend>
        <table width="100%">
          <tr>
            <td nowrap title="<?=@$Trh05_recis?>" align="right">
              <?=@$Lrh05_recis?>
            </td>
            <td>
              <?php db_inputdata('rh05_recis',
                                 @$rh05_recis_dia,
                                 @$rh05_recis_mes,
                                 @$rh05_recis_ano,
                                 true,
                                 'text',
                                 $db_opcao,
                                 "onchange='js_validarecis();'",
                                 "",
                                 "",
                                 "parent.js_validarecis();"); ?>
            </td>
            <td nowrap title="<?=@$Trh05_causa?>" align="right">
              <?php db_ancora(@$Lrh05_causa,"js_pesquisarh05_causa(true);",$db_opcao); ?>
            </td>
            <td>
              <?php db_input('rh05_causa',6,$Irh05_causa,true,'text',3,""); ?>
              <?php db_input('r59_descr',40,$Ir59_descr,true,'text',3,""); ?>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td nowrap title="<?=@$Trh05_caub?>" align="right">
              <?php db_ancora(@$Lrh05_caub,"",3); ?>
            </td>
            <td>
              <?php
                db_input('rh05_caub',6,$Irh05_caub,true,'text',3,"");
                db_input('r59_descr1',40,$Ir59_descr1,true,'text',3,"");
                db_input('descr',40,0,true,'hidden',3);
                db_input('descr1',40,0,true,'hidden',3);
                db_input('r59_menos1',4,0,true,'hidden',3);
                db_input('r59_regime',7,0,true,'hidden', 3);
                db_input('fre',7,0,true,'hidden', 3);
                db_input('flt',7,0,true,'hidden', 3);
                db_input('causa',6,0,true,'hidden', 3);
                db_input('caub',6,true,'hidden',3);
                db_input('rescisao',8,0,true,'hidden', 3);
                db_input('taviso',1,0,true,'hidden', 3);
                db_input('aviso',8,0,true,'hidden', 3);
                db_input('remun',6,0,true,'hidden', 3);
                db_input('recis_ano', 4, 0, true, 'hidden', 3);
                db_input('recis_mes', 2, 0, true, 'hidden', 3);
                db_input('recis_dia', 2, 0, true, 'hidden', 3);
                db_input('aviso_ano', 4, 0, true, 'hidden', 3);
                db_input('aviso_mes', 2, 0, true, 'hidden', 3);
                db_input('aviso_dia', 2, 0, true, 'hidden', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh05_taviso?>" align="right">
              <?php db_ancora(@$Lrh05_taviso,"",3); ?>
            </td>
            <td>
              <?php
                if(!isset($rh05_taviso)){
                  $rh05_taviso = 3;
                }
                $x = array("1"=>"Trabalhado","2"=>"Aviso indenizado","3"=>"Sem aviso");
                db_select('rh05_taviso',$x,true,$db_opcao,"onchange='js_disabdata(this.value);'");
              ?>
            </td>
            <td nowrap title="<?=@$Trh05_aviso?>" align="right">
              <?=@$Lrh05_aviso?>
							
            </td>
            <td>
              <?php db_inputdata('rh05_aviso',
                                 @$rh05_aviso_dia,
                                 @$rh05_aviso_mes,
                                 @$rh05_aviso_ano,
                                 true,
                                 'text',
                                 $db_opcao,
                                 "onchange='js_validaaviso(1);'",
                                 "",
                                 "",
                                 "parent.js_validaaviso(1);",
                                 "js_validaaviso(2);",
                                 "js_validaaviso(2);"); ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh05_mremun?>" align="right">
              <?php db_ancora(@$Lrh05_mremun,"",3); ?>
            </td>
            <td>
              <?php
                $rh05_mremun = 0;
                db_input('rh05_mremun',6,$Irh05_mremun,true,'text',$db_opcao,"");
              ?>
            </td>
            <td colspan="2" id="caixa_de_texto">
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <input type="submit" name="processar" value="Processar" <?=($db_botao==false?"disabled":"")?> onclick="return js_verificadados();">
    </td>
  </tr>
</table> 
<script type="text/javascript">
function js_verificadados() {

  x = document.form1;
  if (document.form1.selreg == '') {

    alert("Informe a matrícula do funcionário.");
    x.rh01_regist.focus();
  } else if (x.rh05_recis_dia.value == "" || x.rh05_recis_mes.value == "" || x.rh05_recis_ano.value == "") {

    alert("Informe a data da rescisão.");
    x.rh05_recis.focus();
    x.rh05_recis.select();
  } else if (x.rh05_causa.value == "") {

    alert("Informe a causa da rescisão.");
    x.rh05_causa.focus();
  } else {

    if (document.form1.selreg) {
      if (document.form1.selreg.length > 0) {
        document.form1.fre.value = js_campo_recebe_valores();
      }
    }

    if (document.form1.sellot) {
      if (document.form1.sellot.length > 0) {
        document.form1.flt.value = js_campo_recebe_valores();
      }
    }

    document.form1.causa.value = document.form1.rh05_causa.value;
    document.form1.descr.value = document.form1.r59_descr.value;
    document.form1.descr1.value = document.form1.r59_descr1.value;
    document.form1.caub.value = document.form1.rh05_caub.value;
    document.form1.rescisao.value = document.form1.rh05_recis.value;
    document.form1.recis_dia.value = document.form1.rh05_recis_dia.value;
    document.form1.recis_mes.value = document.form1.rh05_recis_mes.value;
    document.form1.recis_ano.value = document.form1.rh05_recis_ano.value;
    document.form1.taviso.value = document.form1.rh05_taviso.value;
    document.form1.aviso.value = document.form1.rh05_aviso.value;
    document.form1.aviso_dia.value = document.form1.rh05_aviso_dia.value;
    document.form1.aviso_mes.value = document.form1.rh05_aviso_mes.value;
    document.form1.aviso_ano.value = document.form1.rh05_aviso_ano.value;
    document.form1.remun.value = document.form1.rh05_mremun.value;
    document.form1.action = 'pes4_rhpesrescis004.php';
  }
}

function js_validaaviso(opcao) {

  x = document.form1;
  document.getElementById('caixa_de_texto').innerHTML = "";

  if (x.rh05_recis_dia.value != ""
   && x.rh05_recis_mes.value != ""
   && x.rh05_recis_ano.value != ""
   && x.rh05_causa.value != "") {

    if (x.rh05_aviso_dia.value != "" && x.rh05_aviso_mes.value != "" && x.rh05_aviso_ano.value != "") {

      dtadmiss = new Date(x.rh01_admiss_ano.value, (x.rh01_admiss_mes.value - 1), x.rh01_admiss_dia.value);
      dtreciss = new Date(x.rh05_recis_ano.value, (x.rh05_recis_mes.value - 1), x.rh05_recis_dia.value);
      dtavisos = new Date(x.rh05_aviso_ano.value, (x.rh05_aviso_mes.value - 1), x.rh05_aviso_dia.value);

      if (dtreciss < dtadmiss) {

        alert("Data de rescisão não pode ser posterior à data de admissão. Verifique.");
        x.rh05_recis_dia.value = "";
        x.rh05_recis_mes.value = "";
        x.rh05_recis_ano.value = "";
        x.rh05_recis.value = "";
        x.rh05_recis.focus();
      } else if (x.r59_aviso.value == 't' && dtavisos > dtadmiss && dtavisos <= dtreciss) {

        mensagem = "Para Aviso Indenizado - data aviso = rescisao.";
        document.getElementById('caixa_de_texto').innerHTML = "<b><font color='red'>" + mensagem + "</font></b>";
      }
    }
  } else if (opcao == 1) {

    if (x.rh05_causa.value == "") {
      alert("Informe a causa da rescisão.");
    } else {
      alert("Informe a data da rescisão.");
    }

    x.rh05_aviso_dia.value = "";
    x.rh05_aviso_mes.value = "";
    x.rh05_aviso_ano.value = "";
    x.rh05_aviso.value = "";
    x.rh05_aviso.focus();
  }
}

function js_validarecis() {

  x = document.form1;
  if (document.form1.selreg != " ") {

    if (x.rh05_recis_dia.value != "" && x.rh05_recis_mes.value != "" && x.rh05_recis_ano.value != "") {

      anoatual = "<?=$rh02_anousu?>";
      mesatual = "<?=$rh02_mesusu?>";
      anorecis = x.rh05_recis_ano.value;
      mesrecis = x.rh05_recis_mes.value;

      if (mesatual.length < 2) {
        mesatual = "0" + mesatual;
      }

      if (mesrecis.length < 2) {
        mesrecis = "0" + mesrecis;
      }

      anomesatual = new Number(anoatual + mesatual);
      anomesrecis = new Number(anorecis + mesrecis);
      dtadmiss = new Date(x.rh01_admiss_ano.value, (x.rh01_admiss_mes.value - 1), x.rh01_admiss_dia.value);
      dtreciss = new Date(x.rh05_recis_ano.value, (x.rh05_recis_mes.value - 1), x.rh05_recis_dia.value);
      dtatualh = new Date(anoatual, mesatual, 1);

      if (dtreciss < dtadmiss) {

        alert("Data de rescisão não pode ser posterior à data de admissão. Verifique.");
        x.rh05_recis_dia.value = "";
        x.rh05_recis_mes.value = "";
        x.rh05_recis_ano.value = "";
        x.rh05_aviso_dia.value = "";
        x.rh05_aviso_mes.value = "";
        x.rh05_aviso_ano.value = "";
        x.rh05_recis_dia.focus();
      } else if (anoatual > anorecis) {
        alert("ALERTA: Data da rescisão com ano anterior ao atual.");
      } else if (anomesatual < anomesrecis) {

        alert("anomesatual :  " + anomesatual + "  anomesrecis : " + anomesrecis);
        alert("ALERTA: Data da rescisão posterior ao ano / mês atual..");
      }
    }
  } else {

    alert("Informe a matrícula do funcionário.");
    x.rh05_recis_dia.value = "";
    x.rh05_recis_mes.value = "";
    x.rh05_recis_ano.value = "";
    x.rh05_recis.value = "";
    x.rh01_regist.focus();
  }
}

function js_pesquisa() {
  js_OpenJanelaIframe('top.corpo', 'db_iframe_rescisao', 'func_rescisao.php?funcao_js=parent.js_monstrarescisao|r59_anousu|r59_mesusu|r59_regime|r59_causa|r59_caub|r59_menos1', 'Pesquisa', true);
}

function js_pesquisarh05_causa(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo', 'db_iframe_rescisao', 'func_rescisao.php?testarescisao=raf&funcao_js=parent.js_mostrarescisao1|r59_causa|r59_descr|r59_caub|r59_descr1|r59_aviso|r59_menos1|r59_regime', 'Pesquisa', true);
  }
}

function js_mostrarescisao(chave, chave2, chave3, chave4, chave5, chave6, erro) {

  db_iframe_rescisao.hide();
  document.form1.r59_descr.value = chave;
  if (erro == true) {

    document.form1.rh05_causa.focus();
    document.form1.rh05_causa.value = '';
    document.form1.rh05_caub.value = '';
    document.form1.r59_descr1.value = '';
    document.form1.r59_menos1.value = '';
    document.form1.r59_aviso.value = '';
    document.form1.rh05_aviso_dia.value = "";
    document.form1.rh05_aviso_mes.value = "";
    document.form1.rh05_aviso_ano.value = "";
    document.form1.rh05_aviso.value = "";
    document.form1.r59_regime.value = "";
  } else {

    document.form1.rh05_caub.value = chave2;
    document.form1.r59_descr1.value = chave3;
    document.form1.r59_aviso.value = chave4;
    document.form1.r59_menos1.value = chave5;
    document.form1.r59_regime.value = chave6;
  }

  js_disabdata(document.form1.rh05_taviso.value);
  js_validaaviso(1);
}

function js_mostrarescisao1(chave1, chave2, chave3, chave4, chave5, chave6, chave7) {

  db_iframe_rescisao.hide();
  document.form1.rh05_causa.value = chave1;
  document.form1.r59_descr.value = chave2;
  document.form1.rh05_caub.value = chave3;
  document.form1.r59_descr1.value = chave4;
  document.form1.r59_aviso.value = chave5;
  document.form1.r59_menos1.value = chave6;
  document.form1.r59_regime.value = chave7;

  db_iframe_rescisao.hide(); // alterado
  js_disabdata(document.form1.rh05_taviso.value);
  js_validaaviso(1);
  //db_iframe_rescisao.hide(); // original
}

function js_disabdata(valor) {

  x = document.form1;
  if (valor == 1 && x.r59_aviso.value == 't') {

    dtadmiss = new Date(x.rh01_admiss_ano.value, (x.rh01_admiss_mes.value - 1), x.rh01_admiss_dia.value);
    dtreciss = new Date(x.rh05_recis_ano.value, (x.rh05_recis_mes.value - 1), (x.rh05_recis_dia.value - 30));

    if (dtreciss < dtadmiss) {

      document.form1.rh05_aviso_dia.value = x.rh01_admiss_dia.value;
      document.form1.rh05_aviso_mes.value = x.rh01_admiss_mes.value;
      document.form1.rh05_aviso_ano.value = x.rh01_admiss_ano.value;
      document.form1.rh05_aviso.value = x.rh01_admiss_dia.value + '/' + x.rh01_admiss_mes.value + '/' + x.rh01_admiss_ano.value;
    } else {

      month = (dtreciss.getMonth() + 1);
      document.form1.rh05_aviso_dia.value = (dtreciss.getDate() < 10) ? "0" + dtreciss.getDate() : dtreciss.getDate();
      document.form1.rh05_aviso_mes.value = (month < 10) ? "0" + month : month;
      document.form1.rh05_aviso_ano.value = dtreciss.getFullYear();
      document.form1.rh05_aviso.value = (dtreciss.getDate() < 10) ? "0" + dtreciss.getDate() : dtreciss.getDate() + '/' + (month < 10) ? "0" + month : month + '/' + dtreciss.getFullYear();
    }

    document.form1.rh05_aviso_dia.disabled = false;
    document.form1.rh05_aviso_dia.readOnly = false;
    document.form1.rh05_aviso_mes.readOnly = false;
    document.form1.rh05_aviso_ano.readOnly = false;
    document.form1.rh05_aviso_dia.style.backgroundColor = '';
    document.form1.rh05_aviso_mes.style.backgroundColor = '';
    document.form1.rh05_aviso_ano.style.backgroundColor = '';
    js_tabulacaoforms("form1", "rh05_aviso_dia", true, 1, "rh05_aviso_dia", true);
  } else {

    document.form1.rh05_aviso_dia.disabled = true;
    document.form1.rh05_aviso_dia.readOnly = true;
    document.form1.rh05_aviso_mes.readOnly = true;
    document.form1.rh05_aviso_ano.readOnly = true;
    document.form1.rh05_aviso.readOnly = true;
    document.form1.rh05_aviso_dia.style.backgroundColor = '#DEB887';
    document.form1.rh05_aviso_mes.style.backgroundColor = '#DEB887';
    document.form1.rh05_aviso_ano.style.backgroundColor = '#DEB887';
    document.form1.rh05_aviso.style.backgroundColor = '#DEB887';
    document.form1.rh05_aviso_dia.value = "";
    document.form1.rh05_aviso_mes.value = "";
    document.form1.rh05_aviso_ano.value = "";
    document.form1.rh05_aviso.value = "";
    document.form1.rh05_mremun.focus()
    js_tabulacaoforms("form1", "rh01_regist", false, 1, "rh01_regist", false);
  }
}

js_disabdata("<?=($rh05_taviso)?>");

// Esconde os filtros caso a tela seja bloqueada pela liberação do dbpref
<?php if ($db_botao == false) { ?>
  document.getElementById('field-filtros').style.display = 'none';
<?php } ?>
</script>