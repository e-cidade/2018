<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once('libs/db_utils.php');

db_postmemory($_POST);
db_postmemory($_GET);

$clcalendario       = new cl_calendario;
$clcalendario->rotulo->label();
$clcalendarioescola = new cl_calendarioescola;
$db_opcao           = 1;
$db_opcao1          = 1;
$db_botao           = true;

if( isset( $incluir ) ) {
  
  if( strlen( $ed52_i_ano ) < 4 ) {
    
    db_msgbox("Ano deve ser preenchido com 4 dígitos!");
    $erro_ano = true;
  } else {

    db_inicio_transacao();

    $clcalendario->ed52_c_aulasabado = 'N';
    $clcalendario->ed52_c_passivo    = 'N';
    $clcalendario->incluir($ed52_i_codigo);

    db_fim_transacao();
    $db_botao = false;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Inclusão de Calendário</b></legend>
    <?include("forms/db_frmcalendario.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?php
if( @$erro_ano == true ) {

  echo "<script> document.form1.ed52_i_ano.style.backgroundColor='#99A9AE';</script>";
  echo "<script> document.form1.ed52_i_ano.focus();</script>";
}

if( isset( $incluir ) ) {
  
  if( @$erro_ano == false ) {

    if( $clcalendario->erro_status == "0" ) {

      $clcalendario->erro( true, false );
      $db_botao = true;

      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

      if( $clcalendario->erro_campo != "" ) {

        echo "<script> document.form1.".$clcalendario->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clcalendario->erro_campo.".focus();</script>";
      }
    } else {

      $clcalendario->erro(true,false);
      $ultimo = $clcalendario->ed52_i_codigo;

      $Y = $ed52_i_ano;// Ano
      $G = ($Y % 19) + 1; // Numero Áureo
      $C = intval(($Y/100) + 1); // Seculo
      $X = intval(((3*$C)/4) - 12); // Primeira Correção
      $Z = intval((((8*$C)+5)/25) -5);//Epacta
      $E = ((11*$G) + 20 + $Z - $X) % 30;

      if( ( ( $E == 25 ) AND ( $G > 11 ) ) OR ( $E == 24 ) ) {
        $E+=1;
      }

      $N = 44 - $E; // Lua Cheia
      if( $N < 21 ) {
        $N+=30;
      }

      $D = intval( ( ( 5 * $Y ) / 4 ) ) - ( $X + 10 );//Domingo
      $N = ( $N + 7 ) - ( $D + $N ) % 7;

      if( $N > 31 ) {

        $diapascoa = $N - 31;
        $diames    = 4;
      } else {

        $diapascoa = $N;
        $diames    = 3;
      }

      //CARNAVAL
      $datas = "CARNAVAL|".date("Y-m-d",mktime (0, 0, 0, $diames , $diapascoa-47, $Y))."|N#";
      //PAIXÃO DE CRISTO
      $datas .= "PAIXÃO DE CRISTO|".date("Y-m-d",mktime (0, 0, 0, $diames , $diapascoa-2, $Y))."|N#";
      //PÁSCOA
      $datas .= "PÁSCOA|".date("Y-m-d",mktime (0, 0, 0, $diames , $diapascoa, $Y))."|N#";
      //CORPUS CHRISTI
      $datas .= "CORPUS CHRISTI|".date("Y-m-d",mktime (0, 0, 0, $diames , $diapascoa+60, $Y))."|N#";
      //CONFRATERNIZAÇÂO UNIVERSAL
      $datas .= "CONFRATERNIZAÇÂO UNIVERSAL|".$ed52_i_ano."-01-01|N#";
      //TIRADENTES
      $datas .= "TIRADENTES|".$ed52_i_ano."-04-21|N#";
      //DIA DO TRABALHO
      $datas .= "DIA DO TRABALHO|".$ed52_i_ano."-05-01|N#";
      //INDEPENDÊNCIA DO BRASIL
      $datas .= "INDEPENDÊNCIA DO BRASIL|".$ed52_i_ano."-09-07|N#";
      //NOSSA SENHORA APARECIDA
      $datas .= "NOSSA SENHORA APARECIDA|".$ed52_i_ano."-10-12|N#";
      //FINADOS
      $datas .= "FINADOS|".$ed52_i_ano."-11-02|N#";
      //PROCLAMAÇÃO DA REPÚBLICA
      $datas .= "PROCLAMAÇÃO DA REPÚBLICA|".$ed52_i_ano."-11-15|N#";
      //NATAL
      $datas .= "NATAL|".$ed52_i_ano."-12-25|N";

      $array_datas   = explode( "#", $datas );
      $ed52_d_inicio = $ed52_d_inicio_ano . "-" . $ed52_d_inicio_mes . "-" . $ed52_d_inicio_dia;
      $ed52_d_fim    = $ed52_d_fim_ano . "-" . $ed52_d_fim_mes . "-" . $ed52_d_fim_dia;

      for( $x = 0; $x < count( $array_datas ); $x++ ) {

        $array_dados = explode("|",$array_datas[$x]);
        //inclui feriado
        $result1       = @db_query("select nextval('feriado_ed54_i_codigo_seq')");
        $ed54_i_codigo = pg_result($result1,0,0);

        $dd = substr( $array_dados[1], 8, 2 );
        $mm = substr( $array_dados[1], 5, 2 );
        $aa = substr( $array_dados[1], 0, 4 );

        $diasemana = date("w",mktime (0, 0, 0, $mm , $dd, $aa));

        if( $diasemana == 0 ) {
          $diasemana = "DOMINGO";
        }

        if( $diasemana == 1 ) {
          $diasemana = "SEGUNDA";
        }

        if( $diasemana == 2 ) {
          $diasemana = "TERÇA";
        }

        if( $diasemana == 3 ) {
          $diasemana = "QUARTA";
        }

        if( $diasemana == 4 ) {
          $diasemana = "QUINTA";
        }

        if( $diasemana == 5 ) {
          $diasemana = "SEXTA";
        }

        if( $diasemana == 6 ) {
          $diasemana = "SÁBADO";
        }

        $sql1 = "INSERT INTO feriado
                 VALUES($ed54_i_codigo,$ultimo,'$array_dados[0]','$diasemana','$array_dados[1]','$array_dados[2]',1)";
        $query1 = db_query($sql1);
      }

      /**
       * So vincula o calendario para a escola se estiver logado no modulo escola
       */
      if( db_getsession("DB_modulo") == 1100747 ) {

        db_inicio_transacao();

        $ed38_i_codigo = "";
        $clcalendarioescola->ed38_i_escola     = db_getsession("DB_coddepto");
        $clcalendarioescola->ed38_i_calendario = $ultimo;
        $clcalendarioescola->incluir($ed38_i_codigo);

        db_fim_transacao();
      }

      ?>
      <script>
       top.corpo.iframe_a1.location.href='edu1_calendario002.php?chavepesquisa=<?=$ultimo?>';
      </script>
      <?php
    }
  }
}