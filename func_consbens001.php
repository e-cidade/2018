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
require("libs/db_liborcamento.php");
include("classes/db_bens_classe.php");
include("classes/db_bensmater_classe.php");
include("classes/db_bensimoveis_classe.php");
include("classes/db_bensbaix_classe.php");
include("classes/db_apolitem_classe.php");
include("classes/db_clabens_classe.php");
include("classes/db_cfpatri_classe.php");
include("classes/db_histbem_classe.php");
include("classes/db_bensdiv_classe.php");
include("classes/db_bensplaca_classe.php");
include("classes/db_cfpatriplaca_classe.php");
include("classes/db_db_departorg_classe.php");
include ("classes/db_benscedente_classe.php");
$clbenscedente  = new cl_benscedente;
$cldepartorg		= new cl_db_departorg;
$clbens         = new cl_bens;
$clbensmater    = new cl_bensmater;
$clbensimoveis  = new cl_bensimoveis;
$clbensbaix     = new cl_bensbaix;
$clapolitem     = new cl_apolitem;
$clrotulo       = new rotulocampo;
$clclabens      = new cl_clabens;
$clcfpatri      = new cl_cfpatri;
$clhistbem      = new cl_histbem;
$clbensdiv      = new cl_bensdiv;
$clbensplaca    = new cl_bensplaca;
$clcfpatriplaca = new cl_cfpatriplaca;

$clbens->rotulo->label();
$clbensmater->rotulo->label();
$clbensimoveis->rotulo->label();
$clbensplaca->rotulo->label();
$clbensbaix->rotulo->label();
$clrotulo->label("t64_class"); //classificação
$clrotulo->label("t64_descr"); //descrição da classificação
$clrotulo->label("descrdepto");//departamento
$clrotulo->label("t81_codapo");//código da apolice
$clrotulo->label("t81_apolice");//descrição da apólice
$clrotulo->label("z01_nome");  //fornecedor

db_postmemory($HTTP_POST_VARS);

if((isset($t52_bem) && trim($t52_bem)!="")||(isset($t52_ident) && trim($t52_ident)!="")){
  if(isset($t52_bem)){
    $pesquisa = " t52_bem=$t52_bem " ;
  }else{
    $pesquisa = " t52_ident='$t52_ident' ";
  }

  $res_cfpatriplaca = $clcfpatriplaca->sql_record($clcfpatriplaca->sql_query_file(db_getsession("DB_instit")));
  if ($clcfpatriplaca->numrows > 0){
       db_fieldsmemory($res_cfpatriplaca,0);
  }

  $result = $clbens->sql_record($clbens->sql_query(null,"*",""," $pesquisa and t52_instit = ".db_getsession("DB_instit")));
  if($clbens->numrows>0){   
    db_fieldsmemory($result,0);
  }else{
    db_redireciona("db_erros.php?fechar=true&db_erro=Bem $t52_bem não encontrado.");
  }
    $result_mater = $clbensmater->sql_record($clbensmater->sql_query_file($t52_bem));
    if($clbensmater->numrows>0){
      $bem_situac = "M";
      db_fieldsmemory($result_mater,0);
    }else{
      $result_imov = $clbensimoveis->sql_record($clbensimoveis->sql_query_file($t52_bem));
      if($clbensimoveis->numrows>0){
        $bem_situac = "I";
        db_fieldsmemory($result_imov,0);
      }else{
        $bemMI = "MATERIAL";
	$bem_situac = "NDA";
      }
    }
    $res_bensbaix = $clbensbaix->sql_record($clbensbaix->sql_query_file($t52_bem));
    if($clbensbaix->numrows>0){
      db_fieldsmemory($res_bensbaix,0);
      $baixado = " <font color='red'>BEM BAIXADO</font> ";
    }else{
      $baixado = "BEM NÃO BAIXADO";
    }
  $r_apolitem = $clapolitem->sql_record($clapolitem->sql_query(null,$t52_bem));
  $numrows = $clapolitem->numrows;
  if($numrows>0){
    $item_apolice = "S";
  }else{
    $item_apolice = "N";
  }
}

$result_estrutural = $clcfpatri->sql_record($clcfpatri->sql_query(null,"db77_estrut"));
if($clcfpatri->numrows>0){
  db_fieldsmemory($result_estrutural,0);
  $arr_estrut = split("\.","$db77_estrut");
  $arr_detonaestrut = array();
  $var = array();
  $val = array();
  $numcar = 0;
  for($i=0;$i<sizeof($arr_estrut);$i++){
    $arr_detonaestrut[$i] = strlen($arr_estrut[$i]);
    $numcar += $arr_detonaestrut[$i];
  }
  $variavel = "";
  $pos = 0;
  for($i=0;$i<sizeof($arr_detonaestrut);$i++){
    $var[$i] = "cla$i";
    $$var[$i]= substr($t64_class,$pos,$arr_detonaestrut[$i]);
    $variavel .= $$var[$i];
    $pesqestrut = str_pad($variavel,$numcar,'0',STR_PAD_RIGHT);

    $val[$i] = "t64_class$i";
    $$val[$i] = $pesqestrut;
    $pos += $arr_detonaestrut[$i];
  }
}


    /*
     *  Consulta o codemp e anousu, se o empenho for do sistema
     *  se nao exibe somento o numero do empenho
     */
    $sSqlEmpen = $clbensmater->sql_query_bensmater("","e60_codemp, e60_anousu, t53_empen","","t53_codbem = {$t52_bem}");
    $rsEmpen   = $clbensmater->sql_record($sSqlEmpen);
    if($clbensmater->numrows > 0){
      db_fieldsmemory($rsEmpen,0);
      if($e60_codemp != "" && $e60_anousu != ""){   
          $sEmpenho = $e60_codemp." / ".$e60_anousu ;   
        }else{
          $sEmpenho = $t53_empen;
      }   
    }

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
      <center>
      <table border="1" bordercolor="#000000" cellspacing="0" cellpadding="0" width="90%">
        <tr> 
	  <td><strong>Código:</strong></td>
	  <td align="center"><?=($t52_bem)?></td>
	  <td colspan="4"><?=($t52_descr)?></td>
	</tr>
        <tr>
	  <td><strong>Classificação:</strong></td>
	  <td colspan="2" align="center">
	    <?=($t64_class)?>&nbsp;&nbsp;(<?=($t64_descr)?>)
	  </td>
	  <td colspan="3">
	  <?
	    echo "
            <table>";
            $variavel = "";
            if(isset($arr_detonaestrut)){
              for($i=0;$i<sizeof($arr_detonaestrut);$i++){
                $result_clabens = $clclabens->sql_record($clclabens->sql_query_file(null,"t64_descr as t64_class$i","","t64_class = '".$$val[$i]."'"));
                if($clclabens->numrows > 0){
                  db_fieldsmemory($result_clabens,0);
                }else{
                  $$val[$i] = "Classificação não encontrada.";
                }
                echo "
                <tr>
                  <td align='left'>".$variavel.$$var[$i]."</td>
                  <td>".$$val[$i]."</td>
                </tr>";
                    $variavel .= $$var[$i];
              }
            }
            echo "
	    </table>";
	  ?>
	  </td>
	</tr>
	<? 
	$resPesqOrgaoUnidade = $cldepartorg->sql_record($cldepartorg->sql_query_orgunid($t52_depart,db_getsession('DB_anousu'),'o40_orgao,o40_descr,o41_unidade,o41_descr'));
	if($cldepartorg->numrows>0){
		db_fieldsmemory($resPesqOrgaoUnidade,0);
	}
	?>
	<tr> 
	  <td><strong>Órgão:</strong></td>
	  <td align="center"><?=($o40_orgao)?></td>
	  <td colspan="4"><?=($o40_descr)?></td>
	</tr>
	<tr> 
	  <td><strong>Unidade:</strong></td>
	  <td align="center"><?=($o41_unidade)?></td>
	  <td colspan="4"><?=($o41_descr)?></td>
	</tr>
  <tr> 
	  <td><strong>Departamento:</strong></td>
	  <td align="center"><?=($t52_depart)?></td>
	  <td colspan="4"><?=($descrdepto)?></td>
	</tr>
	<tr> 
	<?$result_divatual=$clbensdiv->sql_record($clbensdiv->sql_query($t52_bem));
	  if ($clbensdiv->numrows>0){
	  	db_fieldsmemory($result_divatual,0);
	  }
	 ?>
	  <td><strong>Divisão Depart.:</strong></td>
	  <td align="center"><?=(@$t30_codigo)?>&nbsp;</td>
	  <td colspan="4"><?=(@$t30_descr)?>&nbsp;</td>
	</tr>
  <tr> 
	  <td><strong>Fornecedor:</strong></td>
	  <td align="center"><?=($t52_numcgm)?></td>
	  <td colspan="4"><?=($z01_nome)?></td>
	</tr>
	<?
		//die($clbenscedente->sql_query(null,"cgm.z01_numcgm as z01_numcgm_convenio,cgm.z01_nome as z01_nome_convenio",null,"t09_bem = $t52_bem"));
		$rs_convenio = $clbenscedente->sql_record($clbenscedente->sql_query(null,"cgm.z01_numcgm as z01_numcgm_convenio,cgm.z01_nome as z01_nome_convenio",null,"t09_bem = $t52_bem"));
		$z01_nome_convenio 		= "&nbsp;";
		$z01_numcgm_convenio 	= "&nbsp;";
		if($clbenscedente->numrows > 0){
			db_fieldsmemory($rs_convenio,0);
		}
	?>
	<tr> 
	  <td><strong>Convênio:</strong></td>
	  <td align="center"><?=($z01_numcgm_convenio)?></td>
	  <td colspan="4"><?=($z01_nome_convenio)?></td>
	</tr>
	
		<?
		   if (strlen(trim($t52_ident)) > 0){
		        if ($t07_confplaca == 4){
		             $t52_ident = db_formatar($t52_ident,"s","0",$t07_digseqplaca,"e",0);
		        }
		   }        
		?>
	<tr>
	  <td><strong>Aquisição:</strong></td>
	  <td align="center"><?=($t52_dtaqu==""?"&nbsp;":(db_formatar($t52_dtaqu,"d")))?></td>
	  <td><strong>&nbsp;&nbsp;&nbsp;&nbsp;Valor:</strong></td>
	  <td align="center"><?=(db_formatar($t52_valaqu,"f"))?></td>
	  <td><strong>&nbsp;&nbsp;&nbsp;&nbsp;Placa ident:</strong></td>
	  <td align="center"><?=(strlen(trim($t52_ident)) > 0?$t52_ident:"NÃO INFORMADA")?></td>
	</tr>

<?
if($bem_situac == "M"){
?>
	<tr>
	  <td colspan="6" align="center"><br><br>
	    <strong>Dados do Material (<?=$baixado?>)</strong>
	  </td>
	</tr>
	<tr>
	  <td><strong>Nota Fiscal:</strong></td>
	  <td colspan="5"><?=($t53_ntfisc)?></td>
	</tr>
	<tr>
	  <td><strong>Empenho:</strong></td>
	  <td colspan="5"><? echo $sEmpenho;  //=($t53_empen==""?"&nbsp;":$t53_empen)?></td>
	</tr>
	<tr>
	  <td><strong>Ordem compra:</strong></td>
	  <td colspan="5"><?=($t53_ordem==""?"&nbsp;":$t53_ordem)?></td>
	</tr>
	<tr>
	  <td><strong>Garantia:</strong></td>
	  <td colspan="5"><?=$t53_garant==""?"&nbsp":(db_formatar($t53_garant,"d"))?></td>
	</tr>
<?
}else if($bem_situac == "I"){
?>
	<tr>
	  <td colspan="6" align="center"><br><br>
	    <strong>Dados do Imóvel (<?=$baixado?>)</strong>
	  </td>
	</tr>
	<tr>
	  <td><strong>Código do lote:</strong></td>
	  <td colspan="5"><?=($t54_idbql)?></td>
	</tr>
	<tr>
	  <td><strong>OBS:</strong></td>
	  <td colspan="5"><?=($t54_obs==""?"&nbsp;":substr($t54_obs,0,100))?></td>
	</tr>
<?
}else{
  $placaidentificacao = "Não";
  $sQueryPlaca  = " select t73_sequencial "; 
  $sQueryPlaca .= "   from bensplacaimpressa "; 
  $sQueryPlaca .= "        inner join bensplaca on bensplaca.t41_codigo = t73_bensplaca "; 
  $sQueryPlaca .= "  where t41_bem = $t52_bem";
          
  $rsQueryPlaca = db_query($sQueryPlaca);
          
  if(pg_num_rows($rsQueryPlaca) > 0){
    $placaidentificacao = "Sim";
  }
?>
	<tr>
	  <td colspan="1" align="left">
	    <strong>Definição do bem :</strong>
	  </td>
	
	  <td colspan="3" align="center"><?=($bemMI)?></td>	
	  <td colspan="2" align="center"><?=($baixado)?></td>
	</tr>
  <tr>
    <td colspan="1" align="left">
      <strong>Placa de Identificação :</strong>
    </td>
  
    <td colspan="3" align="center"><?=($placaidentificacao)?></td> 
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
  
<?
}

if ($opcao_obs == "S"){
     if (trim($t52_obs) != ""){
?>
  <tr>
    <td width="200" colspan="1" align="left" title="Características adicionais do bem"><b>Características adicionais do bem:</b></td>
    <td colspan="5" align="center" title=""><?=$t52_obs?></td> 
  </tr>
<?
     }
}

if (trim(@$t55_obs) != ""){
?>
        <tr>
	   <td colspan="1" align="left"><?=$Lt55_obs?></td>
	   <td colspan="5" align="left">&nbsp;&nbsp;<?=($t55_obs)?></td>	
        </tr>
<?
}
?>  
      </table>
<?
if($item_apolice == "S"){
?>
      <table align="center" width="90%">
        <tr>
          <td valign="top"  align="center" width="100%"><br>
            <iframe name="elementos" id="elementos"  marginwidth="0" marginheight="0" frameborder="0" src="func_consbens002.php?t82_codbem=<?=$t52_bem?>" width="740" height="150">
          </td>
        </tr>
      </table>
<?
}else{
?> 
      <table width="90%">
        <tr>
          <td valign="top"  align="center">
            <br>
            <b> Bem não cadastrado em apólices. </b>
          </td>
        </tr>
      </table>
<?
}
?> 
      <table width="100%" border='1' cellspacing="0" cellpadding="0" align ="center" >   
        <tr>
          <td colspan='6' align='center' nowrap ><b> Histórico do bem </b></td>
	</tr>
	<tr>
	  <td nowrap bgcolor='#CDCDFF' align='center' title='Data da confirmação da  última transferência'><b>Data confirmação        </b></td>
	  <td nowrap bgcolor='#CDCDFF' align='center' title='Departamento de origem'                      ><b>Departamento origem     </b></td>
	  <td nowrap bgcolor='#CDCDFF' align='center' title='Departamento de destino'                     ><b>Departamento destino    </b></td>
	  <td nowrap bgcolor='#CDCDFF' align='center' title='Divisão de destino'                     ><b>Divisão destino    </b></td>
	  <td nowrap bgcolor='#CDCDFF' align='center' title='Histórico do bem'                            ><b>Histórico               </b></td>
	  <td nowrap bgcolor='#CDCDFF' align='center' title='Situação atual do bem'                       ><b>Situação do bem         </b></td>
	</tr>
      <?
        $cor1='#97B5E6';
	$cor2='#E796A4';
        if(isset($t52_bem) && trim($t52_bem) != ''){	 
//	  die($clhistbem->sql_query(null,"*","t56_histbem"," t56_codbem =$t52_bem "));
	  $result_histbem = $clhistbem->sql_record($clhistbem->sql_query_div(null,"*","t56_histbem"," t56_codbem =$t52_bem "));
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
          echo "<td align='left' nowrap bgcolor=\"$cor\"> $t30_descr &nbsp;</td>";
	      
	      if(isset($t56_histor) && $t56_histor != ""){
            if (isset($t97_codtran)&&trim($t97_codtran)!=""){
                 $t56_historico = "TRANSFERÊNCIA CONFIRMADA... CÓD. ".$t97_codtran."<br>"; 
            }
		        if(strlen($t56_histor)>15){
          		  @$t56_historico .= substr($t56_histor,0,15);
        		}else{
          		  @$t56_historico .= $t56_histor;
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
	  }
	}
      ?>
</table>

      <!-- MOSTRA TRANSFERÊNCIAS PENDENTES DO BEM -->
      <table width="100%" border='1' cellspacing="0" cellpadding="0" align ="center" >   
<?

    if (isset($t52_bem)&&trim($t52_bem)!=""){
         $cor1='#97B5E6';
         $cor2='#E796A4';
    
	       $result_bens = $clbens->sql_record($clbens->sql_query_histbem(null,"t93_codtran,t93_data,descrdepto as depto_transf,t30_descr as div_transf,t70_descr","t95_codtran","t52_bem = $t52_bem and t97_histbem is null"));
//	       echo($clbens->sql_query_histbem(null,"t93_codtran,t93_data,descrdepto as depto_transf,t30_descr as div_transf,t70_descr","t95_codtran","t52_bem = $t52_bem and t97_histbem is null"));
         
         if ($clbens->numrows > 0){
              $numrows = $clbens->numrows;
?>
        <tr>
          <td colspan='6' align='center' nowrap ><b> Transferências pendentes do bem </b></td>
        </tr>
	      <tr>
      	  <td nowrap bgcolor='#CDCDFF' align='center' title='Data da inclusão da última transferência'><b>Data inclusão       </b></td>
      	  <td nowrap bgcolor='#CDCDFF' align='center' title='Departamento de origem'                  ><b>Departamento origem </b></td>
      	  <td nowrap bgcolor='#CDCDFF' align='center' title='Departamento de destino'                 ><b>Departamento destino</b></td>
      	  <td nowrap bgcolor='#CDCDFF' align='center' title='Divisão de destino'                      ><b>Divisão destino     </b></td>
      	  <td nowrap bgcolor='#CDCDFF' align='center' title='Histórico do bem'                        ><b>Histórico           </b></td>
      	  <td nowrap bgcolor='#CDCDFF' align='center' title='Situação atual do bem'                   ><b>Situação do bem     </b></td>
        </tr>
<?
              for($i=0; $i < $numrows; $i++){
                   db_fieldsmemory($result_bens,$i);
?>
        <tr> 
          <td align="center" nowrap bgcolor="<?=$cor?>"><? echo db_formatar($t93_data,"d") ?></td>
          <td align="left"   nowrap bgcolor="<?=$cor?>">TRANSFERÊNCIA PENDENTE</td>
          <td align="left"   nowrap bgcolor="<?=$cor?>"><?=$depto_transf?></td>
          <td align="left"   nowrap bgcolor="<?=$cor?>"><?=$div_transf?></td>
          <td align="left"   nowrap bgcolor="<?=$cor?>">TRANSFERÊNCIA PENDENTE... CÓD. <?=$t93_codtran?></td>
          <td align="left"   nowrap bgcolor="<?=$cor?>"><?=$t70_descr?></td>
        </tr>
<?
              }
         } else {
?>
	      <tr>
		      <td colspan='6' nowrap bgcolor=\"$cor1\" align='center'><b> Não existem transferências pendentes para este bem </b></td>
		    </tr>
<?
         }
     }
?>
      </table>  
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

      </center>
    </td>
  </tr>
</table>
</body>
</html>