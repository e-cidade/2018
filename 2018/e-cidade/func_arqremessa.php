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

set_time_limit(0);
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

require_once ("classes/db_lista_classe.php");
require_once ("classes/db_listadeb_classe.php");
require_once ("classes/db_listanotifica_classe.php");
require_once("classes/db_certidarqremessa_classe.php");
require_once ("libs/db_sql.php");
require_once ("classes/db_termo_classe.php");
require_once ("classes/db_cgm_classe.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("classes/db_listacda_classe.php");

$clcgm             = new cl_cgm;
$cllista           = new cl_lista;
$cllistadeb        = new cl_listadeb;
$cllistanotifica   = new cl_listanotifica;
$clListaCda        = new cl_listacda;
$clrotulo          = new rotulocampo;
$oCertidArqremessa = new cl_certidarqremessa;
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k51_procede');
$clrotulo->label('k51_descr');
$clrotulo->label('v83_sequencial');
$clrotulo->label('v83_nomearq');
$instit = db_getsession("DB_instit");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$oPost = db_utils::postMemory($_POST);

  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("classes/dbViewAvaliacoes.classe.js");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("widgets/dbmessageBoard.widget.js");  
  db_app::load("dbcomboBox.widget.js"); 
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<style>

  .link_botao {
    cursor: pointer;
  }  
    
</style>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" onload="js_gridNaoProcessados();"  >

<center>
  <form name="form1" method="post">
    <fieldset style="margin-top: 50px;width: 500px;">
      <legend><b>Arquivos de Remessa Não Processados</b></legend>
      <table border="0" >

        <tr>
          <td align="right" nowrap title="<?=@$Tk60_codigo?>" >
            <b>Sequencial</b>
          </td>
          <td align="left">
            <?
              db_input("v83_sequencial",  4, $Iv83_sequencial, true, "text", 1, "");
            ?>
          </td>
        </tr>

        <tr>
          <td align="right" nowrap title="<?=@$Tk60_codigo?>" >
            <b>Nome do Arquivo</b>
          </td>
          <td align="left">
            <?
              db_input("v83_nomearq",  47, $Iv83_nomearq, true, "text", 1, "");
            ?>
          </td>
        </tr>

        <tr>
          <td align="right" nowrap title="<?=@$Tk60_codigo?>" >
            <b><?db_ancora("Lista", "js_pesquisalista(true);", 4);?></b>
          </td>
          <td align="left">
            <?
              db_input("k60_codigo",  4, $Ik60_codigo, true, "text", 4, "onchange='js_pesquisalista(false);'");
              db_input("k60_descr",  40, $Ik60_descr,  true, "text", 3, "");
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input type="button" id="pesquisar"  value="Pesquisar" onclick="js_pesquisanaoprocessado();" >
            <input type="button" id="limpar"     value="Limpar"    onclick="js_limpar();" >
            <input type="button" id="fechar"     value="Fechar"    onclick="js_fechar();" >
          </td>
        </tr>  
      </table>
      <div id='ctnGridNaoProcessados'>

      </div>      
      
    </fieldset> 
  </form>
</center>
<? 
//db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>

<script><!--
var sUrlRPC = "jur4_certidarqremessa.RPC.php";


function preencheNaoProcessados(iSequencial, sNome, dDataArquivo){

  //alert(sNome);
  parent.$('iArqRemessa').value   = iSequencial;
  parent.$('v83_nomearq').value   = sNome;
  parent.$('v83_nomearq').value   = sNome;
  parent.$('v84_dtarquivo').value = dDataArquivo;
  
  top.corpo.db_iframe_arqremessa.hide();
}

 /*
  * Inicia a Montagem do grid (sem os registros)
  *
  */
function js_gridNaoProcessados() {

  oGridNaoProcessados = new DBGrid('gridNaoProcessados');
  oGridNaoProcessados.nameInstance = 'oGridNaoProcessados';
  oGridNaoProcessados.setCellWidth(new Array( '70px' ,
                                              '280px',
                                              '100px'
                                           ));
  oGridNaoProcessados.setCellAlign(new Array( 'left'  ,
                                              'left'  ,
                                              'center'
                                           ));
  oGridNaoProcessados.setHeader(new Array( 'Sequencial',
                                           'Nome Arquivo',
                                           'Data Geração'
                                        ));
  oGridNaoProcessados.setHeight(300);
  oGridNaoProcessados.show($('ctnGridNaoProcessados'));
  oGridNaoProcessados.clearAll(true);
  
}


function js_pesquisanaoprocessado() {

  var iSequencial          = $F('v83_sequencial');
  var sNomeAqruivo         = $F('v83_nomearq');
  var iLista               = $F('k60_codigo');
  
  var oParametros          = new Object();
  var msgDiv               = _M('tributario.juridico.func_arqremessa.pesquisando_registros');
  oParametros.exec         = 'naoprocessados'; 
   
  oParametros.iCodLista    = iLista;     
  oParametros.iSequencial  = iSequencial;
  oParametros.sNomeAqruivo = sNomeAqruivo;
  
  js_divCarregando(msgDiv,'msgBox');
   
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoNaoProcessados
                                             });   
  
}

function js_retornoNaoProcessados(oAjax){
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    
      if ( oRetorno.dados.length == 0 ) {
      
        alert(_M('tributario.juridico.func_arqremessa.nenhum_arquivo_encontrado'));
        oGridNaoProcessados.clearAll(true);
        return false;
      } 
      oGridNaoProcessados.clearAll(true); 
      oRetorno.dados.each( 
                    function (oDado, iInd) {       

                        var aRow    = new Array();  
                            aRow[0] = oDado.v83_sequencial;
                        var sNome   = oDado.v83_nomearq.urlDecode();
                            aRow[1] = "<span class='link_botao' onclick='preencheNaoProcessados("+aRow[0]+",\" "+sNome+" \", \" "+oDado.v83_dtgeracao+" \");' >"+oDado.v83_nomearq.urlDecode()+"</span>";                             
                            aRow[2] = oDado.v83_dtgeracao;
                            oGridNaoProcessados.addRow(aRow);
                       });
      oGridNaoProcessados.renderRows(); 
          
}    




function js_pesquisalista(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lista','func_listacda.php?funcao_js=parent.js_mostralista1|k60_codigo|k60_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_lista','func_listacda.php?pesquisa_chave='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostralista','Pesquisa','false');
  }
}

function js_mostralista(chave,erro){
  document.form1.k60_descr.value = chave;
  if(erro==true){
    document.form1.k60_descr.focus();
    document.form1.k60_descr.value = '';
  }
  db_iframe_lista.hide();
  //js_habilita();
}

function js_mostralista1(chave1,chave2){
  document.form1.k60_codigo.value = chave1;
  document.form1.k60_descr.value = chave2;
  db_iframe_lista.hide();
  //js_habilita();
}



function js_limpar(){

  $('v83_sequencial').value = ''
  $('v83_nomearq')   .value = ''
  $('k60_codigo')    .value = ''
  $('k60_descr')     .value = ''
  oGridNaoProcessados.clearAll(true);
}
function js_fechar() {

  top.corpo.db_iframe_arqremessa.hide();

}

--></script>
<script>

$("v83_sequencial").addClassName("field-size2");
$("v83_nomearq").addClassName("field-size9");
$("k60_codigo").addClassName("field-size2");
$("k60_descr").addClassName("field-size7");

</script>