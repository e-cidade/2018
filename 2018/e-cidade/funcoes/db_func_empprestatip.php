<?
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

$campos = "empprestatip.e44_tipo,empprestatip.e44_descr, case when empprestatip.e44_obriga = 0 then 'Não Presta'::varchar
                                                              when empprestatip.e44_obriga = 1 then 'Obriga Valores'::varchar
                                                              when empprestatip.e44_obriga = 2 then 'Obriga Contas'::varchar end as e44_obriga,
                                                         case when empprestatip.e44_naturezaevento = 1 then 'Não se Aplica'::varchar
                                                              when empprestatip.e44_naturezaevento = 2 then 'Adiantamentos Concedidos'::varchar
                                                              when empprestatip.e44_naturezaevento = 3 then 'Subvenções e Auxílios'::varchar end as e44_naturezaevento";
?>