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


// 15 - CERTIDÃO DO FORO

$iInstituicao = db_getsession('DB_instit');

$sSqlCertidaoForo  = " select ce.*,                                           ";
$sSqlCertidaoForo .= "        d.v01_obs                                       ";
$sSqlCertidaoForo .= " from divida d                                          ";
$sSqlCertidaoForo .= " inner join certdiv c on c.v14_coddiv  = d.v01_coddiv   ";
$sSqlCertidaoForo .= " inner join certid ce on ce.v13_certid = c.v14_certid   ";
$sSqlCertidaoForo .= " where d.v01_numpre = {$numpre}                         ";
$sSqlCertidaoForo .= "     and v01_instit = {$iInstituicao}                   ";

$sSqlCertidaoForo .= " union                                                  ";

$sSqlCertidaoForo .= " select cp.*,                                           ";
$sSqlCertidaoForo .= "        'Parcelamento - '||v07_parcel as v01_obs        ";
$sSqlCertidaoForo .= " from termo t                                           ";
$sSqlCertidaoForo .= " inner join certter c on c.v14_parcel  = t.v07_parcel   ";
$sSqlCertidaoForo .= " inner join certid cp on cp.v13_certid = c.v14_certid   ";
$sSqlCertidaoForo .= " where t.v07_numpre = {$numpre}                         ";
$sSqlCertidaoForo .= "     and v07_instit = {$iInstituicao}                   ";

$rsCertidaoForo = db_query($sSqlCertidaoForo);

if (pg_numrows($rsCertidaoForo) == 0) {
  
  echo "Código de Arrecadação não cadastrado.";
  exit;
  
} else {
  
  //db_fieldsmemory($rsCertidaoForo, 0, true);
  $oCertidaoForo = db_utils::fieldsMemory($rsCertidaoForo, 0);
  
  $v13_certid = $oCertidaoForo->v13_certid;
  $v13_dtemis = db_formatar($oCertidaoForo->v13_dtemis, 'd');
  
}

?>

<fieldset>
  <legend>Certidão do Foro</legend>
  
  <table class="linhaZebrada">
    <tr> 
      <td>C&oacute;digo da Certid&atilde;o:</td>
      <td><?php echo $v13_certid; ?></td>
    </tr>
    <tr> 
      <td>Data Emiss&atilde;o:</td>
      <td><?php echo $v13_dtemis; ?></td>
    </tr>
    <tr> 
      <td>Certid&atilde;o:</td>
      <td><input type="submit" name="Submit3" value="Visualizar a Certid&atilde;o"></td>
    </tr>
    <tr> 
      <td>C&oacute;digo Arrecada&ccedil;&atilde;o:</td>
      <td><?php echo $numpre; ?></td>
    </tr>
    <tr> 
      <td>Observa&ccedil;&atilde;o:</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  
</fieldset>