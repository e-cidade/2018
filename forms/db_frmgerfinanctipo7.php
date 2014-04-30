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


// Débito tipo 7 - DIVERSOS

$iInstituicao = db_getsession('DB_instit');

$sSqlDiversos  = " select diversos.dv05_coddiver ,                                                  ";
$sSqlDiversos .= "        diversos.dv05_dtinsc   ,                                                  ";
$sSqlDiversos .= "        diversos.dv05_privenc  ,                                                  ";
$sSqlDiversos .= "        diversos.dv05_vlrhis   ,                                                  ";
$sSqlDiversos .= "        diversos.dv05_procdiver,                                                  ";
$sSqlDiversos .= "        diversos.dv05_numpre   ,                                                  ";
$sSqlDiversos .= "        diversos.dv05_obs      ,                                                  ";
$sSqlDiversos .= "        cgm.z01_nome           ,                                                  ";
$sSqlDiversos .= "        arreinscr.k00_inscr    ,                                                  ";
$sSqlDiversos .= "        arrematric.k00_matric  ,                                                  ";
$sSqlDiversos .= "        procdiver.dv09_descr                                                      ";
$sSqlDiversos .= " from diversos                                                                    ";
$sSqlDiversos .= " inner join arreinstit      on arreinstit.k00_numpre    = diversos.dv05_numpre    ";
$sSqlDiversos .= "                           and arreinstit.k00_instit    = {$iInstituicao}         ";
$sSqlDiversos .= " left outer join arrematric on arrematric.k00_numpre    = diversos.dv05_numpre    ";
$sSqlDiversos .= " left outer join arreinscr  on arreinscr.k00_numpre     = diversos.dv05_numpre    ";
$sSqlDiversos .= " inner join procdiver       on procdiver.dv09_procdiver = diversos.dv05_procdiver ";
$sSqlDiversos .= " inner join cgm             on diversos.dv05_numcgm     = cgm.z01_numcgm          ";
$sSqlDiversos .= " where diversos.dv05_numpre = {$numpre}                                           ";

$rsDiversos = db_query($sSqlDiversos);

if (pg_numrows($rsDiversos) == 0) {
  
  echo "Código de Arrecadação não cadastrado no diversos.";
  exit;
  
} else {
  
  //db_fieldsmemory($rsDiversos,0,'1');
  $oDiversos = db_utils::fieldsMemory($rsDiversos, 0);
  
  $dv05_coddiver   = $oDiversos->dv05_coddiver ;
  $dv05_dtinsc     = db_formatar($oDiversos->dv05_dtinsc, 'd');
  $dv05_privenc    = db_formatar($oDiversos->dv05_privenc, 'd');
  $dv05_vlrhis     = db_formatar($oDiversos->dv05_vlrhis, 'f');
  $dv05_procdiver  = $oDiversos->dv05_procdiver;
  $dv05_numpre     = $oDiversos->dv05_numpre   ;
  $dv05_obs        = $oDiversos->dv05_obs      ;
  $dv09_descr      = $oDiversos->dv09_descr    ;
  $z01_nome        = $oDiversos->z01_nome      ;
  $k00_matric      = $oDiversos->k00_matric    ;
  $k00_inscr       = $oDiversos->k00_inscr     ;
  
}

?>

<fieldset>
  <legend>Módulo Diversos</legend>
  
  <table class="linhaZebrada">  
    <tr> 
      <td>C&oacute;digo Diverso:</td>
      <td><?php echo $dv05_coddiver; ?></td>
    </tr>
    <tr> 
      <td>Data Inclus&atilde;o:</td>
      <td><?php echo $dv05_dtinsc; ?></td>
    </tr>
    <tr> 
      <td>Vencimento:</td>
      <td><?php echo $dv05_privenc; ?></td>
    </tr>
    <tr> 
      <td>Valor Lan&ccedil;ado:</td>
      <td><?php echo $dv05_vlrhis; ?></td>
    </tr>
    <tr> 
      <td>Proced&ecirc;ncia:</td>
      <td><?php echo $dv05_procdiver.'-'.$dv09_descr; ?></td>
    </tr>
    <tr> 
      <td>Contribu&iacute;nte:</td>
      <td><?php echo $z01_nome; ?></td>
    </tr>
    <tr> 
      <td>C&oacute;digo Arrecada&ccedil;&atilde;o:</td>
      <td><?php echo $dv05_numpre; ?></td>
    </tr>
    <tr> 
      <td>Hist&oacute;rico:</td>
      <td><?php echo $dv05_obs; ?></td>
    </tr>
    
    <tr> 
      <td>Matr&iacute;cula Im&oacute;vel:</td>
      <td>
        <?
          if ($k00_matric != "") {
          	echo $k00_matric;
          }
        ?>
      </td>
    </tr>
    <tr> 
      <td>Inscri&ccedil;&atilde;o Alvar&aacute;:</td>
      <td> 
        <?
          if ($k00_inscr != "") {
          	echo $k00_inscr;
          }
        ?>
      </td>
    </tr>
  </table>

</fieldset>