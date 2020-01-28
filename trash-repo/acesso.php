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
require_once ("libs/db_utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ('model/configuracao/PreferenciaUsuario.model.php');
db_postmemory($HTTP_POST_VARS);

require("libs/db_conecta.php");
require("libs/db_usuariosonline.php");

if (isset($atualiza)) {
  
  /**
   * Atualiza o nome do banco de dados
   */
  if (isset($db_base)) {

    db_putsession("DB_NBASE",$db_base);
    $DB_BASE = db_getsession("DB_NBASE");
    echo "<script>
            top.topo.document.getElementById('auxAcesso').value = '".$DB_BASE."';
          </script>";
  }
    
  /**
   * Salva as preferências do usuário
   */
  try{

    $oUsuarioSistema     = new UsuarioSistema(db_getsession("DB_id_usuario"));
    $oPreferenciaUsuario = $oUsuarioSistema->getPreferenciasUsuario();
    $oPreferenciaUsuario->setOrdenacao($_POST['preferencia_menu']);
    $oPreferenciaUsuario->setExibeBusca($_POST['exibe_busca']);
    
    $oPreferenciaUsuario->salvar();

    $sMensagem = _M('configuracao.configuracao.preferenciaUsuario.sucesso');
  } catch (Exception $oErro){
    $sMensagem = $oErro->getMessage();;
  }
}

if(!isset($trocaip) && !isset($atualiza)){
 
  $result = db_query("select nome,login,id_usuario from db_usuarios where id_usuario = ".db_getsession("DB_id_usuario"));

  if (pg_result($result,0,'id_usuario')==1) {
    $atualiza = true;
  }
}

?>
<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
      #conteudo_abas{
        width: 600px;
        margin: 0 auto;
        margin-top: 20px;
      }

      #conteudo_abas .containerConteudo{
        border: 1px solid #a8a8a8;
      }

      .bt_salvar{
        margin: 0 auto;
        display: block;
      }
    </style>
  </head>

  <body bgcolor="#ccc" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

   <form name="form1" action="" method="post" class="form-container">
     <div id="aba_banco">
        <fieldset>
          <legend>Configuração de Banco de dados</legend>
           <table border="0" cellspacing="0" cellpadding="2">
             <tr> 
               <td> <strong>Nome:</strong> </td>
               <td> <?=@pg_result($result,0,0)?> </td>
             </tr>
             <tr> 
               <td><strong>Login:</strong></td>
               <td><?=@pg_result($result,0,1)?></td>
             </tr>
             <tr> 
               <td><strong>Base de dados atual:</strong></td>
               <td><?=$DB_BASE?></td>
             </tr>
             <tr> 
               <td><strong>Servidor:</strong></td>
               <td><input readonly name="servidor" value="<?=$DB_SERVIDOR?>" type="text" size="20"></td>
             </tr>
             <tr> 
               <td><strong>IP:</strong></td>
               <td><?=(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])?></td>
             </tr>
             <tr> 
               <td><strong>Local:</strong></td>
               <td><?=$HTTP_SERVER_VARS['PHP_SELF'];?></td>
             </tr>
             <?php
               if (isset($HTTP_SESSION_VARS["DB_SELLER"]) || (isset($atualiza) || isset($db_ip))) {

                 $result = db_query("select datname from pg_database where substr(datname,1,6) != 'templa' order by datname"); 
                 if ($result!=false && pg_numrows($result)!=0) {

                   if (!session_is_registered("DB_anousu")) {
                     $ano = date("Y");
                   } else {
                     $ano = db_getsession("DB_anousu");
                   }

                   $permissao_parcelamento=db_permissaomenu($ano,1,5333); // 5333
                   ?>
                   <tr> 
                   <?php
                   if ($permissao_parcelamento == "true" or db_getsession("DB_id_usuario") == 1) {
                   ?>
                     <td><strong>Base:</strong></td>
                     <td><select name="db_base">
                      <?php
                        for($bb=0;$bb<pg_numrows($result);$bb++){
                      ?>
                         <option value="<?=pg_result($result,$bb,0)?>" <?=($DB_BASE==pg_result($result,$bb,0)?"selected":"")?>><?=pg_result($result,$bb,0)?></option> 
                   <?php
                        }
                   }
                   ?>
                       </select>
                     </td>
                   </tr>
          <?php
                 }
              }
          ?>
          </table>
       </fieldset>
       <input type="submit" class="bt_salvar" name="atualiza" value="Salvar" />
     </div>
     
     <div id="aba_preferencias">
       <fieldset>

         <?php 
           $oPreferencias    = unserialize(base64_decode(db_getsession('DB_preferencias_usuario')));
           $preferencia_menu = $oPreferencias->getOrdenacao();
           $exibe_busca      = $oPreferencias->getExibeBusca();
           $aOrdenação  = array('sequencial' => 'Ordenação padrão do sistema', 'alfabetico' => 'Alfabética');
           $aBuscaMenus = array('0' => 'Não', '1' => 'Sim');
         ?>
         <legend>Preferências do Usuário</legend>
         <table border="0"  class="form-container">
           <tr>
             <td><strong>Ordenação dos Menus:</strong></td>
             <td><?php db_select('preferencia_menu', $aOrdenação, true, 1) ?></td>
           </tr>
           <tr>
            <td><strong>Exibe Busca por Menus:</strong></td>
            <td><?php db_select('exibe_busca', $aBuscaMenus, true, 1) ?></td>
           </tr>
         </table>
       </fieldset>
       <input type="submit" class="bt_salvar" name="atualiza" value="Salvar" />
     </div>
     
     <div id="conteudo_abas" class="container"></div>
    </form>


    <script>
      var oDBAbas = new DBAbas($('conteudo_abas'));
      oDBAbas.adicionarAba('Preferências' , $('aba_preferencias'));
      oDBAbas.adicionarAba('Acesso ao sistema', $('aba_banco'));
    </script>
    <?php
        if (isset($sMensagem)){
    ?>
          <script>
            alert('<?php echo $sMensagem ?>');
          </script>
    <?php
        }
    ?>
  </body>
</html>