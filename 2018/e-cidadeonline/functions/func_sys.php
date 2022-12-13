<?
//Verificação do Login
function Login(){
 if($_COOKIE["ID_USUARIO"]=="")
 {
  msg_box("ERRO! Faça Login.");
  parent.location("index.php");
 }
}

//criptografar
function enc($string)
{
 if((isset($string)) && (is_string($string)))
  {
    $enc_string = base64_encode($string);
    $enc_string = str_replace("=","",$enc_string);
    $enc_string = strrev($enc_string);
    $md5 = md5($string);
    $enc_string = substr($md5,0,3).$enc_string.substr($md5,-3);
  }
 else
  {
   $enc_string = "ERRO de Segurança! - 11";
  }
   return $enc_string;
}

//descriptografar
function des($string)
{
   if((isset($string)) && (is_string($string)))
    {
         $ini = substr($string,0,3);
         $end = substr($string,-3);
         $des_string = substr($string,0,-3);
         $des_string = substr($des_string,3);
         $des_string = strrev($des_string);
         $des_string = base64_decode($des_string);
         $md5 = md5($des_string);
         $ver = substr($md5,0,3).substr($md5,-3);
         if($ver != $ini.$end)
         {
             $des_string = "ERRO de Segurança! - 21";
         }
    }
   else
    {
        $des_string = "ERRO de Segurança! - 22";
    }
    return $des_string;
}

//Location para outra página
function location($location){
 echo "<script>";
 echo "location='$location'";
 echo "</script>";
}

//Mensagem
function msg_box($msg){
 ?>
  <script>
   alert("<?=$msg?>");
  </script>
 <?
}

function voltar(){
 ?>
  <script>
   history.back();
  </script>
 <?
}

//Input do Formulário
function input($type,$name,$value,$size=null,$maxlength=null,$condicao=null,$javascript=null,$class=null){
  $frm = "<input type='$type' name='$name' value='$value' size='$size' maxlength='$maxlength'";
  if($class !=""){
  $frm .= "class='$class'";
  }
  if($condicao == 1){
   $frm.= "readonly class='disabled' ";
  }
  if($condicao == 2){
   $frm.= " disabled ";
  }
  if($condicao == 3){
   $frm.= " class='valorfinal' ";
  }
  if( ($type == "submit") || ($type == "button") and ($condicao == 1) ){
   $frm.= "readonly class='disabled'";
  }
  $frm.= " $javascript >";

  return $frm;
}

//textarea
function textarea($colunas,$linhas,$nome,$value,$java=null){
 echo "<textarea cols='$colunas' rows='$linhas' name='$nome' $java>$value</textarea>";
}

//data
function FormData($name1,$padrao1,$name2,$padrao2,$name3,$padrao3,$class=""){
 echo input("text",$name1,$padrao1,"2","2",$class)."&nbsp;";
 echo input("text",$name2,$padrao2,"2","2",$class)."&nbsp;";
 echo input("text",$name3,$padrao3,"4","4",$class)."&nbsp;";
 echo "<a href=\"javascript:abre('calendario_00.php?name1=$name1&name2=$name2&name3=$name3&','calendario',200,400,245,150,'no')\"><img src=\"images/calendario.gif\" border=\"0\"></a>";
}

//próximo registro
function NextReg($tabela,$campo){
 $sql = "SELECT MAX($campo) FROM $tabela";
 $query = db_query($sql);
 $dados = pg_fetch_array($query);
 $max = $dados[0]+1;
 return $max;
}

function maiusculo(&$string)
{
$string = strtoupper($string);
$string = str_replace("á","Á",$string);
$string = str_replace("é","É",$string);
$string = str_replace("í","Í",$string);
$string = str_replace("ó","Ó",$string);
$string = str_replace("ú","Ú",$string);
$string = str_replace("â","Â",$string);
$string = str_replace("ê","Ê",$string);
$string = str_replace("ô","Ô",$string);
$string = str_replace("î","Î",$string);
$string = str_replace("û","Û",$string);
$string = str_replace("ã","Ã",$string);
$string = str_replace("õ","Õ",$string);
$string = str_replace("ç","Ç",$string);
$string = str_replace("à","À",$string);
$string = str_replace("è","È",$string);
return $string;
}

Function TiraAcento($string)
{
   set_time_limit(240);
   $acentos = 'áéíóúÁÉÍÓÚàÀÂâÊêôÔüÜïÏöÖñÑãÃõÕçÇªºäÄ\'';
   $letras  = 'AEIOUAEIOUAAAAEEOOUUIIOONNAAOOCCAOAA ';
   $new_string = '';
   for($x=0; $x<strlen($string); $x++)
   {
      $let = substr($string, $x, 1);
      for($y=0; $y<strlen($acentos); $y++)
      {
         if($let==substr($acentos, $y, 1))
         {
            $let=substr($letras, $y, 1);
            break;
         }
      }
      $new_string = $new_string . $let;
   }
   return $new_string;
}

function VerEmBranco($campos){
 $array = explode(";",$campos);
 $qtd = count($array);
 $branco = 0;
 for($x=0;$x<$qtd;$x++){
  if($array[$x]=="")
   $branco = 1;
 }
 return $branco;
}

// data do servidor
 $diasemana[0] = 'domingo';
 $diasemana[1] = 'segunda-feira';
 $diasemana[2] = 'terça-feira';
 $diasemana[3] = 'quarta-feira';
 $diasemana[4] = 'quinta-feira';
 $diasemana[5] = 'sexta-feira';
 $diasemana[6] = 'sábado';
 $mesnome[1] = 'janeiro';
 $mesnome[2] = 'fevereiro';
 $mesnome[3] = 'março';
 $mesnome[4] = 'abril';
 $mesnome[5] = 'maio';
 $mesnome[6] = 'junho';
 $mesnome[7] = 'julho';
 $mesnome[8] = 'agosto';
 $mesnome[9] = 'setembro';
 $mesnome[10] = 'outubro';
 $mesnome[11] = 'novembro';
 $mesnome[12] = 'dezembro';
 
//select
function combo($nome,$tabela,$campo1,$campo2,$where=null,$selected=null,$java=null){
 $sql = "SELECT $campo1, $campo2 FROM $tabela $where";
 $query = db_query($sql);
 $linhas = pg_num_rows($query);
 echo "<select name='$nome' $java>";
 for($i=0;$i<$linhas;$i++){
  $dados = pg_fetch_array($query);
  if($dados[0]==$selected){
  echo "<option value='$dados[0]' selected>$dados[1]</option>";
  }else{
  echo "<option value='$dados[0]'>$dados[1]</option>";
  }
 }
 echo "</select>";
}

//último registro
function Ultimo($tabela,$ordem){
 $sql = "SELECT * FROM $tabela ORDER BY $ordem DESC";
 $query = db_query($sql);
 $dados = pg_fetch_array($query);
 echo $dados[0]." - ".$dados[1]." - ".$dados[2];
}

//total de registros
function Total($tabela){
 $sql = "SELECT count(*) FROM $tabela";
 $query = db_query($sql);
 $dados = pg_fetch_array($query);
 echo str_pad($dados[0],5,0,str_pad_left);
}

//Transforma data
function data($data,$td){
//formato brasileiro
 if($td == "1"){
  $data = substr($data,8,2)."/".substr($data,5,2)."/".substr($data,0,4);
 }
//formato do banco de dados
 if($td == "2"){
 $data = substr($data,6,4)."/".substr($data,3,2)."/".substr($data,0,2);
 }
 return $data;
}

//logs
function logs($log_i_codigo,$usuario,$nome,$ip,$url){
 $log_d_data   = date("Y-m-d");
 $log_h_hora   = date("H:i");
 $sql = "INSERT INTO logs
         (log_i_codigo,log_c_login,log_c_nome,log_c_url,log_c_ip,log_d_data,log_h_hora)
         VALUES
         ($log_i_codigo,'$usuario','$nome','$url','$ip','$log_d_data','$log_h_hora')
        ";
 $query = @db_query($sql);
}

//conta corrente
function ContaCorrente($cliente,$valor){
 $contacorrente_i_codigo = NextReg("contacorrente","contacorrente_i_codigo");
 $cliente_i_codigo       = $cliente;
 $contacorrente_d_data   = date("Y-m-d");
 $contacorrente_f_valor  = $valor;
 $sql = "INSERT INTO contacorrente
         (contacorrente_i_codigo,cliente_i_codigo,contacorrente_d_data,contacorrente_f_valor)
         VALUES
         ($contacorrente_i_codigo,$cliente_i_codigo,'$contacorrente_d_data','$contacorrente_f_valor')
        ";
 $query = @db_query($sql);
}

//saldo anterior
function Saldo($cliente){
 //débito
 $sql1 = "SELECT sum(movimento_f_valorfinal)
         FROM movimentos
         WHERE cliente_i_codigo = $cliente
           AND movimento_c_dbcr = 'D'
        ";
 $query1 = db_query($sql1);
 $dados1 = pg_fetch_array($query1);
 //crédito
 $sql2 = "SELECT sum(movimento_f_valorfinal),
                 sum(movimento_f_juro),
                 sum(movimento_f_desconto)
         FROM movimentos
         WHERE cliente_i_codigo = $cliente
           AND movimento_c_dbcr = 'C'
        ";
 $query2 = db_query($sql2);
 $dados2 = pg_fetch_array($query2);
 //saldo
 $saldo = ($dados1[0]+$dados2[1]) - ($dados2[0]+$dados2[2]);
 echo number_format($saldo,2,',','.');
}

?>
