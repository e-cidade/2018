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
//CLASSE DA ENTIDADE orclei
class cl_orclei { 
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
   var $o45_codlei = 0; 
   var $o45_numlei = null; 
   var $o45_descr = null; 
   var $o45_datafim_dia = null; 
   var $o45_datafim_mes = null; 
   var $o45_datafim_ano = null; 
   var $o45_datafim = null; 
   var $o45_dataini_dia = null; 
   var $o45_dataini_mes = null; 
   var $o45_dataini_ano = null; 
   var $o45_dataini = null; 
   var $o45_tipolei = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o45_codlei = int4 = Código Lei 
                 o45_numlei = varchar(30) = Número Lei 
                 o45_descr = text = Descrição 
                 o45_datafim = date = Data Final 
                 o45_dataini = date = Data Inicial 
                 o45_tipolei = int4 = Tipo da Lei 
                 ";
   //funcao construtor da classe 
   function cl_orclei() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orclei"); 
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
       $this->o45_codlei = ($this->o45_codlei == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_codlei"]:$this->o45_codlei);
       $this->o45_numlei = ($this->o45_numlei == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_numlei"]:$this->o45_numlei);
       $this->o45_descr = ($this->o45_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_descr"]:$this->o45_descr);
       if($this->o45_datafim == ""){
         $this->o45_datafim_dia = ($this->o45_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_datafim_dia"]:$this->o45_datafim_dia);
         $this->o45_datafim_mes = ($this->o45_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_datafim_mes"]:$this->o45_datafim_mes);
         $this->o45_datafim_ano = ($this->o45_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_datafim_ano"]:$this->o45_datafim_ano);
         if($this->o45_datafim_dia != ""){
            $this->o45_datafim = $this->o45_datafim_ano."-".$this->o45_datafim_mes."-".$this->o45_datafim_dia;
         }
       }
       if($this->o45_dataini == ""){
         $this->o45_dataini_dia = ($this->o45_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_dataini_dia"]:$this->o45_dataini_dia);
         $this->o45_dataini_mes = ($this->o45_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_dataini_mes"]:$this->o45_dataini_mes);
         $this->o45_dataini_ano = ($this->o45_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_dataini_ano"]:$this->o45_dataini_ano);
         if($this->o45_dataini_dia != ""){
            $this->o45_dataini = $this->o45_dataini_ano."-".$this->o45_dataini_mes."-".$this->o45_dataini_dia;
         }
       }
       $this->o45_tipolei = ($this->o45_tipolei == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_tipolei"]:$this->o45_tipolei);
     }else{
       $this->o45_codlei = ($this->o45_codlei == ""?@$GLOBALS["HTTP_POST_VARS"]["o45_codlei"]:$this->o45_codlei);
     }
   }
   // funcao para inclusao
   function incluir ($o45_codlei){ 
      $this->atualizacampos();
     if($this->o45_numlei == null ){ 
       $this->erro_sql = " Campo Número Lei nao Informado.";
       $this->erro_campo = "o45_numlei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o45_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "o45_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o45_datafim == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "o45_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o45_dataini == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "o45_dataini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o45_tipolei == null ){ 
       $this->o45_tipolei = "1";
     }
     if($o45_codlei == "" || $o45_codlei == null ){
       $result = db_query("select nextval('orclei_o45_codlei_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orclei_o45_codlei_seq do campo: o45_codlei"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o45_codlei = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orclei_o45_codlei_seq");
       if(($result != false) && (pg_result($result,0,0) < $o45_codlei)){
         $this->erro_sql = " Campo o45_codlei maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o45_codlei = $o45_codlei; 
       }
     }
     if(($this->o45_codlei == null) || ($this->o45_codlei == "") ){ 
       $this->erro_sql = " Campo o45_codlei nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orclei(
                                       o45_codlei 
                                      ,o45_numlei 
                                      ,o45_descr 
                                      ,o45_datafim 
                                      ,o45_dataini 
                                      ,o45_tipolei 
                       )
                values (
                                $this->o45_codlei 
                               ,'$this->o45_numlei' 
                               ,'$this->o45_descr' 
                               ,".($this->o45_datafim == "null" || $this->o45_datafim == ""?"null":"'".$this->o45_datafim."'")." 
                               ,".($this->o45_dataini == "null" || $this->o45_dataini == ""?"null":"'".$this->o45_dataini."'")." 
                               ,$this->o45_tipolei 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro das leis do orçamento ($this->o45_codlei) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro das leis do orçamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro das leis do orçamento ($this->o45_codlei) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o45_codlei;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o45_codlei));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5318,'$this->o45_codlei','I')");
       $resac = db_query("insert into db_acount values($acount,770,5318,'','".AddSlashes(pg_result($resaco,0,'o45_codlei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,770,5314,'','".AddSlashes(pg_result($resaco,0,'o45_numlei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,770,5315,'','".AddSlashes(pg_result($resaco,0,'o45_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,770,5317,'','".AddSlashes(pg_result($resaco,0,'o45_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,770,5316,'','".AddSlashes(pg_result($resaco,0,'o45_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,770,17704,'','".AddSlashes(pg_result($resaco,0,'o45_tipolei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o45_codlei=null) { 
      $this->atualizacampos();
     $sql = " update orclei set ";
     $virgula = "";
     if(trim($this->o45_codlei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o45_codlei"])){ 
       $sql  .= $virgula." o45_codlei = $this->o45_codlei ";
       $virgula = ",";
       if(trim($this->o45_codlei) == null ){ 
         $this->erro_sql = " Campo Código Lei nao Informado.";
         $this->erro_campo = "o45_codlei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o45_numlei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o45_numlei"])){ 
       $sql  .= $virgula." o45_numlei = '$this->o45_numlei' ";
       $virgula = ",";
       if(trim($this->o45_numlei) == null ){ 
         $this->erro_sql = " Campo Número Lei nao Informado.";
         $this->erro_campo = "o45_numlei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o45_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o45_descr"])){ 
       $sql  .= $virgula." o45_descr = '$this->o45_descr' ";
       $virgula = ",";
       if(trim($this->o45_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "o45_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o45_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o45_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o45_datafim_dia"] !="") ){ 
       $sql  .= $virgula." o45_datafim = '$this->o45_datafim' ";
       $virgula = ",";
       if(trim($this->o45_datafim) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "o45_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o45_datafim_dia"])){ 
         $sql  .= $virgula." o45_datafim = null ";
         $virgula = ",";
         if(trim($this->o45_datafim) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "o45_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->o45_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o45_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o45_dataini_dia"] !="") ){ 
       $sql  .= $virgula." o45_dataini = '$this->o45_dataini' ";
       $virgula = ",";
       if(trim($this->o45_dataini) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "o45_dataini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o45_dataini_dia"])){ 
         $sql  .= $virgula." o45_dataini = null ";
         $virgula = ",";
         if(trim($this->o45_dataini) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "o45_dataini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->o45_tipolei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o45_tipolei"])){ 
        if(trim($this->o45_tipolei)=="" && isset($GLOBALS["HTTP_POST_VARS"]["o45_tipolei"])){ 
           $this->o45_tipolei = "0" ; 
        } 
       $sql  .= $virgula." o45_tipolei = $this->o45_tipolei ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o45_codlei!=null){
       $sql .= " o45_codlei = $this->o45_codlei";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o45_codlei));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5318,'$this->o45_codlei','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o45_codlei"]) || $this->o45_codlei != "")
           $resac = db_query("insert into db_acount values($acount,770,5318,'".AddSlashes(pg_result($resaco,$conresaco,'o45_codlei'))."','$this->o45_codlei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o45_numlei"]) || $this->o45_numlei != "")
           $resac = db_query("insert into db_acount values($acount,770,5314,'".AddSlashes(pg_result($resaco,$conresaco,'o45_numlei'))."','$this->o45_numlei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o45_descr"]) || $this->o45_descr != "")
           $resac = db_query("insert into db_acount values($acount,770,5315,'".AddSlashes(pg_result($resaco,$conresaco,'o45_descr'))."','$this->o45_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o45_datafim"]) || $this->o45_datafim != "")
           $resac = db_query("insert into db_acount values($acount,770,5317,'".AddSlashes(pg_result($resaco,$conresaco,'o45_datafim'))."','$this->o45_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o45_dataini"]) || $this->o45_dataini != "")
           $resac = db_query("insert into db_acount values($acount,770,5316,'".AddSlashes(pg_result($resaco,$conresaco,'o45_dataini'))."','$this->o45_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o45_tipolei"]) || $this->o45_tipolei != "")
           $resac = db_query("insert into db_acount values($acount,770,17704,'".AddSlashes(pg_result($resaco,$conresaco,'o45_tipolei'))."','$this->o45_tipolei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro das leis do orçamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o45_codlei;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das leis do orçamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o45_codlei;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o45_codlei;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o45_codlei=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o45_codlei));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5318,'$o45_codlei','E')");
         $resac = db_query("insert into db_acount values($acount,770,5318,'','".AddSlashes(pg_result($resaco,$iresaco,'o45_codlei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,770,5314,'','".AddSlashes(pg_result($resaco,$iresaco,'o45_numlei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,770,5315,'','".AddSlashes(pg_result($resaco,$iresaco,'o45_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,770,5317,'','".AddSlashes(pg_result($resaco,$iresaco,'o45_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,770,5316,'','".AddSlashes(pg_result($resaco,$iresaco,'o45_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,770,17704,'','".AddSlashes(pg_result($resaco,$iresaco,'o45_tipolei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orclei
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o45_codlei != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o45_codlei = $o45_codlei ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro das leis do orçamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o45_codlei;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro das leis do orçamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o45_codlei;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o45_codlei;
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
        $this->erro_sql   = "Record Vazio na Tabela:orclei";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o45_codlei=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orclei ";
     $sql2 = "";
     if($dbwhere==""){
       if($o45_codlei!=null ){
         $sql2 .= " where orclei.o45_codlei = $o45_codlei "; 
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
   function sql_query_file ( $o45_codlei=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orclei ";
     $sql2 = "";
     if($dbwhere==""){
       if($o45_codlei!=null ){
         $sql2 .= " where orclei.o45_codlei = $o45_codlei "; 
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