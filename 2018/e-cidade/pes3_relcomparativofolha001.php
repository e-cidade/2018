<?
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_classesgenericas.php");
require_once ("model/pessoal/std/DBPessoal.model.php");
$lblRelatorio = "Processar";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script src="scripts/prototype.js"></script>
<script src="scripts/scripts.js"></script>
<script src="scripts/strings.js"></script>
<?
db_app::load("estilos.css");
?>
<style>
  select {
    width:100%;
  }
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
db_menu ( db_getsession ( "DB_id_usuario" ), db_getsession ( "DB_modulo" ), db_getsession ( "DB_anousu" ), db_getsession ( "DB_instit" ) );
?>
<form id="form1" name="form1" method="get" action="pes3_relcomparativofolha002.php" target="Relatorio">
<center>

<table style="top: 60px; position: relative;">
<?
  $rubricas_selecionadas_text = "";
  db_input('rubricas_selecionadas_text',20,0,true,'hidden',3);
?>
  <tr>
    <td valign="middle" align="center">
    <fieldset>
      <legend><b>Filtros</b></legend>
      <table style="width: 100%;">
        <tr>
          <td style="width: 150px;"><b>Ano/Mês Base:</b></td>
          <td>
            <? 
              $iAnoBase = DBPessoal::getAnoFolha(); 
              db_input ("iAnoBase", "4", "", "", "text", 1, 'onKeyUp="js_ValidaCampos(this,4,\'Ano Base\',\'f\',\'f\',event);"');  
              echo "/";
              $iMesBase = str_pad(DBPessoal::getMesFolha(), 2, '0', STR_PAD_LEFT);
              db_input ("iMesBase", "2", "", "", "text", 1, 'onchange="js_testamesbase();" onKeyUp="js_ValidaCampos(this,4,\'Mês Base\',\'f\',\'f\',event);"');  
            ?>
          </td>
        </tr>
        <tr>
          <td><b>Ano/Mês a Comparar:</b></td>
          <td>
            <? 
              db_input ("iAnoCompara", "4", "", "", "text", 1, 'onKeyUp="js_ValidaCampos(this,4,\'Ano a Comparar\',\'f\',\'f\',event);"' );  
              echo "/";
              db_input ("iMesCompara", "2", "", "", "text", 1, 'onchange="js_testamescompara();" onKeyUp="js_ValidaCampos(this,4,\'Mês a Comparar\',\'f\',\'f\',event);"' );  
            ?>
          </td>
        </tr>
        <tr>
          <td><b>Servidores:</b></td>
          <td>
            <? 
            
              $aFiltro = array( 
							                  ''           => 'Selecione',
							                  'todos'      => 'Todos',
							                  'diferentes' => 'Com Diferença de Valor'
							                );
							db_select("sFiltro", $aFiltro, "",1);
            ?>
          </td>
        </tr>
        <tr>
          <td><b>Rubricas:</b></td>
          <td>
            <? 
            
              $aFiltroRubrica = array(
                                        ''             => 'Selecione', 
                                        'todos'        => 'Proventos',
                                        'selecionadas' => 'Selecionadas'
                                      );
              db_select("sFiltroRubrica", $aFiltroRubrica, '',1,'onchange="js_mostratiporubricas();"');
            ?>
          </td>
        </tr>

        <tr id="tipo" style = "display:none;">
          <td><b>Tipo de Rubrica:</b></td>
          <td>
            <? 
            
              $aFiltroTipoRubrica = array(
                                        ''      => 'Selecione', 
                                        '1'     => 'Provento',
                                        '2'     => 'Desconto',
                                        '3'     => 'Base'
                                      );
              db_select("iFiltroTipoRubrica", $aFiltroTipoRubrica, '',1,'onchange="js_mostrarubricas();"');
            ?>
          </td>
        </tr>
 
        <?
          $selecRubri                                  = new cl_arquivo_auxiliar;
          $selecRubri->cabecalho                       = "<strong>Seleção de Rubricas</strong>";
          $selecRubri->obrigarselecao                  = false;
          $selecRubri->codigo                          = "rh27_rubric";
          $selecRubri->descr                           = "rh27_descr";
          $selecRubri->nomeobjeto                      = 'rubricas_selecionadas';
          $selecRubri->funcao_js                       = 'js_mostra';
          $selecRubri->funcao_js_hide                  = 'js_mostra1';
          $selecRubri->func_arquivo                    = "func_rhrubricas.php";
          $selecRubri->nomeiframe                      = "db_iframe_rhrubricas";
          $selecRubri->executa_script_apos_incluir     = "document.form1.rh27_rubric.focus();";
          $selecRubri->mostrar_botao_lancar            = false;
          $selecRubri->executa_script_lost_focus_campo = "js_insSelectrubricas_selecionadas()";
          $selecRubri->completar_com_zeros_codigo      = true;
          $selecRubri->executa_script_change_focus     = "document.form1.rh27_rubric.focus();";
          $selecRubri->passar_query_string_para_func   = "&tipo_rubrica='+js_get_tiporubricas()+'&instit=".db_getsession("DB_instit"); //<<na verdade é aqui 
          $selecRubri->localjan                        = "";
          $selecRubri->db_opcao                        = 2;
          $selecRubri->tipo                            = 2;
          $selecRubri->top                             = 20;
          $selecRubri->linhas                          = 5;
          $selecRubri->tamanho_campo_descricao         = 34;
          $selecRubri->vwidth                          = "394";
          $selecRubri->Labelancora                     = "Rubrica:";
          $selecRubri->funcao_gera_formulario();

        ?>
      </table>
    </fieldset>
    <fieldset>
      <legend><b>Tipo de Folha</b></legend>
      <table>
        <tr>
	        <td>
	        
	        </td>
	        <td>
			          <? 
			            $aTiposFolha = array( 
			                                  'gerfsal' => 'Salário',
			                                  'gerfs13' => 'Saldo do 13º',
			                                  'gerfcom' => 'Complementar',
                                        'gerfadi' => 'Adiantamento'
			                                );
                  db_multiploselect("valor","descr", "", "", $aTiposFolha, "", 6, 250, "", "", true, "");

			          ?>

          </td>
        </tr>
      </table>
    </fieldset>
   </td>
  </tr>
</table>
<p>&nbsp;
<p>&nbsp;
<p>&nbsp;

<?
	db_input ( "lblRelatorio", "20", "", "", "submit", 1, "onClick='return js_abrejanela();'" );
?>

</center>
</form>
</body>
</html>
<script>

var fEventoAncoraRubrica = $$('.dbancora')[0].onclick;

/*
 Valida para não deixar colar letras nos campos numéricos
 */

$('iAnoBase').onpaste = function(event) {
  return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
} 

$('iMesBase').onpaste = function(event) {
  return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
} 

$('iMesCompara').onpaste = function(event) {
  return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
} 

$('iAnoCompara').onpaste = function(event) {
  return /^[0-9|.]+$/.test(event.clipboardData.getData('text/plain'));
} 

$('sFiltroRubrica').value                          = '';
$('tr_inicio_rubricas_selecionadas').style.display = 'none';

function js_testamesbase() {

  if ($('iMesBase').getValue() > 12 || $('iMesBase').getValue() < 1 ) {
    $('iMesBase').value                    = '';
    $('iMesBase').style.backgroundColor    = "#99A9AE";
    $('iMesBase').focus();
    alert("Mês Base Inválido");
    return false;
  }
}

function js_testamescompara() {

  if ($('iMesCompara').getValue() > 12 || $('iMesCompara').getValue() < 1) { 
    $('iMesCompara').style.backgroundColor    = "#99A9AE";
    $('iMesCompara').value                    = '';
    $('iMesCompara').focus();
    alert("Mês a Comparar Inválido");
    return false;
  }
}

function js_mostratiporubricas() {
  
  if ($F('sFiltroRubrica') == 'selecionadas'){
      $('tipo').style.display                            = '';
      $('tr_inicio_rubricas_selecionadas').style.display = '';
      $('iFiltroTipoRubrica').value                      = '';
      js_mostrarubricas();
  }
  if ($F('sFiltroRubrica') == 'todos' || $F('sFiltroRubrica') == '') {
      $('tipo').style.display                            = 'none';
      $('tr_inicio_rubricas_selecionadas').style.display = 'none';
      $('rubricas_selecionadas_text').value              = '';
      $('rh27_descr').value                              = '';
      $('iFiltroTipoRubrica').value                      = '';
      $('rubricas_selecionadas').options.length          = 0;
  }
}

function js_mostrarubricas() {

  var lHabilitado             = $('iFiltroTipoRubrica').value != '';
  var oElementoAncora         = $$('.dbancora')[0];
  var oElementoInputCodigo    = $('rh27_rubric');
  var oElementoInputDescricao = $('rh27_descr');
  var oElementoSelect         = $('rubricas_selecionadas');
  
  if ( !lHabilitado ) {

      oElementoSelect.options.length       = 0;
      oElementoAncora.style.color          = '#000000';
      oElementoAncora.style.textDecoration = 'none';
      oElementoInputDescricao.value        = '';
      oElementoInputCodigo.className       = 'readonly';
      oElementoSelect.className            = 'readonly';
      oElementoAncora.onclick              = function(){ 
      return;
    };
    return;
  }
  
  oElementoAncora.style.color          = 'blue';
  oElementoAncora.style.textDecoration = 'underline';
  oElementoInputCodigo.className       = '';
  oElementoSelect.className            = '';
  oElementoInputDescricao.value        = '';
  oElementoSelect.options.length       = 0;
  oElementoAncora.onclick = fEventoAncoraRubrica;
}

function js_get_tiporubricas() {
  return $F('iFiltroTipoRubrica');
}

function js_abrejanela(){
  
  $('iAnoBase').style.backgroundColor    = "#FFFFFF";
  $('iAnoCompara').style.backgroundColor = "#FFFFFF";
  $('iMesBase').style.backgroundColor    = "#FFFFFF";
  $('iMesCompara').style.backgroundColor = "#FFFFFF";
  $('sFiltro').style.backgroundColor     = "#FFFFFF";
  $('objeto2').style.border              = "1px solid #999999";
   
  if ($('iAnoBase').getValue() == "" || $('iAnoBase').getValue() == 0) { 
    $('iAnoBase').style.backgroundColor    = "#99A9AE";
    $('iAnoBase').focus();
    alert("Campo Ano Base é de preenchimento obrigatório.");
    return false;
  }
  if ($('iMesBase').getValue() == "") { 
    $('iMesBase').style.backgroundColor    = "#99A9AE";
    $('iMesBase').focus();
    alert("Campo Mês Base é de preenchimento obrigatório.");
    return false;
  }
  if ($('iAnoCompara').getValue() == "" || $('iAnoCompara').getValue() == 0) { 
    $('iAnoCompara').style.backgroundColor    = "#99A9AE";
    $('iAnoCompara').focus();
    alert("Campo Ano a Comparar é de preenchimento obrigatório.");
    return false;
  }
  if ($('iMesCompara').getValue() == "") { 
    $('iMesCompara').style.backgroundColor    = "#99A9AE";
    $('iMesCompara').focus();
    alert("Campo Mês a Comparar é de preenchimento obrigatório.");
    return false;
  }
  if ($('iMesBase').getValue() > 12 || $('iMesBase').getValue() < 1 ) { 
    $('iMesBase').style.backgroundColor    = "#99A9AE";
    $('iMesBase').focus();
    alert("Mês Base Inválido");
    return false;
  }
  if ($('iMesCompara').getValue() > 12 || $('iMesCompara').getValue() < 1) { 
    $('iMesCompara').style.backgroundColor    = "#99A9AE";
    $('iMesCompara').focus();
    alert("Mês a Comparar Inválido");
    return false;
  }
  if ($('sFiltro').getValue() == "") { 
    $('sFiltro').style.backgroundColor    = "#99A9AE";
    $('sFiltro').focus();
    alert("Campo Servidores é de preenchimento obrigatório.");
    return false;
  }  
  if ($('sFiltroRubrica').getValue() == "") { 
    $('sFiltroRubrica').style.backgroundColor    = "#99A9AE";
    $('sFiltroRubrica').focus();
    alert("Campo Rubricas é de preenchimento obrigatório.");
    return false;
  }
  if ($('sFiltroRubrica').getValue() != "" && $('sFiltroRubrica').getValue() != "todos" && $('iFiltroTipoRubrica').getValue() == "") { 
    $('iFiltroTipoRubrica').style.backgroundColor    = "#99A9AE";
    $('iFiltroTipoRubrica').focus();
    alert("Campo Tipo de Rubrica é de preenchimento obrigatório");
    return false;
  }
  if ($('sFiltroRubrica').getValue() != "" && $('sFiltroRubrica').getValue() != "todos" && $('iFiltroTipoRubrica').getValue() != "" && $('rubricas_selecionadas').options.length == 0) { 
    $('rubricas_selecionadas').style.backgroundColor    = "#99A9AE";
    $('rubricas_selecionadas').focus();
    alert("É obrigatório selecionar ao menos uma Rubrica.");
    return false;
  }

  if ($('objeto2').options.length == 0) { 
    $('objeto2').style.border             = "3px solid #99A9AE";
    $('objeto2').focus();
    alert("É obrigatório selecionar ao menos um Tipo de Folha.");
    return false;
  } else {
    for(iI in $('objeto2').options){
      $('objeto2').options[iI].selected = true;
    }
  }
  if (document.form1.rubricas_selecionadas){
      document.form1.rubricas_selecionadas_text.value = js_campo_recebe_valores();
   }

  oJanela = window.open("","Relatorio",'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  oJanela.moveTo(0,0);
  $('form1').submit();
  return false;
}
</script>