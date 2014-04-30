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

/**
 * 
 * @author I
 * @revision $Author: dbluizmarcelo $
 * @version $Revision: 1.2 $
 */

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
require("libs/db_app.utils.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$dbopcao  = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post">
   <table align="center" border="0">
     <tr>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td>
         <fieldset>
           <legend><b>Relatório de Liberação de Empenhos<b></legend>
            <table  align="center" border="0">
					      <tr>
					        <td align="left"><b>Empenhos: </b></td>
					        <td align="left">
					          <?
					          $aArray = array("t"=>"Todos","l"=>"Liberados" ,"n"=>"Não Liberados");
					          db_select('emempenho',$aArray,true,4,"");
					          ?>
					        </td>
					      </tr>
                <tr>
                    <td nowrap align="left"><b>Data de Liberação: </b></td>
                    <td  align="left" nowrap>
                     <?      
                       db_inputdata('dtliberacaoini',@$dia,@$mes,@$ano,true,'text',$dbopcao,"");
                       echo " <b>até:</b> ";
                       db_inputdata('dtliberacaofim',@$dia2,@$mes2,@$ano2,true,'text',$dbopcao,"");
                     ?>
                    </td>
                </tr>
            </table>
         </fieldset>
       </td>
     </tr>
     <tr>
       <td align="center">
         <input  name="emitir" id="emitir" type="button" value="Emitir" onclick="js_emite();">      
       </td>
     </tr>
   </table>
</form>
<table align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_emite(){
  
  var empenhos        = $F('emempenho');
  var dtliberacaoini  = $F('dtliberacaoini');
  var dtliberacaofim  = $F('dtliberacaofim');
  var sQuery          = '';               
  var vlrItens        = '';
  var vrg             = '';

  sQuery += '&empenhos='+empenhos;
  sQuery += '&dtliberacaoini='+dtliberacaoini;
  sQuery += '&dtliberacaofim='+dtliberacaofim;
  
  jan = window.open('emp2_liberacaoempenhos002.php?'+sQuery,'',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  
  jan.moveTo(0,0);  
}
</script>