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

//MODULO: contrib
//CLASSE DA ENTIDADE projmelhorias
class cl_projmelhorias { 
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
   var $d40_codigo = 0; 
   var $d40_data_dia = null; 
   var $d40_data_mes = null; 
   var $d40_data_ano = null; 
   var $d40_data = null; 
   var $d40_login = 0; 
   var $d40_codlog = 0; 
   var $d40_trecho = null; 
   var $d40_profun = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d40_codigo = int4 = Lista de projeto 
                 d40_data = date = Data 
                 d40_login = int4 = Login do usuário 
                 d40_codlog = int4 = Código do logradouro 
                 d40_trecho = varchar(100) = Descrição do trecho 
                 d40_profun = float8 = Profundidade 
                 ";
   //funcao construtor da classe 
   function cl_projmelhorias() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("projmelhorias"); 
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
       $this->d40_codigo = ($this->d40_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["d40_codigo"]:$this->d40_codigo);
       if($this->d40_data == ""){
         $this->d40_data_dia = ($this->d40_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["d40_data_dia"]:$this->d40_data_dia);
         $this->d40_data_mes = ($this->d40_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["d40_data_mes"]:$this->d40_data_mes);
         $this->d40_data_ano = ($this->d40_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["d40_data_ano"]:$this->d40_data_ano);
         if($this->d40_data_dia != ""){
            $this->d40_data = $this->d40_data_ano."-".$this->d40_data_mes."-".$this->d40_data_dia;
         }
       }
       $this->d40_login = ($this->d40_login == ""?@$GLOBALS["HTTP_POST_VARS"]["d40_login"]:$this->d40_login);
       $this->d40_codlog = ($this->d40_codlog == ""?@$GLOBALS["HTTP_POST_VARS"]["d40_codlog"]:$this->d40_codlog);
       $this->d40_trecho = ($this->d40_trecho == ""?@$GLOBALS["HTTP_POST_VARS"]["d40_trecho"]:$this->d40_trecho);
       $this->d40_profun = ($this->d40_profun == ""?@$GLOBALS["HTTP_POST_VARS"]["d40_profun"]:$this->d40_profun);
     }else{
       $this->d40_codigo = ($this->d40_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["d40_codigo"]:$this->d40_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($d40_codigo){ 
      $this->atualizacampos();
     if($this->d40_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "d40_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d40_login == null ){ 
       $this->erro_sql = " Campo Login do usuário nao Informado.";
       $this->erro_campo = "d40_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d40_codlog == null ){ 
       $this->erro_sql = " Campo Código do logradouro nao Informado.";
       $this->erro_campo = "d40_codlog";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d40_trecho == null ){ 
       $this->erro_sql = " Campo Descrição do trecho nao Informado.";
       $this->erro_campo = "d40_trecho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d40_profun == null ){ 
       $this->erro_sql = " Campo Profundidade nao Informado.";
       $this->erro_campo = "d40_profun";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($d40_codigo == "" || $d40_codigo == null ){
       $result = db_query("select nextval('projmelhorias_d40_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: projmelhorias_d40_codigo_seq do campo: d40_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->d40_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from projmelhorias_d40_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $d40_codigo)){
         $this->erro_sql = " Campo d40_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->d40_codigo = $d40_codigo; 
       }
     }
     if(($this->d40_codigo == null) || ($this->d40_codigo == "") ){ 
       $this->erro_sql = " Campo d40_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into projmelhorias(
                                       d40_codigo 
                                      ,d40_data 
                                      ,d40_login 
                                      ,d40_codlog 
                                      ,d40_trecho 
                                      ,d40_profun 
                       )
                values (
                                $this->d40_codigo 
                               ,".($this->d40_data == "null" || $this->d40_data == ""?"null":"'".$this->d40_data."'")." 
                               ,$this->d40_login 
                               ,$this->d40_codlog 
                               ,'$this->d40_trecho' 
                               ,$this->d40_profun 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Listas dos projetos ($this->d40_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Listas dos projetos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Listas dos projetos ($this->d40_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d40_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d40_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3572,'$this->d40_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,513,3572,'','".AddSlashes(pg_result($resaco,0,'d40_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,513,3573,'','".AddSlashes(pg_result($resaco,0,'d40_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,513,3574,'','".AddSlashes(pg_result($resaco,0,'d40_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,513,3575,'','".AddSlashes(pg_result($resaco,0,'d40_codlog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,513,3576,'','".AddSlashes(pg_result($resaco,0,'d40_trecho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,513,5179,'','".AddSlashes(pg_result($resaco,0,'d40_profun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($d40_codigo=null) { 
      $this->atualizacampos();
     $sql = " update projmelhorias set ";
     $virgula = "";
     if(trim($this->d40_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d40_codigo"])){ 
       $sql  .= $virgula." d40_codigo = $this->d40_codigo ";
       $virgula = ",";
       if(trim($this->d40_codigo) == null ){ 
         $this->erro_sql = " Campo Lista de projeto nao Informado.";
         $this->erro_campo = "d40_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d40_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d40_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["d40_data_dia"] !="") ){ 
       $sql  .= $virgula." d40_data = '$this->d40_data' ";
       $virgula = ",";
       if(trim($this->d40_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "d40_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["d40_data_dia"])){ 
         $sql  .= $virgula." d40_data = null ";
         $virgula = ",";
         if(trim($this->d40_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "d40_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->d40_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d40_login"])){ 
       $sql  .= $virgula." d40_login = $this->d40_login ";
       $virgula = ",";
       if(trim($this->d40_login) == null ){ 
         $this->erro_sql = " Campo Login do usuário nao Informado.";
         $this->erro_campo = "d40_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d40_codlog)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d40_codlog"])){ 
       $sql  .= $virgula." d40_codlog = $this->d40_codlog ";
       $virgula = ",";
       if(trim($this->d40_codlog) == null ){ 
         $this->erro_sql = " Campo Código do logradouro nao Informado.";
         $this->erro_campo = "d40_codlog";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d40_trecho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d40_trecho"])){ 
       $sql  .= $virgula." d40_trecho = '$this->d40_trecho' ";
       $virgula = ",";
       if(trim($this->d40_trecho) == null ){ 
         $this->erro_sql = " Campo Descrição do trecho nao Informado.";
         $this->erro_campo = "d40_trecho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d40_profun)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d40_profun"])){ 
       $sql  .= $virgula." d40_profun = $this->d40_profun ";
       $virgula = ",";
       if(trim($this->d40_profun) == null ){ 
         $this->erro_sql = " Campo Profundidade nao Informado.";
         $this->erro_campo = "d40_profun";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($d40_codigo!=null){
       $sql .= " d40_codigo = $this->d40_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d40_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3572,'$this->d40_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d40_codigo"]))
           $resac = db_query("insert into db_acount values($acount,513,3572,'".AddSlashes(pg_result($resaco,$conresaco,'d40_codigo'))."','$this->d40_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d40_data"]))
           $resac = db_query("insert into db_acount values($acount,513,3573,'".AddSlashes(pg_result($resaco,$conresaco,'d40_data'))."','$this->d40_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d40_login"]))
           $resac = db_query("insert into db_acount values($acount,513,3574,'".AddSlashes(pg_result($resaco,$conresaco,'d40_login'))."','$this->d40_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d40_codlog"]))
           $resac = db_query("insert into db_acount values($acount,513,3575,'".AddSlashes(pg_result($resaco,$conresaco,'d40_codlog'))."','$this->d40_codlog',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d40_trecho"]))
           $resac = db_query("insert into db_acount values($acount,513,3576,'".AddSlashes(pg_result($resaco,$conresaco,'d40_trecho'))."','$this->d40_trecho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d40_profun"]))
           $resac = db_query("insert into db_acount values($acount,513,5179,'".AddSlashes(pg_result($resaco,$conresaco,'d40_profun'))."','$this->d40_profun',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Listas dos projetos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d40_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Listas dos projetos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d40_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d40_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($d40_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d40_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3572,'$d40_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,513,3572,'','".AddSlashes(pg_result($resaco,$iresaco,'d40_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,513,3573,'','".AddSlashes(pg_result($resaco,$iresaco,'d40_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,513,3574,'','".AddSlashes(pg_result($resaco,$iresaco,'d40_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,513,3575,'','".AddSlashes(pg_result($resaco,$iresaco,'d40_codlog'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,513,3576,'','".AddSlashes(pg_result($resaco,$iresaco,'d40_trecho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,513,5179,'','".AddSlashes(pg_result($resaco,$iresaco,'d40_profun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from projmelhorias
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d40_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d40_codigo = $d40_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Listas dos projetos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d40_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Listas dos projetos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d40_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d40_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:projmelhorias";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $d40_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from projmelhorias ";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = projmelhorias.d40_codlog";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = projmelhorias.d40_login";
     $sql2 = "";
     if($dbwhere==""){
       if($d40_codigo!=null ){
         $sql2 .= " where projmelhorias.d40_codigo = $d40_codigo "; 
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
   function sql_query_file ( $d40_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from projmelhorias ";
     $sql2 = "";
     if($dbwhere==""){
       if($d40_codigo!=null ){
         $sql2 .= " where projmelhorias.d40_codigo = $d40_codigo "; 
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