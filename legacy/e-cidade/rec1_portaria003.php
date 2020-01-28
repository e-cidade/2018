<?php
/**
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);

$clportaria              = new cl_portaria;
$classenta               = new cl_assenta;
$clrhpessoal             = new cl_rhpessoal;
$clportariaassenta       = new cl_portariaassenta;
$clportariatipo          = new cl_portariatipo;
$classentamentofuncional = new cl_assentamentofuncional;
          

$db_botao = false;
$db_opcao = 33;
$sqlerro  = false;
$erro_msg = "";

$lExibirNumeracaoPortaria = true;
$db_opcao_numero = 3;

try {

  if(isset($excluir)) {


    /**
     * Verificamos se o assentamento já não esta vinculado com um lote de registros de ponto
     * se estiver, não permite a exclusão.
     */
    $oDaoAssentaLoteRegistroPonto = new cl_assentaloteregistroponto();
    $sSqlAssentaLoteRegistroPonto = $oDaoAssentaLoteRegistroPonto->sql_query_file(null, "rh160_sequencial", null, "rh160_assentamento = {$h16_codigo}");
    $rsAssentaLoteRegistroPonto   = db_query($sSqlAssentaLoteRegistroPonto);
   
    if (pg_num_rows($rsAssentaLoteRegistroPonto) > 0) {
      
      db_msgbox("Assentamento já possuí evento financeiro, exclusão não permitida.");
      db_redireciona("");
    }

    db_inicio_transacao();
    $db_opcao = 3;

    /**
     * Tratamento para exclusão de assentamentos de substituição
     */
    $oDaoAssentamentoSubstituicao = new cl_assentamentosubstituicao();
    $rsAssentamentoSubstituicao   = $oDaoAssentamentoSubstituicao->sql_record($oDaoAssentamentoSubstituicao->sql_query_file($h16_codigo));

    if($rsAssentamentoSubstituicao && $oDaoAssentamentoSubstituicao->numrows > 0){
      $oDaoAssentamentoSubstituicao->excluir($h16_codigo);
    }
    
    $clportariaassenta->excluir(null,"h33_portaria = $h31_sequencial ");
    if($classenta->erro_status !='0') {
      $classentamentofuncional->excluir($h16_codigo);
    }
          

    if ($clportariaassenta->erro_status == "0"){
         $sqlerro  = true;
         $erro_msg = $clportariaassenta->erro_msg;
    }

    if ($sqlerro == false){

      /**
       * Verificamos a configuração se há tipo de assentamentos do RH que geram afastamentos do pessoal
       * se houver excluímos o afastamento vinculado
       */
      $oAssentamento  = AssentamentoRepository::getInstanceByCodigo($h16_codigo);
      $aListaInformacoesExternas = InformacoesExternasTipoAssentamento::getTipoAssentamentoConfiguradosPorCompetencia(DBPessoal::getCompetenciaFolha());

      if(is_array($aListaInformacoesExternas)){

        $aTiposAssentamentoConfigurados = array();
        foreach ($aListaInformacoesExternas as $oInformacoesExternas) {
          $aTiposAssentamentoConfigurados[] = $oInformacoesExternas->getTipoAssentamento()->getCodigo();
        }

        if( in_array($oAssentamento->getTipoAssentamento(), $aTiposAssentamentoConfigurados) ) {

          $aAfastaAssenta = AfastaAssentaRepository::getAfastamentosPorAssentamento($oAssentamento);

          if(!is_array($aAfastaAssenta)) {
            throw new BusinessException("Não foi possível buscar o vínculo entre assentamento e afastamento.");
          }

          $oAfastamento   = $aAfastaAssenta[0];
          $oAfastaAssenta = new AfastaAssenta($oAssentamento, $oAfastamento);

          /**
           * Excluímos o vínculo entre assentamentos e afastamentos
           */
          if(!$oAfastaAssenta->remove()) {
            throw new BusinessException("Erro ao excluir o vínculo entre o assentamento e afastamento.");
          }

          /**
           * Excluímos o afastamento que foi originado a partir do assentamento
           */
          if(!AfastamentoRepository::remove($oAfastamento)) {
            throw new BusinessException("Erro ao excluir o afastamento.");
          }
        }
      }

      $clportaria->excluir($h31_sequencial);
      if ($clportaria->erro_status == "0") {

          $sqlerro  = true;
          $erro_msg = $clportaria->erro_msg;
      }
    }

    if ($h80_db_cadattdinamicovalorgrupo) {
      $oDaoAssentaAttr = new cl_assentadb_cadattdinamicovalorgrupo();
      $oDaoAssentaAttr->excluir(null,null, "h80_db_cadattdinamicovalorgrupo = {$h80_db_cadattdinamicovalorgrupo}" );
    }

    if ($sqlerro == false && !empty($h16_codigo)) {

      $classenta->excluir($h16_codigo);
      if ($classenta->erro_status == "0"){
           $sqlerro  = true;
           $erro_msg = $classenta->erro_msg;
      }
    }
    
    db_fim_transacao($sqlerro);
  }else if(isset($chavepesquisa)){
     $db_opcao = 3;
     $result = $clportaria->sql_record($clportaria->sql_query($chavepesquisa));
     $classentamentofuncional = new cl_assentamentofuncional;
     $rsAssentamentoFuncional = db_query($classentamentofuncional->sql_query($chavepesquisa));
     $sOpcaoAssentamento      = 1;

     if($rsAssentamentoFuncional && pg_num_rows($rsAssentamentoFuncional) > 0) {
       $sOpcaoAssentamento    = 2;
     }
           
     db_fieldsmemory($result,0);
     $db_botao = true;

     if(isset($h16_regist) && !empty($h16_regist)) {
      
      $oServidor = ServidorRepository::getInstanciaByCodigo($h16_regist, DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());

      if($oServidor instanceof Servidor) {
        $z01_nome = $oServidor->getCgm()->getNome();
      }
    }

    if ($result && pg_numrows($result) > 0) {
    
      $res_portariaassenta = $clportariaassenta->sql_record($clportariaassenta->sql_query_file(null,"h33_assenta",null,"h33_portaria = {$h31_sequencial}"));

      db_fieldsmemory($res_portariaassenta,0);
             
      $oDaoAssentaAttr = new cl_assentadb_cadattdinamicovalorgrupo();
      $rsComplemento   = db_query($oDaoAssentaAttr->sql_query(null,null, "h80_db_cadattdinamicovalorgrupo", null, "h80_assenta = {$h33_assenta}"));
      if (pg_num_rows($rsComplemento) > 0) {
        db_fieldsmemory($rsComplemento,0);
      }
    }
  }
} catch (Exception $oException) {
    db_msgbox($oException->getMessage());
    db_redireciona("rec1_portaria003.php");
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

	<?php include(modification("forms/db_frmportaria.php")); ?>
  <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>

</body>
</html>
<?
if(isset($excluir)){
  if($clportaria->erro_status=="0"){
    $clportaria->erro(true,false);
  }else{
    $clportaria->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
renderizarFormulario();
js_criarCamposAdicionais();
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>
