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


db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');


?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
  obj = document.form1;
  dt_ini = obj.DBtxt21_ano.value+'-'+obj.DBtxt21_mes.value +'-'+obj.DBtxt21_dia.value;
  dt_fin = obj.DBtxt22_ano.value+'-'+obj.DBtxt22_mes.value +'-'+obj.DBtxt22_dia.value;
  dt_prazo = obj.p_ano.value+'-'+obj.p_mes.value+'-'+obj.p_dia.value;
  jan = window.open('fis2_relautosprazo002.php?setorfiscal='+obj.setorfiscal.value+'&dt_ini='+dt_ini+'&dt_fin='+dt_fin+'&dt_prazo='+dt_prazo,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
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

  <table  align="center" border=0>
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td align="left">
          <b>Data do Auto :</b>   	
	</td>
        <td nowrap><?
               $DBtxt21_ano = db_getsession("DB_anousu");
               $DBtxt21_mes = '01';
               $DBtxt21_dia = '01';
               db_inputdata('DBtxt21',$DBtxt21_dia,$DBtxt21_mes,$DBtxt21_ano ,true,'text',4);
	     ?> <b>à</b>
	     <?
               $DBtxt22_ano = date("Y",db_getsession("DB_datausu"));
               $DBtxt22_mes = date("m",db_getsession("DB_datausu"));
               $DBtxt22_dia = date("d",db_getsession("DB_datausu"));
               db_inputdata('DBtxt22',$DBtxt22_dia,$DBtxt22_mes,$DBtxt22_ano,true,'text',4);
	      ?>
        </td>
       </tr>
       <tr>
        <td>
	 <b>Prazo menor que a data :</b>
        </td>
	<td><?
               $p_ano = date("Y",db_getsession("DB_datausu"));
               $p_mes = date("m",db_getsession("DB_datausu"));
               $p_dia = date("d",db_getsession("DB_datausu"));
               db_inputdata('p',$p_dia,$p_mes,$p_ano,true,'text',4);
	     ?>
	</td>
      </tr>
      </tr> 
      <tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
      <tr>
        <td>
	<b>Setor Fiscal:</b>
	</td>
        <td>
	<?  /**************************************************************************************/
	    /*     COMBOBOX contendo os setores existentes na tabela auto para seleção do setor   */
	    /**************************************************************************************/
	    
	    $i = 0;
	    $select[0] = "(todos setores)";

            $resultTipoFiscaliza = pg_query("SELECT coddepto, descrdepto FROM db_depart 
					                                 OUTTER join auto ON coddepto = y50_setor
					                                 GROUP BY coddepto, descrdepto
									 ORDER BY descrdepto");
	    
	    while ($row = pg_fetch_row($resultTipoFiscaliza)){
	       $i++;
	       $select[$row[0]] = "$row[1]";
	    }
	    db_select("setorfiscal",$select,true,2);
	

           //////////////////////////////////////////////////////////////////////////////////////////
	
	?>
	  
	</td>
      </tr>
      <tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>