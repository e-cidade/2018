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
include("classes/db_histbem_classe.php");
include("classes/db_benstransfcodigo_classe.php");
include("classes/db_benstransfconf_classe.php");
include("classes/db_bensplaca_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_cfpatriplaca_classe.php");

$clhistbem      = new cl_histbem;
$clbensplaca    = new cl_bensplaca;
$clcfpatriplaca = new cl_cfpatriplaca;

$clbensplaca->rotulo->label();

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
// echo $t52_bem;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<tr> 
    <td align="center" valign="top" bgcolor="#CCCCCC">
      <table width="100%" border='1' cellspacing="0" cellpadding="0" align ="center" >   
        <tr>
          <td colspan='6' align='center' nowrap ><b> Histórico do bem </b></td>
	</tr>
	<tr>
	  <td nowrap bgcolor='#CDCDFF' align='center' title='Data da confirmação da  última transferência'><b>Data confirmação        </b></td>
	  <td nowrap bgcolor='#CDCDFF' align='center' title='Departamento de origem'                      ><b>Departamento origem     </b></td>
	  <td nowrap bgcolor='#CDCDFF' align='center' title='Departamento de destino'                     ><b>Departamento destino    </b></td>
	  <td nowrap bgcolor='#CDCDFF' align='center' title='Histórico do bem'                            ><b>Histórico               </b></td>
	  <td nowrap bgcolor='#CDCDFF' align='center' title='Situação atual do bem'                       ><b>Situação do bem         </b></td>
	</tr>
      <?
        $cor1='#97B5E6';
	$cor2='#E796A4';
        if(isset($t52_bem) && trim($t52_bem) != ''){	 

  $res_cfpatriplaca = $clcfpatriplaca->sql_record($clcfpatriplaca->sql_query_file(db_getsession("DB_instit")));
  if ($clcfpatriplaca->numrows > 0){
       db_fieldsmemory($res_cfpatriplaca,0);
  }

//	  die($clhistbem->sql_query(null,"*","t56_histbem"," t56_codbem =$t52_bem "));
	  $result_histbem = $clhistbem->sql_record($clhistbem->sql_query(null,"*","t56_histbem"," t56_codbem =$t52_bem "));
	  if($clhistbem->numrows>0){
	    $numrows = $clhistbem->numrows;	    
	    for($i=0;$i<$numrows;$i++){	      
              if(isset($cor)){
	        $cor = $cor==$cor1?$cor2:$cor1;	
	      }else{
	        $cor = $cor1;
              }
	      echo "
	      </tr>";
              db_fieldsmemory($result_histbem,$i);
	      
	      echo "
		<td align='center' nowrap bgcolor=\"$cor\">".db_formatar($t56_data,"d")."</td>";
	      if($i==0){
	      echo "
		<td align='left' nowrap bgcolor=\"$cor\"> $t56_histor  </td>";
	      }else{
	      echo "
		<td align='left' nowrap bgcolor=\"$cor\"> $depto_origem </td>";
	      }
	      echo "
	      <td align='left' nowrap bgcolor=\"$cor\"> $descrdepto </td>";
              $depto_origem = $descrdepto;
	      
	      if(isset($t56_histor) && $t56_histor != ""){
		if(strlen($t56_histor)>15){
		  $t56_historico = substr($t56_histor,0,15);
		}else{
		  $t56_historico = $t56_histor;
		}
	      echo "
		<td align='left' nowrap bgcolor=\"$cor\" title='$t56_histor'> $t56_historico... </td>";
              }else{
	      echo "
		<td align='center' nowrap bgcolor=\"$cor\" title='Não informado'> --- </td>";
	      }
	      echo "
		<td align='left' nowrap bgcolor=\"$cor\"> $t70_descr </td>
	      </tr>
	       ";
	    }
	  }else{
	    echo "<tr>
		    <td colspan='6' nowrap bgcolor=\"$cor1\" align='center'><b> Não existem transferências para este bem </b></td>
		  </tr>";
	  }
	}
      ?>
      </table>
    </td>
  </tr>
  <tr>
     <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="top" bgcolor="#CCCCCC">
					<!--MOSTRA AS ALTERAÇÕES DA PLACA PARA O BEM-->
<table width="100%" border='1' cellspacing="0" cellpadding="0" align ="center" >   
	<tr>
    	<td colspan='6' align='center' nowrap ><b> Histórico da Placa </b></td>
	</tr>
	<tr>
	  	<td nowrap bgcolor='#CDCDFF' align='center' title='Data '><b>Data</b></td>	  	
	  	<td nowrap bgcolor='#CDCDFF' align='center' title='Hora '><b>Usuário</b></td>
	  	<td nowrap bgcolor='#CDCDFF' align='center' title="<?=$RLt41_placa?>"><b><?=$RLt41_placa?></b></td>
	  	<td nowrap bgcolor='#CDCDFF' align='center' title="<?=$RLt41_placaseq?>"><b><?=$RLt41_placaseq?></b></td>
	  	<td nowrap bgcolor='#CDCDFF' align='center' title="<?=$RLt41_obs?>"><b><?=$RLt41_obs?></b></td>	  	
	</tr>
<?
$cor1='#97B5E6';
$cor2='#E796A4';
if(isset($t52_bem) && trim($t52_bem) != ''){	
        // echo $clbensplaca->sql_query(null,"*","t41_codigo"," t41_bem = $t52_bem ");
	$result_bensplaca = $clbensplaca->sql_record($clbensplaca->sql_query(null,"*","t41_codigo"," t41_bem = $t52_bem "));
	if($clbensplaca->numrows>0){
		$numrows = $clbensplaca->numrows;	    
	    for($i=0;$i<$numrows;$i++){	      
         	db_fieldsmemory($result_bensplaca,$i);

          if ($t07_confplaca == 4){
               if (strlen(trim(@$t41_placa)) > 0){
                    $t41_placa = db_formatar($t41_placa,"s","0",$t07_digseqplaca,"e",0);
               }

               $t41_placaseq = db_formatar($t41_placaseq,"s","0",$t07_digseqplaca,"e",0);
          }

        	if(isset($cor)){
	        	$cor = $cor==$cor1?$cor2:$cor1;	
	      	}else{
	        	$cor = $cor1;
            }
	     	echo "</tr>";
	     	echo "<td align='center' nowrap bgcolor=\"$cor\">".db_formatar($t41_data,"d")."</td>";	     	
	     	echo "<td align='left' nowrap bgcolor=\"$cor\"> $nome </td>";
	     	echo "<td align='left' nowrap bgcolor=\"$cor\"> $t41_placa &nbsp</td>";
	     	echo "<td align='left' nowrap bgcolor=\"$cor\"> $t41_placaseq &nbsp </td>";
	     	echo "<td align='left' nowrap bgcolor=\"$cor\"> $t41_obs &nbsp </td>";
         	}
	}else{
		echo "<tr><td colspan='6' nowrap bgcolor=\"$cor1\" align='center'><b> Não existem alterações na placa para este bem </b></td></tr>";
	}
}
?>
</table>      

     </td>
  </tr>   
</body>
</html>