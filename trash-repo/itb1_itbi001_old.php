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
include("classes/db_itbi_classe.php");
include("classes/db_itbimatric_classe.php");
include("classes/db_itbilogin_classe.php");
include("classes/db_itburbano_classe.php");
include("classes/db_itbirural_classe.php");
include("classes/db_itbiruralcaract_classe.php");
include("classes/db_itbipropriold_classe.php");
include("classes/db_propri_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_itbidadosimovel_classe.php");
include("classes/db_itbinome_classe.php");
include("classes/db_itbinomecgm_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($_POST);

if(!isset($abas)){
  echo "<script>location.href='itb1_itbi005.php?tipo=".@$tipo."'</script>";
  exit;
}

db_postmemory($HTTP_POST_VARS);
$clitbi            = new cl_itbi;
$clitbimatric      = new cl_itbimatric;
$clitbipropriold   = new cl_itbipropriold;
$clpropri          = new cl_propri;
$clitbilogin       = new cl_itbilogin;
$clitburbano       = new cl_itburbano;
$clitbirural       = new cl_itbirural;
$clitbiruralcaract = new cl_itbiruralcaract;

$clitbidadosimovel = new cl_itbidadosimovel;
$clitbinome        = new cl_itbinome;
$clitbinomecgm     = new cl_itbinomecgm;

$db_opcao = 1;
$db_botao = true;

global $tipo;

$sqlerro = false;

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  db_inicio_transacao();
  
  $clitbi->it01_data       = date("Y-m-d",db_getsession("DB_datausu"));
  $clitbi->it01_hora       = db_hora();
  $clitbi->it01_origem     = 1;
  $clitbi->it01_coddepto   = db_getsession('DB_coddepto');
  $clitbi->it01_id_usuario = db_getsession('DB_id_usuario');
  $clitbi->incluir(@$it01_guia);
  if($clitbi->erro_status == "0"){
    $erro = $clitbi->erro_msg;
    $sqlerro = true;
  }else{
    $ok = $clitbi->erro_msg;
  }
  if($tipo == "urbano" && $sqlerro == false){
    $clitburbano->incluir($clitbi->it01_guia);
    if($clitburbano->erro_status == "0"){
      $erro = $clitburbano->erro_msg;
      $sqlerro = true;
    }
  }elseif($tipo == "rural" && $sqlerro == false){
    $clitbirural->incluir($clitbi->it01_guia);
    if($clitbirural->erro_status == "0"){
      $erro = $clitbirural->erro_msg;
      $sqlerro = true;
    }
    if(isset($codigo) && isset($valor) && $codigo != "" && $valor != "" && $sqlerro == false){
      $cod = split(",",$codigo);
      $val = split(",",$valor);
      for($i=0;$i<sizeof($cod);$i++){
        $clitbiruralcaract->it19_guia = $clitbi->it01_guia;
        $clitbiruralcaract->it19_codigo = $cod[$i];
        $clitbiruralcaract->it19_valor = $val[$i];
		$clitbiruralcaract->incluir($clitbi->it01_guia,$cod[$i]);
        if($clitbiruralcaract->erro_status == "0"){
          $erro = $clitbiruralcaract->erro_msg;
          $sqlerro = true;
        }
      }
    }
  }
  if($sqlerro == false && $tipo != "rural"){
    $clitbimatric->incluir($clitbi->it01_guia,$j01_matric);
    if($clitbimatric->erro_status == "0"){
      $erro = $clitbimatric->erro_msg;
      $sqlerro = true;
    }
  }
  if($sqlerro == false){
    $clitbilogin->it13_id_usuario = db_getsession("DB_id_usuario");
    $clitbilogin->incluir($clitbi->it01_guia);
    if($clitbilogin->erro_status == "0"){
      $erro = $clitbilogin->erro_msg;
      $sqlerro = true;
    }
  }
  if($sqlerro == false && $j01_matric != ""){
    $res = $clpropri->sql_record($clpropri->sql_query($j01_matric));
    if($clpropri->numrows > 0){
      for($m=0;$m<$clpropri->numrows;$m++){ 
        db_fieldsmemory($res,$m);
		$clitbipropriold->it20_numcgm = $j42_numcgm;
		$clitbipropriold->it20_pri = 'f';
		$cgm = $j42_numcgm;
		$clitbipropriold->incluir($clitbi->it01_guia,$cgm);
		if($clitbipropriold->erro_status == "0"){
		  $erro = $clitbipropriold->erro_msg;
		  $sqlerro = true;
		}
      }
      $clitbipropriold->it20_numcgm = $j01_numcgm;
      $clitbipropriold->it20_pri = 't';
      $cgm = $j01_numcgm;
      $clitbipropriold->incluir($clitbi->it01_guia,$cgm);
      if($clitbipropriold->erro_status == "0"){
			$erro = $clitbipropriold->erro_msg;
			$sqlerro = true;
      }
    }
  }

  if($sqlerro == false){
/************* I N C L U I   O S   D A D O S   D O   I M O V E L  ***********************************************************************************************************/
		$rsdadosimovel =  pg_query("select * from proprietario
                   						  inner join itbimatric on it06_matric = j01_matric
				                   	where it06_guia = ".$clitbi->it01_guia);
		$numdados = pg_numrows($rsdadosimovel);
		if($numdados > 0){
			db_fieldsmemory($rsdadosimovel,0);
			$clitbidadosimovel->it22_itbi        = $clitbi->it01_guia;
			$clitbidadosimovel->it22_setor       = $j34_setor;
			$clitbidadosimovel->it22_quadra      = $j34_quadra;
			$clitbidadosimovel->it22_lote        = $j34_lote;
			$clitbidadosimovel->it22_descrlograd = $nomepri;
			$clitbidadosimovel->it22_numero      = $j39_numero;
			$clitbidadosimovel->it22_compl       = $j39_compl;
	    $clitbidadosimovel->incluir(null);
	 	  if($clitbidadosimovel->erro_status == "0"){
	 			$erro = "itbidadosimovel : ".$clitbidadosimovel->erro_msg;
	 			$sqlerro = true;
		  }

/*********** I N C L U I   O   P R O P R I E T A R I O(visao proprietario)   C O M O   T R A N S M I T E N T E   P R I N C I P A L   ***************************************************************/
			$rsdadosimovel =  pg_query("select z01_cxpostal, z01_email from itbimatric
																	inner join iptubase on it06_matric = j01_matric
																	inner join cgm on z01_numcgm = j01_numcgm
															where it06_guia = ".$clitbi->it01_guia);
			$numdados = pg_numrows($rsdadosimovel);
			if($numdados > 0){
				db_fieldsmemory($rsdadosimovel,0);
			}
       
		  $clitbinome->it03_guia     = $clitbi->it01_guia;
		  $clitbinome->it03_tipo     = 'T';
		  $clitbinome->it03_princ    = 'true'; 
		  $clitbinome->it03_nome     = addslashes($z01_nome);
		  $clitbinome->it03_sexo     = 'm';
		  $clitbinome->it03_cpfcnpj  = $z01_cgccpf;
		  $clitbinome->it03_endereco = addslashes($z01_ender);
		  $clitbinome->it03_numero   = $z01_numero;
		  $clitbinome->it03_compl    = $z01_compl;
		  $clitbinome->it03_cxpostal = $z01_cxpostal;
		  $clitbinome->it03_bairro   = addslashes($z01_bairro);
		  $clitbinome->it03_munic    = $z01_munic;
		  $clitbinome->it03_uf       = $z01_uf;
		  $clitbinome->it03_cep      = $z01_cep;
		  $clitbinome->it03_mail     = $z01_email; 
		  $clitbinome->incluir(null);
		  if($clitbinome->erro_status == '0'){
	 			$erro = "Proprietario itbinome : ".$clitbinome->erro_msg;
	 			$sqlerro = true;
		  }
      if($sqlerro == false) {
        $clitbinomecgm->it21_itbinome = $clitbinome->it03_seq;
		    $clitbinomecgm->it21_numcgm   = $z01_numcgm;
        $clitbinomecgm->incluir(null);
		    if($clitbinomecgm->erro_status == "0"){
	 			  $erro = "Proprietario itbinomecgm : ".$clitbinomecgm->erro_msg;
	 			  $sqlerro = true;
        }
      }
   }
   
/*********** I N C L U I   O U T R O S   P R O P R I E T A R I O S(propri)   C O M O   O U T R O S   T R A N S M I T E N T E S **************************************************************/

		$rsoutros =  pg_query("select * from propri
                 					  inner join itbimatric on it06_matric = j42_matric
                            inner join cgm        on z01_numcgm  = j42_numcgm
				               	where it06_guia = ".$clitbi->it01_guia);
		$numoutros = pg_numrows($rsoutros);
		if($numdados > 0){
        for($i=0;$i<$numoutros;$i++){            
				db_fieldsmemory($rsoutros,$i);
				$clitbinome->it03_guia     = $clitbi->it01_guia;
				$clitbinome->it03_tipo     = 'T';
				$clitbinome->it03_princ    = 'false'; 
				$clitbinome->it03_nome     = addslashes($z01_nome);
				$clitbinome->it03_sexo     = 'm';
				$clitbinome->it03_cpfcnpj  = $z01_cgccpf;
				$clitbinome->it03_endereco = addslashes($z01_ender); 
				$clitbinome->it03_numero   = $z01_numero;
				$clitbinome->it03_compl    = $z01_compl;
				$clitbinome->it03_cxpostal = $z01_cxpostal;
				$clitbinome->it03_bairro   = addslashes($z01_bairro);
				$clitbinome->it03_munic    = "";
				$clitbinome->it03_uf       = $z01_uf;
				$clitbinome->it03_cep      = $z01_cep;
				$clitbinome->it03_mail     = $z01_email; 
				$clitbinome->incluir(null);
				if($clitbinome->erro_status == '0'){
					  $erro = "Outros proprietarios itbinome : ".$clitbinome->erro_msg;
					  $sqlerro = true;
				}
				$clitbinomecgm->it21_itbinome = $clitbinome->it03_seq;
				$clitbinomecgm->it21_numcgm   = $z01_numcgm;
				$clitbinomecgm->incluir(null);
				if(isset($clitbinomecgm->erro_status) && $clitbinomecgm->erro_status == 0){
					  $erro = "Outros proprietarios itbinomecgm : ".$clitbinomecgm->erro_msg;
					  $sqlerro = true;
				}
            }
		}

/*************************************************************************************************************************************************************************/
}  
  db_fim_transacao($sqlerro);
}
if(!isset($pri) && $tipo != "rural"){
	
  include("itb1_itbi004.php");
  exit;
  
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmitbi.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?

if(isset($pri) && $tipo != "rural" && !isset($incluir) && !isset($alterar)){
	
  require_once("classes/db_iptubase_classe.php");
  	
  $cliptubase = new cl_iptubase();
  
  $aDebitosMatric = $cliptubase->consultaDebitosMatricula($j01_matric);

  if ( !empty($aDebitosMatric) ) {
  	
  	$sMsg = '\n';
  	
  	foreach ( $aDebitosMatric as $oDebitosMatric ) {
 	  $sMsg .= "* {$oDebitosMatric->k03_descr}";
 	  $sMsg .= '\n'; 	
  	}
  	 	  
  	echo " <script> 																		    ";
    echo " if( !confirm('Existe débito de: ".$sMsg."para esta matrícula, deseja continuar?')){  ";
    echo "    parent.location.href='itb1_itbi001.php?tipo=urbano';								";
	echo " }																					";
	echo " </script>																			";

  }
}


if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($sqlerro == true){
    db_msgbox($erro);
    if($clitbi->erro_campo!=""){
      echo "<script> document.form1.".$clitbi->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clitbi->erro_campo.".focus();</script>";
    };
  }else{
    db_msgbox($ok);
    echo "<script>
            parent.iframe_itbi.location.href = 'itb1_itbi002.php?chavepesquisa=".$clitbi->it01_guia."&abas=1'; 
			//parent.document.formaba.comp.disabled = false;
            parent.document.formaba.compnome.disabled = false;
            parent.document.formaba.old.disabled = false;
		    parent.mo_camada('comp');
          </script>";
  };
};
?>