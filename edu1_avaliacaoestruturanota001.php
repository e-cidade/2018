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

  db_postmemory($_POST);
  $oDaoAvaliacaoEstruturaNota      = db_utils::getDao("avaliacaoestruturanota");
  $oDaoNovaAvaliacaoEstruturaNota  = db_utils::getDao("avaliacaoestruturanota");
  $oDaoRegraArredondamento         = db_utils::getDao("regraarredondamento");
  $oDaoAvaliacaoEstruturaRegra     = db_utils::getDao("avaliacaoestruturaregra");

  $db_opcao    = 1;
  $db_botao    = true;
  $iContador   = 0;
  $iCodEscola  = db_getsession("DB_coddepto");
  $sParametros = '';
  $lErroInclusao = false;

  if (isset($incluir)) {

    $sWhereAvaliacaoEstruturaNota = "ed315_ano = {$ed315_ano} AND ed315_escola = {$iCodEscola}";
    $sSqlAvaliacaoEstruturaNota   = $oDaoAvaliacaoEstruturaNota->sql_query_file(null, "*", null, $sWhereAvaliacaoEstruturaNota);
    $rsAvaliacaoEstruturaNota     = $oDaoAvaliacaoEstruturaNota->sql_record($sSqlAvaliacaoEstruturaNota);

    if ($oDaoAvaliacaoEstruturaNota->numrows > 0) {

      $lErroInclusao = true;
      $sParametros  = "ed315_db_estrutura={$ed315_db_estrutura}&db77_descr={$db77_descr}&ed315_ativo={$ed315_ativo}";
      $sParametros .= "&ed315_arredondamedia={$ed315_arredondamedia}&ed315_observacao={$ed315_observacao}";
      $sParametros .= "&ed316_sequencial={$ed316_sequencial}&ed316_descricao={$ed316_descricao}";
      $sParametros .= "&ed315_escola={$ed315_escola}&ed315_ano={$ed315_ano}&lErroInclusao=true";

      db_msgbox("Já existe uma estrutura de nota configurada para o ano informado.");
      db_redireciona("edu1_avaliacaoestruturanota001.php");
      break;
    }

    db_inicio_transacao();
    $oDaoAvaliacaoEstruturaNota->ed315_escola = $iCodEscola;

    $sWhere  = " ed315_ativo = 't' and ed315_escola = {$iCodEscola} and ed315_ano = {$ed315_ano}";
    $sSqlAvaliacaoEstruturaNota    = $oDaoNovaAvaliacaoEstruturaNota->sql_query(null,
                                                                                'avaliacaoestruturanota.*',
                                                                                null,
                                                                                $sWhere
                                                                               );
    $rsAvaliacaoEstruturaNota      = $oDaoNovaAvaliacaoEstruturaNota->sql_record($sSqlAvaliacaoEstruturaNota);
    $iLinhasAvaliacaoEstruturaNota = $oDaoNovaAvaliacaoEstruturaNota->numrows;
    if ($iLinhasAvaliacaoEstruturaNota > 0) {

      for ($iContador = 0; $iContador < $iLinhasAvaliacaoEstruturaNota; $iContador++){

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

    $oDaoAvaliacaoEstruturaNota->incluir($ed315_sequencial);
    $ed315_sequencial = $oDaoAvaliacaoEstruturaNota->ed315_sequencial;
    if ($oDaoAvaliacaoEstruturaNota->ed315_arredondamedia == 't' && $ed316_sequencial != "") {

      $oDaoAvaliacaoEstruturaRegra->ed318_avaliacaoestruturanota = $ed315_sequencial;
      $oDaoAvaliacaoEstruturaRegra->ed318_regraarredondamento    = $ed316_sequencial;
      $oDaoAvaliacaoEstruturaRegra->incluir(null);
    }

    if ($oDaoAvaliacaoEstruturaNota->erro_status == 0) {

      db_msgbox($oDaoAvaliacaoEstruturaNota->erro_msg);
      $sqlerro = true;
    } else {

      db_msgbox($oDaoAvaliacaoEstruturaNota->erro_msg);
      $ed315_sequencial     = '';
      $ed315_db_estrutura   = '';
      $db77_descr           = '';
      $ed315_ativo          = 'f';
      $ed315_arredondamedia = 'f';
      $ed316_sequencial     = '';
      $ed316_descricao      = '';
      $ed315_observacao     = '';
      $ed315_ano            = '';
    }
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
<script>
  js_tabulacaoforms("form1","ed315_escola",true,1,"ed315_escola",true);
</script>
<?
  if (isset($incluir)) {
    if ($oDaoAvaliacaoEstruturaNota->erro_status == "0") {

      $oDaoAvaliacaoEstruturaNota->erro(true,false);
      $db_botao = true;
      echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
      if ($oDaoAvaliacaoEstruturaNota->erro_campo != "") {

        echo "<script> document.form1.".$oDaoAvaliacaoEstruturaNota->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$oDaoAvaliacaoEstruturaNota->erro_campo.".focus();</script>";
      }
    } else {
      $oDaoAvaliacaoEstruturaNota->erro(true,true);
    }
  }
?>