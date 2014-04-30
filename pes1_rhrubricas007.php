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
include("classes/db_bases_classe.php");
include("classes/db_basesr_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clbases = new cl_bases;
$clbasesr = new cl_basesr;
$db_opcao = 1;
$db_botao = true;
if(isset($cadastrar)){
  $sqlerro=false;
  db_inicio_transacao();
  $anousu = db_anofolha();
  $mesusu = db_mesfolha();
  $clbasesr->excluir($anousu,$mesusu,$r09_base,null,db_getsession("DB_instit"));  
  if($clbasesr->erro_status==0){
    $sqlerro=true;
    $erro_msg = $clbasesr->erro_msg;
  }  
  if($sqlerro == false){
    while (list($k,$v) = @ each($r09_rubric)){      
      $clbasesr->incluir($anousu,$mesusu,$r09_base,$v,db_getsession("DB_instit"));
      if($clbasesr->erro_status==0){
        $erro_msg = $clbasesr->erro_msg;
        $sqlerro=true;
      }
    }
  }
  db_fim_transacao($sqlerro);
  /*
   $del = "DELETE FROM  rhbasesr 
           WHERE  rh33_base = $rh33_base";
      // echo $del."<br>";       
      if (pg_query($del)){
         while (list($k,$v) = @ each($rh33_rubric)){
             $insert = "INSERT INTO rhbasesr VALUES('$rh33_base','$v')";
            // echo $insert."<br>";
             pg_query($insert); 
         }
         
      }
   */
      
}
?>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
  function valida_form(){
}   
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post">
<center>
<table  border="1" cellspacing="0" cellpadding="0" width='90%'>
   <thead>
    <tr>
      <th colspan='2' align='center' bgcolor='#EEEEE2'>
        <b>Rubricas</b>
      </th>
    </tr>
   </thead>
   <tbody style="max-height:50ex;overflow:auto;">
   <!--<tbody style="max-height:100ex;overflow:auto;">-->  
    <tr>
    <?
    $sql ="SELECT distinct r09_base,
                  r09_rubric,
                  rh27_rubric,
                  rh27_descr
           FROM   rhrubricas 
					        LEFT OUTER JOIN basesr ON rh27_rubric = r09_rubric 
                              AND r09_base = '".$_GET["r09_base"]."'
								            	and r09_anousu = ".db_anofolha()."
									            and r09_mesusu = ".db_mesfolha()."
									            and r09_instit = ".db_getsession("DB_instit"). "
           WHERE  rh27_instit = ".db_getsession("DB_instit"). " 
	   order by rh27_rubric";
	   
//     echo $sql;
    $rs = pg_query($sql);

    $auxl = 1;
    while($ln = pg_fetch_array($rs)){
      $estilo_fundo = "";
      $checka_botao = "";
      if($ln["r09_rubric"] == $ln["rh27_rubric"]){
        $estilo_fundo = "style='background-color:#E4F471;'";
        $checka_botao = " checked ";
      }
      echo "<td ".$estilo_fundo.">
              <input  type='checkbox' name='r09_rubric[]' value='".$ln["rh27_rubric"]."' id='".$ln["rh27_rubric"]."' ".$checka_botao.">";
      echo "  <label for='".$ln["rh27_rubric"]."'>".str_pad($ln["rh27_rubric"],4,"0",STR_PAD_LEFT)." - ".$ln["rh27_descr"]."</label>";
      echo "</td>";
      if($auxl == 2){
        echo "
              </tr>
              <tr>";
        $auxl = 0;
      }
      $auxl ++;   
    }  
    ?>
    </tr>
    </tbody>
    <tfoot>
    <tr>
      <td colspan='10' align='center'><input type='submit' name='cadastrar' value='Cadastrar'></td>
    </tr>

  </tfoot>
</table>
</center>
</form>
</body>
</html>
<?
if(isset($cadastrar)){
  if($sqlerro == true){
  	db_msgbox($erro_msg);
  }
}
?>