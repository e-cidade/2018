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
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_pcorcamtroca_classe.php");
include("classes/db_pcorcamval_classe.php");
include("classes/db_pcorcamforne_classe.php");
include("classes/db_pcorcamjulg_classe.php");

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$clpcorcamtroca = new cl_pcorcamtroca;
$clpcorcamforne = new cl_pcorcamforne;
$clpcorcamval   = new cl_pcorcamval;
$clpcorcamjulg  = new cl_pcorcamjulg;
$clrotulo       = new rotulocampo;

$db_opcao = 1;
$db_botao = false;
$sqlerro  = false;

if(isset($trocar)){
  db_inicio_transacao();

  if ($tipojulg == 2 || $tipojulg == 3) {
       $sql_itens_trocados = "select pc23_orcamitem
                              from pcorcamval
                                   inner join pcorcamitemlic on pc26_orcamitem  = pc23_orcamitem
                                   inner join liclicitemlote on l04_liclicitem  = pc26_liclicitem
                                   inner join liclicitem     on l21_codigo      = pc26_liclicitem
                                   left  join pcorcamdescla  on pc32_orcamitem  = pc23_orcamitem and
                                                                pc32_orcamforne = pc23_orcamforne
                              where l21_situacao = 0 and 
                                    pc32_orcamitem is null and pc32_orcamforne is null and 
                                    pc23_orcamforne = $pc21_orcamforne and
                                    l04_descricao   = '$lote'";

       $res_itens_trocados     = $clpcorcamforne->sql_record($sql_itens_trocados);
       $numrows_itens_trocados = $clpcorcamforne->numrows;
       if ($clpcorcamforne->numrows > 0){
            $dbwhere = "pc24_orcamitem in (";
            $virgula = "";
            for($i = 0; $i < $numrows_itens_trocados; $i++){
                 db_fieldsmemory($res_itens_trocados,$i);

                 $vetor_itens[$i] = trim($pc23_orcamitem);

                 $dbwhere .= $virgula.$pc23_orcamitem;
                 $virgula  = ", ";

                 $clpcorcamtroca->pc25_orcamitem = $pc23_orcamitem;
                 $clpcorcamtroca->pc25_motivo    = $pc25_motivo;

                 $clpcorcamtroca->pc25_forneant  = $pc21_orcamforne_ant;
                 $clpcorcamtroca->pc25_forneatu  = $pc21_orcamforne;

                 $clpcorcamtroca->incluir(null);
                 if($clpcorcamtroca->erro_status == 0){
                     $erro_msg = $clpcorcamtroca->erro_msg;
                     $sqlerro  = true;
                     break;
                 }
            }
            
            $dbwhere .= ")";

            if ($sqlerro == false){
                 $erro_msg = $clpcorcamtroca->erro_msg;
            }
       }
       
  } else {
       $clpcorcamtroca->pc25_orcamitem = $pc25_orcamitem;
       $clpcorcamtroca->pc25_motivo    = $pc25_motivo;
       $clpcorcamtroca->pc25_forneant  = $pc21_orcamforne_ant;
       $clpcorcamtroca->pc25_forneatu  = $pc21_orcamforne;

       $clpcorcamtroca->incluir(null);
       $erro_msg = $clpcorcamtroca->erro_msg;
       if($clpcorcamtroca->erro_status==0){
           $sqlerro=true;
       }

       $dbwhere = "pc24_orcamitem=$pc25_orcamitem";
  }       

  if($sqlerro==false){
    $arr_troca = array();
    $result_troca = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query_file(null,null,"pc24_orcamforne as pc24_orcamforne_sql,pc24_pontuacao","pc24_orcamforne","$dbwhere and (pc24_orcamforne=$pc21_orcamforne_ant or pc24_orcamforne=$pc21_orcamforne)"));    
    for($i=0;$i<$clpcorcamjulg->numrows;$i++){
      db_fieldsmemory($result_troca,$i);
      $arr_troca[$pc24_orcamforne_sql] = $pc24_pontuacao;
    }

    if ($tipojulg == 2 || $tipojulg == 3) {
         for($i = 0; $i < count($vetor_itens); $i++){
              $flag_incluir = false;
              $sql_item     = "select pc24_orcamitem 
                               from pcorcamjulg
                               where pc24_orcamitem = $vetor_itens[$i] and pc24_orcamforne = $pc21_orcamforne_ant";
              $res_item     = $clpcorcamjulg->sql_record($sql_item);

              if ($clpcorcamjulg->numrows == 0){
                   $flag_incluir = true;
              }

              $clpcorcamjulg->pc24_orcamitem  = $vetor_itens[$i];
              $clpcorcamjulg->pc24_pontuacao  = $arr_troca[$pc21_orcamforne_ant];
              $clpcorcamjulg->pc24_orcamforne = $pc21_orcamforne;

              if ($flag_incluir == false) {
                   $clpcorcamjulg->alterar($vetor_itens[$i],$pc21_orcamforne,"pc24_orcamitem=$vetor_itens[$i] and pc24_orcamforne=$pc21_orcamforne_ant");
              } else {
                   $clpcorcamjulg->incluir($vetor_itens[$i],$pc21_orcamforne);
              }

              if ($clpcorcamjulg->erro_status==0){
                   $erro_msg = $clpcorcamjulg->erro_msg;
                   $sqlerro  = true;
                   break;
              }
         }
    } else {
         $clpcorcamjulg->pc24_orcamitem  = $pc25_orcamitem;
         $clpcorcamjulg->pc24_pontuacao  = $arr_troca[$pc21_orcamforne_ant];
         $clpcorcamjulg->pc24_orcamforne = $pc21_orcamforne;

         $clpcorcamjulg->alterar($pc25_orcamitem,$pc21_orcamforne_ant,"pc24_orcamitem=$pc25_orcamitem and pc24_orcamforne=$pc21_orcamforne_ant");
         if($clpcorcamjulg->erro_status==0){
             $erro_msg = $clpcorcamjulg->erro_msg;
             $sqlerro=true;
         }
    }
  }

  db_fim_transacao($sqlerro);
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
      <?
      include("forms/db_frmtrocpcorcamtrocalic.php");
      ?>
    </center>
    </td>
  </tr>
</table>
      <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
      ?>
</body>
</html>
<?
if(isset($trocar)){
  if($sqlerro==true){
    $erro_msg = str_replace("\n","\\n",$erro_msg);
    db_msgbox($erro_msg);
    if($clpcorcamtroca->erro_campo!=""){
      echo "<script> document.form1.".$clpcorcamtroca->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpcorcamtroca->erro_campo.".focus();</script>";
    }  
  }else{
    echo "<script> top.corpo.location.href = 'lic1_pcorcamtroca001.php?pc20_codorc=$orcamento&pc21_orcamforne=$orcamforne&l20_codigo=$l20_codigo';</script>";
  }
}