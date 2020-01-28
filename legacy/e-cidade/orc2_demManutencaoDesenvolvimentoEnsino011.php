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
include("libs/db_utils.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");

$oGet   = db_utils::postMemory($_GET);
$codrel = $oGet->iCodRel;

$clrotulo = new rotulocampo();
$clrotulo->label("o05_ppalei");
$clrotulo->label("o0i_descricao");
$clrotulo = new rotulocampo();
$clrotulo->label("o05_ppaversao");
$clrotulo->label("o01_sequencial");
$clrotulo->label("o01_descricao");
$db_opcao = 1;

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/ppaUserInterface.js"></script>
<link href="estilos.css"            rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table  align="center">

    <form name="form1" method="post" action="" >
			<tr>
    		<td colspan=2  class='table_header'>
      		 Demonstrativo XI - Demonstrativo das Receitas e Despesas com Manutenção e Desenvolvimento do Ensino - MDE
    		</td>
  	     	</tr>
            <tr>
            <td>
            <fieldset>
            <legend>
               <b>Selecionar PPA</b>
            </legend>
            <table>
            <tr>
              <td nowrap title="<?=@$To05_ppalei?>">
                <?
                db_ancora("<b>Lei do PPA</b>","js_pesquisao05_ppalei(true);",$db_opcao);
                ?>
              </td>
              <td nowrap>
                <?
                db_input('o05_ppalei',10,$Io01_sequencial,true,'text',$db_opcao," onchange='js_pesquisao05_ppalei(false);'")
                ?>
                <?
                db_input('o01_descricao',40,$Io01_descricao,true,'text',3,'');
                db_input('codrel',40,'',true,'hidden',3,'');
                $o116_periodo = 1;
                db_input('o116_periodo',40,'',true,'hidden',3,'');
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
                <td align="center" colspan="2">
                   <? db_selinstit('',300,100); ?>
                </td>
              </tr>
				       <tr>
				        <td align="left" colspan="2">
				          <b>Modelo :</b> <?
				                    $sList = array("ldo"=>"LDO",
				                                   "loa"=>"LOA"
				                                  );
				                    db_select("modelo",$sList,"",1); ?>
				        </td>
				      </tr>
	  			  </table>
	  			</fieldset>
				</td>
      </tr>
      <tr>
        <td align="center">
          <input  name="emite" id="emite" type="button" value="Imprimir" onclick="js_emite();">
        </td>
      </tr>
  </form>
    </table>
</body>
</html>
<script>

  function js_pesquisao05_ppalei(mostra) {

    if (mostra) {

      sUrl = 'func_ppaleiretornoano.php?funcao_js=parent.js_mostrappalei1|o01_sequencial|o01_descricao|o01_anoinicio|o01_anofinal';
      js_OpenJanelaIframe('',
                          'db_iframe_ppalei',
                          sUrl,
                          'Pesquisa de Leis para o PPA',
                          true);
    } else {

       if (document.form1.o05_ppalei.value != '') {

          js_OpenJanelaIframe('',
                              'db_iframe_ppalei',
                              'func_ppaleiretornoano.php?pesquisa_chave='
                              +document.form1.o05_ppalei.value+'&funcao_js=parent.js_mostrappalei',
                              'Leis PPA',
                              false);
       } else {
         document.form1.o01_descricao.value = '';
       }
    }
  }

  function js_mostrappalei(chave, anoinicial, anofinal, erro) {

    document.form1.o01_descricao.value = chave;
    if(erro==true){
      document.form1.o05_ppalei.focus();
      document.form1.o05_ppalei.value = '';
    } else {

      js_getVersoesPPA($F('o05_ppalei'));
      js_recarregaParametros(anoinicial, anofinal);
    }

  }

  function js_mostrappalei1(chave1,chave2, iAnoInicial, iAnoFinal) {

    document.form1.o05_ppalei.value = chave1;
    document.form1.o01_descricao.value = chave2;
    js_getVersoesPPA(chave1);
    db_iframe_ppalei.hide();

    js_recarregaParametros(iAnoInicial, iAnoFinal);
  }

  function js_emite(){

    var doc = document.form1;

    if ( doc.db_selinstit.value == '' ) {
      alert('Nenhum instituição selecionada');
      return false;
    }

    if ( doc.o05_ppalei.value == '' ) {
      alert('Nenhuma lei selecionada!');
      return false;
    }

    if ( doc.codrel.value == '' ) {
      alert('Código do relatório não informado!');
      return false;
    }

    var sQuery  = '?iLei='+doc.o05_ppalei.value;
        sQuery += '&sListaInstit='+doc.db_selinstit.value;
        sQuery += '&iCodRel='+doc.codrel.value;
        sQuery += '&iCodVersao='+$F('o05_ppaversao');
        sQuery += '&sModelo='+doc.modelo.value;

     var jan = window.open('orc2_demManutencaoDesenvolvimentoEnsino002.php'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
         jan.moveTo(0,0);
  }

  function js_recarregaParametros(iAnoInicial, iAnoFinal) {

    var sUrl = "con4_parametrosrelatorioslegais001.php?c83_codrel="+$F('codrel');
    top.corpo.iframe_parametro.location.href = sUrl+"&iAnoInicial="+iAnoInicial+"&iAnoFinal="+iAnoFinal ;
  }
js_drawSelectVersaoPPA($('verppa'));
</script>