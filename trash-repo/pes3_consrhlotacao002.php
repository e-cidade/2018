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
include("classes/db_rhlota_classe.php");
include("classes/db_orcorgao_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
$clrhlota = new cl_rhlota;
$clorcorgao = new cl_orcorgao;
$clrhlota->rotulo->label();
$clorcorgao->rotulo->label();
$clrotulo = new rotulocampo;
if(!isset($ano) || (isset($ano) && trim($ano)=="")){
  $ano = db_anofolha();
}
if(!isset($mes) || (isset($mes) && trim($mes)=="")){
  $mes = db_mesfolha();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style type="text/css">
<!--
.tabcols {
  font-size:11px;
}
.tabcols1 {
  text-align: right;
  font-size:11px;
}
.btcols {
  height: 17px;
  font-size:10px;
}
.links {
  font-weight: bold;
  color: #0033FF;
  text-decoration: none;
  font-size:10px;
  cursor: hand;
}
a.links:hover {
  color:black;
  text-decoration: underline;
}
.links2 {
  font-weight: bold;
  color: #0587CD;
  text-decoration: none;
  font-size:10px;
}
a.links2:hover {
  color:black;
  text-decoration: underline;
}
.nome {
  color:black;  
}
a.nome:hover {
  color:blue;
}
-->
</style>

<script>
function js_MudaLink(nome) {
  document.getElementById('processando').style.visibility = 'visible';
  document.getElementById('processandoTD').innerHTML = '<h3>Aguarde, processando ...</h3>';
  for(i = 0;i < document.links.length;i++) {
    var L = document.links[i].id;
    if(L!=""){
      document.getElementById(L).style.backgroundColor = '#CCCCCC';
      document.getElementById(L).hideFocus = true;
    }
  }
  document.getElementById(nome).style.backgroundColor = '#E8EE6F';
}

function js_relatorio(){
  <?
  if(!empty($lotacao)) {
    echo "jan = window.open('pes2_consrhlotacao003.php?lotacao='+document.form1.r70_codigo.value+'&ano=$ano','sdjklsdklsdf','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');\n";
  }else{
    echo "jan = window.open('pes2_consrhlotacao002.php?ano=$ano','sdjklsdklsdf','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');\n";
  }
  ?>
}
function js_location(){
  if(document.form1.lotacao){
    document.form1.lotacao.value = "";
  }
  document.form1.submit();
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div id="DDD"></div>
<div id="processando" style="position:absolute; left:25px; top:107px; width:975px; height:400px; z-index:1; visibility: hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000;">
<Table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle" id="processandoTD" onclick="document.getElementById('processando').style.visibility='hidden'">
    </td>
  </tr>
</Table>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<?      	
if(isset($lotacao) && trim($lotacao)!=""){
  $porlotacao = true;
  $result_lotacao = $clrhlota->sql_record($clrhlota->sql_query_leftorgao(null,"r70_codigo, r70_estrut, r70_descr, o40_orgao, o40_descr ", "r70_codigo", "r70_estrut = '$lotacao' and r70_instit=".db_getsession("DB_instit")));
  if($clrhlota->numrows == 0){
    db_msgbox("Lotação não encontrada");
    echo "<script>location.href = 'pes3_consrhlotacao001.php'</script>";
  }else{
    db_fieldsmemory($result_lotacao,0);
  }
}else{
  $porlotacao = false;
  $result_lotacoes = $clrhlota->sql_record($clrhlota->sql_query_file(null,"*",null," r70_instit=".db_getsession("DB_instit")));
  if($clrhlota->numrows == 0){
    db_msgbox("Nenhuma Lotacao encontrada");
    echo "<script>location.href = 'pes3_consrhlotacao001.php'</script>";
  }
}
?>
<center>
<form name='form1'>
<table width="100%" height="90%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="2"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="3"></td>
        </tr>
        <tr>
          <td colspan="3"></td>
        </tr>
        <tr> 
          <? 
          if($porlotacao == true){
          ?>
          <td nowrap class="tabcols" width="10%" align="right">
            <strong style=\"color:blue\">
              <?
              db_ancora("$Lr70_codigo","","3");
              ?>
            </strong>
          </td>
          <td class="tabcols" nowrap width="30%"> 
            <?
            db_input('r70_codigo', 8, $Ir70_codigo, true, 'text', 3);
            ?>
            <?
            db_input('r70_descr', 30, $Ir70_descr, true, 'text', 3);
            ?>
          </td>
          <td width="60%">                     
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="tabcols" nowrap align="right" colspan="2">
                  <strong  class="links2">
                    <?
                    db_ancora("VER LOTAÇÕES","js_location();","1");
                    ?>
                  </strong>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td nowrap class="tabcols" width="10%" align="right">
            <strong style=\"color:blue\">
              <?
              db_ancora("$Lo40_orgao","","3");
              ?>
            </strong>
          </td>
          <td class="tabcols" nowrap width="30%"> 
            <?
            db_input('o40_orgao', 8, $Io40_orgao, true, 'text', 3);
            ?>
            <?
            db_input('o40_descr', 30, $Io40_descr, true, 'text', 3);
            ?>
          </td>
        <tr>
          <td nowrap class="tabcols" width="10%" align="right">
            <strong style=\"color:blue\">
              <?
              db_ancora("$Lr70_estrut","","3");
              ?>
            </strong>
          </td>
          <td class="tabcols" nowrap width="30%"> 
            <?
            db_input('r70_estrut', 20, $Ir70_estrut, true, 'text', 3);
            ?>
          </td>
          <?
          }else{
          ?>
          <td nowrap class="tabcols">
            <BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <b>TODAS AS LOTAÇÕES</b>
          </td>
          <?
          }
          ?>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td colspan="2" align="center"  height="90%"  valign="middle"> 
      <table width="100%" height="90%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td align="center">
            <?
            $qry = "";
            $rog = "?";
            if(isset($lotacao) && trim($lotacao)!=""){
              $qry .= $rog."lotacao=$lotacao";
              $rog = "&";
            }
            if(isset($ano) && trim($ano)!=""){
              $qry .= $rog."ano=$ano";
              $rog = "&";
            }
            if(isset($mes) && trim($mes)!=""){
              $qry .= $rog."mes=$mes";
              $rog = "&";
            }
            //echo $qry;
            ?> 
            <iframe id="registros" height="95%" width="95%" name="registros" src="pes3_consrhlotacao021.php<?=$qry?>"></iframe> 
            <?      	
            if(isset($lotacao) && trim($lotacao)!=""){
            ?>
            <input type="hidden" name="lotacao"  value="<?=$lotacao?>">
            <?
            }      	
            ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td colspan="2" align="center"> 
      <input name="retornar" type="button" id="retornar" value="Nova Pesquisa" title="Inicio da Consulta" onclick="location.href='pes3_consrhlotacao001.php'"> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
      <input name="pesquisar" type="submit" id="pesquisar"  title="Atualiza a Consulta" value="Atualizar">
      &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; 
      <input name="imprimir" type="button" id="imprimir" value="Imprimir" title="Imprimir" onclick="js_relatorio();">
      <strong>
        &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
        Período:
      </strong>
      &nbsp;&nbsp;
      <?
      db_input("ano",4,'',true,'text',4)
      ?>
      &nbsp;/&nbsp;
      <?
      db_input("mes",2,'',true,'text',4)
      ?>
    </td>   
  </tr>
</table>
</form>
</center>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>