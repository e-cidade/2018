<?
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
require_once("libs/db_libpessoal.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_utils.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$oBairro                 = new cl_arquivo_auxiliar;
$oBairro->nome_botao     = "btnBairro";
$oBairro->cabecalho      = "<strong>Selecione</strong>";
$oBairro->codigo         = "j13_codi";
$oBairro->descr          = "j13_descr";
$oBairro->nomeobjeto     = 'bairros';
$oBairro->funcao_js      = 'js_mostra_bairro';
$oBairro->funcao_js_hide = 'js_mostra_bairro1';
$oBairro->sql_exec       = "";
$oBairro->func_arquivo   = "func_bairro.php";
$oBairro->nomeiframe     = "db_iframe_bairro";
$oBairro->localjan       = "";
$oBairro->db_opcao       = 2;
$oBairro->tipo           = 2;
$oBairro->linhas         = 6;
$oBairro->vwhidth        = 600;

$oLogradouro                 = new cl_arquivo_auxiliar;
$oLogradouro->nome_botao     = "btnLogradouro";
$oLogradouro->cabecalho      = "<strong>Selecione</strong>";
$oLogradouro->codigo         = "j14_codigo";
$oLogradouro->descr          = "j14_nome";
$oLogradouro->nomeobjeto     = 'ruas';
$oLogradouro->funcao_js      = 'js_mostra_logradouro';
$oLogradouro->funcao_js_hide = 'js_mostra_logradouro1';
$oLogradouro->sql_exec       = "";
$oLogradouro->func_arquivo   = "func_ruas.php";
$oLogradouro->nomeiframe     = "db_iframe_ruas";
$oLogradouro->localjan       = "";
$oLogradouro->db_opcao       = 2;
$oLogradouro->tipo           = 2;
$oLogradouro->linhas         = 6;
$oLogradouro->vwhidth        = 600;


$oZona                 = new cl_arquivo_auxiliar;
$oZona->cabecalho      = "<strong>Selecione</strong>";
$oZona->nome_botao     = "btnZona";
$oZona->codigo         = "j50_zona";
$oZona->descr          = "j50_descr";
$oZona->nomeobjeto     = 'zonas';
$oZona->funcao_js      = 'js_mostra_zona';
$oZona->funcao_js_hide = 'js_mostra_zona1';
$oZona->sql_exec       = "";
$oZona->func_arquivo   = "func_zonas.php";
$oZona->nomeiframe     = "db_iframe_zonas";
$oZona->localjan       = "";
$oZona->db_opcao       = 2;
$oZona->tipo           = 2;
$oZona->linhas         = 6;
$oZona->vwhidth        = 600;


$oCgm                 = new cl_arquivo_auxiliar;
$oCgm->nome_botao     = "btnCgm";
$oCgm->cabecalho      = "<strong>Selecione</strong>";
$oCgm->codigo         = "z01_numcgm";
$oCgm->descr          = "z01_nome";
$oCgm->nomeobjeto     = 'contribuinte';
$oCgm->funcao_js      = 'js_mostra_contribuinte';
$oCgm->funcao_js_hide = 'js_mostra_contribuinte1';
$oCgm->sql_exec       = "";
$oCgm->isfuncnome     = true;
$oCgm->func_arquivo   = "func_nome.php";

$oCgm->nomeiframe     = "db_iframe_contribuinte";
$oCgm->localjan       = "";
$oCgm->db_opcao       = 2;
$oCgm->tipo           = 2;
$oCgm->linhas         = 6;
$oCgm->vwhidth        = 600;

$oMatricula                 = new cl_arquivo_auxiliar;
$oMatricula->nome_botao     = "btnMatricula";
$oMatricula->cabecalho      = "<strong>Selecione</strong>";
$oMatricula->codigo         = "j01_matric";
$oMatricula->descr          = "z01_nome";
$oMatricula->nomeobjeto     = 'contribuinte';
$oMatricula->funcao_js      = 'js_mostra_contribuinte';
$oMatricula->funcao_js_hide = 'js_mostra_contribuinte1';
$oMatricula->sql_exec       = "";
$oMatricula->func_arquivo   = "func_iptubase.php";
$oMatricula->nomeiframe     = "db_iframe_contribuinte";
$oMatricula->localjan       = "";
$oMatricula->db_opcao       = 2;
$oMatricula->tipo           = 2;
$oMatricula->linhas         = 6;
$oMatricula->vwhidth        = 600;

$oInscricao                 = new cl_arquivo_auxiliar;
$oInscricao->nome_botao     = "btnInscricao";
$oInscricao->cabecalho      = "<strong>Selecione</strong>";
$oInscricao->codigo         = "q02_inscr";
$oInscricao->descr          = "z01_nome";
$oInscricao->nomeobjeto     = 'contribuinte';
$oInscricao->funcao_js      = 'js_mostra_contribuinte';
$oInscricao->funcao_js_hide = 'js_mostra_contribuinte1';
$oInscricao->sql_exec       = "";
$oInscricao->func_arquivo   = "func_issbase.php";
$oInscricao->nomeiframe     = "db_iframe_contribuinte";
$oInscricao->localjan       = "";
$oInscricao->db_opcao       = 2;
$oInscricao->tipo           = 2;
$oInscricao->linhas         = 6;
$oInscricao->vwhidth        = 600;

$sStyleDvFiltros            = "display:none;";

if ($_GET['iFiltro'] == "M" || $_GET['iFiltro'] == "I" ) {

	$sStyleDvFiltros = "display:inLine;";
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

  <form class="container" name="form1" method="post"  >
  
<fieldset style="width: 400px">
  <legend>Contribuinte</legend>
<table class="form-container">
  <tr>
    <td>
      Tipo de Filtro :
     <select id='selContribuinte' >
      <option value='1'>Selecionados</option>
      <option value='2'>Sem Selecionados</option>
    </select>   
    </td>
  </tr>
  <tr>
    <td align="center" >  
      <?  
        /*
         * aqui trataremos qual das opções vamos gerar
         * dependendo do que vier do select
         * 1 - CGM
         * 2 - Matricula
         * 3 - Inscricao
         */  
         switch ($_GET['iFiltro']){
         	
         	case "N" :
         		$oCgm->funcao_gera_formulario();
         	break;

         	case "C" :
            $oCgm->funcao_gera_formulario();
          break;
          
          case "M" :
            $oMatricula->funcao_gera_formulario();
          break;
          
          case "I" :
            $oInscricao->funcao_gera_formulario();
          break;
         	
         }
      ?>
    </td>
   </tr> 
  </table>
</fieldset>

<br>


<div id='dv_filtros' style="<?=$sStyleDvFiltros?>">

<fieldset style="width: 500px;" id='field_bairros'>
<legend><b>Bairros</b></legend>
<table width="auto" border="0"  cellspacing="2" cellpadding="0">
  <tr>
    <td align="left">
      <b>Tipo de Filtro :</b>
     <select id='selBairro' >
      <option value='1'>Selecionados</option>
      <option value='2'>Sem Selecionados</option>
    </select>   
    </td>
  </tr>
  <tr>
    <td align="center" >  
	    <?  	
	      $oBairro->funcao_gera_formulario();
      ?>
    </td>
   </tr> 
  </table>
</fieldset>
  
<br>

<fieldset style="width: 500px;" id='field_logradouros'>
<legend><b>Logradouros</b></legend>
<table width="auto" border="0"  cellspacing="2" cellpadding="0">
  <tr>
    <td align="left">
      <b>Tipo de Filtro :</b>
     <select id='selLogradouro' >
      <option value='1'>Selecionados</option>
      <option value='2'>Sem Selecionados</option>
    </select>   
    </td>
  </tr>
  <tr>
    <td align="center" >  
      <?    
        $oLogradouro->funcao_gera_formulario();
      ?>
    </td>
   </tr> 
  </table>
</fieldset>  

<br>

<fieldset style="width: 500px;" id='field_zona'>
<legend><b>Zonas</b></legend>
<table width="auto" border="0"  cellspacing="2" cellpadding="0">
  <tr>
    <td align="left">
      <b>Tipo de Filtro :</b>
     <select id='selZona' >
      <option value='1'>Selecionados</option>
      <option value='2'>Sem Selecionados</option>
    </select>   
    </td>
  </tr>
  <tr>
    <td align="center" >  
      <?    
        $oZona->funcao_gera_formulario();
      ?>
    </td>
   </tr> 
  </table>
</fieldset> 
</div>


</form>


</body>
<script>


function js_getZonas(){

  sVirgula = "";
  sLista   = "";
  iOpcao   = $F('selZona');
  sZonas   = "";

  for( iLista = 0; iLista < document.getElementById("zonas").length; iLista++){

    sValorLista = document.getElementById("zonas").options[iLista].value;
    sLista += sVirgula + sValorLista;
    sVirgula = ",";
  } 

  sZonas = "&sZonas="+sLista+"&iZonas="+iOpcao;
  return sZonas;
}

function js_getBairros(){

  sVirgula = "";
  sLista   = "";
  iOpcao   = $F('selBairro');
  sBairros = "";
  
  for( iLista = 0; iLista < document.getElementById("bairros").length; iLista++){
  
    sValorLista = document.getElementById("bairros").options[iLista].value;
    sLista += sVirgula + sValorLista;
    sVirgula = ",";
  } 

  sBairros = "&sBairros="+sLista+"&iBairros="+iOpcao;
  return sBairros;
}

function js_getRuas(){

  sVirgula = "";
  sLista   = "";
  iOpcao   = $F('selLogradouro');
  sRuas    = "";
  
  for( iLista = 0; iLista < document.getElementById("ruas").length; iLista++){
  
    sValorLista = document.getElementById("ruas").options[iLista].value;
    sLista += sVirgula + sValorLista;
    sVirgula = ",";
  } 
  
  sRuas = "&sRuas="+sLista+"&iRuas="+iOpcao;
  //alert( sRuas);
  return sRuas;
}

function js_getContribuinte(){

  sVirgula = "";
  sLista   = "";
  iOpcao   = $F('selContribuinte');
  sContribuintes = "";
  
  for( iLista = 0; iLista < document.getElementById("contribuinte").length; iLista++){
  
    sValorLista = document.getElementById("contribuinte").options[iLista].value;
    sLista += sVirgula + sValorLista;
    sVirgula = ",";
  } 
  
  sContribuintes = "&sContribuintes="+sLista+"&iContribuinte="+iOpcao;
  //alert( sContribuintes);
  return sContribuintes;
}


oBairro = new DBToogle("field_bairros", false);
oLograd = new DBToogle("field_logradouros", false);
oZona   = new DBToogle("field_zona", false);


var iMatricula = '';

function js_enviar_dados(tp){
  if(document.form1.selregist){
  
    valores = '';
    virgula = '';
    
    
    for(i = 0; i < document.form1.selregist.length; i++){
      valores+= virgula+document.form1.selregist.options[i].value;
      virgula = ',';
      if (i == 0) {
         iMatricula = 1;
      } else {
         iMatricula = 2;
      }
    }
    
    document.form1.faixa_regis.value = valores;
    document.form1.selregist.selected = 0;
    if (valores==""){
      alert(_M('tributario.notificacoes.cai4_listafiltros003.selecione_matricula'));
      return false;
    }
  }else if(document.form1.sellotac){
    valores = '';
    virgula = '';
    for(i=0; i < document.form1.sellotac.length; i++){
      valores+= virgula+"'"+document.form1.sellotac.options[i].value+"'";
      virgula = ',';
    }
    document.form1.faixa_lotac.value = valores;
    document.form1.sellotac.selected = 0;
    if (valores==""){
      alert(_M('tributario.notificacoes.cai4_listafiltros003.selecione_lotacao'));
      return false;
    }
  }
  if (document.form1.r110_regisi){
    if (document.form1.r110_regisi.value=="" && document.form1.r110_regisf.value==""){
      alert(_M('tributario.notificacoes.cai4_listafiltros003.informe_matricula'));
      return false;
    }
  }else if (document.form1.r110_lotaci){
    if (document.form1.r110_lotaci.value=="" && document.form1.r110_lotacf.value==""){
      alert(_M('tributario.notificacoes.cai4_listafiltros003.informe_lotacao'));
      return false;
    }
  }
  
  if (tp == 2) {
    document.form1.db_debug.value = "true";
  }
  
  document.form1.action = 'pes4_gerafolha002.php';
  

   js_OpenJanelaIframe(  "","db_calculo",
                          "",
                          "Cálculo Financeiro",
                          true,
                          (document.height - (document.height-30)),     //top 
                          (document.width  - (document.width-60))/2,    //left
                          document.width-60,
                          document.height
                       );
  document.form1.target = 'IFdb_calculo';
  document.form1.submit();
  
  if (iMatricula == 1 ){ 
    
    setTimeout("document.location.href='pes3_gerfinanc001.php?lConsulta=1&iMatricula='+$F('faixa_regis')", 2000);
    
  }
  if ($F('r110_regisi') == $F('r110_regisf') ){
  
    setTimeout("document.location.href='pes3_gerfinanc001.php?lConsulta=1&iMatricula='+$F('r110_regisi')", 2000);
  }
  
}

js_trocacordeselect();
</script>
</html>
<script>

$("fieldset_contribuinte").addClassName("separator");
$("z01_numcgm").addClassName("field-size1");
$("z01_nome").addClassName("field-size5");
$("selContribuinte").setAttribute("rel","ignore-css");
$("selContribuinte").addClassName("field-size7");
$("contribuinte").setAttribute("rel","ignore-css");
$("contribuinte").style.width = "100%";

</script>