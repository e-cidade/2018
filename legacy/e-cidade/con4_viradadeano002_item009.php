<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

// Para garantir que nao houve erros em outros itens
if ($sqlerro == false) {

  // PERMISSÕES DE USUÁRIOS";
  $oDaoDBPermissao = db_utils::getDao("db_permissao");
  $oDaoDBUsuMod    = db_utils::getDao("db_usumod");

  // Lista permissoes do ano de origem
  $sqlorigem    = $oDaoDBPermissao->sql_query_file(null, null, null, null, null, "distinct id_usuario", null, "anousu = {$anoorigem}");
  $resultorigem = $oDaoDBPermissao->sql_record($sqlorigem);
  if (is_resource($resultorigem)) {
  	$linhasorigem = $oDaoDBPermissao->numrows;

    $usuariodest  = "";
    $vir          = "";

    for ($pu = 0; $pu < $linhasorigem; $pu++) {

      db_fieldsmemory($resultorigem, $pu);
      db_atutermometro($pu, $linhasorigem, 'termometroitem', 1, $sMensagemTermometroItem);

      // Exclui permissoes no ano destino para garantir Reprocessamento
      $oDaoDBPermissao->excluir(null, null, null, null, null, "anousu = $anodestino and id_usuario = $id_usuario");
      if ($oDaoDBPermissao->erro_status == 0) {

        $sqlerro   = true;
        $erro_msg .= $oDaoDBPermissao->erro_msg;
        break;
      }

      // inclui no ano destino
      $sqlinclui    = "select *                                                       ";
      $sqlinclui   .= "  from db_permissao                                            ";
      $sqlinclui   .= " where id_usuario = {$id_usuario}                              ";
      $sqlinclui   .= "   and anousu     = {$anoorigem}                               ";
      $sqlinclui   .= "   and not exists( select *                                    ";
      $sqlinclui   .= "                     from db_permissao a                       ";
      $sqlinclui   .= "                    where a.id_usuario = {$id_usuario}         ";
      $sqlinclui   .= "                      and a.anousu     = {$anodestino}         ";
      $sqlinclui   .= "                      and a.id_item    = db_permissao.id_item) ";

      $resultinclui = db_query($sqlinclui);
      $linhasinclui = pg_num_rows($resultinclui);

      if ($linhasinclui > 0) {

        for ($pui = 0; $pui < $linhasinclui; $pui++) {

          db_fieldsmemory($resultinclui, $pui);

          $oDaoDBPermissao->id_usuario     = $id_usuario;
          $oDaoDBPermissao->id_item        = $id_item;
          $oDaoDBPermissao->permissaoativa = $permissaoativa;
          $oDaoDBPermissao->anousu         = $anodestino;
          $oDaoDBPermissao->id_instit      = $id_instit;
          $oDaoDBPermissao->id_modulo      = $id_modulo;
          $oDaoDBPermissao->incluir($id_usuario,$id_item,$anodestino,$id_instit,$id_modulo);
          if ($oDaoDBPermissao->erro_status == 0) {

            $sqlerro   = true;
            $erro_msg .= $oDaoDBPermissao->erro_msg;
            break;
          }
        }

        if ($sqlerro == true) {
          break;
        }
      }
    }

    if ($sqlerro == false) {

      // deletar permissoes do ano de origem dos usuários NAO ADMINISTRADORES
      $dbwhere  = " anousu = {$anoorigem}                                                     ";
      $dbwhere .= "and not exists (select *                                                   ";
      $dbwhere .= "                  from db_usuarios                                         ";
      $dbwhere .= "                 where db_usuarios.id_usuario    = db_permissao.id_usuario ";
      $dbwhere .= "                   and db_usuarios.administrador = 1)                      ";
      $oDaoDBPermissao->excluir(null, null, null, null, null, $dbwhere);
      if ($oDaoDBPermissao->erro_status == 0) {

        $sqlerro   = true;
        $erro_msg .= $oDaoDBPermissao->erro_msg;
      }
    }

    if ($sqlerro == false) {

      $sWhere               = "anousu = {$anoorigem}";
      $oDaoDBUsuMod->anousu = $anodestino;
      $oDaoDBUsuMod->alterar_where($sWhere);
      if ($oDaoDBUsuMod->erro_status == 0) {

        $sqlerro   = true;
        $erro_msg .= $oDaoDBUsuMod->erro_msg;
      }
    }

  } else {

    $cldb_viradaitemlog->c35_log           = "Não existem Permissões de usuário para o exercício {$anoorigem}";
    $cldb_viradaitemlog->c35_codarq        = 174;
    $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
    $cldb_viradaitemlog->c35_data          = date("Y-m-d");
    $cldb_viradaitemlog->c35_hora          = date("H:i");
    $cldb_viradaitemlog->incluir(null);
    if ($cldb_viradaitemlog->erro_status == 0) {

      $sqlerro   = true;
      $erro_msg .= $cldb_viradaitemlog->erro_msg;
    }
  }

  DBMenu::limpaCache();
}
?>