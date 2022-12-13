<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_classesgenericas.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_gerfcom_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clgerfcom = new cl_gerfcom;
$clrotulo  = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("DBLancador.widget.js, DBAncora.widget.js, dbtextField.widget.js, datagrid.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>
<style>
td {
  white-space: nowrap
}

.fildset-principal table td:first-child {

  width: 90px;
  white-space: nowrap
}

#anobase, #r70_estrut_ini, #r70_estrut_fim, #rh01_regist_ini, #rh01_regist_fim {
  width: 90px;
}

#anofolha {
  width: 50px;
}

#mesfolha {
  width: 30px;
}

#ordem, #semirf, #tipofiltro {
  width: 191px;
}

#resp {
  width: 100%;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="js_limpacampos();">
<table align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
      <form name="form1" method="post" action="pes2_cedulac002.php">
        <?
          db_input('listlotacoes', 10, "", true, "hidden", 3);
          db_input('listmatriculas', 10, "", true, "hidden", 3);
          db_input('listcgms', 10, "", true, "hidden", 3);
        ?>
      <fieldset class="fildset-principal">
        <legend>
          <b>Comprovante de Rendimentos</b>
        </legend>
        <table align="left" border="0" class="table-campos">
            <tr>
                <td nowrap align="left">
                  <b>Ano Base:</b>
                </td>
                <td  align="left" nowrap>
                 <?
                   $anobase = db_anofolha() -1;
                   db_input('anobase', 4, 0, true, 'text', 1, "");
                 ?>
                </td>
            </tr>
            <tr>
                <td nowrap align="left">
                  <b>Ano/Mês:</b>
                </td>
                <td  align="left" nowrap>
                 <?
									 if(!isset($anofolha) || (isset($anofolha) && trim($anofolha) == "")){
									   $anofolha = db_anofolha();
									 }

									 if(!isset($mesfolha) || (isset($mesfolha) && trim($mesfolha) == "")){
									   $mesfolha = db_mesfolha();
									 }

                   db_input('anofolha', 4, 0, true, 'text', 1, "");
                   echo " <b>/</b> ";
                   db_input('mesfolha', 2, 0, true, 'text', 1, "");
                 ?>
                </td>
            </tr>
            <tr>
              <td nowrap title="Tipo Resumo" align="left">
               <b>Tipo Resumo:</b>
              </td>
              <td>
					      <?
					        $tipo        = 'g';
					        $aTipoResumo = array('g'  => 'Geral',
					                             'l'  => 'Lotação',
					                             'm'  => 'Matricula',
					                             'pf' => 'Autônomos/Fornecedores PF',
					                             'pj' => 'Pessoas Jurídicas');
					        db_select("tipo", $aTipoResumo, true, 1, " onchange='js_tiporesumo();' ");
					      ?>
              </td>
            </tr>
            <tr id="box-tipo-filtro" style="display: none;">
              <td nowrap title="Tipo Filtro" align="left">
               <b>Tipo Filtro:</b>
              </td>
              <td>
                <?
                  $tipofiltro  = 'null';
                  $aTipoFiltro = array('null' => 'Selecione',
                                       'i'    => 'Intervalo',
                                       's'    => 'Selecionados');
                  db_select("tipofiltro", $aTipoFiltro, true, 1, " onchange='js_tiporesumo();' ");
                ?>
              </td>
            </tr>
            <tr id="containner-tipo-filtro" style="display: none;">
              <td nowrap align="center" colspan="2">
               <fieldset id="tipo-filtro-lotacao-intervalo" style="display: none;">
	               <legend>
	                 <strong>Lotação</strong>
	               </legend>
	               <table align="left">
	                  <tr>
				              <td nowrap title="Lotação" align="left" width="40px">
				               <? db_ancora("<b>Lotação:</b>","js_pesquisa_r70_estrut_ini(true);",1); ?>
				              </td>
				              <td>
				               <? db_input('r70_estrut', 10, @$Ir70_estrut, true, 'text', 4, " onchange='js_pesquisa_r70_estrut_ini(false);'", "r70_estrut_ini" )  ?>
				                <strong><? db_ancora('à',"js_pesquisa_r70_estrut_fim(true);",1); ?></strong>
				               <? db_input('r70_estrut',10,@$Ir70_estrut,true,'text',4," onchange='js_pesquisa_r70_estrut_fim(false);'","r70_estrut_fim" )  ?>
				              </td>
	                  </tr>
	               </table>
               </fieldset>
               <table align="left" id="tipo-filtro-lotacao-selecionados" style="display: none;">
                  <tr>
                    <td colspan="2">
                      <?
                        $cl_rhlotaestrut                 = new cl_arquivo_auxiliar;
                        $cl_rhlotaestrut->nome_botao     = "db_lanca_rhlotaestrut";
                        $cl_rhlotaestrut->cabecalho      = "<strong>Lotação Selecionadas</strong>";
                        $cl_rhlotaestrut->codigo         = "r70_estrut";
                        $cl_rhlotaestrut->descr          = "r70_descr";
                        $cl_rhlotaestrut->nomeobjeto     = 'rhlotaestrut';
                        $cl_rhlotaestrut->funcao_js      = 'js_mostra';
                        $cl_rhlotaestrut->funcao_js_hide = 'js_mostra1';
                        $cl_rhlotaestrut->sql_exec       = "";
                        $cl_rhlotaestrut->func_arquivo   = "func_rhlotaestrutinstit.php";
                        $cl_rhlotaestrut->nomeiframe     = "db_iframe_itens_rhlotaestrut";
                        $cl_rhlotaestrut->localjan       = "";
                        $cl_rhlotaestrut->onclick        = "";
                        $cl_rhlotaestrut->db_opcao       = 2;
                        $cl_rhlotaestrut->tipo           = 2;
                        $cl_rhlotaestrut->top            = 0;
                        $cl_rhlotaestrut->linhas         = 5;
                        $cl_rhlotaestrut->vwidth         = 500;
                        $cl_rhlotaestrut->funcao_gera_formulario();
                      ?>
                    </td>
                  </tr>
               </table>
               <fieldset id="tipo-filtro-matricula-intervalo" style="display: none;">
	               <legend>
	                 <strong>Matrícula</strong>
	               </legend>
	               <table align="left">
	                  <tr>
	                    <td nowrap title="Matricula" align="left" width="50px">
	                     <? db_ancora("<b>Matricula:</b>","js_pesquisa_rh01_regist_ini(true);",1); ?>
	                    </td>
	                    <td>
	                     <? db_input('rh01_regist',10,@$Irh01_regist,true,'text',4," onchange='js_pesquisa_rh01_regist_ini(false);'","rh01_regist_ini" )  ?>
	                      <strong><? db_ancora('à',"js_pesquisa_rh01_regist_fim(true);",1); ?></strong>
	                     <? db_input('rh01_regist',10,@$Irh01_regist,true,'text',4," onchange='js_pesquisa_rh01_regist_fim(false);'","rh01_regist_fim" )  ?>
	                    </td>
	                  </tr>
	               </table>
               </fieldset>
               <table align="left" id="tipo-filtro-matricula-selecionados" style="display: none;">
                  <tr>
                    <td colspan="2">
                      <?
                        $cl_rhpessoal                 = new cl_arquivo_auxiliar;
                        $cl_rhpessoal->nome_botao     = "db_lanca_rhpessoal";
                        $cl_rhpessoal->cabecalho      = "<strong>Matrícula Selecionadas</strong>";
                        $cl_rhpessoal->codigo         = "rh01_regist";
                        $cl_rhpessoal->descr          = "z01_nome";
                        $cl_rhpessoal->nomeobjeto     = 'rhpessoal';
                        $cl_rhpessoal->funcao_js      = 'js_mostra2';
                        $cl_rhpessoal->funcao_js_hide = 'js_mostra3';
                        $cl_rhpessoal->sql_exec       = "";
                        $cl_rhpessoal->func_arquivo   = "func_rhpessoal.php";
                        $cl_rhpessoal->nomeiframe     = "db_iframe_itens_rhpessoal";
                        $cl_rhpessoal->localjan       = "";
                        $cl_rhpessoal->onclick        = "";
                        $cl_rhpessoal->db_opcao       = 2;
                        $cl_rhpessoal->tipo           = 2;
                        $cl_rhpessoal->top            = 0;
                        $cl_rhpessoal->linhas         = 5;
                        $cl_rhpessoal->vwidth         = 500;
                        $cl_rhpessoal->funcao_gera_formulario();
                      ?>
                    </td>
                  </tr>
               </table>

               <fieldset id="tipo-filtro-cgm-intervalo" style="display: none;">
                 <legend>
                   <strong>Numcgm</strong>
                 </legend>
                 <table align="left">
                    <tr>
                      <td nowrap title="Numcgm" align="left" width="50px">
                       <? db_ancora("<b>Numcgm:</b>","js_pesquisa_z01_numcgm_ini(true);",1); ?>
                      </td>
                      <td>
                       <? db_input('z01_numcgm',10,@$Iz01_numcgm,true,'text',4," onchange='js_pesquisa_z01_numcgm_ini(false);'","z01_numcgm_ini" )  ?>
                        <strong><? db_ancora('à',"js_pesquisa_z01_numcgm_fim(true);",1); ?></strong>
                       <? db_input('z01_numcgm',10,@$Iz01_numcgm,true,'text',4," onchange='js_pesquisa_z01_numcgm_fim(false);'","z01_numcgm_fim" )  ?>
                      </td>
                    </tr>
                 </table>
               </fieldset>
               <table align="left" id="tipo-filtro-cgm-selecionados" style="display: none;">
                  <tr>
                    <td colspan="2" id='lancadorCgm'>
                      <script type="text/javascript">
                        var oLancadorCgm = new DBLancador("oLancadorCgm");
                        oLancadorCgm.setLabelAncora("CGM");
                        oLancadorCgm.setNomeInstancia("oLancadorCgm");
                        oLancadorCgm.setParametrosPesquisa("func_nome.php", ["z01_numcgm", "z01_nome"]);
                        oLancadorCgm.show($("lancadorCgm"));
                      </script>
                    </td>
                  </tr>
               </table>
              </td>
            </tr>
            <tr>
              <td nowrap title="Ordem" align="left">
               <b>Ordem:</b>
              </td>
              <td>
                <?
                  $aOrdem = array('a' => 'Alfabética',
                                  'n' => 'Numérica');
                  db_select("ordem", $aOrdem, true, 1, "");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="Sem Retenção" align="left">
               <b>Sem Retenção:</b>
              </td>
              <td>
                <?
                  $aSemRetencao = array('s' => 'Sim',
                                        'n' => 'Não');
                  db_select("semirf", $aSemRetencao, true, 1, "");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="Responsável" align="left">
                <b>Responsável:</b>
              </td>
              <td  align="left" nowrap>
               <?
                 db_input('resp', 50, 0, true, 'text', 1, "");
               ?>
              </td>
            </tr>
					  <tr>
					    <td colspan="2" title="CNPJ" align="center">
					      <fieldset>
					        <legend>
					          <b>CNPJ</b>
					        </legend>
					        <table>
					          <tr>
					            <td nowrap align="left" title="CNPJ" style="width: 50px;">
					              <b>CNPJ:</b>
					            </td>
					            <td nowrap align="left">
										  <?
										     $instit = db_getsession("DB_instit");
										     $sSqlUnidades  = "select distinct  o41_cnpj, ";
										     $sSqlUnidades .= "       case when o41_cnpj = cgc then nomeinst else o41_descr end as nome_fundo ";
										     $sSqlUnidades .= "  from orcunidade  ";
										     $sSqlUnidades .= "       inner join orcorgao  on o41_orgao  = o40_orgao ";
										     $sSqlUnidades .= "                           and o40_anousu = o41_anousu ";
										     $sSqlUnidades .= "       inner join db_config on codigo     = o41_instit ";
										     $sSqlUnidades .= " where o41_instit = {$instit} ";
										     $sSqlUnidades .= "   and o41_anousu = ".db_getsession("DB_anousu");
										     $result = db_query($sSqlUnidades);
										     db_selectrecord("cnpj", $result, true     , @$db_opcao, "",           "",          "",       "", "","2");
					              ?>
					            </td>
					          </tr>
					        </table>
					      </fieldset>
					    </td>
					  </tr>
        </table>
      </fieldset>
      <table align="center">
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align = "center">
            <input  name="emiterel" id="emiterel" type="button" value="Emitir Relátorio" onclick="return js_emite();" >
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
var sInstit='<?=db_getsession("DB_instit")?>';

function js_limpacampos() {

  $('listlotacoes').value    = '';
  $('listmatriculas').value  = '';
  $('r70_estrut_ini').value  = '';
  $('r70_estrut_fim').value  = '';
  $('rh01_regist_ini').value = '';
  $('rh01_regist_fim').value = '';
  $('z01_numcgm_ini').value  = '';
  $('z01_numcgm_fim').value  = '';

  for (var x = 0; x < $('rhlotaestrut').options.length; x++) {
    $('rhlotaestrut').options[x] = null;
  }

  for (var i = 0; i < $('rhpessoal').options.length; i++) {
    $('rhpessoal').options[i] = null;
  }

  /*for (var y = 0; y < $('cgmpfpj').options.length; y++) {
    $('cgmpfpj').options[y] = null;
  }*/
}

function js_emite() {

  var iLicitacoes = $('rhlotaestrut').options.length;
  var iMatriculas = $('rhpessoal').options.length;
  var aCgms       = oLancadorCgm.getRegistros();
  //var iCgms       = $('cgmpfpj').options.length;

  if ($('r70_estrut_ini').value != "" && $('r70_estrut_fim').value != "") {

    if ($('r70_estrut_fim').value < $('r70_estrut_ini').value) {

      alert('Código da lotação inicial maior que o código final. Verifique!');
      return false;
    }
  }

  if ($('rh01_regist_ini').value != "" && $('rh01_regist_fim').value != "") {

    if ($('rh01_regist_fim').value < $('rh01_regist_ini').value) {

      alert('Código da matricula inicial maior que o código final. Verifique!');
      return false;
    }
  }

  if ($('tipo').value == 'l') {

    if ($('tipofiltro').value == 's') {

		  var sVirgula     = '';
		  var sLiscitacoes = '';
		  for (i = 0; i < iLicitacoes; i++) {

		    sLiscitacoes = sLiscitacoes+sVirgula+$('rhlotaestrut').options[i].value;
		    sVirgula     = ',';
		  }

		  $('listlotacoes').value = sLiscitacoes;
    }
  }

  if ($('tipo').value == 'm') {

    if ($('tipofiltro').value == 's') {

		  var sVirgula    = '';
		  var sMatriculas = '';
		  for (i = 0; i < iMatriculas; i++) {

		    sMatriculas = sMatriculas+sVirgula+$('rhpessoal').options[i].value;
		    sVirgula     = ',';
		  }

		  $('listmatriculas').value = sMatriculas;
    }
  }

  if ($('tipo').value == 'pf' || $('tipo').value == 'pj') {

    if ($('tipofiltro').value == 's') {

      var sVirgula = '';
      var sCgms    = '';
      for (i = 0; i < aCgms.length; i++) {

        sCgms = sCgms + sVirgula + aCgms[i].sCodigo;
        sVirgula     = ',';
      }

      $('listcgms').value = sCgms;
    }
  }

  var novorelatorio = 1;
  jan = window.open('', 'relatorio'+novorelatorio,
                    'width='+(screen.availWidth-5)+
                    ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);

  document.form1.target = 'relatorio'+novorelatorio++;
  document.form1.submit();

  return true;
}

function js_tiporesumo() {

  $('box-tipo-filtro').hide();
  $('containner-tipo-filtro').hide();
  $('tipo-filtro-lotacao-selecionados').hide();
  $('tipo-filtro-lotacao-intervalo').hide();
  $('tipo-filtro-matricula-intervalo').hide();
  $('tipo-filtro-cgm-intervalo').hide();
  $('tipo-filtro-matricula-selecionados').hide();
  $('tipo-filtro-cgm-selecionados').hide();

  js_limpacampos();

  if ($('tipo').value == 'l') {

    $('box-tipo-filtro').show();
    if ($('tipofiltro').value == 'i') {

      $('containner-tipo-filtro').show();
      $('tipo-filtro-lotacao-intervalo').show();
    }

    if ($('tipofiltro').value == 's') {

      $('containner-tipo-filtro').show();
      $('tipo-filtro-lotacao-selecionados').show();
    }
  }

  if ($('tipo').value == 'm') {

    $('box-tipo-filtro').show();
    if ($('tipofiltro').value == 'i') {

      $('containner-tipo-filtro').show();
      $('tipo-filtro-matricula-intervalo').show();
    }

    if ($('tipofiltro').value == 's') {

      $('containner-tipo-filtro').show();
      $('tipo-filtro-matricula-selecionados').show();
    }
  }

  if ($('tipo').value == 'pf' || $('tipo').value == 'pj') {

    $('box-tipo-filtro').show();
    if ($('tipofiltro').value == 'i') {

      $('containner-tipo-filtro').show();
      $('tipo-filtro-cgm-intervalo').show();
    }

    if ($('tipofiltro').value == 's') {

      $('containner-tipo-filtro').show();
      $('tipo-filtro-cgm-selecionados').show();
    }
  }

  if ($('tipofiltro').value != 'null') {
    $('tipofiltro').options[0].disabled = true;
  }
}


function js_pesquisa_r70_estrut_ini(mostra) {

  var lMostra         = mostra;
  var r70_estrut_ini = $('r70_estrut_ini').value;
  var sFuncao         = '&funcao_js=parent.js_mostrar70_estrut_ini&instit='+sInstit;

  var sUrl1           = 'func_rhlotaestrut.php?funcao_js=parent.js_mostrar70_estrut_ini1|r70_estrut&instit='+sInstit;
  var sUrl2           = 'func_rhlotaestrut.php?pesquisa_chave='+r70_estrut_ini+'&tipobusca=1'+sFuncao;

  if (lMostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_rhlotaestrut',sUrl1,'Pesquisa',true);
  } else {

     if (r70_estrut_ini != '') {
        js_OpenJanelaIframe('top.corpo','db_iframe_rhlotaestrut',sUrl2,'Pesquisa',false);
     } else {
       $('r70_estrut_ini').value = '';
     }
  }
}

function js_mostrar70_estrut_ini(chave,erro) {

  if (erro == true) {

    $('r70_estrut_ini').value = '';
    $('r70_estrut_ini').focus();
  }
}

function js_mostrar70_estrut_ini1(chave) {

  $('r70_estrut_ini').value = chave;
  db_iframe_rhlotaestrut.hide();
}

function js_pesquisa_r70_estrut_fim(mostra) {

  var lMostra         = mostra;
  var r70_estrut_fim = $('r70_estrut_fim').value;
  var sFuncao         = '&funcao_js=parent.js_mostrar70_estrut_fim&instit='+sInstit;

  var sUrl1           = 'func_rhlotaestrut.php?funcao_js=parent.js_mostrar70_estrut_fim1|r70_estrut&instit='+sInstit;
  var sUrl2           = 'func_rhlotaestrut.php?pesquisa_chave='+r70_estrut_fim+'&tipobusca=1'+sFuncao;

  if (lMostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_rhlotaestrut',sUrl1,'Pesquisa',true);
  } else {

     if (r70_estrut_fim != '') {
       js_OpenJanelaIframe('top.corpo','db_iframe_rhlotaestrut',sUrl2,'Pesquisa',false);
     } else {
       $('r70_estrut_fim').value = '';
     }
  }
}

function js_mostrar70_estrut_fim(chave,erro) {

  if (erro == true) {

    $('r70_estrut_fim').value = '';
    $('r70_estrut_fim').focus();
  }
}

function js_mostrar70_estrut_fim1(chave1) {

  $('r70_estrut_fim').value = chave1;
  db_iframe_rhlotaestrut.hide();
}

function js_pesquisa_rh01_regist_ini(mostra) {

  var lMostra         = mostra;
  var rh01_regist_ini = $('rh01_regist_ini').value;
  var sFuncao         = '&funcao_js=parent.js_mostrarh01_regist_ini';

  var sUrl1           = 'func_rhpessoal.php?funcao_js=parent.js_mostrarh01_regist_ini1|rh01_regist';
  var sUrl2           = 'func_rhpessoal.php?pesquisa_chave='+rh01_regist_ini+'&tipobusca=1'+sFuncao;

  if (lMostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal',sUrl1,'Pesquisa',true);
  } else {

     if (rh01_regist_ini != '') {
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal',sUrl2,'Pesquisa',false);
     } else {
       $('rh01_regist_ini').value = '';
     }
  }
}

function js_mostrarh01_regist_ini(chave,erro) {

  if (erro == true) {

    $('rh01_regist_ini').value = '';
    $('rh01_regist_ini').focus();
  }
}

function js_mostrarh01_regist_ini1(chave) {

  $('rh01_regist_ini').value = chave;
  db_iframe_rhpessoal.hide();
}

function js_pesquisa_rh01_regist_fim(mostra) {

  var lMostra         = mostra;
  var rh01_regist_fim = $('rh01_regist_fim').value;
  var sFuncao         = '&funcao_js=parent.js_mostrarh01_regist_fim';

  var sUrl1           = 'func_rhpessoal.php?funcao_js=parent.js_mostrarh01_regist_fim1|rh01_regist';
  var sUrl2           = 'func_rhpessoal.php?pesquisa_chave='+rh01_regist_fim+'&tipobusca=1'+sFuncao;

  if (lMostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal',sUrl1,'Pesquisa',true);
  } else {

     if (rh01_regist_fim != '') {
       js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal',sUrl2,'Pesquisa',false);
     } else {
       $('rh01_regist_fim').value = '';
     }
  }
}

function js_mostrarh01_regist_fim(chave,erro) {

  if (erro == true) {

    $('rh01_regist_fim').value = '';
    $('rh01_regist_fim').focus();
  }
}

function js_mostrarh01_regist_fim1(chave1) {

  $('rh01_regist_fim').value = chave1;
  db_iframe_rhpessoal.hide();
}

function js_pesquisa_z01_numcgm_ini(mostra) {

  var lMostra         = mostra;
  var z01_numcgm_ini = $('z01_numcgm_ini').value;
  var sFuncao         = '&funcao_js=parent.js_mostraz01_numcgm_ini';

  var sUrl1           = 'func_nome.php?funcao_js=parent.js_mostraz01_numcgm_ini1|z01_numcgm';
  var sUrl2           = 'func_nome.php?pesquisa_chave='+z01_numcgm_ini+'&tipobusca=1'+sFuncao;

  if (lMostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_nome',sUrl1,'Pesquisa',true);
  } else {

     if (z01_numcgm_ini != '') {
        js_OpenJanelaIframe('top.corpo','db_iframe_nome',sUrl2,'Pesquisa',false);
     } else {
       $('z01_numcgm_ini').value = '';
     }
  }
}

function js_mostraz01_numcgm_ini(chave,erro) {

  if (erro == true) {

    $('z01_numcgm_ini').value = '';
    $('z01_numcgm_ini').focus();
  }
}

function js_mostraz01_numcgm_ini1(chave) {

  $('z01_numcgm_ini').value = chave;
  db_iframe_nome.hide();
}

function js_pesquisa_z01_numcgm_fim(mostra) {

  var lMostra         = mostra;
  var z01_numcgm_fim = $('z01_numcgm_fim').value;
  var sFuncao         = '&funcao_js=parent.js_mostraz01_numcgm_fim';

  var sUrl1           = 'func_nome.php?funcao_js=parent.js_mostraz01_numcgm_fim1|z01_numcgm';
  var sUrl2           = 'func_nome.php?pesquisa_chave='+z01_numcgm_fim+'&tipobusca=1'+sFuncao;

  if (lMostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_nome',sUrl1,'Pesquisa',true);
  } else {

     if (z01_numcgm_fim != '') {
       js_OpenJanelaIframe('top.corpo','db_iframe_nome',sUrl2,'Pesquisa',false);
     } else {
       $('z01_numcgm_fim').value = '';
     }
  }
}

function js_mostraz01_numcgm_fim(chave,erro) {

  if (erro == true) {

    $('z01_numcgm_fim').value = '';
    $('z01_numcgm_fim').focus();
  }
}

function js_mostraz01_numcgm_fim1(chave1) {

  $('z01_numcgm_fim').value = chave1;
  db_iframe_nome.hide();
}
</script>