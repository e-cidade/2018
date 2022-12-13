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

//MODULO: caixa
//CLASSE DA ENTIDADE conciliazeralog
class cl_conciliazeralog { 
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
   var $k123_sequencial = 0; 
   var $k123_data_dia = null; 
   var $k123_data_mes = null; 
   var $k123_data_ano = null; 
   var $k123_data = null; 
   var $k123_hora = null; 
   var $k123_id_usuario = 0; 
   var $k123_obs = null; 
   var $k132_filtros = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k123_sequencial = int4 = Sequencial 
                 k123_data = date = Data 
                 k123_hora = char(5) = Hora 
                 k123_id_usuario = int4 = Id Usuario 
                 k123_obs = text = Observações 
                 k132_filtros = text = Filtros 
                 ";
   //funcao construtor da classe 
   function cl_conciliazeralog() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conciliazeralog"); 
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
       $this->k123_sequencial = ($this->k123_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k123_sequencial"]:$this->k123_sequencial);
       if($this->k123_data == ""){
         $this->k123_data_dia = ($this->k123_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k123_data_dia"]:$this->k123_data_dia);
         $this->k123_data_mes = ($this->k123_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k123_data_mes"]:$this->k123_data_mes);
         $this->k123_data_ano = ($this->k123_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k123_data_ano"]:$this->k123_data_ano);
         if($this->k123_data_dia != ""){
            $this->k123_data = $this->k123_data_ano."-".$this->k123_data_mes."-".$this->k123_data_dia;
         }
       }
       $this->k123_hora = ($this->k123_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["k123_hora"]:$this->k123_hora);
       $this->k123_id_usuario = ($this->k123_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k123_id_usuario"]:$this->k123_id_usuario);
       $this->k123_obs = ($this->k123_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["k123_obs"]:$this->k123_obs);
       $this->k132_filtros = ($this->k132_filtros == ""?@$GLOBALS["HTTP_POST_VARS"]["k132_filtros"]:$this->k132_filtros);
     }else{
       $this->k123_sequencial = ($this->k123_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k123_sequencial"]:$this->k123_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k123_sequencial){ 
      $this->atualizacampos();
     if($this->k123_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "k123_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k123_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "k123_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k123_id_usuario == null ){ 
       $this->erro_sql = " Campo Id Usuario nao Informado.";
       $this->erro_campo = "k123_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k123_obs == null ){ 
       $this->erro_sql = " Campo Observações nao Informado.";
       $this->erro_campo = "k123_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k132_filtros == null ){ 
       $this->erro_sql = " Campo Filtros nao Informado.";
       $this->erro_campo = "k132_filtros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k123_sequencial == "" || $k123_sequencial == null ){
       $result = db_query("select nextval('conciliazeralog_k123_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conciliazeralog_k123_sequencial_seq do campo: k123_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k123_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from conciliazeralog_k123_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k123_sequencial)){
         $this->erro_sql = " Campo k123_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k123_sequencial = $k123_sequencial; 
       }
     }
     if(($this->k123_sequencial == null) || ($this->k123_sequencial == "") ){ 
       $this->erro_sql = " Campo k123_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conciliazeralog(
                                       k123_sequencial 
                                      ,k123_data 
                                      ,k123_hora 
                                      ,k123_id_usuario 
                                      ,k123_obs 
                                      ,k132_filtros 
                       )
                values (
                                $this->k123_sequencial 
                               ,".($this->k123_data == "null" || $this->k123_data == ""?"null":"'".$this->k123_data."'")." 
                               ,'$this->k123_hora' 
                               ,$this->k123_id_usuario 
                               ,'$this->k123_obs' 
                               ,'$this->k132_filtros' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Log do zeramento de conciliação ($this->k123_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Log do zeramento de conciliação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Log do zeramento de conciliação ($this->k123_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k123_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k123_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17666,'$this->k123_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3120,17666,'','".AddSlashes(pg_result($resaco,0,'k123_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3120,17667,'','".AddSlashes(pg_result($resaco,0,'k123_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3120,17668,'','".AddSlashes(pg_result($resaco,0,'k123_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3120,17669,'','".AddSlashes(pg_result($resaco,0,'k123_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3120,17670,'','".AddSlashes(pg_result($resaco,0,'k123_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3120,17671,'','".AddSlashes(pg_result($resaco,0,'k132_filtros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k123_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update conciliazeralog set ";
     $virgula = "";
     if(trim($this->k123_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k123_sequencial"])){ 
       $sql  .= $virgula." k123_sequencial = $this->k123_sequencial ";
       $virgula = ",";
       if(trim($this->k123_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k123_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k123_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k123_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k123_data_dia"] !="") ){ 
       $sql  .= $virgula." k123_data = '$this->k123_data' ";
       $virgula = ",";
       if(trim($this->k123_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "k123_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k123_data_dia"])){ 
         $sql  .= $virgula." k123_data = null ";
         $virgula = ",";
         if(trim($this->k123_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "k123_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k123_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k123_hora"])){ 
       $sql  .= $virgula." k123_hora = '$this->k123_hora' ";
       $virgula = ",";
       if(trim($this->k123_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "k123_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k123_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k123_id_usuario"])){ 
       $sql  .= $virgula." k123_id_usuario = $this->k123_id_usuario ";
       $virgula = ",";
       if(trim($this->k123_id_usuario) == null ){ 
         $this->erro_sql = " Campo Id Usuario nao Informado.";
         $this->erro_campo = "k123_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k123_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k123_obs"])){ 
       $sql  .= $virgula." k123_obs = '$this->k123_obs' ";
       $virgula = ",";
       if(trim($this->k123_obs) == null ){ 
         $this->erro_sql = " Campo Observações nao Informado.";
         $this->erro_campo = "k123_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k132_filtros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k132_filtros"])){ 
       $sql  .= $virgula." k132_filtros = '$this->k132_filtros' ";
       $virgula = ",";
       if(trim($this->k132_filtros) == null ){ 
         $this->erro_sql = " Campo Filtros nao Informado.";
         $this->erro_campo = "k132_filtros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k123_sequencial!=null){
       $sql .= " k123_sequencial = $this->k123_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k123_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17666,'$this->k123_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k123_sequencial"]) || $this->k123_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3120,17666,'".AddSlashes(pg_result($resaco,$conresaco,'k123_sequencial'))."','$this->k123_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k123_data"]) || $this->k123_data != "")
           $resac = db_query("insert into db_acount values($acount,3120,17667,'".AddSlashes(pg_result($resaco,$conresaco,'k123_data'))."','$this->k123_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k123_hora"]) || $this->k123_hora != "")
           $resac = db_query("insert into db_acount values($acount,3120,17668,'".AddSlashes(pg_result($resaco,$conresaco,'k123_hora'))."','$this->k123_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k123_id_usuario"]) || $this->k123_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3120,17669,'".AddSlashes(pg_result($resaco,$conresaco,'k123_id_usuario'))."','$this->k123_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k123_obs"]) || $this->k123_obs != "")
           $resac = db_query("insert into db_acount values($acount,3120,17670,'".AddSlashes(pg_result($resaco,$conresaco,'k123_obs'))."','$this->k123_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k132_filtros"]) || $this->k132_filtros != "")
           $resac = db_query("insert into db_acount values($acount,3120,17671,'".AddSlashes(pg_result($resaco,$conresaco,'k132_filtros'))."','$this->k132_filtros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Log do zeramento de conciliação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k123_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Log do zeramento de conciliação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k123_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k123_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k123_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k123_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17666,'$k123_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3120,17666,'','".AddSlashes(pg_result($resaco,$iresaco,'k123_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3120,17667,'','".AddSlashes(pg_result($resaco,$iresaco,'k123_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3120,17668,'','".AddSlashes(pg_result($resaco,$iresaco,'k123_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3120,17669,'','".AddSlashes(pg_result($resaco,$iresaco,'k123_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3120,17670,'','".AddSlashes(pg_result($resaco,$iresaco,'k123_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3120,17671,'','".AddSlashes(pg_result($resaco,$iresaco,'k132_filtros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conciliazeralog
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k123_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k123_sequencial = $k123_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Log do zeramento de conciliação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k123_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Log do zeramento de conciliação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k123_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k123_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:conciliazeralog";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k123_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conciliazeralog ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = conciliazeralog.k123_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($k123_sequencial!=null ){
         $sql2 .= " where conciliazeralog.k123_sequencial = $k123_sequencial "; 
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
   function sql_query_file ( $k123_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conciliazeralog ";
     $sql2 = "";
     if($dbwhere==""){
       if($k123_sequencial!=null ){
         $sql2 .= " where conciliazeralog.k123_sequencial = $k123_sequencial "; 
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