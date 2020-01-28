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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_rharqbanco_classe.php"));
db_postmemory($HTTP_POST_VARS);
$clrharqbanco = new cl_rharqbanco;
$clrotulo = new rotulocampo;
$clrharqbanco->rotulo->label();
$clrotulo->label('rh34_codarq');
$clrotulo->label('rh34_descr');
$clrotulo->label('db90_descr');

if(isset($emite2)){
  db_inicio_transacao();
  $sqlerro = false;
  $clrharqbanco->alterar($rh34_codarq);
  $rh34_sequencial += 1;
  db_fim_transacao($sqlerro);
}else if(isset($rh34_codarq)){
  $result = $clrharqbanco->sql_record($clrharqbanco->sql_query($rh34_codarq));
  if($clrharqbanco->numrows > 0){ 
    db_fieldsmemory($result,0);
    $rh34_sequencial += 1;
  }
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link type="text/css" rel="stylesheet" href="estilos.css">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/dates.js"></script>
    <script>
      function js_valores(){

        if(document.form1.rh34_codarq.value==""){
          alert("Campo Código do Arquivo é de preenchimento obrigatório.");
          document.form1.rh34_codarq.focus();
          return false;
        }else if(document.form1.datagera_dia.value == "" || document.form1.datagera_mes.value == "" || document.form1.datagera_ano.value == ""){
          alert("Campo Data da Geração é de preenchimento obrigatório.");
          document.form1.datagera_dia.select();
          return false;
        }else if(document.form1.datadeposit_dia.value == "" || document.form1.datadeposit_mes.value == "" || document.form1.datadeposit_ano.value == ""){
          alert("Campo Data do Depósito é de preenchimento obrigatório.");
          document.form1.datadeposit_dia.select();
          return false;
        }else if(js_diferenca_datas(getDateInDatabaseFormat($F('datagera')), getDateInDatabaseFormat($F('datadeposit')),3) == true){
          alert("Campo Data de Depósito deve ser igual ou maior que a Data de Geração.");
          return false;
        }else{
          return true;
        }
      }

      function js_emite(){
        js_controlarodape(true);
        qry  = 'rh34_codarq='+document.form1.rh34_codarq.value;
        qry += '&datadeposit='+document.form1.datadeposit_ano.value+'-'+document.form1.datadeposit_mes.value+'-'+document.form1.datadeposit_dia.value;
        qry += '&datagera='+document.form1.datagera_ano.value+'-'+document.form1.datagera_mes.value+'-'+document.form1.datagera_dia.value;
        qry += '&codban='+document.form1.rh34_codban.value;
        qry += '&tiparq='+document.form1.tiparq.value;
        qry += '&qfolha='+document.form1.qfolha.value;
        qry += '&opt_todosbcos='+document.form1.opt_todosbcos.value;
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_geraarqbanco','pes2_bb240cnab002.php?'+qry,'Gerando Arquivo',true);
      }
      
      function js_detectaarquivo(arquivo,pdf){
        js_controlarodape(false);
        CurrentWindow.corpo.db_iframe_geraarqbanco.hide();
        listagem = arquivo+"#Download arquivo TXT (pagamento eletrônico)|";
        listagem+= pdf+"#Download relatório";
        js_montarlista(listagem,"form1");
      }
      
      function js_erro(msg){
        js_controlarodape(false);
        CurrentWindow.corpo.db_iframe_geraarqbanco.hide();
        alert(msg);
      }
      function js_fechaiframe(){
        db_iframe_geraarqbanco.hide();
      }
      function js_controlarodape(mostra){
        if(mostra == true){
          document.form1.rodape.value = CurrentWindow.bstatus.document.getElementById('st').innerHTML;
          CurrentWindow.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;<blink><strong><font color="red">GERANDO ARQUIVO</font></strong></blink>' ;
        }else{
          CurrentWindow.bstatus.document.getElementById('st').innerHTML = document.form1.rodape.value;
        }
      }
    </script>  
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body>

    <form name="form1" method="post" action="" class="container">
      <fieldset style="width: 505px">

        <legend>Geração de Arquivo do Banco do Brasil</legend>

        <table align="center" border="0" class="form-container">
          <tr>
            <td><b>Data da Geração:</b></td>
            <td><?
              if((!isset($datagera_dia) || (isset($datagera_dia) && trim($datagera_dia) == "")) && (!isset($datagera_mes) || (isset($datagera_mes) && trim($datagera_mes) == "")) && (!isset($datagera_ano) || (isset($datagera_ano) && trim($datagera_ano) == ""))){
                $datagera_dia=date('d',db_getsession('DB_datausu'));
                $datagera_mes=date('m',db_getsession('DB_datausu'));
                $datagera_ano=date('Y',db_getsession('DB_datausu'));
              }
              db_inputdata('datagera',@$datagera_dia,@$datagera_mes,@$datagera_ano,true,'text',1,"");
              ?></td>
          </tr>
          <tr>
            <td><b>Data de Depósito:</b></td>
            <td>
              <?
              if((!isset($datadeposit_dia) || (isset($datadeposit_dia) && trim($datadeposit_dia) == "")) && (!isset($datadeposit_mes) || (isset($datadeposit_mes) && trim($datadeposit_mes) == "")) && (!isset($datadeposit_ano) || (isset($datadeposit_ano) && trim($datadeposit_ano) == ""))){
                $datadeposit_dia = "";
                $datadeposit_mes = "";
                $datadeposit_ano = "";
              }
              db_inputdata('datadeposit',@$datadeposit_dia,@$datadeposit_mes,@$datadeposit_ano,true,'text',1);
              ?>
            </td>
          </tr>
          <tr>
            <td><b>Banco:</b></td>
            <td>
              <?  
              (!isset($GLOBALS['opt_todosbcos'])) ? $GLOBALS['opt_todosbcos'] = '1' : '';
              $arr_tipobanco = Array("1"=>"Banco do Brasil", "0"=>"Todos");
              db_select("opt_todosbcos", $arr_tipobanco, true, 1, 'style="max-width: 125px;"');
              ?>
            </td>
          </tr>
          <tr> 
            <td title="<?=@$Trh34_codarq?>">
              <?php
                db_ancora(@$Lrh34_codarq,"js_pesquisa(true);",1);
              ?>
            </td>
            <td>
              <?php
                db_input("rh34_codarq",10,@$Irh34_codarq,true,"text",4,"onchange='js_pesquisa(false);'");
                db_input("rh34_descr",34,@$Irh34_descr,true,"text",3);
                db_input("rodape",40,0,true,"hidden",3);
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <fieldset id="fieldsetdadosbancarios">
                <legend >Dados Bancários</legend>
                  <table>
                    <tr>
                      <td  title="<?=@$Trh34_codban?>">
                        <?
                        db_ancora(@$Lrh34_codban,"return true;",3);
                        ?>
                      </td>
                      <td colspan="3" rel="ignore-css" style="padding-top:3px; white-space:nowrap"> 
                        <?
                        db_input('rh34_codban',6,$Irh34_codban,true,'text',3);
                        db_input('db90_descr',31,$Idb90_descr,true,'text',3);
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td  title="<?=@$Trh34_agencia?>">
                        <?=@$Lrh34_agencia?>
                      </td>
                      <td > 
                        <?
                        db_input('rh34_agencia',6,$Irh34_agencia,true,'text',3,"")
                        ?>
                      </td>
                      <td  title="<?=@$Trh34_dvagencia?>" align="left" rel="ignore-css">
                       <b> DV da Agência:</b>
                      </td>
                      <td> 
                        <?
                        db_input('rh34_dvagencia',2,$Irh34_dvagencia,true,'text',3,"style='width:100%' class='readonly'");
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td  title="<?=@$Trh34_conta?>"> 
                        <?=@$Lrh34_conta?>
                      </td>
                      <td> 
                        <?
                        db_input('rh34_conta',15,$Irh34_conta,true,'text',3,"")
                        ?>
                      </td>
                      <td  title="<?=@$Trh34_dvconta?>" align="left" rel="ignore-css">
                        <b>DV da Conta Corrente:</b>
                      </td>
                      <td> 
                        <?
                        db_input('rh34_dvconta',2,$Irh34_dvconta,true,'text',3,"style='width:100%' class='readonly'");
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td  title="<?=@$Trh34_convenio?>">
                        <?=@$Lrh34_convenio?>
                      </td> 
                      <td colspan="3" rel="ignore-css" style="padding-top:3px;"> 
                        <?
                        db_input('rh34_convenio',15,$Irh34_convenio,true,'text',3,"")
                        ?>
                      </td>
                  </tr> 
                </table>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td  title="<?=@$Trh34_sequencial?>">
              <?=@$Lrh34_sequencial?>
            </td>
            <td> 
              <?
              db_input('rh34_sequencial',15,$Irh34_sequencial,true,'text',1,"","","",'max-width: 125px;')
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <strong>Tipo de Arquivo:</strong>
            </td>
            <td>
              <?
              $arr_tiparq = Array(
        			                    "1"=>"1 - Pensão Judicial",
        			                    "0"=>"0 - Todos"
                                 );
              db_select("tiparq", $arr_tiparq, true, 1, "onchange='js_habilita(this.value);'");
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <strong>Tipo de Folha:</strong>
            </td>
            <td>
              <?php
              $arr_qfolha = Array(
                                  "1"=>"Salário",
        	                        "2"=>"Complementar",
                                  "3"=>"13º. Salário",
                                  "4"=>"Rescisão"
                                 );

              if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
                $arr_qfolha["5"] = "Suplementar"; 
              }
              
              db_select("qfolha", $arr_qfolha, true, 1);
              ?>
            </td>
      </table>
    </fieldset>
    <input name="emite2" id="emite2" type="submit" value="Processar" onclick="return js_valores();" >
  </form>
  <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>
<script>
function js_habilita(valor){
  if(valor == 1){
    document.form1.qfolha.disabled = false;
  }else{
    document.form1.qfolha.disabled = true;
  }
}
function js_pesquisa(mostra){
  if (mostra==true) {

    js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'db_iframe_rharqbanco',
        'func_rharqbanco.php?ativas=true&iCodigoBanco=001&funcao_js=parent.js_mostra1|rh34_codarq|rh34_descr',
        'Pesquisa de Arquivo Bancário',
        true);

  } else {
    if (document.form1.rh34_codarq.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rharqbanco','func_rharqbanco.php?ativas=true&pesquisa_chave='+document.form1.rh34_codarq.value+'&iCodigoBanco=001&funcao_js=parent.js_mostra','Pesquisa',false);
    } else {
      document.form1.rh34_codarq.value = '';
      document.form1.rh34_descr.value = '';
    }
  }
} 
function js_mostra(chave,erro){
  if (erro == true) {
    document.form1.rh34_descr.value = chave;
    document.form1.rh34_codarq.value = '';
    document.form1.rh34_codarq.focus();
  } else {
    document.form1.submit();
  }
}
function js_mostra1(chave1,chave2){
  document.form1.rh34_codarq.value = chave1;
  document.form1.submit();
  db_iframe_rharqbanco.hide();
}
function js_pesquisarh34_codban(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_bancos','func_db_bancos.php?funcao_js=parent.js_mostradb_bancos1|db90_codban|db90_descr','Pesquisa',true);
  }else{
    if(document.form1.rh34_codban.value != ''){
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_bancos','func_db_bancos.php?pesquisa_chave='+document.form1.rh34_codban.value+'&funcao_js=parent.js_mostradb_bancos','Pesquisa',false);
    }else{
      document.form1.db90_descr.value = '';
    }
  }
}
function js_mostradb_bancos(chave,erro){
  document.form1.db90_descr.value = chave;
  if(erro==true){
    document.form1.rh34_codban.focus();
    document.form1.rh34_codban.value = '';
  }
}
function js_mostradb_bancos1(chave1,chave2){
  document.form1.rh34_codban.value = chave1;
  document.form1.db90_descr.value = chave2;
  db_iframe_db_bancos.hide();
}
js_habilita(document.form1.tiparq.value);
</script>
<?
if(isset($emite2)){
  if($clrharqbanco->erro_status=="0"){
    $clrharqbanco->erro(true,false);
    $db_botao=true;
    if($clrharqbanco->erro_campo!=""){
      echo "<script> document.form1.".$clrharqbanco->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrharqbanco->erro_campo.".focus();</script>";
    };
  }else{
    echo "<script>js_emite();</script>";
  };
};
?>
