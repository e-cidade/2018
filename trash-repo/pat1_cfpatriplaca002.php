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
include("dbforms/db_funcoes.php");
include("classes/db_cfpatriplaca_classe.php");
include("classes/db_bens_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clcfpatriplaca = new cl_cfpatriplaca;
$clbens         = new cl_bens;

$db_opcao = 22;
$db_botao = false;
$sqlerro  = false;
$erro_msg = "";

if (isset($incluir)||isset($alterar)){
     if (trim($t07_digseqplaca)==""||$t07_digseqplaca=="0"){
          $t07_digseqplaca = 6;
     }
     if (trim($t07_sequencial)==""||$t07_sequencial=="0"){
          $t07_sequencial = 1;
     }

     if ($t07_confplaca != 4){
          $t07_obrigplaca = "t";
     }

     $clcfpatriplaca->t07_instit      = $t07_instit;
     $clcfpatriplaca->t07_confplaca   = $t07_confplaca;
     $clcfpatriplaca->t07_digseqplaca = $t07_digseqplaca;
     $clcfpatriplaca->t07_sequencial  = $t07_sequencial;
     $clcfpatriplaca->t07_obrigplaca  = $t07_obrigplaca;
}

if(isset($alterar)){
    db_inicio_transacao();
    $sql_bensplaca = "select max(t41_placaseq) as ultseq 
                      from bensplaca inner join bens on t52_bem = t41_bem 
		      where t52_instit = ".db_getsession("DB_instit").
		     " group by t41_bem, t52_instit order by t41_bem desc limit 1";
		      
    $res_bensplaca = @db_query($sql_bensplaca);

    if (@pg_numrows($res_bensplaca) > 0){
         db_fieldsmemory($res_bensplaca,0);
         if ($ultseq == $t07_sequencial){
           
            $sqlerro  = true;
            $erro_msg = _M('patrimonial.patrimonio.db_frmcfpatriplaca.sequencial_possui_bem');
	          $clcfpatriplaca->erro_campo = "t07_sequencial";
	 }
    }

    if ($sqlerro == false){
         $clcfpatriplaca->alterar($t07_instit);
         if ($clcfpatriplaca->erro_status == 0){
              $sqlerro = true;
         }
         $erro_msg = $clcfpatriplaca->erro_msg;
    }	  
    db_fim_transacao($sqlerro);
} else if(isset($incluir)){
   db_inicio_transacao();
     $clcfpatriplaca->incluir($t07_instit);
     if ($clcfpatriplaca->erro_status == 0){
	  $sqlerro = true;
     }
     $erro_msg = $clcfpatriplaca->erro_msg;
   db_fim_transacao($sqlerro);
}

if (db_getsession("DB_login")!="dbseller"){
     $result = $clbens->sql_record($clbens->sql_query_file(null,"*","t52_instit","t52_instit = ".db_getsession("DB_instit")));
     if ($clbens->numrows > 0){
       
          $db_opcao = 3;
          db_msgbox(_M('patrimonial.patrimonio.db_frmcfpatriplaca.contate_dbseller'));
     } else {
          $db_opcao = 2;
          $db_botao = true;
     }
} else {
     $db_opcao = 2;
     $db_botao = true;
}

$result = $clcfpatriplaca->sql_record($clcfpatriplaca->sql_query(db_getsession("DB_instit")));
if($result!=false && $clcfpatriplaca->numrows>0){
    db_fieldsmemory($result,0);
} else {
    if ($sqlerro == false){
         $db_opcao       = 1;
         $t07_instit     = db_getsession("DB_instit");
         $t07_sequencial = 1;
    }	 
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
	<?
	include("forms/db_frmcfpatriplaca.php");
	?>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir)||isset($alterar)){
  db_msgbox($erro_msg);
  if($sqlerro==true){
    //$db_botao=true;
    //echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcfpatriplaca->erro_campo!=""){
      echo "<script> document.form1.".$clcfpatriplaca->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcfpatriplaca->erro_campo.".focus();</script>";
    }
  }
}
if($db_opcao==22){
 // echo "<script>document.form1.pesquisar.click();</script>";
}
?>