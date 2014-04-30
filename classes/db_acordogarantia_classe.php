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
//CLASSE DA ENTIDADE acordogarantia
class cl_acordogarantia { 
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
   var $ac11_sequencial = 0; 
   var $ac11_descricao = null; 
   var $ac11_obs = null; 
   var $ac11_textopadrao = null; 
   var $ac11_validade_dia = null; 
   var $ac11_validade_mes = null; 
   var $ac11_validade_ano = null; 
   var $ac11_validade = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac11_sequencial = int4 = Sequencial 
                 ac11_descricao = varchar(100) = Descrição 
                 ac11_obs = text = Observação 
                 ac11_textopadrao = text = Texto Padrão 
                 ac11_validade = date = Data de Validade 
                 ";
   //funcao construtor da classe 
   function cl_acordogarantia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordogarantia"); 
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
       $this->ac11_sequencial = ($this->ac11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac11_sequencial"]:$this->ac11_sequencial);
       $this->ac11_descricao = ($this->ac11_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac11_descricao"]:$this->ac11_descricao);
       $this->ac11_obs = ($this->ac11_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ac11_obs"]:$this->ac11_obs);
       $this->ac11_textopadrao = ($this->ac11_textopadrao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac11_textopadrao"]:$this->ac11_textopadrao);
       if($this->ac11_validade == ""){
         $this->ac11_validade_dia = ($this->ac11_validade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac11_validade_dia"]:$this->ac11_validade_dia);
         $this->ac11_validade_mes = ($this->ac11_validade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac11_validade_mes"]:$this->ac11_validade_mes);
         $this->ac11_validade_ano = ($this->ac11_validade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac11_validade_ano"]:$this->ac11_validade_ano);
         if($this->ac11_validade_dia != ""){
            $this->ac11_validade = $this->ac11_validade_ano."-".$this->ac11_validade_mes."-".$this->ac11_validade_dia;
         }
       }
     }else{
       $this->ac11_sequencial = ($this->ac11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac11_sequencial"]:$this->ac11_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac11_sequencial){ 
      $this->atualizacampos();
     if($this->ac11_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ac11_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac11_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "ac11_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac11_textopadrao == null ){ 
       $this->erro_sql = " Campo Texto Padrão nao Informado.";
       $this->erro_campo = "ac11_textopadrao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac11_validade == null ){ 
       $this->erro_sql = " Campo Data de Validade nao Informado.";
       $this->erro_campo = "ac11_validade_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac11_sequencial == "" || $ac11_sequencial == null ){
       $result = db_query("select nextval('acordogarantia_ac11_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordogarantia_ac11_sequencial_seq do campo: ac11_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac11_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordogarantia_ac11_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac11_sequencial)){
         $this->erro_sql = " Campo ac11_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac11_sequencial = $ac11_sequencial; 
       }
     }
     if(($this->ac11_sequencial == null) || ($this->ac11_sequencial == "") ){ 
       $this->erro_sql = " Campo ac11_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordogarantia(
                                       ac11_sequencial 
                                      ,ac11_descricao 
                                      ,ac11_obs 
                                      ,ac11_textopadrao 
                                      ,ac11_validade 
                       )
                values (
                                $this->ac11_sequencial 
                               ,'$this->ac11_descricao' 
                               ,'$this->ac11_obs' 
                               ,'$this->ac11_textopadrao' 
                               ,".($this->ac11_validade == "null" || $this->ac11_validade == ""?"null":"'".$this->ac11_validade."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acordo Garantia ($this->ac11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acordo Garantia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acordo Garantia ($this->ac11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac11_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac11_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16131,'$this->ac11_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2829,16131,'','".AddSlashes(pg_result($resaco,0,'ac11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2829,16132,'','".AddSlashes(pg_result($resaco,0,'ac11_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2829,16133,'','".AddSlashes(pg_result($resaco,0,'ac11_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2829,16134,'','".AddSlashes(pg_result($resaco,0,'ac11_textopadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2829,16135,'','".AddSlashes(pg_result($resaco,0,'ac11_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac11_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordogarantia set ";
     $virgula = "";
     if(trim($this->ac11_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac11_sequencial"])){ 
       $sql  .= $virgula." ac11_sequencial = $this->ac11_sequencial ";
       $virgula = ",";
       if(trim($this->ac11_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ac11_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac11_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac11_descricao"])){ 
       $sql  .= $virgula." ac11_descricao = '$this->ac11_descricao' ";
       $virgula = ",";
       if(trim($this->ac11_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ac11_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac11_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac11_obs"])){ 
       $sql  .= $virgula." ac11_obs = '$this->ac11_obs' ";
       $virgula = ",";
       if(trim($this->ac11_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "ac11_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac11_textopadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac11_textopadrao"])){ 
       $sql  .= $virgula." ac11_textopadrao = '$this->ac11_textopadrao' ";
       $virgula = ",";
       if(trim($this->ac11_textopadrao) == null ){ 
         $this->erro_sql = " Campo Texto Padrão nao Informado.";
         $this->erro_campo = "ac11_textopadrao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac11_validade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac11_validade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac11_validade_dia"] !="") ){ 
       $sql  .= $virgula." ac11_validade = '$this->ac11_validade' ";
       $virgula = ",";
       if(trim($this->ac11_validade) == null ){ 
         $this->erro_sql = " Campo Data de Validade nao Informado.";
         $this->erro_campo = "ac11_validade_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac11_validade_dia"])){ 
         $sql  .= $virgula." ac11_validade = null ";
         $virgula = ",";
         if(trim($this->ac11_validade) == null ){ 
           $this->erro_sql = " Campo Data de Validade nao Informado.";
           $this->erro_campo = "ac11_validade_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($ac11_sequencial!=null){
       $sql .= " ac11_sequencial = $this->ac11_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac11_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16131,'$this->ac11_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac11_sequencial"]) || $this->ac11_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2829,16131,'".AddSlashes(pg_result($resaco,$conresaco,'ac11_sequencial'))."','$this->ac11_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac11_descricao"]) || $this->ac11_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2829,16132,'".AddSlashes(pg_result($resaco,$conresaco,'ac11_descricao'))."','$this->ac11_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac11_obs"]) || $this->ac11_obs != "")
           $resac = db_query("insert into db_acount values($acount,2829,16133,'".AddSlashes(pg_result($resaco,$conresaco,'ac11_obs'))."','$this->ac11_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac11_textopadrao"]) || $this->ac11_textopadrao != "")
           $resac = db_query("insert into db_acount values($acount,2829,16134,'".AddSlashes(pg_result($resaco,$conresaco,'ac11_textopadrao'))."','$this->ac11_textopadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac11_validade"]) || $this->ac11_validade != "")
           $resac = db_query("insert into db_acount values($acount,2829,16135,'".AddSlashes(pg_result($resaco,$conresaco,'ac11_validade'))."','$this->ac11_validade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Garantia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Garantia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac11_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac11_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16131,'$ac11_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2829,16131,'','".AddSlashes(pg_result($resaco,$iresaco,'ac11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2829,16132,'','".AddSlashes(pg_result($resaco,$iresaco,'ac11_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2829,16133,'','".AddSlashes(pg_result($resaco,$iresaco,'ac11_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2829,16134,'','".AddSlashes(pg_result($resaco,$iresaco,'ac11_textopadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2829,16135,'','".AddSlashes(pg_result($resaco,$iresaco,'ac11_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordogarantia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac11_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac11_sequencial = $ac11_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Garantia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Garantia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac11_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordogarantia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordogarantia ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac11_sequencial!=null ){
         $sql2 .= " where acordogarantia.ac11_sequencial = $ac11_sequencial "; 
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
   function sql_query_file ( $ac11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordogarantia ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac11_sequencial!=null ){
         $sql2 .= " where acordogarantia.ac11_sequencial = $ac11_sequencial "; 
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