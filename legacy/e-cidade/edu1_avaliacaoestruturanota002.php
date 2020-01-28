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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_stdlibwebseller.php");

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);

$oDaoAvaliacaoEstruturaNota     = new cl_avaliacaoestruturanota();
$oDaoAvaliacaoEstruturaRegra    = new cl_avaliacaoestruturaregra();
$oDaoNovaAvaliacaoEstruturaNota = new cl_avaliacaoestruturanota();

$db_opcao   = 22;
$db_botao   = true;
$iContador  = 0;
$iCodEscola = db_getsession("DB_coddepto");

if (isset($alterar)) {

  $sWhereAvaliacaoEstruturaNota  = "ed315_ano = {$ed315_ano} AND ed315_escola = {$iCodEscola} ";
  $sWhereAvaliacaoEstruturaNota .= " and ed315_sequencial <> {$ed315_sequencial}";
  $sSqlAvaliacaoEstruturaNota    = $oDaoAvaliacaoEstruturaNota->sql_query_file(null, "*", null, $sWhereAvaliacaoEstruturaNota);
  $rsAvaliacaoEstruturaNota      = $oDaoAvaliacaoEstruturaNota->sql_record($sSqlAvaliacaoEstruturaNota);

  if ($oDaoAvaliacaoEstruturaNota->numrows > 0) {

    db_msgbox("Já existe uma estrutura de nota configurada para o ano informado.");
    db_redireciona("edu1_avaliacaoestruturanota001.php");
  }

  db_inicio_transacao();
  $db_opcao = 2;

  $sWhereCodigo       = " ed318_avaliacaoestruturanota = {$ed315_sequencial}";
  $oDaoAvaliacaoEstruturaRegra->excluir(null, $sWhereCodigo);
  if ($ed315_arredondamedia == 't' && $ed316_sequencial != "") {

    $oDaoAvaliacaoEstruturaRegra->ed318_avaliacaoestruturanota = $ed315_sequencial;
    $oDaoAvaliacaoEstruturaRegra->ed318_regraarredondamento    = $ed316_sequencial;
    $oDaoAvaliacaoEstruturaRegra->incluir(null);
  }
  $oDaoAvaliacaoEstruturaNota->ed315_sequencial = $ed315_sequencial;
  $oDaoAvaliacaoEstruturaNota->alterar($ed315_sequencial);

  if ($ed315_ativo == 't') {

    $sWhere  = " ed315_ativo is true and ed315_escola = {$iCodEscola} and ed315_ano = {$ed315_ano}";
    $sWhere .= " and ed315_sequencial <> {$ed315_sequencial}";
    $sSqlAvaliacaoEstruturaNota    = $oDaoNovaAvaliacaoEstruturaNota->sql_query(null,
                                                                                'avaliacaoestruturanota.*',
                                                                                null,
                                                                                $sWhere
                                                                               );
    $rsAvaliacaoEstruturaNota      = $oDaoNovaAvaliacaoEstruturaNota->sql_record($sSqlAvaliacaoEstruturaNota);
    $iLinhasAvaliacaoEstruturaNota = $oDaoNovaAvaliacaoEstruturaNota->numrows;
    if ($iLinhasAvaliacaoEstruturaNota > 0) {

      for ($iContador = 0; $iContador < $iLinhasAvaliacaoEstruturaNota; $iContador++) {

        $oDadosAvaliacaoEstruturaNota = db_utils::fieldsMemory($rsAvaliacaoEstruturaNota, $iContador);
        $sArredondar = $oDadosAvaliacaoEstruturaNota->ed315_arredondamedia=="t"?"true":"false";
        $oDaoNovaAvaliacaoEstruturaNota->ed315_sequencial     = $oDadosAvaliacaoEstruturaNota->ed315_sequencial;
        $oDaoNovaAvaliacaoEstruturaNota->ed315_db_estrutura   = $oDadosAvaliacaoEstruturaNota->ed315_db_estrutura;
        $oDaoNovaAvaliacaoEstruturaNota->ed315_ativo          = 'false';
        $oDaoNovaAvaliacaoEstruturaNota->ed315_arredondamedia = $sArredondar;
        $oDaoNovaAvaliacaoEstruturaNota->ed315_observacao     = $oDadosAvaliacaoEstruturaNota->ed315_observacao;
        $oDaoNovaAvaliacaoEstruturaNota->ed315_escola         = $iCodEscola;
        $oDaoNovaAvaliacaoEstruturaNota->ed315_ano            = $oDadosAvaliacaoEstruturaNota->ed315_ano;
        $oDaoNovaAvaliacaoEstruturaNota->alterar($oDadosAvaliacaoEstruturaNota->ed315_sequencial);
      }
    }
  }
  if ($oDaoAvaliacaoEstruturaNota->erro_status == 0) {

    db_msgbox($oDaoAvaliacaoEstruturaNota->erro_msg);
    $sqlerro = true;
  } else {
    db_msgbox($oDaoAvaliacaoEstruturaNota->erro_msg);
  }
  db_fim_transacao();
} else if (isset($chavepesquisa)) {

   $db_opcao                 = 2;
   $sSqlDadosAvaliacao       = $oDaoAvaliacaoEstruturaNota->sql_query_configuracao_escola($chavepesquisa);
   $rsAvaliacaoEstruturaNota = $oDaoAvaliacaoEstruturaNota->sql_record($sSqlDadosAvaliacao);
   db_fieldsmemory($rsAvaliacaoEstruturaNota, 0);
   $db_botao = true;
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
  <body bgcolor=#CCCCCC style="margin-top: 25px" >
    <center>
    	<?
        require_once("forms/db_frmavaliacaoestruturanota.php");
      ?>
    </center>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
</html>
<?
  if (isset($alterar)) {

    if ($oDaoAvaliacaoEstruturaNota->erro_status == "0") {

      $db_botao = true;
      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
      if ($oDaoAvaliacaoEstruturaNota->erro_campo != "") {

        echo "<script> document.form1.".$oDaoAvaliacaoEstruturaNota->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$oDaoAvaliacaoEstruturaNota->erro_campo.".focus();</script>";
      }
    }
  }
  if ($db_opcao == 22) {
    echo "<script>document.form1.pesquisar.click();</script>";
  }
?>
<script>
  js_tabulacaoforms("form1","ed315_escola",true,1,"ed315_escola",true);
</script>