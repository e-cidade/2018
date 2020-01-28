<?
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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$db_opcao = 1;
$escola = db_getsession("DB_nomedepto");
$codescola = db_getsession("DB_coddepto");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
 .t0im {
  border: 0px;
  width: 16x;
  height: 16px;
 }
</style>
</head>
<script>
function tree (a_items,a_template) {

        this.a_tpl      = a_template;
        this.a_config   = a_items;
        this.o_root     = this;
        this.a_index    = [];
        this.o_selected = null;
        this.n_depth    = -1;

        var o_icone = new Image(),
                o_iconl = new Image();
        o_icone.src = a_template['icon_e'];
        o_iconl.src = a_template['icon_l'];
        a_template['im_e'] = o_icone;
        a_template['im_l'] = o_iconl;
        for (var i = 0; i < 64; i++)
                if (a_template['icon_' + i]) {
                        var o_icon = new Image();
                        a_template['im_' + i] = o_icon;
                        o_icon.src = a_template['icon_' + i];
                }

        this.toggle = function (n_id) { var o_item = this.a_index[n_id]; o_item.open(o_item.b_opened) };
        this.select = function (n_id) { return this.a_index[n_id].select(); };
        this.mout   = function (n_id) { this.a_index[n_id].upstatus(true) };
        this.mover  = function (n_id) { this.a_index[n_id].upstatus() };

        this.a_children = [];
        for (var i = 0; i < a_items.length; i++)
                new tree_item(this, i);

        this.n_id = trees.length;
        trees[this.n_id] = this;
        for (var i = 0; i < this.a_children.length; i++) {
                document.write(this.a_children[i].init());
                this.a_children[i].open();
        }
}
function tree_item (o_parent, n_order) {
        this.n_depth  = o_parent.n_depth + 1;
        this.a_config = o_parent.a_config[n_order + (this.n_depth ? 2 : 0)];
        if (!this.a_config) return;

        this.o_root    = o_parent.o_root;
        this.o_parent  = o_parent;
        this.n_order   = n_order;
        this.b_opened  = !this.n_depth;

        this.n_id = this.o_root.a_index.length;
        this.o_root.a_index[this.n_id] = this;
        o_parent.a_children[n_order] = this;

        this.a_children = [];
        for (var i = 0; i < this.a_config.length - 2; i++)
                new tree_item(this, i);

        this.get_icon = item_get_icon;
        this.open     = item_open;
        this.select   = item_select;
        this.init     = item_init;
        this.upstatus = item_upstatus;
        this.is_last  = function () { return this.n_order == this.o_parent.a_children.length - 1 };
}

function item_open (b_close) {
        var o_idiv = get_element('i_div' + this.o_root.n_id + '_' + this.n_id);
        if (!o_idiv) return;

        if (!o_idiv.innerHTML) {
                var a_children = [];
                for (var i = 0; i < this.a_children.length; i++)
                        a_children[i]= this.a_children[i].init();
                o_idiv.innerHTML = a_children.join('');
        }
        o_idiv.style.display = (b_close ? 'none' : 'block');

        this.b_opened = !b_close;
        var o_jicon = document.images['j_img' + this.o_root.n_id + '_' + this.n_id],
                o_iicon = document.images['i_img' + this.o_root.n_id + '_' + this.n_id];
        if (o_jicon) o_jicon.src = this.get_icon(true);
        if (o_iicon) o_iicon.src = this.get_icon();
        this.upstatus();
}

function item_select (b_deselect) {
        if (!b_deselect) {
                var o_olditem = this.o_root.o_selected;
                this.o_root.o_selected = this;
                if (o_olditem) o_olditem.select(true);
        }
        var o_iicon = document.images['i_img' + this.o_root.n_id + '_' + this.n_id];
        if (o_iicon) o_iicon.src = this.get_icon();
        get_element('cor_div' + this.o_root.n_id + '_' + this.n_id).style.fontWeight = b_deselect ? 'normal' : 'bold';
        get_element('cor_div' + this.o_root.n_id + '_' + this.n_id).style.color = b_deselect ? '#000000' : '#DEB887';
        get_element('cor_div' + this.o_root.n_id + '_' + this.n_id).style.backgroundColor = b_deselect ? '#CCCCCC' : '#444444';

        this.upstatus();
        return Boolean(this.a_config[1]);
}

function item_upstatus (b_clear) {
        window.setTimeout('window.status="' + (b_clear ? '' : this.a_config[0] + (this.a_config[1] ? ' ('+ this.a_config[1] + ')' : '')) + '"', 10);
}
function js_aguarde(){
 parent.document.getElementById("tab_aguarde").style.left = screen.availHeight-250;
 parent.document.getElementById("tab_aguarde").style.visibility = "visible";
}

function item_init () {
        var a_offset = [],
                o_current_item = this.o_parent;
        for (var i = this.n_depth; i > 1; i--) {
                a_offset[i] = '<img src="' + this.o_root.a_tpl[o_current_item.is_last() ? 'icon_e' : 'icon_l'] + '" border="0" align="absbottom">';
                o_current_item = o_current_item.o_parent;
        }
        texto = this.a_config[0];
        title = texto.split(":");
        return '<table cellpadding="0" cellspacing="0" border="0"><tr><td style="font-size:10px;" nowrap>' + (this.n_depth ? a_offset.join('') + (this.a_children.length
                ? '<a style="text-decoration:none;color:#FF0000;" href="javascript: trees[' + this.o_root.n_id + '].toggle(' + this.n_id + ')" onmouseover="trees[' + this.o_root.n_id + '].mover(' + this.n_id + ')" onmouseout="trees[' + this.o_root.n_id + '].mout(' + this.n_id + ')"><img src="' + this.get_icon(true) + '" border="0" align="absbottom" name="j_img' + this.o_root.n_id + '_' + this.n_id + '"></a>'
                : '<img src="' + this.get_icon(true) + '" border="0" align="absbottom">') : '')
                + '<a onclick="js_aguarde();" style="text-decoration:none;color:#000000" href="' + this.a_config[1] + '" target="' + this.o_root.a_tpl['target'] + '" onclick="return trees[' + this.o_root.n_id + '].select(' + this.n_id + ');" ondblclick="trees[' + this.o_root.n_id + '].toggle(' + this.n_id + ')" onmouseover="trees[' + this.o_root.n_id + '].mover(' + this.n_id + ');" onmouseout="trees[' + this.o_root.n_id + '].mout(' + this.n_id + ')" class="t' + this.o_root.n_id + 'i" id="i_txt' + this.o_root.n_id + '_' + this.n_id + '"><img src="' + this.get_icon() + '" border="0" align="absbottom" name="i_img' + this.o_root.n_id + '_' + this.n_id + '" class="t' + this.o_root.n_id + 'im">&nbsp;<span id="cor_div' + this.o_root.n_id + '_' + this.n_id + '" title="'+title[0]+'">' + title[1] + '</div></a></td></tr></table>' + (this.a_children.length ? '<div id="i_div' + this.o_root.n_id + '_' + this.n_id + '" style="display:none"></div>' : '');
}

function item_get_icon (b_junction) {
 return this.o_root.a_tpl['icon_' + ((this.n_depth ? 0 : 32) + (this.a_children.length ? 16 : 0) + (this.a_children.length && this.b_opened ? 8 : 0) + (!b_junction && this.o_root.o_selected == this ? 4 : 0) + (b_junction ? 2 : 0) + (b_junction && this.is_last() ? 1 : 0))];
}
var trees = [];
get_element = document.all ?
 function (s_id) { return document.all[s_id] } :
 function (s_id) { return document.getElementById(s_id) };

var TREE_ITEMS = [
                   ['Escola: <b><?=$escola?></b>', '',
                   <?
                   $sql = "SELECT DISTINCT ed52_i_codigo,ed52_c_descr,ed52_i_ano
                           FROM calendario
                            inner join turma on ed57_i_calendario = ed52_i_codigo
                            inner join matricula on ed60_i_turma = ed57_i_codigo
                            inner join calendarioescola on ed38_i_calendario = ed52_i_codigo
                           WHERE ed38_i_escola = $codescola
                           AND ed52_c_passivo = 'N'
                           ORDER BY ed52_i_ano,ed52_c_descr
                           ";
                   $query = db_query($sql);
                   $linhas = pg_num_rows($query);
                   if($linhas==0){
                    ?>
                    ['Calendário: <b>Nenhuma turma com matrículas</b>', ''],
                    <?
                   }else{
                    for($a=0;$a<$linhas;$a++){
                    ?>
                     ['Calendário: <b><?=trim(pg_result($query,$a,"ed52_c_descr"))?></b>', 'edu1_diarioclasse003.php?ed52_i_codigo=<?=pg_result($query,$a,"ed52_i_codigo")?>&ed52_c_descr=<?=trim(pg_result($query,$a,"ed52_c_descr"))?>',
                     <?
                     $sql1 = "SELECT DISTINCT ed29_i_codigo,ed29_c_descr,ed10_c_abrev
                              FROM cursoedu
                               inner join cursoescola on ed71_i_curso = ed29_i_codigo
                               inner join base on ed31_i_curso = ed29_i_codigo
                               inner join turma on ed57_i_base = ed31_i_codigo
                               inner join matricula on ed60_i_turma = ed57_i_codigo
                               inner join ensino on ed10_i_codigo = ed29_i_ensino
                              WHERE ed57_i_calendario = ".pg_result($query,$a,"ed52_i_codigo")."
                              AND ed71_i_escola = $codescola
                              AND ed57_i_escola = $codescola
                              ORDER BY ed10_c_abrev
                             ";
                     $query1 = db_query($sql1);
                     $linhas1 = pg_num_rows($query1);
                     for($b=0;$b<$linhas1;$b++){
                     ?>
                       ['Curso: <b><?=trim(pg_result($query1,$b,"ed29_c_descr"))?></b>', 'edu1_diarioclasse003.php?ed29_i_codigo=<?=pg_result($query1,$b,"ed29_i_codigo")?>&proximo=<?=trim(pg_result($query1,$b,"ed29_c_descr"))?>&calendario=<?=pg_result($query,$a,"ed52_i_codigo")?>&ed52_c_descr=<?=trim(pg_result($query,$a,"ed52_c_descr"))?>',
                       <?
                       $sql11 = "SELECT DISTINCT ed218_i_codigo,ed218_c_nome
                                 FROM regimemat
                                  inner join base on ed31_i_regimemat = ed218_i_codigo
                                  inner join turma on ed57_i_base = ed31_i_codigo
                                  inner join matricula on ed60_i_turma = ed57_i_codigo
                                  inner join escolabase on ed77_i_base = ed31_i_codigo
                                 WHERE ed31_i_curso = ".pg_result($query1,$b,"ed29_i_codigo")."
                                 AND ed57_i_calendario = ".pg_result($query,$a,"ed52_i_codigo")."
                                 AND ed77_i_escola = $codescola
                                 ORDER BY ed218_i_codigo
                                ";
                       $query11 = db_query($sql11);
                       $linhas11 = pg_num_rows($query11);
                       for($bb=0;$bb<$linhas11;$bb++){
                       ?>
                         ['Regime de Matrícula: <b><?=trim(pg_result($query11,$bb,"ed218_c_nome"))?></b>', 'edu1_diarioclasse003.php?ed218_i_codigo=<?=pg_result($query11,$bb,"ed218_i_codigo")?>&ed29_i_codigo=<?=pg_result($query1,$b,"ed29_i_codigo")?>&proximo=<?=trim(pg_result($query11,$bb,"ed218_c_nome"))?>&calendario=<?=pg_result($query,$a,"ed52_i_codigo")?>&ed52_c_descr=<?=trim(pg_result($query,$a,"ed52_c_descr"))?>&',
                         <?
                         $sql2 = "SELECT DISTINCT ed31_i_codigo,ed31_c_descr,ed31_i_regimemat
                                  FROM base
                                   inner join turma on ed57_i_base = ed31_i_codigo
                                   inner join matricula on ed60_i_turma = ed57_i_codigo
                                   inner join escolabase on ed77_i_base = ed31_i_codigo
                                  WHERE ed31_i_curso = ".pg_result($query1,$b,"ed29_i_codigo")."
                                  AND ed57_i_calendario = ".pg_result($query,$a,"ed52_i_codigo")."
                                  AND ed31_i_regimemat = ".pg_result($query11,$bb,"ed218_i_codigo")."
                                  AND ed77_i_escola = $codescola
                                  ORDER BY ed31_c_descr desc
                                 ";
                         $query2 = db_query($sql2);
                         $linhas2 = pg_num_rows($query2);
                         for($c=0;$c<$linhas2;$c++){
                         ?>
                           ['Base Curricular: <b><?=trim(pg_result($query2,$c,"ed31_c_descr"))?></b>', 'edu1_diarioclasse003.php?ed31_i_codigo=<?=pg_result($query2,$c,"ed31_i_codigo")?>&ed31_i_regimemat=<?=pg_result($query2,$c,"ed31_i_regimemat")?>&proximo=<?=trim(pg_result($query2,$c,"ed31_c_descr"))?>&calendario=<?=pg_result($query,$a,"ed52_i_codigo")?>&ed52_c_descr=<?=trim(pg_result($query,$a,"ed52_c_descr"))?>&',
                           <?
                           $sql3 = "SELECT si.ed11_i_sequencia as inicial,sf.ed11_i_sequencia as final,si.ed11_i_ensino as ensino
                                     FROM baseserie
                                      inner join serie as si on si.ed11_i_codigo = baseserie.ed87_i_serieinicial
                                      inner join serie as sf on sf.ed11_i_codigo = baseserie.ed87_i_seriefinal
                                     WHERE ed87_i_codigo = ".pg_result($query2,$c,"ed31_i_codigo")."
                                    ";
                           $query3 = db_query($sql3);
                           $sql4 = "SELECT DISTINCT ed11_i_codigo,ed11_c_descr,ed11_i_sequencia
                                    FROM turma
                                     inner join matricula on ed60_i_turma = ed57_i_codigo
                                     inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
                                     inner join serie on ed11_i_codigo = ed221_i_serie
                                     inner join base on ed31_i_codigo = ed57_i_base
                                    WHERE ed11_i_sequencia >= ".pg_result($query3,0,"inicial")." AND ed11_i_sequencia <= ".pg_result($query3,0,"final")." AND ed11_i_ensino = ".pg_result($query3,0,"ensino")."
                                    AND ed57_i_calendario = ".pg_result($query,$a,"ed52_i_codigo")."
                                    AND ed57_i_escola = $codescola
                                    AND ed31_i_regimemat = ".pg_result($query11,$bb,"ed218_i_codigo")."
                                    AND ed221_c_origem = 'S'
                                    ORDER BY ed11_i_sequencia
                                   ";
                           $query4 = db_query($sql4);
                           $linhas4 = pg_num_rows($query4);
                           for($d=0;$d<$linhas4;$d++){
                           ?>
                             ['Etapa: <b><?=trim(pg_result($query4,$d,"ed11_c_descr"))?></b>', 'edu1_diarioclasse003.php?ed11_i_codigo=<?=pg_result($query4,$d,"ed11_i_codigo")?>&ed31_i_regimemat=<?=pg_result($query2,$c,"ed31_i_regimemat")?>&proximo=<?=trim(pg_result($query4,$d,"ed11_c_descr"))?>&ed52_c_descr=<?=trim(pg_result($query,$a,"ed52_c_descr"))?>&calendario=<?=pg_result($query,$a,"ed52_i_codigo")?>',
                             <?

                             $sql5 = "SELECT DISTINCT ed57_i_codigo, ed57_c_descr,
                                             (select count(*)
                                               from regencia
                                              where ed59_i_turma = ed57_i_codigo
                                                and ed59_i_serie = ed221_i_serie
                                                and ed59_procedimento <> ed220_i_procedimento ) as n_procedimentos
                                        FROM turma
                                       inner join matricula           on ed60_i_turma = ed57_i_codigo
                                       inner join matriculaserie      on ed221_i_matricula = ed60_i_codigo
                                       inner join turmaserieregimemat on ed220_i_turma = ed60_i_turma
                                       inner join serieregimemat      on ed223_i_codigo = ed220_i_serieregimemat
                                       inner join base on ed31_i_codigo = ed57_i_base
                                      WHERE ed221_i_serie in (".pg_result($query4,$d,"ed11_i_codigo").")
                                      AND ed57_i_calendario = ".pg_result($query,$a,"ed52_i_codigo")."
                                      AND ed57_i_escola = $codescola
                                      AND ed31_i_regimemat = ".pg_result($query11,$bb,"ed218_i_codigo")."
                                      AND ed221_c_origem = 'S'
                                      ORDER BY ed57_c_descr
                                     ";

                             $query5 = db_query($sql5);
                             $linhas5 = pg_num_rows($query5);
                             for($e=0;$e<$linhas5;$e++){

                              if ( pg_result($query5, $e, "n_procedimentos") > 0) {
                                continue;
                              }
                             ?>
                               ['Turma: <b><?=trim(pg_result($query5,$e,"ed57_c_descr"))?></b>', 'edu1_diarioclasse004.php?turma=<?=pg_result($query5,$e,"ed57_i_codigo")?>&ed57_c_descr=<?=trim(pg_result($query5,$e,"ed57_c_descr"))?>&codserieregencia=<?=pg_result($query4,$d,"ed11_i_codigo")?>&ed52_c_descr=<?=trim(pg_result($query,$a,"ed52_c_descr"))?>'],
                             <?}?>
                             ],
                           <?}?>
                            ],
                           <?}?>
                          ],
                        <?}?>
                       ],
                     <?}?>
                     ],
                    <?}?>
                   <?}?>
                   ]
                 ];


/*
 Feel free to use your custom icons for the tree. Make sure they are all of the same size.
 User icons collections are welcome, we'll publish them giving all regards.
*/

var tree_tpl = {
        'target'  : 'dados',        // name of the frame links will be opened in
                                                        // other possible values are: _blank, _parent, _search, _self and _top

        'icon_e'  : 'imagens/tree/empty.gif', // empty image
        'icon_l'  : 'imagens/tree/line.gif',  // vertical line

        'icon_48' : 'imagens/tree/empty.gif',   // root icon normal
        'icon_52' : 'imagens/tree/empty.gif',   // root icon selected
        'icon_56' : 'imagens/tree/empty.gif',   // root icon opened
        'icon_60' : 'imagens/tree/empty.gif',   // root icon selected

        'icon_16' : 'imagens/tree/folder.gif', // node icon normal
        'icon_20' : 'imagens/tree/folder.gif', // node icon selected
        'icon_24' : 'imagens/tree/folderopen.gif', // node icon opened
        'icon_28' : 'imagens/tree/folderopen.gif', // node icon selected opened

        'icon_0'  : 'imagens/tree/page.gif', // leaf icon normal
        'icon_4'  : 'imagens/tree/pagee.gif', // leaf icon selected
        'icon_8'  : 'imagens/tree/pagee.gif', // leaf icon opened
        'icon_12' : 'imagens/tree/pagee.gif', // leaf icon selected

        'icon_2'  : 'imagens/tree/joinbottom.gif', // junction for leaf
        'icon_3'  : 'imagens/tree/join.gif',       // junction for last leaf
        'icon_18' : 'imagens/tree/plusbottom.gif', // junction for closed node
        'icon_19' : 'imagens/tree/plus.gif',       // junction for last closed node
        'icon_26' : 'imagens/tree/minusbottom.gif',// junction for opened node
        'icon_27' : 'imagens/tree/minus.gif'       // junction for last opended node
};
</script>
<body bgcolor="#cccccc" leftmargin="5" marginheight="2" marginwidth="5" topmargin="2">
 <script>tree(TREE_ITEMS, tree_tpl);</script>
</body>
</html>