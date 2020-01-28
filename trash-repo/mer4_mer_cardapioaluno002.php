<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("libs/db_stdlibwebseller.php");
include("dbforms/db_funcoes.php");
include("classes/db_mer_cardapioaluno_classe.php");
$escola              = db_getsession("DB_coddepto");
db_postmemory($HTTP_POST_VARS);
$clmer_cardapioaluno = new cl_mer_cardapioaluno;

?>
<html>

<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<style>
. cabec{
    text-align: left;
    font-size: 10;
    color: #DEB887;
    background-color:#444444;
    border:1px solid #CCCCCC;
  }
</style>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <center>
  <table  border="0" cellspacing="0" cellpadding="0">
      <tr> 
         <td align="left" valign="top" bgcolor="#CCCCCC"> 
         <center>
         <form name="form1" method="post" action="">
             <center>             
             <br><br><br>
             <fieldset style="width:95%"><legend><b>Refeição por Alunos</b></legend>
                 
                 <table cellspacing="0" cellpading="0" border="1" bordercolor="#000000">
                    <tr class='cabec'>
                       <td colspan="2">Lista de alunos presente na merenda  </td>
                    </tr>
                    <?$alunos = explode(",",$cod_alunos);
                      for ($x=0;$x<count($alunos);$x++) { ?>
                        <tr>
                          <td>
                             <?=$alunos[$x]?>
                          </td>
                          <?$ed47_v_nome="";
                            $sql    = " SELECT DISTINCT ed60_i_codigo, ed60_matricula,ed47_v_nome FROM aluno ";
                            $sql   .= "             inner join matricula on ed60_i_aluno=ed47_i_codigo ";
				            $sql   .= "             inner join turma on ed60_i_turma=ed57_i_codigo ";			   
				            $sql   .= "             WHERE ed60_i_codigo=$alunos[$x]";
                            $result = pg_query($sql);
                            if (pg_num_rows($result)>0) {
                          	  db_fieldsmemory($result,0);
                            }
                          ?>
                        <td>
                           <?=$ed47_v_nome?>
                        </td>
                     </tr>
                    <?}?>
                 </table>
                 <input type="button" name="voltar" value="Voltar" onClick="js_voltar();">
                 <input type="button" name="cancelar" value="Cancelar" 
                        onClick="js_cancelar('<?=$me11_d_data?>',<?=$refeicao?>);">
             </fieldset>
             </center>
        </form>
     </center>
	</td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),
        db_getsession("DB_modulo"),
        db_getsession("DB_anousu"),
        db_getsession("DB_instit")
       );
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","me11_i_cardapio",true,1,"me11_i_cardapio",true);
function js_voltar() {
  location.href = 'mer4_mer_cardapioaluno001.php'; 
}

function js_cancelar(data,refeicao) {
  location.href = 'mer4_mer_cardapioaluno001.php?excluir=1&me11_d_data='+data+'&refeicao='+refeicao;
}
</script>