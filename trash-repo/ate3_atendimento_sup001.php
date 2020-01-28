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

db_postmemory($HTTP_POST_VARS);

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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name='form1' action='' method='post'>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
    <br>
    <strong>Área:</strong>
    <?
    $sql = "select * from (select * from atendcadarea order by at25_descr ) as x union all select 9999,'NENHUMA'";
    $result = pg_exec($sql);
    //db_selectrecord("area",$result,true,2," ","","","0","js_troca(this.value)");
    db_selectrecord("area",$result,true,2," ","","","0","");

//    echo "<select style='visibility:hidden' name='tipoconsulta'>";
/*    echo "<select name='tipoconsulta'>";
    echo "<option value='0' ".($tipoconsulta==0?'selected':'').">Sem Quebra</option>";
    echo "<option value='1' ".($tipoconsulta==1?'selected':'').">Mês</option>";
    echo "<option value='2' ".($tipoconsulta==2?'selected':'').">Módulo</option>";
    echo "<option value='3' ".($tipoconsulta==3?'selected':'').">Usuário</option>";
    echo "<option value='4' ".($tipoconsulta==4?'selected':'').">Departamento</option>";
    echo "<option value='5' ".($tipoconsulta==5?'selected':'').">Procedimento</option>";
    echo "<option value='6' ".($tipoconsulta==6?'selected':'').">Cliente</option>";
    echo "</select>";	*/
 ?>
    </td>
  </tr>
  <tr>
    <td>
    <br>
    <strong>Intervalor de Data:</strong>
    <?
    if(!isset($pesquisar)){
      $dataini_ano = date('Y',db_getsession('DB_datausu'));
      $dataini_mes = date('m',db_getsession('DB_datausu'));
      $dataini_dia = date('d',db_getsession('DB_datausu'));
      $datafim_ano = date('Y',db_getsession('DB_datausu'));
      $datafim_mes = date('m',db_getsession('DB_datausu'));
      $datafim_dia = date('d',db_getsession('DB_datausu'));
    }
    db_inputdata("dataini",@$dataini_dia,@$dataini_mes,@$dataini_ano,true,'text',2);
    ?>
    a
    <?
    db_inputdata("datafim",@$datafim_dia,@$datafim_mes,@$datafim_ano,true,'text',2);
    ?>
      <input name='ordenar' value='' type='hidden'>
      <input name='ultima_ordenar' value='<?=(isset($pesquisar)?$ordenar:"")?>' type='hidden'>
      <input name='pesquisar' value='Pesquisar' type='submit'>
    </td>
  </tr>
  <td>
  <br>

<?

if( isset($pesquisar) ){

  $sql = "select at15_sequencial,at15_descr, count(*) as quant 
          from atendimento 
               inner join tecnico on at03_codatend = at02_codatend 
               inner join atendimentosituacao on at16_atendimento = at02_codatend 
               inner join atendarea on at28_atendimento = at02_codatend 
               inner join atendcadarea on at28_atendcadarea = at26_sequencial 
               inner join atendimentolanc on at06_codatend = at02_codatend 
               inner join atendimentocadsituacao on at16_situacao = at15_sequencial 
          where at06_datalanc between '$dataini_ano-$dataini_mes-$dataini_dia' and '$datafim_ano-$datafim_mes-$datafim_dia' ";

  if( $area != 0 ){
    $sql .= " and at28_atendcadarea = $area ";
  }
  $sql .= "
          group by at15_sequencial,at15_descr 
          order by at15_sequencial";

  


  echo "<table border='1'>";
  echo "<tr>";
  echo "<td align='left'  ><strong>Tipo Atendimento</strong></td>";
  echo "<td align='right'><strong>Quantidade</strong></td>";
  echo "<td align='right' ><strong>% Total </strong></td>";
  echo "<td align='right'  ><strong>% Tipo Normal   </strong></td>";
  echo "</tr>";
   
  $result = db_query($sql);
  if(pg_numrows($result)>0){
    $totquant = 0;
    $quant_normal = 0;
    for($numero=0;$numero < pg_numrows($result);$numero++){
      db_fieldsmemory($result,$numero);
      if( $at15_sequencial == 1 ){
        $quant_normal = $quant;
      }
      $totquant += $quant;
    }
    
    for($numero=0;$numero < pg_numrows($result);$numero++){
      db_fieldsmemory($result,$numero);
      echo "<tr>";
      echo "<td align='left'   title='$at15_sequencial' ><strong>$at15_descr</strong></td>";
      echo "<td align='right'><strong>$quant</strong></td>";
      echo "<td align='right'  title='% pelo total'><strong>".db_formatar($quant*100/$totquant,'f')."</strong></td>";
      echo "<td align='right'   title='% pelo tipo normal'><strong>".db_formatar($quant*100/$quant_normal,'f')."</strong></td>";
      echo "</tr>";
    }
    echo "<tr>";
    echo "<td align='left'   title='Total' ><strong>Total</strong></td>";
    //echo "<td align='left'  ><strong>$tipo</strong></td>";
    echo "<td align='right'><strong>$totquant</strong></td>";
    echo "<td align='right'  ><strong></strong>&nbsp</td>";
    echo "<td align='right'  ><strong></strong>&nbsp</td>";
      echo "</tr>";
 

  }
  echo "</tr>";
  echo "</table>";


}
?>
	</td>
  </tr>
</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>