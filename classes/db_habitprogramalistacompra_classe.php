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

//MODULO: Habitacao
//CLASSE DA ENTIDADE habitprogramalistacompra
class cl_habitprogramalistacompra { 
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
   var $ht17_sequencial = 0; 
   var $ht17_habitprograma = 0; 
   var $ht17_descricao = null; 
   var $ht17_datalimite_dia = null; 
   var $ht17_datalimite_mes = null; 
   var $ht17_datalimite_ano = null; 
   var $ht17_datalimite = null; 
   var $ht17_formaavaliacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht17_sequencial = int4 = Sequencial 
                 ht17_habitprograma = int4 = Programa 
                 ht17_descricao = varchar(50) = Descrição 
                 ht17_datalimite = date = Data Limite 
                 ht17_formaavaliacao = int4 = Forma de Avaliação 
                 ";
   //funcao construtor da classe 
   function cl_habitprogramalistacompra() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habitprogramalistacompra"); 
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
       $this->ht17_sequencial = ($this->ht17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht17_sequencial"]:$this->ht17_sequencial);
       $this->ht17_habitprograma = ($this->ht17_habitprograma == ""?@$GLOBALS["HTTP_POST_VARS"]["ht17_habitprograma"]:$this->ht17_habitprograma);
       $this->ht17_descricao = ($this->ht17_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["ht17_descricao"]:$this->ht17_descricao);
       if($this->ht17_datalimite == ""){
         $this->ht17_datalimite_dia = ($this->ht17_datalimite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht17_datalimite_dia"]:$this->ht17_datalimite_dia);
         $this->ht17_datalimite_mes = ($this->ht17_datalimite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ht17_datalimite_mes"]:$this->ht17_datalimite_mes);
         $this->ht17_datalimite_ano = ($this->ht17_datalimite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ht17_datalimite_ano"]:$this->ht17_datalimite_ano);
         if($this->ht17_datalimite_dia != ""){
            $this->ht17_datalimite = $this->ht17_datalimite_ano."-".$this->ht17_datalimite_mes."-".$this->ht17_datalimite_dia;
         }
       }
       $this->ht17_formaavaliacao = ($this->ht17_formaavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ht17_formaavaliacao"]:$this->ht17_formaavaliacao);
     }else{
       $this->ht17_sequencial = ($this->ht17_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht17_sequencial"]:$this->ht17_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ht17_sequencial){ 
      $this->atualizacampos();
     if($this->ht17_habitprograma == null ){ 
       $this->erro_sql = " Campo Programa nao Informado.";
       $this->erro_campo = "ht17_habitprograma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht17_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ht17_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht17_datalimite == null ){ 
       $this->ht17_datalimite = "null";
     }
     if($this->ht17_formaavaliacao == null ){ 
       $this->erro_sql = " Campo Forma de Avaliação nao Informado.";
       $this->erro_campo = "ht17_formaavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ht17_sequencial == "" || $ht17_sequencial == null ){
       $result = db_query("select nextval('habitprogramalistacompra_ht17_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: habitprogramalistacompra_ht17_sequencial_seq do campo: ht17_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ht17_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from habitprogramalistacompra_ht17_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ht17_sequencial)){
         $this->erro_sql = " Campo ht17_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ht17_sequencial = $ht17_sequencial; 
       }
     }
     if(($this->ht17_sequencial == null) || ($this->ht17_sequencial == "") ){ 
       $this->erro_sql = " Campo ht17_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habitprogramalistacompra(
                                       ht17_sequencial 
                                      ,ht17_habitprograma 
                                      ,ht17_descricao 
                                      ,ht17_datalimite 
                                      ,ht17_formaavaliacao 
                       )
                values (
                                $this->ht17_sequencial 
                               ,$this->ht17_habitprograma 
                               ,'$this->ht17_descricao' 
                               ,".($this->ht17_datalimite == "null" || $this->ht17_datalimite == ""?"null":"'".$this->ht17_datalimite."'")." 
                               ,$this->ht17_formaavaliacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lista de Compras do Programa da Habitação ($this->ht17_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lista de Compras do Programa da Habitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lista de Compras do Programa da Habitação ($this->ht17_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht17_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht17_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17020,'$this->ht17_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3005,17020,'','".AddSlashes(pg_result($resaco,0,'ht17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3005,17021,'','".AddSlashes(pg_result($resaco,0,'ht17_habitprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3005,17022,'','".AddSlashes(pg_result($resaco,0,'ht17_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3005,17024,'','".AddSlashes(pg_result($resaco,0,'ht17_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3005,17073,'','".AddSlashes(pg_result($resaco,0,'ht17_formaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht17_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update habitprogramalistacompra set ";
     $virgula = "";
     if(trim($this->ht17_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht17_sequencial"])){ 
       $sql  .= $virgula." ht17_sequencial = $this->ht17_sequencial ";
       $virgula = ",";
       if(trim($this->ht17_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ht17_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht17_habitprograma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht17_habitprograma"])){ 
       $sql  .= $virgula." ht17_habitprograma = $this->ht17_habitprograma ";
       $virgula = ",";
       if(trim($this->ht17_habitprograma) == null ){ 
         $this->erro_sql = " Campo Programa nao Informado.";
         $this->erro_campo = "ht17_habitprograma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht17_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht17_descricao"])){ 
       $sql  .= $virgula." ht17_descricao = '$this->ht17_descricao' ";
       $virgula = ",";
       if(trim($this->ht17_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ht17_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht17_datalimite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht17_datalimite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ht17_datalimite_dia"] !="") ){ 
       $sql  .= $virgula." ht17_datalimite = '$this->ht17_datalimite' ";
       $virgula = ",";
     }else{ 
       $sql  .= $virgula." ht17_datalimite = null ";
       $virgula = ",";
     }
     if(trim($this->ht17_formaavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht17_formaavaliacao"])){ 
       $sql  .= $virgula." ht17_formaavaliacao = $this->ht17_formaavaliacao ";
       $virgula = ",";
       if(trim($this->ht17_formaavaliacao) == null ){ 
         $this->erro_sql = " Campo Forma de Avaliação nao Informado.";
         $this->erro_campo = "ht17_formaavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ht17_sequencial!=null){
       $sql .= " ht17_sequencial = $this->ht17_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht17_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17020,'$this->ht17_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht17_sequencial"]) || $this->ht17_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3005,17020,'".AddSlashes(pg_result($resaco,$conresaco,'ht17_sequencial'))."','$this->ht17_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht17_habitprograma"]) || $this->ht17_habitprograma != "")
           $resac = db_query("insert into db_acount values($acount,3005,17021,'".AddSlashes(pg_result($resaco,$conresaco,'ht17_habitprograma'))."','$this->ht17_habitprograma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht17_descricao"]) || $this->ht17_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3005,17022,'".AddSlashes(pg_result($resaco,$conresaco,'ht17_descricao'))."','$this->ht17_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht17_datalimite"]) || $this->ht17_datalimite != "")
           $resac = db_query("insert into db_acount values($acount,3005,17024,'".AddSlashes(pg_result($resaco,$conresaco,'ht17_datalimite'))."','$this->ht17_datalimite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht17_formaavaliacao"]) || $this->ht17_formaavaliacao != "")
           $resac = db_query("insert into db_acount values($acount,3005,17073,'".AddSlashes(pg_result($resaco,$conresaco,'ht17_formaavaliacao'))."','$this->ht17_formaavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lista de Compras do Programa da Habitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lista de Compras do Programa da Habitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht17_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht17_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17020,'$ht17_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3005,17020,'','".AddSlashes(pg_result($resaco,$iresaco,'ht17_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3005,17021,'','".AddSlashes(pg_result($resaco,$iresaco,'ht17_habitprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3005,17022,'','".AddSlashes(pg_result($resaco,$iresaco,'ht17_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3005,17024,'','".AddSlashes(pg_result($resaco,$iresaco,'ht17_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3005,17073,'','".AddSlashes(pg_result($resaco,$iresaco,'ht17_formaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habitprogramalistacompra
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht17_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht17_sequencial = $ht17_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lista de Compras do Programa da Habitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht17_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lista de Compras do Programa da Habitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht17_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht17_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:habitprogramalistacompra";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht17_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitprogramalistacompra ";
     $sql .= "      inner join habitprograma  on  habitprograma.ht01_sequencial = habitprogramalistacompra.ht17_habitprograma";
     $sql .= "      inner join habitformaavaliacao  on  habitformaavaliacao.ht07_sequencial = habitprogramalistacompra.ht17_formaavaliacao";
     $sql .= "      inner join habitgrupoprograma  on  habitgrupoprograma.ht03_sequencial = habitprograma.ht01_habitgrupoprograma";
     $sql2 = "";
     if($dbwhere==""){
       if($ht17_sequencial!=null ){
         $sql2 .= " where habitprogramalistacompra.ht17_sequencial = $ht17_sequencial "; 
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
   function sql_query_file ( $ht17_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitprogramalistacompra ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht17_sequencial!=null ){
         $sql2 .= " where habitprogramalistacompra.ht17_sequencial = $ht17_sequencial "; 
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