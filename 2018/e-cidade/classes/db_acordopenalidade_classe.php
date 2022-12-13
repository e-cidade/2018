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
//CLASSE DA ENTIDADE acordopenalidade
class cl_acordopenalidade { 
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
   var $ac13_sequencial = 0; 
   var $ac13_descricao = null; 
   var $ac13_obs = null; 
   var $ac13_textopadrao = null; 
   var $ac13_validade_dia = null; 
   var $ac13_validade_mes = null; 
   var $ac13_validade_ano = null; 
   var $ac13_validade = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac13_sequencial = int4 = Sequencial 
                 ac13_descricao = varchar(100) = Descrição 
                 ac13_obs = text = Observação 
                 ac13_textopadrao = text = Texto Padrão 
                 ac13_validade = date = Validade 
                 ";
   //funcao construtor da classe 
   function cl_acordopenalidade() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordopenalidade"); 
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
       $this->ac13_sequencial = ($this->ac13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac13_sequencial"]:$this->ac13_sequencial);
       $this->ac13_descricao = ($this->ac13_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac13_descricao"]:$this->ac13_descricao);
       $this->ac13_obs = ($this->ac13_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ac13_obs"]:$this->ac13_obs);
       $this->ac13_textopadrao = ($this->ac13_textopadrao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac13_textopadrao"]:$this->ac13_textopadrao);
       if($this->ac13_validade == ""){
         $this->ac13_validade_dia = ($this->ac13_validade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac13_validade_dia"]:$this->ac13_validade_dia);
         $this->ac13_validade_mes = ($this->ac13_validade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac13_validade_mes"]:$this->ac13_validade_mes);
         $this->ac13_validade_ano = ($this->ac13_validade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac13_validade_ano"]:$this->ac13_validade_ano);
         if($this->ac13_validade_dia != ""){
            $this->ac13_validade = $this->ac13_validade_ano."-".$this->ac13_validade_mes."-".$this->ac13_validade_dia;
         }
       }
     }else{
       $this->ac13_sequencial = ($this->ac13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac13_sequencial"]:$this->ac13_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac13_sequencial){ 
      $this->atualizacampos();
     if($this->ac13_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ac13_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac13_obs == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "ac13_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac13_textopadrao == null ){ 
       $this->erro_sql = " Campo Texto Padrão nao Informado.";
       $this->erro_campo = "ac13_textopadrao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac13_validade == null ){ 
       $this->erro_sql = " Campo Validade nao Informado.";
       $this->erro_campo = "ac13_validade_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac13_sequencial == "" || $ac13_sequencial == null ){
       $result = db_query("select nextval('acordopenalidade_ac13_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordopenalidade_ac13_sequencial_seq do campo: ac13_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac13_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordopenalidade_ac13_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac13_sequencial)){
         $this->erro_sql = " Campo ac13_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac13_sequencial = $ac13_sequencial; 
       }
     }
     if(($this->ac13_sequencial == null) || ($this->ac13_sequencial == "") ){ 
       $this->erro_sql = " Campo ac13_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordopenalidade(
                                       ac13_sequencial 
                                      ,ac13_descricao 
                                      ,ac13_obs 
                                      ,ac13_textopadrao 
                                      ,ac13_validade 
                       )
                values (
                                $this->ac13_sequencial 
                               ,'$this->ac13_descricao' 
                               ,'$this->ac13_obs' 
                               ,'$this->ac13_textopadrao' 
                               ,".($this->ac13_validade == "null" || $this->ac13_validade == ""?"null":"'".$this->ac13_validade."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acordo Penalidade ($this->ac13_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acordo Penalidade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acordo Penalidade ($this->ac13_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac13_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac13_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16156,'$this->ac13_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2834,16156,'','".AddSlashes(pg_result($resaco,0,'ac13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2834,16157,'','".AddSlashes(pg_result($resaco,0,'ac13_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2834,16158,'','".AddSlashes(pg_result($resaco,0,'ac13_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2834,16159,'','".AddSlashes(pg_result($resaco,0,'ac13_textopadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2834,16160,'','".AddSlashes(pg_result($resaco,0,'ac13_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac13_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordopenalidade set ";
     $virgula = "";
     if(trim($this->ac13_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac13_sequencial"])){ 
       $sql  .= $virgula." ac13_sequencial = $this->ac13_sequencial ";
       $virgula = ",";
       if(trim($this->ac13_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ac13_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac13_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac13_descricao"])){ 
       $sql  .= $virgula." ac13_descricao = '$this->ac13_descricao' ";
       $virgula = ",";
       if(trim($this->ac13_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ac13_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac13_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac13_obs"])){ 
       $sql  .= $virgula." ac13_obs = '$this->ac13_obs' ";
       $virgula = ",";
       if(trim($this->ac13_obs) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "ac13_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac13_textopadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac13_textopadrao"])){ 
       $sql  .= $virgula." ac13_textopadrao = '$this->ac13_textopadrao' ";
       $virgula = ",";
       if(trim($this->ac13_textopadrao) == null ){ 
         $this->erro_sql = " Campo Texto Padrão nao Informado.";
         $this->erro_campo = "ac13_textopadrao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac13_validade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac13_validade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac13_validade_dia"] !="") ){ 
       $sql  .= $virgula." ac13_validade = '$this->ac13_validade' ";
       $virgula = ",";
       if(trim($this->ac13_validade) == null ){ 
         $this->erro_sql = " Campo Validade nao Informado.";
         $this->erro_campo = "ac13_validade_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac13_validade_dia"])){ 
         $sql  .= $virgula." ac13_validade = null ";
         $virgula = ",";
         if(trim($this->ac13_validade) == null ){ 
           $this->erro_sql = " Campo Validade nao Informado.";
           $this->erro_campo = "ac13_validade_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($ac13_sequencial!=null){
       $sql .= " ac13_sequencial = $this->ac13_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac13_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16156,'$this->ac13_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac13_sequencial"]) || $this->ac13_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2834,16156,'".AddSlashes(pg_result($resaco,$conresaco,'ac13_sequencial'))."','$this->ac13_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac13_descricao"]) || $this->ac13_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2834,16157,'".AddSlashes(pg_result($resaco,$conresaco,'ac13_descricao'))."','$this->ac13_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac13_obs"]) || $this->ac13_obs != "")
           $resac = db_query("insert into db_acount values($acount,2834,16158,'".AddSlashes(pg_result($resaco,$conresaco,'ac13_obs'))."','$this->ac13_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac13_textopadrao"]) || $this->ac13_textopadrao != "")
           $resac = db_query("insert into db_acount values($acount,2834,16159,'".AddSlashes(pg_result($resaco,$conresaco,'ac13_textopadrao'))."','$this->ac13_textopadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac13_validade"]) || $this->ac13_validade != "")
           $resac = db_query("insert into db_acount values($acount,2834,16160,'".AddSlashes(pg_result($resaco,$conresaco,'ac13_validade'))."','$this->ac13_validade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Penalidade nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Penalidade nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac13_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac13_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16156,'$ac13_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2834,16156,'','".AddSlashes(pg_result($resaco,$iresaco,'ac13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2834,16157,'','".AddSlashes(pg_result($resaco,$iresaco,'ac13_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2834,16158,'','".AddSlashes(pg_result($resaco,$iresaco,'ac13_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2834,16159,'','".AddSlashes(pg_result($resaco,$iresaco,'ac13_textopadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2834,16160,'','".AddSlashes(pg_result($resaco,$iresaco,'ac13_validade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordopenalidade
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac13_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac13_sequencial = $ac13_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Penalidade nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Penalidade nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac13_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordopenalidade";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac13_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordopenalidade ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac13_sequencial!=null ){
         $sql2 .= " where acordopenalidade.ac13_sequencial = $ac13_sequencial "; 
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
   function sql_query_file ( $ac13_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordopenalidade ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac13_sequencial!=null ){
         $sql2 .= " where acordopenalidade.ac13_sequencial = $ac13_sequencial "; 
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