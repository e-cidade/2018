<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: Merenda
$clmer_restricaointolerancia->rotulo->label();
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo                 = new rotulocampo;
$clrotulo->label("me24_i_codigo");
$clrotulo->label("me33_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tme34_i_codigo?>">
       <?=@$Lme34_i_codigo?>
    </td>
    <td> 
    <?db_input('me34_i_codigo',10,$Ime34_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme34_i_restricao?>">
     <b>Aluno:</b>
    </td>
    <td> 
     <?db_input('me24_i_aluno',10,@$Ime24_i_aluno,true,'text',3,"")?>
     <?db_input('ed47_v_nome',40,@$Ied47_v_nome,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tme34_i_intolerancia?>">
     <?db_ancora(@$Lme34_i_intolerancia,"js_pesquisame34_i_intolerancia(true);",$db_opcao);?>
    </td>
    <td> 
     <?db_input('me34_i_intolerancia',10,$Ime34_i_intolerancia,true,'text',$db_opcao,
                " onchange='js_pesquisame34_i_intolerancia(false);'"
               )
     ?>
     <?db_input('me33_c_descr',40,$Ime33_i_codigo,true,'text',3,'')?>
     <?db_input('me34_i_restricao',40,@$Ime34_i_restricao,true,'hidden',3,'')?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
       type="submit" id="db_opcao"
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
        <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="button" id="cancela" value="Cancelar" 
       onclick="js_cancela();"  <?=($db_botao==false?"disabled":"")?> >
<br><br>
<?
$chavepri                           = array("me34_i_codigo" => @$me34_i_codigo);
$cliframe_alterar_excluir->chavepri = $chavepri;
if (isset($me34_i_restricao) && @$me34_i_restricao != "") {
  $cliframe_alterar_excluir->sql = $clmer_restricaointolerancia->sql_query(null,
                                                                           '*',
                                                                           null,
                                                                           " me34_i_restricao = $me34_i_restricao"
                                                                          );
}
$cliframe_alterar_excluir->campos        ="me33_i_codigo,me33_c_descr,ed47_v_nome";
$cliframe_alterar_excluir->legenda       ="Intolerância Alimentar";
$cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
$cliframe_alterar_excluir->textocabec    = "darkblue";
$cliframe_alterar_excluir->textocorpo    = "black";
$cliframe_alterar_excluir->fundocabec    = "#aacccc";
$cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
$cliframe_alterar_excluir->iframe_width  = "100%";
$cliframe_alterar_excluir->iframe_height = "200";
$cliframe_alterar_excluir->opcoes        = 1;
$cliframe_alterar_excluir->iframe_alterar_excluir(1);
?>
</form>
<script>
function js_pesquisame34_i_intolerancia(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('','db_iframe_mer_intoleranciaalimentar',
                        'func_mer_intoleranciaalimentar.php?funcao_js=parent.js_mostramer_intoleranciaalimentar1|'+
                         'me33_i_codigo|me33_c_descr',
                        'Pesquisa',true
                       );

  } else {

     if (document.form1.me34_i_intolerancia.value != '') { 

        js_OpenJanelaIframe('','db_iframe_mer_intoleranciaalimentar',
                            'func_mer_intoleranciaalimentar.php?pesquisa_chave='+document.form1.me34_i_intolerancia.value+
                            '&funcao_js=parent.js_mostramer_intoleranciaalimentar',
                            'Pesquisa',false
                           )
     } else {
       document.form1.me33_i_codigo.value = ''; 
     }
  }
}

function js_mostramer_intoleranciaalimentar(chave,erro) {

  document.form1.me33_c_descr.value = chave; 
  if (erro == true) { 

    document.form1.me34_i_intolerancia.focus(); 
    document.form1.me34_i_intolerancia.value = ''; 

  }

}

function js_mostramer_intoleranciaalimentar1(chave1,chave2) {

  document.form1.me34_i_intolerancia.value = chave1;
  document.form1.me33_c_descr.value        = chave2;
  db_iframe_mer_intoleranciaalimentar.hide();

}

function js_cancela() {
  location.href='mer1_mer_restricaointolerancia001.php?me34_i_restricao=<?=$me34_i_restricao?>'+
                 '&me24_i_aluno=<?=$me24_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>';
	}
</script>