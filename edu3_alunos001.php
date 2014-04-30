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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_aluno_classe.php");
require_once("classes/db_alunocurso_classe.php");
require_once("classes/db_alunopossib_classe.php");
require_once("classes/db_historico_classe.php");
require_once("classes/db_matricula_classe.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$claluno = new cl_aluno;
$clalunocurso = new cl_alunocurso;
$clalunopossib = new cl_alunopossib;
$clhistorico = new cl_historico;
$clmatricula = new cl_matricula;
$clrotulo = new rotulocampo;
$clrotulo->label("ed31_i_curso");
$claluno->rotulo->label();
$clalunocurso->rotulo->label();
$clalunopossib->rotulo->label();
$clhistorico->rotulo->label();
$db_opcao = 1;
$db_botao = true;
if(isset($chavepesquisa)){
 $campos = "aluno.*,
            censoufident.ed260_c_sigla as ufident,
            censoufnat.ed260_c_sigla as ufnat,
            censoufcert.ed260_c_sigla as ufcert,
            censoufend.ed260_c_sigla as ufend,
            censomunicnat.ed261_c_nome as municnat,
            censomuniccert.ed261_c_nome as municcert,
            censomunicend.ed261_c_nome as municend,
            censoorgemissrg.ed132_c_descr as orgemissrg,
            pais.ed228_c_descr
           ";
 $result = $claluno->sql_record($claluno->sql_query("",$campos,""," ed47_i_codigo = $chavepesquisa"));
 db_fieldsmemory($result,0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
  <?
    db_app::load("scripts.js, prototype.js, strings.js");
    db_app::load("estilos.css");
  ?>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cabec{
 text-align: center;
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
 font-weight: bold;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td valign="top" bgcolor="#CCCCCC">
   <fieldset style="width:97%"><legend><b>Consulta de Alunos</b></legend>
    <table border="0" width="100%" cellspacing="0" cellpading="0" bgcolor="#f3f3f3">
     <tr>
      <td>
       <fieldset style="background:#f3f3f3;padding:0px;border:2px solid #000000">
       <legend class="cabec"><b>Nome</b></legend>
       <table border="0" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="0">
        <tr>
         <td style="font-size:18px;font-weight:bold;font-family:verdana;">
          &nbsp;&nbsp;<?=$ed47_i_codigo?>&nbsp;&nbsp;-&nbsp;&nbsp;<?=$ed47_v_nome?>
         </td>
         <td align="right">
          <input type="button" value="Fechar" id='btnFecharJanela' onclick="js_fechajanela();">&nbsp;&nbsp;
          <input type="button" value="Imprimir" onclick="js_imprimir(<?=$chavepesquisa?>)">&nbsp;&nbsp;
         </td>
        </tr>
       </table>
       </fieldset>
      </td>
     </tr>
     <tr>
      <td>
       <table border="0" width="100%" cellspacing="0" cellpading="0">
        <tr>
         <td width="21%">
          <fieldset style="height:167px;background:#f3f3f3;padding:0px;border:4px outset #000000"><legend class="cabec"><b>Foto</b></legend>
          <table border="0" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="0">
           <tr>
            <td align="center">
             <?
             if ($ed47_o_oid != 0) {
               
               $arquivo = "tmp/".$ed47_c_foto;
               
               db_query("begin");
               $lResultExport = pg_lo_export($ed47_o_oid, $arquivo, $conn);
               db_query("end");
               
               if (!$lResultExport) {
            	   
            	   db_msgbox("Erro ao recuperar o foto do aluno.");
            	   
               } elseif (!file_exists($arquivo)) {
            	   db_msgbox("Foto do aluno não encontrada.");
               }
              
             } else {
              $arquivo = "imagens/none1.jpeg";
             }
             ?>
             <img src="<?=$arquivo?>" width="120" height="150" style="border:0px solid #000000">
            </td>
           </tr>
          </table>
          </fieldset>
         </td>
         <td valign="top">
          <fieldset style="background:#f3f3f3;border:2px solid #000000"><legend class="cabec"><b>Dados Pessoais</b></legend>
          <table border="1" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="4">
           <tr>
            <td>
             <?=$Led47_c_codigoinep?> <?=$ed47_c_codigoinep==""?"Não Informado":$ed47_c_codigoinep?>
            </td>
           </tr>
           <tr>
            <td>
             <?=$Led47_d_nasc?> <?=db_formatar($ed47_d_nasc,'d')?>
             &nbsp;&nbsp;
             <?=$Led47_i_censomunicnat?> <?=$ed47_i_censomunicnat==""?"Não Informado":$municnat?>
             &nbsp;&nbsp;
             <?=$Led47_i_censoufnat?> <?=$ed47_i_censoufnat==""?"Não Informado":$ufnat?>
            </td>
           </tr>
           <tr>
            <td>
             <?=$Led47_i_nacion?> <?=$ed47_i_nacion=="1"?"Brasileira":($ed47_i_nacion=="2"?"Brasileira no Exterior ou Naturalizado":"Estrangeira")?>
             &nbsp;&nbsp;
             <?=$Led47_i_pais?> <?=$ed228_c_descr?>
            </td>
           </tr>
           <tr>
            <td>
             <?=$Led47_v_ender?> <?=$ed47_v_ender==""?"Não Informado":$ed47_v_ender?>
             &nbsp;&nbsp;
             <?=$Led47_c_numero?> <?=$ed47_c_numero==""?"Não Informado":$ed47_c_numero?>
            </td>
           </tr>
           <tr>
            <td>
             <?=$Led47_v_bairro?> <?=$ed47_v_bairro==""?"Não Informado":$ed47_v_bairro?>
             &nbsp;&nbsp;
             <?=$Led47_v_compl?> <?=$ed47_v_compl==""?"Não Informado":$ed47_v_compl?>
             &nbsp;&nbsp;
             <?=$Led47_c_zona?> <?=$ed47_c_zona==""?"Não Informado":$ed47_c_zona?>
            </td>
           </tr>
           <tr>
            <td>
             <?=$Led47_i_censomunicend?> <?=$ed47_i_censomunicend==""?"Não Informado":$municend?>
             &nbsp;&nbsp;
             <?=$Led47_i_censoufend?> <?=$ed47_i_censoufend==""?"Não Informado":$ufend?>
             &nbsp;&nbsp;
             <?=$Led47_v_cep?> <?=$ed47_v_cep==""?"Não Informado":$ed47_v_cep?>
            </td>
           </tr>
           <tr>
            <td>
             <?=$Led47_v_sexo?> <?=$ed47_v_sexo=="M"?"MASCULINO":"FEMININO"?>
             &nbsp;&nbsp;
             <?=$Led47_i_estciv?>
             <?
             if($ed47_i_estciv==1){
              echo "SOLTEIRO";
             }elseif($ed47_i_estciv==2){
              echo "CASADO";
             }elseif($ed47_i_estciv==3){
              echo "VIÚVO";
             }elseif($ed47_i_estciv==4){
              echo "DIVORCIADO";
             }
             ?>
             <?=$Led47_c_raca?> <?=$ed47_c_raca?>
            </td>
           </tr>
           <tr>
            <td>
             <?=$Led47_v_telef?> <?=$ed47_v_telef==""?"Não Informado":$ed47_v_telef?>
             &nbsp;&nbsp;
             <?=$Led47_v_telcel?> <?=$ed47_v_telcel==""?"Não Informado":$ed47_v_telcel?>
            </td>
           </tr>
          </table>
          </fieldset>
         </td>
        </tr>
        <tr>
         <td valign="top" colspan="2">
          <table border="0" width="100%">
           <tr align="center">
            <td id="menu1" bgcolor="#444444" style="border:2px outset #f3f3f3" onmouseover="document.getElementById('menu1').style.border='2px inset #f3f3f3'" onmouseout="document.getElementById('menu1').style.border='2px outset #f3f3f3'">
             <a style="color:#DEB887;font-weight:bold;" href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=1"  target="iframe_dados">
             Documentos
             </a>
            </td>
            <td id="menu2" bgcolor="#444444" style="border:2px outset #f3f3f3" onmouseover="document.getElementById('menu2').style.border='2px inset #f3f3f3'" onmouseout="document.getElementById('menu2').style.border='2px outset #f3f3f3'">
             <a style="color:#DEB887;font-weight:bold;" href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=2"  target="iframe_dados">
             Outras Informações
             </a>
            </td>
            <td id="menu5" bgcolor="#444444" style="border:2px outset #f3f3f3" onmouseover="document.getElementById('menu5').style.border='2px inset #f3f3f3'" onmouseout="document.getElementById('menu5').style.border='2px outset #f3f3f3'">
             <a style="color:#DEB887;font-weight:bold;" href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=5"  target="iframe_dados">
             Necessidades Especiais
             </a>
            </td>
            <td id="menu3" bgcolor="#444444" style="border:2px outset #f3f3f3" onmouseover="document.getElementById('menu3').style.border='2px inset #f3f3f3'" onmouseout="document.getElementById('menu3').style.border='2px outset #f3f3f3'">
             <?
             $result1 = $clmatricula->sql_record($clmatricula->sql_query("","ed60_i_codigo","ed60_i_codigo desc limit 1"," ed60_i_aluno = $chavepesquisa AND ed60_c_ativa = 'S'"));
             if($clmatricula->numrows>0){
              db_fieldsmemory($result1,0);
             }else{
              $ed60_i_codigo = 0;
             }
             ?>
             <a id="matr" style="color:#DEB887;font-weight:bold;" href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=3&ed60_i_codigo=<?=$ed60_i_codigo?>"  target="iframe_dados">
             Matrículas
             </a>
            </td>
            <td id="menu4" bgcolor="#444444" style="border:2px outset #f3f3f3" onmouseover="document.getElementById('menu4').style.border='2px inset #f3f3f3'" onmouseout="document.getElementById('menu4').style.border='2px outset #f3f3f3'">
             <a style="color:#DEB887;font-weight:bold;" href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=4"  target="iframe_dados">
             Histórico
             </a>
            </td>
            <td id="menu6" bgcolor="#444444" style="border:2px outset #f3f3f3" onmouseover="document.getElementById('menu6').style.border='2px inset #f3f3f3'" onmouseout="document.getElementById('menu6').style.border='2px outset #f3f3f3'">
             <a style="color:#DEB887;font-weight:bold;" href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=6"  target="iframe_dados">
             Movimentação Escolar
             </a>
            </td>
            <td id="menu7" bgcolor="#444444" style="border:2px outset #f3f3f3" onmouseover="document.getElementById('menu7').style.border='2px inset #f3f3f3'" onmouseout="document.getElementById('menu7').style.border='2px outset #f3f3f3'">
             <a style="color:#DEB887;font-weight:bold;" href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=7"  target="iframe_dados">
             Consulta Faltas
             </a>
            </td>
           </tr>
          </table>
         </td>
        </tr>
        <tr>
         <td valign="top" colspan="2">
          <iframe name="iframe_dados" src="" frameborder="0" width="100%" height="1400"></iframe>
         </td>
        </tr>
       </table>
      </td>
     </tr>
    </table>
   </fieldset>
  </td>
 </tr>
</table>
</body>
</html>
<script>
var lAlunosDuplos = false;
<?php if ( isset($lAlunosDuplos) ) {?>
  lAlunosDuplos = true;
<?php } ?>

function js_imprimir(chave){
 jan = window.open('edu2_fichaaluno002.php?alunos='+chave,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}
function js_fechajanela(){
  
  if (lAlunosDuplos) {
    parent.db_iframe_aluno.hide();
  } else {
    
	  parent.document.getElementById('ed47_i_codigo').value='';
	  parent.document.getElementById('ed47_v_nome').value='';
	  parent.document.getElementById('ed47_v_pai').value='';
	  parent.document.getElementById('ed47_v_mae').value='';
	  parent.document.getElementById('ed56_i_escola').value='';
	  parent.document.getElementById('ed56_c_situacao').value='';
	  parent.document.getElementById('ed31_i_curso').value='';
	  parent.document.getElementById('ed223_i_serie').value='';
	  parent.document.getElementById('ed47_i_codigo').focus();
	  parent.db_iframe_aluno.hide();
  }
}
iframe_dados.location.href = "edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=3&ed60_i_codigo=<?=$ed60_i_codigo?>";
parent.db_iframe_aluno.liberarJanBTFechar('false');
parent.db_iframe_aluno.liberarJanBTMinimizar('false');
parent.db_iframe_aluno.liberarJanBTMaximizar('false');
</script>
<?
$sql = "SELECT count(*),ed43_i_procedimento,ed43_c_geraresultado
        FROM procresultado where ed43_c_geraresultado = 'S'
        GROUP BY ed43_i_procedimento,ed43_c_geraresultado
        HAVING count(*) > 1
        ORDER BY count desc";
$result = db_query($sql);
if(pg_num_rows($result)>0){
 for($t=0;$t<pg_num_rows($result);$t++){
  db_fieldsmemory($result,$t);
  $sql1 = "SELECT max(ed43_i_sequencia)
           FROM procresultado
           WHERE ed43_i_procedimento = $ed43_i_procedimento
           AND ed43_c_geraresultado = 'S'";
  $result1 = db_query($sql1);
  db_fieldsmemory($result1,0);
  $sql1 = "UPDATE procresultado SET
            ed43_c_geraresultado = 'N'
           WHERE ed43_i_procedimento = $ed43_i_procedimento
           AND ed43_c_geraresultado = 'S'
           AND ed43_i_sequencia < $max";
  $result1 = db_query($sql1);
 }
}
if (isset($_GET["fc_close"])) {
?>
  <script>
    document.getElementById('btnFecharJanela').onclick=<?=$_GET["fc_close"]?>;
  </script>
<?  
}
?>