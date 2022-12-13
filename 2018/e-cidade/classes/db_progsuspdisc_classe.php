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

//MODULO: educação
//CLASSE DA ENTIDADE progsuspdisc
class cl_progsuspdisc { 
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
   var $ed119_i_codigo = 0; 
   var $ed119_i_progmatricula = 0; 
   var $ed119_i_usuario = 0; 
   var $ed119_d_data_dia = null; 
   var $ed119_d_data_mes = null; 
   var $ed119_d_data_ano = null; 
   var $ed119_d_data = null; 
   var $ed119_t_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed119_i_codigo = int8 = Código 
                 ed119_i_progmatricula = int8 = Matrícula 
                 ed119_i_usuario = int8 = Usuário 
                 ed119_d_data = date = Data 
                 ed119_t_obs = text = Observações 
                 ";
   //funcao construtor da classe 
   function cl_progsuspdisc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("progsuspdisc"); 
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
       $this->ed119_i_codigo = ($this->ed119_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed119_i_codigo"]:$this->ed119_i_codigo);
       $this->ed119_i_progmatricula = ($this->ed119_i_progmatricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed119_i_progmatricula"]:$this->ed119_i_progmatricula);
       $this->ed119_i_usuario = ($this->ed119_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed119_i_usuario"]:$this->ed119_i_usuario);
       if($this->ed119_d_data == ""){
         $this->ed119_d_data_dia = ($this->ed119_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed119_d_data_dia"]:$this->ed119_d_data_dia);
         $this->ed119_d_data_mes = ($this->ed119_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed119_d_data_mes"]:$this->ed119_d_data_mes);
         $this->ed119_d_data_ano = ($this->ed119_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed119_d_data_ano"]:$this->ed119_d_data_ano);
         if($this->ed119_d_data_dia != ""){
            $this->ed119_d_data = $this->ed119_d_data_ano."-".$this->ed119_d_data_mes."-".$this->ed119_d_data_dia;
         }
       }
       $this->ed119_t_obs = ($this->ed119_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed119_t_obs"]:$this->ed119_t_obs);
     }else{
       $this->ed119_i_codigo = ($this->ed119_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed119_i_codigo"]:$this->ed119_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed119_i_codigo){ 
      $this->atualizacampos();
     if($this->ed119_i_progmatricula == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "ed119_i_progmatricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed119_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ed119_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed119_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ed119_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed119_i_codigo == "" || $ed119_i_codigo == null ){
       $result = db_query("select nextval('progsuspdisc_ed119_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: progsuspdisc_ed119_i_codigo_seq do campo: ed119_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed119_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from progsuspdisc_ed119_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed119_i_codigo)){
         $this->erro_sql = " Campo ed119_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed119_i_codigo = $ed119_i_codigo; 
       }
     }
     if(($this->ed119_i_codigo == null) || ($this->ed119_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed119_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into progsuspdisc(
                                       ed119_i_codigo 
                                      ,ed119_i_progmatricula 
                                      ,ed119_i_usuario 
                                      ,ed119_d_data 
                                      ,ed119_t_obs 
                       )
                values (
                                $this->ed119_i_codigo 
                               ,$this->ed119_i_progmatricula 
                               ,$this->ed119_i_usuario 
                               ,".($this->ed119_d_data == "null" || $this->ed119_d_data == ""?"null":"'".$this->ed119_d_data."'")." 
                               ,'$this->ed119_t_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Suspensões Disciplinares do Professor ($this->ed119_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Suspensões Disciplinares do Professor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Suspensões Disciplinares do Professor ($this->ed119_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed119_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed119_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1009123,'$this->ed119_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010175,1009123,'','".AddSlashes(pg_result($resaco,0,'ed119_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010175,1009124,'','".AddSlashes(pg_result($resaco,0,'ed119_i_progmatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010175,1009125,'','".AddSlashes(pg_result($resaco,0,'ed119_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010175,1009126,'','".AddSlashes(pg_result($resaco,0,'ed119_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010175,1009127,'','".AddSlashes(pg_result($resaco,0,'ed119_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed119_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update progsuspdisc set ";
     $virgula = "";
     if(trim($this->ed119_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed119_i_codigo"])){ 
       $sql  .= $virgula." ed119_i_codigo = $this->ed119_i_codigo ";
       $virgula = ",";
       if(trim($this->ed119_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed119_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed119_i_progmatricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed119_i_progmatricula"])){ 
       $sql  .= $virgula." ed119_i_progmatricula = $this->ed119_i_progmatricula ";
       $virgula = ",";
       if(trim($this->ed119_i_progmatricula) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "ed119_i_progmatricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed119_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed119_i_usuario"])){ 
       $sql  .= $virgula." ed119_i_usuario = $this->ed119_i_usuario ";
       $virgula = ",";
       if(trim($this->ed119_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ed119_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed119_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed119_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed119_d_data_dia"] !="") ){ 
       $sql  .= $virgula." ed119_d_data = '$this->ed119_d_data' ";
       $virgula = ",";
       if(trim($this->ed119_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ed119_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed119_d_data_dia"])){ 
         $sql  .= $virgula." ed119_d_data = null ";
         $virgula = ",";
         if(trim($this->ed119_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ed119_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed119_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed119_t_obs"])){ 
       $sql  .= $virgula." ed119_t_obs = '$this->ed119_t_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed119_i_codigo!=null){
       $sql .= " ed119_i_codigo = $this->ed119_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed119_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009123,'$this->ed119_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed119_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010175,1009123,'".AddSlashes(pg_result($resaco,$conresaco,'ed119_i_codigo'))."','$this->ed119_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed119_i_progmatricula"]))
           $resac = db_query("insert into db_acount values($acount,1010175,1009124,'".AddSlashes(pg_result($resaco,$conresaco,'ed119_i_progmatricula'))."','$this->ed119_i_progmatricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed119_i_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1010175,1009125,'".AddSlashes(pg_result($resaco,$conresaco,'ed119_i_usuario'))."','$this->ed119_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed119_d_data"]))
           $resac = db_query("insert into db_acount values($acount,1010175,1009126,'".AddSlashes(pg_result($resaco,$conresaco,'ed119_d_data'))."','$this->ed119_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed119_t_obs"]))
           $resac = db_query("insert into db_acount values($acount,1010175,1009127,'".AddSlashes(pg_result($resaco,$conresaco,'ed119_t_obs'))."','$this->ed119_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Suspensões Disciplinares do Professor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed119_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Suspensões Disciplinares do Professor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed119_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed119_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed119_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed119_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009123,'$ed119_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010175,1009123,'','".AddSlashes(pg_result($resaco,$iresaco,'ed119_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010175,1009124,'','".AddSlashes(pg_result($resaco,$iresaco,'ed119_i_progmatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010175,1009125,'','".AddSlashes(pg_result($resaco,$iresaco,'ed119_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010175,1009126,'','".AddSlashes(pg_result($resaco,$iresaco,'ed119_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010175,1009127,'','".AddSlashes(pg_result($resaco,$iresaco,'ed119_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from progsuspdisc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed119_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed119_i_codigo = $ed119_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Suspensões Disciplinares do Professor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed119_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Suspensões Disciplinares do Professor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed119_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed119_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:progsuspdisc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed119_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progsuspdisc ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = progsuspdisc.ed119_i_usuario";
     $sql .= "      inner join progmatricula  on  progmatricula.ed112_i_codigo = progsuspdisc.ed119_i_progmatricula";
     $sql .= "      inner join progclasse  on  progclasse.ed107_i_codigo = progmatricula.ed112_i_progclasse";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = progmatricula.ed112_i_rhpessoal";
     $sql .= "      inner join db_config  on  db_config.codigo = rhpessoal.rh01_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql2 = "";
     if($dbwhere==""){
       if($ed119_i_codigo!=null ){
         $sql2 .= " where progsuspdisc.ed119_i_codigo = $ed119_i_codigo "; 
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
   function sql_query_file ( $ed119_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progsuspdisc ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed119_i_codigo!=null ){
         $sql2 .= " where progsuspdisc.ed119_i_codigo = $ed119_i_codigo "; 
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