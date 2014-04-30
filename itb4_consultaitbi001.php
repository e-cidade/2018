<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_itbi_classe.php");
include("classes/db_itbicancela_classe.php");
include("classes/db_itbinumpre_classe.php");
include("classes/db_itbinome_classe.php");
include("classes/db_itbiavalia_classe.php");

$oGet   = db_utils::postmemory($_GET);

$clitbi	       = new cl_itbi();
$clitbicancela = new cl_itbicancela();
$clitbinumpre  = new cl_itbinumpre();
$clitbiavalia  = new cl_itbiavalia();
$clitbinome	   = new cl_itbinome();


$rsConsultaITBI = $clitbi->sql_record($clitbi->sql_query_dados($oGet->it01_guia)); 

  if ( $clitbi->numrows > 0 ) {
    $oDadosITBI = db_utils::fieldsMemory($rsConsultaITBI,0);    
  }else{
    db_msgbox("ITBI não encontrada!");  
    echo " <script> parent.db_iframe_consulta.hide(); </script>";
    exit;
  }

 if (  isset($oDadosITBI->it05_guia) && trim($oDadosITBI->it05_guia) != "" ) {
   $sTipo = "Urbano";
 } else {
   $sTipo = "Rural"; 	
 }
 
 if ( $oDadosITBI->it01_origem == 1) {
   $sOrigem = "DBPortal";
 } else {
   $sOrigem = "DBPref"; 	
 }
 
 
 $rsConsultaPag = $clitbiavalia->sql_record($clitbiavalia->sql_query_pag($oGet->it01_guia,"it24_valor,it14_valorpaga"));
 $iNumRowsPag   = $clitbiavalia->numrows;
 
 if ( $iNumRowsPag > 0 ) {
 	
 	 $nValorTotal = 0;
 	 
	 for ( $iInd=0; $iInd < $iNumRowsPag; $iInd++ ) {
	 	
	   $oDadosPag     = db_utils::fieldsMemory($rsConsultaPag,$iInd);
	   
	   $nValorTotal  += $oDadosPag->it24_valor;
	   $nValorApagar  = $oDadosPag->it14_valorpaga;
	 }
	
	 
 } else {
 	 $nValorApagar  = null;
 	 $nValorTotal   = null;
 }
 
 
 
 $sWhere   = " 	   it03_guia = {$oGet->it01_guia} ";
 $sWhere  .= " and it03_princ is true 			  "; 
 $rsConsultaNome = $clitbinome->sql_record($clitbinome->sql_query(null,"*",null,$sWhere));  
 $iNumRowsNome   = $clitbinome->numrows;
 
 $sNomeTransmitente = "";
 $sNomeAdquirente   = "";
 
 for ( $iInd=0; $iInd < $iNumRowsNome; $iInd++  ) {
 	
   $oDadosNome = db_utils::fieldsMemory($rsConsultaNome,$iInd);
 	
   if ( $oDadosNome->it03_tipo == "T" && $oDadosNome->it03_princ == "t") {
 	 $iCgmTransmitente  = $oDadosNome->it21_numcgm;
   	 $sNomeTransmitente = $oDadosNome->it03_nome;
   } else if ( $oDadosNome->it03_tipo == "C" && $oDadosNome->it03_princ == "t") {
   	 $iCgmAdquirente    = $oDadosNome->it21_numcgm;
   	 $sNomeAdquirente   = $oDadosNome->it03_nome;
   }
   
 	
 }
 

 $rsConsultaCancela = $clitbicancela->sql_record($clitbicancela->sql_query_file($oGet->it01_guia));
 
 if ( $clitbicancela->numrows > 0 ) {
 	 $lCancelada = true;
 } else {
 	 $lCancelada = false;
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

   for (i=0; i < lista.length; i++){

     if ( lista[i].className == 'selecionados' && lista[i].className != '') {
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
		  <legend><b>Dados da ITBI Guia - <?=$oGet->it01_guia?> <?=($lCancelada?"<font color='red'>- CANCELADA</font>":"")?></b></legend>
		    <table border='0'>
		      <tr>
		        <td><b>Tipo :</b>  				  	                            </td>
		        <td colspan="3" class='texto'><?=$sTipo?> 					    </td>
		      </tr>    
		      <tr>
		        <td><b>Tipo de Transação :</b></td>
		        <td 			class='texto'><?=$oDadosITBI->it01_tipotransacao?> 	</td>
		        <td colspan="2" class='texto'><?=$oDadosITBI->it04_descr?> 	       	</td>
		      </tr>
		      <? if ( $iNumRowsPag > 0 ) { ?>
		      <tr>
		        <td><b>Valor Total da Guia :</b>			</td>
		        <td  class='texto'><?=db_formatar($nValorTotal,"f")?> 	    </td>
				    <td><b>Valor Total a Pagar :</b>		    </td>        
		        <td  class='texto'><?=db_formatar($nValorApagar,"f")?>      	</td>
		      </tr>
		      <? } else { ?>
          <tr>
            <td><b>Valor Total da Guia :</b></td>
            <td  class='texto'></td>
            <td><b>Valor Total a Pagar :</b></td>        
            <td  class='texto'></td>
          </tr>		      
		      <? } ?>
		      <tr>
		        <td>
              <?
                db_ancora("<b>Transmitente :</b>","js_buscaCgm(".@$iCgmTransmitente.")",1);
              ?>
            </td>
		        <td class='texto' colspan="3">
		          <?=@$iCgmTransmitente?>&nbsp;-&nbsp;<?=$sNomeTransmitente?>
		        </td>
		      </tr>            
		      <tr>
		        <td>
            <?
             db_ancora("<b>Adquirente :</b>","js_buscaCgm(".@$iCgmAdquirente.")",1);
            ?>
            </td>
		        <td class='texto' colspan="3">
		          <?=@$iCgmAdquirente?>&nbsp;-&nbsp;<?=$sNomeAdquirente?> 
		        </td>        
		      </tr>
		      <tr>
		        <td><b>Email de Contato :</b>  				  	            </td>
		        <td colspan="3" class='texto'><?=$oDadosITBI->it01_mail?>   </td>
		      </tr>
		      <tr>
		        <td><b>Observação :</b>  				  	                </td>
		        <td colspan="3" class='texto'><?=$oDadosITBI->it01_obs?>    </td>
		      </tr>
		      <tr>
		        <td><b>Data de Inclusão :</b>			</td>
		        <td  class='texto'><?=db_formatar($oDadosITBI->it01_data,"d")?> </td>
				<td><b>Hora de Inclusão :</b>		    					    </td>        
		        <td  class='texto'><?=$oDadosITBI->it01_hora?>  		    	</td>
		      </tr>            
		      <tr>
		        <td><b>Origem :</b>			</td>
		        <td  class='texto'><?=$sOrigem?> </td>
				<td><b>Departamento :</b>		    						    </td>        
		        <td  class='texto'><?=$oDadosITBI->descrdepto?>  		    	</td>
		      </tr>      
		      <tr>
		        <td><b>Usuário :</b>	  		 </td>
		        <td  class='texto'><?=$oDadosITBI->nome?></td>
				<td><b>Tipo Usuário :</b>		    						        </td>        
		        <td  class='texto'><?=($oDadosITBI->usuext==1?"Externo":"Interno")?></td>
		      </tr>      
		    </table>
		  </fieldset>
		</td>
	  </tr>
	  <tr>
	    <td colspan='2'>
		  <fieldset>
		   <legend><b>Detalhamento : </b></legend>
		     <table width='100%'>
		       <tr>
		         <td width='20%' valign='top' height='100%' rowspan='2'>
					<? if ( $sTipo == "Urbano") { ?>		         
		           <a class='selecionados'	onclick='js_marca(this);this.blur()' href='itb4_consultadadosimovel001.php?guia=<?=$oGet->it01_guia;?>'      target='dados'><b> Dados do Imóvel  </b></a>
					<? } else { ?>
		           <a class='selecionados'	onclick='js_marca(this);this.blur()' href='itb4_consultadadosimovelrural001.php?guia=<?=$oGet->it01_guia;?>' target='dados'><b> Dados da Terra  </b></a>					  
					<? } ?>
	             <a class='dados'	onclick='js_marca(this);this.blur()' href='itb4_situacaoitbi001.php?guia=<?=$oGet->it01_guia;?>'                     target='dados'><b> Situação  	  	  </b></a>
		           <a class='dados'	onclick='js_marca(this);this.blur()' href='itb4_consultavaloresitbi001.php?guia=<?=$oGet->it01_guia;?>'              target='dados'><b> Valores Informados / Avaliados	  	  </b></a>
		           <a class='dados'	onclick='js_marca(this);this.blur()' href='itb4_consultaformaspgtoitbi001.php?guia=<?=$oGet->it01_guia;?>'           target='dados'><b> Formas de Pagamento Informado </b></a>
		           <a class='dados' onclick='js_marca(this);this.blur()' href='itb4_consultaformaspgtoavaliaitbi001.php?guia=<?=$oGet->it01_guia;?>'     target='dados'><b> Formas de Pagamento Avaliação </b></a>
  				     <a class='dados' onclick='js_marca(this);this.blur()' href='itb4_consultatransmitenteitbi001.php?guia=<?=$oGet->it01_guia;?>'         target='dados'><b> Transmitentes  </b></a>		           
  				     <a class='dados' onclick='js_marca(this);this.blur()' href='itb4_consultaadquirenteitbi001.php?guia=<?=$oGet->it01_guia;?>'           target='dados'><b> Adquirentes   </b></a>
   				     <a class='dados' onclick='js_marca(this);this.blur()' href='itb4_consultadadosbenfeitoriasl001.php?guia=<?=$oGet->it01_guia;?>'       target='dados'><b> Benfeitorias  </b></a>
   				     <a class='dados' onclick='js_marca(this);this.blur()' href='itb4_consultadadosalteracoes001.php?guia=<?=$oGet->it01_guia;?>'          target='dados'><b> Alterações Realizadas </b></a>
					<? if ( $sTipo == "Rural") { ?>
   				     <a class='dados' onclick='js_marca(this);this.blur()' href='itb4_consultacaracruralutil001.php?guia=<?=$oGet->it01_guia;?>'           target='dados'><b> Utilização da Terra  </b></a>					
   				     <a class='dados' onclick='js_marca(this);this.blur()' href='itb4_consultacaracruraldist001.php?guia=<?=$oGet->it01_guia;?>'           target='dados'><b> Distribuição da Terra  </b></a>
					<? } ?>		
               <a class='dados' onclick='js_marca(this);this.blur()' href='itb4_consultaguiaemitidas001.php?guia=<?=$oGet->it01_guia;?>'             target='dados'><b> Guias Emitidas  </b></a>
		        </td>
		         <td valign='top' height='100%' style='border:1px inset white'>
					<? if ( $sTipo == "Urbano") { ?>		         
		           <iframe height='300' name='dados' frameborder='0' width='100%' src='itb4_consultadadosimovel001.php?guia=<?=$oGet->it01_guia;?>' style='background-color:#CCCCCC'></iframe>
					<? } else { ?>
			       <iframe height='300' name='dados' frameborder='0' width='100%' src='itb4_consultadadosimovelrural001.php?guia=<?=$oGet->it01_guia;?>' style='background-color:#CCCCCC'></iframe>					
					<? } ?>		  		         
		         </td>
		       </tr>
		     </table>
		  </fieldset>
		</td>
	  </tr>
	  
</table>
<center>
  <input type='button' value='Voltar'  onclick='parent.db_iframe_consulta.hide()'>
</center>
</body>
</html>
<script>

  function js_buscaCgm(iCgm){
     js_OpenJanelaIframe('top.corpo','db_iframe_nome','prot3_conscgm002.php?fechar=db_iframe_nome&numcgm='+iCgm,'Pesquisa',true);
  }
  
</script>