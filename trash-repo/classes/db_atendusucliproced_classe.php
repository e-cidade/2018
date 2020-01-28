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

//MODULO: atendimento
//CLASSE DA ENTIDADE atendusucliproced
class cl_atendusucliproced { 
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
   var $at82_seq = 0; 
   var $at82_usucliitem = 0; 
   var $at82_id_item = 0; 
   var $at82_id_item_filho = 0; 
   var $at82_modulo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at82_seq = int4 = Código do procedimento 
                 at82_usucliitem = int4 = Sequencial da tarefa 
                 at82_id_item = int4 = Código menu 
                 at82_id_item_filho = int4 = Item filho 
                 at82_modulo = int4 = Módulo 
                 ";
   //funcao construtor da classe 
   function cl_atendusucliproced() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atendusucliproced"); 
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
       $this->at82_seq = ($this->at82_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["at82_seq"]:$this->at82_seq);
       $this->at82_usucliitem = ($this->at82_usucliitem == ""?@$GLOBALS["HTTP_POST_VARS"]["at82_usucliitem"]:$this->at82_usucliitem);
       $this->at82_id_item = ($this->at82_id_item == ""?@$GLOBALS["HTTP_POST_VARS"]["at82_id_item"]:$this->at82_id_item);
       $this->at82_id_item_filho = ($this->at82_id_item_filho == ""?@$GLOBALS["HTTP_POST_VARS"]["at82_id_item_filho"]:$this->at82_id_item_filho);
       $this->at82_modulo = ($this->at82_modulo == ""?@$GLOBALS["HTTP_POST_VARS"]["at82_modulo"]:$this->at82_modulo);
     }else{
       $this->at82_seq = ($this->at82_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["at82_seq"]:$this->at82_seq);
     }
   }
   // funcao para inclusao
   function incluir ($at82_seq){ 
      $this->atualizacampos();
     if($this->at82_usucliitem == null ){ 
       $this->erro_sql = " Campo Sequencial da tarefa nao Informado.";
       $this->erro_campo = "at82_usucliitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at82_id_item == null ){ 
       $this->erro_sql = " Campo Código menu nao Informado.";
       $this->erro_campo = "at82_id_item";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at82_id_item_filho == null ){ 
       $this->erro_sql = " Campo Item filho nao Informado.";
       $this->erro_campo = "at82_id_item_filho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at82_modulo == null ){ 
       $this->erro_sql = " Campo Módulo nao Informado.";
       $this->erro_campo = "at82_modulo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at82_seq == "" || $at82_seq == null ){
       $result = @pg_query("select nextval('atendusucliproced_at82_seq_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: atendusucliproced_at82_seq_seq do campo: at82_seq"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at82_seq = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from atendusucliproced_at82_seq_seq");
       if(($result != false) && (pg_result($result,0,0) < $at82_seq)){
         $this->erro_sql = " Campo at82_seq maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at82_seq = $at82_seq; 
       }
     }
     if(($this->at82_seq == null) || ($this->at82_seq == "") ){ 
       $this->erro_sql = " Campo at82_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atendusucliproced(
                                       at82_seq 
                                      ,at82_usucliitem 
                                      ,at82_id_item 
                                      ,at82_id_item_filho 
                                      ,at82_modulo 
                       )
                values (
                                $this->at82_seq 
                               ,$this->at82_usucliitem 
                               ,$this->at82_id_item 
                               ,$this->at82_id_item_filho 
                               ,$this->at82_modulo 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Procedimentos na tarefa ($this->at82_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Procedimentos na tarefa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Procedimentos na tarefa ($this->at82_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at82_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at82_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,9199,'$this->at82_seq','I')");
       $resac = pg_query("insert into db_acount values($acount,1574,9199,'','".AddSlashes(pg_result($resaco,0,'at82_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1574,9200,'','".AddSlashes(pg_result($resaco,0,'at82_usucliitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1574,9201,'','".AddSlashes(pg_result($resaco,0,'at82_id_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1574,9212,'','".AddSlashes(pg_result($resaco,0,'at82_id_item_filho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1574,9213,'','".AddSlashes(pg_result($resaco,0,'at82_modulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at82_seq=null) { 
      $this->atualizacampos();
     $sql = " update atendusucliproced set ";
     $virgula = "";
     if(trim($this->at82_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at82_seq"])){ 
       $sql  .= $virgula." at82_seq = $this->at82_seq ";
       $virgula = ",";
       if(trim($this->at82_seq) == null ){ 
         $this->erro_sql = " Campo Código do procedimento nao Informado.";
         $this->erro_campo = "at82_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at82_usucliitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at82_usucliitem"])){ 
       $sql  .= $virgula." at82_usucliitem = $this->at82_usucliitem ";
       $virgula = ",";
       if(trim($this->at82_usucliitem) == null ){ 
         $this->erro_sql = " Campo Sequencial da tarefa nao Informado.";
         $this->erro_campo = "at82_usucliitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at82_id_item)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at82_id_item"])){ 
       $sql  .= $virgula." at82_id_item = $this->at82_id_item ";
       $virgula = ",";
       if(trim($this->at82_id_item) == null ){ 
         $this->erro_sql = " Campo Código menu nao Informado.";
         $this->erro_campo = "at82_id_item";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at82_id_item_filho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at82_id_item_filho"])){ 
       $sql  .= $virgula." at82_id_item_filho = $this->at82_id_item_filho ";
       $virgula = ",";
       if(trim($this->at82_id_item_filho) == null ){ 
         $this->erro_sql = " Campo Item filho nao Informado.";
         $this->erro_campo = "at82_id_item_filho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at82_modulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at82_modulo"])){ 
       $sql  .= $virgula." at82_modulo = $this->at82_modulo ";
       $virgula = ",";
       if(trim($this->at82_modulo) == null ){ 
         $this->erro_sql = " Campo Módulo nao Informado.";
         $this->erro_campo = "at82_modulo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at82_seq!=null){
       $sql .= " at82_seq = $this->at82_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at82_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,9199,'$this->at82_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at82_seq"]))
           $resac = pg_query("insert into db_acount values($acount,1574,9199,'".AddSlashes(pg_result($resaco,$conresaco,'at82_seq'))."','$this->at82_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at82_usucliitem"]))
           $resac = pg_query("insert into db_acount values($acount,1574,9200,'".AddSlashes(pg_result($resaco,$conresaco,'at82_usucliitem'))."','$this->at82_usucliitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at82_id_item"]))
           $resac = pg_query("insert into db_acount values($acount,1574,9201,'".AddSlashes(pg_result($resaco,$conresaco,'at82_id_item'))."','$this->at82_id_item',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at82_id_item_filho"]))
           $resac = pg_query("insert into db_acount values($acount,1574,9212,'".AddSlashes(pg_result($resaco,$conresaco,'at82_id_item_filho'))."','$this->at82_id_item_filho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at82_modulo"]))
           $resac = pg_query("insert into db_acount values($acount,1574,9213,'".AddSlashes(pg_result($resaco,$conresaco,'at82_modulo'))."','$this->at82_modulo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimentos na tarefa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at82_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimentos na tarefa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at82_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at82_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at82_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at82_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,9199,'$at82_seq','E')");
         $resac = pg_query("insert into db_acount values($acount,1574,9199,'','".AddSlashes(pg_result($resaco,$iresaco,'at82_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1574,9200,'','".AddSlashes(pg_result($resaco,$iresaco,'at82_usucliitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1574,9201,'','".AddSlashes(pg_result($resaco,$iresaco,'at82_id_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1574,9212,'','".AddSlashes(pg_result($resaco,$iresaco,'at82_id_item_filho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1574,9213,'','".AddSlashes(pg_result($resaco,$iresaco,'at82_modulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from atendusucliproced
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at82_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at82_seq = $at82_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimentos na tarefa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at82_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimentos na tarefa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at82_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at82_seq;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:atendusucliproced";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $at82_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendusucliproced ";
     $sql .= "      inner join atendusucliitem  on  atendusucliitem.at81_seq = atendusucliproced.at82_usucliitem";
     $sql .= "      inner join tipoatend  on  tipoatend.at04_codtipo = atendusucliitem.at81_codtipo";
     $sql .= "      inner join atendusucli  on  atendusucli.at80_codatendcli = atendusucliitem.at81_codatendcli";
     $sql2 = "";
     if($dbwhere==""){
       if($at82_seq!=null ){
         $sql2 .= " where atendusucliproced.at82_seq = $at82_seq "; 
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
   function sql_query_file ( $at82_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from atendusucliproced ";
     $sql2 = "";
     if($dbwhere==""){
       if($at82_seq!=null ){
         $sql2 .= " where atendusucliproced.at82_seq = $at82_seq "; 
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