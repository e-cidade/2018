<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

/**
 * 
 * @author I
 * @revision $Author: dbiuri $
 * @version $Revision: 1.1 $
 */
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_ppaestimativa_classe.php");
include("libs/db_liborcamento.php");
include("dbforms/db_classesgenericas.php");
$clppaestimativa = new cl_ppaestimativa();
$oPost           = db_utils::postMemory($_POST);
$oListaRecurso   = new cl_arquivo_auxiliar;
$clppaestimativa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o124_descricao");
$clrotulo->label("o124_sequencial");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js");
db_app::load("estilos.css");
db_app::load("prototype.js");
db_app::load("strings.js");
?>
<script language="JavaScript" type="text/javascript" src="scripts/ppaUserInterface.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <center>
  <form name='form1' method='post'>
  <table>
    <tr>
      <td>
        <fieldset>
          <legend>
            <b>Filtros</b>
          </legend>
          <table>
            <tr>
              <td>
                <?
                 db_ancora("<b>Perspectiva:</b>","js_pesquisao125_cronogramaperspectiva(true);",$db_opcao);
                ?>
              </td>
              <td> 
                <?
                db_input('o124_sequencial',10,$Io124_sequencial,true,'text',
                         $db_opcao," onchange='js_pesquisao125_cronogramaperspectiva(false);'");
                db_input('o124_descricao',40,$Io124_descricao,true,'text',3,'')
                ?>
              </td>
            <tr>
               <td>&nbsp;</td>
               <td>
                 <? db_selinstit('',300,100); 
                  db_input('filtra_despesa', 10,'',true, 'hidden', 3);
                 ?>
              </td>
            </tr>
           
          </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td>
        <fieldset>
          <legend>
            <b>Opções</b>
          </legend>
          <table >
             <tr>
               <td>
                 <b>Periodicidade:</b>
               </td>
               <td>
                 <?
                   db_select("periodicidade",array(1 => "mensal", 2 => "Bimestral"), true, 1);
                 ?>
               </td>
             </tr>
             <tr>
               <td>
                 <b>Forma de Emissão:</b>
               </td>
               <td>
                 <?
                   db_select("forma",array(1 => "Por Recurso", 2 => "Totalizaçao Geral"), true, 1);
                 ?>
               </td>
             </tr>
             <tr id='listarecursos' style='display:'>
              <td colspan=2><table>
               <?
                 // $aux = new cl_arquivo_auxiliar;
                 $oListaRecurso->cabecalho = "<strong>Recurso</strong>";
                 $oListaRecurso->codigo = "o15_codigo"; //chave de retorno da func
                 $oListaRecurso->descr  = "o15_descr";   //chave de retorno
                 $oListaRecurso->nomeobjeto = 'recursos';
                 $oListaRecurso->funcao_js = 'js_mostra';
                 $oListaRecurso->funcao_js_hide = 'js_mostra1';
                 $oListaRecurso->sql_exec  = "";
                 $oListaRecurso->func_arquivo = "func_orctiporec.php";  //func a executar
                 $oListaRecurso->nomeiframe = "db_iframe_orctiporec";
                 $oListaRecurso->localjan = "";
                 $oListaRecurso->onclick                     ="";
                 //$oListaRecurso->executa_script_apos_incluir ='js_verifica_orgao();';
                 $oListaRecurso->db_opcao = 2;
                 $oListaRecurso->tipo = 2;
                 $oListaRecurso->top = 0;
                 $oListaRecurso->linhas = 10;
                 $oListaRecurso->vwhidth = 400;
                 $oListaRecurso->funcao_gera_formulario();
               ?>
               </table>
              </td>
            </tr>           
          </table>
        </fieldset>
      </td>
    </tr>  
    <tr>
      <td colspan='2' align="center">
        <input name="imprime" type="button" id="imprime" value="Imprime"
               onclick='js_imprimeRelatorio()'>
      </td>
    </tr>
  </table>
  </form>
  </center>
</body>
</html>
<script>
function js_pesquisao125_cronogramaperspectiva(mostra) {

  if(mostra==true){
    js_OpenJanelaIframe('',
                        'db_iframe_cronogramaperspectiva',
                        'func_cronogramaperspectiva.php?funcao_js='+
                        'top.corpo.iframe_g1.js_mostracronogramaperspectiva1|o124_sequencial|o124_descricao|o124_ano',
                        'Perspectivas do Cronograma',true);
  }else{
     if(document.form1.o124_sequencial.value != ''){ 
        js_OpenJanelaIframe('',
                            'db_iframe_cronogramaperspectiva',
                            'func_cronogramaperspectiva.php?pesquisa_chave='+
                            document.form1.o124_sequencial.value+
                            '&funcao_js=top.corpo.iframe_g1.js_mostracronogramaperspectiva',
                            'Perspectivas do Cronograma',
                            false);
     }else{
     
       document.form1.o124_descricao.value = '';
       document.form1.ano.value             = ''
        
     }
  }
}

function js_mostracronogramaperspectiva(chave,erro, ano){
  document.form1.o124_descricao.value = chave; 
  if(erro==true) { 
    
    document.form1.o124_sequencial.focus(); 
    document.form1.o124_sequencial.value = '';
      
  }
}

function js_mostracronogramaperspectiva1(chave1,chave2,chave3) {

  document.form1.o124_sequencial.value = chave1;
  document.form1.o124_descricao.value  = chave2;
  db_iframe_cronogramaperspectiva.hide();
}
variavel = 0;
function js_imprimeRelatorio() {
    
    variavel++; 
    var sQuery  = "?iPerspectiva="+$F('o124_sequencial');
    /**
     * MArcamos todos os options do filtro por recurso
     */
    var aOptions = $('recursos').options;
    
    for (var i = 0;i < aOptions.length; i++) {
       aOptions[i].selected = true;
    }
    jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    document.form1.target = 'safo' + variavel;
    document.form1.action = "orc2_metasreceitascotasdespesa002.php";
    document.form1.submit();
      
  
}
</script>