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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clagualeitura = new cl_agualeitura;
$clagualeiturasaldoutilizado = new cl_agualeiturasaldoutilizado;
$claguahidromatricleitura = new cl_aguahidromatricleitura;
$db_botao = false;
$db_opcao = 33;
$nexclui = false;
$nalterapgto = false;

if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;

  $clagualeiturasaldoutilizado->excluir(null, "x34_agualeitura = $x21_codleitura");
  // Se for a leitura inicial de um Hidrometro (lancado pelo cadastro de hidrometros)
  $sql = $claguahidromatricleitura->sql_query(null, "x05_codigo", null, "x05_codleitura = $x21_codleitura");
  $claguahidromatricleitura->sql_record($sql);

  if($claguahidromatricleitura->numrows>0){
    // Exclui o vinculo da leitura com o cadastro do hidrometro
    $claguahidromatricleitura->excluir(null, "x05_codleitura = $x21_codleitura");
  }

  $oDaoAguaColetorExportaDadosLeitura = db_utils::getDao('aguacoletorexportadadosleitura');

  $sSqlBuscaLeituraColetor = $oDaoAguaColetorExportaDadosLeitura->sql_query_file(NULL,
                                                                                 'x51_sequencial',
                                                                                 NULL,
                                                                                 "x51_agualeitura = {$x21_codleitura}");

  $rsBuscaLeituraColetor = $oDaoAguaColetorExportaDadosLeitura->sql_record($sSqlBuscaLeituraColetor);
  $iColetorCodigoLeitura = db_utils::fieldsMemory($rsBuscaLeituraColetor, 0)->x51_sequencial;

  if (!empty($iColetorCodigoLeitura)) {
    $oDaoAguaColetorExportaDadosLeitura->excluir($iColetorCodigoLeitura);
  }

  $clagualeitura->excluir($x21_codleitura);

  db_fim_transacao();

}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clagualeitura->sql_record(
                                        $clagualeitura->sql_query_dados(
                                          $chavepesquisa,
                        "
                   x21_codleitura,
                   x21_codhidrometro,
                   x21_aguacontrato,
                         x21_exerc,
                         x21_mes,
                         x04_matric,
                         x01_numcgm,
                         x01_codrua,
                         x01_numero,
                         x01_letra,
                         x01_zona,
                         x01_qtdeconomia,
                         case when x01_multiplicador = 'f' then 'Não' else 'Sim' end as x01_multiplicador,
                         x04_nrohidro,
                         x04_qtddigito,
                         x03_nomemarca,
                         x15_diametro,
                         x21_situacao,
                         x17_descr,
                         x21_numcgm,
                         cgm.z01_nome,
                         x21_dtleitura,
                         x21_leitura,
                         x21_consumo,
                         x21_excesso,
                   a.z01_nome as z01_nomedad,
                   j14_nome, 
                   x21_tipo,
                   x21_status, x21_saldo
                        "
                       )
                     );
   db_fieldsmemory($result,0);

   $sSqlLeituraAnterior = $clagualeitura->sql_query_sitecgm(null,"x21_situacao as x21_situacant,x17_descr as x17_descrant,x21_numcgm as x21_numcgmant,z01_nome as z01_nomeant,x21_dtleitura as x21_dtleituraant,x21_leitura as x21_leituraant,x21_consumo as x21_consumoant,x21_excesso as x21_excessoant","x21_codleitura desc limit 1","x21_codleitura < $chavepesquisa and x21_codhidrometro=$x21_codhidrometro ");
   $result_leituraant = $clagualeitura->sql_record($sSqlLeituraAnterior);
   if($clagualeitura->numrows > 0){
     db_fieldsmemory($result_leituraant,0);
   }

   $sql_leituraant = $clagualeitura->sql_query_file(null,
                                                    "*",
                                                    "x21_exerc desc, x21_mes desc, x21_codleitura desc limit 1",
                                                    "x21_codhidrometro=$x21_codhidrometro
   and cast(x21_exerc::varchar||'-'||x21_mes::varchar||'-01' as date) 
     > cast('{$x21_exerc}-{$x21_mes}-01' as date)");

   $result_leituraant = $clagualeitura->sql_record($sql_leituraant);
   if($clagualeitura->numrows > 0){
     $nexclui = true;
   }

   $db_botao = true;

   $rPagamentos = $clagualeitura->sql_record($clagualeitura->sql_query_pagamentos_posteriores($x21_exerc, $x21_mes, $x04_matric));

   if($clagualeitura->numrows > 0) {
     $nalterapgto = true;
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
if(isset($excluir)){
  if($clagualeitura->erro_status=="0"){
    $clagualeitura->erro(true,false);
  }else{
    $clagualeitura->erro(true,true);
  }
}

if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}

if($nalterapgto == true){

  if(db_permissaomenu(db_getsession("DB_anousu"), 4555, 8938)=="true") {

    echo "<script>";
    echo "var msg = 'Parcelas posteriores já pagas. Esse procedimento pode gerar um re-calculo no SALDO dessas parcelas. Deseja continuar? ';";
    echo "if (!confirm(msg)) {";
    echo "  document.form1.db_opcao.disabled=true; ";
    echo "  window.location = 'agu1_agualeitura003.php'";
    echo "}";
    echo "</script>";

  } else {
    db_msgbox("Existem leituras ou parcelas posteriores já pagas. Esse procedimento pode gerar um re-calculo no SALDO dessas parcelas. Favor efetuar processo administrativo");
    echo "<script>location.href = 'agu1_agualeitura003.php';</script>";
  }

}
