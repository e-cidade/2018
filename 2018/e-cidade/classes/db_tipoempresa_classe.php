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

//MODULO: Configuracoes
//CLASSE DA ENTIDADE tipoempresa
class cl_tipoempresa { 
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
   var $db98_sequencial = 0; 
   var $db98_descricao = null; 
   var $db98_estrutural = null; 
   var $db98_dataini_dia = null; 
   var $db98_dataini_mes = null; 
   var $db98_dataini_ano = null; 
   var $db98_dataini = null; 
   var $db98_datafin_dia = null; 
   var $db98_datafin_mes = null; 
   var $db98_datafin_ano = null; 
   var $db98_datafin = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db98_sequencial = int4 = Código 
                 db98_descricao = varchar(100) = Descrição 
                 db98_estrutural = varchar(50) = Estrutural 
                 db98_dataini = date = Data Inicial 
                 db98_datafin = date = Data Final 
                 ";
   //funcao construtor da classe 
   function cl_tipoempresa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tipoempresa"); 
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
       $this->db98_sequencial = ($this->db98_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db98_sequencial"]:$this->db98_sequencial);
       $this->db98_descricao = ($this->db98_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db98_descricao"]:$this->db98_descricao);
       $this->db98_estrutural = ($this->db98_estrutural == ""?@$GLOBALS["HTTP_POST_VARS"]["db98_estrutural"]:$this->db98_estrutural);
       if($this->db98_dataini == ""){
         $this->db98_dataini_dia = ($this->db98_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db98_dataini_dia"]:$this->db98_dataini_dia);
         $this->db98_dataini_mes = ($this->db98_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db98_dataini_mes"]:$this->db98_dataini_mes);
         $this->db98_dataini_ano = ($this->db98_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db98_dataini_ano"]:$this->db98_dataini_ano);
         if($this->db98_dataini_dia != ""){
            $this->db98_dataini = $this->db98_dataini_ano."-".$this->db98_dataini_mes."-".$this->db98_dataini_dia;
         }
       }
       if($this->db98_datafin == ""){
         $this->db98_datafin_dia = ($this->db98_datafin_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db98_datafin_dia"]:$this->db98_datafin_dia);
         $this->db98_datafin_mes = ($this->db98_datafin_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db98_datafin_mes"]:$this->db98_datafin_mes);
         $this->db98_datafin_ano = ($this->db98_datafin_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db98_datafin_ano"]:$this->db98_datafin_ano);
         if($this->db98_datafin_dia != ""){
            $this->db98_datafin = $this->db98_datafin_ano."-".$this->db98_datafin_mes."-".$this->db98_datafin_dia;
         }
       }
     }else{
       $this->db98_sequencial = ($this->db98_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db98_sequencial"]:$this->db98_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db98_sequencial){ 
      $this->atualizacampos();
     if($this->db98_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "db98_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db98_estrutural == null ){ 
       $this->erro_sql = " Campo Estrutural nao Informado.";
       $this->erro_campo = "db98_estrutural";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db98_dataini == null ){ 
       $this->db98_dataini = "null";
     }
     if($this->db98_datafin == null ){ 
       $this->db98_datafin = "null";
     }
     if($db98_sequencial == "" || $db98_sequencial == null ){
       $result = db_query("select nextval('tipoempresa_db98_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tipoempresa_db98_sequencial_seq do campo: db98_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db98_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tipoempresa_db98_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db98_sequencial)){
         $this->erro_sql = " Campo db98_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db98_sequencial = $db98_sequencial; 
       }
     }
     if(($this->db98_sequencial == null) || ($this->db98_sequencial == "") ){ 
       $this->erro_sql = " Campo db98_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tipoempresa(
                                       db98_sequencial 
                                      ,db98_descricao 
                                      ,db98_estrutural 
                                      ,db98_dataini 
                                      ,db98_datafin 
                       )
                values (
                                $this->db98_sequencial 
                               ,'$this->db98_descricao' 
                               ,'$this->db98_estrutural' 
                               ,".($this->db98_dataini == "null" || $this->db98_dataini == ""?"null":"'".$this->db98_dataini."'")." 
                               ,".($this->db98_datafin == "null" || $this->db98_datafin == ""?"null":"'".$this->db98_datafin."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "TIpo de Empresa ($this->db98_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "TIpo de Empresa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "TIpo de Empresa ($this->db98_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db98_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db98_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16202,'$this->db98_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2844,16202,'','".AddSlashes(pg_result($resaco,0,'db98_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2844,16203,'','".AddSlashes(pg_result($resaco,0,'db98_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2844,16204,'','".AddSlashes(pg_result($resaco,0,'db98_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2844,16205,'','".AddSlashes(pg_result($resaco,0,'db98_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2844,16206,'','".AddSlashes(pg_result($resaco,0,'db98_datafin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db98_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tipoempresa set ";
     $virgula = "";
     if(trim($this->db98_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db98_sequencial"])){ 
       $sql  .= $virgula." db98_sequencial = $this->db98_sequencial ";
       $virgula = ",";
       if(trim($this->db98_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "db98_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db98_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db98_descricao"])){ 
       $sql  .= $virgula." db98_descricao = '$this->db98_descricao' ";
       $virgula = ",";
       if(trim($this->db98_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "db98_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db98_estrutural)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db98_estrutural"])){ 
       $sql  .= $virgula." db98_estrutural = '$this->db98_estrutural' ";
       $virgula = ",";
       if(trim($this->db98_estrutural) == null ){ 
         $this->erro_sql = " Campo Estrutural nao Informado.";
         $this->erro_campo = "db98_estrutural";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db98_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db98_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db98_dataini_dia"] !="") ){ 
       $sql  .= $virgula." db98_dataini = '$this->db98_dataini' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["db98_dataini_dia"])){ 
         $sql  .= $virgula." db98_dataini = null ";
         $virgula = ",";
       }
     }
     if(trim($this->db98_datafin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db98_datafin_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db98_datafin_dia"] !="") ){ 
       $sql  .= $virgula." db98_datafin = '$this->db98_datafin' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["db98_datafin_dia"])){ 
         $sql  .= $virgula." db98_datafin = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($db98_sequencial!=null){
       $sql .= " db98_sequencial = $this->db98_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db98_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16202,'$this->db98_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db98_sequencial"]) || $this->db98_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2844,16202,'".AddSlashes(pg_result($resaco,$conresaco,'db98_sequencial'))."','$this->db98_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db98_descricao"]) || $this->db98_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2844,16203,'".AddSlashes(pg_result($resaco,$conresaco,'db98_descricao'))."','$this->db98_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db98_estrutural"]) || $this->db98_estrutural != "")
           $resac = db_query("insert into db_acount values($acount,2844,16204,'".AddSlashes(pg_result($resaco,$conresaco,'db98_estrutural'))."','$this->db98_estrutural',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db98_dataini"]) || $this->db98_dataini != "")
           $resac = db_query("insert into db_acount values($acount,2844,16205,'".AddSlashes(pg_result($resaco,$conresaco,'db98_dataini'))."','$this->db98_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db98_datafin"]) || $this->db98_datafin != "")
           $resac = db_query("insert into db_acount values($acount,2844,16206,'".AddSlashes(pg_result($resaco,$conresaco,'db98_datafin'))."','$this->db98_datafin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "TIpo de Empresa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db98_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "TIpo de Empresa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db98_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db98_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db98_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db98_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16202,'$db98_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2844,16202,'','".AddSlashes(pg_result($resaco,$iresaco,'db98_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2844,16203,'','".AddSlashes(pg_result($resaco,$iresaco,'db98_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2844,16204,'','".AddSlashes(pg_result($resaco,$iresaco,'db98_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2844,16205,'','".AddSlashes(pg_result($resaco,$iresaco,'db98_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2844,16206,'','".AddSlashes(pg_result($resaco,$iresaco,'db98_datafin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tipoempresa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db98_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db98_sequencial = $db98_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "TIpo de Empresa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db98_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "TIpo de Empresa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db98_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db98_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tipoempresa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db98_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tipoempresa ";
     $sql2 = "";
     if($dbwhere==""){
       if($db98_sequencial!=null ){
         $sql2 .= " where tipoempresa.db98_sequencial = $db98_sequencial "; 
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
   function sql_query_file ( $db98_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tipoempresa ";
     $sql2 = "";
     if($dbwhere==""){
       if($db98_sequencial!=null ){
         $sql2 .= " where tipoempresa.db98_sequencial = $db98_sequencial "; 
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