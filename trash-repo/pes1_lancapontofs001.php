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
require_once("classes/db_convenio_classe.php");
require_once("classes/db_relac_classe.php");
require_once("classes/db_movrel_classe.php");
require_once("classes/db_pontofs_classe.php");
require_once("classes/db_rhpessoal_classe.php");
require_once("libs/db_sql.php");
require_once("libs/db_app.utils.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clconvenio = new cl_convenio;
$clrelac = new cl_relac;
$clmovrel = new cl_movrel;
$clpontofs = new cl_pontofs;
$clrhpessoal = new cl_rhpessoal;
$clrotulo = new rotulocampo;
$clrotulo->label("r54_regist");
$clrotulo->label("z01_nome");
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");
$clrotulo->label("r54_codrel");
$clrotulo->label("r56_descr");
$clrotulo->label("r54_codeve");
$clrotulo->label("r55_descr");
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
  db_app::load("classes/DBViewLancamentoAtributoDinamico.js");
  db_app::load("widgets/dbmessageBoard.widget.js");
  db_app::load("widgets/dbcomboBox.widget.js");
  db_app::load("widgets/dbtextField.widget.js");
  db_app::load("widgets/dbtextFieldData.widget.js");
  db_app::load("widgets/windowAux.widget.js");   
?>


</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="if(document.form1.r54_codrel)document.form1.r54_codrel.focus();">


<center>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 40px;">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <center>
<fieldset style="margin-top: 20px; width: 550px;">
<legend><strong>Processar Valores</strong></legend>      
      <form name="form1" method="post">
	  <table border="0">
        <tr>
          <td align="left"><strong>Ano/Mês:</strong></td>
          <td>
       	    <?
       	    $ano = db_anofolha();
    	    db_input("ano",4,'',true,'text',3)
	        ?>
	        &nbsp;/&nbsp;
	        <?
       	    $mes = db_mesfolha();
            db_input("mes",2,'',true,'text',3)
            ?>
          </td>
        </tr>
        <tr>
          <td align="left" nowrap title="<?=@$Tr54_codrel?>">
            <?
            db_ancora(@$Lr54_codrel,"js_pesquisar54_codrel(true);",1);
            ?>
          </td>
          <td> 
            <?
            db_input('r54_codrel',8,$Ir54_codrel,true,'text',1,"onchange='js_pesquisar54_codrel(false);' tabIndex='1'");
            db_input('r56_descr',40,$Ir56_descr,true,'text',3,"");
            ?>
          </td>
        </tr>
        <tr> 
          <td align="left" title="<?=$Tr54_regist?>"> 
            <?
            db_ancora(@ $Lr54_regist, "js_pesquisar54_regist(true);", 1);
    		?>
          </td>
          <td> 
            <?
            db_input('r54_regist', 8, $Ir54_regist, true, 'text', 1, " onchange='js_pesquisar54_regist(false);' tabIndex='2'")
            ?>
            <?
            db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr>
          <td align="left" nowrap title="<?=@$Tr54_codeve?>">
            <?
            db_ancora(@$Lr54_codeve,"js_pesquisar54_codeve(true);",1);
            ?>
          </td>
          <td> 
            <?
            db_input('r54_codeve',8,$Ir54_codeve,true,'text',1,"onchange='js_pesquisar54_codeve(false);' tabIndex='3'");
            db_input('r55_descr',40,$Ir55_descr,true,'text',3,"");
            ?>
          </td>
        </tr>
        <tr>
          <td align="left" nowrap title="<?=@$Tr54_codeve?>">
            <strong>
            
              Valores Duplicados:
            </strong>
          </td>
          <td> 
            <?
            $aAcoes  = array(
                             0 => "Selecione",
                             1 => "Somar",
                             2 => "Substituir",
                             3 => "Perguntar" 
                            );
            db_select('iTipoAcao', $aAcoes, '',1);
            ?>
          </td>
        </tr>
       </table> 
</fieldset>           
<table border="0">       
        <tr> 
          <td height="25" colspan="2" align="center">
            <input type="button" value="Processar" name="janela" onclick="processarDadosConvenio();" tabIndex='4'> 
          </td>
        </tr>
      </table>
  
      </form>
      </center>
    </td>
  </tr>
</table>


</center>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

var sUrlRPC = 'pes4_processaconvenios.RPC.php';


 /*
  * Inicia a Montagem do grid (sem os registros)
  *
  */
function js_gridRubricas() {

  oGridRubricas              = new DBGrid('Rubricas');
  oGridRubricas.nameInstance = 'oGridRubricas';
  //oGridRubricas.setCheckbox(0);
  oGridRubricas.setCellWidth(new Array( '70px' ,
                                        '180px',
                                        '100px',
                                        '100px',
                                        '100px',
                                        '100px',
                                        '100px'
                                      ));
  
  oGridRubricas.setCellAlign(new Array( 'left'  ,
                                        'left'  ,
                                        'center',
                                        'right' ,
                                        'center',
                                        'right' ,
                                        'center'
                                      ));
  
  oGridRubricas.setHeader(new Array( 'Matrícula',
                                     'Nome',
                                     'Rubrica',
                                     'Valor Ponto',
                                     'Qtd. Ponto',
                                     'Valor',
                                     'Ação'
                                   ));

  oGridRubricas.setHeight(300);
  oGridRubricas.show($('ctnRubrica'));
  oGridRubricas.clearAll(true);
  
}



/* 
 * Função responsavel por montar a janela auxiliar
 */
 
function js_Rubricas(sId) {


  var sContent  = "  <table align='center' width='98%'>                                                             ";
      sContent += "    <tr>                                                                                         ";
      sContent += "      <td>                                                                                       ";
      sContent += "    <fieldset>                                                                                   ";
      sContent += "      <legend>                                                                                   ";
      sContent += "        <b>Matrículas :<b>                                                                       ";
      sContent += "      </legend>                                                                                  ";
      sContent += "      <table align='center'>                                                                     ";
      sContent += "        <tr>                                                                                     ";
      sContent += "          <td><div id='ctnRubrica'></div></td>                                                   ";
      sContent += "        </tr>                                                                                    "; 
      sContent += "      </table>                                                                                   ";
      sContent += "    </fieldset>                                                                                  ";
      sContent += "    <div id='atributos'></div>                                                                   ";
      sContent += "    <table align='center'>                                                                       ";
      sContent += "      <tr>                                                                                       ";
      sContent += "        <td>                                                                                     ";
      sContent += "          <input type='button' value='Salvar' id='btnsalvar' onclick='processarRubricas();' \>   ";
      sContent += "        </td>                                                                                    ";
      sContent += "      </tr>                                                                                      ";      
      sContent += "    </table>                                                                                     ";
      sContent += "      </td>                                                                                      ";        
      sContent += "    </tr>                                                                                        ";
      sContent += "  </table>                                                                                       ";
           
      windowrubricas = new windowAux('rubricas', '&nbsp; Rúbricas Cadastradas', 900, 500);
      windowrubricas.setContent(sContent);
        
      var oMessage = new DBMessageBoard( 'msgboard1', 
                                         'Servidores que já possuem lançamentos.',
                                         'Selecione a operação que deseja efetuar e Salve os dados.',
                                         $("windowrubricas_content"
                                        ));
      oMessage.show();        
        
      windowrubricas.show(60, 300);
        
      /*
         Sobrescrevemos a função de close, para que não feche a janela antes de processar os Conflitos
      */  
      windowrubricas.setShutDownFunction(function (){
        alert('Escolha a ação desejada para cada rubrica em conflito \n e clique em Salvar.'); 
        return false;  
        windowrubricas.destroy();  
        windowrubricas = '';    
      });
      
     js_gridRubricas();          // monta a grid
    // processarDadosConvenio(); // preenche a  grid
}
/*
 * funcao para montar os registros iniciais da grid
 *
 */ 
function processarDadosConvenio() {

	 // setamos as variaveis para enviar ao processamento que retornara na grid as rubricas em conlito
	 var iAno                    = $F('ano');
	 var iMes                    = $F('mes');
	 var iConvenio               = $F('r54_codrel');
	 var iServidor               = $F('r54_regist');
	 var iRelacionamento         = $F('r54_codeve');
   var iAcaoConflito           = $F('iTipoAcao');

   if (iAcaoConflito == 0) {
     
     alert("Selecione uma ação para os conflitos.");
     $('iTipoAcao').focus();
     return false;
   }
   
   var msgDiv                  = "Aguarde ...";
   var oParametros             = new Object();
   oParametros.exec            = 'processarDadosConvenio';
   oParametros.iAno            = iAno;
   oParametros.iMes            = iMes;
   oParametros.iConvenio       = iConvenio;
   oParametros.iServidor       = iServidor;
   oParametros.iRelacionamento = iRelacionamento;
   oParametros.iAcaoConflito   = iAcaoConflito;
         
   js_divCarregando(msgDiv,'msgBox');
  
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoCompletaRubricas
                                             });
                                            
}
/*
 * funcao para montar a grid com os registros de interessados
 *  retornado do RPC
 */ 
function js_retornoCompletaRubricas(oAjax) {
    
    js_removeObj('msgBox');
    var oRetorno        = eval("("+oAjax.responseText+")");
    var sPessoalRecisao = "";
    
    if (oRetorno.status == 1) {
    
      if (oRetorno.erroPessoalRecisao.length > 0) {

        for ( var irh = 0; irh < oRetorno.erroPessoalRecisao.length ; irh++ ) {
          
          if ( sPessoalRecisao == "" ) {
            sPessoalRecisao += oRetorno.erroPessoalRecisao[irh];          
          } else {
            sPessoalRecisao += ','+oRetorno.erroPessoalRecisao[irh];
          }
        }       
        
        if ( confirm("Deseja emitir um relatório de registros não lançados?") ) {
        
          var sQuery  = "pes2_consmovrel002.php?nao_lancados=true";
              sQuery += "&rh01_regist="+sPessoalRecisao;
              sQuery += "&mes="+$('mes').value+"&ano="+$('ano').value;
          jan = window.open(sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
          jan.moveTo(0,0);
        }
      }
      
      alert(oRetorno.mensagem.urlDecode());
      //oGridRubricas.clearAll(true);
      if ( oRetorno.dados.length == 0 ) {
      
        alert('Nenhum Conflito de Rubricas Encontrado!');
        return false;
        
      } else {
      
          js_Rubricas('rub_35');
		      oRetorno.dados.each( 
		                    function (oDado, iInd) {       
		
		                        var aRow     = new Array();  
		                            aRow[0]  = oDado.matricula;
		                            aRow[1]  = oDado.nome.urlDecode();
		                            aRow[2]  = oDado.rubrica;
		                            aRow[3]  = oDado.valorponto;                            
		                            aRow[4]  = oDado.qtdeponto;
		                            aRow[5]  = oDado.valorconvenio;
		                            aRow[6]  = "<select id='cboAcao"+aRow[0]+aRow[2]+"' style='width: 100%' >";
		                            aRow[6] += " <option value='1'>Substituir</option>";
		                            aRow[6] += " <option value='2'>Adicionar</option> ";
		                            aRow[6] += "</select> ";                                                   
		                            oGridRubricas.addRow(aRow);
		                       });
		      oGridRubricas.renderRows(); 
      }
      
    } 
}

/*
    Função para realizar o processamento de rubricas em conflito

*/

function processarRubricas(){

  var msgDiv    = "Processando...";
  js_divCarregando(msgDiv,'msgBox');
  
  var aListaRow = oGridRubricas.aRows;
  
  var oLinha    = new Object();
  var aLinha    = new Array;
  //para cada linha da grid pegamos o valor das colunas a serem enviadas ao processamento
  for (iLinhas = 0; iLinhas < aListaRow.length; iLinhas++) {
     
     var  oLinhaValores           = new Object();
		      oLinhaValores.rubrica   = aListaRow[iLinhas].aCells[2].getValue();
		      oLinhaValores.matricula = aListaRow[iLinhas].aCells[0].getValue();
		      oLinhaValores.valor     = js_strToFloat(aListaRow[iLinhas].aCells[5].getValue()).valueOf();
		      oLinhaValores.acao      = aListaRow[iLinhas].aCells[6].getValue();
		      oLinhaValores.qtde      = aListaRow[iLinhas].aCells[4].getValue(); 
          aLinha[iLinhas]         = oLinhaValores;
  }

  var oParametros    = new Object();
  
  // enviamos o array criado com os atributos e valores das rubricas em conflito
  oParametros.exec   = 'processarRubricas';  
  oParametros.oDados = aLinha;   
   
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoProcessarRubricas
                                             });   


}
// retorno do processamento das rubricas em conflito
function js_retornoProcessarRubricas(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if (oRetorno.status == 1) {
      
      alert('Dados Processados com Sucesso');
      windowrubricas.destroy(); 
           
    } else {
    
      alert(oRetorno.message);
    }  
      
}



function js_abrejan(){
  qry = "";
  con = "?";
  if(document.form1.r54_codrel.value == "" && document.form1.r54_regist.value == "" && document.form1.r54_codeve.value == ""){
    // alert("Sem dados para consultar.");
    // document.form1.r54_codrel.focus();
  }
}
function js_pesquisar54_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=r&funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  }else{
    if(document.form1.r54_regist.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=r&pesquisa_chave='+document.form1.r54_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
    }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.r54_regist.focus(); 
    document.form1.r54_regist.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.r54_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpessoal.hide();
}
function js_pesquisar54_codeve(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_relac','func_relac.php?funcao_js=parent.js_mostrarelac1|r55_codeve|r55_descr|r56_dirarq&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true,'20');
  }else{
    if(document.form1.r54_codeve.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_relac','func_relac.php?pesquisa_chave='+document.form1.r54_codeve.value+'&funcao_js=parent.js_mostrarelac&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false,'0');
    }else{
      document.form1.r55_descr.value = '';
    }
  }
}
function js_pesquisar54_codrel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_convenio','func_convenioalt.php?funcao_js=parent.js_mostraconvenio1|r56_codrel|r56_descr|r56_dirarq&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true,20);
  }else{
    if(document.form1.r54_codrel.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_convenio','func_convenioalt.php?pesquisa_chave='+document.form1.r54_codrel.value+'&funcao_js=parent.js_mostraconvenio&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false,'0');
    }else{
      document.form1.r56_descr.value = '';
      document.form1.diretorio_arquivo.value = ''; 
    }
  }
}
function js_mostraconvenio(chave1,chave2,erro){
  document.form1.r56_descr.value  = chave1;
  if(erro==true){
    document.form1.r54_codrel.value = '';
    document.form1.r54_codrel.focus(); 
  }
}
function js_mostraconvenio1(chave1,chave2,chave3){
  document.form1.r54_codrel.value = chave1;
  document.form1.r56_descr.value  = chave2;
  db_iframe_convenio.hide();
}
function js_mostrarelac(chave,erro){
  document.form1.r55_descr.value  = chave;
  if(erro==true){ 
    document.form1.r54_codeve.value = '';
    document.form1.r54_codeve.focus(); 
  }
}
function js_mostrarelac1(chave1,chave2){
  document.form1.r54_codeve.value = chave1;
  document.form1.r55_descr.value  = chave2;
  db_iframe_relac.hide();
}
</script>
<?
if(isset($incluir)){
  db_msgbox($erro_msg);
  if($erro_cntRPESn > 0){
    echo "
          <script>
            if(confirm('Deseja emitir relatório de registros não lançados?')){
              jan = window.open('pes2_consmovrel002.php?nao_lancados=true".$queryst."','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
              jan.moveTo(0,0);
            }
          </script>
         ";
  }
}
?>