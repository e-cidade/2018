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
include("classes/db_conlancamval_classe.php");
include("libs/db_libcontabilidade.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);


$clconlancamval = new cl_conlancamval;
$clrotulo = new rotulocampo;
$clrotulo->label("c61_reduz");

$anousu = db_getsession("DB_anousu");

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
    <td height="13" align="center" valign="top">
       <table width="95%" border="0" align="center" cellspacing="0">
       <form name="form2" method="post" action="" >
          
          <?
          if (isset($perini) && isset($perfin)){
                  $sql01 = db_planocontassaldo(db_getsession("DB_anousu"),$perini,$perfin,true,"c61_reduz=$codrec ");
		  
  	           $sql= "select c61_reduz,
		                 c60_descr,
				 saldo_anterior,
		                 saldo_anterior_debito as saldo_a_Debito,
		                 saldo_anterior_credito as saldo_a_Credito,
		                 saldo_final as saldo
	                  from ($sql01) as X
 	                  where c61_reduz >0 ";	     
                   $res = $clconlancamval->sql_record($sql);
		   //db_criatabela($res);
		   if ($clconlancamval->numrows >0 ){
                         db_fieldsmemory($res,0,true);
		   }  
          } ?>
         <tr>
             <td><?=@$Lc61_reduz?></td>
	     <td colspan='4'>
	     <? 
	     db_input('c61_reduz',10,"","true",'text',3); 
	     db_input('c60_descr',60,"","true",'text',3); ?></td> 
         </tr>
         <tr>
             <td nowrap><strong> Saldo Anterior:</strong></td>
	     <td>
	     <?
	     db_input('saldo_anterior',10,"","false",'text',3);
	     echo $sinal_anterior;
	     ?>
	     </td> 
	     <td> &nbsp;  </td>  
	     <td> &nbsp;  </td>  
         </tr>

         <tr>
             <td nowrap><strong>Débito:<strong></td>
	     <td><? db_input('saldo_a_debito',10,"","false",'text',3); ?></td>
	     <td>&nbsp; </td> 
	     <td colspan="2" ><strong>Período:</strong> 
	     <?
	     $perini = db_formatar($perini,'d');
	     db_input('perini',10,"",true,'text',3);
	     echo " a "; 
	     $perfin = db_formatar($perfin,'d');
	     db_input('perfin',10,"",true,'text',3); 
	     ?> 
	     </td>  
         </tr>
	 <tr>
             <td nowrap><strong>Crédito:</strong></td>
	     <td><? db_input('saldo_a_credito',10,"","false",'text',3); ?></td>
             <td> &nbsp; </td>  
	     <td><input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conlancamval.hide();"></td>
         </tr>
	 <tr>
             <td> <strong>Saldo Atual:</strong></td>
	     <td><?
	     db_input('saldo',10,"","false",'text',3); 
             echo $sinal_final;
	     ?></td>
	     <td align="left"> &nbsp;</td> 
	     <td> &nbsp; </td>  
	     <td> &nbsp;  </td>  
         </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_conlancamval.php")==true){
             include("funcoes/db_func_conlancamval.php");
           }else{
           $campos = "conlancamval.*";
           }
        }
	if (isset($codrec) && $codrec !=""){
///----  
        $sql = "select c69_codlan,
                      c69_sequen,
	              c50_descr,
		      conta_partida as DL_contra_partida,
		      c60_descr,
		      c69_valor
	        from (
		        select c69_codlan,
	                       c69_sequen,
			       c50_descr,
			       ( case  when c69_credito = $codrec  then  c69_debito
			               when c69_debito  = $codrec  then  c69_credito
			         end ) as conta_partida,
			       c69_valor
		        from conlancamval
			         left outer join conhist on c50_codhist = c69_codhist
		        where  c69_anousu = $anousu
		               and (c69_credito = $codrec or c69_debito = $codrec)
                     ) as x
                    inner join conplanoreduz on c61_reduz= x.conta_partida and c61_anousu=".db_getsession("DB_anousu")."
	                inner join conplano     on c60_codcon=c61_codcon  and c60_anousu=c61_anousu              
	       order by c69_codlan
               " ;
																							 
	
//	 $sql = $clconlancamval->sql_query(null,$campos,"c69_sequen","c69_anousu=$anousu and (c69_credito = $codrec or c69_debito=$codrec) ");
	 // echo $sql;
         db_lovrot($sql,13,"()","",$funcao_js);
	}

        ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>