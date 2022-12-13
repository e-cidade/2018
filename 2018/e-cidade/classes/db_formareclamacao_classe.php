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

//MODULO: ouvidoria
//CLASSE DA ENTIDADE formareclamacao
class cl_formareclamacao { 
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
   var $p42_sequencial = 0; 
   var $p42_descricao = null; 
   var $p42_dtinicio_dia = null; 
   var $p42_dtinicio_mes = null; 
   var $p42_dtinicio_ano = null; 
   var $p42_dtinicio = null; 
   var $p42_dtfim_dia = null; 
   var $p42_dtfim_mes = null; 
   var $p42_dtfim_ano = null; 
   var $p42_dtfim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p42_sequencial = int4 = Sequencial 
                 p42_descricao = varchar(100) = Descrição 
                 p42_dtinicio = date = Data Inicio 
                 p42_dtfim = date = Data Fim 
                 ";
   //funcao construtor da classe 
   function cl_formareclamacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("formareclamacao"); 
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
       $this->p42_sequencial = ($this->p42_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p42_sequencial"]:$this->p42_sequencial);
       $this->p42_descricao = ($this->p42_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["p42_descricao"]:$this->p42_descricao);
       if($this->p42_dtinicio == ""){
         $this->p42_dtinicio_dia = ($this->p42_dtinicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p42_dtinicio_dia"]:$this->p42_dtinicio_dia);
         $this->p42_dtinicio_mes = ($this->p42_dtinicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p42_dtinicio_mes"]:$this->p42_dtinicio_mes);
         $this->p42_dtinicio_ano = ($this->p42_dtinicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p42_dtinicio_ano"]:$this->p42_dtinicio_ano);
         if($this->p42_dtinicio_dia != ""){
            $this->p42_dtinicio = $this->p42_dtinicio_ano."-".$this->p42_dtinicio_mes."-".$this->p42_dtinicio_dia;
         }
       }
       if($this->p42_dtfim == ""){
         $this->p42_dtfim_dia = ($this->p42_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p42_dtfim_dia"]:$this->p42_dtfim_dia);
         $this->p42_dtfim_mes = ($this->p42_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p42_dtfim_mes"]:$this->p42_dtfim_mes);
         $this->p42_dtfim_ano = ($this->p42_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p42_dtfim_ano"]:$this->p42_dtfim_ano);
         if($this->p42_dtfim_dia != ""){
            $this->p42_dtfim = $this->p42_dtfim_ano."-".$this->p42_dtfim_mes."-".$this->p42_dtfim_dia;
         }
       }
     }else{
       $this->p42_sequencial = ($this->p42_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p42_sequencial"]:$this->p42_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($p42_sequencial){ 
      $this->atualizacampos();
     if($this->p42_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "p42_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p42_dtinicio == null ){ 
       $this->erro_sql = " Campo Data Inicio nao Informado.";
       $this->erro_campo = "p42_dtinicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p42_dtfim == null ){ 
       $this->erro_sql = " Campo Data Fim nao Informado.";
       $this->erro_campo = "p42_dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($p42_sequencial == "" || $p42_sequencial == null ){
       $result = db_query("select nextval('formareclamacao_p42_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: formareclamacao_p42_sequencial_seq do campo: p42_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->p42_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from formareclamacao_p42_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $p42_sequencial)){
         $this->erro_sql = " Campo p42_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p42_sequencial = $p42_sequencial; 
       }
     }
     if(($this->p42_sequencial == null) || ($this->p42_sequencial == "") ){ 
       $this->erro_sql = " Campo p42_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into formareclamacao(
                                       p42_sequencial 
                                      ,p42_descricao 
                                      ,p42_dtinicio 
                                      ,p42_dtfim 
                       )
                values (
                                $this->p42_sequencial 
                               ,'$this->p42_descricao' 
                               ,".($this->p42_dtinicio == "null" || $this->p42_dtinicio == ""?"null":"'".$this->p42_dtinicio."'")." 
                               ,".($this->p42_dtfim == "null" || $this->p42_dtfim == ""?"null":"'".$this->p42_dtfim."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Formas de Reclamação ($this->p42_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Formas de Reclamação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Formas de Reclamação ($this->p42_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p42_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->p42_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14710,'$this->p42_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2587,14710,'','".AddSlashes(pg_result($resaco,0,'p42_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2587,14711,'','".AddSlashes(pg_result($resaco,0,'p42_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2587,14712,'','".AddSlashes(pg_result($resaco,0,'p42_dtinicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2587,14713,'','".AddSlashes(pg_result($resaco,0,'p42_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p42_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update formareclamacao set ";
     $virgula = "";
     if(trim($this->p42_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p42_sequencial"])){ 
       $sql  .= $virgula." p42_sequencial = $this->p42_sequencial ";
       $virgula = ",";
       if(trim($this->p42_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "p42_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p42_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p42_descricao"])){ 
       $sql  .= $virgula." p42_descricao = '$this->p42_descricao' ";
       $virgula = ",";
       if(trim($this->p42_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "p42_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p42_dtinicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p42_dtinicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p42_dtinicio_dia"] !="") ){ 
       $sql  .= $virgula." p42_dtinicio = '$this->p42_dtinicio' ";
       $virgula = ",";
       if(trim($this->p42_dtinicio) == null ){ 
         $this->erro_sql = " Campo Data Inicio nao Informado.";
         $this->erro_campo = "p42_dtinicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["p42_dtinicio_dia"])){ 
         $sql  .= $virgula." p42_dtinicio = null ";
         $virgula = ",";
         if(trim($this->p42_dtinicio) == null ){ 
           $this->erro_sql = " Campo Data Inicio nao Informado.";
           $this->erro_campo = "p42_dtinicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->p42_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p42_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p42_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." p42_dtfim = '$this->p42_dtfim' ";
       $virgula = ",";
       if(trim($this->p42_dtfim) == null ){ 
         $this->erro_sql = " Campo Data Fim nao Informado.";
         $this->erro_campo = "p42_dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["p42_dtfim_dia"])){ 
         $sql  .= $virgula." p42_dtfim = null ";
         $virgula = ",";
         if(trim($this->p42_dtfim) == null ){ 
           $this->erro_sql = " Campo Data Fim nao Informado.";
           $this->erro_campo = "p42_dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($p42_sequencial!=null){
       $sql .= " p42_sequencial = $this->p42_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->p42_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14710,'$this->p42_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p42_sequencial"]) || $this->p42_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2587,14710,'".AddSlashes(pg_result($resaco,$conresaco,'p42_sequencial'))."','$this->p42_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p42_descricao"]) || $this->p42_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2587,14711,'".AddSlashes(pg_result($resaco,$conresaco,'p42_descricao'))."','$this->p42_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p42_dtinicio"]) || $this->p42_dtinicio != "")
           $resac = db_query("insert into db_acount values($acount,2587,14712,'".AddSlashes(pg_result($resaco,$conresaco,'p42_dtinicio'))."','$this->p42_dtinicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p42_dtfim"]) || $this->p42_dtfim != "")
           $resac = db_query("insert into db_acount values($acount,2587,14713,'".AddSlashes(pg_result($resaco,$conresaco,'p42_dtfim'))."','$this->p42_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Formas de Reclamação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p42_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Formas de Reclamação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p42_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p42_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p42_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($p42_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14710,'$p42_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2587,14710,'','".AddSlashes(pg_result($resaco,$iresaco,'p42_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2587,14711,'','".AddSlashes(pg_result($resaco,$iresaco,'p42_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2587,14712,'','".AddSlashes(pg_result($resaco,$iresaco,'p42_dtinicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2587,14713,'','".AddSlashes(pg_result($resaco,$iresaco,'p42_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from formareclamacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p42_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p42_sequencial = $p42_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Formas de Reclamação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p42_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Formas de Reclamação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p42_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p42_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:formareclamacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $p42_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from formareclamacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($p42_sequencial!=null ){
         $sql2 .= " where formareclamacao.p42_sequencial = $p42_sequencial "; 
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
   function sql_query_file ( $p42_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from formareclamacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($p42_sequencial!=null ){
         $sql2 .= " where formareclamacao.p42_sequencial = $p42_sequencial "; 
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