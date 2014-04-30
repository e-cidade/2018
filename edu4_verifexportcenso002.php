<?
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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$db_opcao = 1;
$escola = db_getsession("DB_coddepto");
function SerieTurma($turma,$tipo){
 if($tipo!=4 && $tipo!=5){
 $sql = "SELECT fc_nomeetapaturma(ed57_i_codigo) as ed11_c_descr
          FROM turma
          WHERE ed57_i_codigo = $turma
         ";
  $result = pg_query($sql);
  return trim(pg_result($result,0,0));
 }else{
  if($tipo==4){
   return "Atividade Complementar";
  }
  if($tipo==5){
   return "AEE";
  }
 }
}
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

function item_init () {
        var a_offset = [],
                o_current_item = this.o_parent;
        for (var i = this.n_depth; i > 1; i--) {
                a_offset[i] = '<img src="' + this.o_root.a_tpl[o_current_item.is_last() ? 'icon_e' : 'icon_l'] + '" border="0" align="absbottom">';
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
                   <?
                   $ponteiro = fopen($arquivogerado,"r");
                   $soma_turman = 0;
                   $soma_turmaac = 0;
                   $soma_turmaaee = 0;
                   $soma_docente = 0;
                   $soma_aluno = 0;
                   while (!feof($ponteiro)){
                    $linha = " ".fgets($ponteiro);
                    $explode_linha = explode("|",$linha);
                    if($explode_linha[0]=="00"){
                     $nomeescola = trim($linha[5]);
                    }
                    if($explode_linha[0]=="20" && trim($explode_linha[9])<4){
                     $soma_turman++;
                    }
                    if($explode_linha[0]=="20" && trim($explode_linha[9])==4){
                     $soma_turmaac++;
                    }
                    if($explode_linha[0]=="20" && trim($explode_linha[9])==5){
                     $soma_turmaaee++;
                    }
                    if($explode_linha[0]=="30"){
                     $soma_docente++;
                    }
                    if($explode_linha[0]=="60"){
                     $soma_aluno++;
                    }
                   }
                   fclose($ponteiro);
                   ?>
                   ['Arquivo: <b>Arquivo</b>', 'edu4_verifexportcenso003.php?registro=null',
                     ['Escola: <b>Escola</b>', 'edu4_verifexportcenso003.php?registro=null',
                       ['Registro 00: <b>Registro 00 - Identificação</b>', 'edu4_verifexportcenso003.php?registro=00&arquivogerado=<?=$arquivogerado?>'],
                       ['Registro 10: <b>Registro 10 - Autenticação / Caracterização</b>', 'edu4_verifexportcenso003.php?registro=10&arquivogerado=<?=$arquivogerado?>'],
                     ],
                     ['Turmas: <b>Turmas (<?=$soma_turman+$soma_turmaac+$soma_turmaaee?>)</b>', 'edu4_verifexportcenso003.php?registro=null',
                       ['Turmas Regular / Especial / EJA: <b>Turmas Regular / Especial / EJA (<?=$soma_turman?>)</b>', 'edu4_verifexportcenso003.php?registro=null',
                        <?
                        $ponteiro = fopen($arquivogerado,"r");
                        while (!feof($ponteiro)){
                         $linha = " ".fgets($ponteiro);
                         $explode_linha = explode("|",$linha);
                         if($explode_linha[0]=="20" && trim($explode_linha[9])<4){                          
                          $codigoturma = trim($explode_linha[3]);
                          $nometurma = trim($explode_linha[4]);
                          $tipoatendimento = trim($explode_linha[9]);
                          $serieturma = SerieTurma($codigoturma,$tipoatendimento);
                          $qtd_vinculo = 0;
                          $qtd_docente = 0;
                          $ponteiro3 = fopen($arquivogerado,"r");
                          while (!feof($ponteiro3)){
                           $linha = " ".fgets($ponteiro3);
                           $explode_linha = explode("|",$linha);
                           if($explode_linha[0]=="80" && trim($explode_linha[3])==$codigoturma){
                            $qtd_vinculo++;
                           }
                           if($explode_linha[0]=="51" && trim($explode_linha[3])==$codigoturma){
                            $qtd_docente++;
                           }
                          }
                          fclose($ponteiro3);
                          ?>
                          ['Turma / Etapa: <b><?=$nometurma?> / <?=$serieturma?></b>', 'edu4_verifexportcenso003.php?registro=null',
                            ['Registro 20: <b>Registro 20 - Cadastro de Turma</b>', 'edu4_verifexportcenso003.php?registro=20&codigoturma=<?=$codigoturma?>&arquivogerado=<?=$arquivogerado?>'],
                            ['Registro 51: <b>Registro 51 - Vínculo Turma / Docentes (<?=$qtd_docente?>)</b>', 'edu4_verifexportcenso003.php?registro=21&codigoturma=<?=$codigoturma?>&arquivogerado=<?=$arquivogerado?>'],
                            ['Registro 80: <b>Registro 80 - Vínculo Turma / Alunos (<?=$qtd_vinculo?>)</b>', 'edu4_verifexportcenso003.php?registro=81&codigoturma=<?=$codigoturma?>&arquivogerado=<?=$arquivogerado?>'],
                          ],
                          <?
                         }
                        }
                        fclose($ponteiro);
                        ?>
                       ],
                       <?if($soma_turmaac>0){?>
                       ['Turmas Atividade Complementar: <b>Turmas Atividade Complementar (<?=$soma_turmaac?>)</b>', 'edu4_verifexportcenso003.php?registro=null',
                        <?
                        $ponteiro = fopen($arquivogerado,"r");
                        while (!feof($ponteiro)){
                         $linha = " ".fgets($ponteiro);
                         $explode_linha = explode("|",$linha);
                         if($linha[0]=="20" && trim($explode_linha[9])==4){
                          $codigoturma = trim($explode_linha[3]);
                          $nometurma = trim($explode_linha[4]);
                          $tipoatendimento = trim($explode_linha[9]);
                          $serieturma = SerieTurma($codigoturma,$tipoatendimento);
                          $qtd_vinculo = 0;
                          $qtd_docente = 0;
                          $ponteiro3 = fopen($arquivogerado,"r");
                          while (!feof($ponteiro3)){
                           $linha = " ".fgets($ponteiro3);
                           $explode_linha = explode("|",$linha);
                           if($explode_linha[0]=="80" && trim($explode_linha[3])==$codigoturma){
                            $qtd_vinculo++;
                           }   
                          if($explode_linha[0]=="51" && trim($explode_linha[3])==$codigoturma){
                            $qtd_docente++;
                           }                        
                          }
                          fclose($ponteiro3);
                          ?>
                          ['Turma / Etapa: <b><?=$nometurma?> / <?=$serieturma?></b>', 'edu4_verifexportcenso003.php?registro=null',
                            ['Registro 20: <b>Registro 20 - Cadastro de Turma</b>', 'edu4_verifexportcenso003.php?registro=20&codturma=<?=$codigoturma?>&arquivogerado=<?=$arquivogerado?>'],
                            ['Registro 51: <b>Registro 51 - Vínculo Turma / Docentes (<?=$qtd_docente?>)</b>', 'edu4_verifexportcenso003.php?registro=21&codturma=<?=$codigoturma?>&arquivogerado=<?=$arquivogerado?>'],
                            ['Registro 80: <b>Registro 80 - Vínculo Turma / Alunos (<?=$qtd_vinculo?>)</b>', 'edu4_verifexportcenso003.php?registro=81&codturma=<?=$codigoturma?>&arquivogerado=<?=$arquivogerado?>'],
                          ],
                          <?
                         }
                        }
                        fclose($ponteiro);
                        ?>
                       ],
                       <?}?>
                       <?if($soma_turmaaee>0){?>
                       ['Turmas AEE: <b>Turmas AEE (<?=$soma_turmaaee?>)</b>', 'edu4_verifexportcenso003.php?registro=null',
                        <?
                        $ponteiro = fopen($arquivogerado,"r");
                        while (!feof($ponteiro)){
                         $linha = " ".fgets($ponteiro);
                         $explode_linha = explode("|",$linha);
                         if($explode_linha[0]=="20" && trim($explode_linha[9])==5){
                          $codigoturma = trim($explode_linha[3]);
                          $nometurma = trim($explode_linha[4]);
                          $tipoatendimento = trim($explode_linha[9]);
                          $serieturma = SerieTurma($codigoturma,$tipoatendimento);
                          $qtd_vinculo = 0;
                          $qtd_docente = 0;
                          $ponteiro3 = fopen($arquivogerado,"r");
                          while (!feof($ponteiro3)){
                           $linha = " ".fgets($ponteiro3);
                           $explode_linha = explode("|",$linha);
                           if($explode_linha[0]=="80" && trim($explode_linha[3])==$codigoturma){
                            $qtd_vinculo++;
                           }                           
                          }
                          fclose($ponteiro3);
                          ?>
                          ['Turma / Etapa: <b><?=$nometurma?> / <?=$serieturma?></b>', 'edu4_verifexportcenso003.php?registro=null',
                            ['Registro 20: <b>Registro 20 - Cadastro de Turma</b>', 'edu4_verifexportcenso003.php?registro=20&codigoturma=<?=$codigoturma?>&arquivogerado=<?=$arquivogerado?>'],
                            ['Registro 51: <b>Registro 51 - Vínculo Turma / Docentes (<?=$qtd_docente?>)</b>', 'edu4_verifexportcenso003.php?registro=21&codigoturma=<?=$codigoturma?>&nometurma=<?=$nometurma?>&arquivogerado=<?=$arquivogerado?>'],
                            ['Registro 80: <b>Registro 80 - Vínculo Turma / Alunos (<?=$qtd_vinculo?>)</b>', 'edu4_verifexportcenso003.php?registro=81&codigoturma=<?=$codigoturma?>&arquivogerado=<?=$arquivogerado?>'],
                          ],
                          <?
                         }
                        }
                        fclose($ponteiro);
                        ?>
                       ],
                       <?}?>
                     ],
                     ['Docentes: <b>Docentes (<?=$soma_docente?>)</b>', 'edu4_verifexportcenso003.php?registro=null',
                       <?
                       $ponteiro = fopen($arquivogerado,"r");
                       while (!feof($ponteiro)){
                        $linha = " ".fgets($ponteiro);
                        $explode_linha = explode("|",$linha);
                        if($explode_linha[0]=="30"){
                         $codigodocente = trim($explode_linha[3]);
                         $nomedocente = trim($explode_linha[4]);
                         $nometurma = trim($explode_linha[5]);
                         $qtd_docente = 0;
                         $ponteiro3 = fopen($arquivogerado,"r");
                         while (!feof($ponteiro3)){
                          $linha = " ".fgets($ponteiro3);
                          $explode_linha = explode("|",$linha);
                          if($explode_linha[0]=="51" && trim($explode_linha[3])==$codigodocente){
                           $qtd_docente++;
                          }
                         }
                         fclose($ponteiro3);
                         ?>
                         ['Docente: <b><?=$codigodocente?> - <?=$nomedocente?></b>', 'edu4_verifexportcenso003.php?registro=null',
                           ['Registro 30: <b>Registro 30 - Dados do Docente</b>', 'edu4_verifexportcenso003.php?registro=30&codigodocente=<?=$codigodocente?>&arquivogerado=<?=$arquivogerado?>'],
                           ['Registro 40: <b>Registro 40 - Documentos do Docente</b>', 'edu4_verifexportcenso003.php?registro=40&codigodocente=<?=$codigodocente?>&nomedocente=<?=$nomedocente?>&arquivogerado=<?=$arquivogerado?>'],
                           ['Registro 50: <b>Registro 50 - Dados Variáveis do Docente</b>', 'edu4_verifexportcenso003.php?registro=50&codigodocente=<?=$codigodocente?>&nomedocente=<?=$nomedocente?>&arquivogerado=<?=$arquivogerado?>'],
                           ['Registro 51: <b>Registro 51 - Vínculo Docente / Turmas (<?=$qtd_docente?>)</b>', 'edu4_verifexportcenso003.php?registro=51&codigodocente=<?=$codigodocente?>&nomedocente=<?=$nomedocente?>&nometurma=<?=$nometurma?>&arquivogerado=<?=$arquivogerado?>'],
                         ],
                         <?
                        }
                       }
                       fclose($ponteiro);
                       ?>
                     ],
                     ['Alunos: <b>Alunos (<?=$soma_aluno?>)</b>', 'edu4_verifexportcenso003.php?registro=null',
                       <?
                       $ponteiro = fopen($arquivogerado,"r");
                       while (!feof($ponteiro)){
                        $linha = " ".fgets($ponteiro);
                        $explode_linha = explode("|",$linha);
                        if($explode_linha[0]=="60"){                         
                         $codigoaluno = trim($explode_linha[3]);
                         $nomealuno = trim($explode_linha[4]);
                         $qtd_vinculo = 0;
                         $ponteiro3 = fopen($arquivogerado,"r");
                         while (!feof($ponteiro3)){
                          $linha = " ".fgets($ponteiro3);
                          $explode_linha = explode("|",$linha);
                          if($explode_linha[0]=="80" && trim($explode_linha[3])==$codigoaluno){
                           $qtd_vinculo++;
                          }
                         }
                         fclose($ponteiro3);
                         ?>
                         ['Aluno: <b><?=$codigoaluno?> - <?=$nomealuno?></b>', 'edu4_verifexportcenso003.php?registro=null',
                           ['Registro 60: <b>Registro 60 - Dados do Aluno</b>', 'edu4_verifexportcenso003.php?registro=60&codigoaluno=<?=$codigoaluno?>&nomealuno=<?=$nomealuno?>&arquivogerado=<?=$arquivogerado?>'],
                           ['Registro 70: <b>Registro 70 - Documentos do Aluno</b>', 'edu4_verifexportcenso003.php?registro=70&codigoaluno=<?=$codigoaluno?>&nomealuno=<?=$nomealuno?>&arquivogerado=<?=$arquivogerado?>'],
                           ['Registro 80: <b>Registro 80 - Vínculo Aluno / Turmas (<?=$qtd_vinculo?>)</b>', 'edu4_verifexportcenso003.php?registro=80&codigoaluno=<?=$codigoaluno?>&nomealuno=<?=$nomealuno?>&arquivogerado=<?=$arquivogerado?>'],
                         ],
                         <?
                        }
                       }
                       fclose($ponteiro);
                       ?>
                     ],
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