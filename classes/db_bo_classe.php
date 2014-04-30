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

//MODULO: teleatend
//CLASSE DA ENTIDADE bo
class cl_bo { 
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
   var $bo01_codbo = 0; 
   var $bo01_numcgm = 0; 
   var $bo01_codtipo = 0; 
   var $bo01_obs = null; 
   var $bo01_data_dia = null; 
   var $bo01_data_mes = null; 
   var $bo01_data_ano = null; 
   var $bo01_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 bo01_codbo = int4 = Número do BO 
                 bo01_numcgm = int4 = Numcgm 
                 bo01_codtipo = int4 = Código Tipo 
                 bo01_obs = text = Observação 
                 bo01_data = date = Data de Abertura 
                 ";
   //funcao construtor da classe 
   function cl_bo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bo"); 
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
       $this->bo01_codbo = ($this->bo01_codbo == ""?@$GLOBALS["HTTP_POST_VARS"]["bo01_codbo"]:$this->bo01_codbo);
       $this->bo01_numcgm = ($this->bo01_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["bo01_numcgm"]:$this->bo01_numcgm);
       $this->bo01_codtipo = ($this->bo01_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["bo01_codtipo"]:$this->bo01_codtipo);
       $this->bo01_obs = ($this->bo01_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["bo01_obs"]:$this->bo01_obs);
       if($this->bo01_data == ""){
         $this->bo01_data_dia = ($this->bo01_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["bo01_data_dia"]:$this->bo01_data_dia);
         $this->bo01_data_mes = ($this->bo01_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["bo01_data_mes"]:$this->bo01_data_mes);
         $this->bo01_data_ano = ($this->bo01_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["bo01_data_ano"]:$this->bo01_data_ano);
         if($this->bo01_data_dia != ""){
            $this->bo01_data = $this->bo01_data_ano."-".$this->bo01_data_mes."-".$this->bo01_data_dia;
         }
       }
     }else{
       $this->bo01_codbo = ($this->bo01_codbo == ""?@$GLOBALS["HTTP_POST_VARS"]["bo01_codbo"]:$this->bo01_codbo);
     }
   }
   // funcao para inclusao
   function incluir ($bo01_codbo){ 
      $this->atualizacampos();
     if($this->bo01_numcgm == null ){ 
       $this->erro_sql = " Campo Numcgm nao Informado.";
       $this->erro_campo = "bo01_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bo01_codtipo == null ){ 
       $this->erro_sql = " Campo Código Tipo nao Informado.";
       $this->erro_campo = "bo01_codtipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bo01_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "bo01_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bo01_data == null ){ 
       $this->erro_sql = " Campo Data de Abertura nao Informado.";
       $this->erro_campo = "bo01_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($bo01_codbo == "" || $bo01_codbo == null ){
       $result = db_query("select nextval('tel_codbo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tel_codbo_seq do campo: bo01_codbo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->bo01_codbo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tel_codbo_seq");
       if(($result != false) && (pg_result($result,0,0) < $bo01_codbo)){
         $this->erro_sql = " Campo bo01_codbo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->bo01_codbo = $bo01_codbo; 
       }
     }
     if(($this->bo01_codbo == null) || ($this->bo01_codbo == "") ){ 
       $this->erro_sql = " Campo bo01_codbo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bo(
                                       bo01_codbo 
                                      ,bo01_numcgm 
                                      ,bo01_codtipo 
                                      ,bo01_obs 
                                      ,bo01_data 
                       )
                values (
                                $this->bo01_codbo 
                               ,$this->bo01_numcgm 
                               ,$this->bo01_codtipo 
                               ,'$this->bo01_obs' 
                               ,".($this->bo01_data == "null" || $this->bo01_data == ""?"null":"'".$this->bo01_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Boletim de Ocorrência ($this->bo01_codbo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Boletim de Ocorrência já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Boletim de Ocorrência ($this->bo01_codbo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bo01_codbo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->bo01_codbo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8580,'$this->bo01_codbo','I')");
       $resac = db_query("insert into db_acount values($acount,1458,8580,'','".AddSlashes(pg_result($resaco,0,'bo01_codbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1458,8581,'','".AddSlashes(pg_result($resaco,0,'bo01_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1458,8582,'','".AddSlashes(pg_result($resaco,0,'bo01_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1458,8583,'','".AddSlashes(pg_result($resaco,0,'bo01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1458,8584,'','".AddSlashes(pg_result($resaco,0,'bo01_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($bo01_codbo=null) { 
      $this->atualizacampos();
     $sql = " update bo set ";
     $virgula = "";
     if(trim($this->bo01_codbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo01_codbo"])){ 
       $sql  .= $virgula." bo01_codbo = $this->bo01_codbo ";
       $virgula = ",";
       if(trim($this->bo01_codbo) == null ){ 
         $this->erro_sql = " Campo Número do BO nao Informado.";
         $this->erro_campo = "bo01_codbo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bo01_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo01_numcgm"])){ 
       $sql  .= $virgula." bo01_numcgm = $this->bo01_numcgm ";
       $virgula = ",";
       if(trim($this->bo01_numcgm) == null ){ 
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "bo01_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bo01_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo01_codtipo"])){ 
       $sql  .= $virgula." bo01_codtipo = $this->bo01_codtipo ";
       $virgula = ",";
       if(trim($this->bo01_codtipo) == null ){ 
         $this->erro_sql = " Campo Código Tipo nao Informado.";
         $this->erro_campo = "bo01_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bo01_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo01_obs"])){ 
       $sql  .= $virgula." bo01_obs = '$this->bo01_obs' ";
       $virgula = ",";
       if(trim($this->bo01_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "bo01_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bo01_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bo01_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["bo01_data_dia"] !="") ){ 
       $sql  .= $virgula." bo01_data = '$this->bo01_data' ";
       $virgula = ",";
       if(trim($this->bo01_data) == null ){ 
         $this->erro_sql = " Campo Data de Abertura nao Informado.";
         $this->erro_campo = "bo01_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["bo01_data_dia"])){ 
         $sql  .= $virgula." bo01_data = null ";
         $virgula = ",";
         if(trim($this->bo01_data) == null ){ 
           $this->erro_sql = " Campo Data de Abertura nao Informado.";
           $this->erro_campo = "bo01_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($bo01_codbo!=null){
       $sql .= " bo01_codbo = $this->bo01_codbo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->bo01_codbo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8580,'$this->bo01_codbo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo01_codbo"]))
           $resac = db_query("insert into db_acount values($acount,1458,8580,'".AddSlashes(pg_result($resaco,$conresaco,'bo01_codbo'))."','$this->bo01_codbo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo01_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,1458,8581,'".AddSlashes(pg_result($resaco,$conresaco,'bo01_numcgm'))."','$this->bo01_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo01_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,1458,8582,'".AddSlashes(pg_result($resaco,$conresaco,'bo01_codtipo'))."','$this->bo01_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo01_obs"]))
           $resac = db_query("insert into db_acount values($acount,1458,8583,'".AddSlashes(pg_result($resaco,$conresaco,'bo01_obs'))."','$this->bo01_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["bo01_data"]))
           $resac = db_query("insert into db_acount values($acount,1458,8584,'".AddSlashes(pg_result($resaco,$conresaco,'bo01_data'))."','$this->bo01_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Boletim de Ocorrência nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bo01_codbo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Boletim de Ocorrência nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bo01_codbo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bo01_codbo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($bo01_codbo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($bo01_codbo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8580,'$bo01_codbo','E')");
         $resac = db_query("insert into db_acount values($acount,1458,8580,'','".AddSlashes(pg_result($resaco,$iresaco,'bo01_codbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1458,8581,'','".AddSlashes(pg_result($resaco,$iresaco,'bo01_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1458,8582,'','".AddSlashes(pg_result($resaco,$iresaco,'bo01_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1458,8583,'','".AddSlashes(pg_result($resaco,$iresaco,'bo01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1458,8584,'','".AddSlashes(pg_result($resaco,$iresaco,'bo01_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from bo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($bo01_codbo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " bo01_codbo = $bo01_codbo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Boletim de Ocorrência nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bo01_codbo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Boletim de Ocorrência nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bo01_codbo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$bo01_codbo;
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
        $this->erro_sql   = "Record Vazio na Tabela:bo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $bo01_codbo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bo ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bo.bo01_numcgm";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = bo.bo01_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($bo01_codbo!=null ){
         $sql2 .= " where bo.bo01_codbo = $bo01_codbo "; 
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
   function sql_query_file ( $bo01_codbo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bo ";
     $sql2 = "";
     if($dbwhere==""){
       if($bo01_codbo!=null ){
         $sql2 .= " where bo.bo01_codbo = $bo01_codbo "; 
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