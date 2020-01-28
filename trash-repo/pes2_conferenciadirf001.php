<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
<?   
  db_app::load("scripts.js, strings.js, prototype.js");
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
      <fieldset class="fildset-principal" style="width: 600px;">
        <legend>
          <b>Conferência dos Dados Contábeis</b>
        </legend>
        <table align="left" border="0" class="table-campos">
            <tr>
                <td nowrap align="left">
                  <b>Ano :</b>
                </td>
                <td  align="left" nowrap>
                 <?      
                   $anobase = db_anofolha() -1;
                   db_input('ano', 4, 0, true, 'text', 1, "");
                 ?>
                </td>
            </tr>
            
                        
                   
            <tr style="display: none;">
              <td nowrap title="Tipo Resumo" align="left">
               <b>Tipo Resumo:</b>
              </td>
              <td> 
					      <?
					        $tipo        = 'pf';
					        $aTipoResumo = array('g'  => 'Geral', 
					                             'l'  => 'Lotação',
					                             'm'  => 'Matricula',
					                             'pf' => 'Autônomos/Fornecedores',
					                             'pj' => 'Pessoas Jurídicas');
					        db_select("tipo", $aTipoResumo, true, 1, " onchange='js_tiporesumo();' ");
					      ?>
              </td>
            </tr>
            <tr id="box-tipo-filtro" >
              <td nowrap title="Tipo Filtro" align="left">
               <b>CGM:</b>
              </td>
              <td> 
                <?
                  $tipofiltro  = '1';
                  $aTipoFiltro = array('1' => 'Geral', 
                                       '2'    => 'Intervalo',
                                       '3'    => 'Selecionados');
                  db_select("tipofiltro", $aTipoFiltro, true, 1, " onchange='js_tiporesumo();' ");
                ?>
              </td>
            </tr>
            <tr id="containner-tipo-filtro" style="display: none;">
              <td nowrap align="center" colspan="2">

 
               
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
                    <td colspan="2">
                      <?
                        $cl_cgmpfpj                 = new cl_arquivo_auxiliar;
                        $cl_cgmpfpj->nome_botao     = "db_lanca_cgmpfpj";
                        $cl_cgmpfpj->cabecalho      = "<strong>Cgm Selecionados</strong>";
                        $cl_cgmpfpj->codigo         = "z01_numcgm";
                        $cl_cgmpfpj->descr          = "z01_nome";
                        $cl_cgmpfpj->nomeobjeto     = 'cgmpfpj';
                        $cl_cgmpfpj->funcao_js      = 'js_mostra4';
                        $cl_cgmpfpj->funcao_js_hide = 'js_mostra5';
                        $cl_cgmpfpj->sql_exec       = "";
                        $cl_cgmpfpj->func_arquivo   = "func_nome.php";
                        $cl_cgmpfpj->nomeiframe     = "db_iframe_itens_cgmpfpj";
                        $cl_cgmpfpj->localjan       = "";
                        $cl_cgmpfpj->onclick        = "";
                        $cl_cgmpfpj->db_opcao       = 1;
                        $cl_cgmpfpj->tipo           = 2;
                        $cl_cgmpfpj->top            = 0;
                        $cl_cgmpfpj->linhas         = 5;
                        $cl_cgmpfpj->isfuncnome     = true;
                        $cl_cgmpfpj->vwidth         = 500;
                        $cl_cgmpfpj->funcao_gera_formulario();
                      ?>
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
            <input  type="button" value="Emitir Relátorio" onclick="return js_emite();" >
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

  $('z01_numcgm_ini').value  = '';
  $('z01_numcgm_fim').value  = '';
  for (var y = 0; y < $('cgmpfpj').options.length; y++) {
    $('cgmpfpj').options[y] = null;
  }
}


function js_emite(){

  var iAno       = document.getElementById('ano').value;
  var sTipoCgm   = parseFloat(document.getElementById('tipofiltro').value);
  var sOrdem     = document.getElementById('ordem').value;
  
  //alert(iAno+"  "+sCgm+"  "+sOrdem);

  if (iAno == null || iAno == "" ){
  
    alert("Selecione um Ano");
    document.getElementById('ano').focus();
    return false;
  } else if ( iAno < 1900 ) {
  
    alert("Ano Inválido");
    document.getElementById('ano').focus();  
    return false;
  } else {
    
    if (sTipoCgm == 1) {
    
	    var sCgm   = 'geral';
	    query  = "";
	    query += "?ano="+iAno+"&cgm="+sCgm+"&ordem="+sOrdem;
	    programa = "pes2_conferenciadirf002.php";
	    programa += query;
	
	    jan = window.open(programa,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	    jan.moveTo(0,0);
	  } else if (sTipoCgm == 2) {
	  
      var sCgmDe  = document.getElementById('z01_numcgm_ini').value;
      var sCgmAte = document.getElementById('z01_numcgm_fim').value;
      
      query  = "";
      query += "?ano="+iAno+"&de="+sCgmDe+"&ate="+sCgmAte+"&ordem="+sOrdem;
      programa = "pes2_conferenciadirf002.php";
      programa += query;
  
      jan = window.open(programa,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);	  
	  }else if (sTipoCgm == 3) {
    
      //var sCgm  = document.getElementById('cgmpfpj').value;
      var iCgms       = $('cgmpfpj').options.length;
      var sVirgula = '';
      var sCgms    = '';
      for (i = 0; i < iCgms; i++) {
      
        sCgms = sCgms+sVirgula+$('cgmpfpj').options[i].value;
        sVirgula     = ',';
      }     
      
      query  = "";
      query += "?ano="+iAno+"&cgms="+sCgms+"&ordem="+sOrdem;
      programa = "pes2_conferenciadirf002.php";
      programa += query;
  
      jan = window.open(programa,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);    
    }
	 return true;   
  }  
   
}

function js_tiporesumo() {

  js_limpacampos();
  
    if ($('tipofiltro').value == '1') {
      
      $('containner-tipo-filtro').hide();
      $('tipo-filtro-cgm-selecionados').hide();
      $('tipo-filtro-cgm-intervalo').hide();
    }  
    
    if ($('tipofiltro').value == '2') {
      
      $('containner-tipo-filtro').show();
      $('tipo-filtro-cgm-intervalo').show();
      $('tipo-filtro-cgm-selecionados').hide();
    }
    
    if ($('tipofiltro').value == '3') {
      
      $('containner-tipo-filtro').show();
      $('tipo-filtro-cgm-selecionados').show();
      $('tipo-filtro-cgm-intervalo').hide();
    }
  

/*  
  if ($('tipofiltro').value != 'null') {
    $('tipofiltro').options[0].disabled = true; 
  }
*/  
  
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
  var sFuncao         = '&funcao_js=parent.js_mostrar70_estrut_fim&instit='+Instit;
  
  var sUrl1           = 'func_rhlotaestrut.php?funcao_js=parent.js_mostrar70_estrut_fim1|r70_estrut&instit='+Instit;
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