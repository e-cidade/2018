<?php
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


// Débito tipo 6 - PARCELAMENTO DIVIDA ATIVA
//            13 - PARCELAMENTO DE INICIAL D. ATIVA
//            16 - PARCELAMENTO DIVERSO
//            17 - PARCELAMENTO DE CONTRIB. MELHORIA
//            30 - PARCELAMENTO DO FORO

$iInstituicao = db_getsession('DB_instit');

$inicial       = '';
$v01_proced    = "&nbsp;";
$k00_matric    = "&nbsp;";
$k00_inscr     = "&nbsp;";
$v01_exerc     = "&nbsp;";
$v03_descr     = "&nbsp;";
$certid        = "&nbsp;";
$aProcessoForo = array();

// 6 - PARCELAMENTO DIVIDA ATIVA
$sSqlParcelamentoDividaAtiva  = " select termo.v07_parcel                  ,                   ";
$sSqlParcelamentoDividaAtiva .= "        termo.v07_dtlanc                  ,                   ";
$sSqlParcelamentoDividaAtiva .= "        termo.v07_valor                   ,                   ";
$sSqlParcelamentoDividaAtiva .= "        termo.v07_numpre                  ,                   ";
$sSqlParcelamentoDividaAtiva .= "        termo.v07_totpar                  ,                   ";
$sSqlParcelamentoDividaAtiva .= "        termo.v07_vlrent                  ,                   ";
$sSqlParcelamentoDividaAtiva .= "        termo.v07_datpri                  ,                   ";
$sSqlParcelamentoDividaAtiva .= "        termo.v07_hist                    ,                   ";
$sSqlParcelamentoDividaAtiva .= "        cgm.z01_nome as nome_resp         ,                   ";
$sSqlParcelamentoDividaAtiva .= "        cgm.z01_nome                      ,                   ";
$sSqlParcelamentoDividaAtiva .= "        termoini.parcel                   ,                   ";
$sSqlParcelamentoDividaAtiva .= "        termoini.inicial                                      ";
$sSqlParcelamentoDividaAtiva .= " from termo                                                   ";
$sSqlParcelamentoDividaAtiva .= " left outer join cgm on cgm.z01_numcgm  = termo.v07_numcgm    ";
$sSqlParcelamentoDividaAtiva .= " left join termoini  on termoini.parcel = termo.v07_parcel    ";
$sSqlParcelamentoDividaAtiva .= " where     termo.v07_numpre   = {$numpre}                     ";
$sSqlParcelamentoDividaAtiva .= "       and termo.v07_instit   = {$iInstituicao}               ";

$rsParcelamentoDividaAtiva = db_query($sSqlParcelamentoDividaAtiva) or die($sSqlParcelamentoDividaAtiva);

if (pg_numrows($rsParcelamentoDividaAtiva) == 0) {
  echo "Código de Arrecadação não cadastrado.";
  exit;
} else {
  
  db_fieldsmemory($rsParcelamentoDividaAtiva, 0);
  
  if ($parcel > 0) {

    $v07_hist .= ($v07_hist != "" ? "<br>" : "") . "Inicia" . (pg_numrows($rsParcelamentoDividaAtiva) == 1 ? "l" : "is") . ":";
    
    for ($termoini = 0; $termoini < pg_numrows($rsParcelamentoDividaAtiva); $termoini++) {
      
      db_fieldsmemory($rsParcelamentoDividaAtiva, $termoini);
      $v07_hist .= $inicial . ($termoini == pg_numrows($rsParcelamentoDividaAtiva) - 1 ? "" : ",");
    
      $sSqlProcessoForo  = " select v70_codforo                                                                                   ";
      $sSqlProcessoForo .= " from processoforo                                                                                    ";
      $sSqlProcessoForo .= " inner join processoforoinicial on processoforoinicial.v71_processoforo = processoforo.v70_sequencial ";
      $sSqlProcessoForo .= " where processoforoinicial.v71_inicial = {$inicial}                                                   ";
      
      $rsProcessoForo = db_query($sSqlProcessoForo);
      
      $oDadosProcessoForo = db_utils::fieldsmemory($rsProcessoForo,0);
      
      if (!in_array($oDadosProcessoForo->v70_codforo, $aProcessoForo)) {
        $aProcessoForo[] = $oDadosProcessoForo->v70_codforo;
      }
  
    }
  
    $v70_codforo = implode(",", $aProcessoForo);
  
    $v07_hist .= "<br>Exercicios ajuizados:<br>";
  
    $sSqlOrigem  = "	select v51_certidao                             ,                        ";
    $sSqlOrigem .= "         array_accum(distinct v01_exerc) as exerc ,                        ";
    $sSqlOrigem .= "         k00_matric                               ,                        ";
    $sSqlOrigem .= "         k00_inscr                                ,                        ";
    $sSqlOrigem .= "         z01_nome                                                          ";
    $sSqlOrigem .= "  from termoini                                                            ";
    $sSqlOrigem .= "  inner join inicialcert      on inicial           = v51_inicial           ";
    $sSqlOrigem .= "  inner join certdiv          on v14_certid        = v51_certidao          ";
    $sSqlOrigem .= "  inner join divida           on v01_coddiv        = v14_coddiv            ";
    $sSqlOrigem .= "                             and v01_instit        = {$iInstituicao}       ";
    $sSqlOrigem .= "  left outer join cgm         on divida.v01_numcgm = cgm.z01_numcgm        ";
    $sSqlOrigem .= "  left outer join arrematric  on divida.v01_numpre = arrematric.k00_numpre ";
    $sSqlOrigem .= "  left outer join arreinscr   on divida.v01_numpre = arreinscr.k00_numpre  ";
    $sSqlOrigem .= "  where parcel = {$v07_parcel}                                             ";
    $sSqlOrigem .= "  group by v51_certidao,                                                   ";
    $sSqlOrigem .= "           k00_matric  ,                                                   ";
    $sSqlOrigem .= "           k00_inscr   ,                                                   ";
    $sSqlOrigem .= "           z01_nome                                                        ";
    $sSqlOrigem .= "  order by v51_certidao                                                    ";
    
    $rsOrigem = db_query($sSqlOrigem) or die($sSqlOrigem);
    
    if (pg_numrows($rsOrigem) > 0) {
      
      db_fieldsmemory($rsOrigem, 0);
      
      $cda        = $v51_certidao;
      $exercicios = "";
      $iCertidao  = "";
      
      for ($origem = 0; $origem < pg_numrows($rsOrigem); $origem++) {
        db_fieldsmemory($rsOrigem, $origem);
      
        if ($v51_certidao != $iCertidao) {
          $v07_hist .= "CDA: <strong>$v51_certidao</strong>: " . str_replace("{","",str_replace("}","",$exerc)) . "<br>";
          	
          $iCertidao = $v51_certidao;
        }
      	
      }
    }

  } else {
    
    $sSql  = " select distinct                                                ";
    $sSql .= "       d.v01_proced,                                            ";
    $sSql .= "       d.v01_exerc,                                             ";
    $sSql .= "       k00_matric,                                              ";
    $sSql .= "       k00_inscr,                                               ";
    $sSql .= "       z01_nome,                                                ";
    $sSql .= "       v03_descr                                                ";
    $sSql .= " from termodiv t                                                ";
    $sSql .= " inner join divida d          on d.v01_coddiv = t.coddiv        ";
    $sSql .= "                             and d.v01_instit = {$iInstituicao} ";
    $sSql .= " left outer join proced     p on p.v03_codigo = d.v01_proced    ";
    $sSql .= " left outer join cgm        c on d.v01_numcgm = c.z01_numcgm    ";
    $sSql .= " left outer join arrematric a on d.v01_numpre = a.k00_numpre    ";
    $sSql .= " left outer join arreinscr  i on d.v01_numpre = i.k00_numpre    ";
    $sSql .= " where t.parcel = {$v07_parcel}                                 ";
    
    if ($numpar != 0) {
      $sSql .= " and d.v01_numpar = $numpar ";
    }
    
    $rsResult = db_query($sSql);
    
    if (pg_numrows($rsResult) > 0) {
      db_fieldsmemory($rsResult, 0, true);
    } else {
    	
      $sSql  = " select k00_matric as j01_matric,                                   ";
      $sSql .= "        k00_inscr  as q02_inscr                                     ";
      $sSql .= " from termo                                                         ";     
      $sSql .= " left outer join arrematric on v07_numpre  = arrematric.k00_numpre  ";
      $sSql .= " left outer join arreinscr  on v07_numpre  = arreinscr.k00_numpre   ";
      $sSql .= " where v07_numpre = $numpre                                         ";
      $sSql .= "   and v07_instit =	{$iInstituicao}                                 ";
      
      $rsResult = db_query($sSql);
      
      if (pg_numrows($rsResult) > 0) {
        db_fieldsmemory($rsResult, 0, true);
      } else {
        
        $v01_proced = "&nbsp;" ;
        $k00_matric = "&nbsp;" ;
        $k00_inscr  = "&nbsp;" ;
        $v01_exerc  = "&nbsp;" ;
        $v03_descr  = "&nbsp;" ;
        
      }
    }
  }

  if ($inicial != '') {
    
    require_once('classes/db_processoforoinicial_classe.php');
    
    $clprocessoforoinicial = new cl_processoforoinicial();
    
    $sCampos  = " processoforoinicial.v71_inicial,     ";
    $sCampos .= " processoforoinicial.v71_data,        ";
    $sCampos .= " processoforoinicial.v71_id_usuario,  ";
    $sCampos .= " processoforo.v70_codforo,            ";
    $sCampos .= " processoforo.v70_vara                ";
    
    $sWhere  = " processoforoinicial.v71_inicial = {$inicial} and ";
    $sWhere .= " processoforoinicial.v71_anulado is false     and ";
    $sWhere .= " inicial.v50_situacao = 1                     and ";
    $sWhere .= " inicial.v50_instit = {$iInstituicao}             ";    
    
    $sSqlProcessoForoInicial = $clprocessoforoinicial->sql_query(null, $sCampos, "v71_inicial", $sWhere);
  
    $rsInicialForo = $clprocessoforoinicial->sql_record($sSqlProcessoForoInicial);
  
    db_fieldsmemory($rsInicialForo, 0);
  }
}

// 13 - PARCELAMENTO DE INICIAL D. ATIVA

if ($k03_tipo == 13) {

  $sSqlParcelamentoInicialDividaAtiva  = " select k00_matric as j01_matric,                                   ";
  $sSqlParcelamentoInicialDividaAtiva .= "        k00_inscr  as q02_inscr                                     ";
  $sSqlParcelamentoInicialDividaAtiva .= " from termo                                                         ";
  $sSqlParcelamentoInicialDividaAtiva .= " left outer join arrematric on v07_numpre = arrematric.k00_numpre   ";
  $sSqlParcelamentoInicialDividaAtiva .= " left outer join arreinscr  on v07_numpre = arreinscr.k00_numpre    ";
  $sSqlParcelamentoInicialDividaAtiva .= " where v07_numpre = $numpre and                                     ";
  $sSqlParcelamentoInicialDividaAtiva .= "       v07_instit = {$iInstituicao}                                 ";
  
  $rsParcelamentoInicialDividaAtiva = db_query($sSqlParcelamentoInicialDividaAtiva);
  
  if (pg_numrows($rsParcelamentoInicialDividaAtiva) > 0) {
    db_fieldsmemory($rsParcelamentoInicialDividaAtiva, 0, true);
  } else {
    
    $v01_proced = "&nbsp;";
    $k00_matric = "&nbsp;";
    $k00_inscr  = "&nbsp;";
    $v01_exerc  = "&nbsp;";
    $v03_descr  = "&nbsp;";
    
  }

  $sSql  = " select termo.v07_parcel         ,                         ";
  $sSql .= "        termo.v07_dtlanc         ,                         ";
  $sSql .= "        termo.v07_valor          ,                         ";
  $sSql .= "        termo.v07_numpre         ,                         ";
  $sSql .= "        termo.v07_totpar         ,                         ";
  $sSql .= "        termo.v07_vlrent         ,                         ";
  $sSql .= "        termo.v07_datpri         ,                         ";
  $sSql .= "        termo.v07_hist           ,                         ";
  $sSql .= "        c.z01_nome as nome_resp                            ";
  $sSql .= " from termo                                                ";
  $sSql .= " left outer join cgm c on c.z01_numcgm = termo.v07_numcgm  ";
  $sSql .= " where termo.v07_numpre = $numpre         and              ";
  $sSql .= "       termo.v07_instit = {$iInstituicao}                  ";
  
  $rsResult = db_query($sSql);
  
  db_fieldsmemory($rsResult, 0, true);
}

$oTermo = new cl_termoprotprocesso();

$sCampos  = "termoprotprocesso.v27_sequencial,  ";
$sCampos .= "termoprotprocesso.v27_termo,       ";
$sCampos .= "termoprotprocesso.v27_protprocesso ";

$sWhere = " termoprotprocesso.v27_termo = {$v07_parcel} ";

$sSqlBuscaProtocolo   = $oTermo->sql_query(null, $sCampos, null, $sWhere);
$rsSqlBuscaProtocolo  = db_query($sSqlBuscaProtocolo);
$oProtocolo           = db_utils::fieldsMemory($rsSqlBuscaProtocolo, null);

?>

<?php
if ($k03_tipo != 16) {
?>

<fieldset>
  <legend>Parcelamento</legend>
  
  <table class="linhaZebrada">
  
    <tr> 
      <td>C&oacute;digo do Parcelamento:</td>
      <td><?php echo $v07_parcel; ?></td>
    </tr>        
    <tr> 
      <td>Data Parcelamento:</td>
      <td><?php echo db_formatar($v07_dtlanc, 'd'); ?></td>
    </tr>
    <tr> 
      <td>Total Parcelas:</td>
      <td><?php echo $v07_totpar; ?></td>
    </tr>
    <tr> 
      <td>Valor Total Parcelado:</td>
      <td><?php echo db_formatar($v07_valor, 'f'); ?></td>
    </tr>
    <tr> 
      <td>Valor Entrada:</td>
      <td><?php echo db_formatar($v07_vlrent, 'f'); ?></td>
    </tr>
    <tr> 
      <td>Data Primeira Parcela:</td>
      <td><?php echo db_formatar($v07_datpri, 'd'); ?></td>
    </tr>
    <tr> 
      <td>Contribu&iacute;nte:</td>
      <td><?php echo $z01_nome; ?></td>
    </tr>
    <tr> 
      <td>Nome Respons&aacute;vel:</td>
      <td><?php echo $nome_resp; ?></td>
    </tr>
    
    <form name="form1" method="post">
      <tr> 
        <td>Termo:</td>
        <td> 
          <input type="button" name="Submit3" value="Visualizar o Termo" onclick="js_AbreJanelaRelatorio();"> 
          <input type="hidden" name="v07_parcel" value="<?php echo $v07_parcel; ?>">
          <?php
            $mostrabotao      = db_permissaomenu(db_getsession("DB_anousu"),81,2537);
            $mostrabotaoBySim = db_permissaomenu(db_getsession("DB_anousu"),81,8393);
            
            if ($mostrabotao == "true" || $mostrabotaoBySim == "true") {
            	if (@$mostra != "nao") {
          ?>
                <input type="button" name="anula" value="Simular Anulação de Parcelamento" onclick="js_anula();" > 
          <?php        	
             }
            }
          ?>
        </td>
      </tr>
    </form>
    
    <tr> 
      <td>C&oacute;digo Arrecada&ccedil;&atilde;o:</td>
      <td><?php echo $v07_numpre; ?></td>
    </tr>
    <tr> 
      <td>Hist&oacute;rico:</td>
      <td>
        <?php echo $v07_hist; ?> 
        <?php
          if ($oProtocolo->v27_protprocesso) {
            echo 'Protocolo: '.$oProtocolo->v27_protprocesso; 
          }
        ?>
      </td>
    </tr>
    <?php
      if(@$v70_codforo != '') {
    ?>
      <tr> 
        <td>C&oacute;digo do Processo do Foro:</td>
        <td><?php echo @$v70_codforo; ?></td>
      </tr>
    <?php
      }
    ?>
    <tr>    
    <td>Matr&iacute;cula Im&oacute;vel:</td>
    <td> 
    <?php
      if (pg_numrows($rsResult) != 0) {
      	for ($i = 0; $i < pg_numrows($rsResult); $i++) {
      		db_fieldsmemory($rsResult, $i, '1');
      		if ($k03_tipo == 13) {
      			echo $certid."<br>";
      		}
      		if ($k00_matric != "") {
      			echo "matric: $k00_matric - exerc: $v01_exerc " . ((int) $v01_proced == 0 ? "" : " - $v01_proced - $v03_descr") . " <br>";
      		}
      	}
      }
    ?>
    </td>
    </tr>
    <tr> 
    <td>Inscri&ccedil;&atilde;o Alvar&aacute;:</td>
    <td> 
    <?php
      if (pg_numrows($rsResult) != 0) {
      	for ($i = 0; $i < pg_numrows($rsResult); $i++) {
      		db_fieldsmemory($rsResult, $i, '1');
      		if ($k00_inscr != "") {
      			echo $k00_inscr."-".$v01_exerc."-".$v01_proced."<br>";
      		}
      	}
      }
    ?>
    </td>
    </tr>
  </table>

</fieldset>
<?php
}
?>

<?php

// 16 - PARCELAMENTO DIVERSO

if ($k03_tipo == 16) {
		
	$sSqlParcelamentoDiverso  = " select *                                                                    ";
	$sSqlParcelamentoDiverso .= " from  termodiver                                                            ";
	$sSqlParcelamentoDiverso .= " inner join termo             on v07_parcel            = dv10_parcel         ";
	$sSqlParcelamentoDiverso .= "                             and v07_instit            =	{$iInstituicao}     ";
	$sSqlParcelamentoDiverso .= " inner join cgm               on v07_numcgm            = z01_numcgm          ";
	$sSqlParcelamentoDiverso .= " inner join arrecad           on k00_numpre            = v07_numpre          ";
	$sSqlParcelamentoDiverso .= " inner join arreinstit        on arreinstit.k00_numpre = arrecad.k00_numpre  ";
	$sSqlParcelamentoDiverso .= "                             and arreinstit.k00_instit = {$iInstituicao}     ";
	$sSqlParcelamentoDiverso .= " left  outer join arrematric a on v07_numpre           = a.k00_numpre        ";
	$sSqlParcelamentoDiverso .= " left  outer join arreinscr  i on v07_numpre           = i.k00_numpre        ";
	$sSqlParcelamentoDiverso .= " where v07_numpre = {$numpre}                                                ";
	
	$rsParcelamentoDiverso = db_query($sSqlParcelamentoDiverso);
	
	if (pg_numrows($rsParcelamentoDiverso) == 0) {
		echo "Parcelamento não cadastrado no diversos."; 
		exit;
	} else {
		db_fieldsmemory($rsParcelamentoDiverso,0,'1');
	}
?>
	
	<fieldset>
	  <legend>Parcelamento Módulo Diversos</legend>
	  
		<table class="linhaZebrada">
		
  		<tr> 
    		<td>C&oacute;digo do Parcelamento:</td>
    		<td><?php echo $dv10_parcel; ?></td>
  		</tr>
  		<tr> 
    		<td>Data Parcelamento:</td>
    		<td><?php echo db_formatar($v07_dtlanc, 'd'); ?></td>
  		</tr>
  		<tr> 
    		<td>Total Parcelas:</td>
    		<td><?php echo $v07_totpar; ?></td>
  		</tr>
  		<tr> 
    		<td>Valor Total Parcelado:</td>
    		<td><?php echo db_formatar($vlrParcelamento, 'f'); ?></td>
  		</tr>
  		<tr> 
    		<td>Valor Entrada:</td>
    		<td><?php echo db_formatar($v07_vlrent, 'f'); ?></td>
  		</tr>
  		<tr> 
    		<td>Data Primeira Parcela:</td>
    		<td><?php echo db_formatar($v07_datpri, 'd'); ?></td>
  		</tr>
  		
  		<script>
    		function js_AbreJanelaRelatorio() {
    			window.open('div2_termoparc_002.php?parcel='+document.form1.v07_parcel.value,'','width=790,height=530,scrollbars=1,location=0');
    		}
    	</script>
  		
  		<form name="form1" method="post">
  		<tr>  		
    		<td>Termo:</td>  		
    		<td> 
      		<input type="button" name="Submit3" value="Visualizar o Termo" onclick="js_AbreJanelaRelatorio();"> 
      		<input type="hidden" name="v07_parcel" value="<?php echo $v07_parcel; ?>"> 
      		<?php
        		$mostrabotao      = db_permissaomenu(db_getsession("DB_anousu"),81,2537);
        		$mostrabotaoBySim = db_permissaomenu(db_getsession("DB_anousu"),81,8393);
        		
        		if ($mostrabotao == "true" || $mostrabotaoBySim == "true") {
        			if (@$mostra != "nao") {
          ?>
        	      	<input type="button" name="anula" value="Simular Anulação de Parcelamento" onclick="js_anula();" > 
        	<?php
        			}
        		}
      		?>
    		</td>  		
  		</tr>
  		</form>
  		
  		<tr> 
    		<td>Contribu&iacute;nte:</td>
    		<td><?php echo $z01_nome; ?></td>
  		</tr>
  		<tr> 
    		<td>Nome Respons&aacute;vel:</td>
    		<td><?php echo $z01_nome; ?></td>
  		</tr>
  		<tr> 
    		<td>C&oacute;digo Arrecada&ccedil;&atilde;o:</td>
    		<td><?php echo $k00_numpre; ?></td>
  		</tr>
  		<tr> 
    		<td>Matr&iacute;cula Im&oacute;vel:</td>
    		<td> 
      		<?php
      		if (@$k00_matric != "") {
      			echo $k00_matric;
      		}
      		?>
    		</td>
  		</tr>
  		<tr> 
    		<td>Inscri&ccedil;&atilde;o Alvar&aacute;:</td>
    		<td> 
      		<?php
      		if (@$k00_inscr != "") {
      			echo $k00_inscr;
      		}
      		?>
    		</td>
  		</tr>
  		
		</table>
		
	</fieldset>
	
<?php
	}
?>