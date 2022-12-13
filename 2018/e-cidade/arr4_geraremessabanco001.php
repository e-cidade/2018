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
require_once ("libs/db_sql.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$instit = db_getsession("DB_instit");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php 
db_app::load("estilos.css, grid.style.css, scripts.js, strings.js, prototype.js, datagrid.widget.js, dbtextField.widget.js");
?>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" onload="js_alteraOnblur_db_inputdata()">

<center>
  <form name="form1" method="post">
    <fieldset style="margin-top: 50px;width: 500px;">
      <legend><b>Geração de Arquivos de Remessa de Cobrança Registrada ao Banco	</b></legend>
      <table border="0">
        <tr>
          <td align="right" nowrap title="Data" >
            <strong>Data para Processamento: </strong>
          </td>
          <td align="left">
            <?
              db_inputdata("dtProc", null, null, null, true, 'text', 1, '',null, '', null, '',"", "js_pesquisaArquivos()");
            ?>
          </td>
        </tr>
      </table>
      
      <fieldset id="fieldsetArquivos" style="display:none">
        <legend><strong>Arquivos existentes</strong></legend>
        
        <div style="width: 100%" id="ctnGridArquivos"></div>
                
      </fieldset>

    </fieldset> 
            <input type="button" id="processar" style="margin-top: 10px;"  value="Processar" onclick="js_processar();" />
  </form>
</center>
<? 
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>

<script>

var sUrlRPC = "arr4_arqremessacobranca.RPC.php";

function js_processar(){

  var oParametros                        = new Object();
  var msgDiv                             = "Processando Arquivo \n Por Favor Aguarde ...";    
  oParametros.dtProc                     = $F("dtProc");  
  oParametros.exec                       = 'geraArqBanco';
  oParametros.aSelecionados              = new Array();
     
  if ( oParametros.dtProc == "") {
    alert("Informe a data para processamento do arquivo");
    return false;
  }

  aSelecionados = oGridArquivos.getSelection();

  aSelecionados.each( function (aRow) {
	  oParametros.aSelecionados.push(aRow[0]);
  }); 

  if (oParametros.aSelecionados.length > 0) {
	  oParametros.lReemitir = true; 
  } else {	  
	  oParametros.lReemitir = false;  
  }
  
  js_divCarregando(msgDiv,'msgBox');
   
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
      
    if ( oRetorno.arquivo.length == 0 ) {
      return false;
    } 

    var sListagem = '';
    
    for (var iIndice = 0; iIndice < oRetorno.arquivo.length; iIndice++) {
    	sListagem += oRetorno.arquivo[iIndice].urlDecode()+"# Download do Arquivo - "+ oRetorno.arquivo[iIndice].urlDecode()+"|";    		
    }
    js_montarlista(sListagem,'form1');
          
  } else {
    
    alert(oRetorno.message.urlDecode());
    location.href = "arr4_geraremessabanco001.php";
    
  }

}

// Função para pesquisar os arquivos já gerados
function js_pesquisaArquivos() {

	var oParametros                        = new Object();
  var msgDiv                             = "Consultando Arquivos \n Por Favor Aguarde ...";
  oParametros.exec                       = 'pesquisaArquivos';  
  oParametros.dtProc                     = $F("dtProc");
    
  if ( oParametros.dtProc == "") {
    alert("Informe a data para processamento do arquivo");
    return false;
  }  
  
  js_divCarregando(msgDiv,'msgBox');
   
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoPesquisaArquivos
                                             });

	
}

// Função para criar a GRID
function js_criaGrid() {
	
	oGridArquivos                   = new DBGrid('datagridArquivos');
  oGridArquivos.sName             = 'datagridArquivos';
  oGridArquivos.nameInstance      = 'oGridArquivos';
  oGridArquivos.setCheckbox       (0);
  
  oGridArquivos.setCellWidth      ( new Array('0', '100%') );
  oGridArquivos.setCellAlign      ( new Array('center','left') );
  oGridArquivos.setHeader         ( new Array('Código','Arquivo') );
    
  oGridArquivos.show              ( $('ctnGridArquivos') );
  oGridArquivos.clearAll          (true);
  
}

// Função para retornar os arquivos txt já gerados
function js_retornoPesquisaArquivos(oAjax) {

  try {
	  
	  js_removeObj('msgBox');
	
    var oRetorno = eval("("+oAjax.responseText+")");


    if (oRetorno.aRegistros.length == 0) {
        document.getElementById('fieldsetArquivos').style.display = 'none';
    } else {
        
    	document.getElementById('fieldsetArquivos').style.display = 'block';
    	    	
    } 

    js_criaGrid();

  	var fFuncaoPadrao = oGridArquivos.selectSingle;
    oGridArquivos.selectSingle = function (oCheckbox, iIdLinhaGrid, oTableRow) {
    
      fFuncaoPadrao(oCheckbox, iIdLinhaGrid, oTableRow);	
    	  
    }
  
    for (var iIndice = 0; iIndice < oRetorno.aRegistros.length; iIndice++) {
  
  	     var aCelulas = new Array(oRetorno.aRegistros[iIndice].v78_sequencial , oRetorno.aRegistros[iIndice].v78_nomearq);
  	     oGridArquivos.addRow( aCelulas );
  	     
    }
    
    oGridArquivos.renderRows();       
    
	} catch ( sErro ) {
    alert(sErro);
	}
	
}

// Função para sobreescrever o atributo onblur do campo de data. É adicionado junto a função do gerador uma função para
// pesquisar arquivos.
function js_alteraOnblur_db_inputdata() {
	
	document.getElementById('dtProc').setAttribute('onblur',document.getElementById('dtProc').getAttribute('onblur')+'js_pesquisaArquivos()');
	
}

</script>