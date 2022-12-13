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

  //MODULO: agua
  require_once("dbforms/db_classesgenericas.php");
  require_once("dbforms/db_funcoes.php");
  require_once("dbforms/db_classesgenericas.php");
  require_once("libs/db_utils.php");
  require_once ("libs/db_app.utils.php");
  
  db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js');
  db_app::load('estilos.css, grid.style.css');

  $cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
  $claguacondominiomat->rotulo->label();
  $clrotulo = new rotulocampo;
  
  $clrotulo->label("x01_numcgm");
  $clrotulo->label("z01_nome");
  $clrotulo->label("x31_matric");

  if (isset($db_opcaoal)) {
    
    $db_opcao = 33;
    $db_botao = false;
  } else if(isset($opcao) && $opcao == "alterar") {
    
    $db_botao = true;
    $db_opcao = 2;
  } else if (isset($opcao) && $opcao == "excluir") {
    
    $db_opcao = 3;
    $db_botao = true;
  } else {
    
    $db_opcao = 1;
    $db_botao = true;
    
    if (isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro == false )) {
     
      $x40_matric = "";
      $z01_nome   = "";
    }
  }
?>

<fieldset style="margin-top: 20px;">
<legend><b>Cadastro de Codominios - Matriculas</b></legend>
  <form name="form1" method="post" action="">
    <center>
      <table border="0">
        <tr>
          <td nowrap title="<?=@$Tx40_codcondominio?>">
            <?
              db_ancora(@$Lx40_codcondominio, "js_pesquisax40_codcondominio(true);", $db_opcao);
            ?>
          </td>
          <td>
            <?
              db_input('x40_codcondominio', 10, $Ix40_codcondominio, true, 'text',
                 3, " onchange='js_pesquisax40_codcondominio(false);'");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tx40_matric?>">
            <?
              db_ancora(@$Lx40_matric, "js_pesquisax40_matric(true);", $db_opcao);
            ?>
          </td>
          <td> 
            <?
              db_input('x40_matric', 10, $Ix40_matric, true, 'text', $db_opcao, " onchange='js_pesquisax40_matric(false);'");
              //db_input('x01_numcgm',10,$Ix01_numcgm,true,'text',3,'')
              db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
              type="submit" id="db_opcao"
              value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
              <?=($db_botao == false ? "disabled" : "")?>>
            <input name="novo" type="button" id="cancelar" value="Novo"
              onclick="js_cancelar();" <?=($db_opcao == 1 || isset($db_opcaoal) ? "style='visibility:hidden;'" : "")?> >
          </td>
        </tr>
      </table>
      <table>
        <tr id="grid" style="display: none;">
          <td align="center" width= "600px">
            <div id="oGrid"></div>
          <td>
        </tr>
      </table>
    </center>
  </form>
</fieldset>

<script>

  function js_cancelar() {
    
    var opcao = document.createElement("input");
    
    opcao.setAttribute("type" , "hidden");
    opcao.setAttribute("name" , "novo");
    opcao.setAttribute("value", "true");
    document.form1.appendChild(opcao);
    document.form1.submit();
  }
  
  
  function js_pesquisax40_matric(mostra) {
    
    if (mostra == true) {
      
      js_OpenJanelaIframe('top.corpo.iframe_aguacondominiomat', 'db_iframe_aguabase', 
        'func_aguabase.php?funcao_js=parent.js_mostraaguabase1|x01_matric|z01_nome', 'Pesquisa', true);
    } else {
      
      if (document.form1.x40_matric.value != '') {
        
        js_OpenJanelaIframe('top.corpo.iframe_aguacondominiomat', 'db_iframe_aguabase', 
          'func_aguabase.php?pesquisa_chave='+document.form1.x40_matric.value+'&funcao_js=parent.js_mostraaguabase', 'Pesquisa', false);
      } else {
        
        document.form1.x01_numcgm.value = '';
      }
    }
  }
  
  
  function js_mostraaguabase(chave, erro) {
    
    document.form1.z01_nome.value = chave;
    
    if (erro == true) { 
      
      document.form1.x40_matric.focus();
      document.form1.x40_matric.value = '';
    }
  }
  
  
  function js_mostraaguabase1(chave1, chave2) {
    
    document.form1.x40_matric.value = chave1;
    document.form1.z01_nome.value   = chave2;
    db_iframe_aguabase.hide();
  }
  
  
  function js_pesquisax40_codcondominio(mostra) {
    
    if (mostra == true) {
      
      js_OpenJanelaIframe('top.corpo.iframe_aguacondominiomat', 'db_iframe_aguacondominio', 
        'func_aguacondominio.php?funcao_js=parent.js_mostraaguacondominio1|x31_codcondominio|x31_matric',
        'Pesquisa', true);
    } else {
      
      if (document.form1.x40_codcondominio.value != '') {
        
        js_OpenJanelaIframe('top.corpo.iframe_aguacondominiomat','db_iframe_aguacondominio',
          'func_aguacondominio.php?pesquisa_chave='+document.form1.x40_codcondominio.value+'&funcao_js=parent.js_mostraaguacondominio',
          'Pesquisa', false);
      } else {
        
        document.form1.x31_matric.value = '';
      }
    }
  }
  
  
  function js_mostraaguacondominio(chave, erro) {
    
    document.form1.x31_matric.value = chave;
    
    if (erro == true) {
      
      document.form1.x40_codcondominio.focus();
      document.form1.x40_codcondominio.value = '';
    }
  }
  
  
  function js_mostraaguacondominio1(chave1, chave2) {
    
    document.form1.x40_codcondominio.value = chave1;
    document.form1.x31_matric.value        = chave2;
    db_iframe_aguacondominio.hide();
  }
  
  
  var sUrlRPC = 'agu1_aguacondominio.RPC.php';
  js_init_table();
  js_pesquisaDebitos();
  

  function js_init_table() {
    
    oGrid              = new DBGrid('oGrid');
    oGrid.nameInstance = 'oGrid';
    
    var sMsg = null;

    oGrid.setHeight(150);
    oGrid.hasCheckbox = 0;
    oGrid.allowSelectColumns(false);
    oGrid.setCellAlign(new Array('center', 
                                 'center', 
                                 'left',
                                 'center'));
    
    oGrid.setCellWidth(new Array('20%',
                                 '15%',
                                 '60%',
                                 '10%'));
      
    oGrid.setHeader(new Array('Código do Condomínio',
                              'Matricula'           ,
                              'Proprietário'        ,
                              'opções'));
  
    
    oGrid.show($('oGrid'));
    
  }


  function js_pesquisaDebitos() {
    
    var oParam            = new Object();
    oParam.iCodCondominio = $F('x40_codcondominio');
    oParam.sExec          = 'getMatriculasCondominio';
    
    js_divCarregando('Pesquisando registros, aguarde.', 'msgbox');
    
    var oAjax = new Ajax.Request(sUrlRPC,
                                { 
                                 method    : 'POST',
                                 parameters: 'json=' + Object.toJSON(oParam), 
                                 onComplete: js_retorna
                                });
  }
  
  function js_retorna(oAjax) {
    
    js_removeObj('msgbox');
    
    var oRetorno  = eval("(" + oAjax.responseText + ")");
    
    oGrid.clearAll(true);
    
    if (oRetorno.status == 1) {
      
      $('grid').style.display = '';

      for (var i = 0; i < oRetorno.aMatriculas.length; i++) {
    	  
        with (oRetorno.aMatriculas[i]) {
         
          aLinha     = new Array();
          aLinha[0]  = x40_codcondominio;
          aLinha[1]  = x40_matric;
          aLinha[2]  = x01_numcgm.urlDecode();
          aLinha[3]  = "<input type='button' value='E' onclick='js_excluir(" + x40_codcondominio + "," + x40_matric +")'>";
          
          
        }
        oGrid.addRow(aLinha);
        
      }
      
      oGrid.renderRows();
      
    } else {
      
      alert('Nenhum registro encontrado.');
    }
  }

  
  function js_excluir(iCodCondominio, iMatricula) {
    
    if (!confirm('Confirma a Exclusao da Matricula?')) {
      return false;
    } 
    js_divCarregando('Aguarde, Excluindo Matricula do Condominio','msgBox');
    
    var oParam             = new Object();
    oParam.sExec           = 'excluirMatriculaCondominio';
    oParam.iCodCondominio  = iCodCondominio;
    oParam.iMatricula      = iMatricula;
    
    var oAjax = new Ajax.Request(sUrlRPC,
                                 {method     : 'post',
                                  parameters :'json='+Object.toJSON(oParam),
                                  onComplete : js_retorno
                                 }
                                )
  }
  
  
  function js_retorno(oAjax) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    if (oRetorno.status == 1) {
    
      js_init_table();
      js_pesquisaDebitos();
    } else {
      alert(oRetorno.message.urlDecode());
    }
  }
</script>