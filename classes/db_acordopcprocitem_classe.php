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
//CLASSE DA ENTIDADE acordopcprocitem
class cl_acordopcprocitem { 
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
   var $ac23_sequencial = 0; 
   var $ac23_pcprocitem = 0; 
   var $ac23_acordoitem = 0; 
   var $ac23_quantidade = 0; 
   var $ac23_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac23_sequencial = int4 = Sequencial 
                 ac23_pcprocitem = int8 = Item Processo de Compras 
                 ac23_acordoitem = int4 = Acordo Item 
                 ac23_quantidade = float8 = Quantidade 
                 ac23_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_acordopcprocitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordopcprocitem"); 
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
       $this->ac23_sequencial = ($this->ac23_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac23_sequencial"]:$this->ac23_sequencial);
       $this->ac23_pcprocitem = ($this->ac23_pcprocitem == ""?@$GLOBALS["HTTP_POST_VARS"]["ac23_pcprocitem"]:$this->ac23_pcprocitem);
       $this->ac23_acordoitem = ($this->ac23_acordoitem == ""?@$GLOBALS["HTTP_POST_VARS"]["ac23_acordoitem"]:$this->ac23_acordoitem);
       $this->ac23_quantidade = ($this->ac23_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ac23_quantidade"]:$this->ac23_quantidade);
       $this->ac23_valor = ($this->ac23_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["ac23_valor"]:$this->ac23_valor);
     }else{
       $this->ac23_sequencial = ($this->ac23_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac23_sequencial"]:$this->ac23_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac23_sequencial){ 
      $this->atualizacampos();
     if($this->ac23_pcprocitem == null ){ 
       $this->erro_sql = " Campo Item Processo de Compras nao Informado.";
       $this->erro_campo = "ac23_pcprocitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac23_acordoitem == null ){ 
       $this->erro_sql = " Campo Acordo Item nao Informado.";
       $this->erro_campo = "ac23_acordoitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac23_quantidade == null ){ 
       $this->ac23_quantidade = "0";
     }
     if($this->ac23_valor == null ){ 
       $this->ac23_valor = "0";
     }
     if($ac23_sequencial == "" || $ac23_sequencial == null ){
       $result = db_query("select nextval('acordopcprocitem_ac23_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordopcprocitem_ac23_sequencial_seq do campo: ac23_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac23_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordopcprocitem_ac23_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac23_sequencial)){
         $this->erro_sql = " Campo ac23_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac23_sequencial = $ac23_sequencial; 
       }
     }
     if(($this->ac23_sequencial == null) || ($this->ac23_sequencial == "") ){ 
       $this->erro_sql = " Campo ac23_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordopcprocitem(
                                       ac23_sequencial 
                                      ,ac23_pcprocitem 
                                      ,ac23_acordoitem 
                                      ,ac23_quantidade 
                                      ,ac23_valor 
                       )
                values (
                                $this->ac23_sequencial 
                               ,$this->ac23_pcprocitem 
                               ,$this->ac23_acordoitem 
                               ,$this->ac23_quantidade 
                               ,$this->ac23_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acordo Pc Proc Item ($this->ac23_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acordo Pc Proc Item já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acordo Pc Proc Item ($this->ac23_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac23_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac23_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16191,'$this->ac23_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2842,16191,'','".AddSlashes(pg_result($resaco,0,'ac23_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2842,16192,'','".AddSlashes(pg_result($resaco,0,'ac23_pcprocitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2842,16193,'','".AddSlashes(pg_result($resaco,0,'ac23_acordoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2842,16663,'','".AddSlashes(pg_result($resaco,0,'ac23_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2842,16664,'','".AddSlashes(pg_result($resaco,0,'ac23_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac23_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordopcprocitem set ";
     $virgula = "";
     if(trim($this->ac23_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac23_sequencial"])){ 
       $sql  .= $virgula." ac23_sequencial = $this->ac23_sequencial ";
       $virgula = ",";
       if(trim($this->ac23_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ac23_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac23_pcprocitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac23_pcprocitem"])){ 
       $sql  .= $virgula." ac23_pcprocitem = $this->ac23_pcprocitem ";
       $virgula = ",";
       if(trim($this->ac23_pcprocitem) == null ){ 
         $this->erro_sql = " Campo Item Processo de Compras nao Informado.";
         $this->erro_campo = "ac23_pcprocitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac23_acordoitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac23_acordoitem"])){ 
       $sql  .= $virgula." ac23_acordoitem = $this->ac23_acordoitem ";
       $virgula = ",";
       if(trim($this->ac23_acordoitem) == null ){ 
         $this->erro_sql = " Campo Acordo Item nao Informado.";
         $this->erro_campo = "ac23_acordoitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac23_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac23_quantidade"])){ 
        if(trim($this->ac23_quantidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac23_quantidade"])){ 
           $this->ac23_quantidade = "0" ; 
        } 
       $sql  .= $virgula." ac23_quantidade = $this->ac23_quantidade ";
       $virgula = ",";
     }
     if(trim($this->ac23_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac23_valor"])){ 
        if(trim($this->ac23_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ac23_valor"])){ 
           $this->ac23_valor = "0" ; 
        } 
       $sql  .= $virgula." ac23_valor = $this->ac23_valor ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ac23_sequencial!=null){
       $sql .= " ac23_sequencial = $this->ac23_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac23_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16191,'$this->ac23_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac23_sequencial"]) || $this->ac23_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2842,16191,'".AddSlashes(pg_result($resaco,$conresaco,'ac23_sequencial'))."','$this->ac23_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac23_pcprocitem"]) || $this->ac23_pcprocitem != "")
           $resac = db_query("insert into db_acount values($acount,2842,16192,'".AddSlashes(pg_result($resaco,$conresaco,'ac23_pcprocitem'))."','$this->ac23_pcprocitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac23_acordoitem"]) || $this->ac23_acordoitem != "")
           $resac = db_query("insert into db_acount values($acount,2842,16193,'".AddSlashes(pg_result($resaco,$conresaco,'ac23_acordoitem'))."','$this->ac23_acordoitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac23_quantidade"]) || $this->ac23_quantidade != "")
           $resac = db_query("insert into db_acount values($acount,2842,16663,'".AddSlashes(pg_result($resaco,$conresaco,'ac23_quantidade'))."','$this->ac23_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac23_valor"]) || $this->ac23_valor != "")
           $resac = db_query("insert into db_acount values($acount,2842,16664,'".AddSlashes(pg_result($resaco,$conresaco,'ac23_valor'))."','$this->ac23_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Pc Proc Item nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac23_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Pc Proc Item nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac23_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac23_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16191,'$ac23_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2842,16191,'','".AddSlashes(pg_result($resaco,$iresaco,'ac23_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2842,16192,'','".AddSlashes(pg_result($resaco,$iresaco,'ac23_pcprocitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2842,16193,'','".AddSlashes(pg_result($resaco,$iresaco,'ac23_acordoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2842,16663,'','".AddSlashes(pg_result($resaco,$iresaco,'ac23_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2842,16664,'','".AddSlashes(pg_result($resaco,$iresaco,'ac23_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordopcprocitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac23_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac23_sequencial = $ac23_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Pc Proc Item nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac23_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Pc Proc Item nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac23_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac23_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordopcprocitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac23_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordopcprocitem ";
     $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codprocitem = acordopcprocitem.ac23_pcprocitem";
     $sql .= "      inner join acordoitem  on  acordoitem.ac20_sequencial = acordopcprocitem.ac23_acordoitem";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = pcprocitem.pc81_codproc";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = acordoitem.ac20_pcmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = acordoitem.ac20_matunid";
     $sql .= "      inner join acordoposicao  on  acordoposicao.ac26_sequencial = acordoitem.ac20_acordoposicao";
     $sql2 = "";
     if($dbwhere==""){
       if($ac23_sequencial!=null ){
         $sql2 .= " where acordopcprocitem.ac23_sequencial = $ac23_sequencial "; 
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
   function sql_query_file ( $ac23_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordopcprocitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac23_sequencial!=null ){
         $sql2 .= " where acordopcprocitem.ac23_sequencial = $ac23_sequencial "; 
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
  
   function sql_query_acordo ( $ac23_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordopcprocitem ";
     $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codprocitem = acordopcprocitem.ac23_pcprocitem";
     $sql .= "      inner join acordoitem  on  acordoitem.ac20_sequencial = acordopcprocitem.ac23_acordoitem";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
     $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = pcprocitem.pc81_codproc";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = acordoitem.ac20_pcmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = acordoitem.ac20_matunid";
     $sql .= "      inner join acordoposicao  on  acordoposicao.ac26_sequencial = acordoitem.ac20_acordoposicao";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoposicao.ac26_acordo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac23_sequencial!=null ){
         $sql2 .= " where acordopcprocitem.ac23_sequencial = $ac23_sequencial "; 
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