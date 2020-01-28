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

set_time_limit(0);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
$clgerasql = new cl_gera_sql_folha;
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

if(trim($sigla) != ""){
  $arr_folha = Array("tem13salario"=>"r34", "temcomplementar"=>"r47", "temferias"=>"r29", "temsalario"=>"r10", "tempontofixo"=>"r90", "temadiantamento"=>"r21", "temrescisao"=>"r19");
  $sigla = $arr_folha[$sigla];
  $clgerasql->inicio_rh = false;
  $clgerasql->usar_pes = true;
  $clgerasql->usar_rub = true;
  $clgerasql->usar_cgm = true;
  $clgerasql->usar_lot = true;
  $sql_dados = $clgerasql->gerador_sql($sigla,$ano,$mes,null,$rubrica,"rh01_regist, z01_nome, #s#_valor as valor, #s#_quant as quant, r70_codigo, r70_estrut, r70_descr","","",db_getsession("DB_instit"));
  $res_dados = $clgerasql->sql_record($sql_dados);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style>
.fonte {
  font-family:Arial, Helvetica, sans-serif;
  font-size:12px;
}
td {
  font-family:Arial, Helvetica, sans-serif;
  font-size:12px;
}
th {
  font-family:Arial, Helvetica, sans-serif;
  font-size:12px;
}
</style>
</head>
<body bgcolor=#CCCCCC onload="parent.document.getElementById('processando').style.visibility = 'hidden';">
<form name="form1" method="post">
  <?
  if($clgerasql->numrows_exec > 0){
  ?>
  <center>
  <table border="1" cellpadding="0" cellspacing="0">
    <tr bgcolor="#FFCC66">
      <th style="font-size:12px" nowrap>Matrícula</th>
      <th style="font-size:12px" nowrap>Nome</th>
      <th style="font-size:12px" nowrap>Lotação</th>
      <th style="font-size:12px" nowrap>Descrição</th>
      <th style="font-size:12px" nowrap>Quantidade</th>
      <th style="font-size:12px" nowrap>Valor</th>
    </tr>
  <?
    $valortotal = 0;
    $quanttotal = 0;
    $cor="#EFE029";
    for($i=0; $i<$clgerasql->numrows_exec; $i++){
      db_fieldsmemory($res_dados, $i);
      if($cor=="#EFE029"){
        $cor="#E4F471";
      }else if($cor=="#E4F471"){
        $cor="#EFE029";
      }
      $valortotal += $valor;
      $quanttotal += $quant;
  ?>
    <tr>
      <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">
        <?
        db_ancora($rh01_regist,"parent.location.href='pes3_gerfinanc001.php?ano=".$ano."&mes=".$mes."&matricula=".$rh01_regist."'",1);
        ?>
      </td>
      <td align="left"   style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=$z01_nome?></td>
      <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=$r70_estrut?></td>
      <td align="left"   style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=$r70_descr?></td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=db_formatar($quant,'f')?></td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=db_formatar($valor,'f')?></td>
    </tr>
  <?
    }
  ?>
    <tr bgcolor="#FFCC66">
      <td align="right" style="font-size:12px" colspan="4"><b>Totalização</b></td>
      <td align="right" style="font-size:12px"><b><?=db_formatar($quanttotal,'f')?></b></td>
      <td align="right" style="font-size:12px"><b><?=db_formatar($valortotal,'f')?></b></td>
    </tr>
  </table>
  </center>
  <?
  }else{
    echo "SEM CALCULO NO MÊS";
  }
  ?>
</form>
</body>
</html>