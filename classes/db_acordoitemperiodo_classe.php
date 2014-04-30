<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: acordos
//CLASSE DA ENTIDADE acordoitemperiodo
class cl_acordoitemperiodo { 
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
   var $ac41_sequencial = 0; 
   var $ac41_acordoitem = 0; 
   var $ac41_datainicial_dia = null; 
   var $ac41_datainicial_mes = null; 
   var $ac41_datainicial_ano = null; 
   var $ac41_datainicial = null; 
   var $ac41_datafinal_dia = null; 
   var $ac41_datafinal_mes = null; 
   var $ac41_datafinal_ano = null; 
   var $ac41_datafinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac41_sequencial = int4 = Sequencial 
                 ac41_acordoitem = int4 = Acordo Item 
                 ac41_datainicial = date = Data Inicial 
                 ac41_datafinal = date = Data Final 
                 ";
   //funcao construtor da classe 
   function cl_acordoitemperiodo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordoitemperiodo"); 
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
       $this->ac41_sequencial = ($this->ac41_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac41_sequencial"]:$this->ac41_sequencial);
       $this->ac41_acordoitem = ($this->ac41_acordoitem == ""?@$GLOBALS["HTTP_POST_VARS"]["ac41_acordoitem"]:$this->ac41_acordoitem);
       if($this->ac41_datainicial == ""){
         $this->ac41_datainicial_dia = ($this->ac41_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac41_datainicial_dia"]:$this->ac41_datainicial_dia);
         $this->ac41_datainicial_mes = ($this->ac41_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac41_datainicial_mes"]:$this->ac41_datainicial_mes);
         $this->ac41_datainicial_ano = ($this->ac41_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac41_datainicial_ano"]:$this->ac41_datainicial_ano);
         if($this->ac41_datainicial_dia != ""){
            $this->ac41_datainicial = $this->ac41_datainicial_ano."-".$this->ac41_datainicial_mes."-".$this->ac41_datainicial_dia;
         }
       }
       if($this->ac41_datafinal == ""){
         $this->ac41_datafinal_dia = ($this->ac41_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac41_datafinal_dia"]:$this->ac41_datafinal_dia);
         $this->ac41_datafinal_mes = ($this->ac41_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac41_datafinal_mes"]:$this->ac41_datafinal_mes);
         $this->ac41_datafinal_ano = ($this->ac41_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac41_datafinal_ano"]:$this->ac41_datafinal_ano);
         if($this->ac41_datafinal_dia != ""){
            $this->ac41_datafinal = $this->ac41_datafinal_ano."-".$this->ac41_datafinal_mes."-".$this->ac41_datafinal_dia;
         }
       }
     }else{
       $this->ac41_sequencial = ($this->ac41_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac41_sequencial"]:$this->ac41_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac41_sequencial){ 
      $this->atualizacampos();
     if($this->ac41_acordoitem == null ){ 
       $this->erro_sql = " Campo Acordo Item nao Informado.";
       $this->erro_campo = "ac41_acordoitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac41_datainicial == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "ac41_datainicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac41_datafinal == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "ac41_datafinal_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac41_sequencial == "" || $ac41_sequencial == null ){
       $result = db_query("select nextval('acordoitemperiodo_ac41_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoitemperiodo_ac41_sequencial_seq do campo: ac41_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac41_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordoitemperiodo_ac41_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac41_sequencial)){
         $this->erro_sql = " Campo ac41_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac41_sequencial = $ac41_sequencial; 
       }
     }
     if(($this->ac41_sequencial == null) || ($this->ac41_sequencial == "") ){ 
       $this->erro_sql = " Campo ac41_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoitemperiodo(
                                       ac41_sequencial 
                                      ,ac41_acordoitem 
                                      ,ac41_datainicial 
                                      ,ac41_datafinal 
                       )
                values (
                                $this->ac41_sequencial 
                               ,$this->ac41_acordoitem 
                               ,".($this->ac41_datainicial == "null" || $this->ac41_datainicial == ""?"null":"'".$this->ac41_datainicial."'")." 
                               ,".($this->ac41_datafinal == "null" || $this->ac41_datafinal == ""?"null":"'".$this->ac41_datafinal."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acordo Item Periodo ($this->ac41_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acordo Item Periodo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acordo Item Periodo ($this->ac41_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac41_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac41_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18618,'$this->ac41_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3296,18618,'','".AddSlashes(pg_result($resaco,0,'ac41_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3296,18619,'','".AddSlashes(pg_result($resaco,0,'ac41_acordoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3296,18620,'','".AddSlashes(pg_result($resaco,0,'ac41_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3296,18621,'','".AddSlashes(pg_result($resaco,0,'ac41_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac41_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordoitemperiodo set ";
     $virgula = "";
     if(trim($this->ac41_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac41_sequencial"])){ 
       $sql  .= $virgula." ac41_sequencial = $this->ac41_sequencial ";
       $virgula = ",";
       if(trim($this->ac41_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ac41_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac41_acordoitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac41_acordoitem"])){ 
       $sql  .= $virgula." ac41_acordoitem = $this->ac41_acordoitem ";
       $virgula = ",";
       if(trim($this->ac41_acordoitem) == null ){ 
         $this->erro_sql = " Campo Acordo Item nao Informado.";
         $this->erro_campo = "ac41_acordoitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac41_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac41_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac41_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." ac41_datainicial = '$this->ac41_datainicial' ";
       $virgula = ",";
       if(trim($this->ac41_datainicial) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "ac41_datainicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac41_datainicial_dia"])){ 
         $sql  .= $virgula." ac41_datainicial = null ";
         $virgula = ",";
         if(trim($this->ac41_datainicial) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "ac41_datainicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ac41_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac41_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac41_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." ac41_datafinal = '$this->ac41_datafinal' ";
       $virgula = ",";
       if(trim($this->ac41_datafinal) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "ac41_datafinal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac41_datafinal_dia"])){ 
         $sql  .= $virgula." ac41_datafinal = null ";
         $virgula = ",";
         if(trim($this->ac41_datafinal) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "ac41_datafinal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($ac41_sequencial!=null){
       $sql .= " ac41_sequencial = $this->ac41_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac41_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18618,'$this->ac41_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac41_sequencial"]) || $this->ac41_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3296,18618,'".AddSlashes(pg_result($resaco,$conresaco,'ac41_sequencial'))."','$this->ac41_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac41_acordoitem"]) || $this->ac41_acordoitem != "")
           $resac = db_query("insert into db_acount values($acount,3296,18619,'".AddSlashes(pg_result($resaco,$conresaco,'ac41_acordoitem'))."','$this->ac41_acordoitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac41_datainicial"]) || $this->ac41_datainicial != "")
           $resac = db_query("insert into db_acount values($acount,3296,18620,'".AddSlashes(pg_result($resaco,$conresaco,'ac41_datainicial'))."','$this->ac41_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac41_datafinal"]) || $this->ac41_datafinal != "")
           $resac = db_query("insert into db_acount values($acount,3296,18621,'".AddSlashes(pg_result($resaco,$conresaco,'ac41_datafinal'))."','$this->ac41_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Item Periodo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac41_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Item Periodo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac41_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac41_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac41_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac41_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18618,'$ac41_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3296,18618,'','".AddSlashes(pg_result($resaco,$iresaco,'ac41_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3296,18619,'','".AddSlashes(pg_result($resaco,$iresaco,'ac41_acordoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3296,18620,'','".AddSlashes(pg_result($resaco,$iresaco,'ac41_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3296,18621,'','".AddSlashes(pg_result($resaco,$iresaco,'ac41_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordoitemperiodo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac41_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac41_sequencial = $ac41_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Item Periodo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac41_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Item Periodo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac41_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac41_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoitemperiodo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac41_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoitemperiodo ";
     $sql .= "      inner join acordoitem  on  acordoitem.ac20_sequencial = acordoitemperiodo.ac41_acordoitem";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = acordoitem.ac20_pcmater";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = acordoitem.ac20_matunid";
     $sql .= "      inner join acordoposicao  on  acordoposicao.ac26_sequencial = acordoitem.ac20_acordoposicao";
     $sql2 = "";
     if($dbwhere==""){
       if($ac41_sequencial!=null ){
         $sql2 .= " where acordoitemperiodo.ac41_sequencial = $ac41_sequencial "; 
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
   function sql_query_file ( $ac41_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordoitemperiodo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac41_sequencial!=null ){
         $sql2 .= " where acordoitemperiodo.ac41_sequencial = $ac41_sequencial "; 
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