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
require("libs/db_utils.php");
$oDaoPlacaixa = db_utils::getDao("placaixa");
$oDaoPlacaixa->rotulo->label("k80_codpla");

db_postmemory($_GET);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table  border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="360" height="25">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
  <center>
  <form name="frmRelatorio" method="post" action="" >
  <table  border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr><td><fieldset><legend><B>Opções</b></legend>
    <table>
    <tr> 
      <td  nowrap title="<?=$Tk80_codpla?>">
      <?=$Lk80_codpla?>
      </td>
      <td align="left" nowrap> 
       <?
       db_input("k80_codpla", 10,$Ik80_codpla,true,"text",1);
       ?>
       </td>
    </tr>
    <tr>
      <td>
        <b>Data Inicial:</b>
      </td>
      <td>
         <?
          db_inputdata("dataini",null,null,null,true,'text',1);
         ?>
      </td>
      <td>
        <b>Data Final</b>
      </td>
      <td> 
         <?
          db_inputdata("datafim",null,null,null,true,'text',1);
         ?> 
      </td>
    </tr>
    <tr>
      <td colspan='1'>
        <b>Filtrar Por:</b>
      </td>
      <td colspan='3'>
        <?
           $aFiltro = array( 
                            "k80_data"  => "Data de Lancamento",
                            "k80_dtaut" => "Data de Autenticação"
                           );
          db_select("sFiltro", $aFiltro,true,1);  
         ?>
      </td>
    </tr>  
    </table></fieldset></td></tr>
    <tr> 
      <td colspan="4" align="center"> 
       <input name="pesquisar" type="button" id="pesquisar2" value="Pesquisar" onclick="js_showReport()"> 
       <input name="limpar" type="reset" id="limpar" value="Limpar" >
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
function js_showReport() {

  iPlanilha = $F('k80_codpla');
  dtDataIni = $F('dataini');
  dtDataFim = $F('datafim');
  sFiltro   = $F("sFiltro");

  if (iPlanilha != '') {
    jan = window.open('cai2_emiteplanilha002.php?codpla='+iPlanilha,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  } else {

	  sFuncaoPesquisa = 'func_placaixairelatorio.php?dataini='+dtDataIni+'&datafim='+dtDataFim+'&sFiltro='+sFiltro ;
	  <?php 
		  if (isset($Modulo)) { ?>
				sFuncaoPesquisa += '&Modulo=Pessoal';
				<?php 
		  } 
		?>
    js_OpenJanelaIframe('top.corpo','db_iframe_plan',sFuncaoPesquisa,'Planilhas',true);
  }
}
</script>