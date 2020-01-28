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

  require_once("libs/db_stdlib.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_usuariosonline.php");
  require_once("libs/db_utils.php");
  require_once("dbforms/db_funcoes.php");
  require_once("libs/db_stdlibwebseller.php");

  parse_str($_SERVER["QUERY_STRING"]);
  db_postmemory($_POST);
  $oDaoAvaliacaoEstruturaFrequencia      = new cl_avaliacaoestruturafrequencia();
  $oDaoAvaliacaoEstruturaRegraFrequencia = new cl_avaliacaoestruturaregrafrequencia();
  $oDaoNovaAvaliacaoEstruturaFrequencia  = new cl_avaliacaoestruturafrequencia();

  $db_opcao   = 22;
  $db_botao   = true;
  $iContador  = 0;
  $iCodEscola = db_getsession("DB_coddepto");

  if (isset($alterar)) {

    $sWhereAvaliacaoEstruturaFrequencia  = "ed328_ano = {$ed328_ano} AND ed328_escola = {$iCodEscola} ";
    $sWhereAvaliacaoEstruturaFrequencia .= " and ed328_sequencial <> {$ed328_sequencial}";
    $sSqlAvaliacaoEstruturaFrequencia    = $oDaoAvaliacaoEstruturaFrequencia->sql_query_file(
                                                                                              null,
                                                                                              "*",
                                                                                              null,
                                                                                              $sWhereAvaliacaoEstruturaFrequencia
                                                                                            );
    $rsAvaliacaoEstruturaFrequencia      = $oDaoAvaliacaoEstruturaFrequencia->sql_record($sSqlAvaliacaoEstruturaFrequencia);

    if ($oDaoAvaliacaoEstruturaFrequencia->numrows > 0) {

      $sParametros  = "?ed328_db_estrutura={$ed328_db_estrutura}&db77_descr={$db77_descr}&ed328_ativo={$ed328_ativo}";
      $sParametros .= "&ed328_arredondafrequencia={$ed328_arredondafrequencia}&ed328_observacao={$ed328_observacao}";
      $sParametros .= "&ed316_sequencial={$ed316_sequencial}&ed316_descricao={$ed316_descricao}";
      $sParametros .= "&ed328_escola={$ed328_escola}&ed328_ano={$ed328_ano}";

      db_msgbox("Já existe uma estrutura de Frequência configurada para o ano informado.");
      db_redireciona("edu4_avaliacaoestruturafrequencia002.php{$sParametros}");
      break;
    }

    db_inicio_transacao();
    $db_opcao = 2;

    $sWhereCodigo = " ed329_avaliacaoestruturafrequencia = {$ed328_sequencial}";
    $oDaoAvaliacaoEstruturaRegraFrequencia->excluir(null, $sWhereCodigo);
    if ($ed328_arredondafrequencia == 't' && $ed316_sequencial != "") {

      $oDaoAvaliacaoEstruturaRegraFrequencia->ed329_avaliacaoestruturafrequencia = $ed328_sequencial;
      $oDaoAvaliacaoEstruturaRegraFrequencia->ed329_regraarredondamento          = $ed316_sequencial;
      $oDaoAvaliacaoEstruturaRegraFrequencia->incluir(null);
    }

    if ($oDaoAvaliacaoEstruturaRegraFrequencia->erro_status == 0) {

      db_msgbox($oDaoAvaliacaoEstruturaRegraFrequencia->erro_msg);
      $sqlerro = true;
    }

    $oDaoAvaliacaoEstruturaFrequencia->ed328_sequencial = $ed328_sequencial;
    $oDaoAvaliacaoEstruturaFrequencia->alterar($ed328_sequencial);

    if ($ed328_ativo == 't') {

      $sWhere  = " ed328_ativo is true and ed328_escola = {$iCodEscola} and ed328_ano = {$ed328_ano}";
      $sWhere .= " and ed328_sequencial <> {$ed328_sequencial}";
      $sSqlAvaliacaoEstruturaFrequencia = $oDaoNovaAvaliacaoEstruturaFrequencia->sql_query(null,
                                                                               'avaliacaoestruturafrequencia.*',
                                                                               null,
                                                                               $sWhere
                                                                              );
      $rsAvaliacaoEstruturaFrequencia      = $oDaoNovaAvaliacaoEstruturaFrequencia->sql_record($sSqlAvaliacaoEstruturaFrequencia);
      $iLinhasAvaliacaoEstruturaFrequencia = $oDaoNovaAvaliacaoEstruturaFrequencia->numrows;
      if ($iLinhasAvaliacaoEstruturaFrequencia > 0) {

        for ($iContador = 0; $iContador < $iLinhasAvaliacaoEstruturaFrequencia; $iContador++) {

          $oDadosAvaliacaoEstruturaFrequencia = db_utils::fieldsMemory($rsAvaliacaoEstruturaFrequencia, $iContador);
          $sArredondar = $oDadosAvaliacaoEstruturaFrequencia->ed328_arredondamedia=="t"?"true":"false";
          $oDaoNovaAvaliacaoEstruturaFrequencia->ed328_sequencial     = $oDadosAvaliacaoEstruturaFrequencia->ed328_sequencial;
          $oDaoNovaAvaliacaoEstruturaFrequencia->ed328_db_estrutura   = $oDadosAvaliacaoEstruturaFrequencia->ed328_db_estrutura;
          $oDaoNovaAvaliacaoEstruturaFrequencia->ed328_ativo          = 'false';
          $oDaoNovaAvaliacaoEstruturaFrequencia->ed328_arredondamedia = $sArredondar;
          $oDaoNovaAvaliacaoEstruturaFrequencia->ed328_observacao     = $oDadosAvaliacaoEstruturaFrequencia->ed328_observacao;
          $oDaoNovaAvaliacaoEstruturaFrequencia->ed328_escola         = $iCodEscola;
          $oDaoNovaAvaliacaoEstruturaFrequencia->ed328_ano            = $oDadosAvaliacaoEstruturaFrequencia->ed328_ano;
          $oDaoNovaAvaliacaoEstruturaFrequencia->alterar($oDadosAvaliacaoEstruturaFrequencia->ed328_sequencial);
        }
      }
    }
    if ($oDaoAvaliacaoEstruturaFrequencia->erro_status == 0) {

      db_msgbox($oDaoAvaliacaoEstruturaFrequencia->erro_msg);
      $sqlerro = true;
    } else {
      db_msgbox($oDaoAvaliacaoEstruturaFrequencia->erro_msg);
    }
    db_fim_transacao();
  } else if (isset($chavepesquisa)) {

     $db_opcao                       = 2;
     $sSqlDadosAvaliacao             = $oDaoAvaliacaoEstruturaFrequencia->sql_query_configuracao_escola($chavepesquisa);
     $rsAvaliacaoEstruturaFrequencia = $oDaoAvaliacaoEstruturaFrequencia->sql_record($sSqlDadosAvaliacao);
     db_fieldsmemory($rsAvaliacaoEstruturaFrequencia, 0);
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
        require_once("forms/db_frmavaliacaoestruturafrequencia.php");
      ?>
    </center>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
</html>
<?
  if (isset($alterar)) {

    if ($oDaoAvaliacaoEstruturaFrequencia->erro_status == "0") {

      $db_botao = true;
      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
      if ($oDaoAvaliacaoEstruturaFrequencia->erro_campo != "") {

        echo "<script> document.form1.".$oDaoAvaliacaoEstruturaFrequencia->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$oDaoAvaliacaoEstruturaFrequencia->erro_campo.".focus();</script>";
      }
    }
  }
  if ($db_opcao == 22) {
    echo "<script>document.form1.pesquisar.click();</script>";
  }
?>
<script>
  js_tabulacaoforms("form1","ed328_escola",true,1,"ed328_escola",true);
</script>