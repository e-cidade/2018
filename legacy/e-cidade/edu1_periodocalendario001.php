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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory( $_POST );

$clcalendario        = new cl_calendario;
$clperiodocalendario = new cl_periodocalendario;
$clperiodoavaliacao  = new cl_periodoavaliacao;
$clregencia          = new cl_regencia;

$db_opcao  = 1;
$db_opcao1 = 1;
$db_botao  = true;
$erro_data = false;

if( isset( $incluir ) ) {

  $data_inicio_dig = $ed53_d_inicio_ano."-".$ed53_d_inicio_mes."-".$ed53_d_inicio_dia;
  $data_fim_dig    = $ed53_d_fim_ano."-".$ed53_d_fim_mes."-".$ed53_d_fim_dia;

  $sCampos = "ed09_i_sequencia, ed09_c_somach";
  $sWhere  = " ed09_i_codigo = {$ed53_i_periodoavaliacao}";
  $sql2    = $clperiodoavaliacao->sql_query( "", $sCampos, "", $sWhere );
  $result2 = $clperiodoavaliacao->sql_record( $sql2 );
  db_fieldsmemory( $result2, 0 );

  if( $ed09_c_somach == "N" ) {

    $sCamposCalendario = "ed52_d_inicio, ed52_d_resultfinal as ed52_d_fim";
    $sql               = $clcalendario->sql_query( "", $sCamposCalendario, "", " ed52_i_codigo = {$ed53_i_calendario}" );
    $result            = $clcalendario->sql_record($sql);
    db_fieldsmemory( $result, 0 );
  } else {

    $sql    = $clcalendario->sql_query( "", "ed52_d_inicio, ed52_d_fim", "", " ed52_i_codigo = {$ed53_i_calendario}" );
    $result = $clcalendario->sql_record($sql);
    db_fieldsmemory( $result, 0 );
  }

  if( $data_inicio_dig < $ed52_d_inicio ) {

    db_msgbox("Data inicial de {$ed09_c_descr} é anterior a data inicial das aulas do calendário {$ed52_c_descr}!");
    $erro_data  = true;
    $campo_erro = "inicio";
  } else if( $data_fim_dig > $ed52_d_fim ) {

    db_msgbox("Data final de {$ed09_c_descr} é posterior a data final das aulas do calendário {$ed52_c_descr}!");
    $erro_data  = true;
    $campo_erro = "fim";
  } else if( $data_inicio_dig > $ed52_d_fim ) {

    db_msgbox("Data inicial de {$ed09_c_descr} é posterior a data final das aulas do calendário {$ed52_c_descr}!");
    $erro_data  = true;
    $campo_erro = "inicio";
  } else if( $data_fim_dig < $ed52_d_inicio ) {

    db_msgbox("Data final de {$ed09_c_descr} é anterior a data inicial das aulas do calendário {$ed52_c_descr}!");
    $erro_data  = true;
    $campo_erro = "fim";
  } else if( $data_inicio_dig > $data_fim_dig ) {

    db_msgbox("Data final de {$ed09_c_descr} é anterior a data inicial!");
    $erro_data  = true;
    $campo_erro = "fim";
  } else {

    $sCamposPeriodo1 = "ed53_d_inicio as inicio, ed53_d_fim as fim, ed09_c_descr as existente";
    $sWherePeriodo1  = " ed53_i_calendario = {$ed53_i_calendario} and ed09_i_sequencia < {$ed09_i_sequencia}";
    $sql1            = $clperiodocalendario->sql_query( "", $sCamposPeriodo1, "", $sWherePeriodo1 );
    $result1         = $clperiodocalendario->sql_record($sql1);

    if( $clperiodocalendario->numrows > 0 ) {

      db_fieldsmemory( $result1, 0 );

      if( $data_inicio_dig <= @$fim ) {

        db_msgbox("Data inicial de {$ed09_c_descr} é anterior ou igual a data final do {$existente}!");
        $erro_data  = true;
        $campo_erro = "inicio";
      }
    }

    $sCamposPeriodo3 = "ed53_d_inicio as inicio,ed53_d_fim as fim,ed09_c_descr as existente";
    $WherePeriodo3   = " ed53_i_calendario = {$ed53_i_calendario} and ed09_i_sequencia > {$ed09_i_sequencia}";
    $sql3            = $clperiodocalendario->sql_query( "", $sCamposPeriodo3, "", $WherePeriodo3 );
    $result3         = $clperiodocalendario->sql_record($sql3);

    if( $clperiodocalendario->numrows > 0 ) {

      db_fieldsmemory( $result3, 0 );

      if( $data_fim_dig >= @$inicio ) {

        db_msgbox("Data final de {$ed09_c_descr} é posterior ou igual a data inicial do {$existente}!");
        $erro_data  = true;
        $campo_erro = "fim";
      }
    }

    if( @$erro_data == false ) {

      db_inicio_transacao();

      $clperiodoescola->ed53_d_inicio = $data_inicio_dig;
      $clperiodoescola->ed53_d_fim    = $data_fim_dig;
      $clperiodocalendario->incluir($ed53_i_codigo);

      db_fim_transacao();
    }
  }
}

if( isset( $alterar ) ) {

  $data_inicio_dig = $ed53_d_inicio_ano."-".$ed53_d_inicio_mes."-".$ed53_d_inicio_dia;
  $data_fim_dig    = $ed53_d_fim_ano."-".$ed53_d_fim_mes."-".$ed53_d_fim_dia;

  $sCamposPeriodo2 = "ed09_i_sequencia, ed09_c_somach";
  $sWherePeriodo2  = " ed09_i_codigo = $ed53_i_periodoavaliacao";
  $sql2            = $clperiodoavaliacao->sql_query( "", $sCamposPeriodo2, "", $sWherePeriodo2 );
  $result2         = $clperiodoavaliacao->sql_record($sql2);

  db_fieldsmemory( $result2, 0 );

  if( $ed09_c_somach == "N" ) {

    $sCamposCalendario = "ed52_d_inicio, ed52_d_resultfinal as ed52_d_fim";
    $sql               = $clcalendario->sql_query( "", $sCamposCalendario, "", " ed52_i_codigo = {$ed53_i_calendario}" );
    $result            = $clcalendario->sql_record($sql);

    db_fieldsmemory( $result, 0 );
  } else {

    $sql    = $clcalendario->sql_query( "", "ed52_d_inicio, ed52_d_fim", "", " ed52_i_codigo = {$ed53_i_calendario}" );
    $result = $clcalendario->sql_record($sql);
    db_fieldsmemory( $result, 0 );
  }

  if( $data_inicio_dig < $ed52_d_inicio ) {

    db_msgbox("Data inicial de {$ed09_c_descr} é anterior a data inicial das aulas do calendário {$ed52_c_descr}!");
    $erro_data  = true;
    $campo_erro = "inicio";
  } else if( $data_fim_dig > $ed52_d_fim ) {

    db_msgbox("Data final de {$ed09_c_descr} é posterior a data final das aulas do calendário {$ed52_c_descr}!");
    $erro_data  = true;
    $campo_erro = "fim";
  } else if( $data_inicio_dig > $ed52_d_fim ) {

    db_msgbox("Data inicial de {$ed09_c_descr} é posterior a data final das aulas do calendário {$ed52_c_descr}!");
    $erro_data  = true;
    $campo_erro = "inicio";
  } else if( $data_fim_dig < $ed52_d_inicio ) {

    db_msgbox("Data final de {$ed09_c_descr} é anterior a data inicial das aulas do calendário {$ed52_c_descr}!");
    $erro_data  = true;
    $campo_erro = "fim";
  } else if( $data_inicio_dig > $data_fim_dig ) {
    
    db_msgbox("Data final de {$ed09_c_descr} é menor a data inicial!");
    $erro_data  = true;
    $campo_erro = "fim";
  } else {

    $sCamposPeriodo1 = "ed53_d_inicio as inicio,ed53_d_fim as fim,ed09_c_descr as existente";
    $sWherePeriodo1  = " ed53_i_calendario = {$ed53_i_calendario} and ed09_i_sequencia < {$ed09_i_sequencia}";
    $sql1            = $clperiodocalendario->sql_query( "", $sCamposPeriodo1, "", $sWherePeriodo1 );
    $result1         = $clperiodocalendario->sql_record( $sql1 );

    if( $clperiodocalendario->numrows > 0 ) {

      db_fieldsmemory( $result1, 0 );

      if( $data_inicio_dig <= @$fim ) {

        db_msgbox("Data inicial de {$ed09_c_descr} é anterior ou igual a data final do {$existente}!");
        $erro_data  = true;
        $campo_erro = "inicio";
      }
    }

    $sCampoPeriodo3 = "ed53_d_inicio as inicio, ed53_d_fim as fim, ed09_c_descr as existente";
    $sWherePeriodo3 = " ed53_i_calendario = {$ed53_i_calendario} and ed09_i_sequencia > {$ed09_i_sequencia}";
    $sql3           = $clperiodocalendario->sql_query( "", $sCampoPeriodo3, "", $sWherePeriodo3 );
    $result3        = $clperiodocalendario->sql_record( $sql3 );

    if( $clperiodocalendario->numrows > 0 ) {

      db_fieldsmemory( $result3, 0 );

      if( $data_fim_dig >= @$inicio ) {

        db_msgbox("Data final de {$ed09_c_descr} é posterior ou igual a data inicial do {$existente}!");
        $erro_data  = true;
        $campo_erro = "fim";
      }
    }

    if( @$erro_data == false ) {

      db_inicio_transacao();

      $db_opcao = 2;
      $clperiodoescola->ed53_d_inicio = $data_inicio_dig;
      $clperiodoescola->ed53_d_fim    = $data_fim_dig;
      $clperiodocalendario->alterar($ed53_i_codigo);

      db_fim_transacao();
    }
  }
}

if( isset( $excluir ) ) {

  db_inicio_transacao();
  $db_opcao = 3;
  $clperiodocalendario->excluir($ed53_i_codigo);
  db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Períodos de Avaliação do Calendário <?=$ed52_c_descr?></b></legend>
    <?include(modification("forms/db_frmperiodocalendario.php"));?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?php
if( @$erro_data == true ) {

  echo "<script> document.form1.ed53_d_".@$campo_erro."_dia.style.backgroundColor='#99A9AE';</script>";
  echo "<script> document.form1.ed53_d_".@$campo_erro."_mes.style.backgroundColor='#99A9AE';</script>";
  echo "<script> document.form1.ed53_d_".@$campo_erro."_ano.style.backgroundColor='#99A9AE';</script>";
  echo "<script> document.form1.ed53_d_".@$campo_erro."_dia.focus();</script>";
}

if( isset( $incluir ) ) {

  if( @$erro_data == false ) {

    if( $clperiodocalendario->erro_status == "0" ) {

      $clperiodocalendario->erro( true, false );
      $db_botao = true;

      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

      if( $clperiodocalendario->erro_campo != "" ) {

        echo "<script> document.form1.".$clperiodocalendario->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clperiodocalendario->erro_campo.".focus();</script>";
      }
    } else {

      $sCamposPeriodo1 = "sum(ed53_i_diasletivos) as dias, sum(ed53_i_semletivas) as semanas";
      $sWherePeriodo1  = " ed53_i_calendario = {$ed53_i_calendario} AND ed09_c_somach = 'S'";
      $sql1            = $clperiodocalendario->sql_query( "", $sCamposPeriodo1, "", $sWherePeriodo1 );
      $result1         = $clperiodocalendario->sql_record( $sql1 );

      if( $clperiodocalendario->numrows > 0 ) {

        db_fieldsmemory( $result1, 0 );

        $sql2   = "UPDATE calendario ";
        $sql2  .= "   SET ed52_i_diasletivos = {$dias}, ";
        $sql2  .= "       ed52_i_semletivas = {$semanas} ";
        $sql2  .= " WHERE ed52_i_codigo = {$ed53_i_calendario}";
        $query2 = db_query($sql2);
        ?>
        <script>
          (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a1.location.href='edu1_calendario002.php?chavepesquisa=<?=$ed53_i_calendario?>';
        </script>
        <?php
        $clperiodocalendario->erro( true, true );
      }
    }
  }
}

if( isset( $alterar ) ) {

  if( @$erro_data == false ) {

    if( $clperiodocalendario->erro_status == "0" ) {

      $clperiodocalendario->erro( true, false );
      $db_botao = true;

      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

      if( $clperiodocalendario->erro_campo != "" ) {

        echo "<script> document.form1.".$clperiodocalendario->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clperiodocalendario->erro_campo.".focus();</script>";
      }
    } else {

      $sCamposPeriodo1 = "sum(ed53_i_diasletivos) as dias,sum(ed53_i_semletivas) as semanas";
      $sWherePeriodo1  = " ed53_i_calendario = {$ed53_i_calendario} AND ed09_c_somach = 'S'";
      $sql1            = $clperiodocalendario->sql_query( "", $sCamposPeriodo1, "", $sWherePeriodo1 );
      $result1         = $clperiodocalendario->sql_record($sql1);

      if( $clperiodocalendario->numrows > 0 ) {

        db_fieldsmemory( $result1, 0 );

        $sql2   = "UPDATE calendario ";
        $sql2  .= "   SET ed52_i_diasletivos = {$dias}, ";
        $sql2  .= "       ed52_i_semletivas = {$semanas} ";
        $sql2  .= " WHERE ed52_i_codigo = {$ed53_i_calendario}";
        $query2 = db_query($sql2);
        ?>
        <script>
          (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a1.location.href='edu1_calendario002.php?chavepesquisa=<?=$ed53_i_calendario?>';
        </script>
        <?php
        $clperiodocalendario->erro( true, true );
      }
    }
  }
}

if( isset( $excluir ) ) {

  if( $clperiodocalendario->erro_status == "0" ) {
    $clperiodocalendario->erro( true, false );
  } else {

    $sCamposPeriodo1 = "sum(ed53_i_diasletivos) as dias, sum(ed53_i_semletivas) as semanas";
    $sWherePeriodo1  = " ed53_i_calendario = {$ed53_i_calendario} AND ed09_c_somach = 'S'";
    $sql1            = $clperiodocalendario->sql_query( "", $sCamposPeriodo1, "", $sWherePeriodo1 );
    $result1         = $clperiodocalendario->sql_record($sql1);

    if( $clperiodocalendario->numrows > 0 ) {

      db_fieldsmemory( $result1, 0 );

      if( $dias == "" ) {

        $dias    = 0;
        $semanas = 0;
      }

      $sql2   = "UPDATE calendario ";
      $sql2  .= "   SET ed52_i_diasletivos = {$dias}, ";
      $sql2  .= "       ed52_i_semletivas = {$semanas} ";
      $sql2  .= " WHERE ed52_i_codigo = {$ed53_i_calendario}";
      $query2 = db_query($sql2);
      ?>
      <script>
        (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a1.location.href='edu1_calendario002.php?chavepesquisa=<?=$ed53_i_calendario?>';
      </script>
      <?php
      $clperiodocalendario->erro( true, true );
    }
  }
}

if( isset( $cancelar ) ) {
  echo "<script>location.href='".$clperiodocalendario->pagina_retorno."'</script>";
}