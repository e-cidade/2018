<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
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

use Classes\PostgresMigration;

class M8655ManutencaoEmLote extends PostgresMigration
{

  public function up()
  {

    $this->execute("insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10429 ,'Manutenção em Lote' ,'Manutenção em Lote' ,'rec4_pontoeletronicomanutencaolote001.php' ,'1' ,'1' ,'Menu para lançamento de horas em lote para o ponto eletrônico.' ,'true' )");
    $this->execute("insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10384 ,10429 ,4 ,2323 )");
    $this->execute("update db_itensmenu set id_item = 10392 , descricao = 'Manutenção Individual' , help = 'Manutenção Individual' , funcao = 'rec4_pontoeletronicomanutencao001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Rotina para manutenção do ponto eletrônico dos servidores.' , libcliente = 'true' where id_item = 10392");
    $this->execute("drop index if exists pontoeletronicoarquivodata_pontoeletronicoarquivo_data_pis_un_i");
  }

  public function down() {

    $this->execute("delete from db_menu where id_item_filho = 10429 AND modulo = 2323");
    $this->execute("delete from db_itensmenu where id_item = 10429");
  }
}