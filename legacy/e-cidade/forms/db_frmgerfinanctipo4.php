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


// 4 - CONTRIBUIÇÃO MELHORIA

$iInstituicao = db_getsession('DB_instit');

$sSql  = " select edital.d01_numero          ,                                              ";
$sSql .= "        contrib.d07_data           ,                                              ";
$sSql .= "        contrib.d07_contri         ,                                              ";
$sSql .= "        contrib.d07_valor          ,                                              ";
$sSql .= "        contr.j14_nome             ,                                              ";
$sSql .= "        proprietario.j01_matric    ,                                              ";
$sSql .= "        proprietario.z01_nome      ,                                              ";
$sSql .= "        proprietario.z01_ender     ,                                              ";
$sSql .= "        proprietario.z01_munic     ,                                              ";
$sSql .= "        proprietario.proprietario  ,                                              ";
$sSql .= "        proprietario.j34_setor     ,                                              ";
$sSql .= "        proprietario.j34_quadra    ,                                              ";
$sSql .= "        proprietario.j34_lote      ,                                              ";
$sSql .= "        proprietario.nomepri       ,                                              ";
$sSql .= "        proprietario.j39_numero    ,                                              ";
$sSql .= "        proprietario.j39_compl     ,                                              ";
$sSql .= "        proprietario.j13_descr                                                    ";
$sSql .= " from arrematric                                                                  ";
$sSql .= "   	 inner join arreinstit  on arreinstit.k00_numpre    = arrematric.k00_numpre   ";
$sSql .= "                      		   and arreinstit.k00_instit  = {$iInstituicao}         ";
$sSql .= "      left join contricalc   on d09_numpre              = arrematric.k00_numpre   ";
$sSql .= "      left join contrib      on d07_contri              = d09_contri              ";
$sSql .= "                            and d07_matric              = d09_matric              ";
$sSql .= "      left join editalrua    on d07_contri              = d02_contri              ";
$sSql .= "      left join ruas contr   on d02_codigo              = contr.j14_codigo        ";
$sSql .= "      left join edital       on d02_codedi              = d01_codedi              ";
$sSql .= "     inner join proprietario on proprietario.j01_matric = arrematric.k00_matric   ";
$sSql .= " where arrematric.k00_numpre = {$numpre}                                          ";

$rsResult = db_query($sSql);

if (pg_numrows($rsResult) == 0) {
  
  echo "Código de Arrecadação não cadastrado no arrematric ou Constribuição ou Matrícula não cadastrada em proprietario.";
  exit;
  
} else {
  
  $oContribuicaoMelhoria = db_utils::fieldsMemory($rsResult, 0);
  
  $d01_numero    =  $oContribuicaoMelhoria->d01_numero  ;
  $d07_data      =  db_formatar($oContribuicaoMelhoria->d07_data, 'd');
  $d07_contri    =  $oContribuicaoMelhoria->d07_contri  ;
  $d07_valor     =  $oContribuicaoMelhoria->d07_valor   ;
  $j14_nome      =  $oContribuicaoMelhoria->j14_nome    ;
  $j01_matric    =  $oContribuicaoMelhoria->j01_matric  ;
  $z01_nome      =  $oContribuicaoMelhoria->z01_nome    ;
  $z01_ender     =  $oContribuicaoMelhoria->z01_ender   ;
  $z01_munic     =  $oContribuicaoMelhoria->z01_munic   ;
  $proprietario  =  $oContribuicaoMelhoria->proprietario;
  $j34_setor     =  $oContribuicaoMelhoria->j34_setor   ;
  $j34_quadra    =  $oContribuicaoMelhoria->j34_quadra  ;
  $j34_lote      =  $oContribuicaoMelhoria->j34_lote    ;
  $nomepri       =  $oContribuicaoMelhoria->nomepri     ;
  $j39_numero    =  $oContribuicaoMelhoria->j39_numero  ;
  $j39_compl     =  $oContribuicaoMelhoria->j39_compl   ;
  $j13_descr     =  $oContribuicaoMelhoria->j13_descr   ;
  
}

?>

<fieldset>
  <legend><?php echo ($k03_tipo == 4 ? "" : "Parcelamento de "); ?> Contribui&ccedil;&atilde;o Melhoria</legend>

  <table class="linhaZebrada">
    <tr>
      <td>Matr&iacute;cula:</td>
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
      <td>Propriet&aacute;rio:</td>
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
    <tr>
      <td>Contribui&ccedil;&atilde;o:</td>
      <td><?php echo $d07_contri; ?>&nbsp;Edital: <?php echo $d01_numero; ?></td>
    </tr>
    <tr>
      <td>Rua/Avenida:</td>
      <td><?php echo substr($j14_nome,0,35); ?></td>
    </tr>
    <tr>
      <td>Data Lan&ccedil;amento:</td>
      <td><?php echo $d07_data; ?></td>
    </tr>
    <tr>
      <td>Valor Lan&ccedil;ado:</td>
      <td><?php echo $d07_valor; ?></td>
    </tr>
  </table>

</fieldset>