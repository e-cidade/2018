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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_sql.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_rhlota_classe.php");
include ("classes/db_lotacao_classe.php");
include ("classes/db_rhfuncao_classe.php");
$clrhfuncao = new cl_rhfuncao();
$clrhlota = new cl_rhlota();
$cllotacao = new cl_lotacao();
$clrhlota->rotulo->label();

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);
if(isset($lotacao) && trim($lotacao)!=""){
  $porlotacao = true;
  $result_funcionarios =	  	 
  $clrhlota->sql_record($clrhlota->sql_query_cgm(null,"rh01_regist as r01_regist,
                                                       z01_nome,
                                                       case when rh30_vinculo='A' 
                                                            then 'ATIVO' 
                                                            else case when rh30_vinculo='I' 
                                                                      then 'INATIVO' 
                                                                      else 'PENSIONISTA' 
                                                                 end 
                                                       end as vinculo,
                                                       rh37_funcao,
                                                       rh37_descr",
                                                      "z01_nome",
                                                      "    rh02_anousu  = $ano
                                                       and rh02_mesusu  = $mes
                                                       and rh02_instit  = ".db_getsession("DB_instit")."
                                                       and r70_instit  = ".db_getsession("DB_instit")."
                                                       and r70_estrut  = '$lotacao'
                                                       and rh05_seqpes is null
                                                      "));
  if($clrhlota->numrows == 0){
    db_msgbox("Lotação não encontrada");
    echo "<script>parent.location.href = 'pes3_consrhlotacao001.php'</script>";
  }
}else{
  $porlotacao = false;
  $result_lotacoes = $clrhlota->sql_record($clrhlota->sql_query_leftorgao(null, "r70_codigo, r70_estrut, r70_descr, o40_codtri as o40_orgao, o40_descr", "r70_estrut","  r70_instit  = ".db_getsession("DB_instit")));
  if($clrhlota->numrows == 0){
    db_msgbox("Nenhuma lotação encontrada");
    echo "<script>parent.location.href = 'pes3_consrhlotacao001.php'</script>";
  }
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
<body bgcolor=#CCCCCC onload="parent.document.getElementById('processando').style.visibility = 'hidden'">
<center>
<form name="form1" method="post">
<table border="1" cellpadding="0" cellspacing="0">
<?
if($porlotacao == true){
?>
  <tr bgcolor="#FFCC66">
    <th class="borda" style="font-size:12px" nowrap>Registro</th>
    <th class="borda" style="font-size:12px" nowrap>Nome</th>
    <th class="borda" style="font-size:12px" nowrap>Cargo</th>
    <th class="borda" style="font-size:12px" nowrap>Descrição</th>
    <th class="borda" style="font-size:12px" nowrap>Vínculo</th>
  </tr>
  <?
  $cor = "#EFE029";
  $totalvalor = 0;
  $totalquant = 0;
  $totalregis = 0;
  for($x = 0; $x < pg_numrows($result_funcionarios); $x ++){
    db_fieldsmemory($result_funcionarios, $x);
    if($cor == "#EFE029"){
      $cor = "#E4F471";
    }else if ($cor == "#E4F471"){
      $cor = "#EFE029";
    }
  ?>
  <tr>
    <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">
      <?db_ancora($r01_regist,"js_consultaregistro('$r01_regist','$lotacao');","1");?>
      &nbsp;
    </td>
    <td align="left" style="font-size:12px" bgcolor="<?=$cor?>">&nbsp;<?=$z01_nome?></td>
    <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$rh37_funcao?></td>
    <td align="left" style="font-size:12px" bgcolor="<?=$cor?>">&nbsp;<?=$rh37_descr?></td>
    <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$vinculo?></td>
  </tr>
  <?
  }
}else{
?>
  <tr bgcolor="#FFCC66">
    <th class="borda" style="font-size:12px" nowrap>Lotação</th>
    <th class="borda" style="font-size:12px" nowrap>Estrutural</th>
    <th class="borda" style="font-size:12px" nowrap>Descrição</th>
    <th class="borda" style="font-size:12px" nowrap>Órgão</th>
    <th class="borda" style="font-size:12px" nowrap>Descrição</th>
  </tr>
  <?
  $cor = "#EFE029";
  $orgaoantigo = "";
  $totalp = 0;
  for($x = 0; $x < pg_numrows($result_lotacoes); $x++){
    db_fieldsmemory($result_lotacoes, $x);
    if($cor == "#EFE029"){
      $cor = "#E4F471";
    }else if($cor == "#E4F471"){
      $cor = "#EFE029";
    }
  ?>
  <tr>
    <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">
      <?db_ancora($r70_codigo,"js_consultalotacao('$r70_codigo');","1");?>
      &nbsp;
    </td>
    <td align="left" style="font-size:12px" bgcolor="<?=$cor?>">&nbsp;<?=$r70_estrut?></td>
    <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$r70_descr?></td>
    <td align="left" style="font-size:12px" bgcolor="<?=$cor?>">&nbsp;<?=$o40_orgao?></td>
    <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$o40_descr?></td>
  </tr>
  <?
  }
}
?>
</table>
</form>
</center>
</body>
<script>
function js_consultaregistro(registro,lotacao){
  js_OpenJanelaIframe('top.corpo','db_iframe_conspessoal','pes3_conspessoal002.php?regist='+registro,'Visualização das matriculas cadastradas',true);
}
function js_consultalotacao(lotacao){
  parent.location.href = "pes3_consrhlotacao002.php?ano=<?=($ano)?>&mes=<?=($mes)?>&lotacao="+lotacao;
}
</script>
</html>