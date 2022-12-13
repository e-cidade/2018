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

require_once ("libs/db_sql.php");
require_once ("classes/db_termo_classe.php");
require_once ("classes/db_cgm_classe.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_listacda_classe.php");
require_once("dbforms/db_classesgenericas.php");

$clcgm           = new cl_cgm;
$cllista         = new cl_lista;
$cllistadeb      = new cl_listadeb;
$cllistanotifica = new cl_listanotifica;
$clListaCda      = new cl_listacda;
$clrotulo        = new rotulocampo;

$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k51_procede');
$clrotulo->label('k51_descr');
$instit = db_getsession("DB_instit");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("strings.js");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" onload="js_habilita();"  >
  <form class="container" name="form1" method="post">
    <fieldset>
      <legend>Procedimentos - Geração de Arquivos de Remessa</legend>
      <table class="form-container">
        <tr>
          <td align="right" nowrap title="<?=@$Tk60_codigo?>" >
            <?db_ancora(@$Lk60_codigo, "js_pesquisalista(true);", 4);?>
          </td>
          <td align="left">
            <?
              db_input("k60_codigo",  4, $Ik60_codigo, true, "text", 4, "onchange='js_pesquisalista(false);'");
              db_input("k60_descr",  40, $Ik60_descr,  true, "text", 3, "");
            ?>
          </td>
        </tr>
      </table>
    </fieldset> 
    <input type="button" id="processar"  value="Procesar" onclick="js_processar();">
  </form>
<? 
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>

<script>

var sUrlRPC = "jur4_certidarqremessa.RPC.php";

function js_processar(){

  var iLista                             = $F('k60_codigo');
  var oParametros                        = new Object();
  oParametros.exec                       = 'processar';  
  oParametros.iLista                     = iLista;   
  js_divCarregando(_M('tributario.juridico.jur4_certidarqremessa001.processando_arquivo'),'msgBox');
   
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoProcessar
                                             }); 

}
function js_retornoProcessar(oAjax){

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.status == 1) {
      
      if ( oRetorno.dados.length == 0 ) {
      
        //alert('Nenhum Programa encontrado!');
        return false;
      } 
      
     var listagem  = oRetorno.dados+"# Download do Arquivo - "+ oRetorno.dados;
         js_montarlista(listagem,'form1');      
          
    } else {
    
      alert(oRetorno.message.urlDecode());
      location.href = "jur4_certidarqremessa001.php";
    
    }

}

function js_habilita(){

  if($F('k60_codigo') == null || $F('k60_codigo') == '' ) {
    $('processar').disabled = true;
  } else {
    $('processar').disabled = false;
  }

}

function js_pesquisalista(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lista','func_listacda.php?funcao_js=parent.js_mostralista1|k60_codigo|k60_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_lista','func_listacda.php?pesquisa_chave='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostralista','Pesquisa','false');
  }
}

function js_mostralista(chave,erro){
  document.form1.k60_descr.value = chave;
  if(erro==true){
    document.form1.k60_descr.focus();
    document.form1.k60_descr.value = '';
  }
  db_iframe_lista.hide();
  js_habilita();
}

function js_mostralista1(chave1,chave2){
  document.form1.k60_codigo.value = chave1;
  document.form1.k60_descr.value = chave2;
  db_iframe_lista.hide();
  js_habilita();
}

</script>
<script>

$("k60_codigo").addClassName("field-size2");
$("k60_descr").addClassName("field-size7");

</script>