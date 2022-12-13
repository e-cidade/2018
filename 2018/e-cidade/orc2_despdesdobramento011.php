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
include("dbforms/db_funcoes.php");
include("libs/db_liborcamento.php");
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
variavel = 1;
function js_emite(){
 sel_instit  = new Number(document.form1.db_selinstit.value);
 if(sel_instit == 0){
     alert('Você não escolheu nenhuma Instituição. Verifique!');
     return false;
 }  
 // pega dados da func_selorcdotacao_aba.php 
 document.form1.filtra_despesa.value = parent.iframe_filtro.js_atualiza_variavel_retorno();
 jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 document.form1.target = 'safo' + variavel++;
 setTimeout("document.form1.submit()",1000);
 return true;
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table  align="center">
    <form name="form1" method="post" action="orc2_despdesdobramento002.php" >
     <input type=hidden name="filtra_despesa" value="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
         <td align="center" colspan="3">
         <?
           db_selinstit('parent.js_limpa',300,100);
          // ja tem instituição na aba filtros
         ?>
         </td>
      </tr>
      <tr>
         <td colspan="2"</td>
         <td >&nbsp;</td>
      </tr>

      <tr>
          <td nowrap align="right" title="<?=$TDBtxt21?>">
            <?=$LDBtxt21?>
          </td>
          <td>
	    <?
              $DBtxt21_ano = db_getsession("DB_anousu");
              $DBtxt21_mes = '01';
              $DBtxt21_dia = '01';
              db_inputdata('DBtxt21',$DBtxt21_dia,$DBtxt21_mes,$DBtxt21_ano ,true,'text',4);
	    ?>
        </td>
      </tr>
      <tr>
        <td nowrap align="right" title="<?=$TDBtxt22?>">
          <?=$LDBtxt22?>
        </td>
        <td>
	  <?
            $DBtxt22_ano = date("Y",db_getsession("DB_datausu"));
            $DBtxt22_mes = date("m",db_getsession("DB_datausu"));
            $DBtxt22_dia = date("d",db_getsession("DB_datausu"));
            db_inputdata('DBtxt22',$DBtxt22_dia,$DBtxt22_mes,$DBtxt22_ano ,true,'text',4);
	  ?>
        </td>
      </tr>   
 
    
    <tr>
        <td>&nbsp; </td>
    </tr>
    
    <tr>
      <td><b>Imprime Filtro: </b></td>
      <td><input type=checkbox name=imprime_filtro ></td>
    </tr>




      </tr>
       <tr>
         <td colspan="2" align="center">
	    <input name="emiterel" value="Gera Relatório" type="button" onclick="js_emite();">
         </td>
      </tr>
      
  </form>
  </table>
</body>
</html>