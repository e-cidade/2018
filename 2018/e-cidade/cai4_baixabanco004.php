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
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$iInstit = db_getsession("DB_instit");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<?


if(isset($acertabanco)){
  $sqlbanco = "select * from disbanco where codret = $codret and classi is true and instit = {$iInstit}";
  $resultbanco = pg_exec($sqlbanco);
  if (pg_numrows($resultbanco) == 0 ){
     echo "<script>alert('Este banco ainda não foi classificado. Verifique!')</script>";
  }else{
     pg_exec("begin");
     pg_exec("update  disbanco set classi = 't' where codret = $codret and classi = 'f' and instit = $instit");
     pg_exec("commit");
     echo '<script>parent.location.href="cai4_baixabanco002.php?db_opcao=2"</script>';
  }
}
if(isset($erros)) {
   $opcao = 'erros';
} else if(isset($corretos)) {
   $opcao = 'corretos';
} else {
   $opcao = 'todos';
}


?>
<script>
function js_acerta(){
  var acertabanco;
  acertabanco = confirm('Confirma acerto do banco?');
  if (acertabanco == true){
     location.href='cai4_baixabanco004.php?codret=<?=$codret?>&acertabanco=1';
  }
}

function js_acertavalores(idret){
  disbanco.jan.location.href='cai4_baixabanco005.php?idret='+idret+'&opcao='<?$opcao?>;
  disbanco.show();
  disbanco.focus();
}

function js_imprime(erro){
 if (erro==1){
  jan = window.open('cai4_baixabanco011.php?codret=<?=$codret?>&opcao=<?=$opcao?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
   }else{ 
    // js_OpenJanelaIframe('','db_iframe_relatorio','cai4_baixabanco008.php?codret=<?=$codret?>&opcao=<?=$opcao?>','',true);
  jan = window.open('cai4_baixabanco008.php?codret=<?=$codret?>&opcao=<?=$opcao?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
   }
   
}

</script>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
        <table width="58%" border="0" cellspacing="0">
          <tr> 
            <form name="frmerros" method="post" action="">
              <td align="center">
                <input name="erros" type="button" id="acerta" value="Acertar Banco" onclick="js_acerta()"> 
                <input name="erros" type="submit" id="erros" value="Somente os Erros"> 
                <input name="corretos" type="submit" id="corretos" value="N&uacute;meros Corretos"> 
                <input name="todos" type="submit" id="todos" value="Todos N&uacute;meros ">
                <input name="imprimir" id="imprimir" value="Imprimir" onClick="js_imprime('0')" type="button">
             <? if($opcao == 'erros'){?>
                <input name="imprimir_ana"  value="Imprimir Analítico" onClick="js_imprime('1')" type="button"></td>
             <? }?>
                </td>
                
            </form>
          </tr>
          <tr align="center"> 
            <td> 
            <? 
              $disbanco = new janela("disbanco","");
              $disbanco->iniciarVisivel = false;
              $disbanco->largura = "470";
              $disbanco->altura = "350";
              $disbanco->mostrar();


              $sql  = "select distinct d.idret as idret,d.k15_codbco,d.k15_codage,d.k00_numbco,a.k00_numpre,";
              $sql .= "       case when d.k00_numpar = 0 then 0 else d.k00_numpar end as k00_numpar, ";
              $sql .= "       d.k00_numpre as numpre,d.vlrtot,classi,cedente,convenio ";
              $sql .= "  from disbanco d ";
              $sql .= "       left outer join arrecad a on a.k00_numpre = d.k00_numpre ";
              $sql .= "       left outer join recibopaga r on r.k00_numnov = a.k00_numpre ";
              $sql .= " where d.codret = {$codret} ";
              $sql .= "   and d.instit = {$iInstit} ";
              if (isset($erros)) {
                $sql .= " and d.classi is false ";
              } else if (isset($corretos)) {
                $sql .= " and d.classi is true ";
              }
              $sql .= " order by d.idret ";

              db_lovrot($sql,15,"()",$codret,"js_acertavalores|0");

            ?>
            </td>
          </tr>
        </table>
        </center>
	</td>
  </tr>
</table>
</body>
</html>