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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcppalei
class cl_orcppalei { 
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
   var $o21_codleippa = 0; 
   var $o21_anoini = 0; 
   var $o21_anofim = 0; 
   var $o21_descr = null; 
   var $o21_numero = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o21_codleippa = int8 = Sequencial 
                 o21_anoini = int4 = Ano de inicío 
                 o21_anofim = int4 = Ano final 
                 o21_descr = varchar(40) = Descrição 
                 o21_numero = char(10) = Numero da Lei 
                 ";
   //funcao construtor da classe 
   function cl_orcppalei() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcppalei"); 
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
       $this->o21_codleippa = ($this->o21_codleippa == ""?@$GLOBALS["HTTP_POST_VARS"]["o21_codleippa"]:$this->o21_codleippa);
       $this->o21_anoini = ($this->o21_anoini == ""?@$GLOBALS["HTTP_POST_VARS"]["o21_anoini"]:$this->o21_anoini);
       $this->o21_anofim = ($this->o21_anofim == ""?@$GLOBALS["HTTP_POST_VARS"]["o21_anofim"]:$this->o21_anofim);
       $this->o21_descr = ($this->o21_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o21_descr"]:$this->o21_descr);
       $this->o21_numero = ($this->o21_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["o21_numero"]:$this->o21_numero);
     }else{
       $this->o21_codleippa = ($this->o21_codleippa == ""?@$GLOBALS["HTTP_POST_VARS"]["o21_codleippa"]:$this->o21_codleippa);
     }
   }
   // funcao para inclusao
   function incluir ($o21_codleippa){ 
      $this->atualizacampos();
     if($this->o21_anoini == null ){ 
       $this->erro_sql = " Campo Ano de inicío nao Informado.";
       $this->erro_campo = "o21_anoini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o21_anofim == null ){ 
       $this->erro_sql = " Campo Ano final nao Informado.";
       $this->erro_campo = "o21_anofim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o21_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "o21_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o21_numero == null ){ 
       $this->erro_sql = " Campo Numero da Lei nao Informado.";
       $this->erro_campo = "o21_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o21_codleippa == "" || $o21_codleippa == null ){
       $result = db_query("select nextval('orcppalei_o21_codleippa_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcppalei_o21_codleippa_seq do campo: o21_codleippa"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o21_codleippa = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcppalei_o21_codleippa_seq");
       if(($result != false) && (pg_result($result,0,0) < $o21_codleippa)){
         $this->erro_sql = " Campo o21_codleippa maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o21_codleippa = $o21_codleippa; 
       }
     }
     if(($this->o21_codleippa == null) || ($this->o21_codleippa == "") ){ 
       $this->erro_sql = " Campo o21_codleippa nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcppalei(
                                       o21_codleippa 
                                      ,o21_anoini 
                                      ,o21_anofim 
                                      ,o21_descr 
                                      ,o21_numero 
                       )
                values (
                                $this->o21_codleippa 
                               ,$this->o21_anoini 
                               ,$this->o21_anofim 
                               ,'$this->o21_descr' 
                               ,'$this->o21_numero' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lei PPA ($this->o21_codleippa) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lei PPA já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lei PPA ($this->o21_codleippa) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o21_codleippa;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o21_codleippa));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6468,'$this->o21_codleippa','I')");
       $resac = db_query("insert into db_acount values($acount,1063,6468,'','".AddSlashes(pg_result($resaco,0,'o21_codleippa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1063,6469,'','".AddSlashes(pg_result($resaco,0,'o21_anoini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1063,6470,'','".AddSlashes(pg_result($resaco,0,'o21_anofim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1063,6497,'','".AddSlashes(pg_result($resaco,0,'o21_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1063,8312,'','".AddSlashes(pg_result($resaco,0,'o21_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o21_codleippa=null) { 
      $this->atualizacampos();
     $sql = " update orcppalei set ";
     $virgula = "";
     if(trim($this->o21_codleippa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o21_codleippa"])){ 
       $sql  .= $virgula." o21_codleippa = $this->o21_codleippa ";
       $virgula = ",";
       if(trim($this->o21_codleippa) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "o21_codleippa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o21_anoini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o21_anoini"])){ 
       $sql  .= $virgula." o21_anoini = $this->o21_anoini ";
       $virgula = ",";
       if(trim($this->o21_anoini) == null ){ 
         $this->erro_sql = " Campo Ano de inicío nao Informado.";
         $this->erro_campo = "o21_anoini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o21_anofim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o21_anofim"])){ 
       $sql  .= $virgula." o21_anofim = $this->o21_anofim ";
       $virgula = ",";
       if(trim($this->o21_anofim) == null ){ 
         $this->erro_sql = " Campo Ano final nao Informado.";
         $this->erro_campo = "o21_anofim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o21_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o21_descr"])){ 
       $sql  .= $virgula." o21_descr = '$this->o21_descr' ";
       $virgula = ",";
       if(trim($this->o21_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "o21_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o21_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o21_numero"])){ 
       $sql  .= $virgula." o21_numero = '$this->o21_numero' ";
       $virgula = ",";
       if(trim($this->o21_numero) == null ){ 
         $this->erro_sql = " Campo Numero da Lei nao Informado.";
         $this->erro_campo = "o21_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o21_codleippa!=null){
       $sql .= " o21_codleippa = $this->o21_codleippa";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o21_codleippa));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6468,'$this->o21_codleippa','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o21_codleippa"]))
           $resac = db_query("insert into db_acount values($acount,1063,6468,'".AddSlashes(pg_result($resaco,$conresaco,'o21_codleippa'))."','$this->o21_codleippa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o21_anoini"]))
           $resac = db_query("insert into db_acount values($acount,1063,6469,'".AddSlashes(pg_result($resaco,$conresaco,'o21_anoini'))."','$this->o21_anoini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o21_anofim"]))
           $resac = db_query("insert into db_acount values($acount,1063,6470,'".AddSlashes(pg_result($resaco,$conresaco,'o21_anofim'))."','$this->o21_anofim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o21_descr"]))
           $resac = db_query("insert into db_acount values($acount,1063,6497,'".AddSlashes(pg_result($resaco,$conresaco,'o21_descr'))."','$this->o21_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o21_numero"]))
           $resac = db_query("insert into db_acount values($acount,1063,8312,'".AddSlashes(pg_result($resaco,$conresaco,'o21_numero'))."','$this->o21_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lei PPA nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o21_codleippa;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lei PPA nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o21_codleippa;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o21_codleippa;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o21_codleippa=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o21_codleippa));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6468,'$o21_codleippa','E')");
         $resac = db_query("insert into db_acount values($acount,1063,6468,'','".AddSlashes(pg_result($resaco,$iresaco,'o21_codleippa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1063,6469,'','".AddSlashes(pg_result($resaco,$iresaco,'o21_anoini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1063,6470,'','".AddSlashes(pg_result($resaco,$iresaco,'o21_anofim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1063,6497,'','".AddSlashes(pg_result($resaco,$iresaco,'o21_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1063,8312,'','".AddSlashes(pg_result($resaco,$iresaco,'o21_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcppalei
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o21_codleippa != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o21_codleippa = $o21_codleippa ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lei PPA nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o21_codleippa;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lei PPA nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o21_codleippa;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o21_codleippa;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcppalei";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $o21_codleippa=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcppalei ";
     $sql2 = "";
     if($dbwhere==""){
       if($o21_codleippa!=null ){
         $sql2 .= " where orcppalei.o21_codleippa = $o21_codleippa "; 
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
   function sql_query_file ( $o21_codleippa=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcppalei ";
     $sql2 = "";
     if($dbwhere==""){
       if($o21_codleippa!=null ){
         $sql2 .= " where orcppalei.o21_codleippa = $o21_codleippa "; 
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