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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_liborcamento.php");
require_once("classes/db_ppaestimativa_classe.php");
require_once("dbforms/db_classesgenericas.php");
require_once("dbforms/db_funcoes.php");
$clppaestimativa = new cl_ppaestimativa();
$oPost           = db_utils::postMemory($_POST);
$oListaRecurso   = new cl_arquivo_auxiliar;
$clppaestimativa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o01_descricao");
$clrotulo->label("o01_anoinicio");
$clrotulo->label("o01_anofinal");
$clrotulo->label("o01_descricao");
$clrotulo->label("o01_sequencial");
$clrotulo->label("o01_numerolei");
$clrotulo->label("o57_fonte");
$db_opcao = 1;

$lProcessaManual = false;
if (isset($oPost->o05_ppalei) && $oPost->o05_ppalei != "") {

  $oDaoPPALei = db_utils::getDao("ppalei");
  $sSqlLei    = $oDaoPPALei->sql_query($oPost->o05_ppalei);
  $rsLei      = $oDaoPPALei->sql_record($sSqlLei);
  if ($oDaoPPALei->numrows > 0) {

     $oLei          = db_utils::fieldsMemory($rsLei, 0);
     $o01_anoinicio = $oLei->o01_anoinicio;
     $o01_anofinal  = $oLei->o01_anofinal;
     $o01_descricao = $oLei->o01_descricao;
     $o01_numerolei = $oLei->o01_numerolei;
  }

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/ppaUserInterface.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <center>
  <form name='form1' method='post'>
  <table>
    <tr>
      <td>
        <fieldset>
          <legend>
            <b>Demonstrativo das Projeções da Receita</b>
          </legend>
          <table>
           <tr>
              <td nowrap title="<?=@$To05_ppalei?>">
                <?
                db_ancora("<b>Lei do PPA</b>","js_pesquisao05_ppalei(true);",$db_opcao);
                ?>
              </td>
              <td>
                <?
                db_input('o05_ppalei',10,$Io01_sequencial,true,'text',$db_opcao," onchange='js_pesquisao05_ppalei(false);'")
                ?>
                <?
                db_input('o01_descricao',40,$Io01_descricao,true,'text',3,'')
                ?>
              </td>
            </tr>
             <tr>
              <td nowrap title="<?=@$To05_ppaversao?>">
                <b>Perspectiva:</b>
              </td>
              <td id='verppa'>

              </td>
            </tr>
              <tr>
                <td nowrap title="<?=@$To01_anoinicio?>">
                 <?=@$Lo01_anoinicio?>
                </td>
                <td>
                <?
                  db_input('o01_anoinicio',10,$Io01_anoinicio,true,'text',3,"")
                ?>
               </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$To01_anofinal?>">
                <?=@$Lo01_anofinal?>
              </td>
              <td>
                <?
                  db_input('o01_anofinal',10,$Io01_anofinal,true,'text',3,"")
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$To01_numerolei?>">
                 <?=@$Lo01_numerolei?>
              </td>
              <td>
                <?
                  db_input('o01_numerolei',10,$Io01_numerolei,true,'text',3,"")
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$To57_fonte?>">
                 <?=@$Lo57_fonte?>
              </td>
              <td>
                <?
                  db_input('o57_fonte',15,$Io57_fonte,true,'text',1,"")
                ?>
              </td>
            </tr>
             <tr>
              <td align="center" colspan="3">
                <?
                if (isset($oPost->o05_ppalei) && $oPost->o05_ppalei != "") {
                  db_selinstit('',300,100);
                }
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap>
                 <b>Agrupa por Recurso:</b>
              </td>
              <td>
                <?
                  $agrupaporrecurso = 2;
                  db_select("agrupaporrecurso",
                            array(1 => "Sim", 2=>"Não"),
                            true,1,"onchange='js_showListaRecursos(this.value)'");
                ?>
              </td>
            </tr>
            <tr id='listarecursos' style='display: none;'>
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
               onclick='js_imprimePPAReceita()'>
      </td>
    </tr>
  </table>
  </form>
  </center>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_showListaRecursos(iShow) {

  if (iShow == 1) {
    $('listarecursos').style.display='';
  } else {
    $('listarecursos').style.display='none';
  }
}
sUrlRPC       = 'orc4_ppaRPC.php';
lJaProcessado = <?=$lProcessaManual?"true":"false"; ?>;


function js_pesquisao05_ppalei(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_ppalei',
                        'func_ppalei.php?funcao_js=parent.js_mostrappalei1|o01_sequencial|o01_descricao',
                        'Pesquisa de Leis para o PPA',
                        true);
  }else{
     if(document.form1.o05_ppalei.value != ''){
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_ppalei',
                            'func_ppalei.php?pesquisa_chave='
                            +document.form1.o05_ppalei.value+'&funcao_js=parent.js_mostrappalei',
                            'Leis PPA',
                            false);
     }else{
       document.form1.o01_descricao.value = '';
     }
  }
}
function js_mostrappalei(chave, erro) {

  document.form1.o01_descricao.value = chave;
  if(erro==true){
    document.form1.o05_ppalei.focus();
    document.form1.o05_ppalei.value = '';
    js_limpaComboBoxPerspectivaPPA();
  } else {
    document.form1.submit();
  }

}
function js_mostrappalei1(chave1,chave2){
  document.form1.o05_ppalei.value = chave1;
  document.form1.o01_descricao.value = chave2;
  db_iframe_ppalei.hide();
  document.form1.submit();


}

function js_imprimePPAReceita() {

  var iProcessado = $('o05_ppaversao').options[$('o05_ppaversao').selectedIndex].processadoreceita;
  if (iProcessado == 1) {

    var sQuery  = "?ppalei="+$F('o05_ppalei');
        sQuery += "&ppaversao="+$F('o05_ppaversao');
    	sQuery += "&anoini="+$F('o01_anoinicio');
    	sQuery += "&anofin="+$F('o01_anofinal');
    	sQuery += "&estrut="+$F('o57_fonte');
    	sQuery += "&agrupaporrecurso="+$F('agrupaporrecurso');

    var oListaRecursos = $('recursos').options;
    var sListaRecursos = "";
    var sVirgula = "";
    if (oListaRecursos.length > 0) {

      for (var i = 0; i < oListaRecursos.length; i++) {

         sListaRecursos += sVirgula+oListaRecursos[i].value;
         sVirgula = ",";

      }
      sQuery += "&sRecursos="+sListaRecursos;
    }
    var sInstits = document.form1.db_selinstit.value;
    if (sInstits == "") {

      alert('Selecione uma instituicao');
      return false;

    }
    sQuery       += "&sInstit="+sInstits;
    jan = window.open('orc2_ppademostrativoreceita002.php'+sQuery,
                      '',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);

  } else {

    alert('Não existem estimativas calculadas!');
    return false;


  }

}
js_drawSelectVersaoPPA($('verppa'));
<?
 if (isset($oPost->o05_ppalei) && $oPost->o05_ppalei != "") {
   echo "js_getVersoesPPA({$oPost->o05_ppalei})\n";
 }
?>
</script>