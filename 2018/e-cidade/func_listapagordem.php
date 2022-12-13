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
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

include("classes/db_pagordem_classe.php");
include("classes/db_pagordemele_classe.php");

$clpagordem = new cl_pagordem;
$clpagordemele = new cl_pagordemele;

$clpagordem->rotulo->label("e50_codord");
$clpagordem->rotulo->label("e50_numemp");

if(isset($e50_codord) && $e50_codord!=''){
   $result = $clpagordem->sql_record($clpagordem->sql_query($e50_codord)); 
   db_fieldsmemory($result,0);


   $result  = $clpagordem->sql_record($clpagordemele->sql_query_file($e50_codord,null,"sum(e53_valor) as tot_valor, sum(e53_vlranu) as tot_vlranu,sum(e53_vlrpag) as tot_vlrpag")); 
   db_fieldsmemory($result,0);
}


$clpagordem->rotulo->label();
$clpagordemele->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e60_numemp");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr> 
  <td height="63" align="center" valign="top">
    <table>      
	<tr>
	  <td nowrap title="<?=@$Te50_codord?>">
	     <?=@$Le50_codord?>
	  </td>
	  <td> 
           <?
              db_input('e50_codord',6,$Ie50_codord,true,'text',3)
           ?>
	  </td>
	</tr>  
	<tr>
	  <td nowrap title="<?=@$Te50_numemp?>">
       <?=db_ancora($Le50_numemp,"js_JanelaAutomatica('empempenho','".@$e50_numemp."')",1)?>        
	  </td>
	  <td> 
           <?
              db_input('e50_numemp',6,$Ie50_numemp,true,'text',3)
           ?>
	  </td>
	</tr>  
	<tr>  
	    <td nowrap title="<?=@$Te50_data?>">
	       <?=@$Le50_data?>
	    </td>
	    <td> 
	<?
	if(empty($e50_data_dia)){
	  $e50_data_dia =  date("d",db_getsession("DB_datausu"));
	  $e50_data_mes =  date("m",db_getsession("DB_datausu"));
	  $e50_data_ano =  date("Y",db_getsession("DB_datausu"));
	}
	db_inputdata('e50_data',@$e50_data_dia,@$e50_data_mes,@$e50_data_ano,true,'text',3);
	?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Te50_obs?>">
	       <?=@$Le50_obs?>
	    </td>
	    <td > 
	  <?
	  db_textarea('e50_obs',0,30,$Ie50_obs,true,'text',3)
	  ?>
	    </td>
	  </tr>  
	  <tr>
	    <td nowrap title="<?=@$Te53_valor?>">
	       <?=@$Le53_valor?>
	    </td>
	    <td > 
	  <?
	  $tot_valor = number_format($tot_valor,'2',".",",");
	  db_input('tot_valor',9,$Ie53_valor,true,'text',3)
	  ?>
	    </td>
	  </tr>  
	  <tr>
	    <td nowrap title="<?=@$Te53_vlranu?>">
	       <?=@$Le53_vlranu?>
	    </td>
	    <td > 
	  <?
	  $tot_vlranu = number_format($tot_vlranu,'2',".",",");
	  db_input('tot_vlranu',9,$Ie53_vlranu,true,'text',3)
	  ?>
	    </td>
	  </tr>  
	  <tr>
	    <td nowrap title="<?=@$Te53_vlrpag?>">
	       <?=@$Le53_vlrpag?>
	    </td>
	    <td > 
	  <?
	  $tot_vlrpag = number_format($tot_vlrpag,'2',".",",");
	  db_input('tot_vlrpag',9,$Ie53_vlrpag,true,'text',3)
	  ?>
	    </td>
	  </tr>
    </table>      
    <table>      
	   <tr>
	     <td colspan='2' align='center'>
	      <fieldset><legend align='center'><b><small>ELEMENTOS DA ORDEM</small></b></legend>
	       <iframe name="elementos" id="elementos"  marginwidth="0" marginheight="0" frameborder="0" src="func_listapagordem_ordem.php?e50_codord=<?=$e50_codord?>" width="620" height="100">
	       </iframe>
	      </fieldset> 
	     </td>
	   </tr>  
    
    </table>      
  </td>
 </tr>
</table>
</body>
</html>