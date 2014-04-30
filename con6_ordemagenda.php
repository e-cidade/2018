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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);


function carrega_destinatario(){
 $texto = "";
 $res = pg_exec("select id_usuario,nome from db_usuarios order by nome ");    
 for ($x=0;$x < pg_numrows($res);$x++){
        db_fieldsmemory($res,$x);
	global $id_usuario,$nome,$cod_destinatario;
	if ($cod_destinatario==$id_usuario){
          $texto .="<option value=$id_usuario selected>$id_usuario $nome </option>";
	} else{
	  $texto .="<option value=$id_usuario>$id_usuario $nome </option>";
	}
 }
 return $texto;

}
function checa_eventos($dia,$mes,$ano){
  /* selecionar as tarefas em ordem de prioridade...
     - como estamos sem prioridades, vamos em ordem de data de finalização mesmo
  */
   global $cod_destinatario;
   $data = "$ano-$mes-$dia";
   $retorno = "";
   $sql = " select o.codordem, 
	            o.dataprev,                               
	            (o.dataprev - current_date::date)::float4 as DL_dias,	
		    nome_modulo,
		    ( case  o.status 
		      when 1 then 'Desenv'		     
		      when 2 then 'Teste'
		      when 3 then 'Retorno/Teste'
    		      when 4 then 'A/C'
		      else 'N/A'
		      end  
    	  	    )::varchar(22) as status,
		    no.nome as  DL_destinatario,
	            substr(o.descricao,1,90)::varchar(90) as DL_descricao
  	      from db_ordem o
                    inner join db_ordemmod on db_ordemmod.codordem = o.codordem
                    inner join db_modulos on db_modulos.id_item = db_ordemmod.id_item
		    inner join db_usuarios u on u.id_usuario = o.id_usuario
		    inner join db_usuarios no on no.id_usuario = o.usureceb
	      where
	 	   o.codordem not in(select codordem from db_ordemfim order by codordem)
                   and (o.dataordem <='$data' and o.dataprev >='$data')
		   and o.status in (1,3)
		   and o.usureceb =  $cod_destinatario
              order by nome_modulo		   
             ";
    $res = @pg_exec($sql);    
    for ($x=0;$x < @pg_numrows($res);$x++){
        db_fieldsmemory($res,$x);
	global $nome_modulo,$codordem,$status;
	$retorno.="<tr><td nowrap>[".$codordem."] ".$nome_modulo."/".$status."</td></tr>";
    }
    return $retorno; // "<tr><td> hj nada  </td></tr>";
}
// --
class calendario{ 
   var $sem;//Array com os dias da semana como índice 
   var $mes;//Array com os meses do ano 
   var $nome_objeto_data;

   function inicializa(){//Atribui valores para $sem e $mes.
       $this->sem=array('Sun'=>1,'Mon'=>2,'Tue'=>3,'Wed'=>4,'Thu'=>5,'Fri'=>6,'Sat'=>7);
       $this->mes=array('1'=>'JANEIRO','2'=>'FEVEREIRO','3'=>'MARÇO',
                        '4'=>'ABRIL','5'=>'MAIO','6'=>'JUNHO','7'=>'JULHO',
			'8'=>'AGOSTO','9'=>'SETEMBRO','10'=>'OUTUBRO','11'=>'NOVEMBRO','12'=>'DEZEMBRO');
   } 

   function aux($i){//Complementa a tabela com espaços em branco 
      $retval=""; 
      for($k=0;$k < $i;$k++){ 
         $retval.="<td width=\"20\">&nbsp;</td>"; 
      } 
      return $retval; 
   }
   function cria($dia,$mes,$ano,$marca=0){
      $this->inicializa(); 
      $verf=date ("d/n/Y", mktime (0,0,0,$mes,$dia,$ano));/*Corrige qualquer data invalida*/ 
      $pieces=explode("/",$verf); 
      $dia=$pieces[0]; 
      $mes=$pieces[1]; 
      $ano=$pieces[2]; 
      $last  =date ("d", mktime (0,0,0,$mes+1,0,$ano));/*Inteiro do ultimo dia do mês*/
      $diasem=date ("D", mktime (0,0,0,$mes,1,$ano));/*String com dia da semana em inglês*/ 
      $str = "";
      if($this->sem[$diasem] != 1){/*Se dia semana diferente de domingo,completa com colunas em branco*/ 
         $valor=$this->sem[$diasem]-1; 
         $str="<tr align=center >".$this->aux($valor); // para dias em branco 
      } 
      for($i=1;$i < ($last+1);$i++){       //; pega todos os dias do mes informado....
         $diasem=date ("D", mktime (0,0,0,$mes,$i,$ano)); 
         if($this->sem[$diasem] == 1){
            $str.="<tr align=\"left\" >";
            $s="$i";
         }else{ 
            $s="$i"; 
         } 
         $data_script = "$ano-$mes-$s";
	
	 $evento = checa_eventos($s,$mes,$ano);       
         $str.="<td> 
	         <table border=1 width=\"100%\" height=\"100%\">
		 <tr height=10px valign=top>	 
	         <td     ";   // começa a escrever o Dia
         if($marca != 0){  // marca o dia atual em laranja
            if($dia == $i){
               $str.= " bgcolor=orange ";  // marcar o dia atual
            }
	 } 
	 $final_de_semana = false;
	 if($this->sem[$diasem] == 1 || $this->sem[$diasem] == 7){
            $str.="  bgcolor=#CCCCCC ";
  	    $final_de_semana = true;
	    $evento = "";
         } 
         $str .="  width=\"25\">
                 <a href=\"\" onclick=\"return janela($s,$mes,$ano);\">$s</a>
              </td>
	      </tr>
                  <!-- aqui vai os eventos do dia 
		       a funcao checa eventos deve escrever no formato <tr><td> evento </td></tr>
 		    -->
	       $evento	       
	      </table>
	      </td>
	      ";
         
         if($this->sem[$diasem] == 7){
            $str.="</tr>"; 
         } 
      } 
      $diasem=date ("D", mktime (0,0,0,$mes,$last,$ano)); 
      if($this->sem[$diasem] != 7){
         $valor=7-$this->sem[$diasem]; 
         $str=$str.$this->aux($valor)."</tr>"; 
      }
      ////////////////////////////////////////////////////////////////
      $destinatario = carrega_destinatario();
     
     $str="       
     <table border=\"1\" width=\"100%\" height=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
     <tr align=\"left\" height=10px>
       <td width=\"100%\" colspan=\"7\" nowrap>
        <form name=form1 action=\"\" method=\"POST\">
        <b>Destinatário </b>
	 <select name=cod_destinatario>
            ".$destinatario."
	</select>
	<input type=submit name=Pesquisar value=Pesquisar>
 
     <input type=button name=agendar value=Agendamento onClick=\"js_agendamento();\">

	</form>
       </td>
     </tr>
     <tr align=\"left\" height=10px>
       <td width=\"100%\" colspan=\"7\" nowrap>

      <FONT SIZE='1' FACE='Verdana' COLOR='black'>
           <a href=\"con6_ordemagenda.php?nome_objeto_data=".$this->nome_objeto_data."&mes_solicitado=".($mes)."&ano_solicitado=".($ano-1)."\"> << </a>
           	   $ano
	   <a href=\"con6_ordemagenda.php?nome_objeto_data=".$this->nome_objeto_data."&mes_solicitado=".($mes)."&ano_solicitado=".($ano+1)."\"> >> </a>   
        </font>	


       
        <FONT SIZE='1' FACE='Verdana' COLOR='black'>
         <a href=\"con6_ordemagenda.php?nome_objeto_data=".$this->nome_objeto_data."&ano_solicitado=".($ano)."&mes_solicitado=".($mes-1)."\"> << </a>
         ".$this->mes[$mes]."
         <a href=\"con6_ordemagenda.php?nome_objeto_data=".$this->nome_objeto_data."&ano_solicitado=".($ano)."&mes_solicitado=".($mes+1)."\"> >> </a>

	</FONT> 
       </td>
     </tr>
     <tr align=\"center\" height=10px>
       <td class=dias width=\"20\"><FONT SIZE='2' FACE='Verdana' COLOR='darkgreen'><b>D</font></td>
       <td class=dias width=\"20\"><FONT SIZE='2' FACE='Verdana' COLOR='darkgreen'><b>Segunda </font></td>
       <td class=dias width=\"20\"><FONT SIZE='2' FACE='Verdana' COLOR='darkgreen'><b>Terça </font></td>
       <td class=dias width=\"20\"><FONT SIZE='2' FACE='Verdana' COLOR='darkgreen'><b>Quarta </font></td>
       <td class=dias width=\"20\"><FONT SIZE='2' FACE='Verdana' COLOR='darkgreen'><b>Quinta </font></td>
       <td class=dias width=\"20\"><FONT SIZE='2' FACE='Verdana' COLOR='darkgreen'><b>Sexta </font></td>
       <td class=dias width=\"20\"><FONT SIZE='2' FACE='Verdana' COLOR='darkgreen'><b>S  </font></td>
       </tr>
       ".$str."
     </table>
     ";
      echo $str; 
   } 
} 

$clcalendario=new calendario; 
if (!isset($mes_solicitado)){
  $mes_solicitado = date("n",db_getsession("DB_datausu"));
}
if (!isset($ano_solicitado)){
  $ano_solicitado = date("Y",db_getsession("DB_datausu"));
}
// $clcalendario->nome_objeto_data = $nome_objeto_data;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr> 
     <td width="360" height="18">&nbsp;</td>
     <td width="263">&nbsp;</td>
     <td width="25">&nbsp;</td>
     <td width="140">&nbsp;</td>
  </tr>
</table>

<?

// cria o calendadio na bela
$clcalendario->cria(date("d",db_getsession("DB_datausu")),date("$mes_solicitado"),date("$ano_solicitado"),1);
?> 
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_agendamento(){  
     js_OpenJanelaIframe('top.corpo','db_iframe_agenda','func_dbagenda.php','Agendamento',true); 
}
</script>