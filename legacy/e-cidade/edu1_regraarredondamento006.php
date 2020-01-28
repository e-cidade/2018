<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

  require_once("libs/db_stdlib.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_usuariosonline.php");
  require_once("libs/db_utils.php");
  require_once("dbforms/db_funcoes.php");
  
  $oDaoRegraArredondamento               = new cl_regraarredondamento();
  $oDaoRegraArredondamentoFaixa          = new cl_regraarredondamentofaixa();
  $oDaoAvaliacaoEstruturaRegra           = new cl_avaliacaoestruturaregra();
  $oDaoAvaliacaoEstruturaRegraFrequencia = new cl_avaliacaoestruturaregrafrequencia();
  
  db_postmemory($_POST);
  $db_opcao          = 33;
  $db_botao          = false;
  $iExcluiRegraTotal = false;
  if (isset($excluir)) {
    
    /**
     * Verifica se a regra de arredondamento possui vínculo com algum estrutural de nota configurado pelas escolas
     */
    $sSqlAvaliacaoEstruturaRegra = $oDaoAvaliacaoEstruturaRegra->sql_query_file( 
                                                                                 null, 
                                                                                 "ed318_sequencial", 
                                                                                 null, 
                                                                                 "ed318_regraarredondamento = {$ed316_sequencial}"
                                                                               );
    $rsAvaliacaoEstruturaRegra = db_query( $sSqlAvaliacaoEstruturaRegra );
    if ( $rsAvaliacaoEstruturaRegra && pg_num_rows( $rsAvaliacaoEstruturaRegra ) > 0 ) {
    
      db_msgbox( "Não é possível excluir regra de arredondamento, pois a mesma está sendo utilizada." );
      db_redireciona( "edu1_regraarredondamento006.php" );
    }
    
    /**
     * Verifica se a regra de arredondamento possui vínculo com algum estrutural de frequência configurado pelas escolas
     */
    $sSqlAvaliacaoEstruturaRegraFrequencia = $oDaoAvaliacaoEstruturaRegraFrequencia->sql_query_file(
                                                                                                     null,
                                                                                                     "ed329_sequencial",
                                                                                                     null,
                                                                                                     "ed329_regraarredondamento = {$ed316_sequencial}"
                                                                                                   );
    $rsAvaliacaoEstruturaRegraFrequencia = db_query( $sSqlAvaliacaoEstruturaRegraFrequencia );
    if ( $rsAvaliacaoEstruturaRegraFrequencia && pg_num_rows( $rsAvaliacaoEstruturaRegraFrequencia ) > 0 ) {
      
      db_msgbox( "Não é possível excluir regra de arredondamento, pois a mesma está sendo utilizada." );
      db_redireciona( "edu1_regraarredondamento006.php" );
    }
    
    $sqlerro = false;
    db_inicio_transacao();
    $sWhere = " ed317_regraarredondamento = {$ed316_sequencial}";
    $oDaoRegraArredondamentoFaixa->ed317_regraarredondamento = $ed316_sequencial;
    $oDaoRegraArredondamentoFaixa->excluir($ed316_sequencial, $sWhere);
  
    if ($oDaoRegraArredondamentoFaixa->erro_status == 0) {
      $sqlerro = true;
    } 
    $erro_msg = $oDaoRegraArredondamentoFaixa->erro_msg; 
    $oDaoRegraArredondamento->excluir($ed316_sequencial);
    if ($oDaoRegraArredondamento->erro_status == 0) {
      $sqlerro = true;
    } 
    $erro_msg = $oDaoRegraArredondamento->erro_msg; 
    db_fim_transacao($sqlerro);
    $db_opcao = 3;
    $db_botao = true;
  }
  if (isset($chavepesquisa)) {

    $db_opcao = 3;
    $db_botao = true;
    $result   = $oDaoRegraArredondamento->sql_record($oDaoRegraArredondamento->sql_query($chavepesquisa)); 
    db_fieldsmemory($result,0);
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
    <center>
      <?
        require_once("forms/db_frmregraarredondamento.php");
      ?>
    </center>
  </body>
</html>
<?
  if (isset($excluir)) {
    if ($sqlerro == true) {
      
      db_msgbox($erro_msg);
      if ($oDaoRegraArredondamento->erro_campo != "") {
        echo "<script> document.form1.".$oDaoRegraArredondamento->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$oDaoRegraArredondamento->erro_campo.".focus();</script>";
      }
    } else {
      db_msgbox($erro_msg);
   echo "
    <script>
      function js_db_tranca(){
        parent.location.href='edu1_regraarredondamento003.php';
      }\n
      js_db_tranca();
    </script>\n
   ";
    }
  }
  if (isset($chavepesquisa)) {
    echo "
    <script>
        function js_db_libera(){
           parent.document.formaba.regraarredondamentofaixa.disabled=false;
           top.corpo.iframe_regraarredondamentofaixa.location.href='edu1_regraarredondamentofaixa001.php?db_opcaoal=33".
                                                                                                       "&ed317_regraarredondamento={@$ed316_sequencial}
       ";
           if (isset($liberaaba)) {
             echo "  parent.mo_camada('regraarredondamentofaixa');";
           }
   echo "}\n
      js_db_libera();
    </script>\n
   ";
  }
  if ($db_opcao == 22 || $db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
  }
?>