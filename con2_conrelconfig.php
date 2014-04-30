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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("libs/db_liborcamento.php");

include ("classes/db_conrelinfo_classe.php");
include ("classes/db_conrelvalor_classe.php");
include ("classes/db_orcparamrel_classe.php");
include ("classes/db_orcparamseq_classe.php");
include ("classes/db_orcparamelemento_classe.php");
include ("classes/db_orcparamrecurso_classe.php");
include ("classes/db_orcparamsubfunc_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS,2);

$clconrelinfo = new cl_conrelinfo;
$clconrelvalor = new cl_conrelvalor;
$clorcparamrel = new cl_orcparamrel;
$clorcparamseq = new cl_orcparamseq;
$clorcparamelemento = new cl_orcparamelemento;
$clorcparamrecurso  = new cl_orcparamrecurso;
$clorcparamsubfunc  = new cl_orcparamsubfunc;

$clrotulo = new rotulocampo;


$db_opcao = 1;
$db_botao = true;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_editar_estrut(codrel,seq){
  js_OpenJanelaIframe('top.corpo.iframe_config','nome','con2_conrelconfig_estrut.php?c83_codrel='+codrel+'&sequen='+seq,'Editar',true,'0');
}  
function js_editar_recurso(codrel,seq){
  js_OpenJanelaIframe('top.corpo.iframe_config','nome','con2_conrelconfig_recurso.php?c83_codrel='+codrel+'&sequen='+seq,'Editar',true,'0');
}  

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<table  align="center" border=1>
<tr>
  <td><b> Sequencia </b></td>
  <td><b> Descrição </b></td>
  <td><b> Estrutural</b></td>
  <td><b> Nível     </b></td>
  <td><b> SubFunção </b></td>
  <td><b> Recurso   </b></td>
</tr>
<?
$record = $clorcparamseq->sql_record($clorcparamseq->sql_query($codrel, null, "o69_codseq,o69_descr","o69_codseq"));
if ($clorcparamseq->numrows > 0) {
  for ($xx = 0; $xx < $clorcparamseq->numrows; $xx ++) {
	db_fieldsmemory($record, $xx);
	$matriz["$o69_codseq"] = $o69_descr;
        ?>
         <tr>
           <td valign=top align=right><?=$o69_codseq ?></td>
	   <td valign=top align=left><?=$o69_descr  ?></td>
	   <td valign=top align=left >
              <a href="#" onClick="js_editar_estrut('<?=$codrel?>','<?=$o69_codseq?>');">Editar</a>
	      <? $res = $clorcparamelemento->sql_record($clorcparamelemento->sql_query_estrutural(db_getsession("DB_anousu"),$codrel,$o69_codseq,null,"o44_codele,c60_estrut","c60_estrut"));
	         //if ($clorcparamelemento->numrows > 0)
	 	   db_selectmultiple('parametros',$res,$clorcparamelemento->numrows+1,1);	      	
	      ?>
             </td>	   
	   <td valign=top><input type=text name=nivel maxlength=2 size=2> <td/>
	   <td valign=top>
	   <? $res = $clorcparamsubfunc->sql_record($clorcparamsubfunc->sql_query(db_getsession("DB_anousu"),$codrel,$o69_codseq,null,"o44_codele,c60_estrut","c60_estrut"));
	      //if ($clorcparamsubfunc->numrows > 0)
		 db_selectmultiple('subfunc',$res,$clorcparamsubfunc->numrows+1,1);	      	
	   ?>
           </td>   	   
	   <td valign=top align=left>
             <a href="#" onClick="js_editar_recurso('<?=$codrel?>','<?=$o69_codseq?>');">Editar</a> 
 	     <? $res = $clorcparamrecurso->sql_record($clorcparamrecurso->sql_query(db_getsession("DB_anousu"),$codrel,$o69_codseq,null,"o44_codrec,o15_descr"));
	       //if ($clorcparamrecurso->numrows > 0)
		 db_selectmultiple('recurso',$res,$clorcparamrecurso->numrows+1,1);	      	
	     ?>
	   </td>
	 </tr>
	<?
  }
}
?>
</table>
</form>
</body>
</html>