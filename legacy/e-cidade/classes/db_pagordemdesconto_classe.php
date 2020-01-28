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

//MODULO: empenho
//CLASSE DA ENTIDADE pagordemdesconto
class cl_pagordemdesconto { 
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
   var $e34_sequencial = 0; 
   var $e34_codord = 0; 
   var $e34_data_dia = null; 
   var $e34_data_mes = null; 
   var $e34_data_ano = null; 
   var $e34_data = null; 
   var $e34_valordesconto = 0; 
   var $e34_motivo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e34_sequencial = int4 = Código Sequencial 
                 e34_codord = int4 = Código da Nota 
                 e34_data = date = Data do Desconto 
                 e34_valordesconto = float4 = Valor do Desconto 
                 e34_motivo = text = Motivo 
                 ";
   //funcao construtor da classe 
   function cl_pagordemdesconto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pagordemdesconto"); 
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
       $this->e34_sequencial = ($this->e34_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e34_sequencial"]:$this->e34_sequencial);
       $this->e34_codord = ($this->e34_codord == ""?@$GLOBALS["HTTP_POST_VARS"]["e34_codord"]:$this->e34_codord);
       if($this->e34_data == ""){
         $this->e34_data_dia = ($this->e34_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["e34_data_dia"]:$this->e34_data_dia);
         $this->e34_data_mes = ($this->e34_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["e34_data_mes"]:$this->e34_data_mes);
         $this->e34_data_ano = ($this->e34_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["e34_data_ano"]:$this->e34_data_ano);
         if($this->e34_data_dia != ""){
            $this->e34_data = $this->e34_data_ano."-".$this->e34_data_mes."-".$this->e34_data_dia;
         }
       }
       $this->e34_valordesconto = ($this->e34_valordesconto == ""?@$GLOBALS["HTTP_POST_VARS"]["e34_valordesconto"]:$this->e34_valordesconto);
       $this->e34_motivo = ($this->e34_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["e34_motivo"]:$this->e34_motivo);
     }else{
       $this->e34_sequencial = ($this->e34_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e34_sequencial"]:$this->e34_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e34_sequencial){ 
      $this->atualizacampos();
     if($this->e34_codord == null ){ 
       $this->erro_sql = " Campo Código da Nota nao Informado.";
       $this->erro_campo = "e34_codord";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e34_data == null ){ 
       $this->erro_sql = " Campo Data do Desconto nao Informado.";
       $this->erro_campo = "e34_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e34_valordesconto == null ){ 
       $this->erro_sql = " Campo Valor do Desconto nao Informado.";
       $this->erro_campo = "e34_valordesconto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e34_sequencial == "" || $e34_sequencial == null ){
       $result = db_query("select nextval('pagordemdesconto_e34_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pagordemdesconto_e34_sequencial_seq do campo: e34_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e34_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pagordemdesconto_e34_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e34_sequencial)){
         $this->erro_sql = " Campo e34_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e34_sequencial = $e34_sequencial; 
       }
     }
     if(($this->e34_sequencial == null) || ($this->e34_sequencial == "") ){ 
       $this->erro_sql = " Campo e34_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pagordemdesconto(
                                       e34_sequencial 
                                      ,e34_codord 
                                      ,e34_data 
                                      ,e34_valordesconto 
                                      ,e34_motivo 
                       )
                values (
                                $this->e34_sequencial 
                               ,$this->e34_codord 
                               ,".($this->e34_data == "null" || $this->e34_data == ""?"null":"'".$this->e34_data."'")." 
                               ,$this->e34_valordesconto 
                               ,'$this->e34_motivo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Descontos ($this->e34_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Descontos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Descontos ($this->e34_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e34_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e34_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11751,'$this->e34_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2026,11751,'','".AddSlashes(pg_result($resaco,0,'e34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2026,11752,'','".AddSlashes(pg_result($resaco,0,'e34_codord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2026,11753,'','".AddSlashes(pg_result($resaco,0,'e34_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2026,11754,'','".AddSlashes(pg_result($resaco,0,'e34_valordesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2026,11758,'','".AddSlashes(pg_result($resaco,0,'e34_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e34_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update pagordemdesconto set ";
     $virgula = "";
     if(trim($this->e34_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e34_sequencial"])){ 
       $sql  .= $virgula." e34_sequencial = $this->e34_sequencial ";
       $virgula = ",";
       if(trim($this->e34_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "e34_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e34_codord)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e34_codord"])){ 
       $sql  .= $virgula." e34_codord = $this->e34_codord ";
       $virgula = ",";
       if(trim($this->e34_codord) == null ){ 
         $this->erro_sql = " Campo Código da Nota nao Informado.";
         $this->erro_campo = "e34_codord";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e34_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e34_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e34_data_dia"] !="") ){ 
       $sql  .= $virgula." e34_data = '$this->e34_data' ";
       $virgula = ",";
       if(trim($this->e34_data) == null ){ 
         $this->erro_sql = " Campo Data do Desconto nao Informado.";
         $this->erro_campo = "e34_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["e34_data_dia"])){ 
         $sql  .= $virgula." e34_data = null ";
         $virgula = ",";
         if(trim($this->e34_data) == null ){ 
           $this->erro_sql = " Campo Data do Desconto nao Informado.";
           $this->erro_campo = "e34_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->e34_valordesconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e34_valordesconto"])){ 
       $sql  .= $virgula." e34_valordesconto = $this->e34_valordesconto ";
       $virgula = ",";
       if(trim($this->e34_valordesconto) == null ){ 
         $this->erro_sql = " Campo Valor do Desconto nao Informado.";
         $this->erro_campo = "e34_valordesconto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e34_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e34_motivo"])){ 
       $sql  .= $virgula." e34_motivo = '$this->e34_motivo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($e34_sequencial!=null){
       $sql .= " e34_sequencial = $this->e34_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e34_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11751,'$this->e34_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e34_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2026,11751,'".AddSlashes(pg_result($resaco,$conresaco,'e34_sequencial'))."','$this->e34_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e34_codord"]))
           $resac = db_query("insert into db_acount values($acount,2026,11752,'".AddSlashes(pg_result($resaco,$conresaco,'e34_codord'))."','$this->e34_codord',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e34_data"]))
           $resac = db_query("insert into db_acount values($acount,2026,11753,'".AddSlashes(pg_result($resaco,$conresaco,'e34_data'))."','$this->e34_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e34_valordesconto"]))
           $resac = db_query("insert into db_acount values($acount,2026,11754,'".AddSlashes(pg_result($resaco,$conresaco,'e34_valordesconto'))."','$this->e34_valordesconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e34_motivo"]))
           $resac = db_query("insert into db_acount values($acount,2026,11758,'".AddSlashes(pg_result($resaco,$conresaco,'e34_motivo'))."','$this->e34_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Descontos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e34_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Descontos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e34_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e34_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11751,'$e34_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2026,11751,'','".AddSlashes(pg_result($resaco,$iresaco,'e34_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2026,11752,'','".AddSlashes(pg_result($resaco,$iresaco,'e34_codord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2026,11753,'','".AddSlashes(pg_result($resaco,$iresaco,'e34_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2026,11754,'','".AddSlashes(pg_result($resaco,$iresaco,'e34_valordesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2026,11758,'','".AddSlashes(pg_result($resaco,$iresaco,'e34_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pagordemdesconto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e34_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e34_sequencial = $e34_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Descontos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e34_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Descontos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e34_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e34_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:pagordemdesconto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e34_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pagordemdesconto ";
     $sql .= "      inner join pagordem  on  pagordem.e50_codord = pagordemdesconto.e34_codord";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pagordem.e50_id_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = pagordem.e50_numemp";
     $sql2 = "";
     if($dbwhere==""){
       if($e34_sequencial!=null ){
         $sql2 .= " where pagordemdesconto.e34_sequencial = $e34_sequencial "; 
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
   function sql_query_file ( $e34_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pagordemdesconto ";
     $sql2 = "";
     if($dbwhere==""){
       if($e34_sequencial!=null ){
         $sql2 .= " where pagordemdesconto.e34_sequencial = $e34_sequencial "; 
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