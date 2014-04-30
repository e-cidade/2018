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

//MODULO: issqn
//CLASSE DA ENTIDADE issplanitold
class cl_issplanitold { 
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
   var $q73_sequencial = 0; 
   var $q73_issplanitorigem = 0; 
   var $q73_issplanitdestino = 0; 
   var $q73_data_dia = null; 
   var $q73_data_mes = null; 
   var $q73_data_ano = null; 
   var $q73_data = null; 
   var $q73_hora = null; 
   var $q73_ip = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q73_sequencial = int4 = Código 
                 q73_issplanitorigem = int4 = Nota Origem 
                 q73_issplanitdestino = int4 = Nota Destino 
                 q73_data = date = Data 
                 q73_hora = char(5) = Hora 
                 q73_ip = varchar(20) = IP 
                 ";
   //funcao construtor da classe 
   function cl_issplanitold() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issplanitold"); 
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
       $this->q73_sequencial = ($this->q73_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q73_sequencial"]:$this->q73_sequencial);
       $this->q73_issplanitorigem = ($this->q73_issplanitorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["q73_issplanitorigem"]:$this->q73_issplanitorigem);
       $this->q73_issplanitdestino = ($this->q73_issplanitdestino == ""?@$GLOBALS["HTTP_POST_VARS"]["q73_issplanitdestino"]:$this->q73_issplanitdestino);
       if($this->q73_data == ""){
         $this->q73_data_dia = ($this->q73_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q73_data_dia"]:$this->q73_data_dia);
         $this->q73_data_mes = ($this->q73_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q73_data_mes"]:$this->q73_data_mes);
         $this->q73_data_ano = ($this->q73_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q73_data_ano"]:$this->q73_data_ano);
         if($this->q73_data_dia != ""){
            $this->q73_data = $this->q73_data_ano."-".$this->q73_data_mes."-".$this->q73_data_dia;
         }
       }
       $this->q73_hora = ($this->q73_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["q73_hora"]:$this->q73_hora);
       $this->q73_ip = ($this->q73_ip == ""?@$GLOBALS["HTTP_POST_VARS"]["q73_ip"]:$this->q73_ip);
     }else{
       $this->q73_sequencial = ($this->q73_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q73_sequencial"]:$this->q73_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q73_sequencial){ 
      $this->atualizacampos();
     if($this->q73_issplanitorigem == null ){ 
       $this->erro_sql = " Campo Nota Origem nao Informado.";
       $this->erro_campo = "q73_issplanitorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q73_issplanitdestino == null ){ 
       $this->erro_sql = " Campo Nota Destino nao Informado.";
       $this->erro_campo = "q73_issplanitdestino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q73_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "q73_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q73_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "q73_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q73_ip == null ){ 
       $this->erro_sql = " Campo IP nao Informado.";
       $this->erro_campo = "q73_ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q73_sequencial == "" || $q73_sequencial == null ){
       $result = db_query("select nextval('issplanitold_q73_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issplanitold_q73_sequencial_seq do campo: q73_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q73_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from issplanitold_q73_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q73_sequencial)){
         $this->erro_sql = " Campo q73_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q73_sequencial = $q73_sequencial; 
       }
     }
     if(($this->q73_sequencial == null) || ($this->q73_sequencial == "") ){ 
       $this->erro_sql = " Campo q73_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issplanitold(
                                       q73_sequencial 
                                      ,q73_issplanitorigem 
                                      ,q73_issplanitdestino 
                                      ,q73_data 
                                      ,q73_hora 
                                      ,q73_ip 
                       )
                values (
                                $this->q73_sequencial 
                               ,$this->q73_issplanitorigem 
                               ,$this->q73_issplanitdestino 
                               ,".($this->q73_data == "null" || $this->q73_data == ""?"null":"'".$this->q73_data."'")." 
                               ,'$this->q73_hora' 
                               ,'$this->q73_ip' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "issplanitold ($this->q73_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "issplanitold já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "issplanitold ($this->q73_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q73_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q73_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11994,'$this->q73_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2075,11994,'','".AddSlashes(pg_result($resaco,0,'q73_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2075,11995,'','".AddSlashes(pg_result($resaco,0,'q73_issplanitorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2075,11996,'','".AddSlashes(pg_result($resaco,0,'q73_issplanitdestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2075,11997,'','".AddSlashes(pg_result($resaco,0,'q73_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2075,11998,'','".AddSlashes(pg_result($resaco,0,'q73_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2075,11999,'','".AddSlashes(pg_result($resaco,0,'q73_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q73_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update issplanitold set ";
     $virgula = "";
     if(trim($this->q73_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q73_sequencial"])){ 
       $sql  .= $virgula." q73_sequencial = $this->q73_sequencial ";
       $virgula = ",";
       if(trim($this->q73_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "q73_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q73_issplanitorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q73_issplanitorigem"])){ 
       $sql  .= $virgula." q73_issplanitorigem = $this->q73_issplanitorigem ";
       $virgula = ",";
       if(trim($this->q73_issplanitorigem) == null ){ 
         $this->erro_sql = " Campo Nota Origem nao Informado.";
         $this->erro_campo = "q73_issplanitorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q73_issplanitdestino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q73_issplanitdestino"])){ 
       $sql  .= $virgula." q73_issplanitdestino = $this->q73_issplanitdestino ";
       $virgula = ",";
       if(trim($this->q73_issplanitdestino) == null ){ 
         $this->erro_sql = " Campo Nota Destino nao Informado.";
         $this->erro_campo = "q73_issplanitdestino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q73_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q73_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q73_data_dia"] !="") ){ 
       $sql  .= $virgula." q73_data = '$this->q73_data' ";
       $virgula = ",";
       if(trim($this->q73_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "q73_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q73_data_dia"])){ 
         $sql  .= $virgula." q73_data = null ";
         $virgula = ",";
         if(trim($this->q73_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "q73_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q73_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q73_hora"])){ 
       $sql  .= $virgula." q73_hora = '$this->q73_hora' ";
       $virgula = ",";
       if(trim($this->q73_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "q73_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q73_ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q73_ip"])){ 
       $sql  .= $virgula." q73_ip = '$this->q73_ip' ";
       $virgula = ",";
       if(trim($this->q73_ip) == null ){ 
         $this->erro_sql = " Campo IP nao Informado.";
         $this->erro_campo = "q73_ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q73_sequencial!=null){
       $sql .= " q73_sequencial = $this->q73_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q73_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11994,'$this->q73_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q73_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2075,11994,'".AddSlashes(pg_result($resaco,$conresaco,'q73_sequencial'))."','$this->q73_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q73_issplanitorigem"]))
           $resac = db_query("insert into db_acount values($acount,2075,11995,'".AddSlashes(pg_result($resaco,$conresaco,'q73_issplanitorigem'))."','$this->q73_issplanitorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q73_issplanitdestino"]))
           $resac = db_query("insert into db_acount values($acount,2075,11996,'".AddSlashes(pg_result($resaco,$conresaco,'q73_issplanitdestino'))."','$this->q73_issplanitdestino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q73_data"]))
           $resac = db_query("insert into db_acount values($acount,2075,11997,'".AddSlashes(pg_result($resaco,$conresaco,'q73_data'))."','$this->q73_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q73_hora"]))
           $resac = db_query("insert into db_acount values($acount,2075,11998,'".AddSlashes(pg_result($resaco,$conresaco,'q73_hora'))."','$this->q73_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q73_ip"]))
           $resac = db_query("insert into db_acount values($acount,2075,11999,'".AddSlashes(pg_result($resaco,$conresaco,'q73_ip'))."','$this->q73_ip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "issplanitold nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q73_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "issplanitold nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q73_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q73_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11994,'$q73_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2075,11994,'','".AddSlashes(pg_result($resaco,$iresaco,'q73_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2075,11995,'','".AddSlashes(pg_result($resaco,$iresaco,'q73_issplanitorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2075,11996,'','".AddSlashes(pg_result($resaco,$iresaco,'q73_issplanitdestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2075,11997,'','".AddSlashes(pg_result($resaco,$iresaco,'q73_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2075,11998,'','".AddSlashes(pg_result($resaco,$iresaco,'q73_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2075,11999,'','".AddSlashes(pg_result($resaco,$iresaco,'q73_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issplanitold
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q73_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q73_sequencial = $q73_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "issplanitold nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q73_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "issplanitold nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q73_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:issplanitold";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issplanitold ";
     $sql .= "      inner join issplanit  on  issplanit.q21_sequencial = issplanitold.q73_issplanitorigem";
     $sql .= "      inner join issplan  on  issplan.q20_planilha = issplanit.q21_planilha";
     $sql2 = "";
     if($dbwhere==""){
       if($q73_sequencial!=null ){
         $sql2 .= " where issplanitold.q73_sequencial = $q73_sequencial "; 
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
   function sql_query_file ( $q73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from issplanitold ";
     $sql2 = "";
     if($dbwhere==""){
       if($q73_sequencial!=null ){
         $sql2 .= " where issplanitold.q73_sequencial = $q73_sequencial "; 
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