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

include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
//MODULO: pessoal
$clrhdepend->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh31_regist?>">
      <fieldset>
        <legend align="left"><b>DEPENDENTES</b></legend>
        <table align="center" >
         <tr>
           <td nowrap title="<?=@$Trh16_regist?>">
              <?
              db_ancora(@$Lrh31_regist,"",3);
              ?>
            </td>
            <td> 
              <?
              db_input('rh31_regist',6,$Irh31_regist,true,'text',3,"");
              db_input('rh31_codigo',6,$Irh31_codigo,true,'hidden',3);
              ?>
              <?
              db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh31_nome?>">
              <?=@$Lrh31_nome?>
            </td>
            <td> 
              <?
              db_input('rh31_nome',40,$Irh31_nome,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh31_dtnasc?>">
              <?=@$Lrh31_dtnasc?>
            </td>
            <td> 
              <?
              db_inputdata('rh31_dtnasc',@$rh31_dtnasc_dia,@$rh31_dtnasc_mes,@$rh31_dtnasc_ano,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh31_gparen?>">
              <?=@$Lrh31_gparen?>
            </td>
            <td> 
              <?
              $arr_gparen = array( 
                                  'C'=>'Cônjuge',
                                  'F'=>'Filho',
                                  'P'=>'Pai',
                                  'M'=>'Mãe',
                                  'A'=>'Avó',
                                  'O'=>'Outros'
                                 );
              db_select("rh31_gparen",$arr_gparen,true,$db_opcao);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh31_depend?>">
              <?=@$Lrh31_depend?>
            </td>
            <td> 
              <?
              if(!isset($rh31_depend)){
                $rh31_depend = "N";
              }
        
              $arr_depend = array(
                                  'C'=>'Cálculo',
                                  'S'=>'Sempre dependente',
                                  'N'=>'Não dependente'
                                 );
              db_select("rh31_depend",$arr_depend,true,$db_opcao);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh31_irf?>">
              <?=@$Lrh31_irf?>
            </td>
            <td> 
              <?
             
              
              $arr_irf = array(
                               '0' => 'Não Dependente',
                               '1' => 'Cônjuge,Companheiro(a)',
                               '2' => 'Filho(a)/Enteado(a), até 21 anos de idade',
                               '3' => 'Filho(a) ou enteado(a),  24 anos de idade cursando ensino superior',
                               '4' => 'Irmão(ã), neto(a) ou bisneto(a),  até 21 anos',
                               '5' => 'Irmão(ã), neto(a) ou bisneto(a), de 21 a 24 anos c/ensino superior',
                               '6' => 'Pais, avós e bisavós',
                               '7' => 'Menor pobre até 21 anos, com a guarda judicial',
                               '8' => 'Pessoa absolutamente incapaz'
                              );
              db_select("rh31_irf",$arr_irf,true,$db_opcao);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Trh31_especi?>">
              <?=@$Lrh31_especi?>
            </td>
            <td> 
              <?
              if(!isset($rh31_especi)){
                $rh31_especi = "N";
              }
        
              $arr_especi = array(
                                  'N'=>'Não dependente',
                                  'C'=>'Cálculo',
                                  'S'=>'Sempre dependente'
                                 );
              db_select("rh31_especi",$arr_especi,true,$db_opcao);
              ?>
           </td>
         </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?
if(isset($opcao)){
  echo "<input name='novo' type='button' id='novo' value='Novo' onclick='document.location.href=\"pes1_rhdepend001.php?rh31_regist=$rh31_regist&vmenu=true\"' >";
}
?>
<table width="90%">
  <tr>
    <td valign="top"  align="center" width="90%" heigth="100%">  
      <?
      $dbwhere = " rh31_regist = $rh31_regist ";
      if(isset($rh31_codigo) && trim($rh31_codigo)!=""){
        $dbwhere .= " and rh31_codigo <> $rh31_codigo ";
      }
      $sql = $clrhdepend->sql_query_file(null,"
                                          rh31_codigo,
                                          rh31_regist,
                                          rh31_nome,
                                          rh31_dtnasc,
                                          case rh31_gparen 
                                               when 'C' then 'Conjuje'
                                               when 'F' then 'Filho'
                                               when 'P' then 'Pai'
                                               when 'M' then 'Mãe'
                                               when 'A' then 'Avó'
                                          else 'Outros'
                                          end
                                          as rh31_gparen
                                          ,
                                          case when rh31_depend='C' then
                                               'Cálculo'
                                               else case when rh31_depend='S' then
                                                    'Sempre dependente'
                                                     else 
                                                    'Não dependente'
                                               end
                                          end
                                          as rh31_depend,
                                          
                                          
                                          case rh31_irf
                                               when '0' then 'Não dependente'
                                               when '1' then 'Cônjuge,Companheiro(a)'
                                               when '2' then 'Filho(a)/Enteado(a), até 21 anos de idade'
                                               when '3' then 'Filho(a) ou enteado(a),  24 anos de idade cursando ensino superior'
                                               when '4' then 'Irmão(ã), neto(a) ou bisneto(a),  até 21 anos'
                                               when '5' then 'Irmão(ã), neto(a) ou bisneto(a), de 21 a 24 anos c/ensino superior'
                                               when '6' then 'Pais, avós e bisavós'
                                               when '7' then 'Menor pobre até 21 anos, com a guarda judicia'
                                          else 'Pessoa absolutamente incapaz'
                                          end as rh31_irf
                                          ,
                                          case when rh31_especi='C' then
                                               'Cálculo'
                                               else case when rh31_especi='S' then
                                                    'Sempre dependente'
                                                     else 
                                                    'Não dependente'
                                               end
                                          end
                                          as rh31_especi
                                        ",
                                        "rh31_nome",
                                        $dbwhere
                                        );
      $asopcoes = 1;
      if($db_opcao==3){
//        $asopcoes = 4;
      }
//      die($sql);
      $chavepri= array("rh31_codigo"=>@$rh31_codigo);
      $cliframe_alterar_excluir->chavepri=$chavepri;
      $cliframe_alterar_excluir->sql = $sql;
      $cliframe_alterar_excluir->campos  ="rh31_nome,rh31_dtnasc,rh31_gparen,rh31_depend,rh31_irf,rh31_especi";
      $cliframe_alterar_excluir->legenda="DEPENDENTES LANÇADOS";
      $cliframe_alterar_excluir->iframe_height ="100%";
      $cliframe_alterar_excluir->iframe_width ="100%";
      $cliframe_alterar_excluir->opcoes = $asopcoes;
      $cliframe_alterar_excluir->iframe_alterar_excluir(1);
      ?>
    </td>
   </tr>
 </table>
</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_rhdepend','func_rhdepend.php?funcao_js=parent.js_preenchepesquisa|rh31_regist','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhdepend.hide();
  <?
  if($db_opcao!=1){
    echo "  location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>