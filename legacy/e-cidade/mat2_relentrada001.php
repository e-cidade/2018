<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require("libs/db_app.utils.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);

$aux    = new cl_arquivo_auxiliar;
$rotulo = new rotulocampo();
$rotulo->label("m60_codmater");
$rotulo->label("m60_descr");
$rotulo->label("m81_codtipo");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
	<?
	  db_app::load("scripts.js, prototype.js, strings.js");
	  db_app::load("estilos.css");
	?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
<form name="form1" method="post" action="">
	<table width="790" border="0" cellpadding="0" cellspacing="0">
	  <tr>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	  </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
	</table>
  <table align="center" border="0">
    <tr>
      <td>
			  <fieldset>
			    <legend><b>Relatório Entrada da Ordem de Compra - Filtros</b></legend>
			  <table align="center" border="0">
			    <tr>
			      <td align="right" title="<?=@$Tm60_codmater?>">
			        <?
			          db_ancora(@$Lm60_codmater,"js_pesquisam60_codmater(true);",1);
			        ?>
			      </td>
			      <td align="left"> 
			        <?
			          db_input('m60_codmater',10,$Im60_codmater,true,'text',1," onchange='js_pesquisam60_codmater(false);'");
			          db_input('m60_descr',40,$Im60_descr,true,'text',3,'');
			        ?>
			      </td>
			    </tr>
			    <tr>
			      <td align="right">
			        <strong>Opções:</strong>
			      </td>
			      <td align="left">
			        <?
			          $aOpcao = array("com"=>"Com os Fornecedores selecionados",
			                          "sem"=>"Sem os Fornecedores selecionados");             
			          db_select("vertipo",$aOpcao,true,1);
			        ?> 
			      </td>
			    </tr>
          <tr>
            <td align="right">
              <strong>Agrupar por:</strong>
            </td>
            <td align="left">
              <?
                $aAgruparPor = array("agrpn"=>"Por Nota",
                                     "agrpoc"=>"Por Ordem de Compra");             
                db_select("agrupar",$aAgruparPor,true,1); 
              ?> 
            </td>
          </tr>
          <tr>
            <td align="right">
              <strong>Ordenar:</strong>
            </td>
            <td align="left">
              <?
                $aOrdenar = array("ordfrn"=>"Fornecedor",
                                  "ordoc"=>"Ordem de compra",
                                  "ordnt"=>"Nota",
                                  "orddt"=>"Data");             
                db_select("ordenar",$aOrdenar,true,1); 
              ?> 
            </td>
          </tr>
			    <tr>
			      <td colspan="2">
			        <?
			          $aux->cabecalho = "<strong>Fornecedores</strong>";
			          $aux->codigo = "pc60_numcgm"; //chave de retorno da func
			          $aux->descr  = "z01_nome";   //chave de retorno
			          $aux->nomeobjeto = 'fornecedor';
			          $aux->funcao_js = 'js_mostra';
			          $aux->funcao_js_hide = 'js_mostra1';
			          $aux->sql_exec  = "";
			          $aux->func_arquivo = "func_pcforne.php";  //func a executar
			          $aux->nomeiframe = "db_iframe_pcforne";
			          $aux->localjan = "";
			          $aux->onclick = "";
			          $aux->db_opcao = 2;
			          $aux->tipo = 2;
			          $aux->top = 0;
			          $aux->linhas = 10;
			          $aux->vwhidth = 400;
			          $aux->funcao_gera_formulario();
			        ?>
			      </td>
			    </tr>
			    <tr>
			      <td align="right">
			        <b>Período:</b>
			      </td>
			      <td align="left">
              <? 
                db_inputdata('dtInicial',null,null,null,true,'text',1,"");                
                 echo "<b> a </b> ";
                db_inputdata('dtFinal',null,null,null,true,'text',1,"");
              ?>
			      </td>
			    </tr>
			  </table>
			  </fieldset>
      </td>
    </tr>
    <tr align="center">
      <td>
        <input name="emitir" id="emitir" type="button" value="Visualizar" onclick="js_mandadados();"/>
      </td>
    </tr>
  </table>
</form>
</body>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script>
function js_mandadados(){
  
  var sUrl       = "";
  var sVir       = "";
  var listaTipo  = "";
  var iTam       = $('fornecedor').length;
  var verTipo    = $F('vertipo');
  var codMater   = $F('m60_codmater');
  var agruparPor = $F('agrupar');
  var ordenarPor = $F('ordenar');
  var dtInicial  = $F('dtInicial');
  var dtFinal    = $F('dtFinal');
    
  for ( x = 0; x < iTam; x++ ) {
  
    listaTipo += sVir+$('fornecedor').options[x].value;
  	sVir       = ",";
  	
  }
  	 	
 	sUrl += 'codmater='+codMater;
 	sUrl += '&listatipo='+listaTipo;
 	sUrl += '&vertipo='+verTipo;
 	sUrl += '&agrupar='+agruparPor;
 	sUrl += '&ordenar='+ordenarPor;
 	sUrl += '&dtInicial='+dtInicial;
 	sUrl += '&dtFinal='+dtFinal;
 	
 	jan = window.open('mat2_relentrada002.php?'+sUrl,'',
 	                  'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);  
}

function js_pesquisam60_codmater(mostra){

  var sUrl1 = 'func_matmater.php?funcao_js=parent.js_mostramatmater1|m60_codmater|m60_descr';
  var sUrl2 = 'func_matmater.php?pesquisa_chave='+$F('m60_codmater')+'&funcao_js=parent.js_mostramatmater';
  
  if ( mostra == true ) {
    js_OpenJanelaIframe('','db_iframe_matmater',sUrl1,'Pesquisa',true);
  } else {
     if ( $F('m60_codmater') != '' ) { 
       js_OpenJanelaIframe('','db_iframe_matmater',sUrl2,'Pesquisa',false);
     } else {
       $('m60_descr').value = ''; 
     }
  }
}

function js_mostramatmater(chave,erro){

  $('m60_descr').value = chave; 
  if ( erro == true ) { 
    $('m60_codmater').focus(); 
    $('m60_codmater').value = ''; 
  }
}

function js_mostramatmater1(chave1,chave2){
  $('m60_codmater').value = chave1;
  $('m60_descr').value    = chave2;
  db_iframe_matmater.hide();
}
</script>