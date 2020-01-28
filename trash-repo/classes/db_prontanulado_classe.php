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

//MODULO: saude
//CLASSE DA ENTIDADE prontanulado
class cl_prontanulado { 
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
   var $sd57_i_codigo = 0; 
   var $sd57_i_prontuario = 0; 
   var $sd57_d_data_dia = null; 
   var $sd57_d_data_mes = null; 
   var $sd57_d_data_ano = null; 
   var $sd57_d_data = null; 
   var $sd57_c_hora = null; 
   var $sd57_i_login = 0; 
   var $sd57_t_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd57_i_codigo = int4 = Código 
                 sd57_i_prontuario = int4 = Prontuário 
                 sd57_d_data = date = Data 
                 sd57_c_hora = varchar(8) = Hora 
                 sd57_i_login = int4 = Login 
                 sd57_t_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_prontanulado() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("prontanulado"); 
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
       $this->sd57_i_codigo = ($this->sd57_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd57_i_codigo"]:$this->sd57_i_codigo);
       $this->sd57_i_prontuario = ($this->sd57_i_prontuario == ""?@$GLOBALS["HTTP_POST_VARS"]["sd57_i_prontuario"]:$this->sd57_i_prontuario);
       if($this->sd57_d_data == ""){
         $this->sd57_d_data_dia = ($this->sd57_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["sd57_d_data_dia"]:$this->sd57_d_data_dia);
         $this->sd57_d_data_mes = ($this->sd57_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["sd57_d_data_mes"]:$this->sd57_d_data_mes);
         $this->sd57_d_data_ano = ($this->sd57_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["sd57_d_data_ano"]:$this->sd57_d_data_ano);
         if($this->sd57_d_data_dia != ""){
            $this->sd57_d_data = $this->sd57_d_data_ano."-".$this->sd57_d_data_mes."-".$this->sd57_d_data_dia;
         }
       }
       $this->sd57_c_hora = ($this->sd57_c_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["sd57_c_hora"]:$this->sd57_c_hora);
       $this->sd57_i_login = ($this->sd57_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["sd57_i_login"]:$this->sd57_i_login);
       $this->sd57_t_obs = ($this->sd57_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["sd57_t_obs"]:$this->sd57_t_obs);
     }else{
       $this->sd57_i_codigo = ($this->sd57_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd57_i_codigo"]:$this->sd57_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd57_i_codigo){ 
      $this->atualizacampos();
     if($this->sd57_i_prontuario == null ){ 
       $this->erro_sql = " Campo Prontuário nao Informado.";
       $this->erro_campo = "sd57_i_prontuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd57_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "sd57_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd57_c_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "sd57_c_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd57_i_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "sd57_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd57_t_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "sd57_t_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd57_i_codigo == "" || $sd57_i_codigo == null ){
       $result = db_query("select nextval('prontanulado_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: prontanulado_codigo_seq do campo: sd57_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd57_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from prontanulado_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd57_i_codigo)){
         $this->erro_sql = " Campo sd57_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd57_i_codigo = $sd57_i_codigo; 
       }
     }
     if(($this->sd57_i_codigo == null) || ($this->sd57_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd57_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into prontanulado(
                                       sd57_i_codigo 
                                      ,sd57_i_prontuario 
                                      ,sd57_d_data 
                                      ,sd57_c_hora 
                                      ,sd57_i_login 
                                      ,sd57_t_obs 
                       )
                values (
                                $this->sd57_i_codigo 
                               ,$this->sd57_i_prontuario 
                               ,".($this->sd57_d_data == "null" || $this->sd57_d_data == ""?"null":"'".$this->sd57_d_data."'")." 
                               ,'$this->sd57_c_hora' 
                               ,$this->sd57_i_login 
                               ,'$this->sd57_t_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Prontuário Anulado ($this->sd57_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Prontuário Anulado já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Prontuário Anulado ($this->sd57_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd57_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd57_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12240,'$this->sd57_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2128,12240,'','".AddSlashes(pg_result($resaco,0,'sd57_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2128,12241,'','".AddSlashes(pg_result($resaco,0,'sd57_i_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2128,12242,'','".AddSlashes(pg_result($resaco,0,'sd57_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2128,12243,'','".AddSlashes(pg_result($resaco,0,'sd57_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2128,12244,'','".AddSlashes(pg_result($resaco,0,'sd57_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2128,12245,'','".AddSlashes(pg_result($resaco,0,'sd57_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd57_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update prontanulado set ";
     $virgula = "";
     if(trim($this->sd57_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd57_i_codigo"])){ 
       $sql  .= $virgula." sd57_i_codigo = $this->sd57_i_codigo ";
       $virgula = ",";
       if(trim($this->sd57_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd57_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd57_i_prontuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd57_i_prontuario"])){ 
       $sql  .= $virgula." sd57_i_prontuario = $this->sd57_i_prontuario ";
       $virgula = ",";
       if(trim($this->sd57_i_prontuario) == null ){ 
         $this->erro_sql = " Campo Prontuário nao Informado.";
         $this->erro_campo = "sd57_i_prontuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd57_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd57_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["sd57_d_data_dia"] !="") ){ 
       $sql  .= $virgula." sd57_d_data = '$this->sd57_d_data' ";
       $virgula = ",";
       if(trim($this->sd57_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "sd57_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["sd57_d_data_dia"])){ 
         $sql  .= $virgula." sd57_d_data = null ";
         $virgula = ",";
         if(trim($this->sd57_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "sd57_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->sd57_c_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd57_c_hora"])){ 
       $sql  .= $virgula." sd57_c_hora = '$this->sd57_c_hora' ";
       $virgula = ",";
       if(trim($this->sd57_c_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "sd57_c_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd57_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd57_i_login"])){ 
       $sql  .= $virgula." sd57_i_login = $this->sd57_i_login ";
       $virgula = ",";
       if(trim($this->sd57_i_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "sd57_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd57_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd57_t_obs"])){ 
       $sql  .= $virgula." sd57_t_obs = '$this->sd57_t_obs' ";
       $virgula = ",";
       if(trim($this->sd57_t_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "sd57_t_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd57_i_codigo!=null){
       $sql .= " sd57_i_codigo = $this->sd57_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd57_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12240,'$this->sd57_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd57_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2128,12240,'".AddSlashes(pg_result($resaco,$conresaco,'sd57_i_codigo'))."','$this->sd57_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd57_i_prontuario"]))
           $resac = db_query("insert into db_acount values($acount,2128,12241,'".AddSlashes(pg_result($resaco,$conresaco,'sd57_i_prontuario'))."','$this->sd57_i_prontuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd57_d_data"]))
           $resac = db_query("insert into db_acount values($acount,2128,12242,'".AddSlashes(pg_result($resaco,$conresaco,'sd57_d_data'))."','$this->sd57_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd57_c_hora"]))
           $resac = db_query("insert into db_acount values($acount,2128,12243,'".AddSlashes(pg_result($resaco,$conresaco,'sd57_c_hora'))."','$this->sd57_c_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd57_i_login"]))
           $resac = db_query("insert into db_acount values($acount,2128,12244,'".AddSlashes(pg_result($resaco,$conresaco,'sd57_i_login'))."','$this->sd57_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd57_t_obs"]))
           $resac = db_query("insert into db_acount values($acount,2128,12245,'".AddSlashes(pg_result($resaco,$conresaco,'sd57_t_obs'))."','$this->sd57_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prontuário Anulado nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd57_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prontuário Anulado nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd57_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd57_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd57_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd57_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12240,'$sd57_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2128,12240,'','".AddSlashes(pg_result($resaco,$iresaco,'sd57_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2128,12241,'','".AddSlashes(pg_result($resaco,$iresaco,'sd57_i_prontuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2128,12242,'','".AddSlashes(pg_result($resaco,$iresaco,'sd57_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2128,12243,'','".AddSlashes(pg_result($resaco,$iresaco,'sd57_c_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2128,12244,'','".AddSlashes(pg_result($resaco,$iresaco,'sd57_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2128,12245,'','".AddSlashes(pg_result($resaco,$iresaco,'sd57_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from prontanulado
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd57_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd57_i_codigo = $sd57_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prontuário Anulado nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd57_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Prontuário Anulado nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd57_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd57_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:prontanulado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd57_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prontanulado ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = prontanulado.sd57_i_login";
     $sql .= "      inner join prontuarios  on  prontuarios.sd24_i_codigo = prontanulado.sd57_i_prontuario";
//     $sql .= "      inner join sau_cid  on  sau_cid.sd70_i_codigo = prontuarios.sd24_i_cid";
//     $sql .= "      inner join sau_siasih  on  sau_siasih.sd92_i_codigo = prontuarios.sd24_i_siasih";
//     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = prontuarios.sd24_i_unidade";
//     $sql .= "      inner join especmedico  on  especmedico.sd27_i_codigo = prontuarios.sd24_i_profissional";
//     $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = prontuarios.sd24_i_numcgs";
     $sql2 = "";
     if($dbwhere==""){
       if($sd57_i_codigo!=null ){
         $sql2 .= " where prontanulado.sd57_i_codigo = $sd57_i_codigo "; 
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
   function sql_query_file ( $sd57_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from prontanulado ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd57_i_codigo!=null ){
         $sql2 .= " where prontanulado.sd57_i_codigo = $sd57_i_codigo "; 
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