<?
  /*
   *     E-cidade Software Publico para Gestao Municipal                
   *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
  include("dbforms/db_classesgenericas.php");
  
  db_postmemory($HTTP_POST_VARS);
  
  $arqAux = new cl_arquivo_auxiliar;
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript"
      src="scripts/scripts.js"></script>
    
    <script>
    </script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
    <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
      <tr>
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
      </tr>
    </table>
    <br>
    <br>
    <form name="form1" method="post" action="">
      <table align="center">
        <tr> 
          <td colspan=2  align="center">
            <strong>Opções:</strong>
            <select name="filtros">
              <option value = ""   >Com os filtros selecionados</option>
              <option value = "not">Sem os filtros selecionados</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <table>
              <tr>
                <td>
                  <?php
                    $arqAux->cabecalho      = '<strong>Logradouros</strong>';
                    $arqAux->codigo         = 'j14_codigo'; //chave de retorno da func
                    $arqAux->descr          = 'j14_nome';   //chave de retorno
                    $arqAux->nomeobjeto     = 'logradouro';
                    $arqAux->funcao_js      = 'js_mostra_logradouro';
                    $arqAux->funcao_js_hide = 'js_mostra_logradouro1';
                    $arqAux->func_arquivo   = 'func_ruas.php';  //func a executar
                    $arqAux->nomeiframe     = 'db_iframe_ruas';
                    $arqAux->nome_botao     = 'db_lanca_logradouro';
                    $arqAux->db_opcao       = 2;
                    $arqAux->tipo           = 2;
                    $arqAux->top            = 0;
                    $arqAux->linhas         = 4;
                    $arqAux->vwidth        = 450;
                    $arqAux->funcao_gera_formulario();
                  ?>
                </td>
              </tr>
            </table>
          </td>
          <td>
            <table>
              <tr>
                <td>
                  <?php
                    $arqAux->cabecalho      = '<strong>Bairros</strong>';
                    $arqAux->codigo         = 'j13_codi'; //chave de retorno da func
                    $arqAux->descr          = 'j13_descr';   //chave de retorno
                    $arqAux->nomeobjeto     = 'bairro';
                    $arqAux->funcao_js      = 'js_mostra_bairro';
                    $arqAux->funcao_js_hide = 'js_mostra_bairro1';
                    $arqAux->func_arquivo   = 'func_bairro.php';  //func a executar
                    $arqAux->nomeiframe     = 'db_iframe_bairro';
                    $arqAux->nome_botao     = 'db_lanca_bairro';
                    $arqAux->db_opcao       = 2;
                    $arqAux->tipo           = 2;
                    $arqAux->top            = 0;
                    $arqAux->linhas         = 4;
                    $arqAux->vwidth       = 450;
                    $arqAux->funcao_gera_formulario();
                  ?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <table>
              <tr>
                <td>
                  <?php
                    $arqAux->cabecalho      = '<strong>Zona Fiscal</strong>';
                    $arqAux->codigo         = 'j50_zona'; //chave de retorno da func
                    $arqAux->descr          = 'j50_descr';   //chave de retorno
                    $arqAux->nomeobjeto     = 'zona_fiscal';
                    $arqAux->funcao_js      = 'js_mostra_zona';
                    $arqAux->funcao_js_hide = 'js_mostra_zona1';
                    $arqAux->func_arquivo   = 'func_zonas.php';  //func a executar
                    $arqAux->nomeiframe     = 'db_iframe_zonas';
                    $arqAux->nome_botao     = 'db_lanca_zonas';
                    $arqAux->db_opcao       = 2;
                    $arqAux->tipo           = 2;
                    $arqAux->top            = 0;
                    $arqAux->linhas         = 4;
                    $arqAux->vwidth        = 450;
                    $arqAux->tamanho_campo_descricao = 32;
                    $arqAux->funcao_gera_formulario();
                  ?>    
                </td>
              </tr>
            </table>
          </td>
          <td>
            <table>
              <tr>
                <td>
                  <?php
                    $arqAux->cabecalho      = '<strong>Zona de Entrega</strong>';
                    $arqAux->codigo         = 'j85_codigo'; //chave de retorno da func
                    $arqAux->descr          = 'j85_descr';   //chave de retorno
                    $arqAux->nomeobjeto     = 'zona_entrega';
                    $arqAux->funcao_js      = 'js_mostra_zona_ent';
                    $arqAux->funcao_js_hide = 'js_mostra_zona_ent1';
                    $arqAux->func_arquivo   = 'func_iptucadzonaentrega.php';  //func a executar
                    $arqAux->nomeiframe     = 'db_iframe_zona_ent';
                    $arqAux->nome_botao     = 'db_lanca_zona_ent';
                    $arqAux->db_opcao       = 2;
                    $arqAux->tipo           = 2;
                    $arqAux->top            = 0;
                    $arqAux->linhas         = 4;
                    $arqAux->vwidth        = 450;
                    $arqAux->tamanho_campo_descricao = 18;
                    $arqAux->funcao_gera_formulario();
                  ?>    
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <table align="center">
              <tr>
                <td align="left"><b>Periodo da Instalação: </b></td>
                <td>
                  <?php db_inputdata('dataInicial', '', '', '', true, 'text', 1); ?>
                  até
                  <?php db_inputdata('dataFinal', '', '', '', true, 'text', 1); ?>  
                </td>
              </tr>
              <tr> 
                <td align="right"><b>Opções:</b></td>
                <td>
                  <select name="opcao">
                    <option name="" value="analitico">Analítico</option>
                    <option name="" value="sintetico">Sintético</option>
                  </select>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <br>
            <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_mandadados();">
          </td>
        </tr>
      </table>
    </form>
  </body>
  <?php
    db_menu(db_getsession("DB_id_usuario"), 
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"), 
            db_getsession("DB_instit"));
  ?>
</html>

<script>
function js_mandadados() {

  var vir              = "";
  var listalog         = "";
  var listabairro      = "";
  var listazona        = "";
  var listazonaentrega = "";
  var queryString      = "";
  var comsem           = document.form1.filtros.value;
  var opcao            = document.form1.opcao.value;

  if (comsem != '') {
    
    if(queryString != "") queryString = queryString + "&";

    queryString += "comsem=" + comsem;
  }

  if (opcao != '') {

    if (queryString != "") queryString = queryString + "&";
      queryString += "opcao=" + opcao;    
  }
  
  if ((document.form1.dataInicial.value != '') && (document.form1.dataFinal.value != '')) {

    if (queryString != "") queryString = queryString + "&";
    
    var dataInicial      = document.form1.dataInicial_ano.value + '-' +
                           document.form1.dataInicial_mes.value + '-' +
                           document.form1.dataInicial_dia.value;
      
    var dataFinal        = document.form1.dataFinal_ano.value + '-' + 
                           document.form1.dataFinal_mes.value + '-' + 
                           document.form1.dataFinal_dia.value;
      
    queryString += "datainicial=" + dataInicial + "&datafinal=" + dataFinal;
    
  } else if ((document.form1.dataInicial.value != '') || (document.form1.dataFinal.value != '')) {
    
    alert('Informe o período inicial e final da instalação dos hidrômetros.');
    return false;
  }
  
  if (document.form1.logradouro.length > 0) {
    
    if (queryString != "") queryString = queryString + "&";
    
      for (x = 0; x < document.form1.logradouro.length; x++) {

        listalog += vir + document.form1.logradouro.options[x].value;
        vir       = ",";
      }

      queryString  += "listalog="+listalog;
    } 

    vir = "";

    if (document.form1.bairro.length > 0) {

      if (queryString != "") queryString = queryString + "&";
      
      for (x = 0; x < document.form1.bairro.length; x++) {

        listabairro += vir + document.form1.bairro.options[x].value;
        vir          = ",";
      }

      queryString += "listabairro="+listabairro;
    }

    vir = "";

    if (document.form1.zona_fiscal.length > 0) {

      if (queryString != "") queryString = queryString + "&";
     
      for (x = 0; x < document.form1.zona_fiscal.length; x++) {
         
        listazona += vir + document.form1.zona_fiscal.options[x].value;
        vir        = ",";
      }

      queryString +="listazona="+listazona;
    }

    vir = "";

    if (document.form1.zona_entrega.length > 0) {

      if (queryString != "") queryString = queryString + "&";
    
      for (x = 0; x < document.form1.zona_entrega.length; x++) {
           
        listazonaentrega += vir + document.form1.zona_entrega.options[x].value;
        vir               = ",";
      }

      queryString += "listazonaentrega=" + listazonaentrega;
    }
   
    jan = window.open('agu2_imovlograd002.php?' + queryString, '', 'width=' + (screen.availWidth - 5) +
                      ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
    jan.moveTo(0, 0);
  }
</script>