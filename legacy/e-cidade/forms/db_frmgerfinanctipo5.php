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


// 5 - DÍVIDA ATIVA

$sSqlNumpar = ($numpar != 0 ? " and divida.v01_numpar = {$numpar}" : "");

$iInstituicao = db_getsession('DB_instit');

$sSqlDividaAtiva  = " select         divida.v01_coddiv              ,                                ";
$sSqlDividaAtiva .= "                divida.v01_dtinsc              ,                                ";
$sSqlDividaAtiva .= "                divida.v01_exerc               ,                                ";
$sSqlDividaAtiva .= "                divida.v01_vlrhis              ,                                ";
$sSqlDividaAtiva .= "                divida.v01_proced              ,                                ";
$sSqlDividaAtiva .= "                divida.v01_livro               ,                                ";
$sSqlDividaAtiva .= "                divida.v01_folha               ,                                ";
$sSqlDividaAtiva .= "                divida.v01_dtvenc              ,                                ";
$sSqlDividaAtiva .= "                divida.v01_dtoper              ,                                ";
$sSqlDividaAtiva .= "                divida.v01_obs                 ,                                ";
$sSqlDividaAtiva .= "                proced.v03_descr               ,                                ";  
$sSqlDividaAtiva .= "                arrematric.k00_matric as v01_matric,                            ";
$sSqlDividaAtiva .= "                arreinscr.k00_inscr as v01_inscr ,                              ";
$sSqlDividaAtiva .= "                cgm.z01_nome,                                                   ";
$sSqlDividaAtiva .= "                informacaodebito.*                                              ";
$sSqlDividaAtiva .= " from divida                                                                    ";
$sSqlDividaAtiva .= " left outer join cgm            on cgm.z01_numcgm          = divida.v01_numcgm  ";
$sSqlDividaAtiva .= " left outer join proced         on divida.v01_proced       = proced.v03_codigo  ";
$sSqlDividaAtiva .= " left outer join arrematric     on arrematric.k00_numpre   = divida.v01_numpre  ";
$sSqlDividaAtiva .= " left outer join arreinscr      on arreinscr.k00_numpre    = divida.v01_numpre  ";
$sSqlDividaAtiva .= " left join divold               on k10_coddiv              = divida.v01_coddiv  ";
$sSqlDividaAtiva .= " left join informacaodebito     on k163_numpre             = divold.k10_numpre  ";
$sSqlDividaAtiva .= "                               and k163_numpar             = divold.k10_numpar  ";

$sSqlDividaAtiva .= " where divida.v01_numpre = {$numpre} {$sSqlNumpar} and                          ";
$sSqlDividaAtiva .= "       divida.v01_instit = {$iInstituicao}                                      ";

$rsResult = db_query($sSqlDividaAtiva);

if (pg_numrows($rsResult) == 0) {
  
  echo "Código de Arrecadação não cadastrado.";
  exit;
  
} else {

  $oDividaAtiva  = db_utils::fieldsMemory($rsResult,0,'1');
  
  $v01_coddiv      = $oDividaAtiva->v01_coddiv;
  $v01_dtinsc      = $oDividaAtiva->v01_dtinsc;
  $v01_exerc       = $oDividaAtiva->v01_exerc ;
  $v01_vlrhis      = db_formatar($oDividaAtiva->v01_vlrhis, 'f');
  $v01_proced      = $oDividaAtiva->v01_proced;
  $v01_livro       = $oDividaAtiva->v01_livro ;
  $v01_folha       = $oDividaAtiva->v01_folha ;
  $v01_dtvenc      = $oDividaAtiva->v01_dtvenc;
  $v01_dtoper      = $oDividaAtiva->v01_dtoper;
  $v01_obs         = $oDividaAtiva->v01_obs   ;
  $v03_descr       = $oDividaAtiva->v03_descr ;
  $z01_nome        = $oDividaAtiva->z01_nome  ;
  $dDataLancamento = $oDividaAtiva->k163_data != '' ? $oDividaAtiva->k163_data : $oDividaAtiva->v01_dtoper;
  
}

$sSqlDivOld = "select distinct k10_numpar from divold where k10_coddiv = {$v01_coddiv}";

$rsResultDivOld = db_query($sSqlDivOld) or die($sSqlDivOld );

$sParcelasDivOld = "";

for ($iContDivOld=0; $iContDivOld < pg_numrows($rsResultDivOld); $iContDivOld++) {
  
  $oDivold = db_utils::fieldsMemory($rsResultDivOld, $iContDivOld);
  
  $k10_numpar = $oDivold->k10_numpar;

  if ($sParcelasDivOld == "") {    
    $sParcelasDivOld = " - importação das parcelas: ";
  }

  $sParcelasDivOld .= $k10_numpar . ($iContDivOld < pg_numrows($rsResultDivOld) -1 ? ", " : ".");

}

$v01_obs .= $sParcelasDivOld;

?>

<fieldset>
  <legend>Dívida Ativa</legend>
  
  <table class="linhaZebrada">
    <tr> 
      <td>C&oacute;digo D&iacute;vida:</td>
      <td><?php echo $v01_coddiv; ?></td>
    </tr>
    <tr> 
      <td>Nome:</td>
      <td><?php echo substr($z01_nome,0,35); ?></td>
    </tr>
    <tr> 
      <td>Data Inscri&ccedil;&atilde;o:</td>
      <td><?php echo $v01_dtinsc; ?></td>
    </tr>
    <tr> 
      <td>Data Lançamento Débito:</td>
      <td><?php echo $dDataLancamento; ?></td>
    </tr>
    <tr> 
      <td>Exerc&iacute;cio:</td>
      <td><?php echo $v01_exerc; ?></td>
    </tr>
    <tr>
      <td>Proced&ecirc;ncia:</td>
      <td><?php echo $v01_proced."-".$v03_descr; ?></td>
    </tr>
    <tr> 
      <td nowrap>Matr&iacute;cula Im&oacute;vel:</td>
      <td> 
        <?php
    			for ($iContMatricula = 0; $iContMatricula < pg_numrows($rsResult); $iContMatricula++) {
    			              
            $oMatricula = db_utils::fieldsMemory($rsResult, $iContMatricula, 'v01_matric');
            $v01_matric = $oMatricula->v01_matric;
            
    				if ($v01_matric != "") {
    					echo $v01_matric." - ";
    				}
    				
    			}
    		?>
      </td>
    </tr>
    <tr> 
      <td>Inscri&ccedil;&atilde;o Alvar&aacute;:</td>
      <td>
        <?php    		  
    			for ($iContInscricao = 0; $iContInscricao < pg_numrows($rsResult); $iContInscricao++) {
            
            $oInscricao = db_utils::fieldsMemory($rsResult,$iContInscricao,'v01_inscr');
            $v01_inscr = $oInscricao->v01_inscr;
            
    				if ($v01_inscr != "") {
    					echo $v01_inscr."<br>";
    				}
    				
    			}    			
        ?>
      </td>
    </tr>
    <tr> 
      <td>Livro/Folha:</td>
      <td><?php echo $v01_livro."/".$v01_folha; ?></td>
    </tr>
    <tr> 
      <td>Valor Hist&oacute;rico:</td>
      <td><?php echo $v01_vlrhis; ?></td>
    </tr>
    <tr> 
      <td>Data Vencimento:</td>
      <td><?php echo $v01_dtvenc; ?></td>
    </tr>
    <tr> 
      <td>Data Valor:</td>
      <td><?php echo $v01_dtoper; ?></td>
    </tr>
    <tr> 
      <td> Observa&ccedil;&atilde;o:</td>
      <td> <?php echo $v01_obs; ?></td>
    </tr>
  </table>

</fieldset>