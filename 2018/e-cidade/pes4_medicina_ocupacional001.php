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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
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

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<center>
<form name="form1" method="post" action="">
  <fieldset style="margin-top:50px; width: 500px;">
    <legend><strong>TXT - Medicina Ocupacional </strong></legend>
    <table  align="center" width="100%" cellpadding="3" border="0">
      <tr>
         <td align="right"><b>Data Arquivo :</b></td>
         <td >
          <?
            $sDataArquio = explode("/",date("d/m/Y",db_getsession('DB_datausu')));
            $iDiaArquivo = $sDataArquio[0];
            $iMesArquivo = $sDataArquio[1];
            $iAnoArquivo = $sDataArquio[2];
            db_inputdata('data_arquivo', $iDiaArquivo, $iMesArquivo, $iAnoArquivo, false, 'text',3,"");
          ?>
         </td>
      </tr>
      <tr>
         <td align="right"><b>Intervalo Geração :</b></td>
         <td >
          <?
            db_inputdata('datai',@$datai_dia,@$datai_mes,@$datai_ano,true,'text',1,"");
            echo " à "; 
            db_inputdata('dataf',@$dataf_dia,@$dataf_mes,@$dataf_ano,true,'text',1,"");
          ?>
         </td>
      </tr>      
      
      <tr>
         <td align="right">
            <b>Código Empresa :</b>
         </td>
         <td> 
							<?
							   //db_input('ht15_sequencial',6,false,'','text',3," onchange='js_pesquisaInscricao(false);'");
			           db_input('cod_empresa', 10, '', '', 'text', 1, '');
			       ?>          
         </td>
      </tr>
      <tr>
         <td align="right">
            <b>Código Município :</b>
         </td>
         <td> 
              <?
                 db_input('cod_municipio', 10, '', '', 'text', 1, '');
             ?>          
         </td>
      </tr>      

          <?
             $oTipoResumo = new cl_formulario_rel_pes;
					   
             $oTipoResumo->usaregi = true;
					   $oTipoResumo->usalota = true;
					   $oTipoResumo->usaloca = true;
             $oTipoResumo->manomes = false;
             $oTipoResumo->onchpad = true;
             
             $oTipoResumo->resumopadrao = "l";
             $oTipoResumo->strngtipores = "lmt";
  				   $oTipoResumo->gera_form();           
          ?>
    </table>
  </fieldset> 
  <table style="margin-top: 10px;">
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="gerar" id="gerar" type="button" value="Gerar" onclick="js_gerar();" >
        </td>
      </tr>  
  </table>
</form>   

</center>   
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>
<script>
  var sUrlRPC = 'pes4_medicinaocupacional.RPC.php';
   
function js_gerar() {

  var sIntervaloA = $F('datai');
  var sIntervaloB = $F('dataf');
  var iEmpresa    = $F('cod_empresa');
  var iMunicipio  = $F('cod_municipio');
  var sResumo     = $F('tipores');
  var sFiltro     = $F('tipofil');
  var msgDiv      = "Processando Arquivo TXT \n Aguarde ...";
  var sTipoFiltro = "";
  
  if (iEmpresa == null || iEmpresa == "") {
    
    alert ('Preencher o código da empresa.');
    $('cod_empresa').focus();
    return false;
  }
  if (iMunicipio == null || iMunicipio == "") {
    
    alert ('Preencher o código do município.');
    $('cod_municipio').focus();
    return false;
  } 

  if (sFiltro != 0 || sFiltro != "0" ) {  // definira se o sTipoFiltro sera between ou in
  
	  switch (sFiltro) {
	  
	    case "s" :  // selecionados
	      
	      sTipoFiltro = js_getlistFiltroSelecionados(sResumo);
	    
	    break;
	    
	    case "i" :  // intervalo
	    
	      sTipoFiltro = js_getListaIntervalo(sResumo);
	    
	    break;    
	  
	  }
 } 
  
  var oParametros         = new Object();
  oParametros.exec        = 'txt';  
  oParametros.sIntervaloA = sIntervaloA;
  oParametros.sIntervaloB = sIntervaloB;  
  oParametros.iEmpresa    = iEmpresa;
  oParametros.iMunicipio  = iMunicipio;
  oParametros.sResumo     = sResumo;
  oParametros.sFiltro     = sFiltro;      
  oParametros.sTipoFiltro = sTipoFiltro;   
  
  
  js_divCarregando(msgDiv, 'msgBox');
   
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoTXT
                                             });   


}   

function js_retornoTXT(oAjax) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.status == 1) {
     
     var listagem  = oRetorno.arquivotxt+"# Download do Arquivo - "+ oRetorno.arquivotxt+"|";
         listagem += oRetorno.leiaute+"# Download Leiaute - "   + oRetorno.leiaute+"|";
         js_montarlista(listagem,'form1');
      
      
    } else {
    
       alert('Erro :' + oRetorno.message );
    }
}   
   
// função que retorna a lista de registros selecionados

function js_getlistFiltroSelecionados(sTipo) {

      var sListaSelecionados   = "";
      var sVirgSelecionados    = "";
      var campo                = "";
      
      switch (sTipo) {        // definimos o nome do campo dos itens selecionados (lotação, matricula, trabalho)
                              // o campo muda dependendo da opção no 'tipo de resumo'
        case "l" :
          campo = "sellotac";
        break;
        
        case "m" :
          campo = "selregist";
        break;
        
        case "t" :
          campo = "sellocal";
        break;                
      
      }
      /*
          cria a lista de programas selecionados
      */
      for(iSel = 0; iSel < document.form1.elements[campo].length; iSel++){
      
         sListaSelecionados += (sVirgSelecionados + document.form1.elements[campo].options[iSel].value);
         sVirgSelecionados = ",";
      }
     return   " in ("+sListaSelecionados+") ";
}   

// cria a lista se a opção do tipo de filtro for por intervalos
function js_getListaIntervalo(sTipo) {

  var iIntervaloA     = "";
  var iIntervaloB     = "";
  var sListaIntervalo = "";
      
  switch (sTipo) {        // definimos o nome do campo dos intervalos
      
    case "l" :      // lotaçao
    
    iIntervaloA = "lotacao1"; 
    iIntervaloB = "lotacao2"; 
    break;
        
    case "m" :      // matricula
      
     iIntervaloA = "registro1";
     iIntervaloB = "registro2";
    break;
        
    case "t" :      // trabalho
      
     iIntervaloA = "local1";
     iIntervaloB = "local2";
    break;                
      
  }
  var iInicial    = $F(iIntervaloA);
  var iFinal      = $F(iIntervaloB);  
  sListaIntervalo = " between "+iInicial+" and "+iFinal;
  
  return sListaIntervalo;
  
} 
   


</script>