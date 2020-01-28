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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_saltes_classe.php");

db_postmemory($HTTP_POST_VARS);

$cliframe_seleciona_documaut = new cl_iframe_seleciona;
$clsaltes                    = new cl_saltes;
$aux                         = new cl_arquivo_auxiliar;

$clrotulo = new rotulocampo;
$clrotulo->label("k12_data");
$clrotulo->label("e60_numcgm");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("k12_conta");
$clrotulo->label("k12_estorn");
$clrotulo->label("k12_codord");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_seleciona(false);">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
<table height="100%" border="0"  align="center" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0" cellpadding="4">
	     <form name="form1" method="post" action="" >
<?
   db_input("enviado",4,"",false,"hidden",3);
   db_input("ordens_selecionadas",100,"",false,"hidden",3);
?>
          <tr><td colspan="2">&nbsp;</td></tr>
          <tr> 
            <td align="right" nowrap title="<?=$Tk12_data?>"><b>Periodo&nbsp;&nbsp;</b>
            </td>
            <td align="left" nowrap> 
            <?
		          db_inputdata("k12_dataini",@$k12_dataini_dia,@$k12_dataini_mes,@$k12_dataini_ano,true,"text",4);
              echo "&nbsp;a&nbsp;";
		          db_inputdata("k12_datafim",@$k12_datafim_dia,@$k12_datafim_mes,@$k12_datafim_ano,true,"text",4);
              echo " - Informe periodo completo.";
		        ?>
            </td>
          </tr>
          <tr> 
            <td align="right" nowrap title="<?=$Tz01_numcgm?>"><?db_ancora($Lz01_numcgm,"js_pesquisa_cgm(true);",1);?></td>
            <td align="left" nowrap> 
            <?
		          db_input("z01_numcgm",6,$Iz01_numcgm,true,"text",4,"onChange='js_pesquisa_cgm(false);'");
	            db_input("z01_nome",40,"",true,"text",3);  
		        ?>
            </td>
          </tr>
          <tr>
            <td align="right" nowrap title="<?=$Tk12_conta?>"><?=$Lk12_conta?></td>
            <td align="left" nowrap>
            <?
				       $result = $clsaltes->sql_record($clsaltes->sql_query("","saltes.k13_conta#k13_descr","k13_descr")); 
				       db_selectrecord("k12_conta",$result,true,4,"","","","0");
				    ?>
            </td>
          </tr>
          <tr>
            <td align="right" nowrap title="<?=$Tk12_estorn?>"><?=$Lk12_estorn?></td>
            <td align="left" nowrap>
            <?
		  	       $matriz = array("T"=>"TODOS","f"=>"NAO","t"=>"SIM");
				       db_select("k12_estorn",$matriz,true,4);
				    ?>
            </td>
          </tr>
          <tr>
            <td nowrap colspan="2" align="center"><table border="0">
            <?
              $aux->cabecalho      = "<strong>ORDENS DE PAGAMENTO</strong>";
              $aux->codigo         = "e53_codord";     //chave de retorno da func
              $aux->descr          = "k12_codord";     //chave de retorno
              $aux->nomeobjeto     = 'ordem_sel';
              $aux->funcao_js      = 'js_mostraordem';
              $aux->funcao_js_hide = 'js_mostraordem1';
              $aux->executa_script_lost_focus_campo = "js_seleciona_ordens()";
              $aux->sql_exec       = "";
              $aux->func_arquivo   = "func_pagordemele.php";  //func a executar
              $aux->nomeiframe     = "db_iframe_ordem";
              $aux->localjan       = "";
              $aux->onclick        = "";
              $aux->db_opcao       = 4;
              $aux->tipo           = 2;
              $aux->top            = null;
              $aux->linhas         = 10;
              $aux->vwidth         = 400;
              $aux->funcao_gera_formulario();
            ?>
            </table></td>
          </tr>
          <tr> 
            <td colspan="2" align="center" height="50"> 
              <input name="pesquisar" type="button" id="pesquisar2" value="Pesquisar" onClick="js_valida_datas();"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar">
              <input name="imprimir" type="submit" id="imprimir2" value="Imprimir" onClick="js_imprimir();">
            </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <?
       if(isset($enviado)&&trim($enviado)=="true"){
           $dbwhere = "where 1=1 and corrente.k12_instit = ".db_getsession("DB_instit");
           if(isset($k12_dataini_dia)&&trim($k12_dataini_dia)!=""){
               $dbwhere .= " and corrente.k12_data between '$k12_dataini_ano-$k12_dataini_mes-$k12_dataini_dia' and 
                                                           '$k12_datafim_ano-$k12_datafim_mes-$k12_datafim_dia'";
           }
       
           if(isset($z01_numcgm)&&trim($z01_numcgm)!=""){
               $dbwhere .= " and z01_numcgm = $z01_numcgm";
           }

           if(isset($k12_estorn)&&trim($k12_estorn)!=""){
               if($k12_estorn=="T"){
                    $dbwhere .= " and (corrente.k12_estorn is true or corrente.k12_estorn is not true)";
               } else {
                    $dbwhere .= " and corrente.k12_estorn = '$k12_estorn'";
               }
           }

           if(isset($k12_conta)&&$k12_conta > 0){
               $dbwhere .= " and corrente.k12_conta = $k12_conta";
           }

           if(isset($ordens_selecionadas)&&trim($ordens_selecionadas)!=""){
               $k12_codord = str_replace("|",",",$ordens_selecionadas);
               $k12_codord = substr($k12_codord,0,strlen($k12_codord)-1);
               $vetor      = @split(",",$k12_codord);
               if(sizeof($vetor)==0){
                   $vetor[0] = $k12_codord;
               }
               for($i=0; $i < sizeof($vetor); $i++){
?>
  <script>
    document.form1.e53_codord.value = <?=$vetor[$i]?>;
    document.form1.k12_codord.value = <?=$vetor[$i]?>;
    js_insSelectordem_sel();
  </script>
<?
               }

               $dbwhere   .= " and coremp.k12_codord in ($k12_codord)";
           }

           $sql = "select distinct z01_numcgm,
                          z01_nome,
                          corrente.k12_data,
                          corrente.k12_conta,
                          corrente.k12_valor,
                          corrente.k12_estorn,
                          coremp.k12_empen,
                          empempenho.e60_codemp,
                          coremp.k12_cheque,
                          coremp.k12_codord,
                          empnota.e69_numero,
                          case
                              when corrente.k12_estorn is false then
                              case
                                  when empageforma.e96_descr = 'DIN' and coremp.k12_cheque = 0 then 'DINHEIRO'
                                  when empageforma.e96_descr = 'CHE' or  coremp.k12_cheque > 0 then 'CHEQUE'
                                  when empageforma.e96_descr = 'TRA' then 'TRANSMISSAO'
                              end
                          else
                              'ESTORNO'
                          end as e96_descr
                   from corrente 
                        inner join coremp        on coremp.k12_id               = corrente.k12_id   and
                                                    coremp.k12_data             = corrente.k12_data and
                                                    coremp.k12_autent           = corrente.k12_autent

                        inner join conplanoreduz on conplanoreduz.c61_reduz     = corrente.k12_conta and
                                                    conplanoreduz.c61_anousu    = ".db_getsession("DB_anousu")."

                        inner join empempenho    on empempenho.e60_numemp       = coremp.k12_empen

                        inner join cgm           on cgm.z01_numcgm              = empempenho.e60_numcgm

                        inner join pagordem      on pagordem.e50_numemp         = coremp.k12_empen and
                                                    pagordem.e50_codord         = coremp.k12_codord

                        inner join empord        on empord.e82_codord           = coremp.k12_codord                            

                        left  join pagordemnota  on pagordemnota.e71_codord     = pagordem.e50_codord

                        left  join corempagemov  on corempagemov.k12_id         = corrente.k12_id   and
                                                    corempagemov.k12_data       = corrente.k12_data and
                                                    corempagemov.k12_autent     = corrente.k12_autent
                                                    
                        left  join empagemov     on empagemov.e81_codmov        = corempagemov.k12_codmov

                        left  join empagemovconta on empagemovconta.e98_codmov  = empagemov.e81_codmov

                        left  join pcfornecon     on pcfornecon.pc63_numcgm     = cgm.z01_numcgm and 
                                                     pcfornecon.pc63_contabanco = empagemovconta.e98_contabanco 

                        left  join empnota        on empnota.e69_numemp         = coremp.k12_empen and
                                                     empnota.e69_codnota        = pagordemnota.e71_codnota

                        left  join empagemovforma on empagemovforma.e97_codmov  = empagemov.e81_codmov

                        left  join empageforma    on empageforma.e96_codigo     = empagemovforma.e97_codforma ".$dbwhere."
               order by z01_numcgm, corrente.k12_data desc";
  ?>
    <td align="center" valign="top"> 
    <?
//       echo $sql;
           $sql_marca = "";
           $campos    = "z01_numcgm,z01_nome,k12_data,k12_conta,k12_valor,e60_codemp,e96_descr,k12_cheque,e69_numero,k12_codord";
           $cliframe_seleciona_documaut->campos    = $campos;
           $cliframe_seleciona_documaut->legenda   = "";
           $cliframe_seleciona_documaut->sql       = $sql;	   
           $cliframe_seleciona_documaut->sql_marca = $sql_marca;
           $cliframe_seleciona_documaut->iframe_height = "400";
           $cliframe_seleciona_documaut->iframe_width  = "850";
           $cliframe_seleciona_documaut->iframe_nome   = "documaut"; 
           $cliframe_seleciona_documaut->chaves        = "z01_numcgm,k12_data,k12_empen,k12_estorn,k12_codord,e69_numero,k12_conta,k12_valor";
    	     $cliframe_seleciona_documaut->js_marcador   = "parent.js_seleciona(true)";
    	     $cliframe_seleciona_documaut->dbscript      = "onClick='parent.js_seleciona(true);'";
           $cliframe_seleciona_documaut->mostra_totalizador  = "S";
           $cliframe_seleciona_documaut->posicao_totalizador = "A";
           $cliframe_seleciona_documaut->iframe_seleciona(4);    
       }
    ?>
    </td>
  </tr>
</table>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_pesquisa_cgm(mostra){
   if(mostra==true){
       js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm_empenho.php?funcao_js=parent.js_mostracgm1|e60_numcgm|z01_nome','Pesquisa',true);
   }else{
       if(document.form1.z01_numcgm.value != ''){ 
           js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm_empenho.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
       }else{
           document.form1.z01_nome.value = ''; 
       }
   }
}
function js_mostracgm(chave,erro){
   document.form1.z01_nome.value = chave; 
   if(erro==true){ 
       document.form1.z01_nome.value = ''; 
       document.form1.z01_numcgm.focus(); 
   }
}
function js_mostracgm1(chave1,chave2){
   document.form1.z01_numcgm.value = chave1;  
   document.form1.z01_nome.value   = chave2;
   db_iframe_cgm.hide();
}
function js_valida_datas(){
   var dia_ini = eval("document.form1.k12_datafim_dia");
   var mes_ini = eval("document.form1.k12_datafim_mes");
   var ano_ini = eval("document.form1.k12_datafim_ano");
   var dia_fim = eval("document.form1.k12_dataini_dia");
   var mes_fim = eval("document.form1.k12_dataini_mes");
   var ano_fim = eval("document.form1.k12_dataini_ano");

   var flag_datas  = false;

   var F           = document.getElementById("ordem_sel");
   var total_itens = F.length;
   var ordens      = eval("document.form1.ordens_selecionadas");
   var conta       = document.form1.k12_conta.value;
   var numcgm      = document.form1.z01_numcgm.value;

   if((dia_ini.value == "" && mes_ini.value == "" && ano_ini.value == "")&&
      (dia_fim.value == "" && mes_fim.value == "" && ano_fim.value == "")){
       flag_datas = true;
   } else if((dia_ini.value == "" && mes_ini.value == "" && ano_ini.value == "")||
      (dia_fim.value == "" && mes_fim.value == "" && ano_fim.value == "")){
       alert("Informe o periodo completo.");
       return false;
   }
   
   ordens.value = "";
   for(i=0; i < total_itens; i++){
        ordens.value += F.options[i].value+"|";
   }

   if (flag_datas==false||ordens.value!=""||conta>0||numcgm!=""){
        document.form1.enviado.value = "true";
        document.form1.submit();
   } else {
        alert("Informe algum filtro para pesquisar!");
   }
} 
function js_imprimir(){
   var lista_cgm     = "";
   var lista_data    = "";
   var lista_empen   = "";
   var lista_estorn  = "";
   var lista_ordens  = "";
   var lista_nfs     = "";
   var lista_contas  = "";
   var lista_valores = "";
   var virgula       = "";
   var query         = "";
   var periodo       = "";
   var total         = documaut.document.form1.elements.length;

   if(document.form1.k12_dataini_dia.value != ""){
       periodo = document.form1.k12_dataini_dia.value+"/"+document.form1.k12_dataini_mes.value+"/"+document.form1.k12_dataini_ano.value+
                 " a "+document.form1.k12_datafim_dia.value+"/"+document.form1.k12_datafim_mes.value+"/"+document.form1.k12_datafim_ano.value;
   }

   for(i=0; i < total; i++){
        if(documaut.document.form1.elements[i].type == "checkbox"){
            if(documaut.document.form1.elements[i].checked == true){
                valor = new String(documaut.document.form1.elements[i].value);
                vetor = valor.split("_");
                lista_cgm    += virgula+vetor[0];
                lista_data   += virgula+vetor[1];
                lista_empen  += virgula+vetor[2];
                lista_estorn += virgula+vetor[3];
                lista_ordens += virgula+vetor[4];

                if (vetor[5]!=""&&vetor[5]!="0"){
                     lista_nfs += virgula+vetor[5];
                }

                lista_contas  += virgula+vetor[6];
                lista_valores += virgula+vetor[7];
                virgula = ", ";
            }
        }
   }

   query  = "periodo="+periodo; 
   query += "&lista_cgm="+lista_cgm;
   query += "&lista_data="+lista_data;
   query += "&lista_empen="+lista_empen;
   query += "&lista_estorn="+lista_estorn;
   query += "&lista_ordens="+lista_ordens;

   if (lista_nfs!=""){
        query += "&lista_nfs="+lista_nfs;
   }

   query += "&lista_contas="+lista_contas;
   query += "&lista_valores="+lista_valores;

   jan = window.open('cai3_documaut002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
   jan.moveTo(0,0);
}
function js_seleciona(valor){
   var frm      = document.form1;
   var contador = 0;
 
<?
   if(isset($enviado)&&trim($enviado)=="true"){
?>     
   for(i=0; i < documaut.document.form1.elements.length; i++){
        if(documaut.document.form1.elements[i].type == "checkbox"){
            if(documaut.document.form1.elements[i].checked == true){
                contador++;
                break;
            }
        }
   }
<?
   }  
?>
   if(contador == 0){
       valor = false;
   }

   if (valor == false){
        frm.imprimir.disabled = true;
        frm.imprimir.enabled  = false;
   } else {
        frm.imprimir.disabled = false;
        frm.imprimir.enabled  = true;
   }
}
function js_seleciona_ordens(){
   document.form1.k12_codord.value = document.form1.e53_codord.value;
}
</script>
</body>
</html>