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

//MODULO: escola
//CLASSE DA ENTIDADE ocorrencia
class cl_ocorrencia { 
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
   var $ed103_sequencial = 0; 
   var $ed103_matricula = 0; 
   var $ed103_dataocorrencia_dia = null; 
   var $ed103_dataocorrencia_mes = null; 
   var $ed103_dataocorrencia_ano = null; 
   var $ed103_dataocorrencia = null; 
   var $ed103_ocorrenciatipo = 0; 
   var $ed103_texto = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed103_sequencial = int4 = Código da Ocorrência 
                 ed103_matricula = int4 = Matrícula 
                 ed103_dataocorrencia = date = Data da Ocorrência 
                 ed103_ocorrenciatipo = int4 = Ocorrência Tipo 
                 ed103_texto = varchar(150) = Texto 
                 ";
   //funcao construtor da classe 
   function cl_ocorrencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ocorrencia"); 
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
       $this->ed103_sequencial = ($this->ed103_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_sequencial"]:$this->ed103_sequencial);
       $this->ed103_matricula = ($this->ed103_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_matricula"]:$this->ed103_matricula);
       if($this->ed103_dataocorrencia == ""){
         $this->ed103_dataocorrencia_dia = ($this->ed103_dataocorrencia_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_dataocorrencia_dia"]:$this->ed103_dataocorrencia_dia);
         $this->ed103_dataocorrencia_mes = ($this->ed103_dataocorrencia_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_dataocorrencia_mes"]:$this->ed103_dataocorrencia_mes);
         $this->ed103_dataocorrencia_ano = ($this->ed103_dataocorrencia_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_dataocorrencia_ano"]:$this->ed103_dataocorrencia_ano);
         if($this->ed103_dataocorrencia_dia != ""){
            $this->ed103_dataocorrencia = $this->ed103_dataocorrencia_ano."-".$this->ed103_dataocorrencia_mes."-".$this->ed103_dataocorrencia_dia;
         }
       }
       $this->ed103_ocorrenciatipo = ($this->ed103_ocorrenciatipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_ocorrenciatipo"]:$this->ed103_ocorrenciatipo);
       $this->ed103_texto = ($this->ed103_texto == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_texto"]:$this->ed103_texto);
     }else{
       $this->ed103_sequencial = ($this->ed103_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed103_sequencial"]:$this->ed103_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed103_sequencial){ 
      $this->atualizacampos();
     if($this->ed103_matricula == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "ed103_matricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed103_dataocorrencia == null ){ 
       $this->erro_sql = " Campo Data da Ocorrência nao Informado.";
       $this->erro_campo = "ed103_dataocorrencia_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed103_ocorrenciatipo == null ){ 
       $this->erro_sql = " Campo Ocorrência Tipo nao Informado.";
       $this->erro_campo = "ed103_ocorrenciatipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed103_texto == null ){ 
       $this->erro_sql = " Campo Texto nao Informado.";
       $this->erro_campo = "ed103_texto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed103_sequencial == "" || $ed103_sequencial == null ){
       $result = db_query("select nextval('ocorrencia_ed103_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ocorrencia_ed103_sequencial_seq do campo: ed103_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed103_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ocorrencia_ed103_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed103_sequencial)){
         $this->erro_sql = " Campo ed103_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed103_sequencial = $ed103_sequencial; 
       }
     }
     if(($this->ed103_sequencial == null) || ($this->ed103_sequencial == "") ){ 
       $this->erro_sql = " Campo ed103_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ocorrencia(
                                       ed103_sequencial 
                                      ,ed103_matricula 
                                      ,ed103_dataocorrencia 
                                      ,ed103_ocorrenciatipo 
                                      ,ed103_texto 
                       )
                values (
                                $this->ed103_sequencial 
                               ,$this->ed103_matricula 
                               ,".($this->ed103_dataocorrencia == "null" || $this->ed103_dataocorrencia == ""?"null":"'".$this->ed103_dataocorrencia."'")." 
                               ,$this->ed103_ocorrenciatipo 
                               ,'$this->ed103_texto' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "ocorrencia ($this->ed103_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "ocorrencia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "ocorrencia ($this->ed103_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed103_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed103_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19246,'$this->ed103_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3420,19246,'','".AddSlashes(pg_result($resaco,0,'ed103_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3420,19247,'','".AddSlashes(pg_result($resaco,0,'ed103_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3420,19248,'','".AddSlashes(pg_result($resaco,0,'ed103_dataocorrencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3420,19249,'','".AddSlashes(pg_result($resaco,0,'ed103_ocorrenciatipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3420,19250,'','".AddSlashes(pg_result($resaco,0,'ed103_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed103_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ocorrencia set ";
     $virgula = "";
     if(trim($this->ed103_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed103_sequencial"])){ 
       $sql  .= $virgula." ed103_sequencial = $this->ed103_sequencial ";
       $virgula = ",";
       if(trim($this->ed103_sequencial) == null ){ 
         $this->erro_sql = " Campo Código da Ocorrência nao Informado.";
         $this->erro_campo = "ed103_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed103_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed103_matricula"])){ 
       $sql  .= $virgula." ed103_matricula = $this->ed103_matricula ";
       $virgula = ",";
       if(trim($this->ed103_matricula) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "ed103_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed103_dataocorrencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed103_dataocorrencia_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed103_dataocorrencia_dia"] !="") ){ 
       $sql  .= $virgula." ed103_dataocorrencia = '$this->ed103_dataocorrencia' ";
       $virgula = ",";
       if(trim($this->ed103_dataocorrencia) == null ){ 
         $this->erro_sql = " Campo Data da Ocorrência nao Informado.";
         $this->erro_campo = "ed103_dataocorrencia_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed103_dataocorrencia_dia"])){ 
         $sql  .= $virgula." ed103_dataocorrencia = null ";
         $virgula = ",";
         if(trim($this->ed103_dataocorrencia) == null ){ 
           $this->erro_sql = " Campo Data da Ocorrência nao Informado.";
           $this->erro_campo = "ed103_dataocorrencia_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed103_ocorrenciatipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed103_ocorrenciatipo"])){ 
       $sql  .= $virgula." ed103_ocorrenciatipo = $this->ed103_ocorrenciatipo ";
       $virgula = ",";
       if(trim($this->ed103_ocorrenciatipo) == null ){ 
         $this->erro_sql = " Campo Ocorrência Tipo nao Informado.";
         $this->erro_campo = "ed103_ocorrenciatipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed103_texto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed103_texto"])){ 
       $sql  .= $virgula." ed103_texto = '$this->ed103_texto' ";
       $virgula = ",";
       if(trim($this->ed103_texto) == null ){ 
         $this->erro_sql = " Campo Texto nao Informado.";
         $this->erro_campo = "ed103_texto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed103_sequencial!=null){
       $sql .= " ed103_sequencial = $this->ed103_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed103_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19246,'$this->ed103_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed103_sequencial"]) || $this->ed103_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3420,19246,'".AddSlashes(pg_result($resaco,$conresaco,'ed103_sequencial'))."','$this->ed103_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed103_matricula"]) || $this->ed103_matricula != "")
           $resac = db_query("insert into db_acount values($acount,3420,19247,'".AddSlashes(pg_result($resaco,$conresaco,'ed103_matricula'))."','$this->ed103_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed103_dataocorrencia"]) || $this->ed103_dataocorrencia != "")
           $resac = db_query("insert into db_acount values($acount,3420,19248,'".AddSlashes(pg_result($resaco,$conresaco,'ed103_dataocorrencia'))."','$this->ed103_dataocorrencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed103_ocorrenciatipo"]) || $this->ed103_ocorrenciatipo != "")
           $resac = db_query("insert into db_acount values($acount,3420,19249,'".AddSlashes(pg_result($resaco,$conresaco,'ed103_ocorrenciatipo'))."','$this->ed103_ocorrenciatipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed103_texto"]) || $this->ed103_texto != "")
           $resac = db_query("insert into db_acount values($acount,3420,19250,'".AddSlashes(pg_result($resaco,$conresaco,'ed103_texto'))."','$this->ed103_texto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ocorrencia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed103_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ocorrencia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed103_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed103_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed103_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed103_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19246,'$ed103_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3420,19246,'','".AddSlashes(pg_result($resaco,$iresaco,'ed103_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3420,19247,'','".AddSlashes(pg_result($resaco,$iresaco,'ed103_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3420,19248,'','".AddSlashes(pg_result($resaco,$iresaco,'ed103_dataocorrencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3420,19249,'','".AddSlashes(pg_result($resaco,$iresaco,'ed103_ocorrenciatipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3420,19250,'','".AddSlashes(pg_result($resaco,$iresaco,'ed103_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ocorrencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed103_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed103_sequencial = $ed103_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ocorrencia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed103_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ocorrencia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed103_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed103_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ocorrencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed103_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ocorrencia ";
     $sql .= "      inner join ocorrenciatipo  on  ocorrenciatipo.ed102_sequencial = ocorrencia.ed103_ocorrenciatipo";
     $sql .= "      inner join matricula  on  matricula.ed60_i_codigo = ocorrencia.ed103_matricula";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = matricula.ed60_i_aluno";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = matricula.ed60_i_turma and  turma.ed57_i_codigo = matricula.ed60_i_turmaant";
     $sql2 = "";
     if($dbwhere==""){
       if($ed103_sequencial!=null ){
         $sql2 .= " where ocorrencia.ed103_sequencial = $ed103_sequencial "; 
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
   function sql_query_file ( $ed103_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ocorrencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed103_sequencial!=null ){
         $sql2 .= " where ocorrencia.ed103_sequencial = $ed103_sequencial "; 
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