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
include("classes/db_orcreserva_classe.php");
include("classes/db_orcreservager_classe.php");
include("classes/db_orcreserprev_classe.php");
include("classes/db_orcdotacao_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_liborcamento.php");

db_postmemory($HTTP_POST_VARS);

$clorcreserva    = new cl_orcreserva ; // tabela de reserva
$clorcreservager = new cl_orcreservager; // tabela de reserva automatica
$clorcreserprev  = new cl_orcreserprev; 
$clorcdotacao    = new cl_orcdotacao; 

$db_opcao = 3;
$db_botao = true;

if(isset($processar)){


  db_inicio_transacao();
  $erro = false;

  $sql = "select *
          from orcreserprev
          where o33_anousu = ".db_getsession("DB_anousu")." and
                o33_mes = $mes ";
  if($ativid>0){
     $sql .= " and o33_projativ = $ativid ";
  }  
  $result = pg_query($sql);
  if(pg_numrows($result)>0){
 
    for($i=0;$i<pg_numrows($result);$i++){
      db_fieldsmemory($result,$i);
      // pegar o indice a ser bloqueado
      $o33_perc = ((100 - $o33_perc)/100);     

      $sql = "select o84_codres
              from orcreservager           
                  inner join orcreserva on o84_codres = o80_codres
                  inner join orcdotacao on o58_anousu = o80_anousu and o58_coddot = o80_coddot
              where o58_projativ = $o33_projativ and o58_codigo = $o33_codigo";
      $re = pg_query($sql);
      for($x=0;$x<pg_numrows($re);$x++){  
        db_fieldsmemory($re,$x);
        $sql = "delete from orcreservager where o84_codres = $o84_codres";
        $r = pg_query($sql);
        $sql = "delete from orcreserva where o80_codres = $o84_codres";
        $r = pg_query($sql);
      }      

      // verifica as docoes e distribui cfeo saldo de cada uma
      $res = $clorcreserprev->sql_reserva_prev(true,$o33_projativ,$o33_codigo);
      //db_criatabela($res);	
      for($x=0;$x<pg_numrows($res);$x++){  
        db_fieldsmemory($res,$x);
        $calcula = round($atual_menos_reservado * $o33_perc,2);
 
        //echo "v".$atual_menos_reservado." -> ".$calcula."<br>";
 
        if($calcula>0){
          
          $clorcreserva->o80_anousu = db_getsession("DB_anousu");
          $clorcreserva->o80_coddot = $o58_coddot;
          $clorcreserva->o80_dtlanc = date("Y-m-d",db_getsession("DB_datausu"));
          $clorcreserva->o80_dtini  = date("Y-m-d",db_getsession("DB_datausu"));
          $clorcreserva->o80_dtfim  = db_getsession("DB_anousu")."-12-31";
          $clorcreserva->o80_valor  = $calcula;
          $clorcreserva->o80_descr  = "Reserva gerada para contenção de despesas";
          
          $clorcreserva->incluir(0);
          
          $clorcreservager->o84_codres     = $clorcreserva->o80_codres;
          $clorcreservager->o84_data       = date("Y-m-d",db_getsession("DB_datausu"));
          $clorcreservager->o84_id_usuario = db_getsession("DB_id_usuario");
          $clorcreservager->o84_tipo       = "Mês:$mes";
          $clorcreservager->o84_perc       = "$o33_perc";
          
          $clorcreservager->incluir($clorcreserva->o80_codres);

                   
        }

      }
      
    }

  }   
 
  db_fim_transacao($erro);
 
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
<form name="form1" method="post">
<table width="790" border="0" cellspacing="0" cellpadding="0">
<br>
  <tr> 
    <td align="right"> 
    <strong>Processar o Mês:</strong>
	</td>
	<td>
	<?
    $mes12 = array("1"=>"Janeiro","2"=>"Fevereiro","3"=>"Março","4"=>"Abril","5"=>"Maio",
                 "6"=>"Junho","7"=>"Julho","8"=>"Agosto","9"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
    for($i=1;$i<13;$i++){
      if($i >= date("m",db_getsession("DB_datausu"))){
      	$messel[$i] = $mes12[$i];
      }
    }
    db_select("mes",$messel,true,2); 
	?>
	</td>
<br>
  </tr>
    </tr>
  <tr> 
  <td align="right"><strong>Atividade:</strong>
  </td>
  <td>
  <?
  $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(null,null,"distinct o58_projativ,o55_descr","o58_projativ"," o58_anousu = ".db_getsession("DB_anousu")." and o58_instit = ".db_getsession("DB_instit")));
  //db_criatabela($result);exit;
  db_selectrecord("ativid",$result,true,2,"","","","0","");
  ?>
  <br>
  </td>
  </tr> 
  
  
    <tr> 
    <td colspan="2" align="center"> 
<br>
     <input name="processar" type="submit" value="Processar">
    </td>
    <tr>
</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($processar))
  if($erro==false)
    db_msgbox("Processo concluído.");
  

?>