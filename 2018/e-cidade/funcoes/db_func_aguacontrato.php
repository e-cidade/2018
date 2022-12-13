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

$aCampos = array(
  'aguacontrato.x54_sequencial',
  'aguacontrato.x54_aguabase',
  'aguacontrato.x54_datainicial',
  'aguacontrato.x54_datafinal',
  'cgm.z01_numcgm',
  'cgm.z01_nome',
  'aguacontrato.x54_condominio',
  "(case
     when aguacontrato.x54_responsavelpagamento = 2 then
       'Condomínio'
     when aguacontrato.x54_responsavelpagamento = 1 then
       'Economia'
     else
       'Não aplicável'
   end)::varchar as x54_responsavelpagamento
   ",
);

$campos = implode(',', $aCampos);
