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


// 1 - IPTU

$sSql  = " select proprietario.j01_tipoimp ,                                                   ";
$sSql .= "        proprietario.j01_matric  ,                                                   ";
$sSql .= "        proprietario.z01_nome    ,                                                   ";
$sSql .= "        proprietario.z01_ender   ,                                                   ";
$sSql .= "        proprietario.z01_munic   ,                                                   ";
$sSql .= "        proprietario.proprietario,                                                   ";
$sSql .= "        proprietario.j34_setor   ,                                                   ";
$sSql .= "        proprietario.j34_quadra  ,                                                   ";
$sSql .= "        proprietario.j34_lote    ,                                                   ";
$sSql .= "        proprietario.nomepri     ,                                                   ";
$sSql .= "        proprietario.j39_numero  ,                                                   ";
$sSql .= "        proprietario.j39_compl   ,                                                   ";
$sSql .= "        proprietario.j13_descr                                                       ";
$sSql .= "   from arrematric                                                                   ";
$sSql .= "        inner join proprietario on (arrematric.k00_matric = proprietario.j01_matric) ";
$sSql .= " where  arrematric.k00_numpre = {$numpre}                                            ";

$rsResult = db_query($sSql);

if (pg_numrows($rsResult) == 0) {

  echo "Matrícula não cadastrada em proprietário.";
  exit;

} else {
  
  $oIptu = db_utils::fieldsMemory($rsResult,0,'1');
  
  $j01_tipoimp  = $oIptu->j01_tipoimp ;
  $j01_matric   = $oIptu->j01_matric  ;
  $z01_nome     = $oIptu->z01_nome    ;
  $z01_ender    = $oIptu->z01_ender   ;
  $z01_munic    = $oIptu->z01_munic   ;
  $proprietario = $oIptu->proprietario;
  $j34_setor    = $oIptu->j34_setor   ;
  $j34_quadra   = $oIptu->j34_quadra  ;
  $j34_lote     = $oIptu->j34_lote    ;
  $nomepri      = $oIptu->nomepri     ;
  $j39_numero   = $oIptu->j39_numero  ;
  $j39_compl    = $oIptu->j39_compl   ;
  $j13_descr    = $oIptu->j13_descr   ;
  
}

?>
<fieldset>
  <legend>IPTU - <?php echo $j01_tipoimp; ?></legend>
  <table class="linhaZebrada">
    <tr> 
      <td>Matr&iacute;cula: </td>
      <td><?php echo $j01_matric; ?></td>
    </tr>
    <tr> 
      <td>Propriet&aacute;rio/Promitente:</td>
      <td><?php echo substr($z01_nome,0,35); ?></td>
    </tr>
    <tr> 
      <td>Endere&ccedil;o:</td>
      <td><?php echo substr($z01_ender,0,35); ?></td>
    </tr>
    <tr> 
      <td>Munic&iacute;pio:</td>
      <td><?php echo substr($z01_munic,0,35); ?></td>
    </tr>
    <tr> 
      <td>Propriet&aacute;rio: </td>
      <td><?php echo substr($proprietario,0,35); ?></td>
    </tr>
    <tr> 
      <td>Setor/Quadra/Lote:</td>
      <td><?php echo ($j34_setor."/".$j34_quadra."/".$j34_lote); ?></td>
    </tr>
    <tr> 
      <td>Logradouro:</td>
      <td><?php echo substr($nomepri,0,35); ?></td>
    </tr>
    <tr> 
      <td>N&uacute;mero:</td>
      <td><?php echo $j39_numero; ?></td>
    </tr>
    <tr> 
      <td>Complemeto:</td>
      <td><?php echo $j39_compl; ?></td>
    </tr>
    <tr> 
      <td>Bairro:</td>
      <td><?php echo $j13_descr; ?></td>
    </tr>
  </table>
</fieldset>