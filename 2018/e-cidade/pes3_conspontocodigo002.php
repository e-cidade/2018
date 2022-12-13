<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_rhrubricas_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$clrhrubricas = new cl_rhrubricas;
$clrotulo = new rotulocampo;
$clrotulo->label('rh27_rubric');
$clrotulo->label('rh27_descr');
$clrotulo->label('rh27_pd');
if(!isset($ano) || (isset($ano) && trim($ano) == "")){
  $ano = db_anofolha();
}
if(!isset($mes) || (isset($mes) && trim($mes) == "")){
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
function js_limpar(){
  document.getElementById("opcaoselantes").value = document.getElementById("opcaosel").value;
  document.getElementById("opcaosel").value = "";
  document.getElementById("index").value = "";
}
function js_MudaLink(nome,id,descr){
  document.getElementById("opcaosel").value = nome;
  document.getElementById("index").value = id;

  document.getElementById('processando').style.visibility = 'visible';
  document.getElementById('processandoTD').innerHTML = '<h3>Aguarde, processando ' + descr + '...</h3>';
  for(var i=0; i<7; i++){
    if(document.getElementById("muda" + i)){
      document.getElementById("muda" + i).style.backgroundColor = "#CCCCCC";
    }
  }
  document.getElementById("muda" + id).style.backgroundColor = "#E8EE6F";
  rubricas.location.href = "pes3_conspontocodigo021.php?ano=<?=$ano?>&mes=<?=$mes?>&sigla=" + nome + "&rubrica=" + document.formatu.rh27_rubric.value;
}
function js_relatorio(){
//  jan = window.open('pes3_consponto017.php?opcao='+document.formatu.opcao.value+'&numcgm='+document.formatu.numcgm.value+'&matricula='+document.formatu.matricula.value+'&ano=<?=$ano?>&mes=<?=$mes?>&tbprev='+document.formatu.tbprev.value,'sdjklsdklsdf','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
//  jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div id="processando" style="position:absolute; left:33px; top:114px; width:1213px; height:402px; z-index:1; visibility: hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000;">
  <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center" valign="middle" id="processandoTD" onclick="document.getElementById('processando').style.visibility='hidden'">
      </td>
    </tr>
  </table>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="formatu" action="pes3_conspontocodigo002.php" method="post">
<?  
if(isset($pesquisar) || isset($rubric)) {
  if(isset($rubric) && trim($rubric) != ""){
    $rh27_rubric  = $rubric;
  }

  $sCamposRubricas = " rh27_rubric, 
                       rh27_descr, 
                       rh27_pd, 
                       case 
                         when rh27_pd = 1 then 'PROVENTO' 
                         when rh27_pd = 2 then 'DESCONTO'
                         else 'BASE' 
                       end as rh27_pddescr";
  $result_rubrica  = $clrhrubricas->sql_record($clrhrubricas->sql_query_file($rh27_rubric,db_getsession('DB_instit'),$sCamposRubricas));
  if($clrhrubricas->numrows == 0){
    db_msgbox("Rubrica n√£o encontrada.");
    db_redireciona("pes3_conspontocodigo001.php");
  }

  db_fieldsmemory($result_rubrica, 0);  
?>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="2" height="15%"> 
      <table width="100%" border="0">
        <tr> 
          <td width="33%" valign="top">
            <table width="104%" border="0">
              <tr> 
                <td nowrap title="<?=$Trh27_rubric?>">
                  <?=$Lrh27_rubric?>
                </td>
                <td nowrap> 
                  <?
                  db_input("rh27_rubric",6,$Irh27_rubric,true,'text',3)
                  ?>
                  <?
                  db_input("rh27_descr",40,$Irh27_descr,true,'text',3)
                  ?>
                </td>
                <td nowrap> 
                  <b><?=$rh27_pddescr?></b>
                </td>
              </tr>
            </table>
          </td>
          <td width="67%" valign="top" height="90"> 
            <table border="1" cellspacing="0" cellpadding="0">
              <?
              $clgerasql = new cl_gera_sql_folha;
              $clgerasql->inicio_rh = false;
              
              if(!isset($opcaosel) || (isset($opcaosel) && trim($opcaosel) == "")){
                $opcaosel = "";
              }

              $arr_folha  = Array(
                                  "r10"=>"temsalario", 
                                  "r29"=>"temferias", 
                                  "r19"=>"temrescisao",
                                  "r21"=>"temadiantamento",
                                  "r34"=>"tem13salario", 
                                  "r47"=>"temcomplementar",
                                  "r90"=>"tempontofixo"
                                 );
              $arr_dfolha = Array(
                                  "temsalario"=>"SAL¡RIO", 
                                  "temferias"=>"F…RIAS",
                                  "temrescisao"=>"RESCIS√O",
                                  "temadiantamento"=>"ADIANTAMENTO",
                                  "tem13salario"=>"13o. SAL¡RIO",
                                  "temcomplementar"=>"COMPLEMENTAR",
                                  "tempontofixo"=>"PONTO FIXO"
                                 );
              $cont = 0;
              foreach($arr_folha as $sigla => $folha){
                $sql_dados = $clgerasql->gerador_sql($sigla,$ano,$mes,null,$rh27_rubric,"distinct #s#_rubric","","",db_getsession("DB_instit"));                
                $res_dados = $clgerasql->sql_record($sql_dados);
                
                if($clgerasql->numrows_exec > 0){
                  $opcaosel  = ($opcaosel == "" ? $folha : $opcaosel);
                  if(isset($opcaoselantes) && $opcaoselantes == $folha){
		    $opcaosel = $folha;
		    $index = $cont;
		    $opcaoselantes = "";
                  }
                  ?>
                  <tr class="links">
                    <td valign="top" style="font-size:11px">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td valign="top" class="links2" id="muda<?=$cont?>">
                            <?
                            db_ancora("<b>" . $arr_dfolha[$folha] . "</b>", "js_MudaLink('$folha',$cont,'" . $arr_dfolha[$folha] . "');", 4, "' class='links2");
                            ?>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <?
                  $index = (isset($index) && trim($index) != "" ? $index : $cont);
                }
                $cont ++;
              }
              ?>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td colspan="2" align="center" height="80%" valign="middle"> 
      <table border="0" height="100%" width="100%" cellspacing="0" cellpadding="0">
        <tr> 
          <td align="center"> 
            <iframe id="rubricas" height="400" width="95%" name="rubricas"></iframe> 
            <?
            db_input("opcaosel",8,"",true,"hidden",4);
            db_input("index",8,"",true,"hidden",4);
            db_input("opcaoselantes",8,"",true,"hidden",4);
            ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td height="5%" colspan="2" align="center"> 
      <input name="retornar" type="button" id="retornar" value="Nova Pesquisa" title="Inicio da Consulta" onclick="location.href='pes3_conspontocodigo001.php'"> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input name="pesquisar" type="submit" id="pesquisar" title="Atualiza a Consulta" value="Atualizar" onclick="return js_limpar();">
      &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
      <strong>Per√≠odo:</strong>
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
<?
}
?>
</form>
</center>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
<?
if(isset($cont)){
  echo "js_MudaLink('$opcaosel',$index,'$arr_dfolha[$opcaosel]');";
}
?>
</script>