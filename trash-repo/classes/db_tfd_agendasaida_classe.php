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

//MODULO: TFD
//CLASSE DA ENTIDADE tfd_agendasaida
class cl_tfd_agendasaida { 
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
   var $tf17_i_codigo = 0; 
   var $tf17_i_pedidotfd = 0; 
   var $tf17_d_datasaida_dia = null; 
   var $tf17_d_datasaida_mes = null; 
   var $tf17_d_datasaida_ano = null; 
   var $tf17_d_datasaida = null; 
   var $tf17_c_horasaida = null; 
   var $tf17_c_localsaida = null; 
   var $tf17_i_login = 0; 
   var $tf17_d_datasistema_dia = null; 
   var $tf17_d_datasistema_mes = null; 
   var $tf17_d_datasistema_ano = null; 
   var $tf17_d_datasistema = null; 
   var $tf17_c_horasistema = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tf17_i_codigo = int4 = C�digo 
                 tf17_i_pedidotfd = int4 = Pedido 
                 tf17_d_datasaida = date = Data 
                 tf17_c_horasaida = char(5) = Hora 
                 tf17_c_localsaida = varchar(50) = Local da Sa�da 
                 tf17_i_login = int4 = Login 
                 tf17_d_datasistema = date = Data 
                 tf17_c_horasistema = char(5) = Hora 
                 ";
   //funcao construtor da classe 
   function cl_tfd_agendasaida() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tfd_agendasaida"); 
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
       $this->tf17_i_codigo = ($this->tf17_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf17_i_codigo"]:$this->tf17_i_codigo);
       $this->tf17_i_pedidotfd = ($this->tf17_i_pedidotfd == ""?@$GLOBALS["HTTP_POST_VARS"]["tf17_i_pedidotfd"]:$this->tf17_i_pedidotfd);
       if($this->tf17_d_datasaida == ""){
         $this->tf17_d_datasaida_dia = ($this->tf17_d_datasaida_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf17_d_datasaida_dia"]:$this->tf17_d_datasaida_dia);
         $this->tf17_d_datasaida_mes = ($this->tf17_d_datasaida_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf17_d_datasaida_mes"]:$this->tf17_d_datasaida_mes);
         $this->tf17_d_datasaida_ano = ($this->tf17_d_datasaida_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf17_d_datasaida_ano"]:$this->tf17_d_datasaida_ano);
         if($this->tf17_d_datasaida_dia != ""){
            $this->tf17_d_datasaida = $this->tf17_d_datasaida_ano."-".$this->tf17_d_datasaida_mes."-".$this->tf17_d_datasaida_dia;
         }
       }
       $this->tf17_c_horasaida = ($this->tf17_c_horasaida == ""?@$GLOBALS["HTTP_POST_VARS"]["tf17_c_horasaida"]:$this->tf17_c_horasaida);
       $this->tf17_c_localsaida = ($this->tf17_c_localsaida == ""?@$GLOBALS["HTTP_POST_VARS"]["tf17_c_localsaida"]:$this->tf17_c_localsaida);
       $this->tf17_i_login = ($this->tf17_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["tf17_i_login"]:$this->tf17_i_login);
       if($this->tf17_d_datasistema == ""){
         $this->tf17_d_datasistema_dia = ($this->tf17_d_datasistema_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf17_d_datasistema_dia"]:$this->tf17_d_datasistema_dia);
         $this->tf17_d_datasistema_mes = ($this->tf17_d_datasistema_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf17_d_datasistema_mes"]:$this->tf17_d_datasistema_mes);
         $this->tf17_d_datasistema_ano = ($this->tf17_d_datasistema_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf17_d_datasistema_ano"]:$this->tf17_d_datasistema_ano);
         if($this->tf17_d_datasistema_dia != ""){
            $this->tf17_d_datasistema = $this->tf17_d_datasistema_ano."-".$this->tf17_d_datasistema_mes."-".$this->tf17_d_datasistema_dia;
         }
       }
       $this->tf17_c_horasistema = ($this->tf17_c_horasistema == ""?@$GLOBALS["HTTP_POST_VARS"]["tf17_c_horasistema"]:$this->tf17_c_horasistema);
     }else{
       $this->tf17_i_codigo = ($this->tf17_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf17_i_codigo"]:$this->tf17_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($tf17_i_codigo){ 
      $this->atualizacampos();
     if($this->tf17_i_pedidotfd == null ){ 
       $this->erro_sql = " Campo Pedido nao Informado.";
       $this->erro_campo = "tf17_i_pedidotfd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf17_d_datasaida == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "tf17_d_datasaida_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf17_c_horasaida == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "tf17_c_horasaida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf17_c_localsaida == null ){ 
       $this->erro_sql = " Campo Local da Sa�da nao Informado.";
       $this->erro_campo = "tf17_c_localsaida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf17_i_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "tf17_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf17_d_datasistema == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "tf17_d_datasistema_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf17_c_horasistema == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "tf17_c_horasistema";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tf17_i_codigo == "" || $tf17_i_codigo == null ){
       $result = db_query("select nextval('tfd_agendasaida_tf17_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tfd_agendasaida_tf17_i_codigo_seq do campo: tf17_i_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tf17_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tfd_agendasaida_tf17_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf17_i_codigo)){
         $this->erro_sql = " Campo tf17_i_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf17_i_codigo = $tf17_i_codigo; 
       }
     }
     if(($this->tf17_i_codigo == null) || ($this->tf17_i_codigo == "") ){ 
       $this->erro_sql = " Campo tf17_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tfd_agendasaida(
                                       tf17_i_codigo 
                                      ,tf17_i_pedidotfd 
                                      ,tf17_d_datasaida 
                                      ,tf17_c_horasaida 
                                      ,tf17_c_localsaida 
                                      ,tf17_i_login 
                                      ,tf17_d_datasistema 
                                      ,tf17_c_horasistema 
                       )
                values (
                                $this->tf17_i_codigo 
                               ,$this->tf17_i_pedidotfd 
                               ,".($this->tf17_d_datasaida == "null" || $this->tf17_d_datasaida == ""?"null":"'".$this->tf17_d_datasaida."'")." 
                               ,'$this->tf17_c_horasaida' 
                               ,'$this->tf17_c_localsaida' 
                               ,$this->tf17_i_login 
                               ,".($this->tf17_d_datasistema == "null" || $this->tf17_d_datasistema == ""?"null":"'".$this->tf17_d_datasistema."'")." 
                               ,'$this->tf17_c_horasistema' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tfd_agendasaida ($this->tf17_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tfd_agendasaida j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tfd_agendasaida ($this->tf17_i_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf17_i_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tf17_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16406,'$this->tf17_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2873,16406,'','".AddSlashes(pg_result($resaco,0,'tf17_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2873,16407,'','".AddSlashes(pg_result($resaco,0,'tf17_i_pedidotfd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2873,16408,'','".AddSlashes(pg_result($resaco,0,'tf17_d_datasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2873,16409,'','".AddSlashes(pg_result($resaco,0,'tf17_c_horasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2873,16410,'','".AddSlashes(pg_result($resaco,0,'tf17_c_localsaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2873,16702,'','".AddSlashes(pg_result($resaco,0,'tf17_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2873,16703,'','".AddSlashes(pg_result($resaco,0,'tf17_d_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2873,16704,'','".AddSlashes(pg_result($resaco,0,'tf17_c_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tf17_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tfd_agendasaida set ";
     $virgula = "";
     if(trim($this->tf17_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf17_i_codigo"])){ 
       $sql  .= $virgula." tf17_i_codigo = $this->tf17_i_codigo ";
       $virgula = ",";
       if(trim($this->tf17_i_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "tf17_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf17_i_pedidotfd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf17_i_pedidotfd"])){ 
       $sql  .= $virgula." tf17_i_pedidotfd = $this->tf17_i_pedidotfd ";
       $virgula = ",";
       if(trim($this->tf17_i_pedidotfd) == null ){ 
         $this->erro_sql = " Campo Pedido nao Informado.";
         $this->erro_campo = "tf17_i_pedidotfd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf17_d_datasaida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf17_d_datasaida_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf17_d_datasaida_dia"] !="") ){ 
       $sql  .= $virgula." tf17_d_datasaida = '$this->tf17_d_datasaida' ";
       $virgula = ",";
       if(trim($this->tf17_d_datasaida) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "tf17_d_datasaida_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf17_d_datasaida_dia"])){ 
         $sql  .= $virgula." tf17_d_datasaida = null ";
         $virgula = ",";
         if(trim($this->tf17_d_datasaida) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "tf17_d_datasaida_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf17_c_horasaida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf17_c_horasaida"])){ 
       $sql  .= $virgula." tf17_c_horasaida = '$this->tf17_c_horasaida' ";
       $virgula = ",";
       if(trim($this->tf17_c_horasaida) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "tf17_c_horasaida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf17_c_localsaida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf17_c_localsaida"])){ 
       $sql  .= $virgula." tf17_c_localsaida = '$this->tf17_c_localsaida' ";
       $virgula = ",";
       if(trim($this->tf17_c_localsaida) == null ){ 
         $this->erro_sql = " Campo Local da Sa�da nao Informado.";
         $this->erro_campo = "tf17_c_localsaida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf17_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf17_i_login"])){ 
       $sql  .= $virgula." tf17_i_login = $this->tf17_i_login ";
       $virgula = ",";
       if(trim($this->tf17_i_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "tf17_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf17_d_datasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf17_d_datasistema_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf17_d_datasistema_dia"] !="") ){ 
       $sql  .= $virgula." tf17_d_datasistema = '$this->tf17_d_datasistema' ";
       $virgula = ",";
       if(trim($this->tf17_d_datasistema) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "tf17_d_datasistema_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf17_d_datasistema_dia"])){ 
         $sql  .= $virgula." tf17_d_datasistema = null ";
         $virgula = ",";
         if(trim($this->tf17_d_datasistema) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "tf17_d_datasistema_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf17_c_horasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf17_c_horasistema"])){ 
       $sql  .= $virgula." tf17_c_horasistema = '$this->tf17_c_horasistema' ";
       $virgula = ",";
       if(trim($this->tf17_c_horasistema) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "tf17_c_horasistema";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tf17_i_codigo!=null){
       $sql .= " tf17_i_codigo = $this->tf17_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tf17_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16406,'$this->tf17_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf17_i_codigo"]) || $this->tf17_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2873,16406,'".AddSlashes(pg_result($resaco,$conresaco,'tf17_i_codigo'))."','$this->tf17_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf17_i_pedidotfd"]) || $this->tf17_i_pedidotfd != "")
           $resac = db_query("insert into db_acount values($acount,2873,16407,'".AddSlashes(pg_result($resaco,$conresaco,'tf17_i_pedidotfd'))."','$this->tf17_i_pedidotfd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf17_d_datasaida"]) || $this->tf17_d_datasaida != "")
           $resac = db_query("insert into db_acount values($acount,2873,16408,'".AddSlashes(pg_result($resaco,$conresaco,'tf17_d_datasaida'))."','$this->tf17_d_datasaida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf17_c_horasaida"]) || $this->tf17_c_horasaida != "")
           $resac = db_query("insert into db_acount values($acount,2873,16409,'".AddSlashes(pg_result($resaco,$conresaco,'tf17_c_horasaida'))."','$this->tf17_c_horasaida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf17_c_localsaida"]) || $this->tf17_c_localsaida != "")
           $resac = db_query("insert into db_acount values($acount,2873,16410,'".AddSlashes(pg_result($resaco,$conresaco,'tf17_c_localsaida'))."','$this->tf17_c_localsaida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf17_i_login"]) || $this->tf17_i_login != "")
           $resac = db_query("insert into db_acount values($acount,2873,16702,'".AddSlashes(pg_result($resaco,$conresaco,'tf17_i_login'))."','$this->tf17_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf17_d_datasistema"]) || $this->tf17_d_datasistema != "")
           $resac = db_query("insert into db_acount values($acount,2873,16703,'".AddSlashes(pg_result($resaco,$conresaco,'tf17_d_datasistema'))."','$this->tf17_d_datasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf17_c_horasistema"]) || $this->tf17_c_horasistema != "")
           $resac = db_query("insert into db_acount values($acount,2873,16704,'".AddSlashes(pg_result($resaco,$conresaco,'tf17_c_horasistema'))."','$this->tf17_c_horasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_agendasaida nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf17_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_agendasaida nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf17_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf17_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tf17_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tf17_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16406,'$tf17_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2873,16406,'','".AddSlashes(pg_result($resaco,$iresaco,'tf17_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2873,16407,'','".AddSlashes(pg_result($resaco,$iresaco,'tf17_i_pedidotfd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2873,16408,'','".AddSlashes(pg_result($resaco,$iresaco,'tf17_d_datasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2873,16409,'','".AddSlashes(pg_result($resaco,$iresaco,'tf17_c_horasaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2873,16410,'','".AddSlashes(pg_result($resaco,$iresaco,'tf17_c_localsaida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2873,16702,'','".AddSlashes(pg_result($resaco,$iresaco,'tf17_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2873,16703,'','".AddSlashes(pg_result($resaco,$iresaco,'tf17_d_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2873,16704,'','".AddSlashes(pg_result($resaco,$iresaco,'tf17_c_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tfd_agendasaida
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf17_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf17_i_codigo = $tf17_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_agendasaida nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf17_i_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_agendasaida nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf17_i_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf17_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tfd_agendasaida";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tf17_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_agendasaida ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_agendasaida.tf17_i_login";
     $sql .= "      inner join tfd_pedidotfd  on  tfd_pedidotfd.tf01_i_codigo = tfd_agendasaida.tf17_i_pedidotfd";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_pedidotfd.tf01_i_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = tfd_pedidotfd.tf01_i_depto";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = tfd_pedidotfd.tf01_i_rhcbo";
     $sql .= "      inner join tfd_tipotratamento  on  tfd_tipotratamento.tf04_i_codigo = tfd_pedidotfd.tf01_i_tipotratamento";
     $sql .= "      inner join tfd_situacaotfd  on  tfd_situacaotfd.tf26_i_codigo = tfd_pedidotfd.tf01_i_situacao";
     $sql .= "      inner join tfd_tipotransporte  on  tfd_tipotransporte.tf27_i_codigo = tfd_pedidotfd.tf01_i_tipotransporte";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($tf17_i_codigo!=null ){
         $sql2 .= " where tfd_agendasaida.tf17_i_codigo = $tf17_i_codigo "; 
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
   function sql_query_file ( $tf17_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_agendasaida ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf17_i_codigo!=null ){
         $sql2 .= " where tfd_agendasaida.tf17_i_codigo = $tf17_i_codigo "; 
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
   function sql_query2 ( $tf17_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_agendasaida ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_agendasaida.tf17_i_login";
     $sql .= "      inner join tfd_pedidotfd  on  tfd_pedidotfd.tf01_i_codigo = tfd_agendasaida.tf17_i_pedidotfd";
     $sql .= "      inner join db_usuarios as a on  a.id_usuario = tfd_pedidotfd.tf01_i_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = tfd_pedidotfd.tf01_i_depto";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = tfd_pedidotfd.tf01_i_rhcbo";
     $sql .= "      inner join tfd_tipotratamento  on  tfd_tipotratamento.tf04_i_codigo = tfd_pedidotfd.tf01_i_tipotratamento";
     $sql .= "      inner join tfd_situacaotfd  on  tfd_situacaotfd.tf26_i_codigo = tfd_pedidotfd.tf01_i_situacao";
     $sql .= "      inner join tfd_tipotransporte  on  tfd_tipotransporte.tf27_i_codigo = tfd_pedidotfd.tf01_i_tipotransporte";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($tf17_i_codigo!=null ){
         $sql2 .= " where tfd_agendasaida.tf17_i_codigo = $tf17_i_codigo "; 
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