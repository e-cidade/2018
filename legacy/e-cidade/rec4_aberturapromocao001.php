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
require_once(modification("libs/db_utils.php"));

require_once(modification("classes/db_rhpromocao_classe.php"));
require_once(modification("classes/db_rhavaliacao_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

require_once(modification('model/recursosHumanos/Promocao.model.php'));

$db_opcao      = 1;
$db_botao      = true;

$clrotulo      = new rotulocampo;
$clrotulo->label("z01_nome");

$oDaoRHPromocao  = new cl_rhpromocao;
$oDaoRHAvaliacao = new cl_rhavaliacao;

$oDaoRHPromocao->rotulo->label();
$oDaoRHPromocao->rotulo->tlabel();

$oPost  = db_utils::postmemory($HTTP_POST_VARS);
$oGet	  = db_utils::postmemory($HTTP_GET_VARS);

if( isset($oGet->iSequencial) ) {

	$sSqlCadastroPromocao = $oDaoRHPromocao->sql_query( $oGet->iSequencial, 'h72_regist,z01_nome, h72_dtinicial', null, 'h72_sequencial = '.$oGet->iSequencial );
	$rsCadastroPromocao   = $oDaoRHPromocao->sql_record($sSqlCadastroPromocao);
	$oCadastroPromocao    = db_utils::fieldsMemory($rsCadastroPromocao, 0);
	
	$h72_sequencial    = $oGet->iSequencial;
	$z01_nome          = $oCadastroPromocao->z01_nome;
	$h72_regist        = $oCadastroPromocao->h72_regist;
	
	$dDataInicial = $oCadastroPromocao->h72_dtinicial;
	
	$h72_dtinicial_dia = date('d', strtotime($dDataInicial));
	$h72_dtinicial_mes = date('m', strtotime($dDataInicial));
	$h72_dtinicial_ano = date('Y', strtotime($dDataInicial));
	
	$db_opcao = 3;
}


?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equi$oPostv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<br /><br />

<center>
  <form name="form1" method="post" action="">
    <fieldset style="width:605px;">
      <legend><?php echo $Lrhpromocao; ?></legend>
      <table border="0" width="605">

        <tr>
          <td nowrap title="<?=@$Th72_sequencial?>">
             <?=@$Lh72_sequencial?>
          </td>
          <td> 
          <?
            db_input('h72_sequencial',10,$Ih72_sequencial,true,'text', 3,"")
          ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=@$Th72_regist?>">
          <?
            db_ancora("<b>Matrí­cula:</b>","js_pesquisah72_regist(true);",$db_opcao);
          ?>
          </td>
          <td> 
          <?
            db_input('h72_regist',10,$Ih72_regist,true,'text',$db_opcao," onchange='js_pesquisah72_regist(false);'");
            db_input('z01_nome',50,$Iz01_nome,true,'text',3,'');
          ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=@$Th72_dtinicial?>">
             <?=@$Lh72_dtinicial?>
          </td>
          <td> 
          <?
            db_inputdata('h72_dtinicial',@$h72_dtinicial_dia,@$h72_dtinicial_mes,@$h72_dtinicial_ano,true,'text',$db_opcao,"")
          ?>
          </td>
        </tr>

      </table>
    </fieldset>
    <?php 
    if ($db_opcao == 3) {
     echo "<input name=\"novo\" value=\"Novo\" type=\"button\" onClick=\"window.location.href = 'rec4_aberturapromocao001.php';\" />";
    }
    ?>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >

  </form>

</center>

<script>

function js_pesquisah72_regist(lMostra) {

  if (lMostra == true) {

    js_OpenJanelaIframe('CurrentWindow.corpo', 
                        'db_iframe_rhpessoal',
                        'func_rhpessoal.php?filtro_lotacao=true&funcao_js=parent.js_mostrarhpessoal1|rh01_regist|z01_nome', 
                        'Pesquisa', 
                        true);
  } else {

     if(document.form1.h72_regist.value != ''){ 
       
       js_OpenJanelaIframe('CurrentWindow.corpo', 
                           'db_iframe_rhpessoal',
                           'func_rhpessoal.php?filtro_lotacao=true&pesquisa_chave='+document.form1.h72_regist.value+'&funcao_js=parent.js_mostrarhpessoal',
                           'Pesquisa',
                           false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}

function js_mostrarhpessoal(sNomeServidor, erro) {

  document.form1.z01_nome.value = sNomeServidor; 

  if (erro == true){ 

    document.form1.h72_regist.focus(); 
    document.form1.h72_regist.value = ''; 
  }
}

function js_mostrarhpessoal1(chave1, chave2) {

  document.form1.h72_regist.value  = chave1;
  document.form1.z01_nome.value    = chave2;
  db_iframe_rhpessoal.hide();
}

function js_pesquisa(){

  js_OpenJanelaIframe('CurrentWindow.corpo',
                      'db_iframe_rhpromocao',
                      'func_rhpromocao.php?funcao_js=parent.js_preenchepesquisa|h72_sequencial&lAtivo=1',
                      'Pesquisa',
                      true);
}

function js_preenchepesquisa(chave) {
  db_iframe_rhpromocao.hide();
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?iSequencial='+chave";
  ?>
}
</script>

<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>

</body>
</html>
<?php

try {

	
  if ( isset($oPost->incluir) ) {

    $sSqlPromocao     = $oDaoRHPromocao->sql_query( null, '1', null, "h72_regist = {$oPost->h72_regist} and h72_ativo is true");
    $rsPromocao       = $oDaoRHPromocao->sql_record($sSqlPromocao);
                      
    $iNumRowsPromocao = $oDaoRHPromocao->numrows;
    
    if ($iNumRowsPromocao > 0) {
      throw new Exception("Já Existe Interstício ativo para este servidor. \\nInclusão abortada.");
    }
    
    db_inicio_transacao();
    
    $oPromocao = new Promocao();
    $oPromocao->setMatriculaServidor($oPost->h72_regist);
    $oPromocao->setDataInicioPromocao($oPost->h72_dtinicial);
    $oPromocao->enablePromocao();
    $oPromocao->salvar();
    
    db_fim_transacao(false);
    
    db_msgbox("Promoção: " . $oPromocao->getCodigoPromocao() . 
              "\nIncluida com Sucesso!");
    db_redireciona('rec4_aberturapromocao001.php?iSequencial='.$oPromocao->getCodigoPromocao());
     
  }

  if ( isset($oPost->excluir) ) {
    
    db_inicio_transacao();

    $oPromocao   = new Promocao($oGet->iSequencial);
    $aAvaliacoes = $oPromocao->getAvaliacoes();
    
    if ( count($aAvaliacoes) > 0 ) {
      throw new Exception("Promoção com avaliações ativas.");
    }    
    
    $oPromocao->cancelar();
    
    db_fim_transacao(false);
    db_msgbox( "Excluido com sucesso." );
    db_redireciona('rec4_aberturapromocao001.php');
  	 
  }
   
  
} catch  (Exception $oErro) {
	db_fim_transacao(true);
	db_msgbox( $oErro->getMessage() );
	
}

?>