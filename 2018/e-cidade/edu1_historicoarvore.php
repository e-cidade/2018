<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

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
include("classes/db_historico_classe.php");
include("classes/db_historicomps_classe.php");
include("classes/db_histmpsdisc_classe.php");
include("classes/db_histmpsdiscfora_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($_POST);
$clhistorico       = new cl_historico;
$clhistoricomps    = new cl_historicomps;
$clhistmpsdisc     = new cl_histmpsdisc;
$clhistmpsdiscfora = new cl_histmpsdiscfora;
$db_opcao          = 1;
$db_botao          = true;
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

  var o_icone            = new Image(),
      o_iconl            = new Image();
      o_icone.src        = a_template['icon_e'];
      o_iconl.src        = a_template['icon_l'];
      a_template['im_e'] = o_icone;
      a_template['im_l'] = o_iconl;

  for ( var i = 0; i < 64; i++ )
    if ( a_template['icon_' + i] ) {

      var o_icon            = new Image();
      a_template['im_' + i] = o_icon;
      o_icon.src            = a_template['icon_' + i];
    }

  this.toggle = function (n_id) {        var o_item = this.a_index[n_id]; o_item.open(o_item.b_opened) };
  this.select = function (n_id) { return this.a_index[n_id].select(); };
  this.mout   = function (n_id) { this.a_index[n_id].upstatus(true) };
  this.mover  = function (n_id) { this.a_index[n_id].upstatus() };

  this.a_children = [];

  for ( var i = 0; i < a_items.length; i++ )
    new tree_item(this, i);

  this.n_id        = trees.length;
  trees[this.n_id] = this;

  for ( var i = 0; i < this.a_children.length; i++ ) {

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

  this.n_id                      = this.o_root.a_index.length;
  n_id                           = this.o_root.a_index.length;
  this.o_root.a_index[this.n_id] = this;
  o_parent.a_children[n_order]   = this;

  this.a_children = [];

  for ( var i = 0; i < this.a_config.length - 2; i++ )
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

    var o_olditem          = this.o_root.o_selected;
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

function item_init () {

  var a_offset       = [],
      o_current_item = this.o_parent;

  for ( var i = this.n_depth; i > 1; i-- ) {

    a_offset[i]    = '<img src="' + this.o_root.a_tpl[o_current_item.is_last() ? 'icon_e' : 'icon_l'] + '" border="0" align="absbottom">';
    o_current_item = o_current_item.o_parent;
  }

  texto = this.a_config[0];
  title = texto.split(":");

  return '<table cellpadding="0" cellspacing="0" border="0"><tr><td nowrap>' + (this.n_depth ? a_offset.join('') + (this.a_children.length
          ? '<a style="text-decoration:none;color:#FF0000;" href="javascript: trees[' + this.o_root.n_id + '].toggle(' + this.n_id + ')" onmouseover="trees[' + this.o_root.n_id + '].mover(' + this.n_id + ')" onmouseout="trees[' + this.o_root.n_id + '].mout(' + this.n_id + ')"><img src="' + this.get_icon(true) + '" border="0" align="absbottom" name="j_img' + this.o_root.n_id + '_' + this.n_id + '"></a>'
          : '<img src="' + this.get_icon(true) + '" border="0" align="absbottom">') : '')
          + '<a style="text-decoration:none;color:#000000" href="' + this.a_config[1] + '" target="' + this.o_root.a_tpl['target'] + '" onclick="return trees[' + this.o_root.n_id + '].select(' + this.n_id + ');" ondblclick="trees[' + this.o_root.n_id + '].toggle(' + this.n_id + ')" onmouseover="trees[' + this.o_root.n_id + '].mover(' + this.n_id + ');" onmouseout="trees[' + this.o_root.n_id + '].mout(' + this.n_id + ')" class="t' + this.o_root.n_id + 'i" id="i_txt' + this.o_root.n_id + '_' + this.n_id + '"><img src="' + this.get_icon() + '" border="0" align="absbottom" name="i_img' + this.o_root.n_id + '_' + this.n_id + '" class="t' + this.o_root.n_id + 'im">&nbsp;<span id="cor_div' + this.o_root.n_id + '_' + this.n_id + '" title="'+title[0]+'">' + title[1] + '</div></a></td></tr></table>' + (this.a_children.length ? '<div id="i_div' + this.o_root.n_id + '_' + this.n_id + '" style="display:none"></div>' : '');
}

function item_get_icon (b_junction) {
  return this.o_root.a_tpl['icon_' + ((this.n_depth ? 0 : 32) + (this.a_children.length ? 16 : 0) + (this.a_children.length && this.b_opened ? 8 : 0) + (!b_junction && this.o_root.o_selected == this ? 4 : 0) + (b_junction ? 2 : 0) + (b_junction && this.is_last() ? 1 : 0))];
}

var trees = [];

get_element = document.all ?
 function (s_id) { return document.all[s_id] } :
 function (s_id) { return document.getElementById(s_id) };

var TREE_ITEMS = [
                   ['Aluno: <b><?=$ed47_v_nome?></b>:<?=$ed61_i_aluno?>', '',
                   <?
                    if ( isset($ed61_i_aluno) && !empty($ed61_i_aluno) ) {
                      $query = $clhistorico->sql_record($clhistorico->sql_query("","*","ed29_i_codigo"," ed61_i_aluno = $ed61_i_aluno"));
                    }else{
                      $clhistorico->numrows = 0;
                    }
                    if($clhistorico->numrows == 0) {
                   ?>
                    ['Curso: <b>Nenhum curso no histórico</b>:', 'edu1_historico001.php?ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>'],
                   <?
                    }else{
                    for($a=0;$a<$clhistorico->numrows;$a++){
                     db_fieldsmemory($query,$a);
                     ?>
                     ['Curso: <b><?=$ed29_c_descr?></b>:C<?=$ed29_i_codigo?>', 'edu1_historico002.php?chavepesquisa=<?=$ed61_i_codigo?>',
                     <?
                     $sql1 = "SELECT ed62_i_codigo,ed11_c_descr,ed11_i_codigo,ed62_i_anoref,ed62_i_periodoref,ed11_i_sequencia,'REDE' as tipo
                              FROM historicomps
                               inner join serie on ed11_i_codigo = ed62_i_serie
                              WHERE ed62_i_historico = $ed61_i_codigo
                              UNION
                              SELECT ed99_i_codigo,ed11_c_descr,ed11_i_codigo,ed99_i_anoref,ed99_i_periodoref,ed11_i_sequencia,'FORA' as tipo
                              FROM historicompsfora
                               inner join serie on ed11_i_codigo = ed99_i_serie
                              WHERE ed99_i_historico = $ed61_i_codigo
                              ORDER BY ed11_i_sequencia asc,ed62_i_anoref asc
                             ";

                     $query1 = db_query($sql1);
                     $linhas1 = pg_num_rows($query1);
                     //$query1 = $clhistoricomps->sql_record($clhistoricomps->sql_query("","*","serie.ed11_i_sequencia,ed62_i_anoref,ed62_i_periodoref"," ed62_i_historico = $ed61_i_codigo"));
                     if($linhas1==0){?>
                      ['Etapa: <b>Nenhuma etapa para este curso</b>:', 'edu1_historicomps001.php?ed62_i_historico=<?=$ed61_i_codigo?>&ed29_c_descr=<?=$ed29_c_descr?>&ed29_i_codigo=<?=$ed29_i_codigo?>&ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>'],
                     <?}else{
                      for($b=0;$b<$linhas1;$b++){
                       db_fieldsmemory($query1,$b);
                       if($tipo=="REDE"){
                        ?>
                        ['Etapa: <b><?=$ed11_c_descr?></b>:S<?=$ed11_i_codigo?>', 'edu1_historicomps002.php?chavepesquisa=<?=$ed62_i_codigo?>',
                        <?
                       }else{
                        ?>
                        ['Etapa: <b><?=$ed11_c_descr?></b>:S<?=$ed11_i_codigo?>', 'edu1_historicompsfora002.php?chavepesquisa=<?=$ed62_i_codigo?>',
                        <?
                       }
                       if($tipo=="REDE"){

                          $query2 = $clhistmpsdisc->sql_record($clhistmpsdisc->sql_query("","*","ed65_i_ordenacao","ed65_i_historicomps = $ed62_i_codigo"));

                          if ($clhistmpsdisc->numrows == 0) {
                       ?>
                          ['Disciplina: <b>Nenhum disciplina para esta Etapa</b>:', 'edu1_histmpsdisc001.php?ed65_i_historicomps=<?=$ed62_i_codigo?>'],
                       <? } else {
                            for ( $c = 0; $c < $clhistmpsdisc->numrows; $c++) {

                              db_fieldsmemory($query2,$c);
                        ?>
                              ['Disciplina: <b><?=$ed232_c_descr?></b>:D<?=$ed12_i_codigo?>', 'edu1_histmpsdisc002.php?ed65_i_historicomps=<?=$ed62_i_codigo?>'],
                        <?  }
                         }
                       }else{
                        $query2 = $clhistmpsdiscfora->sql_record($clhistmpsdiscfora->sql_query("","*","ed100_i_ordenacao"," ed100_i_historicompsfora  = $ed62_i_codigo"));
                        if($clhistmpsdiscfora->numrows==0){?>
                         ['Disciplina: <b>Nenhum disciplina para esta Etapa</b>:', 'edu1_histmpsdiscfora001.php?ed100_i_historicompsfora=<?=$ed62_i_codigo?>'],
                        <?}else{
                         for($c=0;$c<$clhistmpsdiscfora->numrows;$c++){
                          db_fieldsmemory($query2,$c);
                          ?>
                          ['Disciplina: <b><?=$ed232_c_descr?></b>:D<?=$ed12_i_codigo?>', 'edu1_histmpsdiscfora002.php?ed100_i_historicompsfora=<?=$ed62_i_codigo?>'],
                         <?}
                        }
                       }?>
                       ],
                      <?}?>
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?if(isset($ed61_i_aluno) && $ed61_i_aluno!=""){?>
 <script>tree(TREE_ITEMS, tree_tpl);</script>
 <?}else{
 	echo "&nbsp;&nbsp;<b>Escolha um aluno</b>"; 	
 }?>
</body>
</html>