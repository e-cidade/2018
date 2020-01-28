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
if(isset($atualizar) ){
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
  
  //----------------------------------------
  if($sqlerro==false && $movs !=''){
    $clempagegera->e87_descgera = $e87_descgera;
    $clempagegera->e87_data     = date("Y-m-d",db_getsession("DB_datausu"));
    $clempagegera->e87_hora     = db_hora();
    $clempagegera->e87_dataproc = "$deposito_ano-$deposito_mes-$deposito_dia";
    $clempagegera->incluir(null);
    $erro_msg = $clempagegera->erro_msg;
    if($clempagegera->erro_status==0){
      $sqlerro = true;
    }else{
      $gera = $clempagegera->e87_codgera;
    } 
  }
  //--------------------------
  
  //-----------------------------------
 //inclui na tabela empageconf
    $arr =  split("XX",$movs);
    $tot_valor ='';
    for($i=0; $i<count($arr); $i++ ){
       $mov = $arr[$i];  

               
       //-------------
       //manutenção empagetipo
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
	   $clempagetipo->e83_codtipo   = $codtipo;
	   $clempagetipo->e83_sequencia = $sequencia+1;
	   $clempagetipo->alterar($codtipo);
	   $erro_msg = $clempagetipo->erro_msg;
	   if($clempagetipo->erro_status==0){
		 $sqlerro = true;
	   }     
      }
  //------- 
  

      ///////////////////////////////////////////////////////////////////////////////////////////////////////
     /*       	começa layouts				*/

$sql = "
select  distinct
        e90_codgera,
	e90_codmov,
	c63_banco,
	c63_agencia,
	c63_conta,
	translate(to_char(round(e81_valor,2),'99999999999.99'),'.','') as valor,
	case when  pc63_banco = c63_banco then '01' else '03' end as  lanc,
	coalesce(pc63_banco,'000') as pc63_banco,
	pc63_agencia,
	pc63_conta,
	case when pc63_cnpjcpf = '0' or trim(pc63_cnpjcpf) = '' or pc63_cnpjcpf is null then length(trim(z01_cgccpf)) else length(trim(pc63_cnpjcpf)) end as tam,
	convenio,
	numcgm,
	substr(z01_nome,1,40) as z01_nome,
        case when  pc63_cnpjcpf = '0' or trim(pc63_cnpjcpf) = '' or pc63_cnpjcpf is null then z01_cgccpf else pc63_cnpjcpf end as z01_cgccpf,
	cancelado
        
from 
	(select e90_codgera,
       		e90_codmov,
       		c63_banco,
       		c63_agencia,
		lpad(e83_convenio,5,0) as convenio,
       		c63_conta,
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
     		inner join conplanoreduz on e83_conta = c61_reduz  and c61_anousu=".db_getsession("DB_anousu")."
     		inner join conplanoconta on c63_codcon = c61_codcon and c63_anousu=c61_anousu 
     		left join slip on slip.k17_codigo = e89_codigo
     		left join slipnum on slipnum.k17_codigo = slip.k17_codigo
     		left join empageconfcanc on e88_codmov = e90_codmov
				where e80_instit = " . db_getsession("DB_instit") . "
        ) as x
	left join cgm on z01_numcgm = numcgm
	left join pcfornecon on z01_numcgm = pc63_numcgm
	left join pcforneconpad on pc63_contabanco = pc64_contabanco
	
where e90_codgera = $gera 
order by c63_conta,lanc,e90_codmov		
     ";
//echo $sql;
$result  =  @db_query($sql);
$numrows =  @pg_numrows($result);
//db_criatabela($result);exit;
if($numrows==0){
  $sqlerro =true;
    db_msgbox("Erro. Contate suporte");
}
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	  
    //////LAYOUTS////////////////////////////////////////////////////////////////////////////////////////////////////////
    //// LAYOUT BANCO DO BANRISUL
       $registro = 0;
   
       
//     $nomeinst = "Prefeitura";
       db_fieldsmemory($result,0);

       $data    =  $dtin_dia.$dtin_mes.$dtin_ano;
       $dat_cred = $deposito_dia.$deposito_mes.$deposito_ano;
       /// criar campo de sequencia do arquivo no empagetipo.
       
//       $nomearquivo = basename(tempnam('tmp','Modelo')).".doc";
       $nomearquivo = 'pagtbr'.$e90_codgera.'.doc';
       //indica qual será o nome do arquivo

       $cllayouts_bb->nomearq = "tmp/$nomearquivo";
       if(!is_writable("tmp/")){
	 $sqlerro= true;
	 $erro_msg = 'Sem permissão de gravar o arquivo. Contate suporte.';
       }  
      
      if($e83_codmod == 3 && $sqlerro == false){   

     $cllayouts_bs->nomearq = "tmp/$nomearquivo";
     
     $banco = db_formatar(str_replace('.','',str_replace('-','',$c63_banco)),'s','0',3,'e',0);
     if($banco == '001'){
       $dbanco = db_formatar('BANCO DO BRASIL','s',' ',30,'d',0);
     }else{
       $dbanco = db_formatar('BANRISUL','s',' ',30,'d',0);; 
     }
     $agencia_pre = db_formatar(str_replace('.','',str_replace('-','',$c63_agencia)),'s','0',5,'e',0);
     $conta_pre   = db_formatar(str_replace('.','',str_replace('-','',$c63_conta)),'s','0',10,'e',0);
     
     $numero_dia = 2;
     
     $seq_arq = 1; 
     
     ///// HEADER DO ARQUIVO
     
     $cllayouts_bs->cabec101 = $banco;			/// fixo 	001 - 003
     $cllayouts_bs->cabec102 = str_repeat('0',4) ;	/// zeros	004 - 007
     $cllayouts_bs->cabec103 = '0';			/// zeros 	
     $cllayouts_bs->cabec104 = str_repeat(' ',9) ;
     $cllayouts_bs->cabec105 = '2';			/// 1 - cpf , 2 - cnpj
     $cllayouts_bs->cabec106 = $cgc;
     $cllayouts_bs->cabec107 = db_formatar(substr($convenio,0,5),'s',' ',5,'e',0);
     $cllayouts_bs->cabec108 = str_repeat(' ',15) ;
     $cllayouts_bs->cabec109 = $agencia_pre;
     $cllayouts_bs->cabec110 = '0';
     $cllayouts_bs->cabec111 = '000';
     $cllayouts_bs->cabec112 = $conta_pre;
     $cllayouts_bs->cabec113 = '0';
     $cllayouts_bs->cabec114 = db_formatar(substr(strtoupper($nomeinst),0,30),'s',' ',30,'e',0); 
     $cllayouts_bs->cabec115 = $dbanco; 
     $cllayouts_bs->cabec116 = str_repeat(' ',10) ;
     $cllayouts_bs->cabec117 = '1';
     $cllayouts_bs->cabec118 = date("d").date("m").date("Y");
     $cllayouts_bs->cabec119 = date("H").date("i").date("s");
     $cllayouts_bs->cabec120 = db_formatar($e90_codgera,'s','0',6,'e',0);
     $cllayouts_bs->cabec121 = '020';
     $cllayouts_bs->cabec122 = '00000';
     $cllayouts_bs->cabec123 = str_repeat(' ',20) ;
     $cllayouts_bs->cabec124 = db_formatar($e90_codgera,'s',' ',20,'e',0);
     $cllayouts_bs->cabec125 = str_repeat(' ',29) ;
     $cllayouts_bs->gera_cabecalho();


     
   $seq_header   = 0;
   $xconta       = '';
   $registro     = 1;
   $valor_header = 0;
   $xlanc        = 0;
   
   for($i = 0;$i < $numrows;$i++){
     if($i == 0){
       $pri = "$c63_banco";
     }  
     db_fieldsmemory($result,$i);
     

//     $agencia_pre = db_formatar(str_replace('-','',$c63_agencia),'s','0',5,'e',0);
//     $conta_pre   = db_formatar(str_replace('-','',$c63_conta),'s','0',10,'e',0);

     /////  HEADER DO LOTE
     
     if($xconta != $c63_conta || $lanc != $xlanc){
       $xconta = $c63_conta;
       $xlanc  = $lanc;

       if($pri != $pc63_banco){
	 $pri = $pc63_banco;
       }  
       if($pc63_banco == $banco){
	 $tipopag = "01";
	 $tiposerv = "20";
       }else{
	 $tipopag = "03";
	 $tiposerv = "12";
       }   
      
       
       if($seq_header != 0){       
          ///// TRAILLER DO LOTE
          $cllayouts_bs->roda101 = $banco; 
	  $cllayouts_bs->roda102 = db_formatar($seq_header,'s','0',4,'e',0);
	  $cllayouts_bs->roda103 = '5';
	  $cllayouts_bs->roda104 = str_repeat(' ',9) ;
	  $cllayouts_bs->roda105 = db_formatar($seq_detalhe + 2,'s','0',6,'e',0); 
	  $cllayouts_bs->roda106 = db_formatar(str_replace(',','',str_replace('.','',$valor_header)),'s','0',18,'e',0);
	  $cllayouts_bs->roda107 = str_repeat('0',18) ;
	  $cllayouts_bs->roda108 = str_repeat(' ',171);
	  $cllayouts_bs->roda109 = str_repeat(' ',10) ;
	  $cllayouts_bs->gera_trailer1();
//        echo 'TRAILLER LOTE '.db_formatar(str_replace(',','',str_replace('.','',$valor_header)),'s','0',18,'e',0)."<br>";
	  $valor_header = 0;
          $registro += 1;
	  
	  ///// FINAL DO TRAILLER DO LOTE
       }
       $seq_header  += 1;
       $seq_detalhe  = 0;
       $registro    += 1;
//     echo 'HEADER '.$seq_header."<br>";
       
       $agencia_pre = db_formatar(str_replace('.','',str_replace('-','',$c63_agencia)),'s','0',5,'e',0);
       $conta_pre   = db_formatar(str_replace('.','',str_replace('-','',$c63_conta)),'s','0',10,'e',0);
       
       $cllayouts_bs->cabec201 = $banco; 
       $cllayouts_bs->cabec202 = db_formatar($seq_header,'s','0',4,'e',0);
       $cllayouts_bs->cabec203 = '1';
       $cllayouts_bs->cabec204 = 'C';
       $cllayouts_bs->cabec205 = $tiposerv;;
       $cllayouts_bs->cabec206 = $tipopag; 
       $cllayouts_bs->cabec207 = '020';
       $cllayouts_bs->cabec208 = ' ';
       $cllayouts_bs->cabec209 = '2'; 
       $cllayouts_bs->cabec210 = $cgc;
       $cllayouts_bs->cabec211 = db_formatar(substr($convenio,0,5),'s',' ',5,'e',0);
       $cllayouts_bs->cabec212 = str_repeat(' ',15) ;
       $cllayouts_bs->cabec213 = $agencia_pre;
       $cllayouts_bs->cabec214 = '0000';
       $cllayouts_bs->cabec215 = $conta_pre;
       $cllayouts_bs->cabec216 = ' ';
       $cllayouts_bs->cabec217 = substr(strtoupper($nomeinst),0,30) ; // nome da empresa 
       $cllayouts_bs->cabec218 = str_repeat(' ',40) ;
       $cllayouts_bs->cabec219 = db_formatar(strtoupper($ender),'s',' ',30,'d',0);
       $cllayouts_bs->cabec220 = db_formatar($numero,'s',' ',5,'e',0);
       $cllayouts_bs->cabec221 = str_repeat(' ',15) ;
       $cllayouts_bs->cabec222 = db_formatar(strtoupper($munic),'s',' ',20,'d',0);
       $cllayouts_bs->cabec223 = db_formatar($cep,'s',' ',8,'e',0);
       $cllayouts_bs->cabec224 = $uf;
       $cllayouts_bs->cabec225 = '  ';
       $cllayouts_bs->cabec226 = str_repeat(' ',16) ;
       $cllayouts_bs->gera_cabecalho02();
     }     

     $seq_detalhe += 1;
     $tot_valor    = 0;
     $numero_lote  = 1;
     
//     for($i = 0;$i < $numrows;$i++){
//       db_fieldsmemory($result,$i);

       $tama =  strlen($pc63_conta);
       if($tama>11){
	 $pc63_conta = substr($pc63_conta,($tama-11));
       }
       
       $agencia_fav = db_formatar(str_replace('.','',str_replace('-','',$pc63_agencia)),'s','0',5,'e',0);
      // $agencia_fav =  substr($agencia_fav,0,4);
       $agencia_fav = db_formatar($agencia_fav,'s','0',5,'e',0);

       
       $conta_fav   = db_formatar(str_replace('.','',str_replace('-','',trim($pc63_conta))),'s','0',11,'e',0);

       
       if($tam == 14){
	 $conf = 2;
	 $cgccpf = $z01_cgccpf;
       }elseif($tam == 11){
	 $conf = 1;
	 $cgccpf = db_formatar($z01_cgccpf,'s','0',14,'e',0);
       }else{
	 $conf = 3;
	 $cgccpf= str_repeat('0',14);
       }
       
       $registro += 1;
       
       
//       echo 'DETALHE '.$registro."  VALOR ".db_formatar(str_replace(',','',str_replace('.','',$valor)),'s','0',15,'e',0)."<br>";
       $cllayouts_bs->detalhe01 = $banco; 
       $cllayouts_bs->detalhe02 = db_formatar($seq_header,'s','0',4,'e',0);
       $cllayouts_bs->detalhe03 = '3';
       $cllayouts_bs->detalhe04 = db_formatar($seq_detalhe,'s','0',5,'e',0);
       $cllayouts_bs->detalhe05 = 'A';
       $cllayouts_bs->detalhe06 = '0';
       $cllayouts_bs->detalhe07 = '00';
       $cllayouts_bs->detalhe08 = '010';
       $cllayouts_bs->detalhe09 = $pc63_banco;
       $cllayouts_bs->detalhe10 = $agencia_fav;
       $cllayouts_bs->detalhe11 = '0';
       $cllayouts_bs->detalhe12 = '00'.$conta_fav; 
       $cllayouts_bs->detalhe13 = '0';
       $cllayouts_bs->detalhe14 = db_formatar(  str_replace("-",'',substr($z01_nome,0,30)),'s',' ',30,'d',0) ;   // nome do favorecido 
       $cllayouts_bs->detalhe15 = db_formatar($e90_codmov,'s','0',6,'e',0)."000000000" ; 
       $cllayouts_bs->detalhe16 = '00005';
       $cllayouts_bs->detalhe17 = $dat_cred; 
       $cllayouts_bs->detalhe18 = 'BRL';
       $cllayouts_bs->detalhe19 = str_repeat('0',15) ;
       $cllayouts_bs->detalhe20 = db_formatar(str_replace(',','',str_replace('.','',$valor)),'s','0',15,'e',0) ;  // valor 
       $cllayouts_bs->detalhe21 = str_repeat(' ',20) ;
       $cllayouts_bs->detalhe22 = str_repeat(' ',8) ;
       $cllayouts_bs->detalhe23 = str_repeat(' ',15) ;
       $cllayouts_bs->detalhe24 = str_repeat(' ',5) ;
       $cllayouts_bs->detalhe25 = str_repeat(' ',20) ;
       $cllayouts_bs->detalhe26 = $conf;
       $cllayouts_bs->detalhe27 = $cgccpf;
       $cllayouts_bs->detalhe28 = str_repeat(' ',12) ;
       $cllayouts_bs->detalhe29 = '0';
       $cllayouts_bs->detalhe30 = str_repeat(' ',10) ;
       $cllayouts_bs->gera_corpo();
       $valor_header += $valor;

   }

   
          ///// TRAILLER DO LOTE
          $cllayouts_bs->roda101 = $banco; 
	  
	  $cllayouts_bs->roda102 = db_formatar($seq_header,'s','0',4,'e',0);;
	  $cllayouts_bs->roda103 = '5';
	  $cllayouts_bs->roda104 = str_repeat(' ',9) ;
	  $cllayouts_bs->roda105 = db_formatar($seq_detalhe + 2,'s','0',6,'e',0); 
	  $cllayouts_bs->roda106 = db_formatar(str_replace(',','',str_replace('.','',$valor_header)),'s','0',18,'e',0);
	  $cllayouts_bs->roda107 = str_repeat('0',18) ;
	  $cllayouts_bs->roda108 = str_repeat(' ',171);
	  $cllayouts_bs->roda109 = str_repeat(' ',10) ;
//          echo 'TRAILLER LOTE '.db_formatar(str_replace(',','',str_replace('.','',$valor_header)),'s','0',18,'e',0)."<br>";
	  $cllayouts_bs->gera_trailer1();
	  $valor_header = 0;
          $registro += 1;
	  
	  ///// FINAL DO TRAILLER DO LOTE
	  
          ////  TRAILLER DO ARQUIVO
          $registro += 1;
	  $cllayouts_bs->roda201 = $banco; 
	  $cllayouts_bs->roda202 = '9999';
	  $cllayouts_bs->roda203 = '9';
	  $cllayouts_bs->roda204 = str_repeat(' ',9) ;
	  $cllayouts_bs->roda205 = db_formatar($seq_header,'s','0',6,'e',0);  /// numero de lotes do arquivo
	  $cllayouts_bs->roda206 = db_formatar($registro,'s','0',6,'e',0);
	  $cllayouts_bs->roda207 = str_repeat('0',6) ;
	  $cllayouts_bs->roda208 = str_repeat(' ',205) ;
          $cllayouts_bs->gera_trailer2();
//  exit; 
          $cllayouts_bs->gera();
	
      }else if($sqlerro==false){
	$sqlerro  = true;
	$erro_msg = "O tipo selecionado, não possui layout cadastrado para pagar no Banco Banrisul.";
      }
//      die("final");
   db_fim_transacao($sqlerro);
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
<table  border="0" cellspacing="0" cellpadding="0" height='100%'>
  <tr> 
    <td  height='100%' align="left" valign="top" bgcolor="#CCCCCC"> 
   <?
   
   $clrotulo = new rotulocampo;
   $clrotulo->label("e80_data");
   $clrotulo->label("e84_codmod");
//sempre que ja existir agenda entra nesta opcao  
	include(modification("forms/db_frmempageconflay03.php"));
?>
    </td>
  </tr>
</table>
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
if(isset($atualizar) ){
  if($sqlerro == true){
    db_msgbox($erro_msg);
  }

  if(isset($nomearquivo)   && $sqlerro==false){
      echo "
        <script>
	 function js_emitir(){
  //          js_OpenJanelaIframe('CurrentWindow.corpo','e','tmp/$nomearquivo','Pesquisa',false,0,0,0,0 );

//  jan = window.open('tmp/$nomearquivo','',width=0,height=0,scrollbars=0,location=0 ');
  jan = window.open('tmp/$nomearquivo','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
	 }   
	 js_emitir();
        </script>
      ";
  }
}
?>