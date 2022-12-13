<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

require_once("classes/db_datadebitos_classe.php");

$clrotulo      = new rotulocampo;
$cldatadebitos = new cl_datadebitos; 

$iInstit       = db_getsession('DB_instit');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?   
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
?>
<script>
function js_limpacampos() {

  $('j14_codigo').value = '';
  $('j14_nome').value   = '';
  $('q03_ativ').value   = '';
  $('q03_descr').value  = '';
  $('k00_tipo').value   = '';
  $('k00_descr').value  = '';
  $('inscricao').value  = 'T';
  $('ordenar').value    = 'A';
}

function js_emite() {

  var sOrdenar       = $('ordenar').value;
  var sInscricao     = $('inscricao').value;
  var sDataDebitos   = $('datadebitos').value;
  var iItensRuas     = $('ruas').options.length;
  var iItensAtivid   = $('ativid').options.length;
  var iItensArretipo = $('arretipo').options.length;
  var sQuery         = '';
 
  var sVirgula       = '';
  var sItensRuas     = '';
  for (i = 0; i < iItensRuas; i++) {
  
    sItensRuas = sItensRuas+sVirgula+$('ruas').options[i].value;
    sVirgula   = ',';
  }

  var sVirgula       = '';
  var sItensAtivid   = '';
  for (i = 0; i < iItensAtivid; i++) {
  
    sItensAtivid = sItensAtivid+sVirgula+$('ativid').options[i].value;
    sVirgula     = ',';
  }
  
  var sVirgula       = '';
  var sItensArretipo = '';
  for (i = 0; i < iItensArretipo; i++) {
  
    sItensArretipo = sItensArretipo+sVirgula+$('arretipo').options[i].value;
    sVirgula       = ',';
  }
  
  sQuery += '?logradouro='+sItensRuas;
  sQuery += '&tipoatividade='+sItensAtivid;
  sQuery += '&tipodebito='+sItensArretipo;
  sQuery += '&inscricao='+sInscricao;
  sQuery += '&datadebitos='+sDataDebitos;
  sQuery += '&ordenar='+sOrdenar;

  jan = window.open('iss2_relatorioruas002.php'+sQuery,'',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  
  jan.moveTo(0,0);  
}
</script>
<style>
  #q03_descr{
    width: 53%;
  }

  #k00_descr{
    width: 53%;
  }

  #k00_descr {
    width: 58%;
  }
  .fildset-principal table td:first-child {
    
    width: 70px;
    white-space: nowrap;
  }

  fildset table {
    width: 100%;
  }

  #j14_nome {
    width: 55%;
  }

</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="js_limpacampos();">
<table align="center" border="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
      <form name="form1" method="post" action="" onsubmit="js_limpacampos();">
      <fieldset class="fildset-principal">
        <legend>
          <b>Com Débito por Logradouro</b>
        </legend>
        <table align="left" border="0">
          <tr>
            <td align="left">
              <?
                $cl_ruas                          = new cl_arquivo_auxiliar;
                $cl_ruas->nome_botao              = "db_lanca_j14_codigo";
                $cl_ruas->cabecalho               = "<strong>Logradouros Selecionados</strong>";
                $cl_ruas->codigo                  = "j14_codigo";
                $cl_ruas->descr                   = "j14_nome";
                $cl_ruas->nomeobjeto              = 'ruas';
                $cl_ruas->funcao_js               = 'js_mostra0';
                $cl_ruas->funcao_js_hide          = 'js_mostra1';
                $cl_ruas->sql_exec                = "";
                $cl_ruas->func_arquivo            = "func_ruas.php";
                $cl_ruas->nomeiframe              = "db_iframe_itens_ruas";
                $cl_ruas->localjan                = "";
                $cl_ruas->onclick                 = "";
                $cl_ruas->db_opcao                = 2;
                $cl_ruas->tipo                    = 2;
                $cl_ruas->top                     = 0;
                $cl_ruas->linhas                  = 4;
                $cl_ruas->vwidth                  = 550;
                $cl_ruas->tamanho_campo_descricao = 40;
                $cl_ruas->funcao_gera_formulario();
              ?>
            </td>
          </tr>
          <tr>
            <td align="left">
              <?
                $cl_ativid                          = new cl_arquivo_auxiliar;
                $cl_ativid->nome_botao              = "db_lanca_q03_ativ";
                $cl_ativid->cabecalho               = "<strong>Atividades Selecionadas</strong>";
                $cl_ativid->codigo                  = "q03_ativ";
                $cl_ativid->descr                   = "q03_descr";
                $cl_ativid->nomeobjeto              = 'ativid';
                $cl_ativid->funcao_js               = 'js_mostra2';
                $cl_ativid->funcao_js_hide          = 'js_mostra3';
                $cl_ativid->sql_exec                = "";
                $cl_ativid->func_arquivo            = "func_ativid.php";
                $cl_ativid->nomeiframe              = "db_iframe_itens_ativid";
                $cl_ativid->localjan                = "";
                $cl_ativid->onclick                 = "";
                $cl_ativid->db_opcao                = 2;
                $cl_ativid->tipo                    = 2;
                $cl_ativid->top                     = 0;
                $cl_ativid->linhas                  = 4;
                $cl_ativid->vwidth                  = 550;
                $cl_ativid->tamanho_campo_descricao = 40;
                $cl_ativid->funcao_gera_formulario();
              ?>
            </td>
          </tr>
          <tr>
            <td align="left">
              <?
                $cl_arretipo                          = new cl_arquivo_auxiliar;
                $cl_arretipo->nome_botao              = "db_lanca_k00_tipo";
                $cl_arretipo->cabecalho               = "<strong>Tipo de Débito Selecionados</strong>";
                $cl_arretipo->codigo                  = "k00_tipo";
                $cl_arretipo->descr                   = "k00_descr";
                $cl_arretipo->nomeobjeto              = 'arretipo';
                $cl_arretipo->funcao_js               = 'js_mostra4';
                $cl_arretipo->funcao_js_hide          = 'js_mostra5';
                $cl_arretipo->sql_exec                = "";
                $cl_arretipo->func_arquivo            = "func_arretipo.php";
                $cl_arretipo->nomeiframe              = "db_iframe_itens_arretipo";
                $cl_arretipo->localjan                = "";
                $cl_arretipo->onclick                 = "";
                $cl_arretipo->db_opcao                = 2;
                $cl_arretipo->tipo                    = 2;
                $cl_arretipo->top                     = 0;
                $cl_arretipo->linhas                  = 4;
                $cl_arretipo->vwidth                  = 550;
                $cl_arretipo->tamanho_campo_descricao = 40;
                $cl_arretipo->funcao_gera_formulario();
              ?>     
            </td>
          </tr>
          <tr>
            <td align="center">
              <table align="center" cellpadding="0" cellspacing="0" border="0" width="100%">
			          <tr>
			            <td align="left"><b>Inscrições:</b></td>
			            <td align="left">
			              <? 
			                $aInscricao = array("T"   => "Todas",
			                                    "BA"  => "Baixadas",
			                                    "NBA" => "Não Baixadas");
			                db_select("inscricao", $aInscricao, true, 2); 
			              ?>
			            </td>
			            
                  <td align="left"><b>Data:</b></td>
                  <td align="left">
                    <? 
                      $sWhere           = "k115_instit = {$iInstit}";
                      $sCampos          = "distinct k115_data";
                      $sOrderBy         = "k115_data desc";
                      $sSqlDataDebitos  = $cldatadebitos->sql_query_file(null, $sCampos, $sOrderBy, $sWhere);
                      $rsSqlDataDebitos = $cldatadebitos->sql_record($sSqlDataDebitos);
                      
                      $aDataDebitos     = array();
				              for ( $i = 0; $i < $cldatadebitos->numrows; $i++ ) {
				              	
				                $oDataDebitos                           = db_utils::fieldsMemory($rsSqlDataDebitos, $i);
				                $aDataDebitos[$oDataDebitos->k115_data] = db_formatar($oDataDebitos->k115_data,'d');
				              }

                      db_select("datadebitos", $aDataDebitos, true, 2);
                    ?>
                  </td>
			            
			            <td align="left"><b>Ordenar por:</b></td>
			            <td align="left">
			              <? 
			                $aOrdenar = array("A" => "Atividades",
			                                  "I" => "Inscrição",
			                                  "L" => "Logradouro",
                                        "N" => "Logradouro/Número");
			                db_select("ordenar", $aOrdenar, true, 2); 
			              ?>
			            </td>
			          </tr>
              </table>
            </td>
          </tr>
        </table>
      </fieldset>
      <table align="center">
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="center"> 
            <input  name="emiterelatorio" id="emiterelatorio" type="button" value="Emitir Relátorio" 
                    onclick="js_emite();">
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