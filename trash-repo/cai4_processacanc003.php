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
include("classes/db_cancdebitosreg_classe.php");

$clcancdebitosreg = new cl_cancdebitosreg;
$clcancdebitosreg->rotulo->label();

db_postmemory($HTTP_POST_VARS);

$db_opcao = 3; 
$sql = "SELECT cancdebitosreg.k21_sequencia,
                 cancdebitosreg.k21_numpre,
                 cancdebitosreg.k21_numpar,
		 arrecad.k00_valor
            FROM cancdebitosreg
           inner join arrecad on arrecad.k00_numpre  = cancdebitosreg.k21_numpre
                              and arrecad.k00_numpar = cancdebitosreg.k21_numpar
           WHERE k21_codigo = $chave
          ";
  $result = $clcancdebitosreg->sql_record($sql);
 if($clcancdebitosreg->numrows > 0){
  @db_fieldsmemory($result,0);
  $db_botao = true;
  $db_opcao = 1;
  }
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <form name="form_reg">
  <table border='1' cellpadding="0" cellspacing='0' width="80%" height="100%" align="center">
    <tr><td colspan="5">Registros</td></tr>
    <tr>
     <td align="center"><input type="button" name="marca" onclick="js_marcar(<?=$clcancdebitosreg->numrows?>,this);" value="M">
      <td align='center'><?=$Lk21_numpre?></td>
      <td align='center'><?=$Lk21_numpar?></td>
      <td align='center'><b>Valor</b></td>
    </tr>
     <?
      for($x = 0; $x < $clcancdebitosreg->numrows; $x++) {
      db_fieldsmemory($result,$x);
       echo "<tr>
              <td align=center><input type='checkbox' name='id' value='$k21_sequencia'></td>
              <td align=center>$k21_numpre</td>
              <td align=center>$k21_numpar</td>
              <td align=right>$k00_valor</td>
             </tr>";
      }
     ?>
     <tr>
      <td colspan="4"><strong>Observações:</strong></td>
     </tr>
     <tr>
      <td colspan="3"><? db_textarea('k23_obs',2,50,@Ik23_obs,true,'text',$db_opcao,"","","#FFFFFF; text-transform:uppercase")?></td>
      <td> 
       <input name="processa" type="button" id="db_opcao" value="Processar" onclick="js_processar(<?=$clcancdebitosreg->numrows?>,this)" <?=($db_botao==false?"disabled":"")?> >
      </td>
     </tr>
   </table>
  </form>  
<script>
function js_processar(total,documento){
 linha = '';
  sep   = '';
 
   //monta linha com os registros selecionados para dar baixa
   for(i=1;i <= total;i++){
    if(document.form_reg[i].checked == true){
      linha += sep+document.form_reg[i].value;
      sep = "|";
    }
  }
  parent.location.href = "cai4_processacanc002.php?chavepesquisa=<?=@$chavepesquisa?>&linha="+linha+"&k23_obs="+document.form_reg.k23_obs.value+"&processar";
}

function js_marcar(tudo,documento){
  for(i=1;i<=tudo;i++){
   if(documento.value=="D"){
    document.form_reg[i].checked=false;
   }
   if(documento.value=="M"){
    document.form_reg[i].checked=true;
   }
  }
  if(document.form_reg.marca.value == "D"){
     document.form_reg.marca.value="M";
  } else {
     document.form_reg.marca.value="D";
  }
 }
 </script>
</body>
</html>