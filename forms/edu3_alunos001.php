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
include("classes/db_aluno_classe.php");
include("classes/db_alunocurso_classe.php");
include("classes/db_alunopossib_classe.php");
include("classes/db_historico_classe.php");
include("classes/db_matricula_classe.php");
include("dbforms/db_funcoes.php");
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
 $result = $claluno->sql_record($claluno->sql_query("","*",""," ed47_i_codigo = $chavepesquisa"));
 db_fieldsmemory($result,0);
}
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
       <fieldset style="background:#f3f3f3;padding:0px;border:2px solid #000000"><legend class="cabec"><b>Nome</b></legend>
       <table border="0" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="0">
        <tr>
         <td style="font-size:18px;font-weight:bold;font-family:verdana;">
          &nbsp;&nbsp;<?=$ed47_i_codigo?>&nbsp;&nbsp;-&nbsp;&nbsp;<?=$ed47_v_nome?>
         </td>
         <td align="right">
          <input type="button" value="Fechar" onclick="parent.db_iframe_aluno.hide();">&nbsp;&nbsp;
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
         <td width="20%">
          <fieldset style="height:139px;background:#f3f3f3;padding:0px;border:4px outset #000000"><legend class="cabec"><b>Foto</b></legend>
          <table border="0" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="0">
           <tr>
            <td align="center">
             <?
             if($ed47_o_oid!=0){
              $arquivo = "tmp/".$ed47_c_foto;
              pg_exec("begin");
              pg_loexport($ed47_o_oid,$arquivo);
              pg_exec("end");
             }else{
              $arquivo = "imagens/none1.jpeg";
             }
             ?>
             <img src="<?=$arquivo?>" width="100" height="120" style="border:0px solid #000000">
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
             <?=@$Led47_d_nasc?> <?=@db_formatar($ed47_d_nasc,'d')?>
             &nbsp;&nbsp;
             <?=@$Led47_c_naturalidade?> <?=@$ed47_c_naturalidade==""?"Não Informado":$ed47_c_naturalidade?>
            </td>
           </tr>
           <tr>
            <td>
             <?=@$Led47_v_ender?> <?=@$ed47_v_ender==""?"Não Informado":$ed47_v_ender?>
             &nbsp;&nbsp;
             <?=@$Led47_i_numero?> <?=@$ed47_i_numero==""?"Não Informado":$ed47_i_numero?>
            </td>
           </tr>
           <tr>
            <td>
             <?=@$Led47_v_bairro?> <?=@$ed47_v_bairro==""?"Não Informado":$ed47_v_bairro?>
             &nbsp;&nbsp;
             <?=@$Led47_v_compl?> <?=@$ed47_v_compl?>
            </td>
           </tr>
           <tr>
            <td>
             <?=@$Led47_v_munic?> <?=@$ed47_v_munic==""?"Não Informado":$ed47_v_munic?>
             &nbsp;&nbsp;
             <?=@$Led47_v_uf?> <?=@$ed47_v_uf==""?"Não Informado":$ed47_v_uf?>
             &nbsp;&nbsp;
             <?=@$Led47_v_cep?> <?=@$ed47_v_cep==""?"Não Informado":$ed47_v_cep?>
            </td>
           </tr>
           <tr>
            <td>
             <?=@$Led47_v_sexo?> <?=@$ed47_v_sexo=="M"?"Masculino":"Feminino"?>
             &nbsp;&nbsp;
             <?=@$Led47_i_estciv?>
             <?
             if($ed47_i_estciv==1){
              echo "Solteiro";
             }elseif($ed47_i_estciv==2){
              echo "Casado";
             }elseif($ed47_i_estciv==3){
              echo "Viúvo";
             }elseif($ed47_i_estciv==4){
              echo "Divorciado";
             }
             ?>
            </td>
           </tr>
           <tr>
            <td>
             <?=@$Led47_v_telef?> <?=@$ed47_v_telef==""?"Não Informado":$ed47_v_telef?>
             &nbsp;&nbsp;
             <?=@$Led47_v_telcel?> <?=@$ed47_v_telcel==""?"Não Informado":$ed47_v_telcel?>
            </td>
           </tr>
          </table>
          </fieldset>
         </td>
        </tr>
        <tr>
         <td valign="top">
          <table border="0" width="100%">
           <tr>
            <td id="menu1" bgcolor="#444444" style="border:2px outset #f3f3f3" onmouseover="document.getElementById('menu1').style.border='2px inset #f3f3f3'" onmouseout="document.getElementById('menu1').style.border='2px outset #f3f3f3'">
             <a style="color:#DEB887;font-weight:bold;" href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=1"  target="iframe_dados">
             Documentos
             </a>
            </td>
           </tr>
           <tr>
            <td id="menu2" bgcolor="#444444" style="border:2px outset #f3f3f3" onmouseover="document.getElementById('menu2').style.border='2px inset #f3f3f3'" onmouseout="document.getElementById('menu2').style.border='2px outset #f3f3f3'">
             <a style="color:#DEB887;font-weight:bold;" href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=2"  target="iframe_dados">
             Outras Informações
             </a>
            </td>
           </tr>
           <tr>
            <td id="menu3" bgcolor="#444444" style="border:2px outset #f3f3f3" onmouseover="document.getElementById('menu3').style.border='2px inset #f3f3f3'" onmouseout="document.getElementById('menu3').style.border='2px outset #f3f3f3'">
             <?
             $result1 = $clmatricula->sql_record($clmatricula->sql_query("","ed60_i_codigo","ed60_d_datamatricula desc limit 1"," ed60_i_aluno = $chavepesquisa"));
             if($clmatricula->numrows>0){
              db_fieldsmemory($result1,0);
             }else{
              $ed60_i_codigo = 0;
             }
             ?>
             <a style="color:#DEB887;font-weight:bold;" href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=3&ed60_i_codigo=<?=$ed60_i_codigo?>"  target="iframe_dados">
             Última Matrícula
             </a>
            </td>
           </tr>
           <tr>
            <td id="menu4" bgcolor="#444444" style="border:2px outset #f3f3f3" onmouseover="document.getElementById('menu4').style.border='2px inset #f3f3f3'" onmouseout="document.getElementById('menu4').style.border='2px outset #f3f3f3'">
             <a style="color:#DEB887;font-weight:bold;" href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=4"  target="iframe_dados">
             Histórico
             </a>
            </td>
           </tr>
           <tr>
            <td id="menu5" bgcolor="#444444" style="border:2px outset #f3f3f3" onmouseover="document.getElementById('menu5').style.border='2px inset #f3f3f3'" onmouseout="document.getElementById('menu5').style.border='2px outset #f3f3f3'">
             <a style="color:#DEB887;font-weight:bold;" href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=5"  target="iframe_dados">
             Necessidades Especiais
             </a>
            </td>
           </tr>
          </table>
         </td>
         <td valign="top">
          <iframe name="iframe_dados" src="" frameborder="0" width="99%" height="500"></iframe>
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
function js_imprimir(alunos){
 jan = window.open('edu2_fichaaluno002.php?alunos='+alunos,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}
</script>