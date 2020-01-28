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
//CLASSE DA ENTIDADE tfd_situacaopedidotfd
class cl_tfd_situacaopedidotfd { 
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
   var $tf28_i_codigo = 0; 
   var $tf28_i_situacao = 0; 
   var $tf28_i_pedidotfd = 0; 
   var $tf28_d_datasistema_dia = null; 
   var $tf28_d_datasistema_mes = null; 
   var $tf28_d_datasistema_ano = null; 
   var $tf28_d_datasistema = null; 
   var $tf28_c_horasistema = null; 
   var $tf28_c_obs = null; 
   var $tf28_i_login = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tf28_i_codigo = int4 = Código 
                 tf28_i_situacao = int4 = Situação 
                 tf28_i_pedidotfd = int4 = Pedido 
                 tf28_d_datasistema = date = Data 
                 tf28_c_horasistema = char(5) = Hora 
                 tf28_c_obs = varchar(100) = Observação 
                 tf28_i_login = int4 = Login 
                 ";
   //funcao construtor da classe 
   function cl_tfd_situacaopedidotfd() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tfd_situacaopedidotfd"); 
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
       $this->tf28_i_codigo = ($this->tf28_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf28_i_codigo"]:$this->tf28_i_codigo);
       $this->tf28_i_situacao = ($this->tf28_i_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["tf28_i_situacao"]:$this->tf28_i_situacao);
       $this->tf28_i_pedidotfd = ($this->tf28_i_pedidotfd == ""?@$GLOBALS["HTTP_POST_VARS"]["tf28_i_pedidotfd"]:$this->tf28_i_pedidotfd);
       if($this->tf28_d_datasistema == ""){
         $this->tf28_d_datasistema_dia = ($this->tf28_d_datasistema_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf28_d_datasistema_dia"]:$this->tf28_d_datasistema_dia);
         $this->tf28_d_datasistema_mes = ($this->tf28_d_datasistema_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf28_d_datasistema_mes"]:$this->tf28_d_datasistema_mes);
         $this->tf28_d_datasistema_ano = ($this->tf28_d_datasistema_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf28_d_datasistema_ano"]:$this->tf28_d_datasistema_ano);
         if($this->tf28_d_datasistema_dia != ""){
            $this->tf28_d_datasistema = $this->tf28_d_datasistema_ano."-".$this->tf28_d_datasistema_mes."-".$this->tf28_d_datasistema_dia;
         }
       }
       $this->tf28_c_horasistema = ($this->tf28_c_horasistema == ""?@$GLOBALS["HTTP_POST_VARS"]["tf28_c_horasistema"]:$this->tf28_c_horasistema);
       $this->tf28_c_obs = ($this->tf28_c_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["tf28_c_obs"]:$this->tf28_c_obs);
       $this->tf28_i_login = ($this->tf28_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["tf28_i_login"]:$this->tf28_i_login);
     }else{
       $this->tf28_i_codigo = ($this->tf28_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf28_i_codigo"]:$this->tf28_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($tf28_i_codigo){ 
      $this->atualizacampos();
     if($this->tf28_i_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "tf28_i_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf28_i_pedidotfd == null ){ 
       $this->erro_sql = " Campo Pedido nao Informado.";
       $this->erro_campo = "tf28_i_pedidotfd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf28_d_datasistema == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "tf28_d_datasistema_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf28_c_horasistema == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "tf28_c_horasistema";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf28_i_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "tf28_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tf28_i_codigo == "" || $tf28_i_codigo == null ){
       $result = db_query("select nextval('tfd_situacaopedidotfd_tf28_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tfd_situacaopedidotfd_tf28_i_codigo_seq do campo: tf28_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tf28_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tfd_situacaopedidotfd_tf28_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf28_i_codigo)){
         $this->erro_sql = " Campo tf28_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf28_i_codigo = $tf28_i_codigo; 
       }
     }
     if(($this->tf28_i_codigo == null) || ($this->tf28_i_codigo == "") ){ 
       $this->erro_sql = " Campo tf28_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tfd_situacaopedidotfd(
                                       tf28_i_codigo 
                                      ,tf28_i_situacao 
                                      ,tf28_i_pedidotfd 
                                      ,tf28_d_datasistema 
                                      ,tf28_c_horasistema 
                                      ,tf28_c_obs 
                                      ,tf28_i_login 
                       )
                values (
                                $this->tf28_i_codigo 
                               ,$this->tf28_i_situacao 
                               ,$this->tf28_i_pedidotfd 
                               ,".($this->tf28_d_datasistema == "null" || $this->tf28_d_datasistema == ""?"null":"'".$this->tf28_d_datasistema."'")." 
                               ,'$this->tf28_c_horasistema' 
                               ,'$this->tf28_c_obs' 
                               ,$this->tf28_i_login 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tfd_situacaopedidotfd ($this->tf28_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tfd_situacaopedidotfd já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tfd_situacaopedidotfd ($this->tf28_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf28_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tf28_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16452,'$this->tf28_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2884,16452,'','".AddSlashes(pg_result($resaco,0,'tf28_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2884,16454,'','".AddSlashes(pg_result($resaco,0,'tf28_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2884,16453,'','".AddSlashes(pg_result($resaco,0,'tf28_i_pedidotfd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2884,16457,'','".AddSlashes(pg_result($resaco,0,'tf28_d_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2884,16458,'','".AddSlashes(pg_result($resaco,0,'tf28_c_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2884,16456,'','".AddSlashes(pg_result($resaco,0,'tf28_c_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2884,16455,'','".AddSlashes(pg_result($resaco,0,'tf28_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tf28_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tfd_situacaopedidotfd set ";
     $virgula = "";
     if(trim($this->tf28_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf28_i_codigo"])){ 
       $sql  .= $virgula." tf28_i_codigo = $this->tf28_i_codigo ";
       $virgula = ",";
       if(trim($this->tf28_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "tf28_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf28_i_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf28_i_situacao"])){ 
       $sql  .= $virgula." tf28_i_situacao = $this->tf28_i_situacao ";
       $virgula = ",";
       if(trim($this->tf28_i_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "tf28_i_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf28_i_pedidotfd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf28_i_pedidotfd"])){ 
       $sql  .= $virgula." tf28_i_pedidotfd = $this->tf28_i_pedidotfd ";
       $virgula = ",";
       if(trim($this->tf28_i_pedidotfd) == null ){ 
         $this->erro_sql = " Campo Pedido nao Informado.";
         $this->erro_campo = "tf28_i_pedidotfd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf28_d_datasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf28_d_datasistema_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf28_d_datasistema_dia"] !="") ){ 
       $sql  .= $virgula." tf28_d_datasistema = '$this->tf28_d_datasistema' ";
       $virgula = ",";
       if(trim($this->tf28_d_datasistema) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "tf28_d_datasistema_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf28_d_datasistema_dia"])){ 
         $sql  .= $virgula." tf28_d_datasistema = null ";
         $virgula = ",";
         if(trim($this->tf28_d_datasistema) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "tf28_d_datasistema_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf28_c_horasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf28_c_horasistema"])){ 
       $sql  .= $virgula." tf28_c_horasistema = '$this->tf28_c_horasistema' ";
       $virgula = ",";
       if(trim($this->tf28_c_horasistema) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "tf28_c_horasistema";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf28_c_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf28_c_obs"])){ 
       $sql  .= $virgula." tf28_c_obs = '$this->tf28_c_obs' ";
       $virgula = ",";
     }
     if(trim($this->tf28_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf28_i_login"])){ 
       $sql  .= $virgula." tf28_i_login = $this->tf28_i_login ";
       $virgula = ",";
       if(trim($this->tf28_i_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "tf28_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tf28_i_codigo!=null){
       $sql .= " tf28_i_codigo = $this->tf28_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tf28_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16452,'$this->tf28_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf28_i_codigo"]) || $this->tf28_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2884,16452,'".AddSlashes(pg_result($resaco,$conresaco,'tf28_i_codigo'))."','$this->tf28_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf28_i_situacao"]) || $this->tf28_i_situacao != "")
           $resac = db_query("insert into db_acount values($acount,2884,16454,'".AddSlashes(pg_result($resaco,$conresaco,'tf28_i_situacao'))."','$this->tf28_i_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf28_i_pedidotfd"]) || $this->tf28_i_pedidotfd != "")
           $resac = db_query("insert into db_acount values($acount,2884,16453,'".AddSlashes(pg_result($resaco,$conresaco,'tf28_i_pedidotfd'))."','$this->tf28_i_pedidotfd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf28_d_datasistema"]) || $this->tf28_d_datasistema != "")
           $resac = db_query("insert into db_acount values($acount,2884,16457,'".AddSlashes(pg_result($resaco,$conresaco,'tf28_d_datasistema'))."','$this->tf28_d_datasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf28_c_horasistema"]) || $this->tf28_c_horasistema != "")
           $resac = db_query("insert into db_acount values($acount,2884,16458,'".AddSlashes(pg_result($resaco,$conresaco,'tf28_c_horasistema'))."','$this->tf28_c_horasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf28_c_obs"]) || $this->tf28_c_obs != "")
           $resac = db_query("insert into db_acount values($acount,2884,16456,'".AddSlashes(pg_result($resaco,$conresaco,'tf28_c_obs'))."','$this->tf28_c_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf28_i_login"]) || $this->tf28_i_login != "")
           $resac = db_query("insert into db_acount values($acount,2884,16455,'".AddSlashes(pg_result($resaco,$conresaco,'tf28_i_login'))."','$this->tf28_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_situacaopedidotfd nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf28_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_situacaopedidotfd nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf28_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf28_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tf28_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tf28_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16452,'$tf28_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2884,16452,'','".AddSlashes(pg_result($resaco,$iresaco,'tf28_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2884,16454,'','".AddSlashes(pg_result($resaco,$iresaco,'tf28_i_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2884,16453,'','".AddSlashes(pg_result($resaco,$iresaco,'tf28_i_pedidotfd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2884,16457,'','".AddSlashes(pg_result($resaco,$iresaco,'tf28_d_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2884,16458,'','".AddSlashes(pg_result($resaco,$iresaco,'tf28_c_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2884,16456,'','".AddSlashes(pg_result($resaco,$iresaco,'tf28_c_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2884,16455,'','".AddSlashes(pg_result($resaco,$iresaco,'tf28_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tfd_situacaopedidotfd
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf28_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf28_i_codigo = $tf28_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_situacaopedidotfd nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf28_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_situacaopedidotfd nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf28_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf28_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:tfd_situacaopedidotfd";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tf28_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_situacaopedidotfd ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_situacaopedidotfd.tf28_i_login";
     $sql .= "      inner join tfd_pedidotfd  on  tfd_pedidotfd.tf01_i_codigo = tfd_situacaopedidotfd.tf28_i_pedidotfd";
     $sql .= "      inner join tfd_situacaotfd  on  tfd_situacaotfd.tf26_i_codigo = tfd_situacaopedidotfd.tf28_i_situacao";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_pedidotfd.tf01_i_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = tfd_pedidotfd.tf01_i_depto";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = tfd_pedidotfd.tf01_i_rhcbo";
     $sql .= "      inner join tfd_tipotratamento  on  tfd_tipotratamento.tf04_i_codigo = tfd_pedidotfd.tf01_i_tipotratamento";
     $sql .= "      inner join tfd_situacaotfd  as a on   a.tf26_i_codigo = tfd_pedidotfd.tf01_i_situacao";
     $sql .= "      inner join tfd_tipotransporte  on  tfd_tipotransporte.tf27_i_codigo = tfd_pedidotfd.tf01_i_tipotransporte";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($tf28_i_codigo!=null ){
         $sql2 .= " where tfd_situacaopedidotfd.tf28_i_codigo = $tf28_i_codigo "; 
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
   function sql_query_file ( $tf28_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_situacaopedidotfd ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf28_i_codigo!=null ){
         $sql2 .= " where tfd_situacaopedidotfd.tf28_i_codigo = $tf28_i_codigo "; 
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
   function sql_query2 ( $tf28_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_situacaopedidotfd ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tfd_situacaopedidotfd.tf28_i_login";
     $sql .= "      inner join tfd_pedidotfd  on  tfd_pedidotfd.tf01_i_codigo = tfd_situacaopedidotfd.tf28_i_pedidotfd";
     $sql .= "      inner join tfd_situacaotfd  on  tfd_situacaotfd.tf26_i_codigo = tfd_situacaopedidotfd.tf28_i_situacao";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = tfd_pedidotfd.tf01_i_depto";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = tfd_pedidotfd.tf01_i_rhcbo";
     $sql .= "      inner join tfd_tipotratamento  on  tfd_tipotratamento.tf04_i_codigo = tfd_pedidotfd.tf01_i_tipotratamento";
     $sql .= "      inner join tfd_tipotransporte  on  tfd_tipotransporte.tf27_i_codigo = tfd_pedidotfd.tf01_i_tipotransporte";
     $sql .= "      inner join cgs_und  on  cgs_und.z01_i_cgsund = tfd_pedidotfd.tf01_i_cgsund";
     $sql2 = "";
     if($dbwhere==""){
       if($tf28_i_codigo!=null ){
         $sql2 .= " where tfd_situacaopedidotfd.tf28_i_codigo = $tf28_i_codigo "; 
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