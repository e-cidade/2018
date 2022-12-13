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


//WebSeller
//Java Script

/**
 * TODAS AS FUNÇÕES JAVASCRIPT TERÃO QUE SER CRIADAS NO ARQUIVO /SCRIPTS/WEBSELLER.JS 
 * 
 * //
//formata mascara da hora
//"OnKeyUp=\"mascara_hora(this.value,4)\""
// x = tem q identifiar qual posição do objeto ou o nome do objeto
?>
<script language="JavaScript" type="text/javascript" src="scripts/webseller.js">

/**
 *A função gera um número aleatório dentro de um intervalo de tempo determinado.
 *Exemplo:
 *alert(rand(1, 5)); // 1 ou 2 ou 3 ou 4 ou 5
 */
/*function rand(min, max) {
	return Math.floor((Math.random() * (max - min + 1)) + min);
}*/

?>


<script language="JavaScript" type="text/javascript" src="scripts/webseller.js">
/*function show_calendarsaude(obj,shutdown_function,especmed) {
//#01#//show_calendar
//#10#//Funcão para mostrar o calendário do sistema
//#20#// shutdown_function: função ao ser executada no final da execução do calendário
//#15#//show_calendar()

	if(PosMouseY >= 270)
	  PosMouseY = 270;
	if(PosMouseX >= 600)
	  PosMouseX = 600;

    js_OpenJanelaIframe('','iframe_data_'+obj,'func_calendariosaude.php?nome_objeto_data='+obj+'&shutdown_function='+shutdown_function+'&sd27_i_codigo='+especmed+'&fechar=true','Calendário',true,PosMouseY,PosMouseX,250,270);

}*/
/*function show_calendarexames(obj,shutdown_function,especmed) {
//#01#//show_calendar
//#10#//Funcão para mostrar o calendário do sistema
//#20#// shutdown_function: função ao ser executada no final da execução do calendário
//#15#//show_calendar()

	if(PosMouseY >= 270)
	  PosMouseY = 270;
	if(PosMouseX >= 600)
	  PosMouseX = 600;

    js_OpenJanelaIframe('','iframe_data_'+obj,'func_calendarioexames.php?nome_objeto_data='+obj+'&shutdown_function='+shutdown_function+'&s111_i_codigo='+especmed+'&fechar=true','Calendário',true,PosMouseY,PosMouseX,250,270);

}*/


/*
  Função para alinhar caracters
  alert('Retorno: ' + strPad('5',10,'0','L'));
*/
/*function strPad(palavra, casas, carac, dir) {
  //dir = 'R' => Right; dir = 'L' => Left;
  if(palavra == null || palavra == '') palavra = 0;
  var ret = '';
  var nro = casas - (palavra.length);
  for(var i = 0; i < nro; i++) ret += carac;
  if(dir == 'R')
    ret = palavra + ret;
  else if(dir == 'L')
    ret += palavra;
  return ret;
}/*

/*
* Coloca mascara na hora
* @hora = valor atuald o campo "this.value"
* @x    = nome do campo('sd29_c_hora') ou número do index
* @event= evento.
* Ex:  OnKeyUp=mascara_hora(this.value,'sd29_c_hora',event)
*/
/*function mascara_hora(hora,x,event){
 var myhora = '';
 myhora = myhora + hora;
 if( event == undefined ){
	 if( myhora.length == 2){
	  myhora = myhora + ':';
	  document.form1[x].value = myhora;
	 }
 }else{
	 //k != 8 -- backspace
	 k = event.keyCode;

	 if( k!=8 && myhora.length == 2){
	  myhora = myhora + ':';
	  document.form1[x].value = myhora;
	 }
 }
 if(myhora.length == 5){
  verifica_hora(x);
 }
}
function verifica_hora(x){
 hrs = (document.form1[x].value.substring(0,2));
 min = (document.form1[x].value.substring(3,5));
 situacao = "";
 // verifica hora
 if ( (hrs < 00 ) || (hrs > 23) || ( min < 00) || ( min > 59) ) {
  alert("E R R O !!!\n\nHora inválida!\nPreencha corretamente o campo!");
  document.form1[x].value="";
  document.form1[x].focus();
 }
}*/

/*
funcão preenche uma string com algum caracter
str = string a ser preenchida
caracter = caracter para preencher o restante da string
tamanho = tamanho a string
direcao = L - left, R - right
*/
/*function preenche( str, caracter, tamanho, direcao ){
     var iLen = String(str).length;
     direcao = direcao.toUpperCase();
     for( ; iLen < tamanho; iLen ++ ){
          if( direcao == 'L' ){
               str = caracter+str;
          }else{
               str = str+caracter;
          }
     }
     return str;
}*/

/*
  verifica se @data se encontra entre @inicio e @fim
  @data  tipo:data
  @inicio tipo:data
  @fim  tipo:data
  Formato de entrada da data= YYYY-MM-DD
*/
/*function js_validata(datamat,inicio,fim){
 var data1 = inicio.substr(0,4)+''+inicio.substr(5,2)+''+inicio.substr(8,2);
 var data2 = fim.substr(0,4)+''+fim.substr(5,2)+''+fim.substr(8,2);
 var datamat  = datamat.substr(0,4)+''+datamat.substr(5,2)+''+datamat.substr(8,2);
 if(parseInt(datamat)>=parseInt(data1) && parseInt(datamat)<=parseInt(data2)){
  ok = true;
 }else{
  ok = false;
 }
 return ok;
}
function RetiraInvalido(string,expres){
 tamanhostring = string.length;
 new_string = '';
 for(x=0;x<tamanhostring;x++){
  let = string.substr(x,1);
  if(let.match(expres)){
   let = "";
  }
  new_string = new_string+let;
 }
 return new_string;
}*/

/*function js_ValidaCamposEdu(obj, tipo, nome, aceitanulo, maiusculo, evt) {
 //#01#//js_ValidaCamposEdu
 //#10#//Funcao para validar o conteúdo do campo quando digitado no formulário
 //#15#//js_ValidaCamposEdu(obj,tipo,nome,aceitanulo,maiusculo,evt);
 //#20#//objeto      : Nome do objeto do formulário
 //#20#//tipo        : Tipo de consistencia do objeto gerado
 //#20#//              1 - Letras e espaço  = RegExp("[^A-Za-zà-úÁ-ÚüÜ ]+")
 //#20#//              2 - Números, Letras, espaço, ª, º e traço = RegExp("[^A-Za-z0-9à-úÁ-ÚüÜ ªº-]+")
 //#20#//              3 - Números, Letras, espaço, ponto, virgula, barra, ª, º e traço = RegExp("[^A-Za-z0-9à-úÁ-ÚüÜ \.,\/ªº-]+")
 //#20#//              4 - Números, Letras(sem acentuação e cedilha), ponto, arroba, sublinha e traço = RegExp("[A-Za-z0-9\.@_-]+")
 //#20#//Nome        : Descrição do campo para mensagem de erro
 //#20#//Aceitanulo  : Se aceita o campo nulo ou não: true = aceita false = não aceita
 //#20#//Maiusculo   : Se campo deve ser maiusculo, quando digita o sistema troca para maiusculo
 //#20#//evt         : este parâmetro não deve ser passado para a função, pois é automático do javascript
 evt = (evt)?evt:(event)?event:'';
 if(maiusculo=='t'){
  var maiusc = new String(obj.value);
  obj.value = maiusc.toUpperCase();
 }
 if(tipo==1){
  var expr = new RegExp("[^A-Zà-úÁ-ÚüÜ ]+");
  if(obj.value.match(expr)){
   alert(nome+" deve ser preenchido somente com Letras!");
   obj.value = RetiraInvalido(obj.value,expr);
   obj.focus();
  }
 }else if(tipo==2){
  var expr = new RegExp("[^A-Zà-úÁ-ÚüÜ0-9 ªº-]+");
  if(obj.value.match(expr)){
   alert(nome+" deve ser preenchido somente com Números, Letras, espaço, ª, º e traço ");
   obj.value = RetiraInvalido(obj.value,expr);
   obj.focus();
  }
 }else if(tipo==3){
  var expr = new RegExp("[^A-Zà-úÁ-ÚüÜ0-9 \.,\/ªº-]+");
  if (obj.value.match(expr)) {
   alert(nome+" deve ser preenchido somente com Números, Letras, espaço, ponto, virgula, barra, ª, º e traço!");
   obj.value = RetiraInvalido(obj.value,expr);
   obj.focus();
  }
 }else if(tipo==4){
  var expr = new RegExp("[^A-Z0-9\.@_-]+");
  if (obj.value.match(expr)) {
   alert(nome+" deve ser preenchido somente com Números, Letras(sem acentuação e cedilha), ponto, arroba, sublinha e traço!");
   obj.value = RetiraInvalido(obj.value,expr);
   obj.focus();
  }
 }
 js_putInputValue(obj.name, obj.value);
 return;
}
function jsValidaEmail(email,label){
 if(email!=""){
  var expr = /\./g;
  var expr1 = /@/g;
  if(!email.match(expr) || !email.match(expr1)){
   alert("E-mail deve possuir arroba e ponto!");
   return false;
  }
  if(email.match(expr1)){
   var expr2 = /[A-Za-z0-9]@[A-Za-z0-9]/g;
   if(!email.match(expr2)){
    alert(label+" deve possuir caracteres alfanuméricos antes e depois do arroba!");
    return false;
   }
  }
 }else{
  return true;
 }
}*/
</script>