<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: acordos
//CLASSE DA ENTIDADE acordo
class cl_acordo {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $ac16_sequencial = 0;
   var $ac16_acordosituacao = 0;
   var $ac16_coddepto = 0;
   var $ac16_numero = null;
   var $ac16_anousu = 0;
   var $ac16_dataassinatura_dia = null;
   var $ac16_dataassinatura_mes = null;
   var $ac16_dataassinatura_ano = null;
   var $ac16_dataassinatura = null;
   var $ac16_contratado = 0;
   var $ac16_datainicio_dia = null;
   var $ac16_datainicio_mes = null;
   var $ac16_datainicio_ano = null;
   var $ac16_datainicio = null;
   var $ac16_datafim_dia = null;
   var $ac16_datafim_mes = null;
   var $ac16_datafim_ano = null;
   var $ac16_datafim = null;
   var $ac16_resumoobjeto = null;
   var $ac16_objeto = null;
   var $ac16_instit = 0;
   var $ac16_acordocomissao = 0;
   var $ac16_lei = null;
   var $ac16_acordogrupo = 0;
   var $ac16_origem = 0;
   var $ac16_qtdrenovacao = 0;
   var $ac16_tipounidtempo = 0;
   var $ac16_deptoresponsavel = 0;
   var $ac16_numeroprocesso = null;
   var $ac16_periodocomercial = 'f';
   var $ac16_qtdperiodo = 0;
   var $ac16_tipounidtempoperiodo = 0;
   var $ac16_acordocategoria = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ac16_sequencial = int4 = Sequencial
                 ac16_acordosituacao = int4 = Acordo Situa��o
                 ac16_coddepto = int4 = C�digo Departamento
                 ac16_numero = varchar(60) = N�mero
                 ac16_anousu = int4 = Ano Exerc�cio
                 ac16_dataassinatura = date = Data da Assinatura
                 ac16_contratado = int4 = Contratado
                 ac16_datainicio = date = Data de In�cio
                 ac16_datafim = date = Data de Fim
                 ac16_resumoobjeto = varchar(50) = Resumo Objeto
                 ac16_objeto = text = Objeto do Contrato
                 ac16_instit = int4 = Institui��o
                 ac16_acordocomissao = int4 = Acordo Comiss�o
                 ac16_lei = varchar(60) = Lei
                 ac16_acordogrupo = int4 = Acordo Grupo
                 ac16_origem = int4 = Origem
                 ac16_qtdrenovacao = float8 = Quantidade de Renova��o
                 ac16_tipounidtempo = int4 = Unidade do Tempo
                 ac16_deptoresponsavel = int4 = Departamento Respons�vel
                 ac16_numeroprocesso = varchar(60) = Numero do Processo
                 ac16_periodocomercial = bool = Per�odo Comercial
                 ac16_qtdperiodo = float8 = Quantidade do Per�odo de Vig�ncia
                 ac16_tipounidtempoperiodo = int4 = Tipo de Per�odo de Vig�ncia
                 ac16_acordocategoria = int4 = Acordo Categoria
                 ";
   //funcao construtor da classe
   function cl_acordo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordo");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->ac16_sequencial = ($this->ac16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_sequencial"]:$this->ac16_sequencial);
       $this->ac16_acordosituacao = ($this->ac16_acordosituacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_acordosituacao"]:$this->ac16_acordosituacao);
       $this->ac16_coddepto = ($this->ac16_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_coddepto"]:$this->ac16_coddepto);
       $this->ac16_numero = ($this->ac16_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_numero"]:$this->ac16_numero);
       $this->ac16_anousu = ($this->ac16_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_anousu"]:$this->ac16_anousu);
       if($this->ac16_dataassinatura == ""){
         $this->ac16_dataassinatura_dia = ($this->ac16_dataassinatura_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_dataassinatura_dia"]:$this->ac16_dataassinatura_dia);
         $this->ac16_dataassinatura_mes = ($this->ac16_dataassinatura_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_dataassinatura_mes"]:$this->ac16_dataassinatura_mes);
         $this->ac16_dataassinatura_ano = ($this->ac16_dataassinatura_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_dataassinatura_ano"]:$this->ac16_dataassinatura_ano);
         if($this->ac16_dataassinatura_dia != ""){
            $this->ac16_dataassinatura = $this->ac16_dataassinatura_ano."-".$this->ac16_dataassinatura_mes."-".$this->ac16_dataassinatura_dia;
         }
       }
       $this->ac16_contratado = ($this->ac16_contratado == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_contratado"]:$this->ac16_contratado);
       if($this->ac16_datainicio == ""){
         $this->ac16_datainicio_dia = ($this->ac16_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_datainicio_dia"]:$this->ac16_datainicio_dia);
         $this->ac16_datainicio_mes = ($this->ac16_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_datainicio_mes"]:$this->ac16_datainicio_mes);
         $this->ac16_datainicio_ano = ($this->ac16_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_datainicio_ano"]:$this->ac16_datainicio_ano);
         if($this->ac16_datainicio_dia != ""){
            $this->ac16_datainicio = $this->ac16_datainicio_ano."-".$this->ac16_datainicio_mes."-".$this->ac16_datainicio_dia;
         }
       }
       if($this->ac16_datafim == ""){
         $this->ac16_datafim_dia = ($this->ac16_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_datafim_dia"]:$this->ac16_datafim_dia);
         $this->ac16_datafim_mes = ($this->ac16_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_datafim_mes"]:$this->ac16_datafim_mes);
         $this->ac16_datafim_ano = ($this->ac16_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_datafim_ano"]:$this->ac16_datafim_ano);
         if($this->ac16_datafim_dia != ""){
            $this->ac16_datafim = $this->ac16_datafim_ano."-".$this->ac16_datafim_mes."-".$this->ac16_datafim_dia;
         }
       }
       $this->ac16_resumoobjeto = ($this->ac16_resumoobjeto == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_resumoobjeto"]:$this->ac16_resumoobjeto);
       $this->ac16_objeto = ($this->ac16_objeto == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_objeto"]:$this->ac16_objeto);
       $this->ac16_instit = ($this->ac16_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_instit"]:$this->ac16_instit);
       $this->ac16_acordocomissao = ($this->ac16_acordocomissao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_acordocomissao"]:$this->ac16_acordocomissao);
       $this->ac16_lei = ($this->ac16_lei == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_lei"]:$this->ac16_lei);
       $this->ac16_acordogrupo = ($this->ac16_acordogrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_acordogrupo"]:$this->ac16_acordogrupo);
       $this->ac16_origem = ($this->ac16_origem == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_origem"]:$this->ac16_origem);
       $this->ac16_qtdrenovacao = ($this->ac16_qtdrenovacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_qtdrenovacao"]:$this->ac16_qtdrenovacao);
       $this->ac16_tipounidtempo = ($this->ac16_tipounidtempo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_tipounidtempo"]:$this->ac16_tipounidtempo);
       $this->ac16_deptoresponsavel = ($this->ac16_deptoresponsavel == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_deptoresponsavel"]:$this->ac16_deptoresponsavel);
       $this->ac16_numeroprocesso = ($this->ac16_numeroprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_numeroprocesso"]:$this->ac16_numeroprocesso);
       $this->ac16_periodocomercial = ($this->ac16_periodocomercial == "f"?@$GLOBALS["HTTP_POST_VARS"]["ac16_periodocomercial"]:$this->ac16_periodocomercial);
       $this->ac16_qtdperiodo = ($this->ac16_qtdperiodo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_qtdperiodo"]:$this->ac16_qtdperiodo);
       $this->ac16_tipounidtempoperiodo = ($this->ac16_tipounidtempoperiodo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_tipounidtempoperiodo"]:$this->ac16_tipounidtempoperiodo);
       $this->ac16_acordocategoria = ($this->ac16_acordocategoria == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_acordocategoria"]:$this->ac16_acordocategoria);
     }else{
       $this->ac16_sequencial = ($this->ac16_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac16_sequencial"]:$this->ac16_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac16_sequencial){
      $this->atualizacampos();
     if($this->ac16_acordosituacao == null ){
       $this->erro_sql = " Campo Acordo Situa��o nao Informado.";
       $this->erro_campo = "ac16_acordosituacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_coddepto == null ){
       $this->erro_sql = " Campo C�digo Departamento nao Informado.";
       $this->erro_campo = "ac16_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_numero == null ){
       $this->erro_sql = " Campo N�mero nao Informado.";
       $this->erro_campo = "ac16_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_anousu == null ){
       $this->erro_sql = " Campo Ano Exerc�cio nao Informado.";
       $this->erro_campo = "ac16_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_dataassinatura == null ){
       $this->ac16_dataassinatura = "null";
     }
     if($this->ac16_contratado == null ){
       $this->erro_sql = " Campo Contratado nao Informado.";
       $this->erro_campo = "ac16_contratado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_datainicio == null ){
       $this->erro_sql = " Campo Data de In�cio nao Informado.";
       $this->erro_campo = "ac16_datainicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_datafim == null ){
       $this->erro_sql = " Campo Data de Fim nao Informado.";
       $this->erro_campo = "ac16_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_resumoobjeto == null ){
       $this->erro_sql = " Campo Resumo Objeto nao Informado.";
       $this->erro_campo = "ac16_resumoobjeto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_objeto == null ){
       $this->erro_sql = " Campo Objeto do Contrato nao Informado.";
       $this->erro_campo = "ac16_objeto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_instit == null ){
       $this->erro_sql = " Campo Institui��o nao Informado.";
       $this->erro_campo = "ac16_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_acordocomissao == null ){
       $this->erro_sql = " Campo Acordo Comiss�o nao Informado.";
       $this->erro_campo = "ac16_acordocomissao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_lei == null ){
       $this->erro_sql = " Campo Lei nao Informado.";
       $this->erro_campo = "ac16_lei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_acordogrupo == null ){
       $this->erro_sql = " Campo Acordo Grupo nao Informado.";
       $this->erro_campo = "ac16_acordogrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_origem == null ){
       $this->erro_sql = " Campo Origem nao Informado.";
       $this->erro_campo = "ac16_origem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_qtdrenovacao == null ){
       $this->erro_sql = " Campo Quantidade de Renova��o nao Informado.";
       $this->erro_campo = "ac16_qtdrenovacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_tipounidtempo == null ){
       $this->erro_sql = " Campo Unidade do Tempo nao Informado.";
       $this->erro_campo = "ac16_tipounidtempo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_deptoresponsavel == null ){
       $this->erro_sql = " Campo Departamento Respons�vel nao Informado.";
       $this->erro_campo = "ac16_deptoresponsavel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_periodocomercial == null ){
       $this->erro_sql = " Campo Per�odo Comercial nao Informado.";
       $this->erro_campo = "ac16_periodocomercial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac16_qtdperiodo == null ){
       $this->ac16_qtdperiodo = "0";
     }
     if($this->ac16_tipounidtempoperiodo == null ){
       $this->ac16_tipounidtempoperiodo = "0";
     }
     if($this->ac16_acordocategoria == null ){
       $this->ac16_acordocategoria = "0";
     }
     if($ac16_sequencial == "" || $ac16_sequencial == null ){
       $result = db_query("select nextval('acordo_ac16_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordo_ac16_sequencial_seq do campo: ac16_sequencial";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ac16_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from acordo_ac16_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac16_sequencial)){
         $this->erro_sql = " Campo ac16_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac16_sequencial = $ac16_sequencial;
       }
     }
     if(($this->ac16_sequencial == null) || ($this->ac16_sequencial == "") ){
       $this->erro_sql = " Campo ac16_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordo(
                                       ac16_sequencial
                                      ,ac16_acordosituacao
                                      ,ac16_coddepto
                                      ,ac16_numero
                                      ,ac16_anousu
                                      ,ac16_dataassinatura
                                      ,ac16_contratado
                                      ,ac16_datainicio
                                      ,ac16_datafim
                                      ,ac16_resumoobjeto
                                      ,ac16_objeto
                                      ,ac16_instit
                                      ,ac16_acordocomissao
                                      ,ac16_lei
                                      ,ac16_acordogrupo
                                      ,ac16_origem
                                      ,ac16_qtdrenovacao
                                      ,ac16_tipounidtempo
                                      ,ac16_deptoresponsavel
                                      ,ac16_numeroprocesso
                                      ,ac16_periodocomercial
                                      ,ac16_qtdperiodo
                                      ,ac16_tipounidtempoperiodo
                                      ,ac16_acordocategoria
                       )
                values (
                                $this->ac16_sequencial
                               ,$this->ac16_acordosituacao
                               ,$this->ac16_coddepto
                               ,'$this->ac16_numero'
                               ,$this->ac16_anousu
                               ,".($this->ac16_dataassinatura == "null" || $this->ac16_dataassinatura == ""?"null":"'".$this->ac16_dataassinatura."'")."
                               ,$this->ac16_contratado
                               ,".($this->ac16_datainicio == "null" || $this->ac16_datainicio == ""?"null":"'".$this->ac16_datainicio."'")."
                               ,".($this->ac16_datafim == "null" || $this->ac16_datafim == ""?"null":"'".$this->ac16_datafim."'")."
                               ,'$this->ac16_resumoobjeto'
                               ,'$this->ac16_objeto'
                               ,$this->ac16_instit
                               ,$this->ac16_acordocomissao
                               ,'$this->ac16_lei'
                               ,$this->ac16_acordogrupo
                               ,$this->ac16_origem
                               ,$this->ac16_qtdrenovacao
                               ,$this->ac16_tipounidtempo
                               ,$this->ac16_deptoresponsavel
                               ,'$this->ac16_numeroprocesso'
                               ,'$this->ac16_periodocomercial'
                               ,$this->ac16_qtdperiodo
                               ,$this->ac16_tipounidtempoperiodo
                               ,$this->ac16_acordocategoria
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acordo ($this->ac16_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acordo j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acordo ($this->ac16_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac16_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac16_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16116,'$this->ac16_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2828,16116,'','".AddSlashes(pg_result($resaco,0,'ac16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,16117,'','".AddSlashes(pg_result($resaco,0,'ac16_acordosituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,16118,'','".AddSlashes(pg_result($resaco,0,'ac16_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,16119,'','".AddSlashes(pg_result($resaco,0,'ac16_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,16120,'','".AddSlashes(pg_result($resaco,0,'ac16_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,16121,'','".AddSlashes(pg_result($resaco,0,'ac16_dataassinatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,16122,'','".AddSlashes(pg_result($resaco,0,'ac16_contratado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,16123,'','".AddSlashes(pg_result($resaco,0,'ac16_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,16124,'','".AddSlashes(pg_result($resaco,0,'ac16_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,16125,'','".AddSlashes(pg_result($resaco,0,'ac16_resumoobjeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,16126,'','".AddSlashes(pg_result($resaco,0,'ac16_objeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,16127,'','".AddSlashes(pg_result($resaco,0,'ac16_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,16129,'','".AddSlashes(pg_result($resaco,0,'ac16_acordocomissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,16130,'','".AddSlashes(pg_result($resaco,0,'ac16_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,16211,'','".AddSlashes(pg_result($resaco,0,'ac16_acordogrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,16233,'','".AddSlashes(pg_result($resaco,0,'ac16_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,16660,'','".AddSlashes(pg_result($resaco,0,'ac16_qtdrenovacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,16659,'','".AddSlashes(pg_result($resaco,0,'ac16_tipounidtempo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,18033,'','".AddSlashes(pg_result($resaco,0,'ac16_deptoresponsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,18487,'','".AddSlashes(pg_result($resaco,0,'ac16_numeroprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,19736,'','".AddSlashes(pg_result($resaco,0,'ac16_periodocomercial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,19928,'','".AddSlashes(pg_result($resaco,0,'ac16_qtdperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,19927,'','".AddSlashes(pg_result($resaco,0,'ac16_tipounidtempoperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2828,19926,'','".AddSlashes(pg_result($resaco,0,'ac16_acordocategoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ac16_sequencial=null) {
      $this->atualizacampos();
     $sql = " update acordo set ";
     $virgula = "";
     if(trim($this->ac16_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_sequencial"])){
       $sql  .= $virgula." ac16_sequencial = $this->ac16_sequencial ";
       $virgula = ",";
       if(trim($this->ac16_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ac16_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac16_acordosituacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordosituacao"])){
       $sql  .= $virgula." ac16_acordosituacao = $this->ac16_acordosituacao ";
       $virgula = ",";
       if(trim($this->ac16_acordosituacao) == null ){
         $this->erro_sql = " Campo Acordo Situa��o nao Informado.";
         $this->erro_campo = "ac16_acordosituacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac16_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_coddepto"])){
       $sql  .= $virgula." ac16_coddepto = $this->ac16_coddepto ";
       $virgula = ",";
       if(trim($this->ac16_coddepto) == null ){
         $this->erro_sql = " Campo C�digo Departamento nao Informado.";
         $this->erro_campo = "ac16_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac16_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_numero"])){
       $sql  .= $virgula." ac16_numero = '$this->ac16_numero' ";
       $virgula = ",";
       if(trim($this->ac16_numero) == null ){
         $this->erro_sql = " Campo N�mero nao Informado.";
         $this->erro_campo = "ac16_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac16_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_anousu"])){
       $sql  .= $virgula." ac16_anousu = $this->ac16_anousu ";
       $virgula = ",";
       if(trim($this->ac16_anousu) == null ){
         $this->erro_sql = " Campo Ano Exerc�cio nao Informado.";
         $this->erro_campo = "ac16_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac16_dataassinatura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_dataassinatura_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac16_dataassinatura_dia"] !="") ){
       $sql  .= $virgula." ac16_dataassinatura = '$this->ac16_dataassinatura' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_dataassinatura_dia"])){
         $sql  .= $virgula." ac16_dataassinatura = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ac16_contratado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_contratado"])){
       $sql  .= $virgula." ac16_contratado = $this->ac16_contratado ";
       $virgula = ",";
       if(trim($this->ac16_contratado) == null ){
         $this->erro_sql = " Campo Contratado nao Informado.";
         $this->erro_campo = "ac16_contratado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac16_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac16_datainicio_dia"] !="") ){
       $sql  .= $virgula." ac16_datainicio = '$this->ac16_datainicio' ";
       $virgula = ",";
       if(trim($this->ac16_datainicio) == null ){
         $this->erro_sql = " Campo Data de In�cio nao Informado.";
         $this->erro_campo = "ac16_datainicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_datainicio_dia"])){
         $sql  .= $virgula." ac16_datainicio = null ";
         $virgula = ",";
         if(trim($this->ac16_datainicio) == null ){
           $this->erro_sql = " Campo Data de In�cio nao Informado.";
           $this->erro_campo = "ac16_datainicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ac16_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac16_datafim_dia"] !="") ){
       $sql  .= $virgula." ac16_datafim = '$this->ac16_datafim' ";
       $virgula = ",";
       if(trim($this->ac16_datafim) == null ){
         $this->erro_sql = " Campo Data de Fim nao Informado.";
         $this->erro_campo = "ac16_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_datafim_dia"])){
         $sql  .= $virgula." ac16_datafim = null ";
         $virgula = ",";
         if(trim($this->ac16_datafim) == null ){
           $this->erro_sql = " Campo Data de Fim nao Informado.";
           $this->erro_campo = "ac16_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ac16_resumoobjeto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_resumoobjeto"])){
       $sql  .= $virgula." ac16_resumoobjeto = '$this->ac16_resumoobjeto' ";
       $virgula = ",";
       if(trim($this->ac16_resumoobjeto) == null ){
         $this->erro_sql = " Campo Resumo Objeto nao Informado.";
         $this->erro_campo = "ac16_resumoobjeto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac16_objeto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_objeto"])){
       $sql  .= $virgula." ac16_objeto = '$this->ac16_objeto' ";
       $virgula = ",";
       if(trim($this->ac16_objeto) == null ){
         $this->erro_sql = " Campo Objeto do Contrato nao Informado.";
         $this->erro_campo = "ac16_objeto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac16_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_instit"])){
       $sql  .= $virgula." ac16_instit = $this->ac16_instit ";
       $virgula = ",";
       if(trim($this->ac16_instit) == null ){
         $this->erro_sql = " Campo Institui��o nao Informado.";
         $this->erro_campo = "ac16_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac16_acordocomissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordocomissao"])){
       $sql  .= $virgula." ac16_acordocomissao = $this->ac16_acordocomissao ";
       $virgula = ",";
       if(trim($this->ac16_acordocomissao) == null ){
         $this->erro_sql = " Campo Acordo Comiss�o nao Informado.";
         $this->erro_campo = "ac16_acordocomissao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac16_lei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_lei"])){
       $sql  .= $virgula." ac16_lei = '$this->ac16_lei' ";
       $virgula = ",";
       if(trim($this->ac16_lei) == null ){
         $this->erro_sql = " Campo Lei nao Informado.";
         $this->erro_campo = "ac16_lei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac16_acordogrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordogrupo"])){
       $sql  .= $virgula." ac16_acordogrupo = $this->ac16_acordogrupo ";
       $virgula = ",";
       if(trim($this->ac16_acordogrupo) == null ){
         $this->erro_sql = " Campo Acordo Grupo nao Informado.";
         $this->erro_campo = "ac16_acordogrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac16_origem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_origem"])){
       $sql  .= $virgula." ac16_origem = $this->ac16_origem ";
       $virgula = ",";
       if(trim($this->ac16_origem) == null ){
         $this->erro_sql = " Campo Origem nao Informado.";
         $this->erro_campo = "ac16_origem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac16_qtdrenovacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_qtdrenovacao"])){
       $sql  .= $virgula." ac16_qtdrenovacao = $this->ac16_qtdrenovacao ";
       $virgula = ",";
       if(trim($this->ac16_qtdrenovacao) == null ){
         $this->erro_sql = " Campo Quantidade de Renova��o nao Informado.";
         $this->erro_campo = "ac16_qtdrenovacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac16_tipounidtempo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_tipounidtempo"])){
       $sql  .= $virgula." ac16_tipounidtempo = $this->ac16_tipounidtempo ";
       $virgula = ",";
       if(trim($this->ac16_tipounidtempo) == null ){
         $this->erro_sql = " Campo Unidade do Tempo nao Informado.";
         $this->erro_campo = "ac16_tipounidtempo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac16_deptoresponsavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_deptoresponsavel"])){
       $sql  .= $virgula." ac16_deptoresponsavel = $this->ac16_deptoresponsavel ";
       $virgula = ",";
       if(trim($this->ac16_deptoresponsavel) == null ){
         $this->erro_sql = " Campo Departamento Respons�vel nao Informado.";
         $this->erro_campo = "ac16_deptoresponsavel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac16_numeroprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_numeroprocesso"])){
       $sql  .= $virgula." ac16_numeroprocesso = '$this->ac16_numeroprocesso' ";
       $virgula = ",";
     }
     if(trim($this->ac16_periodocomercial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_periodocomercial"])){
       $sql  .= $virgula." ac16_periodocomercial = '$this->ac16_periodocomercial' ";
       $virgula = ",";
       if(trim($this->ac16_periodocomercial) == null ){
         $this->erro_sql = " Campo Per�odo Comercial nao Informado.";
         $this->erro_campo = "ac16_periodocomercial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac16_qtdperiodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_qtdperiodo"])){
        if(trim($this->ac16_qtdperiodo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac16_qtdperiodo"])){
           $this->ac16_qtdperiodo = "0" ;
        }
       $sql  .= $virgula." ac16_qtdperiodo = $this->ac16_qtdperiodo ";
       $virgula = ",";
     }
     if(trim($this->ac16_tipounidtempoperiodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_tipounidtempoperiodo"])){
        if(trim($this->ac16_tipounidtempoperiodo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac16_tipounidtempoperiodo"])){
           $this->ac16_tipounidtempoperiodo = "0" ;
        }
       $sql  .= $virgula." ac16_tipounidtempoperiodo = $this->ac16_tipounidtempoperiodo ";
       $virgula = ",";
     }
     if(trim($this->ac16_acordocategoria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordocategoria"])){
        if(trim($this->ac16_acordocategoria)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordocategoria"])){
           $this->ac16_acordocategoria = "0" ;
        }
       $sql  .= $virgula." ac16_acordocategoria = $this->ac16_acordocategoria ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ac16_sequencial!=null){
       $sql .= " ac16_sequencial = $this->ac16_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac16_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,16116,'$this->ac16_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_sequencial"]) || $this->ac16_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,2828,16116,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_sequencial'))."','$this->ac16_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordosituacao"]) || $this->ac16_acordosituacao != "")
             $resac = db_query("insert into db_acount values($acount,2828,16117,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_acordosituacao'))."','$this->ac16_acordosituacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_coddepto"]) || $this->ac16_coddepto != "")
             $resac = db_query("insert into db_acount values($acount,2828,16118,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_coddepto'))."','$this->ac16_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_numero"]) || $this->ac16_numero != "")
             $resac = db_query("insert into db_acount values($acount,2828,16119,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_numero'))."','$this->ac16_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_anousu"]) || $this->ac16_anousu != "")
             $resac = db_query("insert into db_acount values($acount,2828,16120,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_anousu'))."','$this->ac16_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_dataassinatura"]) || $this->ac16_dataassinatura != "")
             $resac = db_query("insert into db_acount values($acount,2828,16121,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_dataassinatura'))."','$this->ac16_dataassinatura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_contratado"]) || $this->ac16_contratado != "")
             $resac = db_query("insert into db_acount values($acount,2828,16122,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_contratado'))."','$this->ac16_contratado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_datainicio"]) || $this->ac16_datainicio != "")
             $resac = db_query("insert into db_acount values($acount,2828,16123,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_datainicio'))."','$this->ac16_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_datafim"]) || $this->ac16_datafim != "")
             $resac = db_query("insert into db_acount values($acount,2828,16124,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_datafim'))."','$this->ac16_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_resumoobjeto"]) || $this->ac16_resumoobjeto != "")
             $resac = db_query("insert into db_acount values($acount,2828,16125,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_resumoobjeto'))."','$this->ac16_resumoobjeto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_objeto"]) || $this->ac16_objeto != "")
             $resac = db_query("insert into db_acount values($acount,2828,16126,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_objeto'))."','$this->ac16_objeto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_instit"]) || $this->ac16_instit != "")
             $resac = db_query("insert into db_acount values($acount,2828,16127,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_instit'))."','$this->ac16_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordocomissao"]) || $this->ac16_acordocomissao != "")
             $resac = db_query("insert into db_acount values($acount,2828,16129,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_acordocomissao'))."','$this->ac16_acordocomissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_lei"]) || $this->ac16_lei != "")
             $resac = db_query("insert into db_acount values($acount,2828,16130,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_lei'))."','$this->ac16_lei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordogrupo"]) || $this->ac16_acordogrupo != "")
             $resac = db_query("insert into db_acount values($acount,2828,16211,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_acordogrupo'))."','$this->ac16_acordogrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_origem"]) || $this->ac16_origem != "")
             $resac = db_query("insert into db_acount values($acount,2828,16233,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_origem'))."','$this->ac16_origem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_qtdrenovacao"]) || $this->ac16_qtdrenovacao != "")
             $resac = db_query("insert into db_acount values($acount,2828,16660,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_qtdrenovacao'))."','$this->ac16_qtdrenovacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_tipounidtempo"]) || $this->ac16_tipounidtempo != "")
             $resac = db_query("insert into db_acount values($acount,2828,16659,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_tipounidtempo'))."','$this->ac16_tipounidtempo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_deptoresponsavel"]) || $this->ac16_deptoresponsavel != "")
             $resac = db_query("insert into db_acount values($acount,2828,18033,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_deptoresponsavel'))."','$this->ac16_deptoresponsavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_numeroprocesso"]) || $this->ac16_numeroprocesso != "")
             $resac = db_query("insert into db_acount values($acount,2828,18487,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_numeroprocesso'))."','$this->ac16_numeroprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_periodocomercial"]) || $this->ac16_periodocomercial != "")
             $resac = db_query("insert into db_acount values($acount,2828,19736,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_periodocomercial'))."','$this->ac16_periodocomercial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_qtdperiodo"]) || $this->ac16_qtdperiodo != "")
             $resac = db_query("insert into db_acount values($acount,2828,19928,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_qtdperiodo'))."','$this->ac16_qtdperiodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_tipounidtempoperiodo"]) || $this->ac16_tipounidtempoperiodo != "")
             $resac = db_query("insert into db_acount values($acount,2828,19927,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_tipounidtempoperiodo'))."','$this->ac16_tipounidtempoperiodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordocategoria"]) || $this->ac16_acordocategoria != "")
             $resac = db_query("insert into db_acount values($acount,2828,19926,'".AddSlashes(pg_result($resaco,$conresaco,'ac16_acordocategoria'))."','$this->ac16_acordocategoria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac16_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac16_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac16_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ac16_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ac16_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,16116,'$ac16_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,2828,16116,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,16117,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_acordosituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,16118,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,16119,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,16120,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,16121,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_dataassinatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,16122,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_contratado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,16123,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,16124,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,16125,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_resumoobjeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,16126,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_objeto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,16127,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,16129,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_acordocomissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,16130,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,16211,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_acordogrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,16233,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,16660,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_qtdrenovacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,16659,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_tipounidtempo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,18033,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_deptoresponsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,18487,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_numeroprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,19736,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_periodocomercial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,19928,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_qtdperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,19927,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_tipounidtempoperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2828,19926,'','".AddSlashes(pg_result($resaco,$iresaco,'ac16_acordocategoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from acordo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac16_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac16_sequencial = $ac16_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac16_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac16_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac16_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:acordo";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ac16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from acordo ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = acordo.ac16_contratado";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = acordo.ac16_coddepto";
     $sql .= "      inner join acordogrupo  on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
     $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
     $sql .= "      inner join acordocomissao  on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      inner join acordonatureza  on  acordonatureza.ac01_sequencial = acordogrupo.ac02_acordonatureza";
     $sql .= "      inner join acordotipo  on  acordotipo.ac04_sequencial = acordogrupo.ac02_acordotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($ac16_sequencial!=null ){
         $sql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql
   function sql_query_file ( $ac16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from acordo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac16_sequencial!=null ){
         $sql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_completo( $ac16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from acordo ";
     $sql .= "      inner join cgm contratado on contratado.z01_numcgm          = acordo.ac16_contratado";
     $sql .= "      inner join db_depart      on db_depart.coddepto             = acordo.ac16_coddepto";
     $sql .= "      inner join acordogrupo    on acordogrupo.ac02_sequencial    = acordo.ac16_acordogrupo";
     $sql .= "      inner join acordosituacao on acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
     $sql .= "      inner join acordocomissao on acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
     $sql .= "      inner join acordonatureza on acordonatureza.ac01_sequencial = acordogrupo.ac02_acordonatureza";
     $sql .= "      inner join acordotipo     on acordotipo.ac04_sequencial     = acordogrupo.ac02_acordotipo";
     $sql .= "      inner join acordoorigem   on acordoorigem.ac28_sequencial   = acordo.ac16_origem";
     $sql2 = "";
     if($dbwhere==""){
       if($ac16_sequencial!=null ){
         $sql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
   }
   function sql_queryEmpenhosVinculados($iCodigoAcordo = null, $sCampos = "*", $sOrder = null, $sWhere = "") {

     $sSql = "";
     if (!empty($iCodigoAcordo)) {
       $sWhere .= " and acordo.ac16_sequencial = {$iCodigoAcordo} ";
     }

     $sSql .= "select distinct {$sCampos}";
     $sSql .= "       from acordo";
     $sSql .= "             left join acordoposicao    on acordoposicao.ac26_acordo        = acordo.ac16_sequencial";
     $sSql .= "             left join acordoitem       on acordoitem.ac20_acordoposicao    = acordoposicao.ac26_sequencial";
     $sSql .= "             left join acordoempempitem on acordoempempitem.ac44_acordoitem = acordoitem.ac20_sequencial";
     $sSql .= "             left join empempitem       on empempitem.e62_sequencial        = acordoempempitem.ac44_empempitem";
     $sSql .= "             left join empempenho       on empempenho.e60_numemp            = empempitem.e62_numemp  ";
     $sSql .= "            inner join empempenhocontrato on acordo.ac16_sequencial         = empempenhocontrato.e100_acordo ";
     $sSql .= "  where 1 = 1 ";
     $sSql .= " {$sWhere} {$sOrder} ";

     return $sSql;
   }

   function sql_queryEmpenhosVinculadosContrato($iCodigoAcordo = null, $sCampos = "*", $sOrder = null, $sWhere = "") {

     $sSql = "";
     if (!empty($iCodigoAcordo)) {
       $sWhere .= " and acordo.ac16_sequencial = {$iCodigoAcordo} ";
     }

     $sSql .= "select {$sCampos}";
     $sSql .= "  from acordo";
     $sSql .= "       inner join acordoposicao      on acordo.ac16_sequencial = acordoposicao.ac26_acordo";
     $sSql .= "       inner join empempenhocontrato on acordo.ac16_sequencial = empempenhocontrato.e100_acordo";
     $sSql .= "       inner join empempenho         on empempenho.e60_numemp = empempenhocontrato.e100_numemp";
     $sSql .= "       left  join acordoitem          on acordoposicao.ac26_sequencial = acordoitem.ac20_acordoposicao";
     $sSql .= "       left  join acordoempempitem    on acordoitem.ac20_sequencial = acordoempempitem.ac44_acordoitem";
     $sSql .= "       left  join empempitem          on acordoempempitem.ac44_empempitem = empempitem.e62_sequencial";
     $sSql .= "  where 1 = 1 ";
     $sSql .= " {$sWhere} {$sOrder} ";

     return $sSql;
   }
   function sql_queryProcessosVinculados($iCodigoAcordo = null, $sCampos = "*", $sOrder = null, $sWhere = "") {

     $sSql = "";
     if (!empty($iCodigoAcordo)) {
       $sWhere .= " and acordo.ac16_sequencial = {$iCodigoAcordo} ";
     }

     $sSql .= "select distinct {$sCampos}";
     $sSql .= "       from acordo";
     $sSql .= "            inner join acordoposicao    on acordoposicao.ac26_acordo        = acordo.ac16_sequencial";
     $sSql .= "            inner join acordoitem       on acordoitem.ac20_acordoposicao    = acordoposicao.ac26_sequencial";
     $sSql .= "            inner join acordopcprocitem on acordopcprocitem.ac23_acordoitem = acordoitem.ac20_sequencial";
     $sSql .= "            inner join pcprocitem       on pcprocitem.pc81_codprocitem      = acordopcprocitem.ac23_pcprocitem";
     $sSql .= "            inner join pcproc           on pcproc.pc80_codproc              = pcprocitem.pc81_codproc";
     $sSql .= "  where 1 = 1 ";
     $sSql .= " {$sWhere} {$sOrder} ";

     return $sSql;
   }
   function sql_queryLicitacoesVinculadas($iCodigoAcordo = null, $sCampos = "*", $sOrder = null, $sWhere = "") {

     $sSql = "";
     if (!empty($iCodigoAcordo)) {
       $sWhere .= " and acordo.ac16_sequencial = {$iCodigoAcordo} ";
     }

     $sSql .= "select distinct {$sCampos}";
     $sSql .= "       from acordo";
     $sSql .= "            inner join acordoposicao    on acordoposicao.ac26_acordo        = acordo.ac16_sequencial";
     $sSql .= "            inner join acordoitem       on acordoitem.ac20_acordoposicao    = acordoposicao.ac26_sequencial";
     $sSql .= "            inner join acordoliclicitem on acordoliclicitem.ac24_acordoitem = acordoitem.ac20_sequencial";
     $sSql .= "            inner join liclicitem       on liclicitem.l21_codigo            = acordoliclicitem.ac24_liclicitem";
     $sSql .= "            inner join liclicita        on liclicita.l20_codigo             = liclicitem.l21_codliclicita";
     $sSql .= "  where 1 = 1 ";
     $sSql .= " {$sWhere} {$sOrder} ";

     return $sSql;
   }
   function sql_query_completo_posicao( $ac16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from acordo ";
     $sql .= "      inner join cgm contratado  on  contratado.z01_numcgm = acordo.ac16_contratado";
     $sql .= "      inner join db_depart       on  db_depart.coddepto = acordo.ac16_coddepto";
     $sql .= "      inner join acordogrupo     on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
     $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
     $sql .= "      inner join acordocomissao  on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
     $sql .= "      inner join acordonatureza  on  acordonatureza.ac01_sequencial = acordogrupo.ac02_acordonatureza";
     $sql .= "      inner join acordotipo      on  acordotipo.ac04_sequencial = acordogrupo.ac02_acordotipo";
     $sql .= "      inner join cgm gestor      on  gestor.z01_numcgm = acordo.ac16_contratado";
     $sql .= "      inner join acordoposicao   on  acordoposicao.ac26_acordo = acordo.ac16_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($ac16_sequencial!=null ){
         $sql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
  function sql_query_empenho ( $ac16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){

    $sql2 = '';
    if ($dbwhere=="") {
      if($ac16_sequencial!=null ){
        $sql2 = " where acordo.ac16_sequencial = $ac16_sequencial ";
      }
    } else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sSqlAutorizacoes  =  "select  $campos ";
    $sSqlAutorizacoes .=  "   from acordoposicao ";
    $sSqlAutorizacoes .=  "        inner join acordoitem          on ac20_acordoposicao = ac26_sequencial ";
    $sSqlAutorizacoes .=  "        inner join acordo              on ac26_acordo        = ac16_sequencial ";
    $sSqlAutorizacoes .=  "        inner join db_depart           on ac16_deptoresponsavel = coddepto ";
    $sSqlAutorizacoes .=  "        inner join cgm                 on ac16_contratado       = z01_numcgm ";
    $sSqlAutorizacoes .=  "        inner join acordoitemexecutado on ac20_sequencial    = ac29_acordoitem ";
    $sSqlAutorizacoes .=  "        inner join acordoitemexecutadoempautitem on ac29_sequencial = ac19_acordoitemexecutado ";
    $sSqlAutorizacoes .=  "        inner join empautitem on e55_sequen = ac19_sequen and ac19_autori = e55_autori ";
    $sSqlAutorizacoes .=  "        inner join empautoriza on e54_autori = e55_autori ";
    $sSqlAutorizacoes .=  "        left join empempaut on e61_autori = e54_autori ";
    $sSqlAutorizacoes .=  "        left join empempenho on e61_numemp = e60_numemp {$sql2}";

    /**
     * pesquisa os empenhos vicnulados por baixa Manual
     */
    $sSqlAutorizacoes .=  " UNION ";
    $sSqlAutorizacoes .=  "select  {$campos}";
    $sSqlAutorizacoes .=  "   from acordoposicao ";
    $sSqlAutorizacoes .=  "        inner join acordoitem          on ac20_acordoposicao = ac26_sequencial ";
    $sSqlAutorizacoes .=  "        inner join acordo              on ac26_acordo        = ac16_sequencial ";
    $sSqlAutorizacoes .=  "        inner join db_depart           on ac16_deptoresponsavel = coddepto ";
    $sSqlAutorizacoes .=  "        inner join cgm                 on ac16_contratado       = z01_numcgm ";
    $sSqlAutorizacoes .=  "        inner join acordoitemexecutado on ac20_sequencial    = ac29_acordoitem ";
    $sSqlAutorizacoes .=  "        inner join acordoitemexecutadoperiodo on ac29_sequencial = ac38_acordoitemexecutado";
    $sSqlAutorizacoes .=  "        inner join acordoitemexecutadoempenho on  ac38_sequencial = ac39_acordoitemexecutadoperiodo";
    $sSqlAutorizacoes .=  "        inner join empempenho    on ac39_numemp = e60_numemp ";
    $sSqlAutorizacoes .=  "        left join empempaut      on e60_numemp  = e61_numemp ";
    $sSqlAutorizacoes .=  "        inner join empautoriza   on e54_autori  = e61_autori ";
    $sSqlAutorizacoes .=  "  {$sql2} ";


    if($ordem != null ){

      $sSqlAutorizacoes .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){

        $sSqlAutorizacoes .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sSqlAutorizacoes;
  }

  /**
   * Retorna as movimenta��es do acordo de origem manual
   */
  function sql_movimentacao_acordo_origem_manual ( $ac16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from acordo";
    $sql .= "      inner join acordoposicao              on acordo.ac16_sequencial                     = acordoposicao.ac26_acordo";
    $sql .= "      inner join acordoitem                 on acordoposicao.ac26_sequencial              = acordoitem.ac20_acordoposicao";
    $sql .= "      inner join acordoitemexecutado        on acordoitem.ac20_sequencial                 = acordoitemexecutado.ac29_acordoitem";
    $sql .= "      inner join acordoitemprevisao         on acordoitem.ac20_sequencial                 = acordoitemprevisao.ac37_acordoitem";
    $sql .= "      inner join acordoitemexecutadoperiodo on acordoitemexecutado.ac29_sequencial        = acordoitemexecutadoperiodo.ac38_acordoitemexecutado";
    $sql .= "                                           and acordoitemprevisao.ac37_sequencial         = acordoitemexecutadoperiodo.ac38_acordoitemprevisao";
    $sql .= "      inner join acordoitemexecutadoempenho on acordoitemexecutadoperiodo.ac38_sequencial = acordoitemexecutadoempenho.ac39_acordoitemexecutadoperiodo";
    $sql .= "      inner join empempenho                 on acordoitemexecutadoempenho.ac39_numemp     = empempenho.e60_numemp";
    $sql .= "      inner join cgm                        on cgm.z01_numcgm                             = acordo.ac16_contratado";
    $sql .= "      inner join acordoorigem               on acordoorigem.ac28_sequencial               = acordo.ac16_origem";
    $sql .= "                                           and acordoorigem.ac28_sequencial               = 3";

    $sql2 = "";
    if($dbwhere == "") {

      if($ac16_sequencial!=null ) {
        $sql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
      }
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;

  }

  function sql_movimentacao_acordo_empenhado ($ac16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#", $campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from  acordo";
    $sql .= " inner join acordoempautoriza on acordo.ac16_sequencial = acordoempautoriza.ac45_acordo";
    $sql .= " inner join empempaut         on empempaut.e61_autori   = acordoempautoriza.ac45_empautoriza";
    $sql .= " inner join empempenho        on empempenho.e60_numemp  =  empempaut.e61_numemp";
    $sql .= " inner join cgm               on acordo.ac16_contratado = cgm.z01_numcgm ";

    $sql2 = "";
    if ($dbwhere == "") {

      if ($ac16_sequencial != null ) {
        $sql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
      }
    } else if ($dbwhere != "") {

      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;
    if ($ordem != null ) {

      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;

  }

  public function sql_movimentacao_acordo_origem_empenho ($ac16_sequencial = null, $sCampos = "*", $sOrder = null, $dbwhere = "") {

    $sSql = "select ";
    if ($sCampos != "*" ) {

      $sCampos_sql = split("#", $sCampos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($sCampos_sql); $i++) {

        $sSql   .= $virgula.$sCampos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sSql .= $sCampos;
    }

    $sSql .= " from acordo";
    $sSql .= "      inner join empempenhocontrato on empempenhocontrato.e100_acordo = acordo.ac16_sequencial";
    $sSql .= "      inner join empempenho         on empempenhocontrato.e100_numemp = empempenho.e60_numemp";
    $sSql .= "      inner join cgm                on acordo.ac16_contratado         = cgm.z01_numcgm";

    $sSql2 = "";
    if ($dbwhere == "") {

      if ($ac16_sequencial != null ) {
        $sSql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sSql2 = " where $dbwhere";
    }

    $sSql .= $sSql2;
    if ($sOrder != null ) {

      $sSql       .= " order by ";
      $sCampos_sql = split("#", $sOrder);
      $virgula     = "";
      for ($i = 0; $i < sizeof($sCampos_sql); $i++) {

        $sSql   .= $virgula.$sCampos_sql[$i];
        $virgula = ",";
      }
    }
    return $sSql;
  }
  
  function sql_query_acordoitemexecutado ( $ac16_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from acordo ";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = acordo.ac16_contratado";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = acordo.ac16_coddepto";
    $sql .= "      inner join acordogrupo  on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
    $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
    $sql .= "      inner join acordocomissao  on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
    $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
    $sql .= "      inner join acordonatureza  on  acordonatureza.ac01_sequencial = acordogrupo.ac02_acordonatureza";
    $sql .= "      inner join acordotipo  on  acordotipo.ac04_sequencial = acordogrupo.ac02_acordotipo";
    $sql .= "      inner join acordoposicao       on acordoposicao.ac26_acordo           = acordo.ac16_sequencial";
    $sql .= "      left  join acordoitem          on acordoitem.ac20_acordoposicao       = acordoposicao.ac26_sequencial";
    $sql2 = "";
    if($dbwhere==""){
      if($ac16_sequencial!=null ){
        $sql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
?>