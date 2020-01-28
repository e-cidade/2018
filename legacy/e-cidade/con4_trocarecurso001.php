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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");

include ("classes/db_orctiporec_classe.php");
include ("classes/db_conplanoreduz_classe.php");
include ("classes/db_conplanoexe_classe.php");
include ("classes/db_orcdotacao_classe.php");
include ("classes/db_orcreceita_classe.php");
include ("classes/db_empresto_classe.php");
include ("classes/db_placaixarec_classe.php");
include ("classes/db_orcreserprev_classe.php");
include ("classes/db_sliprecurso_classe.php");

db_postmemory($HTTP_POST_VARS);

$rotulo = new rotulocampo;
$rotulo->label('o15_codigo');

if(isset($processar)){
  
  db_inicio_transacao();
  
  $clconplanoreduz = new cl_conplanoreduz;
  $clconplanoexe   = new cl_conplanoexe  ;
  $clorcdotacao    = new cl_orcdotacao   ;
  $clorcreceita    = new cl_orcreceita   ;
  $clempresto      = new cl_empresto     ;
  $clplacaixarec   = new cl_placaixarec  ;
  $clorcreserprev  = new cl_orcreserprev ;
  $clsliprecurso   = new cl_sliprecurso  ;
  
  $erro = false;
  
  /* 
  $resultreduz = $clconplanoreduz->sql_record( $clconplanoreduz->sql_query_file( null,null,'c61_reduz',null,' c61_anousu = '.db_getsession("DB_anousu").' and c61_codigo = '.$o15_codigo ));
  for ($i = 0 ; $i < pg_numrows($resultreduz);$i++){
    db_fieldsmemory($resultreduz,$i);
    $clconplanoreduz->c61_reduz  = $c61_reduz ;
    $clconplanoreduz->c61_anousu = db_getsession("DB_anousu") ;
    $clconplanoreduz->c61_codigo = $recurso ;
    $result = $clconplanoreduz->sql_record( $clconplanoreduz->alterar( $c61_reduz, db_getsession("DB_anousu") ));
    if( $clconplanoreduz->erro_status == 0){
      $erro = true;
      $erro_msg = $clconplanoreduz->erro_msg;
      break;
    }
    
  }
  */
  
  $sql = "update conplanoreduz set c61_codigo = $recurso where c61_anousu = ".db_getsession("DB_anousu")." and c61_codigo = ".$o15_codigo;
 
  $result = @pg_query($sql);
  if($result == false ){
    $erro = true;
    $erro_msg = "Erro ao alterar conplanoreduz.";
  }
  
  if( $erro == false ){

    $sql = "update conplanoexe set c62_codrec = $recurso where c62_anousu = ".db_getsession("DB_anousu")." and c62_codrec = ".$o15_codigo;
 
    $result = @pg_query($sql);
    if($result == false ){
      $erro = true;
      $erro_msg = "Erro ao alterar conplanorexe.";
    }
  
  }

  if( $erro == false ){
  
    $sql = "update orcdotacao set o58_codigo = $recurso where o58_anousu = ".db_getsession("DB_anousu")." and o58_codigo = ".$o15_codigo;
 
    $result = @pg_query($sql);
    if($result == false ){
      $erro = true;
      $erro_msg = "Já existe uma dotação com o estrutural a ser inserido. Verifique este recurso novo.";
    }
  
  }
 
  if( $erro == false ){
  
    $sql = "update orcreceita set o70_codigo = $recurso where o70_anousu = ".db_getsession("DB_anousu")." and o70_codigo = ".$o15_codigo;
 
    $result = @pg_query($sql);
    if($result == false ){
      $erro = true;
      $erro_msg = "Erro ao alterar orcreceita.";
    }
  
  }
 
 
  if( $erro == false ){
  
    $sql = "update empresto set e91_recurso = $recurso where e91_anousu = ".db_getsession("DB_anousu")." and e91_recurso = ".$o15_codigo;
 
    $result = @pg_query($sql);
    if($result == false ){
      $erro = true;
      $erro_msg = "Erro ao alterar empresto.";
    }
  
  }
 
  if( $erro == false ){
  
    $sql = "update placaixarec set k81_codigo = $recurso where k81_codpla = placaixa.k80_codpla and placaixa.k80_data > '".db_getsession("DB_anousu")."-01-01' and k81_codigo = ".$o15_codigo;
 
    $result = @pg_query($sql);
    if($result == false ){
      $erro = true;
      $erro_msg = "Erro ao alterar planilhas.";
    }
  
  }
 
  if( $erro == false ){
    
    $sql = "update orcreserprev set o33_codigo = $recurso where o33_anousu = ".db_getsession("DB_anousu")." and  o33_codigo = ".$o15_codigo;
 
    $result = @pg_query($sql);
    if($result == false ){
      $erro = true;
      $erro_msg = "Erro ao alterar reservas.";
    }
  
  }
 
  if( $erro == false ){
    
    $sql = "update sliprecurso set k29_recurso = $recurso where k29_slip = slip.k17_codigo and slip.k17_data > '".db_getsession("DB_anousu")."-01-01' and  k29_recurso = ".$o15_codigo;
 
    $result = @pg_query($sql);
    if($result == false ){
      $erro = true;
      $erro_msg = "Erro ao alterar slips.";
    }
  
  }
 
   if( $erro == false ){

     $sql = "update orcppatiporec set o26_codigo = $recurso where o26_codseqppa = orcppaval.o24_codseqppa and orcppaval.o24_exercicio >= ".db_getsession("DB_anousu")." and o26_codigo = $o15_codigo";
    
 
    $result = @pg_query($sql);
    if($result == false ){
      $erro = true;
      $erro_msg = "Erro ao alterar orcppatiporec.";
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
<br><br>
<form name="form1" action="" method="POST">
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC">
  <tr>
   <td>
     <table border=0 align=center>
     <tr>
       <td><strong>Recurso a retirar:</strong>
       </td>
       <td>
       <?
         $clorctiporec = new cl_orctiporec;
         $dbwhere = " o15_datalimite is null or o15_datalimite > '".date('Y-m-d',db_getsession('DB_datausu'))."'";
         $result = $clorctiporec->sql_record($clorctiporec->sql_query_file(null,"*","o15_codigo",$dbwhere));
         db_selectrecord('o15_codigo',$result,true,2);
       ?>
       </td>
     </tr>    
     <tr>
       <td><strong>Recurso a Incluir:</strong>
       </td>
       <td>
       <?
       db_selectrecord('o15_codigo',$result,true,2,'','recurso');
       ?>
       </td>
     </tr>    
 
     <tr>
       <td colspan="2" align="center"><input type=submit name='processar' value='Processar'></td>
     </tr>   
    </table>
   </td>
  </tr>
</table>    
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($erro)){
 if( $erro == true ){ 
   echo "<script>alert('$erro_msg')</script>";
 }else{
   echo "<script>alert('Processo concluido com sucesso.')</script>";
 }
}
?>