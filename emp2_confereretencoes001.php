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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clrotulo->label("e80_data");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e80_codage");
$clrotulo->label("e50_codord");
$clrotulo->label("e50_numemp");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_emiss");
$clrotulo->label("e82_codord");
$clrotulo->label("e87_descgera");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
$clrotulo->label("e21_sequencial");
$clrotulo->label("e21_descricao");
$db_opcao = 1;
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    
    <style type="text/css">
    #fieldset_credores, #fieldset_saltes, #fieldset_retencoes, #fieldset_recursos {
    	width: 500px;
    	text-align: center;
    }
    #fieldset_credores table, #fieldset_saltes table, #fieldset_retencoes table, #fieldset_recursos table {
      margin: 0 auto;
    }
    </style>
  </head>
  <body bgcolor=#CCCCCC >
    <form name='form1' id="form1">
			<fieldset style="margin:25px auto 0 auto; width: 500px;">
				<legend>
					<strong>Relatório</strong> 
				</legend>
				<table border="0" align="center">
          <tr>
            <td title="<?=@$Te82_codord?>">
               <? db_ancora(@$Le82_codord,"js_pesquisae82_codord(true);",$db_opcao);  ?>
            </td>
            <td> 
              <? db_input('e82_codord',10,$Ie82_codord,true,'text',$db_opcao," onchange='js_pesquisae82_codord(false);'")  ?>
              <? db_ancora("<b>até:</b>","js_pesquisae82_codord02(true);",$db_opcao);  ?>
              <? db_input('e82_codord2',10,$Ie82_codord,true,'text',$db_opcao, "onchange='js_pesquisae82_codord02(false);'","e82_codord02")?>
            </td>
          </tr>
            
          <tr>
            <td>
              <b>Data Inicial:</b>
            </td>
            <td>
              <?
               db_inputdata("datainicial",null,null,null,true,"text", 1);
              ?>
            	<b>Data Final:</b>
              <?
                db_inputdata("datafinal",null,null,null,true,"text", 1);
              ?>
            </td>
          </tr>
        </table>  
        <table>  
          <tr>
						<td>
				    <?
					    $oFiltroCredor = new cl_arquivo_auxiliar;
						  $oFiltroCredor->cabecalho            = "<strong>Credores</strong>";
						  $oFiltroCredor->codigo               = "z01_numcgm";
						  $oFiltroCredor->descr                = "z01_nome";
						  $oFiltroCredor->isfuncnome           = true;
						  $oFiltroCredor->nomeobjeto           = 'credores';
						  $oFiltroCredor->funcao_js            = 'js_mostraCredor';
						  $oFiltroCredor->funcao_js_hide       = 'js_mostraCredorHide';
						  $oFiltroCredor->func_arquivo         = "func_nome.php";
						  $oFiltroCredor->nomeiframe           = "db_iframe_nomes";
						  $oFiltroCredor->vwidth               = '400';
						  $oFiltroCredor->db_opcao             = 2;
						  $oFiltroCredor->tipo                 = 2;
						  $oFiltroCredor->top 				         = 0;
						  $oFiltroCredor->linhas 				       = 5;
						  $oFiltroCredor->nome_botao           = 'lancarCredor';
						  $oFiltroCredor->lFuncaoPersonalizada = true;
						  $oFiltroCredor->obrigarselecao 			 = false;
						  $oFiltroCredor->funcao_gera_formulario();
				    ?>
				  	</td>    
					</tr>
					
					<tr>
						<td>
				    <?
					    $oFiltroRetencao = new cl_arquivo_auxiliar;
						  $oFiltroRetencao->cabecalho            = "<strong>Retenções</strong>";
						  $oFiltroRetencao->codigo               = "e21_sequencial";
						  $oFiltroRetencao->descr                = "e21_descricao";
						  $oFiltroRetencao->nomeobjeto           = 'retencoes';
						  $oFiltroRetencao->funcao_js            = 'js_mostraRetencao';
						  $oFiltroRetencao->funcao_js_hide       = 'js_mostraRetencaoHide';
						  $oFiltroRetencao->func_arquivo         = "func_retencaotiporec.php";
						  $oFiltroRetencao->nomeiframe           = "db_iframe_retencao";
						  $oFiltroRetencao->vwidth               = '400';
						  $oFiltroRetencao->db_opcao             = 2;
						  $oFiltroRetencao->tipo                 = 2;
						  $oFiltroRetencao->top 				         = 0;
						  $oFiltroRetencao->linhas 				       = 5;
						  $oFiltroRetencao->nome_botao           = 'lancarRetencao';
						  $oFiltroRetencao->lFuncaoPersonalizada = true;
						  $oFiltroRetencao->obrigarselecao       = false;
						  $oFiltroRetencao->funcao_gera_formulario();
				    ?>
				  	</td>    
					</tr>
					
					<tr>
						<td>
				    <?
					    $oFiltroRecursos = new cl_arquivo_auxiliar;
						  $oFiltroRecursos->cabecalho            = "<strong>Recursos</strong>";
						  $oFiltroRecursos->codigo               = "o15_codigo";
						  $oFiltroRecursos->descr                = "o15_descr";
						  $oFiltroRecursos->nomeobjeto           = 'recursos';
						  $oFiltroRecursos->funcao_js            = 'js_mostraRecurso';
						  $oFiltroRecursos->funcao_js_hide       = 'js_mostraRecursoHide';
						  $oFiltroRecursos->func_arquivo         = "func_orctiporec.php";
						  $oFiltroRecursos->nomeiframe           = "db_iframe_orctiporec";
						  $oFiltroRecursos->vwidth 				       = '400';
						  $oFiltroRecursos->db_opcao             = 2;
						  $oFiltroRecursos->tipo                 = 2;
						  $oFiltroRecursos->top 				         = 0;
						  $oFiltroRecursos->linhas 				       = 5;
						  $oFiltroRecursos->nome_botao           = 'lancarRecurso';
						  $oFiltroRecursos->lFuncaoPersonalizada = true;
						  $oFiltroRecursos->obrigarselecao       = false;
						  $oFiltroRecursos->funcao_gera_formulario();
				    ?>
				  	</td>    
					</tr>
					
					<tr>
						<td>
					    <?
						    $oFiltroConta = new cl_arquivo_auxiliar;
							  $oFiltroConta->cabecalho            = "<strong>Contas</strong>";
							  $oFiltroConta->codigo               = "k13_conta";
							  $oFiltroConta->descr                = "k13_descr";
							  $oFiltroConta->nomeobjeto           = 'saltes';
							  $oFiltroConta->funcao_js            = 'js_mostraconta';
							  $oFiltroConta->funcao_js_hide       = 'js_mostraconta1';
							  $oFiltroConta->sql_exec  						= "";
							  $oFiltroConta->func_arquivo 			  = "func_saltes.php";
							  $oFiltroConta->nomeiframe           = "db_iframe_saltes";
							  $oFiltroConta->vwidth               = '400';
							  $oFiltroConta->localjan             = "";
							  $oFiltroConta->db_opcao             = 2;
							  $oFiltroConta->tipo                 = 2;
							  $oFiltroConta->top                  = 0;
							  $oFiltroConta->linhas               = 5;
							  $oFiltroConta->nome_botao           = 'lancarConta';
							  $oFiltroConta->lFuncaoPersonalizada = true;
							  $oFiltroConta->obrigarselecao       = false;
							  $oFiltroConta->funcao_gera_formulario();
					    ?>
				  	</td>    
					</tr>

          <tr>
						<td>
              <fieldset style="margin:0 auto 0 auto; width: 500px;">
                <legend>
                   <strong>Filtros</strong>
                </legend>
                <table align="center">
                  <tr>
                    <td>
                       <b>Quebra:</b>
                    </td>
                    <td>
                      <?
                        $aQuebras = array(1 => "Nenhuma",
                                          2 => "Conta",
                                          3 => "Credor");
                       db_select("group", $aQuebras,true,1,"style='width:10em'");             
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                       <b>Ordem:</b>
                    </td>
                    <td>
                      <?
                        $aOrdem  = array(1 => "Númerica",
                                         2 => "Descrição");
                       db_select("order", $aOrdem,true,1,"style='width:10em'");                 
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                       <b>Tipo:</b>
                    </td>
                    <td>
                      <?
                        $aPagamento  = array("p" => "Pagamento",
                                             "l" => "Liquidacao");
                       db_select("pagamento", $aPagamento,true,1,"style='width:10em'");                 
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                       <b>OP's:</b>
                    </td>
                    <td>
                      <?
                        $aOps  = array("t"  => "Todas",
                                       "p"  => "Pagas",
                                       "np" => "Não Pagas");
                        db_select("ops", $aOps, true, 1, "style='width:10em'");                 
                      ?>
                    </td>
                  </tr>
                </table>
            </fieldset>
					</td>
				</tr>
                  
				<tr>
				  <td style='text-align:center'>
				    <input type='button' value='Emitir' onclick='js_emitir()'>
				  </td>
				</tr> 
          
			</table>
		</fieldset>
    </form>
  </body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>  
<script>

oDBToogleCredores = new DBToogle('fieldset_credores', false);
oDBToogleCredores = new DBToogle('fieldset_retencoes', false);
oDBToogleCredores = new DBToogle('fieldset_recursos', false);
oDBToogleCredores = new DBToogle('fieldset_saltes', false);

function js_emitir() {
   
   
  if ($F('datainicial') == "") {
   
    alert('A data do inicial do pagamento deve ser informada');
    return false;
    
  } 

  var oParametro         = new Object();
  oParametro.datainicial = $F('datainicial');
  oParametro.datafinal   = $F('datafinal');
  oParametro.iPagamento  = $F('pagamento');
  oParametro.sOps        = $F('ops');
  oParametro.iOrdemIni   = $F('e82_codord');
  oParametro.iOrdemFim   = $F('e82_codord02');
  oParametro.order       = $F('order');
  oParametro.group       = $F('group');

  oParametro.sContas     = js_campo_recebe_valores_saltes ();
  oParametro.sCredores   = js_campo_recebe_valores_credores ();
  oParametro.sRecursos   = js_campo_recebe_valores_recursos ();
  oParametro.sRetencoes  = js_campo_recebe_valores_retencoes();
  
  var sFiltros = oParametro.toSource();
  sFiltros     = sFiltros.replace("(","");
  sFiltros     = sFiltros.replace(")","");
  
  var sUrlRelatorio = "emp2_confereretencoes002.php?sFiltros="+sFiltros;
  var jan           = window.open(sUrlRelatorio,
                                  '',
                                  'width='+(screen.availWidth-5)+
                                  ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0); 
}

function js_pesquisae82_codord(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord','Pesquisa',true);
  }else{
    ord01 = new Number(document.form1.e82_codord.value);
    ord02 = new Number(document.form1.e82_codord02.value);
    if(ord01 > ord02 && ord01 != "" && ord02 != ""){
      alert("Selecione uma ordem menor que a segunda!");
      document.form1.e82_codord.focus(); 
      document.form1.e82_codord.value = ''; 
    }
  }
}
function js_mostrapagordem1(chave1){
  document.form1.e82_codord.value = chave1;
  db_iframe_pagordem.hide();
}
//-----------------------------------------------------------
//---ordem 02
function js_pesquisae82_codord02(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem102|e50_codord','Pesquisa',true);
  }else{
    ord01 = new Number(document.form1.e82_codord.value);
    ord02 = new Number(document.form1.e82_codord02.value);
    if(ord01 > ord02 && ord02 != ""  && ord01 != ""){
      alert("Selecione uma ordem maior que a primeira");
      document.form1.e82_codord02.focus(); 
      document.form1.e82_codord02.value = ''; 
    }
  }
}
function js_mostrapagordem102(chave1,chave2){
  document.form1.e82_codord02.value = chave1;
  db_iframe_pagordem.hide();
}
function js_pesquisae60_codemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp','Pesquisa',true);
  }else{
   // js_OpenJanelaIframe('top.corpo','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}
</script>