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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_atividaderh_classe.php");
include("classes/db_relacaotrabalho_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clatividaderh = new cl_atividaderh;
$relacaotrabalho = new cl_relacaotrabalho;
$db_opcao = 1;
$db_botao = true;
$nomeescola = db_getsession("DB_nomedepto");
$escola = db_getsession("DB_coddepto");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cabec{
 font-size: 11;
 font-weight: bold;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
}
.aluno{
 font-size: 11;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<a name="topo"></a>
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td valign="top" bgcolor="#CCCCCC">
   <br>
   <fieldset style="width:95%"><legend><b>Consulta de Recursos Humanos</b></legend>
    <table border="0">
     <tr>
      <td align="left">
       <b>Selecione a atividade:</b><br>
       <?
       $result = $clatividaderh->sql_record($clatividaderh->sql_query("","*","ed01_c_descr",""));
       ?>
       <select name="atividade" style="font-size:9px;width:150px;height:18px;">
        <option value=""></option>
        <?
        for($x=0;$x<$clatividaderh->numrows;$x++){
         db_fieldsmemory($result,$x);
         ?>
         <option value="<?=$ed01_i_codigo?>" <?=@$atividade==$ed01_i_codigo?"selected":""?>><?=$ed01_c_descr?></option>
         <?
        }
        ?>
       </select>
      </td>
      <td valign='bottom'>
       <input type="button" name="procurar" value="Procurar" onclick="js_procurar(document.form1.atividade.value)">
      </td>
     </tr>
    </table>
   </fieldset>
  </td>
 </tr>
 <?if(isset($atividade)){
 if($atividade!=""){
  $result3 = $clatividaderh->sql_record($clatividaderh->sql_query("","*","ed01_c_descr"," ed01_i_codigo = $atividade"));
  $descrativ = pg_result($result3,0,'ed01_c_descr');
 }
 ?>
 <tr>
  <td>
   <table border="0" cellspacing="2px" width="98%" cellpadding="1px" bgcolor="#cccccc">
    <tr>
     <td valign="top">
      &nbsp;&nbsp;<b>Consulta de Recursos Humanos - Atividade: <?=$atividade==""?"TODAS":$descrativ?></b>
      <?
      if($atividade!=""){
       $where = " AND ed01_i_codigo = $atividade";
      }else{
       $where = "";
      }
      $instit = db_getsession("DB_instit");
      $ano = db_anofolha();
      $mes = db_mesfolha();
      $sql = "SELECT ed20_i_codigo,
                     case when ed20_i_tiposervidor = 1 then rechumanopessoal.ed284_i_rhpessoal else rechumanocgm.ed285_i_cgm end as identificacao,
                     case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome,
                     case when ed20_i_tiposervidor = 1 then cgmrh.z01_cgccpf else cgmcgm.z01_cgccpf end as z01_cgccpf,
                     ed01_c_descr,
                     case when ed20_i_tiposervidor = 1
                      then regimerh.rh30_descr
                      else regimecgm.rh30_descr
                     end as rh30_descr
              FROM rechumano
               inner join rechumanoescola on ed75_i_rechumano = ed20_i_codigo
               left join rechumanoativ on ed22_i_rechumanoescola = ed75_i_codigo
               left join atividaderh on ed01_i_codigo = ed22_i_atividade
               left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo
               left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal
               left join rhpessoalmov on rhpessoalmov.rh02_anousu  = $ano
                                          and rhpessoalmov.rh02_mesusu  = $mes
                                          and rhpessoalmov.rh02_regist  = rhpessoal.rh01_regist
                                          and rhpessoalmov.rh02_instit  = $instit
               left join rhregime as regimerh on  regimerh.rh30_codreg = rhpessoalmov.rh02_codreg
               left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm
               left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo
               left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm
               left join rhregime as regimecgm on  regimecgm.rh30_codreg = rechumano.ed20_i_rhregime
              WHERE ed75_i_escola = $escola
              $where
              ORDER BY z01_nome,ed01_c_descr
             ";
      $result = pg_query($sql);
      //db_criatabela($result);
      $linhas = pg_num_rows($result);
      if($linhas>0){
       ?>
       <table border='1px' width="95%" bgcolor="#cccccc" style="" cellspacing="0px">
        <tr>
         <td class="cabec" align="center">Matrícula/CGM</td>
         <td class="cabec" align="center">Nome</td>
         <td class="cabec" align="center">CPF</td>
         <td class="cabec" align="center">Atividade</td>
         <td class="cabec" align="center">Regime</td>
        </tr>
        <?
        $cor1 = "#f3f3f3";
        $cor2 = "#dbdbdb";
        $cor = "";
        $cont = 0;
        for($c=0;$c<$linhas;$c++){
         db_fieldsmemory($result,$c);
         if($cor==$cor1){
          $cor = $cor2;
         }else{
          $cor = $cor1;
         }
         $cont++;
         ?>
         <tr bgcolor="<?=$cor?>">
          <td align="center"><?=$identificacao?></td>
          <td><?=$z01_nome?></td>
          <td align="center"><?=$z01_cgccpf?></td>
          <td><?=$ed01_c_descr==""?"Não informado":$ed01_c_descr?></td>
          <td><?=$rh30_descr?></td>
         </tr>
         <?
        }
        ?>
        <tr bgcolor="#999999">
         <td colspan="5"><b>Total de recursos Humanos: <?=$cont?></b></td>
        </tr>
       </table>
       <?
      }else{
       ?>
       <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
        <tr bgcolor="#EAEAEA">
         <td class='aluno'>NENHUMA REGISTRO PARA A OPÇÃO ESCOLHIDA.</td>
        </tr>
       </table>
       <?
      }
      ?>
     </td>
    </tr>
   </table>
   <?}?>
  </td>
 </tr>
</table>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_procurar(atividade){
 location.href = "edu3_rechumano001.php?atividade="+atividade;
}
</script>