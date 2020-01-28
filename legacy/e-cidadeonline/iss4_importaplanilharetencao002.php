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

session_start();

require_once 'libs/db_stdlib.php';
require_once 'libs/db_sql.php';
require_once 'libs/db_utils.php';
require_once 'libs/db_app.utils.php';

require_once 'model/dbLayoutReader.model.php';
require_once 'model/dbLayoutLinha.model.php';
require_once 'model/planilhaRetencao.model.php'; 
require_once 'model/issqn/NotaPlanilhaRetencao.model.php'; 

require_once 'std/DBDate.php';

require_once 'dbforms/db_funcoes.php';

require_once 'classes/db_cgm_classe.php';
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - Prefeitura On - Line</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="javaScript" src="scripts/db_script.js"></script>
    <script language="javaScript" src="scripts/scripts.js"></script>
    <style type="text/css">
     <?php db_estilosite(); ?>
    </style>
    <link href="config/estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="<?php echo $w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>


  <script>
  js_divCarregando("Processando arquivo \nAguarde.", "divCarregando");
  </script>
  </body>
</html>
<?php
flush();

try {

  $oArquivoEnviado = db_utils::postMemory($_FILES['arquivo']);
  $oPost           = db_utils::postMemory($_POST);
  $oPost->cgc      = str_replace(array("/","-","."," "),'',$oPost->cgc);
    
  
  if ( $oPost->cgc == '' ) {
    throw new Exception('Informe o CNPJ/CPF');
  }

  if ( $oPost->inscricaow == '' ) {
    throw new Exception('Informe a inscrição');
  }

  $sSqlValidaDados  = "select q02_numcgm                                 ";
  $sSqlValidaDados .= "  from issbase                                    ";
  $sSqlValidaDados .= "        inner join cgm on z01_numcgm = q02_numcgm ";
  $sSqlValidaDados .= " where q02_inscr  = {$oPost->inscricaow}          ";
  $sSqlValidaDados .= "   and z01_cgccpf = '$oPost->cgc'                 ";

  $rsValidaDados    = db_query($sSqlValidaDados);
  
  $oValidaDados = db_utils::fieldsMemory($rsValidaDados, 0);

  if(pg_numrows($rsValidaDados) == 0) {
    throw new Exception('Não encontrado inscrição para o CNPJ/CPF informado');
  }

  $sNomeArquivo = 'tmp/'.date('Ymd_His').'_'.$oArquivoEnviado->name;

  $lUpload = move_uploaded_file($oArquivoEnviado->tmp_name, $sNomeArquivo);
    
  /**
   * Ocorreu erro ao mover arquivo para pasta tmp
   */
  if ( $lUpload == false ) {
    throw new Exception('Erro ao mover arquivo');
  }

  $oLayout = new DBLayoutReader(190, $sNomeArquivo);

  /**
   * Verifica se arquivo é do tipo txt
   */
  if ( $oArquivoEnviado->type <> 'text/plain' ) {
    throw new Exception('Arquivo inválido, somente txt');
  }

  /**
   * Array com as linhas do arquivo
   */
  $aLinhas = $oLayout->getLines();

  /**
   * Total de linhas informado no final do arquivo txt 
   */
  $iTotalLinhasArquivo = (int) max($aLinhas)->total_registros;

  /**
   * Total de linhas processadas (array aLinhas) 
   */
  $iTotalLinhasProcessadas   = count( $aLinhas );

  if ( $iTotalLinhasArquivo <> $iTotalLinhasProcessadas  ) {
    throw new Exception('Número de linhas não conferem');
  }

  db_inicio_transacao();

  /**
   * Competencia 
   */
  $iAnoUsu = !empty($oPost->ano) ? $oPost->ano : null;
  $iMesUsu = !empty($oPost->mes) ? $oPost->mes : null;

  /**
   * Cria planilha retenção
   */
  $oPlanilhaRetencao = new planilhaRetencao(null, $oValidaDados->q02_numcgm, $iAnoUsu, $iMesUsu, $oPost->inscricaow);

  foreach( $oLayout->getLines() as $oLinha ) {

    /**
     * Se for header ou trailer vai pro proximo
     */
    if ( $oLinha->identificador <> '2' ) {
      continue;
    }

    $oNotaRetencao = new NotaPlanilhaRetencao(); 
    $oNotaRetencao->setCodigoPlanilha  ( $oPlanilhaRetencao->getCodigoPlanilha() );
    $oNotaRetencao->setDataOperacao    ( new DBDate( date('Y-m-d', db_getsession('DB_datausu')) ) );
    $oNotaRetencao->setHoraOperacao    ( db_hora() );
    $oNotaRetencao->setTipoLancamento  ( NotaPlanilhaRetencao::SERVICO_TOMADO );
    $oNotaRetencao->setNome            ( "" );
    $oNotaRetencao->setRetido          ( true );
    $oNotaRetencao->setStatus          ( NotaPlanilhaRetencao::STATUS_ATIVO );
    $oNotaRetencao->setSituacao        ( '0' ); // 0 - Normal | 1 - cancelado
    $oNotaRetencao->setDataNota        ( new DBDate(  $oPlanilhaRetencao->getDataPlanilha() ) );
    $oNotaRetencao->setCNPJ            ( $oLinha->cpf_cnpj_prestador );
    $oNotaRetencao->setSerie           ( $oLinha->serie_nota );
    $oNotaRetencao->setNome            ( substr( $oLinha->nome_razao_social, 0, 40 ) );
    
    $iNumeroNota = preg_replace("@0+@","",$oLinha->numero_nota);
    $oNotaRetencao->setNumeroNota      ($iNumeroNota );
    
    $iValorServico = substr($oLinha->valor_servico, 0, -2).".".substr($oLinha->valor_servico, -2, 2);
    $oNotaRetencao->setValorServico ( $iValorServico );
    
    $iValorRetencao = substr($oLinha->valor_imposto, 0, -2).".".substr($oLinha->valor_imposto, -2, 2);
    $oNotaRetencao->setValorRetencao ( $iValorRetencao ); 
    
    $iAliquota     = substr($oLinha->aliquota, 0, -2).".".substr($oLinha->aliquota, -2, 2);
    $oNotaRetencao->setAliquota ( $iAliquota );   

    $iValorDeducao = substr($oLinha->valor_deducao, 0, -2).".".substr($oLinha->valor_deducao, -2, 2);
    $oNotaRetencao->setValorDeducao ( $iValorDeducao );
    
    $iValorBase    = substr($oLinha->valor_base, 0, -2).".".substr($oLinha->valor_base, -2, 2);
    $oNotaRetencao->setValorBase ( $iValorBase );
    
    $iValorImposto = substr($oLinha->valor_imposto, 0, -2).".".substr($oLinha->valor_imposto, -2, 2);
    $oNotaRetencao->setValorImposto    ( $iValorImposto);
    
    $oNotaRetencao->setDescricaoServico( "Recolhimento de retencao" );
    $oNotaRetencao->setObservacoes     ( "Realizado via importaçao de arquivo no DBPREF" );
    $oPlanilhaRetencao->adicionarNota  ( $oNotaRetencao );
  }

  db_fim_transacao(false);
  db_msgbox('Importação efetuada com sucesso.');
  db_redireciona("planilha.php?nomecontri=&mostra=5&fonecontri=&inscricaow={$oPost->inscricaow}&mesx=&mes={$iMesUsu}&ano={$iAnoUsu}&numcgm={$oValidaDados->q02_numcgm}");

} catch(Exception $oErro) {

  db_fim_transacao(true);
  db_msgbox($oErro->getMessage());
  db_redireciona('iss4_importaplanilharetencao001.php');
}
?>