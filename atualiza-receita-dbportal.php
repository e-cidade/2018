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

db_postmemory($HTTP_POST_VARS);

if(isset($btAtualiza)){

  $sql= "select munic from db_config where codigo = ".db_getsession("DB_instit");
  $result = pg_query($sql);
  $munic = pg_result($result,0,0);

  $iporigem = "192.168.78.245";
  $dborigem = "fiscal";
  $connorigem = pg_connect("host = $iporigem dbname = $dborigem user = postgres");

  $ipdestino = "192.168.78.7";
  $dbdestino = "ontem_20060504_0001";
  $conndestino = pg_connect("host = $ipdestino dbname = $dbdestino user = postgres");

  //$data = date("Y-m-d");
  $data = "$data_ano-$data_mes-$data_dia";
  //echo $data."<br>";
  pg_query($conndestino, "begin");
		   
  $sqldelete = "delete from cornump where k12_data = '$data' and k12_id = 1000";
  $resultAutent = pg_query($conndestino, $sqldelete) or die($sqldelete);
  
  $sqldelete = "delete from corrente where k12_data = '$data' and k12_id = 1000";
  $resultAutent = pg_query($conndestino, $sqldelete) or die($sqldelete);

//  $autent = 1;
  $selectAutent = "select * from corrente where k12_data = '".$data . "' and k12_instit = 4";
  $resultAutent = pg_query($connorigem, $selectAutent) or die($selectAutent);
  
  // db_criatabela($resultAutent);exit;
  // pg_query("begin");
  
//  while($linha = pg_fetch_array($resultAutent)){
  for($xxx=0; $xxx < pg_numrows($resultAutent); $xxx++) {
     $linha = pg_fetch_array($resultAutent, $xxx);
     $id = 1000;
  //   $linha["k12_autent"] = $autent++;
  //   $linha["k12_valor"] = $linha["k12_valor"] * -1;
  //   $k12_estorn = ($linha["k12_valor"] < 0?"t":"f");

     $insertCorrente = 
      "insert into corrente 
      (
      k12_id, 
      k12_data, 
      k12_autent, 
      k12_hora, 
      k12_conta, 
      k12_valor, 
      k12_estorn,
      k12_instit
      )
      values
      (".$id.", 
      '".$linha["k12_data"]."',
      ".$linha["k12_autent"].",
      '".$linha["k12_hora"]."',
      ".$linha["k12_conta"].",
      ".$linha["k12_valor"].",
      '".$linha["k12_estorn"]."',
      ".$linha["k12_instit"]."
      )";
     $insert = pg_query($conndestino, $insertCorrente) or die($insertCorrente);

     if ($insert){
       $sqlcornump = "select * from cornump where k12_data = '" . $linha["k12_data"] . "' and k12_autent = " . $linha["k12_autent"] . " and k12_id = " . $linha["k12_id"];
       $resultcornump = pg_query($connorigem, $sqlcornump) or die($sqlcornump);

       for ($conta=0; $conta < pg_numrows($resultcornump ); $conta++) {
	 $dados = pg_fetch_array($resultcornump, $conta);

	 $insertCornump = 
	  "
	  insert into cornump
	  (
	  k12_id,
	  k12_data,
	  k12_autent,
	  k12_numpre,
	  k12_numpar,
	  k12_numtot,
	  k12_numdig,
	  k12_receit,
	  k12_valor,
	  k12_numnov
	  )
	  values
	  (
	  ".$id.",
	  '".$dados["k12_data"]."',
	  ".$dados["k12_autent"].",
	  ".$dados["k12_numpre"].",
	  ".$dados["k12_numpar"].",
	  ".$dados["k12_numtot"].",
	  ".$dados["k12_numdig"].",
	  ".$dados["k12_receit"].",
	  ".$dados["k12_valor"].",
	  ".$dados["k12_numnov"]."
	  )";
          $resultcornump = pg_query($conndestino, $insertCornump) or die($insertCornump);

	}
      } else {
	 echo "Erro na Atualização, contate o CPD (2)";
	 exit;
      }

  }

  pg_query($conndestino, "commit");
  echo "<script> alert('Processo de atualização de receitas concluído.');</script>"; 
   
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
       <form name='form1' action='atualiza-receita-dbportal.php' method='post'>
           <br>
	   <?
	   db_inputdata("data",@$dia,@$mes,@$ano,true,"text",1);
	   ?>
	   <br>
   	   <input type='submit' name='btAtualiza' value='Processa Data'>
       </form>
    </center>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>