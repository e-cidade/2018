<?php
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


require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_requisicaoaidof_classe.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$claidof      = new cl_aidof;
$clcadescrito = new cl_cadescrito;
$clcgm        = new cl_cgm;

$clcadescrito->rotulo->label();
$clcgm->rotulo->label();

$db_opcao = 1;
$db_botao = true;
?>
<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <form name="form1" action="fis4_aidof004.php" class="container" method="get">
      <fieldset style="margin-top:15px;">
        <legend>
          <strong>Requisição de Aidof</strong>
        </legend>
        <table border="0">
          <tr>
            <td>
              <?php db_ancora("Inscricao:","js_pesquisay08_inscr(true);",$db_opcao); ?>
            </td>
            <td> 
              <?php 
                db_input('y08_inscr', 10, 1, true, 'text', $db_opcao, " onchange='js_pesquisay08_inscr(false);'");
                db_input('z01_nome' , 40, 0, true, 'text', 3, " ");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?php echo $Tq86_numcgm; ?>">
              <?php db_ancora($Lq86_numcgm, "js_pesquisaq86_numcgm(true);", ($db_opcao == 2) ? 3 : $db_opcao); ?>
            </td>
            <td> 
              <?php 
                db_input('q86_numcgm', 10, $Iq86_numcgm, true, 'text', ($db_opcao == 2) ? 3 : $db_opcao,
                         " onchange='js_pesquisaq86_numcgm(false);'");
                
                db_input('z01_nome2', 40, $Iz01_nome, true, 'text', 3, '');
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </form>
  </body>
</html>
<?php
  db_menu(
    db_getsession("DB_id_usuario"),
    db_getsession("DB_modulo"),
    db_getsession("DB_anousu"),
    db_getsession("DB_instit")
  );
  ?>
<script>
  js_tabulacaoforms('form1', 'y116_tipodocumento', true, 1, 'y116_tipodocumento', true);
  
  /**
   * Função de pesquisa para a Empresa
   */
  function js_pesquisay08_inscr(mostra) {
    
    if (mostra) {
        
      js_OpenJanelaIframe('top.corpo', 'db_iframe_issbase', 
                          'func_issbase.php?funcao_js=parent.js_mostraissbase1|q02_inscr|z01_nome|q02_dtbaix',
                          'Pesquisa', true);
    } else {

      js_OpenJanelaIframe('', 'db_iframe_issbase', 
                          'func_issbase.php?pesquisa_chave=' + document.form1.y08_inscr.value + 
                          '&funcao_js=parent.js_mostraissbase', 'Pesquisa', false);
    }
  }

  function js_mostraissbase(chave, erro, baixa) {
    
    document.form1.z01_nome.value = chave; 

    if (erro == true) { 

      document.form1.y08_inscr.focus(); 
      document.form1.y08_inscr.value = ''; 
      $('processar').disabled = true;
      
    } else {

      if (baixa != "") {

        alert("Inscrição já Baixada");
        document.form1.y08_inscr.focus(); 
        document.form1.y08_inscr.value = ''; 
        document.form1.z01_nome.value  = ''; 
        $('processar').disabled = true;
      }
      
      $('processar').disabled = false;
    }
  }

  function js_mostraissbase1(chave1, chave2, baixa) {

    if (baixa != "") {

      db_iframe_issbase.hide();

      alert("Inscrição já Baixada");

      document.form1.y08_inscr.focus(); 
      document.form1.y08_inscr.value = ''; 
      document.form1.z01_nome.value  = '';
      $('processar').disabled = true;
    } else {

      document.form1.y08_inscr.value = chave1;
      document.form1.z01_nome.value  = chave2;
      db_iframe_issbase.hide();
      $('processar').disabled = false;
    }
  }


  /**
   * Função de pesquisa para o Escritório Contábil 
   */
  function js_pesquisaq86_numcgm(mostra) {
   
    if (mostra == true) {
      
      js_OpenJanelaIframe('top.corpo', 'db_iframe_cgm', 
                          'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome',
                          'Pesquisa', true);
    } else {       

      if (document.form1.q86_numcgm.value != '') { 

        js_OpenJanelaIframe('','db_iframe_cgm', 'func_nome.php?pesquisa_chave=' + document.form1.q86_numcgm.value + 
                            '&funcao_js=parent.js_mostracgm', 'Pesquisa', false);

      } else {
          
        document.form1.z01_nome2.value = ''; 
      }
    }
  }
  
  function js_mostracgm(erro, chave){
    
    document.form1.z01_nome2.value = chave; 
    
    if (erro == true) {
       
      document.form1.q86_numcgm.focus(); 
      document.form1.q86_numcgm.value = '';
    }
  }
  
  function js_mostracgm1(chave1, chave2) {
    
    document.form1.q86_numcgm.value = chave1;
    document.form1.z01_nome2.value  = chave2;
    
    db_iframe_cgm.hide();
  }

  /**
   * Função de pesquisa para as Requisições realizadas
   */
   
  function js_pesquisa() {

    js_OpenJanelaIframe('top.corpo','db_iframe_requisicaoAidof', 'func_requisicaoaidof.php?iNumCgm=' + document.form1.q86_numcgm.value +
                                                         '&iInscricao=' + document.form1.y08_inscr.value + 
                        '&funcao_js=parent.js_carregarequisicao|y116_id', 'Pesquisa', true);
  }
  
  function js_carregarequisicao(chave1) {

    db_iframe_requisicaoAidof.hide();
    location.href = 'fis1_requisicaoaidof002.php?idRequisicao='+chave1;
  }
    
</script>