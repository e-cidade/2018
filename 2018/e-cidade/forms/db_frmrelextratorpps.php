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

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt24');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt26');
$clrotulo->label('DBtxt35');
$clrotulo->label('DBtxt36');

$DBtxt23 = db_anofolha();
$DBtxt24 = db_anofolha();
$DBtxt25 = db_mesfolha();
$DBtxt26 = db_mesfolha();
$DBtxt35 = db_mesfolha();
$DBtxt36 = db_anofolha();
$mes = db_mesfolha();
$ano = db_anofolha();
?>
<form name='form1' method='post' class="container">

  <?php 
    db_input('ano',4,$ano,true,'hidden',1,'');
    db_input('mes',2,$mes,true,'hidden',1,'');
  ?>

  <center>
    <fieldset>
      <legend>
        <strong>Extrato à Previdência</strong>
      </legend>
      <table class="form-container">
        <tr>
          <td>
            <table style="width: 450px;">
              <tr style="display:none">
                <td width="130px"><label for="DBtxt36">Ano / Mês - Folha: </label></td>
                <td>
                  <?php db_input('DBtxt36',4,$IDBtxt36,true,'text',1,'');?> / <?php db_input('DBtxt35',2,$IDBtxt35,true,'text',1,'');
                  ?>
                </td>
              </tr>
              <tr>
                <td><label for="DBtxt23">Ano / Mês Inicial:</label></td>
                <td>
                  <?php db_input('DBtxt23',4,$IDBtxt23,true,'text',1,''); ?> / <?php db_input('DBtxt25',2,$IDBtxt25,true,'text',1,''); ?>
                </td>
              </tr>
              <tr>
                <td><label for="DBtxt24">Ano / Mês Final:</label></td>
                <td>
                  <?php db_input('DBtxt24',4,$IDBtxt24,true,'text',1,''); ?> / <?php db_input('DBtxt26',2,$IDBtxt26,true,'text',1,'');?>
                </td>
              </tr>
              <tr style="display:none">
                <td><label for="sTipoEmissao">Dados Cadastrais:</label></td>
                <td>
                  <?php
                    $xcad = array("p"=>"Período", "a"=>"Atual");
                    db_select('sTipoEmissao',$xcad,true,4,"");
                  ?>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <?php 
                    $sInstituicoesSelecionadas = db_getsession("DB_instit");
                    db_input('sInstituicoesSelecionadas',10,4,true,'hidden',3,'');
                  ?>
                  <div id="ctnGridInstituicao"></div>
                </td>
              </tr>
              <tr>
                <td><label for="tipo_res">Tipo Resumo:</label></td>

                <td>
                  <?php
                    $ordem_resumo = array("g" => "Geral",
                    		                  "m" => "Matrícula",
                    		                  "l" => "Lotação",
                    		                  "t" => "Locais de Trabalho");

                    db_select('tipo_res', $ordem_resumo,true,2,"onchange=js_mudaresumo(this.value)");
                  ?>
                </td>
              </tr>
              <tr id='tipoFiltro'>
                <td><label for="tipo_fil">Tipo Filtro:</label></td>

                <td>
                  <?php
                    $ordem_filtro = array("0" => " ----------- ",
                    		                  "i" => "Intervalo",
                    		                  "s" => "Selecionados");

                    db_select('tipo_fil', $ordem_filtro,true,2,"onchange='js_mostratag(this.value, document.form1.tipo_res);'");
                  ?>
                </td>
              </tr>

              <tr id='tipoIntervaloMatric'>
                <td align='right'>
                  <?php
                    db_ancora('<label for="mati">Matrícula:</label>',"js_pesquisalink(true, 'mati')",1);
                  ?>
                </td>
                <td align='left'>
                  <?php                               
                    db_input('mati',10,4,true,'text',1,'');
                    db_ancora("<label for='matf'>a</label>","js_pesquisalink(true,'matf')",1);
                    db_input('matf',10,4,true,'text',1,'');
                  ?>
                </td>
              </tr>

              <tr id='tipoIntervaloLotacao'>
                <td align='right'><?php db_ancora('<label for="lotai">Lotação:</label>',"js_pesquisalink(true,'lotai')",1); ?></td>
                <td align='left'>
                  <?php                                                                                                               
                    db_input('lotai',10,4,true,'text',1,'');
                    db_ancora("<label for='lotaf'>a</label>","js_pesquisalink(true,'lotaf')",1);
                    db_input('lotaf',10,4,true,'text',1,'');
                  ?>
                </td>
              </tr>

              <tr id='tipoIntervaloLocal'>
                <td align='right'><?php db_ancora('<label for="locai">Local:</label>',"js_pesquisalink(true, 'locai')",1); ?></td>
                <td align='left'>
                  <?php                                                                                                               
                    db_input('locai',10,4,true,'text',1,'');
                    db_ancora("<label for='locaf'>a</label>","js_pesquisalink(true,'locaf')",1);
                    db_input('locaf',10,4,true,'text',1,'');
                  ?>
                </td>
              </tr>
            </table>

            <table id='tipoSelecionaMatric'>
              <tr>
                <td>
                  <?php
                    $aux->cabecalho = "<strong>Matrículas</strong>";
                    $aux->codigo = "rh01_regist"; //chave de retorno da func
                    $aux->descr  = "z01_nome";   //chave de retorno
                    $aux->nomeobjeto = 'tipoSelMatric';
                    $aux->funcao_js = 'js_mostraselteste1';
                    $aux->funcao_js_hide = 'js_mostrateste2';
                    $aux->sql_exec  = "";
                    $aux->func_arquivo = "func_rhpessoal.php";  //func a executar
                    $aux->passar_query_string_para_func='&lTodos=true&mInstituicoes=';
                    $aux->parametros="+document.form1.sInstituicoesSelecionadas.value";
                    $aux->nomeiframe = "db_iframe_rhpessoal";
                    $aux->localjan = "";
                    $aux->nome_botao = "lanca_Matric";
                    $aux->onclick = "";
                    $aux->db_opcao = 2;
                    $aux->tipo = 2;
                    $aux->top = '';
                    $aux->linhas = 4;
                    $aux->vwhidth = 400;
                    $aux->funcao_gera_formulario();
                  ?>
                </td>
              </tr>
            </table>

            <table id='tipoSelecionaLotacao'>
              <tr>
                <td>
                  <?php
                    $aux->cabecalho = "<strong>Lotações</strong>";
                    $aux->codigo = "r70_codigo"; //chave de retorno da func
                    $aux->descr  = "r70_descr";   //chave de retorno
                    $aux->nomeobjeto = 'tipoSelLota';
                    $aux->funcao_js = 'js_mostraselteste3';
                    $aux->funcao_js_hide = 'js_mostrateste4';
                    $aux->sql_exec  = "";
                    $aux->func_arquivo = "func_rhlotaestrut.php";  //func a executar
                    $aux->passar_query_string_para_func='&lTodos=true&mInstituicoes=';
                    $aux->parametros="+document.form1.sInstituicoesSelecionadas.value";
                    $aux->nomeiframe = "db_iframe_rhlotaestrut";
                    $aux->localjan = "";
                    $aux->nome_botao = "lanca_Lota";
                    $aux->onclick = "";
                    $aux->db_opcao = 2;
                    $aux->tipo = 2;
                    $aux->top = '';
                    $aux->linhas = 4;
                    $aux->vwhidth = 400;
                    $aux->funcao_gera_formulario();
                  ?>
                </td>
              </tr>
            </table>

            <table id='tipoSelecionaLocal'>
              <tr>
                <td>
                  <?php
                    $aux->cabecalho = "<strong>Locais de Trabalho</strong>";
                    $aux->codigo = "rh55_estrut"; //chave de retorno da func
                    $aux->descr  = "rh55_descr";   //chave de retorno
                    $aux->nomeobjeto = 'tipoSelLoca';
                    $aux->funcao_js = 'js_mostrasel5';
                    $aux->funcao_js_hide = 'js_mostrateste6';
                    $aux->sql_exec  = "";
                    $aux->func_arquivo = "func_rhlocaltrab.php";  //func a executar
                    $aux->passar_query_string_para_func='&lTodos=true&mInstituicoes=';
                    $aux->parametros="+document.form1.sInstituicoesSelecionadas.value";
                    $aux->nomeiframe = "db_iframe_rhlocaltrab";
                    $aux->localjan = "";
                    $aux->nome_botao = "lanca_Local";
                    $aux->onclick = "";
                    $aux->db_opcao = 2;
                    $aux->tipo = 2;
                    $aux->top = '';
                    $aux->linhas = 4;
                    $aux->vwhidth = 400;
                    $aux->funcao_gera_formulario();
                  ?>
                </td>
              </tr>
            </table>

            <table style="width: 450px;">
              <tr>
                <td width="130px"><label for="ordem">Ordem:</label></td>
                <td>
                  <?php
                    $ordemalfnum = array("a"=>"Alfabética ", "n"=>"Numérica");
                    db_select('ordem', $ordemalfnum,true,2,"");
                  ?>
                </td>
              </tr>
              <tr>
                <td><label for="prev">Tabela de Previdência:</label></td>
                <td>
                  <?php

                    $sSqlPrevidencia = $clinssirf->sql_query_file(null,null,'distinct (cast(r33_codtab as integer)-2) as r33_codtab, (cast(r33_codtab as integer)-2) || \' - \' ||array_to_string(array_accum(distinct trim(r33_nome)), \', \') as nome','r33_codtab','r33_instit = '.db_getsession('DB_instit').' and r33_anousu = '.$DBtxt23.' and r33_mesusu = '.$DBtxt25.' and r33_codtab > 2 group by r33_codtab');
                    $rsPrevidencia   = db_query($sSqlPrevidencia);
                    db_selectrecord('prev', $rsPrevidencia, true, 2, "", "", "", "", "", 1);
                  ?>
                </td>
              </tr>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="emite" type="button" id="emite" value="Emitir" onclick="js_emite();">
    </center>
</form>
<script>
  
  var oViewInstituicao;

  (function() {

    oViewInstituicao = new DBViewInstituicao('oViewInstituicao', $('ctnGridInstituicao'));
    oViewInstituicao.iWidth = 422;
    oViewInstituicao.show();
    oViewInstituicao.addCallBackClickLinha(function(iIndice, iCodigo, oItem) {
      document.form1.sInstituicoesSelecionadas.value = getInstituicoesMarcadas().join(',');
      js_recarregarComboTabelaPrevidencia(document.form1.sInstituicoesSelecionadas.value);
    });
    var clickCheckbox = oViewInstituicao.oGridInstituicao.selectSingle;
    oViewInstituicao.oGridInstituicao.selectSingle = function(oCheckbox,sRow,oRow) {
      clickCheckbox(oCheckbox,sRow,oRow);
      document.form1.sInstituicoesSelecionadas.value = getInstituicoesMarcadas().join(',');
      js_recarregarComboTabelaPrevidencia(document.form1.sInstituicoesSelecionadas.value);
    };
  })();

  var listaSeleciona="";
  var docf = document.form1;  
  document.getElementById('tipoFiltro').style.display = "none";
  
  function js_mostratag(tipof, tipor){
   
    switch (tipor.value){
      case "m":
          if (tipof == 'i'){
             js_escondetag('tipoIntervaloMatric');
          }else if(tipof == 's'){
             js_escondetag('tipoSelecionaMatric');
          }else{
             js_escondetag();   
          }
      break;
      case "l":
          if (tipof == 'i'){
             js_escondetag('tipoIntervaloLotacao');             
          }else if(tipof == 's'){
             js_escondetag('tipoSelecionaLotacao');
          }else{
             js_escondetag();   
          }
      break;
      case "t":
          if(tipof == 'i'){
            js_escondetag('tipoIntervaloLocal');              
          }else if(tipof == 's'){
            js_escondetag('tipoSelecionaLocal');
          }else{
             js_escondetag();   
          }
      break;
      case "g":
          js_escondetag();
      break;
    }
  }

  function js_mudaresumo(tipor){
    
    if(tipor == "g"){
       document.getElementById('tipoFiltro').style.display = "none";
       js_escondetag(); 
    }else{
       document.getElementById('tipoFiltro').style.display = "";
       docf.tipo_fil.value = "0";
       js_escondetag(); 
    }   
  }

  function getInstituicoesMarcadas () {

    var aInstituicoes   = [];
    oViewInstituicao.getInstituicoesSelecionadas().each(function(oInstituicao, iIndex) {
      aInstituicoes.push(oInstituicao.codigo);
    });
    return aInstituicoes;
  } 

  function js_pesquisalink(mostra, tipo){
  
    if(tipo == 'mati' || tipo == 'matf' ){
       tipoRes = eval('document.form1.'+tipo);
       funcRes = 'func_rhpessoal';
       campRes = 'rh01_regist';
    }else if(tipo == 'lotai' || tipo == 'lotaf' ){  
       tipoRes = eval('document.form1.'+tipo);
       funcRes = 'func_rhlotaestrut';
       campRes = 'r70_codigo';
    }else{
       tipoRes = eval('document.form1.'+tipo);
       funcRes = 'func_rhlocaltrab';
       campRes = 'rh55_estrut';
       campf = tipoRes.value;
    }

    document.form1.sInstituicoesSelecionadas.value = getInstituicoesMarcadas().join(',');
    
    if(mostra==true){
      js_OpenJanelaIframe('','db_iframe_rh',''+funcRes+'.php?lTodos=true&mInstituicoes='+document.form1.sInstituicoesSelecionadas.value+'&funcao_js=parent.js_abreconsulta|'+campRes+'','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('','db_iframe_rh',''+funcRes+'.php?lTodos=true&mInstituicoes='+document.form1.sInstituicoesSelecionadas.value+'&pesquisa_chave='+tipoRes.value+'&funcao_js=parent.js_mostra','Pesquisa','false');
    }
  }
 
  function js_abreconsulta(chave){
    tipoRes.value = chave;
    db_iframe_rh.hide();
  }

  function js_mostra(chave,erro){
    tipoRes.value = chave;
    if(erro==true){
      tipoRes.focus();
      tipoRes.value = '';
    }
  }
 
  function js_escondetag(tag){
    document.getElementById('tipoIntervaloLocal').style.display = "none";
    document.getElementById('tipoIntervaloLotacao').style.display = "none";
    document.getElementById('tipoIntervaloMatric').style.display = "none";
    document.getElementById('tipoSelecionaMatric').style.display = "none";
    document.getElementById('tipoSelecionaLocal').style.display = "none";
    document.getElementById('tipoSelecionaLotacao').style.display = "none";

    if(tag){
      document.getElementById(tag).style.display = "";
    }

  }

  function js_recarregarComboTabelaPrevidencia(sInstituicoes) {

    if(sInstituicoes) {

      AjaxRequest.create('pes2_relextratorpps.RPC.php', {exec: 'getTabelasPrevidencia', sInstituicoes: sInstituicoes}, function(response) {

        if(response.erro) {

          alert(response.mensagem);
          return;
        }

        iQtdeOpcoes = $('prev').options.length;
        for (var iIndOpcoesTabelaPrevidencia = 0; iIndOpcoesTabelaPrevidencia < iQtdeOpcoes; iIndOpcoesTabelaPrevidencia++) {
          $('prev').options[0].remove();
        };

        for(var opcaoTabelaPrevidencia of response.aTabelasPrevidencia) {

          var oOptionTabelaPrevidencia           = document.createElement('option');
              oOptionTabelaPrevidencia.value     = opcaoTabelaPrevidencia.codigo;
              oOptionTabelaPrevidencia.innerHTML = opcaoTabelaPrevidencia.nome;

          $('prev').add(oOptionTabelaPrevidencia);
        }
      }).setMessage('Carregando tabelas de previdência...')
        .execute();
    }
  }

  function js_retornalista(tag){
  vir="";
  //alert(getElementById(tag).[0].value);
  listaSeleciona = ""; 
   for(x=0;x<document.getElementById(tag).length;x++){
       listaSeleciona+=vir+document.getElementById(tag).options[x].value;
       vir=",";
    }
  }
  
</script>