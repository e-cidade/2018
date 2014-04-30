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

  $db_botao   = false;
  $db_opcao   = 33;
  $iCodEscola = db_getsession("DB_coddepto");

  if (isset($excluir)) {

    db_inicio_transacao();
    $db_opcao = 3;
    $sWhereCodigo       = " ed329_avaliacaoestruturafrequencia = {$ed328_sequencial}";
    $sSqlEstruturaRegra = $oDaoAvaliacaoEstruturaRegraFrequencia->sql_query(null, '*', null, $sWhereCodigo);
    $rsEstruturaRegra   = $oDaoAvaliacaoEstruturaRegraFrequencia->sql_record($sSqlEstruturaRegra);

    if ($oDaoAvaliacaoEstruturaRegraFrequencia->numrows > 0) {

      $oEstruturaRegra    = db_utils::fieldsMemory($rsEstruturaRegra, 0);
      $ed329_sequencial   = $oEstruturaRegra->ed329_sequencial;

      $oDaoAvaliacaoEstruturaRegraFrequencia->ed329_sequencial = $ed329_sequencial;
      $oDaoAvaliacaoEstruturaRegraFrequencia->excluir($ed329_sequencial);

      if ($oDaoAvaliacaoEstruturaRegraFrequencia->erro_status == 0) {

        db_msgbox($oDaoAvaliacaoEstruturaRegraFrequencia->erro_msg);
        $sqlerro = true;
      }
    }

    $oDaoAvaliacaoEstruturaFrequencia->excluir($ed328_sequencial);

    if ($oDaoAvaliacaoEstruturaFrequencia->erro_status == 0) {

      db_msgbox($oDaoAvaliacaoEstruturaFrequencia->erro_msg);
      $sqlerro = true;
    } else {

      db_msgbox($oDaoAvaliacaoEstruturaFrequencia->erro_msg);
      $ed328_sequencial          = '';
      $ed328_db_estrutura        = '';
      $db77_descr                = '';
      $ed328_ativo               = '';
      $ed328_arredondafrequencia = '';
      $ed316_sequencial          = '';
      $ed316_descricao           = '';
      $ed328_observacao          = '';
    }
    db_fim_transacao();
  } else if (isset($chavepesquisa)) {

     $db_opcao = 3;
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
  if (isset($excluir)) {

    if ($oDaoAvaliacaoEstruturaFrequencia->erro_status == "0") {
      $oDaoAvaliacaoEstruturaFrequencia->erro(true,false);
    } else {
      $oDaoAvaliacaoEstruturaFrequencia->erro(true,true);
    }
  }
  if ($db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
  }
?>
<script>
  js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>