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
include("classes/db_orcsuplemlan_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcprojeto_classe.php");
include("classes/db_orcsuplem_classe.php");
include("classes/db_orcprojlan_classe.php");
include("libs/db_libcontabilidade.php");
require("classes/db_orcreserva_classe.php");     // reserva de saldo
require("classes/db_orcreservasup_classe.php");  // reserva de saldo das suplementações
require("classes/db_orcsuplemval_classe.php");  // lançamento das suplementações
require("classes/db_orcsuplemrec_classe.php");  
//require("dbforms/db_classesgenericas.php");
include("classes/db_conlancam_classe.php");
include("classes/db_conlancamval_classe.php");
include("classes/db_conlancamsup_classe.php");
include("classes/db_conlancamdot_classe.php");
include("classes/db_conlancamdoc_classe.php");
include("classes/db_conlancamrec_classe.php");

db_postmemory($HTTP_POST_VARS);
// parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clorcsuplemlan = new cl_orcsuplemlan;
$clorcsuplem    = new cl_orcsuplem;
$clorcprojeto   = new cl_orcprojeto;
$clorcprojlan   = new cl_orcprojlan;
$auxiliar       = new cl_orcsuplem;
$clconlancam    =  new cl_conlancam;
$clconlancamval =  new cl_conlancamval;
$clconlancamsup =  new cl_conlancamsup;
$clconlancamdot =  new cl_conlancamdot;
$clconlancamdoc =  new cl_conlancamdoc;
$clconlancamrec =  new cl_conlancamrec;
$cltranslan  = new cl_translan;


$db_opcao = 1;
$db_botao = true;
if(isset($Processar)){
    db_inicio_transacao();
    $anousu = db_getsession("DB_anousu");
    $data   = "$o49_data_ano-$o49_data_mes-$o49_data_dia";
    $usuario = db_getsession("DB_id_usuario");
    
    if (isset($processa_projeto)){
	    $sql = "select o46_codsup as chave
	            from orcsuplem
		         inner join orcprojeto on o46_codlei=o39_codproj
		         left outer join orcsuplemlan on o49_codsup = o46_codsup
	    	    where o46_codlei=$o39_codproj
		      and o49_codsup is null
	           ";
            $res = $clorcsuplem->sql_record($sql); 
	    for ($i=0;$i <pg_numrows($res);$i++){
	       db_fieldsmemory($res,$i);
	       $matriz[$i]=$chave;
	    }
	    $clorcprojlan->o51_id_usuario= $usuario;
	    $clorcprojlan->o51_data      = $data;	
	    $clorcprojlan->incluir($o39_codproj);
    } 
    if (!isset($matriz)){
      $matriz = explode("#",$chaves); //gera matriz com as chaves    
    } else {
      $matriz = $matriz;
    }
    for ($i=0; $i < sizeof($matriz);$i++){
        $o46_codsup= $matriz[$i];
        $result = $auxiliar->sql_record("select fc_lancam_suplementacao($o46_codsup,'$data',$usuario)");
	// db_criatabela($result);
        if ($auxiliar->numrows > 0 ) {
           db_fieldsmemory($result,0);
           if ($fc_lancam_suplementacao[0]=="1"){
              $reservas_ok = 0 ;
           } else {
              $reservas_ok = 1;
           } 
        };
	$sql = "select  codsup,o48_tiposup,tipo,dot,valor from ( 
                   select o47_codsup as codsup,'s'::char(1) as tipo, o47_coddot as dot, o47_valor   as valor
                   from orcsuplemval where o47_codsup=$o46_codsup  and o47_valor > 0
	           union
		   select  o47_codsup as codsup,  'r'::char(1) as tipo,  o47_coddot as dot,  o47_valor*-1   as valor
                   from orcsuplemval where o47_codsup=$o46_codsup  and o47_valor < 0 
		   union
                   select  o85_codsup as codsup,  'rec'::char(3) as tipo,  o85_codrec as dot,  o85_valor   as valor
                   from orcsuplemrec where o85_codsup=$o46_codsup) as x
                         inner join orcsuplem on o46_codsup=codsup
                         inner join orcsuplemtipo on o46_tiposup =o48_tiposup 
              "; 			 
         $rval= pg_exec ($sql);
         // db_criatabela($rval); 
	 for ($x=0;$x<pg_numrows($rval);$x++){
              db_fieldsmemory($rval,$x);        
	      $clconlancam->c70_anousu = $anousu;
	      $clconlancam->c70_data   = $data;
	      $clconlancam->c70_valor  = $valor; //
	      $clconlancam->incluir(0);
	      $codlan = $clconlancam->c70_codlan; //pega o codigo gerado
              if ($tipo =="rec"){
                   $clconlancamrec->c74_anousu = $anousu;
		   $clconlancamrec->c74_codrec = $dot;
		   $clconlancamrec->c74_data   = $data;
		   $clconlancamrec->incluir($codlan); 
	      } else {
                   $clconlancamdot->c73_data   = $data;
		   $clconlancamdot->c73_anousu = db_getsession("DB_anousu");
		   $clconlancamdot->c73_coddot = $dot;
		   $clconlancamdot->incluir($codlan);			  
	      }  
	      $clconlancamsup->c79_data   =$data;
	      $clconlancamsup->c79_codsup =$codsup;
	      $clconlancamsup->incluir($codlan);			  
	      // pega transacao
	      if ($tipo =="s"){//suplementacao
		 $cltranslan->db_trans_suplem($anousu,$o48_tiposup,false);
	      } else { // reducao ou receita	      
		 $cltranslan->db_trans_suplem($anousu,$o48_tiposup,true);
	      }  
	      echo "<br><Br><Br> qui";
	      print_r($cltranslan->arr_debito);
	      print_r($cltranslan->arr_credito);
	      print_r($cltranslan->arr_histori);
	      echo $cltranslan->coddoc;
	      // grava documento
              $clconlancamdoc->c71_data   = $data;
	      $clconlancamdoc->c71_coddoc = $cltranslan->coddoc;

	      for($fi=0;$fi< sizeof($cltranslan->arr_debito);$fi++){
		 $clconlancamval->c69_anousu = $anousu;
		 $clconlancamval->c69_codlan = $codlan;
		 $clconlancamval->c69_codhist= $cltranslan->arr_histori[$fi];  //
		 $clconlancamval->c69_debito = $cltranslan->arr_debito[$fi];
		 $clconlancamval->c69_credito= $cltranslan->arr_credito[$fi];
		 $clconlancamval->c69_valor  = $valor;
		 $clconlancamval->c69_data   = $data;
		 $clconlancamval->incluir(""); 	
	      }        
      	 }               
    }  
    // db_fim_transacao();
  
} else if (isset($chavepesquisa) && $chavepesquisa!=""){
    $rr= $clorcprojeto->sql_record($clorcprojeto->sql_query_file($chavepesquisa));
    db_fieldsmemory($rr,0);
    // $o39_codproj = $chavepesquisa;

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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
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
	include("forms/db_frmorcsuplemlan.php");
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
if(isset($incluir)){
  if($clorcsuplemlan->erro_status=="0"){
    $clorcsuplemlan->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clorcsuplemlan->erro_campo!=""){
      echo "<script> document.form1.".$clorcsuplemlan->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clorcsuplemlan->erro_campo.".focus();</script>";
    };
  }else{
    $clorcsuplemlan->erro(true,true);
  };
};
?>