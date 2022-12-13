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

//echo ($HTTP_SERVER_VARS['QUERY_STRING']);exit;
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_empnotaord_classe.php");
include("classes/db_empnota_classe.php");
include("classes/db_empnotaele_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_matordem_classe.php");
include("classes/db_matordemitem_classe.php");
include("classes/db_matestoque_classe.php");
include("classes/db_matestoqueitem_classe.php");
include("classes/db_matestoqueitemnota_classe.php");
include("classes/db_matestoqueitemoc_classe.php");
include("classes/db_matestoqueini_classe.php");
include("classes/db_matestoqueinimei_classe.php");
include("classes/db_matestoqueitemunid_classe.php");
include("classes/db_matestoqueinil_classe.php");
include("classes/db_matestoqueinill_classe.php");
$clusuarios = new cl_db_usuarios;
$clempnotaord = new cl_empnotaord;
$clempnota = new cl_empnota;
$clempnotaele = new cl_empnotaele;
$clmatordemitem = new cl_matordemitem;
$clmatordem = new cl_matordem;
$clmatestoque = new cl_matestoque;
$clmatestoqueitem = new cl_matestoqueitem;
$clmatestoqueitemnota = new cl_matestoqueitemnota;
$clmatestoqueitemoc = new cl_matestoqueitemoc;
$clmatestoqueini = new cl_matestoqueini;
$clmatestoqueinimei = new cl_matestoqueinimei;
$clmatestoqueitemunid = new cl_matestoqueitemunid;
$clmatestoqueinil = new cl_matestoqueinil;
$clmatestoqueinill = new cl_matestoqueinill;
$clempnota->rotulo->label();
$clempnotaele->rotulo->label();

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

if (isset($anula)){
  db_inicio_transacao();
  $sqlerro=false;
  $valor_newnota = "0";
  $clmatestoqueini->m80_data=date('Y-m-d',db_getsession("DB_datausu"));
  $clmatestoqueini->m80_hora = date('H:i:s');
  $clmatestoqueini->m80_coddepto=db_getsession("DB_coddepto");
  $clmatestoqueini->m80_login=db_getsession("DB_id_usuario");
  $clmatestoqueini->m80_codtipo='13';
  $clmatestoqueini->m80_obs='';
  $clmatestoqueini->incluir(null);
  if ($clmatestoqueini->erro_status==0){
    $sqlerro=true;
    $erro_msg=$clmatestoqueini->erro_msg;
  }
  $codigoini=$clmatestoqueini->m80_codigo;
  $vlitem=split("valor",$val);
  $dados=split("quant_","$valores");
  $qmult=split("qntmul_",$valmul);
  $unidad=split("codunid_","$codunidade");
  $antigas=split("qantigas_","$qantigas");
  for ($i=1;$i<count($dados);$i++){
    if ($sqlerro==false){
      $numero=split("_",$dados[$i]);
      $matestoqueitem=$numero[0];
      $quantidade=$numero[2];
      $quamul=split("_",$qmult[$i]);
      $quant_mult=$quamul[1];
      $quant_uni=$quantidade;
      $quantidade=$quantidade*$quant_mult;
      $unid=split("_",$unidad[$i]);
      $antig=split("_",$antigas[$i]);
      $codi_unid=$unid[1];
			$quantantiga=$antig[2];
      $tam=strlen($codi_unid);
      $tam=$tam-1;
      $codi_unid=substr($codi_unid,0,$tam);
      $result_iniant=$clmatestoqueinimei->sql_record($clmatestoqueinimei->sql_query(null,"m82_matestoqueini as ini_ant",null,"m82_matestoqueitem=$matestoqueitem and m80_codtipo=12"));
      if ($clmatestoqueinimei->numrows>0){
        db_fieldsmemory($result_iniant,0);
        if ($sqlerro==false){
          $clmatestoqueinil->m86_matestoqueini=$ini_ant;
          $clmatestoqueinil->incluir(null);
          if ($clmatestoqueinil->erro_status==0){
            $sqlerro=true;
            $erro_msg=$clmatestoqueinil->erro_msg;
          }
        }
        $codinil=$clmatestoqueinil->m86_codigo;
        if ($sqlerro==false){
          $clmatestoqueinill->m87_matestoqueini=$codigoini;
          $clmatestoqueinill->incluir($codinil);
          if ($clmatestoqueinill->erro_status==0){
            $sqlerro=true;
            $erro_msg=$clmatestoqueinill->erro_msg;
          }
        }
      }
      $result_unidant=$clmatestoqueitemunid->sql_record($clmatestoqueitemunid->sql_query_file($matestoqueitem));
      if ($clmatestoqueitemunid->numrows){
        db_fieldsmemory($result_unidant,0);
        $quanti_reti=$m75_quant-$quant_uni;
      }
      
      $valitem=split("_",$vlitem[$i]);
      $valorquant=$valitem[2];
      if (strpos(trim($valorquant),',')!=""){
        $valorquant=str_replace('.','',$valorquant);
        $valorquant=str_replace(',','.',$valorquant);
      }
      
      $result_oc=$clmatestoqueitemoc->sql_record($clmatestoqueitemoc->sql_query($matestoqueitem,null,"m52_quant,m52_valor,m71_quant as quant_ant,m70_codigo as codestoque,m70_valor as valor_est,m70_quant as quant_est"));
      if ($clmatestoqueitemoc->numrows!=0){
        db_fieldsmemory($result_oc,0);
        $valor_uni=$m52_valor/$m52_quant;
        //$quant_ret=$quantidade;
        $quant_ret=$quant_ant-$quantidade;
        $valor_ret=$quanti_reti*$valor_uni;
      }
      
      $valor_alt=$valor_uni*$quant_uni;
      
      if ($valorquant > 0){
        if ($valorquant != $valor_alt){
          $valor_alt = $valorquant;
        }
      }
      
      $clmatestoqueitem->m71_valor="$valor_alt";
//			if ($quantidade == 0) {
        $clmatestoqueitem->m71_quant="$quantidade";
//			} else {
//				$clmatestoqueitem->m71_quant="$quant_ret";
//			}
      $clmatestoqueitem->m71_codlanc=$matestoqueitem;
      $clmatestoqueitem->alterar($matestoqueitem);
      if ($clmatestoqueitem->erro_status==0){
        $erro_msg=$clmatestoqueitem->erro_msg;
        $sqlerro=true;
      }      
      $valor_newnota += $valor_alt;
      if ($sqlerro==false){
        $clmatestoqueitemunid->m75_codmatestoqueitem=$matestoqueitem;
        $clmatestoqueitemunid->m75_quantmult="$quant_mult";
        $clmatestoqueitemunid->m75_quant="$quant_uni"; 
        $clmatestoqueitemunid->m75_codmatunid=$codi_unid;
        $clmatestoqueitemunid->alterar($matestoqueitem);
        if ($clmatestoqueitemunid->erro_status==0){
          $erro_msg=$clmatestoqueitemunid->erro_msg;
          $sqlerro=true;
        }
      }
      if ($sqlerro==false){
        $clmatestoqueinimei->m82_matestoqueini=$clmatestoqueini->m80_codigo;
        $clmatestoqueinimei->m82_matestoqueitem=$matestoqueitem;
        //$clmatestoqueinimei->m82_quant="$quantidade";
        $clmatestoqueinimei->m82_quant="$quant_ret";
        $clmatestoqueinimei->incluir(null);
        if ($clmatestoqueinimei->erro_status==0){
          $sqlerro=true;
          $erro_msg=$clmatestoqueinimei->erro_msg;
        }
      }
    }
    
    if ($sqlerro==false){
      $res_estoque = $clmatestoque->sql_record($clmatestoque->sql_query_file($codestoque,"m70_quant,m70_valor"));
      if ($clmatestoque->numrows > 0){
        db_fieldsmemory($res_estoque,0);
        $quant_est = $m70_quant;
        $valor_est = $m70_valor;
      }
      
      $quant_est -= $quant_ret;
      $valor_est -= $valor_ret;
      
      if ($valor_est < 0){
        $valor_est *= -1;
      }
      
      $clmatestoque->m70_valor="$valor_est";
      $clmatestoque->m70_quant="$quant_est";
      $clmatestoque->m70_codigo=$codestoque;
      $clmatestoque->alterar($codestoque);
      if ($clmatestoque->erro_status==0){
        $erro_msg=$clmatestoque->erro_msg;
        $sqlerro=true;
      }
    }
    if ($sqlerro==false){
      $erro_msg='Alteração Efetuada com Sucesso!!';
    }
  }
  if ($sqlerro==false){
    $result_vlrnota=$clempnotaele->sql_record($clempnotaele->sql_query_file($e69_codnota,null,"e70_valor as vlrnota"));
    if ($clempnotaele){
      db_fieldsmemory($result_vlrnota,0);
    }
//    $valor_novo="0";
    $result_oc=$clmatestoqueitemoc->sql_record($clmatestoqueitemoc->sql_query(null,null,"sum(m71_valor) as valor_novo",null,"m52_codordem=$m51_codordem"));
    $numrows_oc=$clmatestoqueitemoc->numrows;
    if ($numrows_oc > 0){
      db_fieldsmemory($result_oc,0);      
    }   
    //db_msgbox($vlr_ne);
    if ($valor_novo==0){    	
      if ($sqlerro==false){
        $res_oc   = $clmatestoqueitemoc->sql_record($clmatestoqueitemoc->sql_query(null,null,"m73_codmatestoqueitem,m73_codmatordemitem",null,"m52_codordem=$m51_codordem"));
        $nrows_oc = $clmatestoqueitemoc->numrows;

        if ($nrows_oc > 0){
             for($x=0; $x < $nrows_oc; $x++){
                  db_fieldsmemory($res_oc,$x);      

                  $clmatestoqueitemoc->excluir($m73_codmatestoqueitem,$m73_codmatordemitem);
                  if ($clmatestoqueitemoc->erro_status==0){
                       $sqlerro=true;
                       $erro_msg=$clmatestoqueitemoc->erro_msg;
                       break;
                  }
             }
        }

        $clmatestoqueitemnota->excluir(null,null,"m74_codempnota=$e69_codnota");
        if ($clmatestoqueitemnota->erro_status==0){
          $sqlerro=true;
          $erro_msg=$clmatestoqueitemnota->erro_msg;
        }
      }
      if ($sqlerro==false){
        $clempnotaord->excluir(null,null,"m72_codnota=$e69_codnota");
        if ($clempnotaord->erro_status==0){
          $sqlerro=true;
          $erro_msg=$clempnotaord->erro_msg;
          
        }
      }
      if ($sqlerro==false){
        $clempnotaele->excluir(null,null,"e70_codnota=$e69_codnota");
        if ($clempnotaele->erro_status==0){
          $sqlerro=true;
          $erro_msg=$clempnotaele->erro_msg;
          
        }
      }
      if ($sqlerro==false){
        $clempnota->excluir(null,"e69_codnota=$e69_codnota");
        if ($clempnota->erro_status==0){
          $sqlerro=true;
          $erro_msg=$clempnota->erro_msg;
          
        }    	
      }
    }else{
      if ($sqlerro==false){    	
        $clempnotaele->e70_valor="$valor_novo";
        $clempnotaele->e70_codnota=$e69_codnota;
        $clempnotaele->alterar($e69_codnota);
        if ($clempnotaele->erro_status==0){
          $erro_msg=$clempnotaele->erro_msg;      		
          $sqlerro=true;
        }
      }
    }
  }
  /*
  $result_matestoque=$clmatestoque->sql_record($clmatestoque->sql_query_file(null,"sum(m70_valor)as vlrtot,sum(m70_quant)as quantot",null,"m70_codmatmater=1"));
  if ($clmatestoque->numrows!=0){
    db_criatabela($result_matestoque);
  }
  */
  /*
  $result_oc=$clmatestoqueitemoc->sql_record($clmatestoqueitemoc->sql_query(null,null,"sum(m71_valor) as valor_est",null,"m52_codordem=$m51_codordem"));
  db_criatabela($result_oc);
  db_msgbox($erro_msg);
  */
  
  //  $sqlerro = true;
  //die("fim");
  db_fim_transacao($sqlerro);
  //  exit;
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
<?include("forms/db_frmentraordcomanu.php");?>
</center>
</td>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<?
if (isset($anula)){
  if (trim($erro_msg) != ""){
    db_msgbox($erro_msg);
  }   
  if($clempnota->erro_campo!=""){
    echo "<script> document.form1.".$clempnota->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clempnota->erro_campo.".focus();</script>";
  }else{ 
    echo"<script>top.corpo.location.href='mat1_entraordcom003.php';</script>";
  }
}
?>
</body>
</html>