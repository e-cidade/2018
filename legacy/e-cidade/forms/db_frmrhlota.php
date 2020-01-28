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

//MODULO: pessoal
$clrhlota->rotulo->label();
$clrhlotacalend->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db77_descr");
$clrotulo->label("o40_orgao");
$clrotulo->label("o40_descr");
$clrotulo->label("o41_unidade");
$clrotulo->label("o41_descr");
$clrotulo->label("rh64_calend");
$clrotulo->label("rh53_descr");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("z01_cgc");
if($db_opcao==1){
  $ac="pes1_rhlota004.php";
}else if($db_opcao==22 || $db_opcao==2){
  $ac="pes1_rhlota005.php";
}else if($db_opcao==33 || $db_opcao==3){
  $ac="pes1_rhlota006.php";
}
$r70_instit = db_getsession("DB_instit");
?>
<form name="form1" method="post" action="<?=$ac?>">
<center>
<table>
  <tr>
    <td>
      <fieldset>
        <legend>
	  <b>Dados da conta</b>
	</legend>
        <table border="0">
          <tr>
            <td nowrap title="<?=@$Tr70_codigo?>">
              <?=@$Lr70_codigo?>
            </td>
            <td> 
              <?
              db_input('r70_codigo',4,$Ir70_codigo,true,'text',3);
              db_input('r70_instit',4,$Ir70_instit,true,'hidden',3);
              ?>
            </td>
          </tr>
          <?  
          if(isset($estrutura_altera) ||   isset($chavepesquisa)&&isset($r70_estrut)){
            if(empty($estrutura_altera)){
              $estrutura_altera=$r70_estrut;
            }
            db_input('estrutura_altera',4,$Ir70_codigo,true,'hidden',3);
          }
        
          $anofolha = db_anofolha();
          $mesfolha = db_mesfolha();
          $result = $clcfpess->sql_record($clcfpess->sql_query_file($anofolha,$mesfolha,db_getsession("DB_instit"),"r11_codestrut"));
          if($clcfpess->numrows>0){
            db_fieldsmemory($result,0);
            if((trim($r11_codestrut) == "" || $r11_codestrut == 0) && ($db_opcao <= 3)){
              $sqlerro = true;
              $erro_msg = "ALERTA: \\nEstrutural da lotação não definido! \\nVerifique manutenção de parâmetros gerais.";
              $sem_parametro_configurado = true;
	    }else{ /// if(trim($r11_codestrut) == "" || $r11_codestrut == 0){
              $cldb_estrut->autocompletar = true;
              $cldb_estrut->mascara = true;
              $cldb_estrut->reload  = false;
              $cldb_estrut->input   = false;
              $cldb_estrut->size    = 22;
              $cldb_estrut->nome    = "r70_estrut";
              $opcaoestrut = 1;
              if($db_opcao!=1){
                $opcaoestrut = 3;
              }
              $cldb_estrut->db_opcao= $opcaoestrut;
              $cldb_estrut->db_mascara("$r11_codestrut");
	    }
          }else{
            $sqlerro  = true;
            $erro_msg = 'Não existem registros na tabela cfpess para o ano '.$anofolha.' e mês '.$mesfolha.' ...';
            $sem_parametro_configurado = true;
          }
          ?>
          <tr>
            <td nowrap title="<?=@$Tr70_descr?>">
              <?=@$Lr70_descr?>
            </td>
            <td> 
              <?
              db_input('r70_descr',59,$Ir70_descr,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh64_calend?>">
              <?
              db_ancora(@$Lrh64_calend,"js_pesquisarh64_calend(true);",$db_opcao);
              ?>
            </td>
            <td> 
              <?
              db_input('rh64_calend',10,$Irh64_calend,true,'text',$db_opcao,"onchange='js_pesquisarh64_calend(false);'");
              db_input('rh53_descr',45,$Irh53_descr,true,'text',3,"");
              ?>
            </td>
          </tr>
					<tr>
					    <td nowrap title="<?=$Tz01_numcgm?>">
					      <?
					         db_ancora($Lz01_numcgm,"js_pesquisaz01_numcgm(true);",1);
					      ?>
					    </td>
					    <td align="left" nowrap>
					      <?
					   db_input("z01_numcgm",10,$Iz01_numcgm,true,"text",1,"onchange='js_pesquisaz01_numcgm(false);'");
					   db_input("z01_nome",45,$Iz01_nome,true,"text",3);
					      ?>
					    </td>
					</tr>
          <tr>
            <td nowrap title="<?=@$Tr70_concarpeculiar?>">
              <?
                db_ancora(@$Lr70_concarpeculiar,"js_pesquisar70_concarpeculiar(true);",$db_opcao);
              ?>
            </td>
            <td>
              <?
                db_input("r70_concarpeculiar",10,$Ir70_concarpeculiar,true,"text",$db_opcao,"onChange='js_pesquisar70_concarpeculiar(false);'");
                db_input("c58_descr",45,0,true,"text",3);
              ?>
            </td>
          </tr>					
					<tr>
					    <td nowrap title="<?=@$Tz01_cgc?>">
					      <?=@$Lz01_cgc?>
					    </td>
					    <td>
					       <?
					        db_input('z01_cgc',16,$Iz01_cgc,true,'text',3);
					       ?>
					    </td>
					</tr>
          <tr>
            <td nowrap title="<?=@$Tr70_analitica?>">
              <?=@$Lr70_analitica?>
            </td>
            <td> 
              <?
              $opcao_nao_troca_analitica = $db_opcao;
              if(isset($sem_parametro_configurado)){
                $opcao_nao_troca_analitica = 3;
              }
              $x = array("f"=>"NAO","t"=>"SIM");
              db_select('r70_analitica',$x,true,$opcao_nao_troca_analitica,"onchange='js_troca();'");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr70_ativo?>">
              <?=@$Lr70_ativo?>
            </td>
            <td> 
              <?
              $x = array("t"=>"Ativo","f"=>"Inativo");
              db_select('r70_ativo',$x,true,$db_opcao);
              ?>
            </td>
          </tr>
        </table>  
      </fieldset>
    </td>
  <tr>
    <?
    if(isset($r70_analitica) && $r70_analitica=="t"){
    ?>
  <tr>
    <td>
      <fieldset><legend><b>Órgão / Unidade</b></legend>
      <center>
      <table border='0' cellpadding='0' cellspacing='0'>
        <tr>
          <td nowrap title="<?=@$To40_orgao?>">
            <?
            db_ancora(@$Lo40_orgao,"js_pesquisaorgunid(true);",$db_opcao);
            ?>
          </td>
          <td> 
            <?
            if(isset($o40_orgao) && trim($o40_orgao)!=""){
              $result_orgao = $clorcorgao->sql_record($clorcorgao->sql_query_file(db_getsession("DB_anousu"),$o40_orgao,"o40_descr"));
              if($clorcorgao->numrows>0){
                db_fieldsmemory($result_orgao,0);
              }
            }
            db_input('o40_orgao',8,$Io40_descr,true,'text',3,"");
            db_input('o40_descr',40,$Io40_descr,true,'text',3,"");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$To41_unidade?>">
            <?
            db_ancora(@$Lo41_unidade,"js_pesquisaorgunid(true);",$db_opcao);
            ?>
          </td>
          <td> 
            <?
            if(isset($o41_unidade) && trim($o41_unidade)!=""){
              $result_unidade = $clorcunidade->sql_record($clorcunidade->sql_query_file(db_getsession("DB_anousu"),$o40_orgao,$o41_unidade,"o41_descr"));
              if($clorcunidade->numrows>0){
                db_fieldsmemory($result_unidade,0);
              }
            }
            db_input('o41_unidade',8,$Io41_descr,true,'text',3,"");
            db_input('o41_descr',40,$Io41_descr,true,'text',3,"");
            ?>
          </td>
        </tr>
      </table>
      </center>
      </fieldset> 
    </td>
  </tr>
    <?
    } 
    ?>
  <tr>
    <td colspan="2" align='center'>
    
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
             type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
             <?=($db_botao==false?"disabled":"")?> <?=($db_opcao !=3 && $db_opcao != 33? "onclick='return js_validacgc();'":"")?>>
             
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
      
      <?
      if($db_opcao == 1 && !isset($sem_parametro_configurado)){
        echo '<input name="importar" type="button" id="importar" value="Importar" onclick="js_pesquisa_importacao();" >';
      }
      ?>
      
    </td>
  </tr>
</table>
</center>
</form>
<script>

function js_pesquisar70_concarpeculiar(mostra) {
  
  if (mostra) {
  
    js_OpenJanelaIframe('','db_iframe_concarpeculiar', 
                        'func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|c58_sequencial|c58_descr',
                        'Pesquisar',true);
  } else {
    if ( document.form1.r70_concarpeculiar.value != '' ) {
     
      js_OpenJanelaIframe('','db_iframe_concarpeculiar',
                          'func_concarpeculiar.php?pesquisa_chave='+document.form1.r70_concarpeculiar.value+
                          '&funcao_js=parent.js_mostraconcarpeculiar', 
                          'Pesquisa',
                          false
                         );
    } else {
      document.form1.c58_descr.value = ''; 
    }
  }
}

function js_mostraconcarpeculiar(chave,erro){
  document.form1.c58_descr.value = chave; 
  if(erro){ 
    document.form1.r70_concarpeculiar.focus(); 
    document.form1.r70_concarpeculiar.value = ''; 
  }
}
function js_mostraconcarpeculiar1(chave1,chave2){
  document.form1.r70_concarpeculiar.value = chave1;
  document.form1.c58_descr.value          = chave2;
  db_iframe_concarpeculiar.hide();
}


//valida cnpj
function js_validacgc(){

  if (document.form1.z01_numcgm.value == "") {
	alert('Informe o campo Numcgm');
	return false;    
  }	  	
  
  if(js_verificaCGCCPF(document.form1.z01_cgc)==false){
	alert('CPF/CNPJ do Numcgm informado inválido!');  
    document.form1.z01_numcgm.value = "";
    document.form1.z01_nome.value   = "";
    document.form1.z01_cgc.value    = "";
   return false;
  }
    
}

//verifica se cnpj é válido
//Função que pesquisa caso seja TRUE a pesquisa foi feita atraves da ancora caso seja FALSE a pesquisa foi digitada um numero de CGM 
function js_pesquisaz01_numcgm(mostra)
{
  if(mostra==true)
  {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlota','db_iframe_rhcadcalend','func_nome.php?funcao_js=parent.js_mostranumcgm1|z01_numcgm|z01_nome|z01_cgc&filtro=2','Pesquisa',true);
  }
  else
  {
     if(document.form1.z01_numcgm.value != '')
     {
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlota','db_iframe_rhcadcalend','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostranumcgm&filtro=2','Pesquisa',false);
     }
     else
     {
       document.form1.z01_nome.value = "";
     }
  }
}

//Função que retorna a pesquisa para o formulario com os dois campos NUMCGM e NOME.
//Caso a função js_pesquisaz01_numcgm tenha sido FALSE.
//Se a função não encontrar um NUMCGM digitado retorna um erro para o formulario.
function js_mostranumcgm(erro,chave1,chave2)
{
  if (chave2==""){
  chave2='0';
  }
  document.form1.z01_nome.value = chave1;
  document.form1.z01_cgc.value = chave2;
  if(erro==true)
  { 
    document.form1.z01_numcgm.value = ''; 
    document.form1.z01_numcgm.focus(); 
  }
}

//Função que retorna a pesquisa para o formulario com os dois campos NUMCGM e NOME
//Caso a função js_pesquisaz01_numcgm tenha sido TRUE.
function js_mostranumcgm1(chave1,chave2,chave3)
{
  if (chave3==""){
      chave3='0';
  }
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value   = chave2;
  document.form1.z01_cgc.value    = chave3;
  
  db_iframe_rhcadcalend.hide();
}




function js_pesquisarh64_calend(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlota','db_iframe_rhcadcalend','func_rhcadcalend.php?funcao_js=parent.CurrentWindow.corpo.iframe_rhlota.js_mostracalend1|rh53_calend|rh53_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.rh64_calend.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlota','db_iframe_rhcadcalend','func_rhcadcalend.php?pesquisa_chave='+document.form1.rh64_calend.value+'&funcao_js=parent.CurrentWindow.corpo.iframe_rhlota.js_mostracalend','Pesquisa',false,'0');
     }else{
       document.form1.rh53_descr.value = '';
     }
  }
}
function js_mostracalend(chave,erro){
  document.form1.rh53_descr.value = chave;
  if(erro==true){ 
    document.form1.rh64_calend.focus(); 
    document.form1.rh64_calend.value = ''; 
  }  
}
function js_mostracalend1(chave1,chave2){
  document.form1.rh64_calend.value = chave1;  
  document.form1.rh53_descr.value = chave2;
  db_iframe_rhcadcalend.hide();
}
function js_pesquisaorgunid(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlota','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.CurrentWindow.corpo.iframe_rhlota.js_mostraorgunid1|o41_orgao|o41_unidade','Pesquisa',true,'0');
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlota','db_iframe_orcunidade','func_orcunidade.php?funcao_js=parent.CurrentWindow.corpo.iframe_rhlota.js_mostraorgunid1|o41_orgao|o41_unidade','Pesquisa',false,'0');
  }
}
function js_mostraorgunid1(chave1,chave2){
  document.form1.o40_orgao.value = chave1;
  document.form1.o41_unidade.value = chave2;
  db_iframe_orcunidade.hide();
  document.form1.submit();
}
function js_troca(){
  document.form1.submit();
}
function js_pesquisa_importacao(){
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlota','db_iframerhlota','func_rhlota.php?funcao_js=parent.js_retornoimportacao|r70_codigo|r70_descr&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true,0);
}
function js_retornoimportacao(chave,chave2){
  db_iframerhlota.hide();
  if(confirm("Realmente deseja importar a lotação "+chave+" ("+chave2+") ?")){
    obj=document.createElement('input');
    obj.setAttribute('name','importar');
    obj.setAttribute('type','hidden');
    obj.setAttribute('value',chave);
    document.form1.appendChild(obj);
    document.form1.submit();
  }
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_rhlota','db_iframerhlota','func_rhlota.php?funcao_js=parent.js_preenchepesquisa|r70_codigo&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true,0);
}
function js_preenchepesquisa(chave){
  db_iframerhlota.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
<?
if(isset($r70_estrut) && trim($r70_estrut)!=""){
  echo "\njs_mascara03_r70_estrut(document.form1.r70_estrut.value);\n";
}
?>
</script>
<?
if(isset($focar)){
  echo "
        <script>
          document.form1.r70_descr.focus();
        </script>
       ";
}	    
if(isset($err_estrutural)){
  db_msgbox($err_estrutural);
  echo "<script> document.form1.r70_estrut.style.backgroundColor='#99A9AE';</script>";
}
?>
