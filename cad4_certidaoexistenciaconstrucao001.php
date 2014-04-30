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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
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

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1;js_gridConstrucoes();" bgcolor="#cccccc">

<center>
<form name="form1" id='form1' method="post" action="cad4_certidaoexistenciaconstrucao002.php">

  <fieldset style="margin-top:50px; width: 700px;">
  
    <legend><strong>Emissão de Certidão de Existência </strong></legend>
    <table  align="left" width="90%" cellpadding="1" border="0">

      <tr>   
        <td>
          <strong>
            <?
              db_ancora("Matrícula:",' js_matri(true); ',1);
            ?>
          </strong>
        </td>
        <td> 
          <?
           db_input('k00_matric',10,'',true,'text',1,"class='pesquisa' onchange='js_matri(false);'");
           db_input('z01_nome',60,0, true,'text',3,"class='label'","z01_nomematri");
          ?>
        </td>
      </tr>  

    </table>
  </fieldset> 
  
          <fieldset style="width: 700px; margin-top: 10px;"> 
          <legend><strong>Lista de Construções</strong></legend> 
            <table  cellpadding="3" border="0">
              <tr>
                <td>
                  <div id="ctnGridConstrucoes" style="margin-top: 10px;"> </div>
                </td>
              </tr>            
            </table>
          </fieldset>   
  
  <table style="margin-top: 10px;">
      <tr>
        <td colspan="2" align = "center"> 
          <input type ='hidden' id='iConstrucao' name='iConstrucao' value='' />
          <input  name="imprimir" id="imprimir" type="button" value="Pesquisar" onclick="js_emite();" >
        </td>
      </tr>  
  </table>
</form>   
</center>   
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>


var sUrlRPC = "cad4_certidaoexistenciaconstrucao.RPC.php";
/*  
 *  função para retornar as construções da matricula selecionada
 *  
 */  
function js_getConstrucoes() {

  oGridConstrucoes.clearAll(true);

  var iMatricula         = $F('k00_matric');
  var msgDiv             = "Carregando Lista de Construções \n Aguarde ...";
  var oParametros        = new Object();
  
  oParametros.exec       = 'getConstrucoes';  
  oParametros.iMatricula = iMatricula;   
  
  js_divCarregando(msgDiv,'msgBox');
   
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_construcoes
                                             });   
}

function js_construcoes(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.iStatus == 1) {
      
      oRetorno.aDados.each( 
          function (oDado, iInd) {       

              var aRow    = new Array();  
                  aRow[0] = "<input type='radio' id='idConstrucao' name='idConstrucao' onclick='js_setIdConstrucaoSelecionado("+oDado.iCodigoConstrucao+")' >";   
                  aRow[1] = oDado.iCodigoConstrucao;
                  aRow[2] = oDado.nArea            ;
                  aRow[3] = oDado.iAnoConstrucao   ;
                  
                  oGridConstrucoes.addRow(aRow);
             });
      oGridConstrucoes.renderRows(); 
      
    } else {
      
      alert(oRetorno.sMessage.urlDecode());
      return false;
    }
}




function js_matri(mostra){
  
  var matri = $F("k00_matric");
  
  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe3','func_iptubaseConstrucoes.php?funcao_js=parent.js_mostramatri|0|2',
                       'Pesquisa',true);
  }else{
    if (matri != "") {
	    js_OpenJanelaIframe('','db_iframe3','func_iptubaseConstrucoes.php?pesquisa_chave='+matri+
	                                                                  '&funcao_js=parent.js_mostramatri1','Pesquisa',false);
    }                                                                 
  }
}
function js_mostramatri(chave1, chave2){
  
  $("k00_matric") .value = chave1;
  $("z01_nomematri")    .value = chave2;
  db_iframe3.hide();
  js_getConstrucoes();
  $('iConstrucao').value ='';
}
function js_mostramatri1(chave, erro) {
  
  $("z01_nomematri")    .value = chave;
  if (erro == true) {
     
    $("k00_matric").focus(); 
    $("k00_matric").value = '';
  }
  $('iConstrucao').value ='';
  js_getConstrucoes();
}


/*
 * Inicia a Montagem da grid construcoes (sem os registros)
 *
 */
function js_gridConstrucoes() {

 oGridConstrucoes = new DBGrid('Construcoes');
 oGridConstrucoes.nameInstance = 'oGridConstrucoes';
 oGridConstrucoes.setCellWidth(new Array(  '30px',
                                           '70px' ,
                                           '120px',
                                           '150px'
                                          ));
 
 oGridConstrucoes.setCellAlign(new Array(  'center',
                                           'left'  ,
                                           'left'  ,
                                           'center'
                                          ));
 
 
 oGridConstrucoes.setHeader(new Array(  '',
                                        'Id Constr.',
                                        'Área',
                                        'Ano Constr.'
                                       ));
                                      

 oGridConstrucoes.setHeight(150);
 oGridConstrucoes.show($('ctnGridConstrucoes'));
 oGridConstrucoes.clearAll(true);
 
}

function js_setIdConstrucaoSelecionado(iConstrucao) {

  $('iConstrucao').value = iConstrucao;
}

function js_emite(){

  var iMatricula = $F("k00_matric");
  var iConstr    = $F('iConstrucao');

  if (iMatricula == '') {
    
    alert('Selecione uma Matrícula.');
    return false;
  }
  if (iConstr == '') {

    alert('Selecione uma Construção.');
    return false;
  }
  $('form1').submit();
}

</script>