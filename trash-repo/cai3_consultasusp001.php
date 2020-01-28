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
require("libs/db_utils.php");
include("dbforms/db_funcoes.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_suspensao_classe.php");
include("classes/db_suspensaofinaliza_classe.php");

$clsuspensao 		 = new cl_suspensao();
$clsuspensaofinaliza = new cl_suspensaofinaliza();
$oGet    	 		 = db_utils::postmemory($_GET);

$sCampos  = " ar18_sequencial,										  ";
$sCampos .= " login,												  ";
$sCampos .= " ar18_data,											  ";	 
$sCampos .= " ar18_hora,											  ";
$sCampos .= " ar18_obs,												  ";
$sCampos .= " v63_sequencial,										  ";
$sCampos .= " ar19_sequencial,										  ";
$sCampos .= " ar19_obs,												  ";
$sCampos .= " case 													  ";
$sCampos .= "   when ar18_situacao = 1 then 'Ativa' else 'Finalizada' ";
$sCampos .= " end as ar18_situacao 									  ";

$rsConsultaSuspensao = $clsuspensao->sql_record($clsuspensao->sql_query_proc($oGet->suspensao,$sCampos));

if ( $clsuspensao->numrows > 0 ) {
  $oSuspensao  = db_utils::fieldsMemory($rsConsultaSuspensao,0);    
}else{
  db_msgbox("Suspensão não encontrada!");  
  echo " <script> parent.db_iframe_consultasusp".$oGet->suspensao.".hide(); </script>";
  exit;
}


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.texto {background-color:white}
.selecionados  {background-color:white;
               text-decoration:none;
               border-right:2px outset #2C7AFE;
               border-bottom:1px outset white;
               display:block;
               padding:3px;
               text-align:center;
               color:black
              }
.dados{ display:block;
        background-color:#CCCCCC;
        text-decoration:none;
        border-right:3px outset #A6A6A6;
        border-bottom:3px outset #EFEFEF;
        color:black;
        text-align:center;
        padding:3px;
      }  
</style>
<script>
function js_marca(obj){

   lista = document.getElementsByTagName("A");

   for (i = 0;i < lista.length;i++){

     if (lista[i].className == 'selecionados' && lista[i].className != '') {
       lista[i].className = 'dados';
     }

   }

   obj.className = 'selecionados';

}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" id='teste'>
<center>
<table width='100%' cellspacing=0>
<tr>
<td colspan='2'>
<fieldset>
  <legend><b>Dados da Suspensão - <?=$oGet->suspensao?>  </b></legend>
    <table border='0'>
      <tr>
        <td><b>Data da Suspensão :</b>    	                            </td>
        <td class='texto'><?=db_formatar($oSuspensao->ar18_data,'d') ?> </td>
        <td align='right'><b>Hora da Suspensão :</b>            	    </td>
        <td class='texto'><?=$oSuspensao->ar18_hora?>                   </td>
      </tr>
      <tr>
        <td><b>Situação :</b>    	                         		    </td>
        <td class='texto'><?=$oSuspensao->ar18_situacao?>               </td>
        <td align='right'><b>Usuário :</b>            	      		    </td>
        <td class='texto'><?=$oSuspensao->login?>                       </td>          
      </tr>      
      <tr>
        <td><b>Observação :</b></td>
        <td colspan="3" class='texto'><?=$oSuspensao->ar18_obs?>   </td>
      </tr>    
    </table>
</fieldset>
<?
  if ( trim($oSuspensao->ar19_sequencial) != "" ) {

  	 $sCamposFinaliza  = " nome, 	  ";
  	 $sCamposFinaliza .= " ar19_data, ";
  	 $sCamposFinaliza .= " ar19_hora, ";
  	 $sCamposFinaliza .= " ar19_obs,  ";
  	 $sCamposFinaliza .= " case 	  ";
  	 $sCamposFinaliza .= "   when ar19_tipo = 1 then 'Débito Reativado' else ' Débito Cancelado' ";
  	 $sCamposFinaliza .= " end as tipo";
  	 
  	
  	 $rsSuspensaoFinaliza = $clsuspensaofinaliza->sql_record($clsuspensaofinaliza->sql_query($oSuspensao->ar19_sequencial,$sCamposFinaliza));
  	 $oSuspensaoFinaliza  = db_utils::fieldsMemory($rsSuspensaoFinaliza,0);
  	  
	 echo "<fieldset>														  		  ";
	 echo "  <legend>														  		  ";
	 echo "    <b>Dados Finalização</b>										  		  ";
	 echo "  </legend>														 		  ";
   	 echo "  <table>														  		  ";
     echo "    <tr> 														  		  ";
     echo "      <td><b>Usuário :</b></td>					 						  ";
     echo "      <td class='texto'>{$oSuspensaoFinaliza->nome}</td>     			  ";
     echo "      <td><b>Tipo :</b></td>					 							  ";
     echo "      <td class='texto'>{$oSuspensaoFinaliza->tipo}</td>     			  ";
     echo "    </tr>														 		  ";
     echo "    <tr> 														  		  ";
     echo "      <td><b>Data :</b></td>					 						 	  ";
     echo "      <td class='texto'>".db_formatar($oSuspensaoFinaliza->ar19_data,"d")."</td> ";
     echo "      <td><b>Hora :</b></td>					 							  ";
     echo "      <td class='texto'>{$oSuspensaoFinaliza->ar19_hora}</td>    		  ";
     echo "    </tr>														 		  ";        	 
     echo "    <tr> 														  		  ";
     echo "      <td><b>Observação :</b></td>					  					  ";
     echo "      <td colspan='3'' class='texto'>{$oSuspensaoFinaliza->ar19_obs}</td>  ";
     echo "    </tr>														  		  ";
     echo "  </table>														  		  ";
	 echo "</fieldset>														  		  ";
  }
?>
</td>
</tr>

<tr>
<td colspan='2'>
  <fieldset>
   <legend><b>Detalhamento : </b></legend>
     <table width='100%'>
 
       <tr>
         <td width='20%' valign='top' height='100%' rowspan='2'>
           <a class='selecionados' onclick='js_marca(this);this.blur()' href='arr3_consultadebitossuspensos.php?suspensao=<?=$oGet->suspensao;?>'  target='dados'><b> Débitos   </b></a> 
           <a class='dados'        onclick='js_marca(this);this.blur()' href='arr3_consultaprocessos001.php?suspensao=<?=$oGet->suspensao;?>'      target='dados'><b> Processo  </b></a>
           <?
		 	 if ( trim($oSuspensao->v63_sequencial) != "") {
		 	   echo "<a class='dados' onclick='js_marca(this);this.blur()' href='arr3_consultprocjuradvog001.php?suspensao={$oGet->suspensao}' 	   target='dados'><b> Advogados  </b></a>";
		 	 }         
           ?>
         </td>
         <td valign='top' height='100%' style='border:1px inset white'>
           <iframe height='300' name='dados' frameborder='0' width='100%' src='arr3_consultadebitossuspensos.php?suspensao=<?=$oGet->suspensao;?>' style='background-color:#CCCCCC'>
           </iframe>
         </td>
       </tr>
 
     </table>
  </fieldset>
</td>
</tr>
</table>
<center>
  <input type='button' value='Voltar'  onclick='parent.db_iframe_consultasusp<?=$oGet->suspensao?>.hide()'>
</center>
</body>
</html>