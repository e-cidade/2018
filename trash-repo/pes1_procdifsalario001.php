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


/**
 *  Desenvolvido por Matheus Felini
 * 
 *  Arquivo criado para processar a diferen�a salarial comparando os dados de uma base
 *  de origem e destino conforme a base selecionada pelo usu�rio.
 * 
 *  Rotina Utilizada:
 *  PESSOAL > PROCEDIMENTOS > DIFEREN�AS > PROCESSA DIFEREN�A DE SALARIO
 * 
 *  Nesta rotina, eu b�sicamente criei uma condi��o para verificar em que tabela deveria ser buscado
 *  os dados e com isso criei vari�veis fixas. Uma para acessar a tabela e outra para utilizar as siglas.
 * 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");


$iIPOrigemHidden = db_getsession('DB_servidor');
$sNomeBaseAtual  = db_getsession('DB_base');
$sBaseDestino    = $iIPOrigemHidden ." :: ". $sNomeBaseAtual;
$iAnoOrigem      = date('Y'); 
$iMesOrigem      = date('m');
$oPost           = db_utils::postMemory($HTTP_POST_VARS);
$sMsgErro        = "Procedimento conclu�do com sucesso!";

if (!isset($DB_PORTA_ALT)){
  $DB_PORTA_ALT = db_getsession('DB_porta');
}


if ( isset($oPost->btnProcessaDiferSalario) ) {

  /**
   *  Configura Rubricas para serem tratadas como STRING
   */
  if ( $oPost->iProcessarDiferenca == "1" ) {
    
    $aExplodeStringRubricas = explode(",",$oPost->aConfRubrica);
    $sInRubrica     = "";
    foreach ($aExplodeStringRubricas as $sRubrica) {
      
      if ( $sInRubrica == "" ) {
        $sInRubrica .= "'{$sRubrica}'";
      } else {
        $sInRubrica .= ",'{$sRubrica}'";
      }
    }
  }

  /**
   * Folha SAL�RIO
   */
  if ( $oPost->iTipoFolhaOrigem == "1" ) {
    
    $sSiglaTabela = "r14";
    $sTabela      = "gerfsal";
  } else if ( $oPost->iTipoFolhaOrigem == "2" ) {
  /**
   * Folha Complementar
   */
    $sSiglaTabela = "r48";
    $sTabela      = "gerfcom";
  }
  
  // WHERE padr�o para todas consultas
  $sWhereTipoFolhaOrigem  = $sSiglaTabela."_anousu = {$oPost->iAnoOrigem}";  
  $sWhereTipoFolhaOrigem .= " and {$sSiglaTabela}_mesusu = {$oPost->iMesOrigem}";
  $sWhereTipoFolhaOrigem .= " and {$sSiglaTabela}_instit = ".db_getsession('DB_instit');
  
  $sInnerCargoOrigem     = "";
  
  /**
   *  Verifica se o processamento � por rubricas.
   */
  if ( $oPost->iProcessarDiferenca == "1" ) { // Rubricas
    $sWhereTipoFolhaOrigem .= " and {$sSiglaTabela}_rubric in ({$sInRubrica}) ";
  } else {                             // Total de Proventos
    $sWhereTipoFolhaOrigem .= " and {$sSiglaTabela}_pd = 1 ";
  }
  
  if ( $oPost->iSelecao == 1 ) { // Matricula
    
    if ( $oPost->sTipoFiltroIframe == "1" ) { 
      $sWhereTipoFolhaOrigem .= " and {$sSiglaTabela}_regist between {$oPost->sConteudoSelecaoIframe} ";
    } else if ( $oPost->sTipoFiltroIframe == "2" ) {
      $sWhereTipoFolhaOrigem .= " and {$sSiglaTabela}_regist in ({$oPost->sConteudoSelecaoIframe}) ";
    }
  } else if ( $oPost->iSelecao == "2" ) { // Lotacao

    if ( $oPost->sTipoFiltroIframe == "1" ) { 
      $sWhereTipoFolhaOrigem .= " and {$sSiglaTabela}_lotac between {$oPost->sConteudoSelecaoIframe} ";
    } else if ( $oPost->sTipoFiltroIframe == "2" ) {
      $sWhereTipoFolhaOrigem .= " and {$sSiglaTabela}_lotac in ({$oPost->sConteudoSelecaoIframe}) ";
    }
  } else if ( $oPost->iSelecao == "3" ) { // Cargo

    $sInnerCargoOrigem  = " inner join pessoal.rhpessoalmov on "; 
    $sInnerCargoOrigem .=  $sTabela.".{$sSiglaTabela}_regist = rhpessoalmov.rh02_regist ";
    $sInnerCargoOrigem .=  $sTabela.".{$sSiglaTabela}_anousu = rhpessoalmov.rh02_anousu ";
    $sInnerCargoOrigem .=  $sTabela.".{$sSiglaTabela}_mesusu = rhpessoalmov.rh02_mesusu ";
    
    if ( $oPost->sTipoFiltroIframe == "1" ) { 
      $sWhereTipoFolhaOrigem .= " and rhpessoalmov.rh02_funcao between {$oPost->sConteudoSelecaoIframe} ";        
    } else if ( $oPost->sTipoFiltroIframe == "2" ) {
      $sWhereTipoFolhaOrigem .= " and rhpessoalmov.rh02_funcao in ({$oPost->sConteudoSelecaoIframe}) ";
    }
  }
  
  /**
   * Monta a Query para ser executada no banco Origem e Destino
   */
  $sOrderByGerfcomGerfsal = " order by {$sSiglaTabela}_regist, {$sSiglaTabela}_anousu, {$sSiglaTabela}_mesusu, {$sSiglaTabela}_rubric, {$sSiglaTabela}_lotac ";
  $sSqlGerfcomGerfsal = "select * from pessoal.{$sTabela}  {$sInnerCargoOrigem}  where  {$sWhereTipoFolhaOrigem}  {$sOrderByGerfcomGerfsal} ";
  $resConexaoOrigem   = @pg_connect("host={$oPost->iIPOrigemHidden} dbname={$oPost->sBancoOrigemHidden} user=".$DB_USUARIO." port=".$DB_PORTA_ALT." password=".$DB_SENHA);

  
  if ( !$resConexaoOrigem ) {

    $sMsgErro = "N�o foi poss�vel conectar na base de dados: $oPost->iIPOrigemHidden ($oPost->sBancoOrigemHidden)";
  } else {

    $aDadosInserir = array();
    $aDadosUpdate  = array();
    
    /**
     * Configura��es de Busca na Base de Origem
     */
    $rsDadosOrigem = db_query($resConexaoOrigem, $sSqlGerfcomGerfsal);
    $iTotalOrigem  = pg_num_rows($rsDadosOrigem);
    $oDadosOrigem  = db_utils::getColectionByRecord($rsDadosOrigem);

    @pg_close($resConexaoOrigem);

    /**
     * Configura��es de Busca na Base de Destino
     */
    $rsConexaoDestino = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA_ALT user=$DB_USUARIO password=$DB_SENHA");    
    $rsDadosDestino   = db_query($rsConexaoDestino, $sSqlGerfcomGerfsal);
    $iTotalDestino    = pg_num_rows($rsDadosDestino);
    $oDadosDestino    = db_utils::getColectionByRecord($rsDadosDestino);
    
    if ( $iTotalOrigem == 0 || $iTotalDestino == 0 ) {
      $sMsgErro = "N�o encontramos registros para os filtros selecionados.";
    } else {
 
      asort($oDadosDestino);
      asort($oDadosOrigem);

      /**
       *  Configura��o das vari�veis de acordo com a sele��o do usu�rio
       */
      $iRegAnoUso = $sSiglaTabela."_anousu";
      $iRegMesUso = $sSiglaTabela."_mesusu";
      $iRegRegist = $sSiglaTabela."_regist";
      $sRegRubric = $sSiglaTabela."_rubric";
      $iRegLotac  = $sSiglaTabela."_lotac";
      $nRegValor  = $sSiglaTabela."_valor";
      $iRegInstit = $sSiglaTabela."_instit";

      /**
       *  Processamento dos dados retornados da busca na base de Origem e Destino, comparando os valores e armazenando
       *  estes em um vetor ($aDadosInserir) para ent�o inserir no banco de dados.
       */
      foreach ( $oDadosOrigem as $oRegOrigem) {
        
        foreach ( $oDadosDestino as $oRegDestino) {
          
          if ( $oRegDestino->$iRegAnoUso == $oRegOrigem->$iRegAnoUso && 
               $oRegDestino->$iRegMesUso == $oRegOrigem->$iRegMesUso &&
               $oRegDestino->$iRegRegist == $oRegOrigem->$iRegRegist &&
               $oRegDestino->$sRegRubric == $oRegOrigem->$sRegRubric ) {

            $nCalcDiferOrigemDestino = ($oRegOrigem->$nRegValor - $oRegDestino->$nRegValor);

            if ( $nCalcDiferOrigemDestino > 0 ) {

              /**
               *  Verifica se o REGIST j� est� inclu�do no array $aDadosInserir, caso esteja
               *  � somada a diferen�a junto com o valor j� existente. Do contr�rio, � inclu�da
               *  uma nova posi��o no array.
               */
              if ( array_key_exists($oRegDestino->$iRegRegist, $aDadosInserir) ) {
                $aDadosInserir[$oRegOrigem->$iRegRegist]["valor"]  += $nCalcDiferOrigemDestino;
              } else {
                $aDadosInserir[$oRegOrigem->$iRegRegist]["anousu"] = $oRegOrigem->$iRegAnoUso;
                $aDadosInserir[$oRegOrigem->$iRegRegist]["mesusu"] = $oRegOrigem->$iRegMesUso;
                $aDadosInserir[$oRegOrigem->$iRegRegist]["regist"] = $oRegOrigem->$iRegRegist;
                $aDadosInserir[$oRegOrigem->$iRegRegist]["rubric"] = "{$oPost->iRubricaDestino}";
                $aDadosInserir[$oRegOrigem->$iRegRegist]["valor"]  = $nCalcDiferOrigemDestino;
                $aDadosInserir[$oRegOrigem->$iRegRegist]["quant"]  = 0;
                $aDadosInserir[$oRegOrigem->$iRegRegist]["lotac"]  = $oRegOrigem->$iRegLotac;
                $aDadosInserir[$oRegOrigem->$iRegRegist]["datlim"] = null;
                $aDadosInserir[$oRegOrigem->$iRegRegist]["instit"] = $oRegOrigem->$iRegInstit;                  
              }
            }
          }
        }
      }

      /**
       *  Configura vari�veis para utilizarmos nas tabelas posteriormente.
       *  
       *  O nome e sigla da tabela s�o armazenados em campos para n�o precisar reescrever o c�digo.
       */
      if ( $oPost->iTipoFolhaDestino == 1 ) {        // SAL�RIO
        
        $sTabelaDestino = "pessoal.pontofs";
        $sSiglaDestino  = "r10";
      } else if ( $oPost->iTipoFolhaDestino == 2 ) { // COMPLEMENTAR
        
        $sTabelaDestino = "pessoal.pontocom";
        $sSiglaDestino  = "r47";
      }
      
      /**
       * Configura campos da tabela de acordo com a sele��o do Tipo de Folha de DESTINO
       */
      $iRegAnoUsoFolha = $sSiglaDestino."_anousu";
      $iRegMesUsoFolha = $sSiglaDestino."_mesusu";
      $iRegRegistFolha = $sSiglaDestino."_regist";
      $iRegRubricFolha = $sSiglaDestino."_rubric";
      $iRegValorFolha  = $sSiglaDestino."_valor";
      $iRegLotacFolha  = $sSiglaDestino."_lotac";
      $iRegInstitFolha = $sSiglaDestino."_instit";
      
      // Where da folha pontofs ou pontocom
      $sWhereTipoFolhaDestino  = " {$iRegAnoUsoFolha} = $oPost->iAnoDestino             ";
      $sWhereTipoFolhaDestino .= " and {$iRegMesUsoFolha} = {$oPost->iMesDestino}       ";
      $sWhereTipoFolhaDestino .= " and {$iRegRubricFolha} = '{$oPost->iRubricaDestino}' ";
      $sWhereTipoFolhaDestino .= " and {$iRegInstitFolha} = ".db_getsession('DB_instit');

      /**
       * Processamento dos dados da Folha PontoFS/PontoCOM
       * 
       * Executa um select para verificar se existem dados j� registrados para a folha de destino, existindo
       * � efetuado o processo de soma ou substitui��o
       */
      $sSqlPontoComFs   = " select * from  $sTabelaDestino  where  $sWhereTipoFolhaDestino";
      
      $rsSqlPonto       = db_query($rsConexaoDestino, $sSqlPontoComFs);
      $iTotalFolhaPonto = pg_num_rows($rsSqlPonto);
      $oDadosPonto      = db_utils::getColectionByRecord($rsSqlPonto); 

      if ( $iTotalFolhaPonto > 0 ) {
      
        /**
         *  Percorre o vetor de Registro dos Dados do Ponto (destino) e verifica
         *  se os mesmo existem na base de origem.
         *  
         *  Caso existam, � feita a soma do valor ou a substitui��o, de acordo com
         *  a op��o selecionada pelo usu�rio em "A��o para Lan�amentos Existentes"
         */
        foreach ( $oDadosPonto as $oRegDadosPonto ) {

          foreach ( $aDadosInserir as $iChaveDadosInserir => $aRegInserir ) {
            
            if ( $oPost->iAnoDestino    == $oRegDadosPonto->$iRegAnoUsoFolha &&
                 $oPost->iMesDestino    == $oRegDadosPonto->$iRegMesUsoFolha &&
                 $aRegInserir["regist"] == $oRegDadosPonto->$iRegRegistFolha &&
                 $aRegInserir["rubric"] == $oRegDadosPonto->$iRegRubricFolha ) {

              /**
               * Armazena o regist para verificar posteriormente se ser� necess�rio efetuar UPDATE ao inv�s de INSERT
               */
              $aDadosUpdate[$aRegInserir["regist"]] = $aRegInserir["regist"];

              if ($oPost->iOperacao == 1 ) {
                // Soma
                $nNovoValorSomado = ( $oRegDadosPonto->$iRegValorFolha + $aRegInserir["valor"] );
                $aDadosInserir[$iChaveDadosInserir]["valor"] = $nNovoValorSomado;
              } else {
                // Substitui
                $aDadosInserir[$iChaveDadosInserir]["valor"] = $oRegDadosPonto->$iRegValorFolha;
              }
            }
          }
        }
      }

      $lErroSql = false;
      db_inicio_transacao();

      /**
       *  Percorremos o array $aDadosInserir para inserir os mesmo na tabela pontofs ou pontocom
       *  de acordo com a sele��o do usu�rio no formul�rio.
       * 
       *  A v�ri�vel $iKeyRegist � utilizada apenas para verificar se o servidor (regist) est� no
       *  array de dados que ser�o atualizados ao inv�is de inseridos.
       */
      foreach ( $aDadosInserir as $iKeyRegist => $aDados ) {

        /**
         *  Verifica se o servidor est� no array $aDadosUpdate. Se estiver, � feito o UPDATE dos valores encontrados
         *  no banco de dados.
         */
        if ( array_key_exists($aDados["regist"],$aDadosUpdate) ) {
          

          $sWherePonto  = " {$sSiglaDestino}_anousu     = {$oPost->iAnoDestino}   ";
          $sWherePonto .= " and {$sSiglaDestino}_mesusu = {$oPost->iMesDestino}   ";
          $sWherePonto .= " and {$sSiglaDestino}_rubric = '{$aDados["rubric"]}' ";
          $sWherePonto .= " and {$sSiglaDestino}_regist = '{$aDados["regist"]}' ";
          $sWherePonto .= " and {$sSiglaDestino}_lotac  = '{$aDados["lotac"]}'  ";

          $sUpdatePonto = " update  {$sTabelaDestino}  set  {$sSiglaDestino}_valor = {$aDados["valor"]} where {$sWherePonto}";
          $rsExecutaUpdate = db_query($sUpdatePonto);

          if ( !$rsExecutaUpdate ) {
            
            $lErroSql = true;
            $sMsgErro = "N�o foi poss�vel substituir o registro existente para o servidor {$aDados['regist']}.\\n\\nContate o suporte.";
          }
          
        } else {
          /**
           *  Configura��es de SQL de acordo com a sele��o do usu�rio no formul�rio
           */
          if ( $oPost->iTipoFolhaDestino == 1 ) {
            // Folha SAL�RIO            
            $sCamposPonto  = "pessoal.pontofs (r10_anousu,r10_mesusu,r10_regist,r10_rubric,r10_valor,r10_quant,r10_lotac,r10_datlim,r10_instit)";
            $sValuesPonto  = "VALUES ({$oPost->iAnoDestino} ,{$oPost->iMesDestino},     ";
            $sValuesPonto .= "        {$aDados['regist']} ,'{$aDados['rubric']}',       ";
            $sValuesPonto .= "        {$aDados['valor']}  ,{$aDados['quant']},          ";
            $sValuesPonto .= "        '{$aDados['lotac']}',null,{$aDados['instit']})    ";
                      
          } else if ( $oPost->iTipoFolhaDestino == 2 ) {
            // Folha COMPLEMENTAR
            $sCamposPonto  = "pessoal.pontocom (r47_anousu,r47_mesusu,r47_regist,r47_rubric,r47_valor,r47_quant,r47_lotac,r47_instit)";
            $sValuesPonto  = "VALUES ({$oPost->iAnoDestino} ,{$oPost->iMesDestino},     ";
            $sValuesPonto .= "        {$aDados['regist']} ,'{$aDados['rubric']}',       ";
            $sValuesPonto .= "        {$aDados['valor']}  ,{$aDados['quant']} ,         ";
            $sValuesPonto .= "        '{$aDados['lotac']}',{$aDados['instit']})         ";          
          }

          $rsExecutaInsert = db_query("insert into {$sCamposPonto}  {$sValuesPonto}");

          if ( !$rsExecutaInsert ) {
            
            $lErroSql = true;
            $sMsgErro = "N�o foi poss�vel inserir um registro para o servidor {$aDados['regist']}.\\n\\nContate o suporte.";
          }
        }
      }
         
      db_fim_transacao($lErroSql);
    }
  }
}
?>
<html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
   
    <?
      db_app::load("scripts.js, prototype.js, widgets/dbtextField.widget.js");
      db_app::load("widgets/messageboard.widget.js, widgets/windowAux.widget.js, widgets/dbtextField.widget.js");
      db_app::load("widgets/dbcomboBox.widget.js, estilos.css, grid.style.css");
    ?>
    
    <style>
    .fieldsetWindowAux {
      margin: 20px;
      width: auto;
    }
    
    legend {
      font-weight: bold;
    }
    </style>
  </head>
  
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_limpaFormulario();" >
<script>

</script>
<br><br>
<center>
<form method="post" name="form2" onSubmit='return js_procDiferencaSalarial();'>
  <fieldset style="width: 500px; padding:20px;">
    <legend>Banco de Dados de Origem</legend>
    <table width="100%">
      <tr>
        <td width="170px">
          <strong>
          <?
            db_ancora("Banco de Dados:", "js_selecionaBanco(); js_buscaBases();", 1);
          ?>
          </strong>
        </td>
        <td>
          <?
            db_input("sBancoOrigem",       40, '', true, "text",   3);
            db_input('iIPOrigemHidden',    30, '', true, 'hidden', 1);
            db_input('sBancoOrigemHidden', 30, '', true, 'hidden', 1);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <strong>
            Compet�ncia:
          </strong>
        </td>
        <td>
          <?
            db_input('iAnoOrigem', 3, '', true, 'text', 1);
            echo " / ";
            db_input('iMesOrigem', 1, '', true, 'text', 1);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <strong>
            Tipo de Folha:
          </strong>
        </td>
        <td>
          <?
            $aTipoFolha = array( "0" => "Selecione", "1" => "Sal�rio", "2" => "Complementar" );
            db_select('iTipoFolhaOrigem', $aTipoFolha, true, 1, "style='width: 150px;'");
          ?>        
        </td>
      </tr>
      <tr>
        <td>
          <strong>
            Processar Diferen�as Por:
          </strong>
        </td>
        <td>
          <?
            db_input("aConfRubrica", 30, '', true, 'hidden');
            
            $aProcessaDiferencas = array( "0" => "Total de Proventos", "1" => "Rubricas" );
            db_select('iProcessarDiferenca', $aProcessaDiferencas, true, 1, "style='width: 150px;'");
            
            echo "&nbsp;&nbsp;";
            echo "<span id='spanAncoraProcDifer' style='display:none;'><strong>";
              db_ancora("Configurar", "js_configRubrica();", 1);
            echo "</strong></span>";
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <strong>
            Sele��o:
          </strong>
        </td>
        <td>
          <?
            db_input("sConteudoSelecaoIframe", 30, '', 1, 'hidden'); // Guarda Somente IDS
            db_input("sTipoFiltroIframe", 30, '', 1, 'hidden');      // Guarda o Tipo de Filtro dentro do IFRAME
            db_input("sSelecaoIframe", 30, '', 1, 'hidden');         // Guarda o VALUE da SELECAO
            db_input("sGuardaSelecionados", 30, '', 1, 'hidden');    // Guarda os dados para listar no componetne cl_arquivoauxiliar();
            
            $aSelecao = array( 0 => "Geral", 1 => "Matr�culas", 2 => "Lota��o", 3 => "Cargo" );
            db_select('iSelecao', $aSelecao, true, '', "style='width: 150px;'");
            
            echo "&nbsp;&nbsp;";
            echo "<span id='spanAncoraSelecao' style='display:none;'><strong>";
              db_ancora("Configurar", "js_configSelecao();", 1);
            echo "</strong></span>";
          ?>
        </td>
      </tr>      
    </table>
  </fieldset>
  <br />
  <fieldset style="width: 500px; padding:20px;">
    <legend>Banco de Dados de Destino</legend>
    <table width="100%">
      <tr>
        <td width="170px">
          <b>Base de Destino:</b>
        </td>
        <td>
          <?
            db_input('sBaseDestino', 40, '', true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <b>Compet�ncia:</b>
        </td>
        <td>
          <?
            db_input('iAnoDestino', 3, '', true, 'text', 1);
            echo " / ";
            db_input('iMesDestino', 1, '', true, 'text', 1);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <b>Tipo de Folha:</b>
        </td>
        <td>
          <?
            $aTipoFolhaDestino = array( 0 => "Selecione", 1 => "Sal�rio", 2 => "Complementar" );
            db_select('iTipoFolhaDestino', $aTipoFolhaDestino, true, 1, "style='width: 150px;'");
          ?> 
        </td>
      </tr>
      <tr>
        <td>
          <b>
            <?
              db_ancora("Rubrica:",'js_pesqRubricaDestino(true)', 1);
            ?>
          </b>
        </td>
        <td>
          <?
            db_input('iRubricaDestino', 5, '', true, 'text', 1, 'onchange=js_pesqRubricaDestino(false);');
            
            db_input('rh27_descr', 31, '', true, 'text', 3);
          ?> 
        </td>
      </tr>
      <tr>
        <td>
          <b>A��o para Lan�amentos Existentes:</b>
        </td>
        <td>
          <?
            $aOpcaoLancamentoExistente = array(0 => "Selecione", 1 => "Somar", 2 =>"Substituir");
            db_select('iOperacao',$aOpcaoLancamentoExistente, true, 1, "style='width: 150px;'");
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <br />
  <input type="submit" name="btnProcessaDiferSalario" id="btnProcessaDiferSalario" value="Processar">
</form>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
  oWindowSelecionaBanco  = '';
  oWindowConfRubrica     = '';
  
  /**
   *  Fun��o js_procDiferencaSalarial()
   *  Valida os dados fornecidos pelo usu�rio e processa as informa��es.
   */
  function js_procDiferencaSalarial(){
  
    // Banco de Origem
    var sBancoOrigem      = $('sBancoOrigem').value;
    var sIpBancoOrigem    = $('iIPOrigemHidden').value;
    var iAnoOrigem        = $('iAnoOrigem').value;
    var iMesOrigem        = $('iMesOrigem').value;
    var iTipoFolhaOrigem  = $('iTipoFolhaOrigem').value;
    var sProcDifPorOrigem = $('iProcessarDiferenca').value;
    var sSelecaoOrigem    = $('iSelecao').value;
    
    var sCamposComErroOrigem  = "";
    var sRubricasConfiguradas = null;
    /**
     *  Valida��o dos Dados de Origem
     */
    if ( sBancoOrigem == "" ) {
      sCamposComErroOrigem += "Banco Origem,";
    }
    if ( iAnoOrigem == "" || iMesOrigem == "" ) {
      sCamposComErroOrigem += "Compet�ncia,";
    }
    if ( iTipoFolhaOrigem == "0" || iTipoFolhaOrigem == "" ) {
      sCamposComErroOrigem += "Tipo de Folha,";
    }
    if ( sProcDifPorOrigem == "" ) {
      sCamposComErroOrigem += "Processar Diferen�as Por,";
    }
    if ( sSelecaoOrigem == "" ) {
      sCamposComErroOrigem += "Selecao,";
    }
    
    // Verifica se o processamento � por RUBRICA
    if ( sProcDifPorOrigem == "1" ) {
      sRubricasConfiguradas = $('aConfRubrica').value;
      if ( sRubricasConfiguradas == "" ) {
        alert("Informe as rubricas que deseja processar.");
        return false;
      }
    }
    
    // Valida se foi configurado sele��o para o Tipo de Sele��o
    if ( sSelecaoOrigem != 0 ) {
      if ( $('sConteudoSelecaoIframe').value == "" ) {
        alert("Configure os dados do campo Sele��o.");
        return false;
      }
    }    
    
    if ( sCamposComErroOrigem != "" ) {
      alert("O(s) campo(s) "+sCamposComErroOrigem+" de ORIGEM n�o foram preenchidos corretamente.");
      return false;
    }
    
    
    
    /* FIM VALIDACAO => ORIGEM */
    
    // Banco de Destino
    
    var sBaseDestino      = '<?=$iIPOrigemHidden;?>';
    var iAnoDestino       = $('iAnoDestino').value;
    var iMesDestino       = $('iMesDestino').value;
    var iTipoFolhaDestino = $('iTipoFolhaDestino').value;
    var iRubricaDestino   = $('iRubricaDestino').value;
    var iAcaoLancamento   = $('iOperacao').value;
    
    var sCamposComErroDestino = "";

    if ( iAnoDestino == "" || iAnoDestino > <?=$iAnoOrigem;?> || iMesDestino == "" || iMesDestino > 12 ) {
      sCamposComErroDestino += "Compet�ncia,";
    }
    if ( iTipoFolhaDestino == "0" || iTipoFolhaDestino == "" ) {
      sCamposComErroDestino += "Tipo de Folha,";
    }
    if ( iRubricaDestino == "" ) {
      sCamposComErroDestino += "Rubrica,";
    }
    if ( iAcaoLancamento == "0" || iAcaoLancamento == "" ) {
      sCamposComErroDestino += "A��o para Lan�amentos Existentes,";
    }
    
    if ( sCamposComErroDestino != "" ) {
      alert("O(s) campo(s) "+sCamposComErroDestino+" de DESTINO n�o foram preenchidos corretamente.");
      return false;
    }
    
    
    if ( !confirm("Este procedimento pode demandar algum tempo.\n\nVoc� deseja continuar com o processamento das diferen�as salariais?") ) {
      return false;
    }
  }
  
  /**
   *  Fun��o selecionaBanco
   *  Configura e mostra a WINDOW que possibilita o usu�rio alterar o
   *  banco desejado. 
   */
  function js_selecionaBanco() {
  
    var iIpDesejado = $('iIPOrigemHidden').value;

    var sContWinAuxBanco  = "<center>";
        sContWinAuxBanco += "<fieldset class='fieldsetWindowAux'>";
        sContWinAuxBanco += "  <legend>Pesquisa Base de Dados Origem</legend>";
        sContWinAuxBanco += "  <table width='100%'>";
        sContWinAuxBanco += "    <tr>";
        sContWinAuxBanco += "      <td width='120px'><b>IP do Servidor:</b></td>";
        sContWinAuxBanco += "      <td><input type='text' name='var_iIpServidor' id='var_iIpServidor' value='"+iIpDesejado+"' size='20' onchange='js_buscaBases();' ></td>";
        sContWinAuxBanco += "    </tr>";
        sContWinAuxBanco += "    <tr>";
        sContWinAuxBanco += "      <td><b>Base de Dados:</b></td>";
        sContWinAuxBanco += "      <td><span id='spanMostraBases'></span></td>";
        sContWinAuxBanco += "    </tr>";
        sContWinAuxBanco += "  </table>";
        sContWinAuxBanco += "</fieldset>";
        sContWinAuxBanco += "<p align='center'><input type='button' name='btnSelecionaBanco' value='Confirmar' onclick='js_preencheBanco();'></p>";
        sContWinAuxBanco += "</center>";    

    // Verifica se j� existe uma instancia da classe. Caso n�o exista, ele cria, do contr�rio, mostra.
    if ( oWindowSelecionaBanco == '' ) {
    
      oWindowSelecionaBanco = new windowAux("windowAux_origem", "Pesquisa Base de Dados Origem", 500, 300);
      oWindowSelecionaBanco.setContent(sContWinAuxBanco); // Set conteudo WindowAux
      
      /**
       *  Configura��es do MessageBoard
       */
      var sMsgBoardTitle = "Banco de Dados";
      var sMsgBoardHelp  = "Selecione a base de dados origem.";
      var oMessageBoard  = new messageBoard('messageBoard_origem', 
                                             sMsgBoardTitle, 
                                             sMsgBoardHelp,
                                             oWindowSelecionaBanco.getContentContainer()
                                            );
      oMessageBoard.show(); // Mostra MessageBoard
    }
    oWindowSelecionaBanco.show(); // Mostra WindowAux
  
  }
  
  
  /**
   *  Fun��o js_configRubrica
   *  Cria um objeto do tipo windowAux para apresentar o iFrame para 
   *  configura��o de rubricas.
   */
  function js_configRubrica() {
    
    var sContWinConfigRubrica  = "<center>";
        sContWinConfigRubrica += "<iframe src='pes1_procConfigRubrica001.php' width='100%' height='100%' scrolling='no' frameborder='0'>";
        sContWinConfigRubrica += "</center>";
        
    if ( oWindowConfRubrica == '' ) {    
      oWindowConfRubrica = new windowAux("windowAux_configRubrica", "Configurar Rubricas");
      oWindowConfRubrica.setContent(sContWinConfigRubrica);
      oWindowConfRubrica.allowCloseWithEsc(true);
        /**
         *  Configura��es do MessageBoard
         */
        var sMsgBoardTitleRub = "Configura��es de Rubricas";
        var sMsgBoardHelpRub  = "Selecione as rubricas desejadas.";
        var oMessageBoardRub  = new messageBoard('messageBoard_configRubrica', 
                                                 sMsgBoardTitleRub, 
                                                 sMsgBoardHelpRub,
                                                 oWindowConfRubrica.getContentContainer()
                                                );
            oMessageBoardRub.show();
    }
    oWindowConfRubrica.show();
  }
  
  
  /** 
   *  Fun��o js_configSelecao
   */
  
  function js_configSelecao() {
  
    var sConteudoSelecaoIframe = $('sConteudoSelecaoIframe').value;
    var sTipoFiltroIframe     = $('sTipoFiltroIframe').value;
    var sSelecaoIframe         = $('sSelecaoIframe').value;
    var sGuardaSelecionados    = $('sGuardaSelecionados').value;
    var iSelecaoSelecionada    = $('iSelecao').value;
    var sTipoSelecao           = "";
    
    oWinAuxMatricula = '';
    oWinAuxLotacao   = '';
    oWinAuxCargo     = '';
  
    var sUrlIframe  = "pes1_procConfigSelecao001.php?iSelecao="+iSelecaoSelecionada;
        sUrlIframe += "&sSelecaoIframe="+sSelecaoIframe;
        sUrlIframe += "&sTipoFiltroIframe="+sTipoFiltroIframe;
        sUrlIframe += "&sConteudoSelecaoIframe="+sConteudoSelecaoIframe;
        sUrlIframe += "&sGuardaSelecionados="+sGuardaSelecionados;

    var sContWindowAuxSelecao  = "<center>";
        sContWindowAuxSelecao += "<iframe src='"+sUrlIframe+"' width='100%' height='100%' scrolling='no' frameborder='0'>";
        sContWindowAuxSelecao += "</center>";
        
    /**
     * WindowAux Matricula
     */
    if ( iSelecaoSelecionada == 1 ) {
    
      if ( oWinAuxMatricula == "" ) {
      
        oWinAuxMatricula = new windowAux('windowAux_SelecaoMatricula', "Configurar Matr�culas");
        oWinAuxMatricula.setContent(sContWindowAuxSelecao);
        
        /**
         *  Configura��es do MessageBoard
         */
        var sMsgBoardTitleMatri = "Configura��es de Matriculas";
        var sMsgBoardHelpMatri  = "Selecione as matr�culas desejadas.";
        var oMessageBoardMatri  = new messageBoard('messageBoard_configSelecaoMatricula', 
                                                   sMsgBoardTitleMatri, 
                                                   sMsgBoardHelpMatri,
                                                   oWinAuxMatricula.getContentContainer()
                                                  );
            oMessageBoardMatri.show();
      }
      oWinAuxMatricula.show();
    }
    
    /**
     *  WindowAux Lotacao
     */
    if ( iSelecaoSelecionada == 2 ) {
    
      if ( oWinAuxLotacao == "" ) {
      
        oWinAuxLotacao = new windowAux('windowAux_SelecaoLotacao', "Configurar Lota��o");
        oWinAuxLotacao.setContent(sContWindowAuxSelecao);
        
        /**
         *  Configura��es do MessageBoard
         */
        var sMsgBoardTitleLotac = "Configura��es de Lota��o";
        var sMsgBoardHelpLotac  = "Selecione as lota��es desejadas.";
        var oMessageBoardLotac  = new messageBoard('messageBoard_configSelecaoLotacao', 
                                                   sMsgBoardTitleLotac, 
                                                   sMsgBoardHelpLotac,
                                                   oWinAuxLotacao.getContentContainer()
                                                  );
            oMessageBoardLotac.show();
      }
      oWinAuxLotacao.show();
    }
    
    /**
     *  Cargo
     */
    if ( iSelecaoSelecionada == 3 ) {
    
      if ( oWinAuxCargo == "" ) {
      
        oWinAuxCargo = new windowAux('windowAux_SelecaoCargo', "Configurar Cargo");
        oWinAuxCargo.setContent(sContWindowAuxSelecao);
        
        /**
         *  Configura��es do MessageBoard
         */
        var sMsgBoardTitleCargo = "Configura��es de Cargo";
        var sMsgBoardHelpCargo  = "Selecione as cargos desejados.";
        var oMessageBoardCargo  = new messageBoard('messageBoard_configSelecaoCargo', 
                                                   sMsgBoardTitleCargo, 
                                                   sMsgBoardHelpCargo,
                                                   oWinAuxCargo.getContentContainer()
                                                  );
            oMessageBoardCargo.show();
      }
      oWinAuxCargo.show();
    }
    
    
  }
  
  /**
   *  Fun��o buscaBases
   *  Executa AJAX e retorna um array com as bases do ip digitado
   */
  function js_buscaBases () {
    
    js_divCarregando('Aguarde, atualizando...',"msgBoxBuscaBases");
  
    var iIpDigitado = $('var_iIpServidor').value;
    
    var oParam            = new Object();
        oParam.sExec      = "buscaBase";
        oParam.iIp        = iIpDigitado;
        oParam.sBaseAtual = '<?=$sNomeBaseAtual;?>'; 
        
    var oAjax       = new Ajax.Request ('buscaBasesClientes.RPC.php',
                                         {
                                            method: 'post',  
                                            parameters:'json='+Object.toJSON(oParam),
                                            onComplete: js_retornoBuscaBases  
                                         });
  }

  /**
   *  Fun��o retornoBuscaBases
   *  Trata os dados de retorno do ajax acima.
   */
  function js_retornoBuscaBases(oAjax) {
    
    js_removeObj('msgBoxBuscaBases');
    var oRetorno = eval("("+oAjax.responseText+")");
    
    if ( oRetorno.lErro || oRetorno.lErroConexao ) {
    
      alert ("N�o foi poss�vel recuperar as bases do servidor "+$('iIPOrigemHidden').value)+".";
      $('var_iIpServidor').value = $('iIPOrigemHidden').value;
      js_buscaBases();
      return false;
    } else {
      
      /**
       *  Essa fun��o faz com que o array tenha o mesmo valor para chave e valor dentro
       *  do array. Facilitando o resgate do nome da base dentro do Widget DBComboBox
       */
      var aOpcaoSelect = new Array();
      oRetorno.aBases.each(
        function (sBase) {        
          aOpcaoSelect[sBase] = sBase;
        }
      );
    }
    
    var oObjSelect = new DBComboBox("oObjSelect", "oObjSelect", aOpcaoSelect);
        oObjSelect.show($('spanMostraBases'));
        
  }

  /**
   *  Fun��o preencheBanco
   *  Altera os valores das vari�veis dentro do form de acordo com o banco/ip escolhido
   */
  function js_preencheBanco() {

    $('sBancoOrigem').value       = $('var_iIpServidor').value+" :: "+$('oObjSelect').value
    $('iIPOrigemHidden').value    = $('var_iIpServidor').value;
    var sBancoOrigem = $('sBancoOrigem').value.split(" :: ");
    $('sBancoOrigemHidden').value = sBancoOrigem[1];

    oWindowSelecionaBanco.hide();
  }

  /**
   *  Observa o selectbox para mostra ou n�o a �ncora configurar 
   */
  $('iProcessarDiferenca').observe('change', 
    function(event) {
      if ( $('iProcessarDiferenca').value == '1' ) {        // Rubrica
        $('spanAncoraProcDifer').style.display = '';
      } else if ( $('iProcessarDiferenca').value == '0' ) { // Total de Proventos
        $('spanAncoraProcDifer').style.display = 'none';
      }
    } 
  );
  
  $('iSelecao').observe('change', 
    function(event) {
      if ( $('iSelecao').value == 0 ) {               // Geral
        $('spanAncoraSelecao').style.display = 'none';
      } else {                                          // Demais Op��es
        $('spanAncoraSelecao').style.display = '';
      }
            
      $('sConteudoSelecaoIframe').value = "";
      $('sTipoFiltroIframe').value      = "";
      $('sSelecaoIframe').value         = "";
      $('sConteudoSelecaoIframe').value = "";
            
            
    }
  );


  /**
   *  Fun��o js_pesqRubricaDestino
   *  Pesquisa e preenche rubricas de destino
   */
  function js_pesqRubricaDestino( lMostra ) {
  
    if ( lMostra == true ) {
      js_OpenJanelaIframe('','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_preencheRubricaDestino|rh27_rubric|rh27_descr&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
    } else {
      if ( $('iRubricaDestino').value != '' ) { 
        js_OpenJanelaIframe('','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+$('iRubricaDestino').value+'&funcao_js=parent.js_preencheRubricaDestino1&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
      }else{
        $('iRubricaDestino').value = '';
      }
    }
  }
  
  function js_preencheRubricaDestino( iChave,sDescricao ) {
    
    if ( iChave != "" || sDescricao != "" ) {
      $('iRubricaDestino').value = iChave;
      $('rh27_descr').value      = sDescricao;
      db_iframe_rhrubricas.hide();
    } else {
      $('iRubricaDestino').value = ''
      $('rh27_descr').value      = '';
    }
  }
  
  function js_preencheRubricaDestino1( sDescricao,sErro ) {

    if ( !sErro ) {
      $('rh27_descr').value = sDescricao;
    } else {
      $('rh27_descr').value      = "Chave ("+$('iRubricaDestino').value+") n�o encontrada.";
      $('iRubricaDestino').value = '';
    }
  }
  
  
  /**
   *  Fun��o utilizada no ONLOAD da p�gina para limpar os dados do formul�rio.
   */
  function js_limpaFormulario() {
  
    $('sBancoOrigem').value        = "";
    $('iAnoOrigem').value          = <?=$iAnoOrigem;?>;
    $('iMesOrigem').value          = <?=$iMesOrigem;?>;
    $('iTipoFolhaOrigem').value    = "0";
    $('iProcessarDiferenca').value = "0";
    $('iSelecao').value            = 0;
    $('iAnoDestino').value         = "";
    $('iMesDestino').value         = "";
    $('iTipoFolhaDestino').value   = "0";
    $('iRubricaDestino').value     = "";
    $('rh27_descr').value          = "";
    $('iOperacao').value           = "0";
  }
</script>


<?
if ( isset ($oPost->btnProcessaDiferSalario) ) {
  db_msgbox($sMsgErro);    
}
?>