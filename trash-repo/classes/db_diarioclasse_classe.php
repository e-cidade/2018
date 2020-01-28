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
//CLASSE DA ENTIDADE diarioclasse
class cl_diarioclasse { 
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
   var $ed300_sequencial = 0; 
   var $ed300_id_usuario = 0; 
   var $ed300_datalancamento_dia = null; 
   var $ed300_datalancamento_mes = null; 
   var $ed300_datalancamento_ano = null; 
   var $ed300_datalancamento = null; 
   var $ed300_hora = null; 
   var $ed300_auladesenvolvida = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed300_sequencial = int4 = Sequencial interno 
                 ed300_id_usuario = int4 = Cod. Usuário 
                 ed300_datalancamento = date = Data de Lançamento 
                 ed300_hora = char(5) = Hora 
                 ed300_auladesenvolvida = text = Aula desenvolvida 
                 ";
   //funcao construtor da classe 
   function cl_diarioclasse() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("diarioclasse"); 
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
       $this->ed300_sequencial = ($this->ed300_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed300_sequencial"]:$this->ed300_sequencial);
       $this->ed300_id_usuario = ($this->ed300_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed300_id_usuario"]:$this->ed300_id_usuario);
       if($this->ed300_datalancamento == ""){
         $this->ed300_datalancamento_dia = ($this->ed300_datalancamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed300_datalancamento_dia"]:$this->ed300_datalancamento_dia);
         $this->ed300_datalancamento_mes = ($this->ed300_datalancamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed300_datalancamento_mes"]:$this->ed300_datalancamento_mes);
         $this->ed300_datalancamento_ano = ($this->ed300_datalancamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed300_datalancamento_ano"]:$this->ed300_datalancamento_ano);
         if($this->ed300_datalancamento_dia != ""){
            $this->ed300_datalancamento = $this->ed300_datalancamento_ano."-".$this->ed300_datalancamento_mes."-".$this->ed300_datalancamento_dia;
         }
       }
       $this->ed300_hora = ($this->ed300_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ed300_hora"]:$this->ed300_hora);
       $this->ed300_auladesenvolvida = ($this->ed300_auladesenvolvida == ""?@$GLOBALS["HTTP_POST_VARS"]["ed300_auladesenvolvida"]:$this->ed300_auladesenvolvida);
     }else{
       $this->ed300_sequencial = ($this->ed300_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed300_sequencial"]:$this->ed300_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed300_sequencial){ 
      $this->atualizacampos();
     if($this->ed300_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "ed300_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed300_datalancamento == null ){ 
       $this->erro_sql = " Campo Data de Lançamento nao Informado.";
       $this->erro_campo = "ed300_datalancamento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed300_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "ed300_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed300_sequencial == "" || $ed300_sequencial == null ){
       $result = db_query("select nextval('diarioclasse_ed300_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diarioclasse_ed300_sequencial_seq do campo: ed300_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed300_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from diarioclasse_ed300_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed300_sequencial)){
         $this->erro_sql = " Campo ed300_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed300_sequencial = $ed300_sequencial; 
       }
     }
     if(($this->ed300_sequencial == null) || ($this->ed300_sequencial == "") ){ 
       $this->erro_sql = " Campo ed300_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diarioclasse(
                                       ed300_sequencial 
                                      ,ed300_id_usuario 
                                      ,ed300_datalancamento 
                                      ,ed300_hora 
                                      ,ed300_auladesenvolvida 
                       )
                values (
                                $this->ed300_sequencial 
                               ,$this->ed300_id_usuario 
                               ,".($this->ed300_datalancamento == "null" || $this->ed300_datalancamento == ""?"null":"'".$this->ed300_datalancamento."'")." 
                               ,'$this->ed300_hora' 
                               ,'$this->ed300_auladesenvolvida' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Diario de classe ($this->ed300_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Diario de classe já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Diario de classe ($this->ed300_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed300_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed300_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18766,'$this->ed300_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3327,18766,'','".AddSlashes(pg_result($resaco,0,'ed300_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3327,18774,'','".AddSlashes(pg_result($resaco,0,'ed300_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3327,18767,'','".AddSlashes(pg_result($resaco,0,'ed300_datalancamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3327,18768,'','".AddSlashes(pg_result($resaco,0,'ed300_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3327,18769,'','".AddSlashes(pg_result($resaco,0,'ed300_auladesenvolvida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed300_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update diarioclasse set ";
     $virgula = "";
     if(trim($this->ed300_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed300_sequencial"])){ 
       $sql  .= $virgula." ed300_sequencial = $this->ed300_sequencial ";
       $virgula = ",";
       if(trim($this->ed300_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial interno nao Informado.";
         $this->erro_campo = "ed300_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed300_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed300_id_usuario"])){ 
       $sql  .= $virgula." ed300_id_usuario = $this->ed300_id_usuario ";
       $virgula = ",";
       if(trim($this->ed300_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "ed300_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed300_datalancamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed300_datalancamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed300_datalancamento_dia"] !="") ){ 
       $sql  .= $virgula." ed300_datalancamento = '$this->ed300_datalancamento' ";
       $virgula = ",";
       if(trim($this->ed300_datalancamento) == null ){ 
         $this->erro_sql = " Campo Data de Lançamento nao Informado.";
         $this->erro_campo = "ed300_datalancamento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed300_datalancamento_dia"])){ 
         $sql  .= $virgula." ed300_datalancamento = null ";
         $virgula = ",";
         if(trim($this->ed300_datalancamento) == null ){ 
           $this->erro_sql = " Campo Data de Lançamento nao Informado.";
           $this->erro_campo = "ed300_datalancamento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed300_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed300_hora"])){ 
       $sql  .= $virgula." ed300_hora = '$this->ed300_hora' ";
       $virgula = ",";
       if(trim($this->ed300_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "ed300_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed300_auladesenvolvida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed300_auladesenvolvida"])){ 
       $sql  .= $virgula." ed300_auladesenvolvida = '$this->ed300_auladesenvolvida' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed300_sequencial!=null){
       $sql .= " ed300_sequencial = $this->ed300_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed300_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18766,'$this->ed300_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed300_sequencial"]) || $this->ed300_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3327,18766,'".AddSlashes(pg_result($resaco,$conresaco,'ed300_sequencial'))."','$this->ed300_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed300_id_usuario"]) || $this->ed300_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3327,18774,'".AddSlashes(pg_result($resaco,$conresaco,'ed300_id_usuario'))."','$this->ed300_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed300_datalancamento"]) || $this->ed300_datalancamento != "")
           $resac = db_query("insert into db_acount values($acount,3327,18767,'".AddSlashes(pg_result($resaco,$conresaco,'ed300_datalancamento'))."','$this->ed300_datalancamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed300_hora"]) || $this->ed300_hora != "")
           $resac = db_query("insert into db_acount values($acount,3327,18768,'".AddSlashes(pg_result($resaco,$conresaco,'ed300_hora'))."','$this->ed300_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed300_auladesenvolvida"]) || $this->ed300_auladesenvolvida != "")
           $resac = db_query("insert into db_acount values($acount,3327,18769,'".AddSlashes(pg_result($resaco,$conresaco,'ed300_auladesenvolvida'))."','$this->ed300_auladesenvolvida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diario de classe nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed300_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Diario de classe nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed300_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed300_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed300_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed300_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18766,'$ed300_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3327,18766,'','".AddSlashes(pg_result($resaco,$iresaco,'ed300_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3327,18774,'','".AddSlashes(pg_result($resaco,$iresaco,'ed300_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3327,18767,'','".AddSlashes(pg_result($resaco,$iresaco,'ed300_datalancamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3327,18768,'','".AddSlashes(pg_result($resaco,$iresaco,'ed300_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3327,18769,'','".AddSlashes(pg_result($resaco,$iresaco,'ed300_auladesenvolvida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from diarioclasse
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed300_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed300_sequencial = $ed300_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diario de classe nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed300_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Diario de classe nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed300_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed300_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:diarioclasse";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed300_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from diarioclasse ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = diarioclasse.ed300_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($ed300_sequencial!=null ){
         $sql2 .= " where diarioclasse.ed300_sequencial = $ed300_sequencial "; 
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
   function sql_query_file ( $ed300_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from diarioclasse ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed300_sequencial!=null ){
         $sql2 .= " where diarioclasse.ed300_sequencial = $ed300_sequencial "; 
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
  
  function sql_query_faltas ($ed300_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") { 

    $sql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#", $campos);
      $virgula    = "";
      for( $i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
       $sql .= $campos;
    }
    $sql .= " from diarioclasse ";
    $sql .= "      inner join diarioclasseregenciahorario on ed300_sequencial      = ed302_diarioclasse ";
    $sql .= "      left  join diarioclassealunofalta      on ed302_sequencial      = ed301_diarioclasseregenciahorario ";
    $sql .= "      inner join regenciahorario             on ed302_regenciahorario = ed58_i_codigo";
    $sql .= "      inner join regencia                    on ed58_i_regencia       = ed59_i_codigo";
    $sql2 = "";
    if ($dbwhere == "") {
     
      if ($ed300_sequencial != null) {
        $sql2 .= " where diarioclasse.ed300_sequencial = $ed300_sequencial "; 
      } 
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {

      $sql        .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
?>