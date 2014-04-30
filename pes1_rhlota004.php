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
include("classes/db_orcorgao_classe.php");
include("classes/db_orcunidade_classe.php");
include("classes/db_rhlota_classe.php");
include("classes/db_rhlotavinc_classe.php");
include("classes/db_rhlotavincele_classe.php");
include("classes/db_rhlotavincativ_classe.php");
include("classes/db_rhlotavincrec_classe.php");
include("classes/db_rhlotaexe_classe.php");
include("classes/db_rhlotacalend_classe.php");
include("classes/db_cfpess_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_cgm_classe.php");
db_postmemory($HTTP_POST_VARS);
$clrhlota = new cl_rhlota;
$clrhlotavinc = new cl_rhlotavinc;
$clrhlotavincele = new cl_rhlotavincele;
$clrhlotavincativ = new cl_rhlotavincativ;
$clrhlotavincrec = new cl_rhlotavincrec;
$clrhlotaexe = new cl_rhlotaexe;
$clcfpess = new cl_cfpess;
$clrhlotacalend = new cl_rhlotacalend;
$cldb_estrut = new cl_db_estrut;
$clorcorgao = new cl_orcorgao;
$clorcunidade = new cl_orcunidade;
$clcgm = new cl_cgm;

$db_opcao = 1;
$db_botao = true;
if(isset($incluir) || isset($importar)){
  $sqlerro = false;
  $anofolha = db_anofolha();
  $mesfolha = db_mesfolha();
  db_inicio_transacao();
  
  $result = $clcfpess->sql_record($clcfpess->sql_query_file($anofolha,$mesfolha,db_getsession("DB_instit"),"r11_codestrut"));
  if($clcfpess->numrows > 0){
    db_fieldsmemory($result,0);
  }

  if(isset($importar)){
    $result = $clrhlota->sql_record($clrhlota->sql_query_file($importar));
    if($clrhlota->numrows > 0){
      db_fieldsmemory($result,0);
      $r70_codigo = null;
    }else{
      $erro_msg = "Importação abortada. \nLotação não encontrada.";
      $sqlerro = true;
    }
  }
//verifica se CNPJ tem 14 digitos
$resultcpfcgc = $clcgm->sql_record($clcgm->sql_query($z01_numcgm,"z01_cgccpf"));
  if($clcgm->numrows > 0){
        db_fieldsmemory($resultcpfcgc,0);

    }
if (strlen(trim($z01_cgccpf))!=14){
$sqlerro = true;
$erro_msg="CNPJ Inválido";
}
//

  if($sqlerro == false){
    $clrhlota->r70_codestrut      = $r11_codestrut;
    $clrhlota->r70_estrut         = str_replace(".","",(isset($r70_estrut) ? $r70_estrut : ""));
    $clrhlota->r70_descr          = $r70_descr    ;
    $clrhlota->r70_analitica      = "$r70_analitica";
    $clrhlota->r70_instit         = $r70_instit;
    $clrhlota->r70_numcgm         = $z01_numcgm;
    $clrhlota->r70_concarpeculiar = $r70_concarpeculiar; 
    
    $clrhlota->incluir($r70_codigo);
    $r70_codigo = $clrhlota->r70_codigo;
    if($r70_analitica != "t"){
      $erro_msg = "Inclusão efetuada com sucesso!";
    }
    if($clrhlota->erro_status == 0){
      $sqlerro = true;
      $erro_msg = $clrhlota->erro_msg;
    }else{
      if($r70_analitica != "t"){
         $erro_msg = "Inclusão efetuada com sucesso!";
      }
    }
  }  

  if(isset($importar) && $sqlerro == false){
    $result_lotavinc = $clrhlotavinc->sql_record($clrhlotavinc->sql_query_file(null,"rh25_codlotavinc as codigo_do_vinculo,rh25_codigo,rh25_projativ,rh25_vinculo,rh25_recurso",""," rh25_codigo=$importar "));
    for($i=0; $i < $clrhlotavinc->numrows; $i++){
      db_fieldsmemory($result_lotavinc,$i);
      if($sqlerro == false){
        $clrhlotavinc->rh25_codigo   = $r70_codigo;
        $clrhlotavinc->rh25_projativ = $rh25_projativ;
        $clrhlotavinc->rh25_vinculo  = $rh25_vinculo;
        $clrhlotavinc->rh25_recurso  = $rh25_recurso;
        $clrhlotavinc->rh25_anousu   = $anofolha;
        $clrhlotavinc->incluir(null);
        $rh25_codlotavinc = $clrhlotavinc->rh25_codlotavinc;  
        if($clrhlotavinc->erro_status == 0){
          $erro_msg = $clrhlotavinc->erro_msg;
          $sqlerro=true;
          break;
        }
      }

      if($sqlerro == false){
        $result_importaele = $clrhlotavincele->sql_record($clrhlotavincele->sql_query_file($codigo_do_vinculo,null,"rh28_codeledef,rh28_codelenov"));
        $numrows_importaele = $clrhlotavincele->numrows;
        for($ii = 0; $ii < $numrows_importaele; $ii++){
          db_fieldsmemory($result_importaele,$ii);
          if($sqlerro == false){
            $clrhlotavincele->rh28_codelenov = $rh28_codelenov;
            $clrhlotavincele->incluir($rh25_codlotavinc,$rh28_codeledef);
            $erro_msg = $clrhlotavincele->erro_msg;
            if($clrhlotavincele->erro_status == 0){
              $sqlerro=true;
              break;
            }
          }
        }
      }

      if($sqlerro == false){
        $result_importaativ = $clrhlotavincativ->sql_record($clrhlotavincativ->sql_query_file($codigo_do_vinculo,null,"rh39_codelenov,rh39_anousu,rh39_projativ"));
        $numrows_importaativ = $clrhlotavincativ->numrows;
        for($ii = 0; $ii < $numrows_importaativ; $ii++){
          db_fieldsmemory($result_importaativ,$ii);
          if($sqlerro == false){
            $clrhlotavincativ->rh39_anousu = $rh39_anousu;
            $clrhlotavincativ->rh39_projativ = $rh39_projativ;
            $clrhlotavincativ->incluir($rh25_codlotavinc,$rh39_codelenov);
            $erro_msg = $clrhlotavincativ->erro_msg;
            if($clrhlotavincativ->erro_status == 0){
              $sqlerro = true;
              break;
            }
          }
        }
      }
      
      if($sqlerro == false){
        $result_importarec = $clrhlotavincrec->sql_record($clrhlotavincrec->sql_query_file($codigo_do_vinculo,null,"rh43_codelenov,rh43_recurso"));
        $numrows_importarec = $clrhlotavincrec->numrows;
        for($ii = 0; $ii < $numrows_importarec; $ii++){
          db_fieldsmemory($result_importarec,$ii);
          $clrhlotavincrec->rh43_recurso = $rh43_recurso;
          $clrhlotavincrec->incluir($rh25_codlotavinc,$rh43_codelenov);
          $erro_msg = $clrhlotavincrec->erro_msg;
          if($clrhlotavincrec->erro_status == 0){
            $sqlerro=true;
            break;
          }
        }
      }
    }

    if($sqlerro == false){
      $result_cadcalend = $clrhlotacalend->sql_record($clrhlotacalend->sql_query_file($importar, "rh64_calend"));
      if($clrhlotacalend->numrows > 0){
	db_fieldsmemory($result_cadcalend, 0);
      }

      $result_rhlotaexe = $clrhlotaexe->sql_record($clrhlotaexe->sql_query_file(null,$importar, "rh26_orgao as o40_orgao, rh26_unidade as o41_unidade"));
      if($clrhlotaexe->numrows > 0){
        db_fieldsmemory($result_rhlotaexe,0);
      }
    }
  }

  if($sqlerro == false && $r70_analitica == "t"){
    $clrhlotaexe->rh26_orgao = $o40_orgao;
    $clrhlotaexe->rh26_unidade = $o41_unidade;
    $clrhlotaexe->incluir($anofolha,$r70_codigo);
    if($clrhlotaexe->erro_status == 0){
      $sqlerro = true;
      $erro_msg = $clrhlotaexe->erro_msg;
    }
  } 

  if($sqlerro == false && $rh64_calend != ""){
    $clrhlotacalend->rh64_calend = $rh64_calend;
    $clrhlotacalend->incluir($r70_codigo);
    $erro_msg = $clrhlotacalend->erro_msg;
    if($clrhlotacalend->erro_status == 0){
      $sqlerro = true;
    }
  }

  if($sqlerro == false){
    $incluir = "incluir";
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmrhlota.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($importar) || isset($sem_parametro_configurado)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clrhlota->erro_campo!=""){
      echo "<script> document.form1.".$clrhlota->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhlota->erro_campo.".focus();</script>";
    };
  }else{
    if($r70_analitica=="t"){
      $libera_aba = "&liberaaba=true";
      if(isset($importar)){
     	$libera_aba = "";
      }
      db_redireciona("pes1_rhlota005.php?chavepesquisa=".$r70_codigo.$libera_aba);
    }else{
      db_msgbox($erro_msg);
      db_redireciona("pes1_rhlota004.php");
    }
  };
  if(isset($sem_parametro_configurado)){
  	echo "<script> document.form1.incluir.disabled = true;</script>";
  }
};
?>