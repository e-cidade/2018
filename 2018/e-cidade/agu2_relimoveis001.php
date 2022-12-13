<?php
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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
require("libs/db_app.utils.php");

$clArqAuxiliar = new cl_arquivo_auxiliar();
      
$clArqAuxiliar->db_opcao       = 2;
$clArqAuxiliar->tipo           = 2;
$clArqAuxiliar->top            = 0;
$clArqAuxiliar->linhas         = 4;
$clArqAuxiliar->vwidth         = 350;
$clArqAuxiliar->Labelancora    = 'Código';



  require_once ("libs/db_utils.php");
  require("classes/db_caracter_classe.php");

  $comboBairros = new cl_arquivo_auxiliar();
  $clcaracter   = new cl_caracter();

?>
<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js');
      db_app::load('estilos.css, grid.style.css');
    ?>
    <script type="text/javascript">
      
      function js_emite_relatorio() {
        
        var sQueryString = '';
        var oZonaFiscal  = document.form1.zona_fiscal;
        var oZonaEntrega = document.form1.zona_entrega;
        var oLogradouro  = document.form1.logradouro;
        var oBairro      = document.form1.bairro;
        var iAgua        = document.form1.caracterAgua.value;
        var iEsgoto      = document.form1.caracterEsgoto.value;

        var sZonaFiscal  = '';
        var sZonaEntrega = '';
        var sLogradouro  = '';
        var sBairro      = ''; 
        var sVirgula    = '';
        
        if (oZonaFiscal.length > 0) {
        
          for (var i = 0; i < oZonaFiscal.length; i++) {
          
            sZonaFiscal  += sVirgula+oZonaFiscal.options[i].value;
            sVirgula      = ',';
          }
        }

        sVirgula     = '';	

        if (oZonaEntrega.length > 0) {
          
          for (var i = 0; i < oZonaEntrega.length; i++) {
          
            sZonaEntrega += sVirgula+oZonaEntrega.options[i].value;
            sVirgula      = ',';   
          }
        }	

        sVirgula    = '';  

        if (oLogradouro.length > 0) {
    	    
          for (var i = 0; i < oLogradouro.length; i++) {
            
            sLogradouro  += sVirgula+oLogradouro.options[i].value;
            sVirgula      = ',';  	           
          }
        }  

        sVirgula = '';
        
        if (oBairro.length > 0) {
    	  
          for (var i = 0; i < oBairro.length; i++) {
            
            sBairro  += sVirgula+oBairro.options[i].value;
            sVirgula  = ',';
          }        
        }

        sQueryString  = '?zonafiscal='  + sZonaFiscal;
        sQueryString += '&zonaentrega=' + sZonaEntrega;
        sQueryString += '&logradouro='  + sLogradouro; 
        sQueryString += '&bairro='      + sBairro;
        sQueryString += '&agua='        + iAgua;
        sQueryString += '&esgoto='      + iEsgoto ;
        
        window.open('agu2_relimoveis002.php' + sQueryString, '',
                    'width='+(screen.availWidth-5)+', height='+(screen.availHeight-40)+', scrollbars=1, location=0 '); 
      
      }

    </script>
  </head>
  <body bgcolor=#CCCCCC onload="js_escondeFieldset();" >
    <fieldset style="width: 300px; margin: 50px auto;">
      <legend>
        <strong> Relatório de Bairros por Caracteristica(s) </strong>
      </legend>
  
      <form name="form1" method="POST" action="" onsubmit="return js_importar_dados()" enctype="multipart/form-data">
        <? 
          db_menu(db_getsession("DB_id_usuario"),
                  db_getsession("DB_modulo"),
                  db_getsession("DB_anousu"),
                  db_getsession("DB_instit"));
        ?>
        
        <table align="center" width="700" style="margin: 10px auto;">
          <tr>
            <td>
              <?
                $clArqAuxiliar->cabecalho      = '<strong>Zona Fiscal</strong>';
                $clArqAuxiliar->codigo         = 'j50_zona'; 
                $clArqAuxiliar->descr          = 'j50_descr';
                $clArqAuxiliar->nomeobjeto     = 'zona_fiscal';
                $clArqAuxiliar->funcao_js      = 'js_mostra_zona';
                $clArqAuxiliar->funcao_js_hide = 'js_mostra_zona1';
                $clArqAuxiliar->func_arquivo   = 'func_zonas.php'; 
                $clArqAuxiliar->nomeiframe     = 'db_iframe_zonas';
                $clArqAuxiliar->nome_botao     = 'db_lanca_zonas';
                $clArqAuxiliar->funcao_gera_formulario();
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <? 
                $clArqAuxiliar->cabecalho      = '<strong>Zona de Entrega</strong>';
                $clArqAuxiliar->codigo         = 'j85_codigo'; //chave de retorno da func
                $clArqAuxiliar->descr          = 'j85_descr';   //chave de retorno
                $clArqAuxiliar->nomeobjeto     = 'zona_entrega';
                $clArqAuxiliar->funcao_js      = 'js_mostra_zona_ent';
                $clArqAuxiliar->funcao_js_hide = 'js_mostra_zona_ent1';
                $clArqAuxiliar->func_arquivo   = 'func_iptucadzonaentrega.php';  //func a executar
                $clArqAuxiliar->nomeiframe     = 'db_iframe_zona_ent';
                $clArqAuxiliar->nome_botao     = 'db_lanca_zona_ent';
                $clArqAuxiliar->funcao_gera_formulario();
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?
                $clArqAuxiliar->cabecalho      = '<strong>Logradouros</strong>';
                $clArqAuxiliar->codigo         = 'j14_codigo'; //chave de retorno da func
                $clArqAuxiliar->descr          = 'j14_nome';   //chave de retorno
                $clArqAuxiliar->nomeobjeto     = 'logradouro';
                $clArqAuxiliar->funcao_js      = 'js_mostra_logradouro';
                $clArqAuxiliar->funcao_js_hide = 'js_mostra_logradouro1';
                $clArqAuxiliar->func_arquivo   = 'func_ruas.php';  //func a executar
                $clArqAuxiliar->nomeiframe     = 'db_iframe_ruas';
                $clArqAuxiliar->nome_botao     = 'db_lanca_logradouro';
                $clArqAuxiliar->funcao_gera_formulario();
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?
                $clArqAuxiliar->cabecalho      = '<strong>Bairros</strong>';
                $clArqAuxiliar->codigo         = 'j13_codi'; //chave de retorno da func
                $clArqAuxiliar->descr          = 'j13_descr';   //chave de retorno
                $clArqAuxiliar->nomeobjeto     = 'bairro';
                $clArqAuxiliar->funcao_js      = 'js_mostra_bairro';
                $clArqAuxiliar->funcao_js_hide = 'js_mostra_bairro1';
                $clArqAuxiliar->func_arquivo   = 'func_bairro.php';  //func a executar
                $clArqAuxiliar->nomeiframe     = 'db_iframe_bairro';
                $clArqAuxiliar->nome_botao     = 'db_lanca_bairro';
                $clArqAuxiliar->funcao_gera_formulario();
              ?>
            </td>
          </tr>
        </table>
        
        <fieldset style="width: 435px; margin: 0 auto 0 auto;">
          <legend>
            <strong>Características</strong>
          </legend>
          <table align="center" width="350">
            <tr>
              <td>
                <strong>Água</strong>
              </td>
              <td>
                <?
                  $rscaracter = $clcaracter->sql_record($clcaracter->sql_query_file(null, "*", "j31_descr", "j31_grupo = 83"));
                ?>
                <select name="caracterAgua" style="width: 300px;">
                  <option value="">Todas</option>
                  <?
                    for($i = 0; $i < $clcaracter->numrows; $i++) {
                     
                      $oCaracter = db_utils::fieldsMemory($rscaracter, $i);
                      echo "<option value=\"$oCaracter->j31_codigo\">$oCaracter->j31_codigo - $oCaracter->j31_descr</option>";
                    }
                  ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <strong>Esgoto</strong>
              </td>
              <td>
                <?
                  $rscaracter = $clcaracter->sql_record($clcaracter->sql_query_file(null, "*", "j31_descr", "j31_grupo = 82"));
                ?>
                <select name="caracterEsgoto" style="width: 300px;">
                  <option value="">Todas</option>
                  <?
                    for($i = 0; $i < $clcaracter->numrows; $i++) {
                    
                      $oCaracter = db_utils::fieldsMemory($rscaracter, $i);
                      echo "<option value=\"$oCaracter->j31_codigo\">$oCaracter->j31_codigo - $oCaracter->j31_descr</option>";
                    } 
                  ?>
                </select>
              </td>
            </tr>
          </table>
        </fieldset>  
        <table style="width: 435px; margin: 20px auto 0 auto;">
          <tr>
            <td>
              <center>
                <input type="button" value="Gerar Relatorio" onclick="js_emite_relatorio()" />
              </center>
            </td>
          </tr>
        </table>
      </form>
    </fieldset>
  </body>
</html>