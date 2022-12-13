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

//MODULO: protocolo
$clprotprocesso       = new cl_protprocesso;
$clprocessosapensados = new cl_processosapensados;
$clrotulo             = new rotulocampo;

$clprotprocesso->rotulo->label();
$clprocessosapensados->rotulo->label();

$clrotulo->label("p58_codproc");
$clrotulo->label("p30_procapensado");
$clrotulo->label("z01_nome");

include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
?>
</script>
<fieldset>
<legend><b>Processos Apensados</b></legend>
<center>
<table border="0" style="margin-top: 20px;">
  <tr align="center"><td colspan="2">
  <table>
    <tr>
        <td nowrap title="<?=@$Tp58_codproc?>" align="right">
           <b>Processo Principal:</b>
        </td>
        <td> 
          <?
            db_input('p58_codproc',12,$Ip58_codproc,true,'text',3,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tp30_procapensado?>" align="right">
          <?
            db_ancora('<b>Processo à Apensar:</b>',"js_pesquisap30_procapensado(true);","");
          ?>
        </td>
        <td> 
          <?
            db_input('p30_procapensado',12,$Ip30_procapensado,true,'text',$db_opcao," onchange='js_pesquisap30_procapensado(false);'");
            db_input('z01_nome',40,$Iz01_nome,true,'text',3,"");
          ?>
        </td>
      </tr>
      <tr>
        <td align="center" colspan="2">
          <input name="opcao" type="submit" id="opcao" value="Incluir" onclick="return js_valida();">
          <input name="imprimir" type="button" id="imprimir" value="Imprimir Capa" onclick="js_imprimir_capa();">
        </td>
      </tr>
      </table>
    </td>
  </tr>    
  <tr>
    <td align="top" colspan="2">
   <?   
    $p58_codproc = (isset($p58_codproc)&&!empty($p58_codproc))?$p58_codproc:'null';
    $sSqlProcessoApensados = " select p30_procprincipal,
                                      p30_procapensado,
                                      p58_requer
                                 from processosapensados 
                                      inner join protprocesso on p30_procapensado = p58_codproc
                                where p30_procprincipal = {$p58_codproc}
                                order by p30_procprincipal ";
    //die($sSqlProcessoApensados);
    $chavepri= array("p30_procapensado"=>@$p58_codproc);
    $cliframe_alterar_excluir->chavepri      = $chavepri;
    $cliframe_alterar_excluir->campos        = "p30_procprincipal,p30_procapensado,p58_requer";
    $cliframe_alterar_excluir->sql           = $sSqlProcessoApensados;
    $cliframe_alterar_excluir->legenda       = "Processos já Apensados";
    $cliframe_alterar_excluir->msg_vazio     = "<font size='1'>Nenhum Processo Apensado Cadastrado!</font>";
    $cliframe_alterar_excluir->textocabec    = "darkblue";
    $cliframe_alterar_excluir->textocorpo    = "black";
    $cliframe_alterar_excluir->fundocabec    = "#aacccc";
    $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
    $cliframe_alterar_excluir->iframe_height = "170";
    $cliframe_alterar_excluir->opcoes        = 3;
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
   ?>
   </td>
 </tr>  
  </table>
  </center>
</form>
<script>
function js_valida(){
  var p58_codproc      = document.form1.p58_codproc.value;
  var p30_procapensado = document.form1.p30_procapensado.value;
  var z01_nome         = document.form1.z01_nome.value;
  
  if (p58_codproc == "" || p30_procapensado == "" || z01_nome == "") {
     alert("Preeencha todos os campos!");
     return false;
  }
}
function js_pesquisap30_procapensado(mostra){
  var p58_codproc      = document.form1.p58_codproc.value;
  var p30_procapensado = document.form1.p30_procapensado.value;
    
  if(mostra == true){
    var sUrl = 'func_protprocesso.php?grupo=1&apensado='+p58_codproc+'&funcao_js=parent.js_mostratipoproc1|0|2';
    db_iframe.jan.location.href = sUrl;
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  } else {
    var sUrl = 'func_protprocesso.php?grupo=1&apensado='+p58_codproc+'&pesquisa_chave='+p30_procapensado+
                                            '&funcao_js=parent.js_mostratipoproc';
    db_iframe.jan.location.href = sUrl;
  }
}
function js_mostratipoproc(chave1,chave2,erro){
  document.form1.p30_procapensado.value = chave1;
  document.form1.z01_nome.value   = chave2; 
  if(erro == true){ 
    document.form1.p30_procapensado.focus(); 
    document.form1.p30_procapensado.value = ''; 
  }
}
function js_mostratipoproc1(chave1,chave2){
  document.form1.p30_procapensado.value = chave1;
  document.form1.z01_nome.value         = chave2;
  document.form1.submit();
  db_iframe.hide();
}
function js_imprimir_capa(){
  var p58_codproc = document.form1.p58_codproc.value;
  window.open('pro4_capaprocesso.php?codproc='+p58_codproc+'','','location=0');
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX    = 0;
$func_iframe->posY    = 2;
$func_iframe->largura = 780;
$func_iframe->altura  = 430;
$func_iframe->titulo  = 'Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>
