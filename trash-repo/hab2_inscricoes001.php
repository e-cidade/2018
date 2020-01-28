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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
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
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
?>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<center>
<form name="form1" method="post" action="">
  <fieldset style="margin-top:50px; width: 500px;">
    <legend><strong>Relatório de Inscrições </strong></legend>
    <table  align="center" width="100%" cellpadding="3" border="0">
      <tr>
         <td ><b>Período de Inscrição :</b></td>
         <td >
          <?
            db_inputdata('ht15_datalancamentoA','','','',true,'text',1,"");
            echo " à "; 
            db_inputdata('ht15_datalancamentoB','','','',true,'text',1,"");
          ?>
         </td>
      </tr>
      <tr>
         <td>
         <b> 
         <?
              db_ancora('Inscrição',"js_pesquisaInscricao(true);",1);
           // db_selectrecord("ht15_habitsituacaoinscricao","ht13_sequencial, ht13_descricao",true,1);
          ?> 
          </b>
         </td>
         <td> 
					<?
					   db_input('ht15_sequencial',6,false,'','text',3," onchange='js_pesquisaInscricao(false);'");
	           db_input('nome_inscricao',30,'text',3,'');
	       ?>          
         </td>
      </tr>
      <tr>
         <td ><b>Ordem :</b></td>
         <td >
          <?
             $aOrdem = array( 'habitinscricao.ht15_datalancamento' => 'Data de Lançamento da Inscrição',
                              'cgm.z01_nome'                       => 'Nome do Candidato',
                              'habitprograma.ht01_sequencial'      => 'Programa',
                              'habitinscricao.ht15_sequencial'     => 'Inscrição'   
                             );
             db_select('filtro_ordem',$aOrdem,true,1,'');            
          ?>
         </td>
      </tr>
      <tr>
         <td ><b>Quebra :</b></td>
         <td >
          <?
             $aQuebra = array( '1' => 'Nenhum',
                               '2' => 'Data de Lançamento da Inscrição',
                               '3' => 'Nome do candidato',
                               '4' => 'Programa'
                             );
             db_select('filtro_quebra',$aQuebra,true,1,'');            
          ?>         
         </td>
      </tr>  
      
      <tr>
         <td colspan="2" align="center">
         
              <?
                 $clProgramas                                = new cl_arquivo_auxiliar();
                 $clProgramas->codigo                        = "ht01_sequencial";
                 $clProgramas->descr                         = "ht01_descricao";
                 $clProgramas->nomeobjeto                    = "lista_programas";
                 $clProgramas->funcao_js                     = 'js_mostra_programa';
                 $clProgramas->funcao_js_hide                = 'js_mostra_programa1';
                 $clProgramas->cabecalho                     = "<b>Lista de Programas</b>";
                 $clProgramas->tipo                          = 2;   
                 $clProgramas->Labelancora                   = "Programas";
                 $clProgramas->func_arquivo                  = "func_habitprograma.php";
                 $clProgramas->passar_query_string_para_func = "&sListaInteresseCandidato='+js_getlist_candidatos()+'"; //filtro se os candidatos foram selecionados
                 $clProgramas->nomeiframe                    = "iframe_programas";
                 $clProgramas->vwidth                        = "500";
                 $clProgramas->nome_botao                    = "cl_lancaPrograma";
                 $clProgramas->linhas                        = 5;
                 $clProgramas->funcao_gera_formulario();
            ?>
         </td>
      </tr>        
          
      <tr>
         <td colspan="2">
          <?
                 $clCandidatos                                = new cl_arquivo_auxiliar();
                 $clCandidatos->codigo                        = "ht10_sequencial";
                 $clCandidatos->descr                         = "z01_nome";
                 $clCandidatos->nomeobjeto                    = "lista_candidatos";
                 $clCandidatos->funcao_js                     = 'js_mostra_candidato';
                 $clCandidatos->funcao_js_hide                = 'js_mostra_candidato1';
                 $clCandidatos->cabecalho                     = "<b>Lista de Candidatos</b>";
                 $clCandidatos->tipo                          = 2;   
                 $clCandidatos->Labelancora                   = "Candidatos";
                 $clCandidatos->func_arquivo                  = "func_habitcandidato.php";
                 $clCandidatos->passar_query_string_para_func = "&sListaInteressePrograma='+js_getlist_programas()+'"; //filtro se os pragramas foram selecionados
                 $clCandidatos->nomeiframe                    = "iframe_candidatos";
                 $clCandidatos->vwidth                        = "500";
                 $clCandidatos->nome_botao                    = "cl_lancaCandidatos";
                 $clCandidatos->linhas                        = 5;
                 $clCandidatos->funcao_gera_formulario();          
          ?>
         </td>
      </tr>  
    </table>
  </fieldset> 
  <table style="margin-top: 10px;">
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="imprimir" id="imprimir" type="button" value="Imprimir" onclick="js_emite();" >
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

function js_pesquisaInscricao(mostra){

  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_habitinscricao.php?funcao_js=parent.js_mostraInscricao1|ht15_sequencial|z01_nome','Pesquisa',true);
  } else {
  
     if(document.form1.ht15_sequencial.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_habitinscricao.php?pesquisa_chave='+document.form1.ht15_sequencial.value+'&funcao_js=parent.js_mostraInscricao','Pesquisa',false);
     }else{
       document.form1.ht15_sequencial.value = ''; 
     }
  }
}
function js_mostraInscricao(chave,chave2,erro){
  document.form1.ht15_sequencial.value = chave; 
  document.form1.nome_inscricao.value = chave2;
  if (erro == true) {
   
    document.form1.ht15_sequencial.focus(); 
    document.form1.ht15_sequencial.value = ''; 
  }
}
function js_mostraInscricao1(chave1,chave2){
  document.form1.ht15_sequencial.value = chave1;
  document.form1.nome_inscricao.value = chave2;
  db_iframe_orcelemento.hide();
}


// função responsável pelo envio dos dados ao relatorio

function js_emite(){

    var sFonte      = "hab2_inscricoes002.php";
    var sDataA      = $F('ht15_datalancamentoA');
    var sDataB      = $F('ht15_datalancamentoB');
    var iInscricao  = $F('ht15_sequencial');
    var sOrdem      = $F('filtro_ordem');
    var sQuebra     = $F('filtro_quebra');
    var iProgramas  = js_getlist_programas();
    var iCandidatos = js_getlist_candidatos();
    var sQuery  = "";
    
		    sQuery  = "?sDataA="     + sDataA;
		    sQuery += "&sDataB="     + sDataB;
		    sQuery += "&iInscricao="  + iInscricao;
		    sQuery += "&sOrdem="     + sOrdem;
		    sQuery += "&sQuebra="    + sQuebra;
		    sQuery += "&iProgramas="  + iProgramas;
		    sQuery += "&iCandidatos=" + iCandidatos;
		    jan = window.open(sFonte+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
		    jan.moveTo(0,0);
}

// função que retorna a lista de programas selecionados

function js_getlist_programas() {

      var sListaPrograma   = "";
      var sVirgPrograma    = "";
      /*
          cria a lista de programas selecionados
      */
      for(iProg = 0; iProg < document.form1.elements['lista_programas'].length; iProg++){
      
         sListaPrograma += (sVirgPrograma + document.form1.elements['lista_programas'].options[iProg].value);
         sVirgPrograma = ",";
      }
     return   sListaPrograma;
}
// função que retorna a lista de candidatos selecionados

function js_getlist_candidatos(){

      var sListaCandidatos = "";
      var sVirgCandidatos  = "";
      
      /*
          cria a lista de candidatos selecionados
      */
      for(iCand = 0; iCand < document.form1.elements['lista_candidatos'].length; iCand++){
      
         sListaCandidatos += (sVirgCandidatos + document.form1.elements['lista_candidatos'].options[iCand].value);
         sVirgCandidatos = ",";
      }      
      return sListaCandidatos;
}


</script>