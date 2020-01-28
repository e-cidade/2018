<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

set_time_limit(0);
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_lista_classe.php");
require_once ("classes/db_listadeb_classe.php");
require_once ("classes/db_listanotifica_classe.php");
require_once ("libs/db_sql.php");
require_once ("classes/db_termo_classe.php");
require_once ("classes/db_cgm_classe.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_listacda_classe.php");

$clcgm           = new cl_cgm;
$cllista         = new cl_lista;
$cllistadeb      = new cl_listadeb;
$cllistanotifica = new cl_listanotifica;
$clListaCda      = new cl_listacda;
$clrotulo        = new rotulocampo;
$instit          = db_getsession("DB_instit");

$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k51_procede');
$clrotulo->label('k51_descr');
$clrotulo->label('v83_nomearq');
$clrotulo->label('v84_nomearq');

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("strings.js");
?>
<style>

  .fieldInterno{
    margin-top: 10px;
    
  }
  .botoes{
    margin-top : 10px;
     
  }

</style>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC">

<center>

  <form name="form1" method="post">
    <fieldset style="margin-top: 50px;width: 700px;">
      <legend><b>Relatório de Processos Fiscais</b></legend>
      
      <fieldset class="fieldinterno">
        <legend><b>Arquivo de Remessa </b></legend>
		      <table border="0" width="100%" >
		        <tr>
		          <td align="right" nowrap width="40%" ><b>
		            Nome do Arquivo :
		            </b>
		          </td>
		          <td align="left">
		            <?
		              db_input("v83_nomearq",  40, $Iv83_nomearq, true, "text", 3, "");
		            ?>
		          </td>
		        </tr>
		        <tr>
		          <td align="right" >
		            <b>Data do Arquivo :</b>
		          </td>
		          <td align="left">
		           <? db_inputdata("v83_dtgeracao",@$v83_dtgeracao_dia,@$v83_dtgeracao_mes,@$v83_dtgeracao_ano,true,'text', 3);?>
		          </td>
		        </tr>        
		      </table>
      </fieldset>
      

      <fieldset class="fieldinterno">
        <legend><b>Arquivo de Retorno</b></legend>
          <table border="0" width="100%" align="center">
            <tr>
              <td align="right" nowrap width="40%" ><b>
                Nome do Arquivo :
                </b>
              </td>
              <td align="left">
                <?
                  db_input("v84_nomearq",  40, $Iv83_nomearq, true, "text", 3, "");
                  db_input("v84_sequencial",  5, null, true, "hidden", 3, "");
                ?>
              </td>
            </tr>
            <tr>
              <td align="right" >
                <b>Data do Arquivo :</b>
              </td>
              <td align="left">
               <? db_inputdata("v84_dtarquivo",@$v84_dtarquivo_dia,@$v84_dtarquivo_mes,@$v84_dtarquivo_ano,true,'text', 3);?>
              </td>
            </tr>        
          </table>
      </fieldset>

      <fieldset class="fieldinterno">
        <legend><b>Registros</b></legend>
          <table border="0" width="100%" align="center" >
            <tr>
              <td align="right" nowrap width="40%" ><b>
                Intervalo de CDA :
                </b>
              </td>
              <td align="left">
                <?db_input("iCdaIni",  10, '', true, "text", 3, "");?> <b>à</b>
                <?db_input("iCdaFim",  10, '', true, "text", 3, "");?>
              </td>
            </tr>
            <tr>
              <td align="right" >
                <b>Intervalo de Inicial :</b>
              </td>
              <td align="left">
                <?db_input("iInicialIni",  10, '', true, "text", 3, "");?> <b>à</b>
                <?db_input("iInicialFim",  10, '', true, "text", 3, "");?>
              </td>
            </tr>        
          </table>
      </fieldset>
      <div id='botoes' class="botoes">
	      <input type="button" id="processarRelatorio"  value="Processar" onclick="js_processaRelatorio();" />
	      <input type="button" id="pesquisar"    value="Pesquisar" onclick="js_pesquisar();"/>
      </div>
    </fieldset> 
    
  </form>
  
  
</center>

<? 
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>

<script>

function js_processaRelatorio(){

  var sNomeRemessa = $F('v83_nomearq'); 
  var sDataRemessa = $F('v83_dtgeracao');
  var sNomeRetorno = $F('v84_nomearq');
  var sDataRetorno = $F('v84_dtarquivo');
  var iCdaIni      = $F('iCdaIni');
  var iCdaFim      = $F('iCdaFim');
  var iInicialIni  = $F('iInicialIni');
  var iInicialFim  = $F('iInicialFim');
  var iSeqRetorno  = $F('v84_sequencial');
  var sFonte      = "jur2_processofiscal002.php";
  if (sNomeRemessa == '' || sNomeRetorno == '') {
  
    alert('Preencha os Dados para o Relatório.');
    js_pesquisar();
    return false;
  }
  var sQuery  = "";
      sQuery  = "?sNomeRemessa=" + sNomeRemessa;
      sQuery += "&sDataRemessa=" + sDataRemessa;
      sQuery += "&sNomeRetorno=" + sNomeRetorno;
      sQuery += "&sDataRetorno=" + sDataRetorno;
      sQuery += "&iCdaIni="      + iCdaIni;
      sQuery += "&iCdaFim="      + iCdaFim;
      sQuery += "&iInicialIni="  + iInicialIni;
      sQuery += "&iInicialFim="  + iInicialFim;
      sQuery += "&iSeqRetorno="  + iSeqRetorno;
      
      jan = window.open(sFonte+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);  
  
}


function js_pesquisar(){

  js_OpenJanelaIframe('top.corpo','db_iframe_remessaprocessados','func_remessaprocessados.php?funcao_js=parent.js_mostraremessa1|iArqRemessa|v83_nomearq','Pesquisa',true);


}
function js_mostraremessa1(chave1,chave2){
  document.form1.iArqRemessa.value = chave1;
  document.form1.v83_nomearq.value = chave2;
  db_iframe_arqremessa.hide();
  //js_habilita();
}



</script>