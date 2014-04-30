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

//MODULO: Acordos
//CLASSE DA ENTIDADE acordoliclicitem
class cl_acordoliclicitem { 
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
   var $ac24_sequencial = 0; 
   var $ac24_acordoitem = 0; 
   var $ac24_liclicitem = 0; 
   var $ac24_quantidade = 0; 
   var $ac24_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac24_sequencial = int4 = Sequencial 
                 ac24_acordoitem = int4 = Acordo Item 
                 ac24_liclicitem = int8 = Código Licitação 
                 ac24_quantidade = float8 = Quantidade 
                 ac24_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_acordoliclicitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoliclicitem"); 
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
       $this->ac24_sequencial = ($this->ac24_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac24_sequencial"]:$this->ac24_sequencial);
       $this->ac24_acordoitem = ($this->ac24_acordoitem == ""?@$GLOBALS["HTTP_POST_VARS"]["ac24_acordoitem"]:$this->ac24_acordoitem);
       $this->ac24_liclicitem = ($this->ac24_liclicitem == ""?@$GLOBALS["HTTP_POST_VARS"]["ac24_liclicitem"]:$this->ac24_liclicitem);
       $this->ac24_quantidade = ($this->ac24_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ac24_quantidade"]:$this->ac24_quantidade);
       $this->ac24_valor = ($this->ac24_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["ac24_valor"]:$this->ac24_valor);
     }else{
       $this->ac24_sequencial = ($this->ac24_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac24_sequencial"]:$this->ac24_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac24_sequencial){ 
      $this->atualizacampos();
     if($this->ac24_acordoitem == null ){ 
       $this->erro_sql = " Campo Acordo Item nao Informado.";
       $this->erro_campo = "ac24_acordoitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac24_liclicitem == null ){ 
       $this->erro_sql = " Campo Código Licitação nao Informado.";
       $this->erro_campo = "ac24_liclicitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac24_quantidade == null ){ 
       $this->ac24_quantidade = "0";
     }
     if($this->ac24_valor == null ){ 
       $this->ac24_valor = "0";
     }
     if($ac24_sequencial == "" || $ac24_sequencial == null ){
       $result = db_query("select nextval('acordoliclicitem_ac24_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoliclicitem_ac24_sequencial_seq do campo: ac24_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac24_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordoliclicitem_ac24_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac24_sequencial)){
         $this->erro_sql = " Campo ac24_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac24_sequencial = $ac24_sequencial; 
       }
     }
     if(($this->ac24_sequencial == null) || ($this->ac24_sequencial == "") ){ 
       $this->erro_sql = " Campo ac24_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoliclicitem(
                                       ac24_sequencial 
                                      ,ac24_acordoitem 
                                      ,ac24_liclicitem 
                                      ,ac24_quantidade 
                                      ,ac24_valor 
                       )
                values (
                                $this->ac24_sequencial 
                               ,$this->ac24_acordoitem 
                               ,$this->ac24_liclicitem 
                               ,$this->ac24_quantidade 
                               ,$this->ac24_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acordo Licitação Licitação Item ($this->ac24_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acordo Licitação Licitação Item já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acordo Licitação Licitação Item ($this->ac24_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac24_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac24_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16188,'$this->ac24_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2841,16188,'','".AddSlashes(pg_result($resaco,0,'ac24_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2841,16189,'','".AddSlashes(pg_result($resaco,0,'ac24_acordoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2841,16190,'','".AddSlashes(pg_result($resaco,0,'ac24_liclicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2841,16661,'','".AddSlashes(pg_result($resaco,0,'ac24_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2841,16662,'','".AddSlashes(pg_result($resaco,0,'ac24_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac24_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordoliclicitem set ";
     $virgula = "";
     if(trim($this->ac24_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac24_sequencial"])){ 
       $sql  .= $virgula." ac24_sequencial = $this->ac24_sequencial ";
       $virgula = ",";
       if(trim($this->ac24_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ac24_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac24_acordoitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac24_acordoitem"])){ 
       $sql  .= $virgula." ac24_acordoitem = $this->ac24_acordoitem ";
       $virgula = ",";
       if(trim($this->ac24_acordoitem) == null ){ 
         $this->erro_sql = " Campo Acordo Item nao Informado.";
         $this->erro_campo = "ac24_acordoitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac24_liclicitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac24_liclicitem"])){ 
       $sql  .= $virgula." ac24_liclicitem = $this->ac24_liclicitem ";
       $virgula = ",";
       if(trim($this->ac24_liclicitem) == null ){ 
         $this->erro_sql = " Campo Código Licitação nao Informado.";
         $this->erro_campo = "ac24_liclicitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac24_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac24_quantidade"])){ 
        if(trim($this->ac24_quantidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac24_quantidade"])){ 
           $this->ac24_quantidade = "0" ; 
        } 
       $sql  .= $virgula." ac24_quantidade = $this->ac24_quantidade ";
       $virgula = ",";
     }
     if(trim($this->ac24_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac24_valor"])){ 
        if(trim($this->ac24_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac24_valor"])){ 
           $this->ac24_valor = "0" ; 
        } 
       $sql  .= $virgula." ac24_valor = $this->ac24_valor ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ac24_sequencial!=null){
       $sql .= " ac24_sequencial = $this->ac24_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac24_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16188,'$this->ac24_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac24_sequencial"]) || $this->ac24_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2841,16188,'".AddSlashes(pg_result($resaco,$conresaco,'ac24_sequencial'))."','$this->ac24_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac24_acordoitem"]) || $this->ac24_acordoitem != "")
           $resac = db_query("insert into db_acount values($acount,2841,16189,'".AddSlashes(pg_result($resaco,$conresaco,'ac24_acordoitem'))."','$this->ac24_acordoitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac24_liclicitem"]) || $this->ac24_liclicitem != "")
           $resac = db_query("insert into db_acount values($acount,2841,16190,'".AddSlashes(pg_result($resaco,$conresaco,'ac24_liclicitem'))."','$this->ac24_liclicitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac24_quantidade"]) || $this->ac24_quantidade != "")
           $resac = db_query("insert into db_acount values($acount,2841,16661,'".AddSlashes(pg_result($resaco,$conresaco,'ac24_quantidade'))."','$this->ac24_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac24_valor"]) || $this->ac24_valor != "")
           $resac = db_query("insert into db_acount values($acount,2841,16662,'".AddSlashes(pg_result($resaco,$conresaco,'ac24_valor'))."','$this->ac24_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Licitação Licitação Item nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac24_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Licitação Licitação Item nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac24_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac24_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac24_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac24_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16188,'$ac24_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2841,16188,'','".AddSlashes(pg_result($resaco,$iresaco,'ac24_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2841,16189,'','".AddSlashes(pg_result($resaco,$iresaco,'ac24_acordoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2841,16190,'','".AddSlashes(pg_result($resaco,$iresaco,'ac24_liclicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2841,16661,'','".AddSlashes(pg_result($resaco,$iresaco,'ac24_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2841,16662,'','".AddSlashes(pg_result($resaco,$iresaco,'ac24_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordoliclicitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac24_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac24_sequencial = $ac24_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Licitação Licitação Item nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac24_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Licitação Licitação Item nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac24_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac24_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoliclicitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac24_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoliclicitem ";
     $sql .= "      inner join liclicitem  on  liclicitem.l21_codigo = acordoliclicitem.ac24_liclicitem";
     $sql .= "      inner join acordoitem  on  acordoitem.ac20_sequencial = acordoliclicitem.ac24_acordoitem";
     $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem";
     $sql .= "      inner join liclicita  on  liclicita.l20_codigo = liclicitem.l21_codliclicita";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = acordoitem.ac20_pcmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = acordoitem.ac20_matunid";
     $sql .= "      inner join acordoposicao  on  acordoposicao.ac26_sequencial = acordoitem.ac20_acordoposicao";
     $sql2 = "";
     if($dbwhere==""){
       if($ac24_sequencial!=null ){
         $sql2 .= " where acordoliclicitem.ac24_sequencial = $ac24_sequencial "; 
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
   function sql_query_file ( $ac24_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoliclicitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac24_sequencial!=null ){
         $sql2 .= " where acordoliclicitem.ac24_sequencial = $ac24_sequencial "; 
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
  
   function sql_query_acordo ( $ac24_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoliclicitem ";
     $sql .= "      inner join liclicitem  on  liclicitem.l21_codigo = acordoliclicitem.ac24_liclicitem";
     $sql .= "      inner join acordoitem  on  acordoitem.ac20_sequencial = acordoliclicitem.ac24_acordoitem";
     $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem";
     $sql .= "      inner join liclicita  on  liclicita.l20_codigo = liclicitem.l21_codliclicita";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = acordoitem.ac20_pcmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = acordoitem.ac20_matunid";
     $sql .= "      inner join acordoposicao  on  acordoposicao.ac26_sequencial = acordoitem.ac20_acordoposicao";
     $sql .= "      inner join acordo         on  acordo.ac16_sequencial = acordoposicao.ac26_acordo";
     $sql2 = "";
     if($dbwhere==""){
       if($ac24_sequencial!=null ){
         $sql2 .= " where acordoliclicitem.ac24_sequencial = $ac24_sequencial "; 
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