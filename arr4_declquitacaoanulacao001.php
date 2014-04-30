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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("dbforms/db_funcoes.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_app.utils.php");
require("libs/db_utils.php");

$oGet = db_utils::postMemory($_GET);

$clrotulo = new rotulocampo();

$clrotulo->label('ar30_sequencial');
$clrotulo->label('j01_matric');
$clrotulo->label('q02_inscr');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');

// Verifica se Sistema de Agua esta em Uso
db_sel_instit(null, "db21_usasisagua, db21_regracgmiptu, db21_regracgmiss");

if (isset($db21_usasisagua) && $db21_usasisagua != '') {
  $db21_usasisagua = ($db21_usasisagua == 't');
  
  if ($db21_usasisagua == true) {
    $j18_nomefunc = "func_aguabase.php";
    
  } else {
    $j18_nomefunc = "func_iptubase.php";
    
  }
  
} else {
  
  $db21_usasisagua = false;
  $j18_nomefunc = "func_iptubase.php";
  
}

?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <? 
      db_app::load('strings.js');
      db_app::load('scripts.js');
      db_app::load('datagrid.widget.js');
      db_app::load('prototype.js');
      db_app::load('estilos.css');
      db_app::load('grid.style.css');
      db_app::load('widgets/windowAux.widget.js');
    ?>
  </head>
  <body bgcolor=#CCCCCC style="margin: 50px">
    <form name="form1" action="" id ="form1">
      <fieldset style="width: 550px; margin: 0 auto;">
        <legend>
          <b>Declaração Quitação Geral</b>
        </legend>
        <table align="center" width="500">
      		<tr>
		  		  <td title="Origem da Declaração" width="25%">
		  		    <b>Tipo Origem</b>
		  		  </td>
		        <td>
		          <?
		            $aOrigem = array(''           => 'Selecione',
		                             'matric'     => 'Matr&iacute;cula',
		                             'inscr'      => 'Inscri&ccedil;&atilde;o',
		                             'cgm'        => 'CGM Geral',
		                             'somentecgm' => 'Somente CGM');
		            db_select('origem', $aOrigem, true, 1,
		                      ' onchange="return js_ancora_origem()" style="width: 130px" '); 
		          ?>
		        </td>  
		      </tr>
          <?
            if (isset($oGet->origem) and $oGet->origem == 'matric') {
          ?>
            <tr>    
	            <td title="<?=$Tj01_matric?>"  width="25%"> 
                <?
                  db_ancora($Lj01_matric, " js_mostramatricula(true, '$j18_nomefunc'); ", 2);
                ?>
	            </td>
	            <td> 
	              <?
	                db_input('j01_matric', 10, $Ij01_matric, true, 'text', 1,
	                         " onchange=\"js_mostramatricula(false,'$j18_nomefunc')\" ");
	                db_input('z01_nome'  , 30, $Iz01_nome  , true, 'text', 3);
	              ?>
	            </td>
            </tr> 
            <tr>
              <td colspan="2" align="center">
                <input type="button" name="pesquisar" id="pesquisar" value="Pesquisar"
                  onclick="js_pesquisa_declaracoes(j01_matric.value, 'matric')" />
              </td>
            </tr>
	        <?   
	          } elseif (isset($oGet->origem) and ($oGet->origem == 'cgm' || $oGet->origem == 'somentecgm')) {   
	        ?>
            <tr> 
              <td title="<?=$Tz01_nome?>" width="25%"> 
                <?
                  db_ancora($Lz01_nome, ' js_mostracgm(true); ', 4);
                ?>
              </td>
              <td> 
                <?
                  db_input('z01_numcgm', 10, $Iz01_numcgm, true, 'text', 4, ' onchange="js_mostracgm(false);" ');
                  db_input('z01_nome'  , 30, $Iz01_nome  , true, 'text', 3);
                ?>  
              </td>
            </tr> 
            <tr>   
              <td colspan="2" align="center">
                <input type="button" name="pesquisar" id="pesquisar" value="Pesquisar"
                  onclick="js_pesquisa_declaracoes(z01_numcgm.value, '<?=$oGet->origem?>')"/>
              </td>
            </tr>
	        <?    
	          } elseif (isset($oGet->origem) and $oGet->origem == 'inscr') {
	        ?>
            <tr>  
              <td title="<?=$Tq02_inscr?>" width="25%">     
                <?
                  db_ancora($Lq02_inscr,' js_mostrainscr(true); ',1);
                ?>
              </td>
              <td> 
                <?
                  db_input('q02_inscr', 10, $Iq02_inscr, true, 'text', 1, 'onchange="js_mostrainscr(false)"');
                  db_input('z01_nome' , 30, $Iz01_nome , true, 'text', 3);
                ?>
              </td>
            </tr>
            <tr>  
              <td colspan="2" align="center">
                <input type="button" name="pesquisar" id="pesquisar" value="Pesquisar"
                  onclick="js_pesquisa_declaracoes(q02_inscr.value, 'inscr')" />
              </td>
            </tr>
	        <? } ?>
        </table>
      </fieldset>

      <fieldset style="width: 550px; margin: 10px auto;">
        <legend>
          <strong>Lista Declarações</strong>
        </legend>
  
        <div id="grid">
        </div>
      </fieldset>

      <fieldset style="width: 550px; margin: 10px auto;">
        <legend>
          <strong>Observação</strong>
        </legend>
        
	      <? 
	        db_textarea('x48_motivo', 10, 75, $Ix48_motivo, true, 'text', 1 );
	      ?>

      </fieldset>
   
      <table align="center" width="500">
        <tr>
          <td colspan="3" align="center">
            <input type="button" name="anular" value="Anular" onclick="js_imprimir_declaracao()" />
          </td>
        </tr>
      </table>
  
      <?
        db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
                db_getsession("DB_anousu"),db_getsession("DB_instit"));
      ?>
    </form>

    <script>
      js_init_table();

      function js_imprimir_declaracao() {
        var totalLinhas          = oDataGrid.getNumRows();
        var contador             = 0;
        var checkBoxSelecionados = document.getElementsByName('checkBoxDeclaracao');
        var sObservacao          = document.getElementById('x48_motivo').value;

        if ((totalLinhas > 0) && (sObservacao.length != '')) {
          for(var i = 0; i < checkBoxSelecionados.length; i++) {
            if (checkBoxSelecionados[i].checked == true) { 
              contador++;
            }
          }
          if (contador == 0) {
            alert("Nenhuma declaração de quitação selecionada!");
          }   
        } else {
          alert("Preencha os campos corretamente!");  
        }

        if ((contador > 0) && (sObservacao != '')) {
          if(confirm('Deseja anular declaração(ões)?')) {
            	  
            js_divCarregando('Aguarde, anulando declaração(ões).', 'msgbox'); 

            for(var i = 0; i < checkBoxSelecionados.length; i++) {
              if (checkBoxSelecionados[i].checked == true) { 
                oParam             = new Object();
                oParam.exec        = 'anulaDeclaracao';
                oParam.declaracao = checkBoxSelecionados[i].value;
                oParam.observacao = sObservacao;
                obj = new Ajax.Request(
                                       'arr4_declquitacao.RPC.php', 
                                       {
                                        method: 'POST',
                                        asynchronous: false,
                                        parameters: 'json='+Object.toJSON(oParam),
                                        onSuccess : js_retornoAnulaDeclaracao
                                       }
                                      );
              }
            }  
            js_removeObj('msgbox');
            location.reload();
          }   
        }      
        
      }

      function js_retornoAnulaDeclaracao(oAjax) {
    	  var oRetorno = eval('('+oAjax.responseText+')');
    	  if (oRetorno.status == '0') {
          alert(oRetorno.message);
        } 
      }

      function js_ancora_origem() {
    	  with (document.form1) {
    		  method: 'GET';
    	    action: 'arr4_declquitacaoanulacao001.php';
    	    submit();
    	  }
    	}

      function js_pesquisa_declaracoes(codigo, origem) {
    	  var iCodigo   = codigo;
    	  var sOrigem   = origem;
    	  var oParam    = new Object();

    	  oParam.exec   = 'listaDeclaracoes';
    	  oParam.origem = sOrigem;
    	  oParam.codigo = iCodigo;

    	  js_divCarregando('Pesquisando, aguarde.', 'msgbox');

    	  var oAjax = new Ajax.Request('arr4_declquitacao.RPC.php',
                                     { method    : 'POST',
    		                               parameters: 'json='+Object.toJSON(oParam), 
                                       onComplete: js_retorna_declaracoes
                                     }
                                    );
      }

      function js_retorna_declaracoes(oAjax) {
    	  js_removeObj('msgbox');
    	  var oRetorno = eval("("+oAjax.responseText+")");

    	  if (oRetorno.status == 1) {
    		  oDataGrid.clearAll(true);
    		  for (var i = 0; i < oRetorno.aDeclaracoes.length; i++) {
    			  with (oRetorno.aDeclaracoes[i]) {
    				  var aColGrid = new Array();
             
              if(ar30_situacao == 'Anulada') {
            	  aColGrid[0]  = "";
              } else {
            	  aColGrid[0]  = "<input type='checkbox' style='margin:0;' name='checkBoxDeclaracao' value ='"+ ar30_sequencial +"'>";
              }

    				  
              aColGrid[1]  = "<a href=\"#\" onclick=\"js_detalhes_declaracao('" + ar30_sequencial + "', '" +
                                                                                  oRetorno.sOrigem + "')\">" + 
                                                                                  ar30_sequencial + "</a>";
              aColGrid[2]  = ar30_origem;
              aColGrid[3]  = ar30_exercicio;
              aColGrid[4]  = '';
              aColGrid[5]  = ar30_situacao;
              oDataGrid.addRow(aColGrid);
				    }
    			}
		      oDataGrid.renderRows();
		    } else {
			    alert(oRetorno.message);
		    }
      }

      function js_detalhes_declaracao(codigo_declaracao, origem){
    	  var oParam            = new Object();
    	  oParam.exec           = 'detalhesDeclaracao';
    	  oParam.iCodDeclaracao = codigo_declaracao;
    	  oParam.sOrigem        = origem;

    	  js_divCarregando('Pesquisando, aguarde.', 'msgbox');

    	  var oAjax = new Ajax.Request('arr4_declquitacao.RPC.php',
    			                           { method    : 'POST',
	                                     parameters: 'json='+Object.toJSON(oParam), 
	                                     onComplete: js_retorna_detalhes
	                                   }
                                    );
      }

      function js_retorna_detalhes(oAjax) {
        js_removeObj('msgbox');
        var oRetorno = eval("("+oAjax.responseText+")");
	
        if (oRetorno.status == 1) {
          with (oRetorno) {
		        js_monta_janela(iExercicio, sNomeCgm, sNomeOrigem, iCodOrigem, iSituacao, dData, sUsuario, iCodDeclaracao);
		      }
		
		      if (oRetorno.aDebitos.length > 0) {
			      oDataGridDetalhes.clearAll(true);
			
			      for (var i = 0; i < oRetorno.aDebitos.length; i++) {
			    	  with (oRetorno.aDebitos[i]) {

			    		  var aRow = Array();
					      aRow[0]  = numpre;
					      aRow[1]  = parcela;
					      aRow[2]  = receita;
					      aRow[3]  = tipo;
					      aRow[4]  = valor;
					      aRow[5]  = situacao;
					      oDataGridDetalhes.addRow(aRow);
					    }
			      }
			      oDataGridDetalhes.renderRows();
			    }
		
        } else {
         alert(oRetorno.message);
        }
      }

      function js_init_table() {
	      oDataGrid = new DBGrid('gridExerc');
	      oDataGrid.nameInstance = 'oDataGrid';
	      oDataGrid.setCellAlign(new Array('center', 'center', 'center', 'center', 'center', 'center'));
	      oDataGrid.setCellWidth(new Array('5%', '20%', '20%', '10%', '30%', '20%'));
	      oDataGrid.setHeader(new Array('<input type="checkbox" style="margin:0;" name="seleciona" onclick="marca()" title="Inverter Selecionados">', 'Declaração', 'Origem', 'Ano', 'Observação', 'Situação'));
	      oDataGrid.setHeight('150');
	      oDataGrid.show($('grid'));	  
	    }

      function js_init_table_detalhes() { 
    	  oDataGridDetalhes = new DBGrid('gridDetalhes');
    	  oDataGridDetalhes.nameInstance = 'oDataGrid';
    	  oDataGridDetalhes.setCellAlign(new Array('center', 'center', 'center', 'center', 'center', 'center'));
    	  oDataGridDetalhes.setCellWidth(new Array('15%', '5%', '25%', '25%', '15%', '15%'));
    	  oDataGridDetalhes.setHeader(new Array('Numpre', 'Par', 'Receita', 'Tipo Débito', 'Valor', 'Situação'));
    	  oDataGridDetalhes.setHeight('150');
    	  oDataGridDetalhes.show($('gridDetalhes'));  
    	}

      function js_monta_janela(exercicio, nome, origem, codorigem, situacao, data, nome_usuario, declaracao) {

    	  var iExerc       = exercicio;
    	  var sNome        = nome;
    	  var sOrigem      = origem;
    	  var iCodOrigem   = codorigem;
    	  var iSituacao    = situacao;
    	  var sSituacao    = '';
    	  var dData        = data;
    	  var sNomeUsuario = nome_usuario;
    	  var iDeclaracao  = declaracao;
    	  var sContent = "";

    	  if (sOrigem == 'matric') 
    		  sOrigem = 'Matrícula';
    	  else if (sOrigem == 'cgm') 
    		  sOrigem = 'CGM';
    	  else if (sOrigem == 'somentecgm') 
    		  sOrigem = 'Somente CGM';
    	  else if (sOrigem == 'inscr')
          sOrigem = 'Inscrição';

    	  if (iSituacao == '1') 
    		  sSituacao = "Ativa";
    	  else if(iSituacao == '2') 
    		  sSituacao = "Anulada";
    	  else 
    		  sSituacao = "Anulada Automaticamente";
	 
    	  sContent += '<div style="margin: 10px auto; text-align: center;">';
  
    	  sContent += '<div id="msgtopo" style="margin:0 auto; width: 570px; font-size:13px; font-weight: bold; background-color: #FFF;">'; 
    	  sContent += 'Detalhes da Declaração de Quitação.';
    	  sContent += '</div>';
  
    	  sContent += '<div style="width:570px; margin:10px auto;">';
    	  sContent += '<fieldset>';
    	  sContent += '<table align="center">';
  
    	  sContent += '<tr><td><strong>Exercício:</strong></td>';
    	  sContent += '<td><input type="text" name="exercicio" id="exercicio" value="'+iExerc+'" readonly="readonly"></td>';
    	  sContent += '<td><strong>C&oacute;digo Declara&ccedil;&atilde;o</strong></td>';
    	  sContent += '<td><input type="text" name="iddeclaracao" id="iddeclaracao" value="'+iDeclaracao+'" readonly="readonly"></td></tr>';
  
    	  sContent += '<tr><td><strong>Nome:</strong></td>';
    	  sContent += '<td colspan="3"><input type="text" name="nome" id="nome" value="'+sNome+'"  size="57" readonly="readonly"></td></tr>';

    	  sContent += '<tr><td><strong>Tipo Origem:</strong></td>';
    	  sContent += '<td><input type="text" name="tipoorigem" id="tipoorigem" value="'+sOrigem+'" readonly="readonly"></td>';
    	  sContent += '<td><strong>Código Origem</strong></td>';
    	  sContent += '<td><input type="text" name="codorigem" id="codorigem" value="'+iCodOrigem+'" readonly="readonly"></td></tr>';

    	  sContent += '<tr><td><strong>Situação:</strong></td>';
    	  sContent += '<td><input type="text" name="situacao" id="situacao" value="'+sSituacao+'" readonly="readonly"></td>';
    	  sContent += '<td><strong>Data Emissão:</strong></td>';
    	  sContent += '<td><input type="text" name="dtemissao" id="dtemissao" value="'+dData+'" readonly="readonly"></td></tr>';

    	  sContent += '<tr><td><strong>Usuário</strong></td>';
    	  sContent += '<td colspan="3"><input type="text" name="usuario" id="usuario" value="'+sNomeUsuario+'" size="57" readonly="readonly"></td></tr>';
  
    	  sContent += '</table>';
    	  sContent += '</fieldset>';
  
    	  sContent += '<fieldset>';
    	  sContent += '<div id="gridDetalhes"></div>';
    	  sContent += '</fieldset>';
  
    	  sContent += '<div style="margin: 10px auto;">';

    	  sContent += '<input type="button" name="fechar" value="Fechar" onclick="js_fechar_janela()"/>';
    	  sContent += '</div>';
  
    	  sContent += '</div>';
    	  sContent += '</div>';
  
    	  windowExerc  = new windowAux('wndexerc', 'Lista de Exercícios', 590, 450);
    	  windowExerc.setContent(sContent);

    	  var w = ((screen.width - 590) / 2);
    	  var h = ((screen.height / 2) - 450);
  
    	  windowExerc.show(h, w);
    	  $('window'+windowExerc.idWindow+'_btnclose').observe("click",js_fechar_janela);

    	  js_init_table_detalhes();
    	}

      function js_fechar_janela(){
    	  windowExerc.destroy();
    	} 

      /**
      * Matriculas
      */
      function js_mostramatricula(mostra, nome_func){
    	  if (mostra == true) {
    		  if (nome_func != "func_iptubase.php") {
    			  js_OpenJanelaIframe('top.corpo',
    	    			                'db_iframe_matric',
    	    			                nome_func + '?funcao_js=parent.js_preenchematricula|0|1',
    	    			                'Pesquisa',
    	    			                true);
    			} else {
    				js_OpenJanelaIframe('top.corpo',
    	    				              'db_iframe_matric',
    	    				              nome_func + '?funcao_js=parent.js_preenchematricula3|0|1|2',
    	    				              'Pesquisa',
    	    				              true);  
    			}
    		}else {
    			js_OpenJanelaIframe('top.corpo',
    	    			              'db_iframe_matric',
    	    			              nome_func + '?pesquisa_chave=' + document.form1.j01_matric.value +
    	    			                '&funcao_js=parent.js_preenchematricula2',
    	    			              'Pesquisa',
    	    			              false);
    		}
    	}

      function js_preenchematricula3(chave,chave1,chave2){

    	  document.form1.j01_matric.value = chave;
    	  document.form1.z01_nome.value   = chave2;
    	  db_iframe_matric.hide();
    	}

      function js_preenchematricula(chave,chave1){
  
    	  document.form1.j01_matric.value = chave;
    	  document.form1.z01_nome.value   = chave1;
    	  db_iframe_matric.hide();
    	}

      function js_preenchematricula2(chave,chave1){
	  
    	  if(chave1 == false) {
    		  document.form1.z01_nome.value = chave;
    		  db_iframe_matric.hide();
    		}else {
    			document.form1.j01_matric.value = "";
    			document.form1.z01_nome.value   = chave;
    			db_iframe_matric.hide();
    		}

    	  if(document.form1.j01_matric.value == ''){
    		  document.form1.z01_nome.value   = '';
    		}  
    	}
      /**
      * fim matriculas
      */

      /**
      * Inicio cgm
      */
      function js_mostracgm(mostra) {
    	  if (mostra == true) {
    		  js_OpenJanelaIframe('top.corpo',
    	    		                'db_iframe_nomes',
    	    		                'func_nome.php?funcao_js=parent.js_preenchecgm|0|1',
    	    		                'Pesquisa',
    	    		                true);
    		} else {
    			js_OpenJanelaIframe('top.corpo',
    	    			              'db_iframe_nomes', 
    	    			              'func_nome.php?pesquisa_chave=' + document.form1.z01_numcgm.value +
    	    			                '&funcao_js=parent.js_preenchecgm1',
    	    			              'Pesquisa',
    	    			              false);
    		}
    	}

      function js_preenchecgm(chave,chave1){  
    	  document.form1.z01_numcgm.value = chave;
    	  document.form1.z01_nome.value   = chave1;
    	  db_iframe_nomes.hide();
    	}
  	
      function js_preenchecgm1(chave,chave1){
    	  document.form1.z01_nome.value = chave1;
    	  if (chave == true) {
    		  document.form1.z01_numcgm.focus();
    		  document.form1.z01_numcgm.value = "";
    		  document.form1.z01_nome.value = chave1;
    		}

    	  if (document.form1.z01_numcgm.value == '') {
    		  document.form1.z01_nome.value = '';
    		}
    	}
      /**
      * fim cgm
      */

      /**
      * Inicio Inscr
      */
      function js_mostrainscr(mostra) {
    	  if (mostra == true) {
    		  js_OpenJanelaIframe('top.corpo','db_iframe',
    	    		                'func_issbase.php?funcao_js=parent.js_preencheinscr|q02_inscr|z01_nome|q02_dtbaix',
    	    		                'Pesquisa',
    	    		                true);
    		} else {
    			js_OpenJanelaIframe('top.corpo',
    	    			              'db_iframe',
    	    			              'func_issbase.php?pesquisa_chave=' + document.form1.q02_inscr.value + 
    	    			                '&funcao_js=parent.js_preencheinscr',
    	    			              'Pesquisa',
    	    			              false);
    		}
      }

      function js_preencheinscr(chave1, chave2, baixa) {
    	  if (baixa != "") {
    		  document.form1.q02_inscr.value = "";
    		  document.form1.z01_nome.value  = "";
    		  db_iframe.hide();
    		  alert("Inscrição já  Baixada");
    	  } else {
    		  if (chave2 != false) {   
            document.form1.q02_inscr.value = chave1;
            document.form1.z01_nome.value  = chave2;
            db_iframe.hide();
          } else {
            document.form1.z01_nome.value  = chave1;
            db_iframe.hide();
          }
        }  
    	  if (document.form1.q02_inscr.value == '') {
    		  document.form1.z01_nome.value   = '';
    		}  
    	}


      function marca() {
    	  var checkbox = document.getElementsByName('checkBoxDeclaracao');

    	  for (var i = 0; i < checkbox.length; i++) {
    		  if (checkbox[i].disabled == false) {

    			  if (checkbox[i].checked == true) {
  						checkbox[i].checked = false;
  			 		} else {
  			      checkbox[i].checked = true;
  			    }
          }
	      }
    	}

    </script>
  </body>
</html>