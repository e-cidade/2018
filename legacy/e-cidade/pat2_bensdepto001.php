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
 *  021 11-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);

$cldbdepart  = new cl_db_depart;
$cldepartdiv = new cl_departdiv;
$clbens      = new cl_bens;
$clcfpatric  = new cl_cfpatri;
$aux_divisao = new cl_arquivo_auxiliar;
$aux_orgao   = new cl_arquivo_auxiliar;
$aux_unidade = new cl_arquivo_auxiliar;
$aux         = new cl_arquivo_auxiliar;

$clrotulo = new rotulocampo;
$clrotulo->label("coddepto");
$clrotulo->label("descrdepto");
$clrotulo->label("nomeresponsavel");
$clrotulo->label("emailresponsavel");
$clrotulo->label("limite");
$cldbdepart->rotulo->label();
$clbens->rotulo->label();

//Verifica se utiliza pesquisa por orgão sim ou não
$t06_pesqorgao = "f";

$resPesquisaOrgao = $clcfpatric->sql_record($clcfpatric->sql_query_file(null,'t06_pesqorgao'));
if($clcfpatric->numrows > 0) {
  $t06_pesqorgao = db_utils::fieldsMemory($resPesquisaOrgao,0)->t06_pesqorgao;
}

?>
<html>
	<head>
		<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Expires" CONTENT="0">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
		<link href="estilos.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">

      var oLancadorDepartamentos = null,
          oLancadorOrgaos = null,
          oLancadorUnidades = null,
          oLancadorDivisoes = null;

    </script>
	</head>
  <body class="body_default">
    <div class="container">

      <form name="form1" method="post" action="">
        <fieldset style="width: 600px">
          <legend>Emissão do Termo de Responsabilidade</legend>
            <fieldset class="separator">
              <legend>Filtros</legend>

                <table class="form-container">

                  <?php if ($t06_pesqorgao == 't') { ?>
                    <tr>
                      <td colspan=2 >
                        <div id="lancadorOrgaos"></div>
                        <script type="text/javascript">
                          var oLancadorOrgaos = new DBLancador('oLancadorOrgaos');

                          oLancadorOrgaos.setNomeInstancia('oLancadorOrgaos');
                          oLancadorOrgaos.setGridHeight(100);
                          oLancadorOrgaos.setLabelAncora('Orgão:');
                          oLancadorOrgaos.setLabelValidacao('Orgão');
                          oLancadorOrgaos.setTextoFieldset('Orgãos');
                          oLancadorOrgaos.setParametrosPesquisa('func_orcorgao.php', ['o40_orgao', 'o40_descr']);
                          oLancadorOrgaos.show($('lancadorOrgaos'));

                          var oFieldset = oLancadorOrgaos.getFieldset();
                          oFieldset.id = "fieldsetOrgao";

                          new DBToogle("fieldsetOrgao", false);

                        </script>
                      </td>
                    </tr>
                    <tr>
                      <td colspan=2 >
                        <div id="lancadorUnidades"></div>
                        <script type="text/javascript">
                          var oLancadorUnidades = new DBLancador('oLancadorUnidades');

                          oLancadorUnidades.setNomeInstancia('oLancadorUnidades');
                          oLancadorUnidades.setGridHeight(100);
                          oLancadorUnidades.setLabelAncora('Unidade:');
                          oLancadorUnidades.setLabelValidacao('Unidade');
                          oLancadorUnidades.setTextoFieldset('Unidades');
                          oLancadorUnidades.setParametrosPesquisa('func_orcunidade.php', ['o41_unidade', 'o41_descr']);
                          oLancadorUnidades.show($('lancadorUnidades'));

                          oLancadorUnidades.setCallbackAncora( function() {

                            if (oLancadorOrgaos.getRegistros().length) {
                              var aOrgaos = new Array();
                              oLancadorOrgaos.getRegistros().each(function(oRegistro) {
                                aOrgaos.push(oRegistro.sCodigo);
                              })

                              oLancadorUnidades.setParametro('orgaos', '(' + aOrgaos.join(',') + ')');
                            }

                          });

                          var oFieldset = oLancadorUnidades.getFieldset();
                          oFieldset.id = "fieldsetUnidade";

                          new DBToogle("fieldsetUnidade", false);

                        </script>
                      </td>
                    </tr>
                    <tr>
                      <td colspan=2 >
                        <div id="lancadorDepartamentos"></div>
                        <script type="text/javascript">
                          var oLancadorDepartamentos = new DBLancador('oLancadorDepartamentos');

                          oLancadorDepartamentos.setNomeInstancia('oLancadorDepartamentos');
                          oLancadorDepartamentos.setGridHeight(100);
                          oLancadorDepartamentos.setLabelAncora('Departamento:');
                          oLancadorDepartamentos.setLabelValidacao('Departamento');
                          oLancadorDepartamentos.setTextoFieldset('Departamentos');
                          oLancadorDepartamentos.setParametrosPesquisa('func_db_depart.php', ['coddepto', 'descrdepto']);
                          oLancadorDepartamentos.show($('lancadorDepartamentos'));

                          oLancadorDepartamentos.setCallbackAncora( function() {

                            if (oLancadorUnidades.getRegistros().length) {
                              var aUnidades = new Array();
                              oLancadorUnidades.getRegistros().each(function(oRegistro) {
                                aUnidades.push(oRegistro.sCodigo);
                              })

                              oLancadorDepartamentos.setParametro('unidades', '(' + aUnidades.join(',') + ')');
                            }

                            if (oLancadorOrgaos.getRegistros().length) {
                              var aOrgaos = new Array();
                              oLancadorOrgaos.getRegistros().each(function(oRegistro) {
                                aOrgaos.push(oRegistro.sCodigo);
                              })

                              oLancadorDepartamentos.setParametro('orgao', aOrgaos.join(','));
                            }
                          });

                          oLancadorDepartamentos.setCallbackBotao( function() {

                            if (oLancadorDepartamentos.getRegistros().length && $('lancadorDivisoes').style.display != '') {

                              $('lancadorDivisoes').style.display = '';

                              var aOrgaos = oLancadorOrgaos.getRegistros(),
                                  aUnidades = oLancadorUnidades.getRegistros();

                              oLancadorOrgaos.setHabilitado(false);
                              oLancadorOrgaos.clearAll();
                              oLancadorOrgaos.show($('lancadorOrgaos'));
                              oLancadorOrgaos.carregarRegistros(
                                  aOrgaos.map(function(oItem) {
                                    return [oItem.sCodigo, oItem.sDescricao]
                                  })
                                );

                              oLancadorUnidades.setHabilitado(false);
                              oLancadorUnidades.clearAll();
                              oLancadorUnidades.show($('lancadorUnidades'));
                              oLancadorUnidades.carregarRegistros(
                                  aUnidades.map(function(oItem) {
                                    return [oItem.sCodigo, oItem.sDescricao]
                                  })
                                );

                              oLancadorOrgaos.getFieldset().id = "fieldsetOrgao";
                              oLancadorUnidades.getFieldset().id = "fieldsetUnidade";

                              new DBToogle("fieldsetOrgao", false);
                              new DBToogle("fieldsetUnidade", false);
                            }
                          });

                          var oFieldset = oLancadorDepartamentos.getFieldset();
                          oFieldset.id = "fieldsetDepartamento";

                          new DBToogle("fieldsetDepartamento", false);
                        </script>
                      </td>
                    </tr>
                    <tr>
                      <td colspan=2 >
                        <div id="lancadorDivisoes" style="display: none"></div>
                        <script type="text/javascript">
                          var oLancadorDivisoes = new DBLancador('oLancadorDivisoes');

                          oLancadorDivisoes.setNomeInstancia('oLancadorDivisoes');
                          oLancadorDivisoes.setGridHeight(100);
                          oLancadorDivisoes.setLabelAncora('Divisão:');
                          oLancadorDivisoes.setLabelValidacao('Divisão');
                          oLancadorDivisoes.setTextoFieldset('Divisões');
                          oLancadorDivisoes.setParametrosPesquisa('func_departdiv.php', ['t30_codigo', 't30_descr']);
                          oLancadorDivisoes.show($('lancadorDivisoes'));

                          oLancadorDivisoes.setCallbackAncora( function() {

                            if (oLancadorDepartamentos.getRegistros().length) {

                              var aDepartamentos = new Array();
                              oLancadorDepartamentos.getRegistros().each(function(oRegistro) {
                                aDepartamentos.push(oRegistro.sCodigo);
                              })

                              oLancadorDivisoes.setParametro('departamentos', aDepartamentos.join(','));
                            }
                          });

                          var oFieldset = oLancadorDivisoes.getFieldset();
                          oFieldset.id = "fieldsetDivisao";

                          new DBToogle("fieldsetDivisao", false);
                        </script>
                      </td>
                    </tr>
                  <?php } else { ?>

                    <tr>
                      <td nowrap title="<?=@$descrdepto?>">
                        <?php db_ancora("Departamento:", "js_coddepto(true);", 1); ?>
                      </td>
                      <td>
                        <?php
                          $Scoddepto = "Departamento";
                          db_input('coddepto', 10, 1,true,'text',1," onchange='js_coddepto(false);'");
                          db_input('descrdepto', 35,$Idescrdepto,true,'text',3,'');
                        ?>
                      </td>
                    </tr>
                  <?php } ?>
                  <!-- Fechamento do if da pesquisa por orgão -->

                  <?php if (isset($coddepto) && $coddepto != "") { ?>
                  <tr>
                    <td nowrap title="Divisão do Departamento">
                      <b>Divisão:</b>
                    </td>
                    <td>
                      <div class="field-size5">
                        <select name='t33_divisao' OnChange="js_divisao();">
                  			  <option value=''>Todas</option>
                  			  <option value='0' <?php  echo $t33_divisao == '0' ? 'SELECTED' : ''?> >Sem divisão</option>
                  			  <?php
                  			    $result = $cldepartdiv->sql_record($cldepartdiv->sql_query_file( null,
                  			                                                                     "t30_codigo, t30_descr",
                  			                                                                     null,
                  			                                                                     "t30_depto=$coddepto" ));
                    			  for ($y = 0;$y < $cldepartdiv->numrows; $y++) {
                     	  	    db_fieldsmemory($result, $y);
                    				  if ($t33_divisao == $t30_codigo) {
                    				   $selected = "SELECTED";
                    				  } else {
                      				 	$selected = "";
                    				  }
                   	  	    ?>
                  			    <option value=<?=@$t30_codigo?> <?=$selected?>> <?=@$t30_descr?></option>
                   		    <?php } ?>
                        </select>
                      </div>
                    </td>
                	</tr>
                  <?php
                    } else {
                      db_input('t33_divisao',10,"",true,'hidden',3,'');
                    }
                  ?>

                  <tr>
                    <td>
                      <label class="bold" for="dtini" id="lbl_dtini">Período de Aquisição:</label>
                    </td>
                    <td>
                    <?php
                      db_inputdata('dtini',null, null, null, true,'text',1,"");
                    ?>
                    <label id="lbl_dtfim" for="dtfim" class="bold">à</label>
                    <?php
                     db_inputdata('dtfim',null, null, null, true,'text',1,"");
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td nowrap title="Filtro de bens"><b>Filtro de Bens:</b></td>
                    <td nowrap title="">
                    <div class="field-size3">
                      <?
                      $matriz = array("G"=>"Geral","I"=>"Intervalo","S"=>"Selecionados");
                  	  db_select("filtro_bens",$matriz,true,1,"onChange='js_filtro_bens();'");
                      ?>
                    </div>
                    </td>
                  </tr>
                  <?
                  if (isset($filtro_bens) && $filtro_bens == "I" || $t06_pesqorgao == 't'){

                  	$display = '';
                  	if ($t06_pesqorgao == 't') {
                  		$display = 'none';
                  	}

                  ?>
                  <tr  style="display: <?=$display; ?>" id="trIntervaloBens">
                    <td nowrap title="Intervalo de bens">
                      <b>Intervalo de Bens: </b>
                    </td>
                    <td nowrap title="<?=$Tt52_bem?>">
                    <? db_ancora("Inicial","js_pesquisa_bem_ini(true);",1);?>
                    <?
                      db_input("t52_bem_ini",8,"",true,"text",1,"onchange='js_pesquisa_bem_ini(false);'");
                    ?>
                	  <b>&nbsp;&nbsp;a&nbsp;&nbsp;<? db_ancora("Final","js_pesquisa_bem_fim(true);",1);?></b>
                    <?
                      db_input("t52_bem_fim",8,"",true,"text",1,"onchange='js_pesquisa_bem_fim(false);'");
                    ?>
                    </td>
                   </tr>
                  <?php } ?>

                  <?php if ((isset($filtro_bens) && $filtro_bens == "S") || $t06_pesqorgao == 't') { ?>
                    <tr>
                      <td nowrap colspan="2" align="center">
                        <div id="lancadorBens" style="<?php echo ($t06_pesqorgao == 't' ? "display: none" : ''); ?>"></div>
                        <script type="text/javascript">
                          var oLancadorBens = new DBLancador('oLancadorBens');

                          oLancadorBens.setNomeInstancia('oLancadorBens');
                          oLancadorBens.setGridHeight(100);
                          oLancadorBens.setLabelAncora('Bem:');
                          oLancadorBens.setLabelValidacao('Bens');
                          oLancadorBens.setTextoFieldset('Bens');
                          oLancadorBens.setParametrosPesquisa('func_bens.php', ['t52_bem', 't52_descr']);
                          oLancadorBens.show($('lancadorBens'));

                          oLancadorBens.setCallbackAncora( function() {

                            if (oLancadorDepartamentos && oLancadorDepartamentos.getRegistros().length) {

                              var aDepartamentos = new Array();
                              oLancadorDepartamentos.getRegistros().each(function(oRegistro) {
                                aDepartamentos.push(oRegistro.sCodigo);
                              })

                              oLancadorBens.setParametro('departamentos', aDepartamentos.join(','));
                            } else if (!oLancadorDepartamentos) {

                              oLancadorBens.setParametro('chave_depto', $F('coddepto'));
                              oLancadorBens.setParametro('chave_div', document.form1.t33_divisao.value);
                            }
                          });

                        </script>
                      </td>
                    </tr>
                  <?php } ?>
                </table>
              </fieldset>

            <fieldset class="separator">
              <legend>Visualização</legend>

                <table class="form-container">
                 <tr>
                  <td nowrap width="1%" title="Características adicionais do bem">
                    <b>Características Adicionais do Bem:&nbsp;&nbsp;</b>
                  </td>
                  <td nowrap>
                    <div class="field-size2">
                      <?php
                        $matriz = array("S"=>"Sim","N"=>"Não");
                    	  db_select("opcao_obs",$matriz,true,1);
                      ?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td nowrap title="Posição do parágrafo">
                    <b>Posição do Parágrafo:</b>
                  </td>
                  <td nowrap title="">
                    <div class="field-size2">
                      <?php
                        $matriz = array("A"=>"Acima","B"=>"Abaixo");
                    	  db_select("posicao",$matriz,true,1);
                      ?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td nowrap>
                    <b>Imprimir Valor:</b>
                  </td>
                  <td nowrap title="">
                    <div class="field-size2">
                      <?php
                        $matriz = array(1=>"Sim", 2=>"Não");
                        db_select("cboValor", $matriz, true, 1);
                      ?>
                    </div>
                  </td>
                </tr>

                <tr>
                  <td nowrap>
                    <label class="bold" for="exibeclassificacao" id="lbl_exibeclassificacao">Exibir Classificação:</label>
                  </td>
                  <td nowrap>
                    <div class="field-size2">
                      <?php

                        $aOpcoes = array(
                            1 => "Sim",
                            2 => "Não"
                          );

                        db_select("exibeclassificacao", $aOpcoes, true, 1);
                      ?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td nowrap>
                    <b>Agrupar Por:</b>
                  </td>
                  <td nowrap title="">
                    <div class="field-size4">
                      <?php
                        $matriz = array(1=>"Nenhum", 4=>"Departamento", 5=>"Departamento/Divisão");
                        if ($t06_pesqorgao == 't') {
                          $matriz = array(1=>"Nenhum", 2=>"Órgão", 3=>"Órgão/Unidade", 4=>"Departamento", 5=>"Departamento/Divisão");
                        }
                        db_select("cboAgrupa", $matriz, true, 1);
                      ?>
                    </div>
                  </td>
                </tr>

                <?php if ($t06_pesqorgao == 't') { ?>

                  <tr>
                    <td>
                      <label class="bold">Modelos Ata:</label>
                    </td>
                    <td>
                      <?php
                        $oDaoModelos = db_utils::getDao("db_documentotemplate");
                        $sSql        = $oDaoModelos->sql_query_file(null,"db82_sequencial,db82_descricao",null,"db82_templatetipo = 7");
                        $rsSql       = $oDaoModelos->sql_record($sSql);

                        db_selectrecord('atamodelo',$rsSql,true,1,'');
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td nowrap align="right" title="Assinatura" colspan="2">
                      <fieldset class="separator">
                        <legend>Assinatura</legend>
                        <?php
                          db_textarea('assinatura', 3,30, 0,true, $dbhidden = 'text',1 );
                        ?>
                      </fieldset>
                    </td>
                  </tr>
                <? } ?>
              </table>
            </fieldset>
          </fieldset>

        <input name="consultar" type="button" value="Termo de Responsabilidade" onclick="return js_mandadados(1);" >
        <input name="atainventario" type="button" value="Ata de Inventário" onclick="return js_mandadados(2);" >
      </form>
      <?php
        db_menu( db_getsession("DB_id_usuario"),
                 db_getsession("DB_modulo"),
                 db_getsession("DB_anousu"),
                 db_getsession("DB_instit") );
      ?>
    </div>
  </body>
</html>
<script>

  function js_divisao() {

    if (document.form1.filtro_bens.value == "I") {

      document.form1.t52_bem_ini.value = "";
      document.form1.t52_bem_fim.value = "";
    }

    document.form1.submit();
  }

  function js_filtro_bens(){

    if (oLancadorDepartamentos) {

      if($F('filtro_bens') == "G") {
        $('trIntervaloBens').style.display = "none";
        $('lancadorBens').style.display = "none";
      }

      if((oLancadorDepartamentos.getRegistros().length == 0 && document.form1.filtro_bens.value != "G")) {

        alert(_M("patrimonial.patrimonio.pat2_bensdepto001.selecione_departamento"));
        $('filtro_bens').value = "G";
        return false;
      } else if ($F('filtro_bens') == "S") {

        $('lancadorBens').style.display = "";
        $('trIntervaloBens').style.display = "none";
        return false;
      } else if ($F('filtro_bens') == "I") {

        $('lancadorBens').style.display = "none";
        $('trIntervaloBens').style.display = "";
        return false;
      }

    } else {

      if ((document.form1.coddepto.value == "" && document.form1.filtro_bens.value != "G")){
  	    alert(_M("patrimonial.patrimonio.pat2_bensdepto001.selecione_departamento"));
  	    $('filtro_bens').value = "G";
  		  return false;
  		} else {
  	     document.form1.submit();
  		}
    }
  }

  function js_pesquisa_bem_ini(mostra) {

    if (mostra == true) {

        if (!oLancadorDepartamentos && document.form1.coddepto.value != "") {
             js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bens',
                                 'func_bens.php?chave_depto='+document.form1.coddepto.value+
                                 '&chave_div='+document.form1.t33_divisao.value+
                                 '&funcao_js=parent.js_mostrabem1_ini|t52_bem',
                                 'Pesquisa de Bens',true);

        } else if (oLancadorDepartamentos && oLancadorDepartamentos.getRegistros().length) {

            var query = "",
                listadepartamentos = new Array(),
                listadivisoes = new Array();

            oLancadorDepartamentos.getRegistros().each(function(oRegistro) {
              listadepartamentos.push(oRegistro.sCodigo)
            });

			      if (listadepartamentos.length) {
			        query +='&departamentos=('+listadepartamentos.join(',')+')';
			      } else {
			        query +='&departamentos=';
			      }

            oLancadorDivisoes.getRegistros().each(function(oRegistro) {
              listadivisoes.push(oRegistro.sCodigo);
            })

			      if (listadivisoes.length) {
			        query += '&divisoes=(' + listadivisoes.join(',') + ')';
			      } else {
			        query += '&divisoes=';
			      }

  			    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bens',
                                 'func_bens.php?chave_depto='+
                                 '&chave_div='+query+
                                 '&funcao_js=parent.js_mostrabem1_ini|t52_bem',
                                 'Pesquisa de Bens',true);

        }

    } else {

      if (document.form1.t52_bem_ini.value != "") {
           js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bens',
                               'func_bens.php?chave_coddepto='+document.form1.coddepto.value+
                               '&chave_div='+document.form1.t33_divisao.value+
                               '&funcao_js=parent.js_mostrabem_ini',
                               'Pesquisa de Bens',false);
      }
    }
  }

  function js_pesquisa_bem_fim(mostra) {

    if (mostra == true) {

      if (!oLancadorDepartamentos && document.form1.coddepto.value != ""){
           js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bens',
                               'func_bens.php?chave_depto='+document.form1.coddepto.value+
                               '&chave_div='+document.form1.t33_divisao.value+
                               '&funcao_js=parent.js_mostrabem1_fim|t52_bem',
                               'Pesquisa de Bens',true);
      } else if (oLancadorDepartamentos && oLancadorDepartamentos.getRegistros().length) {

        var query = "",
              listadepartamentos = new Array(),
              listadivisoes = new Array();

        oLancadorDepartamentos.getRegistros().each(function(oRegistro) {
          listadepartamentos.push(oRegistro.sCodigo)
        });

        if (listadepartamentos.length) {
          query +='&departamentos=('+listadepartamentos.join(',')+')';
        } else {
          query +='&departamentos=';
        }

        oLancadorDivisoes.getRegistros().each(function(oRegistro) {
          listadivisoes.push(oRegistro.sCodigo);
        })

        if (listadivisoes.length) {
          query += '&divisoes=(' + listadivisoes.join(',') + ')';
        } else {
          query += '&divisoes=';
        }

          js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bens',
                               'func_bens.php?chave_depto='+
                               '&chave_div='+query+
                               '&funcao_js=parent.js_mostrabem1_fim|t52_bem',
                               'Pesquisa de Bens',true);

      }

    } else {

      if (document.form1.t52_bem_fim.value != "") {
           js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_bens',
                               'func_bens.php?chave_coddepto='+document.form1.coddepto.value+
                               '&chave_div='+document.form1.t33_divisao.value+
                               '&funcao_js=parent.js_mostrabem_fim',
                               'Pesquisa de Bens',false);
      }
    }
  }

  function js_mostrabem_ini(chave,erro){
     document.form1.t52_bem_ini.value = chave;

     if(erro==true){
         document.form1.t52_bem_ini.focus();
         document.form1.t52_bem_ini.value = "";
     }
  }

  function js_mostrabem1_ini(chave1){

     document.form1.t52_bem_ini.value = chave1;
     if (document.form1.t52_bem_ini.value > document.form1.t52_bem_fim.value && document.form1.t52_bem_fim.value != "") {
          alert(_M("patrimonial.patrimonio.pat2_bensdepto001.intervalo_inicial_maior_final"));
          document.form1.t52_bem_ini.focus();
          document.form1.t52_bem_ini.value = "";
     }

     db_iframe_bens.hide();
  }

  function js_mostrabem_fim(chave,erro){
     document.form1.t52_bem_fim.value = chave;

     if(erro==true){
         document.form1.t52_bem_fim.focus();
         document.form1.t52_bem_fim.value = "";
     }
  }

  function js_mostrabem1_fim(chave1){
     document.form1.t52_bem_fim.value = chave1;
     if (document.form1.t52_bem_fim.value < document.form1.t52_bem_ini.value &&
         document.form1.t52_bem_ini.value != "") {
          alert(_M("patrimonial.patrimonio.pat2_bensdepto001.intervalo_final_menor_inicial"));
  	document.form1.t52_bem_fim.focus();
  	document.form1.t52_bem_fim.value = "";
     }
     db_iframe_bens.hide();
  }

  //-------------------------------------------------------------------------------
  function js_limpacampos(){

    for(i=0;i<document.form1.length;i++){
      if(document.form1.elements[i].type == 'text'){
        document.form1.elements[i].value = '';
      }
    }
  }

  function js_consultasani(){
    var vazio = 0;
    for(i=0;i<document.form1.length;i++){
      if(document.form1.elements[i].type == 'text'){
        if(document.form1.elements[i].value == ""){
          vazio = 1;
        }else{
  	vazio = 0;
  	break;
        }
      }
    }
    if(vazio == 1){
      alert(_M("patrimonial.patrimonio.pat2_bensdepto001.preencha_campos"));
      return false;
    }else{
      jan = window.open('','rel',
                        'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
      jan.moveTo(0,0);
    }
  }

  function js_abreconsulta(chave){
    js_OpenJanelaIframe('','db_iframe_consulta','fis3_consultavist002.php?y70_codvist='+chave,'Pesquisa',true,15);
  }

  function js_mandadados(tipo) {

    var iTipoRelatorio = tipo;
    var query     = "";
    var listabens = "";
    var vir       = "";
    var i;

    var dtini = "";
    var dtfim = "";

    if ($F('dtini').trim() != "") {
  	  dtini = js_formatar($F('dtini'),'d','');
  	}

  	if ($F('dtfim').trim() != "") {
      dtfim = js_formatar($F('dtfim'),'d','');
    }

    query += "dtini="+dtini;
    query += "&dtfim="+dtfim;
    query += "&cboAgrupar="+$F('cboAgrupa');
    query += "&cboValor="+$F('cboValor');

    <? if ($t06_pesqorgao == 't') { ?>
      if ($('atamodelo').length == 0 && iTipoRelatorio == 2) {

        alert(_M("patrimonial.patrimonio.pat2_bensdepto001.nenhum_documento_emissao_ata"));
        return false;

        query += "&atamodelo=";
      } else {
        query += "&atamodelo="+$F('atamodelo');
      }
    <? } ?>

    if ($('assinatura')) {
      query += "&ass="+encodeURIComponent(tagString($F('assinatura')));
    }

    if (oLancadorOrgaos) {

      /**
       * Lê os Orgãos
       */
      if (oLancadorOrgaos.getRegistros().length) {

        var aOrgaos = new Array();

        oLancadorOrgaos.getRegistros().each(function(oRegistro) {
          aOrgaos.push(oRegistro.sCodigo);
        })

        query += '&orgaos=(' + aOrgaos.join(',') + ')';
      } else {
        query += '&orgaos=';
      }

      /**
       * Lê as Unidades
       */
      if (oLancadorUnidades.getRegistros().length) {

        var aUnidades = new Array();

        oLancadorUnidades.getRegistros().each(function(oRegistro) {
          aUnidades.push(oRegistro.sCodigo);
        })

        query += '&unidades=(' + aUnidades.join(',') + ')';
      } else {
        query += '&unidades=';
      }

      /**
       * Lê os Departamentos
       */
      if (oLancadorDepartamentos.getRegistros().length) {

        var aDepartamentos = new Array();

        oLancadorDepartamentos.getRegistros().each(function(oRegistro) {
          aDepartamentos.push(oRegistro.sCodigo);
        })

        query += '&departamentos=(' + aDepartamentos.join(',') + ')';
      } else {
        query += '&departamentos=';
      }

      /**
       * Lê as Divisões
       */
      if (oLancadorDivisoes.getRegistros().length) {

        var aDivisoes = new Array();

        oLancadorDivisoes.getRegistros().each(function(oRegistro) {
          aDivisoes.push(oRegistro.sCodigo);
        })

        query += '&divisoes=(' + aDivisoes + ')';
      } else {
        query += '&divisoes=';
      }

    }

    if (document.form1.filtro_bens.value == "I"){
      if (document.form1.t52_bem_ini.value == "" ||document.form1.t52_bem_fim.value == ""){
   	    alert(_M("patrimonial.patrimonio.pat2_bensdepto001.selecione_intervalo_valido"));
   	    return false;
      }
    }

    if (document.form1.filtro_bens.value == "S") {

      var aBens = new Array();

      if (oLancadorBens.getRegistros().length) {

        oLancadorBens.getRegistros().each(function(oRegistro) {
          aBens.push(oRegistro.sCodigo);
        })
      }

      listabens = aBens.join(',');

   	  if (listabens == "") {
    		alert(_M("patrimonial.patrimonio.pat2_bensdepto001.selecione_bens"));
    		return false;
   	  }
    }

    query += '&depto='         + ((document.form1.coddepto) ? document.form1.coddepto.value : '') +
             '&div='           + document.form1.t33_divisao.value        +
  	         '&opcao_obs='     + document.form1.opcao_obs.value          +
             '&posicao='       + document.form1.posicao.value            +
  	         '&classificacao=' + document.form1.exibeclassificacao.value +
  	         '&filtro_bens='   + document.form1.filtro_bens.value;

    if (document.form1.filtro_bens.value == "I"){
      query += "&t52_bem_ini="+document.form1.t52_bem_ini.value+"&t52_bem_fim="+document.form1.t52_bem_fim.value;
    }

    if (document.form1.filtro_bens.value == "S"){
      query += "&listabens=" + listabens;
    }

    if (iTipoRelatorio == 1) {

  	  jan = window.open('pat2_bensdepto002.php?'+query,'',
  	                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  	  jan.moveTo(0,0);
  	} else if (iTipoRelatorio == 2) {

  	  jan = window.open('pat2_bensata001.php?'+query,'',
                        'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);

  	}
  }

  function js_coddepto(mostra){

      if(mostra==true){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_depart',
                            'func_db_depart.php?funcao_js=parent.js_mostracoddepto1|coddepto|descrdepto',
                            'Pesquisa de Departamentos',true);
      }else{
        coddepto = document.form1.coddepto.value;
        if(coddepto!=""){
          js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_depart',
                              'func_db_depart.php?pesquisa_chave='+coddepto+'&funcao_js=parent.js_mostracoddepto',
                              'Pesquisa de Departamentos',false);
        }else{
  	      document.form1.descrdepto.value='';
        }
      }
  }

  function js_mostracoddepto1(chave1,chave2) {

    document.form1.coddepto.value = chave1;
    document.form1.descrdepto.value = chave2;
    db_iframe_db_depart.hide();
    document.form1.submit();
  }

  function js_mostracoddepto(chave,erro) {

    document.form1.descrdepto.value = chave;

    if(erro==true){
      document.form1.coddepto.focus();
      document.form1.coddepto.value = '';
    }else{
      document.form1.submit();
    }

  }

  $("atamodelo").setAttribute("rel","ignore-css");
  $("atamodelo").addClassName("field-size2");
  $("atamodelodescr").setAttribute("rel","ignore-css");
  $("atamodelodescr").addClassName("field-size9");
</script>
