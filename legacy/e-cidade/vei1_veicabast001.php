<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
require(modification("libs/db_utils.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_veiculos_classe.php"));
include(modification("classes/db_veicabast_classe.php"));
include(modification("classes/db_veicabastposto_classe.php"));
include(modification("classes/db_veicabastpostoempnota_classe.php"));
include(modification("classes/db_veicabastretirada_classe.php"));
include(modification("classes/db_veicparam_classe.php"));
include(modification("classes/db_veicretirada_classe.php"));
require(modification("libs/db_app.utils.php"));
db_app::import("veiculos.*");
db_postmemory($HTTP_POST_VARS);

$clveiculos              = new cl_veiculos;
$clveicabast             = new cl_veicabast;
$clveicabastposto        = new cl_veicabastposto;
$clveicabastpostoempnota = new cl_veicabastpostoempnota;
$clveicabastretirada     = new cl_veicabastretirada;
$clveicparam             = new cl_veicparam;
$clveicretirada          = new cl_veicretirada;
$db_opcao = 1;
$db_botao = true;

$sqlerro=false;

if (isset($ve60_datasaida) && $ve60_datasaida != "") {
  
  $aData = explode("/", $ve60_datasaida);

  if (count($aData) >= 3) {

    $ve60_datasaida_dia = $aData[0];
    $ve60_datasaida_mes = $aData[1];
    $ve60_datasaida_ano = $aData[2];
  }
}



if (isset($incluir)) {
  $medida     = $ve70_medida;
  $dataabast  = implode("-",array_reverse(explode("/",$ve70_dtabast)));
  $sDataBanco = $ve70_dtabast_ano."-".$ve70_dtabast_mes."-".$ve70_dtabast_dia;
  $passa=false;
  //último abastecimento
  $clveicabast->sql_query_file_anula(null,"ve70_medida,ve74_codigo"," ve70_dtabast desc ,ve70_medida desc limit 1","ve70_veiculos=$ve70_veiculos and  ve74_codigo is null ");
  $result_abast=$clveicabast->sql_record($clveicabast->sql_query_file_anula(null,"ve70_medida,ve74_codigo"," ve70_dtabast desc ,ve70_medida desc limit 1","ve70_veiculos=$ve70_veiculos and  ve74_codigo is null "));
  
  if ($clveicabast->numrows>0 ) {
    $oAbast       = db_utils::fieldsMemory($result_abast,0);
    $ve70_medida  = $oAbast->ve70_medida;
  }
  
  if (isset($ve70_medida) and $ve70_medida==0) {
    $medidazero=true;
  } else {
    $medidazero=false;
  }
  
  if ($medidazero==false) {
    
    if (!isset($confirmamedida)) {
      
      //verifica se a última medida é zero
      $result_abast=$clveicabast->sql_record($clveicabast->sql_query_file_anula(null,"ve70_medida,ve74_codigo,ve70_dtabast,ve70_codigo as ve70codigo"," ve70_dtabast desc ,ve70_medida desc limit 1","ve70_veiculos=$ve70_veiculos and  ve74_codigo is null"));
      
      if ($clveicabast->numrows>0 ) {
        $oAbast       = db_utils::fieldsMemory($result_abast,0);
        $ve70_medida0 = $oAbast->ve70_medida;
        $ve70_codigo0 = $oAbast->ve70codigo;
        
      }
      
      //abastecimento anterior
      //verifica se a existem vários registros com a mesma data
      @$result_abast1=$clveicabast->sql_record($clveicabast->sql_query_file_anula(null,"ve70_medida,ve74_codigo,ve70_dtabast,ve70_codigo as ve70codigo","ve70_dtabast desc ,ve70_medida ","ve70_veiculos=$ve70_veiculos and ve70_dtabast = $databanco and ve74_codigo is null"));
      $iNumrows=$clveicabast->numrows;
      if ($iNumrows > 1 ) {
        
        $result_abast1=$clveicabast->sql_record($clveicabast->sql_query_file_anula(null,"ve70_medida,ve74_codigo,ve70_dtabast,ve70_codigo as ve70codigo","ve70_dtabast asc,ve70_medida asc limit 1","ve70_veiculos=$ve70_veiculos and ve70_dtabast = $databanco and ve74_codigo is null"));
        $iNumrows=$clveicabast->numrows;
        if ($clveicabast->numrows>0 ) {
          $oAbast1       = db_utils::fieldsMemory($result_abast1,0);
          $ve70_codigo1  = $oAbast1->ve70codigo;
          $ve70_medida1  = $oAbast1->ve70_medida;
          $ve70_dtabast1 = $oAbast1->ve70_dtabast;
        }
        $passa=true;
      } else {
        
        if (!isset($sDataParaFazerTeste)){
          $sDataParaFazerTeste = "";
        }
        
        $result_abast1 = $clveicabast->sql_record($clveiculos->sql_query_ultimamedida($ve70_veiculos, $sDataParaFazerTeste));
        if ($clveicabast->numrows>0 ) {
          $oAbast1       = db_utils::fieldsMemory($result_abast1,0);
          $ve70_codigo1  = "";
          $ve70_medida1  = $oAbast1->ultimamedida;
          $ve70_dtabast1 = $oAbast1->data;
        }
        
      }
      
      
      //abastecimento posterior
      //se tiver vários registros com a mesma data passa por aqui.
      if ($passa==true) {
        
        if (!isset($databanco)) {
          $databanco = "";
        }
        
        $result_abast3=$clveicabast->sql_record($clveicabast->sql_query_file_anula(null,"max(ve70_medida) as ve70_medida,ve74_codigo,ve70_dtabast,ve70_codigo as ve70codigo",null,"ve70_veiculos=$ve70_veiculos and  ve70_dtabast > $databanco and ve74_codigo is null group by  ve70_medida, ve70_dtabast,ve74_codigo,ve70_codigo limit 1"));
        if ($clveicabast->numrows>0 ) {
          $oAbast3       = db_utils::fieldsMemory($result_abast3,0);
          $ve70_codigo3  = $oAbast3->ve70codigo;
          $ve70_medida3  = $oAbast3->ve70_medida;
          $ve70_dtabast3 = $oAbast3->ve70_dtabast;
        }
      } else {
        @$result_abast3=$clveicabast->sql_record($clveicabast->sql_query_file_anula(null,"ve70_medida,ve74_codigo,ve70_dtabast,ve70_codigo as ve70codigo","ve70_dtabast,ve70_medida limit 1","ve70_veiculos=$ve70_veiculos and  ve70_dtabast > $databanco and ve74_codigo is null "));
        if ($clveicabast->numrows>0 ) {
          $oAbast3       = db_utils::fieldsMemory($result_abast3,0);
          $ve70_codigo3  = $oAbast3->ve70codigo;
          $ve70_medida3  = $oAbast3->ve70_medida;
          $ve70_dtabast3 = $oAbast3->ve70_dtabast;
        }
      }
      
      if (isset($ve70_dtabast3) && $ve70_dtabast3!=0) {
        
        
        $dataabast     = db_strtotime($dataabast);
        $ve70_dtabast1 = db_strtotime($ve70_dtabast1);
        $ve70_dtabast3 = db_strtotime($ve70_dtabast3);
        
        if (isset($ve70_dtabast1) && isset($ve70_dtabast3) && isset($dataabast) && ($dataabast < $ve70_dtabast1) && ($dataabast > $ve70_dtabast3)) {
          $sqlerro=true;
          db_msgbox("Medida inválida para esta data");
          $erro_msg="Não foi possível incluir.";
          
          
        } else if (isset($medida) && isset($ve70_medida1) && isset($ve70_medida3) && ($medida > $ve70_medida1) && ($medida > $ve70_medida3) ) {
          $sqlerro=true;
          db_msgbox("Medida inválida");
          $erro_msg="Não foi possível incluir.";
          
          
        } else if (isset($medida) && isset($ve70_medida1) && isset($ve70_medida3) && ($medida < $ve70_medida1) && ($medida < $ve70_medida3) ) {
          $sqlerro=true;
          db_msgbox("Medida inválida");
          $erro_msg="Não foi possível incluir.";
        }
        
      } else if (!isset($ve70_dtabast3) && !isset($ve70_medida3)  && ($medida < $ve70_medida1)  ) {
        $sqlerro=true;
        db_msgbox("Medida inválida");
        $erro_msg="Não foi possível incluir.";
      }
      
    }
  }
  if (isset($sel_proprio) && $sel_proprio==2) {
    if ($ve70_valor == "") {
      db_msgbox("Informar o valor abastecido.");
      $sqlerro=true;
      $erro_msg="Não foi possível incluir.";
    }
    if ($ve70_vlrun == '') {
      db_msgbox("Informar o valor do litro.");
      $sqlerro=true;
      $erro_msg="Não foi possível incluir.";
    }
    if ($ve71_nota =="" && $empnota == "" && $e69_codnota=="") {
      db_msgbox("Informar a nota.");
      $sqlerro=true;
      $erro_msg="Não foi possível incluir.";
    }
    
  }
  
}

if (isset($incluir) && $self != "") {
  if ($sqlerro==false) {
    db_inicio_transacao();
    
    if (isset($sel_proprio) && trim($sel_proprio) != "") {
      if ($sel_proprio == 0 && (isset($ve71_veiccadposto) &&
      (trim($ve71_veiccadposto) == "" || trim($ve71_veiccadposto) != ""))) {
        $erro_msg = "Deve-se optar por escolher um Tipo de Posto";
        $clveicabast->erro_campo = "ve71_veiccadposto";
        $sqlerro = true;
      }
    }
    
    if ($sqlerro == false) {
      $clveicabast->ve70_usuario = db_getsession("DB_id_usuario");
      $clveicabast->ve70_data    = date("Y-m-d",db_getsession("DB_datausu"));
      $clveicabast->ve70_hora    = $ve70_hora;//db_hora();
      $clveicabast->ve70_ativo="1";
      $clveicabast->incluir($ve70_codigo);
      $erro_msg=$clveicabast->erro_msg;
      if ($clveicabast->erro_status=="0") {
        $sqlerro=true;
      }
    }
    
    if ($sqlerro==false) {
      if (isset($posto_proprio) && trim($posto_proprio)!="") {
        if ($posto_proprio == 2) {
          if (isset($empnota) && $empnota!=null) {
            $ve71nota="";
          } else {
            $ve71nota=$e69_codnota;
          }
          $clveicabastposto->ve71_veicabast=$clveicabast->ve70_codigo;
          $clveicabastposto->ve71_nota     =$ve71nota;
          $clveicabastposto->incluir(null);
          if ($clveicabastposto->erro_status=="0") {
            $sqlerro=true;
            $erro_msg=$clveicabastposto->erro_msg;
          }
        } else {
          $clveicabastposto->ve71_veicabast=$clveicabast->ve70_codigo;
          $clveicabastposto->incluir(null);
          if ($clveicabastposto->erro_status=="0") {
            $sqlerro=true;
            $erro_msg=$clveicabastposto->erro_msg;
          }
        }
        
      } else {
        $clveicabastposto->ve71_veicabast=$clveicabast->ve70_codigo;
        $clveicabastposto->incluir(null);
        if ($clveicabastposto->erro_status=="0") {
          $sqlerro=true;
          $erro_msg=$clveicabastposto->erro_msg;
        }
      }
    }    
    
    if ($sqlerro==false) {
      if (isset($empnota) && $empnota!="") {
        $clveicabastpostoempnota->ve72_veicabastposto=$clveicabastposto->ve71_codigo;
        $clveicabastpostoempnota->ve72_empnota=$e69_codnota;
        $clveicabastpostoempnota->incluir(null);
        if ($clveicabastpostoempnota->erro_status=="0") {
          $sqlerro=true;
          $erro_msg=$clveicabastpostoempnota->erro_msg;
        }
      }
    }    
    
    if ($sqlerro==false) {

      $clveicabastretirada->ve73_veicabast=$clveicabast->ve70_codigo;
      $clveicabastretirada->incluir(null);

      if ($clveicabastretirada->erro_status=="0") {

        $sqlerro=true;
        $erro_msg=$clveicabastretirada->erro_msg;
      } 
    }

    if (isset($posto_proprio)) {
      $sel_proprio = $posto_proprio;
    }
    
    
    db_fim_transacao($sqlerro);
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
<body bgcolor=#CCCCCC  style='margin-top: 25px' leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
	<?
	include(modification("forms/db_frmveicabast.php"));
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ve70_veiculos",true,1,"ve70_veiculos",true);
</script>
<?
if(isset($incluir) && $self != ""){
  if($clveicabast->erro_status=="0"||$sqlerro==true){
    //$clveicabast->erro(true,false);
    db_msgbox($erro_msg);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clveicabast->erro_campo!=""){
      echo "<script> document.form1.".$clveicabast->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clveicabast->erro_campo.".focus();</script>";
    }
  }else{
    $clveicabast->erro(true,true);
  }
}
?>