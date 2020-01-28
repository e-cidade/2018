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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));

include(modification("libs/db_libcaixa_ze.php"));
$cllayouts_bb = new LayoutBB;
$cllayouts_bs = new LayoutBS;

include(modification("classes/db_empagetipo_classe.php"));
include(modification("classes/db_empage_classe.php"));
include(modification("classes/db_empagemov_classe.php"));
include(modification("classes/db_empagegera_classe.php"));
include(modification("classes/db_empageconf_classe.php"));
include(modification("classes/db_empageconfgera_classe.php"));
include(modification("classes/db_conplanoconta_classe.php"));
include(modification("classes/db_empagepag_classe.php"));
include(modification("classes/db_empagemod_classe.php"));

$clempage = new cl_empage;
$clconplanoconta = new cl_conplanoconta;
$clempagetipo = new cl_empagetipo;
$clempagemov  = new cl_empagemov;
$clempagegera = new cl_empagegera;
$clempageconf = new cl_empageconf;
$clempageconfgera = new cl_empageconfgera;
$clempagepag  = new cl_empagepag;
$clempagemod  = new cl_empagemod;



parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);

$db_botao = false;

if(isset($e80_data_ano)){
  $data = "$e80_data_ano-$e80_data_mes-$e80_data_dia";
}

  $sqlerro=false;
if(isset($atualizar) || isset($adicionar)){
  $e83_convenio = $e83_conv;
  
  db_inicio_transacao();
  $sqlerro = false;

  $sqlinst = "select * from db_config where codigo = ".db_getsession("DB_instit"); 
  $resultinst = db_query($sqlinst);
  
  db_fieldsmemory($resultinst,0);
 
  //esta variavel contem o tipo de um movimentos selecionados
  $e83_codtipo = $codtipo;  
  
  //rotina que traz a sequencia do cheque... executada apenas na primeira vez
   $result = $clempagetipo->sql_record($clempagetipo->sql_query($codtipo,'e83_sequencia as sequencia,e83_conta,e83_codmod'));
   if($clempagetipo->numrows>0){
        db_fieldsmemory($result,0);
    }
  //-----------------------------------
  
  //-----------------------------------
  //rotina que gera as agendas
  if($sqlerro==false && $movs !='' && empty($adicionar)){
    $clempagegera->e87_descgera = $e87_descgera;
    $clempagegera->e87_data     = date("Y-m-d",db_getsession("DB_datausu"));
    $clempagegera->e87_hora     = db_hora();
    $clempagegera->e87_dataproc = $deposito_ano . "-" . $deposito_mes . "-" . $deposito_dia;
    $clempagegera->incluir(null);
    $erro_msg = $clempagegera->erro_msg;
    if($clempagegera->erro_status==0){
      $sqlerro = true;
    }else{
      $gera = $clempagegera->e87_codgera;
    } 
  }
  //-----------------------------------
 //inclui na tabela empageconf
    $arr =  split("XX",$movs);
    $tot_valor ='';
    for($i=0; $i<count($arr); $i++ ){
       $mov = $arr[$i];  
       
       if($sqlerro==false){
   $clempageconf->e86_codmov = $mov;
//   $clempageconf->e86_data   = date("Y-m-d",db_getsession("DB_datausu"));
   $clempageconf->e86_data   = $deposito_ano . "-" . $deposito_mes . "-" . $deposito_dia;
   $clempageconf->e86_cheque = $sequencia;
   $clempageconf->e86_correto= 'true';
   //$clempageconf->xxxxe86xxxx_codgera = $gera;
   $clempageconf->incluir($mov);
   $erro_msg = $clempageconf->erro_msg;
   if($clempageconf->erro_status==0){
         $sqlerro = true;
   }     
       }  
   
   //-------------
   //manutenção empageconfgera
   //soh quando for banco do brasil
      if($sqlerro==false && empty($adicionar)) {
     $clempageconfgera->e90_codmov   = $mov;
     $clempageconfgera->e90_codgera = $gera;
         $clempageconfgera->e90_correto = "true";
                 
     $clempageconfgera->incluir($mov,$gera);
     $erro_msg = $clempageconfgera->erro_msg;
     if($clempageconfgera->erro_status==0){
           $sqlerro = true;
     }     
      }
  //------- 
    }
   ///------------
   //-------------
   //manutenção empagetipo
      if($sqlerro==false && empty($adicionar)) {

          $sql11    = $clempagemod->sql_query_file($e83_codmod,"e84_sequencia");
    $result11 = $clempagemod->sql_record($sql11);
          db_fieldsmemory($result11,0);       
  
     $clempagemod->e84_codmod   = $e83_codmod;
     $clempagemod->e84_sequencia = $e84_sequencia+1;
     $clempagemod->alterar($e83_codmod);
     $erro_msg = $clempagemod->erro_msg;
     if($clempagemod->erro_status==0){
     $sqlerro = true;
     }     
      }
  //------- 

  
//   db_msgbox('adicionar 1');

 //quando for do banco  do brasil
 if(empty($adicionar)){
//   db_msgbox('adicionar 2');

      ///////////////////////////////////////////////////////////////////////////////////////////////////////
     /*         começa layouts        */

$sql = "
select  distinct
        e90_codgera,
  e90_codmov,
  c63_banco,
  c63_agencia,
  c63_conta,
  translate(to_char(round(e81_valor,2),'99999999999.99'),'.','') as valor,
  coalesce(pc63_banco,'000') as pc63_banco,
  upper(pc63_agencia) as pc63_agencia,
  upper(pc63_conta) as pc63_conta,
  length(trim(pc63_cnpjcpf)) as tam,
  convenio,
  numcgm,
  substr(z01_nome,1,40) as z01_nome,
        pc63_cnpjcpf as z01_cgccpf,
  cancelado
        
from 
  (select e90_codgera,
          e90_codmov,
          c63_banco,
          upper(c63_agencia) as c63_agencia,
    lpad(e83_convenio,6,0) as convenio,
          upper(c63_conta) as c63_conta,
          e81_valor,
          case when e60_numcgm is null then k17_numcgm else e60_numcgm end as numcgm,
          e88_codmov as cancelado
       
  from empageconfgera 
        inner join empagemov on e90_codmov = e81_codmov 
        inner join empage  on  empage.e80_codage = empagemov.e81_codage
        left join empempenho on e60_numemp = e81_numemp
        inner join empagepag on e81_codmov = e85_codmov 
        inner join empagetipo on e85_codtipo = e83_codtipo 
        left join empageslip on e81_codmov = e89_codmov 
        inner join conplanoreduz on e83_conta = c61_reduz and c61_anousu = ".db_getsession("DB_anousu")." and c61_instit = " . db_getsession("DB_instit") . "
        inner join conplanoconta on c63_codcon = c61_codcon and c63_anousu=c61_anousu 
        left join slip on slip.k17_codigo = e89_codigo
        left join slipnum on slipnum.k17_codigo = slip.k17_codigo
        left join empageconfcanc on e88_codmov = e90_codmov
        where e80_instit = " . db_getsession("DB_instit") . "
        ) as x
  left join cgm on z01_numcgm = numcgm
  inner join pcfornecon on z01_numcgm = pc63_numcgm
  left join pcforneconpad on pc63_contabanco = pc64_contabanco
where e90_codgera = $gera 
order by c63_conta,e90_codmov   
     ";
$result  =  db_query($sql);
$numrows =  pg_numrows($result);
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
    //////LAYOUTS////////////////////////////////////////////////////////////////////////////////////////////////////////
    //// LAYOUT BANCO DO BRASIL
       $registro = 2;
//       $nomeinst = "Prefeitura";
       db_fieldsmemory($result,0);

       $data    =  $dtin_dia.$dtin_mes.$dtin_ano;
       $dat_cred = $deposito_dia.$deposito_mes.$deposito_ano;
       /// criar campo de sequencia do arquivo no empagetipo.
       $seq_arq = $e83_sequencia; 
       
//       $nomearquivo = basename(tempnam('tmp','Modelo')).".doc";
       $nomearquivo = 'pagtbb'.$e90_codgera.'.doc';
       //indica qual será o nome do arquivo
       $cllayouts_bb->nomearq = "tmp/$nomearquivo";
       
       if(!is_writable("tmp/")){
   $sqlerro= true;
   $erro_msg = 'Sem permissão de gravar o arquivo. Contate suporte.';
       }  
      if($e83_codmod == 2 && $sqlerro == false){   
   //db_msgbox("//// LAYOUT BANCO DO BRASIL");
   
   
         //--------------------------------------------------------------------------------------------------
        ///// COMEÇO DO HEADER 
        $agencia_pre = db_formatar(str_replace('-','',$c63_agencia),'s','0',5,'e',0);
        $conta_pre   = db_formatar(str_replace('.','',str_replace('-','',$c63_conta)),'s','0',10,'e',0);
        
        $cllayouts_bb->cabec01 = '0' ;          // fixo
        $cllayouts_bb->cabec02 = '1' ;          // fixo
        $cllayouts_bb->cabec03 = '       ' ;        // branco
        $cllayouts_bb->cabec04 = '03' ;           // fixo
        $cllayouts_bb->cabec05 = ' ' ;          // branco
        $cllayouts_bb->cabec06 = '00000' ;          // fixo
        $cllayouts_bb->cabec07 = '         ';           // brancos
        $cllayouts_bb->cabec08 = substr($agencia_pre,0,4);    // numero da agencia
        $cllayouts_bb->cabec09 = strtoupper(substr($agencia_pre,4,1));  // digito da agencia
        $cllayouts_bb->cabec10 = substr($conta_pre,0,9);      // conta
        $cllayouts_bb->cabec11 = strtoupper(substr($conta_pre,9,1));    // digito da conta
        $cllayouts_bb->cabec12 = '     ' ;        // brancos
        $cllayouts_bb->cabec13 = substr(strtoupper($nomeinst),0,30) ;   // nome da empresa
        $cllayouts_bb->cabec14 = '001';           // codigo do banco
        $cllayouts_bb->cabec15 = $convenio ;        // numero do converio
        $cllayouts_bb->cabec16 = '   ' ;          // brancos
        $cllayouts_bb->cabec17 = db_formatar($e90_codgera,'s',' ',10,'e',0);  // campo livre
        $cllayouts_bb->cabec18 = '00' ;         // tipo de retorno magnetico
        $cllayouts_bb->cabec19 = '000' ;          // para uso do banco
        $cllayouts_bb->cabec20 = str_repeat(' ',46) ;     // brancos
        $cllayouts_bb->cabec21 = str_repeat(' ',17) ;     // exclusivo do sistema
        $cllayouts_bb->cabec22 = 'NOVO';          // "novo" para possibilitar o arquivo de retorno
        $cllayouts_bb->cabec23 = str_repeat(' ',15) ;     // brancos
        $cllayouts_bb->cabec24 = str_repeat(' ',9) ;      // brancos
        $cllayouts_bb->cabec25 = '000001' ;       // sequencial - 000001
        $cllayouts_bb->gera_cabecalho();
       
        //// FIM DO HEADER

        for($i=0; $i<$numrows; $i++ ){
     db_fieldsmemory($result,$i); 
         //gera detalhe    
      if($tam == 14){
        $conf = 4;
        $cgccpf = substr($z01_cgccpf,0,12);
        $dig_cgccpf = substr($z01_cgccpf,12,2);
      }elseif($tam == 11){
        if($z01_cgccpf == '00000000000'){
          $conf = 1;
          $cgccpf = '000000000000';
          $dig_cgccpf = '00';
        }else{
          $conf = 2;
          $cgccpf = str_pad(substr($z01_cgccpf,0,9),12,"0",STR_PAD_LEFT);
          $dig_cgccpf = substr($z01_cgccpf,9,2);
        }
      }else{
        $conf = 1;
        $cgccpf = '000000000000';
        $dig_cgccpf = '00';
      }

      $agencia_fav = db_formatar(str_replace('-','',$pc63_agencia),'s','0',5,'e',0);
      $conta_fav   = db_formatar(str_replace('-','',$pc63_conta),'s','0',13,'e',0);
      
      $cllayouts_bb->corp01 = '1' ;           // fixo 
      $cllayouts_bb->corp02 = ' ' ;           // brancos 
      $cllayouts_bb->corp03 = $conf ;         // indicador de conferencia
      $cllayouts_bb->corp04 = $cgccpf ;         // cgc, cnpf ou zeros
      $cllayouts_bb->corp05 = $dig_cgccpf ;         // digitos do cpf ou cnpf
      $cllayouts_bb->corp06 = '0000' ;          // sequencial 
      $cllayouts_bb->corp07 = '0' ;           // sequencial 
      $cllayouts_bb->corp08 = '000000000' ;         // sequencial 
      $cllayouts_bb->corp09 = '0' ;           // sequencial 
      $cllayouts_bb->corp10 = db_formatar($e90_codmov,'s',' ',10,'d',0) ; // livre
      $cllayouts_bb->corp11 = str_repeat(' ',8) ;       // sequencial 
      $cllayouts_bb->corp12 = str_repeat(' ',6) ;       // campo livre
      $cllayouts_bb->corp13 = '000' ;         // camara de compensação
      $cllayouts_bb->corp14 = ($pc63_banco = 001?'   ':$pc63_banco) ; // codigo do banco
      $cllayouts_bb->corp15 = substr($agencia_fav,0,4) ;      // agencia do favorecido 
      $cllayouts_bb->corp16 = ($pc63_banco = 001?substr($agencia_fav,4,1):db_CalculaDV(substr($agencia_fav,0,4))) ;  // digito da agencia 
      $cllayouts_bb->corp17 = substr($conta_fav,0,12) ; // conta do favorecido 
      $cllayouts_bb->corp18 = ($pc63_banco = 001?substr($conta_fav,12,1):db_CalculaDV(substr($conta_fav,0,4))) ;   // digito da conta
      $cllayouts_bb->corp19 = '  ' ;          // brancos 
      $cllayouts_bb->corp20 = db_formatar($z01_nome,'s',' ',40,'d',0) ; // nome do favorecido 
      $cllayouts_bb->corp21 =  $deposito_dia.$deposito_mes.substr($deposito_ano,2,2) ;          // data do depósito(DDMMAA) 
      $cllayouts_bb->corp22 = db_formatar(str_replace('.','',$valor),'s','0',13,'e',0) ;  // valor 
      $cllayouts_bb->corp23 = ($pc63_banco = 001?'002':'017') ;   // codigo do serviço 
      $cllayouts_bb->corp24 = str_repeat(' ',40) ;        // livre 
      $cllayouts_bb->corp25 = str_repeat(' ',10) ;        // livre 
      $cllayouts_bb->corp26 = str_pad($registro,6,"0",STR_PAD_LEFT);  // sequencial
      $cllayouts_bb->gera_corpo();
      $registro += 1;
    //final detalhe
        } 
       
        //trailler.;...  RODAPÉ
        $cllayouts_bb->rodap01 = '9';       // fixo
        $cllayouts_bb->rodap02 = str_repeat(' ',193) ;  // brancos
        $cllayouts_bb->rodap03 = str_pad($registro,6,"0",STR_PAD_LEFT);        // numero de registros
        $cllayouts_bb->gera_trailer();

         $cllayouts_bb->gera();
      }else if($sqlerro==false){
  $sqlerro  = true;
  $erro_msg = "O tipo selecionado, não possui layout cadastrado.";
      }
  

  }//fecha if(isset($adicionar)){
  db_fim_transacao($sqlerro);
}







//quando entra pela primeira vez
if(empty($e80_data_ano)){
  $e80_data_ano = date("Y",db_getsession("DB_datausu"));
  $e80_data_mes = date("m",db_getsession("DB_datausu"));
  $e80_data_dia = date("d",db_getsession("DB_datausu"));
  $data = "$e80_data_ano-$e80_data_mes-$e80_data_dia";
}

if(isset($data)){
    $result01 = $clempage->sql_record($clempage->sql_query_file(null,'e80_codage','',"e80_data='$data' and e80_instit = " . db_getsession("DB_instit")));
    $numrows01 = $clempage->numrows;
}



?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr> 
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
   <?
   
   $clrotulo = new rotulocampo;
   $clrotulo->label("e80_data");
   $clrotulo->label("e84_codmod");
   
//sempre que ja existir agenda entra nesta opcao  
 if(isset($e80_codage) && empty($pesquisar)){  
  include(modification("forms/db_frmempageconflay.php"));

//pela primeira vez que entrar neste arquivo, entra nesta opcao para digitar a data da agenda 
  }else{
  ?>
    <center>
      <table>
        <tr>
    <td>
       <fieldset><legend><b>Manutenção de agenda</b></legend> 
        <form name="form1" method="post" action="">
        <br>
         <table >
     <tr>
        <td nowrap title="<?=@$Te80_data?>" align='right'>
        <?=$Le80_data?>
        </td> 
        <td>  
         <?
     db_inputdata('e80_data',@$e80_data_dia,@$e80_data_mes,@$e80_data_ano,true,'text',1);
         ?>
          <input name="pesquisar" type="submit"    value="Pesquisar">
        </td>
     </tr>
           <tr>
       <td class='bordas' align='right'>
                 <? db_ancora("Agendas","js_empage();",1);  ?>
     :
       </td>
<?
          if($numrows01!=0){
       for($i=0; $i<$numrows01; $i++){
         db_fieldsmemory($result01,$i);
         $arr[$e80_codage] = $e80_codage;
       }
    }  
?>
             <td class='bordas'><small>
<?
          //variavel setada apenas quando o usuario pesquisar na func 
          if(isset($pri_codage)){
      $e80_codage = $pri_codage;
    } 

          if($numrows01==0){
      echo "Nenhuma encontrado";  
    }else{  
         db_select("e80_codage",$arr,true,1);
    }
?>     
         
       </small>
       <?=$Le84_codmod?>  
<?       
     
          $sql01    = $clempagemod->sql_query_mod(null,"distinct e84_codmod,e84_descr","","e84_codmod <> 1");
    $result01 = $clempagemod->sql_record($sql01);
        $numrows01 = $clempagemod->numrows;
    
    $arr ='';
          if($numrows01!=0){
       for($i=0; $i<$numrows01; $i++){
         db_fieldsmemory($result01,$i);
         $arr[$e84_codmod] = $e84_descr;
       }
    }  
          if($numrows01==0){
      echo "Nenhuma modelo para esta agenda";  
    }else{  
         db_select("e84_codmod",$arr,true,1);
    }
?>     
        </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
        <br>
          <input name="alterar" type="submit" value="Entrar selecionada" <?=($numrows01==0?"disabled":"")?> >
        </td> 
            </tr>

   </table>
       </form>   
       </fieldset>
       </td>
     </tr>  
   </table>  
    </center>
<?    
   }  
?>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_empage(){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empage','func_empage.php?funcao_js=parent.js_mostra|e80_codage|e80_data','Pesquisa',true);
}
function js_mostra(codage,data){
  arr = data.split('-');
  
  obj = document.form1;

  obj.e80_data_ano.value = arr[0];
  obj.e80_data_mes.value = arr[1];
  obj.e80_data_dia.value = arr[2];
  obj.e80_data.value = arr[2]+'/'+arr[1]+'/'+arr[0];
 
            obj=document.createElement('input');
            obj.setAttribute('name','pri_codage');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value',codage);
            document.form1.appendChild(obj);

  document.form1.pesquisar.click();
 
  db_iframe_empage.hide();
  
}
</script>
<?
if(isset($atualizar) || isset($adicionar)){
  if($sqlerro == true){
    db_msgbox($erro_msg);
  }

  if(isset($nomearquivo) && isset($atualizar) && $sqlerro == false ){
   
      echo "
        <script>
   function js_emitir(){
           location.href = 'tmp/$nomearquivo';
   }   
   js_emitir();
        </script>
      ";
      
  }
}
?>
