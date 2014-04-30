<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt24');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt26');
$clrotulo->label('DBtxt35');
$clrotulo->label('DBtxt36');

$DBtxt23 = db_anofolha();
$DBtxt24 = db_anofolha();
$DBtxt25 = db_mesfolha();
$DBtxt26 = db_mesfolha();
$DBtxt35 = db_mesfolha();
$DBtxt36 = db_anofolha();
$mes = db_mesfolha();
$ano = db_anofolha();

?>
<form name='form1' enctype="multipart/form-data" method='post'>
  <center>
    <fieldset style="margin: 40px auto 10px; width: 800px">
      <legend>
        <strong>Extrato à Previdência</strong>
      </legend>
      <table>
        <tr>
          <td style="padding-left: 80px;">
            <table align="center" width="450px">
              <tr>
                <td title=" Digite o Ano / Mês" width="150px"><strong>Ano / Mês - Folha: </strong>
                </td>

                <td><?                      
                db_input('DBtxt36',4,$IDBtxt36,true,'text',1,'');
                db_input('ano',4,$ano,true,'hidden',1,'');
                ?> &nbsp;/&nbsp; <?
                db_input('DBtxt35',2,$IDBtxt35,true,'text',1,'');
                db_input('mes',2,$mes,true,'hidden',1,'');
                ?>
                </td>

              </tr>

              <tr>
                <td title=" Digite o Ano / Mês"><strong>Ano / Mês Inicial:</strong>
                </td>

                <td align='left' nowrap><?                      
                db_input('DBtxt23',4,$IDBtxt23,true,'text',1,'');
                ?> &nbsp;/&nbsp; <?
                db_input('DBtxt25',2,$IDBtxt25,true,'text',1,'');
                ?>
                </td>
              </tr>

              <tr>
                <td title=" Digite o Ano / Mês"><strong>Ano / Mês Final:</strong>
                </td>

                <td align='left' nowrap><?                                                                                                               
                db_input('DBtxt24',4,$IDBtxt24,true,'text',1,'');
                ?> &nbsp;/&nbsp; <?
                db_input('DBtxt26',2,$IDBtxt26,true,'text',1,'');
                ?>
                </td>
              </tr>
              <tr>
                <td title="Dados Cadastrais"><b>Dados Cadastrais:</b></td>
                <td><?
                $xcad = array("p"=>"Período", "a"=>"Atual");
                db_select('sTipoEmissao',$xcad,true,4,"");
                ?>
                </td>
              </tr>

              <tr>
                <td title="Selecionar Resumo"><strong>Tipo Resumo:&nbsp;</strong>
                </td>

                <td><? 
                $ordem_resumo = array("g"=>"Geral",
                		"m"=>"Matrícula",
                		"l"=>"Lotação",
                		"t"=>"Locais de Trabalho");

                db_select('tipo_res', $ordem_resumo,true,2,"onchange=js_mudaresumo(this.value)");
                ?>
                </td>
              </tr>

              <tr id='tipoFiltro'>
                <td title="Selecionar Filtro"><strong>Tipo Filtro:&nbsp;</strong>
                </td>

                <td><? 
                $ordem_filtro = array("0"=>" ----------- ",
                		"i"=>"Intervalo",
                		"s"=>"Selecionados");

                db_select('tipo_fil', $ordem_filtro,true,2,"onchange='js_mostratag(this.value, document.form1.tipo_res);'");
                ?>
                </td>
              </tr>


              <tr id='tipoIntervaloMatric'>
                <td align='right' nowrap><?
                db_ancora('<strong>Matrícula</strong>',"js_pesquisalink(true, 'mati')",1);
                ?>
                </td>
                <td align='left' nowrap><?                                                                                                               
                db_input('mati',10,4,true,'text',1,'');
                db_ancora("<strong>a</strong>","js_pesquisalink(true,'matf')",1);
                db_input('matf',10,4,true,'text',1,'');
                ?>
                </td>
              </tr>

              <tr id='tipoIntervaloLotacao'>
                <td align='right' nowrap><?
                db_ancora('<strong>Lotação</strong>',"js_pesquisalink(true,'lotai')",1);
                ?>
                </td>
                <td align='left' nowrap><?                                                                                                               
                db_input('lotai',10,4,true,'text',1,'');
                db_ancora("<strong>a</strong>","js_pesquisalink(true,'lotaf')",1);
                db_input('lotaf',10,4,true,'text',1,'');
                ?>
                </td>
              </tr>

              <tr id='tipoIntervaloLocal'>
                <td align='right' nowrap><?
                db_ancora('<strong>Local</strong>',"js_pesquisalink(true, 'locai')",1);
                ?>
                </td>
                <td align='left' nowrap><?                                                                                                               
                db_input('locai',10,4,true,'text',1,'');
                db_ancora("<strong>a</strong>","js_pesquisalink(true,'locaf')",1);
                db_input('locaf',10,4,true,'text',1,'');
                ?>
                </td>
              </tr>
            </table>

            <table id='tipoSelecionaMatric' width="450px">
              <tr>
                <td nowrap width="50%"><?
                $aux->cabecalho = "<strong>Matrículas</strong>";
                $aux->codigo = "rh01_regist"; //chave de retorno da func
                $aux->descr  = "z01_nome";   //chave de retorno
                $aux->nomeobjeto = 'tipoSelMatric';
                $aux->funcao_js = 'js_mostraselteste1';
                $aux->funcao_js_hide = 'js_mostrateste2';
                $aux->sql_exec  = "";
                $aux->func_arquivo = "func_rhpessoal.php";  //func a executar
                $aux->nomeiframe = "db_iframe_rhpessoal";
                $aux->localjan = "";
                $aux->nome_botao = "lanca_Matric";
                $aux->onclick = "";
                $aux->db_opcao = 2;
                $aux->tipo = 2;
                $aux->top = '';
                $aux->linhas = 4;
                $aux->vwhidth = 400;
                $aux->funcao_gera_formulario();
                ?>
                </td>
              </tr>
            </table>

            <table id='tipoSelecionaLotacao' width="450px">
              <tr>
                <td nowrap width="50%"><?
                $aux->cabecalho = "<strong>Lotações</strong>";
                $aux->codigo = "r70_codigo"; //chave de retorno da func
                $aux->descr  = "r70_descr";   //chave de retorno
                $aux->nomeobjeto = 'tipoSelLota';
                $aux->funcao_js = 'js_mostraselteste3';
                $aux->funcao_js_hide = 'js_mostrateste4';
                $aux->sql_exec  = "";
                $aux->func_arquivo = "func_rhlotaestrut.php";  //func a executar
                $aux->nomeiframe = "db_iframe_rhlotaestrut";
                $aux->localjan = "";
                $aux->nome_botao = "lanca_Lota";
                $aux->onclick = "";
                $aux->db_opcao = 2;
                $aux->tipo = 2;
                $aux->top = '';
                $aux->linhas = 4;
                $aux->vwhidth = 400;
                $aux->funcao_gera_formulario();
                ?>
                </td>
              </tr>
            </table>

            <table id='tipoSelecionaLocal' width="450px">
              <tr>
                <td nowrap width="50%"><?
                $aux->cabecalho = "<strong>Locais de Trabalho</strong>";
                $aux->codigo = "rh55_estrut"; //chave de retorno da func
                $aux->descr  = "rh55_descr";   //chave de retorno
                $aux->nomeobjeto = 'tipoSelLoca';
                $aux->funcao_js = 'js_mostrasel5';
                $aux->funcao_js_hide = 'js_mostrateste6';
                $aux->sql_exec  = "";
                $aux->func_arquivo = "func_rhlocaltrab.php";  //func a executar
                $aux->nomeiframe = "db_iframe_rhlocaltrab";
                $aux->localjan = "";
                $aux->nome_botao = "lanca_Local";
                $aux->onclick = "";
                $aux->db_opcao = 2;
                $aux->tipo = 2;
                $aux->top = '';
                $aux->linhas = 4;
                $aux->vwhidth = 400;
                $aux->funcao_gera_formulario();
                ?>
                </td>
              </tr>
            </table>

            <table align='center' width="450px">
              <tr>
                <td title="Selecionar Ordem" width="150px"><strong>Ordem:&nbsp;</strong>
                </td>

                <td><? 
                $ordemalfnum = array("a"=>"Alfabética ",
                		"n"=>"Numérica");

                db_select('ordem', $ordemalfnum,true,2,"");
                ?>
                </td>
              </tr>

              <tr>
                <td title="Tabela de Previdência "><strong>Tabela de Previdência:&nbsp;</strong>
                </td>

                <td><? 
                $resPrev  = $clinssirf->sql_record($clinssirf->sql_query_file(null,db_getsession('DB_instit'),"distinct (cast(r33_codtab as integer)-2) as r33_codtab, r33_nome","r33_codtab","r33_anousu = ".$DBtxt23." and r33_mesusu = ".$DBtxt25." and r33_codtab > 2"));
                db_selectrecord('prev', $resPrev ,true,4);
                ?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </fieldset>
              <input name="emite" type="button" id="emite" value="Emitir" onclick="js_emite();">
    </center>
</form>
<script>
  
  var listaSeleciona="";
  var docf = document.form1;  
  document.getElementById('tipoFiltro').style.display = "none";
  
  function js_mostratag(tipof, tipor){
   
    switch (tipor.value){
      case "m":
          if (tipof == 'i'){
             js_escondetag('tipoIntervaloMatric');
          }else if(tipof == 's'){
             js_escondetag('tipoSelecionaMatric');
          }else{
             js_escondetag();   
          }
      break;
      case "l":
          if (tipof == 'i'){
             js_escondetag('tipoIntervaloLotacao');             
          }else if(tipof == 's'){
             js_escondetag('tipoSelecionaLotacao');
          }else{
             js_escondetag();   
          }
      break;
      case "t":
          if(tipof == 'i'){
            js_escondetag('tipoIntervaloLocal');              
          }else if(tipof == 's'){
            js_escondetag('tipoSelecionaLocal');
          }else{
             js_escondetag();   
          }
      break;
      case "g":
          js_escondetag();
      break;
    }
  }

  function js_mudaresumo(tipor){
    
    if(tipor == "g"){
       document.getElementById('tipoFiltro').style.display = "none";
       js_escondetag(); 
    }else{
       document.getElementById('tipoFiltro').style.display = "";
       docf.tipo_fil.value = "0";
       js_escondetag(); 
    }   
  }

  function js_pesquisalink(mostra, tipo){
  
    if(tipo == 'mati' || tipo == 'matf' ){
       tipoRes = eval('document.form1.'+tipo);
       funcRes = 'func_rhpessoal';
       campRes = 'rh01_regist';
    }else if(tipo == 'lotai' || tipo == 'lotaf' ){  
       tipoRes = eval('document.form1.'+tipo);
       funcRes = 'func_rhlotaestrut';
       campRes = 'r70_codigo';
    }else{
       tipoRes = eval('document.form1.'+tipo);
       funcRes = 'func_rhlocaltrab';
       campRes = 'rh55_estrut';
       campf = tipoRes.value;
    }
    
    if(mostra==true){
      js_OpenJanelaIframe('','db_iframe_rh',''+funcRes+'.php?funcao_js=parent.js_abreconsulta|'+campRes+'','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('','db_iframe_rh',''+funcRes+'.php?pesquisa_chave='+tipoRes.value+'&funcao_js=parent.js_mostra','Pesquisa','false');
    }
  }
 
  function js_abreconsulta(chave){
    tipoRes.value = chave;
    db_iframe_rh.hide();
  }

  function js_mostra(chave,erro){
    tipoRes.value = chave;
    if(erro==true){
      tipoRes.focus();
      tipoRes.value = '';
    }
  }
 
  function js_escondetag(tag){
    document.getElementById('tipoIntervaloLocal').style.display = "none";
    document.getElementById('tipoIntervaloLotacao').style.display = "none";
    document.getElementById('tipoIntervaloMatric').style.display = "none";
    document.getElementById('tipoSelecionaMatric').style.display = "none";
    document.getElementById('tipoSelecionaLocal').style.display = "none";
    document.getElementById('tipoSelecionaLotacao').style.display = "none";

    if(tag){
      document.getElementById(tag).style.display = "";
    }

  }

  function js_retornalista(tag){
  vir="";
  //alert(getElementById(tag).[0].value);
  listaSeleciona = ""; 
   for(x=0;x<document.getElementById(tag).length;x++){
       listaSeleciona+=vir+document.getElementById(tag).options[x].value;
       vir=",";
    }
  } 
  
</script>