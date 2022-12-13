<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_itbi_classe.php");
require_once("classes/db_itbicancela_classe.php");
require_once("classes/db_itbinumpre_classe.php");
require_once("classes/db_itbinome_classe.php");
require_once("classes/db_itbiavalia_classe.php");

$oGet   = db_utils::postmemory($_GET);

$clitbi	       = new cl_itbi();
$clitbicancela = new cl_itbicancela();
$clitbinumpre  = new cl_itbinumpre();
$clitbiavalia  = new cl_itbiavalia();
$clitbinome	   = new cl_itbinome();

$rsConsultaITBI = $clitbi->sql_record($clitbi->sql_query_dados($oGet->it01_guia)); 

  if ( $clitbi->numrows > 0 ) {

    $oDadosITBI = db_utils::fieldsMemory($rsConsultaITBI,0);    
  } else {

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
<body class="body-default">
  <table width='100%' cellspacing=0>
    <tr>
      <td colspan='2'>
	      <fieldset>
	        <legend><strong>Dados da ITBI Guia - <?=$oGet->it01_guia?> <?=($lCancelada?"<font color='red'>- CANCELADA</font>":"")?></strong></legend>
	        <table border='0'>
	      	  <tr>
	      	    <td><strong>Tipo :</strong>  				  	                            </td>
	      	    <td colspan="3" class='texto'><?=$sTipo?> 					    </td>
	      	  </tr>    
	      	  <tr>
	      	    <td><strong>Tipo de Transação :</strong></td>
	      	    <td 			class='texto'><?=$oDadosITBI->it01_tipotransacao?> 	</td>
	      	    <td colspan="2" class='texto'><?=$oDadosITBI->it04_descr?> 	       	</td>
	      	  </tr>
	      	  <?php if ( $iNumRowsPag > 0 ) { ?>
	      	  <tr>
	      	    <td><strong>Valor Total da Guia :</strong>			</td>
	      	    <td  class='texto'><?=db_formatar($nValorTotal,"f")?> 	    </td>
	      	  <td><strong>Valor Total a Pagar :</strong>		    </td>        
	      	    <td  class='texto'><?=db_formatar($nValorApagar,"f")?>      	</td>
	      	  </tr>
	      	  <?php } else { ?>
            <tr>
              <td><strong>Valor Total da Guia :</strong></td>
              <td  class='texto'></td>
              <td><strong>Valor Total a Pagar :</strong></td>        
              <td  class='texto'></td>
            </tr>		      
	      	  <?php } ?>
	      	  <tr>
	      	    <td>
                <?php
                  db_ancora("<strong>Transmitente :</strong>","js_buscaCgm(".@$iCgmTransmitente.")",1);
                ?>
              </td>
		          <td class='texto' colspan="3">
		            <?php echo @$iCgmTransmitente?>&nbsp;-&nbsp;<?php echo $sNomeTransmitente?>
		          </td>
		        </tr>            
		        <tr>
		          <td>
                <?php
                 db_ancora("<strong>Adquirente :</strong>","js_buscaCgm(".@$iCgmAdquirente.")",1);
                ?>
              </td>
		          <td class='texto' colspan="3">
		            <?=@$iCgmAdquirente?>&nbsp;-&nbsp;<?=$sNomeAdquirente?> 
		          </td>        
		        </tr>
		        <tr>
		          <td><strong>Email de Contato :</strong>  				  	            </td>
		          <td colspan="3" class='texto'><?=$oDadosITBI->it01_mail?>   </td>
		        </tr>
		        <tr>
		          <td><strong>Observação :</strong>  				  	                </td>
		          <td colspan="3" class='texto'><?=$oDadosITBI->it01_obs?>    </td>
		        </tr>
		        <tr>
		          <td><strong>Data de Inclusão :</strong>			</td>
		          <td  class='texto'><?=db_formatar($oDadosITBI->it01_data,"d")?> </td>
			        <td><strong>Hora de Inclusão :</strong>		    					    </td>        
		          <td  class='texto'><?=$oDadosITBI->it01_hora?>  		    	</td>
		        </tr>            
		        <tr>
		          <td><strong>Origem :</strong>			</td>
		          <td  class='texto'><?=$sOrigem?> </td>
			        <td><strong>Departamento :</strong>		    						    </td>        
		          <td  class='texto'><?=$oDadosITBI->descrdepto?>  		    	</td>
		        </tr>      
		        <tr>
	      	    <td><strong>Usuário :</strong>	  		 </td>
	      	    <td  class='texto'><?=$oDadosITBI->nome?></td>
	      		  <td><strong>Tipo Usuário :</strong>		    						        </td>        
	      	    <td  class='texto'><?=($oDadosITBI->usuext==1?"Externo":"Interno")?></td>
	      	  </tr>      
	      	</table>
	      </fieldset>
		  </td>
	  </tr>
	  <tr>
	    <td colspan='2'>
	      <fieldset>
	        <legend><strong>Detalhamento : </strong></legend>
	        <table width='100%'>
	          <tr>
	            <td width='20%' valign='top' height='100%' rowspan='2'>
	      	      <?php if ( $sTipo == "Urbano") { ?>		         
	                     <a class='selecionados'	onclick='js_marca(this);this.blur()' href='itb4_consultadadosimovel001.php?guia=<?=$oGet->it01_guia;?>'      target='dados'><strong> Dados do Imóvel  </strong></a>
	      	      <?php } else { ?>
	                     <a class='selecionados'	onclick='js_marca(this);this.blur()' href='itb4_consultadadosimovelrural001.php?guia=<?=$oGet->it01_guia;?>' target='dados'><strong> Dados da Terra  </strong></a>					  
	      	      <?php } ?>
	              <a class='dados'	onclick='js_marca(this);this.blur()' href='itb4_situacaoitbi001.php?guia=<?=$oGet->it01_guia;?>'                     target='dados'><strong> Situação  	  	  </strong></a>
	              <a class='dados'	onclick='js_marca(this);this.blur()' href='itb4_consultavaloresitbi001.php?guia=<?=$oGet->it01_guia;?>'              target='dados'><strong> Valores Informados / Avaliados	  	  </strong></a>
	              <a class='dados'	onclick='js_marca(this);this.blur()' href='itb4_consultaformaspgtoitbi001.php?guia=<?=$oGet->it01_guia;?>'           target='dados'><strong> Formas de Pagamento Informado </strong></a>
	              <a class='dados' onclick='js_marca(this);this.blur()' href='itb4_consultaformaspgtoavaliaitbi001.php?guia=<?=$oGet->it01_guia;?>'     target='dados'><strong> Formas de Pagamento Avaliação </strong></a>
        	      <a class='dados' onclick='js_marca(this);this.blur()' href='itb4_consultatransmitenteitbi001.php?guia=<?=$oGet->it01_guia;?>'         target='dados'><strong> Transmitentes  </strong></a>		           
        	      <a class='dados' onclick='js_marca(this);this.blur()' href='itb4_consultaadquirenteitbi001.php?guia=<?=$oGet->it01_guia;?>'           target='dados'><strong> Adquirentes   </strong></a>
        		    <a class='dados' onclick='js_marca(this);this.blur()' href='itb4_consultadadosbenfeitoriasl001.php?guia=<?=$oGet->it01_guia;?>'       target='dados'><strong> Benfeitorias  </strong></a>
        		    <a class='dados' onclick='js_marca(this);this.blur()' href='itb4_consultadadosalteracoes001.php?guia=<?=$oGet->it01_guia;?>'          target='dados'><strong> Alterações Realizadas </strong></a>
	      	      <?php if ( $sTipo == "Rural") { ?>
        		      <a class='dados' onclick='js_marca(this);this.blur()' href='itb4_consultacaracruralutil001.php?guia=<?=$oGet->it01_guia;?>'           target='dados'><strong> Utilização da Terra  </strong></a>					
        		      <a class='dados' onclick='js_marca(this);this.blur()' href='itb4_consultacaracruraldist001.php?guia=<?=$oGet->it01_guia;?>'           target='dados'><strong> Distribuição da Terra  </strong></a>
	      	      <?php } ?>		
                  <a class='dados' onclick='js_marca(this);this.blur()' href='itb4_consultaguiaemitidas001.php?guia=<?=$oGet->it01_guia;?>'             target='dados'><strong> Guias Emitidas  </strong></a>
                  <a class='dados' onclick='js_marca(this);this.blur()' href='itb4_consultacancelamento001.php?guia=<?=$oGet->it01_guia;?>'          target='dados'><strong> Cancelamento </strong></a>
	            </td>
	            <td valign='top' height='100%' style='border:1px inset white'>
	      	      <?php if ( $sTipo == "Urbano") { ?>		         
	                <iframe height='300' name='dados' frameborder='0' width='100%' src='itb4_consultadadosimovel001.php?guia=<?=$oGet->it01_guia;?>' style='background-color:#CCCCCC'></iframe>
	      	      <?php } else { ?>
	      	        <iframe height='300' name='dados' frameborder='0' width='100%' src='itb4_consultadadosimovelrural001.php?guia=<?=$oGet->it01_guia;?>' style='background-color:#CCCCCC'></iframe>					
	      	      <?php } ?>		  		         
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

  function js_buscaCgm(iCgm) {

     js_OpenJanelaIframe('top.corpo','db_iframe_nome','prot3_conscgm002.php?fechar=db_iframe_nome&numcgm='+iCgm,'Pesquisa',true);
  }
</script> 