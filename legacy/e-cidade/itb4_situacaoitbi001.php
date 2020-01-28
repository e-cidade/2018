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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("classes/db_itbi_classe.php");
require_once("classes/db_itbinumpre_classe.php");
require_once("classes/db_itbiavalia_classe.php");

$oGet   			= db_utils::postmemory($_GET);
$clitbi 	    = new cl_itbi();
$clitbinumpre = new cl_itbinumpre();
$clitbiavalia = new cl_itbiavalia();
?>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
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
<body class="body-default">
<table style="padding-top:15px;"height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr align="center" valign="top">
 	  <td>
	    <table>
	      <?php

	        $rsDadosAvalia = $clitbiavalia->sql_record($clitbiavalia->sql_query($oGet->guia));
	        if ( $clitbiavalia->numrows > 0 ) {

	          $oDadosAvalia = db_utils::fieldsMemory($rsDadosAvalia,0);
	      ?>
	      <tr>
	        <td><strong>Liberada :</strong>		</td>
		      <td  class='texto'>Sim	    </td>
			    <td><strong>Data :</strong>		    </td>
		      <td  class='texto'><?=db_formatar($oDadosAvalia->it14_dtliber,"d")?> 	</td>
		    </tr>
		    <tr>
		      <td><strong>Usuário :</strong>			</td>
		      <td  class='texto'><?=$oDadosAvalia->nome?>      </td>
		      <td><strong>Hora :</strong>		   				         </td>
		      <td  class='texto'><?=$oDadosAvalia->it14_hora?> </td>
		    </tr>
		    <tr>
		      <td><strong>Observação Avaliação :</strong>  				            </td>
		      <td colspan="3" class='texto'><?=(isset($oDadosITBI->it14_obs)?"$oDadosITBI->it14_obs":"")?>    </td>
		    </tr>
		    <?php
		      } else {
		    ?>
		    <tr>
		      <td><strong>Liberada :</strong>	     </td>
		      <td  class='texto'><strong>Não</strong></td>
		    </tr>
	      <?php

	        }
		      $oGuiaPaga          = new stdClass();
		      $oGuiaAberto        = new stdClass();
		      $oGuiaSemIncidencia = new stdClass();

		      $sCampos  = " it15_numpre, 		                                 ";
	        $sCampos .= " recibo.k00_dtvenc,it01_data,	                   ";
	        $sCampos .= " recibo.k00_valor, 	                             ";
	        $sCampos .= " arrepaga.k00_dtpaga, 						             		 ";
	        $sCampos .= " arrepaga.k00_conta, 				                     ";
	        $sCampos .= " case 													                   ";
	        $sCampos .= "   when k00_dtpaga is not null then 'Pago' else   ";
	        $sCampos .= "   	 case                   										 ";
	        $sCampos .= "   	 	 when recibo.k00_valor > 0 then 'Em Aberto'";
	        $sCampos .= "   	 	 else 'Não Incide'			              		 ";
	        $sCampos .= "   	 end						                     	       ";
	        $sCampos .= " end as situacao                			             ";
	        $rsDadosEmite = $clitbinumpre->sql_record($clitbinumpre->sql_query_recibo("",$sCampos,"it15_numpre asc"," it15_guia = {$oGet->guia}"));
	        $aGuiasEncontradas = db_utils::getCollectionByRecord($rsDadosEmite);

	        $iPagas   = 0;
	        $iAbertas = 0;
	        $iIncide  = 0;
	        foreach ($aGuiasEncontradas as $oGuiasEmitidas) {

	          if ($oGuiasEmitidas->situacao =='Pago'){
	           
	            $oGuiaPaga          = $oGuiasEmitidas;
	            $iPagas            += 1;
            } elseif ($oGuiasEmitidas->situacao == 'Em Aberto') {
	            
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

	      ?>
        <tr>
	  	    <td><strong>Emitida :</strong>		</td>
		      <td  class='texto'>Sim	    </td>
		      <td><strong>Numpre  :</strong>	    </td>
		      <td  class='texto'><?=$oDadosEmite->it15_numpre?></td>
	      </tr>
	      <tr>
	        <td><strong>Data de Vencimento :</strong>	</td>
		      <td  class='texto'><?=db_formatar($oDadosEmite->k00_dtvenc,"d")?></td>
			    <td><strong>Valor a Pagar :</strong>	    </td>
		      <td  class='texto'><?=db_formatar($oDadosEmite->k00_valor,"f") ?></td>
		    </tr>
		    <tr>
		      <td><strong>Situação do Recibo :</strong>	</td>
		      <td  class='texto'><?=$oDadosEmite->situacao?></td>
		   	  <td><strong>Data			 :</strong>	    </td>
		      <td  class='texto'><?=db_formatar($oDadosEmite->k00_dtpaga,"d")?></td>
		    </tr>
		    <tr>
		      <td><strong>Conta :</strong>	</td>
		      <td  colspan="3" class='texto'><?=$oDadosEmite->k00_conta?> </td>
		    </tr>
		    <?php
		      } else {
		    ?>
		    <tr>
		      <td><strong>Emitida :</strong>   	     </td>
		      <td  class='texto'><strong>Não</strong></td>
		    </tr>
		    <?php
		      }
		    ?>
      </table>
    </td>
  </tr>
</table>
</body>
</html>