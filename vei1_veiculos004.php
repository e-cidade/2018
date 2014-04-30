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
include("classes/db_veiculos_classe.php");
include("classes/db_veicresp_classe.php");
include("classes/db_veicpatri_classe.php");
include("classes/db_veicparam_classe.php");
include("classes/db_veiculoscomb_classe.php");
include("classes/db_veictipoabast_classe.php");
include("classes/db_veiccentral_classe.php");

db_postmemory($HTTP_POST_VARS);

$clveiculos      = new cl_veiculos;
$clveicresp      = new cl_veicresp;
$clveicpatri     = new cl_veicpatri;
$clveicparam     = new cl_veicparam;
$clveiculoscomb  = new cl_veiculoscomb;
$clveictipoabast = new cl_veictipoabast;
$clveiccentral   = new cl_veiccentral;

$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;	
  db_inicio_transacao(); 

if (isset($ve01_placa) && $ve01_placa==null){
    $erro_msg = "Informar a placa do veiculo.Verifique.";
    $sqlerro  = true;
    db_msgbox($erro_msg);
   
}


if (isset($ve01_veiccadmarca) && $ve01_veiccadmarca=="0"){
      $erro_msg = "Informar a marca .Verifique.";
      $sqlerro  = true;
      db_msgbox($erro_msg);
}


if (isset($ve01_veiccadmodelo) && $ve01_veiccadmodelo==null){
        $erro_msg = "Informar o modelo .Verifique.";
        $sqlerro  = true;
        db_msgbox($erro_msg);
}

if (isset($ve01_veiccadcor) && $ve01_veiccadcor=="0"){
          $erro_msg = "Informar a cor .Verifique.";
          $sqlerro  = true;
          db_msgbox($erro_msg);
}

if (isset($ve01_chassi) && $ve01_chassi==null){
            $erro_msg = "Informar o chassi .Verifique.";
            $sqlerro  = true;
            db_msgbox($erro_msg);
}

if (isset($ve01_ranavam) && $ve01_ranavam==null){
              $erro_msg = "Informar o renavam .Verifique.";
              $sqlerro  = true;
              db_msgbox($erro_msg);
}

if (isset($ve01_placanum) && $ve01_placanum==null){
          $erro_msg = "Informar a placa em número .Verifique.";
          $sqlerro  = true;
          db_msgbox($erro_msg);
}

if (isset($ve01_certif) && $ve01_certif==null){
          $erro_msg = "Informar o número do certificado .Verifique.";
          $sqlerro  = true;
          db_msgbox($erro_msg);
}

if (isset($ve01_quantpotencia) && $ve01_quantpotencia==null){
            $erro_msg = "Informar quantidade de potência .Verifique.";
            $sqlerro  = true;
            db_msgbox($erro_msg);
}


if (isset($ve01_veictipoabast) && $ve01_veictipoabast==null){
          $erro_msg = "Informar o tipo de abastecimento .Verifique.";
          $sqlerro  = true;
          db_msgbox($erro_msg);
}

if (isset($ve01_medidaini) && $ve01_medidaini==null){
          $erro_msg = "Informar a medida inicial .Verifique.";
          $sqlerro  = true;
          db_msgbox($erro_msg);
}

if (isset($ve01_quantcapacidad) && $ve01_quantcapacidad==null){
          $erro_msg = "Informar a quantidade capacidade .Verifique.";
          $sqlerro  = true;
          db_msgbox($erro_msg);
}


if (isset($ve01_dtaquis) && $ve01_dtaquis==null){
          $erro_msg = "Informar a data de aquisição .Verifique.";
          $sqlerro  = true;
          db_msgbox($erro_msg);
}

if (isset($cod_comb) && $cod_comb==null){
          $erro_msg = "Informar o combustível .Verifique.";
          $sqlerro  = true;
          db_msgbox($erro_msg);
}

if (isset($ve01_anofab) && $ve01_anofab==null){
          $erro_msg = "Informar o ano de fabricação .Verifique.";
          $sqlerro  = true;
          db_msgbox($erro_msg);
}

if (isset($ve01_anomod) && $ve01_anomod==null){
          $erro_msg = "Informar o ano do modelo .Verifique.";
          $sqlerro  = true;
          db_msgbox($erro_msg);
}


  if ($sqlerro == false){
    $clveiculos->ve01_ativo = 1;

    $result = $clveiculos->sql_record($clveiculos->sql_query_file(null,"*",null,"ve01_placa = '$ve01_placa'"));
    if ($clveiculos->numrows > 0){
      $sqlerro  = true;
      $erro_msg = "Placa já cadastrada para outro veículo. Verifique.";
      $clveiculos->erro_campo = "ve01_placa";
    }

    $result = $clveiculos->sql_record($clveiculos->sql_query_file(null,"*",null,"ve01_ranavam = $ve01_ranavam"));
    if ($clveiculos->numrows > 0){
      $sqlerro  = true;
      $erro_msg = "Renavam já cadastrado para outro veículo. Verifique.";
      $clveiculos->erro_campo = "ve01_ranavam";
    }

    $result = $clveiculos->sql_record($clveiculos->sql_query_file(null,"*",null,"ve01_chassi = '$ve01_chassi'"));
    if ($clveiculos->numrows > 0){
      $sqlerro  = true;
      $erro_msg = "Chassi já cadastrado para outro veículo. Verifique.";
      $clveiculos->erro_campo = "ve01_chassi";
    }

    if ($sqlerro==false){
      $clveiculos->incluir(null);
      $erro_msg=$clveiculos->erro_msg;
      if ($clveiculos->erro_status=="0"){
      	$sqlerro=true;
      }

      if ($sqlerro == false){
        $ve01_codigo       = $clveiculos->ve01_codigo;
        $vetor_comb        = split(",",$cod_comb);
        $vetor_comb_padrao = split(",",$comb_padrao);

        $inc_comb          = array(array("ve06_veiculos","ve06_veiccadcomb","ve06_padrao"));
        $inc_contador      = 0;
        
        for($x = 0; $x < count($vetor_comb); $x++){
          $inc_comb["ve06_veiculos"][$inc_contador]    = $ve01_codigo;
          $inc_comb["ve06_veiccadcomb"][$inc_contador] = $vetor_comb[$x];
          for($xx = $x; $xx < count($vetor_comb_padrao); $xx++){
            $inc_comb["ve06_padrao"][$inc_contador] = $vetor_comb_padrao[$xx];
            break;
          }

          $inc_contador++;
        }

        for($x = 0; $x < $inc_contador; $x++){
          $clveiculoscomb->ve06_veiculos    = $inc_comb["ve06_veiculos"][$x];
          $clveiculoscomb->ve06_veiccadcomb = $inc_comb["ve06_veiccadcomb"][$x];

          if ($inc_comb["ve06_padrao"][$x] == 1){
            $padrao = "true";
          } else {
            $padrao = "false";
          }

          $clveiculoscomb->ve06_padrao = $padrao;
          $clveiculoscomb->incluir(null);
          if ($clveiculoscomb->erro_status == 0){
            $sqlerro  = true;
            $erro_msg = $clveiculoscomb->erro_msg;
            break;
          }
        }
      }
    }

    if ($sqlerro==false){
    	$clveicresp->ve02_veiculo=$clveiculos->ve01_codigo;
    	$clveicresp->incluir(null);
    	if ($clveicresp->erro_status=="0"){
    		$sqlerro=true;
    		$erro_msg=$clveicresp->erro_msg;
    	}
    } 
/*
    if ($sqlerro==false){
      $clveiccentral->ve40_veiculos       = $clveiculos->ve01_codigo;
      $clveiccentral->ve40_veiccadcentral = $ve40_veiccadcentral;

      $clveiccentral->incluir(null);
      if ($clveiccentral->erro_status=="0"){
        $sqlerro  = true;
        $erro_msg = $clveiccentral->erro_msg;
      }
    }
 */ 
    
    if ($sqlerro==false){
    	if (isset($ve03_bem)&&$ve03_bem){
    		$clveicpatri->ve03_veiculo=$clveiculos->ve01_codigo;
    		$clveicpatri->incluir(null);
    		if ($clveicresp->erro_status=="0"){
    		  $sqlerro=true;  		
    		  $erro_msg=$clveicresp->erro_msg;
    		}  		
    	}
    } 
  }

  db_fim_transacao($sqlerro);
}

if (isset($codveictipoabast) && trim($codveictipoabast)!=""){
  $result_veictipoabast = $clveictipoabast->sql_record($clveictipoabast->sql_query($codveictipoabast,"ve07_sigla"));
  if ($clveictipoabast->numrows > 0){
    db_fieldsmemory($result_veictipoabast,0);
  }
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
	include("forms/db_frmveiculos.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ve01_placa",true,1,"ve01_placa",true);
</script>
<?
if(isset($incluir)){
  if($clveiculos->erro_status=="0"||$sqlerro==true){
    db_msgbox($erro_msg);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clveiculos->erro_campo!=""){
      echo "<script> document.form1.".$clveiculos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clveiculos->erro_campo.".focus();</script>";
    }
  }else{
    db_msgbox($erro_msg);
    db_redireciona("vei1_veiculos005.php?chavepesquisa=$ve01_codigo&liberaaba=true");
  }
}
?>