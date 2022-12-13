<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_liborcamento.php");
require_once("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo;
$clrotulo->label("ac16_sequencial");
$clrotulo->label("ac16_resumoobjeto");
$clrotulo->label("ac16_datainicio");
$clrotulo->label("ac16_datafim");
$clrotulo->label("ac08_sequencial");
$clrotulo->label("ac08_descricao");
$clrotulo->label("ac17_sequencial");
$clrotulo->label("descrdepto");
$clrotulo->label("ac02_sequencial");
$clrotulo->label("ac08_descricao");
$clrotulo->label("ac50_descricao");
$clrotulo->label("z01_nome");
$clrotulo->label("ac46_sequencial");
$clrotulo->label("ac46_descricao");

db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("DBLancador.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("classes/dbViewAvaliacoes.classe.js");
db_app::load("widgets/windowAux.widget.js");
db_app::load("widgets/dbmessageBoard.widget.js");
db_app::load("dbcomboBox.widget.js");
db_app::load("widgets/DBAncora.widget.js");
db_app::load("widgets/dbtextField.widget.js");

$oDepartamento = new DBDepartamento(db_getsession("DB_coddepto"));
$iDepartamento = $oDepartamento->getCodigo();
$sDepartamento = $oDepartamento->getNomeDepartamento();

$oDaoAcordoClassificacao = new cl_acordoclassificacao();
$sSqlClassificacoes = $oDaoAcordoClassificacao->sql_query_file();
$rsClassificacoes = $oDaoAcordoClassificacao->sql_record($sSqlClassificacoes); 
$aClassificacoes = array();

if ($oDaoAcordoClassificacao->numrows > 0) {

  $aClassificacoes[0] = "Todas";
  foreach (db_utils::getCollectionByRecord($rsClassificacoes) as $oDadosClassificacao) {
    $aClassificacoes[$oDadosClassificacao->ac46_sequencial] = $oDadosClassificacao->ac46_descricao;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<style>
 .fora {background-color: #d1f07c;}
  #ac46_sequencial{
    width: 100%; 
 }
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <center>
    <form name="form1" method="post" action="con2_relatorioacordos002.php" target='relatorioacordo'>
      <input type="hidden" id="listaacordonatureza" name="listaacordonatureza" value="">
      <input type="hidden" id="listaacordogrupo"    name="listaacordogrupo"    value="">
      <input type="hidden" id="listacontratado"     name="listacontratado"     value="">
      <input type="hidden" id="situacaodescricao"   name="situacaodescricao"   value="">
      <input type="hidden" id="origemdescricao"     name="origemdescricao"     value="">
      <input type="hidden" id="tipodescricao"       name="tipodescricao"       value="">
      <input type="hidden" id="ordemdescricao"      name="ordemdescricao"      value="">
    <table style="margin-top: 20px;">
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Filtro Acordos</b>
            </legend>
            <table border="0" width="100%">
            <tr>
              <td nowrap title="<?php echo $Tac16_sequencial; ?>" width="130">
                 <?php db_ancora($Lac16_sequencial,"js_acordo(true);",1); ?>
              </td>
              <td colspan="3">  
                <?php
                  db_input('ac16_sequencial', 10, $Iac16_sequencial, true, 'text', 1, "onchange='js_acordo(false);'");
                  db_input('ac16_resumoobjeto', 40, $Iac16_resumoobjeto, true, 'text', 3);
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="" width="130">
                 <?php 
                  db_ancora("<b>Comissão:</b>","js_pesquisaAcordoComissao(true);",1);
                 ?>
              </td>
              <td colspan="3">  
                <?php
                  db_input('ac08_sequencial', 10, $Iac08_sequencial, true, 'text', 1, "onchange='js_pesquisaAcordoComissao(false);'");
                  db_input('ac08_descricao', 40, $Iac08_descricao, true, 'text', 3);
                ?>
              </td>
            </tr>      
            
		        <tr>
		          <td nowrap title="<?=@$Tac50_descricao?>">
		            <?php
		              db_ancora('<b>Categoria:</b>', "onchange=js_pesquisaac50_descricao(true)", 1);
		            ?>
		          </td>
		          <td>
		            <?
		              db_input('ac50_sequencial', 10, $Iac50_descricao, true, 'text', 1,
		                       "onchange=js_pesquisaac50_descricao(false)");
		              db_input('ac50_descricao', 40, $Iac50_descricao, true, 'text', 3);
		            ?>
		          </td>
		        </tr>
            
		        <tr>
		          <td nowrap title="<?php echo $Tac46_sequencial; ?>">
                <strong>Classificação:</strong>
		          </td>
		          <td>
		            <?php db_select('ac46_sequencial', $aClassificacoes, true, 1); ?>
		          </td>
		        </tr>
                  
            <tr>
              <td align="left" title="<?php echo @$Tac16_datainicio?>">
                <?=@$Lac16_datainicio?>
              </td>
              <td align="left">
                <?
                  db_inputdata('ac16_datainicio',@$ac16_datainicio_dia,@$ac16_datainicio_mes,@$ac16_datainicio_ano,true,
                               'text',1);
                ?>
              </td>
              <td align="right" title="<?php echo @$Tac16_datafim?>">
                <?php echo @$Lac16_datafim; ?>
              </td>
              <td align="right">
                <?
                  db_inputdata('ac16_datafim',@$ac16_datafim_dia,@$ac16_datafim_mes,@$ac16_datafim_ano,true,
                               'text',1)
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="" width="130">
                 <b>Situação:</b>
              </td>
              <td colspan="3">  
                <?php
                  $aSituacao    = array();
                  $aSituacao[0] = "Todas";
                  $oDaoAcordoSituacao = db_utils::getDao("acordosituacao");
                  $sSql  = $oDaoAcordoSituacao->sql_query_file(null, "ac17_sequencial,ac17_descricao", null, '');
                  $rsSql = $oDaoAcordoSituacao->sql_record($sSql);
                  if ($rsSql !== false) {
                                  
                    for ($iInd = 0; $iInd < $oDaoAcordoSituacao->numrows; $iInd++) {
                    
                      $chave             = db_utils::fieldsMemory($rsSql,$iInd)->ac17_sequencial;
                      $aSituacao[$chave] = db_utils::fieldsMemory($rsSql,$iInd)->ac17_descricao; 
                    }
                  }
                  db_select('ac16_acordosituacao', $aSituacao, true, 1, "style='width: 100%;'");              
                ?>
              </td>
            </tr> 
            <tr>
              <td nowrap title="" width="130">
                 <b>Origem:</b>
              </td>
              <td colspan="3">  
                <?php
                  $aOrigem    = array();
                  $aOrigem[0] = "Todas";
                  $oDaoAcordoOrigem = db_utils::getDao("acordoorigem");
                  $sSql  = $oDaoAcordoOrigem->sql_query_file(null, "ac28_sequencial,ac28_descricao", null, '');
                  $rsSql = $oDaoAcordoOrigem->sql_record($sSql);
                  if ($rsSql !== false) {
                                  
                    for ($iInd = 0; $iInd < $oDaoAcordoOrigem->numrows; $iInd++) {
                    
                      $chave           = db_utils::fieldsMemory($rsSql,$iInd)->ac28_sequencial;
                      $aOrigem[$chave] = db_utils::fieldsMemory($rsSql,$iInd)->ac28_descricao; 
                    }
                  }
                  db_select('ac16_origem', $aOrigem, true, 1, "style='width: 100%;'");              
                ?>
              </td>
            </tr> 
            <tr>
              <td nowrap title="" width="130">
                 <b>Tipo de Acordo:</b>
              </td>
              <td colspan="3">  
                <?PHP
                  $aTipoAcordo    = array();
                  $aTipoAcordo[0] = "Todos";
                  $oDaoAcordoTipo = db_utils::getDao("acordotipo");
                  $sSql  = $oDaoAcordoTipo->sql_query_file(null, "ac04_sequencial,ac04_descricao", null, '');
                  $rsSql = $oDaoAcordoTipo->sql_record($sSql);
                  if ($rsSql !== false) {
                                  
                    for ($iInd = 0; $iInd < $oDaoAcordoTipo->numrows; $iInd++) {
                    
                      $chave               = db_utils::fieldsMemory($rsSql,$iInd)->ac04_sequencial;
                      $aTipoAcordo[$chave] = db_utils::fieldsMemory($rsSql,$iInd)->ac04_descricao; 
                    }
                  }
                  db_select('acordotipo', $aTipoAcordo, true, 1, "style='width: 100%;'");              
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="" width="100">
                 <b>Listar Itens:</b>
              </td>
              <td colspan="3">  
                <?php
                  $aListarItens = array('N'=>'Não',
                                        'S'=>'Sim');
                  db_select('listaitens', $aListarItens, true, 1, "style='width: 100%;'");              
                ?>
              </td>
            </tr>    
            <tr>
              <td nowrap title="" width="100">
                 <b>Listar Movimentação:</b>
              </td>
              <td colspan="3">  
                <?php
                  $aListarMovimentacao = array('N'=>'Não',
                                               'S'=>'Sim');
                  db_select('listamovimentacao', $aListarMovimentacao, true, 1, "style='width: 100%;'");              
                ?>
              </td>
            </tr> 
            <tr>
              <td nowrap title="" width="100">
                 <b>Listar Empenho:</b>
              </td>
              <td colspan="3">  
                <?php
                  $aListarEmpenho = array('N'=>'Não',
                                          'S'=>'Sim');
                  db_select('listaautorizacao', $aListarEmpenho, true, 1, "style='width: 100%;'");              
                ?>
              </td>
            </tr> 
            <tr>
              <td nowrap title="" width="100">
                 <b>Ordem:</b>
              </td>
              <td colspan="3">  
                <?
                  $aOrdem = array(1=>'Data da Criação',
                                  2=>'Contratado',
                                  3=>'Número do Contrato',
                                  4=>'Vigência');
                  db_select('ordem', $aOrdem, true, 1, "style='width: 100%;'");              
                ?>
              </td>
            </tr> 
            
          </table>
          
          <input type='hidden' id='sListaDepto' name='sListaDepto' />
					<div id='ctnLancador'></div>
					      
          </fieldset>
        </td>
      </tr>
      <tr>
        <td style="text-align: center;">
          <input type='submit' value='Gerar Relatório' onclick="return js_gerarRelatorio();">
        </td>
      </tr>
    </table>
    </form>
  </center>
</body>
</html>
<script type="text/javascript">

function js_listaDepartamentos() {

	var oLista  = oLancadorDepartamentos.getRegistros();
  var sLista  = '';
  var sSepara = '';
  
	oLista.each( 
              function (oDado, iInd) {       

                sLista +=  sSepara + oDado.sCodigo;
                sSepara = ", ";
            });
$('sListaDepto').value = sLista;
}

                
                
function js_criaLancador(){

	oLancadorDepartamentos = new DBLancador("oLancadorDepartamentos");
	oLancadorDepartamentos.setNomeInstancia("oLancadorDepartamentos");
	oLancadorDepartamentos.setLabelAncora("Departamentos: ");
	oLancadorDepartamentos.setParametrosPesquisa("func_departamento.php", ['coddepto', 'descrdepto']);
	oLancadorDepartamentos.show($("ctnLancador"));

	$('txtCodigooLancadorDepartamentos').value = <?php echo $iDepartamento?>;
	$('txtDescricaooLancadorDepartamentos').value = '<?php echo $sDepartamento?>';
	
	oLancadorDepartamentos.lancarRegistro();

	$('txtCodigooLancadorDepartamentos').value = "";
	$('txtDescricaooLancadorDepartamentos').value = '';
	
}

function js_pesquisaac50_descricao(mostra) {

	  if (mostra == true) {

	    js_OpenJanelaIframe('top.corpo.iframe_acordo',
	                        'db_iframe_acordocategoria',
	                        'func_acordocategoria.php?funcao_js=parent.js_mostraacordocategoria1|'+
	                        'ac50_sequencial|ac50_descricao',
	                        'Pesquisar Categorias de Acordo',
	                        true,
	                        '0');
	  } else {

	    if ($('ac50_sequencial').value != '') {

	      js_OpenJanelaIframe('top.corpo.iframe_acordo',
	                          'db_iframe_acordocategoria',
	                          'func_acordocategoria.php?pesquisa_chave='+$F('ac50_sequencial')+
	                          '&funcao_js=parent.js_mostraacordocategoria',
	                          'Pesquisar Categorias de Acordo',
	                          false,
	                          '0');
	     } else {
	       $('ac50_descricao').value = '';
	     }
	  }
	}

	function js_mostraacordocategoria(chave1, chave2) {

	  $('ac50_descricao').value  = chave1;
	  $('ac50_sequencial').focus();

	  db_iframe_acordocategoria.hide();
	}

	function js_mostraacordocategoria1(chave1, chave2) {

	  $('ac50_sequencial').value = chave1;
	  $('ac50_descricao').value  = chave2;
	  $('ac50_sequencial').focus();

	  db_iframe_acordocategoria.hide();
	}



                
function js_acordo(mostra){
	
  if (mostra == true){
    js_OpenJanelaIframe('','db_iframe_acordo',
                        'func_acordo.php?lDepartamento=1&funcao_js=parent.js_mostraAcordo1|ac16_sequencial|ac16_resumoobjeto',
                        'Pesquisa',true,0);
  }else{
     if($F('ac16_sequencial').trim() != ''){ 
        js_OpenJanelaIframe('','db_iframe_depart',
                            'func_acordo.php?lDepartamento=1&pesquisa_chave='+$F('ac16_sequencial')+'&funcao_js=parent.js_mostraAcordo'+
                            '&descricao=true',
                            'Pesquisa',false,0);
     }else{
       $('ac16_resumoobjeto').value = ''; 
     }
  }
}

function js_pesquisaAcordoComissao(mostra) {

  if (mostra == true) {
  
    js_OpenJanelaIframe('top.corpo.iframe_acordo', 
                        'db_iframe_comissao', 
                        'func_acordocomissao.php?funcao_js=parent.js_mostracomissao1|'+
                        'ac08_sequencial|ac08_descricao',
                        'Pesquisar Comissões de Vistoria',
                        true,
                        '0');
  } else {
  
    if ($('ac08_sequencial').value != '') { 
    
      js_OpenJanelaIframe('top.corpo.iframe_acordo',
                          'db_iframe_comissao',
                          'func_acordocomissao.php?pesquisa_chave='+$F('ac08_sequencial')+
                          '&funcao_js=parent.js_mostracomissao',
                          'Pesquisar Comissões de Vistoria',
                          false,
                          '0');
     } else {
       $('ac08_descricao').value = ''; 
     }
  }
}

function js_mostracomissao(chave, erro) {

  $('ac08_descricao').value = chave;
  if (erro ) {
   
    $('ac08_sequencial').focus(); 
    $('ac08_sequencial').value = ''; 
  }
}

function js_mostracomissao1(chave1, chave2) {

  $('ac08_sequencial').value = chave1;
  $('ac08_descricao').value      = chave2;
  $('ac08_sequencial').focus();
  
  db_iframe_comissao.hide();
}

function js_mostraAcordo(chave,erro){
  
  $('ac16_resumoobjeto').value = erro 
  if(erro==true){ 
  
    $('ac16_sequencial').focus(); 
    $('ac16_sequencial').value = ''; 
  }
}

function js_mostraAcordo1(chave1,chave2){
  $('ac16_sequencial').value = chave1;
  $('ac16_resumoobjeto').value = chave2;
  db_iframe_acordo.hide();
}

function js_gerarRelatorio(){
  
  $('situacaodescricao').value = $('ac16_acordosituacao').options[$('ac16_acordosituacao').selectedIndex].innerHTML;
  $('origemdescricao').value   = $('ac16_origem').options[$('ac16_origem').selectedIndex].innerHTML;
  $('tipodescricao').value     = $('acordotipo').options[$('acordotipo').selectedIndex].innerHTML;
  $('ordemdescricao').value    = $('ordem').options[$('ordem').selectedIndex].innerHTML;


  js_listaDepartamentos();
  
  var dataInicio = $F('ac16_datainicio');
  var dataFim    = $F('ac16_datafim');  
  if (dataInicio != '' && dataFim != '') {
	  if( !js_comparadata(dataInicio, dataFim, '<=') ) {
	    alert("A Data de Início deve ser maior ou igual a Data de Fim!");
	    return false;
	  }
  }
 
  var sVirgula            = '';
  var listaacordonatureza = '';
  for(i=0; i < parent.iframe_vinculoacordo.$('listaacordonatureza').length; i++) {
    listaacordonatureza += sVirgula + parent.iframe_vinculoacordo.$('listaacordonatureza').options[i].value;
    sVirgula             = ",";
  }

  $('listaacordonatureza').value = listaacordonatureza;

  var sVirgula         = '';
  var listaacordogrupo = '';
  for(i=0; i < parent.iframe_grupoacordo.$('listaacordogrupo').length; i++) {
    listaacordogrupo += sVirgula + parent.iframe_grupoacordo.$('listaacordogrupo').options[i].value;
    sVirgula          = ",";
  }
  
  $('listaacordogrupo').value = listaacordogrupo;
  
  var sVirgula        = '';
  var listacontratado = '';
  for(i=0; i < parent.iframe_contratado.$('listacontratado').length; i++) {
    listacontratado += sVirgula + parent.iframe_contratado.$('listacontratado').options[i].value;
    sVirgula         = ",";
  }
  
  $('listacontratado').value = listacontratado;

  jan = window.open('', 'relatorioacordo', 
                    'width='+(screen.availWidth-5)+', height='+(screen.availHeight-40)+', scrollbars=1, location=0');
  jan.moveTo(0,0);
  return true;
}
$('ac50_sequencial').value = '';
$('ac50_descricao') .value = '';

js_criaLancador();

</script>
