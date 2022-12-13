<?php
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_agualeitura_classe.php"));
require_once(modification("classes/db_ruas_classe.php"));
require_once(modification("classes/db_cgm_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);

$clagualeitura = new cl_agualeitura;
$clruas = new cl_ruas;
$clcgm = new cl_cgm;
$db_opcao = 1;
$db_botao = true;

if(isset($lancar) || isset($incluir)) {
  db_inicio_transacao();

  $sqlerro = false;
  if(!isset($incmesanter) && !isset($lancar)){
  	$anoanter = $x21_exerc;
    $mesanter = $x21_mes - 1;
  	if($mesanter == 0){
      $anoanter-= 1;
      $mesanter = 12;
  	}

    $result_mes_anter = $clagualeitura->sql_record($clagualeitura->sql_query_file(null," x21_codhidrometro ",""," x21_exerc = ".$anoanter." and x21_mes = ".$mesanter." and x21_codhidrometro = ".$x21_codhidrometro));
    if($clagualeitura->numrows == 0){
      $nmesanter = true;
  	}
  }

  if (!isset($nmesanter)) {

    $x21_saldo = isset($x21_saldo) ? $x21_saldo : null;
    $dtleitura = $x21_dtleitura_ano . '-' . $x21_dtleitura_mes . '-' . $x21_dtleitura_dia;
    if(trim($dtleitura) == "--"){
      $dtleitura = "";
    }
    $clagualeitura->x21_codhidrometro = $x21_codhidrometro;
    $clagualeitura->x21_exerc         = $x21_exerc;
    $clagualeitura->x21_mes           = $x21_mes;
    $clagualeitura->x21_situacao      = "$x21_situacao";
    $clagualeitura->x21_numcgm        = $x21_numcgm;
    $clagualeitura->x21_dtleitura     = $dtleitura;
    $clagualeitura->x21_usuario       = db_getsession("DB_id_usuario");
    $clagualeitura->x21_dtinc         = date("Y-m-d",db_getsession("DB_datausu"));
    $clagualeitura->x21_leitura       = "$x21_leitura";
    $clagualeitura->x21_consumo       = "$x21_consumo";
    $clagualeitura->x21_excesso       = "$x21_excesso";
    $clagualeitura->x21_virou         = "$x21_virou";
    $clagualeitura->x21_tipo          = "1"; // 1 = Digitacao Manual
    $clagualeitura->x21_status        = "1"; // 1 = Status Ativo
    $clagualeitura->x21_saldo         = "$x21_saldo"; // 1 = Status Ativo
    $clagualeitura->x21_aguacontrato  = AguaLeitura::getContratoPorMatricula($x04_matric);
    $clagualeitura->incluir(null);
    $erro_msg = $clagualeitura->erro_msg;
    $codigoleitura = $clagualeitura->x21_codleitura;

    if(!isset($lancar)){

      $sSqlDadosAtuais = $clagualeitura->sql_query_file($codigoleitura,"x21_consumo as consumo,x21_excesso as excesso");
      $result_dados_atuais = $clagualeitura->sql_record($sSqlDadosAtuais);
      if($clagualeitura->numrows > 0){
        db_fieldsmemory($result_dados_atuais,0);
      }
      $sqlerro = true;
    }
  }

  // Registra ocorrencia de ajuste de leitura com periodo maior que 30 dias
  if ( $leitura_base_ajuste && $sqlerro == false ) {

  	$clhistocorrencia                    = new cl_histocorrencia;

    $sDataBaseAjuste                     = "{$dtleitura_base_ajuste_dia}/";
    $sDataBaseAjuste                    .= "{$dtleitura_base_ajuste_mes}/";
    $sDataBaseAjuste                    .= "{$dtleitura_base_ajuste_ano}";

    $sDataAjustada                       = "{$x21_dtleitura_dia}/{$x21_dtleitura_mes}/{$x21_dtleitura_ano}";

    $sOcorrencia                         = "Ajustada leitura de {$leitura_base_ajuste} ";
    $sOcorrencia                        .= "com data de {$sDataBaseAjuste} para ";
    $sOcorrencia                        .= "{$x21_leitura} e data {$sDataAjustada}";

    $clhistocorrencia->ar23_id_usuario   = db_getsession("DB_id_usuario");
    $clhistocorrencia->ar23_instit       = db_getsession("DB_instit");
    $clhistocorrencia->ar23_modulo       = db_getsession("DB_modulo");
    $clhistocorrencia->ar23_id_itensmenu = db_getsession("DB_itemmenu_acessado");
    $clhistocorrencia->ar23_descricao    = "Adequação Leitura Maior 30 Dias";
    $clhistocorrencia->ar23_ocorrencia   = $sOcorrencia;
    $clhistocorrencia->ar23_tipo         = 1;
    $clhistocorrencia->ar23_hora         = date("H:i");
    $clhistocorrencia->ar23_data         = date("d")."/".date("m")."/".date("Y");

    $clhistocorrencia->incluir(null);

    if ( $clhistocorrencia->erro_status == 0 ) {

      $erro_msg = $clhistocorrencia->erro_msg;
      $sqlerro = true;

    } else {

      $clhistocorrenciamatric = new cl_histocorrenciamatric;

      $clhistocorrenciamatric->ar25_matric         = $x04_matric;
      $clhistocorrenciamatric->ar25_histocorrencia = $clhistocorrencia->ar23_sequencial;

      $clhistocorrenciamatric->incluir(null);

      if ( $clhistocorrenciamatric->erro_status == 0 ) {

        $erro_msg = $clhistocorrencia->erro_msg;
        $sqlerro = true;
      }

      if ($erro_msg != "") {
      	db_msgbox($erro_msg);
      }

    }

  }

  db_fim_transacao($sqlerro);
}else{
  if(isset($x01_codruaref)){
    $result_nomeruaref = $clruas->sql_record($clruas->sql_query_file($x01_codruaref,"j14_nome as j14_nomeref"));
    if($clruas->numrows > 0){
      db_fieldsmemory($result_nomeruaref, 0);
    }
  }
  if(isset($x21_numcgm)){
    $result_nomeleiref = $clcgm->sql_record($clcgm->sql_query_file($x21_numcgm,"z01_nome"));
    if($clcgm->numrows > 0){
      db_fieldsmemory($result_nomeleiref, 0);
    }
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
<body class="body-default">

<div class="container">
<?php require_once(modification("forms/db_frmagualeitura.php")) ?>
</div>

<?php db_menu() ?>
</body>
</html>
<?php
if(isset($lancar)){
  //db_msgbox($erro_msg);
  if($clagualeitura->erro_status=="0"){
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clagualeitura->erro_campo!=""){
      echo "<script> document.form1.".$clagualeitura->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clagualeitura->erro_campo.".focus();</script>";
    };
  };
}else if(isset($incluir)){
  if(isset($nmesanter)){
    echo "
          <script>
            if(confirm('Nenhuma leitura encontrada para o mês anterior (".$mesanter." / ".$anoanter.").\\n\\n\\n\\nDeseja prosseguir?')){
              obj=document.createElement('input');
              obj.setAttribute('name','incmesanter');
              obj.setAttribute('type','hidden');
              obj.setAttribute('value','incmesanter');
              document.form1.appendChild(obj);
              document.getElementById('db_opcao').click();
            }else{
              location.href = 'agu1_agualeitura001.php?x01_codruaref=$x01_codruaref&x21_numcgm=$x21_numcgm&x21_dtleitura_dia=$x21_dtleitura_dia&x21_dtleitura_mes=$x21_dtleitura_mes&x21_dtleitura_ano=$x21_dtleitura_ano';
            }
          </script>
         ";
  }else if($clagualeitura->erro_status=="0"){
    db_msgbox($erro_msg);
    if($clagualeitura->erro_campo!=""){
      echo "<script> document.form1.".$clagualeitura->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clagualeitura->erro_campo.".focus();</script>";
    };
  }else if(isset($consumo) && isset($excesso)){
    echo "
          <script>
            if(confirm('Dados após inclusão:\\n\\nConsumo: $consumo\\nExcesso: $excesso\\n\\n\\nDeseja prosseguir?')){
              obj=document.createElement('input');
              obj.setAttribute('name','lancar');
              obj.setAttribute('type','hidden');
              obj.setAttribute('value','lancar');
              document.form1.appendChild(obj);
              document.form1.submit();
            }else{
              location.href = 'agu1_agualeitura001.php?x01_codruaref=$x01_codruaref&x21_numcgm=$x21_numcgm&x21_dtleitura_dia=$x21_dtleitura_dia&x21_dtleitura_mes=$x21_dtleitura_mes&x21_dtleitura_ano=$x21_dtleitura_ano';
            }
          </script>
         ";
  }
}

if((!isset($incluir) && !isset($lancar)) || ((isset($incluir) || isset($lancar)) && $clagualeitura->erro_status!="0")){
  echo '
        <script>
          document.form1.x04_matric.value = "";
          js_pesquisax04_matric(false);
        </script>
       ';
  if(isset($incluir) || isset($lancar)){
    echo '
          <script>
            js_tabulacaoforms("form1","x04_matric",true,1,"x04_matric",true);
          </script>
         ';
  }else{
    echo '
          <script>
            js_tabulacaoforms("form1","x21_exerc",true,1,"x21_exerc",true);
          </script>
         ';
  }
}
