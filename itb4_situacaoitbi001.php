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
include("classes/db_itbi_classe.php");
include("classes/db_itbinumpre_classe.php");
include("classes/db_itbiavalia_classe.php");

$oGet   = db_utils::postmemory($_GET);

$clitbi 	  = new cl_itbi();
$clitbinumpre = new cl_itbinumpre();
$clitbiavalia = new cl_itbiavalia();


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
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
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table style="padding-top:15px;"height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr align="center" valign="top">
	<td>
	    <table>
	     <?
	       $rsDadosAvalia = $clitbiavalia->sql_record($clitbiavalia->sql_query($oGet->guia));
	          
	       if ( $clitbiavalia->numrows > 0 ) {
		                  
	         $oDadosAvalia = db_utils::fieldsMemory($rsDadosAvalia,0);
	         	
	     ?>
	      <tr>
	        <td><b>Liberada :</b>		</td>
		    <td  class='texto'>Sim	    </td>
			<td><b>Data :</b>		    </td>        
		    <td  class='texto'><?=db_formatar($oDadosAvalia->it14_dtliber,"d")?> 	</td>
		  </tr>
		  <tr>
		    <td><b>Usuário :</b>			</td>
		    <td  class='texto'><?=$oDadosAvalia->nome?>      </td>
		    <td><b>Hora :</b>		   				         </td>        
		    <td  class='texto'><?=$oDadosAvalia->it14_hora?> </td>
		  </tr>
		  <tr>
		    <td><b>Observação Avaliação :</b>  				            </td>
		    <td colspan="3" class='texto'><?=(isset($oDadosITBI->it14_obs)?"$oDadosITBI->it14_obs":"")?>    </td>
		  </tr>                            
		<?
		   } else {	
		?>
		  <tr>
		    <td><b>Liberada :</b>	     </td>
		    <td  class='texto'><b>Não</b></td>
		  </tr>
	    <?
	       }	
		     $oGuiaPaga          = new stdClass();
		     $oGuiaAberto        = new stdClass();
		     $oGuiaSemIncidencia = new stdClass();
		      
		     $sCampos  = " it15_numpre, 		                                     ";
	       $sCampos .= " recibo.k00_dtvenc,it01_data,	                         ";
	       $sCampos .= " recibo.k00_valor, 	                            	     ";
	       $sCampos .= " arrepaga.k00_dtpaga, 						             		     ";
	       $sCampos .= " arrepaga.k00_conta, 				                    	     ";
	       $sCampos .= " case 													                       ";
	       $sCampos .= "   when k00_dtpaga is not null then 'Pago' else        ";
	       $sCampos .= "   	 case                   											     ";
	       $sCampos .= "   	 	 when recibo.k00_valor > 0 then 'Em Aberto'      ";
	       $sCampos .= "   	 	 else 'Não Incide'			              			     ";
	       $sCampos .= "   	 end						                     	             ";
	       $sCampos .= " end as situacao                			                 ";
	       $rsDadosEmite = $clitbinumpre->sql_record($clitbinumpre->sql_query_recibo("",$sCampos,"it15_numpre asc"," it15_guia = {$oGet->guia}"));
	       
	       $aGuiasEncontradas = db_utils::getColectionByRecord($rsDadosEmite);
	       
	       $iPagas   = 0;
	       $iAbertas = 0;
	       $iIncide  = 0;
	       
	       foreach ($aGuiasEncontradas as $oGuiasEmitidas) {
	         
	         if ($oGuiasEmitidas->situacao =='Pago'){
	           $oGuiaPaga          = $oGuiasEmitidas;
	           $iPagas            += 1;
	           
	         } elseif ($oGuiasEmitidas->situacao == 'Em Aberto'){
	           $oGuiaAberto        = $oGuiasEmitidas;
	           $iAbertas          += 1;
	           
	         } else {
	           $oGuiaSemIncidencia = $oGuiasEmitidas;
	           $iIncide           += 1;
	           
	         }
	       }
	       
	       if ($iPagas > 0) {
	         $oDadosEmite = $oGuiaPaga;
	       } else {
	         
	         if ($iAbertas > 0) {
	           $oDadosEmite = $oGuiaAberto;
	       } else {
	           $oDadosEmite = $oGuiaSemIncidencia;
	         }
	       }
	       if ( $clitbinumpre->numrows > 0 ) {
	                  
	  //      $oDadosEmite = db_utils::fieldsMemory($rsDadosEmite,0);	

	    ?>
          <tr>
	  	    <td><b>Emitida :</b>		</td>
		    <td  class='texto'>Sim	    </td>
		    <td><b>Numpre  :</b>	    </td>        
		    <td  class='texto'><?=$oDadosEmite->it15_numpre?></td>
	      </tr>
	      <tr>
	        <td><b>Data de Vencimento :</b>	</td>
		    <td  class='texto'><?=db_formatar($oDadosEmite->k00_dtvenc,"d")?></td>
			<td><b>Valor a Pagar :</b>	    </td>        
		    <td  class='texto'><?=db_formatar($oDadosEmite->k00_valor,"f") ?></td>
		  </tr>              
		  <tr>
		    <td><b>Situação do Recibo :</b>	</td>
		    <td  class='texto'><?=$oDadosEmite->situacao?></td>
		   	<td><b>Data			 :</b>	    </td>        
		    <td  class='texto'><?=db_formatar($oDadosEmite->k00_dtpaga,"d")?></td>
		  </tr>              
		  <tr>
		    <td><b>Conta :</b>	</td>
		    <td  colspan="3" class='texto'><?=$oDadosEmite->k00_conta?> </td>
		  </tr>              
		<?
		   } else {	
		?>
		  <tr>
		    <td><b>Emitida :</b>   	     </td>
		    <td  class='texto'><b>Não</b></td>
		  </tr>
		<?
		   }	
		?>              
       </table>
   </td>
 </tr>
</table>
</body>
</html>