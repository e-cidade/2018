<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("std/DBDate.php");
require_once("libs/db_app.utils.php");
require_once("libs/smtp.class.php");

$oPost = db_utils::postMemory($_POST);
if (isset($oPost->btncadastrar)) {
  
  try {

    db_inicio_transacao();
    
    /**
     * Deletamos os registros do dia, caso já tenha:
     */
    $oDataDia                              = new DBDate($oPost->data);
    $oDaoControleAcessoAlunoRegistro       = db_utils::getDao("controleacessoalunoregistro");
    $oDaoControleAcessoAlunoRegistroValido = db_utils::getDao("controleacessoalunoregistrovalido");
    $oDaoControleAcesso                    = db_utils::getDao("controleacessoaluno");
    
    $sWhereDelete   = "ed101_dataleitura = '".$oDataDia->convertTo(DBDate::DATA_EN)."'";
    $sWhereDelete  .= " and  ed101_entrada is ".($oPost->entrada == 't'?" true ":" false ");
    $sSqlRegistros  = $oDaoControleAcessoAlunoRegistro->sql_query_file(null, "*", null, $sWhereDelete);
    
    $rsRegistros    = $oDaoControleAcessoAlunoRegistro->sql_record($sSqlRegistros);
    $iTotalLinhas   = $oDaoControleAcessoAlunoRegistro->numrows;
    if ($iTotalLinhas > 0) {
      
      $aLeituras = array();
      for ($i = 0; $i < $iTotalLinhas; $i++) {
        
        $oDadosLeitura = db_utils::fieldsMemory($rsRegistros, $i);
        $oDaoControleAcessoAlunoRegistroValido->excluir(null, "ed303_controleacessoalunoregistro = {$oDadosLeitura->ed101_sequencial}");
        if ($oDaoControleAcessoAlunoRegistroValido->erro_status == 0) {
        
          $sMensagemErro   = "Erro ao excluir leituras dos alunos.\\n ";
          $sMensagemErro  .= "Erro Técnico : {$oDaoControleAcessoAlunoRegistroValido->erro_msg}";
          throw new Exception($sMensagemErro);
        }
        
        $oDaoControleAcessoAlunoRegistro->excluir($oDadosLeitura->ed101_sequencial);
        if ($oDaoControleAcessoAlunoRegistro->erro_status == 0) {
        
          $sMensagemErro   = "Erro ao exluir dados da leitura.\\n ";
          $sMensagemErro  .= "Erro Técnico : {$oDaoControleAcessoAlunoRegistro->erro_msg}";
          throw new Exception($sMensagemErro);
        }
        $aLeituras[] = $oDadosLeitura->ed101_controleacessoaluno;
      }
      
      $oDaoControleAcesso->excluir(null, "ed100_sequencial in(".implode(",", $aLeituras).")");
      if ($oDaoControleAcesso->erro_status == 0) {
      
        $sMensagemErro   = "Erro ao excluir leituras do sistema.\\n ";
        $sMensagemErro  .= "Erro Técnico : {$oDaoControleAcesso->erro_msg}";
        throw new Exception($sMensagemErro);
      } 
    }
    
    $oDaoMatricula  = db_utils::getDao("matricula");
    $sWhere         = '';
    if (trim($oPost->alunos) != "") {
      
      $sWhere        = "ed60_i_aluno not in ({$oPost->alunos})";
    }
    $sSqlMatricula = $oDaoMatricula->sql_query(null, "ed60_i_aluno", null, $sWhere);
    $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);
    $iTotalAlunos  = $oDaoMatricula->numrows;
    $oDaoControleAcesso->ed100_dataleitura = $oDataDia->convertTo(DBDate::DATA_EN);
    $oDaoControleAcesso->ed100_horaleitura = $oPost->hora;
    $oDaoControleAcesso->ed100_id_usuario  = db_getsession("DB_id_usuario");
    $oDaoControleAcesso->incluir(null);
    if ($oDaoControleAcesso->erro_status == 0) {
      
      $sMensagemErro   = "Erro ao incluir leituras.\\n ";
      $sMensagemErro  .= "Erro Técnico : {$oDaoControleAcesso->erro_msg}";
      throw new Exception($sMensagemErro);
    }
    for ($i = 0; $i < $iTotalAlunos; $i++) {  
      
      $iCodigoAluno = db_utils::fieldsMemory($rsMatricula, $i)->ed60_i_aluno;
      $oDaoControleAcessoAlunoRegistro->ed101_controleacessoaluno = $oDaoControleAcesso->ed100_sequencial;
      $oDaoControleAcessoAlunoRegistro->ed101_dataleitura         = $oDaoControleAcesso->ed100_dataleitura;
      $oDaoControleAcessoAlunoRegistro->ed101_datasistema         = $oDaoControleAcesso->ed100_dataleitura;
      $oDaoControleAcessoAlunoRegistro->ed101_entrada             = $oPost->entrada=='t'?"true":"false";
      $oDaoControleAcessoAlunoRegistro->ed101_horaleitura         = $oPost->hora;
      $oDaoControleAcessoAlunoRegistro->ed101_horasistema         = $oPost->hora;
      $oDaoControleAcessoAlunoRegistro->incluir(null);
      if ($oDaoControleAcessoAlunoRegistro->erro_status == 0) {
      
        $sMensagemErro   = "Erro ao salvar acessos.\\n ";
        $sMensagemErro  .= "Erro Técnico : {$oDaoControleAcessoAlunoRegistro->erro_msg}";
        throw new Exception($sMensagemErro);
      }
      
      $oDaoControleAcessoAlunoRegistroValido->ed303_aluno = $iCodigoAluno;
      $oDaoControleAcessoAlunoRegistroValido->ed303_controleacessoalunoregistro = $oDaoControleAcessoAlunoRegistro->ed101_sequencial;
      $oDaoControleAcessoAlunoRegistroValido->incluir(null);
      if ($oDaoControleAcessoAlunoRegistroValido->erro_status == 0) {
      
        $sMensagemErro   = "Erro ao salvar dados do acesso do aluno.\\n ";
        $sMensagemErro  .= "Erro Técnico : {$oDaoControleAcessoAlunoRegistroValido->erro_msg}";
        throw new BusinessException($sMensagemErro);
      }
       
    } 
    
    db_msgBox("Leituras salvas com sucesso");
    db_fim_transacao(false);
  } catch (Exception $e) {
    
    db_msgbox($e->getMessage());
    db_fim_transacao(true);
  }
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load("scripts.js, strings.js, datagrid.widget.js, prototype.js, arrays.js, dbtextFieldData.widget.js");
      db_app::load("dbcomboBox.widget.js, windowAux.widget.js, dbmessageBoard.widget.js,dbtextField.widget.js");
      db_app::load("estilos.css, grid.style.css");
    ?>
    </style>
  </head>
  <body style="margin-top: 25px; background-color: #CCCCCC;">
    <form action="" method="post">
    <center> 
      <div style="display: table">
        <fieldset>
          <legend>
            <b>Inclusão de leituras no RFID</b>
          </legend>
          <table>
            <tr>
               <td>
                  <b>Data:</b>
               </td>
               <td>
                 <?
                   db_inputdata("data","", "", "", true, "text", 1);
                 ?>
               </td>
            </tr>
              <tr>
               <td>
                  <b>Hora:</b>
               </td>
               <td>
                 <?
                   db_input("hora", 5, 0, true, "text", 1);
                 ?>
               </td>
            </tr>
            <tr>
               <td>
                  <b>Entrada/Saída</b>
               </td>
               <td>
                 <?
                   db_select("entrada", array("t" => "Entrada", "f" => "Saída"), true, 1);
                 ?>
               </td>
            </tr>
            <tr>
               <td>
                  <b>Alunos sem Leitura:</b>
               </td>
               <td>
                 <?
                   db_input("alunos", 25, 0, true, "text", 1);
                 ?>
               </td>
            </tr>
          </table>
        </fieldset>
      </div>
      <input type="submit" value='Cadastrar' name='btncadastrar'>
      </form>
    </center>
  </body>
</html>  


<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>