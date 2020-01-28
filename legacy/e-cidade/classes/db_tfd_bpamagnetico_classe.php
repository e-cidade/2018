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

//MODULO: TFD
//CLASSE DA ENTIDADE tfd_bpamagnetico
class cl_tfd_bpamagnetico { 
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
   var $tf33_i_codigo = 0; 
   var $tf33_i_login = 0; 
   var $tf33_i_fechamento = 0; 
   var $tf33_c_nomearquivo = null; 
   var $tf33_d_datasistema_dia = null; 
   var $tf33_d_datasistema_mes = null; 
   var $tf33_d_datasistema_ano = null; 
   var $tf33_d_datasistema = null; 
   var $tf33_c_horasistema = null; 
   var $tf33_o_arquivo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tf33_i_codigo = int4 = Código 
                 tf33_i_login = int4 = Login 
                 tf33_i_fechamento = int4 = Competência 
                 tf33_c_nomearquivo = varchar(100) = Nome do arquivo 
                 tf33_d_datasistema = date = Data do sistema 
                 tf33_c_horasistema = char(5) = Hora do sistema 
                 tf33_o_arquivo = oid = Arquivo 
                 ";
   //funcao construtor da classe 
   function cl_tfd_bpamagnetico() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tfd_bpamagnetico"); 
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
       $this->tf33_i_codigo = ($this->tf33_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf33_i_codigo"]:$this->tf33_i_codigo);
       $this->tf33_i_login = ($this->tf33_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["tf33_i_login"]:$this->tf33_i_login);
       $this->tf33_i_fechamento = ($this->tf33_i_fechamento == ""?@$GLOBALS["HTTP_POST_VARS"]["tf33_i_fechamento"]:$this->tf33_i_fechamento);
       $this->tf33_c_nomearquivo = ($this->tf33_c_nomearquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf33_c_nomearquivo"]:$this->tf33_c_nomearquivo);
       if($this->tf33_d_datasistema == ""){
         $this->tf33_d_datasistema_dia = ($this->tf33_d_datasistema_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf33_d_datasistema_dia"]:$this->tf33_d_datasistema_dia);
         $this->tf33_d_datasistema_mes = ($this->tf33_d_datasistema_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf33_d_datasistema_mes"]:$this->tf33_d_datasistema_mes);
         $this->tf33_d_datasistema_ano = ($this->tf33_d_datasistema_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf33_d_datasistema_ano"]:$this->tf33_d_datasistema_ano);
         if($this->tf33_d_datasistema_dia != ""){
            $this->tf33_d_datasistema = $this->tf33_d_datasistema_ano."-".$this->tf33_d_datasistema_mes."-".$this->tf33_d_datasistema_dia;
         }
       }
       $this->tf33_c_horasistema = ($this->tf33_c_horasistema == ""?@$GLOBALS["HTTP_POST_VARS"]["tf33_c_horasistema"]:$this->tf33_c_horasistema);
       $this->tf33_o_arquivo = ($this->tf33_o_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf33_o_arquivo"]:$this->tf33_o_arquivo);
     }else{
       $this->tf33_i_codigo = ($this->tf33_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf33_i_codigo"]:$this->tf33_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($tf33_i_codigo){ 
      $this->atualizacampos();
     if($this->tf33_i_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "tf33_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf33_i_fechamento == null ){ 
       $this->erro_sql = " Campo Competência nao Informado.";
       $this->erro_campo = "tf33_i_fechamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf33_c_nomearquivo == null ){ 
       $this->erro_sql = " Campo Nome do arquivo nao Informado.";
       $this->erro_campo = "tf33_c_nomearquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf33_d_datasistema == null ){ 
       $this->erro_sql = " Campo Data do sistema nao Informado.";
       $this->erro_campo = "tf33_d_datasistema_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf33_c_horasistema == null ){ 
       $this->erro_sql = " Campo Hora do sistema nao Informado.";
       $this->erro_campo = "tf33_c_horasistema";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf33_o_arquivo == null ){ 
       $this->erro_sql = " Campo Arquivo nao Informado.";
       $this->erro_campo = "tf33_o_arquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tf33_i_codigo == "" || $tf33_i_codigo == null ){
       $result = db_query("select nextval('tfd_bpamagnetico_tf33_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tfd_bpamagnetico_tf33_i_codigo_seq do campo: tf33_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tf33_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tfd_bpamagnetico_tf33_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf33_i_codigo)){
         $this->erro_sql = " Campo tf33_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf33_i_codigo = $tf33_i_codigo; 
       }
     }
     if(($this->tf33_i_codigo == null) || ($this->tf33_i_codigo == "") ){ 
       $this->erro_sql = " Campo tf33_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tfd_bpamagnetico(
                                       tf33_i_codigo 
                                      ,tf33_i_login 
                                      ,tf33_i_fechamento 
                                      ,tf33_c_nomearquivo 
                                      ,tf33_d_datasistema 
                                      ,tf33_c_horasistema 
                                      ,tf33_o_arquivo 
                       )
                values (
                                $this->tf33_i_codigo 
                               ,$this->tf33_i_login 
                               ,$this->tf33_i_fechamento 
                               ,'$this->tf33_c_nomearquivo' 
                               ,".($this->tf33_d_datasistema == "null" || $this->tf33_d_datasistema == ""?"null":"'".$this->tf33_d_datasistema."'")." 
                               ,'$this->tf33_c_horasistema' 
                               ,$this->tf33_o_arquivo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tfd_bpamagnetico ($this->tf33_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tfd_bpamagnetico já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tfd_bpamagnetico ($this->tf33_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf33_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tf33_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17320,'$this->tf33_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3066,17320,'','".AddSlashes(pg_result($resaco,0,'tf33_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3066,17321,'','".AddSlashes(pg_result($resaco,0,'tf33_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3066,17322,'','".AddSlashes(pg_result($resaco,0,'tf33_i_fechamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3066,17323,'','".AddSlashes(pg_result($resaco,0,'tf33_c_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3066,17324,'','".AddSlashes(pg_result($resaco,0,'tf33_d_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3066,17325,'','".AddSlashes(pg_result($resaco,0,'tf33_c_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3066,17326,'','".AddSlashes(pg_result($resaco,0,'tf33_o_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tf33_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tfd_bpamagnetico set ";
     $virgula = "";
     if(trim($this->tf33_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf33_i_codigo"])){ 
       $sql  .= $virgula." tf33_i_codigo = $this->tf33_i_codigo ";
       $virgula = ",";
       if(trim($this->tf33_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "tf33_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf33_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf33_i_login"])){ 
       $sql  .= $virgula." tf33_i_login = $this->tf33_i_login ";
       $virgula = ",";
       if(trim($this->tf33_i_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "tf33_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf33_i_fechamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf33_i_fechamento"])){ 
       $sql  .= $virgula." tf33_i_fechamento = $this->tf33_i_fechamento ";
       $virgula = ",";
       if(trim($this->tf33_i_fechamento) == null ){ 
         $this->erro_sql = " Campo Competência nao Informado.";
         $this->erro_campo = "tf33_i_fechamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf33_c_nomearquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf33_c_nomearquivo"])){ 
       $sql  .= $virgula." tf33_c_nomearquivo = '$this->tf33_c_nomearquivo' ";
       $virgula = ",";
       if(trim($this->tf33_c_nomearquivo) == null ){ 
         $this->erro_sql = " Campo Nome do arquivo nao Informado.";
         $this->erro_campo = "tf33_c_nomearquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf33_d_datasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf33_d_datasistema_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf33_d_datasistema_dia"] !="") ){ 
       $sql  .= $virgula." tf33_d_datasistema = '$this->tf33_d_datasistema' ";
       $virgula = ",";
       if(trim($this->tf33_d_datasistema) == null ){ 
         $this->erro_sql = " Campo Data do sistema nao Informado.";
         $this->erro_campo = "tf33_d_datasistema_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf33_d_datasistema_dia"])){ 
         $sql  .= $virgula." tf33_d_datasistema = null ";
         $virgula = ",";
         if(trim($this->tf33_d_datasistema) == null ){ 
           $this->erro_sql = " Campo Data do sistema nao Informado.";
           $this->erro_campo = "tf33_d_datasistema_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf33_c_horasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf33_c_horasistema"])){ 
       $sql  .= $virgula." tf33_c_horasistema = '$this->tf33_c_horasistema' ";
       $virgula = ",";
       if(trim($this->tf33_c_horasistema) == null ){ 
         $this->erro_sql = " Campo Hora do sistema nao Informado.";
         $this->erro_campo = "tf33_c_horasistema";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf33_o_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf33_o_arquivo"])){ 
       $sql  .= $virgula." tf33_o_arquivo = $this->tf33_o_arquivo ";
       $virgula = ",";
       if(trim($this->tf33_o_arquivo) == null ){ 
         $this->erro_sql = " Campo Arquivo nao Informado.";
         $this->erro_campo = "tf33_o_arquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tf33_i_codigo!=null){
       $sql .= " tf33_i_codigo = $this->tf33_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tf33_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17320,'$this->tf33_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf33_i_codigo"]) || $this->tf33_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3066,17320,'".AddSlashes(pg_result($resaco,$conresaco,'tf33_i_codigo'))."','$this->tf33_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf33_i_login"]) || $this->tf33_i_login != "")
           $resac = db_query("insert into db_acount values($acount,3066,17321,'".AddSlashes(pg_result($resaco,$conresaco,'tf33_i_login'))."','$this->tf33_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf33_i_fechamento"]) || $this->tf33_i_fechamento != "")
           $resac = db_query("insert into db_acount values($acount,3066,17322,'".AddSlashes(pg_result($resaco,$conresaco,'tf33_i_fechamento'))."','$this->tf33_i_fechamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf33_c_nomearquivo"]) || $this->tf33_c_nomearquivo != "")
           $resac = db_query("insert into db_acount values($acount,3066,17323,'".AddSlashes(pg_result($resaco,$conresaco,'tf33_c_nomearquivo'))."','$this->tf33_c_nomearquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf33_d_datasistema"]) || $this->tf33_d_datasistema != "")
           $resac = db_query("insert into db_acount values($acount,3066,17324,'".AddSlashes(pg_result($resaco,$conresaco,'tf33_d_datasistema'))."','$this->tf33_d_datasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf33_c_horasistema"]) || $this->tf33_c_horasistema != "")
           $resac = db_query("insert into db_acount values($acount,3066,17325,'".AddSlashes(pg_result($resaco,$conresaco,'tf33_c_horasistema'))."','$this->tf33_c_horasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf33_o_arquivo"]) || $this->tf33_o_arquivo != "")
           $resac = db_query("insert into db_acount values($acount,3066,17326,'".AddSlashes(pg_result($resaco,$conresaco,'tf33_o_arquivo'))."','$this->tf33_o_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_bpamagnetico nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf33_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_bpamagnetico nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf33_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf33_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tf33_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tf33_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17320,'$tf33_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3066,17320,'','".AddSlashes(pg_result($resaco,$iresaco,'tf33_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3066,17321,'','".AddSlashes(pg_result($resaco,$iresaco,'tf33_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3066,17322,'','".AddSlashes(pg_result($resaco,$iresaco,'tf33_i_fechamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3066,17323,'','".AddSlashes(pg_result($resaco,$iresaco,'tf33_c_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3066,17324,'','".AddSlashes(pg_result($resaco,$iresaco,'tf33_d_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3066,17325,'','".AddSlashes(pg_result($resaco,$iresaco,'tf33_c_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3066,17326,'','".AddSlashes(pg_result($resaco,$iresaco,'tf33_o_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tfd_bpamagnetico
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf33_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf33_i_codigo = $tf33_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_bpamagnetico nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf33_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_bpamagnetico nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf33_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf33_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tfd_bpamagnetico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tf33_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_bpamagnetico ";
     $sql .= "      inner join tfd_fechamento  on  tfd_fechamento.tf32_i_codigo = tfd_bpamagnetico.tf33_i_fechamento";
     $sql2 = "";
     if($dbwhere==""){
       if($tf33_i_codigo!=null ){
         $sql2 .= " where tfd_bpamagnetico.tf33_i_codigo = $tf33_i_codigo "; 
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
   function sql_query_file ( $tf33_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_bpamagnetico ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf33_i_codigo!=null ){
         $sql2 .= " where tfd_bpamagnetico.tf33_i_codigo = $tf33_i_codigo "; 
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

/**
 * Função que retorna o sql da produção do BPA
 * @param object $dados
 */
function sql_querry_prd_bpa($oDados) {

  $sGroupBy = '';
  if ($oDados->sTipo == "02" || $oDados->sTipo == "03") {

    $sCampos  = "  select distinct 'I' as prd_orig, ";
    $sCampos .= "         uniaobpa.* from";
 
  } else {

    $sCampos  = "  select distinct 'C' as prd_orig, ";
    $sCampos .= "         prd_ups, ";
    $sCampos .= "         prd_pa, ";
    $sCampos .= "         ' ' as cod_faa, ";
    $sCampos .= "         prd_cbo, ";
    $sCampos .= "         prd_idade, ";
    $sCampos .= "         sum(qt) as prd_qt from ";
    
  }

  $sCamposSub  = " select sd02_v_cnes as prd_ups, ";
  $sCamposSub .= "        lpad(sd63_c_procedimento,10,'0') as prd_pa, ";
  $sCamposSub .= "        tf01_i_codigo as cod_faa, ";	
  $sCamposSub .= "        '$oDados->iCompano".str_pad ($oDados->iCompmes,2, "0", STR_PAD_LEFT )."' as prd_cmp, ";
  $sCamposSub .= "        lpad(rh70_estrutural,6,'0') as prd_cbo,";
  $sCamposSub .= "        (select  s115_c_cartaosus from cgs_cartaosus ";
  $sCamposSub .= "         where s115_i_cgs=cgs.z01_i_numcgs ";
  $sCamposSub .= "         order by  s115_c_tipo asc limit 1) as prd_cnspac, ";
  $sCamposSub .= "        lpad(z01_v_sexo,1,' ') as prd_sexo, ";
  $sCamposSub .= "        lpad('$oDados->iCidade',6,' ') as prd_ibge, ";
  $sCamposSub .= "        lpad(' ',4,' ') as prd_cid, ";
  $sCamposSub .= "        ' 01' as prd_caten, ";
  $sCamposSub .= "        '              ' as prd_naut, ";
  $sCamposSub .= "        ' BPA' as prd_org, ";
  $sCamposSub .= "        lpad(z01_v_nome,30,' ') as prd_nmpac, ";
  $sCamposSub .= "        z01_d_nasc as prd_dtnasc, ";
  $sCamposSub .= "        '99' as prd_raca, ";
  $sCamposSub .= "        null as prd_flh, ";
  $sCamposSub .= "        null as prd_seq, ";
  $sCamposSub .= "        z02_i_cns as prd_cnsmed,";
  $sCamposSub .= "        z01_nome as nome_med, ";
  $sCamposSub .= "        sd03_i_cgm    as cod_prof, ";
  $sCamposSub .= "        z01_i_cgsund  as cod_pac, ";
  $sCamposSub .= "        case when (select  s115_c_cartaosus from cgs_cartaosus"; 
  $sCamposSub .= "                   where s115_i_cgs=cgs.z01_i_numcgs order by  s115_c_tipo asc limit 1)";
  $sCamposSub .= "        is null then false ";
  $sCamposSub .= "        else fc_valida_cns( (select  s115_c_cartaosus from cgs_cartaosus ";
  $sCamposSub .= "                             where s115_i_cgs=cgs.z01_i_numcgs order by  s115_c_tipo asc limit 1) ) ";
  $sCamposSub .= "        end as valida_cns_cgs,";
  $sCamposSub .= "        case when z02_i_cns is null then false ";
  $sCamposSub .= "             else fc_valida_cns(z02_i_cns)  ";
  $sCamposSub .= "        end as valida_cns_med,";
  
  if ($oDados->sTipo == "02" || $oDados->sTipo == "03") {

    $sCamposSub .= "            case when fc_idade(z01_d_nasc,tf18_d_datasaida) > 99 then 40 ";
    $sCamposSub .= "            else fc_idade(z01_d_nasc,tf18_d_datasaida)  ";
    $sCamposSub .= "            end  as prd_idade, ";

  } else {

    $sCamposSub .= "       case when ( select sd73_c_detalhe ";
    $sCamposSub .= "                   from sau_procdetalhe ";
    $sCamposSub .= "                   inner join sau_detalhe on sau_detalhe.sd73_i_codigo = sau_procdetalhe.sd74_i_detalhe ";
    $sCamposSub .= "                   where sau_procdetalhe.sd74_i_procedimento  = sau_procedimento.sd63_i_codigo ";
    $sCamposSub .= "                   and sd73_c_detalhe = '012' ";
    $sCamposSub .= "                   limit 1 ";
    $sCamposSub .= "                 ) = '012' then  ";
    $sCamposSub .= "            case when fc_idade(z01_d_nasc,tf18_d_datasaida) > 99 then 40 ";
    $sCamposSub .= "            else fc_idade(z01_d_nasc,tf18_d_datasaida)  ";
    $sCamposSub .= "            end  ";
    $sCamposSub .= "       else '999'  ";
    $sCamposSub .= "       end as prd_idade, ";

  }
  $sCamposSub .= "        rh70_estrutural ";
  /* Sub consulta */
  $sSqlSub  = " (($sCamposSub, 1 as tipo, tfd_pedidotfd.tf01_i_codigo, ";
  $sSqlSub .= "   case when tfd_passageiroveiculo.tf19_i_fica = 1 then lpad(ceil(tf03_f_distancia / tf24_i_percurso), 6, '0') ";
  $sSqlSub .= "   else lpad(2 * ceil(tf03_f_distancia / tf24_i_percurso), 6, '0') end as prd_qt, ";
  $sSqlSub .= "   case when tfd_passageiroveiculo.tf19_i_fica = 1 then ceil(tf03_f_distancia / tf24_i_percurso)";
  $sSqlSub .= "   else 2 * ceil(tf03_f_distancia / tf24_i_percurso) end as qt, ";
  $sSqlSub .= "   tf18_d_datasaida as prd_dtaten ";
  $sSqlSub .= "     from tfd_passageiroveiculo ";
  $sSqlSub .= "       left join tfd_veiculodestino on tfd_veiculodestino.tf18_i_codigo = tfd_passageiroveiculo.tf19_i_veiculodestino ";
  $sSqlSub .= "       left join tfd_pedidotfd on tfd_pedidotfd.tf01_i_codigo = tfd_passageiroveiculo.tf19_i_pedidotfd ";
  $sSqlSub .= "       left join tfd_agendamentoprestadora on tfd_agendamentoprestadora.tf16_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo ";
  $sSqlSub .= "       left join tfd_prestadoracentralagend on tfd_prestadoracentralagend.tf10_i_codigo = tfd_agendamentoprestadora.tf16_i_prestcentralagend ";
  $sSqlSub .= "       left join tfd_prestadora on tfd_prestadora.tf25_i_codigo = tfd_prestadoracentralagend.tf10_i_prestadora ";
  $sSqlSub .= "       left join tfd_destino on tfd_destino.tf03_i_codigo = tfd_prestadora.tf25_i_destino ";
  $sSqlSub .= "       left join tfd_tipodistancia on tfd_tipodistancia.tf24_i_codigo = tfd_destino.tf03_i_tipodistancia ";
  $sSqlSub .= "       left join unidades on unidades.sd02_i_codigo = tfd_pedidotfd.tf01_i_depto ";
  $sSqlSub .= "       left join cgs on cgs.z01_i_numcgs = tfd_pedidotfd.tf01_i_cgsund ";
  $sSqlSub .= "       left join cgs_und on cgs_und.z01_i_cgsund = tfd_passageiroveiculo.tf19_i_cgsund ";
  $sSqlSub .= "       left join tfd_procpedidotfd on tfd_procpedidotfd.tf23_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo ";
  $sSqlSub .= "       left join sau_procedimento on sau_procedimento.sd63_i_codigo = tfd_procpedidotfd.tf23_i_procedimento ";
  $sSqlSub .= "       left join sau_financiamento on sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento ";
  $sSqlSub .= "       left join tfd_pedidoregulado on tfd_pedidoregulado.tf34_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo ";
  $sSqlSub .= "       left join especmedico on especmedico.sd27_i_codigo = tfd_pedidoregulado.tf34_i_especmedico ";
  $sSqlSub .= "       left join rhcbo on rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo ";
  $sSqlSub .= "       left join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed ";
  $sSqlSub .= "       left join medicos on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico ";
  $sSqlSub .= "       left join cgm m on m.z01_numcgm = medicos.sd03_i_cgm ";
  $sSqlSub .= "       left join cgmdoc on cgmdoc.z02_i_cgm = m.z01_numcgm ";
  $sSqlSub .= "         where tfd_veiculodestino.tf18_d_datasaida between '".$oDados->dIni."' and '".$oDados->dFim."'";
  
  /*=============================================================================
   * CLAUSULA WHERE COM SUBSELECT PARA VALIDAR CAMPO tf12_faturaBPA 
   * ( só entra se tiver ajuda de custo e se tiver o campo com valor 1 = TRUE) 
   *=============================================================================
   */
  $sSubSelectFaturaAjudaCustoBPA  = " and exists (select tf12_faturabpa from tfd_ajudacusto ";
  $sSubSelectFaturaAjudaCustoBPA .= " inner join tfd_beneficiadosajudacusto on ";
  $sSubSelectFaturaAjudaCustoBPA .= " tfd_beneficiadosajudacusto.tf15_i_ajudacusto = tfd_ajudacusto.tf12_i_codigo ";
  $sSubSelectFaturaAjudaCustoBPA .= " inner join tfd_ajudacustopedido on ";
  $sSubSelectFaturaAjudaCustoBPA .= " tfd_ajudacustopedido.tf14_i_codigo = tfd_beneficiadosajudacusto.tf15_i_ajudacustopedido ";
  $sSubSelectFaturaAjudaCustoBPA .= " where tfd_ajudacustopedido.tf14_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo ";
  $sSubSelectFaturaAjudaCustoBPA .= " and tfd_ajudacusto.tf12_faturabpa = 1 ) ";

  $sSqlSub .= $sSubSelectFaturaAjudaCustoBPA;
  
  if ($oDados->sTipo != "03") {

    $sSqlSub .= "           and exists (select *  ";
    $sSqlSub .= "                       from sau_procregistro ";
    $sSqlSub .= "                       inner join sau_registro  on sau_registro.sd84_i_codigo = sau_procregistro.sd85_i_registro";
    $sSqlSub .= "                                               and sau_registro.sd84_c_registro = '$oDados->sTipo'  ";
    $sSqlSub .= "                       where sau_procregistro.sd85_i_procedimento = sau_procedimento.sd63_i_codigo ";
    $sSqlSub .= "                      ) ";

  }
  if ($oDados->iUnidade != "") {
    $sSqlSub .= "           and tf01_i_depto in (".$oDados->iUnidade.")";
  }
  if ($oDados->iFinanciamento != 0) {
    $sSqlSub .= " and  sd65_c_financiamento = (select sd65_c_financiamento from sau_financiamento where sd65_i_codigo=$oDados->iFinanciamento) ";
  }
  $sSqlSub .= " and tfd_passageiroveiculo.tf19_i_valido = 1 and tf19_i_tipopassageiro = 1) ";

  $sSqlSub .= "                            union";

  $sSqlSub .= "  ($sCamposSub, 1 as tipo, tfd_pedidotfd.tf01_i_codigo, lpad(ceil(tf03_f_distancia / tf24_i_percurso), 6, '0') as prd_qt, ";
  $sSqlSub .= "   ceil(tf03_f_distancia / tf24_i_percurso) as qt,";
  $sSqlSub .= "   tf18_d_dataretorno as prd_dtaten ";
  $sSqlSub .= "     from tfd_passageiroretorno ";
  $sSqlSub .= "       left join tfd_veiculodestino on tfd_veiculodestino.tf18_i_codigo = tfd_passageiroretorno.tf31_i_veiculodestino ";
  $sSqlSub .= "       left join tfd_passageiroveiculo on tfd_passageiroveiculo.tf19_i_codigo = tfd_passageiroretorno.tf31_i_passageiroveiculo ";
  $sSqlSub .= "       left join tfd_pedidotfd on tfd_pedidotfd.tf01_i_codigo = tfd_passageiroveiculo.tf19_i_pedidotfd ";
  $sSqlSub .= "       left join tfd_agendamentoprestadora on tfd_agendamentoprestadora.tf16_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo ";
  $sSqlSub .= "       left join tfd_prestadoracentralagend on tfd_prestadoracentralagend.tf10_i_codigo = tfd_agendamentoprestadora.tf16_i_prestcentralagend ";
  $sSqlSub .= "       left join tfd_prestadora on tfd_prestadora.tf25_i_codigo = tfd_prestadoracentralagend.tf10_i_prestadora ";
  $sSqlSub .= "       left join tfd_destino on tfd_destino.tf03_i_codigo = tfd_prestadora.tf25_i_destino ";
  $sSqlSub .= "       left join tfd_tipodistancia on tfd_tipodistancia.tf24_i_codigo = tfd_destino.tf03_i_tipodistancia ";
  $sSqlSub .= "       left join unidades on unidades.sd02_i_codigo = tfd_pedidotfd.tf01_i_depto ";
  $sSqlSub .= "       left join cgs on cgs.z01_i_numcgs = tfd_passageiroveiculo.tf19_i_cgsund ";
  $sSqlSub .= "       left join cgs_und on cgs_und.z01_i_cgsund = cgs.z01_i_numcgs ";
  $sSqlSub .= "       left join tfd_procpedidotfd on tfd_procpedidotfd.tf23_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo ";
  $sSqlSub .= "       left join sau_procedimento on sau_procedimento.sd63_i_codigo = tfd_procpedidotfd.tf23_i_procedimento ";
  $sSqlSub .= "       left join sau_financiamento on sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento ";
  $sSqlSub .= "       left join tfd_pedidoregulado on tfd_pedidoregulado.tf34_i_pedidotfd = tfd_pedidotfd.tf01_i_codigo ";
  $sSqlSub .= "       left join especmedico on especmedico.sd27_i_codigo = tfd_pedidoregulado.tf34_i_especmedico ";
  $sSqlSub .= "       left join rhcbo on rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo ";
  $sSqlSub .= "       left join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed ";
  $sSqlSub .= "       left join medicos on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico ";
  $sSqlSub .= "       left join cgm m on m.z01_numcgm = medicos.sd03_i_cgm ";
  $sSqlSub .= "       left join cgmdoc on cgmdoc.z02_i_cgm = m.z01_numcgm ";
  $sSqlSub .= "         where tfd_veiculodestino.tf18_d_dataretorno between '".$oDados->dIni."' and '".$oDados->dFim."'";
  if ($oDados->sTipo != "03") {

    $sSqlSub .= "           and exists (select *  ";
    $sSqlSub .= "                       from sau_procregistro ";
    $sSqlSub .= "                       inner join sau_registro  on sau_registro.sd84_i_codigo = sau_procregistro.sd85_i_registro";
    $sSqlSub .= "                                               and sau_registro.sd84_c_registro = '$oDados->sTipo'  ";
    $sSqlSub .= "                       where sau_procregistro.sd85_i_procedimento = sau_procedimento.sd63_i_codigo ";
    $sSqlSub .= "                      ) ";

  }
  if ($oDados->iUnidade != "") {
    $sSqlSub .= "           and tf01_i_depto in (".$oDados->iUnidade.")";
  }
  if ($oDados->iFinanciamento != 0) {
    $sSqlSub .= " and  sd65_c_financiamento = (select sd65_c_financiamento from sau_financiamento where sd65_i_codigo=$oDados->iFinanciamento) ";
  }
  $sSqlSub .= " and tfd_passageiroretorno.tf31_i_valido = 1 and tf19_i_tipopassageiro = 1)) as uniaobpa ";


  if ($oDados->sTipo == "01") {
    $sGroupBy = " group by prd_ups, prd_pa, prd_cbo, prd_idade ";
  }
  $sOrderBy = " order by prd_cbo, prd_pa ";

  return $sCampos.$sSqlSub.$sGroupBy.$sOrderBy;

}

/**
 * Função que retorna o sql da produção do BPA
 * @param object $dados  
 */
function sql_querry_cbr_bpa($oDados,$sSql) {

  $sSql1  = " select ";
  $sSql1 .= "   '#BPA#' as cbc_hdr, ";
  $sSql1 .= "   lpad($oDados->iCompano,4,'0')||lpad($oDados->iCompmes,2,'0')  as cbc_mvm,"; 
  $sSql1 .= "   lpad($oDados->iLinhas,6,'0')  as cbc_lin,";
  $sSql1 .= "   lpad(ceil($oDados->iLinhas/20),6,'0')  as cbc_flh,";
  $sSql1 .= "   '$oDados->sOrgResp'  as cbc_rsp, ";
  $sSql1 .= "   lpad('$oDados->sSigla',6,' ')  as cbc_sgl, ";
  $sSql1 .= "   (select cgc from db_config where codigo = ".db_getsession ( "DB_instit" ).") as cbc_cgccpf, ";
  $sSql1 .= "   lpad('$oDados->sDestino',40,' ')  as cbc_dst, ";
  $sSql1 .= "   'M' as cbc_dst_in, ";
  $sSql1 .= "   lpad('$oDados->sVersao',10,' ') as cbc_versao, ";
  $sSql1 .= "   (sum(prd_pa::bigint)+sum(prd_qt::bigint))%1111+1111 as cbc_smt_vrf ";
  $sSql1 .= " from ($sSql) as a ";

  return $sSql1;

}

}
?>